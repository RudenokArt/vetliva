<?php

// для услуг
return array(
        
        // имя
        array(
            "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_services']['ID'],
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
        
        // название типа услуги
        array(
            "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_services']['ID'],
            "FIELD_NAME" => "UF_SERVICE_TYPE_NAME",
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
                'ru'    => 'Тип услуги',
                'en'    => 'Service type name',
            ),
            'LIST_COLUMN_LABEL' => array(
                'ru'    => 'Тип услуги',
                'en'    => 'Service type name',
            ),
            'LIST_FILTER_LABEL' => array(
                'ru'    => 'Тип услуги',
                'en'    => 'Service type name',
            ),
            'ERROR_MESSAGE'  => array(
                'ru'    => 'Ошибка при заполнении пользовательского свойства UF_SERVICE_TYPE_NAME',
                'en'    => 'An error in completing the user field UF_SERVICE_TYPE_NAME',
            ),
            'HELP_MESSAGE'      => array(
                'ru'    => '',
                'en'    => '',
            ),
        ),
        
        // привязка к элементу инфоблока услуги
        array(
            "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_services']['ID'],
            "FIELD_NAME" => "UF_IBLOCK_ELEMENT_ID",
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
                'ru'    => 'Привязка к элементу инфоблока услуги',
                'en'    => 'Link to element',
            ),
            'LIST_COLUMN_LABEL' => array(
                'ru'    => 'Привязка к элементу инфоблока услуги',
                'en'    => 'Link to element',
            ),
            'LIST_FILTER_LABEL' => array(
                'ru'    => 'Привязка к элементу инфоблока услуги',
                'en'    => 'Link to element',
            ),
            'ERROR_MESSAGE'  => array(
                'ru'    => 'Ошибка при заполнении пользовательского свойства UF_IBLOCK_ELEMENT_ID',
                'en'    => 'An error in completing the user field UF_IBLOCK_ELEMENT_ID',
            ),
            'HELP_MESSAGE'      => array(
                'ru'    => '',
                'en'    => '',
            ),
        ),
        
        // количество взрослых
        array(
            "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_services']['ID'],
            "FIELD_NAME" => "UF_ADULTS",
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
                'ru'    => 'Количество взрослых',
                'en'    => 'Adults',
            ),
            'LIST_COLUMN_LABEL' => array(
                'ru'    => 'Количество взрослых',
                'en'    => 'Adults',
            ),
            'LIST_FILTER_LABEL' => array(
                'ru'    => 'Количество взрослых',
                'en'    => 'Adults',
            ),
            'ERROR_MESSAGE'  => array(
                'ru'    => 'Ошибка при заполнении пользовательского свойства UF_ADULTS',
                'en'    => 'An error in completing the user field UF_ADULTS',
            ),
            'HELP_MESSAGE'      => array(
                'ru'    => '',
                'en'    => '',
            ),
        ),
    
    // количество детей
        array(
            "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_services']['ID'],
            "FIELD_NAME" => "UF_CHILDREN",
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
                'ru'    => 'Количество детей',
                'en'    => 'Children',
            ),
            'LIST_COLUMN_LABEL' => array(
                'ru'    => 'Количество детей',
                'en'    => 'Children',
            ),
            'LIST_FILTER_LABEL' => array(
                'ru'    => 'Количество детей',
                'en'    => 'Children',
            ),
            'ERROR_MESSAGE'  => array(
                'ru'    => 'Ошибка при заполнении пользовательского свойства UF_CHILDREN',
                'en'    => 'An error in completing the user field UF_CHILDREN',
            ),
            'HELP_MESSAGE'      => array(
                'ru'    => '',
                'en'    => '',
            ),
        ), 
    
    // привязка к пользователю
    array(
        "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_services']['ID'],
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
