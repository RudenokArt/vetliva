<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

use \Bitrix\Main\Localization\Loc;

$captcha = $APPLICATION->CaptchaGetCode();
?>
<div class="add-review-form mfp-hide" id="feedback-guide-form-wrapper">
    <div class="row form bg-none">
        <h3 class="bx-title"><?= Loc::getMessage('T_FEEDBACK_GUIDE_TITLE')?></h3>
        <div class="col-md-12">
            <div class="container">
                <form class="form-simple-ajax">
                    <?= bitrix_sessid_post() ?>
                    <input type="hidden" name="FORM_ID" value="77">
                    <input type="hidden" name="SITE_ID" value="<?= SITE_ID ?>">
                    <textarea name="MESSAGE" class="hide">1</textarea>
                    <input type="hidden" name="GUIDE_ID" value="<?=$arParams['GUIDE_ID']?>">

                    <div class="row">
                        <div class="col-md-12 col-lg-12">

                            <div class="form-field">
                                <?=Loc::getMessage('T_FEEDBACK_GUIDE_NAME')?><span class="starrequired">*</span>
                                <input type="text" name="NAME" class="field-input review" required>
                            </div>

                            <div class="form-field">
                                <?=Loc::getMessage('T_FEEDBACK_GUIDE_PHONE')?><span class="starrequired">*</span>
                                <input type="tel" name="PHONE" class="field-input review" required>
                            </div>

                            <div class="form-field">
                                <?=Loc::getMessage('T_FEEDBACK_GUIDE_EMAIL')?><span class="starrequired">*</span>
                                <input type="email" name="EMAIL" class="field-input review" required>
                            </div>

                            <div class="form-field">
                                <?=Loc::getMessage('T_FEEDBACK_GUIDE_MESSAGE')?><span class="starrequired">*</span>
                                <textarea name="MESS" required></textarea>
                            </div>

                            <div class="form-field form-field--captcha">
                                <input name="captcha_code" value="<?=$captcha?>" type="hidden">
                                <input id="captcha_word" name="captcha_word" type="text" required>
                                <img src="/bitrix/tools/captcha.php?captcha_code=<?=$captcha?>">
                                <button type="button" class="button-update-captcha" aria-label="<?=Loc::getMessage('T_FEEDBACK_GUIDE_CAPTCHA_RELOAD')?>"><?=Loc::getMessage('T_FEEDBACK_GUIDE_CAPTCHA_RELOAD')?></button>
                            </div>

                            <input type="submit" value="<?=Loc::getMessage('T_FEEDBACK_GUIDE_SUBMIT')?>" class="awe-btn awe-btn-5 arrow-right awe-btn-lager text-uppercase float-right mt-20 mr-20">

                            <div class="form-message"></div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>