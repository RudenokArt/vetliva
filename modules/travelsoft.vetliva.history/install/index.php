<?php

use Bitrix\Main\ModuleManager,
    Bitrix\Main\Loader,
    Bitrix\Main\Config\Option;

class travelsoft_vetliva_history extends CModule {

    public $MODULE_ID = "travelsoft.vetliva.history";
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_GROUP_RIGHTS = "N";
    protected $namespaceFolder = "travelsoft";
    protected $componentsList = array(
        "vetliva.history.archive",
        "vetliva.history.page_counter",
        "vetliva.history.view_page_stat",
    );

    function __construct() {
        $arModuleVersion = array();
        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        @include($path . "/version.php");
        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }
        $this->MODULE_NAME = "История изменений на портале vetliva";
        $this->MODULE_DESCRIPTION = "Модуль для портала vetliva для ведения истории изменений и просмотра объектов бронирования и всего что с ними связано.";
        $this->PARTNER_NAME = "TRAVELSOFT";
        $this->PARTNER_URI = "https://travelsoft.by";

    }

    public function copyFiles() {

        foreach ($this->componentsList as $componentName) {
            CopyDirFiles(
                    $_SERVER["DOCUMENT_ROOT"] . "/local/modules/" . $this->MODULE_ID . "/install/components/" . $componentName, $_SERVER["DOCUMENT_ROOT"] . "/local/components/" . $this->namespaceFolder . "/" . $componentName, true, true
            );
        }
    }

    public function deleteFiles() {
        foreach ($this->componentsList as $componentName) {
            DeleteDirFilesEx("/local/components/" . $this->namespaceFolder . "/" . $componentName);
        }
        if (!glob($_SERVER["DOCUMENT_ROOT"] . "/local/components/" . $this->namespaceFolder . "/*")) {
            DeleteDirFilesEx("/local/components/" . $this->namespaceFolder);
        }
        return true;
    }

    public function DoInstall() {
        try {

            # проверка зависимостей
            if (!Loader::includeModule("iblock")) {
                throw new Exception("Для установки необходим модуль инфоблоков");
            }
            if (!Loader::includeModule("highloadblock")) {
                throw new Exception("Для установки необходим модуль highloadblock");
            }

            #преднастройка модуля
            if (!$_REQUEST["settings"] || !strlen($_REQUEST["next"]) || !check_bitrix_sessid()) {

                $GLOBALS['MODULE_ID'] = $this->MODULE_ID;
                $GLOBALS['APPLICATION']->IncludeAdminFile('Настройки', $_SERVER["DOCUMENT_ROOT"] . "/local/modules/" . $this->MODULE_ID . "/install/settings_form.php");
            } else {

                #регистрируем модуль
                ModuleManager::registerModule($this->MODULE_ID);

                #сохраняем параметры модуля из формы
                @include_once __DIR__ . '/../save_module_parameters_from_settings_form.php';

                Option::set("travelsoft.vetliva.history", "DB_SERVER_NAME", "");
                Option::set("travelsoft.vetliva.history", "DB_NAME", "");
                Option::set("travelsoft.vetliva.history", "DB_LOGIN", "");
                Option::set("travelsoft.vetliva.history", "DB_PASSWORD", "");
                Option::set("travelsoft.vetliva.history", "SAVE_PER_DAYS", "365");
                Option::set("travelsoft.vetliva.history", "YANDEX_CLIENT_ID", "");
                Option::set("travelsoft.vetliva.history", "YANDEX_ACCESS_TOKEN", "");
                Option::set("travelsoft.vetliva.history", "YANDEX_COUNTER_ID", "");

                $this->copyFiles();

                return true;
            }
        } catch (Exception $ex) {
            $GLOBALS["APPLICATION"]->ThrowException($ex->getMessage());
            $this->DoUninstall();
            return false;
        }
    }

    public function DoUninstall() {
        #удаление таблицы истории
        Bitrix\Highloadblock\HighloadBlockTable::delete(Option::get($this->MODULE_ID, "history_highloadblock"));

        @include_once __DIR__ . '/../functions.php';
        travelsoft\vetliva\unRegisterAllModuleDependences();
        travelsoft\vetliva\unsetModuleOptions();
        ModuleManager::UnRegisterModule($this->MODULE_ID);
        $this->deleteFiles();
        return true;
    }

}
