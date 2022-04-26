<?php

include_once "_common.php";

$req = $_REQUEST["HISTORY_ARCHIVE"];

if (
        $req["SERVICE"] > 0 &&
        checkServiceAccess($req["SERVICE"]) &&
        strlen($req["DATE_FROM"]) &&
        strlen($req["DATE_TO"])
) {
    
    $is_multiple_tour = isMultipletour($req["SERVICE"]);
    $commom_archive_filter = [
        "UF_OBJECT" => "HIGHLOADBLOCK_ELEMENT",
        "UF_SERVICE_ID" => $req["SERVICE"],
        "UF_STORE_ID" => $is_multiple_tour ?  \travelsoft\booking\Utils::getOpt("ratesQuotas") : \travelsoft\booking\Utils::getOpt("quotas"),
        "><UF_DATE" => [
            date("Y-m-d H:i:s", strtotime($req["DATE_FROM"])),
            date("Y-m-d", strtotime($req["DATE_TO"])).' 23:59:59'
        ]
    ];
    
    $filter = [];
    
    $action = strtolower($req["ACTION"]);
    $need_value_before_field = false;
    $show_user_field = true;
    switch (strtolower($action)) {

        case "add":
        case "delete":
        case "update":

            $need_value_before_field = strtolower($req["ACTION"]) === "update";
            
            $filter = ["UF_ACTION" => strtoupper($req["ACTION"]), ">UF_USER_ID" => 0];

            break;
        case "history":
            $show_user_field = false;
            $filter = ["UF_ACTION" => "DELETE", "UF_USER_ID" => 0];

            break;
        
        default: __exit();
    }
    
    
    $db_list = \travelsoft\vetliva\DBHistory::getInstance()->getArchive([
                "where" => array_merge($filter, $commom_archive_filter),
                "order" => ["UF_DATE" => "desc"]
            ])->fetchAll();
    
    $list = [];
    
    $colspan = 5;
    if (!$show_user_field) {
        $colspan--;
    }
    if (!$need_value_before_field) {
        $colspan--;
    }
    
    foreach ($db_list as $row) {
        $detail_info = \travelsoft\vetliva\sta($row["UF_DETAIL_INFO"]);
        
        if (key_exists("UF_STOP", $detail_info["CHANGE"])) {
            if (is_array($detail_info["CHANGE"]["UF_DATE"])) $detail_info["CHANGE"]["UF_DATE"]  = $detail_info["CHANGE"]["UF_DATE"]['VALUE'];
            if (is_array($detail_info["CHANGE"]["UF_RATE_ID"])) $detail_info["CHANGE"]["UF_RATE_ID"]  = $detail_info["CHANGE"]["UF_RATE_ID"]['VALUE'];
            if (is_array($detail_info["BEFORE_CHANGE"]["UF_RATE_ID"])) $detail_info["BEFORE_CHANGE"]["UF_RATE_ID"]  = $detail_info["BEFORE_CHANGE"]["UF_RATE_ID"]['VALUE'];
            if (is_array($detail_info["CHANGE"]["UF_STOP"])) $detail_info["CHANGE"]["UF_STOP"]  = $detail_info["CHANGE"]["UF_STOP"]['VALUE'];
            if (is_array($detail_info["BEFORE_CHANGE"]["UF_STOP"])) $detail_info["BEFORE_CHANGE"]["UF_STOP"]  = $detail_info["BEFORE_CHANGE"]["UF_STOP"]['VALUE'];
            $row["unserialized_detail_info"] = $detail_info;
            $row["value"] = intval($detail_info["CHANGE"]["UF_STOP"]);
            $row["value_before"] = intval($detail_info["BEFORE_CHANGE"]["UF_STOP"]);
            if ($is_multiple_tour) {
                $row["rate_name"] = getRateName(intval($detail_info["BEFORE_CHANGE"]["UF_RATE_ID"]));
            }
            $row["date"] = $row["UF_DATE"]->toString();
            $row["user"] = getUser($row["UF_USER_ID"]);
            $row["on_date"] = $detail_info["CHANGE"]["UF_DATE"] ? date("d.m.Y", $detail_info["CHANGE"]["UF_DATE"]) : onDateQuota($row["UF_ELEMENT_ID"]);
            $list[] = $row;
        }
    }

    if (!empty($list)) {
        include_once '../parts/stop_sale.php';
    }
}


__exit();
