<?php
/**
 * Created by PhpStorm.
 * User: kosmos
 * Date: 17.05.2018
 * Time: 6:59
 */

namespace Kosmos\Main\Handlers;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

Loc::loadMessages(__FILE__);

class IBlock
{

    /**
     * @var array
     */
    public $arFields = [];

    /**
     * @var array
     */
    protected $access = [
        'template' => false,
    ];

    /**
     * @var array
     */
    protected $errors = [];


    /**
     * @var self
     */
    private static $instance;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function onAfterIBlockElementAdd(&$arFields)
    {
        $handler = self::getInstance();
        $handler->arFields = $arFields;
        $handler->onAddnUpdate();
        $arFields = $handler->arFields;
    }

    public static function onAfterIBlockElementUpdate(&$arFields)
    {
        $handler = self::getInstance();
        $handler->arFields = $arFields;
        $handler->onAddnUpdate();
        $arFields = $handler->arFields;
    }

    private function onAddnUpdate()
    {
        if ($this->arFields['BREAK'] == 'Y') {
            return;
        }

        switch ($this->arFields['IBLOCK_ID']) {
            case \Kosmos\Main\Helpers\Guide::IBLOCK_ID:
                $this->setFullNames();
                break;
            default:
                break;
        }
    }

    private function setFullNames()
    {
        $arFullnameParts = [
            'LAST_NAME',
            'FIRST_NAME',
            'SECOND_NAME'
        ];

        $arLanguages = [
            'RU',
            'BY',
            'EN'
        ];


        $arSelect = [
            'ID',
            'IBLOCK_ID'
        ];

        foreach($arFullnameParts as $code){
            foreach ($arLanguages as $lang){
                $arSelect[] = 'PROPERTY_' . $code . '_' . $lang;
            }
        }

        $arFilter = [
            '=ID' => $this->arFields['ID'],
            '=IBLOCK_ID' => $this->arFields['IBLOCK_ID']
        ];

        $result = \CIBlockElement::GetList([], $arFilter, false, ['nTopCount' => 1], $arSelect);
        if($row = $result->GetNext()){

            $arProperties = [];

            foreach($arLanguages as $lang){

                if($lang === 'ru'){
                    continue;
                }

                $fullname = [];

                foreach($arFullnameParts as $code){

                    $value = $row['PROPERTY_' . $code . '_' . $lang . '_VALUE'];

                    if($value){
                        $fullname[] = $value;
                    }

                }

                $arProperties['NAME_' . $lang] = implode(' ', $fullname);

            }

            \CIBlockElement::SetPropertyValuesEx($this->arFields['ID'], $this->arFields['IBLOCK_ID'], $arProperties);

            $fullname = [];
            foreach($arFullnameParts as $code){
                $fullname[] = $row['PROPERTY_' . $code . '_RU_VALUE'];
            }
            $fullname = (empty($fullname)) ? $this->arFields['ID'] : implode(' ', $fullname);

            $el = new \CIBlockElement;
            $el->Update($this->arFields['ID'], [
                'NAME' => $fullname,
                'BREAK' => 'Y'
            ]);


        }
    }
}