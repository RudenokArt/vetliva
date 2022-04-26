<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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

if ($arResult['MESSOK']) {
?>
<div class="panel panel-flat">
         <div class="panel-body">
                <div class="text-left">
                    <?= ShowMessage(array("MESSAGE" => $arResult['MESSOK'], "TYPE" => "OK"))?>
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
                    <?= ShowError(implode("<br>", $arResult['ERRORS']))?>
                </div>
         </div>
</div>
<?
}

$HTML_ID = "UF_NAME_" . $arParams['BLOCK_ID'];
?>
<form id="block_row_add" name="block_row_add" action="<?= POST_FORM_ACTION_URI ?>" method="post" enctype="multipart/form-data">
    <?= bitrix_sessid_post()?>
    <input type="hidden" name="form_id" value="<?= $arResult['FORM_ID']?>">
    <div class="panel panel-flat">
         <div class="panel-body">
<?

if (!empty($arResult['PRICE_TYPES_HL_ELEMENTS']['rows'])) {
    $arResult['ROW_DATA']['fields']["PRICE_TYPES"] = $arResult['PRICE_TYPES_HL_ELEMENTS']['rows'];
    $arResult['FIELDS_FOR_SHOW'][] = "PRICE_TYPES";
}

$default_multiple_size = 5;
foreach ($arResult['ROW_DATA']['fields'] as $field_name => &$arField) {

        if (!in_array($field_name, $arResult['FIELDS_FOR_SHOW']))
            continue;
        
        // привязка к элементам питания (select'ы)
        if ($field_name == "UF_FOOD_ID") {
                    unset($arField['USER_TYPE_ID']);
                    $result = $arResult["FOOD_HL_ELEMENTS"]['rows'];
                    $placeholder = "data-placeholder='" . GetMessage('CHOOSE') . "'";
                    $settedVals = (array)$arField['VALUE'];
            ?>
             
                <div class="form-group">
                    <label><b><?= $arField['EDIT_FORM_LABEL']?><?if ($arField['MANDATORY'] == "Y"):?><span class="starrequired">*</span><?endif?></b></label>
                    <select <?= $placeholder?> name="<?= $field_name?><?if ($arField['MULTIPLE'] == "Y") echo "[]"?>" <?if ($arField['MULTIPLE'] == "Y"):?>multiple<?endif?> class="select">
                        <?foreach ($result as $key => $arItem) :?>
                        <option <?if (in_array($arItem['ID'], $settedVals)):?>selected<?endif?> value="<?= $arItem['ID']?>"><?= $arItem["UF_NAME_RU"]?></option>
                       <?endforeach?>
                    </select>
                </div>
             
        <?}
        
        // ТИПЫ ЦЕН
        /*if ($field_name == "PRICE_TYPES") {?>
            <div class="form-group">
                <label><b><?= GetMessage('PRICE_TYPE_TITLE')?><span class="starrequired">*</span></b></label>
                <?foreach ($arResult['PRICE_TYPES_HL_ELEMENTS']['rows'] as $key => $arField) :
                    $checked = "checked";
                    if ($arResult['pt'] && !in_array($arField['ID'], $arResult['pt'])) {
                            $checked = "";
                    }
                    ?>
                        
                        <div class="checkbox">
                            <input <?= $checked?> type="checkbox" name="<?= $field_name?>[<?= $key?>]" value="<?= $arField['ID']?>"> <label for="<?= $field_name?>[<?= $key?>]"><?= $arField["UF_NAME"]?></label> 
                        </div>
                    
                <?endforeach?>
            </div>
            <?}*/
        
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
                    <input type="checkbox" value="1" name="<?= $field_name?>" <?if ($arField["VALUE"]) {echo "checked";}?>>
                    <label><b><?= $alias?><?if ($arField['MANDATORY'] == "Y"):?><span class="starrequired">*</span><?endif?></b></label>
                </div>
            <?
        
        break;
        
    case "integer":
    case "double":
    case "string":
        
        
        // Примечание
        if (in_array($field_name, array("UF_NOTE", "UF_NOTE_EN", "UF_NOTE_BY", "UF_CANCEL_POLICY_BY", "UF_CANCEL_POLICY_EN", "UF_CANCEL_POLICY"))) { ?>
            <div class="form-group">
                    <label><b><?= $alias?><?if ($arField['MANDATORY'] == "Y"):?><span class="starrequired">*</span><?endif?></b></label>
                   
                    <?$LHE = new CHTMLEditor;
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
                        ));?>
            </div>
        <? break;}
        
        
        
        // привязка к элементам услуг(номеров) (select'ы)
        if ($field_name == "UF_SERVICES_ID") {
                    $result = $arResult["SERVICES_HL_ELEMENTS"]['rows'];
                    $placeholder = "data-placeholder='" . GetMessage('CHOOSE') . "'";
               
            ?>
             
                <div class="form-group">
                    <label><b><?= $alias?><?if ($arField['MANDATORY'] == "Y"):?><span class="starrequired">*</span><?endif?></b></label>
                    <select <?= $placeholder?> name="<?= $field_name?><?if ($arField['MULTIPLE'] == "Y") echo "[]"?>" <?if ($arField['MULTIPLE'] == "Y"):?>multiple="Y"<?endif?> class="select">
                        
                        <?$settedVals = (array)$arField['VALUE'];?>
                        <?foreach( $result as $key => $arItem ):?>
                        <option <?if (in_array($arItem['ID'], $settedVals)):?>selected<?endif?> value="<?= $arItem['ID']?>"><?= $arItem['UF_NAME']?></option>
                       <?endforeach?>
                        
                    </select>
                </div>
             
        <?break;}
        
        if ($field_name == "UF_CURRENCY_ID") {
           
            ?>
                <div class="form-group">
                    <label><b><?= $alias?><?if ($arField['MANDATORY'] == "Y"):?><span class="starrequired">*</span><?endif?></b></label>
                    <select <?= $placeholder?> name="<?= $field_name?><?if ($arField['MULTIPLE'] == "Y") echo "[]"?>" <?if ($arField['MULTIPLE'] == "Y"):?>multiple<?endif?> class="select">
                        <?
                        $settedVals = (array)$arField['VALUE'];
                        foreach( $arResult['CURRENCY'] as $key => $arItem ):?>
                        <option <?if (in_array($arItem['id'], $settedVals)):?>selected<?endif?> value="<?= $arItem['id']?>"><?= $arItem['name']?></option>
                       <?endforeach?>
                    </select>
                </div>
             <?break;
        }
        
        if ($arField['MULTIPLE'] == "Y")
            for ($i = 0; $i < $default_multiple_size; $i++) {
                ?>
             <div class="form-group">
                <label><b><?= $alias?><?if ($arField['MANDATORY'] == "Y"):?><span class="starrequired">*</span><?endif?></b></label>
             </div>
             <div class="form-group">
                <input class="form-control" name="<?= $field_name?>[<?= $i?>]" value="<?= $arField['VALUE']?>" type="text">
             </div><?
        } else {
        ?>
             <div class="form-group">
                <label><b><?= $alias?><?if ($arField['MANDATORY'] == "Y"):?><span class="starrequired">*</span><?endif?></b></label>
                <input <?if ($field_name == "UF_NAME") echo "id='".$HTML_ID."'"?> class="form-control" name="<?= $field_name?>" value="<?= $arField['VALUE']?>" type="text">
             </div>
        <?
        }
        break;
    case "enumeration":
        ?>
             <div class="form-group">
                <label><b><?= $alias?><?if ($arField['MANDATORY'] == "Y"):?><span class="starrequired">*</span><?endif?></b></label>
                <select name="<?= $field_name?><?if ($arField['MULTIPLE'] == "Y") echo "[]"?>" <?if ($arField['MULTIPLE'] == "Y"):?>multiple<?endif?> class="select">
                    <?
                    $settedVals = (array)$arField['VALUE'];
                    foreach( $arField['VALUES'] as $id => $arValue ):?>
                    <option <?if (in_array($id, $settedVals)):?>selected<?endif?> value="<?= $id?>"><?= $arValue['VALUE']?></option>
                   <?endforeach?>
                </select>
            </div>
        <?
        break;
    case "file":
        if ($arField['MULTIPLE'] == 'Y') {?>
                <div class="form-group">
                   <label><b><?= $alias?><?if ($arField['MANDATORY'] == "Y"):?><span class="starrequired">*</span><?endif?></b></label>
                </div>
            <?
            $cnt = $arField['VALUE'] ? count($arField['VALUE']) : 0;
            if ($cnt > 0) {
                for ($i = 0; $i < $cnt; $i++) { ?>
                <div class="form-group">
                        <input class="file-styled" type="file" name="<?= $field_name?>[<?= $i?>]">
                        <input type='hidden' name='<?= $field_name?>_old_id[<?= $i?>]' value="<?= $arField['VALUE'][$i]?>">
                        <div class="checkbox">
                                <input type="checkbox" name="<?= $field_name?>_del[<?= $i?>]" value="Y" > <label for="<?= $field_name?>_del[<?= $i?>]"><b><?= GetMessage('DELETE_FILE')?></b></label>
                        </div>
                        <?if ( $arField['VALUE_DETAIL_INFO'][$i]["IS_IMAGE"] ):?>
                                <img src="<?= $arField['VALUE_DETAIL_INFO'][$i]["SRC"] ?>" height="150<?//= $arResult["ELEMENT_FILES"][$value]["HEIGHT"] ?>" width="150<?//= $arResult["ELEMENT_FILES"][$value]["WIDTH"] ?>" border="0" />
                        <?else:?>
                                [<a href="<?= $arField['VALUE_DETAIL_INFO'][$i]["SRC"] ?>"><?= $arField['VALUE_DETAIL_INFO'][$i]['FILE_NAME'] ?></a>]
                        <?endif?>
                </div>
                        <?
                }
            }
            
            for ($i = $cnt; $i < ($cnt + $default_multiple_size); $i++) {?>
            <div class="form-group">
                    <input class="file-styled" type="file" name="<?= $field_name?>[<?= $i?>]">
            </div>
            <?}
            
        } else {
                if ($arField['VALUE'] > 0){
        ?>
                        <div class="form-group">
                           <label><b><?= $alias?><?if ($arField['MANDATORY'] == "Y"):?><span class="starrequired">*</span><?endif?></b></label>
                           <input type='hidden' name='<?= $field_name?>_old_id' value="<?= $arField['VALUE']?>">
                                <div class="checkbox">
                                    <input type=checkbox" name="<?= $field_name?>_del" value="Y" > <label for="<?= $field_name?>_del"><b>Удалить файл</b></label>
                                        <?if ( $arField['VALUE_DETAIL_INFO']["IS_IMAGE"] ):?>
                                                <img src="<?= $arField['VALUE_DETAIL_INFO']["SRC"] ?>" height="150<?//= $arResult["ELEMENT_FILES"][$value]["HEIGHT"] ?>" width="150<?//= $arResult["ELEMENT_FILES"][$value]["WIDTH"] ?>" border="0" />
                                        <?else:?>
                                                [<a href="<?= $arField['VALUE_DETAIL_INFO']["SRC"] ?>"><?= $arField['VALUE_DETAIL_INFO']['FILE_NAME'] ?></a>]
                                        <?endif?>
                                </div>
                        </div>
        <?
                } else {
                        ?>
                        <div class="form-group">
                                <input class="file_styled" type="file" name="<?= $field_name?>">
                        </div>
                        <?
                }
        }
        break;
    } 
    
}?>
            <div class="mt-20">
                <button class="btn btn-primary" type="submit" name="save" value="<?= GetMessage("SERVICE_FORM_SUBMIT") ?>" ><?= GetMessage("SERVICE_FORM_SUBMIT") ?></button>
                <? if (strlen($arParams["LIST_URL"]) > 0): ?>
                <button class="btn btn-primary" type="submit" name="apply" value="<?= GetMessage("SERVICE_FORM_APPLY") ?>" ><?= GetMessage("SERVICE_FORM_APPLY") ?></button>
                <button class="btn btn-danger"
                    type="button"
                    name="cancel"
                    value="<? echo GetMessage('IBLOCK_FORM_CANCEL'); ?>"
                    onclick="location.href='<? echo CUtil::JSEscape($arParams["LIST_URL"]) ?>';"
                    ><? echo GetMessage('SERVICE_FORM_CANCEL'); ?></button>
                <? endif ?>
            </div>
         </div>
    </div>
</form>
<?if (!empty($arResult['HL_ELEMENTS']['rows'])) {
        $this->addExternalJs(SITE_TEMPLATE_PATH . '/js/plugins/forms/inputs/typeahead/typeahead.bundle.min.js');
    ?>
    <script>
    (function ($) {
        // Substring matches
        var substringMatcher = function(strs) {
            return function findMatches(q, cb) {
                var matches, substringRegex;

                // an array that will be populated with substring matches
                matches = [];

                // regex used to determine if a string contains the substring `q`
                substringRegex = new RegExp(q, 'i');

                // iterate through the pool of strings and for any string that
                // contains the substring `q`, add it to the `matches` array
                $.each(strs, function(i, str) {
                    if (substringRegex.test(str)) {

                        // the typeahead jQuery plugin expects suggestions to a
                        // JavaScript object, refer to typeahead docs for more info
                        matches.push({ value: str });
                    }
                });

                cb(matches);
            };
        };
        var names = [];
        <?foreach ($arResult['HL_ELEMENTS']['rows'] as $el) {?>
            names.push("<?= $el['UF_NAME']?>");
        <?}?>
        $('#<?= $HTML_ID?>').typeahead(
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
        
        $("#block_row_add").on("submit", function () {
            
            var $this = $(this);
            
        <?if ($booleanExists):?>
                
            // значени 0 для boolean, если их не отметили при отправке
            $(".boolPropContainer input[type='checkbox']").each(function () {
                var $$this = $(this);
                if (!$$this.is(":checked")) {
                    $this.append("<input name='"+$$this.attr("name")+"' type='hidden' value='0'>");
                }
            });
            
            // значение по-умолчанию для множественных select'ов
            $("select[multiple='Y']").each(function () {
                var $$this = $(this);
                if (!$$this.val()) {
                    $this.append("<input name='"+$$this.attr("name")+"' type='hidden' value=''>");
                }
            });
        <?endif?>
         });
    })(jQuery);
    </script>
<? } ?>


