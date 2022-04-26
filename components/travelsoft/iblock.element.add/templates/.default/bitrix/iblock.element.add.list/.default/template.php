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
$this->setFrameMode(false);

$colspan = 2;
if ($arResult["CAN_EDIT"] == "Y")
    $colspan++;
if ($arResult["CAN_DELETE"] == "Y")
    $colspan++;
?>
<? if (strlen($arResult["MESSAGE"]) > 0): ?>
    <div class="text-left">
        <? ShowNote($arResult["MESSAGE"]) ?>
    </div>
<? endif ?>

<!-- Basic table -->
<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title"><?= $arParams["LIST_NAME_TITLE"] ?></h5>
        <? if (count($arResult["ELEMENTS"]) > 0): ?>
            <br>
            <div id="search-by-name">
                <select id="search_by_name" class="select2-container select fx-min-width-300px">
                    <option value="">Выберите из списка</option>
                    <?
                    $dbList = CIBlockElement::GetList(
                                    false, array('PROPERTY_USER' => $GLOBALS['USER']->GetID(), 'PROPERTY_USER_ID' => $GLOBALS['USER']->GetID(), 'IBLOCK_ID' => $arParams['IBLOCK_ID']), false, false, array('ID', 'NAME')
                    );

                    while ($arItem = $dbList->Fetch()) {
                        ?>
                        <option value="<?= $arItem['ID'] ?>"><?= $arItem['NAME'] ?></option>
                    <? } ?></select>
                <a href="javascript:void(0)" id="send_search_by_name" class="btn btn-primary">Редактировать</a>
            </div>
            <form action>
            </form>
        <? endif ?>
    </div>

    <div class="table-responsive">
        <table class="table">
            <? if ($arResult["NO_USER"] == "N"): ?>
                <thead>
                    <tr>
                        <th class="no-borders no-paddings"></th>
                        <th class="no-borders no-paddings"></th>
                        <th class="no-borders"><? if ($arParams["MAX_USER_ENTRIES"] > 0 && $arResult["ELEMENTS_COUNT"] < $arParams["MAX_USER_ENTRIES"]): ?><button class="btn btn-primary" onclick="document.location.href = '<?= $arParams["EDIT_URL"] ?>?edit=Y'"><?= GetMessage("IBLOCK_ADD_LINK_TITLE") ?></button><? else: ?><?= GetMessage("IBLOCK_LIST_CANT_ADD_MORE") ?><? endif ?></th>
                    </tr>

                    <tr>
                        <th>ID</th>

                        <th><?= $arParams["NAME_TITLE"] ?></th>

                        <th>Действия</th>
                    </tr>

                </thead>
                <tbody>
                    <? if (count($arResult["ELEMENTS"]) > 0): ?>
                        <? foreach ($arResult["ELEMENTS"] as $arElement): ?>
                            <tr>
                                <td><?= $arElement['ID'] ?></td>
                                <td><b><?= $arElement['NAME'] ?></b> <small>(<?= is_array($arResult["WF_STATUS"]) ? $arResult["WF_STATUS"][$arElement["WF_STATUS_ID"]] : $arResult["ACTIVE_STATUS"][$arElement["ACTIVE"]] ?>)</small></td>
                                <td>
                                    <ul class="icons-list">
                                        <? if ($arResult["CAN_EDIT"] == "Y"): ?>
                                            <li class="text-primary-600"><a href="<?= $arParams["EDIT_URL"] ?>?edit=Y&amp;CODE=<?= $arElement["ID"] ?>"><i class="icon-pencil7"></i></a></li>
                                        <? endif ?>
                                        <? if ($arResult["CAN_DELETE"] == "Y"): ?>
                                            <li class="text-danger-600"><a href="?delete=Y&amp;CODE=<?= $arElement["ID"] ?>&amp;<?= bitrix_sessid_get() ?>" onClick="return confirm('<? echo CUtil::JSEscape(str_replace("#ELEMENT_NAME#", $arElement["NAME"], GetMessage("IBLOCK_ADD_LIST_DELETE_CONFIRM"))) ?>')"><i class="icon-trash"></i></a></li>
                                                <? endif ?>
                                                <?= $arResult["add_price"][$arElement["ID"]] ?>
                                    </ul>
                                </td>
                            </tr>
                        <? endforeach ?>
                    <? endif ?>
                </tbody>
            <? endif ?>
<!--            <tfoot>

</tfoot>-->
        </table>
    </div>
</div>
<!-- /basic table -->
<? if (strlen($arResult["NAV_STRING"]) > 0): ?><?= $arResult["NAV_STRING"] ?><? endif ?>

<?
if (count($arResult["ELEMENTS"]) > 0):
    //' . 
//Bitrix\Main\Page\Asset::getInstance()->addCss($templateFolder . '/jquery-ui.min.css', true);
//Bitrix\Main\Page\Asset::getInstance()->addJs($templateFolder . '/jquery-ui.min.js', true);
    ?>

    <script>

        $(document).ready(function () {

            $('#search_by_name').on('change', function () {
                if ($(this).val()) {
                    $('#send_search_by_name').attr('href', '<?= $arParams["EDIT_URL"] ?>?edit=Y&CODE=' + $(this).val());
                }
            });

        });

    </script>

<? endif; ?>
