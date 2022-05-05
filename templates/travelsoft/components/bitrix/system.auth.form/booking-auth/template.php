<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if($arResult["FORM_TYPE"] == "login"): ?>
<div class="bx-authform">
<?
if($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR'] && $_POST["TYPE"] == "AUTH"):
	$text = str_replace(array("<br>", "<br />"), "\n", $arResult['ERROR_MESSAGE']["MESSAGE"]);
?>
	<div class="alert alert-danger"><?=nl2br(htmlspecialcharsbx($text))?></div>
<?endif?>

	

	<form name="form_auth" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
            <input name="popup_auth_form" value="Y" type="hidden">
		<input type="hidden" name="AUTH_FORM" value="Y" />
		<input type="hidden" name="TYPE" value="AUTH" />
<?if (strlen($arResult["BACKURL"]) > 0):?>
		<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?endif?>
<?foreach ($arResult["POST"] as $key => $value):?>
		<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
<?endforeach?>
        <div class="user_email_exist" style="display: none;"><?=GetMessage('user_email_exist')?></div>
		<div class="bx-authform-formgroup-container">
			<div class="bx-authform-input-container">
				<input type="text" name="USER_LOGIN" placeholder="<?=GetMessage("AUTH_LOGIN")?>" maxlength="255" value="<?=$arResult["LAST_LOGIN"]?>" />
			</div>
		</div>
		<div class="bx-authform-formgroup-container">
			<div class="bx-authform-input-container">
<?if($arResult["SECURE_AUTH"]):?>
				<div class="bx-authform-psw-protected" id="bx_auth_secure" style="display:none"><div class="bx-authform-psw-protected-desc"><span></span><?echo GetMessage("AUTH_SECURE_NOTE")?></div></div>

<script type="text/javascript">
document.getElementById('bx_auth_secure').style.display = '';
</script>
<?endif?>
				<input type="password" name="USER_PASSWORD" maxlength="255" autocomplete="off" placeholder="<?=GetMessage("AUTH_PASSWORD")?>" />
			</div>
		</div>

<?if($arResult["CAPTCHA_CODE"]):?>
		<input type="hidden" name="captcha_sid" value="<?echo $arResult["CAPTCHA_CODE"]?>" />

		<div class="bx-authform-formgroup-container dbg_captha">
			<div class="bx-authform-label-container">
				<?echo GetMessage("AUTH_CAPTCHA_PROMT")?>
			</div>
			<div class="bx-captcha"><img src="/bitrix/tools/captcha.php?captcha_sid=<?echo $arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" /></div>
			<div class="bx-authform-input-container">
				<input type="text" name="captcha_word" maxlength="50" value="" autocomplete="off" />
			</div>
		</div>
<?endif;?>

<?if ($arResult["STORE_PASSWORD"] == "Y"):?>
		<div class="bx-authform-formgroup-container formgroup-forgotpsw">
			<div class="checkbox">
				<label class="bx-filter-param-label">
					<input type="checkbox" id="USER_REMEMBER" name="USER_REMEMBER" value="Y" />
					<span class="bx-filter-param-text"><?=GetMessage("AUTH_REMEMBER_ME")?></span>
				</label>
			</div>
		</div>
<?endif?>
		<div class="submit-auth_row">
			
			<div class="col-md-6">
				<input type="submit" class="btn btn-primary" name="Login" value="<?=GetMessage("AUTH_LOGIN_BUTTON")?>" />
			</div>
			<?if ($arParams["NOT_SHOW_LINKS"] != "Y"):?>
			<noindex>
				<div class="bx-authform-link-container col-md-12">
					<a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" rel="nofollow"><?=GetMessage("AUTH_FORGOT_PASSWORD_2")?></a>
				</div>
			</noindex>
			<?endif?>
			
		</div>
			
	</form>
<div class="auth-footer auth">
    <div class="left-text"><?=GetMessage("NO_ACC")?></div>
    <div class="right-text"><a class="enter-button" data-toggle="tab" onclick="$('.registration-switch').click(); return false;" href="#booking-registration"><?=GetMessage("CREATE")?></a></div>
</div>
<?if($arResult["AUTH_SERVICES"]):?>
<?
// $APPLICATION->IncludeComponent("bitrix:socserv.auth.form",
// 	"booking-flat",
// 	array(
// 		"AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
// 		"AUTH_URL" => $arResult["AUTH_URL"],
// 		"POST" => $arResult["POST"],
// 	),
// 	$component,
// 	array("HIDE_ICONS"=>"Y")
// );
?>
<?endif?>

</div>
<script type="text/javascript">
<?if (strlen($arResult["LAST_LOGIN"])>0):?>
try{document.form_auth.USER_PASSWORD.focus();}catch(e){}
<?else:?>
try{document.form_auth.USER_LOGIN.focus();}catch(e){}
<?endif?>
</script>
<?endif?>