<?php

include_once "_common.php";

$req = $_REQUEST["HISTORY_ARCHIVE"];

if (
        $req["SERVICE"] > 0 &&
        checkServiceAccess($req["SERVICE"]) &&
        strlen($req["DATE_FROM"]) &&
        strlen($req["DATE_TO"])
) {

    $commom_archive_filter = [
        "UF_OBJECT" => "HIGHLOADBLOCK_ELEMENT",
        "UF_SERVICE_ID" => $req["SERVICE"],
        "UF_STORE_ID" => \travelsoft\booking\Utils::getOpt("prices"),
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
    $test = array_merge($filter, $commom_archive_filter);
    
    $db_list = \travelsoft\vetliva\DBHistory::getInstance()->getArchive([
                "where" => array_merge($filter, $commom_archive_filter),
                "order" => ["UF_DATE" => "desc"]
            ])->fetchAll();

    $list = [];
    
    $colspan = 7;
    if (!$show_user_field) {
        $colspan--;
    }
    if (!$need_value_before_field) {
        $colspan--;
    }
    
    foreach ($db_list as $row) {
        $detail_info = \travelsoft\vetliva\sta($row["UF_DETAIL_INFO"]);
        if (isset($detail_info["CHANGE"]["UF_GROSS"])) {
            if (is_array($detail_info["CHANGE"]["UF_DATE"])) $detail_info["CHANGE"]["UF_DATE"]  = $detail_info["CHANGE"]["UF_DATE"]['VALUE'];
            if (is_array($detail_info["CHANGE"]["UF_PTPR_ID"])) $detail_info["CHANGE"]["UF_PTPR_ID"]  = $detail_info["CHANGE"]["UF_PTPR_ID"]['VALUE'];
            if (is_array($detail_info["CHANGE"]["UF_GROSS"])) $detail_info["CHANGE"]["UF_GROSS"]  = $detail_info["CHANGE"]["UF_GROSS"]['VALUE'];
            if (is_array($detail_info["BEFORE_CHANGE"]["UF_GROSS"])) $detail_info["BEFORE_CHANGE"]["UF_GROSS"]  = $detail_info["BEFORE_CHANGE"]["UF_GROSS"]['VALUE'];
            $row["unserialized_detail_info"] = $detail_info;
            $row["value"] = $detail_info["CHANGE"]["UF_GROSS"];
            $row["value_before"] = $detail_info["BEFORE_CHANGE"]["UF_GROSS"];
            $row["date"] = $row["UF_DATE"]->toString();
            $row["user"] = getUser($row["UF_USER_ID"]);
            $row["on_date"] = $row["on_date"] = $detail_info["CHANGE"]["UF_DATE"] ? date("d.m.Y", $detail_info["CHANGE"]["UF_DATE"]) : onDatePrice($row["UF_ELEMENT_ID"]);
            $row["rate"] = $detail_info["CHANGE"]["UF_PTPR_ID"] > 0 ? getRateByPTPRid($detail_info["CHANGE"]["UF_PTPR_ID"]) : getRate($row["UF_ELEMENT_ID"]);
            $row["ptype"] = $detail_info["CHANGE"]["UF_PTPR_ID"]> 0 ? getPtypeByPTPRid($detail_info["CHANGE"]["UF_PTPR_ID"]) : getPtype($row["UF_ELEMENT_ID"]);
            $list[] = $row;
        }
    }

    if (!empty($list)) {
        include_once '../parts/prices.php';
    }
}


__exit();
