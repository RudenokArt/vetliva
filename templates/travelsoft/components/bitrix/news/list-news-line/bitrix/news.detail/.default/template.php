<!-- Проверка юзер агента для адаптации размера видоса во фреймах  -->
<?
function isMobile() { 
	return preg_match("/(android|avantgo|Mobile|Phone|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}
?>
<? if(isMobile()): ?>
<script>
$(document).ready(function () {
	var heightV = document.body.clientWidth * 0.65;
	$(".vidFrame").attr("height", heightV);
	$("#subscr_form").attr("style", "width: 100%; border: 1px solid #264B87; height: 130px; padding-right: 6%;");
	$("#subscr_txt").attr("style", "color: #ffffff; font-size: 12pt; margin-top: 10px;");
});
</script>
<? endif; ?>
<?
$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/slider-prop.css");

$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/jquery.sliderPro.min.js");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/slide.js");


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
?>
<?$p=$arResult["PROPERTIES"];?>
<? /* h1><?echo LANGUAGE_ID == "ru" ? $arResult["NAME"] : $arResult["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]?> </h1 */ ?>
<? $this->addExternalJs(SITE_TEMPLATE_PATH . "/js/slide.js"); ?>
<?
$arWaterMark = Array(
    array(
        "name" => "watermark",
        "position" => "topright", // Положение
        "type" => "image",
        "size" => "real",
        "file" => NO_PHOTO_PATH_WATERMARK, // Путь к картинке
        "fill" => "exact",
    )
);
?>

<p>

<? if ($arParams["DISPLAY_DATE"] != "N"): ?>
	<?
	$date_need = "";
	$date_need_L = "";
	if (!empty($arResult["PROPERTIES"]["DATE_NEED"]["VALUE"])) {
		$date_need =  FormatDateFromDB($arResult["PROPERTIES"]["DATE_NEED"]["VALUE"], 'SHORT');
		$date_need_L = str_replace(" ", "T", FormatDateFromDB($arResult["PROPERTIES"]["DATE_NEED"]["VALUE"], 'YYYY-MM-DD HH:MI:SS'));
	}
	else {
		$date_need =  FormatDateFromDB($arResult["DATE_CREATE"], 'SHORT');
		$date_need_L = str_replace(" ", "T", FormatDateFromDB($arResult["DATE_CREATE"], 'YYYY-MM-DD HH:MI:SS'));
	}
	?>
<? endif; ?>

<? if($arResult["IBLOCK_ID"] == 58 && $arResult["PROPERTIES"]["SCH_SHOW"]["VALUE"]): ?>
<!-- Шаблон для обёртки статей в schema -->
<div itemscope itemtype="https://schema.org/BlogPosting" style="display:none;">
	<? $site_suff = (LANGUAGE_ID != "en")?LANGUAGE_ID:"com"; ?>
	<meta content="<?="https://vetliva.".$site_suff."/blog/".$arResult["CODE"]."/";?>" itemprop="mainEntityOfPage">
    <h2 class="post-title" itemprop="headline"><?=$arResult["NAME"];?></h2>
    <span class="entry-date">

		<time class="published" datetime="<?=$date_need_L."+00:00"?>" itemprop="datePublished"></time>
		<time class="updated" datetime="<?=$date_need_L."+00:00"?>" itemprop="dateModified"><?=$date_need?></time>
    </span>
    <span class="byline" itemprop="author" itemscope itemtype="https://schema.org/Person">
        <span itemprop="name">VETLIVA</span>
        <?=$arResult["PROPERTIES"]["SCH_AUTHOR"]["VALUE"];?>
    </span>
    <link href="https://vetliva.ru/" itemprop="url">
    <div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
        <div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject" style="display:none;">
            <img alt="VETLIVA" height="122" itemprop="url image" src="https://vetliva.ru/local/templates/travelsoft/images/logo-header-140.png" width="198">
            <meta content="198" itemprop="width">
            <meta content="122" itemprop="height">
        </div>
		<script>
			$(document).ready(function(){
				$('meta[itemprop=name]').attr("content", document.title);
			});
		</script>
		<meta content="" itemprop="name">
        <meta content="+375172154808" itemprop="telephone">
        <meta content="г. Минск, ул. Мясникова, 39, 2 этаж" itemprop="address">
    </div>
    <span itemprop="articleSection">
        <a href="<?="https://vetliva.".$site_suff."/blog/".$arResult["CODE"]."/";?>" rel="category tag"><?=$arResult["NAME"];?></a>
    </span>
    <span itemprop="keywords">
        <a href="<?="https://vetliva.".$site_suff."/blog/".$arResult["CODE"]."/";?>" rel="tag"><?=$arResult["PROPERTIES"]["SCH_KEYWORDS"]["VALUE"];?></a>
    </span>
    <span itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
        <img alt="<?=$arResult["PROPERTIES"]["SCH_ALT"]["VALUE"];?>" height="" itemprop="url contentUrl" src="<?=$arResult["PROPERTIES"]["SCH_IMG_PATH"]["VALUE"];?>" width="">
    </span>
    <div class="entry-description" itemprop="description">
        <p><?=$arResult["DISPLAY_PROPERTIES"]["SCH_SHORT_DSCR"]["DISPLAY_VALUE"];?></p>
    </div>
</div>
<? endif; ?>

    <? if ($arParams["DISPLAY_DATE"] != "N"): ?>
		<i class="fa fa-calendar"></i> 
		<?=$date_need;?>
	<? endif; ?>
    <?
    if (!empty($arResult["PROPERTIES"]["REGIONS"]["VALUE"])) {
        $region = strip_tags($arResult["DISPLAY_PROPERTIES"]["REGIONS"]["DISPLAY_VALUE"]);
        if (LANGUAGE_ID != "ru") {
            $prop = getIBElementProperties($arResult["PROPERTIES"]["REGIONS"]["VALUE"]);
            $region = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
        }
    }
	if (!empty($arItem["PROPERTIES"]["EXCURTION"]["VALUE"])) {
            $excurtion = strip_tags($arItem["DISPLAY_PROPERTIES"]["EXCURTION"]["DISPLAY_VALUE"]);
            if (LANGUAGE_ID != "ru") {
                $prop = getIBElementProperties($arItem["PROPERTIES"]["EXCURTION"]["VALUE"]);
                $excurtion = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
            }
        }
    if (!empty($arResult["PROPERTIES"]["TOWN"]["VALUE"])) {
        $town = strip_tags($arResult["DISPLAY_PROPERTIES"]["TOWN"]["DISPLAY_VALUE"]);
        if (LANGUAGE_ID != "ru") {
            $prop = getIBElementProperties($arResult["PROPERTIES"]["TOWN"]["VALUE"]);
            $town = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
        }
        if (!empty($arItem["PROPERTIES"]["ACCOMODATION"]["VALUE"])) {
            $accomodation = strip_tags($arItem["DISPLAY_PROPERTIES"]["ACCOMODATION"]["DISPLAY_VALUE"]);
            if (LANGUAGE_ID != "ru") {
                $prop = getIBElementProperties($arItem["PROPERTIES"]["ACCOMODATION"]["VALUE"]);
                $accomodation = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
            }
        }
        if (!empty($arItem["PROPERTIES"]["SANATORIUM"]["VALUE"])) {
            $sanatorium = strip_tags($arItem["DISPLAY_PROPERTIES"]["SANATORIUM"]["DISPLAY_VALUE"]);
            if (LANGUAGE_ID != "ru") {
                $prop = getIBElementProperties($arItem["PROPERTIES"]["SANATORIUM"]["VALUE"]);
                $sanatorium = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
            }
        }
        if (!empty($arItem["PROPERTIES"]["ATTRACTION"]["VALUE"])) {
            $attraction = strip_tags($arItem["DISPLAY_PROPERTIES"]["ATTRACTION"]["DISPLAY_VALUE"]);
            if (LANGUAGE_ID != "ru") {
                $prop = getIBElementProperties($arItem["PROPERTIES"]["ATTRACTION"]["VALUE"]);
                $attraction = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
            }
        }
    }
    ?>
    <? if (!empty($town) || !empty($region)): ?><i class="fa fa-map-marker"></i><? endif; ?>
	<? /* if ($region): ?><?= $region ?><? endif; */ ?>
	<? /* if ($town): ?> <?= $town ?><? endif; */ ?>
	<?= implode2(array($region, $town, $arResult["DISPLAY_PROPERTIES"]["ADDRESS".POSTFIX_PROPERTY]["DISPLAY_VALUE"])); ?>
    <? if ($accomodation): ?> <?= $accomodation ?><? endif; ?>
    <? if ($sanatorium): ?> <?= $sanatorium ?><? endif; ?>
    <? if ($attraction): ?> <?= $attraction ?><? endif; ?>
	<? if ($excurtion): ?> <?= $excurtion ?><? endif; ?></p>
                        <?
                            $kitchentype = null;
                            if ($p["KITCHEN2"]["VALUE"]) {
                                $p["KITCHEN2"]["VALUE"] = (array) $p["KITCHEN2"]["VALUE"];
                                $db_res_kitchentype = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $p["KITCHEN2"]["LINK_IBLOCK_ID"], "ID" => $p["KITCHEN2"]["VALUE"]), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY));
                                $kitchentype = null;
                                while ($res = $db_res_kitchentype->Fetch()) {
                                    $kitchentype[] = $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] ? $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $res["NAME"];
                                }
                            }
                            if ($kitchentype):
                                ?>
                            <p>
								<i class="fa fa-info-circle blue"></i> <?= GetMessage('KITCHEN_TYPE')?>:	<?= implode(", ", $kitchentype) ?>
							</p>
                        <? endif; ?>
                        <? if (!empty($arResult["DISPLAY_PROPERTIES"]["DATE_FROM"]["VALUE"]) && $arResult["IBLOCK_ID"] == 25): ?><b><?= GetMessage('DATE_FROM') ?></b>
                            <?$first_date = $arResult["DISPLAY_PROPERTIES"]["DATE_FROM"]["VALUE"][0];$end_date = count($arResult["DISPLAY_PROPERTIES"]["DATE_FROM"]["VALUE"]) > 1 ? $arResult["DISPLAY_PROPERTIES"]["DATE_FROM"]["VALUE"][0] : '';?>
                            <?foreach($arResult["DISPLAY_PROPERTIES"]["DATE_FROM"]["VALUE"] as $keydate=>$date_val):?>
                                <?$date = MakeTimeStamp($date_val, "DD.MM.YYYY");?>
                                <?if($date < MakeTimeStamp($first_date, "DD.MM.YYYY")){
                                    $first_date = $date_val;
                                }?>
                                <?if(!empty($end_date) && $date > MakeTimeStamp($end_date, "DD.MM.YYYY")){
                                    $end_date = $date_val;
                                }?>
                            <?endforeach?>
                            <br><?=date("d.m.Y", MakeTimeStamp($first_date, "DD.MM.YYYY"))?><?if(!empty($end_date)):?> - <?=date("d.m.Y", MakeTimeStamp($end_date, "DD.MM.YYYY"))?><?endif?>
                        <? endif; ?>
                        <?
                            $type = null;
                            if ($p["TYPE"]["VALUE"]) {
                                $p["TYPE"]["VALUE"] = (array) $p["TYPE"]["VALUE"];
                                $db_res_type = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $p["TYPE"]["LINK_IBLOCK_ID"], "ID" => $p["TYPE"]["VALUE"]), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY));
                                $type = null;
                                while ($res = $db_res_type->Fetch()) {
                                    $type[] = $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] ? $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $res["NAME"];
                                }
                            }
                            if ($type):
                                ?>
                            <p>
								<i class="fa fa-info-circle blue"></i> <?= GetMessage('TYPE')?>	<?= implode(", ", $type) ?>
							</p>
                        <? endif; ?>
						<? if(!empty($arResult["PROPERTIES"]["WORKING_HOURS".POSTFIX_PROPERTY]["VALUE"])):?>
							<p>
								<i class='fa fa-info-circle blue'></i>
								<?= GetMessage('WORKING_HOURS') . ': ' . $arResult["DISPLAY_PROPERTIES"]["WORKING_HOURS".POSTFIX_PROPERTY]["DISPLAY_VALUE"]?>
							</p>
						<? endif;?>
<?/*if ($p["TYPE".POSTFIX_PROPERTY]["VALUE"]):?>
                            <p>
								<i class="fa fa-info-circle blue"></i> <?= $p["TYPE".POSTFIX_PROPERTY]["VALUE"] ?>
							</p>
<?endif;*/?>

<?if ($arParams["HIDE_DETAIL_PICTURE"] !== "Y"): ?>
    <? $waterMark = ''; ?>
    <? if ($arParams["NO_SHOW_WATERMARK"] !== "Y"): ?>
        <? $waterMark = $arWaterMark ?>
    <? endif ?>
    <?
    if (count($arResult["PROPERTIES"]["PICTURES"]["VALUE"]) == 1):

        $langImg = LANGUAGE_ID == "ru" ? $arResult["PROPERTIES"]["PICTURES"]["VALUE"][0] : $arResult["PROPERTIES"]["PICTURES".POSTFIX_PROPERTY]["VALUE"]["0"];
        $an_file = CFile::ResizeImageGet($langImg, array('width' => 823, 'height' => 428), BX_RESIZE_IMAGE_EXACT, true, $waterMark);

        $pre_photo_915 = $an_file["src"];
        ?>
        <?$webpfile = makeWebpBig($pre_photo_915);?>
        <picture> 
            <?if ($webpfile!=''):?>
            <source type="image/webp" srcset="<?=$webpfile?>"> 
            <?endif;?>
            <img loading="lazy" src="<?= $pre_photo_915 ?>" alt="<?echo LANGUAGE_ID == "ru" ? $arResult["PROPERTIES"]["PICTURES"]["DESCRIPTION"]["0"] : $arResult["PROPERTIES"]["IMG_DESCRIPTION".POSTFIX_PROPERTY]["VALUE"]["0"]?>" class="img-responsive">
        </picture>  
        
    <? elseif (count($arResult["PROPERTIES"]["PICTURES"]["VALUE"]) > 1): ?>
        <div class="slider-wrap">
            <div class="slider-container">
                <div id="search-preloader-slider" class="reloader-postion">

                    <div id="search-page-loading">
                        <div></div>
                    </div>
                </div>
            <div id="slider-room" class="slider-pro">
                <div class="sp-slides">
                    <?
                    $i = 0;
                    foreach ($arResult["PROPERTIES"]["PICTURES"]["VALUE"] as $item):
                        $file_big = CFile::ResizeImageGet($item, Array('width' => 1170, 'height' => 640), BX_RESIZE_IMAGE_EXACT, true, $arWaterMark);
                        $img_count++;
                        ?>
                        <div class="sp-slide">
                            <img src="<?= $file_big["src"]; ?>"  alt="<?= $arResult["PROPERTIES"]["PICTURES"]["DESCRIPTION"][$i] ?>">
                        </div>
                        <? $i++;
                    endforeach;
                    ?>
                </div>
                <div class="sp-thumbnails">
                    <?
                    $i = 0;
                    foreach ($arResult["PROPERTIES"]["PICTURES"]["VALUE"] as $item):
                        $file_small = CFile::ResizeImageGet($item, Array('width' => 220, 'height' => 100), BX_RESIZE_IMAGE_EXACT, true);
                        $img_count++;
                        ?>
                        <div class="sp-thumbnail">

                            <img src="<?= $file_small["src"]; ?>"  alt="<?= $arResult["PROPERTIES"]["PICTURES"]["DESCRIPTION"][$i] ?>">
                        </div>
                        <? $i++;
                    endforeach;
                    ?>
                </div>
            </div>

            </div>
        </div>
       
    <? endif; ?>
<? endif; ?>


<? $this->SetViewTarget("menu-poster-detail"); ?>
			 <? if (!empty($arResult["PROPERTIES"]["DETAIL_TEXT" . POSTFIX_PROPERTY]["VALUE"])): ?>
				<li class=""><a href="#iblock_descc" class="anchor"><?= GetMessage("POSTER_DESC") ?></a></li>
			<?endif;?>
			<? if (!empty($arResult["PROPERTIES"]["MAP"]["VALUE"])): ?>
					<li class=""><a href="#placement_locationn" class="anchor"><?= GetMessage("MAP") ?></a></li>
			 <?endif;?>
			
<? $this->EndViewTarget(); ?>


<? if (!empty($arResult["PROPERTIES"]["HD_DESC" . POSTFIX_PROPERTY]["VALUE"])): ?>
    <div class="policies-item">
        <p <?if($arResult["IBLOCK_ID"] == NEWS_IBLOCK_ID):?> itemprop="description"<?endif?>>
    <?= $arResult["DISPLAY_PROPERTIES"]["HD_DESC" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
        </p>
    </div>
<? endif ?>
        <? if (!empty($arResult["PROPERTIES"]["DETAIL_TEXT" . POSTFIX_PROPERTY]["VALUE"])): ?>
		
    <div class="policies-item"  id="iblock_descc">

        <p <?if($arResult["IBLOCK_ID"] == NEWS_IBLOCK_ID):?> itemprop="description"<?endif?>>
    <?= $arResult["DISPLAY_PROPERTIES"]["DETAIL_TEXT" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
        </p>
    </div>
<? endif ?>
<? if (!empty($arResult["PROPERTIES"]["YOUTUBE" . POSTFIX_PROPERTY]["VALUE"])): ?>
    <div class="policies-item">
        <h4><?= GetMessage("VIDEO") ?></h4>
        <iframe width="100%" height="460" src="https://www.youtube.com/embed/<?= $arResult["PROPERTIES"]["YOUTUBE" . POSTFIX_PROPERTY]["VALUE"] ?>" frameborder="0" allowfullscreen></iframe>
    </div>
<? endif;?>
<? if (!empty($arResult["PROPERTIES"]["VIMEO" . POSTFIX_PROPERTY]["VALUE"])): ?>
    <div class="policies-item">
        <h4><?= GetMessage("VIDEO") ?></h4>
        <iframe width="100%" height="460" src="https://player.vimeo.com/video/<?= $arResult["PROPERTIES"]["VIMEO" . POSTFIX_PROPERTY]["VALUE"] ?>" frameborder="0" allowfullscreen></iframe>
    </div>
<? endif;?>


    <? if (!empty($arResult["PROPERTIES"]["MAP"]["VALUE"])): ?>
    <div class="hotel-detail-map" id="placement_locationn">

        <h4><?= GetMessage("MAP") ?></h4>
        <div style="width: 100%; height: 400px" id="placement_location_map"></div>
                    <?
                    $arLatLon = explode(",", $arResult["PROPERTIES"]["MAP"]["VALUE"]);
                    $this->addExternalJs(SITE_TEMPLATE_PATH . "/js/MapAdapter/MapAdapter.js");
                    ?>
                    <script>
                        $(window).load(function () {
                            var mapAdapter = new MapAdapter({
                                map_id: "placement_location_map",
                                center: {
                                    lat: 53.53,
                                    lng: 27.34
                                },
                                object: "ymaps",
                                zoom: 14
                            });
                            mapAdapter.addMarker({
                                lat: <?= $arLatLon[0]?>,
                                lng: <?= $arLatLon[1]?>,
                                icon: "<?//= MAP_MARKER_PATH?>",
								//title: "<?= $arResult['NAME']?>",
								content: "<span style='color: #264B87'><?= $arResult['NAME']?></span>"
                            });
                        });

                    </script>
    </div>
	<br>
	<? endif; ?>

<?//Голосование на статье с дедом морозом
if($arResult["ID"] == 6746) {
$APPLICATION->IncludeComponent("bitrix:voting.current", "blog_new_year", Array(
	"AJAX_MODE" => "N",	// Включить режим AJAX
		"AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
		"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
		"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
		"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
		"CACHE_TIME" => "0",	// Время кеширования (сек.)
		"CACHE_TYPE" => "N",	// Тип кеширования
		"CHANNEL_SID" => "BLOG_VOTE",	// Группа опросов
		"VOTE_ALL_RESULTS" => "N",	// Показывать варианты ответов для полей типа Text и Textarea
		"VOTE_ID" => "1",	// ID опроса
		"COMPONENT_TEMPLATE" => "blog_new_year"
	),
	false
);
}
?>

	<!-- Форма подписки для раздела блог -->
	<? if($arResult["IBLOCK_ID"] == 58): ?>

	<!-- Subscribe Form -->
	<div style="padding-top: 2%; padding-bottom: 3%; padding-left: 5%; padding-right: 5%; background-color: #264B87;">
		<center>
			<p id="subscr_txt" style="color: #ffffff; font-size: 14pt;"><?=getMessage("SUBSCRIBE_TEXT");?></p>
		<div class="subscribe">
			<!-- unisender.com -->
			<div id="subscr_form" style="width: 60%; border: 1px solid #264B87;" class="subscribe-form">
				<form method="POST" action="<?=getMessage("UNISENDER")?>" name="subscribtion_form">

					<? if(!isMobile()): ?>

					<input style="background-color: #ffffff; color: #000000;" class="subscribe-input" 
						placeholder="<?=getMessage("YOU")?> email" type="text" name="email" value="">
					<input style="width: 40%; height: 100%; background-color: #00bfff; color: white;" 
						class="awe-btn awe-btn-5 arrow-right text-uppercase awe-btn-lager" type="submit" 
						value="<?=getMessage("SUBSCRIBE")?>">

					<? else: ?>

					<input style="width: 100%; background-color: #ffffff; color: #000000;" class="subscribe-input" 
						placeholder="<?=getMessage("YOU")?> email" type="text" name="email" value=""><br><br>
					<input style="width: 100%; height: 52px; background-color: #00bfff; color: white; border-radius: 5px; border: 1px solid #00bfff; font-size: 12pt;" 
					type="submit" value="<?=getMessage("SUBSCRIBE")?>">

					<? endif; ?>

					<input type="hidden" name="charset" value="UTF-8">
					<input type="hidden" name="default_list_id" value="<?=getMessage("UNISENDER_ID")?>">
					<input type="hidden" name="overwrite" value="2">
					<input type="hidden" name="is_v5" value="1">
				</form>
			</div>
		</div>
		</center>
	</div>
	<br>

	<? endif; ?>

	<?
		if(!empty($arResult["PROPERTIES"]["READ_MORE"]["VALUE"]))
		{
			$news_sect = "";
			switch ($arResult["IBLOCK_ID"]) 
			{
    		case 58:
				$news_sect = "/belarus/blog/";
        		break;
    		case 28:
        		$news_sect = "/belarus/news/";
        		break;
    		default:
       			$news_sect = "/belarus/blog/";
			}
			echo "<div class='policies-item'>";
			echo "<h4 style='color:black'>".GetMessage("READ_MORE")."</h4>";
			$arFilter = array('IBLOCK_ID' => $arResult["IBLOCK_ID"], 'ID' => $arResult["PROPERTIES"]["READ_MORE"]["VALUE"], 'ACTIVE' => 'Y');
			$arSelect = array("ID","NAME","DETAIL_PAGE_URL","CODE");

			$res = CIBlockElement::GetList(array(), $arFilter, false,  false, $arSelect);

			while($ob = $res->GetNextElement())
			{
				$arFields = $ob->GetFields();
				$arProp = $ob->GetProperties();
				$news_name = (LANGUAGE_ID == "ru")?$arFields["NAME"]:$arProp["NAME". POSTFIX_PROPERTY]["VALUE"];
				$news_suff = (LANGUAGE_ID == "en")?"com":LANGUAGE_ID;
				echo "<p style='margin-top:4px;margin-bottom:4px;'><img src='".SITE_TEMPLATE_PATH."/images/vet_logo_star.png' width=20px height=15px> ";
				echo "<a href='https://vetliva.".$news_suff.$news_sect.$arFields["CODE"]."/' target='_blank'>".$news_name."</a></p>";
			}
			echo "</div>";
		} 
	?>

<script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
<script src="//yastatic.net/share2/share.js"></script>
<div style="width:210px;float:left;" class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,gplus,twitter" data-counter="" 
						data-lang="<?if (LANGUAGE_ID=="by"){ echo "be";} else {echo LANGUAGE_ID; }?>" 
<?if (!empty($pre_photo_915)):?>
						data-image="https://vetliva.<?=LANGUAGE_ID?><?=$pre_photo_915?>"
<?elseif(!empty($file_big["src"])):?>
						data-image="https://vetliva.<?=LANGUAGE_ID?><?=$file_big["src"]?>"
<?endif;?>
>
</div>

	<!-- Подключение скрипта увеличения картинок -->
	<? if($arResult["IBLOCK_ID"] == 58 && !isMobile()): ?>
<script>


if(window.location.href.indexOf("#header_name") > -1) {
(function($) {
    $(document).ready(function() {
		 var distance = $('#header_name').offset().top;
		 //console.log(distance);
         $('html, body').animate({
           'scrollTop':   $('#header_name').offset().top - 150 + "px"
         }, 1000);
    });
})(jQuery);
} 

$(document).ready(function() {	// Ждём загрузки страницы
	$("section.info-list div.policies-item p img").click(function(){    // Событие клика на маленькое изображение
		var img = $(this);    // Получаем изображение, на которое кликнули
		var src = img.attr('src'); // Достаем из этого изображения путь до картинки
		var title = img.attr('title'); // Достаём тайтл для увеличенной фотки
		$("body").append("<div class='popup_adpt'>"+ //Добавляем в тело документа разметку всплывающего окна
			"<div class='popup_bg_adpt'></div>"+ // Блок, который будет служить фоном затемненным
			"<img src='"+src+"' class='popup_img_adpt' />"+ // Само увеличенное фото
		"</div>");
		$(".popup_adpt").fadeIn(800); // Медленно выводим изображение
		$(".popup_img_adpt").attr("title", title);
		$(".popup_bg_adpt").click(function(){    // Событие клика на затемненный фон      
			$(".popup_adpt").fadeOut(800);    // Медленно убираем всплывающее окно
			setTimeout(function() {    // Выставляем таймер
			$(".popup_adpt").remove(); // Удаляем разметку всплывающего окна
			}, 800);
		});
		$(".popup_img_adpt").click(function(){    // Событие клика на затемненный фон      
			$(".popup_adpt").fadeOut(800);    // Медленно убираем всплывающее окно
			setTimeout(function() {    // Выставляем таймер
			$(".popup_adpt").remove(); // Удаляем разметку всплывающего окна
			}, 800);
		});
	});
});
</script>
	<? endif; ?>

	<? if($arResult["IBLOCK_ID"] == 58): ?>

		<div style="float:left;height:58px;padding-top:27px;">
			<div class="border-box1">
				<img width="20px" height="20px" src="/local/templates/travelsoft/images/like.png" class="like">
				<span class="L"><?=(empty($arResult["PROPERTIES"]["LIKES"]["VALUE"]) || $arResult["PROPERTIES"]["LIKES"]["VALUE"] == "0")?"":$arResult["PROPERTIES"]["LIKES"]["VALUE"]?></span>
			</div>
			<div class="border-box2">
				<img width="19px" height="19px" src="/local/templates/travelsoft/images/dislike.png" class="dislike">
				<span class="D"><?=(empty($arResult["PROPERTIES"]["DISLIKES"]["VALUE"]) || $arResult["PROPERTIES"]["DISLIKES"]["VALUE"] == "0")?"":$arResult["PROPERTIES"]["DISLIKES"]["VALUE"]?></span>
			</div>
		</div>
		<script>
			$(document).ready(function () {

				var checkLikeStat;
				$(".like").bind("click", function(event) {
					if(!$.cookie('vet_like'+"<?=$arResult['ID']?>")) {
						checkLikeStat = "N";
						$.cookie('vet_like'+"<?=$arResult['ID']?>", "liked", { expires: 365, path: '/' });
					} else if($.cookie('vet_like'+"<?=$arResult['ID']?>") == "liked") {
						checkLikeStat = "liked";
						$.cookie('vet_like'+"<?=$arResult['ID']?>", null, { expires: 365, path: '/' });
					} else if($.cookie('vet_like'+"<?=$arResult['ID']?>") == "disliked") {
						checkLikeStat = "disliked";
						$.cookie('vet_like'+"<?=$arResult['ID']?>", "liked", { expires: 365, path: '/' });
					}
					
					$.ajax({
						url: "/local/templates/travelsoft/components/bitrix/news/list-news-line/bitrix/news.detail/.default/ajax.php",
						type: "POST",
						data: ("inf="+"<?=$arResult['IBLOCK_ID']?>"+"-"+"<?=$arResult['ID']?>"+"-"+"L"+"-"+checkLikeStat),
						dataType: "text",
						success: function(result) {
							if (result >= 0) {
								if(result == 0)
									$(".L").text("");
								else
									$(".L").text(result);
							}
							else if(result == -1)
							{
								$(".L").text(Number($(".L").text()) + 1);
								if($(".D").text() == "1")
									$(".D").text("");
								else
									$(".D").text(Number($(".D").text()) - 1);
							}
							else alert(result);
						}
					});
				});
				
				$(".dislike").bind("click", function(event) {
					if(!$.cookie('vet_like'+"<?=$arResult['ID']?>")) {
						checkLikeStat = "N";
						$.cookie('vet_like'+"<?=$arResult['ID']?>", "disliked", { expires: 365, path: '/' });
					} else if($.cookie('vet_like'+"<?=$arResult['ID']?>") == "disliked") {
						checkLikeStat = "disliked";
						$.cookie('vet_like'+"<?=$arResult['ID']?>", null, { expires: 365, path: '/' });
					} else if($.cookie('vet_like'+"<?=$arResult['ID']?>") == "liked") {
						checkLikeStat = "liked";
						$.cookie('vet_like'+"<?=$arResult['ID']?>", "disliked", { expires: 365, path: '/' });
					}
					
					$.ajax({
						url: "/local/templates/travelsoft/components/bitrix/news/list-news-line/bitrix/news.detail/.default/ajax.php",
						type: "POST",
						data: ("inf="+"<?=$arResult['IBLOCK_ID']?>"+"-"+"<?=$arResult['ID']?>"+"-"+"D"+"-"+checkLikeStat),
						dataType: "text",
						success: function(result) {
							if (result >= 0) {
								if(result == 0)
									$(".D").text("");
								else
									$(".D").text(Number(result));
							}
							else if(result == -1)
							{
								$(".D").text(Number($(".D").text()) + 1);
								if($(".L").text() == "1")
									$(".L").text("");
								else
									$(".L").text(Number($(".L").text()) - 1);
							}
							else alert("Error");
						}
					});
				});
			});
		</script>

	<? endif; ?>

<div style="clear: both;"></div>

<script>

 $(document).ready(function () {
	var WindowWidth = $(window).width();
	function ScrollSiderBarInit() {

            var $window = $(window);
            var $body = $(document.body);
            var top = $("#header").outerHeight() + $(".main-tabs-search").outerHeight();

            $body.scrollspy({
                target: '.scrollspy-sidebar',
                offset: top + 50
            });

            $window.on('load', function () {
                $body.scrollspy('refresh')
            });

            $('a.anchor[href*=#]:not([href=#])').on("click", function () {
                if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
                    var target = $(this.hash);
                    target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                    $('html,body').stop().animate({
                        scrollTop: (target.offset().top - 80) // 70px offset for navbar menu
                    }, 1000);
                    return false;

                }
            });

        }
		
        function ScrollSiderBar() {

            if (WindowWidth >= 1200) {

                var scroll = $(window).scrollTop(),
                        top = $("#header").outerHeight() + $(".main-tabs-search").outerHeight();


                $('.detail-cn').each(function (index, value) {

                    var $this = $(this),
                            offset = $this.offset().top,
                            height = $this.outerHeight(),
                            $taget = $this.find('.scroll-heading'),
                            eheight = $taget.outerHeight(),
                            scroll_top = scroll - offset + top;
					if($('.widget_categories').length){
						var heightWidget = $('.widget_categories').outerHeight();
						offset = $this.offset().top + heightWidget;
						eheight = $taget.outerHeight() + heightWidget,
							scroll_top = scroll - offset + top;
					}
                    if (scroll_top > 0) {
                        if (height - scroll_top >= 0 && (height - eheight) > scroll_top) {
                            $taget.css({
                                'position': 'fixed',
                                'top': +top + 'px'
                            });
                        } else {
                            $taget.css({'position': 'static'});
                        }
                    } else {
                        $taget.css({'position': 'static'});
                    }
					
					console.log(scroll_top)
                });

              
            }
        }
		
		 $(window).load(function (event) {
          
         
            ScrollSiderBarInit();
            ScrollSiderBar();
        });
		
		$(window).scroll(function (event) {
            
            ScrollSiderBar();
       
        });
		
	});	
</script>
