<?php

// квоты и наличие мест

return array(
    
    // услуга
    array(
        "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_quotas']['ID'],
        "FIELD_NAME" => "UF_SERVICE_ID",
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
            'HLBLOCK_ID' => $this->arHighloadblocks['ts_services']['ID'],
            'HLFIELD_ID' => 0,
            'DISPLAY' => 'LIST',
            'LIST_HEIGHT' => 5
        ),
        'EDIT_FORM_LABEL'   => array(
            'ru'    => 'Услуга',
            'en'    => 'Service',
        ),
        'LIST_COLUMN_LABEL' => array(
            'ru'    => 'Услуга',
            'en'    => 'Service',
        ),
        'LIST_FILTER_LABEL' => array(
            'ru'    => 'Услуга',
            'en'    => 'Service',
        ),
        'ERROR_MESSAGE'  => array(
            'ru'    => 'Ошибка при заполнении пользовательского свойства UF_SERVICE_ID',
            'en'    => 'An error in completing the user field UF_SERVICE_ID',
        ),
        'HELP_MESSAGE'      => array(
            'ru'    => '',
            'en'    => '',
        ),  
    ),
    
    // дата
    array(
        "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_quotas']['ID'],
        "FIELD_NAME" => "UF_DATE",
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
            'ru'    => 'Дата',
            'en'    => 'Date',
        ),
        'LIST_COLUMN_LABEL' => array(
            'ru'    => 'Дата',
            'en'    => 'Date',
        ),
        'LIST_FILTER_LABEL' => array(
            'ru'    => 'Дата',
            'en'    => 'Date',
        ),
        'ERROR_MESSAGE'  => array(
            'ru'    => 'Ошибка при заполнении пользовательского свойства UF_DATE',
            'en'    => 'An error in completing the user field UF_DATE',
        ),
        'HELP_MESSAGE'      => array(
            'ru'    => '',
            'en'    => '',
        ),  
    ),
    
    // квота
    array(
        "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_quotas']['ID'],
        "FIELD_NAME" => "UF_QUOTE",
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
            'ru'    => 'Квота',
            'en'    => 'Quote',
        ),
        'LIST_COLUMN_LABEL' => array(
            'ru'    => 'Квота',
            'en'    => 'Quote',
        ),
        'LIST_FILTER_LABEL' => array(
           'ru'    => 'Квота',
            'en'    => 'Quote',
        ),
        'ERROR_MESSAGE'  => array(
            'ru'    => 'Ошибка при заполнении пользовательского свойства UF_QUOTE',
            'en'    => 'An error in completing the user field UF_QUOTE',
        ),
        'HELP_MESSAGE'      => array(
            'ru'    => '',
            'en'    => '',
        ),  
    ),
    
    // количество проданных
    array(
        "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_quotas']['ID'],
        "FIELD_NAME" => "UF_SOLD_NUMBER",
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
            'ru'    => 'Количество проданных',
            'en'    => 'Sold number',
        ),
        'LIST_COLUMN_LABEL' => array(
            'ru'    => 'Количество проданных',
            'en'    => 'Sold number',
        ),
        'LIST_FILTER_LABEL' => array(
           'ru'    => 'Количество проданных',
            'en'    => 'Sold number',
        ),
        'ERROR_MESSAGE'  => array(
            'ru'    => 'Ошибка при заполнении пользовательского свойства UF_SOLD_NUMBER',
            'en'    => 'An error in completing the user field UF_SOLD_NUMBER',
        ),
        'HELP_MESSAGE'      => array(
            'ru'    => '',
            'en'    => '',
        ),  
    ),
    
    // стопы
    array(
        "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_quotas']['ID'],
        "FIELD_NAME" => "UF_STOP",
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
            'ru'    => 'Стопы',
            'en'    => 'Stop',
        ),
        'LIST_COLUMN_LABEL' => array(
            'ru'    => 'Стопы',
            'en'    => 'Stop',
        ),
        'LIST_FILTER_LABEL' => array(
            'ru'    => 'Стопы',
            'en'    => 'Stop',
        ),
        'ERROR_MESSAGE'  => array(
            'ru'    => 'Ошибка при заполнении пользовательского свойства UF_STOP',
            'en'    => 'An error in completing the user field UF_STOP',
        ),
        'HELP_MESSAGE'      => array(
            'ru'    => '',
            'en'    => '',
        ),  
    ),
);

