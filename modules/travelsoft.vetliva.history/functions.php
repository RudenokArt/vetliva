<?php

namespace travelsoft\vetliva;

use Bitrix\Main\Config\Option;
\Bitrix\Main\Loader::includeModule("highloadblock");

if (!function_exists("travelsoft\\vetliva\\doHLEvHandlersRegUnReg")) {
    function doHLEvHandlersRegUnReg (string $function) {
        global $DB;
        if ($function === "UnRegisterModuleDependences") {
            $DB->query("delete from b_module_to_module where `TO_CLASS`='TravelsoftVetlivaHistoryEventsHandlers' and (`TO_METHOD`='onAfterHighloadElementAdd' or `TO_METHOD`='onBeforeHighloadElementUpdate' or `TO_METHOD`='onAfterHighloadElementUpdate' or `TO_METHOD`='onBeforeHighloadElementDelete' or `TO_METHOD`='onAfterHighloadElementDelete')");
        } else {
            if ( ($HL_ID = explode(";", Option::get("travelsoft.vetliva.history", "follow_by_highloadblocks"))) ) {
                foreach ($HL_ID as $ID) {
                    $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($ID)->fetch();
                    if ($hlblock["NAME"]) {
                        RegisterModuleDependences("", $hlblock["NAME"] . "OnAfterAdd", "travelsoft.vetliva.history", "TravelsoftVetlivaHistoryEventsHandlers", "onAfterHighloadElementAdd", 100, null, array($ID));
                        RegisterModuleDependences("", $hlblock["NAME"] . "OnAfterUpdate", "travelsoft.vetliva.history", "TravelsoftVetlivaHistoryEventsHandlers", "onAfterHighloadElementUpdate", 100, null, array($ID));
                        RegisterModuleDependences("", $hlblock["NAME"] . "OnBeforeUpdate", "travelsoft.vetliva.history", "TravelsoftVetlivaHistoryEventsHandlers", "onBeforeHighloadElementUpdate", 100, null, array($ID));
                        RegisterModuleDependences("", $hlblock["NAME"] . "OnBeforeDelete", "travelsoft.vetliva.history", "TravelsoftVetlivaHistoryEventsHandlers", "onBeforeHighloadElementDelete", 100, null, array($ID));
                        RegisterModuleDependences("", $hlblock["NAME"] . "OnAfterDelete", "travelsoft.vetliva.history", "TravelsoftVetlivaHistoryEventsHandlers", "onAfterHighloadElementDelete", 100, null, array($ID));
                    }
                }
            }
        }
    }  
}

if (!function_exists("travelsoft\\vetliva\\doIBlockEvHandlersRegUnReg")) {

    function doIBlockEvHandlersRegUnReg (string $function) {
        $function("iblock", "OnAfterIBlockElementAdd", "travelsoft.vetliva.history", "TravelsoftVetlivaHistoryEventsHandlers", "onAfterIBlockElementAdd");
        $function("iblock", "OnAfterIBlockElementDelete", "travelsoft.vetliva.history", "TravelsoftVetlivaHistoryEventsHandlers", "onAfterIBlockElementDelete");
        $function("iblock", "OnAfterIBlockElementUpdate", "travelsoft.vetliva.history", "TravelsoftVetlivaHistoryEventsHandlers", "onAfterIBlockElementUpdate");
    }
    
}

if (!function_exists("travelsoft\\vetliva\\doUsersEvHandlersRegUnReg")) {
    function doUsersEvHandlersRegUnReg (string $function) {
        $function("main", "OnBeforeUserDelete", "travelsoft.vetliva.history", "TravelsoftVetlivaHistoryEventsHandlers", "onBeforeUserDelete");
        $function("main", "OnAfterUserDelete", "travelsoft.vetliva.history", "TravelsoftVetlivaHistoryEventsHandlers", "onAfterUserDelete");
        $function("main", "OnAfterUserUpdate", "travelsoft.vetliva.history", "TravelsoftVetlivaHistoryEventsHandlers", "onAfterUserUpdate");
        $function("main", "OnAfterUserAdd", "travelsoft.vetliva.history", "TravelsoftVetlivaHistoryEventsHandlers", "onAfterUserAdd");
    }
}

if (!function_exists("travelsoft\\vetliva\\RegisterHighloadblocksEventsHandlers")) {
    function RegisterHighloadblocksEventsHandlers () {
        doHLEvHandlersRegUnReg("RegisterModuleDependences");
    }
}

if (!function_exists("travelsoft\\vetliva\\UnRegisterHighloadblocksEventsHandlers")) {
    function UnRegisterHighloadblocksEventsHandlers () {
        doHLEvHandlersRegUnReg("UnRegisterModuleDependences");
    }
}

if (!function_exists("travelsoft\\vetliva\\RegisterIBlocksEventsHandlers")) {
    function RegisterIBlocksEventsHandlers () {
        doIBlockEvHandlersRegUnReg("RegisterModuleDependences");
    }
}

if (!function_exists("travelsoft\\vetliva\\UnRegisterIBlocksEventsHandlers")) {
    function UnRegisterIBlocksEventsHandlers () {
        doIBlockEvHandlersRegUnReg("UnRegisterModuleDependences");
    }
}

if (!function_exists("travelsoft\\vetliva\\RegisterUsersEventsHandlers")) {
    function RegisterUsersEventsHandlers () {
        doUsersEvHandlersRegUnReg("RegisterModuleDependences");
    }
}

if (!function_exists("travelsoft\\vetliva\\UnRegisterUsersEventsHandlers")) {
    function UnRegisterUsersEventsHandlers () {
        doUsersEvHandlersRegUnReg("UnRegisterModuleDependences");
    }
}

if (!function_exists("travelsoft\\vetliva\\ReinstallIBlockModuleDependences")) {
    function ReinstallIBlockModuleDependences () {

        UnRegisterIBlocksEventsHandlers();
        RegisterIBlocksEventsHandlers();

    }
}

if (!function_exists("travelsoft\\vetliva\\ReinstallHighloadblocksModuleDependences")) {
    function ReinstallHighloadblocksModuleDependences () {

        UnRegisterHighloadblocksEventsHandlers();
        RegisterHighloadblocksEventsHandlers();

    }
}

if (!function_exists("travelsoft\\vetliva\\ReinstallUsersModuleDependences")) {
    function ReinstallUsersModuleDependences () {

        UnRegisterUsersEventsHandlers();
        RegisterUsersEventsHandlers();

    }
}

if (!function_exists("travelsoft\\vetliva\\unRegisterAllModuleDependences")) {
    function unRegisterAllModuleDependences () {
        UnRegisterIBlocksEventsHandlers();
        UnRegisterUsersEventsHandlers();
        UnRegisterHighloadblocksEventsHandlers();
    }
}

if (!function_exists("travelsoft\\vetliva\\unsetFollowOptions")) {
    function unsetFollowOptions () {
        Option::delete("travelsoft.vetliva.history",  array('name' => 'follow_by_iblocks'));
        Option::delete("travelsoft.vetliva.history",  array('name' => 'follow_by_highloadblocks'));
        Option::delete("travelsoft.vetliva.history",  array('name' => 'follow_by_users'));
    }
}

if (!function_exists("travelsoft\\vetliva\\setSettingsOptions")) {
    function setSettingsOptions () {
        
    }
}

if (!function_exists("travelsoft\\vetliva\\unsetModuleOptions")) {
    function unsetModuleOptions () {
        Option::delete("travelsoft.vetliva.history",  array('name' => 'DB_SERVER_NAME'));
        Option::delete("travelsoft.vetliva.history",  array('name' => 'DB_NAME'));
        Option::delete("travelsoft.vetliva.history",  array('name' => 'DB_LOGIN'));
        Option::delete("travelsoft.vetliva.history",  array('name' => 'DB_PASSWORD'));
        Option::delete("travelsoft.vetliva.history",  array('name' => 'SAVE_PER_DAYS'));
        Option::delete("travelsoft.vetliva.history",  array('name' => 'YANDEX_CLIENT_ID'));
        Option::delete("travelsoft.vetliva.history",  array('name' => 'YANDEX_ACCESS_TOKEN'));
        Option::delete("travelsoft.vetliva.history",  array('name' => 'YANDEX_COUNTER_ID'));
        unsetFollowOptions ();
    }
}

if (!function_exists("travelsoft\\vetliva\\ats")) {
    function ats ($arFields) {
        
        return base64_encode(serialize($arFields));
    }
}

if (!function_exists("travelsoft\\vetliva\\sta")) {
    function sta (string $str) {
        return unserialize(base64_decode($str));
    }
}

if (!function_exists("travelsoft\\vetliva\\getHLDataClass")) {
    function getHLDataClass (int $ID) {
        $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity(\Bitrix\Highloadblock\HighloadBlockTable::getById($ID)->fetch());
        return $entity->getDataClass();
    }
}

if (!function_exists("travelsoft\\vetliva\\tryGetServiceId")) {
    function tryGetServiceId (int $id = 0, int $store_id = 0) {
        
        $class = getHLDataClass($store_id);
        if ($class) {
            $row = $class::getList(["filter" => ["ID" => $id]])->fetch();
            return $row["UF_SERVICE_ID"] ?: 0;
        }
        
        
        return 0;
    }
}


