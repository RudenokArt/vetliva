<?
/**
 * @global CMain $APPLICATION
 * @param array $arParams
 * @param array $arResult
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>
<script>
    function viewPassword(element) {
        if (element.closest('.bx-authform-formgroup-container').find('input').attr('type')=='password') {
            element.closest('.bx-authform-formgroup-container').find('input').attr('type','text');
            element.attr('class', 'fa fa-eye icon-eye-blocked text-muted');
        }
        else {
            element.closest('.bx-authform-formgroup-container').find('input').attr('type','password');
            element.attr('class', 'fa fa-eye-slash icon-eye text-muted');
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
<div class="my-profile">

    <? ShowError($arResult["strProfileError"]); ?>
    <?
    if ($arResult['DATA_SAVED'] == 'Y')
        ShowNote(GetMessage('PROFILE_DATA_SAVED'));
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
    </script>
    <form method="post" name="form1" action="<?= $arResult["FORM_TARGET"] ?>" enctype="multipart/form-data">
        <input type="hidden" name="passwordcorret" value="Y">
<?= $arResult["BX_SESSION_CHECK"] ?>
        <input type="hidden" name="lang" value="<?= LANG ?>" />
        <input type="hidden" name="LOGIN" value="<? echo $arResult["arUser"]["EMAIL"] ?>" />
        <input type="hidden" name="ID" value="<?= $arResult["ID"] ?>" />
        <ul>

                    <? // ********************* User properties *************************************************** ?>
<? if ($arResult["USER_PROPERTIES"]["SHOW"] == "Y"): ?>
            <ul>
    <? $first = true; ?>
    <? 
    $USER_PROPERTY_HTML_ARRAY = null;
    foreach ($arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField): 
            $html = "";
            if ($FIELD_NAME == "UF_ACCEPT_PDATA") {
                $html .= "<span>".$arUserField["EDIT_FORM_LABEL"].":</span>";
                $checked = $arUserField["VALUE"] == 1 ? "checked" : "";
                $html .= "<input type='checkbox' $checked  name='$FIELD_NAME' value='1' >";
                $USER_PROPERTY_HTML_ARRAY[] = "<li id='acceptPData' style='margin-bottom: 130px;'>" . $html . "</li>";
                continue;
            }
        
        ?>
                    <li>
                <? if ($arUserField["MANDATORY"] == "Y"): ?>
                            <span class="starrequired">*</span>
                    <? endif; ?>
                        <span><?= $arUserField["EDIT_FORM_LABEL"] ?>:</span>
                    <?
                    $APPLICATION->IncludeComponent(
                            "bitrix:system.field.edit", $arUserField["USER_TYPE"]["USER_TYPE_ID"], array("bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arUserField), null, array("HIDE_ICONS" => "Y"));
                    ?></li>
                    <? endforeach; ?>
            </ul>
                <? endif; ?>
                <? // ******************** /User properties ***************************************************?>
            <li>
                <span><? echo GetMessage("main_profile_title") ?></span>
                <input type="text" name="TITLE" value="<?= $arResult["arUser"]["TITLE"] ?>" />
            </li>
            <li>
                <span><?= GetMessage('NAME') ?></span>
                <input type="text" name="NAME" maxlength="50" value="<?= $arResult["arUser"]["NAME"] ?>" />
            </li>
            <li>
                <span><?= GetMessage('LAST_NAME') ?></span>
                <input type="text" name="LAST_NAME" maxlength="50" value="<?= $arResult["arUser"]["LAST_NAME"] ?>" />
            </li>
            <li>
                <span><?= GetMessage('SECOND_NAME') ?></span>
                <input type="text" name="SECOND_NAME" maxlength="50" value="<?= $arResult["arUser"]["SECOND_NAME"] ?>" />
            </li>
            <li>
                <span><?= GetMessage('EMAIL') ?><? if ($arResult["EMAIL_REQUIRED"]): ?><span class="starrequired">*</span><? endif ?></span>
                <input type="text" name="EMAIL" maxlength="50" value="<? echo $arResult["arUser"]["EMAIL"] ?>" />
            </li>
            <? if ($arResult["arUser"]["EXTERNAL_AUTH_ID"] == ''): ?>
                <li class="bx-authform-formgroup-container">
                    <span><?= GetMessage('NEW_PASSWORD_REQ') ?></span>
                    <input type="password" id="pass" onchange="check_pass()" onkeyup="check_pass()" name="NEW_PASSWORD" maxlength="50" value="" autocomplete="off" class="bx-auth-input" />
                    <div class="form-control-feedback">
                        <i class="fa fa-eye-slash icon-eye text-muted" aria-hidden="true" onClick="viewPassword($(this))"></i>
                    </div>
                    <div class="passerror-container"></div>
                <? if ($arResult["SECURE_AUTH"]): ?>
                        <span class="bx-auth-secure" id="bx_auth_secure" title="<? echo GetMessage("AUTH_SECURE_NOTE") ?>" style="display:none">
                            <div class="bx-auth-secure-icon"></div>
                        </span>
                        <noscript>
                        <span class="bx-auth-secure" title="<? echo GetMessage("AUTH_NONSECURE_NOTE") ?>">
                            <div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
                        </span>
                        </noscript>
                        <script type="text/javascript">
                            document.getElementById('bx_auth_secure').style.display = 'inline-block';
                        </script>
                <? endif ?>
                </li>
                <li class="bx-authform-formgroup-container">
                    <span><?= GetMessage('NEW_PASSWORD_CONFIRM') ?></span>
                    <input type="password" name="NEW_PASSWORD_CONFIRM" maxlength="50" value="" autocomplete="off" />
                    <div class="form-control-feedback">
                        <i class="fa fa-eye-slash icon-eye text-muted" aria-hidden="true" onClick="viewPassword($(this))"></i>
                    </div>
                </li>
            <? endif ?>
            <? if ($arResult["TIME_ZONE_ENABLED"] == true): ?>
                <li>
                    <span><? echo GetMessage("main_profile_time_zones") ?></span>
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
                    <? endif ?>
        </ul>

        <ul>

            <li>
                <span><?= GetMessage('USER_PHONE') ?></span>
                <input type="text" name="WORK_PHONE" maxlength="255" value="<?= $arResult["arUser"]["WORK_PHONE"] ?>" />
            </li>
            <li>
                <span><?= GetMessage('USER_COUNTRY') ?></span>
<?= $arResult["COUNTRY_SELECT_WORK"] ?>
            </li>
            <li>
                <span><?= GetMessage('USER_CITY') ?></span>
                <input type="text" name="WORK_CITY" maxlength="255" value="<?= $arResult["arUser"]["WORK_CITY"] ?>" />
            </li>
        </ul>

        <?
if ($arResult["ID"] > 0) {
    ?>
        <ul>
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
                    
        </ul>

          <?
                }
                ?>
        <?if ($USER_PROPERTY_HTML_ARRAY) {
          echo "<ul>" . implode("", $USER_PROPERTY_HTML_ARRAY) . "</ul>";  
        }?>
        <p><?// echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"]; ?></p>
        <p><input class="btn" type="submit" name="save" value="<?= (($arResult["ID"] > 0) ? GetMessage("MAIN_SAVE") : GetMessage("MAIN_ADD")) ?>"></p>
    </form>
        <?
        if ($arResult["SOCSERV_ENABLED"]) {?>
			<div style="margin-top: 35px;">
            <?$APPLICATION->IncludeComponent("bitrix:socserv.auth.split", ".default", array(
                "SHOW_PROFILES" => "Y",
                "ALLOW_DELETE" => "Y"
                    ), false
            );?>
			</div><?
        }
        ?>
</div>
<script>
(function ($) {
    // bad practice of creating css properties '((
    $("form[name='form1'] select").css({padding: "10px"});
    $("#acceptPData span").css({"max-width" : "200px"});
    $("#acceptPData input").css({"width" : "auto"});
    $('form[name=form1]').on('submit', function (e) {
        if ($("input[name='passwordcorret']").val()!='Y'  && $('#pass').val()!='') {
             $([document.documentElement, document.body]).animate({
                scrollTop: $("#pass").offset().top
            }, 2000);
            $("#pass").focus();
            return false;
        }
    });
})(jQuery)
</script>