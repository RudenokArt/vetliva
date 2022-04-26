<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

if ($arParams["IS_COPY"] === "Y") {

        $arResult["ELEMENT"]["NAME"] .= " (Копия)";
        unset($arResult["ELEMENT"]["ID"]);
        unset($arResult["ELEMENT"]["CODE"]);
        unset($arResult["ELEMENT"]["ACTIVE"]);
        unset($arResult["ELEMENT_FILES"]);
}