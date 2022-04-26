<?php

// категории тарифов
return array(
    
    // имя
    array(
        "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_rates_category']['ID'],
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
        "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_rates_category']['ID'],
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
    
    // примечение
    array(
        "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_rates_category']['ID'],
        "FIELD_NAME" => "UF_NOTE",
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
            'ru'    => 'Примечение',
            'en'    => 'Note',
        ),
        'LIST_COLUMN_LABEL' => array(
            'ru'    => 'Примечение',
            'en'    => 'Note',
        ),
        'LIST_FILTER_LABEL' => array(
            'ru'    => 'Примечение',
            'en'    => 'Note',
        ),
        'ERROR_MESSAGE'  => array(
            'ru'    => 'Ошибка при заполнении пользовательского свойства UF_NOTE',
            'en'    => 'An error in completing the user field UF_NOTE',
        ),
        'HELP_MESSAGE'      => array(
            'ru'    => '',
            'en'    => '',
        ),
    ),
    
    // питание
    array(
        "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_rates_category']['ID'],
        "FIELD_NAME" => "UF_FOOD_ID",
        "USER_TYPE_ID" => 'hlblock',
        "XML_ID" => "",
        "SORT" => 100,
        "MULTIPLE" => 'N',
        'MANDATORY' => 'N',
        'SHOW_FILTER' => 'N',
        'SHOW_IN_LIST' => 'N',
        'IS_SEARCHABLE' => 'N',
        'SETTINGS' => array(
            'DEFAULT_VALUE' => "",
            'HLBLOCK_ID' => $this->arHighloadblocks['ts_food']['ID'],
            'HLFIELD_ID' => 0,
            'DISPLAY' => 'LIST',
            'LIST_HEIGHT' => 5
        ),
        'EDIT_FORM_LABEL'   => array(
            'ru'    => 'Питание',
            'en'    => 'Food',
        ),
        'LIST_COLUMN_LABEL' => array(
            'ru'    => 'Питание',
            'en'    => 'Food',
        ),
        'LIST_FILTER_LABEL' => array(
            'ru'    => 'Питание',
            'en'    => 'Food',
        ),
        'ERROR_MESSAGE'  => array(
            'ru'    => 'Ошибка при заполнении пользовательского свойства UF_FOOD_ID',
            'en'    => 'An error in completing the user field UF_FOOD_ID',
        ),
        'HELP_MESSAGE'      => array(
            'ru'    => '',
            'en'    => '',
        ),  
    ),
    
    // Лечение
    array(
        "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_rates_category']['ID'],
        "FIELD_NAME" => "UF_TREATMENT_ID",
        "USER_TYPE_ID" => 'hlblock',
        "XML_ID" => "",
        "SORT" => 100,
        "MULTIPLE" => 'N',
        'MANDATORY' => 'N',
        'SHOW_FILTER' => 'N',
        'SHOW_IN_LIST' => 'N',
        'IS_SEARCHABLE' => 'N',
        'SETTINGS' => array(
            'DEFAULT_VALUE' => "",
            'HLBLOCK_ID' => $this->arHighloadblocks['ts_treatment']['ID'],
            'HLFIELD_ID' => 0,
            'DISPLAY' => 'LIST',
            'LIST_HEIGHT' => 5
        ),
        'EDIT_FORM_LABEL'   => array(
            'ru'    => 'Лечение',
            'en'    => 'Treatment',
        ),
        'LIST_COLUMN_LABEL' => array(
            'ru'    => 'Лечение',
            'en'    => 'Treatment',
        ),
        'LIST_FILTER_LABEL' => array(
            'ru'    => 'Лечение',
            'en'    => 'Treatment',
        ),
        'ERROR_MESSAGE'  => array(
            'ru'    => 'Ошибка при заполнении пользовательского свойства UF_TREATMENT_ID',
            'en'    => 'An error in completing the user field UF_TREATMENT_ID',
        ),
        'HELP_MESSAGE'      => array(
            'ru'    => '',
            'en'    => '',
        ),  
    ),
    
);
