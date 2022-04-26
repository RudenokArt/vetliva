<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

?>
<div class="panel panel-flat">

    <? if (!empty($arResult["ERRORS"])): ?>
        <div id="errors-box">
            <?
            foreach ($arResult["ERRORS"] as $error) {
                ShowError($error);
            }
            ?>
        </div>
    <? endif ?>

    <a class="btn btn-primary" data-toggle="modal" id="autostopsale-mass-edit-btn" href="#modal_form_vertical">Нажмите для массового редактирование/добавление autostopsale</a>

    <? if (!empty($arResult["AUTOSTOPSALE"])): ?>
    <div id="table">
        <div class="row table-row">
            <div class="col-md-3">
                <b>Название тура</b>
            </div>
            <div class="col-md-3">
                <b>Активность</b>
            </div>
            <div class="col-md-3">
                <b>Кол-во часов до окончания продаж</b>
            </div>
            <div class="col-md-3">
                <b>Действия</b>
            </div>
        </div>
        <? foreach ($arResult["AUTOSTOPSALE"] as $arr_autostopsale): ?>
            <form method="POST" action="<?= POST_FORM_ACTION_URI ?>">
                <?= bitrix_sessid_post() ?>
                <input type="hidden" name="autostopsale[service_id]" value="<?= $arr_autostopsale["UF_SERVICE_ID"]?>">
                <div class="row table-row">

                    <div class="col-md-3">
                        <?= $arResult["SERVICES"][$arr_autostopsale["UF_SERVICE_ID"]][0]["UF_NAME"] ?>
                    </div>
                    <div class="col-md-3">
                        <input type="hidden" value="0" name="autostopsale[active]">

                        <input <? if ($arr_autostopsale["UF_ACTIVE"]): ?>checked=""<? endif ?> type="checkbox" value="1" name="autostopsale[active]">

                    </div>
                    <div class="col-md-3">
                        <input name="autostopsale[hours]" value="<?= $arr_autostopsale["UF_HOURS"] ?>" class="form-control hours-input" type="text">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary action-btn">Сохранить</button>
                        <a onclick="return confirm('Действительно хотите удалить ?')" class="btn btn-danger action-btn" href="<?= $APPLICATION->GetCurPageParam("delete=" . $arr_autostopsale["ID"] . "&sessid=" . bitrix_sessid() , array("delete", "sessid"), false) ?>">Удалить</a>
                    </div>
                </div>
            </form>
        <? endforeach ?>
        </div>
    <? endif ?>

    <div class="row">
    </div>
    <div id="modal_form_vertical" class="modal fade massedit-autostopsale">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close close-modal" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title"></h5>
                </div>

                <form method="POST" id="massedit-autostopsale" action="<?= POST_FORM_ACTION_URI ?>">
                    <?= bitrix_sessid_post() ?>
                    <div class="modal-body">
                        <div class="form-group">
                            <label><b>Выберите туры (начните вводить название; для выделение сразу нескольких туров нажмите ctrl+правая кнопка мыши)</b></label>
                            <select name="autostopsale[massedit][services_id][]" multiple="" class="select">
                                <? foreach ($arResult["SERVICES"] as $arr_service): ?>
                                    <option value="<?= $arr_service[0]["ID"] ?>"><?= $arr_service[0]["UF_NAME"] ?></option>
                                <? endforeach ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><b>Активность</b></label>
                            <select name="autostopsale[massedit][active]" class="form-control">
                                <option value="1" selected="">Да</option>
                                <option value="0">Нет</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><b>Количество часов до закрытия продаж</b></label>
                            <input name="autostopsale[massedit][hours]" class="form-control" type="text">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link close-modal" data-dismiss="modal">Закрыть</button>
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </form>

                <div class="preloader-up" style="display: none; text-align: center; margin: 50px">
                    <div class="preloader-up-text">
                        <b>Происходит загрузка данных. Это может занять несколько минут. Благодарим за ожидание.</b>
                    </div>
                    <div class="preloader-up-text"><img src="<?= $templateFolder ?>/loading7_black.gif"></div>
                </div>

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {

            $("#massedit-autostopsale").on("submit", function () {

                $(this).hide();

                $(".preloader-up").show();

                return true;

            });

        });
        
        $(".action-btn").on("click", function () {
            $("#table").css({opacity: 0.5});
        });
    </script>