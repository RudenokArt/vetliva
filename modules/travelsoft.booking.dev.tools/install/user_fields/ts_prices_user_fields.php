<?php

// цены
return array(
    
    // нетто
        array(
            "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_prices']['ID'],
            "FIELD_NAME" => "UF_NET",
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
                'ru'    => 'Нетто',
                'en'    => 'Net',
            ),
            'LIST_COLUMN_LABEL' => array(
                'ru'    => 'Нетто',
                'en'    => 'Net',
            ),
            'LIST_FILTER_LABEL' => array(
                'ru'    => 'Нетто',
                'en'    => 'Net',
            ),
            'ERROR_MESSAGE'  => array(
                'ru'    => 'Ошибка при заполнении пользовательского свойства UF_NET',
                'en'    => 'An error in completing the user field UF_NET',
            ),
            'HELP_MESSAGE'      => array(
                'ru'    => '',
                'en'    => '',
            ),
        ),
    
    // брутто
        array(
            "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_prices']['ID'],
            "FIELD_NAME" => "UF_GROSS",
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
                'ru'    => 'Брутто',
                'en'    => 'Gross',
            ),
            'LIST_COLUMN_LABEL' => array(
                'ru'    => 'Брутто',
                'en'    => 'Gross',
            ),
            'LIST_FILTER_LABEL' => array(
                'ru'    => 'Брутто',
                'en'    => 'Gross',
            ),
            'ERROR_MESSAGE'  => array(
                'ru'    => 'Ошибка при заполнении пользовательского свойства UF_GROSS',
                'en'    => 'An error in completing the user field UF_GROSS',
            ),
            'HELP_MESSAGE'      => array(
                'ru'    => '',
                'en'    => '',
            ),
        ),
    
    // валюта
        array(
            "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_prices']['ID'],
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
                'en'    => 'Currency',
            ),
            'LIST_COLUMN_LABEL' => array(
                 'ru'    => 'Валюта',
                'en'    => 'Currency',
            ),
            'LIST_FILTER_LABEL' => array(
                'ru'    => 'Валюта',
                'en'    => 'Currency',
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
    
    // тариф + категория тарифа
    array(
            "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_prices']['ID'],
            "FIELD_NAME" => "UF_RPC_ID",
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
                'HLBLOCK_ID' => $this->arHighloadblocks['ts_rates_pluse_category_type']['ID'],
                'HLFIELD_ID' => 0,
                'DISPLAY' => 'LIST',
                'LIST_HEIGHT' => 5
            ),
            'EDIT_FORM_LABEL'   => array(
                'ru'    => 'Тариф + категория тарифа',
                'en'    => 'Rates pluse category',
            ),
            'LIST_COLUMN_LABEL' => array(
                 'ru'    => 'Тариф + категория тарифа',
                'en'    => 'Rates pluse category',
            ),
            'LIST_FILTER_LABEL' => array(
                'ru'    => 'Тариф + категория тарифа',
                'en'    => 'Rates pluse category',
            ),
            'ERROR_MESSAGE'  => array(
                'ru'    => 'Ошибка при заполнении пользовательского свойства UF_RPC_ID',
                'en'    => 'An error in completing the user field UF_RPC_ID',
            ),
            'HELP_MESSAGE'      => array(
                'ru'    => '',
                'en'    => '',
            ),
        ),
    
    // квоты и наличие мест
    array(
            "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_prices']['ID'],
            "FIELD_NAME" => "UF_QUOTAS_ID",
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
                'HLBLOCK_ID' => $this->arHighloadblocks['ts_quotas']['ID'],
                'HLFIELD_ID' => 0,
                'DISPLAY' => 'LIST',
                'LIST_HEIGHT' => 5
            ),
            'EDIT_FORM_LABEL'   => array(
                'ru'    => 'Квоты и наличие мест',
                'en'    => 'Quotas and places',
            ),
            'LIST_COLUMN_LABEL' => array(
                 'ru'    => 'Квоты и наличие мест',
                'en'    => 'Quotas and places',
            ),
            'LIST_FILTER_LABEL' => array(
                'ru'    => 'Квоты и наличие мест',
                'en'    => 'Quotas and places',
            ),
            'ERROR_MESSAGE'  => array(
                'ru'    => 'Ошибка при заполнении пользовательского свойства UF_QUOTAS_ID',
                'en'    => 'An error in completing the user field UF_QUOTAS_ID',
            ),
            'HELP_MESSAGE'      => array(
                'ru'    => '',
                'en'    => '',
            ),
        ),
    
);
