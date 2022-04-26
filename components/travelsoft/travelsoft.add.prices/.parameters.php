<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

CModule::IncludeModule('highloadblock');

$arHiloadblocks = Bitrix\Highloadblock\HighloadBlockTable::getList(array(
	"order" => array("ID" => "ASC")
))->fetchAll();

foreach  ($arHiloadblocks as $arHL) {
    $arHLDef[$arHL['ID']] = $arHL['NAME'];
}

$arUsersGroups = Bitrix\Main\GroupTable::getList(array(
    "select" => array('ID', "NAME")
))->fetchAll();

foreach ($arUsersGroups as $arUserGroup) {
    $arUGDef[$arUserGroup['ID']] = $arUserGroup['NAME'];
}

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(
		
		"ROW_ID" => array(
			"PARENT" => "BASE",
			"NAME" => 'ID Записи',
			"TYPE" => "TEXT",
			"DEFAULT" => '={$_REQUEST[\'row_id\']}'
		),

                                "ALLOW_USER_GROUPS" => array(
                                                "PARENT" => "BASE",
                                                "NAME" => "Группы пользователей, которым разрешено добавлять/редактировать элементы",
                                                "TYPE" => "LIST",
                                                "MULTIPLE" => "Y",
                                                "VALUES" => $arUGDef
                                ),
            
                                "SERVICES_BOOKING_HL_BLOCK" => array(
                                                "PARENT" => "BASE",
                                                "NAME" => "hiloadblock услуг(номеров)",
                                                "TYPE" => "LIST",
                                                "MULTIPLE" => "N",
                                                "VALUES" => $arHLDef
                                ),
            
                                "QUOTAS_BOOKING_HL_BLOCK" => array(
                                                "PARENT" => "BASE",
                                                "NAME" => "hiloadblock квот",
                                                "TYPE" => "LIST",
                                                "MULTIPLE" => "N",
                                                "VALUES" => $arHLDef
                                ),
                                
            
                                "RATES_BOOKING_HL_BLOCK" => array(
                                                "PARENT" => "BASE",
                                                "NAME" => "hiloadblock тарифов",
                                                "TYPE" => "LIST",
                                                "MULTIPLE" => "N",
                                                "VALUES" => $arHLDef
                                ),
            
                                "PRICE_TYPES_PLUSE_RATES_BOOKING_HL_BLOCK" => array(
                                                "PARENT" => "BASE",
                                                "NAME" => "hiloadblock тарифы+тип цен",
                                                "TYPE" => "LIST",
                                                "MULTIPLE" => "N",
                                                "VALUES" => $arHLDef
                                ),
            
                                "PRICE_TYPE_BOOKING_HL_BLOCK" => array(
                                                "PARENT" => "BASE",
                                                "NAME" => "hiloadblock тип цен",
                                                "TYPE" => "LIST",
                                                "MULTIPLE" => "N",
                                                "VALUES" => $arHLDef
                                ),
            
            
                                "PRICES_BOOKING_HL_BLOCK" => array(
                                                "PARENT" => "BASE",
                                                "NAME" => "hiloadblock цен",
                                                "TYPE" => "LIST",
                                                "MULTIPLE" => "N",
                                                "VALUES" => $arHLDef
                                ),
            
                                "MESSOK" =>  array(
                                                "PARENT" => "BASE",
                                                "NAME" => "Сообщение об успешном сохранении",
                                                "TYPE" => "TEXT"
                                ),
            
                                "UNIQUE_ID" =>  array(
                                                "PARENT" => "BASE",
                                                "NAME" => "Уникальный id компонента для корректной обработки запроса (задавать обязательно).",
                                                "TYPE" => "TEXT",
                                                "DEFAULT" => "d41d8cd98f00b204e9800998ecf8427e"
                                ),
	)
);