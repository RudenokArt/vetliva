<?php
@include_once 'functions.php';
use Bitrix\Main\Config\Option;
#сохраняем id инфоблоков для истории
if (!empty($_REQUEST["settings"]["follow_by_iblocks"]) && is_array($_REQUEST["settings"]["follow_by_iblocks"]) 
        && !in_array("nofollow", $_REQUEST["settings"]["follow_by_iblocks"])) {
        Option::set("travelsoft.vetliva.history", "follow_by_iblocks", implode(";", $_REQUEST["settings"]["follow_by_iblocks"]));
        travelsoft\vetliva\ReinstallIBlockModuleDependences();
} else {
    Option::set("travelsoft.vetliva.history", "follow_by_iblocks", "");
    travelsoft\vetliva\UnRegisterIBlocksEventsHandlers();
}

#сохраняем id highloadblock для истории
if (!empty($_REQUEST["settings"]["follow_by_highloadblocks"]) && is_array($_REQUEST["settings"]["follow_by_highloadblocks"]) &&
        !in_array("nofollow", $_REQUEST["settings"]["follow_by_highloadblocks"])) {
    Option::set("travelsoft.vetliva.history", "follow_by_highloadblocks", implode(";", $_REQUEST["settings"]["follow_by_highloadblocks"]));
    travelsoft\vetliva\ReinstallHighloadblocksModuleDependences();
} else {
    travelsoft\vetliva\UnRegisterHighloadblocksEventsHandlers();
    Option::set("travelsoft.vetliva.history", "follow_by_highloadblocks", "");
}

#сохраняем id групп пользователей для истории
if ($_REQUEST["settings"]["follow_by_users"] == "Y") {
    Option::set("travelsoft.vetliva.history", "follow_by_users", "Y");
    travelsoft\vetliva\ReinstallUsersModuleDependences();
} else {
    Option::set("travelsoft.vetliva.history", "follow_by_users", "");
    travelsoft\vetliva\UnRegisterUsersEventsHandlers();
}