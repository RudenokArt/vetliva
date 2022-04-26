<?php

/**
 * Редактирование данных о компании и реквизитов партнёра
 */

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

class TravelsoftPartnersDataEditComponent extends CBitrixComponent {
    
    /**
     * Ошибки
     * @var array
     */
    protected $arErrors = array();
    
    /**
     *  Объект пользователя в системе
     * @var CUser
     */
    protected $oUser = null;
    
    /**
     * Проверка пользователя
     * @global CUser $USER
     * @throws Exception 
     */
    protected function checkUser () {
        
        global $USER;
        
        $this->oUser = $USER;
        
        $groupProviderID = \Bitrix\Main\Config\Option::get('travelsoft.booking.dev.tools', 'service_provider_group_id');

        $isProvider = in_array($groupProviderID, $this->oUser->GetUserGroupArray()) || $this->oUser->IsAdmin();

        if (!$isProvider) {
            throw new Exception(Loc::getMessage('exception_message'));
        }
    }
    
    protected function setFormFields () {

        $arDbProviderFields = $this->oUser->GetList(
                    ($by="ID"),
                    ($order="desc"),
                    array("ID" => $this->oUser->GetID()),
                    array( "SELECT" => array( "UF_*" ), "FIELDS" => array("*"))
                )->Fetch();

        if ($arDbProviderFields['ID'] <= 0) {
            throw new Exception(Loc::getMessage('exception_db_message'));
        }
        
        $arFormFields = array(
            'tab1' => array(
                "active" => true,
                "name" => Loc::getMessage("tab1_name"),
                "fields" => array(
                    array(
                        "WORK_COMPANY" => array(
                            "type" => 'text',
                            "name" => Loc::getMessage('WORK_COMPANY'),
                            "value" => "",
                            "required" => true
                        ),
                        "WORK_COUNTRY" => array(
                            "type" => 'select',
                            "name" => Loc::getMessage('WORK_COUNTRY'),
                            "value" => "",
                            "required" => true,
                            "placeholder" => Loc::getMessage("CHOOSE_THE_COUNTRY"),
                            'default' => $this->arResult['COUNTRIES']
                        ),
                    ),
                    array(
                        "WORK_CITY" => array(
                            "type" => 'text',
                            "name" => Loc::getMessage('WORK_CITY'),
                            "value" => "",
                            "required" => true
                        ),
                        "WORK_PHONE" => array(
                            "type" => 'text',
                            "name" => Loc::getMessage('WORK_PHONE'),
                            "value" => "",
                            "pattern" => defined(PHONE_REGEXPR) ? PHONE_REGEXPR : "^\+?[0-9,\s]{0,}$",
                            "placeholder" => "+375291111111",
                            "required" => true
                        ),
                    ),
                    array(
                        "WORK_MAILBOX" => array(
                            "type" => 'email',
                            "name" => Loc::getMessage('WORK_MAILBOX'),
                            "value" => "",
                            "required" => true
                        )
                    )
                )
            ),
            "tab2" => array(
                "active" => false,
                "name" => Loc::getMessage('tab2_name'),
                "fields" => array(
                    array(
                        "UF_LEGAL_NAME" => array(
                            "type" => 'text',
                            "name" => Loc::getMessage("UF_LEGAL_NAME"),
                            "value" => "",
                            "required" => true
                        ),
                        "UF_LEGAL_ADDRESS" => array(
                            "type" => 'text',
                            "name" => Loc::getMessage('UF_LEGAL_ADDRESS'),
                            "value" => "",
                            "required" => true
                        ),
                    ),
                    array(
                        "UF_BANK_NAME" => array(
                            "type" => 'text',
                            "name" => Loc::getMessage('UF_BANK_NAME'),
                            "value" => "",
                            "required" => true
                        ),
                        "UF_BANK_ADDRESS" => array(
                            "type" => 'text',
                            "name" => Loc::getMessage('UF_BANK_ADDRESS'),
                            "value" => "",
                            "required" => true
                        ),
                    ),
                    array(
                        "UF_BANK_CODE" => array(
                            "type" => 'text',
                            "name" => Loc::getMessage('UF_BANK_CODE'),
                            "value" => "",
                            "required" => true
                        ),
                        "UF_CHECKING_ACCOUNT" => array(
                            "type" => 'text',
                            "name" => Loc::getMessage('UF_CHECKING_ACCOUNT'),
                            "value" => "",
                            "required" => true
                        ),
                   ),
                   array(
                        "UF_UNP" => array(
                            "type" => 'text',
                            "name" => Loc::getMessage("UF_UNP"),
                            "value" => "",
                            "required" => true
                        ),
                        "UF_OKPO" => array(
                            "type" => 'text',
                            "name" => Loc::getMessage('UF_OKPO'),
                            "value" => "",
                            "required" => true
                        )
                   )    
                ) 
            ),
            "tab3" => array(
                "active" => false,
                "name" => Loc::getMessage('tab3_name'),
                'fields' => array(
                    array(
                        "WORK_NOTES" => array(
                            "type" => 'textarea',
                            "name" => Loc::getMessage('WORK_NOTES'),
                            "value" => "",
                            "required" => false
                        )
                    )
                )
            )
        );

        foreach ($arFormFields as $tab => &$arTab) {
            foreach ($arTab['fields'] as $key => &$arFields) {
                foreach ($arFields as $field => &$arFieldsVal) {
                    $arFieldsVal['value'] = $arDbProviderFields[$field];
                }
            }
        }
        
        $this->arResult['FORM_FIELDS'] = $arFormFields;
        
    }
    
    protected function checkRequest() {
        
        $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
        
        if ($request->isPost() && $request->getPost('submit') != "" &&
                check_bitrix_sessid() && $request->getPost('IS_PROVIDER_EDIT') == "Y") {
                        
                        foreach ($this->arResult['FORM_FIELDS'] as $tab => &$arTab) {
                            foreach ($arTab['fields'] as $key => &$arFields) {
                                foreach ($arFields as $field => &$arFieldsVal) {
                                    $value = $request->getPost($field);
                                    if ($this->_checkField($field, $value, $arTab['name'] . ": ")) {
                                            $arFieldsVal['value'] =  $arForUpdate[$field] = $value;
                                    }
                                }
                            }
                        }
                        
                        if (empty($this->arErrors)) {
                            $this->oUser->Update($this->oUser->GetID(), $arForUpdate);
                            $this->arResult['MESSOK'] = Loc::getMessage("MESSOK");
                        }
                        
                        $this->arResult['ERRORS'] = $this->arErrors;
                        
                }
    }
    
    /**
     * Проверка поля формы
     * @param string $name
     * @param mixed $value
     * @return boolean
     */
    protected function _checkField ($name, $value, $messPart = "") {
        
        if (function_exists("__checkFormFields"))
            
            return __checkFormFields($name, $value, $this->arErrors, $messPart);
        
        else {
        
            $error = ""; 

            switch ($name) {

                    case "WORK_COMPANY":
                    case "WORK_NOTES":
                    case "WORK_CITY":
                    case "UF_LEGAL_NAME":
                    case "UF_LEGAL_ADDRESS":
                    case "UF_BANK_NAME":
                    case "UF_BANK_ADDRESS":
                    case "UF_BANK_CODE":
                    case "UF_CHECKING_ACCOUNT":
                    case "UF_UNP":
                    case "UF_OKPO":

                        if ($value == "") {
                            $this->arErrors[] = Loc::getMessage("empty_field_error_text", array(
                                        '#messPart#' => $messPart, "#fieldName#" => Loc::getMessage($name)));
                            return false;
                        }

                        if ($name == "WORK_PHONE") {
                            $pattern = defined("PHONE_REGEXPR") ? PHONE_REGEXPR  : "^\+?[0-9,\s]{0,}$";
                            if (!preg_match("#".$pattern."#", $value))
                                    $this->arErrors[] =  Loc::getMessage("wrong_format_field_error_text", array(
                                        '#messPart#' => $messPart, "#fieldName#" => Loc::getMessage($name)));
                            return false;
                        }

                        break;
                    
                    case "WORK_COUNTRY":

                        if (!in_array($value, $this->arResult['COUNTRIES']['reference_id'])) {
                                $this->arErrors[] = Loc::getMessage("wrong_field_error_text", array(
                                        '#messPart#' => $messPart, "#fieldName#" => Loc::getMessage($name)));
                                return false;
                        }

                        break;
                        
                    case "WORK_MAILBOX": 

                        if (!check_email($value)) {
                            $this->arErrors[] = Loc::getMessage("wrong_field_error_text", array(
                                        '#messPart#' => $messPart, "#fieldName#" => Loc::getMessage($name)));
                            return false;
                        } 

                        break;
                        
                    case "WORK_PHONE":
                        
                        return true;
                        
                        break;

                    default: 

                        $this->arErrors[] = Loc::getMessage("default_field_error_text", array(
                                        '#messPart#' => $messPart, "#fieldName#" => Loc::getMessage($name)));
                        return false;

                        break;

            }

            return true;
            
        }
        
    }
    
    /**
     * Component body
     */
    public function executeComponent() {
        
        try {
            
            $this->arResult['COUNTRIES'] = GetCountryArray();
            
            $this->checkUser();

            $this->setFormFields();

            $this->checkRequest();

            $this->IncludeComponentTemplate();
            
        } catch (Exception $ex) {
            ShowError($ex->getMessage());
        }

    }
    
}
