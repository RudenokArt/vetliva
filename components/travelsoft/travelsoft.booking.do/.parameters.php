<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$rsET = CEventType::GetList(array('LID' => LANGUAGE_ID));
while ($arET = $rsET->Fetch()) {
    $mailEv[$arET['EVENT_NAME']] = "[" . $arET['EVENT_NAME'] . "]" . $arET['NAME'];
}

$arComponentParameters = array(
    "PARAMETERS" => array(
        "_POST" => array (
            "PARENT" => "BASE",
            "NAME" => "POST данные запроса",
            "DEFAULT" => '={$_POST["make_booking"]}'
        ),
        "PAYMENT_PAGE" => array(
            "PARENT" => "BASE",
            "NAME" => 'Страница для оплаты',
            "TYPE" => "STRING",
            "DEFAULT" => "/personal/payment/"
        ),
        "INC_JQUERY_MASKEDINPUT" => array(
            "PARENT" => "BASE",
            "TYPE" => "CHECKBOX",
            "NAME" => "Подключать jquery.maskedinput.js(для проверки ввода данных в полях фомы)",
            "DEFAULT" => "Y"
        )
    )
);