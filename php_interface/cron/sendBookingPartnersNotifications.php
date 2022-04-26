<?php

require_once "cronHeader.php";

Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools");
Bitrix\Main\Loader::includeModule("iblock");

\travelsoft\booking\Utils::checkExcursionsQuotas();
\travelsoft\booking\Utils::checkExcursiontoursQuotas();
\travelsoft\booking\Utils::checkPlacementsQuotas();
\travelsoft\booking\Utils::checkSanatoriumQuotas();
\travelsoft\booking\Utils::checkPlacementsPrice();
\travelsoft\booking\Utils::checkSanatoriumPrice();
\travelsoft\booking\Utils::checkExcursionsPrice();
\travelsoft\booking\Utils::checkTransfersPrice();