<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

$arEvent = Array();
$arFilter = Array("TYPE_ID" => "ADD_REVIEWS", "ACTIVE" => "Y");
$dbType = CEventMessage::GetList($by = "site_id", $order = "desc", $arFilter);
while ($arType = $dbType->GetNext())
    $arEvent[$arType["ID"]] = "[" . $arType["ID"] . "] " . $arType["SUBJECT"];

$arTemplateParameters = array(
    "DISPLAY_DATE" => Array(
        "NAME" => GetMessage("T_IBLOCK_DESC_NEWS_DATE"),
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "Y",
    ),
    "DISPLAY_PICTURE" => Array(
        "NAME" => GetMessage("T_IBLOCK_DESC_NEWS_PICTURE"),
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "Y",
    ),
    "DISPLAY_PREVIEW_TEXT" => Array(
        "NAME" => GetMessage("T_IBLOCK_DESC_NEWS_TEXT"),
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "Y",
    ),
    "USE_SHARE" => Array(
        "NAME" => GetMessage("T_IBLOCK_DESC_NEWS_USE_SHARE"),
        "TYPE" => "CHECKBOX",
        "MULTIPLE" => "N",
        "VALUE" => "Y",
        "DEFAULT" => "N",
        "REFRESH" => "Y",
    ),
    "EVENT_MESSAGE_ID" => Array(
        "NAME" => GetMessage("T_IBLOCK_EMAIL_TEMPLATES"),
        "TYPE" => "LIST",
        "VALUES" => $arEvent,
        "DEFAULT" => "",
        "MULTIPLE" => "Y",
        "COLS" => 25,
    ),
);

if ($arCurrentValues["USE_SHARE"] == "Y") {
    $arTemplateParameters["SHARE_HIDE"] = array(
        "NAME" => GetMessage("T_IBLOCK_DESC_NEWS_SHARE_HIDE"),
        "TYPE" => "CHECKBOX",
        "VALUE" => "Y",
        "DEFAULT" => "N",
    );

    $arTemplateParameters["SHARE_TEMPLATE"] = array(
        "NAME" => GetMessage("T_IBLOCK_DESC_NEWS_SHARE_TEMPLATE"),
        "DEFAULT" => "",
        "TYPE" => "STRING",
        "MULTIPLE" => "N",
        "COLS" => 25,
        "REFRESH" => "Y",
    );

    if (strlen(trim($arCurrentValues["SHARE_TEMPLATE"])) <= 0)
        $shareComponentTemlate = false;
    else
        $shareComponentTemlate = trim($arCurrentValues["SHARE_TEMPLATE"]);

    include_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/components/bitrix/main.share/util.php");

    $arHandlers = __bx_share_get_handlers($shareComponentTemlate);

    $arTemplateParameters["SHARE_HANDLERS"] = array(
        "NAME" => GetMessage("T_IBLOCK_DESC_NEWS_SHARE_SYSTEM"),
        "TYPE" => "LIST",
        "MULTIPLE" => "Y",
        "VALUES" => $arHandlers["HANDLERS"],
        "DEFAULT" => $arHandlers["HANDLERS_DEFAULT"],
    );

    $arTemplateParameters["SHARE_SHORTEN_URL_LOGIN"] = array(
        "NAME" => GetMessage("T_IBLOCK_DESC_NEWS_SHARE_SHORTEN_URL_LOGIN"),
        "TYPE" => "STRING",
        "DEFAULT" => "",
    );

    $arTemplateParameters["SHARE_SHORTEN_URL_KEY"] = array(
        "NAME" => GetMessage("T_IBLOCK_DESC_NEWS_SHARE_SHORTEN_URL_KEY"),
        "TYPE" => "STRING",
        "DEFAULT" => "",
    );
}

$arTemplateParameters["MAKE_PRICING"] = array(
    "NAME" => "Производить расчёт цен",
    "TYPE" => "CHECKBOX",
    "REFRESH" => "Y"
);
if ($arCurrentValues["MAKE_PRICING"] == "Y") {
    Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools");
    $arTemplateParameters["__BOOKING_REQUEST"] = array(
        "NAME" => "Параметры для расчёта цен",
        "TYPE" => "STRING",
        "DEFAULT" => '={$_REQUEST["booking"]}'
    );

    $arTemplateParameters["FILTER_BY_PRICES_FOR_CITIZEN"] = array(
        "NAME" => "Фильтровать по ценам для граждан",
        "TYPE" => "CHECKBOX"
    );

    $arTemplateParameters["TYPE"] = array(
        "NAME" => "Тип объектов",
        "TYPE" => "LIST",
        "VALUES" => travelsoft\booking\Utils::stOnlyNames()
    );
}