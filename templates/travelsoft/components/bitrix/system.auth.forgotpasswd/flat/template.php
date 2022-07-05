<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

//one css for all system.auth.* forms
$APPLICATION->SetAdditionalCSS("/bitrix/css/main/system.auth/flat/style.css");
?>

<div class="bx-authform">

<?
if(!empty($arParams["~AUTH_RESULT"])):
	if($arParams["~AUTH_RESULT"]["TYPE"] == "OK")
	{
		$text = GetMessage("AUTH_SEND_OK");
		$class = "alert-success";
	}
	else
	{
		$text = GetMessage("AUTH_SEND_ERROR");
		$class = "alert-danger";
	}

?>
	<div class="alert <?=$class?>"><?=$text?></div>
<?endif?>

	<h3 class="bx-title"><?=GetMessage("AUTH_GET_CHECK_STRING")?></h3>



	<form name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
<?if($arResult["BACKURL"] <> ''):?>
		<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?endif?>
		<input type="hidden" name="AUTH_FORM" value="Y">
		<input type="hidden" name="TYPE" value="SEND_PWD">

		<div class="bx-authform-formgroup-container">

			<div class="bx-authform-input-container">
				<input class="forgotpass" placeholder="E-mail" type="text" name="USER_LOGIN" maxlength="255" value="<?=$arResult["USER_LOGIN"]?>" />
				<input type="hidden" name="USER_EMAIL" />
			</div>

		</div>

<?if($arResult["PHONE_REGISTRATION"]):?>
		<div class="bx-authform-formgroup-container">
			<div class="bx-authform-label-container"><?echo GetMessage("forgot_pass_phone_number")?></div>
			<div class="bx-authform-input-container">
				<input type="text" name="USER_PHONE_NUMBER" maxlength="255" value="<?=$arResult["USER_PHONE_NUMBER"]?>" />
			</div>
			<div class="bx-authform-note-container"><?echo GetMessage("forgot_pass_phone_number_note")?></div>
		</div>
<?endif?>

<?if ($arResult["USE_CAPTCHA"]):?>
		<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />

		<div class="bx-authform-formgroup-container">
			<div class="bx-authform-label-container"><?echo GetMessage("system_auth_captcha")?></div>
			<div class="bx-captcha"><img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" /></div>
			<div class="bx-authform-input-container">
				<input type="text" name="captcha_word" maxlength="50" value="" autocomplete="off"/>
			</div>
		</div>

<?endif?>
		<div class="bx-authform-formgroup-container">
			<input type="submit" onclick="forgotPassword()" class="btn btn-primary" name="send_account_info" value="<?=GetMessage("AUTH_SEND")?>" />
		</div>



	</form>

</div>

<script type="text/javascript">

let arr_num = ["1", "2", "3", "4", "5", "6", "7", "8", "9"];
      let arr_en = [
        "a",
        "b",
        "c",
        "d",
        "e",
        "f",
        "g",
        "h",
        "i",
        "j",
        "k",
        "l",
        "m",
        "n",
        "p",
        "q",
        "r",
        "s",
        "t",
        "u",
        "v",
        "w",
        "x",
        "y",
        "z",
      ];
      let arr_EN = [
        "A",
        "B",
        "C",
        "D",
        "E",
        "F",
        "G",
        "H",
        "I",
        "J",
        "K",
        "L",
        "M",
        "N",
        "P",
        "Q",
        "R",
        "S",
        "T",
        "U",
        "V",
        "W",
        "X",
        "Y",
        "Z",
      ];
      let arr_symb = ['!', '@', '#', '$', '%', '^', '~', '?'];

      const compareRandom = () => Math.random() - 0.5;

      const randomInteger = (min, max) =>
        Math.round(min - 0.5 + Math.random() * (max - min + 1));

        function generatePassword() {
        let arr = [];

        const randomInteger = (min, max) =>
          Math.round(min - 0.5 + Math.random() * (max - min + 1));

        pass = "";
        let passLenght = 12;

        for (let i = 0; i < 3; i++) {
          pass += arr_num[randomInteger(0, arr_num.length - 1)];
          pass += arr_en[randomInteger(0, arr_en.length - 1)];
          pass += arr_EN[randomInteger(0, arr_EN.length - 1)];
          pass += arr_symb[randomInteger(0, arr_symb.length - 1)];
        }

        password = pass
          .split("")
          .sort(function () {
            return 0.5 - Math.random();
          })
          .join("");

          return password
        console.log(password);
      }

     
     
	
	  password = generatePassword();


    block = document.querySelector('.content-page-detail')
    let answ = document.createElement('div')
    answ.classList.add('forgot-answ')
    block.append(answ)
    

    function forgotPassword(){

      block = document.querySelector('.content-page-detail')
      
      answ = document.querySelector('.forgot-answ')

      answ.innerHTML += `<i class="fa fa-circle-o-notch fa-spin" style="font-size:24px; margin-left: 235px;"></i>`

      email = document.querySelector('.forgotpass').value


      url = '../../local/php_interface/php-mail/index.php'
	  var req = new XMLHttpRequest();
      req.open('POST', url);
      req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      req.send(`receiver=${email}&subject=VETLIVA - Смена пароля&text=Новый временный пароль: ${password} Пароль сгенерирован автоматически. Рекомендуем изменить пароль в личном кабинете`);
      req.onreadystatechange=function (){
       if (req.readyState == 4 && req.status == 200) {
         console.log(req.responseText);
        }
	  }




	  url = '../../local/templates/travelsoft/components/bitrix/system.auth.forgotpasswd/ajax.php'
	  var req = new XMLHttpRequest();
      req.open('POST', url);
      req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      req.send(`receiver=${email}&password=${password}`);
      req.onreadystatechange=function (){
       if (req.readyState == 4 && req.status == 200) {
         console.log(req.responseText);


         spinner = document.querySelector('.fa-spin')
         spinner.style = 'display: none';

         block = document.querySelector('.content-page-detail')
          answ = document.querySelector('.forgot-answ')

         answ.innerHTML = `<div>  <p style="color: green; font-size: 24px;"> ${req.responseText} </p> </div>`
        }
	  }



 }
 






document.bform.onsubmit = function(){document.bform.USER_EMAIL.value = document.bform.USER_LOGIN.value;};
document.bform.USER_LOGIN.focus();
</script>
