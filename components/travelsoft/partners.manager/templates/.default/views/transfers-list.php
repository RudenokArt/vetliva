
<table class="table objects-list">
    <thead>
        <tr>
            <td colspan='3'><h3>Список объектов</h3></td>
        </tr>
        <tr>
            <td colspan="3">
                <div class='form-inline filter'>
                    <div class='form-group'>
                        <label>По названию</label>
                        <input class='filter__by-name form-control' type='text' oninput="partnersManager.filterTransfersList()">
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td><b>ID</b></td>
            <td><b>Название</b></td>
            <td><b>Действия</b></td>
        </tr>

    </thead>
    <tbody>
        <? foreach ($arResult['OBJECTS-LIST'] as $id => $name): ?>
        <tr class='objects-list__row' data-name='<?= $name?>'>
                <td><?= $id ?></td>
                <td><?= $name?></td>
                <td class="actions">
                    <a class="actions__action" href="/partners/partners-manager/transfers-prices-table/?row_id=<?= $id?>&provider_id=<?= $arResult['PROVIDER-ID']?>">Детальный просмотр наличия цен и квот</a>
                </td>
            </tr>
        <? endforeach ?>
    </tbody>
    <tfoot></tfoot>
</table>
