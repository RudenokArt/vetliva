<?php

namespace travelsoft\favorites\adapters;
use Bitrix\Highloadblock\HighloadBlockTable as HL;

\Bitrix\Main\Loader::includeModule("highloadblock");

/**
 * Класс адаптер для bitrix highloadblock
 *
 * @author dimabresky
 * @copyright (c) 2019, travelsoft
 */
abstract class Highloadblock {

    /**
     * Возвращает полученные данные из хранилища в виде массива
     * @param array $query
     * @param callable $callback
     * @return array
     */
    public function get(array $query = array(), bool $likeArray = true, callable $callback = null) {

        $table = $this->getTable();
        $dbList = $table::getList((array) $query);

        if (!$likeArray) {

            return $dbList;
        }

        $result = array();
        if ($callback) {
            while ($res = $dbList->fetch()) {
                $callback($res);
                $result[$res["ID"]] = $res;
            }
        } else {
            while ($res = $dbList->fetch()) {
                $result[$res["ID"]] = $res;
            }
        }

        return (array) $result;
    }

    /**
     * Обновление записи по id
     * @param int $id
     * @param array $arUpdate
     * @return boolean
     */
    public function update(int $id, array $arUpdate): bool {

        $table = $this->getTable();
        $result = boolval($table::update($id, $arUpdate));

        return $result;
    }

    /**
     * Добавляет запись в хранилище
     * @param array $arSave
     * @return int
     */
    public function add(array $arSave): int {

        $table = $this->getTable();

        $result = (int) $table::add($arSave)->getId();

        return $result;
    }

    /**
     * 
     * @param int $id
     */
    public function delete(int $id): bool {

        $table = $this->getTable();
        $result = boolval($table::delete($id));

        return $result;
    }

    /**
     * Возвращает поля записи таблицы по id
     * @param int $id
     * @param array $select
     * @return array
     */
    public function getById(int $id, array $select = array()): array {

        $query = array("filter" => array("ID" => $id));
        if (!empty($select)) {
            $query["select"] = $select;
        }
        $result = current($this->get($query));
        if (is_array($result) && !empty($result)) {

            return $result;
        } else {

            return array();
        }
    }

    /**
     * @return array
     */
    public function getLast() {
        $result = current($this->getById($this->getLastId()));
        if (is_array($result) && !empty($result)) {

            return $result;
        } else {

            return [];
        }
    }

    /**
     * @return int
     */
    public function getLastId() {
        $result = $this->get(array('select' => array(new \Bitrix\Main\Entity\ExpressionField('MAX_ID', 'max(ID)'))), false)->fetch();
        return intVal($result['MAX_ID']);
    }

    /**
     * @return string
     */
    protected function getTable(): string {

        return HL::compileEntity(HL::getById($this->getTableId())->fetch())->getDataClass();
    }

    /**
     * @return int
     */
    protected function getTableId(): int {
       
        return intval($this->getStoreId());
    }
    
    /**
     * @param int $store_id
     * @return \travelsoft\favorites\adapters\#anon#Highloadblock_php#1
     */
    public static function createTable (int $store_id) {
        
        return new class($store_id) extends \travelsoft\favorites\adapters\Highloadblock {
            
            protected $_store_id = null;
            
            public function __construct(int $store_id) {
            
                $this->_store_id = $store_id;
            }
            
            public function getStoreId () {
                return $this->_store_id;
            }
        };
    }

}
