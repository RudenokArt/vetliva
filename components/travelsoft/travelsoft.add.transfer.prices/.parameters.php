<?php

\Bitrix\Main\Loader::includeModule("iblock");
\Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools");

$ibTable = new CIBlock;

$dbRes = $ibTable->GetList(array("NAME" => "ASC"), array("ACTIVE" => "Y"));

while ($arRes = $dbRes->Fetch()) {
    $arIblocks[$arRes['ID']] = $arRes['NAME'];
}

$arComponentParameters["PARAMETERS"]["ROW_ID"] = array(
    "PARENT" => "BASE",
    "NAME" => "ID трансфера",
    "DEFAULT" => '={$_REQUEST["row_id"]}'
);

$arComponentParameters["PARAMETERS"]["SERVICE_TYPE"] = array(
    "PARENT" => "BASE",
    "TYPE" => "LIST",
    "VALUES" => \travelsoft\booking\Utils::stypes(),
    "NAME" => "Тип услуги"
);