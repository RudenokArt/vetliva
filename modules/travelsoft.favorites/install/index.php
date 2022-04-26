<?php

use Bitrix\Main\ModuleManager,
    Bitrix\Main\Loader,
    Bitrix\Main\Config\Option;

class travelsoft_favorites extends CModule {

    public $MODULE_ID = "travelsoft.favorites";
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_GROUP_RIGHTS = "N";
    protected $highloadblocksFiles = [
        "favorites.php"
    ];
    protected $namespaceFolder = "travelsoft";
    protected $componentsList = [
        "favorites.list",
        "favorites.add"
    ];

    function __construct() {
        $arModuleVersion = array();
        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        @include($path . "/version.php");
        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }
        $this->MODULE_NAME = "TS:Favorites";
        $this->MODULE_DESCRIPTION = "Модуль добавления в избранное";
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

            if (!Loader::includeModule("highloadblock")) {
                throw new Exception("Для установки необходим модуль highloadblock");
            }

            # создание higloadblock модуля
            $this->createHighloadblockTables();

            # обработчики событий модуля
            $this->registerModuleDependencies();

            # копируем файлы компонентов
            $this->copyFiles();

            # переменные модуля
            $this->addOptions();
            
            ModuleManager::registerModule($this->MODULE_ID);
            
            return true;
        } catch (Exception $ex) {
            $GLOBALS["APPLICATION"]->ThrowException($ex->getMessage());
            $this->DoUninstall();
            return false;
        }
    }

    public function addOptions() {
        
    }

    public function deleteOptions() {
        
    }

    public function registerModuleDependencies() {
        // to do if need
    }

    public function unRegisterModuleDependencies() {
        // to do if need
    }

    public function DoUninstall() {

        $this->unRegisterModuleDependencies();

        $this->deleteHighloadblockTables();

        $this->deleteFiles();

        $this->deleteOptions();

        ModuleManager::UnRegisterModule($this->MODULE_ID);

        return true;
    }

    public function createHighloadblockTables() {

        foreach ($this->highloadblocksFiles as $file) {

            $arr = include "highloadblocks/" . $file;

            $result = Bitrix\Highloadblock\HighloadBlockTable::add(array(
                        'NAME' => $arr["table_data"]["NAME"],
                        'TABLE_NAME' => $arr["table"]
            ));

            if (!$result->isSuccess()) {
                throw new Exception($arr["table_data"]['ERR'] . "<br>" . implode("<br>", (array) $result->getErrorMessages()));
            }

            $table_id = $result->getId();

            Option::set($this->MODULE_ID, $arr["table_data"]["OPTION_PARAMETER"], $table_id);

            $arr_fields = $arr["fields"];

            $oUserTypeEntity = new CUserTypeEntity();

            foreach ($arr_fields as $arr_field) {

                $arr_field["ENTITY_ID"] = str_replace("{{table_id}}", $table_id, $arr_field["ENTITY_ID"]);

                if (!$oUserTypeEntity->Add($arr_field)) {
                    throw new Exception("Возникла ошибка при добавлении свойства " . $arr_field["ENTITY_ID"] . "[" . $arr_field["FIELD_NAME"] . "]" . $oUserTypeEntity->LAST_ERROR);
                }
            }

            if (isset($arr["items"]) && !empty($arr["items"])) {

                $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity(
                                Bitrix\Highloadblock\HighloadBlockTable::getById($table_id)->fetch());
                $class = $entity->getDataClass();
                foreach ($arr["items"] as $item) {
                    $class::add($item);
                }
            }
        }
    }

    public function deleteHighloadblockTables() {

        foreach ($this->highloadblocksFiles as $file) {

            $arr = include "highloadblocks/" . $file;

            $table_id = Option::get($this->MODULE_ID, $arr["table_data"]["OPTION_PARAMETER"]);
            if ($table_id > 0) {
                Bitrix\Highloadblock\HighloadBlockTable::delete($table_id);
            }
            Option::delete($this->MODULE_ID, array("name" => $arr["table_data"]["OPTION_PARAMETER"]));
        }
    }

    private function __loadHigloadblockFiles() {

        $this->highloadblocksFiles = $this->__loadFiles("highloadblocks");
    }

    private function __loadFiles($dirName) {

        $directory = __DIR__ . "/" . $dirName;
        return array_diff(scandir($directory), array('..', '.'));
    }

}
