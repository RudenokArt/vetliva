<?php

return array(
    "table" => "ts_favorite",
    "table_data" => array(
        "NAME" => "TSFAVORITES",
        "ERR" => "Ошибка при создании highloadblock'a favorites",
        "LANGS" => array(
            "ru" => 'Таблица избранного',
            "en" => "Favorites"
        ),
        "OPTION_PARAMETER" => "FAVORITES_STORAGE_ID"
    ),
    "fields" => [
        array(
            "ENTITY_ID" => 'HLBLOCK_{{table_id}}',
            "FIELD_NAME" => "UF_OBJECT",
            "USER_TYPE_ID" => 'string',
            "XML_ID" => "",
            "SORT" => 100,
            "MULTIPLE" => 'N',
            'MANDATORY' => 'N',
            'SHOW_FILTER' => 'N',
            'SHOW_IN_LIST' => 'Y',
            'IS_SEARCHABLE' => 'N',
            'SETTINGS' => array(
                'DEFAULT_VALUE' => "",
                "PRECISION" => 2,
                'SIZE' => '20',
                'ROWS' => 1,
                'MIN_LENGTH' => 0,
                'MAX_LENGTH' => 0,
                'REGEXP' => ''
            ),
            'EDIT_FORM_LABEL' => array(
                'ru' => 'Объект',
                'en' => 'Object',
            ),
            'LIST_COLUMN_LABEL' => array(
                'ru' => 'Объект',
                'en' => 'Object',
            ),
            'LIST_FILTER_LABEL' => array(
                'ru' => 'Объект',
                'en' => 'Object',
            ),
            'ERROR_MESSAGE' => array(
                'ru' => 'Ошибка при заполнении поля "Объект" ',
                'en' => 'An error in completing the field "Object"',
            ),
            'HELP_MESSAGE' => array(
                'ru' => '',
                'en' => '',
            ),
        ),
        array(
            "ENTITY_ID" => 'HLBLOCK_{{table_id}}',
            "FIELD_NAME" => "UF_ID",
            "USER_TYPE_ID" => 'integer',
            "XML_ID" => "",
            "SORT" => 100,
            "MULTIPLE" => 'N',
            'MANDATORY' => 'N',
            'SHOW_FILTER' => 'N',
            'SHOW_IN_LIST' => 'Y',
            'IS_SEARCHABLE' => 'N',
            'SETTINGS' => array(
                'DEFAULT_VALUE' => "",
                'SIZE' => '20',
                'ROWS' => 1,
                'MIN_LENGTH' => 0,
                'MAX_LENGTH' => 0,
                'REGEXP' => ''
            ),
            'EDIT_FORM_LABEL' => array(
                'ru' => 'Элемент',
                'en' => 'Element',
            ),
            'LIST_COLUMN_LABEL' => array(
                'ru' => 'Элемент',
                'en' => 'Element',
            ),
            'LIST_FILTER_LABEL' => array(
                'ru' => 'Элемент',
                'en' => 'Element',
            ),
            'ERROR_MESSAGE' => array(
                'ru' => 'Ошибка при заполнении поля "Элемент" ',
                'en' => 'An error in completing the field "Element"',
            ),
            'HELP_MESSAGE' => array(
                'ru' => '',
                'en' => '',
            ),
        ),
        array(
            "ENTITY_ID" => 'HLBLOCK_{{table_id}}',
            "FIELD_NAME" => "UF_STORE_ID",
            "USER_TYPE_ID" => 'integer',
            "XML_ID" => "",
            "SORT" => 100,
            "MULTIPLE" => 'N',
            'MANDATORY' => 'N',
            'SHOW_FILTER' => 'N',
            'SHOW_IN_LIST' => 'Y',
            'IS_SEARCHABLE' => 'N',
            'SETTINGS' => array(
                'DEFAULT_VALUE' => "",
                'SIZE' => '20',
                'ROWS' => 1,
                'MIN_LENGTH' => 0,
                'MAX_LENGTH' => 0,
                'REGEXP' => ''
            ),
            'EDIT_FORM_LABEL' => array(
                'ru' => 'Id хранилища элемента',
                'en' => 'Store id',
            ),
            'LIST_COLUMN_LABEL' => array(
                'ru' => 'Id хранилища элемента',
                'en' => 'Store id',
            ),
            'LIST_FILTER_LABEL' => array(
                'ru' => 'Id хранилища элемента',
                'en' => 'Store id',
            ),
            'ERROR_MESSAGE' => array(
                'ru' => 'Ошибка при заполнении поля "Id хранилища элемента" ',
                'en' => 'An error in completing the field "Store id"',
            ),
            'HELP_MESSAGE' => array(
                'ru' => '',
                'en' => '',
            ),
        ),
        array(
            "ENTITY_ID" => 'HLBLOCK_{{table_id}}',
            "FIELD_NAME" => "UF_USER_ID",
            "USER_TYPE_ID" => 'integer',
            "XML_ID" => "",
            "SORT" => 100,
            "MULTIPLE" => 'N',
            'MANDATORY' => 'N',
            'SHOW_FILTER' => 'N',
            'SHOW_IN_LIST' => 'Y',
            'IS_SEARCHABLE' => 'N',
            'SETTINGS' => array(
                'DEFAULT_VALUE' => "",
                'SIZE' => '20',
                'ROWS' => 1,
                'MIN_LENGTH' => 0,
                'MAX_LENGTH' => 0,
                'REGEXP' => ''
            ),
            'EDIT_FORM_LABEL' => array(
                'ru' => 'Пользователь',
                'en' => 'User',
            ),
            'LIST_COLUMN_LABEL' => array(
                'ru' => 'Пользователь',
                'en' => 'User',
            ),
            'LIST_FILTER_LABEL' => array(
                'ru' => 'Стоимость netto',
                'en' => 'User',
            ),
            'ERROR_MESSAGE' => array(
                'ru' => 'Ошибка при заполнении поля "Пользователь" ',
                'en' => 'An error in completing the field "User"',
            ),
            'HELP_MESSAGE' => array(
                'ru' => '',
                'en' => '',
            ),
        ),
        array(
            "ENTITY_ID" => 'HLBLOCK_{{table_id}}',
            "FIELD_NAME" => "UF_GUEST_ID",
            "USER_TYPE_ID" => 'integer',
            "XML_ID" => "",
            "SORT" => 100,
            "MULTIPLE" => 'N',
            'MANDATORY' => 'N',
            'SHOW_FILTER' => 'N',
            'SHOW_IN_LIST' => 'Y',
            'IS_SEARCHABLE' => 'N',
            'SETTINGS' => array(
                'DEFAULT_VALUE' => "",
                "PRECISION" => 2,
                'SIZE' => '20',
                'ROWS' => 1,
                'MIN_LENGTH' => 0,
                'MAX_LENGTH' => 0,
                'REGEXP' => ''
            ),
            'EDIT_FORM_LABEL' => array(
                'ru' => 'Гостевой id',
                'en' => 'Guset id',
            ),
            'LIST_COLUMN_LABEL' => array(
                'ru' => 'Гостевой id',
                'en' => 'Guset id',
            ),
            'LIST_FILTER_LABEL' => array(
                'ru' => 'Гостевой id',
                'en' => 'Guset id',
            ),
            'ERROR_MESSAGE' => array(
                'ru' => 'Ошибка при заполнении поля "Гостевой id" ',
                'en' => 'An error in completing the field "Guset id"',
            ),
            'HELP_MESSAGE' => array(
                'ru' => '',
                'en' => '',
            ),
        ),
        array(
            "ENTITY_ID" => 'HLBLOCK_{{table_id}}',
            "FIELD_NAME" => "UF_DATETIME",
            "USER_TYPE_ID" => 'datetime',
            "XML_ID" => "",
            "SORT" => 100,
            "MULTIPLE" => 'N',
            'MANDATORY' => 'N',
            'SHOW_FILTER' => 'N',
            'SHOW_IN_LIST' => 'Y',
            'IS_SEARCHABLE' => 'N',
            'SETTINGS' => array(
                'DEFAULT_VALUE' => "",
                "PRECISION" => 2,
                'SIZE' => '20',
                'ROWS' => 1,
                'MIN_LENGTH' => 0,
                'MAX_LENGTH' => 0,
                'REGEXP' => ''
            ),
            'EDIT_FORM_LABEL' => array(
                'ru' => 'Дата и время добавления',
                'en' => 'Datetime',
            ),
            'LIST_COLUMN_LABEL' => array(
                'ru' => 'Дата и время добавления',
                'en' => 'Datetime',
            ),
            'LIST_FILTER_LABEL' => array(
                'ru' => 'Дата и время добавления',
                'en' => 'Datetime',
            ),
            'ERROR_MESSAGE' => array(
                'ru' => 'Ошибка при заполнении поля "Дата и время добавления" ',
                'en' => 'An error in completing the field "Datetime"',
            ),
            'HELP_MESSAGE' => array(
                'ru' => '',
                'en' => '',
            ),
        )
    ]
);
