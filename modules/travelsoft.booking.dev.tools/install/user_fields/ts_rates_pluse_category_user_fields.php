<?php

// категории + тарифы
return array(
    
    // тариф
    array(
            "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_rates_pluse_category']['ID'],
            "FIELD_NAME" => "UF_RATE_ID",
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
                'HLBLOCK_ID' => $this->arHighloadblocks['ts_rates']['ID'],
                'HLFIELD_ID' => 0,
                'DISPLAY' => 'LIST',
                'LIST_HEIGHT' => 5
            ),
            'EDIT_FORM_LABEL'   => array(
                'ru'    => 'Тариф',
                'en'    => 'Rate',
            ),
            'LIST_COLUMN_LABEL' => array(
                'ru'    => 'Тариф',
                'en'    => 'Rate',
            ),
            'LIST_FILTER_LABEL' => array(
                'ru'    => 'Тариф',
                'en'    => 'Rate',
            ),
            'ERROR_MESSAGE'  => array(
                'ru'    => 'Ошибка при заполнении пользовательского свойства UF_RATE_ID',
                'en'    => 'An error in completing the user field UF_RATE_ID',
            ),
            'HELP_MESSAGE'      => array(
                'ru'    => '',
                'en'    => '',
            ),
        ),
    
    // категория тарифа
    array(
            "ENTITY_ID" => 'HLBLOCK_' . $this->arHighloadblocks['ts_rates_pluse_category']['ID'],
            "FIELD_NAME" => "UF_RATE_CATEGORY_ID",
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
                'HLBLOCK_ID' => $this->arHighloadblocks['ts_rates_category']['ID'],
                'HLFIELD_ID' => 0,
                'DISPLAY' => 'LIST',
                'LIST_HEIGHT' => 5
            ),
            'EDIT_FORM_LABEL'   => array(
                'ru'    => 'Категория ',
                'en'    => 'Rate',
            ),
            'LIST_COLUMN_LABEL' => array(
                'ru'    => 'Тариф',
                'en'    => 'Rate',
            ),
            'LIST_FILTER_LABEL' => array(
                'ru'    => 'Тариф',
                'en'    => 'Rate',
            ),
            'ERROR_MESSAGE'  => array(
                'ru'    => 'Ошибка при заполнении пользовательского свойства UF_RATE_CATEGORY_ID',
                'en'    => 'An error in completing the user field UF_RATE_CATEGORY_ID',
            ),
            'HELP_MESSAGE'      => array(
                'ru'    => '',
                'en'    => '',
            ),
        ),
    
);
