<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
   $this->setFrameMode(true);

$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/webui-popover/jquery.webui-popover.min.css");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/webui-popover/jquery.webui-popover.min.js");

$request_string = $arParams["BOOKING_REQUEST_DETAIL_STRING"] != "" ? "&".$arParams["BOOKING_REQUEST_DETAIL_STRING"] : "";
   ?>
<?if($arResult["ITEMS"]):?>

<?
// ПРОИЗВОДИМ РАСЧЁТ ЦЕН
if ($arParams["MAKE_PRICING"] == "Y") {
    
    foreach ($arResult["ITEMS"] as $arItem) {
        $parameters["id"][] = $arItem["ID"];
    }
    
    $result = $APPLICATION->IncludeComponent(
	"travelsoft:travelsoft.service.price.result", 
	"on.detail.page.render", 
	array(
		"RETURN_RESULT" => "Y",
		"FILTER_BY_PRICES_FOR_CITIZEN" => "N",
		"TYPE" => "excursionstours",
		"POSTFIX_PROPERTY" => POSTFIX_PROPERTY,
		"__BOOKING_REQUEST" => $parameters,
		"MP" => "Y",
		"COMPONENT_TEMPLATE" => "on.detail.page.render",
		"CODE" => "",
		"MAKE_ORDER_PAGE" => "/booking/",
		"INC_JQUERY" => "Y",
		"INC_MAGNIFIC_POPUP" => "Y",
		"INC_OWL_CAROUSEL" => "Y"
	),
	false
);
    
}

?>


		<?if($arParams["TITLE_LIST"]):?><h3><?=$arParams["TITLE_LIST"]?></h3><?endif;?>
		<div class="tour-list-cn clearfix">
		<? foreach ($arResult["ITEMS"] as $arItem): ?>
						<?
						$arItem["DETAIL_PAGE_URL"] = (($arItem["PROPERTIES"]["IS_EXCURSION_TOUR"]["VALUE"] == "Y") ? $arItem["DETAIL_PAGE_URL"] : str_replace("tourism/tours-in-belarus", "tourism/cognitive-tourism", $arItem["DETAIL_PAGE_URL"]));
						$p = $arItem["DISPLAY_PROPERTIES"];
						$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
						$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
						?>
						<div class="tour-list-item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                            <?
                            if (!empty($arItem["PREVIEW_PICTURE"])):
                                $an_file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width' => 1170, 'height' => 350), BX_RESIZE_IMAGE_EXACT, true);
                                $pre_photo = $an_file["src"];
                            elseif (!empty($arItem["PROPERTIES"]["PICTURES"]["VALUE"])):
                                $an_file = CFile::ResizeImageGet($arItem["PROPERTIES"]["PICTURES"]["VALUE"][0], array('width' => 1170, 'height' => 350), BX_RESIZE_IMAGE_EXACT, true);
                                $pre_photo = $an_file["src"];
                            else:
                                $pre_photo = SITE_TEMPLATE_PATH . "/images/nophoto.jpg";
                            endif;
                            ?>
							<figure style="background-image: url(<?= $pre_photo ?>)">
                                <div class="tour-text">
                                    <div class="tour-name">
                                        <a href="<? echo $arItem["DETAIL_PAGE_URL"].$request_string ?>" title="<? echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?>"><? echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?></a>
                                    </div>
                                    <div class="tour-route">
                                        <? if (!empty($arItem["PROPERTIES"]["ROUTE"]["VALUE"])): ?>

                                                <span class="label"><span data-content="<?= GetMessage('TOWN') ?>" class="border_icon"><img src="<?= $templateFolder ?>/images/route_24.png"></span> </span>
                                                <span class="route"><?= strip_tags($arItem["DISPLAY_PROPERTIES"]["ROUTE" . POSTFIX_PROPERTY]["DISPLAY_VALUE"]) ?></span>

                                        <? elseif (!empty($arItem["PROPERTIES"]["TOWN"]["VALUE"])): ?>

                                                <span class="label"><span data-content="<?= GetMessage('TOWN') ?>" class="border_icon"><img src="<?= $templateFolder ?>/images/route_24.png"></span> </span>
                                                <?
                                                $p["TOWN"]["VALUE"] = (array) $p["TOWN"]["VALUE"];
                                                $db_res_towns = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $p["TOWN"]["LINK_IBLOCK_ID"], "ID" => $p["TOWN"]["VALUE"]), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY));
                                                $towns = null;
                                                while ($res = $db_res_towns->Fetch()) {
                                                    $towns[] = $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] ? $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $res["NAME"];
                                                }
                                                if ($towns) {
                                                    ?><span class="route"><?echo implode(" - ", $towns);?></span><?
                                                }
                                                ?>

                                        <? endif; ?>
                                    </div>
                                </div>
                                <div class="price-box float-right">
                                    <a href="<? echo $arItem["DETAIL_PAGE_URL"] . $request_string ?>" title="<? echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?>"><span class="detail"><?=GetMessage('MORE')?></span></a>
                                </div>
							</figure>
						</div>
		<? endforeach; ?>
		<div style="clear: both;"></div>
		   <?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
		   <br /><?=$arResult["NAV_STRING"]?>
		   <?endif;?>
		</div>
<?endif;?>
<script>
    (function () {
        function initPopover() {
            $('.tour-service a, .ship-port li .label a').webuiPopover({
                placement: "left",
                trigger: "hover"
            });
			/*$('.tour-service a, .ship-port li .').webuiPopover({
                placement: "left",
                trigger: "hover"
			});*/
        }
        initPopover();
    })();
</script>
