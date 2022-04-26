# travelsoft.favorites Добавить в избранное 1C-Bitrix

Скопируйте папку модуля travelsoft.favorites в папку
в корне сайта /local/modules/ или клонировать в папку /local/modules/ данный репозиторий командой
```
git clone https://gitlab.com/travelsoft_by/travelsoft.favorites.git
```


## Установка

Установите модуль пройдя процедуру установки модуля в админке битрикса на сайте.
После установки на сайте будут доступны компоненты по пути /local/components/travelsoft/favorites.add и 
/local/components/travelsoft/favorites.list. 

## Примеры подключения компонент

Для добавления кнопки добавить в избранное используется комопнент favorites.add.
Пример подключения для детальной страницы элемента комплексного компонента news: в файле detail.php компонента news.list перед вызовом компонента 
news.detail вставляем код
```php
<?
ob_start();
$element_id = $arResult["VARIABLES"]["ELEMENT_ID"];
if (!$element_id) {
    $element_id = (CIBlockElement::GetList(false, ["IBLOCK_ID" => $arParams["IBLOCK_ID"], "CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"]], false, false, ["ID"])->Fetch())["ID"];
}
$APPLICATION->IncludeComponent(
	"travelsoft:favorites.add",
	"", // при необходимости указать свой шаблон
	Array(
		"OBJECT_ID" => $element_id,
		"OBJECT_TYPE" => "IBLOCK_ELEMENT",
                "STORE_ID" => $arParams["IBLOCK_ID"]
	)
);
$GLOBALS["favorites_html"] = ob_get_clean();?>
```
Далее в параметры компонента news.detail добавляем
```php
"favorites_html_hash" => md5("__travelsoft__".$GLOBALS["favorites_html"]),
```
а в шаблоне компонента в нужном месте выводим
```php
<?= $GLOBALS["favorites_html"]?>
```

Для вывода в списке элементов компонента travelsoft.news.list: в шаблоне компонента создать файл component_prolog.php.
В данный файл добавить код
```php

Bitrix\Main\Loader::includeModule('iblock');

$filter = [
    'IBLOCK_ID' => $this->arParams['IBLOCK_ID'];
];

if ($this->arParams['PARENT_SECTION'] > 0) {
    $filter['SECTION_ID'] = $this->arParams['PARENT_SECTION'];
    if ($this->arParams['INCLUDE_SUBSECTIONS'] === "Y") {
        $filter["INCLUDE_SUBSECTIONS"] = "Y"
    }
} elseif ($this->arParams['PARENT_SECTION_CODE']) {
    $filter['SECTION_CODE'] = $this->arParams['PARENT_SECTION_CODE'];
}

if (
    isset($_GLOBALS[$this->arParams["FILTER_NAME"]]) && 
    is_array($_GLOBALS[$this->arParams["FILTER_NAME"]]) &&
    !empty($_GLOBALS[$this->arParams["FILTER_NAME"]])
) {
    $filter = array_merge($filter, $_GLOBALS[$this->arParams["FILTER_NAME"]]);
}

$dbElements = CIBlockElement::GetList(
    false, 
    $filter, 
    false, 
    ['iNumPage' => $_REQUEST["PAGEN_1"] ?: 1, 'nPageSize' => $this->arParams["NEWS_COUNT"]], 
    ["ID"]
);

$this->arParams["FAVORITES_HTML"] = [];
while ($arElement = $dbElements->Fetch()) {
    ob_start();
    $GLOBALS["APPLICATION"]->IncludeComponent(
	"travelsoft:favorites.add",
	"", // при необходимости указать свой шаблон
	Array(
		"OBJECT_ID" => $arElement["ID"],
		"OBJECT_TYPE" => "IBLOCK_ELEMENT",
                "STORE_ID" => $this->arParams["IBLOCK_ID"]
	)
    );
    $this->arParams["FAVORITES_HTML"][$arElement["ID"]] = ob_get_clean();
}
```
затем для вывода в списке необходимо в шаблоне компонента в цикле по элементам ``` foreach($arParams["ITEMS"] as $arItem) ``` в нужном месте добавить код
```php 

if (isset($arParams["FAVORITES_HTML"][$arItem["ID"]])) {
    echo $arParams["FAVORITES_HTML"][$arItem["ID"]];
}
```

Для вывода списка избранного создаем страницу и подключаем компонент
```php
<?$APPLICATION->IncludeComponent(
	"travelsoft:favorites.list",
	"", // при необходимости указать свой шаблон
Array()
);?>
```

## Требования:
php >= 7.0; Bitrix >= 18.0.0