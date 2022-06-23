<?php

// bitrix events handlers

$eventManager = \Bitrix\Main\EventManager::getInstance();

$eventManager->addEventHandler(
  "main",
  "OnBeforeUserRegister",
  array(
    "travelsoft\\BxEventsHandlers",
    "bxOnBeforeUserRegister"
  )
);

$eventManager->addEventHandler(
  "main",
  "OnAfterUserRegister",
  array(
    "travelsoft\\BxEventsHandlers",
    "bxOnAfterUserRegister"
  )
);

$eventManager->addEventHandler(
  "main",
  "OnAfterUserLogin",
  array(
    "travelsoft\\BxEventsHandlers",
    "bxOnAfterUserLogin"
  )
);

$eventManager->addEventHandler(
  "main",
  "OnBeforeUserChangePassword",
  array(
    "travelsoft\\BxEventsHandlers",
    "bxOnBeforeUserChangePassword"
  )
);

$eventManager->addEventHandler(
  "main",
  "OnSendUserInfo",
  array(
    "travelsoft\\BxEventsHandlers",
    "bxOnSendUserInfo"
  )
);

$eventManager->addEventHandler(
  "main",
  "OnAfterUserLogout",
  array(
    "travelsoft\\BxEventsHandlers",
    "bxOnAfterUserLogout"
  )
);

$eventManager->addEventHandler(
  "main",
  "OnAfterUserUpdate",
  array(
    "travelsoft\\BxEventsHandlers",
    "bxOnAfterUserUpdate"
  )
);

$eventManager->addEventHandler(
  "main",
  "OnBeforeUserUpdate",
  array(
    "travelsoft\\BxEventsHandlers",
    "bxOnBeforeUserUpdate"
  )
);




$eventManager->addEventHandler(
  "main",
  "OnAfterUserSimpleRegister",
  array(
    "travelsoft\\BxEventsHandlers",
    "bxOnAfterUserSimpleRegister"
  )
);

$eventManager->addEventHandler(
  "main",
  "OnBeforeUserSimpleRegister",
  array(
    "travelsoft\\BxEventsHandlers",
    "bxOnBeforeUserSimpleRegister"
  )
);

$eventManager->addEventHandler(
  "main",
  "OnBeforeUserAdd",
  array(
    "travelsoft\\BxEventsHandlers",
    "bxOnBeforeUserAdd"
  )
);

$eventManager->addEventHandler(
  "iblock",
  "OnAfterIBlockElementAdd",
  array(
    "travelsoft\\BxEventsHandlers",
    "bxOnAfterIBlockElementAdd"
  )
);

$eventManager->addEventHandler(
  "iblock",
  "OnAfterIBlockElementAdd",
  array(
    "travelsoft\\BxEventsHandlers",
    "setNewCurrencyCourse"
  )
);

$eventManager->addEventHandler(
  "iblock",
  "OnAfterIBlockElementAdd",
  array(
    "travelsoft\\BxEventsHandlers",
    "createLeadBx24AfterFeedbackAdd"
  )
);

$eventManager->addEventHandler(
  "iblock",
  "OnAfterIBlockElementUpdate",
  array(
    "travelsoft\\BxEventsHandlers",
    "bxOnAfterIBlockElementUpdate"
  )
);

$eventManager->addEventHandler(
  "iblock",
  "OnAfterIBlockElementUpdate",
  array(
    "travelsoft\\BxEventsHandlers",
    "setNewRaiting"
  )
);

$eventManager->addEventHandler(
  "iblock",
  "OnStartIBlockElementAdd",
  array(
    "travelsoft\\BxEventsHandlers",
    "bxOnStartIBlockElementAdd"
  )
);

$eventManager->addEventHandler(
  "iblock",
  "OnBeforeIBlockElementUpdate",
  array(
    "travelsoft\\BxEventsHandlers",
    "bxCheckAndSendAcivity"
  )
);

$eventManager->addEventHandler(
  "iblock",
  "OnIBlockPropertyBuildList",
  array(
    "CIBlockPropertyTinyMCE",
    "GetUserTypeDescription"
  )
);


# обработчики событий работы с хранилищами модуля бронирования
Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools");
travelsoft\booking\Utils::setEventsHandlers();


\Bitrix\Main\Loader::includeModule('kosmos.main');
/*
 * user
 */
$eventManager->addEventHandler('main', 'OnAfterUserAdd', ['Kosmos\Main\Handlers\User', 'onAfterUserAdd']);
$eventManager->addEventHandler('main', 'OnAfterUserUpdate', ['Kosmos\Main\Handlers\User', 'onAfterUserUpdate']);

/*
 * iblock
 */
$eventManager->addEventHandler('iblock', 'OnAfterIBlockElementAdd', ['Kosmos\Main\Handlers\IBlock', 'onAfterIBlockElementAdd']);
$eventManager->addEventHandler('iblock', 'OnAfterIBlockElementUpdate', ['Kosmos\Main\Handlers\IBlock', 'onAfterIBlockElementUpdate']);

//вырезаем type="text/javascript" 
AddEventHandler("main", "OnEndBufferContent", "removeType");

function removeType(&$content){
  $content = replace_output($content);
}

function replace_output($d){
  return str_replace(' type="text/javascript"', "", $d);
}

AddEventHandler("main", "OnEndBufferContent", "Sanitize_Output");
function Sanitize_Output(&$buffer) {
	global $USER, $APPLICATION;
	if((is_object($USER) && $USER->IsAuthorized()) || strpos($APPLICATION->GetCurDir(), "/bitrix/")!==false) return;

  $search = array(
    '/\>[^\S ]+/s',
    '/[^\S ]+\</s',
    '~>\s*\n\s*<~',
    '/<!--(?!noindex)(.*?)-->/'
  );

  $replace = array(
    '>',
    '<',
    '><',
    ''
  );

  $buffer = preg_replace($search, $replace, $buffer);

  return $buffer;
}

// Загрузка курсов валют
AddEventHandler("iblock", "OnBeforeIBlockElementAdd", function ($arFields) {
  if ($arFields['IBLOCK_ID']==2) {
    $check_date=(new InfoBlock())->getItemsList([],[
      'NAME'=> $arFields['NAME'],
      'IBLOCK_ID'=>2,
    ],false,false, [
      'ID',
      'IBLOCK_ID',
      'CODE',
      'CREATED_DATE',
      'PROPERTY_USD',
      'PROPERTY_EUR',
      'PROPERTY_RUB',
      'NAME',
    ]);
    global $APPLICATION;
    if(count($check_date) > 0) {
      $APPLICATION->ThrowException('Запись на указанную дату уже существует!'); 
      return false;
    }
  }
});

// Интеграция с CRM обращений в техподдержку OnAfterTicketAdd
AddEventHandler("support", "OnAfterTicketAdd", function ($arFields) {
  // file_put_contents($_SERVER['DOCUMENT_ROOT'].'/test.json', json_encode($arFields));
  (new B24_PartnersSupport($arFields))->sendPartnerRequest();
});

AddEventHandler("support", "OnAfterTicketUpdate", function ($arFields) {
  if ($arFields) {
    B24_PartnersSupport::partnerTicketMessage($arFields);
  }
});