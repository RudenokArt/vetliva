
<?php
$titles = [
    "placements" => "Поставщики размещений",
    "sanatorium" => "Поставщики санаториев",
    "excursiontours" => "Поставщики экскурсионных туров",
    "transfers" => "Поставщики трансферов",
        ]
?>

<label><b>Выберите поставщика</b></label>
<select id="providers-selection" onchange="partnersManager.showObjectsList()" class="partners-manager-selection-provider__select">
    <? foreach ($arResult['providers-list'] as $provider_type => $arr_providers): ?>
        <option></option>
        <optgroup label="<?= $titles[$provider_type] ?>">
            <? foreach ($arr_providers as $id => $name): ?>
                <option value="<?= $id ?>" <? if ($arResult['current-provider'] == $id): ?>selected<? endif ?>><?= $name ?></option>
            <? endforeach ?>
        </optgroup>
        <? endforeach ?>
</select>
