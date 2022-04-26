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

use \Bitrix\Main\Localization\Loc;
$is_mobile = check_smartphone(); 
if(empty($arResult["ITEMS"])):
    ?>
    <div class="col-md-9 col-md-pull-0 content-page-detail">
        <div class="alert-box alert-attention"><?=GetMessage("TEXT_NOT_FOUND", array("#LINK#" => $APPLICATION->GetCurDir()))?></div>
    </div>
    <?
    if ($is_mobile):
    $this->SetViewTarget("cnt__elements");
    ?>
    <div class="search-result">
        <div class="mobile-filtr-sort-block">
            <div class="filter-block">
                <a class="magnificbutton show-filter-link" href="#filter-area"><?= GetMessage('FILTRES') ?></a>
            </div>
            <div class="searcbyname-block">
                <a href="javascript:void(0)" onclick="$('.searchbyname-block-content').toggle();" class="show-search-link"><i class="fa fa-search" aria-hidden="true"></i></a>
            </div>
        </div>
    </div>
    <? $this->EndViewTarget();
    endif;
    return;
endif;

$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/webui-popover/jquery.webui-popover.min.css");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/webui-popover/jquery.webui-popover.min.js");
?>
<div class="col-md-9 col-md-pull-0 content-page-detail">
    <h1><?= $APPLICATION->GetTitle() ?></h1>
    <section class="tour-list">
        <?if ($arParams["SORT_PARAMETERS"]) :?>
            <?php
            $arUnsetSort = ['price', 'sort'];
            foreach($arParams["SORT_PARAMETERS"] as $key => $arItem){
                if(in_array($arItem['name'], $arUnsetSort)){
                    unset($arParams["SORT_PARAMETERS"][$key]);
                }
            }
            if(!empty($arParams["SORT_PARAMETERS"])):
            ?>
                <!-- Sort by and View by -->
                <div <?if ($is_mobile):?> class="header-auth-form sort-view-mobile mfp-hide clearfix" id="sort-area"<?else:?>class="sort-view clearfix"<?endif;?>>
                    <div class="sort-by float-left">
                    <?if ($is_mobile):?>
                        <? foreach ($arParams["SORT_PARAMETERS"] as $arp): ?>
                            <div class="sort-select select float-left">
                                <?
                                $arrow = "<i class=\"fa fa-long-arrow-up\" aria-hidden=\"true\"></i> <i class=\"fa fa-long-arrow-down\" aria-hidden=\"true\"></i>";
                                if ($arp["selected"]) {
                                    $arrow = $arp["order"] == "asc" ? "<i class=\"fa fa-long-arrow-up\" aria-hidden=\"true\"></i>" : "<i class=\"fa fa-long-arrow-down\" aria-hidden=\"true\"></i>";
                                }
                                ?>
                                <input id="sort<?=$arp["name"]?>" type="radio" <?if ($_REQUEST['sort_by']==$arp["name"] || ($_REQUEST['sort_by']=='' && $arp["name"]=='rating')):?>checked=""<?endif;?> onclick="location.href='<?= $APPLICATION->GetCurPageParam("sort_by=" . $arp["name"] . "&" . "order=" . $arp["order"], array("sort_by", "order"), false) ?>'"/>
                                <label for="sort<?=$arp["name"]?>"><?= GetMessage($arp["name"]) ?></label>
                            </div>
                        <? endforeach ?>
                      <?else:?>                    
                        <label><?= GetMessage("SORT_TITLE")?>: </label>
                        <?foreach ($arParams["SORT_PARAMETERS"] as $arp):?>
                            <div class="sort-select select float-left">
                                <?
                                $arrow = "<i class=\"fa fa-long-arrow-up\" aria-hidden=\"true\"></i> <i class=\"fa fa-long-arrow-down\" aria-hidden=\"true\"></i>";
                                if ($arp["selected"]) {
                                    $arrow = $arp["order"] == "asc" ? "<i class=\"fa fa-long-arrow-up\" aria-hidden=\"true\"></i>" : "<i class=\"fa fa-long-arrow-down\" aria-hidden=\"true\"></i>";
                                }
                                ?>
                                <a class="sorting" rel="nofollow" href="<?= $APPLICATION->GetCurPageParam("sort_by=" . $arp["name"] . "&" . "order=" . $arp["order"], array("sort_by", "order"), false)?>"><?= GetMessage($arp["name"])?></a> <?= $arrow?>
                            </div>
                        <?endforeach?>
                      <?endif;?>                        
                    </div>
                </div>
            <?php endif?>
        <?endif?>

        <div class="tour-list-cn clearfix">
            <? if ($arParams["DISPLAY_TOP_PAGER"]): ?>
                <?= $arResult["NAV_STRING"] ?><br />
            <? endif; ?>
            <? foreach ($arResult["ITEMS"] as $arItem): ?>
                <?
                $p = $arItem["DISPLAY_PROPERTIES"];
                $arParams["__BOOKING_REQUEST"]["id"] = array($arItem["ID"]);
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                ?>
                <div class="tour-list-item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                    <figure class="tour-img float-left">
                        <a href="<? echo getCalculateDetailLink($arItem["DETAIL_PAGE_URL"], $arParams["__BOOKING_REQUEST"])?>" title="">
                            <?
                            if (!empty($arItem["PREVIEW_PICTURE"])):
                                $an_file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width' => 225, 'height' => 150), BX_RESIZE_IMAGE_EXACT, true);
                                $pre_photo = $an_file["src"];
							elseif (!empty($arItem["PROPERTIES"]["PICTURES". POSTFIX_PROPERTY]["VALUE"])):
                                $an_file = CFile::ResizeImageGet($arItem["PROPERTIES"]["PICTURES". POSTFIX_PROPERTY]["VALUE"][0], array('width' => 225, 'height' => 150), BX_RESIZE_IMAGE_EXACT, true);
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
                            <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" title="<? echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?>"><? echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?></a>
                        </div>

                        <ul class="ship-port ship-port_new">

                            <?php if (!empty($arItem['DISPLAY_PROPERTIES']['RESIDENCE']['DISPLAY_VALUE'])):?>

                                <li>
                                    <span class="label">
                                        <a data-content="<?=Loc::getMessage('T_GUIDES_LIST_RESIDENCE')?>" class="border_icon">
                                            <img src="<?= SITE_TEMPLATE_PATH ?>/images/icon/route.png">
                                        </a>
                                    </span>
                                    <?=Loc::getMessage('T_GUIDES_LIST_RESIDENCE')?> <?=$arItem['DISPLAY_PROPERTIES']['RESIDENCE']['DISPLAY_VALUE'][0]?>
                                </li>

                            <?php endif ?>

                            <?php if($arItem['DISPLAY_PROPERTIES']['TRANSPORT']['VALUE']):?>
                                <li>
                                    <span class="label">
                                        <a data-content="<?=Loc::getMessage('T_GUIDES_LIST_TRANSPORT')?>" class="border_icon">
                                            <img src="<?= SITE_TEMPLATE_PATH ?>/images/icon-service-3.png">
                                        </a>
                                    </span>
                                    <?=Loc::getMessage('T_GUIDES_LIST_TRANSPORT')?>
                                </li>
                            <?php endif ?>

                            <?php if (!empty($arItem['DISPLAY_PROPERTIES']['TOUR_LANGUAGE']['DISPLAY_VALUE'])):?>

                                <li>
                                    <span class="label">
                                        <a data-content="<?=Loc::getMessage('T_GUIDES_LIST_TOUR_LANGUAGE')?>" class="border_icon">
                                            <img width="18px" src="<?= SITE_TEMPLATE_PATH ?>/images/icon/language.png">
                                        </a>
                                    </span>
                                    <?=Loc::getMessage('T_GUIDES_LIST_TOUR_LANGUAGE')?> <?=strtolower(implode(', ', $arItem['DISPLAY_PROPERTIES']['TOUR_LANGUAGE']['DISPLAY_VALUE']))?>
                                </li>

                            <?php endif ?>

                            <?php if (!empty($arItem['DISPLAY_PROPERTIES']['TOUR_TYPE']['DISPLAY_VALUE'])):?>

                                <li>
                                    <span class="label">
                                        <a data-content="<?=Loc::getMessage('T_GUIDES_LIST_TOUR_TYPE')?>" class="border_icon">
                                            <img width="18px" src="<?= SITE_TEMPLATE_PATH ?>/images/icon/map.png">
                                        </a>
                                    </span>
                                    <?=Loc::getMessage('T_GUIDES_LIST_TOUR_TYPE')?> <?=strtolower(implode(', ', $arItem['DISPLAY_PROPERTIES']['TOUR_TYPE']['DISPLAY_VALUE']))?>
                                </li>

                            <?php endif ?>

                        </ul>
                        <p><?=$arItem['DISPLAY_PROPERTIES']['ABOUT_SELF' . POSTFIX_PROPERTY]['DISPLAY_VALUE']?></p>

                        <div class="btns-wrp">

                            <?php if(!empty($arItem['PROPERTIES']['PHONE']['VALUE'])):?>
                                <a href="javascript:;" title="" data-src="#guide-contacts-<?=$arItem['ID']?>" class="m-popup btn-blue">
                                    <span class="detail"><?=Loc::getMessage('T_GUIDES_LIST_SHOW_CONTACTS')?></span>
                                </a>
                                <div id="guide-contacts-<?=$arItem['ID']?>" class="add-review-form mfp-hide">
                                    <div class="row form bg-none">
                                        <div class="col-md-12">
                                            <div class="popup-inner">
												<div class="popup-ttl"><?=Loc::getMessage('T_GUIDES_LIST_CONTACTS_TITLE')?></div>
												<div class="popup-phones">
                                                <?php foreach($arItem['PROPERTIES']['PHONE']['VALUE'] as $value):?>
                                                    <a href="tel:+<?=\Kosmos\Main\Helpers\Common::getPhone($value)?>" title="<?=$value?>" target="_blank" rel="nofollow"><?=$value?></a>
													<br>
                                                <?php endforeach?>
												</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif?>
                            <a class="btn-blue btn-blue_arrow" href="<?=$arItem["DETAIL_PAGE_URL"]?>" title="">
                                <span class="detail"><?= GetMessage("MORE") ?></span>
                            </a>
                        </div>

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
    }
    initPopover();
  })();
</script>
<?
//count elements tags
$this->SetViewTarget("cnt__elements");
?>
<div class="search-result">
    <?if ($is_mobile):?>
    <div class="mobile-filtr-sort-block">
    <div class="filter-block">
        <a class="magnificbutton show-filter-link" href="#filter-area"><?= GetMessage('FILTRES') ?></a>
    </div>
    <?if (!empty($arResult["ITEMS"])):?>
    <div class="sort-block">
        <a href="#sort-area" class="magnificbutton show-sort-link"><?if (!empty($_REQUEST['sort_by'])):?><?= GetMessage($_REQUEST['sort_by']) ?><?else:?><?= GetMessage('rating') ?><?endif;?></a>
    </div>
    <?endif;?>
    <div class="searcbyname-block">
        <a href="javascript:void(0)" onclick="$('.searchbyname-block-content').toggle();" class="show-search-link"><i class="fa fa-search" aria-hidden="true"></i></a>
    </div>
    </div>
    <?endif;?>
    <?if (!$is_mobile):?>
    <p><?= GetMessage('FOUND') ?> <ins id="searching__cnt__elements"><?= $arResult['NAV_RESULT']->NavRecordCount ?></ins></p>
    <?endif;?>
</div>
<? $this->EndViewTarget() ?>
<?if ($is_mobile) {
    $this->SetViewTarget("cnt__elements_header");?>
    (<ins id="searching__cnt__elements"><?=$arResult['NAV_RESULT']->NavRecordCount?></ins>)
    <?$this->EndViewTarget();
}?>
<span id="cnt__elements"><?= $arResult['NAV_RESULT']->NavRecordCount ?></span>
