<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

//CJSCore::Init();

// библиотека модальных окон
$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/magnific-popup.css");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/jquery.magnific-popup.js");

// css flat шаблона
$APPLICATION->SetAdditionalCSS("/bitrix/css/main/system.auth/flat/style.css");

?>

<?if($arResult["FORM_TYPE"] == "login"): ?>
	<?if ($arParams['IS_MOBILE'] != 'Y'):?>
		<a class="show-header-auth-popup registration-link" href="#header-register-popup">
			<?= GetMessage("REGISTRATION")?>
		</a>
	
		<a class="show-header-auth-popup auth-link" href="#header-auth-popup">
			<?= GetMessage("LOGIN_MESSAGE")?>
		</a>
		
		
	<?else:?>
	
						<a class="show-header-auth-popup registration-link" href="#header-register-popup">
							<?= GetMessage("REGISTRATION")?>
						</a>
						<a class="show-header-auth-popup auth-link more-btn" href="#header-auth-popup">
							<?= GetMessage("LOGIN_MESSAGE")?>
						</a>
						
			
		
		<script>				
				$(document).ready(function (){
							
						$(".show-header-auth-popup.auth-link").magnificPopup({
							type: "inline",
							midClick: true,
							closeOnBgClick: true 
						});
													
					});
		</script>
	
	<?endif?>
<?ob_start();?>
<div id="header-auth-popup" class="header-auth-form mfp-hide">
<div class="bx-authform">
    <div class="logotype"></div>
<?
if($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR'] && $_POST["TYPE"] == "AUTH"):
	$text = str_replace(array("<br>", "<br />"), "\n", $arResult['ERROR_MESSAGE']["MESSAGE"]);
?>
	<div class="alert alert-danger"><?=nl2br(htmlspecialcharsbx($text))?></div>
<?endif?>

	<?if($arResult["AUTH_SERVICES"]):?>

  <!-- ==================================== АВТОРИЗАЦИЯ ЧЕРЕЗ СОЦ. СЕТИ ========================= -->
<?
// $APPLICATION->IncludeComponent("bitrix:socserv.auth.form",
// 	"flat",
// 	array(
// 		"AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
// 		"AUTH_URL" => $arResult["AUTH_URL"],
// 		"POST" => $arResult["POST"],
// 	),
// 	$component,
// 	array("HIDE_ICONS"=>"Y")
// );
?>
        <div style="display:none;" class="modal-seperator"><span><?=GetMessage('AUTH_SEPARATOR')?></span></div>
<!-- ===================================================================================================== -->

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

		<div class="bx-authform-formgroup-container">
			<div class="bx-authform-label-container"><?=GetMessage("AUTH_LOGIN")?></div>
			<div class="bx-authform-input-container">
				<input type="text" name="USER_LOGIN" placeholder="<?=GetMessage("AUTH_LOGIN")?>" maxlength="255" value="<?=$arResult["LAST_LOGIN"]?>" />
			</div>
		</div>
		<div class="bx-authform-formgroup-container">
			<div class="bx-authform-label-container"><?=GetMessage("AUTH_PASSWORD")?></div>
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
<?if ($arParams["NOT_SHOW_LINKS"] != "Y"):?>
	<noindex>
		<div class="bx-authform-link-container">
			<a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" rel="nofollow"><?=GetMessage("AUTH_FORGOT_PASSWORD_2")?></a>
		</div>
	</noindex>
<?endif?>
		<div class="bx-authform-formgroup-container">
			<input type="submit" class="btn btn-primary" name="Login" value="<?=GetMessage("AUTH_LOGIN_BUTTON")?>" />
		</div>
	</form>
<div class="auth-footer auth">
    <div class="left-text"><?=GetMessage("NO_ACC")?></div>
    <div class="right-text"><a class="show-header-auth-popup enter-button" href="#header-register-popup"><?=GetMessage("CREATE")?></a></div>
</div>


</div>
</div>

<?$auth_form = ob_get_clean();?>
<div id="blok_js_form_auth"></div>
<div id="header-register-popup" class="header-auth-form mfp-hide">
<div class="bx-authform register-link">
<div class="logotype"></div>
<?if($arParams["NOT_SHOW_LINKS"] != "Y" && $arResult["NEW_USER_REGISTRATION"] == "Y" && $arParams["AUTHORIZE_REGISTRATION"] != "Y"):?>
	<noindex>
		<div class="bx-authform-link-container">
			<a href="<?=$arResult["AUTH_REGISTER_URL"]?>" rel="nofollow" class="reg-user"><?=GetMessage("AUTH_REGISTER_USER")?></a>
            <!--<a href="<?=$arResult["AUTH_REGISTER_URL"]?>/?regparam=agen" rel="nofollow"><b><?=GetMessage("REG_AGEN")?></b></a><br/>-->
            <a href="/partners/index.php?register=yes" class="reg-partner"> <?=GetMessage("REG_SUP")?></a>
			<a href="/private-office/?register=yes&IS_AGENT=Y" class="reg-agency"> <?=GetMessage("REG_AGEN")?></a>
            <?/*<a href="/agentIHWC/private-office/?register=yes&IS_AGENT_IHWC=Y" class="reg-agency enter-button"> <?=GetMessage("REG_AGEN_IHWC")?></a>*/?>
            
            
		</div>
        <div class="auth-footer register">
            <div class="left-text"><?=GetMessage("YET_REGISTER")?></div>
            <div class="right-text"><a class="show-header-auth-popup enter-button" href="#header-auth-popup"><?=GetMessage("AUTH_LOGIN_BUTTON")?></a></div>
        </div>
	</noindex>
<?endif?>


</div>
</div>
<script type="text/javascript">
<?if (strlen($arResult["LAST_LOGIN"])>0):?>
try{document.form_auth.USER_PASSWORD.focus();}catch(e){}
<?else:?>
try{document.form_auth.USER_LOGIN.focus();}catch(e){}
<?endif?>
</script>

<script>

(function ($) {
	$(document).ready(function (){
	    $('#blok_js_form_auth').html(<?=json_encode($auth_form)?>);   
		$(".show-header-auth-popup").magnificPopup({
        type: "inline",
        midClick: true
    });

	$(".nav-desktop .show-header-auth-popup.auth-link").magnificPopup({
        type: "inline",
        midClick: true,
		closeOnBgClick: false 
    });


    <?if ($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR'] && $_POST["popup_auth_form"] == "Y"):?>
        <?if(CSite::InDir(SITE_DIR.'booking/')):?>
            $('.authorization-switch').trigger('click');
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#booking-authorization").offset().top
            }, 2000);
        <?else:?>
            $(".show-header-auth-popup").magnificPopup("open");
        <?endif;?>
    <?endif?>
	});
})(jQuery, document);
</script>
<?if ($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR'] && $_POST["popup_auth_form"] == "Y"):?>
<script>
<?if(!CSite::InDir(SITE_DIR.'booking/')):?>
    $(document).ready(function(){
        $.magnificPopup.open({
            items: {
                src: '#header-auth-popup'
            },
            type: 'inline'
        });
    });
<?endif?>
</script>
<?endif?>

<?
elseif($arResult["FORM_TYPE"] == "otp"): echo "It is not supported by the input OTP";/*
 *
 * при необходимости кастомизировать под ввод одноразовых паролей
 *
?>

<form name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
<?if($arResult["BACKURL"] <> ''):?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?endif?>
	<input type="hidden" name="AUTH_FORM" value="Y" />
	<input type="hidden" name="TYPE" value="OTP" />
	<table width="95%">
		<tr>
			<td colspan="2">
			<?echo GetMessage("auth_form_comp_otp")?><br />
			<input type="text" name="USER_OTP" maxlength="50" value="" size="17" autocomplete="off" /></td>
		</tr>
<?if ($arResult["CAPTCHA_CODE"]):?>
		<tr>
			<td colspan="2">
			<?echo GetMessage("AUTH_CAPTCHA_PROMT")?>:<br />
			<input type="hidden" name="captcha_sid" value="<?echo $arResult["CAPTCHA_CODE"]?>" />
			<img src="/bitrix/tools/captcha.php?captcha_sid=<?echo $arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" /><br /><br />
			<input type="text" name="captcha_word" maxlength="50" value="" /></td>
		</tr>
<?endif?>
<?if ($arResult["REMEMBER_OTP"] == "Y"):?>
		<tr>
			<td valign="top"><input type="checkbox" id="OTP_REMEMBER_frm" name="OTP_REMEMBER" value="Y" /></td>
			<td width="100%"><label for="OTP_REMEMBER_frm" title="<?echo GetMessage("auth_form_comp_otp_remember_title")?>"><?echo GetMessage("auth_form_comp_otp_remember")?></label></td>
		</tr>
<?endif?>
		<tr>
			<td colspan="2"><input type="submit" name="Login" value="<?=GetMessage("AUTH_LOGIN_BUTTON")?>" /></td>
		</tr>
		<tr>
			<td colspan="2"><noindex><a href="<?=$arResult["AUTH_LOGIN_URL"]?>" rel="nofollow"><?echo GetMessage("auth_form_comp_auth")?></a></noindex><br /></td>
		</tr>
	</table>
</form>

<?*/
else:
?>
<?if ($arParams['IS_MOBILE'] != 'Y'): ?>
	<a class="down-links">
		<div class="block-title-auth"><i class="fa fa-user-circle-o" aria-hidden="true"></i>&nbsp;
        <?$name = explode(' ', $arResult["USER_NAME"]);?>
		<?=(isset($arResult["USER_PROFILE"]) && $arResult["USER_PROFILE"]!='/private-office/' ? GetMessage("CABINET") :$name[0])?>
		</div>
		<i class="fa fa-arrow-down" aria-hidden="true"></i>

	</a>


	<div class="header-authorized-form " id="desktop-authorized-popup">
		<a class="my-profilelink" href="<?=$arResult["USER_PROFILE"]?>" title="<?=GetMessage("AUTH_PROFILE")?>"><?=GetMessage("PERSONAL_CABINET")?></a>
		<a class="logoutlink" href="<?= $APPLICATION->GetCurPageParam("logout=yes", array("logout"), false) ?>" title="<?=GetMessage("LOGOUT")?>"><?=GetMessage("AUTH_LOGOUT_BUTTON")?></a>
	</div>
	
	
<script>

	/*(function ($) {
		$(document).ready(function (){
			$(".down-links").magnificPopup({
				type: "inline",
				midClick: true
			});
		});
	})(jQuery, document);*/
</script>
	
<?else: ?>
    <a style="color:#fefeff" href="#header-authorized-popup" class="show-header-auth-popup down-mobile-link" title="<?=GetMessage("AUTH_PROFILE")?>">
		<i class="fa fa-user-circle-o" aria-hidden="true"></i> <?=GetMessage("AUTH_TITLE")?>
	</a>
    <?if ( !CSite::InGroup (array(7) ) ):?>
	<div id="header-authorized-popup" class="header-auth-form mfp-hide">
	    <div class="mfp-head"><?=GetMessage("PERSONAL_MENU_TITLE")?></div> 
		
        <?$APPLICATION->IncludeComponent(
				"bitrix:menu", 
				"section-menu", 
				array(
					"ALLOW_MULTI_SELECT" => "N",
					"CHILD_MENU_TYPE" => "child",
					"DELAY" => "N",
					"MAX_LEVEL" => "2",
					"MENU_CACHE_GET_VARS" => array(
					),
					"MENU_CACHE_TIME" => "3600",
					"MENU_CACHE_TYPE" => "N",
					"MENU_CACHE_USE_GROUPS" => "Y",
					"ROOT_MENU_TYPE" => "left_private_mobile",
					"USE_EXT" => "N",
					"COMPONENT_TEMPLATE" => "section-menu"
				),
				false
			);?>
	    </div>
        <?else:?>
        <div id="header-authorized-popup" class="header-auth-form mfp-hide">
	    <div class="mfp-head"><?=GetMessage("PERSONAL_MENU_TITLE")?></div>    
		<div class="bx-authform register-link widget widget_categories">
			<noindex>
				<ul>
                <li><a class="my-profilelink" href="<?=$arResult["USER_PROFILE"]?>" title="<?=GetMessage("AUTH_PROFILE")?>"><?=GetMessage("PERSONAL_CABINET")?></a></li>
				<li><a class="logoutlink" href="?logout=yes" title="<?=GetMessage("LOGOUT")?>"><?=GetMessage("AUTH_LOGOUT_BUTTON")?></a></li>
                </ul>
			</noindex>
		</div>
        </div>
        <?endif;?>
<script>

	(function ($) {
		$(document).ready(function (){
			$(".show-header-auth-popup").magnificPopup({
				type: "inline",
				mainClass: 'mfp-menu-mobile',
				midClick: true
			});
		});
	})(jQuery, document);
</script>
<?endif?>
<?endif?>