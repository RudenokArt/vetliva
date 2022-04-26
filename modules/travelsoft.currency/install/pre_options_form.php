<?php
/*
 * set options before install finish
 */
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
CJSCore::Init(array("jquery"));

$html_currency_add = "<tr><td>". Loc::getMessage('TRAVELSOFT_CURRENCY_ADD_CURRENCY_NAME') ."<input type='text' name='currency[names][]' value='' size='20' ></td><td>". Loc::getMessage('TRAVELSOFT_CURRENCY_ADD_CURRENCY_VALUE') ."<input type='text' name='currency[iso][]' value='' size='20' ></td></tr>";
$html_course_add = "<tr><td>". Loc::getMessage('TRAVELSOFT_CURRENCY_ADD_COURSE_CURRENCY_NAME') ."<input type='text' name='course[iso][]' value='' size='20' ></td><td>". Loc::getMessage('TRAVELSOFT_CURRENCY_ADD_COURSE_CURRENCY_VALUE') ."<input type='text' name='course[values][]' value='' size='20' ></td></tr>";
?>
<style>
    .pre-options td {
        width: 50%;
    }
    
    .pre-options td:first-child {
        text-align: right;
        padding-right: 20px;
    }
    
    .pre-options .hr td {
        text-align: center;
        padding: 20px 0;
        font-size: 16px;
    }
    
    .pre-options .button-add td {
        text-align: left;
        padding: 10px 0 10px 60%;
    }
    
    .pre-options .notice td {
        text-align: left;
        padding: 0;
        color: green;
    }
    
    .pre-options .next-btn td {
        padding: 0 25% 0 0;
    }
    
</style>

<script>
    function addCurrency (jqbtn) {
        
        var html = "<?= $html_currency_add?>";

        jqbtn.parent().parent().before(html);
        
        return false;
    }
    
    function addCourse (jqbtn) {
        var html = "<?= $html_course_add?>";

        jqbtn.parent().parent().before(html);
        
        return false;
    }
</script>
<?

if (!empty($GLOBALS['ERRORS_FORM'])) { 
    CAdminMessage::ShowMessage(Array("TYPE" => "ERROR", "MESSAGE" => implode('<br>', $GLOBALS['ERRORS_FORM']), "HTML" => true));
}
?>
<form action="<?echo $APPLICATION->GetCurPage()?>" name="form1">
    <?=bitrix_sessid_post()?>
    <input type="hidden" name="lang" value="<?echo LANG?>">
    <input type="hidden" name="id" value="<?= $GLOBALS['MODULE_ID']?>">
    <input type="hidden" name="install" value="Y">
    <input type="hidden" name="step" value="next">
    
    <table class="pre-options" cellpadding="3" cellspacing="0" border="0" width="100%">
        
        <tr class="notice">
            <td colspan="2"><i><?= Loc::getMessage('TRAVELSOFT_CURRENCY_NOTICE')?></i></td>
	</tr>
        
        <tr class="hr">
            <td colspan="2"><b><?= Loc::getMessage('TRAVELSOFT_CURRENCY_ADD_CURRENCY_TITLE')?></b></td>
	</tr>
        
        <?= str_repeat($html_currency_add, 5)?>
        
        <tr class='button-add'>
            <td colspan="2"><input type="submit" onClick="return addCurrency($(this));" value="<?echo Loc::getMessage('TRAVELSOFT_CURRENCY_ADD_BTN')?>"></td>
        </tr>
        
        <tr class="hr">
            <td colspan="2"><b><?= Loc::getMessage('TRAVELSOFT_CURRENCY_ADD_COURSE_CURRENCY_TITLE')?></b></td>
	</tr>
        
        <?= str_repeat($html_course_add, 5)?>
        
        <tr class='button-add'>
            <td colspan="2"><input type="submit" onClick="return addCourse($(this));" value="<?echo Loc::getMessage('TRAVELSOFT_CURRENCY_ADD_BTN')?>"></td>
        </tr>
        <tr class="next-btn"><td colspan="2"><br><input type="submit" name="next" value="<?echo GetMessage("TRAVELSOFT_CURRENCY_INSTALL_BTN")?>"></td></tr>
    </table>
    
</form>