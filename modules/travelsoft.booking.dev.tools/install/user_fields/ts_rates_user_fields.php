<?php

// тарифы
return array(
    
    // имя
    array(
        "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_rates']['ID'],
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
    
    // минимальный возраст
    array(
        "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_rates']['ID'],
        "FIELD_NAME" => "UF_MIN_AGE",
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
            'ru'    => 'Минимальный возраст',
            'en'    => 'Min age',
        ),
        'LIST_COLUMN_LABEL' => array(
            'ru'    => 'Минимальный возраст',
            'en'    => 'Min age',
        ),
        'LIST_FILTER_LABEL' => array(
            'ru'    => 'Минимальный возраст',
            'en'    => 'Min age',
        ),
        'ERROR_MESSAGE'  => array(
            'ru'    => 'Ошибка при заполнении пользовательского свойства UF_MIN_AGE',
            'en'    => 'An error in completing the user field UF_MIN_AGE',
        ),
        'HELP_MESSAGE'      => array(
            'ru'    => '',
            'en'    => '',
        ),
    ),
    
    // максимальный возраст
    array(
        "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_rates']['ID'],
        "FIELD_NAME" => "UF_MAX_AGE",
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
            'ru'    => 'Максимальный возраст',
            'en'    => 'Max age',
        ),
        'LIST_COLUMN_LABEL' => array(
            'ru'    => 'Максимальный возраст',
            'en'    => 'Max age',
        ),
        'LIST_FILTER_LABEL' => array(
            'ru'    => 'Максимальный возраст',
            'en'    => 'Max age',
        ),
        'ERROR_MESSAGE'  => array(
            'ru'    => 'Ошибка при заполнении пользовательского свойства UF_MAX_AGE',
            'en'    => 'An error in completing the user field UF_MAX_AGE',
        ),
        'HELP_MESSAGE'      => array(
            'ru'    => '',
            'en'    => '',
        ),
    ),
    
    // активность
    array(
        "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_rates']['ID'],
        "FIELD_NAME" => "UF_ACTIVE",
        "USER_TYPE_ID" => 'boolean',
        "XML_ID" => "",
        "SORT" => 100,
        "MULTIPLE" => 'N',
            'MANDATORY' => 'N',
        'SHOW_FILTER' => 'N',
        'SHOW_IN_LIST' => 'N',
        'IS_SEARCHABLE' => 'N',
        'SETTINGS' => array(
                'DEFAULT_VALUE' => "1",
                'DISPLAY' => 'CHECKBOX',
            ),
        'EDIT_FORM_LABEL'   => array(
            'ru'    => 'Активность',
            'en'    => 'Active',
        ),
        'LIST_COLUMN_LABEL' => array(
            'ru'    => 'Активность',
            'en'    => 'Active',
        ),
        'LIST_FILTER_LABEL' => array(
            'ru'    => 'Активность',
            'en'    => 'Active',
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
    
    // Дата активности с
    array(
        "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_rates']['ID'],
        "FIELD_NAME" => "UF_DATE_ACTIVE_FROM",
        "USER_TYPE_ID" => 'datetime',
        "XML_ID" => "",
        "SORT" => 100,
       "MULTIPLE" => 'N',
            'MANDATORY' => 'N',
        'SHOW_FILTER' => 'N',
        'SHOW_IN_LIST' => 'N',
        'IS_SEARCHABLE' => 'N',
        'SETTINGS' => array(
            'DEFAULT_VALUE' => "",
        ),
        'EDIT_FORM_LABEL'   => array(
            'ru'    => 'Дата активности с',
            'en'    => 'Date active from',
        ),
        'LIST_COLUMN_LABEL' => array(
            'ru'    => 'Дата активности с',
            'en'    => 'Date active from',
        ),
        'LIST_FILTER_LABEL' => array(
            'ru'    => 'Дата активности с',
            'en'    => 'Date active from',
        ),
        'ERROR_MESSAGE'  => array(
            'ru'    => 'Ошибка при заполнении пользовательского свойства UF_DATE_ACTIVE_FROM',
            'en'    => 'An error in completing the user field UF_DATE_ACTIVE_FROM',
        ),
        'HELP_MESSAGE'      => array(
            'ru'    => '',
            'en'    => '',
        ),  
    ),
    
    // Дата активности по
    array(
        "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_rates']['ID'],
        "FIELD_NAME" => "UF_DATE_ACTIVE_TO",
        "USER_TYPE_ID" => 'datetime',
        "XML_ID" => "",
        "SORT" => 100,
        "MULTIPLE" => 'N',
            'MANDATORY' => 'N',
        'SHOW_FILTER' => 'N',
        'SHOW_IN_LIST' => 'N',
        'IS_SEARCHABLE' => 'N',
        'SETTINGS' => array(
            'DEFAULT_VALUE' => "",
        ),
        'EDIT_FORM_LABEL'   => array(
            'ru'    => 'Дата активности по',
            'en'    => 'Date active to',
        ),
        'LIST_COLUMN_LABEL' => array(
            'ru'    => 'Дата активности по',
            'en'    => 'Date active to',
        ),
        'LIST_FILTER_LABEL' => array(
            'ru'    => 'Дата активности по',
            'en'    => 'Date active to',
        ),
        'ERROR_MESSAGE'  => array(
            'ru'    => 'Ошибка при заполнении пользовательского свойства UF_DATE_ACTIVE_TO',
            'en'    => 'An error in completing the user field UF_DATE_ACTIVE_TO',
        ),
        'HELP_MESSAGE'      => array(
            'ru'    => '',
            'en'    => '',
        ),  
    ),
    
    // привязка к пользователю
    array(
        "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_rates']['ID'],
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