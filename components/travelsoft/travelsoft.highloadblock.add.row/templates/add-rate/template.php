<?php
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
if (empty($arResult['ROW_DATA']['fields']))
    return;

$this->addExternalCss('/local/templates/partners/css/jquery.webui-popover.min.css');
$this->addExternalJs('/local/templates/partners/js/webui-popover/jquery.webui-popover.min.js');

$user_group_array = $GLOBALS["USER"]->GetUserGroupArray();
$isExcursion = in_array(travelsoft\booking\Utils::getOpt("excursions_provider_group"), $user_group_array);
$isSanatorium = in_array(travelsoft\booking\Utils::getOpt("sanatorium_provider_group"), $user_group_array);
if (!empty($arParams['ADDITIOANL_FILTR_TYPE']) && in_array(4, $arParams['ADDITIOANL_FILTR_TYPE']))  $isExcursion = false;
if (!empty($arParams['ADDITIOANL_FILTR_TYPE']) && in_array(8, $arParams['ADDITIOANL_FILTR_TYPE']))  $isSanatorium = false;
$travelline_bx_group_id = 17;
$isBlockedBlockEdit = $arParams['ROW_ID'] > 0 && in_array($travelline_bx_group_id, $user_group_array);

if ($arResult['MESSOK']) {
    ?>
    <div class="panel panel-flat">
        <div class="panel-body">
            <div class="text-left">
                <?= ShowMessage(array("MESSAGE" => $arResult['MESSOK'], "TYPE" => "OK")) ?>
            </div>
        </div>
    </div>
    <?
}
if ($arResult['ERRORS']) {
    ?>
    <div class="panel panel-flat">
        <div class="panel-body">
            <div class="text-left">
                <?= ShowError(implode("<br>", $arResult['ERRORS'])) ?>
            </div>
        </div>
    </div>
    <?
}

$HTML_ID = "UF_NAME_" . $arParams['BLOCK_ID'];
?>
<form id="block_row_add" name="block_row_add" action="<?= POST_FORM_ACTION_URI ?>" method="post" enctype="multipart/form-data">
    <?= bitrix_sessid_post() ?>
    <input type="hidden" name="form_id" value="<?= $arResult['FORM_ID'] ?>">
    <div class="panel panel-flat">
        <div class="panel-body">
            <?
            if (!empty($arResult['PRICE_TYPES_HL_ELEMENTS']['rows'])) {
                $arResult['ROW_DATA']['fields']["PRICE_TYPES"] = $arResult['PRICE_TYPES_HL_ELEMENTS']['rows'];
                $arResult['FIELDS_FOR_SHOW'][] = "PRICE_TYPES";
            }

            $default_multiple_size = 5;
            foreach ($arResult['ROW_DATA']['fields'] as $field_name => &$arField) {

                if (
                        $field_name == 'UF_FOR_PLACE' ||
                        $field_name == 'UF_ADULTS' ||
                        $field_name == 'UF_CHILDREN' ||
                        $field_name == 'UF_PEOPLE' ||
                        $field_name == 'UF_MIN_PEOPLE' ||
                        $field_name == 'UF_MAIN_PLACES' ||
                        $field_name == 'UF_ADD_PLACES'
                ) {
                    continue;
                }

                if (!in_array($field_name, $arResult['FIELDS_FOR_SHOW']))
                    continue;

                // привязка к элементам питания (select'ы)
                if ($field_name == "UF_FOOD_ID") {
                    unset($arField['USER_TYPE_ID']);
                    $result = $arResult["FOOD_HL_ELEMENTS"]['rows'];
                    $placeholder = "data-placeholder='" . GetMessage('CHOOSE') . "'";
                    $settedVals = (array) $arField['VALUE'];
                    ?>

                    <div class="form-group">
                        <label><b><?= $arField['EDIT_FORM_LABEL'] ?><? if ($arField['MANDATORY'] == "Y"): ?><span class="starrequired">*</span><? endif ?></b></label>
                        <select <?= $placeholder ?> name="<?= $field_name ?><? if ($arField['MULTIPLE'] == "Y") echo "[]" ?>" <? if ($arField['MULTIPLE'] == "Y"): ?>multiple<? endif ?> class="select">
                            <? foreach ($result as $key => $arItem) : ?>
                                <option <? if (in_array($arItem['ID'], $settedVals)): ?>selected<? endif ?> value="<?= $arItem['ID'] ?>"><?= $arItem["UF_NAME_RU"] ?></option>
                            <? endforeach ?>
                        </select>
                    </div>

                    <?
                }
                
                // тип тарифа
                if ($field_name == "UF_TYPE_TARIF" && $isSanatorium) {
                    unset($arField['USER_TYPE_ID']);
                    $result = [];
                    $datatmp = \Bitrix\Iblock\ElementTable::getList([
                        'filter' => ['IBLOCK_ID' => $arField['SETTINGS']['IBLOCK_ID'], 'ACTIVE'=>'Y'],
                        'select' => ['ID', 'NAME']
                    ])->fetchAll(); 
                    foreach ($datatmp as $iblock_element) $result[$iblock_element['ID']] = $iblock_element['NAME'];
                    $placeholder = "data-placeholder='" . GetMessage('CHOOSE') . "'";
                    $settedVals = (array) $arField['VALUE'];
                    ?>
                    <div class="form-group">
                        <label><b><?= $arField['EDIT_FORM_LABEL'] ?></b></label>
                        <select <?= $placeholder ?> name="<?= $field_name ?>" class="select">
                            <? foreach ($result as $key => $arItem) : ?>
                                <option <? if (in_array($key, $settedVals)): ?>selected<? endif ?> value="<?= $key ?>"><?= $arItem?></option>
                            <? endforeach ?>
                        </select>
                    </div>
                    <?
                }

                // ТИПЫ ЦЕН
                if ($field_name == "PRICE_TYPES") {
                    ?>

                    <? if (!$isExcursion): ?>
                        <div class="form-group">
                            <?if ($isBlockedBlockEdit):?>
                            <div class="blocked-div"><div class="blocked-text">Недоступно для редактирования данной группе пользователей</div></div>
                            <?endif?>
                            <label><input class="for-place-checkbox" <? if ($arResult['ROW_DATA']['fields']["UF_FOR_PLACE"]["VALUE"]): ?>checked=""<? endif ?> type="radio" name="UF_FOR_PLACE" value="1"> <b>Расчет стоимости за место<span class="starrequired">*</span></b></label>
                            <br>
                            <label><input class="for-place-checkbox" <? if (!$arResult['ROW_DATA']['fields']["UF_FOR_PLACE"]["VALUE"] && $arParams['ROW_ID'] > 0): ?>checked=""<? endif ?> type="radio" name="UF_FOR_PLACE" value="0"> <b>Расчет стоимости за номер<span class="starrequired">*</span></b> <span data-content="Внимание! Если вы используете Channel Manager компании TravelLine, Вам необходимо выбрать только расчет стоимости за номер. Во всех других случаях могут быть использованы оба варианта - за место и за номер" class="icon-question3 popuwer"></span>  </label>     
                            
                        </div>
                    <? else: ?>
                        <div class="form-group">
                            <?if ($isBlockedBlockEdit):?>
                            <div class="blocked-div"><div class="blocked-text">Недоступно для редактирования данной группе пользователей</div></div>
                            <?endif?>
                            <label><input class="for-place-checkbox" <? if ($arResult['ROW_DATA']['fields']["UF_FOR_PLACE"]["VALUE"]): ?>checked=""<? endif ?> type="radio" name="UF_FOR_PLACE" value="1"> <b>Расчет стоимости для однодневных туров<span class="starrequired">*</span></b></label>
                            <br>
                            <label><input class="for-place-checkbox" <? if (!$arResult['ROW_DATA']['fields']["UF_FOR_PLACE"]["VALUE"] && $arParams['ROW_ID'] > 0): ?>checked=""<? endif ?> type="radio" name="UF_FOR_PLACE" value="0"> <b>Расчет стоимости для многодневных туров<span class="starrequired">*</span></b></label>       
                            
                        </div>
                        <div id="people-cnt-area">
                            <div class="form-group">
                                <label><b>Максимальное количество человек<span class="starrequired">*</span></b></label>
                                <input class="form-control" type="text" name="UF_PEOPLE" value="<?= $arResult['ROW_DATA']['fields']['UF_PEOPLE']['VALUE'] ?>">
                            </div>
                            <div class="form-group">
                                <label><b>Минимальное количество человек<span class="starrequired">*</span></b></label>
                                <input class="form-control" type="text" name="UF_MIN_PEOPLE" value="<?= $arResult['ROW_DATA']['fields']['UF_MIN_PEOPLE']['VALUE'] ?>">
                            </div>
                            <div class="form-group">
                                <label><b>Максимальное количество взрослых<span class="starrequired">*</span></b></label>
                                <input class="form-control" type="text" name="UF_ADULTS" value="<?= $arResult['ROW_DATA']['fields']['UF_ADULTS']['VALUE'] ?>">
                            </div>
                            <div class="form-group">
                                <label><b>Максимальное количество детей<span class="starrequired">*</span></b></label>
                                <input class="form-control" type="text" name="UF_CHILDREN" value="<?= $arResult['ROW_DATA']['fields']['UF_CHILDREN']['VALUE'] ?>">
                            </div>
                            <div class="form-group">
                                <label><b>Количество основных мест<span class="starrequired">*</span></b></label>
                                <input class="form-control" type="text" name="UF_MAIN_PLACES" value="<?= $arResult['ROW_DATA']['fields']['UF_MAIN_PLACES']['VALUE'] ?>">
                            </div>
                            <div class="form-group">
                                <label><b>Количество дополнительных мест<span class="starrequired">*</span></b></label>
                                <input class="form-control" type="text" name="UF_ADD_PLACES" value="<?= $arResult['ROW_DATA']['fields']['UF_ADD_PLACES']['VALUE'] ?>">
                            </div>
                        </div>
                    <? endif ?>

                    <div class="form-group" id="price-types-area">
                        <?if ($isBlockedBlockEdit):?>
                        <div class="blocked-div"><div class="blocked-text">Недоступно для редактирования данной группе пользователей</div></div>
                        <?endif?>
                        <!--<label class="price-types-titles-area" id="price-types-title"><b><?= GetMessage('PRICE_TYPE_TITLE') ?><span class="starrequired">*</span></b></label><br>-->
                        <?
                        foreach ($arResult['PRICE_TYPES_HL_ELEMENTS']['rows'] as $key => $arPTField) :
                            $checked = "checked";
                            if (!in_array($arPTField['ID'], $arResult['pt'])) {
                                $checked = "";
                            }

                            if ($arPTField['ID'] == 4 && $checked === '') {
                                continue;
                            }

                            $isChild = $arPTField['UF_AGE_MIN'] != "" && $arPTField['UF_AGE_MAX'] != "";

                            if ($isExcursion):

                                if ($arPTField['ID'] == 4 || $arPTField['UF_FOR_ODEXC'] == 1 || $arPTField['UF_FOR_MDEXC'] == 1) :

                                    ob_start();
                                    ?>
                                    <div class="checkbox <? if ($isChild): ?> is-child  <? if (strlen($checked) <= 0) {
                            echo "hidden";
                        } ?> <? endif ?>">
                                        <input  onclick="onPtClick(this)" <? if ($arPTField['UF_FOR_ODEXC'] || $arPTField['ID'] == 4): ?>data-for-odexc="Y"<? endif ?> <? if ($arPTField['UF_FOR_MDEXC'] || $arPTField['ID'] == 4): ?>data-for-mdexc="Y"<? endif ?> data-index="<?= $key ?>" class="price-type-checkbox" <?= $checked ?> type="checkbox" name="<?= $field_name ?>[<?= $key ?>]" value="<?= $arPTField['ID'] ?>"> <label for="<?= $field_name ?>[<?= $key ?>]"><?= $arPTField["UF_NAME"] ?></label> 
                                    </div>
                                    <?
                                    $content = ob_get_clean();
                                    if ($arPTField['UF_MAIN']) {
                                        $arContent['main'][] = $content;
                                    } elseif ($arPTField['UF_WPHP']) {
                                        $arContent['without_place'][] = $content;
                                    } else {
                                        $arContent['addit'][] = $content;
                                    }
                                endif; elseif ($arPTField['ID'] == 4 || $arPTField['UF_FOR_PLACE'] == 1 || $arPTField['UF_FOR_ROOM'] == 1) :
                                ?>
                <? ob_start() ?>
                                <div class="checkbox <? if ($isChild): ?> is-child <? if (strlen($checked) <= 0) {
                        echo "hidden";
                    } ?> <? endif ?>">
                                    <input onclick="onPtClick(this)" <? if ($arPTField['UF_FOR_PLACE'] || $arPTField['ID'] == 4): ?>data-for-place="Y"<? endif ?> <? if ($arPTField['UF_FOR_ROOM'] || $arPTField['ID'] == 4): ?>data-for-room="Y"<? endif ?> data-index="<?= $key ?>" class="price-type-checkbox" <?= $checked ?> type="checkbox" name="<?= $field_name ?>[<?= $key ?>]" value="<?= $arPTField['ID'] ?>"> <label for="<?= $field_name ?>[<?= $key ?>]"><?= $arPTField["UF_NAME"] ?></label> 
                                </div>
                                <?
                                $content = ob_get_clean();
                                if ($arPTField['UF_MAIN']) {
                                    $arContent['main'][] = $content;
                                } elseif ($arPTField['UF_WPHP']) {
                                    $arContent['without_place'][] = $content;
                                }else {
                                    $arContent['addit'][] = $content;
                                }
                                ?>
                            <? endif ?>
                        <? endforeach ?>
                        <label class="price-types-titles-area"><b>Основные типы цен</b></label><br>
                        <div id="price-types-main"></div>
                        <? /* foreach ($arContent['MAIN'] as $content) {
                          echo $content;
                          } */ ?>
                        <label class="price-types-titles-area"><b>Дополнительные типы цен</b></label>
                        <div id="price-types-addit"></div>
                    <? /* foreach ($arContent['ADDIT'] as $content) {
                      echo $content;
                      } */ ?>
                    </div>
                    <?
                    continue;
                }

//        $aliasLabel = $field_name  . "_ALIAS";
//        $alias = GetMessage($aliasLabel);
//        if (!strlen($alias)) {
                $alias = $arField["EDIT_FORM_LABEL"];
//        }

                switch ($arField['USER_TYPE_ID']) {

                    case "boolean":
                        $booleanExists = true;
                        ?>

                        <div class="form-group boolPropContainer">
                            
                            <label><input type="checkbox" value="1" name="<?= $field_name ?>" <?
                       if ($arField["VALUE"]) {
                           echo "checked";
                       }
                       ?>> <b><?= $alias ?><? if ($arField['MANDATORY'] == "Y"): ?><span class="starrequired">*</span><? endif ?></b></label>

                        </div>

                        <?
                        break;

                    case "integer":
                    case "double":
                    case "string":


                        // Примечание
                        if (in_array($field_name, array("UF_NOTE", "UF_NOTE_EN", "UF_NOTE_BY", "UF_CANCEL_POLICY_BY", "UF_CANCEL_POLICY_EN", "UF_CANCEL_POLICY"))) {
                            ?>
                            <div class="form-group">
                                <label><b><?= $alias ?><? if ($arField['MANDATORY'] == "Y"): ?><span class="starrequired">*</span><? endif ?></b></label>

                                <?
                                $LHE = new CHTMLEditor;
                                $LHE->Show(array(
                                    'name' => $field_name,
                                    'id' => $field_name . $arParam['BLOCK_ID'],
                                    'inputName' => $field_name,
                                    'content' => $arField['VALUE'],
                                    'width' => '100%',
                                    'minBodyWidth' => 350,
                                    'normalBodyWidth' => 555,
                                    'height' => '75',
                                    'bAllowPhp' => false,
                                    'limitPhpAccess' => false,
                                    'autoResize' => true,
                                    'autoResizeOffset' => 40,
                                    'useFileDialogs' => false,
                                    'saveOnBlur' => true,
                                    'showTaskbars' => false,
                                    'showNodeNavi' => false,
                                    'askBeforeUnloadPage' => true,
                                    'bbCode' => false,
                                    'siteId' => SITE_ID,
                                    'controlsMap' => array(
                                        array('id' => 'Bold', 'compact' => true,
                                            'sort' => 80),
                                        array('id' => 'Italic', 'compact' => true,
                                            'sort' => 90),
                                        array('id' => 'Underline', 'compact' => true,
                                            'sort' => 100),
                                        array('id' => 'Strikeout', 'compact' => true,
                                            'sort' => 110),
                                        array('id' => 'RemoveFormat', 'compact' => true,
                                            'sort' => 120),
                                        array('id' => 'Color', 'compact' => true,
                                            'sort' => 130),
                                        array('id' => 'FontSelector', 'compact' => false,
                                            'sort' => 135),
                                        array('id' => 'FontSize', 'compact' => false,
                                            'sort' => 140),
                                        array('separator' => true, 'compact' => false,
                                            'sort' => 145),
                                        array('id' => 'OrderedList', 'compact' => true,
                                            'sort' => 150),
                                        array('id' => 'UnorderedList', 'compact' => true,
                                            'sort' => 160),
                                        array('id' => 'AlignList', 'compact' => false,
                                            'sort' => 190),
                                        array('separator' => true, 'compact' => false,
                                            'sort' => 200),
                                        array('id' => 'InsertLink', 'compact' => true,
                                            'sort' => 210),
                                        array('id' => 'InsertImage', 'compact' => false,
                                            'sort' => 220),
                                        array('id' => 'InsertVideo', 'compact' => true,
                                            'sort' => 230),
                                        array('id' => 'InsertTable', 'compact' => false,
                                            'sort' => 250),
                                        array('separator' => true, 'compact' => false,
                                            'sort' => 290),
                                        array('id' => 'Fullscreen', 'compact' => false,
                                            'sort' => 310),
                                        array('id' => 'More', 'compact' => true,
                                            'sort' => 400)
                                    ),
                                ));
                                ?>
                            </div>
                            <?
                            break;
                        }



                        // привязка к элементам услуг(номеров) (select'ы)
                        if ($field_name == "UF_SERVICES_ID") {
                            $result = $arResult["SERVICES_HL_ELEMENTS"]['rows'];
                            $placeholder = "data-placeholder='" . GetMessage('CHOOSE') . "'";
                            ?>
                            <div style="display: none;"><pre><?print_r($arResult["SERVICES_HL_ELEMENTS"]['rows'])?></pre></div>

                            <div class="form-group" id="select__services">
                                <label><b><?= $alias ?><? if ($arField['MANDATORY'] == "Y"): ?><span class="starrequired">*</span><? endif ?></b></label>
                                <select <?= $placeholder ?> name="<?= $field_name ?><? if ($arField['MULTIPLE'] == "Y") echo "[]" ?>" <? if ($arField['MULTIPLE'] == "Y"): ?>multiple="Y"<? endif ?> class="select">

                <? $settedVals = (array) $arField['VALUE']; ?>
                <? foreach ($result as $key => $arItem): ?>
                                        <option <? if (in_array($arItem['ID'], $settedVals)): ?>selected<? endif ?> value="<?= $arItem['ID'] ?>"><?= $arItem['UF_NAME'] ?></option>
                            <? endforeach ?>

                                </select>
                            </div>

                            <?
                            break;
                        }

                        if ($field_name == "UF_CURRENCY_ID") {
                            ?>
                            <div class="form-group">
                                <?if ($isBlockedBlockEdit):?>
                                <div class="blocked-div"><div class="blocked-text">Недоступно для редактирования данной группе пользователей</div></div>
                                <?endif?>
                                <label><b><?= $alias ?><? if ($arField['MANDATORY'] == "Y"): ?><span class="starrequired">*</span><? endif ?></b></label>
                                
                                <select <?= $placeholder ?> name="<?= $field_name ?><? if ($arField['MULTIPLE'] == "Y") echo "[]" ?>" <? if ($arField['MULTIPLE'] == "Y"): ?>multiple<? endif ?> class="select">
                                    <?
                                    $settedVals = (array) $arField['VALUE'];
                                    foreach ($arResult['CURRENCY'] as $key => $arItem):
                                        ?>
                                        <option <? if (in_array($arItem['id'], $settedVals)): ?>selected<? endif ?> value="<?= $arItem['id'] ?>"><?= $arItem['name'] ?></option>
                            <? endforeach ?>
                                </select>
                                
                            </div>
                            <?
                            break;
                        }

                        if ($arField['MULTIPLE'] == "Y")
                            for ($i = 0; $i < $default_multiple_size; $i++) {
                                ?>
                                <div class="form-group">
                                    <label><b><?= $alias ?><? if ($arField['MANDATORY'] == "Y"): ?><span class="starrequired">*</span><? endif ?></b></label>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" name="<?= $field_name ?>[<?= $i ?>]" value="<?= $arField['VALUE'] ?>" type="text">
                                </div><?
                            } else {
                            ?>
                            <div class="form-group">
                                <label><b><?= $alias ?><? if ($arField['MANDATORY'] == "Y"): ?><span class="starrequired">*</span><? endif ?></b></label>
                                <input <? if ($field_name == "UF_NAME") echo "id='" . $HTML_ID . "'" ?> class="form-control" name="<?= $field_name ?>" value="<?= htmlspecialchars($arField['VALUE']) ?>" type="text">
                            </div>
                            <?
                        }

                        if($field_name == 'UF_DISCOUNT_BY_DAYS'){ 
                            $asArr = json_decode($arField['VALUE'], true);
                            foreach($asArr as $key => $item) :?>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label><?php echo GetMessage('NUMBER_OF_DAYS'); ?></label>
                                        <input class="form-control days" type="text" name="days[]" value="<?php echo $item['days'] ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label><?php echo GetMessage('DISCOUNT'); ?></label>
                                        <input class="form-control discount" type="text" name="discount[]" value="<?php echo $item['discount'] ?>">   
                                    </div>                                
                                </div>
                            <?php endforeach; ?>                            

                            <div class="form-group">    
                                <button class="btn btn-primary add-discount" type="button"><? echo GetMessage('ADD_DISCOUNT_BY_DAY'); ?></button>  
                            </div>
                                
                            <?php
                        }

                        break;
                    case "enumeration":
                        ?>
                        <div class="form-group">
                            <label><b><?= $alias ?><? if ($arField['MANDATORY'] == "Y"): ?><span class="starrequired">*</span><? endif ?></b></label>
                            <select name="<?= $field_name ?><? if ($arField['MULTIPLE'] == "Y") echo "[]" ?>" <? if ($arField['MULTIPLE'] == "Y"): ?>multiple<? endif ?> class="select">
                                <?
                                $settedVals = (array) $arField['VALUE'];
                                foreach ($arField['VALUES'] as $id => $arValue):
                                    ?>
                                    <option <? if (in_array($id, $settedVals)): ?>selected<? endif ?> value="<?= $id ?>"><?= $arValue['VALUE'] ?></option>
                        <? endforeach ?>
                            </select>
                        </div>
                        <?
                        break;
                    case "file":
                        if ($arField['MULTIPLE'] == 'Y') {
                            ?>
                            <div class="form-group">
                                <label><b><?= $alias ?><? if ($arField['MANDATORY'] == "Y"): ?><span class="starrequired">*</span><? endif ?></b></label>
                            </div>
                            <?
                            $cnt = $arField['VALUE'] ? count($arField['VALUE']) : 0;
                            if ($cnt > 0) {
                                for ($i = 0; $i < $cnt; $i++) {
                                    ?>
                                    <div class="form-group">
                                        <input class="file-styled" type="file" name="<?= $field_name ?>[<?= $i ?>]">
                                        <input type='hidden' name='<?= $field_name ?>_old_id[<?= $i ?>]' value="<?= $arField['VALUE'][$i] ?>">
                                        <div class="checkbox">
                                            <input type="checkbox" name="<?= $field_name ?>_del[<?= $i ?>]" value="Y" > <label for="<?= $field_name ?>_del[<?= $i ?>]"><b><?= GetMessage('DELETE_FILE') ?></b></label>
                                        </div>
                                        <? if ($arField['VALUE_DETAIL_INFO'][$i]["IS_IMAGE"]): ?>
                                            <img src="<?= $arField['VALUE_DETAIL_INFO'][$i]["SRC"] ?>" height="150<? //= $arResult["ELEMENT_FILES"][$value]["HEIGHT"]      ?>" width="150<? //= $arResult["ELEMENT_FILES"][$value]["WIDTH"]      ?>" border="0" />
                                    <? else: ?>
                                            [<a href="<?= $arField['VALUE_DETAIL_INFO'][$i]["SRC"] ?>"><?= $arField['VALUE_DETAIL_INFO'][$i]['FILE_NAME'] ?></a>]
                                    <? endif ?>
                                    </div>
                                    <?
                                }
                            }

                            for ($i = $cnt; $i < ($cnt + $default_multiple_size); $i++) {
                                ?>
                                <div class="form-group">
                                    <input class="file-styled" type="file" name="<?= $field_name ?>[<?= $i ?>]">
                                </div>
                                <?
                            }
                        } else {
                            if ($arField['VALUE'] > 0) {
                                ?>
                                <div class="form-group">
                                    <label><b><?= $alias ?><? if ($arField['MANDATORY'] == "Y"): ?><span class="starrequired">*</span><? endif ?></b></label>
                                    <input type='hidden' name='<?= $field_name ?>_old_id' value="<?= $arField['VALUE'] ?>">
                                    <div class="checkbox">
                                        <input type=checkbox" name="<?= $field_name ?>_del" value="Y" > <label for="<?= $field_name ?>_del"><b>Удалить файл</b></label>
                                        <? if ($arField['VALUE_DETAIL_INFO']["IS_IMAGE"]): ?>
                                            <img src="<?= $arField['VALUE_DETAIL_INFO']["SRC"] ?>" height="150<? //= $arResult["ELEMENT_FILES"][$value]["HEIGHT"]      ?>" width="150<? //= $arResult["ELEMENT_FILES"][$value]["WIDTH"]      ?>" border="0" />
                                <? else: ?>
                                            [<a href="<?= $arField['VALUE_DETAIL_INFO']["SRC"] ?>"><?= $arField['VALUE_DETAIL_INFO']['FILE_NAME'] ?></a>]
                                <? endif ?>
                                    </div>
                                </div>
                    <?
                } else {
                    ?>
                                <div class="form-group">
                                    <input class="file_styled" type="file" name="<?= $field_name ?>">
                                </div>
                                <?
                            }
                        }
                        break;
                }
            }
            ?>
            <div class="mt-20">
                <button class="btn btn-primary" type="submit" name="save" value="<?= GetMessage("SERVICE_FORM_SUBMIT") ?>" ><?= GetMessage("SERVICE_FORM_SUBMIT") ?></button>
<? if (strlen($arParams["LIST_URL"]) > 0): ?>
                    <button class="btn btn-primary" type="submit" name="apply" value="<?= GetMessage("SERVICE_FORM_APPLY") ?>" ><?= GetMessage("SERVICE_FORM_APPLY") ?></button>
                    <button class="btn btn-danger"
                            type="button"
                            name="cancel"
                            value="<? echo GetMessage('IBLOCK_FORM_CANCEL'); ?>"
                            onclick="location.href = '<? echo CUtil::JSEscape($arParams["LIST_URL"]) ?>';"
                            ><? echo GetMessage('SERVICE_FORM_CANCEL'); ?></button>
<? endif ?>
            </div>
        </div>
    </div>
</form>
<?
if (!empty($arResult['HL_ELEMENTS']['rows'])) {
    $this->addExternalJs(SITE_TEMPLATE_PATH . '/js/plugins/forms/inputs/typeahead/typeahead.bundle.min.js');
    ?>
    <script>
        (function ($) {

            $('input[name="UF_DISCOUNT_BY_DAYS"]').hide();

            var discountByDaysFields = '<div class="form-group row">' +
                                '<div class="col-md-6">' +
                                    '<label><?php echo GetMessage('NUMBER_OF_DAYS'); ?></label>' +
                                    '<input class="form-control days" type="text" name="days[]">' +
                                '</div>' +
                                '<div class="col-md-6">'  +
                                     '<label><?php echo GetMessage('DISCOUNT'); ?></label>'  +
                                '<input class="form-control discount" type="text" name="discount[]">'  +
                                '</div>' +                              
                            '</div>';

            $('.add-discount').click(function(){
                $(this).parent().before(discountByDaysFields);
            });     

            $('.add-discount').trigger('click');
            
            $('.popuwer').each(function () {
                var $this = $(this);
                $this.webuiPopover({content: $this.data('content'),trigger:'hover', width: "300px"});
            });
            
            // РАБОТА С ТИПАМИ ЦЕН
            var priceTypes = JSON.parse('<?= \Bitrix\Main\Web\Json::encode($arResult['PRICE_TYPES_HL_ELEMENTS']['rows']) ?>');
            var forPlaceCheckbox = $('.for-place-checkbox');
            

    <?
    for ($i = 0; $i < 18; $i++) {
        $sel_options .= '<option value="' . $i . '">' . $i . '</option>';
    }
    $content_ = '<div style="margin: 30px 0 10px 0" class="pt-child-choose-block">';
    $content_ .= 'Стоимость за основное место ребенок от <select  name="main_age_min">';
    $content_ .= $sel_options;
    $content_ .= '</select> до <select  name="main_age_max">';
    $content_ .= $sel_options;
    $content_ .= '</select> лет <button class="pt-child-btn btn btn-primary" data-placement-type="main" onclick="childPtAgeChoose(this)" type="button">Добавить</button>';
    $content_ .= '</div>';

    $content__ = '<div class="pt-child-choose-block">';
    $content__ .= 'Стоимость за дополнит. место ребенок от <select  name="addit_age_min">';
    $content__ .= $sel_options;
    $content__ .= '</select> до <select  name="addit_age_max">';
    $content__ .= $sel_options;
    $content__ .= '</select> лет <button class="pt-child-btn btn btn-primary" data-placement-type="addit" onclick="childPtAgeChoose(this)" type="button">Добавить</button>';
    $content__ .= '</div>';
    
    $content___ = '<div style="margin: 10px 0 10px 0" class="pt-child-choose-block">';
    $content___ .= 'Стоимость для ребенка без места от <select  name="without_place_age_min">';
    $content___ .= $sel_options;
    $content___ .= '</select> до <select  name="without_place_age_max">';
    $content___ .= $sel_options;
    $content___ .= '</select> лет <button class="pt-child-btn btn btn-primary" data-placement-type="without_place" onclick="childPtAgeChoose(this)" type="button">Добавить</button>';
    $content___ .= '</div>';
    ?>


            var ptContent = {main: [], addit: [], without_place: []};
    <? foreach ($arContent as $key => $content): ?>
        <? foreach ($content as $html): ?>
                    ptContent["<?= $key ?>"].push('<?= str_replace(array("\r", "\n"), array("", ""), $html) ?>');
        <? endforeach ?>
    <? endforeach ?>

            window.childPtAgeChoose = function (_this) {

                var minAge;
                var maxAge;
                var checkBox;
                var exists = false;
                var placementType = $(_this).data("placement-type");
                
                switch (placementType) {

                    case "main":

                        minAge = Number($('select[name=main_age_min]').val());

                        maxAge = Number($('select[name=main_age_max]').val());
                        
                        for (var i = 0; i < priceTypes.length; i++) {
      
                            if (
                                    priceTypes[i]["UF_AGE_MIN"] == minAge && 
                                    priceTypes[i]["UF_AGE_MAX"] == maxAge && 
                                    priceTypes[i]["UF_MCHP"] == 1 
                            ) {
                        
                                checkBox = $("input[value="+priceTypes[i]["ID"]+"]");
                                if (checkBox.length) {
                                    if (checkBox.parent().hasClass("hidden")) {
                                        checkBox.parent().removeClass('hidden');
                                        if (!checkBox.prop('disabled')) {
                                            checkBox.prop('checked', true);
                                        } 
                                    } else {
                                        alert("Данный тип цены уже добавлен. Введите корректный возраст.");
                                    }
                                    
                                } else {
                                    
                                    alert("Данный тип цены не найден. Для добавления данного типа цены обратитесь к администратору сайта");
                                }
                                
                                exists = true;
                                
                                break;
                            }
                        }
                        
                        if(!exists){
                            
                            if (maxAge > 0 && minAge >= 0 && maxAge > minAge) {
                              
                              $.ajax({
                                  data: {maxAge: maxAge, minAge: minAge, sessid: "<?= bitrix_sessid()?>"},
                                  dataType: "json",
                                  type: "POST",
                                  url: "<?= $templateFolder?>/ajax.php",
                                  success: function (data) {
                                     
                                      var $this = $(_this);
                                       var pt9 = $("input[value=9]");
                                      var pt7 = $("input[value=7]");
                                      var index_;
                                      var index__;
                                      
                                      if (pt9.length) {
                                          priceTypes[pt9.data('index')].UF_SUB_PRICE_TYPES.push(data[0].ID);
                                          priceTypes[pt9.data('index')].UF_SUB_PRICE_TYPES.push(data[0].ID, data[1].ID);
                                      }
                                      
                                      if (pt7.length) {
                                          priceTypes[pt7.data('index')].UF_SUB_PRICE_TYPES.push(data[1].ID);
                                      }
                                      
                                      index_ = priceTypes.push(data[0]) - 1;
                                      index__ = priceTypes.push(data[1]) - 1;
                                      
                                      $($(".pt-child-choose-block")[0]).before(
                                            '<div class="checkbox  is-child hidden">\
                                                <input onclick="onPtClick(this)" data-for-place="Y" data-index="'+index_+'" class="price-type-checkbox" type="checkbox" name="PRICE_TYPES['+index_+']" value="'+data[0].ID+'">\
                                                <label for="PRICE_TYPES['+index_+']">'+data[0].UF_NAME+'</label> \
                                             </div>',
                                                            '<div class="checkbox  is-child hidden">\
                                                <input onclick="onPtClick(this)" data-for-place="Y" data-for-room="Y" data-index="'+index__+'" class="price-type-checkbox" type="checkbox" name="PRICE_TYPES['+index__+']" value="'+data[1].ID+'">\
                                                <label for="PRICE_TYPES['+index__+']">'+data[1].UF_NAME+'</label> \
                                             </div>'
                                        );
                                
                                        $this.trigger('click');
                                    }
                                  
                              });
                              
                            } else {
                                
                                alert("Данный тип цены не найден. Для добавления данного типа цены обратитесь к администратору сайта");
                            }
                        }
                        
                        break;

                    case "addit":

                        minAge = Number($('select[name=addit_age_min]').val());

                        maxAge = Number($('select[name=addit_age_max]').val());
                        
                        for (var i = 0; i < priceTypes.length; i++) {
                            if (
                                    priceTypes[i]["UF_AGE_MIN"] == minAge && 
                                    priceTypes[i]["UF_AGE_MAX"] == maxAge && 
                                    priceTypes[i]["UF_ADCHP"] == 1 
                            ) {
                                checkBox = $("input[value="+priceTypes[i]["ID"]+"]");
                                if (checkBox.length) {
                                    if (checkBox.parent().hasClass("hidden")) {
                                        checkBox.parent().removeClass('hidden');
                                        if (!checkBox.prop('disabled')) {
                                            checkBox.prop('checked', true);
                                        } 
                                    } else {
                                        alert("Данный тип цены уже добавлен. Введите корректный возраст.");
                                    }
                                } else {
                                    
                                    alert("Данный тип цены не найден. Для добавления данного типа цены обратитесь к администратору сайта");
                                }
                                
                                exists = true;
                                
                                break;
                            
                            }
                        }
                        
                        if (!exists) {
                            if (maxAge > 0 && minAge >= 0 && maxAge > minAge) {
                                
                              $.ajax({
                                  data: {maxAge: maxAge, minAge: minAge, sessid: "<?= bitrix_sessid()?>"},
                                  dataType: "json",
                                  type: "POST",
                                  url: "<?= $templateFolder?>/ajax.php",
                                  success: function (data) {
                                      
                                      var $this = $(_this);
                                      var pt9 = $("input[value=9]");
                                      var pt7 = $("input[value=7]");
                                      var index_;
                                      var index__;
                                      
                                      if (pt9.length) {
                                          priceTypes[pt9.data('index')].UF_SUB_PRICE_TYPES.push(data[0].ID);
                                          priceTypes[pt9.data('index')].UF_SUB_PRICE_TYPES.push(data[1].ID);
                                      }
                                      
                                      if (pt7.length) {
                                          priceTypes[pt7.data('index')].UF_SUB_PRICE_TYPES.push(data[1].ID);
                                      }
                                      
                                      index_ = priceTypes.push(data[0]) - 1;
                                      index__ = priceTypes.push(data[1]) - 1;
                                      
                                      $($(".pt-child-choose-block")[0]).before(
                                            '<div class="checkbox  is-child hidden">\
                                                <input onclick="onPtClick(this)" data-for-place="Y" data-index="'+index_+'" class="price-type-checkbox" type="checkbox" name="PRICE_TYPES['+index_+']" value="'+data[0].ID+'">\
                                                <label for="PRICE_TYPES['+index_+']">'+data[0].UF_NAME+'</label> \
                                             </div>',
                                                            '<div class="checkbox  is-child hidden">\
                                                <input onclick="onPtClick(this)" data-for-place="Y" data-for-room="Y" data-index="'+index__+'" class="price-type-checkbox" type="checkbox" name="PRICE_TYPES['+index__+']" value="'+data[1].ID+'">\
                                                <label for="PRICE_TYPES['+index__+']">'+data[1].UF_NAME+'</label> \
                                             </div>'
                                        );
                                      
                                      $this.trigger('click');
                                      
                                  }
                              });
                              
                            } else {
                                
                                alert("Данный тип цены не найден. Для добавления данного типа цены обратитесь к администратору сайта");
                            }
                        }
                        
                        break;
                        
                    case "without_place":
                        
                        minAge = Number($('select[name=without_place_age_min]').val());

                        maxAge = Number($('select[name=without_place_age_max]').val());
                        
                        for (var i = 0; i < priceTypes.length; i++) {
                            if (
                                    priceTypes[i]["UF_AGE_MIN"] == minAge && 
                                    priceTypes[i]["UF_AGE_MAX"] == maxAge && 
                                    priceTypes[i]["UF_WPHP"] == 1 
                            ) {
                                checkBox = $("input[value="+priceTypes[i]["ID"]+"]");
                                if (checkBox.length) {
                                    if (checkBox.parent().hasClass("hidden")) {
                                        checkBox.parent().removeClass('hidden');
                                        if (!checkBox.prop('disabled')) {
                                            checkBox.prop('checked', true);
                                        } 
                                    } else {
                                        alert("Данный тип цены уже добавлен. Введите корректный возраст.");
                                    }
                                } else {
                                    
                                    alert("Данный тип цены не найден. Для добавления данного типа цены обратитесь к администратору сайта");
                                }
                                
                                exists = true;
                                
                                break;
                            
                            }
                        }
                        
                        if (!exists) {
                            if (maxAge > 0 && minAge >= 0 && maxAge > minAge) {
                              
                              $.ajax({
                                  data: {maxAge: maxAge, minAge: minAge, sessid: "<?= bitrix_sessid()?>", without_place: "Y"},
                                  dataType: "json",
                                  type: "POST",
                                  url: "<?= $templateFolder?>/ajax.php",
                                  success: function (data) {
                                      
                                      var $this = $(_this);
                                      var pt9 = $("input[value=9]");
                                      var pt7 = $("input[value=7]");
                                      var index_;
                                      var index__;
                                      
                                      if (pt9.length) {
                                          priceTypes[pt9.data('index')].UF_SUB_PRICE_TYPES.push(data[0].ID);
                                      }
                                      
                                      if (pt7.length) {
                                          priceTypes[pt7.data('index')].UF_SUB_PRICE_TYPES.push(data[0].ID);
                                      }
                                      
                                      index_ = priceTypes.push(data[0]) - 1;
                                      
                                      $($(".pt-child-choose-block")[0]).before(
                                              '<div class="checkbox  is-child hidden">\
                                                <input onclick="onPtClick(this)" data-for-place="Y" data-for-room="Y" data-index="'+index_+'" class="price-type-checkbox" type="checkbox" name="PRICE_TYPES['+index_+']" value="'+data[0].ID+'">\
                                                <label for="PRICE_TYPES['+index_+']">'+data[0].UF_NAME+'</label> \
                                             </div>'
                                        );
                                      
                                      $this.trigger('click');
                                      
                                  }
                              });
                              
                            } else {
                                
                                alert("Данный тип цены не найден. Для добавления данного типа цены обратитесь к администратору сайта");
                            }
                        }
                        
                    break;
                    
                    default:
                        break;
                }

            }

			//корректная обработка расчета стоимости при копировании
			//forPlaceCheckbox.trigger('click');
			//renderPt(true);

            // АКТИВИРУЕМ/ДИАКТИВИРУЕМ ТИПЫ ЦЕН
            function toggleDisabled(parent) {

                var key = parent.data('index');

                if (priceTypes[key].UF_SUB_PRICE_TYPES.length > 0) {

                    if (parent.is(":checked")) {

                        for (var i = 0; i < priceTypes[key].UF_SUB_PRICE_TYPES.length; i++) {

                            $('.price-type-checkbox[value=' + priceTypes[key].UF_SUB_PRICE_TYPES[i] + ']').prop('disabled', false);
                            
                        }
                        
                        for (var i = 0; i < priceTypes.length; i++) {
                            if ($.inArray(priceTypes[i].ID, priceTypes[key].UF_SUB_PRICE_TYPES) !== -1 && 
                                    (priceTypes[i].UF_ADCHP == 1 || priceTypes[i].UF_MCHP == 1 || priceTypes[i].UF_WPHP == 1)) {
                                $('.pt-child-btn').each(function () {
                                    $(this).prop('disabled', false);
                                    
                                });
                                break;
                            }
                        }

                    } else {

                        for (var i = 0; i < priceTypes[key].UF_SUB_PRICE_TYPES.length; i++) {
                            $('.price-type-checkbox[value=' + priceTypes[key].UF_SUB_PRICE_TYPES[i] + ']').prop('disabled', true);
                            $('.price-type-checkbox[value=' + priceTypes[key].UF_SUB_PRICE_TYPES[i] + ']').prop('checked', false);
                        }
                        
                        for (var i = 0; i < priceTypes.length; i++) {
                            
                            if ($.inArray(priceTypes[i].ID, priceTypes[key].UF_SUB_PRICE_TYPES) !== -1 && 
                                    (priceTypes[i].UF_ADCHP == 1 || priceTypes[i].UF_MCHP == 1)) {
                                $('.pt-child-btn').each(function () {
                                    $(this).prop('disabled', true);
                                });
                                $('.price-type-checkbox[value=' + priceTypes[key].UF_SUB_PRICE_TYPES[i] + ']').parent().addClass('hidden');
                            }
                        }
                    }
                }
            }

            // СКРЫВАЕМ ВСЕ ТИПЫ ЦЕН
            function hideAllPriceTypes() {
                $('.price-type-checkbox').each(function () {
                    $(this).parent().hide();
                });
            }

            // ОТМЕНА ВЫБОРА ВСЕХ ТИПОВ ЦЕН
            function uncheckedAllPriceTypes() {
                $('.price-type-checkbox').each(function () {
                    $(this).prop("checked", false);
                });
            }

    <? if ($isExcursion): ?>
                // СКРЫВАЕМ/ПОКАЗЫВАЕМ ОБЛАСТЬ ВВОДА ИНФЫ ПО ВЗРОСЛЫМ И ДЕТЯМ (ДЛЯ ЭКСКУРСИЙ)
                function togglePeopleCntArea(hide) {

                    var $peopleCntArea = $("#people-cnt-area");
                    if (hide) {
                        $peopleCntArea.hide();
                    } else {
                        $peopleCntArea.show();
                    }
                }
    <? endif ?>

            // СКРЫВАЕМ/ПОКАЗЫВАЕМ ТИЦЫ ЦЕН
            // ЗАВИСИТ ОТ ПОЛОЖЕНИЯ ПОЛЯ "Расчет стоимости производится за место"
            function hidePriceTypes(fp) {

                hideAllPriceTypes();

                if (fp) {

                    $('.price-type-checkbox[data-for-place=Y], .price-type-checkbox[data-for-odexc=Y]').each(function () {
                        var $this = $(this);
                        $this.parent().show();
                        
                        toggleDisabled($this);
                    });
                    
                } else {

                    $('.price-type-checkbox[data-for-room=Y], .price-type-checkbox[data-for-mdexc=Y]').each(function () {
                        var $this = $(this);
                        $this.parent().show();
                        
                        toggleDisabled($this);
                    });
                    
                }

            }

            // ОБРАБОТЧИК КЛИКА ПО ТИПУ ЦЕНЫ
            window.onPtClick = function (_this) {
                var $this = $(_this);
                if ($this.val() == 4) {

                    $this.prop('disabled', true);
                    return;
                }

                toggleDisabled($this);
                
                if ($this.parent().hasClass('is-child') && !$this.is(":checked")) {
                    $this.parent().addClass('hidden');
                }
            }

            // ОТРИСОВКА ТИПОВ ЦЕН
            function renderPt(fp) {

                var main = '', addit = '', without_place="", cnt = 0;
                var htmlItem;
                var isExcursions = <?if ($isExcursion):?>true<?else:?>false<?endif?>;
                if (fp) {

                    for (var i = 0; i < ptContent.main.length; i++) {

                        htmlItem = $(ptContent.main[i])
                                .find('.price-type-checkbox[data-for-place=Y], .price-type-checkbox[data-for-odexc=Y]');
                        if (htmlItem.length) {
                            main += ptContent.main[i];

                        }
                    }

                    for (var i = 0; i < ptContent.addit.length; i++) {

                        htmlItem = $(ptContent.addit[i])
                                .find('.price-type-checkbox[data-for-place=Y], .price-type-checkbox[data-for-odexc=Y]');
                        if (htmlItem.length) {
                            addit += ptContent.addit[i];

                        }
                    }
                    
                    $('#price-types-main').html(main);
                    $('#price-types-addit').html(addit + '<?= $content_ ?>' + '<?= $content__ ?>');

                    $('.price-type-checkbox[data-for-place=Y], .price-type-checkbox[data-for-odexc=Y]').each(function () {
                        toggleDisabled($(this));
                    });
                } else {

                    for (var i = 0; i < ptContent.main.length; i++) {

                        htmlItem = $(ptContent.main[i])
                                .find('.price-type-checkbox[data-for-room=Y], .price-type-checkbox[data-for-mdexc=Y]');
                        if (htmlItem.length) {
                            main += ptContent.main[i];
                        }
                    }

                    for (var i = 0; i < ptContent.addit.length; i++) {

                        htmlItem = $(ptContent.addit[i])
                                .find('.price-type-checkbox[data-for-room=Y], .price-type-checkbox[data-for-mdexc=Y]');
                        if (htmlItem.length) {
                            addit += ptContent.addit[i];
                        }
                    }
                    if (isExcursions) {
                        for (var i = 0; i < ptContent.without_place.length; i++) {

                            htmlItem = $(ptContent.without_place[i])
                                    .find('.price-type-checkbox[data-for-room=Y], .price-type-checkbox[data-for-mdexc=Y]');
                            if (htmlItem.length) {
                                without_place += ptContent.without_place[i];

                            }
                        }
                    }
                    
                    $('#price-types-main').html(main);
                    $('#price-types-addit').html(!isExcursions ? addit + '<?= $content__ ?>' : addit + without_place + '<?= $content_ ?>' + '<?= $content__ ?>' + '<?= $content___ ?>');

                    $('.price-type-checkbox[data-for-room=Y], .price-type-checkbox[data-for-mdexc=Y]').each(function () {
                        toggleDisabled($(this));
                    });

                }

            }

            forPlaceCheckbox.on('click', function () {
                var value = $(this).val();
                uncheckedAllPriceTypes();
                $('.price-types-titles-area').show();
                renderPt(value == 1);
    <? if ($isExcursion): ?>
                    togglePeopleCntArea(value == 1);
    <? endif ?>
            });

    <? if ($arParams['ROW_ID'] <= 0): ?>

                $('.price-types-titles-area').hide();
                hideAllPriceTypes();
        <? if ($isExcursion): ?>
                    togglePeopleCntArea(true);
        <? endif ?>
    <? else: ?>

                //hidePriceTypes(<? if ($arResult['ROW_DATA']['fields']["UF_FOR_PLACE"]["VALUE"] == 1): ?>true<? else: ?>false<? endif ?>);
                renderPt(<? if ($arResult['ROW_DATA']['fields']["UF_FOR_PLACE"]["VALUE"] == 1): ?>true<? else: ?>false<? endif ?>);
        <? if ($isExcursion): ?>
                            togglePeopleCntArea(<? if ($arResult['ROW_DATA']['fields']["UF_FOR_PLACE"]["VALUE"] == 1): ?>true<? else: ?>false<? endif ?>);
        <? endif ?>
    <? endif ?>

                            ////////////////////////////////////////////////////////////////////////////////////

                            // Substring matches
                            var substringMatcher = function (strs) {
                                return function findMatches(q, cb) {
                                    var matches, substringRegex;

                                    // an array that will be populated with substring matches
                                    matches = [];

                                    // regex used to determine if a string contains the substring `q`
                                    substringRegex = new RegExp(q, 'i');

                                    // iterate through the pool of strings and for any string that
                                    // contains the substring `q`, add it to the `matches` array
                                    $.each(strs, function (i, str) {
                                        if (substringRegex.test(str)) {

                                            // the typeahead jQuery plugin expects suggestions to a
                                            // JavaScript object, refer to typeahead docs for more info
                                            matches.push({value: str});
                                        }
                                    });

                                    cb(matches);
                                };
                            };
                            var names = [];
                            var name = "";
    <? foreach ($arResult['HL_ELEMENTS']['rows'] as $el) { ?>
                                names.push('<?= $el['UF_NAME'] ?>');
    <? } ?>
                            $('#<?= $HTML_ID ?>').typeahead(
                                    {
                                        hint: true,
                                        highlight: true,
                                        minLength: 1
                                    },
                                    {
                                            name: 'UF_NAME',
                                        displayKey: 'value',
                                        source: substringMatcher(names)
                                    }
                            );

                            $("#block_row_add").on("submit", function (e) {
                                //преобразуем скидки по дням в json
                                let arrDays = [];
                                $('.days').each(function(){
                                    arrDays.push($(this).val())
                                });

                                let arrDiscount = [];
                                $('.discount').each(function(){
                                    arrDiscount.push($(this).val())
                                });

                                let resArrOfDiscount = [];
                                for (var i = 0; i < arrDays.length; i++) { 
                                    if (arrDays[i].trim() && 
                                        $.isNumeric(arrDays[i]) && $.isNumeric(arrDiscount[i]) && 
                                        arrDays[i] > 0 && arrDiscount[i] > 0) {
                                        resArrOfDiscount.push({days:arrDays[i], discount:arrDiscount[i]});
                                    }    
                                }

                                $('input[name="UF_DISCOUNT_BY_DAYS"]').val(JSON.stringify(resArrOfDiscount));

                                var $this = $(this);

    <? if ($booleanExists): ?>

                                    // значени 0 для boolean, если их не отметили при отправке
                                    $(".boolPropContainer input[type='checkbox']").each(function () {
                                        var $$this = $(this);
                                        if (!$$this.is(":checked")) {
                                            $this.append("<input name='" + $$this.attr("name") + "' type='hidden' value='0'>");
                                        }
                                    });

                                    // значение по-умолчанию для множественных select'ов
                                    $("select[multiple='Y']").each(function () {
                                        var $$this = $(this);
                                        if (!$$this.val()) {
                                            $this.append("<input name='" + $$this.attr("name") + "' type='hidden' value=''>");
                                        }
                                    });
    <? endif ?>

                            });
                            
                            function autosave() {
                                    var form = document.getElementById("block_row_add");
                                    setInterval(function () {

                                        $.ajax({
                                            url: form.action,
                                            data: new FormData(form),
                                            processData: false,
                                            contentType: false,
                                            type: 'POST',
                                        });
                                    }, 60000);
                                }

                            autosave();
                            
                        })(jQuery);
    </script>
<? } ?>


