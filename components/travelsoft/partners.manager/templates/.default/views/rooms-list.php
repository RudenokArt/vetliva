
<table class="table rooms-list">
    <thead>
        <tr>
            <td colspan='3'><h3><?= $arResult['OBJECT-NAME']?>. Номерной фонд</h3></td>
        </tr>
        <tr>
            <td><b>ID</b></td>
            <td><b>Название</b></td>
            <td><b>Действия</b></td>
        </tr>

    </thead>
    <tbody>
        <? foreach ($arResult['ROOMS-LIST'] as $object): ?>
            <tr>
                <td><?= $object['ID'] ?></td>
                <td><?= $object['UF_NAME'] ?></td>
                <td class="actions">
                    <a class="actions__action" href="/partners/partners-manager/rooms-edit/?row_id=<?= $object['ID']?>&provider_id=<?= $arResult['PROVIDER-ID']?><?if($arResult['IS-SANATORIUM']):?>&is_sanatorium=Y<?endif?>">Редактировать</a>
                    <a class="actions__action" href="/partners/partners-manager/archive/?service_id=<?= $object['ID']?>&object=prices&provider_id=<?= $arResult['PROVIDER-ID']?>">Журнал изменений по ценам</a>
                    <a class="actions__action" href="/partners/partners-manager/archive/?service_id=<?= $object['ID']?>&object=quotas&provider_id=<?= $arResult['PROVIDER-ID']?>">Журнал изменений по квотам</a>
                    <a class="actions__action" href="/partners/partners-manager/common-prices-table/?row_id=<?= $object['ID']?>&provider_id=<?= $arResult['PROVIDER-ID']?>">Просмотр наличия цен и квот</a>
                </td>
            </tr>
        <? endforeach ?>
    </tbody>
    <tfoot></tfoot>
</table>