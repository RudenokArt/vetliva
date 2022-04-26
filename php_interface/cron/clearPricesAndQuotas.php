<?php

require_once "cronHeader.php";

Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools");
Bitrix\Main\Loader::includeModule("travelsoft.vetliva.history");

# удаление квот по прошедшим датам (неактуальные квоты)
\travelsoft\booking\Utils::clearStoreByFilter("\\travelsoft\\booking\\datastores\QuotasDataStore", array("<=UF_DATE" => time() - 86400));
\travelsoft\booking\Utils::clearStoreByFilter("\\travelsoft\\booking\\datastores\RatesQuotasDataStore", array("<=UF_DATE" => time() - 86400));
# удаление цен по прошедшим датам (неактуальные цены)
\travelsoft\booking\Utils::clearStoreByFilter("\\travelsoft\\booking\\datastores\PricesDataStore", array("<=UF_DATE" => time() - 86400));

echo "start clear history at " . date("Y-m-d H:i:s", time()) . " ...\n";

#очистка таблицы архива
travelsoft\vetliva\DBHistory::getInstance()->clearArchive([
    "<=UF_DATE" => date("Y-m-d H:i:s", time() - (86400*abs(intval(Bitrix\Main\Config\Option::get("travelsoft.vetliva.history", "SAVE_PER_DAYS")))))
]);

echo "end clear history.\n ---------------------------------------------------\n";