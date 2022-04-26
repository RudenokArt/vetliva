<?php
/*AddEventHandler("main", "OnProlog", "CloseAccessForGroup");
function CloseAccessForGroup()
{
global $USER, $APPLICATION;
$mas = $USER->GetUserGroupArray();
if (count($mas)==1 && in_array(2, $mas) && !in_array(Array (1, 8), $mas) && (strpos($APPLICATION->GetCurPage(),'/bitrix/admin/'))===false)
	{
require($_SERVER["DOCUMENT_ROOT"]."/underconstruction.php");
	die();
	}
}
*/
define("LOG_FILENAME", "/var/log/php/log.txt");


AddEventHandler('main', 'OnEpilog', '_Check404Error', 1);
function _Check404Error(){
 if (defined('ERROR_404') && ERROR_404 == 'Y') {
 global $APPLICATION, $USER;
 $APPLICATION->RestartBuffer();
 include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/header.php';
 include $_SERVER['DOCUMENT_ROOT'] . '/404.php';
 include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/footer.php';
 }
}

//определение дополнительных констант
if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/const.php'))
    require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/const.php';

//подключение и настройка классов
if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/class_includer.php'))
    require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/class_includer.php';

//обработчики событий
if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/events.php'))
    require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/events.php';

//библиотека функций
if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/functions.php'))
    require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/functions.php';


