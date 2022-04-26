<?php

$classes = array(
    "travelsoft\\favorites\\adapters\\Iblock" => "lib/adapters/Iblock.php",
    "travelsoft\\favorites\\adapters\\Highloadblock" => "lib/adapters/Highloadblock.php",
    "travelsoft\\favorites\\Utils" => "lib/Utils.php"
);

CModule::AddAutoloadClasses("travelsoft.favorites", $classes);
