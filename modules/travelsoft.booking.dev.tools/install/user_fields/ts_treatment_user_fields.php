<?php

// лечение

return array(
    
    // имя
    array(
        "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_treatment']['ID'],
        "FIELD_NAME" => "UF_NAME",
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
            'ru'    => 'Название',
            'en'    => 'Name',
        ),
        'LIST_COLUMN_LABEL' => array(
            'ru'    => 'Название',
            'en'    => 'Name',
        ),
        'LIST_FILTER_LABEL' => array(
            'ru'    => 'Название',
            'en'    => 'Name',
        ),
        'ERROR_MESSAGE'  => array(
            'ru'    => 'Ошибка при заполнении пользовательского свойства UF_NAME',
            'en'    => 'An error in completing the user field UF_NAME',
        ),
        'HELP_MESSAGE'      => array(
            'ru'    => '',
            'en'    => '',
        ),
    ), 
    
    // привязка к пользователю
    array(
        "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_treatment']['ID'],
        "FIELD_NAME" => "UF_USER_ID",
        "USER_TYPE_ID" => 'integer',
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
            'ru'    => 'Привязка к пользователю',
            'en'    => 'User id',
        ),
        'LIST_COLUMN_LABEL' => array(
            'ru'    => 'Привязка к пользователю',
            'en'    => 'User id',
        ),
        'LIST_FILTER_LABEL' => array(
            'ru'    => 'Привязка к пользователю',
            'en'    => 'User id',
        ),
        'ERROR_MESSAGE'  => array(
            'ru'    => 'Ошибка при заполнении пользовательского свойства UF_USER_ID',
            'en'    => 'An error in completing the user field UF_USER_ID',
        ),
        'HELP_MESSAGE'      => array(
            'ru'    => '',
            'en'    => '',
        ),
    ),
    
);