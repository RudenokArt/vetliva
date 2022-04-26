<?php
/**
 * Created by PhpStorm.
 * User: kosmos
 * Date: 12.12.2017
 * Time: 22:20
 */

use Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc;

class HtmlInclude extends CBitrixComponent
{

    protected function checkModules()
    {
        if (!Loader::includeModule("kosmos.main")) {
            throw new Main\LoaderException(Loc::getMessage("KOSMOS_MAIN_MODULE_NOT_INSTALLED"));
        }
    }

    function getResult()
    {
        $arResult = [];

        return $arResult;
    }

    public function executeComponent()
    {
        try {
            $this->includeComponentLang("class.php");

            $this->checkModules();

            $this->arResult = $this->getResult();

            $this->includeComponentTemplate();
        } catch (SystemException $exception) {
            ShowError($exception->getMessage());
        }

    }
}