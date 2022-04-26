<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

//one css for all system.auth.* forms
$APPLICATION->SetAdditionalCSS("/bitrix/css/main/system.auth/flat/style.css");
?>
<div class="bx-authform">

<?
if(!empty($arParams["~AUTH_RESULT"])):
	$text = str_replace(array("<br>", "<br />"), "\n", $arParams["~AUTH_RESULT"]["MESSAGE"]);
?>
	<div class="alert <?=($arParams["~AUTH_RESULT"]["TYPE"] == "OK"? "alert-success":"alert-danger")?>"><?=nl2br(htmlspecialcharsbx($text))?></div>
<?endif?>

<?if($arResult["USE_EMAIL_CONFIRMATION"] === "Y" && is_array($arParams["AUTH_RESULT"]) &&  $arParams["AUTH_RESULT"]["TYPE"] === "OK"):?>
	<div class="alert alert-success"><?echo GetMessage("AUTH_EMAIL_SENT")?></div>
<?else:?>

<?if($arResult["USE_EMAIL_CONFIRMATION"] === "Y"):?>
	<div class="alert alert-warning"><?echo GetMessage("AUTH_EMAIL_WILL_BE_SENT")?></div>
<?endif?>

<noindex>
	<form method="post" action="<?=$arResult["AUTH_URL"]?>" name="bform">
<?if($arResult["BACKURL"] <> ''):?>
		<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?endif?>
		<input type="hidden" name="AUTH_FORM" value="Y" />
		<input type="hidden" name="TYPE" value="REGISTRATION" />

		<div class="bx-authform-formgroup-container">
			<div class="bx-authform-label-container"><span class="bx-authform-starrequired">*</span><?=GetMessage("AUTH_NAME")?></div>
			<div class="bx-authform-input-container">
                                                            <input required type="text" name="USER_NAME" maxlength="255" value="<?=$arResult["USER_NAME"]?>" />
			</div>
		</div>

		<div class="bx-authform-formgroup-container">
			<div class="bx-authform-label-container"><span class="bx-authform-starrequired">*</span><?=GetMessage("AUTH_LAST_NAME")?></div>
			<div class="bx-authform-input-container">
                                                            <input required type="text" name="USER_LAST_NAME" maxlength="255" value="<?=$arResult["USER_LAST_NAME"]?>" />
			</div>
		</div>

		<?/*<div class="bx-authform-formgroup-container">
			<div class="bx-authform-label-container"><span class="bx-authform-starrequired">*</span><?=GetMessage("AUTH_LOGIN_MIN")?></div>
			<div class="bx-authform-input-container">
				<input type="text" name="USER_LOGIN" maxlength="255" value="<?=$arResult["USER_LOGIN"]?>" />
			</div>
		</div>*/?>

		<div class="bx-authform-formgroup-container">
			<div class="bx-authform-label-container"><span class="bx-authform-starrequired">*</span><?=GetMessage("AUTH_PASSWORD_REQ")?></div>
			<div class="bx-authform-input-container">
<?if($arResult["SECURE_AUTH"]):?>
				<div class="bx-authform-psw-protected" id="bx_auth_secure" style="display:none"><div class="bx-authform-psw-protected-desc"><span></span><?echo GetMessage("AUTH_SECURE_NOTE")?></div></div>

<script type="text/javascript">
document.getElementById('bx_auth_secure').style.display = '';
</script>
<?endif?>
				<input required type="password" name="USER_PASSWORD" maxlength="255" value="<?=$arResult["USER_PASSWORD"]?>" autocomplete="off" />
			</div>
		</div>

		<div class="bx-authform-formgroup-container">
			<div class="bx-authform-label-container"><span class="bx-authform-starrequired">*</span><?=GetMessage("AUTH_CONFIRM")?></div>
			<div class="bx-authform-input-container">
<?if($arResult["SECURE_AUTH"]):?>
				<div class="bx-authform-psw-protected" id="bx_auth_secure_conf" style="display:none"><div class="bx-authform-psw-protected-desc"><span></span><?echo GetMessage("AUTH_SECURE_NOTE")?></div></div>

<script type="text/javascript">
document.getElementById('bx_auth_secure_conf').style.display = '';
</script>
<?endif?>
				<input required type="password" name="USER_CONFIRM_PASSWORD" maxlength="255" value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>" autocomplete="off" />
			</div>
		</div>

		<div class="bx-authform-formgroup-container">
			<div class="bx-authform-label-container"><?if($arResult["EMAIL_REQUIRED"]):?><span class="bx-authform-starrequired">*</span><?endif?><?=GetMessage("AUTH_EMAIL")?></div>
			<div class="bx-authform-input-container">
				<input required type="text" name="USER_EMAIL" maxlength="255" value="<?=$arResult["USER_EMAIL"]?>" />
			</div>
		</div>

<?if($arResult["USER_PROPERTIES"]["SHOW"] == "Y"):?>
	<?foreach ($arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField):?>

		<div class="bx-authform-formgroup-container">
			<div class="bx-authform-label-container"><?if ($arUserField["MANDATORY"]=="Y"):?><span class="bx-authform-starrequired">*</span><?endif?><?=$arUserField["EDIT_FORM_LABEL"]?></div>
			<div class="bx-authform-input-container">
<?
$APPLICATION->IncludeComponent(
	"bitrix:system.field.edit",
	$arUserField["USER_TYPE"]["USER_TYPE_ID"],
	array(
		"bVarsFromForm" => $arResult["bVarsFromForm"],
		"arUserField" => $arUserField,
		"form_name" => "bform"
	),
	null,
	array("HIDE_ICONS"=>"Y")
);
?>
			</div>
		</div>

	<?endforeach;?>
<?endif;?>
<?if ($arResult["USE_CAPTCHA"] == "Y"):?>
		<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />

		<div class="bx-authform-formgroup-container">
			<div class="bx-authform-label-container">
				<span class="bx-authform-starrequired">*</span><?=GetMessage("CAPTCHA_REGF_PROMT")?>
			</div>
			<div class="bx-captcha"><img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" /></div>
			<div class="bx-authform-input-container">
				<input type="text" name="captcha_word" maxlength="50" value="" autocomplete="off"/>
			</div>
		</div>

<?endif?>
                                   <div id="agent-container" class="bx-authform-formgroup-container">
			<div class="checkbox">
				<label class="bx-filter-param-label">
					<input type="checkbox" id="IS_AGENT" name="IS_AGENT" value="Y" />
					<span class="bx-filter-param-text"><?=GetMessage("AUTH_IS_AGENT")?></span>
				</label>
			</div>
		</div>
		<div class="bx-authform-formgroup-container">
			<input type="submit" class="btn btn-primary" name="Register" value="<?=GetMessage("AUTH_REGISTER")?>" />
		</div>

		<hr class="bxe-light">

		<div class="bx-authform-description-container">
			<?echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?>
		</div>

		<div class="bx-authform-description-container">
			<span class="bx-authform-starrequired">*</span><?=GetMessage("AUTH_REQ")?>
		</div>

		<div class="bx-authform-link-container">
			<a href="<?=$arResult["AUTH_AUTH_URL"]?>" rel="nofollow"><b><?=GetMessage("AUTH_AUTH")?></b></a>
		</div>

	</form>
</noindex>

<?
$arUserFields = $GLOBALS['USER_FIELD_MANAGER']->GetUserFields('USER', 0, LANGUAGE_ID);
if (!empty($arUserFields)):
?>
        
<script type="text/javascript">
document.bform.USER_NAME.focus();

/**
 * @param {jQuery} $
 * @returns {undefined}
 */
(function ($) {
    
    function render () {
            
        var html, component_container = "<div class='bx-authform-formgroup-container'>#COMPONENT#</div>",

        input_container = "<div class='bx-authform-input-container'>#INPUT#</div>",
        
        text_input = "<input required type='text' name='#NAME#'>",

        label_container = "<div class='bx-authform-label-container'><span class='bx-authform-starrequired'>*</span>#LABEL#</div>";

        html = "<div id='additional_fields__container'>";

             html += component_container.replace("#COMPONENT#", label_container.replace("#LABEL#", "<?= $arUserFields["UF_LEGAL_NAME"]["EDIT_FORM_LABEL"]?>") 
                                     + input_container.replace("#INPUT#", text_input.replace("#NAME#", "UF_LEGAL_NAME")));

             html += component_container.replace("#COMPONENT#", label_container.replace("#LABEL#", "<?= $arUserFields["UF_LEGAL_ADDRESS"]["EDIT_FORM_LABEL"]?>") 
                                     + input_container.replace("#INPUT#", text_input.replace("#NAME#", "UF_LEGAL_ADDRESS")));

             html += component_container.replace("#COMPONENT#", label_container.replace("#LABEL#", "<?= $arUserFields["UF_BANK_NAME"]["EDIT_FORM_LABEL"]?>") 
                                     + input_container.replace("#INPUT#", text_input.replace("#NAME#", "UF_BANK_NAME")));

             html += component_container.replace("#COMPONENT#", label_container.replace("#LABEL#", "<?= $arUserFields["UF_BANK_ADDRESS"]["EDIT_FORM_LABEL"]?>") 
                                     + input_container.replace("#INPUT#", text_input.replace("#NAME#", "UF_BANK_ADDRESS")));

             html += component_container.replace("#COMPONENT#", label_container.replace("#LABEL#", "<?= $arUserFields["UF_BANK_CODE"]["EDIT_FORM_LABEL"]?>") 
                                     + input_container.replace("#INPUT#", text_input.replace("#NAME#", "UF_BANK_CODE")));

             html += component_container.replace("#COMPONENT#", label_container.replace("#LABEL#", "<?= $arUserFields["UF_CHECKING_ACCOUNT"]["EDIT_FORM_LABEL"]?>") 
                                     + input_container.replace("#INPUT#", text_input.replace("#NAME#", "UF_CHECKING_ACCOUNT")));

             html += component_container.replace("#COMPONENT#", label_container.replace("#LABEL#", "<?= $arUserFields["UF_UNP"]["EDIT_FORM_LABEL"]?>") 
                                     + input_container.replace("#INPUT#", text_input.replace("#NAME#", "UF_UNP")));

             html += component_container.replace("#COMPONENT#", label_container.replace("#LABEL#", "<?= $arUserFields["UF_OKPO"]["EDIT_FORM_LABEL"]?>") 
                                     + input_container.replace("#INPUT#", text_input.replace("#NAME#", "UF_OKPO")));

        html += "</div>";


        $("#agent-container").before(html);

    }

    function destroy () {

        $("#additional_fields__container").remove();

    }
    
    $("#IS_AGENT").on("change", function () {
        
        var $this = $(this);

        if ($this.is(":checked")) {
            render();
            
        } else {
            
            destroy();
            
        }
        
    })
    
})(jQuery);

</script>
<?endif?>
<?endif?>
</div>