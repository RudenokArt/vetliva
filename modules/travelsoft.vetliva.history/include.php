<?php
$classes = array(
    "travelsoft\\vetliva\\DBHistory" => "lib/DBHistory.php",
    "travelsoft\\vetliva\\YandexMetrika" => "lib/YandexMetrika.php",
    "TravelsoftVetlivaHistoryEventsHandlers" => "lib/TravelsoftVetlivaHistoryEventsHandlers.php"
);
CModule::AddAutoloadClasses("travelsoft.vetliva.history", $classes);

@include_once 'functions.php';