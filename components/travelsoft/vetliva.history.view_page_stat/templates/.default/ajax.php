<?php
define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC", "Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);

$documentRoot = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
require_once($documentRoot . '/bitrix/modules/main/include/prolog_before.php');

header('Content-Type: application/json; charset=' . SITE_CHARSET);

$hash = md5(intval($_POST["ID"]));

if (check_bitrix_sessid() && $hash === $_POST["hash"]) {

    Bitrix\Main\Loader::includeModule("travelsoft.vetliva.history");

    CBitrixComponent::includeComponentClass("travelsoft:vetliva.history.view_page_stat");

    $component = new TravelsoftVetlivaHistoryPageViewStatistic();

    $component->arParams["ID"] = intval($_POST["ID"]);

    $arResult = $component->getResult();

    ob_start();
    ?>
    <div calss="total-shows-block">
        Просмотры Вашей страницы<br>
        <?= intval($arResult['COUNT']['TOTAL']['SHOWS']) ?>
    </div>
    <div calss="total-booking-block">
        Бронирования<br>
        <?= intval($arResult['COUNT']['TOTAL']['QUANTITY_BOOK']) ?>
    </div>
    <div class="arrow-block">&rarr;</div>
    <div calss="conversion-block">
        Конверсия<br>
        <?= ($arResult['COUNT']['TOTAL']['SHOWS'] ? round($arResult['COUNT']['TOTAL']['QUANTITY_BOOK'] / $arResult['COUNT']['TOTAL']['SHOWS'] * 100, 2) : 0) ?>%
    </div>
    <?
    $arResult["DETAIL_INFO"] = str_replace("\r\n", "", ob_get_clean());

    echo \json_encode($arResult);
} else {

    echo \json_encode(["error" => true]);
}

die;
