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
            date("Y-m-d H:i:s", strtotime($req["DATE_TO"]))
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

    $colspan = 6;
    if (!$show_user_field) {
        $colspan--;
    }
    if (!$need_value_before_field) {
        $colspan--;
    }
    if (!$is_multiple_tour) {
        $colspan--;
    }

    foreach ($db_list as $row) {
        $detail_info = \travelsoft\vetliva\sta($row["UF_DETAIL_INFO"]);

        if (isset($detail_info["CHANGE"]["UF_QUOTE"])) {

            $row["unserialized_detail_info"] = $detail_info;
            $row["value"] = $detail_info["CHANGE"]["UF_QUOTE"];
            $row["value_before"] = $detail_info["BEFORE_CHANGE"]["UF_QUOTE"];
            if ($is_multiple_tour) {
                $row["rate"] = getRateName(intval($detail_info["BEFORE_CHANGE"]["UF_RATE_ID"]));
            }
            $row["date"] = $row["UF_DATE"]->toString();
            $row["user"] = getUser($row["UF_USER_ID"]);
            $row["on_date"] = $detail_info["CHANGE"]["UF_DATE"] ? date("d.m.Y", $detail_info["CHANGE"]["UF_DATE"]) : onDateQuota($row["UF_ELEMENT_ID"]);
            $list[] = $row;
        }
    }

    if (!empty($list)) {
        include_once '../parts/quotas.php';
    }
}


__exit();
