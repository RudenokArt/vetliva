<style>
input[type=submit] {
    padding:5px 15px; 
    background:#ccc; 
    border:0 none;
    cursor:pointer;
    -webkit-border-radius: 5px;
    border-radius: 5px; 
}
input[type=submit]:hover {
	box-shadow: 0 0 0 3px rgba(38, 75, 135, .7);
}
.custom_radio {
  position: absolute;
  z-index: -1;
  opacity: 0;
  margin: 10px 0 0 7px;
}
.custom_radio + label {
  position: relative;
  padding: 0 0 0 35px;
  cursor: pointer;
}
.custom_radio + label:before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 24px;
  height: 22px;
  border: 1px solid #858585;
  border-radius: 50%;
  background: #FFF;
}
.custom_radio + label:after {
  content: '';
  position: absolute;
  top: 3px;
  left: 4px;
  width: 16px;
  height: 16px;
  border-radius: 50%;
  background: #264B87;
  box-shadow: inset 0 1px 1px rgba(0,0,0,.5);
  opacity: 0;
  transition: .3s;
}
.custom_radio:checked + label:after {
  opacity: 1;
}
.custom_radio:focus + label:before {
  box-shadow: 0 0 0 3px rgba(133, 133, 133, .2);
}
</style>
<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!empty($arResult["ERROR_MESSAGE"])): 
?>
<div class="vote-note-box vote-note-error">
	<div class="vote-note-box-text"><?=ShowError($arResult["ERROR_MESSAGE"])?></div>
</div>
<?
endif;

if (!empty($arResult["OK_MESSAGE"])): 
?>
<div lass="vote-note-box vote-note-note">
	<div class="vote-note-box-text"><?=ShowNote($arResult["OK_MESSAGE"])?></div>
</div>
<?
endif;

if (empty($arResult["VOTE"])):
	return false;
elseif (empty($arResult["QUESTIONS"])):
	return true;
endif;

?>
<div class="voting-form-box">
<form action="<?=POST_FORM_ACTION_URI?>" method="post" class="vote-form">
	<input type="hidden" name="vote" value="Y">
	<input type="hidden" name="PUBLIC_VOTE_ID" value="<?=$arResult["VOTE"]["ID"]?>">
	<input type="hidden" name="VOTE_ID" value="<?=$arResult["VOTE"]["ID"]?>">
	<?=bitrix_sessid_post()?>
	
	<div class="vote-items-list vote-question-list">
<?
		$arQuestion = $arResult["QUESTIONS"][1];
?>
			<div class="vote-item-header">
<?
				if ($arQuestion["IMAGE"] !== false):
?>
					<div class="vote-item-image">
						<img src="<?=$arQuestion["IMAGE"]["SRC"]?>" width="30" height="30" />
					</div>
<?
				endif;
?>
				<!-- Вывод вопроса-->
				<div class="vote-item-title vote-item-question">
					<?=GetMessage("QUSTION")?>
					<?//if($arQuestion["REQUIRED"]=="Y"){echo "<span class='starrequired'>*</span>";}?>
				</div><br />

				<img src="<?=SITE_TEMPLATE_PATH."/components/bitrix/voting.form/images/9.jpg" ?>" width="49%" />
				<img src="<?=SITE_TEMPLATE_PATH."/components/bitrix/voting.form/images/19.jpg" ?>" width="49%"/>

			</div>
<?		//Вывод ответов
		$ind_a = 1;
		foreach ($arQuestion["ANSWERS"] as $arAnswer):

			$value=(isset($_REQUEST['vote_radio_'.$arAnswer["QUESTION_ID"]]) && 
				$_REQUEST['vote_radio_'.$arAnswer["QUESTION_ID"]] == $arAnswer["ID"]) ? 'checked="checked"' : '';
?>
				<div style="width:49%;float:left" class="vote-answer-item vote-answer-item-radio">
					<center>
					<input class="custom_radio" type="radio" <?=$value?> name="vote_radio_<?=$arAnswer["QUESTION_ID"]?>" <?
						?>id="vote_radio_<?=$arAnswer["QUESTION_ID"]?>_<?=$arAnswer["ID"]?>" <?
						?>value="<?=$arAnswer["ID"]?>" <?=$arAnswer["~FIELD_PARAM"]?> />
						<label for="vote_radio_<?=$arAnswer["QUESTION_ID"]?>_<?=$arAnswer["ID"]?>">
							<?=(LANGUAGE_ID=="by")?GetMessage("ANSW".$ind_a):$arAnswer["MESSAGE"]?>
						</label>
					</center>
				</div>
<?
		$ind_a++;
		endforeach;
?>
	</div>

<? if (isset($arResult["CAPTCHA_CODE"])):  ?>
<div class="vote-item-header">
	<div class="vote-item-title vote-item-question"><?=GetMessage("F_CAPTCHA_TITLE")?></div>
	<div class="vote-clear-float"></div>
</div>
<div class="vote-form-captcha">
	<input type="hidden" name="captcha_code" value="<?=$arResult["CAPTCHA_CODE"]?>"/>
	<div class="vote-reply-field-captcha-image">
		<img src="/bitrix/tools/captcha.php?captcha_code=<?=$arResult["CAPTCHA_CODE"]?>" alt="<?=GetMessage("F_CAPTCHA_TITLE")?>" />
	</div>
	<div class="vote-reply-field-captcha-label">
		<label for="captcha_word"><?=GetMessage("F_CAPTCHA_PROMT")?><span class='starrequired'>*</span></label><br />
		<input type="text" size="20" name="captcha_word" />
	</div>
</div>
<? endif // CAPTCHA_CODE ?>

<div class="vote-form-box-buttons vote-vote-footer">
	<center>
	<span class="vote-form-box-button vote-form-box-button-first"><input type="submit" name="vote" value="<?=GetMessage("VOTE_SUBMIT_BUTTON")?>" /></span>
<?/*?>	<span class="vote-form-box-button vote-form-box-button-last"><input type="reset" value="<?=GetMessage("VOTE_RESET")?>" /></span><?*/?>
	<span class="vote-form-box-button vote-form-box-button-last">
		<a name="show_result" <?
			?>href="<?=$arResult["URL"]["RESULT"]?>"><?=GetMessage("VOTE_RESULTS")?></a>
	</span>
	</center>
</div>
</form>
</div>