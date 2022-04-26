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

$request_string = $arParams["BOOKING_REQUEST_DETAIL_STRING"] != "" ? "?" . $arParams["BOOKING_REQUEST_DETAIL_STRING"] : "";
   ?>
<?if($arResult["ITEMS"]):?>

<?
// ПРОИЗВОДИМ РАСЧЁТ ЦЕН
if ($arParams["MAKE_PRICING"] == "Y") {
    
    foreach ($arResult["ITEMS"] as $arItem) {
        $parameters["id"][] = $arItem["ID"];
    }
    
    $result = $APPLICATION->IncludeComponent(
                "travelsoft:travelsoft.service.price.result", "on.detail.page.render", Array(
            "RETURN_RESULT" => "Y",
            "FILTER_BY_PRICES_FOR_CITIZEN" => "N",
            "TYPE" => $arParams["OBJECT_TYPE"],
            "POSTFIX_PROPERTY" => POSTFIX_PROPERTY,
            "__BOOKING_REQUEST" => $parameters,
                    "MP" => "Y"
                )
        );
    
}

?>

	<section class="tour-list">
		<?if($arParams["TITLE_LIST"]):?><h3><?=$arParams["TITLE_LIST"]?></h3><?endif;?>
		<div class="tour-list-cn clearfix">
		   <?if($arParams["DISPLAY_TOP_PAGER"]):?>
		   <?=$arResult["NAV_STRING"]?><br />
		   <?endif;?>
		<? foreach ($arResult["ITEMS"] as $arItem): ?>
						<?
						$arItem["DETAIL_PAGE_URL"] = (($arItem["PROPERTIES"]["IS_EXCURSION_TOUR"]["VALUE"] == "Y") ? $arItem["DETAIL_PAGE_URL"] : str_replace("tourism/tours-in-belarus", "tourism/cognitive-tourism", $arItem["DETAIL_PAGE_URL"]));
						$p = $arItem["DISPLAY_PROPERTIES"];
						$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
						$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
						?>
						<div class="tour-list-item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
							<figure class="tour-img float-left">
								<a href="<? echo $arItem["DETAIL_PAGE_URL"]."?scroll-to-sp=Y&booking[id][]=".$arItem['ID']."&booking[date_from]=1608854400&booking[date_to]=1609804800&booking[adults]=2" ?>" title="">
									<?
									if (!empty($arItem["PREVIEW_PICTURE"])):
										$an_file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width' => 225, 'height' => 150), BX_RESIZE_IMAGE_EXACT, true);
										$pre_photo = $an_file["src"];
									elseif (!empty($arItem["PROPERTIES"]["PICTURES"]["VALUE"])):
										$an_file = CFile::ResizeImageGet($arItem["PROPERTIES"]["PICTURES"]["VALUE"][0], array('width' => 225, 'height' => 150), BX_RESIZE_IMAGE_EXACT, true);
										$pre_photo = $an_file["src"];
									else:
										$pre_photo = SITE_TEMPLATE_PATH . "/images/nophoto.jpg";
									endif;
									?>
									<img src="<?= $pre_photo ?>" alt="">
								</a>
							</figure>
							<div class="tour-text">
								<div class="tour-name">
									<a href="<? echo $arItem["DETAIL_PAGE_URL"]."?scroll-to-sp=Y&booking[id][]=".$arItem['ID']."&booking[date_from]=1608854400&booking[date_to]=1609804800&booking[adults]=2" ?>" title="<? echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?>"><? echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?></a>
								</div>
								<ul class="ship-port">
										<? if (!empty($arItem["PROPERTIES"]["ROUTE"]["VALUE"])): ?>
										<li>
											<span class="label"><a data-content="<?= GetMessage('TOWN') ?>" class="border_icon"><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon/route.png"></a> </span>
											<?= strip_tags($arItem["DISPLAY_PROPERTIES"]["ROUTE" . POSTFIX_PROPERTY]["DISPLAY_VALUE"]) ?>
										</li>
										<? elseif (!empty($arItem["PROPERTIES"]["TOWN"]["VALUE"])): ?>
										<li>
											<span class="label"><a data-content="<?= GetMessage('TOWN') ?>" class="border_icon"><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon/route.png"></a> </span>
											<?
											$p["TOWN"]["VALUE"] = (array) $p["TOWN"]["VALUE"];
											$db_res_towns = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $p["TOWN"]["LINK_IBLOCK_ID"], "ID" => $p["TOWN"]["VALUE"]), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY));
											$towns = null;
											while ($res = $db_res_towns->Fetch()) {
												$towns[] = $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] ? $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $res["NAME"];
											}
											if ($towns) {
												echo implode(" - ", $towns);
											}
											?>
										</li>
									<? endif; ?>
									<?
									$hotels = null;
									if ($p["HOTEL"]["VALUE"]) {
										$p["HOTEL"]["VALUE"] = (array) $p["HOTEL"]["VALUE"];
										$db_res_hotels = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $p["HOTEL"]["LINK_IBLOCK_ID"], "ID" => $p["HOTEL"]["VALUE"]), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY));
		
										while ($res = $db_res_hotels->Fetch()) {
											$hotels[] = $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] ? $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $res["NAME"];
										}
									}
									if ($hotels):
										?>
										<li>
											<span class="label"><a data-content="<?= GetMessage('HOTEL') ?>" class="border_icon"><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon/house-visiting.png"></a> </span> <?= implode(", ", $hotels) ?>
										</li>
									<? endif ?>
									<?
									$food = null;
									if ($p["FOOD"]["VALUE"]) {
										$p["FOOD"]["VALUE"] = (array) $p["FOOD"]["VALUE"];
										$db_res_food = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $p["FOOD"]["LINK_IBLOCK_ID"], "ID" => $p["FOOD"]["VALUE"]), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY));
										$food = null;
										while ($res = $db_res_food->Fetch()) {
											$food[] = $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] ? $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $res["NAME"];
										}
									}
									if ($food):
										?>
										<li>
											<span class="label"><a data-content="<?= GetMessage('FOOD') ?>" class="border_icon"><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon/dinner.png"></a></span> <?= implode(", ", $food) ?>
										</li>
									<? endif ?>
								<? if ($arItem["PROPERTIES"]["DAYS"]["VALUE"]>"1"): ?>
									<li>
										<span class="label"><a data-content="<?= GetMessage('DAYS') ?>" class="border_icon"><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon/numbered-list.png"></a> </span>
										<?= GetMessage('DAYS') ?> <?= $arItem["PROPERTIES"]["DAYS"]["VALUE"] ?>
									</li>
								<?elseif (!empty($arItem["PROPERTIES"]["DURATION_TIME"]["VALUE"])):?>
									<li>
										<span class="label"><a data-content="<?= GetMessage('DURATION_TIME') ?>" class="border_icon"><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon/numbered-list.png"></a> </span>
										<?= GetMessage('DURATION_TIME') ?> <?= $arItem["PROPERTIES"]["DURATION_TIME"]["VALUE"] ?>
									</li>
								<? endif; ?>
								<?
									$tourtype = null;
									if ($p["TOURTYPE"]["VALUE"]) {
										$p["TOURTYPE"]["VALUE"] = (array) $p["TOURTYPE"]["VALUE"];
										$db_res_tourtype = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $p["TOURTYPE"]["LINK_IBLOCK_ID"], "ID" => $p["TOURTYPE"]["VALUE"]), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY));
										$tourtype = null;
										while ($res = $db_res_tourtype->Fetch()) {
											$tourtype[] = $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] ? $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $res["NAME"];
										}
									}
									if ($tourtype):
										?>
									<li>
										<span class="label"><a data-content="<?= GetMessage('TYPE') ?>" class="border_icon"><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon/earth-pictures.png"></a> </span>
										<?= implode(", ", $tourtype) ?>
									</li>
								<? endif; ?>
								</ul>
								<!-- div class="tour-star-address">
									<span class="rating">
										Рейтинг <br>
										<ins>7.5</ins>
									</span>
								</div -->
								<p>
								<? if (!empty($arItem["PROPERTIES"]["PREVIEW_TEXT" . POSTFIX_PROPERTY]["VALUE"])): ?>
									<?= substr2($arItem["DISPLAY_PROPERTIES"]["PREVIEW_TEXT" . POSTFIX_PROPERTY]["DISPLAY_VALUE"], 150); ?>
								<? elseif (!empty($arItem["PROPERTIES"]["DETAIL_TEXT" . POSTFIX_PROPERTY]["VALUE"])): ?>
									<?= substr2($arItem["DISPLAY_PROPERTIES"]["DETAIL_TEXT" . POSTFIX_PROPERTY]["DISPLAY_VALUE"], 150); ?>
								<? else: ?>
									<?= substr2($arItem["DISPLAY_PROPERTIES"]["HD_DESC" . POSTFIX_PROPERTY]["DISPLAY_VALUE"], 150); ?>
								<? endif ?>                                            
								</p>
									<?
                            if ($result[$arItem["ID"]]):
                                $price = \travelsoft\Currency::getInstance()->convertCurrency(
                                        $result[$arItem["ID"]]["PRICE"], $result[$arItem["ID"]]['CURRENCY_ID']
                                );
                                ?>
                        <div class="price-box float-right" Style="cursor: pointer;">
                            <a href="<?= getCalculateDetailLink($arItem["DETAIL_PAGE_URL"], array('id' => array($arItem["ID"])))."&scroll-to-sp=Y&booking[date_from]=1608854400&booking[date_to]=1609804800&booking[adults]=2"?>"><?= GetMessage("price_night_title", array("#price#" => $price)); ?></a>
                            </div>
                            <? else: ?>
                            <div class="price-box float-right">
                                <a href="<? echo getCalculateDetailLink($arItem["DETAIL_PAGE_URL"], array('id' => array($arItem["ID"])))."&scroll-to-sp=Y&booking[date_from]=1608854400&booking[date_to]=1609804800&booking[adults]=2"?>" title=""><span class="detail"><?= GetMessage("MORE") ?></span></a>
                            </div>
                            <? endif ?>
							</div>
						</div>
		<? endforeach; ?>
		<div style="clear: both;"></div>
		   <?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
		   <br /><?=$arResult["NAV_STRING"]?>
		   <?endif;?>
		</div>
	</section>
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
