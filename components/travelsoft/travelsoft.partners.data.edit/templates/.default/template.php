<?php
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
?>

<div class="panel panel-flat">
    <div class="panel-body">
        <div class="text-left">
            <?if (!empty($arResult['ERRORS'])) {
                ShowError(implode("<br>", $arResult['ERRORS']));
            }
            
            if ($arResult['MESSOK']) {
                ShowMessage(array('TYPE' => "OK", "MESSAGE" => $arResult['MESSOK']));
            }?>
        </div>
        <div class="tabbable">
            <ul class="nav nav-tabs nav-justified">
                <?foreach ($arResult['FORM_FIELDS'] as $tab => $arTab):?>
                <li <?if( $arTab['active'] ):?>class="active"<?endif?>><a  href="#basic-justified-<?= $tab?>" data-toggle="tab"><?= $arTab['name']?></a></li>
                <?endforeach?>
            </ul>
            <form name="edit_provider" method="post" action="<?=$arResult["AUTH_URL"]?>">
                <?= bitrix_sessid_post()?>
                <input type="hidden" name="IS_PROVIDER_EDIT" value="Y" />
                <div class="tab-content">
                    <?foreach ($arResult['FORM_FIELDS'] as $tab => $arTab):?>
                        <div class="tab-pane <?if ($arTab['active']) :?>active<?endif?>" id="basic-justified-<?= $tab?>">
                            
                                <?foreach ($arTab['fields'] as $key => $arFields) :?>
                                <div class="row">
                                        <?foreach ($arFields as $name => $arFieldsVal):?>
                                        <div class="col-md-6">
                                                <div class="form-group">
                                                        <label for="<?= $name?>"><b><?= $arFieldsVal['name']?></b></label>
                                                        <?if ($arFieldsVal['type'] == "select"):?>
                                                        
                                                                <select <?if ($arFieldsVal['required']):?>required<?endif?> name="<?= $name?>" data-placeholder="<?= $arFieldsVal['placeholder']?>" class="select">
                                                                        <option></option>
                                                                        <?foreach ($arFieldsVal['default']['reference'] as $key => $value) :?>
                                                                        <option <?if ($arFieldsVal['default']['reference_id'][$key] == $arFieldsVal['value']):?>selected<?endif?> value="<?= $arFieldsVal['default']['reference_id'][$key]?>"><?= $value?></option>
                                                                        <?endforeach?>
                                                                </select>
                                                        
                                                        <?elseif ($arFieldsVal['type'] == "textarea"): ?>
                                                        <textarea
                                                            rows="5"
                                                            class="form-control"
                                                            name="<?= $name?>"
                                                            <?if ($arFieldsVal['required']):?>required<?endif?>
                                                            <?if ($arFieldsVal['pattern']):?>pattern="<?= $arFieldsVal['pattern']?>"<?endif?>
                                                            ><?= $arFieldsVal['value']?></textarea>
                                                        <?else:?>
                                                                <input
                                                                oninvalid="tabOpen(jQuery, '.nav-tabs a[href=\'#basic-justified-<?= $tab?>\']')"
                                                                <?if ($arFieldsVal['required']):?>required<?endif?>
                                                                <?if ($arFieldsVal['pattern']):?>pattern="<?= $arFieldsVal['pattern']?>"<?endif?>
                                                                type="<?= $arFieldsVal['type']?>" maxlength="255" 
                                                                name="<?= $name?>" value="<?= $arFieldsVal['value']?>"
                                                                class="form-control" 
                                                                <?if ($arFieldsVal['placeholder']):?>placeholder="<?= $arFieldsVal['placeholder']?>"<?endif?>>
                                                             
                                                        <?endif?>
                                                </div>
                                        </div>
                                        <?endforeach?>
                                </div>
                            <?endforeach?>
                            
                        </div>
                    <?  endforeach;?>
                    <div class="text-right">
                        <button type="submit" name="submit" value="Сохранить" class="btn bg-teal-400 "> Сохранить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function tabOpen ($, tabSelector) {
        $(tabSelector).tab("show");
    }
</script>