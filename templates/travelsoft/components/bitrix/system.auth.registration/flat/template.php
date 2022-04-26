<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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


//$arResult["BACKURL"] = '/private-office/statistic.php';
//one css for all system.auth.* forms
$APPLICATION->SetAdditionalCSS("/bitrix/css/main/system.auth/flat/style.css");
$APPLICATION->SetAdditionalCSS("/local/templates/travelsoft/css/icomoon/style.min.css");

if ($_SESSION["JUST_REGISTER_AGENT"]):?>
    <div class="panel panel-body login-form">
        <div class="text-center">
            <div class="icon-object border-slate-300 text-slate-300"><i class="icon-reading"></i></div>
            <h5 class="content-group"><?=GetMessage('THANK_1')?></h5>
            <p><?=GetMessage('THANK_2')?></p>
        </div>
    </div>
<?unset($_SESSION["JUST_REGISTER_AGENT"]); return; endif; ?>
<div class="bx-authform">
<?/*dm(array($arResult,$_REQUEST,$_POST), false,false,false);*/?>
<?
if (SITE_ID === "by") {
    $APPLICATION->SetTitle(GetMessage("AUTH_REGISTER"));
}
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
<?
$is_agent = false;
if($_REQUEST["IS_AGENT"] == "Y") $is_agent = true;
if ($is_agent) $arResult["AUTH_URL"] = '/agent/private-office/?register=yes';
$is_agent_ihwc = false;
if($_REQUEST["IS_AGENT_IHWC"] == "Y") $is_agent_ihwc = true;
if ($is_agent_ihwc) {
    $arResult["AUTH_URL"] = '/agentIHWC/private-office/?register=yes';
    $this->addExternalJs($templateFolder . "/validate/validate.min.js"); 
    $this->addExternalJs($templateFolder . "/validate/localization/messages_".strtolower(LANGUAGE_ID).".js"); 
}
?>
<noindex>
<script>
    
    function viewPassword(element) {
        if (element.closest('.bx-authform-formgroup-container').find('input').attr('type')=='password') {
            element.closest('.bx-authform-formgroup-container').find('input').attr('type','text');
            element.attr('class', 'icon-eye text-muted');
        }
        else {
            element.closest('.bx-authform-formgroup-container').find('input').attr('type','password');
            element.attr('class', 'icon-eye-blocked text-muted');
        }
    }
    function check_pass() {
         var pass = $('#pass').val();
         if (pass!='') {
             var formData = new FormData(); 
             formData.append('pass', pass);
             formData.append('lang', '<?=LANGUAGE_ID?>');
             var xhrCustom = new XMLHttpRequest();
             xhrCustom.open("POST", "<?=$templateFolder?>/ajax.php");
            
             xhrCustom.onreadystatechange = function() {
                 if (xhrCustom.readyState == 4 && xhrCustom.status == 200) {
                     var dataCheck = xhrCustom.responseText;
                     var resultCustom = jQuery.parseJSON( dataCheck);
                     if (resultCustom.length==0) {
                        jQuery("input[name='passwordcorret']").val('Y');
                        $('.passerror-container').html('');
                        $('.passerror-container').hide();
                     }
                     else {
                        jQuery("input[name='passwordcorret']").val('N');
                        var errortext = '<ul>';
                        for (j = 0; j < resultCustom.length; j++) {
                         errortext+='<li>'+resultCustom[j]+'</li>';
                         }
                        errortext+='</ul>';
                        $('.passerror-container').html(errortext);
                        $('.passerror-container').show();
                     }
                 }
             };
             xhrCustom.send(formData);
         }
    }
</script>
<div class="dynamic_reg_form"></div>
<?ob_start();?>
	<form method="post" id="reg_user_form" action="<?=$arResult["AUTH_URL"]?>" name="bform">
    
    <input type="hidden" name="passwordcorret" value="N">
    
<?if($arResult["BACKURL"] <> ''):?>
		<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?endif?>
		<input type="hidden" name="AUTH_FORM" value="Y" />
		<input type="hidden" name="TYPE" value="REGISTRATION" />
        <?if (!$is_agent_ihwc):?>
		<div class="bx-authform-formgroup-container">
			<div class="bx-authform-label-container"><span class="bx-authform-starrequired"></span><?=GetMessage("AUTH_NAME")?></div>
			<div class="bx-authform-input-container">
                                                            <input required type="text" name="USER_NAME" maxlength="255" value="<?=$arResult["USER_NAME"]?>" />
			</div>
		</div>

		<div class="bx-authform-formgroup-container">
			<div class="bx-authform-label-container"><span class="bx-authform-starrequired"></span><?=GetMessage("AUTH_LAST_NAME")?></div>
			<div class="bx-authform-input-container">
                                                            <input required type="text" name="USER_LAST_NAME" maxlength="255" value="<?=$arResult["USER_LAST_NAME"]?>" />
			</div>
		</div>
        <?else:?>
            <div class="bx-authform-formgroup-container">
    			<div class="bx-authform-label-container"><span class="bx-authform-starrequired"></span><?=GetMessage("AUTH_UF_LEGAL_NAME")?></div>
    			<div class="bx-authform-input-container">
                    <input required type="text" name="UF_LEGAL_NAME" maxlength="255" value="<?=$arResult["UF_LEGAL_NAME"]?>" />
    			</div>
    		</div>
            <div class="bx-authform-formgroup-container">
    			<div class="bx-authform-label-container"><span class="bx-authform-starrequired"></span><?=GetMessage("RESIDENT_TITLE")?></div>
    			<div class="bx-authform-input-container">
                    <label for="UF_RESIDENT_1"><?=GetMessage("RESIDENT_1")?></label>
                    <input required type="radio" required="" name="UF_RESIDENT" value="1"  id="UF_RESIDENT_1"/>
                    <label for="UF_RESIDENT_0"><?=GetMessage("RESIDENT_0")?></label>
                    <input required type="radio" required="" name="UF_RESIDENT" value="0"  id="UF_RESIDENT_0"/>
    			</div>
    		</div>
            
            <div class="bx-authform-formgroup-container">
    			<div class="bx-authform-label-container"><span class="bx-authform-starrequired"></span><?=GetMessage("AUTH_UF_LEGAL_ADDRESS")?></div>
    			<div class="bx-authform-input-container">
                    <input required type="text" name="UF_LEGAL_ADDRESS" maxlength="255" value="<?=$arResult["UF_LEGAL_ADDRESS"]?>" />
    			</div>
    		</div>
            <div class="bx-authform-formgroup-container">
    			<div class="bx-authform-label-container"><span class="bx-authform-starrequired"></span><?=GetMessage("AUTH_UF_ACOUNT_CURRENCY")?></div>
    			<div class="bx-authform-input-container">
                    <?$currency = ['BYN', 'RUB', 'USD', 'EUR'];?>
                    <select required="" class="to-validate form-control"  name="UF_ACOUNT_CURRENCY" >
                        <option value=""> - </option>
                        <?foreach ($currency as $val):?>
                            <option value="<?=$val?>"><?=GetMessage($val)?></option>
                        <?endforeach;?>
                    </select>
                </div>
    		</div>
        <?endif;?>
		<div class="bx-authform-formgroup-container">
			<div class="bx-authform-label-container"><?if($arResult["EMAIL_REQUIRED"]):?><span class="bx-authform-starrequired"></span><?endif?><?=GetMessage("AUTH_EMAIL")?></div>
			<div class="bx-authform-input-container">
				<input required type="text" name="USER_EMAIL" maxlength="255" value="<?=$arResult["USER_EMAIL"]?>" />
			</div>
		</div>

		<?/*<div class="bx-authform-formgroup-container">
			<div class="bx-authform-label-container"><span class="bx-authform-starrequired">*</span><?=GetMessage("AUTH_LOGIN_MIN")?></div>
			<div class="bx-authform-input-container">
				<input type="text" name="USER_LOGIN" maxlength="255" value="<?=$arResult["USER_LOGIN"]?>" />
			</div>
		</div>*/?>

		<div class="bx-authform-formgroup-container">
			<div class="bx-authform-label-container"><span class="bx-authform-starrequired"></span><?=GetMessage("AUTH_PASSWORD_REQ")?></div>
			<div class="bx-authform-input-container">
<?if($arResult["SECURE_AUTH"]):?>
				<div class="bx-authform-psw-protected" id="bx_auth_secure" style="display:none"><div class="bx-authform-psw-protected-desc"><span></span><?echo GetMessage("AUTH_SECURE_NOTE")?></div></div>

<script type="text/javascript">
document.getElementById('bx_auth_secure').style.display = '';
</script>
<?endif?>
				<input id="pass" onchange="check_pass()" onkeyup="check_pass()"required type="password" name="USER_PASSWORD" maxlength="255" value="<?=$arResult["USER_PASSWORD"]?>" autocomplete="off" />
                
                <div class="form-control-feedback">
                    <i class="icon-eye-blocked text-muted" aria-hidden="true" onClick="viewPassword($(this))"></i>
                </div>
                <div class="passerror-container"></div>
                
			</div>
		</div>

		<div class="bx-authform-formgroup-container">
			<div class="bx-authform-label-container"><span class="bx-authform-starrequired"></span><?=GetMessage("AUTH_CONFIRM")?></div>
			<div class="bx-authform-input-container">
<?if($arResult["SECURE_AUTH"]):?>
				<div class="bx-authform-psw-protected" id="bx_auth_secure_conf" style="display:none"><div class="bx-authform-psw-protected-desc"><span></span><?echo GetMessage("AUTH_SECURE_NOTE")?></div></div>

<script type="text/javascript">
document.getElementById('bx_auth_secure_conf').style.display = '';
</script>
<?endif?>
				<input required type="password" name="USER_CONFIRM_PASSWORD" maxlength="255" value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>" autocomplete="off" />
                
                <div class="form-control-feedback">
                    <i class="icon-eye-blocked text-muted" aria-hidden="true" onClick="viewPassword($(this))"></i>
                </div>
                
			</div>
		</div>
        <?if ($is_agent_ihwc):?>
            <div class="bx-authform-formgroup-container">
    			<div class="bx-authform-label-container"><span class="bx-authform-starrequired"></span><?=GetMessage("AUTH_WORK_PHONE")?></div>
    			<div class="bx-authform-input-container">
                    <input required type="text" name="WORK_PHONE" maxlength="255" value="<?=$arResult["WORK_PHONE"]?>" />
    			</div>
    		</div>
        <?endif;?>

		<? if($_REQUEST["IS_AGENT"] == "Y"):?>
			<input type="hidden" name="IS_AGENT" value="Y">
		<? endif; ?>
        <? if($is_agent_ihwc):?>
			<input type="hidden" name="IS_AGENT_IHWC" value="Y">
		<? endif; ?>

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
				<span class="bx-authform-starrequired"></span><?=GetMessage("CAPTCHA_REGF_PROMT")?>
			</div>
			<div class="bx-captcha"><img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" /></div>
			<div class="bx-authform-input-container">
				<input type="text" name="captcha_word" maxlength="50" value="" autocomplete="off"/>
			</div>
		</div>

<?endif?>

		<div class="bx-authform-formgroup-container">
			<input type="submit" class="btn btn-primary" name="Register" value="<?=GetMessage("AUTH_REGISTER")?>" />
		</div>

		<div class="bx-authform-formgroup-container" >
			<center>
				<span class="bx-authform-label-container"><?=GetMessage("AUTH_CONF_LABEL") ?></span>
			</center>
		</div>

		<hr class="bxe-light">

		<?/*<div class="bx-authform-description-container">
                    <?if (SITE_ID === "by"):
                        echo GetMessage("AUTH_PASSWORD_POLICY");
                    else:?>
			<?echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?>
                    <?endif?>
		</div>*/?>

		<div class="bx-authform-formgroup-container">
			<span class="bx-authform-label-container"><?=GetMessage("AUTH_LOGIN_LABEL_NOTE") ?></span>
			<a style="float:right;margin-top:0px;" class="btn btn-primary" href="<?=$arResult["AUTH_AUTH_URL"]?>"><?=GetMessage("AUTH_LOGIN_LABEL") ?></a>
		</div>

	</form>
    <?$form_content =ob_get_clean();?>
</noindex>
<script>
$(window).load(function () {
    $('.dynamic_reg_form').html(<?=json_encode($form_content)?>); 
    if (document.bform.USER_NAME != null) document.bform.USER_NAME.focus();
    <?if ($is_agent_ihwc):?>
        $('#reg_user_form').validate({
            
        });
    <?endif;?>
});
</script>
<?
$arUserFields = $GLOBALS['USER_FIELD_MANAGER']->GetUserFields('USER', 0, LANGUAGE_ID);
if (!empty($arUserFields)):
?>
        
<script type="text/javascript">


/**
 * @param {jQuery} $
 * @returns {undefined}
 */
(function ($) {



    function render () {
            
        var html, component_container = "<div class='bx-authform-formgroup-container'>#COMPONENT#</div>",

        input_container = "<div class='bx-authform-input-container'>#INPUT#</div>",
        
        text_input = "<input required type='text' name='#NAME#'>",

        text_input_not_required = "<input type='text' name='#NAME#'>",

        label_container = "<div class='bx-authform-label-container'><span class='bx-authform-starrequired'>*</span>#LABEL#</div>",

        label_container_not_required = "<div class='bx-authform-label-container'>#LABEL#</div>";

        html = "<div id='additional_fields__container'>";

             html += component_container.replace("#COMPONENT#", label_container.replace("#LABEL#", "<?= $arUserFields["UF_LEGAL_NAME"]["EDIT_FORM_LABEL"]?>")
                                     + input_container.replace("#INPUT#", text_input.replace("#NAME#", "UF_LEGAL_NAME")));

             html += component_container.replace("#COMPONENT#", label_container.replace("#LABEL#", "<?= $arUserFields["UF_LEGAL_ADDRESS"]["EDIT_FORM_LABEL"]?>")
                                     + input_container.replace("#INPUT#", text_input.replace("#NAME#", "UF_LEGAL_ADDRESS")));

             html += component_container.replace("#COMPONENT#", label_container_not_required.replace("#LABEL#", "<?= $arUserFields["UF_BANK_NAME"]["EDIT_FORM_LABEL"]?>")
                                     + input_container.replace("#INPUT#", text_input_not_required.replace("#NAME#", "UF_BANK_NAME")));

             html += component_container.replace("#COMPONENT#", label_container_not_required.replace("#LABEL#", "<?= $arUserFields["UF_BANK_ADDRESS"]["EDIT_FORM_LABEL"]?>")
                                     + input_container.replace("#INPUT#", text_input_not_required.replace("#NAME#", "UF_BANK_ADDRESS")));

             html += component_container.replace("#COMPONENT#", label_container_not_required.replace("#LABEL#", "<?= $arUserFields["UF_BANK_CODE"]["EDIT_FORM_LABEL"]?>")
                                     + input_container.replace("#INPUT#", text_input_not_required.replace("#NAME#", "UF_BANK_CODE")));

             html += component_container.replace("#COMPONENT#", label_container_not_required.replace("#LABEL#", "<?= $arUserFields["UF_CHECKING_ACCOUNT"]["EDIT_FORM_LABEL"]?>")
                                     + input_container.replace("#INPUT#", text_input_not_required.replace("#NAME#", "UF_CHECKING_ACCOUNT")));

             html += component_container.replace("#COMPONENT#", label_container_not_required.replace("#LABEL#", "<?= $arUserFields["UF_UNP"]["EDIT_FORM_LABEL"]?>")
                                     + input_container.replace("#INPUT#", text_input_not_required.replace("#NAME#", "UF_UNP")));

             html += component_container.replace("#COMPONENT#", label_container_not_required.replace("#LABEL#", "<?= $arUserFields["UF_OKPO"]["EDIT_FORM_LABEL"]?>")
                                     + input_container.replace("#INPUT#", text_input_not_required.replace("#NAME#", "UF_OKPO")));

        html += "</div>";


        $("#agent-container").before(html);

    }

    function destroy () {

        $("#additional_fields__container").remove();

    }
    
    <?if($_REQUEST["IS_AGENT"] === "Y"):?>
    render();
    <?endif?>
    

//    if(window.location.search.indexOf('regparam=agen')!= -1){
//        if($('#IS_AGENT').is(":checked") == false){
//            $('#IS_AGENT').prop('checked', true);
//            render();
//        }
//    }


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

<script>
(function ($) {
    $('form[name=bform]').on('submit', function (e) {
        if ($("input[name='passwordcorret']").val()!='Y') {
             $([document.documentElement, document.body]).animate({
                scrollTop: $("#pass").offset().top-130
            }, 2000);
            $("#pass").focus();
            return false;
        }
    });
})(jQuery)
</script>

</div>