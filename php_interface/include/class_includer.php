<?
$interface_include_path = '/local/php_interface/include';

/* Вспомогательные классы */
\Bitrix\Main\Loader::registerAutoLoadClasses(
  null, 
  array(
    'InfoBlock' => $interface_include_path .'/classes/InfoBlock.php',
    'Highload_Block' => $interface_include_path .'/classes/Highload_Block.php',
    'B24_Partners' => $interface_include_path .'/classes/B24_Partners.php',
    'B24_Leads' => $interface_include_path .'/classes/B24_Leads.php',
    'B24_Deals' => $interface_include_path .'/classes/B24_Deals.php',
    "travelsoft\Bx24" => $interface_include_path ."/classes/Bx24.php",
    "travelsoft\BxEventsHandlers" => $interface_include_path ."/classes/bxeventshandlers.php",
    "travelsoft\Ajax" => $interface_include_path ."/classes/ajax.php",
//                "travelsoft\booking\Promo" => $interface_include_path ."/classes/Promo.php",
    "travelsoft\CSV" => $interface_include_path."/classes/csv.php",
    "travelsoft\rest\Logger" => $interface_include_path."/classes/Logger.php",
    "CIBlockPropertyTinyMCE" => $interface_include_path."/classes/CIBlockPropertyTinyMCE.php",
    "ReviewMail" => $interface_include_path."/classes/ReviewMail.php",
    "ReportMail" => $interface_include_path."/classes/ReportMail.php"
  )
);
