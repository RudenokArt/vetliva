<?php
use Bitrix\Main\Localization\Loc,
        Bitrix\Main\ModuleManager,
            Bitrix\Main\SiteTable,
                Bitrix\Main\Loader,
                    Bitrix\Main\Config\Option,
                        Bitrix\Main\GroupTable;
                    

Loc::loadMessages(__FILE__);

class travelsoft_booking_dev_tools extends CModule {
    public $MODULE_ID = "travelsoft.booking.dev.tools";
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_GROUP_RIGHTS = "N";
    protected $namespaceFolder = "travelsoft";
    protected $arHLtoDel = array();
    protected $etn = "TRAVELSOFT_BOOKING";
    protected $arHighloadblocks = array(
            // услуги
            "ts_services" => array(
                "NAME" => "TSSERVICES",
                "ID" => NULL,
                "ERR" => "Ошибка при создании highloadblock'a УСЛУГИ"
            ),
            // цены
            "ts_prices" => array(
                "NAME" => "TSPRICES",
                "ID" => NULL,
                "ERR" => "Ошибка при создании highloadblock'a ЦЕНЫ"
            ),
            // квоты и наличие мест
            "ts_quotas" => array(
                "NAME" => "TSQUOTAS",
                "ID" => NULL,
                "ERR" => "Ошибка при создании highloadblock'a КВОТЫ И НАЛИЧИЕ МЕСТ"
            ),
            // тарифы
            "ts_rates" => array(
                "NAME" => "TSRATES",
                "ID" => NULL,
                "ERR" => "Ошибка при создании highloadblock'a ТАРИФЫ"
            ),
            // категория тарифов
            "ts_rates_category" => array(
                "NAME" => "TSRATESCATEGORY",
                "ID" => NULL,
                "ERR" => "Ошибка при создании highloadblock'a КАТЕГОРИИ ТАРИФОВ"
            ),
            // категории + тарифы
            "ts_rates_pluse_category" => array(
                "NAME" => "TSRATESPLUSECATEGORY",
                "ID" => NULL,
                "ERR" => "Ошибка при создании highloadblock'a КАТЕГОРИИ + ТАРИФЫ"
            ),
            // питание
            "ts_food" => array(
                "NAME" => "TSFOOD",
                "ID" => NULL,
                "ERR" => "Ошибка при создании highloadblock'a ПИТАНИЕ"
            ),
            // лечение
            "ts_treatment" => array(
                "NAME" => "TSTREATMENT",
                "ID" => NULL,
                "ERR" => "Ошибка при создании highloadblock'a ЛЕЧЕНИЕ"
            ),
            // трансфер
            "ts_transfer_fix" => array(
                "NAME" => "TSTRANSFERFIX",
                "ID" => NULL,
                "ERR" => "Ошибка при создании highloadblock'a ТРАНСФЕР"
            ),
            // трансфер по километражу
            "ts_transfer_by_kilometrage" => array(
                "NAME" => "TSTRANSFERBYKILOMETRAGE",
                "ID" => NULL,
                "ERR" => "Ошибка при создании highloadblock'a ТРАНСФЕР ПО КИЛОМЕТРАЖУ"
            ),
       );
    

    function __construct() {
        
        $arModuleVersion = array();
        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path."/version.php");
        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion))
        {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }
        $this->MODULE_NAME = "Набор инструментов online бранирования туристических услуг";
        $this->MODULE_DESCRIPTION = "Набор инструментов для разработки  online бранирования туристических услуг"
                . "(Hightloadblocks для разработки, почтовое событиеи и почтовые шаблоны)";
        $this->PARTNER_NAME = "dimabresky (by travelsoft)";
        $this->PARTNER_URI = "https://github.com/dimabresky/";
        
        $this->setSiteID();
        
    }
    
    public function setSiteID() {
        
        $db = SiteTable::getList(array('select' => array('LID')));
        
        while ($res = $db->fetch())
            $this->site_id[] = $res['LID'];
        
    }
    
    public function copyFiles() {

        return CopyDirFiles(
                $_SERVER["DOCUMENT_ROOT"]."/local/modules/".$this->MODULE_ID."/install/components/".$this->namespaceFolder."/",
                $_SERVER["DOCUMENT_ROOT"]."/local/components/".$this->namespaceFolder."/",
                true, true
            );
    }
    
    public function createHighloadTables () {
        
        foreach ($this->arHighloadblocks as $tname => $arHLB) {

            $result = Bitrix\Highloadblock\HighloadBlockTable::add(array(
                'NAME' => $arHLB["NAME"],
                'TABLE_NAME' => $tname,
            ));

            if (!$result->isSuccess())
                throw new Exception ($arHLB['ERR']);
            
            $hlbt = Bitrix\Highloadblock\HighloadBlockTable::getById($result->getId())->fetch();
            
            $this->arHighloadblocks[$tname]["ID"] = $hlbt['ID'];
            $this->arHLtoDel[] = $hlbt['ID'];
        }
        
        $oUserTypeEntity    = new CUserTypeEntity();
        
        foreach ($this->arHighloadblocks as $tname => $arHLB) {
            // поля highload
            $ARRHLBLOCKS_USER_FIELDS = include __DIR__ . '/user_fields/' . $tname .'_user_fields.php';

            foreach ($ARRHLBLOCKS_USER_FIELDS as $aUserFields) {
                
                if (!$oUserTypeEntity->Add( $aUserFields )) {
                    throw new Exception("Возникла ошибка при добавлении свойства " .$aUserFields["ENTITY_ID"] . "[".$aUserFields["FIELD_NAME"]."]" . $oUserTypeEntity->LAST_ERROR);
                }
                
            }
        }
        
        if (!empty($this->arHLtoDel))
            Option::set($this->MODULE_ID, 'highloadblocks_id', implode(';', $this->arHLtoDel));
        
    }
    
    public function deleteHighloadTables () {
       
        if (!empty($this->arHLtoDel)) {
            foreach ($this->arHLtoDel as $id) {
                Bitrix\Highloadblock\HighloadBlockTable::delete($id);
            }
            return;
        }

        $arHLBID = explode(";", Option::get($this->MODULE_ID, 'highloadblocks_id'));

        foreach ($arHLBID as $id) {
            if ($id > 0) {
                Bitrix\Highloadblock\HighloadBlockTable::delete($id);
            }
        }
        
        Option::delete($this->MODULE_ID,  array('name' => 'highloadblocks_id'));
        
    }
    
    public function installUserStaff () {
        
        $arGroup = GroupTable::getList(array(
                                                    'filter' => array('STRING_ID' => 'TS_SERVICE_PROVIDER_GROUP')
                                                ))->fetch();
        
        if ($arGroup['ID'] > 0) {
            $GID = $arGroup['ID'];
        } else {
            $request = GroupTable::add(array(
                "ACTIVE" => "Y",
                "ANONYMOUS" => "N",
                "STRING_ID" => "TS_SERVICE_PROVIDER_GROUP",
                "NAME" => "Поставщики(travelsoft.booking)",
                "DESCRIPTION" => "Группа пользователей для поставщиков туристических услуг (модуль travelsoft.booking)",
            ));

            if (!$request->isSuccess())
                throw new Exception ("Ошибка добавления группы поставщиков услуг");
            
            $GID = $request->getId();
        }
        
        Option::set($this->MODULE_ID, 'service_provider_group_id', $GID);

        $oUserTypeEntity    = new CUserTypeEntity();
        
        $userFields = include __DIR__ . '/user_fields/ts_user_user_fields.php';
        
        foreach ($userFields as $arUF) {
            if ($id = $oUserTypeEntity->Add( $arUF ))
                Option::set($this->MODULE_ID, $arUF['FIELD_NAME'], $id);
        }
        
    }
    
    public function deleteUserStaff () {
        
//        GroupTable::delete(Option::get($this->MODULE_ID, 'service_provider_group'));
        
        Option::delete($this->MODULE_ID, array('name' => 'service_provider_group_id'));
        
        $oUserTypeEntity    = new CUserTypeEntity();
        
        $userFields = include __DIR__ . '/user_fields/ts_user_user_fields.php';
        
        foreach ($userFields as $arUF) {
            $oUserTypeEntity->Delete(Option::get($this->MODULE_ID, $arUF['FIELD_NAME']));
            Option::delete($this->MODULE_ID, array('name' => $arUF['FIELD_NAME']));
        }
        
    }
    
    public function eventsHandlersRegister () {
        
    }
    
    public function eventsHandlersUnRegister () {
        
    }
    
    public function addMails () {
        
        $dbLang = CLangAdmin::GetList(($b="sort"), ($o="asc"), array("ACTIVE" => "Y"));
        while ($lang = $dbLang->Fetch())
                $lang_ids[] = $lang["LID"];
        
        $et = new CEventType;
        
        // почтовое событие
        foreach($lang_ids as $lang) {
            
                $f = array(
                        "LID" => $lang,
                        "EVENT_NAME" => $this->etn,
                        "NAME" => "Почтовые сообщения online бронирования travelsoft.booking",
                );
                
                if($et->Add($f) === false) {
                    throw new Exception("Не удалось добавить почтовое событие");
                }
                
        }
        
        Option::set($this->MODULE_ID, 'booking_mails_event_type', $this->etn);
        
        // почтовые шаблоны
        $arMails = array(
            array(
                    "ACTIVE" => "Y",
                    "EVENT_NAME" => $this->etn,
                    "LID" => $this->site_id,
                    "EMAIL_FROM" => "#DEFAULT_EMAIL_FROM#",
                    "EMAIL_TO" => "#EMAIL_TO#",
                    "SUBJECT" => "Новый поставщик услуг на сайте #SITE_NAME#",
                    "BODY_TYPE" => "html",
                    "MESSAGE" => "На сайте зарегистрирован новый поставщик услуг.<br>"
                    . "Ссылка на профиль: <a href='https://#SERVER_NAME#/bitrix/admin/user_edit.php?lang=ru&ID=#USER_ID#'>https://#SERVER_NAME#/bitrix/admin/user_edit.php?lang=ru&ID=#USER_ID#</a><br><br><br>"
                    . "Сообщение сгенерировано автоматически",
            ),
            array(
                    "ACTIVE" => "Y",
                    "EVENT_NAME" => $this->etn,
                    "LID" => $this->site_id,
                    "EMAIL_FROM" => "#DEFAULT_EMAIL_FROM#",
                    "EMAIL_TO" => "#EMAIL_TO#",
                    "SUBJECT" => "Активация поставщика услуг на сайте #SITE_NAME#",
                    "BODY_TYPE" => "html",
                    "MESSAGE" => "Здравствуйте, #NAME# #LAST_NAME#.<br>"
                    . "После проверки Вы были активированы в системе как поставщик услуг.<br>"
                    . "Ссылка для перехода в личный кабинет поставщика услуг: "
                    . "<a href='https://#SERVER_NAME#/partners/'>https://#SERVER_NAME#/partners/</a><br><br><br>"
                    . "Сообщение сгенерировано автоматически",
            ),
        );
        
        $o_mt = new CEventMessage;
        $cnt = count($arMails);
        for ($i = 0; $i < $cnt; $i++) {
                if ($o_mt->Add($arMails[$i]) <= 0) {
                    throw new Exception("Ошибка добавления почтового шаблона ". $arMails[$i]['SUBJECT']);
                }
        } 
        
    }
    
    public function deleteMails () {
        
        $arFilter = Array(
            "TYPE_ID"       => $this->etn
        );
        
        $rsMess = CEventMessage::GetList($by="site_id", $order="desc", $arFilter);
        while($mess = $rsMess->Fetch())
                CEventMessage::Delete($mess['ID']);
        
        CEventType::Delete( $this->etn );
        
        Option::delete($this->MODULE_ID, array('name' => 'booking_mails_event_type'));
        
    }
    
    public function deleteFiles() {
        DeleteDirFilesEx("/local/components/". $this->namespaceFolder."/");
	return true;
    }
    
    public function DoInstall() {
        try {
           
            // проверка зависимостей модуля
            if ( !ModuleManager::isModuleInstalled("iblock") )
                throw new Exception("Модуль инфоблоков не установлен");
            if ( !ModuleManager::isModuleInstalled("highloadblock") )
                throw new Exception("Модуль highloadblock не установлен");
//            if( !ModuleManager::isModuleInstalled("travelsoft.currency") )
//                throw new Exception("Для установки модуля необходимо установить модуль валюты <a target='__blank' href='https://github.com/dimabresky/travelsoft.currency'>travelsoft.currency</a>");
        
           Loader::includeModule("highloadblock");
            
           // установка highloadblock таблиц
           $this->createHighloadTables();
           
           // установка группы пользователей поставщика услуг и свойств пользователя системы
           $this->installUserStaff();
           
           // установка почтовых событий и почтовых шаблонов
           $this->addMails();
           
           // copy files
          
           // add total module options
           Option::set ($this->MODULE_ID, 'tsmo_url',"");
           
           ModuleManager::registerModule($this->MODULE_ID);
            
        } catch (Exception $ex) {
            $GLOBALS["APPLICATION"]->ThrowException($ex->getMessage());
            $this->DoUninstall();
            return false;
        }
        
        return true;
    }
    
    public function DoUninstall() {
        
        Loader::includeModule("highloadblock");
        
        // удаление highloadblock таблиц
        $this->deleteHighloadTables();
        
        // удаление группы пользователей для поставщиков и свойств пользователя
        $this->deleteUserStaff();
        
        // удаление почтовых событий и почтовых шаблонов
        $this->deleteMails();
        
        // delete files
        
        // remove total module options
        Option::set ($this->MODULE_ID,  array('name' => 'tsmo_url'));

        // unregister module
        ModuleManager::UnRegisterModule($this->MODULE_ID);
        
        return true;

    }
}
