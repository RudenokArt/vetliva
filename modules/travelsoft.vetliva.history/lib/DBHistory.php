<?php

namespace travelsoft\vetliva;

use Bitrix\Main\Config\Option;

/**
 * Класс для работы с базой данных истории
 *
 * @author dimabresky
 */
class DBHistory {

    /**
     * @var array
     */
    protected $_objects = null;

    /**
     * @var array
     */
    protected $_actions = null;

    /**
     * @var \Bitrix\Main\DB\MysqliConnection
     */
    protected $_connection = null;

    /**
     * @var \Bitrix\Main\DB\MysqliSqlHelper
     */
    public $sql_helper = null;
    protected static $_instance = null;

    public static function getInstance() {

        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __clone() {
        
    }

    private function __construct() {

        $this->_objects = @include __DIR__ . '/../history_objects.php';
        $this->_actions = @include __DIR__ . '/../history_actions.php';

        $this->_connection = new \Bitrix\Main\DB\MysqliConnection([
            "host" => Option::get("travelsoft.vetliva.history", "DB_SERVER_NAME"),
            "database" => Option::get("travelsoft.vetliva.history", "DB_NAME"),
            "login" => Option::get("travelsoft.vetliva.history", "DB_LOGIN"),
            "password" => Option::get("travelsoft.vetliva.history", "DB_PASSWORD"),
        ]);

        $this->_connection->connect();

        if (!($this->_connection instanceof \Bitrix\Main\DB\MysqliConnection)) {
            throw new \Exception("DBHistory: connection db error");
        }

        $this->sql_helper = new \Bitrix\Main\DB\MysqliSqlHelper($this->_connection);
    }

    /**
     * Выполняет запрос к бд
     * @param string $sql
     * @param int $offset
     * @param int $limit
     * @return \Bitrix\Main\DB\MysqliResult
     */
    public function query(string $sql, int $offset = 0, int $limit = null) {
        return $this->_connection->query($sql, [], $offset, $limit);
    }

    /**
     * Сохраняет данные в истории
     * @param array $parameters
     * @retrun int
     */
    public function save(array $parameters) {

        if (!in_array($parameters["UF_OBJECT"], $this->_objects)) {
            throw new \Exception("Для сохранения записи в историю следует указать верный тип объекта истории");
        }

        if (!in_array($parameters["UF_ACTION"], $this->_actions)) {
            throw new \Exception("Для сохранения записи в историю следует указать верный тип события истории");
        }

        if ($parameters["UF_ACTION"] === "VIEW_PAGE" && $parameters["UF_OBJECT"] === "PAGE") {
            return $this->incrementPageView($parameters["UF_PAGE_ID"]);
        } else {
            return $this->addToArchive($parameters);
        }
    }

    /**
     * Увеличивает счетчик просмотра страницы
     * @param int $page_id
     * @retrun int
     */
    public function incrementPageView(int $page_id) {

        $res = $this->query("select `ID`,`UF_COUNTER` from `view_counter` where `UF_PAGE_ID`='{$page_id}' and `UF_DATE`='" . date('Y-m-d') . "'")->fetch();
        if (isset($res["ID"])) {

            $counter = $res["UF_COUNTER"] + 1;
            $this->query("update `view_counter` set `UF_COUNTER`='{$counter}' where `UF_PAGE_ID`='{$page_id}' and `UF_DATE`='" . date('Y-m-d') . "'");
            return $res["ID"];
        } else {

            $this->query("insert into `view_counter` (`UF_PAGE_ID`, `UF_DATE`, `UF_COUNTER`) values ('{$page_id}', '" . date('Y-m-d') . "', '1')");
            return @intval($this->_connection->getResource()->insert_id);
        }
    }

    /**
     * Добавляет запись в архив истории
     * @param array $parameters
     * @return int
     */
    public function addToArchive(array $parameters) {
        
        global $USER;
        $parameters["UF_DATE"] = date("Y-m-d H:i:s");
        $parameters["UF_USER_ID"] = $USER->GetID() ?: 0;
        $parameters["UF_IP"] = $_SERVER["REMOTE_ADDR"];
        $this->query("insert into `archive` {$this->_getInsertColumnsAndValues($parameters)}");
        return @intval($this->_connection->getResource()->insert_id);
    }

    /**
     * @param array $parameters
     * @return \Bitrix\Main\DB\MysqliResult
     */
    public function getPageViews(array $parameters = []) {

        return $this->query($this->_getSqlSelectString("view_counter", $parameters));
    }

    /**
     * @param array $parameters
     * @return \Bitrix\Main\DB\MysqliResult
     */
    public function getArchive(array $parameters = []) {
        
        return $this->query($this->_getSqlSelectString("archive", $parameters));
    }

    /**
     * Очистка просмотров страниц
     * @param array $where
     */
    public function clearPageViews(array $where = []) {

        $this->query("delete from `view_counter` {$this->_getWhere($where)}");
    }

    /**
     * Очитска архива истории
     * @param array $where
     */
    public function clearArchive(array $where = []) {

        $this->query("delete from `archive` {$this->_getWhere($where)}");
    }

    /**
     * @param array $where
     * @return string
     */
    protected function _getWhere($where = []) {
        $sql_where = "";
        if (!empty($where) && is_array($where)) {
            $arr_where = [];
            foreach ($where as $key => $val) {
                                
                if (is_array($val)) {

                    if (strpos($key, "><") !== false) {
                        
                        $key = substr($key, 2);
                        
                        $arr_where[] = "`" . $this->sql_helper->forSql($this->_normalizeKey($key)) . "`>='" . $this->sql_helper->forSql($val[0]) . "'";
                        $arr_where[] = "`" . $this->sql_helper->forSql($this->_normalizeKey($key)) . "`<='" . $this->sql_helper->forSql($val[1]) . "'";
                        
                    } else {

                        $arr_or = [];
                        foreach ($val as $v) {
                            $arr_or[] = "`" . $this->sql_helper->forSql($this->_normalizeKey($key)) . "`{$this->_getOperation($key)}'" . $this->sql_helper->forSql($v) . "'";
                        }

                        $arr_where[] = "(" . implode(" or ", $arr_or) . ")";
                    }
                } else {
                    
                    $arr_where[] = "`" . $this->sql_helper->forSql($this->_normalizeKey($key)) . "`{$this->_getOperation($key)}'" . $this->sql_helper->forSql($val) . "'";
                }
            }

            if (!empty($arr_where)) {
                $sql_where = "where " . implode(" and ", $arr_where);
            }
        }
        return $sql_where;
    }

    /**
     * @param array $parameters
     * @return string
     */
    protected function _getInsertColumnsAndValues(array $parameters) {

        $sql_insert_values = "";
        if (!empty($parameters)) {
            
            $arr_columns = [];
            $arr_values = [];
            foreach ($parameters as $key => $val) {

                if (is_array($val)) {
                    continue;
                }

                $arr_columns[] = "`" . $this->sql_helper->forSql($key) . "`";
                $arr_values[] = "'" . $this->sql_helper->forSql($val) . "'";
            }

            $sql_insert_values = "(" . implode(",", $arr_columns) . ") values (" . implode(",", $arr_values) . ")";
        }
        
        return $sql_insert_values;
    }

    /**
     * @param array $select
     * @return string
     */
    protected function _getSelect($select = []) {

        $sql_select = "*";

        if (!empty($select) && is_array($select)) {

            $arr_columns = [];

            foreach ($select as $column) {
                if (is_array($column)) {
                    continue;
                }
                $arr_columns[] = "`" . $this->sql_helper->forSql($column) . "`";
            }

            if (!empty($arr_columns)) {
                $sql_select = implode(",", $arr_columns);
            }
        }

        return $sql_select;
    }

    /**
     * @param array $order
     * @return string
     */
    protected function _getOrder(array $order = []) {

        $sql_order = [];
        if (!empty($order) && is_array($order)) {
            $arr_order_str = [];

            foreach ($order as $column => $ord) {
                if (is_array($ord)) {
                    continue;
                }
                $arr_order_str[] = "`" . $this->sql_helper->forSql($column) . "` " . (strtolower($ord) === "desc" ?: "asc");
            }

            if (!empty($arr_order_str)) {
                $sql_order = "order by " . implode(",", $arr_order_str);
            }
        }
        return $sql_order;
    }

    /**
     * @param int $limit
     * @return string
     */
    protected function _getLimit($limit = 0) {

        $sql_limit = "";
        if ($limit > 0) {
            $sql_limit = "limit {$limit}";
        }
        return $sql_limit;
    }

    /**
     * @param int $offset
     * @return string
     */
    protected function _getOffset($offset = 0) {

        $sql_offset = "";
        if ($offset > 0) {
            $sql_offset = "offset {$offset}";
        }
        return $sql_offset;
    }

    /**
     * @param string $table_name
     * @param array $parameters
     * @return string
     */
    protected function _getSqlSelectString(string $table_name, array $parameters = []) {

        return "select {$this->_getSelect(@$parameters["select"])} from {$table_name} {$this->_getWhere(@$parameters["where"])} "
                . "{$this->_getLimit(@$parameters["limit"])} {$this->_getOffset(@$parameters["limit"])}";
    }
    
    /**
     * @param string $key
     * @return string
     */
    protected function _getOperation ($key) {
        
        $operation = "=";
        if (strpos($key, "!=") !== false) {
            $operation = "!=";
        } elseif (strpos($key, "<=") !== false) {
            $operation = "<=";
        } elseif (strpos($key, ">=") !== false) {
            $operation = ">=";
        } elseif (strpos($key, ">") !== false) {
            $operation = ">";
        } elseif (strpos($key, "<") !== false) {
            $operation = "<";
        } 
        
        return $operation;
    }
    
    /**
     * @param string $key
     * @return string
     */
    protected function _normalizeKey ($key) {
        if (strpos($key, "!=") !== false) {
            $key = substr($key, 2);
        } elseif (strpos($key, "<=") !== false) {
            $key = substr($key, 2);
        } elseif (strpos($key, ">=") !== false) {
            $key = substr($key, 2);
        } elseif (strpos($key, ">") !== false) {
            $key = substr($key, 1);
        } elseif (strpos($key, "<") !== false) {
            $key = substr($key, 1);
        } 
        
        return $key;
    }
    
}
