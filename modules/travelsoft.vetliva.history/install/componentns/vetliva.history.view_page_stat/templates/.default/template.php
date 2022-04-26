<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

$hash = md5(intval($arParams["ID"]));
?>


<div class="filter-area white-area">
    <form id="view-page-history-filter" method="GET" action="<?= $APPLICATION->GetCurPageParam("", array("HISTORY_VP_STAT[DATE_FROM]", "HISTORY_VP_STAT[DATE_TO]"), false); ?>">
        <?= bitrix_sessid_post()?>
        <input type="hidden" name="ID" value="<?= $arParams["ID"]?>">
        <input type="hidden" name="hash" value="<?= $hash?>">
        <fieldset>
            <legend><?= GetMessage("FILTER_TITLE") ?></legend>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                    <div class="form-group has-feedback">
                        <label for="HISTORY_VP_STAT[DATE_FROM]"><?= GetMessage("FILTER_DATE_FROM_TITLE") ?></label>
                        <input required="" onclick="BX.calendar({node: this, field: this, bTime: true})" value="<? if (strlen($_REQUEST["HISTORY_VP_STAT"]["DATE_FROM"]) > 0) {
    echo htmlspecialchars($_REQUEST["HISTORY_VP_STAT"]["DATE_FROM"]);
} else {
    echo $arResult["DATE_FROM"];
} ?>" class="form-control" id="HISTORY_VP_STAT[DATE_FROM]" type="text" name="HISTORY_VP_STAT[DATE_FROM]">
                        <span class="glyphicon glyphicon-calendar form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                    <div class="form-group has-feedback">
                        <label for="HISTORY_VP_STAT[DATE_TO]"><?= GetMessage("FILTER_DATE_TO_TITLE") ?></label>
                        <input required="" onclick="BX.calendar({node: this, field: this, bTime: true})" value="<? if (strlen($_REQUEST["HISTORY_VP_STAT"]["DATE_TO"]) > 0) {
    echo htmlspecialchars($_REQUEST["HISTORY_VP_STAT"]["DATE_TO"]);
} else {
    echo $arResult["DATE_TO"];
} ?>" class="form-control" id="HISTORY_VP_STAT[DATE_TO]" type="text" name="HISTORY_VP_STAT[DATE_TO]">
                        <span class="glyphicon glyphicon-calendar form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>
            </div>
            <div class="row mt10">
                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12" style="padding-top: 10px"><a href="<?= $APPLICATION->GetCurDir() ?>">Вернуться к списку</a></div>
                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12 text-right">
                    <button type="submit" name="HISTORY_VP_STAT[SUBMIT]" value="submit" class="btn btn-primary"><?= GetMessage("FILTER_SUBMIT_TITLE") ?></button>
                    <button type="submit" name="HISTORY_VP_STAT[RESET]" value="reset" class="btn btn-primary"><?= GetMessage("FILTER_CLEAR_TITLE") ?></button>
                </div>
            </div>
        </fieldset>
    </form>
</div>

<div id="vue-app"></div>
<?include "vue-components/conversion.php"?>
<?include "vue-components/age.php"?>
<?include "vue-components/devices.php"?>
<?include "vue-components/male.php"?>
<?include "vue-components/geography.php"?>
<?include "vue-components/vue-app.php"?>
