<?php
/**
 * Bitrix component for converting currecny of application
 *
 * @author dimabresky
 */
class CurrencySwitchComponent
                extends CBitrixComponent{

    public $dr;
    
    // include component_prolog.php
    public function includeComponentProlog()
    {
        $file = "component_prolog.php";

        $template_name = $this->GetTemplateName();
        if ($template_name == "")
            $template_name = ".default";

        $relative_path = $this->GetRelativePath();

        $this->dr = Bitrix\Main\Application::getDocumentRoot();
        
        $file_path = $this->dr . SITE_TEMPLATE_PATH . "/components" . $relative_path . "/" . $template_name . "/" . $file;

        $arParams = &$this->arParams;

        if(file_exists($file_path))
            require $file_path;
        else
        {	
            $file_path = $this->dr . "/bitrix/templates/.default/components" . $relative_path . "/" . $template_name . "/" . $file;

            if(file_exists($file_path))
                require $file_path;
            else
            {
                $file_path = $this->dr . $this->__path . "/templates/" . $template_name . "/" . $file;

                if(file_exists($file_path))
                    require $file_path;
                else {
                    
                    $file_path = $this->dr . "/local/components" . $relative_path . "/templates/" . $template_name . "/" . $file;
                   
                    if(file_exists($file_path))
                        require $file_path;
                }
            }
        }
    }
    
    // execute component
    public function executeComponent() {
        
        $this->includeComponentProlog();

        \Bitrix\Main\Loader::includeModule('travelsoft.currency');

        //устанавливаем для com валюту в евро по-умолчанию
        if (LANGUAGE_ID == "en" &&  !$_SESSION['current_currency']) {
           $_SESSION['current_currency'] = 4;
        }

        $currency = \travelsoft\Currency::getInstance();
           
        //if ($this->startResultCache(false, array($currency))) {

                $this->arResult['CURRENCY'] = $currency->get('currency');

                $this->arResult['CURRENT_CURRENCY'] = $currency->get('current_currency');

                $this->arResult['CURRENCY_FORM_ID'] = 'currency-form';

                $this->IncludeComponentTemplate();
        //}
    } 
   
    
}
