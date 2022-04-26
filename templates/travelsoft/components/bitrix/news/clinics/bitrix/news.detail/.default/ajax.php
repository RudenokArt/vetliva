<?php

define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC","Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);

require ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

if(!$APPLICATION->CaptchaCheckCode($_REQUEST['capthca'], $_REQUEST["captcha_code"]))
{
	echo 'errorCaptcha_' . $APPLICATION->CaptchaGetCode();
} 
else 
{
	$fio = $_REQUEST["fio"];
	$phone = $_REQUEST["phone"];
	$email = strtolower($_REQUEST["email"]);
	$citizenship = $_REQUEST["citizenship"];
	$comment = $_REQUEST["comment"];
	$emailTo = strtolower($_REQUEST["email_to"]);

	// Проверяем, есть ли в highload-блоке уже такой email
	\Bitrix\Main\Loader::includeModule("highloadblock"); 

	$hlbl = 69;
	$hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($hlbl)->fetch(); 
	
	$entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock); 
	$entity_data_class = $entity->getDataClass(); 
	
	$rsData = $entity_data_class::getList(array(
	   "select" => array("UF_EMAIL"),
	   "order" => array(),
	   "filter" => array("UF_EMAIL" => $email)
	));

	if(!($rsData->Fetch())) // Если нету, то нужно сохранить
	{
		$data = array("UF_EMAIL" => $email);
		$result = $entity_data_class::add($data);

		if (!$result->isSuccess())
		{
			echo "error";
			return;
		}
	}

	switch(LANGUAGE_ID)
	{
		case "ru":
			$msgTmpltFrom = 111;
			$msgTmpltTo = 112;
			$lid = "s1";
			break;
		case "by":
			$msgTmpltFrom = 113;
			$msgTmpltTo = 115;
			$lid = "by";
			break;
		case "en":
			$msgTmpltFrom = 114;
			$msgTmpltTo = 116;
			$lid = "en";
			break;
	}

	$arEmailFields = array(
      "NAME"		=> htmlspecialchars($fio),
      "PHONE" 		=> htmlspecialchars($phone),
      "EMAIL_FROM"	=> htmlspecialchars($email),
      "CITIZENSHIP"	=> htmlspecialchars($citizenship),
      "COMMENT"		=> htmlspecialchars($comment),
	  "EMAIL_TO"	=> htmlspecialchars($emailTo)
  	);

  	\Bitrix\Main\Mail\Event::send([    
		"EVENT_NAME" => "FEEDBACK_FORM",
		'MESSAGE_ID' => $msgTmpltFrom,
		"LID" => $lid,
		"C_FIELDS" => $arEmailFields
	]);
	\Bitrix\Main\Mail\Event::send([    
		"EVENT_NAME" => "FEEDBACK_FORM",
		'MESSAGE_ID' => $msgTmpltTo,
		"LID" => $lid,
		"C_FIELDS" => $arEmailFields
	]);
}
