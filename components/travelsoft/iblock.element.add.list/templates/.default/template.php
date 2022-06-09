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
$curPage = $APPLICATION->GetCurPage(false);

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
            <br>
				<? $dbList = CIBlockElement::GetList(false, array('PROPERTY_USER' => $GLOBALS['USER']->GetID(), 
													'PROPERTY_USER_ID' => $GLOBALS['USER']->GetID(),
													'IBLOCK_ID' => $arParams['IBLOCK_ID']), false, false, 
													array('ID', 'NAME')
                    );
                    $listTours = [];
                    while ($arItem = $dbList->Fetch()) {
                        $listTours[] = ['id' => $arItem['ID'], 'name' => $arItem['NAME']];
					}
                ?>
            <?if ((defined("IS_EXCURSION_TOUR") && IS_EXCURSION_TOUR === "Y") || (defined('IS_SANATORIUM') && IS_SANATORIUM === 'Y')):?>
            <form action="<?= $APPLICATION->GetCurPageParam("", array("sort_by_modify_date", "filter", "CANCEL"), false)?>" method="GET">
                <div>
					<div class="form-group" style="margin-bottom: 15px;">
						<label for="filter_by_name" style="margin-right:9px">Поиск по названию: </label>
                        <input class="form-control fx-min-width-300px"
                               style="display: inline-block"
                               id="filter_by_name"
                               type="text"
                               name="filter_by_name"
                               value="<?= htmlspecialchars($_REQUEST['filter_by_name']) ?>">
                    </div>
                    <?if(defined("IS_EXCURSION_TOUR") && IS_EXCURSION_TOUR === "Y"):?>
                        <div class="form-group">
							Поиск по активности: 
                            <select name="filter" class="select2-container select fx-min-width-300px">
                                <option value="show_all">Показывать все</option>
                                <option <?if($_REQUEST["filter"] === "active") echo "selected=''";?> value="active">Показывать только активные</option>
                                <option <?if($_REQUEST["filter"] === "no_active") echo "selected=''";?> value="no_active">Показывать только неактивные</option>

                            </select>
                            <div class="checkbox">
                                <label><input <?if($_REQUEST["sort"] === "sort_by_modify_date") echo "checked=''";?>  name="sort_by_modify_date" value="Y" type="checkbox"> Сортировать по дате изменения</label>
                            </div>
                        </div>
                    <?endif?>
                </div>

                <button type="submit" class="btn btn-primary">Применить</button>
                <button name="CANCEL" value="Y" type="submit" class="btn btn-primary">Отменить</button>
            </form>
            <?endif?>
            <?if(defined('IS_SANATORIUM') && IS_SANATORIUM === 'Y'):?>
                <? $sortAllow = ['ID'];?>
                <div class="mt-20"><?=GetMessage("SORT");?></div>
                <form id="order-sort-form" class="mt-5" action="<?= $curPage ?>" method="get">
                    <div class="row">
                        <div class="col-lg-3 col-md-3">
                            <div class="form-group">
                                <select name="order_sort[sort]" class="form-control">
                                    <? for ($i = 0; $i < count($sortAllow); $i++):
                                        $sort = $sortAllow[$i]; ?>
                                        <option <? if (isset($_REQUEST["order_sort"]["sort"]) && $sort == $_REQUEST["order_sort"]["sort"]) {
                                            echo "selected";
                                        } elseif (!isset($_REQUEST["order_sort"]["sort"]) && $sort == "ID") {
                                            echo "selected";
                                        } ?> value="<?= $sort ?>"><?= GetMessage("SORT_{$sort}") ?></option>
                                    <? endfor ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <div class="form-group">
                                <select name="order_sort[order]" class="form-control">
                                    <?
                                    $orders = ["ASC", "DESC"];
                                    for ($i = 0, $cnt = count($orders); $i < $cnt; $i++):
                                        $order = $orders[$i]; ?>
                                        <option <? if (isset($_REQUEST["order_sort"]["order"]) && $order == $_REQUEST["order_sort"]["order"]) {
                                            echo "selected";
                                        } elseif (!isset($_REQUEST["order_sort"]["order"]) && $order == "ASC") {
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
            <?endif;?>
    </div>

    <?if (key_exists("activation_request_ok", $_REQUEST)):?>
    <div class="text-green">Запрос на активацию успешно отправлен</div>
    <?endif?>
    <?if (key_exists("deleting_request_ok", $_REQUEST)):?>
    <div class="text-green">Запрос на удаление успешно отправлен</div>
    <?endif?>
	<div class="table-responsive" style="margin-top:0px !important">
        <table class="table" id="excursions-tours">
            <? if ($arResult["NO_USER"] == "N"): ?>
                <thead>
                    <tr>
                        <th class="no-borders no-paddings"></th>
                        <th class="no-borders no-paddings"></th>
                        <th class="no-borders"><? if ($arParams["MAX_USER_ENTRIES"] > 0 && $arResult["ELEMENTS_COUNT"] < $arParams["MAX_USER_ENTRIES"]): ?><button style="background-color:#feb818;border-color:#feb818;" class="btn btn-primary" onclick="document.location.href = '<?= $arParams["EDIT_URL"] ?>?edit=Y'"><?= GetMessage("IBLOCK_ADD_LINK_TITLE") ?></button><? else: ?><?= GetMessage("IBLOCK_LIST_CANT_ADD_MORE") ?><? endif ?></th>
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
                            <tr data-id="<?= $arElement['ID'] ?>">
                                <td><?= $arElement['ID'] ?></td>
                                <td>
                                    <b><?= $arElement['NAME'] ?></b>
                                    <small <? if ($arElement["ACTIVE"] === "N"): ?>style="color: red"<? endif ?>>
                                        (<?= is_array($arResult["WF_STATUS"]) ? $arResult["WF_STATUS"][$arElement["WF_STATUS_ID"]] : $arResult["ACTIVE_STATUS"][$arElement["ACTIVE"]] ?>)
                                    </small>
                                    <? if ($arElement["ACTIVE"] === "N" && !in_array($arElement["ID"], $_SESSION["ACTIVATION_REQUEST_ALREADY_DONE"])): ?>
                                        <a onclick="return confirm('Данным действием вы подтверждаете, что услуга готова к активации - заполнено описание, номерной фонд, тарифы и цены.')"
                                        
                                           href="<?= $APPLICATION->GetCurPageParam("sessid=" . bitrix_sessid() . "&element_id=" . $arElement["ID"] . "&activation_request", array("sessid", "element_id", "activation_request", "activation_request_ok")) ?>"
                                           class="activation-request-btn btn-xs btn-primary" style="float:right">
                                            Запросить активацию
                                        </a>
                                    <? endif; ?>
                                    <? if ($arElement["ACTIVE"] === "Y"):?>
                                        <a onclick="return confirm('Уверены, что хотите послать запрос на удаление ?')" rel="nofollow"
                                           href="<?= $APPLICATION->GetCurPageParam("sessid=" . bitrix_sessid() . "&element_id=" . $arElement["ID"] . "&deleting_request", array("sessid", "element_id", "deleting_request", "deleting_request_ok")) ?>"
                                           class="activation-request-btn btn-xs btn-danger" style="float:right">
                                            Запросить удаление
                                        </a>
                                    <? endif; ?>
                                </td>
                                <td>
                                    <ul class="icons-list">
                                        <? if ($arResult["CAN_EDIT"] == "Y"): ?>
                                            <li class="text-primary-600"><a title="Редактировать" href="<?= $arParams["EDIT_URL"] ?>?edit=Y&amp;CODE=<?= $arElement["ID"] ?>"><i class="icon-pencil7"></i></a></li>
                                        <? endif ?>
                                        <? if ($arResult["CAN_DELETE"] == "Y" && $arElement["ACTIVE"] === "N"): ?>
                                            <li class="text-danger-600"><a title="Удалить" href="?delete=Y&amp;CODE=<?= $arElement["ID"] ?>&amp;<?= bitrix_sessid_get() ?>" onClick="return confirm('<? echo CUtil::JSEscape(str_replace("#ELEMENT_NAME#", $arElement["NAME"], GetMessage("IBLOCK_ADD_LIST_DELETE_CONFIRM"))) ?>')"><i class="icon-trash"></i></a></li>
                                        <? endif ?>
                                        <?= $arResult["add_price"][$arElement["ID"]] ?>

                                        <li class="text-danger-600"><a title="Копировать" href="<?= $arParams["EDIT_URL"] ?>?edit=Y&amp;copy=<?= $arElement["ID"] ?>"><i class="icon-copy"></i></a></li>
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

    <script>
        $(document).ready(function () {
            let listTours = <?= json_encode($listTours, JSON_PRETTY_PRINT)?>;

            let $excursionsTours = $('#excursions-tours').find('tbody');

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

<? endif; ?>
