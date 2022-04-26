<?php

namespace travelsoft\favorites\adapters;


\Bitrix\Main\Loader::includeModule("iblock");

/**
 * Класс адаптер для bitrix iblock
 *
 * @author dimabresky
 * @copyright (c) 2019, travelsoft
 */
abstract class Iblock {

    /**
     * Возвращает полученные данные из хранилища в виде массива
     * @param array $query
     * @param callable $callback
     * @return array
     */
    public function get(array $query = array(), bool $likeArray = true,callable $callback = null) {

        if ($query['filter']) {

            $arFilter = $query["filter"];
        }
        
        $arFilter["IBLOCK_ID"] = $this->getStoreId();

        if ($query['order']) {

            $arOrder = $query['order'];
        }

        if ($query['select']) {

            $arSelect = $query['select'];
        }

        if ($query['nav']) {

            $arNav = $query['nav'];
        }

        $dbList = \CIBlockElement::GetList($arOrder, $arFilter, null, $arNav, $arSelect);
        
        if (!$likeArray) {
            
            return $dbList;
        }
        
        $result = array();
        if ($callback) {

            while ($dbElement = $dbList->GetNextElement()) {

                $arFields = $dbElement->GetFields();
                if ($arFields["ID"] > 0) {

                    $arProperties = $dbElement->GetProperties();
                    $callback($arFields, $arProperties);
                    $result[$arFields["ID"]] = $arFields;
                    $result[$arFields["ID"]]["PROPERTIES"] = $arProperties;
                }
            }
        } else {

            while ($dbElement = $dbList->GetNextElement()) {

                $arFields = $dbElement->GetFields();
                if ($arFields["ID"] > 0) {

                    $arProperties = $dbElement->GetProperties();
                    $result[$arFields["ID"]] = $arFields;
                    $result[$arFields["ID"]]["PROPERTIES"] = $arProperties;
                }
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

        $ob = new \CIBlockElement;
        return boolval($ob->Update($id, $arUpdate));
    }

    /**
     * Добавляет запись в хранилище
     * @param array $arSave
     * @return int
     */
    public function add(array $arSave): int {

        $ob = new \CIBlockElement;
        $arSave['IBLOCK_ID'] = $this->getStoreId();
        return (int) $ob->Add($arSave);
    }

    /**
     * Удаляет запись из хранилища
     * @param int $id
     */
    public function delete(int $id): bool {

        $ob = new \CIBlockElement;
        return boolval($ob->Delete($id));
    }
    
    /**
     * Название по id
     * @param int $id
     * @return string
     */
    public function nameById (int $id) : string {
        
        static $names = [];
        
        if (!isset($names[$id])) {
            $names[$id] = (string)current($this->get(array("filter" => array("ID" => $id), "select" => array("ID", "NAME"))))["NAME"];
        }
        
        return $names[$id];
        
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
     * @return int
     */
    public function getStoreId(): int {

        return intval($this->store_id);
    }
    
    /**
     * @param int $store_id
     * @return \travelsoft\booking\adapters\#anon#Iblock_php#1
     */
    public static function createTable (int $store_id) {
        
        return new class($store_id) extends \travelsoft\favorites\adapters\Iblock {
            
            protected $store_id = null;
            
            public function __construct(int $store_id) {
            
                $this->store_id = $store_id;
            }
        };
        
    }
}
