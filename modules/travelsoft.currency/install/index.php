<?php
use Bitrix\Main\Localization\Loc,
        Bitrix\Main\ModuleManager,
            Bitrix\Main\SiteTable,
                Bitrix\Main\Loader,
                    Bitrix\Main\Config\Option;
                    

Loc::loadMessages(__FILE__);

class travelsoft_currency extends CModule
{
    public $MODULE_ID = "travelsoft.currency";
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_GROUP_RIGHTS = "N";
    protected $namespaceFolder = "travelsoft";
    protected $iblocks_id = null;
    protected $site_id = array();
    protected $iso = "ISO";
    protected $currency = array();
    protected $courses = array();
    protected $base_currency_id = null;
    protected $base_courses_id = null;

    function __construct()
    {
        $arModuleVersion = array();
        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path."/version.php");
        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion))
        {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }
        $this->MODULE_NAME = Loc::getMessage("TRAVELSOFT_CURRENCY_MODULE_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("TRAVELSOFT_CURRENCY_MODULE_DESC");
        $this->PARTNER_NAME = "dimabresky";
        $this->PARTNER_URI = "https://github.com/dimabresky/";
        
        Loader::includeModule('iblock');
        
    }
    
    public function setSiteID() {
        
        $db = SiteTable::getList(array('select' => array('LID')));
        
        while ($res = $db->fetch())
            $this->sete_id[] = $res['LID'];
        
    }
    
    public function createIBlockType($id = 'currency') {
        
        global $DB;

        $arLangs = array('ru' => Array(
                                'NAME'=> 'Валюта',
                                'SECTION_NAME'=> 'Разделы',
                                'ELEMENT_NAME'=> 'Элементы'
                            ),
                         'en' => Array(
                                'NAME'=> 'Currency',
                                'SECTION_NAME'=> 'Sections',
                                'ELEMENT_NAME'=> 'Elements'
                            )
                        );
        foreach ($arLangs as $lang => $val) {
            $LANG[$lang] = $val;
        }
        
        $arFields = Array(
            'ID'=>$id,
            'SECTIONS'=>'N',
            'IN_RSS'=>'N',
            'SORT'=>100,
            'LANG'=> $LANG
        );
        $obBlocktype = new CIBlockType;
        $DB->StartTransaction();
        $res = $obBlocktype->Add($arFields);
        if(!$res) {
           $DB->Rollback();
           throw new Exception($obBlocktype->LAST_ERROR);
        }
        else
           $DB->Commit();
    }
    
    public function createIblock($iblock, $type) {
        global $DB;
        
        $arFields = Array(
            "ACTIVE" => "Y",
            "LIST_PAGE_URL" => "",
            "DETAIL_PAGE_URL" => "",
            "IBLOCK_TYPE_ID" => $type,
            "SITE_ID" => $this->sete_id,
            "SORT" => 100,
            "PICTURE" => null,
            "DESCRIPTION" => "",
            "DESCRIPTION_TYPE" => 'text',
            "GROUP_ID" => Array("2"=>"R"),
            "VERSION" => 2,
        );
        
        switch ($iblock) {
            
            case 'currency':
                
                $arFields['NAME'] = "Валюта";
                $arFields['CODE'] = "currency";

                break;
            
            case 'courses':
                
                $arFields['NAME'] = "Курсы валют";
                $arFields['CODE'] = "currency_courses";
                
                break;
            
            default:
                break;
        }
        
        $ib = new CIBlock;

        $DB->StartTransaction();
        $this->iblocks_id[$iblock] = $ib->Add($arFields);

        if (!$this->iblocks_id[$iblock]) {
            $DB->Rollback(); 
            throw new Exception($ib->LAST_ERROR);
        }
        else
            $DB->Commit();

    }
    
    public function addPropertyISO() {
        
        global $DB;
        $arFields = Array(
            "NAME" => "ISO CODE",
            "ACTIVE" => "Y",
            "SORT" => "100",
            "CODE" => $this->iso,
            "PROPERTY_TYPE" => "S",
            "IBLOCK_ID" => $this->iblocks_id['currency']
        );
        
        $ibp = new CIBlockProperty;
        
        $DB->StartTransaction();
        $ID = $ibp->Add($arFields);
        
        if (!$ID) {
            $DB->Rollback(); 
            throw new Exception($ibp->LAST_ERROR);
        }
        else
            $DB->Commit();
        
    }
    
    public function prepareRequest() {
        
        if (isset($_REQUEST['currency'])) {
            $c = $_REQUEST['currency'];
            foreach ($c['names'] as $k => $v) {
                if ($v !== "" && $c['iso'][$k] !== "") {
                   $this->currency[] = array($v, strtoupper($c['iso'][$k]));
                } 
            }
            
            if (empty($this->currency)) 
                $GLOBALS['ERRORS_FORM'][] = Loc::getMessage('TRAVELSOFT_CURRENCY_CURRENCY_NOT_SET');
        }
   
        if (isset($_REQUEST['course'])) {
            $c = $_REQUEST['course'];
            foreach ($c['iso'] as $k => $v) {
                if ($v !== "" && $c['values'][$k] !== "") {
                   $this->courses[] = array(strtoupper($v), (float)str_replace(',', '.', $c['values'][$k]));
                } 
            }
            
            if (empty($this->courses)) 
                $GLOBALS['ERRORS_FORM'][] = Loc::getMessage('TRAVELSOFT_CURRENCY_COURSES_NOT_SET');
        }
        
    }
    
    public function addCurrency() {
        
        $el = new CIBlockElement;
        $first = true;
        foreach ($this->currency as $v) {
            $arLoadProductArray = Array(
                "IBLOCK_ID"      => $this->iblocks_id['currency'],
                "PROPERTY_VALUES"=> array($this->iso => $v[1]),
                "NAME"           => $v[0],
                "ACTIVE"         => "Y",
            );
            
            $id = $el->Add($arLoadProductArray);
            
            if (!$id)
                throw new Exception($el->LAST_ERROR);
            
            if ($first) {
                $this->base_currency_id = $id;
                $first = false;
            }
        }
        
    }
    
    public function addCourses() {
        global $DB;
        $ibp = new CIBlockProperty;
        foreach ($this->courses as $v) {
            $arFields = Array(
                "NAME" => $v[0],
                "ACTIVE" => "Y",
                "SORT" => "100",
                "CODE" => $v[0],
                "PROPERTY_TYPE" => "N",
                "IBLOCK_ID" => $this->iblocks_id['courses']
            );

            $DB->StartTransaction();
            $ID = $ibp->Add($arFields);

            if (!$ID) {
                $DB->Rollback(); 
                throw new Exception($ibp->LAST_ERROR);
            }
            else {
                $DB->Commit();
                $forAdd[$ID] = $v[1];
            }
        }
        
        if ($forAdd) {
            
            $el = new CIBlockElement;
 
            $arLoadProductArray = Array(
                "IBLOCK_ID"      => $this->iblocks_id['courses'],
                "NAME"           => date('d.m.Y H:i:s'),
                "ACTIVE"         => "Y",
            );

            $id = $el->Add($arLoadProductArray);

            if (!$id)
                throw new Exception($el->LAST_ERROR);
            
            foreach ($forAdd as $k => $v) {
                
                CIBlockElement::SetPropertyValuesEx($id, $this->iblocks_id['courses'], array($k => $v));
                
            }
            
            $this->base_courses_id = $id;
        }  
    }
    
    public function delIblockType() {
        CIBlockType::Delete('currency');
    }
    
    public function delIblocks() {
        CIBlock::Delete(Option::get($this->MODULE_ID, 'currency_iblock_id'));
        CIBlock::Delete(Option::get($this->MODULE_ID, 'courses_iblock_id'));
    }
    
    public function copyFiles() {

        return CopyDirFiles(
                $_SERVER["DOCUMENT_ROOT"]."/local/modules/".$this->MODULE_ID."/install/components/".$this->namespaceFolder."/travelsoft.switch.currency",
                $_SERVER["DOCUMENT_ROOT"]."/local/components/".$this->namespaceFolder."/travelsoft.switch.currency",
                true, true
            );
    }
    
    public function deleteFiles() {
        DeleteDirFilesEx("/local/components/". $this->namespaceFolder."/travelsoft.switch.currency");
	return true;
    }
    
    public function DoInstall()
    {
        try {
            // check dependencies
//            if( ModuleManager::isModuleInstalled("currency") )
//                throw new Exception(Loc::getMessage("TRAVELSOFT_CURRENCY_CURRENCY_MODULE_IS_INSTALL_ERROR"));
            if ( !ModuleManager::isModuleInstalled("iblock") )
                throw new Exception(Loc::getMessage("TRAVELSOFT_CURRENCY_IBLOCK_MODULE_NOT_INSTALL_ERROR"));
            
            $this->prepareRequest();
            
            if ($_REQUEST['step'] == "next" && !empty($this->currency) && !empty($this->courses)) {     
            
                $this->setSiteID();

                $type = "currency";

                // create iblock type
                $this->createIBlockType($type);

                // create iblock currency
                $this->createIblock('currency', $type);

                // add iso property code
                $this->addPropertyISO();
                
                // add currency
                $this->addCurrency();
                
                // create iblock courses currency
                $this->createIblock('courses', $type);
                
                // add courses
                $this->addCourses();
                
                $this->copyFiles();
                
                // save current options
                Option::set($this->MODULE_ID, 'currency_iblock_id', $this->iblocks_id['currency']);
                Option::set($this->MODULE_ID, 'courses_iblock_id', $this->iblocks_id['courses']);
                Option::set($this->MODULE_ID, 'base_currency_id', $this->base_currency_id);
                Option::set($this->MODULE_ID, 'base_courses_id', $this->base_courses_id);
                Option::set($this->MODULE_ID, 'currency_format_decimals', 2);
                Option::set($this->MODULE_ID, 'currency_format_dec_point', '.');
                Option::set($this->MODULE_ID, 'currency_format_thousands_sep', '');
                Option::set($this->MODULE_ID, 'iso_code_property', $this->iso);
                
                // register module
                ModuleManager::registerModule($this->MODULE_ID);
                
                return true;
            } 
            
            $GLOBALS['MODULE_ID'] = $this->MODULE_ID;
            // form add currency and currency courses
            $GLOBALS['APPLICATION']->IncludeAdminFile('Pre-options', $_SERVER["DOCUMENT_ROOT"]."/local/modules/".$this->MODULE_ID."/install/pre_options_form.php");
            
            
        } catch (Exception $ex) {
            $GLOBALS["APPLICATION"]->ThrowException($ex->getMessage());
            $this->DoUninstall();
            return false;
        }
        
        return true;
    }
    
    public function DoUninstall()
    {
        // delete iblocks
        $this->delIblocks();
        
        // delete type
	$this->delIblockType();
        
        // delete files
        $this->deleteFiles();
        
        // remove options
        Option::delete($this->MODULE_ID, array('name' => 'currency_iblock_id'));
        Option::delete($this->MODULE_ID, array('name' => 'courses_iblock_id'));
        Option::delete($this->MODULE_ID, array('name' => 'base_currency_id'));
        Option::delete($this->MODULE_ID, array('name' => 'base_courses_id'));
        Option::delete($this->MODULE_ID, array('name' => 'currency_format_decimals'));
        Option::delete($this->MODULE_ID, array('name' => 'currency_format_dec_point'));
        Option::delete($this->MODULE_ID, array('name' => 'currency_format_thousands_sep'));
        Option::delete($this->MODULE_ID, array('name' => 'iso_code_property'));
        
        // unregister module
        ModuleManager::UnRegisterModule($this->MODULE_ID);
        
        return true;

    }
}
