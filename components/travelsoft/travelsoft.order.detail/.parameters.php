<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentParameters = array(
    "PARAMETERS" => array(
        "ORDER_ID" => array (
            "PARENT" => "BASE",
            "NAME" => "ID заказа",
            "DEFAULT" => '={$_REQUEST["order_id"]}'
        ),
        "URL_TO_PAY" => array(
            "NAME" => "URL оплаты карточкой",
            "TYPE" => "STRING"
        )
    )
);
