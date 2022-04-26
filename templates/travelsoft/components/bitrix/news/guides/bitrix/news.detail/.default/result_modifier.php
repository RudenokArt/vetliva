<?

function __getBlocksElements($arFilter, $arWatermark, $detail_link_template = null) {

    $arResult = array();
    $dbList = CIBlockElement::GetList(array("SORT" => "ASC"), $arFilter, false, array("iNumPage" => 1, "nPageSize" => 6));

    while ($element = $dbList->GetNextElement()) {
        $arElement = $element->GetFields();
        $arProperties = $element->GetProperties();
        $arrImg = (array) $arProperties["PICTURES"]["VALUE"];

        if (POSTFIX_PROPERTY !== "") {
            $arElement["NAME"] = $arProperties["NAME" . POSTFIX_PROPERTY]["VALUE"];
        }

		if (isset($arProperties["IS_EXCURSION_TOUR"]) && $arProperties["IS_EXCURSION_TOUR"]["VALUE"] === "Y") {
			$arElement["DETAIL_PAGE_URL"] = str_replace("#CODE#", $arElement["CODE"], $detail_link_template);
		} elseif ($detail_link_template && !isset($arProperties["IS_EXCURSION_TOUR"])) {
            $arElement["DETAIL_PAGE_URL"] = str_replace("#CODE#", $arElement["CODE"], $detail_link_template);
        }

        $arElement["IMG_SRC"] = getSrcImage($arrImg[0], array('width' => 410, 'height' => 250), NO_PHOTO_PATH, $arWatermark);
        $arResult[] = $arElement;
    }

    return $arResult;
}

function __renderBlockItem($arData) {
    ?>
    <div class="items-block">
        <div class="col-md-6 col-sm-6 col-xs-12 text-left"><b><?= $arData["title"] ?></b></div>
        <div class="col-md-6 col-sm-6 col-xs-12 text-right hidden-xs"><a target="_blank" href="<?= $arData["link"] ?>"><?= $arData["link_title"] ?></a></div>
        <div class="clear"></div>
        <? foreach ($arData["elements"] as $arItem): ?>
            <div class="block-item col-md-4 col-sm-12 col-xs-12">
                <a class="item-link" target="_blank" href="<?= $arItem["DETAIL_PAGE_URL"] ?>">
                    <div class="image">

                        <img src="<?= $arItem["IMG_SRC"] ?>" alt="<?= $arItem["NAME"] ?>">
                    </div>
                    <div class="title-block mt-20">
                        <?= $arItem["NAME"] ?>
                    </div>
                    <div class="border-grey-bottom mt-20"></div>
                </a>
            </div>
        <? endforeach ?>
        <div class="clear"></div>
    </div>
    <?
}

//$this->component->arResult["PROPERTIES"] = $arResult['PROPERTIES']; 
$this->__component->SetResultCacheKeys(array("PROPERTIES"));

$arWatermark = array();

if ($arParams["NO_SHOW_WATERMARK"] !== "Y") {
    $arWatermark = Array(
        array(
            "name" => "watermark",
            "position" => "topright", // Положение
            "type" => "image",
            "size" => "real",
            "file" => NO_PHOTO_PATH_WATERMARK, // Путь к картинке
            "fill" => "exact",
        )
    );
}

// ATTRACTIONS
$arResult["ATTRACTIONS"] = __getBlocksElements(array("ACTIVE" => "Y", "IBLOCK_ID" => ATTRACTION_IBLOCK_ID, "PROPERTY_TOWN" => $arResult["ID"]), $arWatermark);

// TOURS
$arResult["TOURS"] = __getBlocksElements(array("ACTIVE" => "Y", "IBLOCK_ID" => EXCURSION_IBLOCK_ID, "PROPERTY_CITY" => $arResult["ID"]), $arWatermark, "/tourism/tours-in-belarus/#CODE#/");

// EVENTS
$arResult["EVENTS"] = __getBlocksElements(array("ACTIVE" => "Y", "IBLOCK_ID" => POSTER_IBLOCK_ID, ">=PROPERTY_DATE_FROM" => ConvertTimeStamp(time(), "FULL"), "PROPERTY_IS_EXCURSION_TOUR_VALUE" => "Y"), $arWatermark);

// PLACEMENTS
$arResult["PLACEMENTS"] = __getBlocksElements(array("ACTIVE" => "Y", "IBLOCK_ID" => PLACEMENTS_IBLOCK_ID, "PROPERTY_TOWN" => $arResult["ID"]), $arWatermark, "/tourism/tours-in-belarus/#CODE#/");

$arResult["CRC32_ID"] = abs(crc32($arResult["ID"]));
