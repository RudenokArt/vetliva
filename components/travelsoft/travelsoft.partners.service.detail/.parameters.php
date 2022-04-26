<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentParameters = array(
    "PARAMETERS" => array(
        "KEY" => array (
            "PARENT" => "BASE",
            "NAME" => "ID услуги",
            "DEFAULT" => '={$_REQUEST["key"]}'
        )
    )
);
