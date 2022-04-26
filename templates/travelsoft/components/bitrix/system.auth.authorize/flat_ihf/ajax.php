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
	$name = $_REQUEST["name"];
	$surname = $_REQUEST["surname"];
	$phone = $_REQUEST["phone"];
	$email = strtolower($_REQUEST["email"]);
	$country = $_REQUEST["country"];
	$company = $_REQUEST["company"];

	switch(LANGUAGE_ID)
	{
		case "ru":
			$lid = "s1";
			break;
		case "by":
			$lid = "by";
			break;
		case "en":
			$lid = "en";
			break;
	}
	$msgTmpltTo = 117;

	$arEmailFields = array(
      "NAME"		=> htmlspecialchars($name),
	  "SURNAME"		=> htmlspecialchars($surname),
      "PHONE" 		=> htmlspecialchars($phone),
      "EMAIL_FROM"	=> htmlspecialchars($email),
      "COUNTRY"	=> htmlspecialchars($country),
      "COMPANY"		=> htmlspecialchars($company)
  	);

	\Bitrix\Main\Mail\Event::send([    
		"EVENT_NAME" => "FEEDBACK_PRESS_REQUIRED",
		'MESSAGE_ID' => $msgTmpltTo,
		"LID" => $lid,
		"C_FIELDS" => $arEmailFields
	]);
}
