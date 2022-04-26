<?php

define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC", "Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);

$documentRoot = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
require_once($documentRoot . '/bitrix/modules/main/include/prolog_before.php');
if ($_POST['action']=='AnnulationSeller') {
    $dataorder = unserialize(base64_decode($_POST['orderdata']));
    $currency = htmlspecialchars($dataorder["currencyTour"]);
    $ORDER_ID = htmlspecialchars($dataorder["dogovor"]["name"]);
    $touristtext = '';
    foreach ($dataorder['turists'] as $tmp) $touristtext.=$tmp['first_name'].' '.$tmp['last_name'].'<br>';
    $first_table = '<tr><td>Номер заказа</td><td>'.$ORDER_ID.'</td></tr>';
    $last_table = '<tr><td>Туристы</td><td>'.$touristtext.'</td></tr>
            <tr><td>Статус заказа</td><td>Запрос на аннуляцию</td></tr>
            <tr><td>Стоимость</td><td>'.htmlspecialchars($dataorder["price"][$currency]).' '.$currency.'</td></tr>';
            if($dataorder["discount"][$currency] > 0)
                $last_table.='<tr><td>Скидка</td><td>'.htmlspecialchars($dataorder["discount"][$currency]).' '.$currency.'</td></tr>';
     
     foreach ($dataorder['services'] as $sevrice) {
        $service_table = '<tr><td>Услуга</td><td>'.htmlspecialchars($sevrice["title"]).'</td></tr>
                    <tr><td>Дата заезда / отъезда</td><td>'.htmlspecialchars($sevrice["date_begin"]).' - '.htmlspecialchars($sevrice["date_end"]).'</td></tr>
                    <tr><td>Продолжительность</td><td>'.htmlspecialchars($sevrice["duration"]).'</td>
                    </tr>';
        $rsUser = CUser::GetByID($sevrice['parts']['partnerId']);
        $arUser = $rsUser->Fetch();
        $arEventFields = [
            "EMAIL"=>$arUser['EMAIL'],
            "ORDER_TABLE"=>'<table  border="1" cellpadding="5" style="border-collapse: collapse;">'.$first_table.$service_table.$last_table.'</table>',
            "ORDER"=>$ORDER_ID
        ];
        CEvent::Send("TRAVELSOFT_BOOKING", SITE_ID, $arEventFields, "N", 121);
     }              
echo 'ok';
exit();
}