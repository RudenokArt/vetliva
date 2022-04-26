<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
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
$this->setFrameMode(false);
// библиотека модальных окон
$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/magnific-popup.css");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/jquery.magnific-popup.js");

$oAsset = \Bitrix\Main\Page\Asset::getInstance();

$oAsset->addCss("https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.min.css", true);
$oAsset->addJs("https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js", true);
?>
<style>
.btn-add-review-wrap {
   display: inline-block;
   padding: 0;
   margin: 0;
   width: 20%;
   background-position: center center;
   background-repeat: no-repeat;
}
.btn-add-review, .btn-add-review1 {
   border: 0;
   margin: 0;
   padding: 0;
}
.btn-add-review {
   opacity: 1.0;
   filter: alpha(opacity=99); /* IE */
   display: block;
   position: absolute;
   z-index: 2;
   width: 20%;
   transition-duration: 0.56s;
   -webkit-transition-duration: 0.56s;
   -moz-transition-duration: 0.56s;
   -o-transition-duration: 0.56s;
   -ms-transition-duration:  0.56s; /* IE9+ */
}
.btn-add-review:hover {
   opacity: 0.00;
   filter: alpha(opacity=00); /* IE */
   width: 20%;
}
</style>

<a id="show-add-review-popup" href="#add-review-popup">
	<div class="btn-add-review-wrap">
		<img class="btn-add-review" src="<?=SITE_TEMPLATE_PATH."/images/btn_add_review" . POSTFIX_PROPERTY .".png"?>">
		<img class="btn-add-review1" src="<?=SITE_TEMPLATE_PATH."/images/btn_add_review_up" . POSTFIX_PROPERTY .".png"?>">
	</div>
</a>
<div id="add-review-popup" class="add-review-form mfp-hide">
<div class="row form bg-none">
    <h3 class="bx-title"><?if(isset($arParams["TITLE_FORM_TEXT"]) && !empty($arParams["TITLE_FORM_TEXT"])):?><?=$arParams["TITLE_FORM_TEXT"]?><?else:?><?=GetMessage('ADD_REVIEW_TITLE')?><?endif;?></h3>

	<?if (!empty($arResult["ERRORS"])):?>
	<?
			$arErrors = array();
		foreach($arResult["ERRORS"] as $code) {
			if(GetMessage($code) != "")
				$arErrors[] = str_replace("#PROPERTY_NAME#", GetMessage($code), GetMessage("IBLOCK_ADD_ERROR_REQUIRED"));
			else
                $arErrors[] = $code;
		}
		if (!empty($arErrors)):?>
			<div class="alert alert-danger"><?ShowError(implode("<br />", $arErrors))?></div>
	<?endif?>
	<?/*<div class="alert alert-danger"><?ShowError(implode("<br />", $arResult["ERRORS"]))?></div>*/?>
<?endif;
if (strlen($arResult["MESSAGE"]) > 0):?>
    <?ShowNote($arResult["MESSAGE"])?>
<?endif?>
    <div class="col-md-12"><div class="container">
<form name="iblock_add" action="<?=POST_FORM_ACTION_URI?>" method="post" enctype="multipart/form-data">
	<?=bitrix_sessid_post()?>
    <input name="popup_add_review_form" value="Y" type="hidden">
	<?if ($arParams["MAX_FILE_SIZE"] > 0):?><input type="hidden" name="MAX_FILE_SIZE" value="<?=$arParams["MAX_FILE_SIZE"]?>" /><?endif?>

		<?if (is_array($arResult["PROPERTY_LIST"]) && !empty($arResult["PROPERTY_LIST"])):?>

            <?foreach ($arResult["PROPERTY_LIST"] as $pid=>$arProperty):?>
                <?$cnt = count($arResult["PROPERTY_LIST"][$pid]);
                if($pid != "PLACE" && $cnt == 2) $cnt = 3;
                $n = 12 / $cnt;?>
                <?if ($pid == "LIST_1"):?><p class="rating"><?=GetMessage('ADD_REVIEW_RATING')?></p><hr><?endif?>
                <?if ($pid == "PHOTO"):?><p class="rating"><?=GetMessage('PROPERTY_PHOTO')?></p><?endif?>
                <div class="row" <?if($pid == "USER" || $pid == "ITEM" || $pid == "NAME"):?>style="display: none<?endif?>">
                <?foreach ($arProperty as $propertyID):?>
                    <div class="col-md-<?=$n?> col-lg-<?=$n?>">

                    <div class="form-field">
                    <?if($arResult["PROPERTY_LIST_FULL"][$propertyID]["CODE"] != "USER" && $arResult["PROPERTY_LIST_FULL"][$propertyID]["CODE"] != "PHOTO" && $arResult["PROPERTY_LIST_FULL"][$propertyID]["CODE"] != "ITEM" && $arResult["PROPERTY_LIST_FULL"][$propertyID]["CODE"] != "RECOMMEND"):?>
					    <?if (intval($propertyID) > 0):?>
                            <?=GetMessage("PROPERTY_".$arResult["PROPERTY_LIST_FULL"][$propertyID]["CODE"])?>
                        <?else:?>
                            <?if($propertyID == "PREVIEW_TEXT"):?><?=GetMessage("PROPERTY_".$propertyID)?><?endif?>
                            <?/*=$arResult["PROPERTY_LIST_FULL"][$propertyID]["NAME"]?>
                        <?else:?>
                            <?=!empty($arParams["CUSTOM_TITLE_".$propertyID]) ? $arParams["CUSTOM_TITLE_".$propertyID] : GetMessage("IBLOCK_FIELD_".$propertyID)*/?>
                        <?endif?><?if(in_array($propertyID, $arResult["PROPERTY_REQUIRED"])):?><span class="starrequired">*</span><?endif?>
                    <?endif?>
						<?
						if (intval($propertyID) > 0)
						{
							if (
								$arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"] == "T"
								&&
								$arResult["PROPERTY_LIST_FULL"][$propertyID]["ROW_COUNT"] == "1"
							)
								$arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"] = "S";
							elseif (
								(
									$arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"] == "S"
									||
									$arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"] == "N"
								)
								&&
								$arResult["PROPERTY_LIST_FULL"][$propertyID]["ROW_COUNT"] > "1"
							)
								$arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"] = "T";
						}
						elseif (($propertyID == "TAGS") && CModule::IncludeModule('search'))
							$arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"] = "TAGS";

						if ($arResult["PROPERTY_LIST_FULL"][$propertyID]["MULTIPLE"] == "Y")
						{
							$inputNum = ($arParams["ID"] > 0 || count($arResult["ERRORS"]) > 0) ? count($arResult["ELEMENT_PROPERTIES"][$propertyID]) : 0;
							$inputNum += $arResult["PROPERTY_LIST_FULL"][$propertyID]["MULTIPLE_CNT"];
						}
						else
						{
							$inputNum = 1;
						}

						if($arResult["PROPERTY_LIST_FULL"][$propertyID]["GetPublicEditHTML"]){
						    if ($arResult["PROPERTY_LIST_FULL"][$propertyID]["USER_TYPE"] = "Date")
                                $INPUT_TYPE = $arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"];
						    else
                                $INPUT_TYPE = "USER_TYPE";
                        }
						else
							$INPUT_TYPE = $arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"];


						switch ($INPUT_TYPE):
							case "USER_TYPE":
								for ($i = 0; $i<$inputNum; $i++)
								{
									if ($arParams["ID"] > 0 || count($arResult["ERRORS"]) > 0)
									{
										$value = intval($propertyID) > 0 ? $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["~VALUE"] : $arResult["ELEMENT"][$propertyID];
										$description = intval($propertyID) > 0 ? $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["DESCRIPTION"] : "";
									}
									elseif ($i == 0)
									{
										$value = intval($propertyID) <= 0 ? "" : $arResult["PROPERTY_LIST_FULL"][$propertyID]["DEFAULT_VALUE"];
										$description = "";
									}
									else
									{
										$value = "";
										$description = "";
									}
									echo call_user_func_array($arResult["PROPERTY_LIST_FULL"][$propertyID]["GetPublicEditHTML"],
										array(
											$arResult["PROPERTY_LIST_FULL"][$propertyID],
											array(
												"VALUE" => $value,
												"DESCRIPTION" => $description,
											),
											array(
												"VALUE" => "PROPERTY[".$propertyID."][".$i."][VALUE]",
												"DESCRIPTION" => "PROPERTY[".$propertyID."][".$i."][DESCRIPTION]",
												"FORM_NAME"=>"iblock_add",
											),
										));
								?><br /><?
								}
							break;
							case "TAGS":
								$APPLICATION->IncludeComponent(
									"bitrix:search.tags.input",
									"",
									array(
										"VALUE" => $arResult["ELEMENT"][$propertyID],
										"NAME" => "PROPERTY[".$propertyID."][0]",
										"TEXT" => 'size="'.$arResult["PROPERTY_LIST_FULL"][$propertyID]["COL_COUNT"].'"',
									), null, array("HIDE_ICONS"=>"Y")
								);
								break;
							case "HTML":
								$LHE = new CHTMLEditor;
								$LHE->Show(array(
									'name' => "PROPERTY[".$propertyID."][0]",
									'id' => preg_replace("/[^a-z0-9]/i", '', "PROPERTY[".$propertyID."][0]"),
									'inputName' => "PROPERTY[".$propertyID."][0]",
									'content' => $arResult["ELEMENT"][$propertyID],
									'width' => '100%',
									'minBodyWidth' => 350,
									'normalBodyWidth' => 555,
									'height' => '200',
									'bAllowPhp' => false,
									'limitPhpAccess' => false,
									'autoResize' => true,
									'autoResizeOffset' => 40,
									'useFileDialogs' => false,
									'saveOnBlur' => true,
									'showTaskbars' => false,
									'showNodeNavi' => false,
									'askBeforeUnloadPage' => true,
									'bbCode' => false,
									'siteId' => SITE_ID,
									'controlsMap' => array(
										array('id' => 'Bold', 'compact' => true, 'sort' => 80),
										array('id' => 'Italic', 'compact' => true, 'sort' => 90),
										array('id' => 'Underline', 'compact' => true, 'sort' => 100),
										array('id' => 'Strikeout', 'compact' => true, 'sort' => 110),
										array('id' => 'RemoveFormat', 'compact' => true, 'sort' => 120),
										array('id' => 'Color', 'compact' => true, 'sort' => 130),
										array('id' => 'FontSelector', 'compact' => false, 'sort' => 135),
										array('id' => 'FontSize', 'compact' => false, 'sort' => 140),
										array('separator' => true, 'compact' => false, 'sort' => 145),
										array('id' => 'OrderedList', 'compact' => true, 'sort' => 150),
										array('id' => 'UnorderedList', 'compact' => true, 'sort' => 160),
										array('id' => 'AlignList', 'compact' => false, 'sort' => 190),
										array('separator' => true, 'compact' => false, 'sort' => 200),
										array('id' => 'InsertLink', 'compact' => true, 'sort' => 210),
										array('id' => 'InsertImage', 'compact' => false, 'sort' => 220),
										array('id' => 'InsertVideo', 'compact' => true, 'sort' => 230),
										array('id' => 'InsertTable', 'compact' => false, 'sort' => 250),
										array('separator' => true, 'compact' => false, 'sort' => 290),
										array('id' => 'Fullscreen', 'compact' => false, 'sort' => 310),
										array('id' => 'More', 'compact' => true, 'sort' => 400)
									),
								));
								break;

							case "S":
							case "E":
							case "N":
								for ($i = 0; $i<$inputNum; $i++)
								{
									if ($arParams["ID"] > 0 || count($arResult["ERRORS"]) > 0)
									{
									    $value = intval($propertyID) > 0 ? $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["VALUE"] : $arResult["ELEMENT"][$propertyID];
									}
									elseif ($i == 0)
									{
										$value = intval($propertyID) <= 0 ? "" : $arResult["PROPERTY_LIST_FULL"][$propertyID]["DEFAULT_VALUE"];
                                        if($arResult["PROPERTY_LIST_FULL"][$propertyID]["CODE"] == "USER" && $value == "")
                                            $value = $USER->GetID();
                                        elseif($arResult["PROPERTY_LIST_FULL"][$propertyID]["CODE"] == "USER_NAME" && $value == "" && $USER->IsAuthorized())
                                            $value = $USER->GetFullName();
                                        elseif($arResult["PROPERTY_LIST_FULL"][$propertyID]["CODE"] == "ITEM" && $value == "")
                                            $value = $arParams["ELEMENT_ID"];
									}
									else
									{
									        $value = "";
									}
								?>
								<input type="<?if($arResult["PROPERTY_LIST_FULL"][$propertyID]["CODE"] == "USER" || $arResult["PROPERTY_LIST_FULL"][$propertyID]["CODE"] == "NAME"):?>hidden<?else:?>text<?endif?>" name="PROPERTY[<?=$propertyID?>][<?=$i?>]" size="25" value="<?=$value?>" class="field-input review<?if($arResult["PROPERTY_LIST_FULL"][$propertyID]["USER_TYPE"] == "Date"):?> date<?endif?>" /><?
								if($arResult["PROPERTY_LIST_FULL"][$propertyID]["USER_TYPE"] == "DateTime" || $arResult["PROPERTY_LIST_FULL"][$propertyID]["USER_TYPE"] == "Date"):?>
                                    <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span><?
								endif
                                    ?>
                                <br /><?
								}
							break;

							case "F":
							    if($arResult["PROPERTY_LIST_FULL"][$propertyID]["CODE"] == "PHOTO")

								for ($i = 0; $i<$inputNum; $i++)
								{
									$value = intval($propertyID) > 0 ? $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["VALUE"] : $arResult["ELEMENT"][$propertyID];
									?>
						<input type="hidden" name="PROPERTY[<?=$propertyID?>][<?=$arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["VALUE_ID"] ? $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["VALUE_ID"] : $i?>]" value="<?=$value?>" />
						<input type="file" size="<?=$arResult["PROPERTY_LIST_FULL"][$propertyID]["COL_COUNT"]?>"  name="PROPERTY_FILE_<?=$propertyID?>_<?=$arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["VALUE_ID"] ? $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["VALUE_ID"] : $i?>" /><br />
									<?

									if (!empty($value) && is_array($arResult["ELEMENT_FILES"][$value]))
									{
										?>
					<input type="checkbox" name="DELETE_FILE[<?=$propertyID?>][<?=$arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["VALUE_ID"] ? $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["VALUE_ID"] : $i?>]" id="file_delete_<?=$propertyID?>_<?=$i?>" value="Y" /><label for="file_delete_<?=$propertyID?>_<?=$i?>"><?=GetMessage("IBLOCK_FORM_FILE_DELETE")?></label><br />
										<?

										if ($arResult["ELEMENT_FILES"][$value]["IS_IMAGE"])
										{
											?>
					<img src="<?=$arResult["ELEMENT_FILES"][$value]["SRC"]?>" height="<?=$arResult["ELEMENT_FILES"][$value]["HEIGHT"]?>" width="<?=$arResult["ELEMENT_FILES"][$value]["WIDTH"]?>" border="0" /><br />
											<?
										}
										else
										{
											?>
					<?=GetMessage("IBLOCK_FORM_FILE_NAME")?>: <?=$arResult["ELEMENT_FILES"][$value]["ORIGINAL_NAME"]?><br />
					<?=GetMessage("IBLOCK_FORM_FILE_SIZE")?>: <?=$arResult["ELEMENT_FILES"][$value]["FILE_SIZE"]?> b<br />
					[<a href="<?=$arResult["ELEMENT_FILES"][$value]["SRC"]?>"><?=GetMessage("IBLOCK_FORM_FILE_DOWNLOAD")?></a>]<br />
											<?
										}
									}
								}

							break;
                            case "L":

                                if ($arResult["PROPERTY_LIST_FULL"][$propertyID]["LIST_TYPE"] == "C" || $arResult["PROPERTY_LIST_FULL"][$propertyID]["CODE"] == "RECOMMEND")
                                    $type = "checkbox"; //$type = $arResult["PROPERTY_LIST_FULL"][$propertyID]["MULTIPLE"] == "Y" ? "checkbox" : "radio";
                                else
                                    $type = $arResult["PROPERTY_LIST_FULL"][$propertyID]["MULTIPLE"] == "Y" ? "multiselect" : "dropdown";

                                switch ($type):
                                    case "checkbox":
                                    case "radio":
                                        foreach ($arResult["PROPERTY_LIST_FULL"][$propertyID]["ENUM"] as $key => $arEnum)
                                        {
                                            $checked = false;
                                            if ($arParams["ID"] > 0 || count($arResult["ERRORS"]) > 0)
                                            {
                                                if (is_array($arResult["ELEMENT_PROPERTIES"][$propertyID]))
                                                {
                                                    foreach ($arResult["ELEMENT_PROPERTIES"][$propertyID] as $arElEnum)
                                                    {
                                                        if ($arElEnum["VALUE"] == $key)
                                                        {
                                                            $checked = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                            }
                                            else
                                            {
                                                if ($arEnum["DEF"] == "Y") $checked = true;
                                            }

                                            ?>
                                            <?if($arResult["PROPERTY_LIST_FULL"][$propertyID]["CODE"] == "RECOMMEND"):?>
                                                <input type="<?=$type?>" name="PROPERTY[<?=$propertyID?>]" value="<?=$key?>" id="property_<?=$key?>"<?=$checked ? " checked=\"checked\"" : ""?> /><label for="property_<?=$key?>"></label><?if (intval($propertyID) > 0):?><?=GetMessage("PROPERTY_".$arResult["PROPERTY_LIST_FULL"][$propertyID]["CODE"])?><?endif?><?if(in_array($propertyID, $arResult["PROPERTY_REQUIRED"])):?><span class="starrequired">*</span><?endif?><br />
                                            <?else:?>
                                                <input type="<?=$type?>" name="PROPERTY[<?=$propertyID?>]<?=$type == "checkbox" ? "[".$key."]" : ""?>" value="<?=$key?>" id="property_<?=$key?>"<?=$checked ? " checked=\"checked\"" : ""?> /><label for="property_<?=$key?>"><?=$arEnum["VALUE"]?></label><br />
                                            <?endif?>
                                            <?
                                        }
                                        break;

                                    case "dropdown":
                                    case "multiselect":
                                        ?><div style="clear: both"></div>
                                        <select name="PROPERTY[<?=$propertyID?>]<?=$type=="multiselect" ? "[]\" size=\"".$arResult["PROPERTY_LIST_FULL"][$propertyID]["ROW_COUNT"]."\" multiple=\"multiple" : ""?>">
                                            <option value=""><?echo GetMessage("CT_BIEAF_PROPERTY_VALUE_NA")?></option>
                                            <?
                                            if (intval($propertyID) > 0) $sKey = "ELEMENT_PROPERTIES";
                                            else $sKey = "ELEMENT";

                                            foreach ($arResult["PROPERTY_LIST_FULL"][$propertyID]["ENUM"] as $key => $arEnum)
                                            {
                                                $checked = false;
                                                if ($arParams["ID"] > 0 || count($arResult["ERRORS"]) > 0)
                                                {
                                                    foreach ($arResult[$sKey][$propertyID] as $elKey => $arElEnum)
                                                    {
                                                        if ($key == $arElEnum["VALUE"])
                                                        {
                                                            $checked = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                else
                                                {
                                                    if ($arEnum["DEF"] == "Y") $checked = true;
                                                }
                                                ?>
                                                <option value="<?=$key?>" <?=$checked ? " selected=\"selected\"" : ""?>><?=$arEnum["VALUE"]?></option>
                                                <?
                                            }
                                            ?>
                                        </select>
                                        <?
                                        break;

                                endswitch;
                            break;
                            case "T":
                                for ($i = 0; $i<$inputNum; $i++)
                                {

                                    if ($arParams["ID"] > 0 || count($arResult["ERRORS"]) > 0)
                                    {
                                        $value = intval($propertyID) > 0 ? $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["VALUE"] : $arResult["ELEMENT"][$propertyID];
                                    }
                                    elseif ($i == 0)
                                    {
                                        $value = intval($propertyID) > 0 ? "" : $arResult["PROPERTY_LIST_FULL"][$propertyID]["DEFAULT_VALUE"];
                                    }
                                    else
                                    {
                                        $value = "";
                                    }
                                    ?>
                                    <textarea cols="<?=$arResult["PROPERTY_LIST_FULL"][$propertyID]["COL_COUNT"]?>" rows="<?=$arResult["PROPERTY_LIST_FULL"][$propertyID]["ROW_COUNT"]?>" name="PROPERTY[<?=$propertyID?>][<?=$i?>]"><?=$value?></textarea>
                                    <?
                                }
                            break;

						endswitch;?>

                    </div>
                    </div>
                    <?endforeach;?>
                </div>
                <?if ($pid == "RECOMMEND"):?><hr><?endif?>
			<?endforeach;?>

			<?if($arParams["USE_CAPTCHA"] == "Y" && $arParams["ID"] <= 0):?>

                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <div class="form-field">
                            <?=GetMessage("IBLOCK_FORM_CAPTCHA_TITLE")?><span class="starrequired">*</span>
                            <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
                            <img style="float: right;" src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
                            <br>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <div class="form-field">
                            <?=GetMessage("IBLOCK_FORM_CAPTCHA_PROMPT")?><span class="starrequired">*</span>
                            <input type="text" name="captcha_word" maxlength="50" value="" class="field-input review">
                            <br>
                        </div>
                    </div>
                </div>
					<?/*=GetMessage("IBLOCK_FORM_CAPTCHA_TITLE")*/?><!--

						<input type="hidden" name="captcha_sid" value="<?/*=$arResult["CAPTCHA_CODE"]*/?>" />
						<img src="/bitrix/tools/captcha.php?captcha_sid=<?/*=$arResult["CAPTCHA_CODE"]*/?>" width="180" height="40" alt="CAPTCHA" />

					<?/*=GetMessage("IBLOCK_FORM_CAPTCHA_PROMPT")*/?><span class="starrequired">*</span>:
					<input type="text" name="captcha_word" maxlength="50" value="">-->

			<?endif?>

		<?endif?>

					<input type="submit" name="iblock_submit" value="<?if(isset($arParams["SUBMIT_TEXT"]) && !empty($arParams["SUBMIT_TEXT"])):?><?=$arParams["SUBMIT_TEXT"]?><?else:?><?=GetMessage("IBLOCK_FORM_SUBMIT")?><?endif?>" class="awe-btn awe-btn-5 arrow-right awe-btn-lager text-uppercase float-right mt-20 mr-20" />
					<?if (strlen($arParams["LIST_URL"]) > 0):?>
						<input type="submit" name="iblock_apply" value="<?=GetMessage("IBLOCK_FORM_APPLY")?>" />
						<input
							type="button"
							name="iblock_cancel"
							value="<? echo GetMessage('IBLOCK_FORM_CANCEL'); ?>"
							onclick="location.href='<? echo CUtil::JSEscape($arParams["LIST_URL"])?>';"
						>
					<?endif?>

</form>
</div></div>

    </div>
</div>
<script>
    (function ($) {
        $("#show-add-review-popup").magnificPopup({
            type: "inline",
            midClick: true
        });

        <?if (($arResult['ERRORS'] && $_POST["popup_add_review_form"] == "Y") || $_GET["add_review_form"] == "Y"):?>
            $("#show-add-review-popup").magnificPopup("open");
        <?endif?>

        $.datetimepicker.setLocale('<?= LANGUAGE_ID == "by" ? "ru": LANGUAGE_ID?>');
        $("input.date").one("click", function () {
            $(this).datetimepicker({
                timepicker: false,
                format: "d.m.Y"
            });
        });
		//удалить get параметр после закрытия формы во избежания повторного показа при перезагрузке страницы
		$('.mfp-close').one("click", function(){
  			var url = window.location.href;
			url = url.replace("&add_review_form=Y", "")
			window.history.pushState({}, document.title, url);
		});
    })(jQuery);
</script>