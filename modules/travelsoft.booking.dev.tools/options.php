<?php
if (!$USER->isAdmin() || !\Bitrix\Main\Loader::includeModule("highloadblock"))
    return;

global $APPLICATION;

$module_id = "travelsoft.booking.dev.tools";

$arHiloadblocks = Bitrix\Highloadblock\HighloadBlockTable::getList(array(
            "order" => array("ID" => "ASC")
        ))->fetchAll();

foreach ($arHiloadblocks as $arHL) {
    $arHLDef[] = array($arHL['ID'], $arHL['NAME']);
}

Bitrix\Main\Loader::includeModule("iblock");
$dbIblocks = CIBlock::GetList(
            array(),
            array("ACTIVE" => "Y")
        );
while ($arRes = $dbIblocks->Fetch()) {
    $arIblocks[] = array($arRes["ID"], $arRes["NAME"]);
}


$db_arGroups = Bitrix\Main\GroupTable::getList(array("select" => array("ID", "NAME")))->fetchAll();

for ($i = 0, $cnt = count($db_arGroups); $i < $cnt; $i++) {
    $arGroups[] = array($db_arGroups[$i]["ID"], $db_arGroups[$i]["NAME"]);
}

# шаблоны писем
$dbRes = CEventMessage::GetList($by = "site_id", $order = "desc", array("TYPE_ID" => "TRAVELSOFT_BOOKING"));
$arMailTemplates = null;
while ($arRes = $dbRes->Fetch()) {
    $arMailTemplates[] = array($arRes["ID"], $arRes["SUBJECT"]);
}

$all_options = array(
    "group_settings" => array(
        "title" => "-- Настройки групп пользователей --",
        "data" => array(
            'service_provider_group_id' => array('desc' => "Группа пользователей для поставщиков", 'type' => 'select', 'def' => $arGroups),
            'transfers_provider_group' => array('desc' => "Группа пользователей для поставщиков трансферов", 'type' => 'select', 'def' => $arGroups),
            'placements_provider_group' => array('desc' => "Группа пользователей для поставщиков объектов размещения", 'type' => 'select', 'def' => $arGroups),
            'sanatorium_provider_group' => array('desc' => "Группа пользователей для поставщиков санаториев", 'type' => 'select', 'def' => $arGroups),
            'excursions_provider_group' => array('desc' => "Группа пользователей для поставщиков экскурсионных туров", 'type' => 'select', 'def' => $arGroups),
            'guide_group' => array('desc' => "Группа пользователей для гидов", 'type' => 'select', 'def' => $arGroups),
            'agents_group_id' => array('desc' => 'Группа пользователей для агентов', 'type' => 'select', 'def' => $arGroups),
            'cut_provider_group_id' => array('desc' => 'Группа пользователей для поставщиков c урезанными парвами на редактирование', 'type' => 'select', 'def' => $arGroups),
        )
    ),
    "mail_settings" => array(
        "title" => "-- Настройки почтовых сообщений --",
        "data" => array(
            'agent_register_mail_template' => array('desc' => 'Письмо о регистрации нового агента', 'type' => 'select', 'def' => $arMailTemplates),
            'provider_register_mail_template' => array('desc' => 'Письмо о регистрации нового поставщика', 'type' => 'select', 'def' => $arMailTemplates),
            'provider_after_register_mail_template' => array('desc' => 'Письмо поставщику о регистрации', 'type' => 'select', 'def' => $arMailTemplates),
            'provider_active_mail_template' => array('desc' => 'Письмо об активации поставщика услуг', 'type' => 'select', 'def' => $arMailTemplates),
            'quota_expired_mail_template' => array('desc' => 'Письмо о распродаже квоты-мест', 'type' => 'select', 'def' => $arMailTemplates),
            'quota_expired_by_period_mail_template' => array('desc' => 'Письмо об окончании периода доступной квоты', 'type' => 'select', 'def' => $arMailTemplates),
        )
    ),
    "stores_settings" => array(
        "title" => "-- Настройки хранилищ данных --",
        "data" => array(
            'placements_ib_id' => array('desc' => "Инфоблок объектов размещения", 'type' => 'select', "def" => $arIblocks),
            'sanatorium_ib_id' => array('desc' => "Инфоблок санаториев", 'type' => 'select', "def" => $arIblocks),
            'excursions_ib_id' => array('desc' => "Инфоблок  экскурсий", 'type' => 'select', "def" => $arIblocks),
            'service_hl_id' => array('desc' => "Highloadblock услуг", 'type' => 'select', "def" => $arHLDef),
            'transfer_hl_id' => array('desc' => "Highloadblock трансферов", 'type' => 'select', "def" => $arHLDef),
            'transfer_rates_hl_id' => array('desc' => "Highloadblock тарифов к трансферам", 'type' => 'select', "def" => $arHLDef),
            'class_auto_hl_id' => array('desc' => "Highloadblock классов авто", 'type' => 'select', "def" => $arHLDef),
            'price_hl_id' => array('desc' => "Highloadblock цен", 'type' => 'select', "def" => $arHLDef),
            'quotas_hl_id' => array('desc' => "Highloadblock квот", 'type' => 'select', "def" => $arHLDef),
            'ptr_hl_id' => array("desc" => "Highloadblock типы цен + тарифы", 'type' => 'select', "def" => $arHLDef),
            'rate_hl_id' => array("desc" => "Highloadblock тарифы", 'type' => 'select', "def" => $arHLDef),
            'price_type_hl_id' => array("desc" => "Highloadblock типы цен", 'type' => 'select', "def" => $arHLDef),
            'food_hl_id' => array("desc" => "Highloadblock питания", 'type' => 'select', "def" => $arHLDef),
            'citizenship_hl_id' => array("desc" => "Highloadblock гражданство", 'type' => 'select', "def" => $arHLDef),
            'rates_quotas_hl_id' => array("desc" => "Highloadblock квоты для тарифов", 'type' => 'select', "def" => $arHLDef),
            'autostopsale' => array("desc" => "Highloadblock autostopsale", 'type' => 'select', "def" => $arHLDef),
        )
    ),
    "calc_price_settings" => array(
        "title" => "-- Настройки для расчёта цен по-умолчанию --",
        "data" => array(
            'dateRange' => array("desc" => "", "type" => "", "def" => array(
                    "placementsDateRange" => array("title" => "Период поиска цен по-умолчанию для размещений", "defValues" => array(0, 7)),
                    "sanatoriumDateRange" => array("title" => "Период поиска цен по-умолчанию для санаториев", "defValues" => array(0, 7)),
                    "excursionsDateRange" => array("title" => "Период поиска цен по-умолчанию для экскурсий", "defValues" => array(0, 7)),
                    "transfersDateRange" => array("title" => "Дата поиска цен по-умолчанию для трансферов", "defValues" => array(3))
                )
            )
        )
    ),
    "other_settings" => array(
        "title" => "-- Другие настройки --",
        "data" => array(
            "google_server_api_key" => array('desc' => "Google api server key", 'type' => 'text'),
            'cache_time' => array('desc' => "Время кеширования цен в секундах", 'type' => 'text'),
            'salt' => array('desc' => "Строка \"примесь\" для формирования hash параметров", 'type' => 'text'),
            'tsmo_url' => array('desc' => "Адрес к ТSMO сервису", 'type' => 'text'),
        )
    )
);

$tabs = array(
    array(
        "DIV" => "edit1",
        "TAB" => "Настройки",
        "ICON" => "erip-icon",
        "TITLE" => "Настройки"
    )
);

$o_tab = new CAdminTabControl("TravelsoftTabControl", $tabs);
if ($REQUEST_METHOD == "POST" && strlen($save . $reset) > 0 && check_bitrix_sessid()) {
    if (strlen($reset) > 0) {
        foreach ($all_options as $arr) {
            foreach ($arr["data"] as $name => $desc) {
                \Bitrix\Main\Config\Option::delete($module_id, array('name' => $name));
            }
        }
    } else {
        foreach ($all_options as $arr) {
            foreach ($arr["data"] as $name => $desc) {

                if (isset($_REQUEST[$name])) {
                    if ($name == "dateRange") {
                        foreach ($_REQUEST["dateRange"] as $inputName => $arValues) {
                            \Bitrix\Main\Config\Option::set($module_id, $inputName, serialize(array(abs(intVal($arValues[0])), abs(intVal($arValues[1])))));
                        }
                    } else {
                        \Bitrix\Main\Config\Option::set($module_id, $name, $_REQUEST[$name]);
                    }
                }
            }
        }
    }

    LocalRedirect($APPLICATION->GetCurPage() . "?mid=" . urlencode($module_id) . "&lang=" . urlencode(LANGUAGE_ID) . "&" . $o_tab->ActiveTabParam());
}
$o_tab->Begin();
?>
<style>
    .dinp {width: 20px;}
    .setting-title {
        text-align: center;
        font-size: 24px;
        font-weight: bolder;
        padding: 20px;
    }
</style>
<form method="post" action="<? echo $APPLICATION->GetCurPage() ?>?mid=<?= urlencode($module_id) ?>&amp;lang=<? echo LANGUAGE_ID ?>">
    <?
    $now = "";
    $o_tab->BeginNextTab();
    foreach ($all_options as $arr):
        ?>
    <tr><td class="setting-title" colspan="2"><?= $arr["title"] ?></td></tr>
        <?
        foreach ($arr["data"] as $name => $desc):
            $name = htmlspecialcharsbx($name);
            if ($name == "dateRange"):
                foreach ($desc["def"] as $inputName => $arValues):
                    $arCurVals = array_map(function ($val) {
                        return intVal($val);
                    }, unserialize(Bitrix\Main\Config\Option::get($module_id, $inputName)));
                    $form = $arCurVals[0] > 0 ? $arCurVals[0] : $arValues["defValues"][0];
                    $to = $arCurVals[1] > 0 ? $arCurVals[1] : $arValues["defValues"][1];
                    ?>
                    <tr>
                        <td width="40%">
                            <label for="<? echo $name ?>"><? echo $arValues["title"] ?>:</label>
                        </td>
                        <td width="60%">
                            (с сегодня(<?= date("d.m.Y", time()) ?>)+ <input class="dinp" type="text" value="<?= $form ?>" name="<?= $name . "[" . $inputName . "][]" ?>"> дней)
                            <? if ($to): ?>
                                + <input class="dinp" type="text" value="<?= $to ?>" name="<?= $name . "[" . $inputName . "][]" ?>"> дней
                    <? endif ?>
                        </td>
                    </tr>
                <?
                endforeach;
            else: $cur_opt_val = htmlspecialcharsbx(Bitrix\Main\Config\Option::get($module_id, $name));
                ?>
                <tr>
                    <td width="40%">
                        <label for="<? echo $name ?>"><? echo $desc['desc'] ?>:</label>
                    </td>
                    <td width="60%">
                            <? if ($desc['type'] == "select"): ?>
                            <select id="<? echo $name ?>" name="<? echo $name ?>">
                            <? foreach ($desc['def'] as $val) : ?>
                                    <option <? if ($cur_opt_val == $val[0]) : ?>selected<? endif ?> value="<?= $val[0] ?>"><?= $val[1] ?></option>
                            <? endforeach ?>
                            </select>
            <? else: ?>
                            <input type="text" id="<? echo $name ?>" value="<? echo $cur_opt_val ?>" name="<? echo $name ?>">
                <? endif ?>
                    </td>
                </tr>
            <? endif ?>
    <? endforeach ?>
    <? endforeach ?>
    <? $o_tab->Buttons(); ?>
    <input type="submit" name="save" value="Сохранить" title="Сохранить" class="adm-btn-save">
    <input type="submit" name="reset" title="Сбросить" OnClick="return confirm('<? echo AddSlashes("Уверены, что хотите сбросить настройки") ?>')" value="Сбросить">
<?= bitrix_sessid_post(); ?>
<? $o_tab->End(); ?>
</form>