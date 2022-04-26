<?php
define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC", "Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);

$documentRoot = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
require_once($documentRoot . '/bitrix/modules/main/include/prolog_before.php');

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();

$actions = ['clear-cache' => function () {
    
    $io = new CBXVirtualIo;
    
    $cache_path = $io->RelativeToAbsolutePath("/bitrix/cache/travelsoft/search_offers_result");
    
    if ($io->DirectoryExists($cache_path)) {
        $io->Delete($cache_path);
    }
    
    exit;
}];



if (!check_bitrix_sessid() || !isset($actions[$request->get('action')])) {
    $protocol = filter_input(INPUT_SERVER, 'SERVER_PROTOCOL');
    header($protocol . " 404 Not Found");
    exit;
}

$actions[$request->get('action')]();