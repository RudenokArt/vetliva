<?php

// трансфер

return array(
    
    // точки локализаций поставщика
    array(
        "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_transfer_by_kilometrage']['ID'],
        "FIELD_NAME" => "UF_POINTS_ID",
        "USER_TYPE_ID" => 'integer',
        "XML_ID" => "",
        "SORT" => 100,
        "MULTIPLE" => 'Y',
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
            'ru'    => 'Точки локализаций',
            'en'    => 'Localization points',
        ),
        'LIST_COLUMN_LABEL' => array(
            'ru'    => 'Точки локализаций',
            'en'    => 'Localization points',
        ),
        'LIST_FILTER_LABEL' => array(
            'ru'    => 'Точки локализаций',
            'en'    => 'Localization points',
        ),
        'ERROR_MESSAGE'  => array(
            'ru'    => 'Ошибка при заполнении пользовательского свойства UF_POINTS_ID',
            'en'    => 'An error in completing the user field UF_POINTS_ID',
        ),
        'HELP_MESSAGE'      => array(
            'ru'    => '',
            'en'    => '',
        ),
    ),
    
    // привязка к авто
    array(
        "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_transfer_by_kilometrage']['ID'],
        "FIELD_NAME" => "UF_AUTO_ID",
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
            'ru'    => 'Автомобиль',
            'en'    => 'Auto',
        ),
        'LIST_COLUMN_LABEL' => array(
            'ru'    => 'Автомобиль',
            'en'    => 'Auto',
        ),
        'LIST_FILTER_LABEL' => array(
            'ru'    => 'Автомобиль',
            'en'    => 'Auto',
        ),
        'ERROR_MESSAGE'  => array(
            'ru'    => 'Ошибка при заполнении пользовательского свойства UF_AUTO_ID',
            'en'    => 'An error in completing the user field UF_AUTO_ID',
        ),
        'HELP_MESSAGE'      => array(
            'ru'    => '',
            'en'    => '',
        ),
    ),
    
    // цена
    array(
        "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_transfer_by_kilometrage']['ID'],
        "FIELD_NAME" => "UF_RPICE",
        "USER_TYPE_ID" => 'double',
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
            'ru'    => 'Цена',
            'en'    => 'Price',
        ),
        'LIST_COLUMN_LABEL' => array(
            'ru'    => 'Цена',
            'en'    => 'Price',
        ),
        'LIST_FILTER_LABEL' => array(
            'ru'    => 'Цена',
            'en'    => 'Price',
        ),
        'ERROR_MESSAGE'  => array(
            'ru'    => 'Ошибка при заполнении пользовательского свойства UF_RPICE',
            'en'    => 'An error in completing the user field UF_RPICE',
        ),
        'HELP_MESSAGE'      => array(
            'ru'    => '',
            'en'    => '',
        ),
    ),
    
    // валюта
    array(
        "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_transfer_by_kilometrage']['ID'],
        "FIELD_NAME" => "UF_CURRENCY_ID",
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
            'ru'    => 'Валюта',
            'en'    => 'Auto',
        ),
        'LIST_COLUMN_LABEL' => array(
            'ru'    => 'Валюта',
            'en'    => 'Auto',
        ),
        'LIST_FILTER_LABEL' => array(
            'ru'    => 'Валюта',
            'en'    => 'Auto',
        ),
        'ERROR_MESSAGE'  => array(
            'ru'    => 'Ошибка при заполнении пользовательского свойства UF_CURRENCY_ID',
            'en'    => 'An error in completing the user field UF_CURRENCY_ID',
        ),
        'HELP_MESSAGE'      => array(
            'ru'    => '',
            'en'    => '',
        ),
    ),
    
    // привязка к пользователю
    array(
        "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_transfer_by_kilometrage']['ID'],
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