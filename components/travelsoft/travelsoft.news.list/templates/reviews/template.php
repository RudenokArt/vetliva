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
?>
<?
$this->addExternalJS("/local/templates/partners/js/plugins/visualization/echarts/echarts.js");
$this->addExternalJS("/local/templates/partners/js/plugins/visualization/d3/d3.min.js");
$this->addExternalJS("/local/templates/partners/js/plugins/visualization/d3/d3_tooltip.js");
$this->addExternalJS("/local/templates/partners/js/core/libraries/jquery.fancybox.js");
$this->addExternalCss("/local/templates/partners/css/jquery.fancybox.css");
$this->addExternalJS("/local/components/travelsoft/travelsoft.news.list/templates/reviews/main.js");
?>
<div class="row">
    <div class="col-lg-12">
        <?if(!empty($arResult["ITEMS"])):?>

                <?
                $params = array(
                    "rating" => array($arResult["RATING"] ? $arResult["RATING"] : 0, GetMessage('RATING')),
                    "cnt_item" => array(count($arResult["ITEMS"]) ? count($arResult["ITEMS"]) : 0, GetMessage('ALL_REVIEWS')),
                    "recommend" => array($arResult["RECOMMEND"] ? $arResult["RECOMMEND"] : 0, GetMessage('RECOMMEND')),
                    "price_quality" => array(!is_nan(round($arResult["PRICE_QUALITY"]["SUMM"]/$arResult["PRICE_QUALITY"]["COUNT"], 1)) ? round($arResult["PRICE_QUALITY"]["SUMM"]/$arResult["PRICE_QUALITY"]["COUNT"], 1) : 0, GetMessage('PRICE_QUALITY')),
                    "location" => array(!is_nan(round($arResult["LOCATION"]["SUMM"]/$arResult["LOCATION"]["COUNT"], 1)) ? round($arResult["LOCATION"]["SUMM"]/$arResult["LOCATION"]["COUNT"], 1) : 0, GetMessage('LOCATION')),
                    "staff" => array(!is_nan(round($arResult["STAFF"]["SUMM"]/$arResult["STAFF"]["COUNT"], 1)) ? round($arResult["STAFF"]["SUMM"]/$arResult["STAFF"]["COUNT"], 1) : 0, GetMessage('STAFF')),
                    "purity" => array(!is_nan(round($arResult["PURITY"]["SUMM"]/$arResult["PURITY"]["COUNT"], 1)) ? round($arResult["PURITY"]["SUMM"]/$arResult["PURITY"]["COUNT"], 1) : 0, GetMessage('PURITY')),
                    "rooms" => array(!is_nan(round($arResult["ROOMS"]["SUMM"]/$arResult["ROOMS"]["COUNT"], 1)) ? round($arResult["ROOMS"]["SUMM"]/$arResult["ROOMS"]["COUNT"], 1) : 0, GetMessage('ROOMS')),
                    "food" => array(!is_nan(round($arResult["FOOD"]["SUMM"]/$arResult["FOOD"]["COUNT"], 1)) ? round($arResult["FOOD"]["SUMM"]/$arResult["FOOD"]["COUNT"], 1) : 0, GetMessage('FOOD'))
                );?>
                <div id="params_block" data-params='<?=\Bitrix\Main\Web\Json::encode($params)?>'></div>
                <!-- Basic bar chart -->
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h6 class="panel-title"><?=$params["rating"][1]?></h6>
                        <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a data-action="collapse"></a></li>
                            </ul>
                        </div>
                    </div>
					 <div class="row">
            			<div class="col-md-9" style="margin-right: -15px;">
                    <div class="panel-body">
                        <div class="chart-container">
							<div class="chart has-fixed-height" id="basic_bars"></div>
                        </div>
                    </div>
</div>
            <div class="col-md-3" style="width: 20%;margin-top: 60px">
				<div class="panel-body" style="border-bottom: 1px dotted #999;">

					<div class="media-left media-middle">
						<div class="btn text-indigo-400 btn-flat btn-rounded btn-xs btn-icon"><i style="font-size: 35px;margin-top: -25px;" class="icon-stats-bars2"></i></div>
					</div>

					<div class="media-left">
						<h5 class="text-semibold no-margin" style="font-weight: 700;font-size: 35px;">
							<?=$params["rating"][0]?> <small style="font-size: 25px;" class="display-block no-margin"><?=$params["rating"][1]?></small>
						</h5>
					</div>

				</div>
				<div class="panel-body">

					<!-- Bars -->
					<!--<div id="goal-bars"></div>-->
					<!-- /bars -->

					<!-- Progress counter -->
					<div style="text-align: center;" class="content-group-sm svg-center position-relative" id="goal-progress"></div>
					<!-- /progress counter -->

				</div>
            </div>
        </div>
                </div>
                <!-- /basic bar chart -->

            <h4 class="review-h">
                <?=GetMessage('ALL_REVIEWS2')?> (<?=count($arResult["ITEMS"])?>)
            </h4>
            <!-- Review All -->
            <div class="tabbable">
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="activity">

                        <!-- Timeline -->
                        <div class="timeline timeline-left content-group">
                            <div class="timeline-container">
								<?$i = 1;?>
                                <?foreach ($arResult["ITEMS"] as $key=>$item):?>
                                    <?$flag = false;$file = '';$img = NO_PHOTO_PEOPLE_PATH;?>
                                    <!-- Review Item -->
                                    <div class="timeline-row">
                                        <div class="timeline-icon">
                                            <div>
                                                <?if(!empty($item["PROPERTIES"]["USER"]["VALUE"])):?>
                                                    <?$rsUser = CUser::GetByID($item["PROPERTIES"]["USER"]["VALUE"]);
                                                    $arUser = $rsUser->Fetch();
                                                    if(!empty($arUser["PERSONAL_PHOTO"])){
                                                        $file = CFile::ResizeImageGet($arUser["PERSONAL_PHOTO"], array('width'=>57, 'height'=>57), BX_RESIZE_IMAGE_EXACT, true);
                                                        $img = $file["src"];
                                                    }?>
                                                <?endif?>
                                                <a><img src="<?=$img?>" alt="<?=$item["PROPERTIES"]["USER_NAME"]["VALUE"]?>"></a>
                                            </div>
                                        </div>

                                        <div class="panel panel-flat timeline-content">
                                            <div class="panel-heading">
                                                <h6 class="panel-title"><?if(isset($arResult["ITEMS_NAME"]) && isset($arResult["ITEMS_NAME"][$item["PROPERTIES"]["ITEM"]["VALUE"]]) && !empty($arResult["ITEMS_NAME"][$item["PROPERTIES"]["ITEM"]["VALUE"]])):?><?=$arResult["ITEMS_NAME"][$item["PROPERTIES"]["ITEM"]["VALUE"]]?><?endif?><a class="heading-elements-toggle"><i class="icon-more"></i></a></h6>
                                            </div>

                                            <div class="panel-body">
                                                <?if($item["ACTIVE"] != "Y"):?>
                                                    <span style="float:right;font-size: 12px;text-decoration: underline;"><?=GetMessage('NOT_ACTIVE')?></span>
                                                    <div style="clear: both"></div>
                                                <?endif?>
                                                <?if(strlen($item["PROPERTIES"]["PLUS"]["VALUE"]) > 0 || strlen($item["PROPERTIES"]["MINUS"]["VALUE"]) > 0):?>
                                                    <h6 class="content-group">
                                                        <i class="icon-statistics position-left"></i>
                                                        <?=GetMessage('PLUS_MINUS_TITLE')?>:
                                                    </h6>
                                                    <div class="display-block content-group">
                                                        <ul class="icons-list">
                                                            <?if(strlen($item["PROPERTIES"]["PLUS"]["VALUE"]) > 0):?><li><span class="icon-add"></span><?=$item["PROPERTIES"]["PLUS"]["VALUE"]?></li><?endif?>
                                                            <?if(strlen($item["PROPERTIES"]["MINUS"]["VALUE"]) > 0):?><li><span class="icon-subtract"></span><?=$item["PROPERTIES"]["MINUS"]["VALUE"]?></li><?endif?>
                                                        </ul>
                                                    </div>
                                                <?endif?>
                                                <?if(!empty($arResult["RATING"])):?>
                                                    <h6 class="content-group">
                                                        <i class="icon-stats-bars2 position-left"></i>
                                                        <?=GetMessage('RATING')?> - <span class="rating"><?=$arResult["RATING"] ?></span>
                                                    </h6>
                                                <?endif?>
                                                <?if(!empty($item["PREVIEW_TEXT"])):?>
                                                    <h6 class="content-group">
                                                        <i class="icon-comment-discussion position-left"></i>
                                                        <?=GetMessage('REVIEW_COMMENT')?>:
                                                    </h6>
                                                    <blockquote class="reviews-content-text">
                                                        <p><?=$item["PREVIEW_TEXT"]?></p>
                                                        <footer><?=$item["PROPERTIES"]["USER_NAME"]["VALUE"]?>, <?=$item["DISPLAY_DATE_CREATE"]?></footer>
                                                    </blockquote>
                                                <?endif?>
                                                <div class="panel-footer panel-footer-transparent" style="padding-left: 15px;">
                                                    <div class="heading-elements-country">
                                                        <span class="heading-text"><i class="icon-pin-alt position-right text-muted"></i> <?if(strlen($item["PROPERTIES"]["COUNTRY"]["VALUE"]) > 0):?><?=$item["PROPERTIES"]["COUNTRY"]["VALUE"]?><?if(strlen($item["PROPERTIES"]["CITY"]["VALUE"]) > 0):?> ,<?$flag = true;?><?endif?><?endif?> <?if($flag):?><?=$item["PROPERTIES"]["CITY"]["VALUE"]?><?endif?></span>
                                                    </div>
                                                </div>
                                            </div>
											<?if(!empty($item["PROPERTIES"]["PHOTO"]["VALUE"])):?>
                                                <div class="panel-body">
                                                    <?$j = 1;?>
                                                    <?foreach ($item["PROPERTIES"]["PHOTO"]["VALUE"] as $img):?>
                                                        <?$file_real = CFile::GetPath($img);
                                                        $file_small = CFile::ResizeImageGet($img, Array('width' => 90, 'height' => 60), BX_RESIZE_IMAGE_EXACT, true);?>
                                                        <a data-fancybox="images-<?=$i?>" href="<?=$file_real?>"><img src="<?=$file_small["src"]?>"  alt="<?=$arResult["ITEMS_NAME"][$item["PROPERTIES"]["ITEM"]["VALUE"]]?>-<?=$j?>"/></a>
                                                        <?$j++;?>
                                                    <?endforeach;?>
                                                </div>
                                            <?endif?>
                                        </div>
                                    </div>
                                    <!-- End Review Item -->
									<?$i++;?>
                                <?endforeach;?>

                                <!-- End Review All -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?else:?>
                <div class="alert alert-danger mt-20" role="alert"><?=GetMessage("EMPTY_REVIEWS");?></div>
            <?endif?>

            <?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
                <br /><?=$arResult["NAV_STRING"]?>
            <?endif;?>
        </div>
    </div>
    <!--<script>
        $('.rev-block').each(function () {
            var hgth = $(this).find(".reviews-content-text").height();
            var hgth_p = $(this).find(".reviews-content-text p").height();
            if (hgth > hgth_p)
                $(this).find(".reviews-content-button").css('display', 'none');
        });
    
        $(".reviews-content-button").click(function () {
            $('.reviews-content-text').show(function () {
                var reducedHeight = $(this).height();
                $(this).css('height', 'auto');
                var fullHeight = $(this).height();
                $(this).height(reducedHeight);
                $(this).animate({height: fullHeight}, 500);
            });
            $(this).css('display', 'none');
        });
    </script>-->

