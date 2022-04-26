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

?>
    <? if (strlen($arResult["MESSOK"]) > 0): ?>
    <div class="text-left">
    <? ShowNote($arResult["MESSOK"]) ?>
    </div>
<? endif; ?>

<!-- Basic table -->
<div class="panel panel-flat">
    <div class="panel-heading">

        <? if ($arParams['ADD_ROW_COMPONENT_TEMPLATE'] == "add-rate"): ?>
            <h5 class="panel-title"><?= GetMessage("ADD_LIST_RATE_TITLE") ?></h5>
        <? else: ?>
            <h5 class="panel-title"><?= GetMessage("ADD_LIST_TITLE") ?></h5>
        <? endif ?>
            <? if ($arResult['ERRORS']): ?>
            <div class="errors-container">
            <? ShowError($arResult['ERRORS']) ?>
            </div>
        <? endif ?>
    </div>

    <form class="mr-lr-20" name="serviceSelectForm" action="<?= POST_FORM_ACTION_URI?>" method="get">
        <?if(!empty($arParams['FILTER_SERVICE_ID'])):?>
            <input type="hidden" name="provider_id" value="<?=$arParams['FILTER_SERVICE_ID']?>">
        <?endif;?>
        <?if ($arResult["FILTER"]["OBJECTS"]):?>
            <div class="form-group">
                <label><b>Выберите объект:</b></label>
                <select data-placeholder="..." onchange="document.serviceSelectForm.submit();" id="selectService" class="select fx-min-width-300px" name="OBJECTS[]">
                    <option>-</option>
                    <?foreach ($arResult['FILTER']["OBJECTS"] as $element_id => $name) :?>

                            <option <?if (in_array($element_id, $arResult["FILTER"]["_GET"]["OBJECTS"])):?>selected<?endif?> value="<?= $element_id?>"><?= $name?></option>

                    <?endforeach?>
                </select>
            </div>
        <?endif?>
        <?if ($arResult["FILTER"]["SERVICES"]):?>
            <div class="form-group">
                <label><b>Выберите услугу:</b></label>
                <select data-placeholder="..." onchange="document.serviceSelectForm.submit();" id="selectService" class="select fx-min-width-300px" name="SERVICES[]">
                    <option>-</option>
                    <?foreach ($arResult['FILTER']["SERVICES"] as $element_id => $arService) :

                        $arEl = CIBlockElement::GetList(
                                false,
                                array('ID' => $element_id),
                                false,
                                false,
                                array('IBLOCK_ID'))->Fetch();

                        $isExcursion = \travelsoft\booking\Utils::getOpt('excursions') == $arEl['IBLOCK_ID'];

                        ?>
                    <?if (!$isExcursion):?>
                            <optgroup label="<?= $arResult['IBLOCK_ELEMENTS'][$element_id]?>">
                                <?endif?>
                            <?foreach ($arService as $service_id => $name):?>
                                    <option <?if (in_array($service_id, $arResult["FILTER"]["_GET"]["SERVICES"])):?>selected<?endif?> value="<?= $service_id?>"><?= $name?></option>
                            <?endforeach?>
                                    <?if (!$isExcursion):?>
                            </optgroup>
                            <?endif?>
                    <?endforeach?>
                </select>
            </div>
        <?endif?>
    </form>

    <?if((defined('IS_SANATORIUM') && IS_SANATORIUM === 'Y') || (defined("IS_RATE") && IS_RATE === true)):?>
        <div class="panel-heading">
            <div class="form-group">
                <label for="filter_by_name">Фильтр по имени:</label>
                <input class="form-control fx-min-width-300px"
                       style="display: inline-block"
                       id="filter_by_name"
                       type="text"
                       name="filter_by_name"
                       value="<?= htmlspecialchars($_REQUEST['filter_by_name']) ?>">
            </div>
            <?
            $listTours = array_values(array_map(function($item){
               return [
                   'id' => $item['ID'],
                   'name' => $item['UF_NAME'],
               ];
            }, $arResult["DB"]["ITEMS"]));
            ?>
            <script>
                $(document).ready(function () {
                    let listTours = <?= json_encode($listTours, JSON_PRETTY_PRINT)?>;

                    let $excursionsTours = $('#nomernoy-fond').find('tbody');

                    let $filterByName = $('#filter_by_name');
                    $filterByName.on('input', function () {
                        let ids = listTours.filter(item => item.name.toLowerCase().includes(this.value.toLowerCase())).map(item => item.id);

                        $excursionsTours.find('tr').hide();

                        ids.forEach(id => {
                            $excursionsTours.find(`tr[data-id="${id}"]`).show();
                        });
                    });

                    $filterByName.trigger('input');
                });
            </script>

            <? $sortAllow = ['ID'];?>
            <div class="mt-20"><?=GetMessage("SORT");?></div>
            <form id="order-sort-form" class="mt-5" action="<?= $curPage ?>" method="get">
                <div class="row">
                    <div class="col-lg-3 col-md-3">
                        <div class="form-group">
                            <select name="sort_id" class="form-control">
                                <? for ($i = 0; $i < count($sortAllow); $i++):
                                    $sort = $sortAllow[$i]; ?>
                                    <option <? if (isset($_REQUEST["sort_id"]) && $sort == $_REQUEST["sort_id"]) {
                                        echo "selected";
                                    } elseif (!isset($_REQUEST["sort_id"]) && $sort == "ID") {
                                        echo "selected";
                                    } ?> value="<?= $sort ?>"><?= GetMessage("SORT_{$sort}") ?></option>
                                <? endfor ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <div class="form-group">
                            <select name="sort_type" class="form-control">
                                <?
                                $orders = ["ASC", "DESC"];
                                for ($i = 0, $cnt = count($orders); $i < $cnt; $i++):
                                    $order = $orders[$i]; ?>
                                    <option <? if (isset($_REQUEST["sort_type"]) && $order == $_REQUEST["sort_type"]) {
                                        echo "selected";
                                    } elseif (!isset($_REQUEST["sort_type"]) && $order == "DESC") {
                                        echo "selected";
                                    } ?> value="<?= $order ?>">
                                        <?= GetMessage("ORDER_{$order}") ?>
                                    </option>
                                <? endfor ?>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
            <script>
                $("#order-sort-form select").on("change", function () {
                    $("#order-sort-form").submit();
                });
            </script>
        </div>
    <?endif;?>

    <div class="table-responsive">
        <div class="text-right"></div>
        <form id="mass_edit" method="POST" action="<?= POST_FORM_ACTION_URI?>">
            <?= bitrix_sessid_post()?>
        <table class="table" id="nomernoy-fond">

            <thead>
                <tr>
                    <th class="no-borders no-paddings">
                        <a class="m-20 btn btn-danger"
                           href="<?=$APPLICATION->GetCurPage().(!empty($arParams['FILTER_SERVICE_ID']) ? '?provider_id='.$arParams['FILTER_SERVICE_ID'] : '')?>">
                            <?= GetMessage("RESET_FILTER") ?>
                        </a>
                    </th>
                    <th class="no-borders no-paddings"></th>
                    <th class="no-borders no-paddings"></th>
                    <th class="no-borders no-paddings"><button class="m-20 btn btn-primary" type="button" onclick="document.location.href = '<?= $arParams["EDIT_URL"] ?>'"><?= GetMessage("ADD_LINK_TITLE") ?></button></th>
                </tr>
                <tr>
                    <th style="width: 1px"><button class="btn btn-primary" type="submit"><i class="icon-trash"></i></button></th>
                    <th>ID</th>
                    <th><?= GetMessage("NAME_TITLE") ?></th>
                    <th><?= GetMessage("ACTIONS_TITLE") ?></th>
                </tr>

            </thead>
            <tbody>
<? foreach ($arResult["DB"]["ITEMS"] as $arElement): ?>
                    <tr data-id="<?= $arElement['ID'] ?>">
                        <td style="text-align: center"><input name="rows_for_delete[]" type="checkbox" value="<?= $arElement['ID'] ?>"></td>
                        <td><?= $arElement['ID'] ?></td>
                        <td><b><?= $arElement['UF_NAME'] ?></b></td>
                        <td>
                            <ul class="icons-list">
                                <? if ($arElement["CAN_EDIT"]): ?>
                                    <li class="text-primary-600"><a title="<?= GetMessage("ACTION_EDIT") ?>" href="<?= $arParams["EDIT_URL"] ?>&amp;row_id=<?= $arElement["ID"] ?>"><i class="icon-pencil7"></i></a></li>
                                <? endif ?>
                                <? if ($arElement["CAN_DELETE"]): ?>
                                    <li class="text-danger-600"><a title="<?= GetMessage("ACTION_DELETE") ?>" href="<?= $APPLICATION->GetCurPageParam("row_id=".$arElement["ID"]."&delete=Y&" . bitrix_sessid_get(), array("delete", "row_id", "sessid"), false)?>" onClick="return confirm('<? echo CUtil::JSEscape(str_replace("#ELEMENT_NAME#", $arElement["UF_NAME"], GetMessage("ADD_LIST_DELETE_CONFIRM"))) ?>')"><i class="icon-trash"></i></a></li>
                                <? endif ?>
                                    <? if ($arElement["CAN_COPY"]): ?>
                                    <li class="text-primary-600"><a title="<?= GetMessage("ACTION_COPY") ?>" href="<?= $arParams["EDIT_URL"] ?>&amp;copy=<?= $arElement["ID"] ?>"><i class="icon-copy3"></i></a></li>
                                <? endif ?>
                                        <? if ($arParams['ADD_PRICES_LINK']): ?>
                                    <li><a title="<?= GetMessage("ACTION_ADD_PRICES") ?>" href="<?= $arParams['ADD_PRICES_LINK'] ?>/?row_id=<?= $arElement["ID"] ?>"><i class="icon-coins"></i></a></li>
    <? endif ?>
                            </ul>
                        </td>
                    </tr>
<? endforeach ?>

            </tbody>
        </table>
        </form>
        <script>
        $(document).ready(function () {
            $("#mass_edit").on("submit", function (e) {
                console.log(1);
                if (!confirm("Действительно хотите удалить выделенное ?")) {
                    e.preventDefault();
                    return false;
                } else {
                    return true;
                }
            });
        });
        </script>
    </div>


        <? if (strlen($arResult["DB"]["NAV_STRING"]) > 0): ?><div class="mr-lr-20"><?= $arResult["DB"]["NAV_STRING"] ?></div><?endif?>

</div>
<!-- /basic table -->
