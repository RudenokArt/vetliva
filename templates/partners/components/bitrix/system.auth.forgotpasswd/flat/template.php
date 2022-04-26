<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->AddViewContent('htmlClass', 'login-container');
?>

<!-- Password recovery -->
<form name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
<?
if (strlen($arResult["BACKURL"]) > 0)
{
?>
        <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?
}
?>
        <input type="hidden" name="AUTH_FORM" value="Y">
        <input type="hidden" name="TYPE" value="SEND_PWD">
        
    <div class="panel panel-body login-form">
        <div class="text-center">
            <div class="icon-object border-warning text-warning"><i class="icon-spinner11"></i></div>
            <h5 class="content-group"><?= GetMessage("AUTH_REQUEST_FOR_CHANGE_PASS")?>
                <?if (!$arParams["~AUTH_RESULT"]):?>
                    <small class="display-block"><?=GetMessage("AUTH_FORGOT_PASSWORD_1")?></small>
                <?endif?>
            </h5>
            <?if ($arParams["~AUTH_RESULT"]):?>
                <div class="text-center"><?ShowMessage($arParams["~AUTH_RESULT"]);?></div>
            <?endif?>
        </div>

        <div class="form-group has-feedback">
            <input type="email" name="USER_EMAIL" class="form-control" placeholder="<?= GetMessage("AUTH_EMAIL")?>">
            <div class="form-control-feedback">
                <i class="icon-mail5 text-muted"></i>
            </div>
        </div>
        <div class="form-group">
            <div class="text-center">
                    <a href="<?=$arResult["AUTH_AUTH_URL"]?>" rel="nofollow"><?=GetMessage("AUTH_AUTH")?></a>
            </div>
        </div>
        <button type="submit" name="send_account_info" value="<?=GetMessage("AUTH_SEND")?>" class="btn bg-blue btn-block"><?=GetMessage("AUTH_SEND")?> <i class="icon-arrow-right14 position-right"></i></button>
    </div>
</form>
<script type="text/javascript">
document.bform.USER_EMAIL.focus();
</script>

<!-- /password recovery -->
