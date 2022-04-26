<?php

/* 
 * пользовательские поля поставщика
 */

return array (
                // активность поставщика
                array(
                    "ENTITY_ID" => 'USER',
                    "FIELD_NAME" => "UF_PARTNER_ACTIVE",
                    "USER_TYPE_ID" => 'boolean',
                    "XML_ID" => "",
                    "SORT" => 100,
                    "MULTIPLE" => 'N',
                        'MANDATORY' => 'N',
                    'SHOW_FILTER' => 'N',
                    'SHOW_IN_LIST' => 'N',
                    'IS_SEARCHABLE' => 'N',
                    'SETTINGS' => array(
                            'DEFAULT_VALUE' => "0",
                            'DISPLAY' => 'CHECKBOX',
                        ),
                    'EDIT_FORM_LABEL'   => array(
                        'ru'    => 'Поставщик активен',
                        'en'    => 'Active provider',
                    ),
                    'LIST_COLUMN_LABEL' => array(
                        'ru'    => 'Поставщик активен',
                        'en'    => 'Active provider',
                    ),
                    'LIST_FILTER_LABEL' => array(
                        'ru'    => 'Поставщик активен',
                        'en'    => 'Active provider',
                    ),
                    'ERROR_MESSAGE'  => array(
                        'ru'    => 'Ошибка при заполнении пользовательского свойства UF_ACTIVE',
                        'en'    => 'An error in completing the user field UF_ACTIVE',
                    ),
                    'HELP_MESSAGE'      => array(
                        'ru'    => '',
                        'en'    => '',
                    ),
                ),

                // ID поставщика в ПК Мастертур
                 array(
                        "ENTITY_ID" => 'USER',
                        "FIELD_NAME" => "UF_USER_MT_ID",
                        "USER_TYPE_ID" => 'string',
                        "XML_ID" => "",
                        "SORT" => 100,
                        "MULTIPLE" => 'N',
                            'MANDATORY' => 'N',
                        'SHOW_FILTER' => 'N',
                        'SHOW_IN_LIST' => 'N',
                        'IS_SEARCHABLE' => 'N',
                        'SETTINGS' => array(
                            'DEFAULT_VALUE' => "",
                            'SIZE' => '20',
                            'ROWS' => 1,
                            'MIN_LENGTH' => 0,
                            'MAX_LENGTH' => 0,
                            'REGEXP' => ''
                        ),
                        'EDIT_FORM_LABEL'   => array(
                            'ru'    => 'ID поставщика в ПК Мастертур',
                            'en'    => 'MT ID',
                        ),
                        'LIST_COLUMN_LABEL' => array(
                            'ru'    => 'ID поставщика в ПК Мастертур',
                            'en'    => 'MT ID',
                        ),
                        'LIST_FILTER_LABEL' => array(
                            'ru'    => 'ID поставщика в ПК Мастертур',
                            'en'    => 'MT ID',
                        ),
                        'ERROR_MESSAGE'  => array(
                            'ru'    => 'Ошибка при заполнении пользовательского свойства UF_USER_MT_ID',
                            'en'    => 'An error in completing the user field UF_USER_MT_ID',
                        ),
                        'HELP_MESSAGE'      => array(
                            'ru'    => '',
                            'en'    => '',
                        ),
                    ),
    
                    // реквизиты
    
                    // юр. название
                    array(
                        "ENTITY_ID" => 'USER',
                        "FIELD_NAME" => "UF_LEGAL_NAME",
                        "USER_TYPE_ID" => 'string',
                        "XML_ID" => "",
                        "SORT" => 100,
                        "MULTIPLE" => 'N',
                            'MANDATORY' => 'N',
                        'SHOW_FILTER' => 'N',
                        'SHOW_IN_LIST' => 'N',
                        'IS_SEARCHABLE' => 'N',
                        'SETTINGS' => array(
                            'DEFAULT_VALUE' => "",
                            'SIZE' => '20',
                            'ROWS' => 1,
                            'MIN_LENGTH' => 0,
                            'MAX_LENGTH' => 0,
                            'REGEXP' => ''
                        ),
                        'EDIT_FORM_LABEL'   => array(
                            'ru'    => 'юр. название',
                            'en'    => 'Legal name',
                        ),
                        'LIST_COLUMN_LABEL' => array(
                             'ru'    => 'юр. название',
                            'en'    => 'Legal name',
                        ),
                        'LIST_FILTER_LABEL' => array(
                            'ru'    => 'юр. название',
                            'en'    => 'Legal name',
                        ),
                        'ERROR_MESSAGE'  => array(
                            'ru'    => 'Ошибка при заполнении пользовательского свойства UF_LEGAL_NAME',
                            'en'    => 'An error in completing the user field UF_LEGAL_NAME',
                        ),
                        'HELP_MESSAGE'      => array(
                            'ru'    => '',
                            'en'    => '',
                        ),
                    ),
    
                    // юр. адрес
                    array(
                        "ENTITY_ID" => 'USER',
                        "FIELD_NAME" => "UF_LEGAL_ADDRESS",
                        "USER_TYPE_ID" => 'string',
                        "XML_ID" => "",
                        "SORT" => 100,
                        "MULTIPLE" => 'N',
                            'MANDATORY' => 'N',
                        'SHOW_FILTER' => 'N',
                        'SHOW_IN_LIST' => 'N',
                        'IS_SEARCHABLE' => 'N',
                        'SETTINGS' => array(
                            'DEFAULT_VALUE' => "",
                            'SIZE' => '20',
                            'ROWS' => 1,
                            'MIN_LENGTH' => 0,
                            'MAX_LENGTH' => 0,
                            'REGEXP' => ''
                        ),
                        'EDIT_FORM_LABEL'   => array(
                            'ru'    => 'юр. адрес',
                            'en'    => 'Legal address',
                        ),
                        'LIST_COLUMN_LABEL' => array(
                            'ru'    => 'юр. адрес',
                            'en'    => 'Legal address',
                        ),
                        'LIST_FILTER_LABEL' => array(
                            'ru'    => 'юр. адрес',
                            'en'    => 'Legal address',
                        ),
                        'ERROR_MESSAGE'  => array(
                            'ru'    => 'Ошибка при заполнении пользовательского свойства UF_LEGAL_ADDRESS',
                            'en'    => 'An error in completing the user field UF_LEGAL_ADDRESS',
                        ),
                        'HELP_MESSAGE'      => array(
                            'ru'    => '',
                            'en'    => '',
                        ),
                    ),
    
                    // наименование банка
                    array(
                        "ENTITY_ID" => 'USER',
                        "FIELD_NAME" => "UF_BANK_NAME",
                        "USER_TYPE_ID" => 'string',
                        "XML_ID" => "",
                        "SORT" => 100,
                        "MULTIPLE" => 'N',
                        'MANDATORY' => 'N',
                        'SHOW_FILTER' => 'N',
                        'SHOW_IN_LIST' => 'N',
                        'IS_SEARCHABLE' => 'N',
                        'SETTINGS' => array(
                            'DEFAULT_VALUE' => "",
                            'SIZE' => '20',
                            'ROWS' => 1,
                            'MIN_LENGTH' => 0,
                            'MAX_LENGTH' => 0,
                            'REGEXP' => ''
                        ),
                        'EDIT_FORM_LABEL'   => array(
                            'ru'    => 'наименование банка',
                            'en'    => 'Bank name',
                        ),
                        'LIST_COLUMN_LABEL' => array(
                            'ru'    => 'наименование банка',
                            'en'    => 'Bank name',
                        ),
                        'LIST_FILTER_LABEL' => array(
                            'ru'    => 'наименование банка',
                            'en'    => 'Bank name',
                        ),
                        'ERROR_MESSAGE'  => array(
                            'ru'    => 'Ошибка при заполнении пользовательского свойства UF_BANK_NAME',
                            'en'    => 'An error in completing the user field UF_BANK_NAME',
                        ),
                        'HELP_MESSAGE'      => array(
                            'ru'    => '',
                            'en'    => '',
                        ),
                    ),
    
                    // адресс банка
                    array(
                        "ENTITY_ID" => 'USER',
                        "FIELD_NAME" => "UF_BANK_ADDRESS",
                        "USER_TYPE_ID" => 'string',
                        "XML_ID" => "",
                        "SORT" => 100,
                        "MULTIPLE" => 'N',
                        'MANDATORY' => 'N',
                        'SHOW_FILTER' => 'N',
                        'SHOW_IN_LIST' => 'N',
                        'IS_SEARCHABLE' => 'N',
                        'SETTINGS' => array(
                            'DEFAULT_VALUE' => "",
                            'SIZE' => '20',
                            'ROWS' => 1,
                            'MIN_LENGTH' => 0,
                            'MAX_LENGTH' => 0,
                            'REGEXP' => ''
                        ),
                        'EDIT_FORM_LABEL'   => array(
                            'ru'    => 'адресс банка',
                            'en'    => 'Bank address',
                        ),
                        'LIST_COLUMN_LABEL' => array(
                            'ru'    => 'адресс банка',
                            'en'    => 'Bank address',
                        ),
                        'LIST_FILTER_LABEL' => array(
                            'ru'    => 'адресс банка',
                            'en'    => 'Bank address',
                        ),
                        'ERROR_MESSAGE'  => array(
                            'ru'    => 'Ошибка при заполнении пользовательского свойства UF_BANK_NAME',
                            'en'    => 'An error in completing the user field UF_BANK_NAME',
                        ),
                        'HELP_MESSAGE'      => array(
                            'ru'    => '',
                            'en'    => '',
                        ),
                    ),
    
                    // код банка
                    array(
                        "ENTITY_ID" => 'USER',
                        "FIELD_NAME" => "UF_BANK_CODE",
                        "USER_TYPE_ID" => 'string',
                        "XML_ID" => "",
                        "SORT" => 100,
                        "MULTIPLE" => 'N',
                        'MANDATORY' => 'N',
                        'SHOW_FILTER' => 'N',
                        'SHOW_IN_LIST' => 'N',
                        'IS_SEARCHABLE' => 'N',
                        'SETTINGS' => array(
                            'DEFAULT_VALUE' => "",
                            'SIZE' => '20',
                            'ROWS' => 1,
                            'MIN_LENGTH' => 0,
                            'MAX_LENGTH' => 0,
                            'REGEXP' => ''
                        ),
                        'EDIT_FORM_LABEL'   => array(
                            'ru'    => 'код банка',
                            'en'    => 'Bank code',
                        ),
                        'LIST_COLUMN_LABEL' => array(
                            'ru'    => 'код банка',
                            'en'    => 'Bank code',
                        ),
                        'LIST_FILTER_LABEL' => array(
                            'ru'    => 'код банка',
                            'en'    => 'Bank code',
                        ),
                        'ERROR_MESSAGE'  => array(
                            'ru'    => 'Ошибка при заполнении пользовательского свойства UF_BANK_CODE',
                            'en'    => 'An error in completing the user field UF_BANK_CODE',
                        ),
                        'HELP_MESSAGE'      => array(
                            'ru'    => '',
                            'en'    => '',
                        ),
                    ),
    
                    // расчётный счёт
                    array(
                        "ENTITY_ID" => 'USER',
                        "FIELD_NAME" => "UF_CHECKING_ACCOUNT",
                        "USER_TYPE_ID" => 'string',
                        "XML_ID" => "",
                        "SORT" => 100,
                        "MULTIPLE" => 'N',
                        'MANDATORY' => 'N',
                        'SHOW_FILTER' => 'N',
                        'SHOW_IN_LIST' => 'N',
                        'IS_SEARCHABLE' => 'N',
                        'SETTINGS' => array(
                            'DEFAULT_VALUE' => "",
                            'SIZE' => '20',
                            'ROWS' => 1,
                            'MIN_LENGTH' => 0,
                            'MAX_LENGTH' => 0,
                            'REGEXP' => ''
                        ),
                        'EDIT_FORM_LABEL'   => array(
                            'ru'    => 'Расчётный счёт',
                            'en'    => 'Checking account',
                        ),
                        'LIST_COLUMN_LABEL' => array(
                            'ru'    => 'Расчётный счёт',
                            'en'    => 'Checking account',
                        ),
                        'LIST_FILTER_LABEL' => array(
                            'ru'    => 'Расчётный счёт',
                            'en'    => 'Checking account',
                        ),
                        'ERROR_MESSAGE'  => array(
                            'ru'    => 'Ошибка при заполнении пользовательского свойства UF_CHECKING_ACCOUNT',
                            'en'    => 'An error in completing the user field UF_CHECKING_ACCOUNT',
                        ),
                        'HELP_MESSAGE'      => array(
                            'ru'    => '',
                            'en'    => '',
                        ),
                    ),
    
                    // УНП
                    array(
                        "ENTITY_ID" => 'USER',
                        "FIELD_NAME" => "UF_UNP",
                        "USER_TYPE_ID" => 'string',
                        "XML_ID" => "",
                        "SORT" => 100,
                        "MULTIPLE" => 'N',
                        'MANDATORY' => 'N',
                        'SHOW_FILTER' => 'N',
                        'SHOW_IN_LIST' => 'N',
                        'IS_SEARCHABLE' => 'N',
                        'SETTINGS' => array(
                            'DEFAULT_VALUE' => "",
                            'SIZE' => '20',
                            'ROWS' => 1,
                            'MIN_LENGTH' => 0,
                            'MAX_LENGTH' => 0,
                            'REGEXP' => ''
                        ),
                        'EDIT_FORM_LABEL'   => array(
                            'ru'    => 'УНП',
                            'en'    => 'UNP',
                        ),
                        'LIST_COLUMN_LABEL' => array(
                            'ru'    => 'УНП',
                            'en'    => 'UNP',
                        ),
                        'LIST_FILTER_LABEL' => array(
                            'ru'    => 'УНП',
                            'en'    => 'UNP',
                        ),
                        'ERROR_MESSAGE'  => array(
                            'ru'    => 'Ошибка при заполнении пользовательского свойства UF_UNP',
                            'en'    => 'An error in completing the user field UF_UNP',
                        ),
                        'HELP_MESSAGE'      => array(
                            'ru'    => '',
                            'en'    => '',
                        ),
                    ),
    
                     // ОКПО
                    array(
                        "ENTITY_ID" => 'USER',
                        "FIELD_NAME" => "UF_OKPO",
                        "USER_TYPE_ID" => 'string',
                        "XML_ID" => "",
                        "SORT" => 100,
                        "MULTIPLE" => 'N',
                        'MANDATORY' => 'N',
                        'SHOW_FILTER' => 'N',
                        'SHOW_IN_LIST' => 'N',
                        'IS_SEARCHABLE' => 'N',
                        'SETTINGS' => array(
                            'DEFAULT_VALUE' => "",
                            'SIZE' => '20',
                            'ROWS' => 1,
                            'MIN_LENGTH' => 0,
                            'MAX_LENGTH' => 0,
                            'REGEXP' => ''
                        ),
                        'EDIT_FORM_LABEL'   => array(
                            'ru'    => 'ОКПО',
                            'en'    => 'OKPO',
                        ),
                        'LIST_COLUMN_LABEL' => array(
                           'ru'    => 'ОКПО',
                            'en'    => 'OKPO',
                        ),
                        'LIST_FILTER_LABEL' => array(
                            'ru'    => 'ОКПО',
                            'en'    => 'OKPO',
                        ),
                        'ERROR_MESSAGE'  => array(
                            'ru'    => 'Ошибка при заполнении пользовательского свойства UF_OKPO',
                            'en'    => 'An error in completing the user field UF_OKPO',
                        ),
                        'HELP_MESSAGE'      => array(
                            'ru'    => '',
                            'en'    => '',
                        ),
                    ),
            );