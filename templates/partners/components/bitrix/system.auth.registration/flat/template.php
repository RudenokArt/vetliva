<?

/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */
/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
$APPLICATION->AddViewContent('htmlClass', 'login-container');

if ($USER->IsAuthorized()) {
    LocalRedirect('/partners/');
}

$APPLICATION->IncludeComponent(
        "bitrix:main.register",
        "provider.register",
        Array(
            "USER_PROPERTY_NAME" => "",
            "SEF_MODE" => "N",
            "SHOW_FIELDS" => Array("EMAIL", "NAME", "LAST_NAME", "WORK_COMPANY", "WORK_CITY", "WORK_COUNTRY",
                "WORK_MAILBOX", "WORK_PHONE", "UF_LEGAL_NAME", "UF_LEGAL_ADDRESS", "UF_BANK_NAME", "UF_BANK_ADDRESS", "UF_BANK_CODE",
                "UF_CHECKING_ACCOUNT", "UF_UNP", "UF_OKPO"),
            "REQUIRED_FIELDS" => Array("EMAIL", "NAME", "LAST_NAME", "WORK_COUNTRY",
                "WORK_PHONE", "UF_LEGAL_NAME", "UF_LEGAL_ADDRESS"),
            "AUTH" => "Y",
            "USE_BACKURL" => "Y",
            //"SUCCESS_PAGE" => "/partners/",
            "SET_TITLE" => "N",
            "USER_PROPERTY" => Array(),
            "AUTH_AUTH_URL" => "/partners/"
        )
);
?>