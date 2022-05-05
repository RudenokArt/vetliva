<?php

namespace travelsoft;

/**
 * Класс с обработчиками событий bitrix
 * @author dimabresky by travelsoft
 */
class BxEventsHandlers {

    /**
     * Объект запроса
     * @var \Bitrix\Main\HttpRequest
     */
    static protected $request = null;
    static public $arErrors = array();

    /**
     * @param array $arFields
     */
    static public function bxOnAfterUserSimpleRegister(&$arFields) {

        self::createUpdateMTUser(array("email" => $arFields["EMAIL"], "password" => $arFields["PASSWORD"], $arFields["userId"] => $arFields["USER_ID"]));
    }

    /**
     * @param array $arFields
     */
    static public function bxOnBeforeUserSimpleRegister(&$arFields) {

        self::setLoginEqlEmail($arFields);
        self::passWorker("save", $arFields["PASSWORD"]);
    }

    /**
     * @param array $arFields
     */
    static public function bxOnBeforeUserUpdate(&$arFields) {
        /*if (!defined("ADMIN_SECTION")) {
            self::setLoginEqlEmail($arFields);
        }*/
        if (!array_key_exists("GROUP_ID", $arFields)){
            $iUserID = 0;
            if (array_key_exists("ID", $arFields)) {
                $iUserID = $arFields['ID'];
            } else if (array_key_exists("LOGIN", $arFields)) {
                $objUser = \CUser::GetByLogin($arFields['LOGIN']);
                if (is_object($objUser) && $arUser = $objUser->Fetch()) {
                    $iUserID = $arUser['ID'];
                }
            }
           $arIntersect = \CUser::GetUserGroup($iUserID);
        }
        else {
            $arIntersect = []; foreach ($arFields["GROUP_ID"] as $val) $arIntersect[] = $val['GROUP_ID'];
        }
        if (in_array(28, $arIntersect) && !in_array(9, $arIntersect)) {
            $arFields["ACTIVE"] = 'N';
        }
        if (in_array(26, $arIntersect) && !in_array(25, $arIntersect) && !in_array(11, $arIntersect) && !in_array(12, $arIntersect) && !in_array(13, $arIntersect) && !in_array(14, $arIntersect) ) {
            $arFields["ACTIVE"] = 'N';
        }
        
    }

    /**
     * @param array $arFields
     */
    static public function bxOnAfterUserLogin(&$arFields) {

        try {
            //region vd сохраняем данные для авторизации в Мастер-Тур
            \travelsoft\Bx24::saveMtUser($arFields);
            //endregion vd

            // авторизация пользователя в ПК-МастерТур
            if (\Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools")) {

                $response = \Bitrix\Main\Web\Json::decode(\travelsoft\booking\Gateway::authorizeMtUser(array(
                                    "url" => \Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "tsmo_url"),
                                    "login" => $arFields["LOGIN"],
                                    "password" => $arFields["PASSWORD"]
                )));
                file_put_contents('/home/bitrix/www/local/php_interface/include/classes/test_responce.txt', print_r($response,1), FILE_APPEND);   
                unset($_SESSION['__TRAVELSOFT']["TOKEN"]);
                booking\LoyalityProgramm::clear();
                if ($response["result"]["token"]) {
                    $_SESSION['__TRAVELSOFT']["TOKEN"] = $response["result"]["token"];
                    booking\LoyalityProgramm::toDeterminate();
                }
            }
        } catch (\Exception $e) {
            
        }
    }

    /**
     * @param array $arFields
     */
    public static function bxOnSendUserInfo(&$arFields) {
        // смена пароля
        $arFields["FIELDS"]["PASSWORD"] = self::passWorker();
        self::passWorker("delete");
    }

    /**
     * @param array $arFields
     */
    public static function bxOnBeforeEventSend(&$arFields) {
        // регистрация нового пользователя в системе
        $arFields["PASSWORD"] = self::passWorker();
        self::passWorker("delete");
    }

    /**
     * 
     * @param array $arParams
     */
    public static function bxOnAfterUserLogout($arParams) {

        // удаление token ПК-МастерТур
        if ($arParams["SUCCESS"] === true && $_SESSION['__TRAVELSOFT']["TOKEN"]) {
            unset($_SESSION['__TRAVELSOFT']["TOKEN"]);
        }
    }

    /**
     * @param type $arFields
     */
    public static function bxOnBeforeUserChangePassword($arFields) {
        $iUserID = 0;

        if (array_key_exists("ID", $arFields)) {
            $iUserID = $arFields['ID'];
        } else if (array_key_exists("LOGIN", $arFields)) {
            $objUser = \CUser::GetByLogin($arFields);
            if (is_object($objUser) && $arUser = $objUser->Fetch()) {
                $iUserID = $arUser['ID'];
            }
        }

        $arIntersect = array(1, 8, 18, 27); // Список групп, которым нельзя менять пороль
        // Если пользователь принадлежит одной из групп в $arIntersect
        if ($iUserID > 0 && count(array_intersect(\CUser::GetUserGroup($iUserID), $arIntersect)) > 0) {
            global $APPLICATION;
            if (is_object($APPLICATION)) {
                $APPLICATION->ThrowException("Can't change the password.");
            }

            return false;
        } else
            self::passWorker("save", $arFields["PASSWORD"]);
    }

    //Email-оповещение об изменении активности туров, санаториев или отелей
    public static function bxCheckAndSendAcivity(&$arFields) {

        //Если это не тур, санаторий или отель, то выходим
        if ($arFields['IBLOCK_ID'] != 33 && $arFields['IBLOCK_ID'] != 7 && $arFields['IBLOCK_ID'] != 8)
            return;

        //Если активность элемента не меняется, то выходим
        $arSelect = Array("ID", "NAME", "ACTIVE", "CODE");
        $arFilter = Array("ID" => $arFields['ID']);
        $res = \CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
        while ($ob = $res->GetNextElement()) {
            $arFieldsRes = $ob->GetFields();
        }

        if ($arFieldsRes['ACTIVE'] === $arFields['ACTIVE'] || empty($arFields['ACTIVE']))
            return;

        //Получаем код свойства "Привязка к пользователю" и свойства "Популярно":
        $user_prop_id = '';
        $pop_prop = '';
        $pop_prop_val = '';
        $link_for_obj = 'https://vetliva.ru';
        switch ($arFields['IBLOCK_ID']) {
            case 8: $user_prop_id = 71;
                $link_for_obj .= "/tourism/health-tourism/" . $arFields['CODE'] . '/';
                $pop_prop = 689;
                break;
            case 7: $user_prop_id = 171;
                $link_for_obj .= "/tourism/where-to-stay/" . $arFields['CODE'] . '/';
                $pop_prop = 688;
                $pop_prop_val = 163;
                break;
            case 33: $user_prop_id = 631;
                $link_for_obj .= "/tourism/cognitive-tourism/" . $arFields['CODE'] . '/';
                $pop_prop = 687;
                $pop_prop_val = 156;
                break;
        }

        //Находим email пользователя
        $user_arr_obj = new \ArrayObject($arFields['PROPERTY_VALUES'][$user_prop_id]);
        $user_id = $user_arr_obj->getIterator()->current();
        $rsUser = \CUser::GetByID($user_id['VALUE']);
        $arUser = $rsUser->Fetch();
        $email = $arUser['EMAIL'];

        //Проверяем статус активации, выставляем свойство "Популярно" и готовим соответствующий почтовый шаблон
        $email_ptrn = "";
        if ($arFields['ACTIVE'] == 'Y') {
            if ($pop_prop != 689) {
                if (!empty($arFields['PROPERTY_VALUES'][$pop_prop]))
                    $arFields['PROPERTY_VALUES'][$pop_prop] = array_merge($arFields['PROPERTY_VALUES'][$pop_prop],
                            array($pop_prop_val));
                else
                    $arFields['PROPERTY_VALUES'][$pop_prop] = array($pop_prop_val);
            }
            $arFields['ACTIVE_FROM'] = ConvertTimeStamp(false, "FULL");
            $next_time = explode(" ", $arFields['ACTIVE_FROM']);
            $next_time = explode(".", $next_time[0]);
            $arrMounthPlus = array("01" => "02", "02" => "03", "03" => "04", "04" => "05", "05" => "06",
                "06" => "07", "07" => "08", "08" => "09", "09" => "10", "10" => "11", "11" => "12",
                "12" => "01");
            $next_time[1] = $arrMounthPlus[$next_time[1]]; //увеличиваем месяц на 1
            $next_time = implode(".", $next_time) . " 00:00:00";
            $email_ptrn = 99;
            \CAgent::AddAgent(
                    "checkPopAfterActivity(" . $arFields['IBLOCK_ID'] . ", " . $arFields['ID'] . ");",
                    "", // идентификатор модуля
                    "N", // агент не критичен к кол-ву запусков
                    1, // интервал запуска - 1 сутки
					$next_time, // дата первой проверки - текущее
                    "Y", // агент активен
                    $next_time, // дата первого запуска - текущее
                    30
            );
        } else
            $email_ptrn = 100;

        //Подготовка и отправка письма
        \CEvent::Send(
                "ACTIVE_OBJ_CHANGE",
                "s1",
                array("EMAIL_USR" => $email, "LINK" => $link_for_obj, "NAME" => $arFields["NAME"]),
                "N",
                $email_ptrn
        );
    }

	/*
     * Занесение нового значения рейтинга в объект при активации/деактивации отзыва
	*/
	public static function setNewRaiting(&$arFields)
	{
		$reviewIBl = 36;
		$clinicIBl = 79;
		//Если это не комент, то выходим
        if ($arFields['IBLOCK_ID'] != $reviewIBl)
            return;

		//Если объект - не клиника, то выходим
		$itemID = current($arFields["PROPERTY_VALUES"]["258"])["VALUE"];
		$itemElement = \CIBlockElement::GetByID($itemID)->GetNext();
		if($itemElement['IBLOCK_ID'] != $clinicIBl)
			return;

        //Выгружаем все комменты, связанные с объектом
        $arSelect = Array("ID", "NAME", "ACTIVE",
					"PROPERTY_PRICE_QUALITY",
					"PROPERTY_LOCATION",
					"PROPERTY_STAFF",
					"PROPERTY_PURITY",
					"PROPERTY_ROOMS",
					"PROPERTY_FOOD");
        $arFilter = Array("IBLOCK_ID" => $reviewIBl,
						"PROPERTY_ITEM" => $itemID,
						"ACTIVE" => "Y");
        $res = \CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
		$arFReviews = array();
        while ($ob = $res->GetNextElement())
		{
			$arCurFields = $ob->GetFields();
            $arFReviews[$arCurFields['ID']] = $arCurFields;
        }

		//Пересчитываем рейтинг для клиники
		$arSums = array();
		$arCounts = array();
		$prop_from_rating = array("PRICE_QUALITY", "LOCATION", "STAFF", "PURITY", "ROOMS", "FOOD");

		foreach ($prop_from_rating as $prop)
		{
			$arCounts[$prop] = 0;
			$arSums[$prop] = 0;
		}

		$raiting = 0;
		foreach ($arFReviews as $item)
		{
			$count = 0;
			$summ = 0;
			foreach ($prop_from_rating as $prop)
			{
				if (!empty($item["PROPERTY_" . $prop . "_VALUE"]) && $item["PROPERTY_" . $prop . "_VALUE"] != 0)
				{
					$count++;
					$summ += $item["PROPERTY_" . $prop . "_VALUE"];
					$arCounts[$prop]++;
					$arSums[$prop] += $item["PROPERTY_" . $prop . "_VALUE"];
				}
			}
		}

		$cnt = 0;
		foreach ($prop_from_rating as $prop)
		{
			if(!empty($arSums[$prop]) && !empty($arCounts[$prop]))
			{
				$raiting += $arSums[$prop] / $arCounts[$prop];
				$cnt++;
			}
		}

		if($raiting > 0 && $cnt > 0) {
			$raiting = round($raiting / $cnt, 1);
		}


		\CIBlockElement::SetPropertyValues($itemID, $clinicIBl, $raiting, 'RATING');
	}

    /**
     * Модификация полей пользователя перед регистрацией в системе bitrix
     * @param array $arFields
     */
    public static function bxOnBeforeUserRegister(&$arFields) {
        if (self::isProviderRequest()) {
            $arFields["ACTIVE"] = "N";
            $arFields["GROUP_ID"][] = 26;
        } 
        if (self::isAgentRequest()) {
            $arFields["ACTIVE"] = "N";
            $arFields["GROUP_ID"][] = 28; 
        }
        self::setLoginEqlEmail($arFields);
        self::passWorker("save", $arFields["PASSWORD"]);
    }

    /**
     * Производим действия после обновления пользователя (отправка письма поставщиуку и тд.)
     * @param array $arFields
     */
    public static function bxOnAfterUserUpdate($arFields) {

        if ($arFields['RESULT']) {

            if ($GLOBALS["USER"]->IsAdmin() && (defined("ADMIN_SECTION") && ADMIN_SECTION === true)) {

                // активируем пользователя как поставщика
                $db_groups = \CUser::GetUserGroupList($arFields["ID"]);

                $groups_id = null;

                while ($group = $db_groups->Fetch()) {
                    $groups_id[] = $group["GROUP_ID"];
                }

                $active_provider = (in_array(\Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "transfers_provider_group"), $groups_id) ||
                        in_array(\Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "placements_provider_group"), $groups_id) ||
                        in_array(\Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "sanatorium_provider_group"), $groups_id) ||
                        in_array(\Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "excursions_provider_group"), $groups_id));

                if ($active_provider) {

                    // отправляем письмо поставщику
                    \Bitrix\Main\Mail\Event::send(array(
                        "EVENT_NAME" => "TRAVELSOFT_BOOKING",
                        "LID" => $arFields['LID'],
                        "C_FIELDS" => array(
                            "NAME" => $arFields['NAME'],
                            "LAST_NAME" => $arFields['LAST_NAME'],
                            "EMAIL" => $arFields['EMAIL']
                        ),
                        "DUPLICATE" => 'N',
                        "MESSAGE_ID" => \Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "provider_active_mail_template")
                    ));
                }
            }

            $mtFields = array("email" => $arFields['EMAIL'], "login" => $arFields['EMAIL'], "password" => $arFields['CONFIRM_PASSWORD'] ? $arFields['CONFIRM_PASSWORD'] : self::passWorker(), "update" => true);

            if ($arFields["ID"]) {
                $mtFields["userId"] = $arFields["ID"];
                if (!$mtFields["email"]) {
                    $arUser = $GLOBALS["USER"]->GetByID($arFields["ID"])->Fetch();
                    $mtFields["email"] = $arUser["EMAIL"];
                    $mtFields["login"] = $mtFields["email"];
                }
            }

            self::createUpdateMTUser($mtFields);
        }
    }

    /**
     * Модификация полей пользователя после регистрации в системе bitrix
     * @param array $arFields
     */
    public static function bxOnAfterUserRegister($arFields) {
      $new_partner = new \B24_Partners($arFields);
      $new_partner->contactAdd();
      $new_partner->companyAdd();
      $new_partner->dealAdd();
      file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logging.json', $new_partner->partner_data_json);

        if ($arFields['USER_ID'] > 0) {

            if (self::isAgentRequest()) {

                // вносим дополнительные поля в профиль агента на сайте
                $add_fields = array("UF_LEGAL_NAME", "UF_LEGAL_ADDRESS", "UF_BANK_NAME", "UF_BANK_ADDRESS",
                    "UF_BANK_CODE", "UF_CHECKING_ACCOUNT", "UF_OKPO");

                for ($i = 0, $cnt = count($add_fields); $i < $cnt; $i++) {
                    if (self::getRequest()->getPost($add_fields[$i]) <> '') {
                        $arFields[$add_fields[$i]] = self::getRequest()->getPost($add_fields[$i]);
                    }
                }

                // отправка письма менеджеру
                \Bitrix\Main\Mail\Event::send(array(
                    "EVENT_NAME" => "TRAVELSOFT_BOOKING",
                    "LID" => SITE_ID,
                    "C_FIELDS" => array(
                        "USER_ID" => $arFields['USER_ID']
                    ),
                    "DUPLICATE" => 'N',
                    "MESSAGE_ID" => \Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "agent_register_mail_template")
                ));

                //агент
                self::createUpdateMTUser(array(
                    "email" => $arFields["EMAIL"],
                    "password" => $arFields["PASSWORD"],
					"userId" => $arFields['USER_ID']
					//"is_agent" => true
					/*"username" => $GLOBALS["USER"]->GetFullName(),
"company" => 		$arFields["UF_LEGAL_NAME"]*/));

                // ОТПРАВКА СТАНДАРТНОГО ПИСЬМО О РЕГИСТРАЦИИ агента
                $arMailsId = [
                    "s1" => 95,
                    "by" => 97,
                    "en" => 96
                ];
                \Bitrix\Main\Mail\Event::send(array(
                    "EVENT_NAME" => "TRAVELSOFT_BOOKING",
                    "LID" => SITE_ID,
                    "C_FIELDS" => array(
                        "EMAIL" => $arFields["USER_EMAIL"],
                        "LOGIN" => $arFields["LOGIN"],
                        "PASSWORD" => $arFields["PASSWORD"],
                        "USER_ID" => $arFields['USER_ID']
                    ),
                    "DUPLICATE" => 'N',
                    "MESSAGE_ID" => $arMailsId[SITE_ID]
                ));
                $_SESSION["JUST_REGISTER_AGENT"] = true;
                return true;
            } else {

                self::createUpdateMTUser(array("email" => $arFields["EMAIL"], "password" => $arFields["PASSWORD"]));

                if (self::isProviderRequest()) {

					//Фикс добавления поставщика в МТ, так как self::createUpdateMTUser() отправит bitrix_id: 0
					self::createUpdateMTUser(array("email" => $arFields["EMAIL"], "password" => $arFields["PASSWORD"], "userId" => $arFields['USER_ID']));

                    \Bitrix\Main\Mail\Event::send(array(
                        "EVENT_NAME" => "TRAVELSOFT_BOOKING",
                        "LID" => SITE_ID,
                        "C_FIELDS" => array(
                            "USER_ID" => $arFields['USER_ID'],
                            "TYPE_PROVIDER" => self::getTypeProvider($_POST["PROVIDER_GROUPS"])
                        ),
                        "DUPLICATE" => 'N',
                        "MESSAGE_ID" => \Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "provider_register_mail_template")
                    ));

                    \Bitrix\Main\Mail\Event::send(array(
                        "EVENT_NAME" => "TRAVELSOFT_BOOKING",
                        "LID" => SITE_ID,
                        "C_FIELDS" => array(
                            "EMAIL" => $arFields["EMAIL"],
                            "LOGIN" => $arFields["LOGIN"],
                            "PASSWORD" => $arFields["PASSWORD"],
                        ),
                        "DUPLICATE" => 'N',
                        "MESSAGE_ID" => \Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "provider_after_register_mail_template")
                    ));

                    # используется для вывода спец. текста в форме авторизации поставщика,
                    # который только что зарегистрирован
                    $_SESSION["JUST_REGISTER_PROVIDER"] = true;

                    return true;
                }
            }

            // ОТПРАВКА СТАНДАРТНОГО ПИСЬМО О РЕГИСТРАЦИИ ПОЛЬЗОВАТЕЛЯ
            \Bitrix\Main\Mail\Event::send(array(
                "EVENT_NAME" => "TRAVELSOFT_BOOKING",
                "LID" => SITE_ID,
                "C_FIELDS" => array(
                    "EMAIL" => $arFields["EMAIL"],
                    "LOGIN" => $arFields["LOGIN"],
                    "PASSWORD" => $arFields["PASSWORD"],
                    "USER_ID" => $arFields['USER_ID']
                ),
                "DUPLICATE" => 'N',
                "MESSAGE_ID" => 2
            ));
        }
    }

    /**
     * обработчик события добавления нового пользователя
     * @param array $arFields
     */
    public static function bxOnBeforeUserAdd(&$arFields) {

        if ($arFields["EXTERNAL_AUTH_ID"] != "" && $arFields['XML_ID'] > 0) {

            if (!empty($_REQUEST["auth_service_id"])) {
                $arFields["EXTERNAL_AUTH_ID"] = $_REQUEST["auth_service_id"];
            } elseif (substr($arFields["LOGIN"], 0, 2) == "G_") {
                $arFields["EXTERNAL_AUTH_ID"] = "GoogleOAuth";
            }

            $_SESSION['__TRAVELSOFT']['USER_SOCSERVICES'] = $arFields;
            LocalRedirect('/private-office/user-profile/socservices.php');
            //header( 'Location: https://'.$_SERVER["SERVER_NAME"].'/private-office/user-profile/socservices.php', true, 301 );
        }
    }

    /**
     * обработчик события добавления нового элемента иб
     * @param array $arFields
     */
    static public function bxOnStartIBlockElementAdd(&$arFields) {

        $langID = defined('LANGUAGE_ID') ? LANGUAGE_ID : "ru";

        if ($arFields['CODE'] == "") {
            $arParams = array("replace_space" => "-", "replace_other" => "-");
            $arFields['CODE'] = \Cutil::translit($arFields['NAME'], $langID, $arParams);
        }
    }

    /**
     * обработчик события после добавления элемента иб
     * @param array $arFields
     */
    static public function bxOnAfterIBlockElementAdd(&$arFields) {

        if ($arFields["RESULT"] > 0) {

            if (self::isExcursionTour($arFields['IBLOCK_ID'])) {

                // создаём услугу по туру
                \Bitrix\Main\Loader::includeModule("highloadblock");

                $data_class = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity(
                                \Bitrix\Highloadblock\HighloadBlockTable::getById(SERVICES_BOOKING_HL_BLOCK)->fetch())->getDataClass();

                $properties = self::__get_ibproperty_id_by_code($arFields["IBLOCK_ID"], array("MAX_PEOPLE", "MAX_ADULTS", "MAX_CHILDREN"));

                $service_id = $data_class::add(array(
                            "UF_NAME" => $arFields["NAME"],
                            "UF_IBLOCK_ELEMENT_ID" => $arFields["ID"],
                            "UF_SERVICE_TYPE_NAME" => $arFields["PROPERTY_VALUES"][857][316] == 316 ? 8 : 6,
                            "UF_USER_ID" => $GLOBALS["USER"]->GetID(),
                            "UF_PEOPLE" => $arFields["PROPERTY_VALUES"][$properties["MAX_PEOPLE"]]["VALUE"],
                            "UF_PLACES_MAIN" => $arFields["PROPERTY_VALUES"][$properties["MAX_PEOPLE"]]["VALUE"],
                            "UF_ADULTS" => $arFields["PROPERTY_VALUES"][$properties["MAX_ADULTS"]]["VALUE"],
                            "UF_CHILDREN" => $arFields["PROPERTY_VALUES"][$properties["MAX_CHILDREN"]]["VALUE"],
                ));
                booking\Utils::clearCacheByTag("highloadblock_" . booking\Utils::getOpt("services"));
            }
        }
    }

    /**
     * Устанавливает добавленный курс валют текущим
     * @param array $arFields
     */
    static public function setNewCurrencyCourse(&$arFields) {

        if ($arFields["RESULT"] > 0 && $arFields["IBLOCK_ID"] == \Bitrix\Main\Config\Option::get("travelsoft.currency", "courses_iblock_id")) {

            \Bitrix\Main\Config\Option::set("travelsoft.currency", "base_courses_id", $arFields["ID"]);
        }
    }

    static public function createLeadBx24AfterFeedbackAdd($arFields) {
        if ($arFields["RESULT"] > 0 && $arFields["IBLOCK_ID"] == CALLBACK_IBLOCK_ID) {
            Bx24::createLead($arFields);
        }
    }

    /**
     * обработчик события после добавления элемента иб
     * @param array $arFields
     */
    static public function bxOnAfterIBlockElementUpdate(&$arFields) {

        if ($arFields["RESULT"]) {

            if (self::isExcursionTour($arFields['IBLOCK_ID'])) {

                // создаём услугу по туру
                \Bitrix\Main\Loader::includeModule("highloadblock");

                $data_class = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity(
                                \Bitrix\Highloadblock\HighloadBlockTable::getById(SERVICES_BOOKING_HL_BLOCK)->fetch())->getDataClass();

                $res = $data_class::getList(array(
                            "filter" => array("UF_IBLOCK_ELEMENT_ID" => $arFields["ID"]),
                            "select" => array("ID")
                        ))->fetch();

                $properties = self::__get_ibproperty_id_by_code($arFields["IBLOCK_ID"], array("MAX_PEOPLE", "MAX_ADULTS", "MAX_CHILDREN"));

                if ($res["ID"] > 0) {
                    $update = [
                        "UF_NAME" => $arFields["NAME"],
                        "UF_IBLOCK_ELEMENT_ID" => $arFields["ID"],
                        "UF_SERVICE_TYPE_NAME" => $arFields["PROPERTY_VALUES"][857][316] == 316 ? 8 : 6,
                        "UF_PEOPLE" => $arFields["PROPERTY_VALUES"][$properties["MAX_PEOPLE"]]["VALUE"],
                        "UF_ADULTS" => $arFields["PROPERTY_VALUES"][$properties["MAX_ADULTS"]]["VALUE"],
                        "UF_CHILDREN" => $arFields["PROPERTY_VALUES"][$properties["MAX_CHILDREN"]]["VALUE"]
                    ];

                    // не админка и не супер пользователь и не админ
                    if ((!defined('ADMIN_SECTION') || ADMIN_SECTION !== true) && !in_array(21, $GLOBALS["USER"]->GetUserGroupArray()) && !$GLOBALS["USER"]->IsAdmin()) {

                        $update['UF_USER_ID'] = $GLOBALS["USER"]->GetID();
                    }
                    $data_class::update($res["ID"], $update);
                    booking\Utils::clearCacheByTag("highloadblock_" . booking\Utils::getOpt("services") . "_" . $res["ID"]);
                    booking\Utils::clearCacheByTag("highloadblock_" . booking\Utils::getOpt("services"));
                }
            }
        }
    }

    /**
     * Делаем LOGIN = EMAIL
     * @param array $arFields
     */
    public static function setLoginEqlEmail(&$arFields) {
        $arFields['LOGIN'] = $arFields['EMAIL'];
    }

    /**
     * Возвращает объект запроса
     * @return \Bitrix\Main\HttpRequest
     */
    protected static function getRequest() {
        return \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
    }

    /**
     * Проверка на запрос от формы заполнения тура
     * @param int $iblock_id
     * @return boolean
     */
    public static function isExcursionTour($iblock_id) {

        self::$request = self::getRequest();

        if ((self::$request->isPost() && self::$request->getPost('IS_EXC_TOUR') == "Y") || $iblock_id == 33) {
            return true;
        }

        return false;
    }

    /**
     * Проверка что запрос исходит от партнёра
     * @return boolean
     */
    public static function isProviderRequest() {

        self::$request = self::getRequest();

        if (self::$request->isPost() && self::$request->getPost('IS_PROVIDER') == "Y") {
            return true;
        }

        return false;
    }

    /**
     * Проверка что запрос исходит от агента
     * @return boolean
     */
    public static function isAgentRequest() {

        self::$request = self::getRequest();

        if (self::$request->isPost() && self::$request->getPost('IS_AGENT') == "Y") {
            return true;
        }

        return false;
    }
    public static function isJson($string) {
     json_decode($string);
     return (json_last_error() == JSON_ERROR_NONE);
    }
    /**
     * Новый пользователь в ПК-МастерТур
     */
    public static function createUpdateMTUser($parameters) {
        try {
            // создаём пользователя в ПК-МастерТур
            if (\Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools")) {

                $returndata = \travelsoft\booking\Gateway::createUpdateMTUser(array(
                                    "url" => \Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "tsmo_url"),
                                    "is_agent" => $parameters["is_agent"],
                                    "update" => $parameters["update"],
                                    "user_info" => array(
                                        "bitrix_id" => $parameters['userId'] ? $parameters['userId'] : $GLOBALS["USER"]->GetID(),
                                        "email" => $parameters['email'],
                                        "login" => $parameters['email'],
                                        "password" => $parameters['password'],
                                        "username" => $parameters["username"],
                                        "company" => $parameters["company"]
                                    )
                ));
                if (self::isJson($returndata)) $response = \Bitrix\Main\Web\Json::decode($returndata);
                if ($response["result"]["token"] && $parameters["update"] != 1) {
                    $_SESSION['__TRAVELSOFT']["TOKEN"] = $response["result"]["token"];
                }
            }
        } catch (\Exeption $e) {
            
        }
    }

    private static function __get_ibproperty_id_by_code($iblock_id, $property_code) {

        \Bitrix\Main\Loader::includeModule("iblock");

        $result = null;

        for ($i = 0, $cnt = count($property_code); $i < $cnt; $i++) {

            $db_properties = \CIBlockProperty::GetList(array("ID" => "DESC"), array("IBLOCK_ID" => $iblock_id, "CODE" => $property_code[$i]));

            while ($property = $db_properties->Fetch()) {
                $result[$property["CODE"]] = $property["ID"];
            }
        }

        return $result;
    }

    /**
     * Сохранение/удаление/получение пароля в массиве $GLOBALS
     * @param string $action
     * @param string $password
     * @return string|null
     */
    private static function passWorker($action = null, $password = '') {

        if ($action == "save") {
            $GLOBALS["PASSWORD"] = $password;
        } elseif ($action == "delete") {
            unset($GLOBALS["PASSWORD"]);
        } else {
            return $GLOBALS["PASSWORD"];
        }
    }

    /**
     * @param mixed $providerGroups
     * @return string
     */
    protected static function getTypeProvider($providerGroups) {

        if (!is_array($providerGroups) && empty($providerGroups)) {
            return "";
        }

        $dbGroup = \CGroup::GetList(($by = "c_sort"), ($order = "desc"), array("ID" => implode(" | ", $providerGroups)));
        while ($arGroup = $dbGroup->Fetch()) {
            $arGroups[] = $arGroup["NAME"];
        }
        return implode(", ", $arGroups);
    }

}
