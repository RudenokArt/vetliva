<button class="btn btn-primary"
        type="button" onclick="document.location.href = '/partners/partners-manager/dobavlenie-redaktirovanie-tarifov/?provider_id=<?=$arResult['PROVIDER-ID']?>'">
    Добавление/редактирование тарифов поставщика
</button>

<table class="table objects-list">
    <thead>
        <tr>
            <td colspan='8'><h3>Список экскурсий и экскурсионных туров</h3></td>
        </tr>
        <tr>
            <td colspan="8">
                <div class='form-inline filter'>
                    <div class='form-group'>
                        <label>По активности</label>
                        <select class="filter__by-active form-control" onchange="partnersManager.filterExcursiontoursList()">
                            <option value="all">Все</option>
                            <option value="active">Только активные</option>
                            <option value="unactive">Только неактивные</option>
                        </select>
                    </div>
                    <div class='form-group'>
                        <label>По дате создания</label>
                        <input placeholder='dd.mm.yyyy' onclick="BX.calendar({node: this, field: this, bTime: false, callback_after: () => {this.dispatchEvent(new Event('input'));}});" class='filter__by-date-create form-control' type='text' oninput="partnersManager.filterExcursiontoursList()">
                    </div>
                    <div class='form-group'>
                        <label>По дате изменения</label>
                        <input placeholder='dd.mm.yyyy' onclick="BX.calendar({node: this, field: this, bTime: false, callback_after: () => {this.dispatchEvent(new Event('input'));}});" class='filter__by-date-change form-control' type='text' oninput="partnersManager.filterExcursiontoursList()">
                    </div>
                    <div class='form-group'>
                        <label>Показать</label>
                        <select class="filter__by-multipledays form-control" onchange="partnersManager.filterExcursiontoursList()">
                            <option value="all">Все</option>
                            <option value="multiple-days">Только многодневные</option>
                            <option value="one-day">Однадневные</option>
                        </select>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td><b>ID</b></td>
            <td><b>Название</b></td>
            <td><b>Активность</b></td>
            <td><b>Дата создания</b></td>
            <td><b>Дата последнего изменения</b></td>
            <td><b>Многодневный</b></td>
            <td><b>Наличие autostopsale</b></td>
            <td><b>Действия</b></td>
        </tr>

    </thead>
    <tbody>
        <? foreach ($arResult['OBJECTS-LIST'] as $object): ?>
            <tr class='objects-list__row' data-active='<?= $object['ACTIVE'] ?>' data-is-multiple="<?= ($object['IS-MULTIPLE'] ? 'Y' : 'N') ?>" data-date-create='<?= $object['DATE_CREATE'] ?>' data-date-change='<?= $object['TIMESTAMP_X'] ?>'>
                <td><?= $object['ID'] ?></td>
                <td><?= $object['NAME'] ?></td>
                <td><?= ($object['ACTIVE'] === "Y" ? "Да" : "Нет") ?></td>
                <td><?= $object['DATE_CREATE'] ?></td>
                <td><?= $object['TIMESTAMP_X'] ?></td>
                <td><?= ($object['IS-MULTIPLE'] ? 'Да' : 'Нет') ?></td>
                <td><?= ($object['AUTOSTOPSALE'] ? 'Да' : 'Нет') ?></td>
                <td class="actions">
                    <a class="actions__action" href="<?= $object['edit-page']?>">Редактировать</a>
                    <a class="actions__action" href="<?= $object['PRICES-TABLE-DETAIL-LINK']?>">Детальный просмотр наличия цен и квот</a>
                    <?if(!$object['IS-MULTIPLE']):?><a class="actions__action" href="javascript:partnersManager.showSimplePricesManage(<?= $object['ID']?>)">Упращенный просмотр наличия цен и квот</a><?endif?>
                    <a class="actions__action" href="/partners/partners-manager/archive/?service_id=<?= $object['SERVICE-ID']?>&object=prices&provider_id=<?= $arResult['PROVIDER-ID']?>">Журнал изменений по ценам</a>
                    <a class="actions__action" href="/partners/partners-manager/archive/?service_id=<?= $object['SERVICE-ID']?>&object=quotas&provider_id=<?= $arResult['PROVIDER-ID']?>">Журнал изменений по квотам</a>
                </td>
            </tr>
        <? endforeach ?>
    </tbody>
    <tfoot></tfoot>
</table>
