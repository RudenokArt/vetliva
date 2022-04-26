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
            <? if (!$arResult["DB"]['ITEMS']): ?>
                <small class="display-block"><?= GetMessage('NO_ELEMENTS_RATE_TITLE') ?></small>
            <? endif ?>
        <? else: ?>
            <h5 class="panel-title"><?= GetMessage("ADD_LIST_TITLE") ?></h5>
            <? if (!$arResult["DB"]['ITEMS']): ?>
                <small class="display-block"><?= GetMessage('NO_ELEMENTS_TITLE') ?></small>
            <? endif ?>
        <? endif ?>
            <? if ($arResult['ERRORS']): ?>
            <div class="errors-container">
            <? ShowError($arResult['ERRORS']) ?>
            </div>
<? endif ?>
    </div>
    
    <?if ($arResult["FILTER"] && ($arResult["DB"]["ITEMS"] || $arResult["FILTER"]["IS_SET_FILTER"])):?>
    
    <form class="mr-lr-20" name="serviceSelectForm" action="<?= POST_FORM_ACTION_URI?>" method="get">
            <?if ($arResult["FILTER"]["OBJECTS"]):?>
            <div class="form-group">
                <label><b>Выберите объект:</b></label>
                <select data-placeholder="..." onchange="document.serviceSelectForm.submit();" id="selectService" class="select fx-min-width-300px" name="OBJECTS[]">
                    <option></option>
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
                    <option></option>
                    <?foreach ($arResult['FILTER']["SERVICES"] as $element_id => $arService) :?>
                            <optgroup label="<?= $arResult['IBLOCK_ELEMENTS'][$element_id]?>">
                            <?foreach ($arService as $service_id => $name):?>
                                    <option <?if (in_array($service_id, $arResult["FILTER"]["_GET"]["SERVICES"])):?>selected<?endif?> value="<?= $service_id?>"><?= $name?></option>
                            <?endforeach?>
                            </optgroup>
                    <?endforeach?>
                </select>
            </div>
            <?endif?>
        </form>
    
    <?endif?>

    <div class="table-responsive">
        <div class="text-right"></div>
        <table class="table">

            <thead>
                <tr>
                    <th class="no-borders no-paddings"></th>
                    <th class="no-borders no-paddings"></th>
                    <th class="no-borders no-paddings"><button class="m-20 btn btn-primary" onclick="document.location.href = '<?= $arParams["EDIT_URL"] ?>'"><?= GetMessage("ADD_LINK_TITLE") ?></button></th>
                </tr>
                <tr>
                    <th>ID</th>
                    <th><?= GetMessage("NAME_TITLE") ?></th>
                    <th><?= GetMessage("ACTIONS_TITLE") ?></th>
                </tr>

            </thead>
            <tbody>
<? foreach ($arResult["DB"]["ITEMS"] as $arElement): ?>
                    <tr>
                        <td><?= $arElement['ID'] ?></td>
                        <td><b><?= $arElement['UF_NAME'] ?></b></td>
                        <td>
                            <ul class="icons-list">
                                <? if ($arElement["CAN_EDIT"]): ?>
                                    <li class="text-primary-600"><a title="<?= GetMessage("ACTION_EDIT") ?>" href="<?= $arParams["EDIT_URL"] ?>&amp;row_id=<?= $arElement["ID"] ?>"><i class="icon-pencil7"></i></a></li>
                                <? endif ?>
                                <? if ($arElement["CAN_DELETE"]): ?>
                                    <li class="text-danger-600"><a title="<?= GetMessage("ACTION_DELETE") ?>" href="?delete=Y&amp;row_id=<?= $arElement["ID"] ?>&amp;<?= bitrix_sessid_get() ?>" onClick="return confirm('<? echo CUtil::JSEscape(str_replace("#ELEMENT_NAME#", $arElement["UF_NAME"], GetMessage("ADD_LIST_DELETE_CONFIRM"))) ?>')"><i class="icon-trash"></i></a></li>
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
    </div>


        <? if (strlen($arResult["DB"]["NAV_STRING"]) > 0): ?><div class="mr-lr-20"><?= $arResult["DB"]["NAV_STRING"] ?></div><?endif?>

</div>
<!-- /basic table -->
