<?php

\Bitrix\Main\Loader::includeModule("iblock");

$ibTable = new CIBlock;

$dbRes = $ibTable->GetList(array("NAME" => "ASC"), array("ACTIVE" => "Y"));

while ($arRes = $dbRes->Fetch()) {

    $arIblocks[$arRes['ID']] = $arRes['NAME'];
}

$arComponentParameters["PARAMETERS"]["_POST"] = array(
    "PARENT" => "BASE",
    "NAME" => "POST данные запроса",
    "DEFAULT" => '={$_POST}'
);

$arComponentParameters["PARAMETERS"]["IBLOCK_ID"] = array(
    "PARENT" => "BASE",
    "TYPE" => "LIST",
    "MULTIPLE" => "Y",
    "VALUES" => $arIblocks,
    "NAME" => "Инфоблоки точек трансферов"
);


$arUF = $GLOBALS["USER_FIELD_MANAGER"]->GetUserFields('HLBLOCK_' . \Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "service_hl_id"), 0);
$dbRes = CUserFieldEnum::GetList(array(), array(
            "USER_FIELD_ID" => $arUF["UF_SERVICE_TYPE_NAME"]['ID'],
        ));
while ($res = $dbRes->Fetch()) {
    $arUFV[$res["ID"]] = $res["VALUE"];
}
$arComponentParameters["PARAMETERS"]["SERVICE_TYPE"] = array(
    "PARENT" => "BASE",
    "TYPE" => "LIST",
    "VALUES" => $arUFV,
    "NAME" => "Тип услуги"
);
$arComponentParameters["PARAMETERS"]["LIST_PAGE"] = array(
    "PARENT" => "BASE",
    "TYPE" => "STRING",
    "DEFAULT" => "/partners/transfery/dobavit-transfery/",
    "NAME" => "Страница списка трансферов"
);

