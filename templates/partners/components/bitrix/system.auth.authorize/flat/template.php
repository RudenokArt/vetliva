<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->AddViewContent('htmlClass', 'login-container');
?>

<?if ($_SESSION["JUST_REGISTER_PROVIDER"]):?>
    <div class="panel panel-body login-form">
        <div class="text-center">
            <div class="icon-object border-slate-300 text-slate-300"><i class="icon-reading"></i></div>
            <h5 class="content-group">Благодарим Вас за регистрацию!</h5>
            <p>После проверки регистрационных данных, Ваш личный кабинет будет активирован в течение 24 часов, а на указанную при регистрации почту будет отправлено соответствующее уведомление.</p>
        </div>
    </div>
<?unset($_SESSION["JUST_REGISTER_PROVIDER"]); return; endif; ?>

<!-- Simple login form -->
<form name="form_auth" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
    
    <input type="hidden" name="AUTH_FORM" value="Y" />
    <input type="hidden" name="TYPE" value="AUTH" />
    <?if (strlen($arResult["BACKURL"]) > 0):?>
    <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
    <?endif?>
    <?foreach ($arResult["POST"] as $key => $value):?>
    <input type="hidden" name="<?=$key?>" value="<?=$value?>" />
    <?endforeach?>
    
    <div class="panel panel-body login-form">
        <div class="text-center">
            <div class="icon-object border-slate-300 text-slate-300"><i class="icon-reading"></i></div>
            <h5 class="content-group"><?echo GetMessage("AUTH_TITLE")?> <small class="display-block"><?=GetMessage("AUTH_PLEASE_AUTH")?></small></h5>
            <?
            if (strpos($arParams["~AUTH_RESULT"]["MESSAGE"], "Доступ запрещен") !== false) {
                $arParams["~AUTH_RESULT"]["MESSAGE"] = "Ваш личный кабинет не активирован в системе. За дополнительной информацией обратитесь в службу поддержки по телефону +375 (29) 911-56-54.";
            }
            ShowMessage(str_replace(array("логин", "login"), array("email", "email"), $arParams["~AUTH_RESULT"]));
            ShowMessage($arResult['ERROR_MESSAGE']);?>
        </div>

        <div class="form-group has-feedback has-feedback-left">
            <input required type="text" maxlength="255" name="USER_LOGIN" value="<?=$arResult["LAST_LOGIN"]?>" class="form-control" placeholder="<?=GetMessage("AUTH_LOGIN")?>">
            <div class="form-control-feedback">
                <i class="icon-user text-muted"></i>
            </div>
        </div>

        <div class="form-group has-feedback has-feedback-left">
            <input required type="password" name="USER_PASSWORD" maxlength="255" autocomplete="off" class="form-control" placeholder="<?=GetMessage("AUTH_PASSWORD")?>">
            <div class="form-control-feedback">
                <i class="icon-lock2 text-muted"></i>
            </div>
        </div>
        
<?if($arResult["CAPTCHA_CODE"]):?>
        <div class="form-group has-feedback has-feedback-left">
                <input type="hidden" name="captcha_sid" value="<?echo $arResult["CAPTCHA_CODE"]?>" />
                <img src="/bitrix/tools/captcha.php?captcha_sid=<?echo $arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" /></td>
        </div>
        <div class="form-group has-feedback has-feedback-left">
                <input placeholder="<?echo GetMessage("AUTH_CAPTCHA_PROMT")?>" class="form-control" type="text" name="captcha_word" maxlength="50" value="" size="15" />
        </div>
<?endif;?>

<?if ($arResult["STORE_PASSWORD"] == "Y"):?>
        <div class="form-group has-feedback has-feedback-left">
                <div class="checkbox">
                        <label for="USER_REMEMBER">
                                <input type="checkbox" id="USER_REMEMBER" name="USER_REMEMBER" value="Y" />&nbsp;<?=GetMessage("AUTH_REMEMBER_ME")?>
                        </label>
                </div>
        </div>
<?endif?>

<?if ($arParams["NOT_SHOW_LINKS"] != "Y"):?>

        <div class="form-group has-feedback has-feedback-left">
                
                <div class="text-center">
                        <a rel="nofollow" href="<?= $arResult["AUTH_FORGOT_PASSWORD_URL"] ?>"><?= GetMessage("AUTH_FORGOT_PASSWORD_2") ?></a>
                </div>
        
        

        <?if($arResult["NEW_USER_REGISTRATION"] == "Y" && $arParams["AUTHORIZE_REGISTRATION"] != "Y" && !$USER->IsAuthorized()):?>
                <div class="form-group">
                        <div class="text-center">
                                <a href="<?=$arResult["AUTH_REGISTER_URL"]?>" rel="nofollow"><?=GetMessage("AUTH_REGISTER")?></a>
                                <!--<small class="display-block"><?=GetMessage("AUTH_FIRST_ONE")?></small>-->
                        </div>
                </div>
        <?endif?>
        </div>

<?endif?>

        <div class="form-group">
            <button type="submit" name="Login" value="<?=GetMessage("AUTH_AUTHORIZE")?>" class="btn btn-primary btn-block"><?=GetMessage("AUTH_AUTHORIZE")?> <i class="icon-circle-right2 position-right"></i></button>
        </div>

        
    </div>
</form>
<!-- /simple login form -->


<script type="text/javascript">
<?if (strlen($arResult["LAST_LOGIN"])>0):?>
try{document.form_auth.USER_PASSWORD.focus();}catch(e){}
<?else:?>
try{document.form_auth.USER_LOGIN.focus();}catch(e){}
<?endif?>
</script>

<?if($arResult["AUTH_SERVICES"]):?>
<?
//$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "",
//	array(
//		"AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
//		"CURRENT_SERVICE" => $arResult["CURRENT_SERVICE"],
//		"AUTH_URL" => $arResult["AUTH_URL"],
//		"POST" => $arResult["POST"],
//		"SHOW_TITLES" => $arResult["FOR_INTRANET"]?'N':'Y',
//		"FOR_SPLIT" => $arResult["FOR_INTRANET"]?'Y':'N',
//		"AUTH_LINE" => $arResult["FOR_INTRANET"]?'N':'Y',
//	),
//	$component,
//	array("HIDE_ICONS"=>"Y")
//);
//?>
<?endif?>