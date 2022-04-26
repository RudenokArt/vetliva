<?php

require_once "cronHeader.php";

\Bitrix\Main\Loader::includeModule("travelsoft.history");

travelsoft\History::getInstance()->clear(array(
    "filter" => array("<=UF_DATE" => time()-(86400*180), "!=UF_ACTION" => "VIEW_PAGE"),
    "limit" => 100
));