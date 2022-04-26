<?php

namespace travelsoft\favorites;

/**
 * Favorites utils
 * @author dimaberesky
 * @copyright (c) 2019, travelsoft
 */
class Utils {

    /**
     * @return array
     */
    public static function getObjectTypes(): array {
        return [
            "IBLOCK_ELEMENT" => "элемент инфоблока",
            "HIGHLOADBLOCK_ELEMENT" => "элемент highloadblock"
        ];
    }

    /**
     * @param string $type
     * @return bool
     */
    public static function checkObjectType(string $type = null): bool {

        return isset(self::getObjectTypes()[$type]);
    }
    
    /**
     * @staticvar travelsoft\favorites\adapters\Highloadblock $table
     * @return travelsoft\favorites\adapters\Highloadblock
     */
    public static function getTable() {
        
        static $table = null;
        
        if (!$table) {
            $table = adapters\Highloadblock::createTable(\Bitrix\Main\Config\Option::get("travelsoft.favorites", "FAVORITES_STORAGE_ID"));
        }
        
        return $table;
    }
    
    /**
     * @param string $type
     * @param int $id
     * @param int $store_id
     * @return string
     */
    public static function createHash(string $type, int $id, int $store_id):string {
        return md5("travelsoft_favorites_$type_$id_$store_id");
    }
    
    /**
     * @param string $hash
     * @param string $type
     * @param int $id
     * @param int $store_id
     * @return bool
     */
    public static function checkHash (string $hash, string $type, int $id, int $store_id):bool {
        return md5("travelsoft_favorites_$type_$id_$store_id") === $hash;
    }
    
    /**
     * Отправка json-строки
     * @global \CMain $APPLICATION
     * @param string $body
     */
    public static function sendJsonResponse(string $body) {
        global $APPLICATION;
        \header('Content-Type: application/json; charset=' . \SITE_CHARSET);
        $APPLICATION->RestartBuffer();
        echo $body;
        die();
    }
}
