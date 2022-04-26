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
if (!$arResult["STATISTICS"]['SHOWS']) { return false;}
?>

<div class="panel panel-flat">
    <div class="panel-body">
        <h4><?= $arParams["TITLE"]?></h4>
        <table class="table table-bordered">
            <?/*<thead>
                <tr>
                    <th rowspan="2">ID</th>
                    <th rowspan="2">Название</th>
                    <th colspan="3">Количество просмотров</th>
                </tr>
                <tr>
                    <th>За сегодня</th>
                    <th>За неделю</th>
                    <th>За месяц</th>
                </tr>
            </thead>
            <tbody>
                <?foreach ($arResult["ITEMS"] as $arItem):?>
                <tr>
                    <td><?= $arItem["ID"]?></td>
                    <td><a href="./detail.php?ID=<?= $arItem["ID"]?>"><?= $arItem["NAME"]?></td>
                    <td><?= intVal($arResult["STATISTICS"]['SHOWS']["TODAY"][$arItem["ID"]])?></td>
                    <td><?= intVal($arResult["STATISTICS"]['SHOWS']["WEEK"][$arItem["ID"]])?></td>
                    <td><?= intVal($arResult["STATISTICS"]['SHOWS']["MONTH"][$arItem["ID"]])?></td>
                </tr>
                <?endforeach;?>
            </tbody>*/?>
            <thead>
                <tr>
                    <th rowspan="2">ID</th>
                    <th rowspan="2">Название</th>
                    <th colspan="2">За сегодня</th>
                    <th colspan="2">За неделю</th>
                    <th colspan="2">За месяц</th>
                </tr>
                <tr>
                    <th>Кол-во просмотров</th>
                    <th>Кол-во заказов</th>
                    <th>Кол-во просмотров</th>
                    <th>Кол-во заказов</th>
                    <th>Кол-во просмотров</th>
                    <th>Кол-во заказов</th>
                </tr>
            </thead>
            <tbody>
                <?foreach ($arResult["ITEMS"] as $arItem):?>
                <tr>
                    <td><?= $arItem["ID"]?></td>
                    <td><a href="./detail.php?ID=<?= $arItem["ID"]?>"><?= $arItem["NAME"]?></a></td>
                    <td><?= intVal($arResult["STATISTICS"]['SHOWS']["TODAY"][$arItem["ID"]])?></td>
                    <td><?= intVal($arResult["STATISTICS"]['QUANTITY_BOOK']["TODAY"][$arItem["ID"]])?></td>
                    <td><?= intVal($arResult["STATISTICS"]['SHOWS']["WEEK"][$arItem["ID"]])?></td>
                    <td><?= intVal($arResult["STATISTICS"]['QUANTITY_BOOK']["WEEK"][$arItem["ID"]])?></td>
                    <td><?= intVal($arResult["STATISTICS"]['SHOWS']["MONTH"][$arItem["ID"]])?></td>
                    <td><?= intVal($arResult["STATISTICS"]['QUANTITY_BOOK']["MONTH"][$arItem["ID"]])?></td>
                </tr>
                <?endforeach;?>
            </tbody>
        </table>
    </div>
</div>