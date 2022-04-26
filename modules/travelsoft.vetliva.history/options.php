<?

if (!$USER->isAdmin() || !\Bitrix\Main\Loader::includeModule("highloadblock") || !Bitrix\Main\Loader::includeModule("iblock")) { return; }

global $APPLICATION;

$arIblocks = $arHighloadblocks = array("nofollow" => "Не отслеживать");

$ibTable = new CIBlock;

$dbIblocks = $ibTable->GetList(array("NAME" => "ASC"), array("ACTIVE" => "Y"));

while ($arRes = $dbIblocks->Fetch()) {
    $arIblocks[$arRes['ID']] = $arRes['NAME'];
}

$dbHLRes = Bitrix\Highloadblock\HighloadBlockTable::getList(array(
            "order" => array("ID" => "ASC")
        ))->fetchAll();

foreach ($dbHLRes as $arHL) {
    $arHighloadblocks[$arHL['ID']] = $arHL['NAME'];
}

$tabs = array(
    array(
            "DIV" => "edit",
            "TAB" => "Настройки модуля travelsoft.vetliva.history",
            "ICON" => "",
            "TITLE" => ""
    ),
);
$o_tab = new CAdminTabControl("TravelsoftVetlivaHistory", $tabs);
if (strlen($_REQUEST["save"]) > 0) {
    @include_once 'save_module_parameters_from_settings_form.php';
    
    Bitrix\Main\Config\Option::set("travelsoft.vetliva.history", "DB_SERVER_NAME", \trim(@$_REQUEST["settings"]["DB_SERVER_NAME"]));
    Bitrix\Main\Config\Option::set("travelsoft.vetliva.history", "DB_NAME", \trim(@$_REQUEST["settings"]["DB_NAME"]));
    Bitrix\Main\Config\Option::set("travelsoft.vetliva.history", "DB_LOGIN", \trim(@$_REQUEST["settings"]["DB_LOGIN"]));
    Bitrix\Main\Config\Option::set("travelsoft.vetliva.history", "DB_PASSWORD", \trim(@$_REQUEST["settings"]["DB_PASSWORD"]));
    Bitrix\Main\Config\Option::set("travelsoft.vetliva.history", "SAVE_PER_DAYS", \trim(@$_REQUEST["settings"]["SAVE_PER_DAYS"]));
    Bitrix\Main\Config\Option::set("travelsoft.vetliva.history", "YANDEX_CLIENT_ID", \trim(@$_REQUEST["settings"]["YANDEX_CLIENT_ID"]));
    Bitrix\Main\Config\Option::set("travelsoft.vetliva.history", "YANDEX_ACCESS_TOKEN", \trim(@$_REQUEST["settings"]["YANDEX_ACCESS_TOKEN"]));
    Bitrix\Main\Config\Option::set("travelsoft.vetliva.history", "YANDEX_COUNTER_ID", \trim(@$_REQUEST["settings"]["YANDEX_COUNTER_ID"]));
    
    LocalRedirect($APPLICATION->GetCurPage() . "?mid=" . urlencode("travelsoft.vetliva.history") . "&lang=" . urlencode(LANGUAGE_ID) . "&" . $o_tab->ActiveTabParam());
} else if (strlen($_REQUEST["reset"]) > 0) {
    @include_once 'functions.php';
    travelsoft\unsetModuleOptions();
    travelsoft\unRegisterAllModuleDependences();
}

$settedIblocks = explode(";", Bitrix\Main\Config\Option::get("travelsoft.vetliva.history", "follow_by_iblocks"));
$settedHighloadblocks = explode(";", Bitrix\Main\Config\Option::get("travelsoft.vetliva.history", "follow_by_highloadblocks"));
$followByUsers = Bitrix\Main\Config\Option::get("travelsoft.vetliva.history", "follow_by_users");

?>

<form method="post" action="<? echo $APPLICATION->GetCurPage() ?>?mid=<?= urlencode("travelsoft.vetliva.history") ?>&amp;lang=<? echo LANGUAGE_ID ?>" name="form1">
    <?
    $o_tab->Begin();
    $o_tab->BeginNextTab();?>
    <?=bitrix_sessid_post()?>
        
        <tr>
            
            <td width="40%">
                <label for="settings[DB_SERVER_NAME]">Сервер баз данных:</label>
            </td>
            
            <td width="60%">
                <?$val = Bitrix\Main\Config\Option::get("travelsoft.vetliva.history", "DB_SERVER_NAME") ?: "";?>
                <input type="text" name="settings[DB_SERVER_NAME]" value="<?= \htmlspecialchars($val)?>">
            </td>
            
        </tr>
        
        <tr>
            
            <td width="40%">
                <label for="settings[DB_NAME]">Имя базы данных:</label>
            </td>
            
            <td width="60%">
                <?$val = Bitrix\Main\Config\Option::get("travelsoft.vetliva.history", "DB_NAME") ?: "";?>
                <input type="text" name="settings[DB_NAME]" value="<?= \htmlspecialchars($val)?>">
            </td>
            
        </tr>
        
        <tr>
            
            <td width="40%">
                <label for="settings[DB_LOGIN]">Логин для подключения к бд:</label>
            </td>
            
            <td width="60%">
                <?$val = Bitrix\Main\Config\Option::get("travelsoft.vetliva.history", "DB_LOGIN") ?: "";?>
                <input type="text" name="settings[DB_LOGIN]" value="<?= \htmlspecialchars($val)?>">
            </td>
            
        </tr>
        
        <tr>
            
            <td width="40%">
                <label for="settings[DB_PASSWORD]">Пароль для подключения к бд:</label>
            </td>
            
            <td width="60%">
                <?$val = Bitrix\Main\Config\Option::get("travelsoft.vetliva.history", "DB_PASSWORD") ?: "";?>
                <input type="password" name="settings[DB_PASSWORD]" value="<?= \htmlspecialchars($val)?>">
            </td>
            
        </tr>
        
        <tr>
            
            <td width="40%">
                <label for="settings[SAVE_PER_DAYS]">Сколько дней хранить историю(минимум 5 дней):</label>
            </td>
            
            <td width="60%">
                <?$val = Bitrix\Main\Config\Option::get("travelsoft.vetliva.history", "SAVE_PER_DAYS") ?: "";?>
                <input type="text" name="settings[SAVE_PER_DAYS]" value="<?= \htmlspecialchars($val)?>">
            </td>
            
        </tr>
        
        <tr>
            
            <td width="40%">
                
                <label for="settings[YANDEX_COUNTER_ID]">ID счетчика яндекс-метрики<?= $acess_token_url?>:</label>
            </td>
            <td width="60%">
                <?$val = Bitrix\Main\Config\Option::get("travelsoft.vetliva.history", "YANDEX_COUNTER_ID") ?: "";?>
                <input type="text" name="settings[YANDEX_COUNTER_ID]" value="<?= \htmlspecialchars($val)?>">
            </td>
            
        </tr>
        
        <tr>
            
            <td width="40%">
                <label for="settings[YANDEX_CLIENT_ID]">ID приложения яндекс-метрики:</label>
            </td>
            
            <td width="60%">
                <?$val = Bitrix\Main\Config\Option::get("travelsoft.vetliva.history", "YANDEX_CLIENT_ID") ?: "";?>
                <input type="text" name="settings[YANDEX_CLIENT_ID]" value="<?= \htmlspecialchars($val)?>">
            </td>
            
        </tr>
        
        <tr>
            
            <td width="40%">
                <?$acess_token_url = '';
                if (Bitrix\Main\Config\Option::get("travelsoft.vetliva.history", "YANDEX_CLIENT_ID")) {
                    $acess_token_url = "(для получения пройдите по <a target=\"__blank\" href=\"https://oauth.yandex.ru/authorize?response_type=token&client_id=".Bitrix\Main\Config\Option::get("travelsoft.vetliva.history", "YANDEX_CLIENT_ID")."\">ссылке</a>)";
                }?>
                <label for="settings[YANDEX_ACCESS_TOKEN]">Токен доступа яндекс-метрики<?= $acess_token_url?>:</label>
            </td>
            <td width="60%">
                <?$val = Bitrix\Main\Config\Option::get("travelsoft.vetliva.history", "YANDEX_ACCESS_TOKEN") ?: "";?>
                <input type="text" name="settings[YANDEX_ACCESS_TOKEN]" value="<?= \htmlspecialchars($val)?>">
            </td>
            
        </tr>
        
        
        <tr>
            
            <td width="40%">
                <label for="settings[follow_by_iblocks][]">Отслеживать изменения элементов инфоблоков:</label>
            </td>
            
            <td width="60%">
                <select multiple="" name="settings[follow_by_iblocks][]">
                    <?foreach ($arIblocks as $value => $title):?>
                    <option <?if (in_array($value, $settedIblocks)):?>selected<?endif?> value="<?= $value?>"><?= $title?></option>
                    <? endforeach;?>
                </select>
            </td>
            
        </tr>
        
        <tr>
            
            <td width="40%">
                <label for="settings[follow_by_highloadblocks][]">Отслеживать изменения элементов higloadblock'ов:</label>
            </td>
            
            <td width="60%">
                <select multiple="" name="settings[follow_by_highloadblocks][]">
                    <?
                    foreach ($arHighloadblocks as $value => $title):?>
                    <option <?if (in_array($value, $settedHighloadblocks)):?>selected<?endif?> value="<?= $value?>"><?= $title?></option>
                    <? endforeach;?>
                </select>
            </td>
            
        </tr>
        
        <tr>
            
            <td width="40%">
                <label for="settings[follow_by_users]">Отслеживать изменения пользователей:</label>
            </td>
            
            <td width="60%">
                <input type="checkbox" <?if ($followByUsers == "Y") { echo "checked"; }?> name="settings[follow_by_users]" value="Y">
            </td>
            
        </tr>
        
        <?$o_tab->Buttons();?>
        <input type="submit" name="save" value="Сохранить" class="adm-btn-save">
        <input type="submit" name="reset" OnClick="return confirm('Вы уверены что хотите сбросить настройки')" value="Сбросить">
    <?$o_tab->End();?>
</form>

    