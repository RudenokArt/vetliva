<?php

define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC", "Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);

$documentRoot = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
require_once($documentRoot . '/bitrix/modules/main/include/prolog_before.php');

CBitrixComponent::includeComponentClass("travelsoft:partners.review-asnwer");

$component = new PartnersReviewAnswer();

$component->arParams['REVIEW_ID'] = $_REQUEST['review_id'];
$component->arParams['ANSWER_IBPROPERTY_CODE'] = 'ANSWER';
$component->arParams['MESSAGE_ID'] = 102;
$component->processingRequest();