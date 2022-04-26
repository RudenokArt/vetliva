<?
/**
 * @global CMain $APPLICATION
 * @param array $arParams
 * @param array $arResult
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

use \Bitrix\Main\Localization\Loc;
?>
<div class="panel panel-flat">
    <div class="panel-body">
        <div class="my-profile">

            <? ShowError($arResult["strProfileError"]); ?>
            <?
            if ($arResult['DATA_SAVED'] == 'Y')
                ShowNote(GetMessage('PROFILE_DATA_SAVED'));
                unset($_SESSION["__TRAVELOSFT"]["PARTNERS_NDS"]);
            ?>
            <script type="text/javascript">
                <!--
            var opened_sections = [<?
            $arResult["opened"] = $_COOKIE[$arResult["COOKIE_PREFIX"] . "_user_profile_open"];
            $arResult["opened"] = preg_replace("/[^a-z0-9_,]/i", "", $arResult["opened"]);
            if (strlen($arResult["opened"]) > 0) {
                echo "'" . implode("', '", explode(",", $arResult["opened"])) . "'";
            } else {
                $arResult["opened"] = "reg";
                echo "'reg'";
            }
            ?>];
                //-->

                var cookie_prefix = '<?= $arResult["COOKIE_PREFIX"] ?>';
                
                function viewPassword(element) {
                    if (element.closest('.form-group').find('input').attr('type')=='password') {
                        element.closest('.form-group').find('input').attr('type','text');
                        element.attr('class', 'icon-eye text-muted');
                    }
                    else {
                        element.closest('.form-group').find('input').attr('type','password');
                        element.attr('class', 'icon-eye-blocked text-muted');
                    }
                }
                function check_pass() {
                     var pass = $('#pass').val();
                     if (pass!='') {
                         var formData = new FormData(); 
                         formData.append('pass', pass);
                         var xhrCustom = new XMLHttpRequest();
                         xhrCustom.open("POST", "<?=$templateFolder?>/ajax.php");
                        
                         xhrCustom.onreadystatechange = function() {
                             if (xhrCustom.readyState == 4 && xhrCustom.status == 200) {
                                 var dataCheck = xhrCustom.responseText;
                                 var resultCustom = jQuery.parseJSON( dataCheck);
                                 if (resultCustom.length==0) {
                                    jQuery("input[name='passwordcorret']").val('Y');
                                    $('.passerror-container').html('');
                                    $('.passerror-container').hide();
                                 }
                                 else {
                                    jQuery("input[name='passwordcorret']").val('N');
                                    var errortext = '<ul>';
                                    for (j = 0; j < resultCustom.length; j++) {
                                     errortext+='<li>'+resultCustom[j]+'</li>';
                                     }
                                    errortext+='</ul>';
                                    $('.passerror-container').html(errortext);
                                    $('.passerror-container').show();
                                 }
                             }
                         };
                         xhrCustom.send(formData);
                     }
                }
            </script>

            <form method="post" name="form1" id="reg-form" action="<?= $arResult["FORM_TARGET"] ?>" enctype="multipart/form-data">
                <input name="save" type="hidden" value="save">
<?= $arResult["BX_SESSION_CHECK"] ?>
                <input type="hidden" name="lang" value="<?= LANG ?>" />
                <input type="hidden" name="ID" value=<?= $arResult["ID"] ?> />
                <input type="hidden" name="LOGIN" value="<? echo $arResult["arUser"]["LOGIN"] ?>" />
                <input type="hidden" name="passwordcorret" value="Y">
                <div class="form-group">
                    <? echo GetMessage("USER_ID") ?>
                    <span class="ts-fs"><?=$arResult["ID"]?></span>
                </div>

                <?php if($arResult['arUser']['IS_GUIDE']):?>

                    <br>

                    <div class="panel panel-default">

                        <div class="panel-body">

                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a data-toggle="tab" href="#language-ru"><?= Loc::getMessage('T_USER_PROFILE_LANGUAGE_RU')?></a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#language-by"><?= Loc::getMessage('T_USER_PROFILE_LANGUAGE_BY')?></a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#language-en"><?= Loc::getMessage('T_USER_PROFILE_LANGUAGE_EN')?></a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div id="language-ru" class="tab-pane fade in active">

                                    <div class="form-group">
                                        <label><?= Loc::getMessage('NAME') ?></label>
                                        <input class="form-control" type="text" name="NAME" maxlength="50" value="<?= $arResult["arUser"]["NAME"] ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label><?= Loc::getMessage('LAST_NAME') ?></label>
                                        <input class="form-control" type="text" name="LAST_NAME" maxlength="50" value="<?= $arResult["arUser"]["LAST_NAME"] ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label><?= Loc::getMessage('SECOND_NAME') ?></label>
                                        <input class="form-control" type="text" name="SECOND_NAME" maxlength="50" value="<?= $arResult["arUser"]["SECOND_NAME"] ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label><?=Loc::getMessage('T_USER_PROFILE_ABOUT_SELF')?></label>
                                        <?php
                                        $inputName = 'ABOUT_SELF';
                                        $LHE = new \CHTMLEditor;
                                        $LHE->Show([
                                            'name' => $inputName,
                                            'id' => $inputName,
                                            'inputName' => $inputName,
                                            'content' => $arResult['GUIDE']['PROPERTIES']['ABOUT_SELF']['~VALUE']['TEXT'],
                                            'width' => '100%',
                                            'minBodyWidth' => 350,
                                            'normalBodyWidth' => 555,
                                            'height' => "75",
                                            'bAllowPhp' => false,
                                            'limitPhpAccess' => false,
                                            'autoResize' => true,
                                            'setFocusAfterShow' => false,
                                            'autoResizeOffset' => 40,
                                            'useFileDialogs' => false,
                                            'saveOnBlur' => false,
                                            'showTaskbars' => false,
                                            'showNodeNavi' => false,
                                            'askBeforeUnloadPage' => false,
                                            'bbCode' => false,
                                            'siteId' => SITE_ID,
                                            'controlsMap' => [
                                                ['id' => 'Bold', 'compact' => true, 'sort' => 80],
                                                ['id' => 'Italic', 'compact' => true, 'sort' => 90],
                                                ['id' => 'Underline', 'compact' => true, 'sort' => 100],
                                                ['id' => 'Strikeout', 'compact' => true, 'sort' => 110],
                                                ['id' => 'RemoveFormat', 'compact' => true, 'sort' => 120],
                                                ['id' => 'Color', 'compact' => true, 'sort' => 130],
                                                ['id' => 'FontSelector', 'compact' => false, 'sort' => 135],
                                                ['id' => 'FontSize', 'compact' => false, 'sort' => 140],
                                                ['separator' => true, 'compact' => false, 'sort' => 145],
                                                ['id' => 'OrderedList', 'compact' => true, 'sort' => 150],
                                                ['id' => 'UnorderedList', 'compact' => true, 'sort' => 160],
                                                ['id' => 'AlignList', 'compact' => false, 'sort' => 190],
                                                ['separator' => true, 'compact' => false, 'sort' => 200],
                                                ['id' => 'InsertLink', 'compact' => true, 'sort' => 210],
                                                ['id' => 'InsertImage', 'compact' => false, 'sort' => 220],
                                                ['id' => 'InsertVideo', 'compact' => true, 'sort' => 230],
                                                ['id' => 'InsertTable', 'compact' => false, 'sort' => 250],
                                                ['separator' => true, 'compact' => false, 'sort' => 290],
                                                ['id' => 'Fullscreen', 'compact' => false, 'sort' => 310],
                                                ['id' => 'More', 'compact' => true, 'sort' => 400]
                                            ],
                                        ]);
                                        ?>
                                    </div>

                                    <div class="form-group">
                                        <label><?= Loc::getMessage('T_USER_PROFILE_VIDEO_YOUTUBE') ?></label>
                                        <input class="form-control" type="text" name="YOUTUBE" maxlength="50" value="<?= $arResult['GUIDE']['PROPERTIES']['YOUTUBE']['VALUE'] ?>" />
                                    </div>

                                    <div class="form-group">
                                        <label><?= Loc::getMessage('T_USER_PROFILE_VIDEO_VIMEO') ?></label>
                                        <input class="form-control" type="text" name="VIMEO" maxlength="50" value="<?= $arResult['GUIDE']['PROPERTIES']['VIMEO']['VALUE'] ?>" />
                                    </div>

                                </div>
                                <div id="language-by" class="tab-pane fade">

                                    <div class="form-group">
                                        <label><?= Loc::getMessage('NAME') ?></label>
                                        <input class="form-control" type="text" name="FIRST_NAME_BY" maxlength="50" value="<?= $arResult['GUIDE']['PROPERTIES']['FIRST_NAME_BY']['VALUE'] ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label><?= Loc::getMessage('LAST_NAME') ?></label>
                                        <input class="form-control" type="text" name="LAST_NAME_BY" maxlength="50" value="<?= $arResult['GUIDE']['PROPERTIES']['LAST_NAME_BY']['VALUE'] ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label><?= Loc::getMessage('SECOND_NAME') ?></label>
                                        <input class="form-control" type="text" name="SECOND_NAME_BY" maxlength="50" value="<?= $arResult['GUIDE']['PROPERTIES']['SECOND_NAME_BY']['VALUE'] ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label><?=Loc::getMessage('T_USER_PROFILE_ABOUT_SELF')?></label>
                                        <?php
                                        $inputName = 'ABOUT_SELF_BY';
                                        $LHE = new \CHTMLEditor;
                                        $LHE->Show([
                                            'name' => $inputName,
                                            'id' => $inputName,
                                            'inputName' => $inputName,
                                            'content' => $arResult['GUIDE']['PROPERTIES']['ABOUT_SELF_BY']['~VALUE']['TEXT'],
                                            'width' => '100%',
                                            'minBodyWidth' => 350,
                                            'normalBodyWidth' => 555,
                                            'height' => "75",
                                            'bAllowPhp' => false,
                                            'limitPhpAccess' => false,
                                            'autoResize' => true,
                                            'setFocusAfterShow' => false,
                                            'autoResizeOffset' => 40,
                                            'useFileDialogs' => false,
                                            'saveOnBlur' => false,
                                            'showTaskbars' => false,
                                            'showNodeNavi' => false,
                                            'askBeforeUnloadPage' => false,
                                            'bbCode' => false,
                                            'siteId' => SITE_ID,
                                            'controlsMap' => [
                                                ['id' => 'Bold', 'compact' => true, 'sort' => 80],
                                                ['id' => 'Italic', 'compact' => true, 'sort' => 90],
                                                ['id' => 'Underline', 'compact' => true, 'sort' => 100],
                                                ['id' => 'Strikeout', 'compact' => true, 'sort' => 110],
                                                ['id' => 'RemoveFormat', 'compact' => true, 'sort' => 120],
                                                ['id' => 'Color', 'compact' => true, 'sort' => 130],
                                                ['id' => 'FontSelector', 'compact' => false, 'sort' => 135],
                                                ['id' => 'FontSize', 'compact' => false, 'sort' => 140],
                                                ['separator' => true, 'compact' => false, 'sort' => 145],
                                                ['id' => 'OrderedList', 'compact' => true, 'sort' => 150],
                                                ['id' => 'UnorderedList', 'compact' => true, 'sort' => 160],
                                                ['id' => 'AlignList', 'compact' => false, 'sort' => 190],
                                                ['separator' => true, 'compact' => false, 'sort' => 200],
                                                ['id' => 'InsertLink', 'compact' => true, 'sort' => 210],
                                                ['id' => 'InsertImage', 'compact' => false, 'sort' => 220],
                                                ['id' => 'InsertVideo', 'compact' => true, 'sort' => 230],
                                                ['id' => 'InsertTable', 'compact' => false, 'sort' => 250],
                                                ['separator' => true, 'compact' => false, 'sort' => 290],
                                                ['id' => 'Fullscreen', 'compact' => false, 'sort' => 310],
                                                ['id' => 'More', 'compact' => true, 'sort' => 400]
                                            ],
                                        ]);
                                        ?>
                                    </div>

                                    <div class="form-group">
                                        <label><?= Loc::getMessage('T_USER_PROFILE_VIDEO_YOUTUBE') ?></label>
                                        <input class="form-control" type="text" name="YOUTUBE_BY" maxlength="50" value="<?= $arResult['GUIDE']['PROPERTIES']['YOUTUBE_BY']['VALUE'] ?>" />
                                    </div>

                                    <div class="form-group">
                                        <label><?= Loc::getMessage('T_USER_PROFILE_VIDEO_VIMEO') ?></label>
                                        <input class="form-control" type="text" name="VIMEO_BY" maxlength="50" value="<?= $arResult['GUIDE']['PROPERTIES']['VIMEO_BY']['VALUE'] ?>" />
                                    </div>

                                </div>
                                <div id="language-en" class="tab-pane fade">

                                    <div class="form-group">
                                        <label><?= Loc::getMessage('NAME') ?></label>
                                        <input class="form-control" type="text" name="FIRST_NAME_EN" maxlength="50" value="<?= $arResult['GUIDE']['PROPERTIES']['FIRST_NAME_EN']['VALUE'] ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label><?= Loc::getMessage('LAST_NAME') ?></label>
                                        <input class="form-control" type="text" name="LAST_NAME_EN" maxlength="50" value="<?= $arResult['GUIDE']['PROPERTIES']['LAST_NAME_EN']['VALUE'] ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label><?= Loc::getMessage('SECOND_NAME') ?></label>
                                        <input class="form-control" type="text" name="SECOND_NAME_EN" maxlength="50" value="<?= $arResult['GUIDE']['PROPERTIES']['SECOND_NAME_EN']['VALUE'] ?>" />
                                    </div>
                                    <div class="form-group">
                                        <label><?=Loc::getMessage('T_USER_PROFILE_ABOUT_SELF')?></label>
                                        <?php
                                        $inputName = 'ABOUT_SELF_EN';
                                        $LHE = new \CHTMLEditor;
                                        $LHE->Show([
                                            'name' => $inputName,
                                            'id' => $inputName,
                                            'inputName' => $inputName,
                                            'content' => $arResult['GUIDE']['PROPERTIES']['ABOUT_SELF_EN']['~VALUE']['TEXT'],
                                            'width' => '100%',
                                            'minBodyWidth' => 350,
                                            'normalBodyWidth' => 555,
                                            'height' => "75",
                                            'bAllowPhp' => false,
                                            'limitPhpAccess' => false,
                                            'autoResize' => true,
                                            'setFocusAfterShow' => false,
                                            'autoResizeOffset' => 40,
                                            'useFileDialogs' => false,
                                            'saveOnBlur' => false,
                                            'showTaskbars' => false,
                                            'showNodeNavi' => false,
                                            'askBeforeUnloadPage' => false,
                                            'bbCode' => false,
                                            'siteId' => SITE_ID,
                                            'controlsMap' => [
                                                ['id' => 'Bold', 'compact' => true, 'sort' => 80],
                                                ['id' => 'Italic', 'compact' => true, 'sort' => 90],
                                                ['id' => 'Underline', 'compact' => true, 'sort' => 100],
                                                ['id' => 'Strikeout', 'compact' => true, 'sort' => 110],
                                                ['id' => 'RemoveFormat', 'compact' => true, 'sort' => 120],
                                                ['id' => 'Color', 'compact' => true, 'sort' => 130],
                                                ['id' => 'FontSelector', 'compact' => false, 'sort' => 135],
                                                ['id' => 'FontSize', 'compact' => false, 'sort' => 140],
                                                ['separator' => true, 'compact' => false, 'sort' => 145],
                                                ['id' => 'OrderedList', 'compact' => true, 'sort' => 150],
                                                ['id' => 'UnorderedList', 'compact' => true, 'sort' => 160],
                                                ['id' => 'AlignList', 'compact' => false, 'sort' => 190],
                                                ['separator' => true, 'compact' => false, 'sort' => 200],
                                                ['id' => 'InsertLink', 'compact' => true, 'sort' => 210],
                                                ['id' => 'InsertImage', 'compact' => false, 'sort' => 220],
                                                ['id' => 'InsertVideo', 'compact' => true, 'sort' => 230],
                                                ['id' => 'InsertTable', 'compact' => false, 'sort' => 250],
                                                ['separator' => true, 'compact' => false, 'sort' => 290],
                                                ['id' => 'Fullscreen', 'compact' => false, 'sort' => 310],
                                                ['id' => 'More', 'compact' => true, 'sort' => 400]
                                            ],
                                        ]);
                                        ?>
                                    </div>

                                    <div class="form-group">
                                        <label><?= Loc::getMessage('T_USER_PROFILE_VIDEO_YOUTUBE') ?></label>
                                        <input class="form-control" type="text" name="YOUTUBE_EN" maxlength="50" value="<?= $arResult['GUIDE']['PROPERTIES']['YOUTUBE_EN']['VALUE'] ?>" />
                                    </div>

                                    <div class="form-group">
                                        <label><?= Loc::getMessage('T_USER_PROFILE_VIDEO_VIMEO') ?></label>
                                        <input class="form-control" type="text" name="VIMEO_EN" maxlength="50" value="<?= $arResult['GUIDE']['PROPERTIES']['VIMEO_EN']['VALUE'] ?>" />
                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="form-group">
                        <label><?= Loc::getMessage('T_USER_PROFILE_BIRTHDAY') ?> (<?= $arResult["DATE_FORMAT"] ?>)</label>
                        <input class="form-control" type="text" name="PERSONAL_BIRTHDAY" value="<?= $arResult["arUser"]["PERSONAL_BIRTHDAY"] ?>" />
                    </div>

                    <div class="form-group">
                        <label><?=Loc::getMessage('T_USER_PROFILE_CERTIFICATION')?></label>
                        <textarea class="form-control" rows="3" name="CERTIFICATION"><?= $arResult['GUIDE']['PROPERTIES']['CERTIFICATION']['VALUE']?></textarea>
                    </div>

                    <div class="form-group">
                        <label><?= GetMessage("T_USER_PROFILE_PHOTO") ?></label>
                        <?= $arResult["arUser"]["PERSONAL_PHOTO_INPUT"] ?>
                        <?
                        if (strlen($arResult["arUser"]["PERSONAL_PHOTO"]) > 0) {
                            ?>
                            <br />
                            <?= $arResult["arUser"]["PERSONAL_PHOTO_HTML"] ?>
                            <?
                        }
                        ?>
                    </div>

                    <div class="form-group">
                        <label><?=Loc::getMessage('T_USER_PROFILE_GALLERY')?></label>
                        <?php
                        $input = [];
                        foreach($arResult['GUIDE']['PROPERTIES']['PICTURES']['VALUE'] as $key => $value){
                            $input['PICTURES['.$key.']'] = $value;
                        }

                        echo \Bitrix\Main\UI\FileInput::createInstance([
                            'name' => 'PICTURES[n#IND#]',
                            'description' => false,
                            'upload' => true,
                            'allowUpload' => 'I',
                            'medialib' => false,
                            'fileDialog' => true,
                            'cloud' => false,
                            'delete' => true,
                            'edit' => true
                        ])->show($input);
                        ?>
                    </div>

                    <div class="form-group">
                        <label><?=Loc::getMessage('T_USER_PROFILE_RESIDENCE')?></label>
                        <select name="RESIDENCE" class="select">
                            <option value=""><?=Loc::getMessage('T_USER_PROFILE_EMPTY_SELECT')?></option>
                            <?php foreach($arResult['DATA']['LOCATIONS'] as $arItem):?>
                                <option value="<?=$arItem['ID']?>"<?=($arItem['ID'] == $arResult['GUIDE']['PROPERTIES']['RESIDENCE']['VALUE']) ? ' selected' : ''?>><?=$arItem['NAME']?></option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <input type="checkbox" name="TRANSPORT" id="user-profile-transport"<?=($arResult['GUIDE']['PROPERTIES']['TRANSPORT']['VALUE']) ? ' checked' : ''?> value="415">
                            <label for="user-profile-transport"><?=Loc::getMessage('T_USER_PROFILE_TRANSPORT')?></label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?=Loc::getMessage('T_USER_PROFILE_TOUR_LANGUAGE')?></label>
                        <select name="TOUR_LANGUAGE[]" class="select" multiple>
                            <?php foreach($arResult['DATA']['LANGUAGES'] as $arItem):?>
                                <option value="<?=$arItem['ID']?>"<?=(in_array($arItem['ID'], $arResult['GUIDE']['PROPERTIES']['TOUR_LANGUAGE']['VALUE'])) ? ' selected' : ''?>><?=$arItem['NAME']?></option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label><?=Loc::getMessage('T_USER_PROFILE_TOUR_TYPE')?></label>
                        <select name="TOUR_TYPE[]" class="select" multiple>
                            <?php foreach($arResult['DATA']['TOUR_TYPES'] as $arItem):?>
                                <option value="<?=$arItem['ID']?>"<?=(in_array($arItem['ID'], $arResult['GUIDE']['PROPERTIES']['TOUR_TYPE']['VALUE'])) ? ' selected' : ''?>><?=$arItem['NAME']?></option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label><?=Loc::getMessage('T_USER_PROFILE_PHONE')?></label>
                        <?php
                        $count = 0;
                        if(empty($arResult['GUIDE']['PROPERTIES']['PHONE']['VALUE'])){
                            ?>
                            <input type="tel" class="form-control" name="PHONE[0]">
                            <?php
                        }
                        else{
                            foreach($arResult['GUIDE']['PROPERTIES']['PHONE']['VALUE'] as $value){
                                echo '<input type="tel" class="form-control" name="PHONE['.$count.']" value="'.$value.'">';
                                $count++;
                            }
                        }
                        ?>
                        <div class="button-area mt-10 text-right">
                            <button type="button" aria-label="<?=Loc::getMessage('T_USER_PROFILE_BUTTON_ADD')?>" class="btn btn-primary button-add-input"><?=Loc::getMessage('T_USER_PROFILE_BUTTON_ADD')?></button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?= Loc::getMessage('EMAIL') ?><? if ($arResult["EMAIL_REQUIRED"]): ?><span class="starrequired">*</span><? endif ?></label>
                        <input class="form-control" type="text" name="EMAIL" maxlength="50" value="<? echo $arResult["arUser"]["EMAIL"] ?>" />
                    </div>

                    <div class="form-group">
                        <label><b>Форма налогооблажения</b></label>
                        <select style="width: 300px" class="form-control" name="UF_TAX_FORM">
                            <option value="">не установлено</option>
                            <?foreach (["10" => "с НДС", "11" => "без НДС"] as $id => $val):?>
                                <option value="<?= $id?>" <?if($id == $arResult["USER_PROPERTIES"]["DATA"]["UF_TAX_FORM"]["VALUE"]):?>selected<?endif?>><?= $val?></option>
                            <? endforeach;?>
                        </select>
                    </div>
                    <div <?if ($arResult["USER_PROPERTIES"]["DATA"]["UF_TAX_FORM"]["VALUE"] != 11):?>style="display: none;"<?endif?> class="form-group">
                        <label><b>Без НДС на основании</b></label>
                        <textarea class="form-control" name="UF_TAX_FORM_BASED"><?= htmlspecialchars($arResult["USER_PROPERTIES"]["DATA"]["UF_TAX_FORM_BASED"]["VALUE"])?></textarea>
                    </div>

                    <div class="form-group">
                        <label><?=Loc::getMessage('T_USER_PROFILE_TOUR_REGION')?></label>
                        <select name="TOUR_REGION[]" class="select" multiple>
                            <?php foreach($arResult['DATA']['REGIONS'] as $arItem):?>
                                <option value="<?=$arItem['ID']?>"<?=(in_array($arItem['ID'], $arResult['GUIDE']['PROPERTIES']['TOUR_REGION']['VALUE'])) ? ' selected' : ''?>><?=$arItem['NAME']?></option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label><?=Loc::getMessage('T_USER_PROFILE_TOUR_LOCATION')?></label>
                        <select name="TOUR_LOCATION[]" class="select" multiple>
                            <?php foreach($arResult['DATA']['LOCATIONS'] as $arItem):?>
                                <option value="<?=$arItem['ID']?>"<?=(in_array($arItem['ID'], $arResult['GUIDE']['PROPERTIES']['TOUR_LOCATION']['VALUE'])) ? ' selected' : ''?>><?=$arItem['NAME']?></option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label><?=Loc::getMessage('T_USER_PROFILE_TOUR_SIGHTS')?></label>
                        <select name="TOUR_SIGHTS[]" class="select" multiple>
                            <?php foreach($arResult['DATA']['SIGHTS'] as $arItem):?>
                                <option value="<?=$arItem['ID']?>"<?=(in_array($arItem['ID'], $arResult['GUIDE']['PROPERTIES']['TOUR_SIGHTS']['VALUE'])) ? ' selected' : ''?>><?=$arItem['NAME']?></option>
                            <?php endforeach ?>
                        </select>
                    </div>

                <? if ($arResult["arUser"]["EXTERNAL_AUTH_ID"] == ''): ?>
                <div class="form-group">
                    <label><?= GetMessage('NEW_PASSWORD_REQ') ?></label>
                    <input class="form-control" onchange="check_pass()" onkeyup="check_pass()"  id="pass" type="password" name="NEW_PASSWORD" maxlength="50" value="" autocomplete="off" class="bx-auth-input" />
                    <div class="passerror-container"></div>
                    <div class="form-control-feedback">
                        <i class="icon-eye-blocked text-muted" aria-hidden="true" onClick="viewPassword($(this))"></i>
                    </div>
                </div>
                    <div class="form-group">
                        <label><?= GetMessage('NEW_PASSWORD_CONFIRM') ?></label>
                        <input class="form-control" type="password" name="NEW_PASSWORD_CONFIRM" maxlength="50" value="" autocomplete="off" />
                        <div class="form-control-feedback">
                            <i class="icon-eye-blocked text-muted" aria-hidden="true" onClick="viewPassword($(this))"></i>
                        </div>
                    </div>
                <? endif ?>

                    <br>

                    <script>

                      $('body')
                        .on('click', '.button-add-input', function(){

                          var container = $(this).closest('.form-group'),
                            lastInput = container.find('input:last'),
                            inputName = lastInput.attr('name'),
                            inputNameMatches = inputName.match('\\[([^\\[\\]]+)\\]'),
                            inputNameClear = inputName.replace(inputNameMatches[0], ''),
                            currentIndex = inputNameMatches[1],
                            newInputName = inputNameClear + '[' +  ++currentIndex + ']',
                            newInput = lastInput.clone(),
                            button = $(this).parent().clone();

                          newInput.attr('name', newInputName);
                          newInput.val('');
                          $(this).parent().remove();
                          container.append(newInput, button);

                        })
                      ;

                    </script>

                <?php else:?>

                    <div class="form-group">
                        <label><?= GetMessage("USER_COMPANY") ?> <?= GetMessage("T_USER_PROFILE_ONLANGUAGE_RU") ?></label>
                        <input class="form-control" type="text" onchange="$('#UF_LEGAL_NAME').val($(this).val())" name="WORK_COMPANY" value="<?= $arResult["arUser"]["WORK_COMPANY"] ?>" />
                        <input class="form-control" type="hidden" id="UF_LEGAL_NAME" name="UF_LEGAL_NAME" value="<?= $arResult["arUser"]["UF_LEGAL_NAME"] ?>" />
                    </div>
                    <div class="form-group">
                        <label><?= GetMessage("USER_COMPANY") ?> <?= GetMessage("T_USER_PROFILE_ONLANGUAGE_EN") ?></label>
                        <input class="form-control" type="text" name="UF_LEGAL_NAME_EN" value="<?= $arResult["arUser"]["UF_LEGAL_NAME_EN"] ?>" />
                    </div>
                    <div class="form-group">
                        <label><?= GetMessage("USER_COMPANY") ?> <?= GetMessage("T_USER_PROFILE_ONLANGUAGE_BY") ?></label>
                        <input class="form-control" type="text" name="UF_LEGAL_NAME_BY" value="<?= $arResult["arUser"]["UF_LEGAL_NAME_BY"] ?>" />
                    </div>
                    <div class="form-group">
                        <label><?= GetMessage('NAME') ?></label>
                        <input class="form-control" type="text" name="NAME" maxlength="50" value="<?= $arResult["arUser"]["NAME"] ?>" />
                    </div>
                    <div class="form-group">
                        <label><?= GetMessage('LAST_NAME') ?></label>
                        <input class="form-control" type="text" name="LAST_NAME" maxlength="50" value="<?= $arResult["arUser"]["LAST_NAME"] ?>" />
                    </div>
                    <div class="form-group">
                        <label><?= GetMessage("USER_PHONE") ?></label>
                        <input class="form-control" type="text" name="WORK_PHONE" value="<?= $arResult["arUser"]["WORK_PHONE"] ?>" />
                    </div>
                    <div class="form-group">
                        <label><?= GetMessage('EMAIL') ?><? if ($arResult["EMAIL_REQUIRED"]): ?><span class="starrequired">*</span><? endif ?></label>
                        <input class="form-control" type="text" name="EMAIL" maxlength="50" value="<? echo $arResult["arUser"]["EMAIL"] ?>" />
                    </div>
                    <? if ($arResult["arUser"]["EXTERNAL_AUTH_ID"] == ''): ?>
                        <div class="form-group">
                            <label><?= GetMessage('NEW_PASSWORD_REQ') ?></label>
                            <input class="form-control" onchange="check_pass()" onkeyup="check_pass()"  id="pass" type="password" name="NEW_PASSWORD" maxlength="50" value="" autocomplete="new-password" class="bx-auth-input" />
                            <div class="passerror-container"></div>
                            <div class="form-control-feedback">
                                <i class="icon-eye-blocked text-muted" aria-hidden="true" onClick="viewPassword($(this))"></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?= GetMessage('NEW_PASSWORD_CONFIRM') ?></label>
                            <input class="form-control" type="password" name="NEW_PASSWORD_CONFIRM" maxlength="50" value="" autocomplete="off" />
                            <div class="form-control-feedback">
                                <i class="icon-eye-blocked text-muted" aria-hidden="true" onClick="viewPassword($(this))"></i>
                            </div>
                        </div>
                    <? endif ?>
                    <div class="form-group">
                        <label><b>Форма налогооблажения</b></label>
                        <select style="width: 300px" class="form-control" name="UF_TAX_FORM">
                            <option value="">не установлено</option>
                            <?foreach (["10" => "с НДС", "11" => "без НДС"] as $id => $val):?>
							<option value="<?= $id?>" <?if($id == $arResult["arUser"]["UF_TAX_FORM"]/*$arResult["USER_PROPERTIES"]["DATA"]["UF_TAX_FORM"]["VALUE"]*/):?>selected<?endif?>><?= $val?></option>
                            <? endforeach;?>
                        </select>
                    </div>
					<div <?if (/*$arResult["USER_PROPERTIES"]["DATA"]["UF_TAX_FORM"]["VALUE"]*/$arResult["arUser"]["UF_TAX_FORM"] != 11):?>style="display: none;"<?endif?> class="form-group">
                        <label><b>Без НДС на основании</b></label>
						<textarea class="form-control" name="UF_TAX_FORM_BASED"><?= htmlspecialchars(/*$arResult["USER_PROPERTIES"]["DATA"]["UF_TAX_FORM_BASED"]["VALUE"]*/$arResult["arUser"]["UF_TAX_FORM_BASED"])?></textarea>
                    </div>
    <!--                <div class="checkbox">
                            <input type="hidden" name="UF_NDS" value="0">
                            <label><input <?if ($arResult["arUser"]["UF_NDS"]):?>checked=""<?endif?> type="checkbox" name="UF_NDS" value="1"> <b>Использую НДС (для печати актов выполненных работ в разделе документы)</b></label>
                            <input class="form-control" type="text" name="PERSONAL_BIRTHDAY" value="<?= $arResult["arUser"]["PERSONAL_BIRTHDAY"] ?>" />

                        </div>-->
                        <?\Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools");
                        if (in_array(travelsoft\booking\Utils::getOpt("transfers_provider_group"), $GLOBALS["USER"]->GetUserGroupArray())) :

                        ?>
                    <div class="checkbox">
                            <label><b><? echo GetMessage("for_spot_payment") ?></b></label>
                            <input  class="form-control" type="hidden" name="UF_FOR_SPOT_PAYMENT" value="0" />
                            <input <?if($arResult["arUser"]["UF_FOR_SPOT_PAYMENT"] == 1):?>checked=""<?endif?> type="checkbox" name="UF_FOR_SPOT_PAYMENT" value="1" />
                        </div>
                    <?endif?>
                    <div class="form-group">
                        <label><?= GetMessage("USER_BIRTHDAY_DT") ?> (<?= $arResult["DATE_FORMAT"] ?>)</label>
                        <input class="form-control" type="text" name="PERSONAL_BIRTHDAY" value="<?= $arResult["arUser"]["PERSONAL_BIRTHDAY"] ?>" />

                    </div>
                        <div class="form-group">
                            <label><?= GetMessage("USER_PHOTO") ?></label>
    <?= $arResult["arUser"]["PERSONAL_PHOTO_INPUT"] ?>
    <?
    if (strlen($arResult["arUser"]["PERSONAL_PHOTO"]) > 0) {
        ?>
                                <br />
                                <?= $arResult["arUser"]["PERSONAL_PHOTO_HTML"] ?>
                                <?
                            }
                            ?>
                        </div>


    <? /*if ($arResult["TIME_ZONE_ENABLED"] == true): ?>
                            <li>
                            <? echo GetMessage("main_profile_time_zones") ?>
                            </li>
                            <li>
                                <span><? echo GetMessage("main_profile_time_zones_auto") ?></span>
                                <select name="AUTO_TIME_ZONE" onchange="this.form.TIME_ZONE.disabled = (this.value != 'N')">
                        <option value=""><? echo GetMessage("main_profile_time_zones_auto_def") ?></option>
                                            <option value="Y"<?= ($arResult["arUser"]["AUTO_TIME_ZONE"] == "Y" ? ' SELECTED="SELECTED"' : '') ?>><? echo GetMessage("main_profile_time_zones_auto_yes") ?></option>
                                            <option value="N"<?= ($arResult["arUser"]["AUTO_TIME_ZONE"] == "N" ? ' SELECTED="SELECTED"' : '') ?>><? echo GetMessage("main_profile_time_zones_auto_no") ?></option>
                                        </select>
                            </li>
                            <li>
                                <span><? echo GetMessage("main_profile_time_zones_zones") ?></span>
                                <select name="TIME_ZONE"<? if ($arResult["arUser"]["AUTO_TIME_ZONE"] <> "N") echo ' disabled="disabled"' ?>>
        <? foreach ($arResult["TIME_ZONE_LIST"] as $tz => $tz_name): ?>
                                        <option value="<?= htmlspecialcharsbx($tz) ?>"<?= ($arResult["arUser"]["TIME_ZONE"] == $tz ? ' SELECTED="SELECTED"' : '') ?>><?= htmlspecialcharsbx($tz_name) ?></option>
        <? endforeach ?>
                                </select>
                            </li>
                                <? endif */?>
                <?php endif?>

                <ul>
                    <?
                    if ($arResult["ID"] > 0) {
                        ?>
                        <?
                        if (strlen($arResult["arUser"]["TIMESTAMP_X"]) > 0) {
                            ?>
                            <li>
                                <span><?= GetMessage('LAST_UPDATE') ?></span>
                            <?= $arResult["arUser"]["TIMESTAMP_X"] ?>
                            </li>
                            <?
                        }
                        ?>
                            <?
                            if (strlen($arResult["arUser"]["LAST_LOGIN"]) > 0) {
                                ?>
                            <li>
                                <span><?= GetMessage('LAST_LOGIN') ?></span>
                            <?= $arResult["arUser"]["LAST_LOGIN"] ?>
                            </li>
                            <?
                        }
                        ?>
    <?
}
?>
                </ul>

                    <? // ******************** /User properties ***************************************************?>
                <p><? echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"]; ?></p>
                <p><input type="submit" name="save" class="btn btn-primary" value="<?= (($arResult["ID"] > 0) ? GetMessage("MAIN_SAVE") : GetMessage("MAIN_ADD")) ?>"></p>
            </form>
                    <?
                    /* if($arResult["SOCSERV_ENABLED"])
                      {
                      $APPLICATION->IncludeComponent("bitrix:socserv.auth.split", ".default", array(
                      "SHOW_PROFILES" => "Y",
                      "ALLOW_DELETE" => "Y"
                      ),
                      false
                      );
                      } */
                    ?>
        </div>
    </div>
</div>


            <? $APPLICATION->AddHeadScript("https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js", true); ?>
   
    <script>
        (function ($) {
            
            
            
            var $tax_form = $("select[name=UF_TAX_FORM]");
            function autosave() {
                var form = document.getElementById("reg-form");
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
            
            $tax_form.on("change", function () {
                if ($tax_form.val() == 11) {
                    $('textarea[name=UF_TAX_FORM_BASED]').parent().show();
                } else {
                    $('textarea[name=UF_TAX_FORM_BASED]').parent().hide();
                }
            });
    
            $("input[name='PERSONAL_BIRTHDAY']").mask("99.99.9999");
            
            $('form[name=form1]').on('submit', function (e) {
                if ($tax_form.val() == 11 && $('textarea[name=UF_TAX_FORM_BASED]').val() == "") {
                    e.preventDefault();
                    alert("Укажите основание для работы без ндс");
                    return;
                }
                if (jQuery("input[name='passwordcorret']").val()!='Y'  && $('#pass').val()!='') {
                     $([document.documentElement, document.body]).animate({
                        scrollTop: $("#pass").offset().top
                    }, 2000);
                    $("#pass").focus();
                    
                    return false;
                }
            });
            
        })(jQuery)
</script>