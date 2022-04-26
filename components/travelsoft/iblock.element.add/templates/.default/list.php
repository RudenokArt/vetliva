<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(false);

if (isset($_REQUEST["CANCEL"])) {
    LocalRedirect($APPLICATION->GetCurPageParam("", array("filter", "sort_by_modify_date", "CANCEL"), false));
}

if (empty($arParams["FILTER"]) || !is_array($arParams["FILTER"])) {
    $arParams["FILTER"] = [];
}



if (defined("IS_EXCURSION_TOUR") && IS_EXCURSION_TOUR === "Y") {
    if (isset($_REQUEST["filter"])) {

        if ($_REQUEST["filter"] === "active") {
            $arParams["FILTER"] = array_merge($arParams["FILTER"], array("ACTIVE" => "Y"));
        } elseif ($_REQUEST["filter"] === "no_active") {
            $arParams["FILTER"] = array_merge($arParams["FILTER"], array("ACTIVE" => "N"));
        } elseif ($_REQUEST["filter"] === "active_multidays_tours") {
            $arParams["FILTER"] = array_merge($arParams["FILTER"], array("ACTIVE" => "Y", "PROPERTY_IS_EXCURSION_TOUR_VALUE" => "Y"));
        } elseif ($_REQUEST["filter"] === "no_active_multidays_tours") {
            $arParams["FILTER"] = array_merge($arParams["FILTER"], array("ACTIVE" => "N", "PROPERTY_IS_EXCURSION_TOUR_VALUE" => "Y"));
        } elseif ($_REQUEST["filter"] === "no_active_tours") {
            $arParams["FILTER"] = array_merge($arParams["FILTER"], array("ACTIVE" => "N", "PROPERTY_IS_EXCURSION_TOUR_VALUE" => false));
        } elseif ($_REQUEST["filter"] === "active_tours") {
            $arParams["FILTER"] = array_merge($arParams["FILTER"], array("ACTIVE" => "Y", "PROPERTY_IS_EXCURSION_TOUR_VALUE" => false));
        }
    }

    if ($_REQUEST["sort_by_modify_date"] === "Y") {
        $arParams["SORT"] = array("TIMESTAMP_X" => "DESC");
    }
}

if(isset($_REQUEST['order_sort'])){
    $sort = $_REQUEST['order_sort']['sort'] ?? 'ID';
    $order = $_REQUEST['order_sort']['order'] ?? 'ASC';

    $arParams["SORT"] = [$sort => $order];
}

$APPLICATION->IncludeComponent("travelsoft:iblock.element.add.list", "", $arParams, $component);
?>
