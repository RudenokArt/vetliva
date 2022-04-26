<?php

/**
 * @param int $service_id
 * @return boolean
 */
function isMultipletour($service_id) {

    $service = current(travelsoft\booking\datastores\ServicesDataStore::get([
                "filter" => ["ID" => $service_id],
                "select" => ["UF_IBLOCK_ELEMENT_ID", "ID"]
    ]));

    if ($service["UF_IBLOCK_ELEMENT_ID"] > 0) {

        $tour = current(travelsoft\booking\datastores\ExcursionsDataStore::get([
                    "filter" => ["ID" => $service["UF_IBLOCK_ELEMENT_ID"]]
        ]));

        if ($tour["ID"] > 0 && $tour["PROPERTIES"]["IS_EXCURSION_TOUR"]["VALUE"] === "Y") {
            return true;
        }
    }

    return false;
}

/**
 * @param int $price_row_id
 * @return string
 */
function onDatePrice(int $price_row_id = 0) {
    $price_row = getPriceRow($price_row_id);

    if (isset($price_row["UF_DATE"])) {
        return date('d.m.Y', $price_row["UF_DATE"]);
    }

    return '';
}

/**
 * @param int $quota_row_id
 * @return string
 */
function onDateQuota(int $quota_row_id = 0) {
    $quota_row = getQuotaRow($quota_row_id);

    if (isset($quota_row["UF_DATE"])) {
        return date('d.m.Y', $quota_row["UF_DATE"]);
    }

    return '';
}

/**
 * @staticvar array $quotas_rows
 * @param int $quota_row_id
 * @return array
 */
function getQuotaRow(int $quota_row_id) {
    static $quotas_rows = [];

    if (!isset($quotas_rows[$quota_row_id])) {

        $quotas_rows[$quota_row_id] = current((new \travelsoft\booking\datastores\QuotasDataStore([
                    "filter" => ["ID" => $quota_row_id]
                        ]))->fetch());

        if (!is_array($quotas_rows[$quota_row_id]) || empty($quotas_rows[$quota_row_id])) {
            $quotas_rows[$quota_row_id] = [];
        }
    }

    return $quotas_rows[$quota_row_id];
}

/**
 * @staticvar array $ptr_rows
 * @param int $ptr_id
 * @return array
 */
function getPTRatesRow(int $ptr_id) {

    static $ptr_rows = [];

    if (!isset($ptr_rows[$ptr_id])) {

        $ptr_rows[$ptr_id] = current((new \travelsoft\booking\datastores\PTRatesDataStore([
                    "filter" => ["ID" => $ptr_id]
                        ]))->fetch());

        if (!is_array($ptr_rows[$ptr_id]) || empty($ptr_rows[$ptr_id])) {
            $ptr_rows[$ptr_id] = [];
        }
    }

    return $ptr_rows[$ptr_id];
}

/**
 * @staticvar array $prices_rows
 * @param int $price_row_id
 * @return array
 */
function getPriceRow(int $price_row_id = 0) {

    static $prices_rows = [];

    if (!isset($prices_rows[$price_row_id])) {

        $prices_rows[$price_row_id] = current((new \travelsoft\booking\datastores\PricesDataStore([
                    "filter" => ["ID" => $price_row_id]
                        ]))->fetch());

        if (!is_array($prices_rows[$price_row_id]) || empty($prices_rows[$price_row_id])) {
            $prices_rows[$price_row_id] = [];
        }
    }

    return $prices_rows[$price_row_id];
}

/**
 * @param int $rate_id
 * @return string
 */
function getRateName(int $rate_id) {
    static $rate_names = [];
    if (!isset($rate_names[$rate_id])) {
        $rate = current((new \travelsoft\booking\datastores\RatesDataStore([
                    "filter" => ["ID" => $rate_id]
                        ]))->fetch());
        if (!empty($rate)) {

            $arr_currency = \travelsoft\Currency::getInstance()->get("currency");

            $rate_names[$rate_id] = $rate["UF_NAME"] . " " . $arr_currency[$rate["UF_CURRENCY_ID"]]["iso"];
        }
    }


    return $rate_names[$rate_id];
}

/**
 * @param int $rate_id
 * @return string
 */
function getTransferRateName(int $rate_id) {
    static $rate_names = [];
    if (!isset($rate_names[$rate_id])) {
        $rate = current((new \travelsoft\booking\datastores\TransfersRatesDataStore([
                    "filter" => ["ID" => $rate_id]
                        ]))->fetch());
        if (!empty($rate)) {

            $arr_currency = \travelsoft\Currency::getInstance()->get("currency");

            $rate_names[$rate_id] = current(travelsoft\booking\datastores\ClassAutoDataStore::get(["filter" => ["ID" => $rate["UF_CLASS_AUTO"]]]))["UF_NAME"] . " " . $arr_currency[$rate["UF_CURRENCY_ID"]]["iso"];
        }
    }


    return $rate_names[$rate_id];
}

/**
 * @staticvar array $rates_rows
 * @param int $price_row_id
 * @return string
 */
function getRate(int $price_row_id = 0) {

    static $rates_rows = [];

    $price_row = getPriceRow($price_row_id);

    if (!empty($price_row)) {

        if ($price_row["UF_PTPR_ID"] > 0) {

            $ptr_row = getPTRatesRow($price_row["UF_PTPR_ID"]);
            if (!empty($ptr_row)) {

                if (!isset($rates_rows[$ptr_row["UF_RATE_CATEGORY_ID"]])) {

                    $rates_rows[$ptr_row["UF_RATE_CATEGORY_ID"]] = getRateName($ptr_row["UF_RATE_CATEGORY_ID"]);
                }
                return $rates_rows[$ptr_row["UF_RATE_CATEGORY_ID"]];
            }
        }
    }

    return '';
}


/**
 * @staticvar array $rates_rows
 * @param int $price_row_id
 * @return string
 */
function getTransferRate(int $price_row_id = 0) {

    static $rates_rows = [];

    $price_row = getPriceRow($price_row_id);

    if (!empty($price_row)) {

        if ($price_row["UF_PTPR_ID"] > 0) {

            $ptr_row = getPTRatesRow($price_row["UF_PTPR_ID"]);
            if (!empty($ptr_row)) {

                if (!isset($rates_rows[$ptr_row["UF_RATE_CATEGORY_ID"]])) {

                    $rates_rows[$ptr_row["UF_RATE_CATEGORY_ID"]] = getTransferRateName($ptr_row["UF_RATE_CATEGORY_ID"]);
                }
                return $rates_rows[$ptr_row["UF_RATE_CATEGORY_ID"]];
            }
        }
    }

    return '';
}

/**
 * @staticvar array $rates_rows
 * @param int $ptpr_id
 * @return string
 */
function getRateByPTPRid(int $ptpr_id) {

    static $rates_rows = [];

    $ptr_row = getPTRatesRow($ptpr_id);
    if (!empty($ptr_row)) {

        if (!isset($rates_rows[$ptr_row["UF_RATE_CATEGORY_ID"]])) {

            $rates_rows[$ptr_row["UF_RATE_CATEGORY_ID"]] = getRateName($ptr_row["UF_RATE_CATEGORY_ID"]);
        }
        return $rates_rows[$ptr_row["UF_RATE_CATEGORY_ID"]];
    }
    return "";
}

/**
 * @staticvar array $rates_rows
 * @param int $ptpr_id
 * @return string
 */
function getTransferRateByPTPRid(int $ptpr_id) {

    static $rates_rows = [];

    $ptr_row = getPTRatesRow($ptpr_id);
    if (!empty($ptr_row)) {

        if (!isset($rates_rows[$ptr_row["UF_RATE_CATEGORY_ID"]])) {

            $rates_rows[$ptr_row["UF_RATE_CATEGORY_ID"]] = getTransferRateName($ptr_row["UF_RATE_CATEGORY_ID"]);
        }
        return $rates_rows[$ptr_row["UF_RATE_CATEGORY_ID"]];
    }
    return "";
}


/**
 * @staticvar array $ptypes_rows
 * @param int $ptpr_id
 * @return string
 */
function getPtypeByPTPRid(int $ptpr_id) {

    static $ptypes_rows = [];

    $ptr_row = getPTRatesRow($ptpr_id);

    if (!empty($ptr_row)) {
        if (!isset($ptypes_rows[$ptr_row["UF_RATE_ID"]])) {

            $ptypes_rows[$ptr_row["UF_RATE_ID"]] = getPtypeName($ptr_row["UF_RATE_ID"]);
        }
        return $ptypes_rows[$ptr_row["UF_RATE_ID"]];
    }
    return '';
}

/**
 * @param int $ptype_id
 * @return string
 */
function getPtypeName(int $ptype_id) {
    $ptype = current((new \travelsoft\booking\datastores\PriceTypesDataStore([
                "filter" => ["ID" => $ptype_id]
                    ]))->fetch());
    if (!empty($ptype)) {

        return $ptype["UF_NAME"];
    }
    return '';
}

/**
 * @staticvar array $ptypes_rows
 * @param int $price_row_id
 * @return string
 */
function getPtype(int $price_row_id = 0) {

    static $ptypes_rows = [];

    $price_row = getPriceRow($price_row_id);

    if (!empty($price_row)) {

        if ($price_row["UF_PTPR_ID"] > 0) {

            $ptr_row = getPTRatesRow($price_row["UF_PTPR_ID"]);
            if (!empty($ptr_row)) {

                if (!isset($ptypes_rows[$ptr_row["UF_RATE_ID"]])) {

                    $ptypes_rows[$ptr_row["UF_RATE_ID"]] = getPtypeName($ptr_row["UF_RATE_ID"]);
                }
                return $ptypes_rows[$ptr_row["UF_RATE_ID"]];
            }
        }
    }

    return '';
}

/**
 * @global CMain $USER
 * @staticvar array $users
 * @param int $user_id
 * @return array
 */
function getUser(int $user_id = 0) {

    global $USER;

    static $users = [];

    if (!isset($users[$user_id])) {

        $user = $USER->GetByID($user_id)->Fetch();

        if (isset($user["ID"])) {
            $users[$user_id] = $user["EMAIL"];
        }
    }

    return $users[$user_id];
}

/**
 * @global CMain $USER
 * @param int $service_id
 * @return boolean
 */
function checkServiceAccess($service_id) {

    global $USER;

    $filter = ["ID" => $service_id, "UF_USER_ID" => $USER->GetID()];

    return !empty((new \travelsoft\booking\datastores\ServicesDataStore(["filter" => $filter]))->fetch());
}

function __exit() {
    echo "";
    exit();
}
