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

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$this->addExternalCss($templateFolder . "/snackbars.css");
$this->addExternalCss($templateFolder . "/intlTelInput.min.css");
$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/slider-prop.css");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/slide.js");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/jquery.sliderPro.min.js");
$this->addExternalJs($templateFolder . "/js/intlTelInput-jquery.min.js");
$this->addExternalJs($templateFolder . "/js/jquery.maskedinput.min.js");



$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/webui-popover/jquery.webui-popover.min.css");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/webui-popover/jquery.webui-popover.min.js");

$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/magnific-popup.css");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/jquery.magnific-popup.js");

$p = $arResult["PROPERTIES"];
$scroll[] = array();
?>
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

// Подготовка информации для времени работы клиники
$arDayMarks = array("Mon" => "",
				"Tue" => "",
				"Wed" => "",
				"Thu" => "",
				"Fri" => "",
				"Sat" => "",
				"Sun" => "");
$arDayMarks[$arResult["TODAY"]] = "<i class=\"fa fa-circle\" aria-hidden=\"true\" style=\"color:#feb818\"></i>";
$arDayTimes = array();
$dayOff = GetMessage("DAY_OFF");

$arDayTimes["Mon"] = $arResult["PROPERTIES"]["Mon"]["VALUE"];
$arDayTimes["Tue"] = $arResult["PROPERTIES"]["Tue"]["VALUE"];
$arDayTimes["Wed"] = $arResult["PROPERTIES"]["Wed"]["VALUE"];
$arDayTimes["Thu"] = $arResult["PROPERTIES"]["Thu"]["VALUE"];
$arDayTimes["Fri"] = $arResult["PROPERTIES"]["Fri"]["VALUE"];
$arDayTimes["Sat"] = $arResult["PROPERTIES"]["Sat"]["VALUE"];
$arDayTimes["Sun"] = $arResult["PROPERTIES"]["Sun"]["VALUE"];

foreach($arDayTimes as $day => $time)
{
	if(trim($time) == "-" || empty($time))
		$arDayTimes[$day] = $dayOff;
}
?>
<? $this->SetViewTarget("head-detail"); ?>

<section class="head-detail">
    <div class="head-dt-cn">
        <div class="row">
            <div class="col-sm-10 col-md-10">
				<? $title = LANGUAGE_ID == "ru" ? $arResult["NAME"] : $arResult["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"]; ?>
                <h1><?=$title ?> </h1>
            </div>
            <div class="col-sm-2 col-md-2 col-xs-12 text-right">     
				<div style="order: 1; margin-left: 10px">
                    <?= $GLOBALS["favorites_html"]?>
                </div>
            </div>
        </div>
    </div>
</section>
<?
if (count($arResult["PROPERTIES"]["PICTURES"]["VALUE"]) == 1):
    $an_file = CFile::ResizeImageGet($arResult["PROPERTIES"]["PICTURES"]["VALUE"][0], array('width' => 1170, 'height' => 641), BX_RESIZE_IMAGE_EXACT, true);
    $pre_photo = $an_file["src"];
    ?>
    <img src="<?= $pre_photo ?>" alt="<?= $arResult["PROPERTIES"]["PICTURES"]["DESCRIPTION"][0] ?>" class="img-responsive">
<? elseif (count($arResult["PROPERTIES"]["PICTURES"]["VALUE"]) > 1): ?>
    <div class="slider-container">
        <div id="search-preloader-slider" class="reloader-postion">
            <div id="search-page-loading">
                <div></div>
            </div>
        </div>
        <div style="margin-top: 20px;">
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
                            <img src="<?= $file_small["src"]; ?>" alt="<?= $arResult["PROPERTIES"]["PICTURES"]["DESCRIPTION"][$i] ?>">
                        </div>
                        <? $i++;
                    endforeach;
                    ?>
                </div>
            </div>
        </div>
    </div>
<? endif; ?>

<section class="hotel-content check-rates detail-cn" id="hotel-content">
	<div class="hl-customer-like" style="margin-top:20px;text-align:center">
			<a class="btn-for-popup" href="#scheduleModalDetail" data-toggle="modal"><?=GetMessage("SHOW_BTN_TITLE") ?></a>
			<a id="send_btn_popup" style="margin-left:80px" class="btn-for-popup" href="#callbackModalDetail" data-toggle="modal"><?=GetMessage("SEND_BTN_TITLE")?></a>
        </div>
</section>
<? $this->EndViewTarget(); ?>

<? $this->SetViewTarget("address-sidebar-detail"); ?>
<?
	if (!empty($arResult["PROPERTIES"]["ADDRESS" . POSTFIX_PROPERTY]["VALUE"])):
	$adress = '';
?>
	<address class="address">
		<b><?= GetMessage('ADDRESS') ?>:</b>
		<? $adress = substr2($arResult["PROPERTIES"]["ADDRESS" . POSTFIX_PROPERTY]["VALUE"], 200); ?>
		<? if (!empty($arResult["PROPERTIES"]["TOWN"]["VALUE"])) {
			$town = strip_tags($arResult["DISPLAY_PROPERTIES"]["TOWN"]["DISPLAY_VALUE"]);
			if (LANGUAGE_ID != "ru") {
				$prop = getIBElementProperties($arResult["PROPERTIES"]["TOWN"]["VALUE"]);
				$town = trim($prop["NAME" . POSTFIX_PROPERTY]["VALUE"]);
			}
		}
		if (!empty($arResult["PROPERTIES"]["REGION"]["VALUE"])) {
			$obl = strip_tags($arResult["DISPLAY_PROPERTIES"]["REGION"]["DISPLAY_VALUE"]);
			if (LANGUAGE_ID != "ru") {
				$prop = getIBElementProperties($arResult["PROPERTIES"]["REGION"]["VALUE"]);
				$obl = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
			}
		} ?>
	<? if ($town): ?><? $adress .= ", " . $town; ?><? endif; ?>
	<? if ($obl): ?><? $adress .= ", " . $obl; ?><? endif; ?>
	<? if ($country): ?><? $adress .= ", " . $country; ?><? endif; ?>
	<? echo $adress; ?>
	<?if($arResult['PROPERTIES']['MAP']['VALUE']):?>
		<br>
		<div class="show-map__wrapper">
			<a
				href="javascript:;"
				title="<?= GetMessage('T_PLACEMENT_LIST_SHOW_MAP') ?>"
				class="show-map"
				data-id="<?=$arResult['ID']?>"
			><?= GetMessage('T_SHOW_MAP') ?></a>
		</div>
	<?endif?>
	<? if (!empty($arResult["PROPERTIES"]["DISTANCE_MINSK"]["VALUE"])): ?><br><b><?= GetMessage('DISTANCE_MINSK') ?></b> <?= substr2($arResult["PROPERTIES"]["DISTANCE_MINSK"]["VALUE"], 100); ?> km<? endif ?>
	<? if (!empty($arResult["PROPERTIES"]["NEAREST_TOWN"]["VALUE"])): ?>
		<? $nearest_town = strip_tags($arResult["DISPLAY_PROPERTIES"]["NEAREST_TOWN"]["DISPLAY_VALUE"]);
		if (LANGUAGE_ID != "ru") {
			$prop = getIBElementProperties($arResult["PROPERTIES"]["NEAREST_TOWN"]["VALUE"]);
			$nearest_town = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
		} ?>
		<br><b><?= GetMessage('NEAREST_TOWN') ?></b> <?= $nearest_town ?><? if (!empty($arResult["PROPERTIES"]["NEAREST_TOWN_KM"]["VALUE"])): ?> (<?= $arResult["PROPERTIES"]["NEAREST_TOWN_KM"]["VALUE"] ?> km)<? endif ?>
	<? endif ?>
	</address>
<? endif; ?>

<? $this->EndViewTarget(); ?>

<? if(check_smartphone()):?>
	<address class="address">
	<? if (!empty($arResult["PROPERTIES"]["ADDRESS" . POSTFIX_PROPERTY]["VALUE"])):?>
		<b><?= GetMessage('ADDRESS') ?>:</b>
		<? echo $adress; ?>
	<? endif; ?>
	<? if($arResult['PROPERTIES']['MAP']['VALUE']):?>
		<br>
		<div class="show-map__wrapper">
			<a
				href="javascript:;"
				title="<?= GetMessage('T_PLACEMENT_LIST_SHOW_MAP') ?>"
				class="show-map"
				data-id="<?=$arResult['ID']?>"
			><?= GetMessage('T_SHOW_MAP') ?></a>
		</div>
	<? endif; ?>
	<? if (!empty($arResult["PROPERTIES"]["DISTANCE_MINSK"]["VALUE"])): ?><br><b><?= GetMessage('DISTANCE_MINSK') ?></b> <?= substr2($arResult["PROPERTIES"]["DISTANCE_MINSK"]["VALUE"], 100); ?> km<? endif ?>
	<? if (!empty($arResult["PROPERTIES"]["NEAREST_TOWN"]["VALUE"])): ?>
		<br><b><?= GetMessage('NEAREST_TOWN') ?></b><?= $nearest_town ?><? if (!empty($arResult["PROPERTIES"]["NEAREST_TOWN_KM"]["VALUE"])): ?> (<?= $arResult["PROPERTIES"]["NEAREST_TOWN_KM"]["VALUE"] ?> km)<? endif ?>
	<? endif ?>
	</address>
<? endif; ?>

<? if (!empty($arResult["PROPERTIES"]["DESCRIPTION" . POSTFIX_PROPERTY]["VALUE"])): ?>
    <section class="details-policies detail-cn" id="iblock_detail_desc">
        <div class="details-policies-cn">
		<? $scroll[] = array("iblock_detail_desc", GetMessage("DESC_TITLE")); ?>
            <div class="policies-item detail-ul">
                <h3><?=GetMessage("DESC_TITLE") ?></h3>
					<?= $arResult["DISPLAY_PROPERTIES"]["DESCRIPTION" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
            </div>
        </div>
    </section>
<? endif ?>

<? if (!empty($arResult["PROPERTIES"]["FILE" . POSTFIX_PROPERTY]["VALUE"])): ?>
    <section class="details-policies detail-cn" id="iblock_detail_prices">
        <div class="details-policies-cn">
		<? $scroll[] = array("iblock_detail_prices", GetMessage("PRICES_TITLE")); ?>
            <div class="policies-item detail-ul">
                <h3><?=GetMessage("PRICES_TITLE") ?></h3>
					<ul>
						<? if($arResult["DISPLAY_PROPERTIES"]["FILE" . POSTFIX_PROPERTY]["FILE_VALUE"][0]): ?>
							<? foreach($arResult["DISPLAY_PROPERTIES"]["FILE" . POSTFIX_PROPERTY]["FILE_VALUE"] as $file): ?>
							<li>
								<?=$file["ORIGINAL_NAME"].": "?>
								<a href="<?=$file["SRC"] ?>" target="_blank">
									<?=GetMessage("LABEL_LOAD")?>
								</a>
							</li>
							<? endforeach; ?>
						<? else: ?>
						<li>
							<?=$arResult["DISPLAY_PROPERTIES"]["FILE" . POSTFIX_PROPERTY]["FILE_VALUE"]["ORIGINAL_NAME"].": "?>
							<a href="<?=$arResult["DISPLAY_PROPERTIES"]["FILE" . POSTFIX_PROPERTY]["FILE_VALUE"]["SRC"] ?>" target="_blank">
								<?=GetMessage("LABEL_LOAD")?>
							</a>
						</li>
						<? endif; ?>
					</ul>
            </div>
        </div>
    </section>
<? endif ?>

<? if (!empty($arResult["DISPLAY_PROPERTIES"]["TYPE"]["VALUE"])):?>
<section class="hl-features detail-cn" id="iblock_detail_profiles">
	<div class="featured-service">
		<? $scroll[] = array("iblock_detail_profiles", GetMessage("MED_PROFILES")); ?>
		<h3><?= GetMessage("MED_PROFILES") ?></h3>
		<ul class="service-accmd popover-ul">
		<? foreach ($arResult["DISPLAY_PROPERTIES"]["TYPE"]["DESC"] as $sId => $arData): ?>
			<li>
				<div><img src="/local/templates/travelsoft/images/icon-check.png" alt=""></div>
			<?= $arData["NAME"]?>
			</li>
		<? endforeach; ?>
		</ul>
	</div>
</section>
<? endif; ?>

<? if (!empty($arResult["DISPLAY_PROPERTIES"]["MED_SERVICES"]["VALUE"])): ?>
<section class="hl-features detail-cn" id="iblock_detail_services">
        <div class="featured-service">
		<? $scroll[] = array("iblock_detail_services", GetMessage("MED_SERVICES_TITLE")); ?>
            <h3><?= GetMessage("MED_SERVICES_TITLE") ?></h3>
            <ul class="service-accmd">
            <? foreach ($arResult["DISPLAY_PROPERTIES"]["MED_SERVICES"]["DESC"] as $sId => $arData): ?>
                    <li><div><img src="/local/templates/travelsoft/images/icon-check.png" alt=""></div>
                         <? if (!empty($arData["DESCRIPTION"])): ?>
                                <a id="show-medservice-popup" data-id="<?= $sId ?>" href="#medservice-popup" class="medservice"><?= $arData["NAME"] ?></a>
                          <? else: ?>
                                <?= $arData["NAME"] ?>
                          <? endif ?>
                    </li>
           	<? endforeach; ?>
            </ul>
        </div>
</section>
<? endif; ?>

<? if (!empty($arResult["PROPERTIES"]["HD_ADDINFORMATION" . POSTFIX_PROPERTY]["VALUE"])): ?>
    <section class="details-policies detail-cn" id="iblock_detail_additional">
        <div class="details-policies-cn">
            <? $scroll[] = array("iblock_detail_additional", GetMessage("HD_ADDINFORMATION")); ?>
            <div class="policies-item detail-ul">
                <h3><?= GetMessage("HD_ADDINFORMATION") ?></h3>
                <?= $arResult["DISPLAY_PROPERTIES"]["HD_ADDINFORMATION" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
            </div>
        </div>
    </section>
<? endif ?>
<? if (!empty($arResult["PROPERTIES"]["YOUTUBE" . POSTFIX_PROPERTY]["VALUE"]) || !empty($arResult["PROPERTIES"]["VIMEO" . POSTFIX_PROPERTY]["VALUE"])): ?>
    <section class="details-policies detail-cn" id="iblock_detail_youtube">
        <div class="details-policies-cn">
            <? $scroll[] = array("iblock_detail_youtube", GetMessage('YOUTUBE')); ?>
            <a name="iblock_detail_youtube" id="iblock_detail_youtube"></a>
            <div class="policies-item">
                <h3><?= GetMessage('VIDEO') ?></h3>
                <? if (!empty($arResult["PROPERTIES"]["YOUTUBE" . POSTFIX_PROPERTY]["VALUE"])): ?>
                    <div class="video-block">
                        <iframe width="100%" style="border: none;" src="https://www.youtube.com/embed/<?= $arResult["PROPERTIES"]["YOUTUBE" . POSTFIX_PROPERTY]["VALUE"] ?>" allowfullscreen=""></iframe>
                    </div>
                <? endif ?>
                <? if (!empty($arResult["PROPERTIES"]["VIMEO" . POSTFIX_PROPERTY]["VALUE"])): ?>
                    <div class="video-block">
                        <iframe width="100%" style="border: none;" src="https://player.vimeo.com/video/<?= $arResult["PROPERTIES"]["YOUTUBE" . POSTFIX_PROPERTY]["VALUE"] ?>" allowfullscreen="" frameborder="0" webkitallowfullscreen mozallowfullscreen></iframe>
                    </div>
                <? endif ?>
            </div>
        </div>
    </section>
<? endif ?>
<? if(!empty($arResult["PROPERTIES"]["PROEZD" . POSTFIX_PROPERTY]["VALUE"])):?>
    <section class="about-area details-policies detail-cn" id="iblock_detail_proezd">
        <div class="details-policies-cn">
            <? $scroll[] = array("iblock_detail_proezd", GetMessage("PROEZD")); ?>
            <div class="about-area-text" style="margin-top:1.5%">
                <h3>
                    <?=GetMessage("PROEZD") ?>
                </h3>
                <div id='before_btn_transfer' style="width:75%;float:left;padding-bottom:1.1%;padding-top:1%;color:#1E3C6E;background-color:#e5f4fa">
                    <b><?=GetMessage("TEXT_BEFORE_BTN");?></b>
                </div>
                <div class="btnTransferChng">
                    <a href='../../../bitrix/click.php?event1=btn_transfer&amp;event2=click&amp;goto=../../../tourism/transfer/' target="_blank">
                        <img id='btn_transfer' src="<?=SITE_TEMPLATE_PATH.'/images/transfer_icon_btn_short'.POSTFIX_PROPERTY.'.png'?>" />
                    </a>
                </div>
                <p style="margin-top:6%">
                    <?= $arResult["DISPLAY_PROPERTIES"]["PROEZD" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
                </p>
            </div>
        </div>
    </section>
<? endif; ?>
    <div id="medservice-popup" class="show-medservice-form mfp-hide">
    </div>

<!-- Модальное окно для времени работы клиники -->
<div class="modal fade" id="scheduleModalDetail" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document" style="left:8%;top:15%;transform:translate(0,-40%);">
    <div class="modal-content col-md-7">
		<div class="modal-header" style="text-align:center;display:flex;align-items:flex-start;">
		 	<h3 class="modal-title" id="modalLabel" style="width:100%;">
				<?=$title ?>
			</h3>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span style="font-size:20pt;" aria-hidden="true">&times;</span>
        	</button>
      </div>
	  <center>
      <div class="modal-body">
		<div id="mainContetntDetail" class="container-fluid text-for-popup">
			<div class="row">
				<div class="mark-div"><?=$arDayMarks["Mon"]; ?></div>
				<div class="col1-div"><?=getMessage("Mon"); ?></div>
			 	<div class="col2-div"><?=$arDayTimes["Mon"]?></div>
			</div>
			<div class="row">
			  <div class="mark-div"><?=$arDayMarks["Tue"]; ?></div>
			  <div class="col1-div"><?=getMessage("Tue"); ?></div>
			  <div class="col2-div"><?=$arDayTimes["Tue"]?></div>
			</div>
			<div class="row">
			  <div class="mark-div"><?=$arDayMarks["Wed"]; ?></div>
			  <div class="col1-div"><?=getMessage("Wed"); ?></div>
			  <div class="col2-div"><?=$arDayTimes["Wed"]?></div>
			</div>
			<div class="row">
			  <div class="mark-div"><?=$arDayMarks["Thu"]; ?></div>
			  <div class="col1-div"><?=getMessage("Thu"); ?></div>
			  <div class="col2-div"><?=$arDayTimes["Thu"]?></div>
			</div>
			<div class="row">
			  <div class="mark-div"><?=$arDayMarks["Fri"]; ?></div>
			  <div class="col1-div"><?=getMessage("Fri"); ?></div>
			  <div class="col2-div"><?=$arDayTimes["Fri"]?></div>
			</div>
			<div class="row">
			  <div class="mark-div"><?=$arDayMarks["Sat"]; ?></div>
			  <div class="col1-div"><?=getMessage("Sat"); ?></div>
			  <div class="col2-div"><?=$arDayTimes["Sat"]?></div>
			</div>
			<div class="row">
			  <div class="mark-div"><?=$arDayMarks["Sun"]; ?></div>
			  <div class="col1-div"><?=getMessage("Sun"); ?></div>
			  <div class="col2-div"><?=$arDayTimes["Sun"]?></div>
			</div>
	  	</div>
      </div>
	  </center>
		<div class="modal-footer" style="text-align:center">
        	<h3 class="modal-title" id="modalLabel" ><?=getMessage("CONTACT")?></h3><br>
			<div class="container-fluid" style="font-size:13pt">
				<? foreach($arResult["DISPLAY_PROPERTIES"]["PHONE"]["DISPLAY_VALUE"] as $phone):?>
					<a href="tel: <?=$phone?>"><?=$phone?></a><br>
				<? endforeach; ?>
			</div>
      </div>
    </div>
  </div>
</div>

<!-- Модальное окно для заявок -->
<div class="modal fade" id="callbackModalDetail" tabindex="-1" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
	<div id="callback-dialog" class="modal-dialog" style="transform:translate(0, 2%);">
		<div class="modal-content">
			<div class="modal-header" style="text-align:center;">
				<button style="font-size:20pt;" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 class="modal-title" id="modal-title"><?=$title?></h3>
			</div>
			<div class="modal-body">
				<input type="hidden" name="current_page" value="<?= $APPLICATION->GetCurPageParam("", array(), false) ?>">
				<div class="form-group">
					<label class="text-for-popup" for="full_name"><?= GetMessage("CALLBACK_FULL_NAME_TITLE")?></label>
					<span id="error_full_name" class="error-container"></span>
					<input name="full_name" value="" type="text" class="form-control">
				</div>
				<div class="form-group">
					<label class="text-for-popup" for="phone"><?= GetMessage("CALLBACK_PHONE_TITLE")?></label>
					<input style="width:100% !important" id="phone_field" name="phone" type="tel" value="" class="form-control">
				</div>
				<div class="form-group">
					<label class="text-for-popup" for="email"><?= GetMessage("CALLBACK_EMAIL_TITLE")?></label>
					<span id="error_email" class="error-container"></span>
					<input name="email" type="email" value="" class="form-control">
				</div>
				<div class="form-group">
					<label class="text-for-popup" for="citizenship"><?= GetMessage("CALLBACK_CITIZEN_TITLE")?></label>
					<span id="error_citizenship" class="error-container"></span>
					<select class="form-control" name="citizenship">
						<option selected><?=GetMessage("CALLBACK_CITIZEN_SELECT") ?></option>
						<? foreach($arResult["CITIZENSHIP"] as $ctzn): ?>
						<option><?=$ctzn["UF_NAME" . POSTFIX_PROPERTY] ?></option>
						<? endforeach; ?>
					</select>
				</div>
				<div class="form-group">
					<label class="text-for-popup" for="comment"><?=GetMessage("CALLBACK_COMMENT_TITLE")?></label>
					<span id="error_comment" class="error-container"></span>
					<textarea name="comment" class="form-control"></textarea>
				</div>
					<?$captcha = new CCaptcha();
					$captchaPass = COption::GetOptionString("main", "captcha_password", "");
					if(strlen($captchaPass) <= 0)
					{
						$captchaPass = randString(10);
						COption::SetOptionString("main", "captcha_password", $captchaPass);
					}
					$captcha->SetCodeCrypt($captchaPass);
					?>
				<div class="form-group has-feedback">
					<span id="error_captcha" class="error-container"></span>
					<div id="captchaDiv" style="display:flex">
						<img id="captchaBlock" width="28%" src="/bitrix/tools/captcha.php?captcha_code=<?=htmlspecialchars($captcha->GetCodeCrypt());?>"
							style="margin-right: 10px">
						<input placeholder="<?=GetMessage("CALLBACK_CAPTCHA_TITLE") ?>" style="width:70%" name="captcha" type="text" value="" class="form-control">
					</div>
					<input name="captcha_code" type="hidden" value="<?=htmlspecialchars($captcha->GetCodeCrypt());?>">
				</div>
				<div class="form-group" style="text-align:center;">
					<label class="text-for-popup" for="policy"><?= GetMessage("CALLBACK_POLICY_TEXT")?></label>
				</div>
			</div>
			<div class="modal-footer">
				<button onclick="yaCounter42451344.reachGoal('formsubmit'); return true;" type="button" id="sendForm" class="btn btn-primary"><?= GetMessage("CALLBACK_SEND_BTN_TITLE")?></button>
			</div>
		</div>
	</div>
</div>

<? $this->SetViewTarget("menu-item-detail"); ?>
<? if (!empty($scroll)): ?>

    <? foreach ($scroll as $s): ?>
        <? if (!empty($s)): ?>
            <li><a href="#<?= $s[0] ?>" class="anchor"><?= $s[1] ?></a></li>
        <? endif ?>
    <? endforeach ?>

<? endif ?>
<? $this->EndViewTarget(); ?>
<!-- snekbar -->

<script>
  (function () {
    function initPopover() {
      $('.service-accmd.popover-ul a').webuiPopover({
        placement: "right",
        trigger: "hover"
      });
    }
    initPopover();

  })();
</script>

<? if ($arParams["IBLOCK_ID"] == 79): ?>
    <script>
      function num2word(num, words, show_num = true) {

        var num_text = '';
        num = num % 100;
        if (num > 19) {
          num = num % 10;
        }

        if(show_num){
          num_text = num+" ";
        }
        else{
          num_text = "";
        }
        switch (num) {
          case 1: {
            return num_text+words[0];
          }
          case 2: case 3: case 4: {
            return num_text+words[1];
          }
          default: {
            return num_text+words[2];
          }
        }

      }

      (function ($) {

		var isMobile = navigator.userAgent.match(/Android/i) || 
				navigator.userAgent.match(/BlackBerry/i) || 
				navigator.userAgent.match(/iPhone|iPad|iPod/i) || 
				navigator.userAgent.match(/Opera Mini/i) || 
				navigator.userAgent.match(/IEMobile/i);

		if(isMobile)
		{
			$('.modal-dialog').attr('style', 'left:0.5%;top:42%;transform:translate(0,-40%);');
			$('.btn-for-popup').attr('style', 'display:block;width:80%;margin-left:10%;padding:15px;margin-top:10px');

			$('#callback-dialog').attr('style', 'top:57%;transform:translate(0,-40%);');
			$('#captchaDiv img').attr('width','40%');
			$('#captchaDiv input').attr('style','width:58%');

			$('#before_btn_transfer').attr('style', 'width:100%;padding-bottom:1.1%;padding-top:1%;color:#1E3C6E;background-color:#e5f4fa');
			$('.btnTransferChng').attr('style', 'background-color:#e5f4fa;width:100%;float:none');
            $('.btnTransferChng').append('<br />');
            $('#btn_transfer').attr('width', '50%');
            $('#btn_transfer').wrap('<center></center>');
		}

		$("#phone_field").intlTelInput({
			utilsScript: "<?=$templateFolder . '/js/utils.min.js'?>",
			separateDialCode: true,
			geoIpLookup: function(success, failure) {
				$.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
				  var countryCode = (resp && resp.country) ? resp.country : "";
				  success(countryCode);
				});
			},
			initialCountry: "auto",
			preferredCountries: ["by", "ru"]
		});

		$(".iti").css("display", "block");

		$(".iti__selected-flag").on('click', function(){
			$("#phone_field").blur();
			$("#phone_field").val("");
			$("#phone_field").unmask();
		});

		$("#phone_field").on('focus', function(){
			var mask = $("#phone_field").attr("placeholder").replace(/\d/gi, '9');
			$("#phone_field").mask(mask);
			console.log(mask);
		});

		$('#sendForm').on('click', function()
		{
			var full_name = $('input[name="full_name"]').val();
			var phone = $("#phone_field").intlTelInput("getNumber");
			var email = $('input[name="email"]').val();
			var citizenship = $('select[name="citizenship"]').children("option:selected").val();
			var comment = $('.form-group textarea').val();
			var capthca = $('input[name="captcha"]').val();
			var capthcaCode = $('input[name="captcha_code"]').val();
			var is_error = false;

			if(full_name)
			{ 
				$('#error_full_name').text(''); 
			}
			else
			{ 
				$('#error_full_name').text('<?=GetMessage("CALLBACK_FULL_NAME_ERROR")?>');
				is_error = true;
			}

			if(email)
			{
				const re = /^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/;
				if(re.test(email))
					$('#error_email').text('');
				else
				{
					$('#error_email').text('<?=GetMessage("CALLBACK_EMAIL_ERROR")?>');
					is_error = true;
				}
			}
			else
			{
				$('#error_email').text('<?=GetMessage("CALLBACK_EMAIL_EMPTY_ERROR")?>');
				is_error = true;
			}

			if(citizenship.indexOf("Ваше") == -1 && citizenship.indexOf("Ваша") == -1 && citizenship.indexOf("Your") == -1)
			{
				$('#error_citizenship').text('');
			}
			else
			{
				$('#error_citizenship').text('<?=GetMessage("CALLBACK_CITIZEN_ERROR")?>');
				is_error = true;
			}

			if(comment)
			{
				$('#error_comment').text(''); 
			}
			else
			{
				$('#error_comment').text('<?=GetMessage("CALLBACK_COMMENT_ERROR")?>');
				is_error = true;
			}

			if(!is_error)
			{
				$.ajax({
					url: '<?=$templateFolder?>/ajax.php',
					type: 'post',
					cache: false,
					data: {"fio":full_name, "phone":phone, "email":email, "citizenship":citizenship,
						   "comment":comment, "capthca":capthca, "captcha_code":capthcaCode,
						   "email_to":"<?=$arResult['DISPLAY_PROPERTIES']['EMAIL']['DISPLAY_VALUE']?>" },
					success: function(data){
						if (data.indexOf('errorCaptcha') != -1)
						{
							var captchaNewCode = data.split('_')[1];

							$('#error_captcha').text('<?=GetMessage("CALLBACK_CAPTCHA_ERROR")?>');
							$('#captchaBlock').attr('src', '/bitrix/tools/captcha.php?captcha_code=' + captchaNewCode);
							$('input[name="captcha_code"]').val(captchaNewCode);
	
						} else 
						{
							$('#callbackModalDetail .modal-body').html('<span class=\"ok-container\"><?=GetMessage("CALLBACK_OK")?></span>');
							$('#callbackModalDetail .modal-footer').text('');

						}
					},
					error: function(jqxhr, status, exception) {
							 alert('Exception:', exception);
					}
				});

			}

		});

        $("#show-medservice-popup").magnificPopup({
          type: "inline",
          midClick: true
        });

        var medServicesAr = <?= \Bitrix\Main\Web\Json::encode($arResult["DISPLAY_PROPERTIES"]["MED_SERVICES"]["DESC"]) ?>;
        var medServicesVideoAr = <?= \Bitrix\Main\Web\Json::encode($arResult["MED_SERVICES_VIDEO"]) ?>;

        var medServiceHtml = '<div class="row form bg-none pad20"><h3 class="bx-title">#medservice_title#</h3><div class="col-md-12">#medservice_content#</div></div>';
        var medServiceHtml_ = '';

        $("#show-medservice-popup.medservice").on("click", function () {

          var medServiceId = $(this).data('id');

          if (typeof medServiceId !== "undefined") {

            medServiceHtml_ = medServiceHtml.replace("#medservice_title#", medServicesAr[medServiceId]["NAME"]);
            medServiceHtml_ = medServiceHtml_.replace("#medservice_content#", medServicesAr[medServiceId]["DESCRIPTION"]);

          } else {

            var medServiceVideoId = $(this).data('video');
            var htmlVideo = '<div class="video-block"><iframe width="100%" style="border: none;" src="https://www.youtube.com/embed/' + medServicesVideoAr[medServiceVideoId]["YOUTUBE_CODE"] + '" allowfullscreen=""></iframe></div>';

            medServiceHtml_ = medServiceHtml.replace("#medservice_title#", '<?= GetMessage('VIDEO') ?>: ' + medServicesVideoAr[medServiceVideoId]["NAME"]);
            medServiceHtml_ = medServiceHtml_.replace("#medservice_content#", htmlVideo);

          }

          $("#medservice-popup").html(medServiceHtml_);

          $("#show-medservice-popup").magnificPopup("open");

        });

      })(jQuery);
    </script>
<?
endif?>
