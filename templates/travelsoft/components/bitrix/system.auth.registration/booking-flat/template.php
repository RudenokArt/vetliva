<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->addExternalJs($templateFolder . "/validate/validate.min.js"); 
$this->addExternalJs($templateFolder . "/validate/localization/messages_".strtolower(LANGUAGE_ID).".js"); 
?>
<div class="bx-authform">
	<form method="post" id="reg_user_form_booking" action="<?=$arResult["AUTH_URL"]?>" name="bform">
        <div class="bx-authform-formgroup-container">
			<div class="bx-authform-input-container">
				<input placeholder="<?=GetMessage("AUTH_LOGIN")?>" required type="email" name="USER_EMAIL" maxlength="255" value="<?=$arResult["USER_EMAIL"]?>" />
			</div>
		</div>
        <div class="bx-authform-formgroup-container">
			<input type="submit" class="btn btn-primary" name="Register" value="<?=GetMessage("REGISTRATION")?>" />
		</div>
        <div class="bx-authform-formgroup-container">
			<span class="bx-authform-label-container"><?=GetMessage("AUTH_LOGIN_LABEL_NOTE") ?></span>
			<a style="float:right;margin-top:10px;" class="btn btn-primary" onclick="$('.authorization-switch').click(); return false;" href="<?=$arResult["AUTH_AUTH_URL"]?>"><?=GetMessage("AUTH_LOGIN_LABEL") ?></a>
		</div>
    </form>
    <?$APPLICATION->IncludeComponent("bitrix:system.auth.form", "only-socservices", Array(
                							"REGISTER_URL" => "/private-office/index.php",
                							"FORGOT_PASSWORD_URL" => "/private-office/index.php",
                							"PROFILE_URL" => "/private-office/",
                							"SHOW_ERRORS" => "Y",
                								));?>
</noindex>
</div>
<script>
$('#reg_user_form_booking').validate({
    submitHandler: function (form) {
        check_email(form);
        return false;
    }
});
function check_email(formtosend) {
    $("input[name='Register']").hide();
    $("input[name='USER_LOGIN']").val('');
    $('.user_email_exist').hide();
    var formData = new FormData(formtosend);  
    formData.append('action','add_user');

	var xhr = new XMLHttpRequest();
	xhr.open("POST", "<?=$templateFolder?>/ajax.php");

	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4) {
			if(xhr.status == 200) {
				data = xhr.responseText;
                var result = BX.parseJSON(data);
				if(result.success) {
				  document.location.reload();
                } 
                else {
                    $("input[name='Register']").show();
                    if (result.type=='user_error') alert(result.message);
                    else {
                        $('.authorization-switch').click(); 
                        $('.user_email_exist').show();
                        $("input[name='USER_LOGIN']").val($("input[name='USER_EMAIL']").val());
                    }
                }
			}
		}
	};
	xhr.send(formData); 
}
</script>