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
                                <?foreach ($arResult["ITEMS"] as $key=>$item): ?>
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
                                                        <span class="heading-text"><i class="icon-pin-alt position-right text-muted"></i> <?if(strlen($item["PROPERTIES"]["COUNTRY"]["VALUE"]) > 0):?><?=$item["PROPERTIES"]["COUNTRY"]["VALUE"]?><?endif?></span>
                                                    </div>
                                                </div>
                                                    <?if ($item['PROPERTIES']['ANSWER']['VALUE']['TEXT']):?>
                                                    <h6 class="content-group">
                                                        <i class="icon-comment-discussion position-left"></i>
                                                        Ответ:
                                                    </h6>
                                                    <blockquote class="reviews-content-text">
                                                        <p><?= $item['PROPERTIES']['ANSWER']['VALUE']['TEXT']?></p>
                                                        
                                                    </blockquote>
                                                    <?endif?>
                                                
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
                                            <?if (!trim($item['PROPERTIES']['ANSWER']['VALUE']['TEXT'])):?>
                                            <section class="detail-footer detail-cn">
                                                
                                                    <?$APPLICATION->IncludeComponent('travelsoft:partners.review-asnwer', 'vetliva', [
                                                        'REVIEW_ID' => $item['ID'],
                                                        'ANSWER_IBPROPERTY_CODE' => "ANSWER"
                                                    ])?>
                                              
                                            </section>
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

