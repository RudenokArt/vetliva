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
$this->setFrameMode(true);

$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/webui-popover/jquery.webui-popover.min.css");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/webui-popover/jquery.webui-popover.min.js");

?>
<div class="col-md-9 col-md-pull-0 content-page-detail">
    <section class="tour-list">
		<?/* Sort by and View by -->
        <div class="sort-view clearfix">
            <!-- Sort by 
            <div class="sort-by float-left">
                <label>Сортировать: </label>
                <div class="sort-select select float-left">
                    <span data-placeholder="Select">По алфавиту</span>
                    <select name="start">
                        <option value="1">А - Я</option>
                        <option selected value="1">Я - А</option>
                    </select>
                </div>
                <div class="sort-select select float-left">
                    <span data-placeholder="Select">По рейтингу</span>
                    <select name="guest">
                        <option value="1">Сначала высокий</option>
                        <option selected value="1">Сначала низкий</option>
                    </select>
                </div>
                <div class="sort-select select float-left">
                    <span data-placeholder="Select">По цене</span>
                    <select name="pricing">
                        <option value="1">Сначала дешевле</option>
                        <option selected value="1">Сначала дороже</option>
                    </select>
                </div>
            </div>
             End Sort by -->
            <!-- View by 
            <div class="view-by float-right">
                <ul>
                                                                <li><a href="#list" title="" class="current"><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon-list.png" alt=""></a></li>
                                                                <li><a href="#map" title=""><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon-map.png" alt=""></a></li>
                </ul>
            </div>
             View by -->
        </div>
<!-- End Sort by and View by */?>

        <div class="tour-list-cn clearfix">
            <? if ($arParams["DISPLAY_TOP_PAGER"]): ?>
                <?= $arResult["NAV_STRING"] ?><br />
            <? endif; ?>
            <? foreach ($arResult["ITEMS"] as $arItem): ?>
                <?
                $p = $arItem["DISPLAY_PROPERTIES"];
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                ?>
                <div class="tour-list-item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                    <figure class="tour-img float-left">
                        <a href="<? echo $arItem["DETAIL_PAGE_URL"] ?>" title="">
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
                            <a href="<? echo $arItem["DETAIL_PAGE_URL"] ?>" title="<? echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?>"><? echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?></a>
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
						<?/* div class="tour-star-address">
                            <span class="rating">
                                Рейтинг <br>
                                <ins>7.5</ins>
                            </span>
						</div */?>
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
                            $arParams["__BOOKING_REQUEST"]["id"] = array($arItem["ID"]);
                            if ($arParams["CALCULATION_PRICE_RESULT"][$arItem["ID"]]):
                                $price = \travelsoft\Currency::getInstance()->convertCurrency(
                                        $arParams["CALCULATION_PRICE_RESULT"][$arItem["ID"]]["MIN_PRICE"], $arParams["CALCULATION_PRICE_RESULT"][$arItem["ID"]]['CURRENCY_ID']
                                );
                                ?>
                        <a href="<?= getCalculateDetailLink($arItem["DETAIL_PAGE_URL"], $arParams["__BOOKING_REQUEST"])?>">
                        <div class="price-box float-right" Style="cursor: pointer;">
                          <?= GetMessage("price_night_title", array("#price#" => $price)); ?>
                            </div>
                            <? else: ?>
                                </a>
                        <a href="<? echo getCalculateDetailLink($arItem["DETAIL_PAGE_URL"], $arParams["__BOOKING_REQUEST"])?>" title="">
                            <div class="price-box float-right">
                               <span class="detail"><?= GetMessage("MORE") ?></span>
                            </div>
                        </a>
                            <? endif ?>
						<?/*
						<div class="tour-service float-left">
                            <? if (!empty($arItem["DISPLAY_PROPERTIES"]["SERVICES"]["VALUE"])): ?>
        <? $count = 0; ?>
        <? foreach ($arItem["DISPLAY_PROPERTIES"]["SERVICES"]["VALUE"] as $k => $value): ?>
                        <? if (!empty($arResult["SERVICES_ICON"][$value]["ICON"]) && $count <= 6): ?>
                                        <a data-content="<?= $arResult["SERVICES_ICON"][$value]["TITLE"] ?>" class="border_icon <?= $arResult["SERVICES_ICON"][$value]["ICON"] ?>"></a>
                        <? endif ?>
                        <? $count++ ?>
                    <? endforeach; ?>
    <? endif; ?>
</div> */?>
                    </div>
                </div>
<? endforeach; ?>
<? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
                <br /><?= $arResult["NAV_STRING"] ?>
<? endif; ?>
        </div>
    </section>
</div>
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
<?
//count elements tags
$this->SetViewTarget("cnt__elements");
?>
<div class="search-result">
    <p><?= GetMessage('FOUND') ?> <ins id="searching__cnt__elements"><?= $arResult['NAV_RESULT']->NavRecordCount ?></ins></p>
</div>
<? $this->EndViewTarget() ?>
<span id="cnt__elements"><?= $arResult['NAV_RESULT']->NavRecordCount ?></span>
