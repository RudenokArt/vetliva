<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
    use Bitrix\Main\Application;
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
use \Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);
$obRequest = Application::getInstance()->getContext()->getRequest();
$requestsort = $obRequest->get("sort_by"); 
$is_mobile = check_smartphone();
if (empty($arResult["ITEMS"])):
    ?>
    <div class="col-md-9 col-md-pull-0 content-page-detail">
        <div class="alert-box alert-attention"><?= GetMessage("TEXT_NOT_FOUND", array("#LINK#" => $APPLICATION->GetCurDir())) ?></div>
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

// Подготовка информации для времени работы клиники
$today = date("D");
$arDayMarks = array("Mon" => "",
				"Tue" => "",
				"Wed" => "",
				"Thu" => "",
				"Fri" => "",
				"Sat" => "",
				"Sun" => "");
$arDayMarks[$today] = "<i class=\"fa fa-circle\" aria-hidden=\"true\" style=\"color:#feb818\"></i>";
$arDayTimes = array();
$dayOff = GetMessage("DAY_OFF");

$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/webui-popover/jquery.webui-popover.min.css");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/webui-popover/jquery.webui-popover.min.js");
?>
<div class="col-md-9 col-md-pull-0 content-page-detail">
    <div class="wrp-ttl">
        <h1><?= $APPLICATION->GetTitle() ?></h1>
        <a target="_blank" class="btn-lnk-map" href="<?=SITE_DIR?>belarus/map/?TYPE[]=<?=$arParams['IBLOCK_ID']?>" title="<?=Loc::getMessage('T_ITEMS_LIST_MAP_LINK')?>"><?=Loc::getMessage('T_ITEMS_LIST_MAP_LINK')?></a>
    </div>
    <section class="hotel-list">
        <? if ($arParams["SORT_PARAMETERS"]) : 
            $sortnames = array();
            foreach ($arParams["SORT_PARAMETERS"] as $arp) $sortnames[] = $arp['name'];
        ?>
            <!-- Sort by and View by -->
            <div <?if ($is_mobile):?> class="header-auth-form sort-view-mobile mfp-hide clearfix" id="sort-area"<?else:?>class="sort-view clearfix"<?endif;?>>

                <div class="sort-by float-left">
                <?if ($is_mobile):?>
                <? foreach ($arParams["SORT_PARAMETERS"] as $arp): ?>
                    <?if (in_array($arp["name"], array('price', 'name'))):?>
                    <div class="sort-select select float-left">
                        <a id="link_sort<?=$arp["name"]?>asc" class="sorting" href="<?= $APPLICATION->GetCurPageParam("sort_by=" . $arp["name"] . "&" . "order=asc", array("sort_by", "order"), false) ?>"></a>    
                        <input data-name="<?= GetMessage($arp["name"]) ?>" name="sortfield"  id="sort<?=$arp["name"]?>asc" type="radio" <?if (($_REQUEST['sort_by']==$arp["name"] && $_REQUEST["order"]=='asc') || ($_REQUEST['sort_by']=='' && $arp["name"]=='sort')):?>checked=""<?endif;?>>
                        <label for="sort<?=$arp["name"]?>asc"><?= GetMessage($arp["name"]) ?> <?= GetMessage($arp["name"].'_asc') ?></label>
                    </div>
                    <div class="sort-select select float-left">  
                        <a id="link_sort<?=$arp["name"]?>desc" class="sorting" href="<?= $APPLICATION->GetCurPageParam("sort_by=" . $arp["name"] . "&" . "order=desc", array("sort_by", "order"), false) ?>"></a>
                        <input data-name="<?= GetMessage($arp["name"]) ?>" name="sortfield" id="sort<?=$arp["name"]?>desc" type="radio" <?if (($_REQUEST['sort_by']==$arp["name"] && $_REQUEST["order"]=='desc') || ($_REQUEST['sort_by']=='' && $arp["name"]=='sort')):?>checked=""<?endif;?>>
                        <label for="sort<?=$arp["name"]?>desc"><?= GetMessage($arp["name"]) ?> <?= GetMessage($arp["name"].'_desc') ?></label>
                    </div>
                    <?else:?>
                    <div class="sort-select select float-left">
                        <a id="link_sort<?=$arp["name"]?>" class="sorting" href="<?= $APPLICATION->GetCurPageParam("sort_by=" . $arp["name"] . "&" . "order=" . $arp["order"], array("sort_by", "order"), false) ?>"></a>
                        <input data-name="<?= GetMessage($arp["name"]) ?>" name="sortfield" id="sort<?=$arp["name"]?>" type="radio" <?if ($_REQUEST['sort_by']==$arp["name"] || ($_REQUEST['sort_by']=='' && $arp["name"]=='sort')):?>checked=""<?endif;?>>
                        <label for="sort<?=$arp["name"]?>"><?= GetMessage($arp["name"]) ?></label>
                    </div>
                    <?endif;?>
                <? endforeach ?>
                <?else:?>
                    <label><?= GetMessage("SORT_TITLE") ?>: </label>
                    <? foreach ($arParams["SORT_PARAMETERS"] as $arp): ?>
                        <div class="sort-select select float-left">
                            <?
                            $arrow = "<i class=\"fa fa-long-arrow-up\" aria-hidden=\"true\"></i> <i class=\"fa fa-long-arrow-down\" aria-hidden=\"true\"></i>";
                            if ($arp["selected"]) {
                                $arrow = $arp["order"] == "asc" ? "<i class=\"fa fa-long-arrow-up\" aria-hidden=\"true\"></i>" : "<i class=\"fa fa-long-arrow-down\" aria-hidden=\"true\"></i>";
                            }
                            ?>
                            <a class="sorting" rel="nofollow" href="<?= $APPLICATION->GetCurPageParam("sort_by=" . $arp["name"] . "&" . "order=" . $arp["order"], array("sort_by", "order"), false) ?>"><?= GetMessage($arp["name"]) ?></a> <?= $arrow ?>
                        </div>
                    <? endforeach ?>
                <?endif;?>
                </div>
            </div>
        <? endif ?>
        <!-- End Sort by and View by -->
        <div class="hotel-list-cn clearfix">
            <? if ($arParams["DISPLAY_TOP_PAGER"]): ?>
                <?= $arResult["NAV_STRING"] ?><br />
            <? endif; ?>
            <? foreach ($arResult["ITEMS"] as $arItem): ?>
                <?
                $_request_string = $arItem["DETAIL_PAGE_URL"];
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                ?>
                <div class="hotel-list-item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>" itemscope itemtype="http://schema.org/Place">
                    <figure class="hotel-img float-left">
                        <?
                        $pre_photo=array();

                        if (!empty($arItem["PREVIEW_PICTURE"])):
                            $an_file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width' => 410, 'height' => 250), BX_RESIZE_IMAGE_EXACT, true);
                            $pre_photo[] = $an_file["src"];
                        endif;
                        if (!empty($arItem["PROPERTIES"]["PICTURES"]["VALUE"])):
                            $countfile = 0;
                            foreach ($arItem["PROPERTIES"]["PICTURES"]["VALUE"] as $idfile) {
                                if ($countfile>4) continue;
                                $an_file = CFile::ResizeImageGet($idfile, array('width' => 410, 'height' => 250), BX_RESIZE_IMAGE_EXACT, true);
                                $pre_photo[] = $an_file["src"];
                                $countfile++;
                            }
                        endif;
                        if (count($pre_photo)==0) $pre_photo[] = SITE_TEMPLATE_PATH . "/images/nophoto.jpg";
                        ?>
                        <?if (count($pre_photo)>1): $limit = (count($pre_photo)>5)? 5 : count($pre_photo);?>
                            <div class="banners-slider-list">
                                <?for ($i=0; $i<$limit; $i++):?>
                                    <a itemprop="url" href="<?=$_request_string?>" title="" target="_blank">
                                    <img src="<?=$pre_photo[$i]?>" alt=""/>
                                    </a>
                                <?endfor;?>
                            </div>
                         <?else:?>
                            <a itemprop="url" href="<?=$_request_string?>" title="" target="_blank">
                                <img src="<?=$pre_photo[0]?>" alt=""/>
                            </a> 
                         <?endif;?>
                    </figure>
                    <div class="hotel-text">
                        <div class="hotel-name" itemprop="name">
                            <a itemprop="url" href="<?=$_request_string?>" title="<? echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?>" target="_blank"><? echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?></a>
                            <div class="list-favorite-button">
                                <?$APPLICATION->IncludeComponent(
                                    	"travelsoft:favorites.add",
                                    	"",
                                    	Array(
                                            "SHORT_DISPLAY"=>"Y",
                                    		"OBJECT_ID" => $arItem["ID"],
                                    		"OBJECT_TYPE" => "IBLOCK_ELEMENT",
                                            "STORE_ID" => $arParams["IBLOCK_ID"]
                                    	)
                                    );
                                ?>
                            </div>
                        </div>
                        <address class="hotel-address" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                            <? if (!empty($arItem["PROPERTIES"]["ADDRESS" . POSTFIX_PROPERTY]["VALUE"])):
								$adress = ''; ?>
                                <i class="fa fa-map-marker"></i>
								<?  $adress = substr2($arItem["DISPLAY_PROPERTIES"]["ADDRESS" . POSTFIX_PROPERTY]["DISPLAY_VALUE"], 200);
									$adress = "<span itemprop=\"streetAddress\">" . $adress . "</span>"; ?>
                                <?
                                if (!empty($arItem["PROPERTIES"]["TOWN"]["VALUE"])) {
                                    $town = strip_tags($arItem["DISPLAY_PROPERTIES"]["TOWN"]["DISPLAY_VALUE"]);
                                    if (LANGUAGE_ID != "ru") {
                                        $prop = getIBElementProperties($arItem["PROPERTIES"]["TOWN"]["VALUE"]);
                                        $town = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
                                    }
                                    if (!empty($town)) {
                                        $town = "<span itemprop=\"addressLocality\">" . $town . "</span>";
                                    }
                                }
								if (!empty($arItem["PROPERTIES"]["REGION"]["VALUE"])) {
                                    $obl = strip_tags($arItem["DISPLAY_PROPERTIES"]["REGION"]["DISPLAY_VALUE"]);
                                    if (LANGUAGE_ID != "ru") {
                                        $prop = getIBElementProperties($arItem["PROPERTIES"]["REGION"]["VALUE"]);
                                        $obl = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
                                    }
                                    if (!empty($obl)) {
                                        $obl = "<span itemprop=\"addressLocality\">" . $obl . "</span>";
                                    }
                                }
                                ?>
                                <? if ($town): ?><?
                                    $adress .= ", " . $town;
                                    unset($town);
                                    ?><? if ($region): ?><? $adress .= ", "; ?><? endif; ?><? endif; ?>
                                <? if ($region): ?><? $adress .= $region; ?><? endif; ?>
								<? if ($obl): ?><? $adress .= ", " . $obl;?><? endif; ?>
                                <? if ($country): ?><? $adress .= ", " . $country; ?><? endif; ?>
                                <? echo $adress; unset($obl);?>
                            <? endif; ?>
                            <?if($arItem['PROPERTIES']['MAP']['VALUE']):?>
                                <div class="show-map__wrapper">
                                    <a
                                            href="javascript:;"
                                            title="<?= GetMessage('T_PLACEMENT_LIST_SHOW_MAP') ?>"
                                            class="show-map"
                                            data-id="<?=$arItem['ID']?>"
											data-filter='<?= \Bitrix\Main\Web\Json::encode($GLOBALS[$arParams['FILTER_NAME']]) ?>'
                                    ><?= GetMessage('T_PLACEMENT_LIST_SHOW_MAP') ?></a>
                                </div>
                            <?endif?>
                        </address>
                        				<ul class="ship-port">
                                        <? if (!empty($arItem["PROPERTIES"]["DISTANCE_MINSK"]["VALUE"])): ?>
                                            <li itemprop="description">
                                                <i class="fa fa-info-circle blue"></i> <?= GetMessage('DISTANCE_MINSK') ?>: <?= substr2($arItem["PROPERTIES"]["DISTANCE_MINSK"]["VALUE"], 100); ?> km
                                            </li>
                                        <? endif ?>

										<?
										$arDayTimes["Mon"] = $arItem["PROPERTIES"]["Mon"]["VALUE"];
										$arDayTimes["Tue"] = $arItem["PROPERTIES"]["Tue"]["VALUE"];
										$arDayTimes["Wed"] = $arItem["PROPERTIES"]["Wed"]["VALUE"];
										$arDayTimes["Thu"] = $arItem["PROPERTIES"]["Thu"]["VALUE"];
										$arDayTimes["Fri"] = $arItem["PROPERTIES"]["Fri"]["VALUE"];
										$arDayTimes["Sat"] = $arItem["PROPERTIES"]["Sat"]["VALUE"];
										$arDayTimes["Sun"] = $arItem["PROPERTIES"]["Sun"]["VALUE"];

										foreach($arDayTimes as $day => $time)
										{
											if(trim($time) == "-" || empty($time))
												$arDayTimes[$day] = $dayOff;
										}

										$today_schedule = $arDayTimes[$today];
										$phones = "";
										foreach($arItem["PROPERTIES"]["PHONE"]["VALUE"] as $phone)
										{
											$phones = $phones . $phone . '_';
										}

										 if (!empty($today_schedule)): ?>
                                            <a class="getSchedule" href=""
												title="<?=LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"]?>"
												monday="<?=$arDayTimes["Mon"] ?>"
												tuesday="<?=$arDayTimes["Tue"] ?>"
												wednesday="<?=$arDayTimes["Wed"] ?>"
												thursday="<?=$arDayTimes["Thu"] ?>"
												friday="<?=$arDayTimes["Fri"] ?>"
												saterday="<?=$arDayTimes["Sat"] ?>"
												sunday="<?=$arDayTimes["Sun"] ?>"
												phones="<?=$phones ?>">
												<li itemprop="description">
                                                	<i class="fa fa-calendar blue"></i> <?=$today_schedule; ?>
												</li>
											</a>
										<? endif; ?>
                          				</ul>
											<br>
											<a class="getSchedule" href=""
												title="<?=LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"]?>"
												monday="<?=$arDayTimes["Mon"] ?>"
												tuesday="<?=$arDayTimes["Tue"] ?>"
												wednesday="<?=$arDayTimes["Wed"] ?>"
												thursday="<?=$arDayTimes["Thu"] ?>"
												friday="<?=$arDayTimes["Fri"] ?>"
												saterday="<?=$arDayTimes["Sat"] ?>"
												sunday="<?=$arDayTimes["Sun"] ?>"
												phones="<?=$phones ?>">
                                                <div class="price-box float-left"><span class="detail"><?=GetMessage("CONTACT")?></span></div>
                                            </a>
                                            <a itemprop="url" href="<?=$_request_string?>" title="" target="_blank">
                                                <div class="price-box float-right"><span class="detail"><?=GetMessage("MORE")?></span></div>
                                            </a>

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
                                                $('.hotel-service a').webuiPopover({
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
                                            <a href="#sort-area" class="magnificbutton show-sort-link"><?if ($requestsort!='' && in_array($requestsort, $sortnames)): $sort_name =$requestsort; ?><?= GetMessage($sort_name) ?><?else:?><?= GetMessage('sort') ?><?endif;?></a>
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

<!-- Модальное окно для времени работы клиники -->
	<div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"aria-hidden="true">
	<div class="modal-dialog" role="document" style="left:8%;top:15%;transform:translate(0,10%);">
    <div class="modal-content col-md-7">
		<div class="modal-header" style="text-align:center;display:flex;align-items:flex-start;">
		 	<h3 id="scheduleTitle" class="modal-title" id="modalLabel" style="width:100%;"></h3>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span style="font-size:20pt;" aria-hidden="true">&times;</span>
        	</button>
      </div>
	  <center>
      <div class="modal-body">
		<div id="mainContetnt" class="container-fluid text-for-popup">
			<div class="row">
				<div class="mark-div"><?=$arDayMarks["Mon"]; ?></div>
				<div class="col1-div"><?=getMessage("Mon"); ?></div>
			 	<div id = "Mon" class="col2-div"></div>
			</div>
			<div class="row">
			  <div class="mark-div"><?=$arDayMarks["Tue"]; ?></div>
			  <div class="col1-div"><?=getMessage("Tue"); ?></div>
			  <div id = "Tue" class="col2-div"></div>
			</div>
			<div class="row">
			  <div class="mark-div"><?=$arDayMarks["Wed"]; ?></div>
			  <div class="col1-div"><?=getMessage("Wed"); ?></div>
			  <div id = "Wed" class="col2-div"></div>
			</div>
			<div class="row">
			  <div class="mark-div"><?=$arDayMarks["Thu"]; ?></div>
			  <div class="col1-div"><?=getMessage("Thu"); ?></div>
			  <div id = "Thu" class="col2-div"></div>
			</div>
			<div class="row">
			  <div class="mark-div"><?=$arDayMarks["Fri"]; ?></div>
			  <div class="col1-div"><?=getMessage("Fri"); ?></div>
			  <div id = "Fri" class="col2-div"></div>
			</div>
			<div class="row">
			  <div class="mark-div"><?=$arDayMarks["Sat"]; ?></div>
			  <div class="col1-div"><?=getMessage("Sat"); ?></div>
			  <div id = "Sat" class="col2-div"></div>
			</div>
			<div class="row">
			  <div class="mark-div"><?=$arDayMarks["Sun"]; ?></div>
			  <div class="col1-div"><?=getMessage("Sun"); ?></div>
			  <div id = "Sun" class="col2-div"></div>
			</div>
	  	</div>
      </div>
	  </center>
		<div class="modal-footer" style="text-align:center">
        	<h3 id="scheduleTitle" class="modal-title" id="modalLabel" ><?=getMessage("CONTACT")?></h3><br>
			<div id="contacts" class="container-fluid" style="font-size:13pt">
			</div>
      </div>
    </div>
  </div>
</div>

<script>
$( document ).ready(function() {
   $('.banners-slider-list').owlCarousel({
        items: 1,
        navigation: true,
        autoplay:false,
        loop:true,
    	dots: false,
        pagination: false,    
    	margin:6,
        navigationText: ["<i class='fas fa-chevron-left icon-white'></i>","<i class='fas fa-chevron-right icon-white'></i>"],
        singleItem: true,
    });
    $('.owl-carousel').trigger( 'refresh.owl.carousel' );

	$('.getSchedule').click(function(e){
        // отменяем стандартное поведение браузера при нажатии на ссылку
        e.preventDefault();

		$('#scheduleTitle').text($(this).attr('title'));

		$('#Mon').text($(this).attr('monday'));
		$('#Tue').text($(this).attr('tuesday'));
		$('#Wed').text($(this).attr('wednesday'));
		$('#Thu').text($(this).attr('thursday'));
		$('#Fri').text($(this).attr('friday'));
		$('#Sat').text($(this).attr('saterday'));
		$('#Sun').text($(this).attr('sunday'));

		var phones = $(this).attr('phones').split('_');
		$('#contacts a').remove();
		$('#contacts br').remove();
		for (var i = 0; i < phones.length; ++i)
		{
			$('#contacts').append('<a href="tel:' + phones[i] + '">' + phones[i] + '</a><br>');
		}

		var isMobile = navigator.userAgent.match(/Android/i) || 
				navigator.userAgent.match(/BlackBerry/i) || 
				navigator.userAgent.match(/iPhone|iPad|iPod/i) || 
				navigator.userAgent.match(/Opera Mini/i) || 
				navigator.userAgent.match(/IEMobile/i);

		if(isMobile)
		{
			$('.modal-dialog').attr('style', 'left:0.5%;top:42%;transform:translate(0,-40%);');
		}

        $('#scheduleModal').modal('show');
    });
});
</script>