<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => "Флажки условия бронирования",
	"DESCRIPTION" => "Флажки условия бронирования",
	"ICON" => "/images/news_list.gif",
	"SORT" => 10,
	"CACHE_PATH" => "Y",
//    "COMPLEX" => "Y",
    "PATH" => array(
        "ID" => "cit",
        "CHILD" => array(
            "ID" => "booking_condition",
            "NAME" => "Условия бронирования",
            "SORT" => 1000
        ),
    ),
);

?>