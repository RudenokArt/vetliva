<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
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
\Bitrix\Main\Loader::includeModule("iblock");

$rsLang = CLanguage::GetList($by="lid", $order="desc");
$arTabs = $arTabsPropertiesId = null;
while ($arLang = $rsLang->Fetch()) {
    $arTabs[$arLang["LID"]] = $arLang["LID"] == "ru" ? array("Основное описание", true)  : array($arLang["NAME"], false);
    $arTabsPropertiesId[$arLang["LID"]] = null;
}

foreach ($arResult["PROPERTY_LIST"] as $propertyID) {
    $arCodeParts = explode("_", $arResult['PROPERTY_LIST_FULL'][$propertyID]['CODE']);
    $langCode = strtolower(array_pop($arCodeParts));
    if (key_exists($langCode, $arTabsPropertiesId)) {
        $arTabsPropertiesId[$langCode][] = $propertyID;
    } else {
        $arTabsPropertiesId["ru"][] = $propertyID;
    }
}
?>
<form name="iblock_add" action="<?= POST_FORM_ACTION_URI ?>" method="post" enctype="multipart/form-data">
    <div class="panel panel-flat">
         <div class="panel-body">
<?if (!empty($arResult["ERRORS"])):?>
    <?ShowError(implode("<br />", $arResult["ERRORS"]))?>
<?endif;
if (strlen($arResult["MESSAGE"]) > 0):?>
    <?ShowNote($arResult["MESSAGE"])?>
<?endif?>
        <div class="tabbable">
            <ul class="nav nav-tabs nav-justified">
                <?foreach ($arTabs as $tab => $arTab):?>
                <li <?if( $arTab[1] ):?>class="active"<?endif?>><a  href="#basic-justified-<?= $tab?>" data-toggle="tab"><?= $arTab[0]?></a></li>
                <?endforeach?>
            </ul>
    <?if ($arParams["IS_EXC_TOUR"] == "Y"):?>
             <input name="IS_EXC_TOUR" value="Y" type="hidden">
    <?endif?>
    <?= bitrix_sessid_post() ?>
    <? if ($arParams["MAX_FILE_SIZE"] > 0): ?><input type="hidden" name="MAX_FILE_SIZE" value="<?= $arParams["MAX_FILE_SIZE"] ?>" /><? endif ?>

        <? if (is_array($arResult["PROPERTY_LIST"]) && !empty($arResult["PROPERTY_LIST"])): ?>
    <div class="tab-content">
            <?foreach ($arTabs as $tab => $arTab):?>
    <div class="tab-pane <?if ($arTab[1]) :?>active<?endif?>" id="basic-justified-<?= $tab?>">
                <? foreach ($arTabsPropertiesId[$tab] as
                        $propertyID): ?>
    
                <?if ($arResult['PROPERTY_LIST_FULL'][$propertyID]['CODE'] == "TOWN") {
                    
                        $arResult['PROPERTY_LIST_FULL'][$propertyID]['GetPublicEditHTML'] = array(
                            "CIBlockPropertyElementList", "GetPropertyFieldHtml"
                        );

                }?>
    
                <?if ($arResult['PROPERTY_LIST_FULL'][$propertyID]['CODE'] == "CITY") {
                    
                        $arResult['PROPERTY_LIST_FULL'][$propertyID]['GetPublicEditHTML'] = array(
                            "CIBlockPropertyElementList", "GetPropertyFieldHtml"
                        );

                }?>
        
                <?if ($arResult['PROPERTY_LIST_FULL'][$propertyID]['CODE'] == "LANG") {
                    
                        $arResult['PROPERTY_LIST_FULL'][$propertyID]['GetPublicEditHTML'] = array(
                            "CIBlockPropertyElementList", "GetPropertyFieldHtml"
                        );

                }?>
    
                <?if ($arResult['PROPERTY_LIST_FULL'][$propertyID]['CODE'] == "TRANSPORT") {
                    
                        $arResult['PROPERTY_LIST_FULL'][$propertyID]['GetPublicEditHTML'] = array(
                            "CIBlockPropertyElementList", "GetPropertyFieldHtml"
                        );
                    
                }?>
    
                <?if ($arResult['PROPERTY_LIST_FULL'][$propertyID]['CODE'] == "TOURTYPE") {
                    
                        $arResult['PROPERTY_LIST_FULL'][$propertyID]['GetPublicEditHTML'] = array(
                            "CIBlockPropertyElementList", "GetPropertyFieldHtml"
                        );
                    
                }?>
    
                <?if ($arResult['PROPERTY_LIST_FULL'][$propertyID]['CODE'] == "SIGHTS") {
                    
                        $arResult['PROPERTY_LIST_FULL'][$propertyID]['GetPublicEditHTML'] = array(
                            "CIBlockPropertyElementList", "GetPropertyFieldHtml"
                        );
                    
                }?>
    
                <?if ($arResult['PROPERTY_LIST_FULL'][$propertyID]['CODE'] == "THEME_TOURS") {
                    
                        $arResult['PROPERTY_LIST_FULL'][$propertyID]['GetPublicEditHTML'] = array(
                            "CIBlockPropertyElementList", "GetPropertyFieldHtml"
                        );
                    
                }?>
        
                <?if ($arResult['PROPERTY_LIST_FULL'][$propertyID]['CODE'] == "SPOSOB_PROVEDENIA") {
                    
                        $arResult['PROPERTY_LIST_FULL'][$propertyID]['GetPublicEditHTML'] = array(
                            "CIBlockPropertyElementList", "GetPropertyFieldHtml"
                        );
                    
                }?>
    
                <?if ($arResult['PROPERTY_LIST_FULL'][$propertyID]['CODE'] == "HOTEL") {
                    
                        $arResult['PROPERTY_LIST_FULL'][$propertyID]['GetPublicEditHTML'] = array(
                            "CIBlockPropertyElementList", "GetPropertyFieldHtml"
                        );
                    
                }?>
        
                <div class="form-group">
                  <label><b> <? if (intval($propertyID) > 0): ?> <?= $arResult["PROPERTY_LIST_FULL"][$propertyID]["NAME"] ?><? else: ?> <?= !empty($arParams["CUSTOM_TITLE_" . $propertyID])
                                    ? $arParams["CUSTOM_TITLE_" . $propertyID] : GetMessage("IBLOCK_FIELD_" . $propertyID) ?><? endif ?><? if (in_array($propertyID,
                                            $arResult["PROPERTY_REQUIRED"])): ?><span class="starrequired">*</span><? endif ?></b></label>
                        
                            <?
                            if (intval($propertyID) > 0) {
                                if (
                                        $arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"]
                                        == "T" &&
                                        $arResult["PROPERTY_LIST_FULL"][$propertyID]["ROW_COUNT"]
                                        == "1"
                                )
                                    $arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"]
                                            = "S";
                                elseif (
                                        (
                                        $arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"]
                                        == "S" ||
                                        $arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"]
                                        == "N"
                                        ) &&
                                        $arResult["PROPERTY_LIST_FULL"][$propertyID]["ROW_COUNT"]
                                        > "1"
                                )
                                    $arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"]
                                            = "T";
                            }
                            elseif (($propertyID == "TAGS") && CModule::IncludeModule('search'))
                                $arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"]
                                        = "TAGS";

                            if ($arResult["PROPERTY_LIST_FULL"][$propertyID]["MULTIPLE"]
                                    == "Y") {
                                $inputNum = ($arParams["ID"] > 0 || count($arResult["ERRORS"])
                                        > 0) ? count($arResult["ELEMENT_PROPERTIES"][$propertyID])
                                            : 0;
                                $inputNum += $arResult["PROPERTY_LIST_FULL"][$propertyID]["MULTIPLE_CNT"];
                            } else {
                                $inputNum = 1;
                            }

                            if ($arResult["PROPERTY_LIST_FULL"][$propertyID]["GetPublicEditHTML"])
                                $INPUT_TYPE = "USER_TYPE";
                            else
                                $INPUT_TYPE = $arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"];
                            
                            // СВОЙСТВА ТИПА HTML  УВОДИМ В CASE HTML
                            if ($arResult["PROPERTY_LIST_FULL"][$propertyID]["USER_TYPE"] == "HTML") {
                                $INPUT_TYPE = "HTML";
                            }

                            switch ($INPUT_TYPE):
                                case "USER_TYPE":
                                    for ($i = 0;
                                            $i < $inputNum;
                                            $i++) {
                                        if ($arParams["ID"] > 0 || count($arResult["ERRORS"])
                                                > 0) {
                                            $value = intval($propertyID) > 0 ? $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["~VALUE"]
                                                        : $arResult["ELEMENT"][$propertyID];
                                            $description = intval($propertyID) > 0
                                                        ? $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["DESCRIPTION"]
                                                        : "";
                                        } elseif ($i == 0) {
                                            $value = intval($propertyID) <= 0 ? ""
                                                        : $arResult["PROPERTY_LIST_FULL"][$propertyID]["DEFAULT_VALUE"];
                                            $description = "";
                                        } else {
                                            $value = "";
                                            $description = "";
                                        }
                                        echo call_user_func_array($arResult["PROPERTY_LIST_FULL"][$propertyID]["GetPublicEditHTML"],
                                                array(
                                            $arResult["PROPERTY_LIST_FULL"][$propertyID],
                                            array(
                                                "VALUE" => $value,
                                                "DESCRIPTION" => $description,
                                            ),
                                            array(
                                                "VALUE" => "PROPERTY[" . $propertyID . "][" . $i . "][VALUE]",
                                                "DESCRIPTION" => "PROPERTY[" . $propertyID . "][" . $i . "][DESCRIPTION]",
                                                "FORM_NAME" => "iblock_add",
                                            ),
                                        ));
                                        ?><?
                                    }

                                    if ($arResult['PROPERTY_LIST_FULL'][$propertyID]["PROPERTY_TYPE"] == "E" && $i > 1) {?>
                                    <div class="button-area mt-10 text-right"><button id="add-select-<?= $propertyID?>" data-select-num="<?= $i?>" type="button" class="btn btn-primary">Добавить</button></div>
                                    <script>
                                    (function ($) {

                                        var elements = [];

                                        <?
                                            if ($arResult['PROPERTY_LIST_FULL'][$propertyID]["LINK_IBLOCK_ID"]) {

                                                $db_res = CIBlockElement::GetList(array("NAME" => "ASC"), array("IBLOCK_ID" => $arResult['PROPERTY_LIST_FULL'][$propertyID]["LINK_IBLOCK_ID"],
                                                    "ACTIVE" => "Y"), false, false, array("ID", "NAME"));

                                                while ($res = $db_res->Fetch()) {
                                                    ?>elements.push(<?=\Bitrix\Main\Web\Json::encode($res)?>);<?
                                                }
                                            }
                                        ?>

                                       if (elements.length) {

                                           $("#add-select-<?= $propertyID?>").on("click", function () {

                                                var selectNum = $(this).data("select-num"), html, k, cnt = elements.length;

                                                html = "<select id='<?= $propertyID?>_"+selectNum+"' name='PROPERTY[<?= $propertyID?>][" + selectNum +"][VALUE]' class='select'>";

                                                html += "<option selected>(не установлено)</option>";

                                                for (k = 0; k < cnt; k++) {
                                                    html += "<option value='"+elements[k].ID+"'>"+elements[k].NAME+"</option>";
                                                }

                                                html += "</select>";

                                                $(this).parent().before(html);

                                                $("#<?= $propertyID?>_"+selectNum+"").select2();

                                                $(this).data("select-num", (Number(selectNum) + 1));
                                                
                                           });

                                       }

                                    })(jQuery);
                                    </script>
                                    <?}
                                    
                                    ?></div><!-- end form-group--><?
                                    break;
                                case "TAGS":
                                    $APPLICATION->IncludeComponent(
                                            "bitrix:search.tags.input", "",
                                            array(
                                        "VALUE" => $arResult["ELEMENT"][$propertyID],
                                        "NAME" => "PROPERTY[" . $propertyID . "][0]",
                                        "TEXT" => 'size="' . $arResult["PROPERTY_LIST_FULL"][$propertyID]["COL_COUNT"] . '"',
                                            ), null, array("HIDE_ICONS" => "Y")
                                    );
                                    ?></div><!-- end form-group--><?
                                    break;
                                case "HTML":
                                    for ($i = 0;
                                            $i < $inputNum;
                                            $i++) {
                                        $LHE = new CHTMLEditor;
                                        $LHE->Show(array(
                                            'name' => "PROPERTY[" . $propertyID . "][".$i."]",
                                            'id' => preg_replace("/[^a-z0-9]/i", '',
                                                    "PROPERTY[" . $propertyID . "][".$i."]"),
                                            'inputName' => "PROPERTY[" . $propertyID . "][".$i."]",
                                            'content' => $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["~VALUE"]["TEXT"],
                                            'width' => '100%',
                                            'minBodyWidth' => 350,
                                            'normalBodyWidth' => 555,
                                            'height' => "75",
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
                                    }
                                    ?></div><!-- end form-group--><?
                                    break;
                                case "T":
                                    for ($i = 0;
                                            $i < $inputNum;
                                            $i++) {

                                        if ($arParams["ID"] > 0 || count($arResult["ERRORS"])
                                                > 0) {
                                            $value = intval($propertyID) > 0 ? $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["VALUE"]
                                                        : $arResult["ELEMENT"][$propertyID];
                                        } elseif ($i == 0) {
                                            $value = intval($propertyID) > 0 ? ""
                                                        : $arResult["PROPERTY_LIST_FULL"][$propertyID]["DEFAULT_VALUE"];
                                        } else {
                                            $value = "";
                                        }
                                        ?>
    <textarea class="form-control" cols="<?= $arResult["PROPERTY_LIST_FULL"][$propertyID]["COL_COUNT"] ?>" rows="<?= $arResult["PROPERTY_LIST_FULL"][$propertyID]["ROW_COUNT"] ?>" name="PROPERTY[<?= $propertyID ?>][<?= $i ?>]"><?= $value ?></textarea>
                                        <?
                                    }
                                    ?></div><!-- end form-group--><?
                                    break;

                                case "S":
                                case "N":
                                    for ($i = 0;
                                            $i < $inputNum;
                                            $i++) {
                                        if ($arParams["ID"] > 0 || count($arResult["ERRORS"])
                                                > 0) {
                                            $value = intval($propertyID) > 0 ? $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["VALUE"]
                                                        : $arResult["ELEMENT"][$propertyID];
                                        } elseif ($i == 0) {
                                            $value = intval($propertyID) <= 0 ? ""
                                                        : $arResult["PROPERTY_LIST_FULL"][$propertyID]["DEFAULT_VALUE"];
                                        } else {
                                            $value = "";
                                        }
                                        ?>
    <input class="form-control" type="text" name="PROPERTY[<?= $propertyID ?>][<?= $i ?>]" size="25" value="<?= $value ?>" /><br /><? if ($arResult["PROPERTY_LIST_FULL"][$propertyID]["USER_TYPE"]
                                                == "DateTime"): ?><?
                                            $APPLICATION->IncludeComponent(
                                                    'bitrix:main.calendar', '',
                                                    array(
                                                'FORM_NAME' => 'iblock_add',
                                                'INPUT_NAME' => "PROPERTY[" . $propertyID . "][" . $i . "]",
                                                'INPUT_VALUE' => $value,
                                                    ), null,
                                                    array('HIDE_ICONS' => 'Y')
                                            );
                                            ?><br /><small><?= GetMessage("IBLOCK_FORM_DATE_FORMAT") ?><?= FORMAT_DATETIME ?></small><?
                    endif
                                        ?><?
                                    }
                                    ?></div><!-- end form-group--><?
                                    break;

                                case "F":
                                    for ($i = 0;
                                            $i < $inputNum;
                                            $i++) {
                                                ?><div class="form-group"><?
                                        $value = intval($propertyID) > 0 ? $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["VALUE"]
                                                    : $arResult["ELEMENT"][$propertyID];
                                        ?>
                                        <input type="hidden" name="PROPERTY[<?= $propertyID ?>][<?= $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["VALUE_ID"]
                                ? $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["VALUE_ID"]
                                : $i ?>]" value="<?= $value ?>" />
                                        <input class="file-styled" type="file" size="<?= $arResult["PROPERTY_LIST_FULL"][$propertyID]["COL_COUNT"] ?>"  name="PROPERTY_FILE_<?= $propertyID ?>_<?= $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["VALUE_ID"]
                                ? $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["VALUE_ID"]
                                : $i ?>" /><br />
                                        <?
                                        if (!empty($value) && is_array($arResult["ELEMENT_FILES"][$value])) {
                                            ?>
                                             <div class="checkbox">
                                            <input type="checkbox" name="DELETE_FILE[<?= $propertyID ?>][<?= $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["VALUE_ID"]
                                    ? $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["VALUE_ID"]
                                                    : $i ?>]" id="file_delete_<?= $propertyID ?>_<?= $i ?>" value="Y" /><label for="file_delete_<?= $propertyID ?>_<?= $i ?>"><?= GetMessage("IBLOCK_FORM_FILE_DELETE") ?></label></div>
                                            <?
                                            if ($arResult["ELEMENT_FILES"][$value]["IS_IMAGE"]) {
                                                ?>
                                                <img src="<?= $arResult["ELEMENT_FILES"][$value]["SRC"] ?>" height="150<?//= $arResult["ELEMENT_FILES"][$value]["HEIGHT"] ?>" width="150<?//= $arResult["ELEMENT_FILES"][$value]["WIDTH"] ?>" border="0" /><br />
                                                <?
                                            } else {
                                                ?>
                                                <?= GetMessage("IBLOCK_FORM_FILE_NAME") ?>: <?= $arResult["ELEMENT_FILES"][$value]["ORIGINAL_NAME"] ?><br />
                                                <?= GetMessage("IBLOCK_FORM_FILE_SIZE") ?>: <?= $arResult["ELEMENT_FILES"][$value]["FILE_SIZE"] ?> b<br />
                                                [<a href="<?= $arResult["ELEMENT_FILES"][$value]["SRC"] ?>"><?= GetMessage("IBLOCK_FORM_FILE_DOWNLOAD") ?></a>]<br />
                                                <?
                                            }
                                        }
                                        ?></div><!-- end form-group --><?
                                    }
                                    ?></div><!-- end form-group--><?
                                    break;
                                case "L":

                                    if ($arResult["PROPERTY_LIST_FULL"][$propertyID]["LIST_TYPE"]
                                            == "C")
                                        $type = $arResult["PROPERTY_LIST_FULL"][$propertyID]["MULTIPLE"]
                                                == "Y" ? "checkbox" : "radio";
                                    else
                                        $type = $arResult["PROPERTY_LIST_FULL"][$propertyID]["MULTIPLE"]
                                                == "Y" ? "multiselect" : "dropdown";

                                    switch ($type):
                                        case "checkbox":
                                        case "radio":
                                            foreach ($arResult["PROPERTY_LIST_FULL"][$propertyID]["ENUM"] as
                                                    $key =>
                                                    $arEnum) {
                                                $checked = false;
                                                if ($arParams["ID"] > 0 || count($arResult["ERRORS"])
                                                        > 0) {
                                                    if (is_array($arResult["ELEMENT_PROPERTIES"][$propertyID])) {
                                                        foreach ($arResult["ELEMENT_PROPERTIES"][$propertyID] as
                                                                $arElEnum) {
                                                            if ($arElEnum["VALUE"]
                                                                    == $key) {
                                                                $checked = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    if ($arEnum["DEF"] == "Y")
                                                        $checked = true;
                                                }
                                                ?>
                                                <div class="checkbox">
                                                <input class="form-control" type="<?= $type ?>" name="PROPERTY[<?= $propertyID ?>]<?= $type
                            == "checkbox" ? "[" . $key . "]" : "" ?>" value="<?= $key ?>" id="property_<?= $key ?>"<?= $checked
                                                        ? " checked=\"checked\"" : "" ?> /><label for="property_<?= $key ?>"><?= $arEnum["VALUE"] ?></label><br /></div>
                                                    <?
                                                }
                                                break;

                                            case "dropdown":
                                        case "multiselect":
                                                ?>
                                                <select class="select" name="PROPERTY[<?= $propertyID ?>]<?= $type == "multiselect"
                                    ? "[]\" size=\"" . $arResult["PROPERTY_LIST_FULL"][$propertyID]["ROW_COUNT"] . "\" multiple=\"multiple"
                                    : "" ?>">
                                                <option value=""><? echo GetMessage("CT_BIEAF_PROPERTY_VALUE_NA") ?></option>
                                            <?
                                            if (intval($propertyID) > 0)
                                                $sKey = "ELEMENT_PROPERTIES";
                                            else
                                                $sKey = "ELEMENT";

                                            foreach ($arResult["PROPERTY_LIST_FULL"][$propertyID]["ENUM"] as
                                                    $key =>
                                                    $arEnum) {
                                                $checked = false;
                                                if ($arParams["ID"] > 0 || count($arResult["ERRORS"])
                                                        > 0) {
                                                    foreach ($arResult[$sKey][$propertyID] as
                                                            $elKey =>
                                                            $arElEnum) {
                                                        if ($key == $arElEnum["VALUE"]) {
                                                            $checked = true;
                                                            break;
                                                        }
                                                    }
                                                } else {
                                                    if ($arEnum["DEF"] == "Y")
                                                        $checked = true;
                                                }
                                                ?>
                                                    <option value="<?= $key ?>" <?= $checked ? " selected=\"selected\""
                                                            : "" ?>><?= $arEnum["VALUE"] ?></option>
                            <?
                        }
                        ?>
                                            </select>
                        <?
                        break;

                endswitch;
                ?></div><!-- end form-group--><?
                break;
        endswitch;
        ?>
                       
    <? endforeach; ?>
</div>
    <? endforeach; ?>
</div>
    <?if ($arParams["USE_CAPTCHA"] == "Y" && $arParams["ID"] <= 0): ?>
                <div class="row">
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                                <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
                                <img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" class="captcha-img" alt="CAPTCHA" />
                        </div>
                    </div>
                    <div class="col-md-8 col-sm-8">
                        <div class="form-group">
                                <input required type="text" name="captcha_word" maxlength="250" value="" class="form-control" placeholder="<?=GetMessage("IBLOCK_FORM_CAPTCHA_PROMPT")?>">
                        </div>
                    </div>
                </div>
    <? endif?>
           
<? endif ?>
        <div class="mt-20">
<button class="btn btn-primary" type="submit" name="iblock_submit" value="<?= GetMessage("IBLOCK_FORM_SUBMIT") ?>" ><?= GetMessage("IBLOCK_FORM_SUBMIT") ?></button>
<? if (strlen($arParams["LIST_URL"]) > 0): ?>
<button class="btn btn-primary" type="submit" name="iblock_apply" value="<?= GetMessage("IBLOCK_FORM_APPLY") ?>" ><?= GetMessage("IBLOCK_FORM_APPLY") ?></button>
<button class="btn btn-danger"
    type="button"
    name="iblock_cancel"
    value="<? echo GetMessage('IBLOCK_FORM_CANCEL'); ?>"
    onclick="location.href='<? echo CUtil::JSEscape($arParams["LIST_URL"]) ?>';"
    ><? echo GetMessage('IBLOCK_FORM_CANCEL'); ?></button></div>
<? endif ?>
                </div>
</div>
        </div>
    </div>
</form>

<script>
((function ($) {
    $('select[name^="PROPERTY"]').addClass('select');
    $('input[name^="bx_address_map"], input[name^="point_map_"]').addClass('form-control').css({display: 'inline-block'});
})(jQuery))   
</script>