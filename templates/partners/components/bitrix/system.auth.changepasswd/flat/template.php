<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->AddViewContent('htmlClass', 'login-container');
?>

<!-- Change password form -->
<form method="post" action="<?=$arResult["AUTH_URL"]?>" name="bform">
    <?if (strlen($arResult["BACKURL"]) > 0): ?>
    <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
    <? endif ?>
    <input type="hidden" name="AUTH_FORM" value="Y">
    <input type="hidden" name="TYPE" value="CHANGE_PWD">
    
    <div class="row">
        <div class="col-lg-6 col-lg-offset-3">
            <div class="panel registration-form">
                <div class="panel-body">
                    <div class="text-center">
                        <div class="icon-object border-warning text-warning"><i class="icon-spinner11"></i></div>
                        <h5 class="content-group-lg"><?=GetMessage("AUTH_CHANGE_PASSWORD")?> 
                            <small class="display-block"><?= $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></small>
                            <small class="display-block"><?= GetMessage("AUTH_REQ");?></small>
                        </h5>
                        <?if ($arParams["~AUTH_RESULT"]):?>
                                <div class="text-center"><?ShowMessage($arParams["~AUTH_RESULT"]);?></div>
                        <?endif?>
                    </div>
                   
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group has-feedback has-feedback-left">
                                <input required type="text" name="USER_LOGIN" maxlength="50" value="<?=$arResult["LAST_LOGIN"]?>" class="form-control" placeholder="<?=GetMessage("AUTH_LOGIN")?>">
                                <div class="form-control-feedback">
                                    <i class="icon-user text-muted"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input required type="text" name="USER_CHECKWORD" maxlength="50" value="<?=$arResult["USER_CHECKWORD"]?>" class="form-control" placeholder="<?=GetMessage("AUTH_CHECKWORD")?>">
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group has-feedback has-feedback-left">
                                <input required type="password" name="USER_PASSWORD" maxlength="255" autocomplete="off" class="form-control" placeholder="<?=GetMessage("AUTH_NEW_PASSWORD")?>">
                                <div class="form-control-feedback">
                                    <i class="icon-lock2 text-muted"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group has-feedback has-feedback-left">
                                <input required type="password" name="USER_CONFIRM_PASSWORD" maxlength="255" autocomplete="off" class="form-control" placeholder="<?=GetMessage("AUTH_NEW_PASSWORD_CONFIRM")?>">
                                <div class="form-control-feedback">
                                    <i class="icon-lock2 text-muted"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button  type="submit" name="change_pwd" value="<?=GetMessage("AUTH_CHANGE")?>" class="btn bg-blue btn-block"><?=GetMessage("AUTH_CHANGE")?></button>
                   
                </div>
            </div>
        </div>
    </div>
    
</form>
<script type="text/javascript">
document.bform.USER_PASSWORD.focus();
</script>
<!-- /change password -->