<h3><?= $arResult['SIMPLE-PRICES-MANAGE']['OBJECT-NAME'] ?>. Упращенный режим просмотра наличия цен и квот</h3>
<div class="spm-filter">
    <form onsubmit="partnersManager.showSimplePricesManage(<?= $arResult['SIMPLE-PRICES-MANAGE']['OBJECT-ID'] ?>); return false;" actio="#" class="form-inline">
        <? if ($arResult['PROVIDER-TYPE'] !== "excursiontours"): ?>
            <div class="form-group">
                <label>Выберите номер</label>
            </div>

            <div class="form-group">
                <select class="select spm-filter__select" id="spm-filter-select">
                    <option value="0">Все</option>
                    <? foreach ($arResult['SIMPLE-PRICES-MANAGE']['ALL-SERVICES'] as $service_id => $arr_service): ?>
                        <option <? if ($service_id == $arResult['SIMPLE-PRICES-MANAGE']['CURRENT-SERVICE']): ?>selected<? endif ?> value="<?= $service_id ?>"><?= $arr_service[0]['UF_NAME'] ?></option>
                    <? endforeach ?>
                </select>
            </div>
        <? endif ?>
        <div class="form-group">
            <label>Дата с</label>
            <input class="form-control" id="spm-filter-date-from" type="text" autocomplete="off" onclick="BX.calendar({node: this, field: this, bTime: false});" placeholder="dd.mm.yyyy" value="<?= $arResult['SIMPLE-PRICES-MANAGE']['DATE-FROM'] ?>">
        </div>
        <div class="form-group">
            <label>Дата по</label>
            <input class="form-control" id="spm-filter-date-to" type="text" autocomplete="off" onclick="BX.calendar({node: this, field: this, bTime: false});" placeholder="dd.mm.yyyy" value="<?= $arResult['SIMPLE-PRICES-MANAGE']['DATE-TO'] ?>">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Показать</button>
        </div>
    </form>
</div>
<? foreach ($arResult['SIMPLE-PRICES-MANAGE']['DATA'] as $service_id => $arr_service_data): ?>
    <? foreach ($arResult['SIMPLE-PRICES-MANAGE']['DATA'] as $arr_service): ?>
        <div class="spm-table-wrapper">
            <table border="1" class="spm-table">
                <tr>
                    <td colspan="<?= count($arResult['SIMPLE-PRICES-MANAGE']['FORMATTED-DATES']) + 1 ?>">
                        <h4><?= $arr_service['NAME'] ?> &nbsp; <small><a href="<?= $arr_service['PRICES-TABLE-DETAIL-LINK'] ?>"> перейти к детальному просмотру цен и квот</a><small></h4>
                                    </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <? foreach ($arResult['SIMPLE-PRICES-MANAGE']['FORMATTED-DATES'] as $arr_date): ?>
                                            <td><?= $arr_date['title'] ?></td>
                                        <? endforeach ?>
                                    </tr>
                                    <tr>
                                        <td><b>Квота<b></td>
                                                    <? foreach ($arr_service['DATA'] as $arr_service_data): ?>
                                                        <td style="background-color: <? if ($arr_service_data['QUOTA'] > 0 && !$arr_service_data['STOP_SALE'] && !$arr_service_data['NO_ARRIVALS']): ?>lightgreen<? else: ?>red<? endif ?>"><b><?= $arr_service_data['QUOTA'] ?></b></td>
                                                    <? endforeach ?>
                                                    </tr>
                                                    <tr>
                                                        <td><b>в продаже<b></td>
                                                                    <? foreach ($arr_service['DATA'] as $arr_service_data): ?>
                                                                        <td style="background-color: <? if ($arr_service_data['IN_SALE'] > 0 && !$arr_service_data['STOP_SALE'] && !$arr_service_data['NO_ARRIVALS']): ?>lightgreen<? else: ?>red<? endif ?>"><b><?= $arr_service_data['IN_SALE'] ?></b></td>
                                                                    <? endforeach ?>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><b>Наличие цены</b></td>
                                                                        <? foreach ($arr_service['DATA'] as $arr_service_data): ?>
                                                                            <td style="background-color: <? if ($arr_service_data['PRICE'] && !$arr_service_data['STOP_SALE'] && !$arr_service_data['NO_ARRIVALS']): ?>lightgreen<? else: ?>red<? endif ?>"><? if ($arr_service_data['PRICE']): ?><b>есть</b><? else: ?><b>нет</b><? endif ?></td>
                                                                        <? endforeach ?>
                                                                    </tr>
                                                                    </table> 
                                                                    </div>
                                                                <? endforeach ?>

                                                            <? endforeach; ?>
