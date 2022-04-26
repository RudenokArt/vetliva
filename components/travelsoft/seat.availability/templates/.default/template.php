<?php

if ($arResult["IS_AJAX"]) {
    $APPLICATION->RestartBuffer();
    include "parts/table.php";
    die;
} else {
    include "parts/button.php";
}