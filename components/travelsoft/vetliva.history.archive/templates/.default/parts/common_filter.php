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
CJSCore::Init(array('popup', 'date'));

?>

<div class="filter-area white-area">
    <form id="archive-filter" method="GET" action="<?= $APPLICATION->GetCurPageParam("", array("HISTORY_ARCHIVE[DATE_FROM]", "HISTORY_ARCHIVE[DATE_TO]"), false); ?>">
        <?= bitrix_sessid_post() ?>
        <input type="hidden" name="object" value="<?= $arParams["OBJECT"] ?>">
        <fieldset>
            <legend>Фильтр архива</legend>
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                    <div class="form-group has-feedback">
                        <label>Объект</label>
                        <select required="" title="Объект" class="select" name="HISTORY_ARCHIVE[SERVICE]">
                            <option></option>
                            <? foreach ($arResult["SERVICES"] as $k => $v): ?>
                                <? if (is_array($v)): ?>
                                    <optgroup label="<?= $arResult["IBLOCK_ELEMENTS"][$k] ?>">
                                        <? foreach ($v as $service_id => $service_name): ?>
                                            <option <? if ($_REQUEST["HISTORY_ARCHIVE"]["SERVICE"] == $service_id): ?>selected<? endif ?> value="<?= $service_id ?>"><?= $service_name ?></option>
                                        <? endforeach ?>
                                    </optgroup>
                                <? else: ?>

                                    <option <? if ($_REQUEST["HISTORY_ARCHIVE"]["SERVICE"] == $k): ?>selected<? endif ?> value="<?= $k ?>"><?= $v ?></option>

                                <? endif ?>
                            <? endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                    <div class="form-group has-feedback">
                        <label>Действие</label>
                        <select required="" title="Действие" class="select" name="HISTORY_ARCHIVE[ACTION]">
                            <option></option>
                            <option <?if($_REQUEST["HISTORY_ARCHIVE"]["ACTION"] === "delete"):?>selected<?endif?> value="delete">Удаление</option>
                            <option <?if($_REQUEST["HISTORY_ARCHIVE"]["ACTION"] === "add"):?>selected<?endif?> value="add">Создание</option>
                            <option <?if($_REQUEST["HISTORY_ARCHIVE"]["ACTION"] === "update"):?>selected<?endif?> value="update">Изменение</option>
                            <option <?if($_REQUEST["HISTORY_ARCHIVE"]["ACTION"] === "history"):?>selected<?endif?> value="history">Просмотр данных по прошедшему периоду</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                    <div class="form-group has-feedback">
                        <label>Дата изменения c</label>
                        <input required="" onclick="BX.calendar({node: this, field: this, bTime: true})" value="<?
                        if (strlen($_REQUEST["HISTORY_ARCHIVE"]["DATE_FROM"]) > 0) {
                            echo htmlspecialchars($_REQUEST["HISTORY_ARCHIVE"]["DATE_FROM"]);
                        } else {
                            echo $arResult["DATE_FROM"];
                        }
                        ?>" class="form-control" id="HISTORY_ARCHIVE[DATE_FROM]" type="text" title="Дата изменения с"  name="HISTORY_ARCHIVE[DATE_FROM]">
                        <span class="glyphicon glyphicon-calendar form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                    <div class="form-group has-feedback">
                        <label>Дата изменения по</label>
                        <input required="" onclick="BX.calendar({node: this, field: this, bTime: true})" value="<?
                        if (strlen($_REQUEST["HISTORY_ARCHIVE"]["DATE_TO"]) > 0) {
                            echo htmlspecialchars($_REQUEST["HISTORY_ARCHIVE"]["DATE_TO"]);
                        } else {
                            echo $arResult["DATE_TO"];
                        }
                        ?>" class="form-control" id="HISTORY_ARCHIVE[DATE_TO]" title="Дата изменения по" type="text" name="HISTORY_ARCHIVE[DATE_TO]">
                        <span class="glyphicon glyphicon-calendar form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>
            </div>
            <div class="row mt-10">
                
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-right">
                    <button type="submit" name="HISTORY_ARCHIVE[SUBMIT]" value="submit" class="btn btn-primary">Показать</button>
                </div>
            </div>
        </fieldset>
    </form>
</div>

<?if ($arResult["LOAD_RESULT"]):?>
<script>
$(document).ready(function () {
    
    var $archive_container = $("#archive-container");
    
    $.post($archive_container.data("ajax-url"), $("#archive-filter").serialize(), function (resp) {

        if (typeof resp === "string" && resp.length) {
            $archive_container.replaceWith(resp);
        } else {
            $archive_container.replaceWith(`<?= ShowError("По данным архива ничего не найдено.")?>`);
        }
        
    });
    
});    
</script>
<?endif?>