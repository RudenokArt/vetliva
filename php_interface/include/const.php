<?php

// Определение дополнительных констант

/**
 * id группы поставщика услуг
 */
define('SERVICE_PROVIDER_GROUP_ID', 7);

/**
 * шаблон регулярного выражения для поля телефона
 */
define("PHONE_REGEXPR", "^\+?[0-9,\s]{0,}$");

/**
 * ID почтового шаблона для отправки писма менеджеру о добавлении нового поставщика услуг
 */
define("MAIL_TEMPLATE_MANAGER_ID", 64);

/**
 * ID почтового шаблона для отправки писма поставщику о его активации
 */
define("MAIL_TEMPLATE_PROVIDER_ID", 65);

/**
 * Путь к кабинету партнёра отноительно корня
 */
define("PROVIDER_RELATIVE_PATH", '/partners');

/**
 * ID Типа услуги САНАТОРИИ
 */
define("SANATORIUM_SERVICE_TYPE_ID", 4);

/**
 * ID Типа услуги ОТЕЛИ
 */
define("HOTEL_SERVICE_TYPE_ID", 5);

/**
 * ID Типа услуги ЭКСКУРСИОННЫЙ ТУР
 */
define("TOUR_SERVICE_TYPE_ID", 6);

/**
 * ID инфоблока "Удобства в номере"
 */
define("SERVICES_IBLOCK_ID", 11);

/**
 * ID highloadblock услуг
 */
define("SERVICES_BOOKING_HL_BLOCK", 51);

/**
 * ID highloadblock типа цен
 */
define("PRICE_TYPES_BOOKING_HL_BLOCK", 54);

/**
 * ID highloadblock тарифы + типы цен
 */
define("PRICE_TYPES_PLUSE_RATES_BOOKING_HL_BLOCK", 56);

/**
 * ID highloadblock квоты
 */
define("QUOTAS_BOOKING_HL_BLOCK", 53);

/**
 * ID highloadblock тарифы
 */
define("RATES_BOOKING_HL_BLOCK", 55);

/**
 * ID highloadblock питание
 */
define("FOOD_BOOKING_HL_BLOCK", 57);

/**
 * ID инфоблока санаториев
 */
define("SANATORIUM_IBLOCK_ID", 8);

/**
 * ID инфоблока Видео
 */
define("VIDEO_IBLOCK_ID", 64);

/**
 * ID инфоблока Стоимость услуг
 */
define("COST_SERVICES_IBLOCK_ID", 66);

/**
 * ID инфоблока варантов размещений (отели)
 */
define("PLACEMENTS_IBLOCK_ID",  7);

/**
 * ID инфоблока достопримечательности
 */
define("ATTRACTION_IBLOCK_ID", 6);

/**
 * ID инфоблока Города, курорты, населенные пункты
 */
define("TOWN_IBLOCK_ID", 5);

/**
 * ID инфоблока Области и районы
 */
define("REGION_IBLOCK_ID", 5);

/**
 * ID инфоблока Туры и экскурсии
 */
define("EXCURSION_IBLOCK_ID",  33);

/**
 * ID инфоблока Новости
 */
define("NEWS_IBLOCK_ID",  9);

/**
 * ID инфоблока Календарь событий
 */
define("POSTER_IBLOCK_ID",  25);

/**
 * ID инфоблока Как добраться
 */
define("GETTING_THERE_IBLOCK_ID",  40);

/**
 * ID инфоблока Акции и скидки
 */
define("ACTIONS_IBLOCK_ID",  29);

/**
 * ЯЗЫКОВОЙ ПОСТФИКС СВОЙСТВ ИНФОБЛОКА
 */
define("POSTFIX_PROPERTY", LANGUAGE_ID === "ru" ? "" : "_" . strtoupper(LANGUAGE_ID));

/**
 * Путь к noPhoto
 */
define("NO_PHOTO_PATH", "/local/templates/travelsoft/images/nophoto.jpg");

/**
 * Путь к картинке YouTube
 */
define("ICON_YOUTUBE_PATH", "/local/templates/travelsoft/images/youtube.png");

/**
 * Путь к noPhoto people
 */
define("NO_PHOTO_PEOPLE_PATH", "/local/templates/travelsoft/images/noavatarpeople.png");

/**
 * Путь к noPhoto watermark
 */
define("NO_PHOTO_PATH_WATERMARK", $_SERVER["DOCUMENT_ROOT"]."/local/templates/travelsoft/images/logo-waterm.png");

/**
 * ключ google api
 */
define("GOOGLE_API_KEY", "AIzaSyATmRxbw92XHPYpifCQ5pV1iAqLuCDR-PY");

/**
 * путь к маркеру для карты
 */
define("MAP_MARKER_PATH", "/local/templates/travelsoft/images/map/marker1.png");

/**
 * номер счетчика ru
 */
if(LANGUAGE_ID == "ru"){
    define("METRIC_KEY", 42451344);
} elseif(LANGUAGE_ID == "en"){
    define("METRIC_KEY", 42451284);
} elseif(LANGUAGE_ID == "by"){
    define("METRIC_KEY", 42451184);
}

/**
 * цель Бронирование (успех)
 */
define("GOAL_ID", "booking_success");

/*
 *  инфоблок Блога
 */
define("BLOG_IBLOCK_ID", 58);

/*
 *  инфоблок Сообщения формы Оставьте заявку с детальной страницы
 */
define("CALLBACK_IBLOCK_ID", 59);
/*
 * Почтовое сообщение формы Оставьте заявку с детальной страницы
 */
define("CALLBACK_MAIL_MESSAGE_ID", 89);

/**
 * Почтовое сообщения запроса на активацию элемента продажи от партнера
 */
define("ACTIVATION_REQUEST_MAIL_TEMPLATE_ID", 91);

/**
 * Почтовое сообщения запроса на редактирование туриста
 */
define("EDIT_TOURIST_REQUEST_MAIL_TEMPLATE_ID", 92);

/**
 * Инфоблок стоимостей платных услуг
 */
define("SERVICES_PRICES_IBLOCK_ID", 66);

/**
 * Инфоблок мероприятий
 */
define("EVENTS_IBLOCK_ID", 70);

/**
 * Инфоблок прощадок
 */
define("PLATFORM_IBLOCK_ID", 69);

/**
 * Почтовое сообщения запроса на удаление элемента продажи от партнера
 */
define("DELETING_REQUEST_MAIL_TEMPLATE_ID", 104);

/**
 * Инфоблок новости туризма Беларуси
 */
define("TOURISM_NEWS_IBLOCK_ID", 28);

/**
 * Инфоблок Шопинг
 */
define("SHOPING_IBLOCK_ID", 30);

/**
 * Инфоблок Еда
 */
define("EAT_IBLOCK_ID", 31);

/**
 * Инфоблок Сделано в Беларуси
 */
define("MADE_IN_BELARUS_IBLOCK_ID", 37);

/**
 * Инфоблок Спортивные комплексы
 */
define("SPORT_COMPLEXES_IBLOCK_ID", 38);

/**
 * Раздел "о беларуси" - коды инфоблоков для поиска, кроме POSTER_IBLOCK_ID(афишы)
 */
define("AR_ABOUT_BELARUS_IBLOCK_ID", [
    TOWN_IBLOCK_ID,
    ATTRACTION_IBLOCK_ID,
    TOURISM_NEWS_IBLOCK_ID,
    SHOPING_IBLOCK_ID,
    EAT_IBLOCK_ID,
    MADE_IN_BELARUS_IBLOCK_ID,
    SPORT_COMPLEXES_IBLOCK_ID,
    GETTING_THERE_IBLOCK_ID,
    BLOG_IBLOCK_ID
]);

/**
 * Раздел "о беларуси" - корневой каталог для поиска
 */
define('ABOUT_BELARUS_ROOT_URL', '/belarus');

/**
 * ID highloadblock направлений
 */
define("DESTINATIONS_HL_BLOCK", 70);

/**
 * ID шаблона письма пользователю оставить отзыв по окончанию действия услуги
 */
define('REVIEW_EVENT_MESSAGE_ID', 124);

/**
 * Тип события для письма пользователю оставить отзыв по окончанию действия услуги
 */
define('REVIEW_EVENT_TYPE', "REVIEW_MAIL");

/**
 * ID highloadblock заказов
 */
define("BOOKING_HL_BLOCK", 71);

/**
 * ID инфоблока Условия бронирования
 */
define("BOOKING_CONDITION_IBLOCK_ID", 96);

/**
 * ID инфоблока путеводитель по сайту
 */

define("INFO_PUTEVODITEL_IBLOCK_ID", 97);

/**
 * Включает рассылку писем пользователям с просьбой оставить отзыв
 */
define("REVIEW_MAILS_ENABLED", 1);

/**
 * ID шаблона письма с отчетами по бронированию
 */
define('REPORT_EVENT_MESSAGE_ID', 126);

/**
 * Тип события для письма пользователю оставить отзыв по окончанию действия услуги
 */
define('REPORT_EVENT_TYPE', "REPORT_MAIL");