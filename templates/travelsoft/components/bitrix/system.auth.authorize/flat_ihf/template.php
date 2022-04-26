<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 */

//one css for all system.auth.* forms
$APPLICATION->SetAdditionalCSS("/local/templates/travelsoft/components/bitrix/system.auth.authorize/flat_ihf/intlTelInput.min.css");
$APPLICATION->SetAdditionalCSS("/bitrix/css/main/system.auth/flat/style.css");
if (SITE_ID === "by") {
    $APPLICATION->SetTitle(GetMessage("AUTH_TITLE_AUTH"));
}

//Подготовка стран для формы
use Bitrix\Highloadblock as HL; 
use Bitrix\Main\Entity;

$hlbl = 61;
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch(); 
$entity = HL\HighloadBlockTable::compileEntity($hlblock); 
$entity_data_class = $entity->getDataClass(); 
$rsData = $entity_data_class::getList(array(
   "select" => array("UF_NAME" . POSTFIX_PROPERTY),
   "order" => array("UF_NAME" . POSTFIX_PROPERTY => "ASC"),
   "filter" => array()
));
$arCountries = array();
while($arData = $rsData->Fetch()){
   $arCountries[] = $arData;
}

// Подключение необходимого js для ввода телефонов.
$this->addExternalJs("/local/templates/travelsoft/components/bitrix/system.auth.authorize/flat_ihf/js/intlTelInput-jquery.min.js");
$this->addExternalJs("/local/templates/travelsoft/components/bitrix/system.auth.authorize/flat_ihf/js/jquery.maskedinput.min.js");

?>

<div class="bx-authform">

<?
if(!empty($arParams["~AUTH_RESULT"])):
	$text = str_replace(array("<br>", "<br />"), "\n", $arParams["~AUTH_RESULT"]["MESSAGE"]);
?>
	<div class="alert alert-danger"><?=nl2br(htmlspecialcharsbx($text))?></div>
<?endif?>

<?
if($arResult['ERROR_MESSAGE'] <> ''):
	$text = str_replace(array("<br>", "<br />"), "\n", $arResult['ERROR_MESSAGE']);
?>
	<div class="alert alert-danger"><?=nl2br(htmlspecialcharsbx($text))?></div>
<?endif?>

	<h3 class="bx-title"><?=GetMessage("AUTH_PLEASE_AUTH")?></h3>

	<form name="form_auth" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">

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
				<input type="text" name="USER_LOGIN" maxlength="255" value="<?=$arResult["LAST_LOGIN"]?>" />
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
				<input type="password" name="USER_PASSWORD" maxlength="255" autocomplete="off" />
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
		<div class="bx-authform-formgroup-container">
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
		<div style="text-align: right" class="bx-authform-formgroup-container">
			<input type="submit" class="btn btn-primary" name="Login" value="<?=GetMessage("AUTH_AUTHORIZE")?>" />
		</div>
	</form>



<?if($arParams["NOT_SHOW_LINKS"] != "Y" && $arResult["NEW_USER_REGISTRATION"] == "Y" && $arParams["AUTHORIZE_REGISTRATION"] != "Y"):?>
	<noindex>
		<div class="auth-footer auth">
            <div class="left-text"><?=GetMessage("NO_ACC")?></div>
        </div>
	</noindex>
<?endif?>
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
<!-- Модальное окно для заявок -->
<div class="modal fade" id="callbackModalPress" tabindex="-1" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
	<div id="callback-dialog" class="modal-dialog" style="transform:translate(0, 2%);">
		<div class="modal-content">
			<div class="modal-header" style="text-align:center;">
				<button style="font-size:20pt;" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 class="modal-title" id="modal-title"><?=GetMessage("CALLBACK_TITLE")?></h3>
			</div>
			<div class="modal-body">
				<input type="hidden" name="current_page" value="<?= $APPLICATION->GetCurPageParam("", array(), false) ?>">
				<div class="form-group">
					<label class="text-for-popup" for="name"><?= GetMessage("CALLBACK_NAME")?></label><span>*</span>
					<span id="error_name" class="error-container"></span>
					<input name="name" value="" type="text" class="form-control">
				</div>
				<div class="form-group">
					<label class="text-for-popup" for="surname"><?= GetMessage("CALLBACK_SURNAME")?></label><span>*</span>
					<span id="error_surname" class="error-container"></span>
					<input name="surname" value="" type="text" class="form-control">
				</div>
				<div class="form-group">
					<label class="text-for-popup" for="company"><?= GetMessage("CALLBACK_COMPANY")?></label>
					<input name="company" value="" type="text" class="form-control">
				</div>
				<div class="form-group">
					<label class="text-for-popup" for="country"><?= GetMessage("CALLBACK_COUNTRY")?></label><span>*</span>
					<span id="error_country" class="error-container"></span>
					<select class="form-control" name="country">
						<option selected><?=GetMessage("CALLBACK_CITIZEN_SELECT") ?></option>
						<? foreach($arCountries as $ctzn): ?>
						<option><?=$ctzn["UF_NAME" . POSTFIX_PROPERTY] ?></option>
						<? endforeach; ?>
					</select>
				</div>
				<div class="form-group">
					<label class="text-for-popup" for="email"><?= GetMessage("CALLBACK_EMAIL")?></label><span>*</span>
					<span id="error_email" class="error-container"></span>
					<input name="email" type="email" value="" class="form-control">
				</div>
				<div class="form-group">
					<label class="text-for-popup" for="phone"><?= GetMessage("CALLBACK_PHONE")?></label><span>*</span>
					<span id="error_phone" class="error-container"></span>
					<input style="width:100% !important" id="phone_field" name="phone" type="tel" value="" class="form-control">
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
						<input placeholder="<?=GetMessage("CALLBACK_CAPTCHA") ?>" style="width:70%" name="captcha" type="text" value="" class="form-control">
					</div>
					<input name="captcha_code" type="hidden" value="<?=htmlspecialchars($captcha->GetCodeCrypt());?>">
				</div>
				<span><?=GetMessage("CALLBACK_NOTIFY")?></span>
			</div>
			<div class="modal-footer">
				<button type="button" id="sendForm" class="btn btn-primary"><?= GetMessage("CALLBACK_SEND_BTN")?></button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
(function ($) {
		$("#phone_field").intlTelInput({
			utilsScript: "/local/templates/travelsoft/components/bitrix/system.auth.authorize/flat_ihf/js/utils.min.js",
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
			var name = $('input[name="name"]').val();
			var surname = $('input[name="surname"]').val();
			var company = $('input[name="company"]').val();
			var phone = $("#phone_field").intlTelInput("getNumber");
			var email = $('input[name="email"]').val();
			var country = $('select[name="country"]').children("option:selected").val();
			var capthca = $('input[name="captcha"]').val();
			var capthcaCode = $('input[name="captcha_code"]').val();
			var is_error = false;

			if(name)
			{ 
				$('#error_name').text(''); 
			}
			else
			{ 
				$('#error_name').text('<?=GetMessage("CALLBACK_NAME_ERROR")?>');
				is_error = true;
			}

			if(surname)
			{ 
				$('#error_surname').text(''); 
			}
			else
			{ 
				$('#error_surname').text('<?=GetMessage("CALLBACK_SURNAME_ERROR")?>');
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

			if(country.indexOf("Ваша") == -1 && country.indexOf("Your") == -1)
			{
				$('#error_country').text('');
			}
			else
			{
				$('#error_country').text('<?=GetMessage("CALLBACK_COUNTRY_ERROR")?>');
				is_error = true;
			}

			if(phone)
			{
				$('#error_phone').text(''); 
			}
			else
			{
				$('#error_phone').text('<?=GetMessage("CALLBACK_PHONE_ERROR")?>');
				is_error = true;
			}

			if(!is_error)
			{
				$.ajax({
					url: '/local/templates/travelsoft/components/bitrix/system.auth.authorize/flat_ihf/ajax.php',
					type: 'post',
					cache: false,
					data: {"name":name, "surname":surname, "phone":phone, "email":email, "country":country,
						   "company":company, "capthca":capthca, "captcha_code":capthcaCode,},
					success: function(data){
						if (data.indexOf('errorCaptcha') != -1)
						{
							var captchaNewCode = data.split('_')[1];

							$('#error_captcha').text('<?=GetMessage("CALLBACK_CAPTCHA_ERROR")?>');
							$('#captchaBlock').attr('src', '/bitrix/tools/captcha.php?captcha_code=' + captchaNewCode);
							$('input[name="captcha_code"]').val(captchaNewCode);
	
						} else 
						{
							$('#callbackModalPress .modal-body').html('<span class=\"ok-container\"><?=GetMessage("CALLBACK_OK")?></span>');
							$('#callbackModalPress .modal-footer').text('');
						}
					},
					error: function(jqxhr, status, exception) {
							 alert('Exception:', exception);
					}
				});

			}

		});
})(jQuery);
<?if (strlen($arResult["LAST_LOGIN"])>0):?>
try{document.form_auth.USER_PASSWORD.focus();}catch(e){}
<?else:?>
try{document.form_auth.USER_LOGIN.focus();}catch(e){}
<?endif?>
</script>

