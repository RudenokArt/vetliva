<?php
/**
 * Created by PhpStorm.
 * User: kosmos
 * Date: 12.12.2017
 * Time: 19:45
 */

use Bitrix\Main\Application,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Loader,
    Bitrix\Main\Config,
    Bitrix\Main\Config\Option,
    Bitrix\Main\Entity\Base,
    Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

Class kosmos_main extends CModule
{

    var $exclusionAdminFiles;

    function __construct()
    {
        $arModuleVersion = [];
        include(__DIR__ . "/version.php");

        $this->exclusionAdminFiles = [
            '..',
            '.',
            'menu.php',
            'operation_description.php',
            'task_description.php',
        ];

        $this->MODULE_ID = "kosmos.main";
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = Loc::getMessage("KOSMOS_MAIN_MODULE_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("KOSMOS_MAIN_MODULE_DESCRIPTION");

        $this->PARTNER_NAME = Loc::getMessage("KOSMOS_MAIN_PARTNER_NAME");
        $this->PARTNER_URI = Loc::getMessage("KOSMOS_MAIN_PARTNER_URI");

        $this->MODULE_SORT = 1;
        $this->SHOW_SUPER_ADMIN_GROUP_RIGHTS = 'Y';
        $this->MODULE_GROUP_RIGHTS = 'Y';
    }

    public function isVersionD7()
    {
        return CheckVersion(ModuleManager::getVersion('main'), '14.00.00');
    }

    public function isModuleInstalled($module_id)
    {
        return ModuleManager::isModuleInstalled($module_id);
    }

    public function GetPath($notDocumentRoot = false)
    {
        if ($notDocumentRoot) {
            return str_ireplace(Application::getDocumentRoot(), '',
                dirname(__DIR__));
        } else {
            return dirname(__DIR__);
        }
    }

    function InstallDB()
    {
        \Bitrix\Main\Loader::includeModule($this->MODULE_ID);
    }

    function UnInstallDB()
    {
        \Bitrix\Main\Loader::includeModule($this->MODULE_ID);

        Option::delete($this->MODULE_ID);
    }

    function InstallEvents()
    {
    }

    function UnInstallEvents()
    {
    }

    function InstallFiles($arParams = [])
    {
        if (Bitrix\Main\IO\Directory::isDirectoryExists($path = $this->GetPath() . "/install/components")) {
            CopyDirFiles($this->GetPath() . "/install/components",
                $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components", true, true);
        } else {
            throw new Bitrix\Main\IO\InvalidPathException($path);
        }

        if (Bitrix\Main\IO\Directory::isDirectoryExists($path = $this->GetPath() . "/admin")) {
            CopyDirFiles($this->GetPath() . "/install/admin",
                $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin", true, true);

            if ($dir = opendir($path)) {
                while (false !== $item = readdir($dir)) {
                    if (in_array($item, $this->exclusionAdminFiles)) {
                        continue;
                    }

                    file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/" . $this->MODULE_ID . "_" . $item,
                        '<' . '? require($_SERVER["DOCUMENT_ROOT"]."' . $this->GetPath(true) . '/admin/' . $item . '");?' . '>');
                }
                closedir($dir);
            }
        }

        return true;
    }

    function UnInstallFiles()
    {
        Bitrix\Main\IO\Directory::deleteDirectory($_SERVER["DOCUMENT_ROOT"] . "/bitrix/components/kosmos/");

        if (Bitrix\Main\IO\Directory::isDirectoryExists($path = $this->GetPath() . "/admin")) {
            DeleteDirFiles($_SERVER["DOCUMENT_ROOT"] . $this->GetPath() . "/install/admin",
                $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin");

            if ($dir = opendir($path)) {
                while (false !== $item = readdir($dir)) {
                    if (in_array($item, $this->exclusionAdminFiles)) {
                        continue;
                    }

                    Bitrix\Main\IO\File::deleteFile($_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/" . $this->MODULE_ID . "_" . $item);
                }
                closedir($dir);
            }
        }

        return true;
    }

    function DoInstall()
    {
        global $APPLICATION;
        if ($this->isVersionD7()) {
            ModuleManager::registerModule($this->MODULE_ID);

            $this->InstallDB();
            $this->InstallEvents();
            $this->InstallFiles();
        } else {
            $APPLICATION->ThrowException(Loc::getMessage("KOSMOS_MAIN_INSTALL_ERROR_VERSION"));
        }

        $APPLICATION->IncludeAdminFile(Loc::getMessage("KOSMOS_MAIN_INSTALL_TITLE"),
            $this->GetPath() . "/install/step.php");
    }

    function DoUninstall()
    {
        global $APPLICATION;

        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();

        if ($request["step"] < 2) {
            $APPLICATION->IncludeAdminFile(Loc::getMessage("KOSMOS_MAIN_UNINSTALL_TITLE"),
                $this->GetPath() . "/install/unstep1.php");
        } elseif ($request["step"] == 2) {
            $this->UnInstallEvents();
            $this->UnInstallFiles();

            if ($request["savedata"] != "Y") {
                $this->UnInstallDB();
            }

            ModuleManager::unRegisterModule($this->MODULE_ID);

            $APPLICATION->IncludeAdminFile(Loc::getMessage("KOSMOS_MAIN_UNINSTALL_TITLE"),
                $this->GetPath() . "/install/unstep2.php");
        }
    }

    function GetModuleRightList()
    {
        return [
            "reference_id" => ["D", "K", "S", "W"],
            "reference" => [
                "[D] " . Loc::getMessage("KOSMOS_MAIN_DENIED"),
                "[K] " . Loc::getMessage("KOSMOS_MAIN_READ_COMPONENT"),
                "[S] " . Loc::getMessage("KOSMOS_MAIN_WRITE_SETTINGS"),
                "[W] " . Loc::getMessage("KOSMOS_MAIN_FULL"),
            ],
        ];
    }
}