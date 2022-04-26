<?php

// трансфер

return array(
    
    // пункт A
    array(
        "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_transfer_fix']['ID'],
        "FIELD_NAME" => "UF_POINT_A_ID",
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
                'IBLOCK_TYPE_ID' => "",
                'IBLOCK_ID' => "",
                'DISPLAY' => 'LIST',
                'LIST_HEIGHT' => 5,
                'ACTIVE_FILTER' => 'Y'
            ),
        'EDIT_FORM_LABEL'   => array(
            'ru'    => 'Пункт A',
            'en'    => 'Point A',
        ),
        'LIST_COLUMN_LABEL' => array(
           'ru'    => 'Пункт A',
            'en'    => 'Point A',
        ),
        'LIST_FILTER_LABEL' => array(
            'ru'    => 'Пункт A',
            'en'    => 'Point A',
        ),
        'ERROR_MESSAGE'  => array(
            'ru'    => 'Ошибка при заполнении пользовательского свойства UF_POINT_A_ID',
            'en'    => 'An error in completing the user field UF_POINT_A_ID',
        ),
        'HELP_MESSAGE'      => array(
            'ru'    => '',
            'en'    => '',
        ),
    ),
    
    // пункт A
    array(
        "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_transfer_fix']['ID'],
        "FIELD_NAME" => "UF_POINT_B_ID",
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
             'ru'    => 'Пункт B',
            'en'    => 'Point B',
        ),
        'LIST_COLUMN_LABEL' => array(
            'ru'    => 'Пункт B',
            'en'    => 'Point B',
        ),
        'LIST_FILTER_LABEL' => array(
             'ru'    => 'Пункт B',
            'en'    => 'Point B',
        ),
        'ERROR_MESSAGE'  => array(
            'ru'    => 'Ошибка при заполнении пользовательского свойства UF_POINT_B_ID',
            'en'    => 'An error in completing the user field UF_POINT_B_ID',
        ),
        'HELP_MESSAGE'      => array(
            'ru'    => '',
            'en'    => '',
        ),
    ),
    
    // привязка к авто
    array(
        "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_transfer_fix']['ID'],
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
        "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_transfer_fix']['ID'],
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
        "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_transfer_fix']['ID'],
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
        "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_transfer_fix']['ID'],
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