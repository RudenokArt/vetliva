<?php

namespace Kosmos\Main\Helpers;

use \Bitrix\Main\Localization\Loc;
use Kosmos\Main\Constant;


class FormSimple
{

    private $request;

    private $formId;

    private $siteId;

    private $fake;

    private $captcha_word;

    private $captcha_sid;

    private $result = [];

    private $formName;

    private $formFields = [];

    private $requiredFields = [];

    private $resultId;

    const module_id = "kosmos.main";

    public const FEEDBACK_GUIDE = 77;

    private function __construct()
    {

        $this->result = [
            'success' => false,
            'type' => 'error',
            'title' => Loc::getMessage('T_FORM_ERROR_TITLE'),
            'message' => Loc::getMessage('T_FORM_ERROR_UNKNOWN'),
        ];

    }

    public static function processRequest()
    {
        $form = self::createForm();
        $form->getRequest();
        $form->getDefaultParams();

        try {
            $form->checkSubmitAccess();
            $form->checkCaptcha();
            $form->getOtherParams();
            self::includeModules();
            $form->getFormFields();
            $form->checkErrors();
            $form->sendAnswer();
            $form->sendEmail();
            $form->setSuccessResult();
        }
        catch (\Exception $ex) {
            $form->result['message'] = $ex->getMessage();
        }

        return $form->result;
    }

    private static function createForm()
    {
        $className = __CLASS__;
        return new $className;
    }

    private function getRequest()
    {
        $this->request = \Bitrix\Main\Application::getInstance()
            ->getContext()
            ->getRequest();
    }

    private function getDefaultParams()
    {
        $this->fake = (int)$this->request->getPost('MESSAGE');
        $this->formId = (int)$this->request->getPost('FORM_ID');
    }

    private function checkSubmitAccess()
    {
        if (!(
            $this->request->isPost() &&
            $this->request->isAjaxRequest() &&
            check_bitrix_sessid() &&
            $this->formId > 0 &&
            $this->fake == 1
        )) {
            throw new \Exception(Loc::getMessage('T_FORM_ERROR_SUBMIT_DENIED'));
        }
    }

    private function checkCaptcha()
    {
        if(!$GLOBALS['APPLICATION']->CaptchaCheckCode($this->request->getPost('captcha_word'), $this->request->getPost('captcha_code'))){
            throw new \Exception(Loc::getMessage('T_FORM_ERROR_CAPTCHA'));
        }
    }

    private static function includeModules()
    {
        \Bitrix\Main\Loader::includeModule('iblock');
    }

    private function getFormFields()
    {
        switch ($this->formId) {

            case static::FEEDBACK_GUIDE:
                $this->formFields['NAME'] = $this->request->getPost('NAME');
                $this->formFields['PHONE'] = $this->request->getPost('PHONE');
                $this->formFields['EMAIL'] = $this->request->getPost('EMAIL');
                $this->formFields['MESSAGE'] = $this->request->getPost('MESS');
                $this->formFields['GUIDE_ID'] = (int) $this->request->getPost('GUIDE_ID');

                $this->requiredFields = ['NAME', 'EMAIL', 'PHONE', 'MESSAGE', 'GUIDE_ID'];
                break;

        }
    }

    private function buildAnswerFields()
    {
        $arFields = [];

        foreach ($this->formFields as $field) {
            $arFields[$field['INPUT']] = $this->request->getPost($field['INPUT']);
        }

        return $arFields;
    }

    private function sendAnswer()
    {
        $el = new \CIBlockElement;

        $arFields = [
            'IBLOCK_ID' => $this->formId,
            'PROPERTY_VALUES' => $this->formFields,
            'ACTIVE' => 'N',
        ];

        switch ($this->formId) {
            case static::FEEDBACK_GUIDE:
                $arFields['NAME'] = Loc::getMessage('T_FORM_TITLE_FEEDBACK_GUIDE',
                    ['#TIME#' => date('H:i d.m.Y')]);

                $arFields['DATE_ACTIVE_FROM'] = new \Bitrix\Main\Type\DateTime();

                break;
        }

        $this->resultId = $el->Add($arFields);
        if (!$this->resultId) {
            throw new \Exception(Loc::getMessage('T_FORM_ERROR_WEBFORM_ADD',
                ['#ERROR#' => $el->LAST_ERROR]));
        }
    }

    private function setSuccessResult()
    {
        $this->result = [
            'success' => true,
            'type' => 'success',
            'title' => Loc::getMessage('T_FORM_SUCCESS_TITLE'),
            'message' => Loc::getMessage('T_FORM_SUCCESS_MESSAGE'),
        ];
    }

    private function getEmailFields()
    {
        $time = date('H:i d.m.Y');

        switch ($this->formId) {
            case static::FEEDBACK_GUIDE:

                $arFields = [
                    'DATE' => $time
                ];

                $arFields = array_merge($arFields, $this->formFields);

                break;
        }

        return $arFields;
    }

    private function getOtherParams()
    {
        $this->siteId = $this->request->getPost('SITE_ID');
    }

    private function sendEmail()
    {
        $arFields = $this->getEmailFields();

        switch ($this->formId) {
            case static::FEEDBACK_GUIDE:
                $eventName = 'FORM_ADD_FEEDBACK_GUIDE';

                try{
                    $arFilter = [
                        'IBLOCK_ID' => 75,
                        '=ID' => $this->formFields['GUIDE_ID']
                    ];

                    $arSelect = ['ID', 'IBLOCK_ID', 'PROPERTY_USER'];

                    $userId = false;

                    $result = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
                    if($row = $result->GetNext()){
                        $userId = (int) $row['PROPERTY_USER_VALUE'];
                    }
                    else{
                        throw new \Exception('Guide not found');
                    }

                    if(!$userId){
                        throw new \Exception('Guide is not a user');
                    }

                    $result = \Bitrix\Main\UserTable::getList([
                        'select' => ['EMAIL'],
                        'filter' => ['=ID' => $userId]
                    ]);

                    if($row = $result->fetch()){
                        $arFields['EMAIL_TO'] = $row['EMAIL'];
                    }
                    else{
                        throw new \Exception('User not found');
                    }
                }
                catch(\Exception $e){
                    return;
                }

                break;
            default:
                return;
        }

        $arFiles = [];
        if ($this->formFields['FILE']) {
            $result = \CIBlockElement::GetProperty($this->formId,
                $this->resultId, [], ["CODE" => "FILE"]);
            if ($row = $result->fetch()) {
                $arFiles[] = $row['VALUE'];
            }
        }

        \CEvent::Send($eventName, [$this->siteId], $arFields, 'Y', '',
            $arFiles);
        \CEvent::CheckEvents();
    }

    private function checkErrors()
    {
        foreach ($this->requiredFields as $code) {
            if (!$this->formFields[$code]) {
                throw new \Exception(Loc::getMessage('T_FORM_ERROR_FIELDS_REQUIRED'));
            }
        }
    }
}