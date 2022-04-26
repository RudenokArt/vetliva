<?php

/*
 * header 404 если не POST запрос со страницы
 * 
 * Статусы ответов:
 *                  0 - не удалось удалить позицию в корзине
 *                  1 - удаление позиции прошло удачно
 *                  2 - введён некорректный email
 *                  3 - удачная авторизация
 *                  4 - ошибка авторизации
 *                  5 - email и подтверждение email не совпадают
 *                  6 - успешная регистрация
 *                  7 - ошибка регистрации
 *                  8 - пользователя с введенным $email не существует
 *                  9 - пользователь с введенным $email существует
 *                  10 - необходимо ввести промокод
 *                  11 - промокод не найден или срок его действия истек
 *                  12 - промокод уже применен
 *                  13 - промокод неактивен
 *                  14 - данный промокод закончился
 *                  15 - общая стоимость корзины недостаточна, чтобы применить данный промокод
 *                  16 - промокод не действует на данные виды услуг
 *                  17 - промокод успешно применен
 *                  18 - промокод недоступен для данной группы пользователей 
 *                  19 - гражданство туристов соответсвует услугам в корзине 
 *                  20 - гражданство туристов не соответсвует услугам в корзине 
 *                  21 - вернулся результат пересчета корзины в зависимости от гражданства 
 *                  22 - корзина установлена
 */

define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC", "Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);

$documentRoot = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
require_once($documentRoot . '/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main\Web\Json;

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();

if (!check_bitrix_sessid() || !$request->isPost()) {
    $protocol = filter_input(INPUT_SERVER, 'SERVER_PROTOCOL');
    header($protocol . " 404 Not Found");
    exit;
}

\Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools");

try {

    if ($request->getPost("action") === "reset-basket") {
        $basket = new \travelsoft\booking\Basket;

        $basket->reset($basket->getTmpBasketFields());

        $basket->_change_blocked = true;
        throw new Exception(Json::encode(
                        array(
                            "message" => "",
                            "status" => 22
                        )
                )
        );
    }

    if ($request->getPost("action") === "pre-recalculation-basket-with-citizenship") {

        $basket = new \travelsoft\booking\Basket;

        $tourists_for_service = $request->getPost("tourists_for_service");

        $tourists = $request->getPost("tourist");

        $arBasketFields = $arRates = [];

        while ($arFetch = $basket->fetch()) {

            $item = $arFetch['item'];

            $basketFields = $item->getPropertiesLikeArray();

            $one_man_in_source_request = $tourists_for_service[$arFetch['position']]['adults'] == 1 && $tourists_for_service[$arFetch['position']]["children"] == 0;
            $basketFields["price_before"] = travelsoft\booking\Utils::convertCurrency($item->price, $item->currency);
            $basketFields["price_after"] = $basketFields["price_before"];

            if (!in_array($item->type, ["placements", "sanatorium"]) || !$item->can_buy) {
                $arBasketFields[] = $basketFields;
                continue;
            }

            if (!isset($arRates[$item->rate_id])) {
                $arRates[$item->rate_id] = current(travelsoft\booking\datastores\RatesDataStore::get(array(
                            "filter" => array("ID" => $item->rate_id),
                            "select" => array("ID", "UF_RF_PRICES", "UF_BR_PRICES", "UF_EU_PRICES", "UF_NAME")
                )));
            }


            if (
                    (
                    1 != $arRates[$item->rate_id]["UF_RF_PRICES"] &&
                    1 != $arRates[$item->rate_id]["UF_BR_PRICES"] &&
                    1 != $arRates[$item->rate_id]["UF_EU_PRICES"]
                    ) ||
                    (
                    1 == $arRates[$item->rate_id]["UF_RF_PRICES"] &&
                    1 == $arRates[$item->rate_id]["UF_BR_PRICES"] &&
                    1 == $arRates[$item->rate_id]["UF_EU_PRICES"]
                    )
            ) {
                $arBasketFields[] = $basketFields;
                continue;
            }

            $arGroupPeopleByCitizenship = [];
            if (isset($tourists_for_service[$arFetch['position']])) {

                if ($tourists_for_service[$arFetch['position']]["adults"]) {
                    foreach ($tourists_for_service[$arFetch['position']]["adults"] as $tourist_index) {
                        if (isset($tourists[$tourist_index])) {
                            $arGroupPeopleByCitizenship[$tourists[$tourist_index]["citizenship"]]["adults"] ++;
                        }
                    }
                }

                if ($tourists_for_service[$arFetch['position']]["children"]) {
                    foreach ($tourists_for_service[$arFetch['position']]["children"] as $tourist_index) {
                        if (isset($tourists[$tourist_index])) {
                            $arGroupPeopleByCitizenship[$tourists[$tourist_index]["citizenship"]]["children"] ++;
                        }
                    }
                }
            }

            $arRateCitizenshipsRelation = [];
            if (1 === $arRates[$item->rate_id]["UF_RF_PRICES"]) {
                $arRateCitizenshipsRelation[] = "UF_RF_PRICES";
            }
            if (1 === $arRates[$item->rate_id]["UF_BR_PRICES"]) {
                $arRateCitizenshipsRelation[] = "UF_BR_PRICES";
            }
            if (1 === $arRates[$item->rate_id]["UF_EU_PRICES"]) {
                $arRateCitizenshipsRelation[] = "UF_EU_PRICES";
            }

            $arCitizenship = \travelsoft\booking\Utils::getCitizenship(true);
            $arCitizenPrices = \travelsoft\booking\Utils::getCitizenPrices();

            if (empty(array_filter($arGroupPeopleByCitizenship, function ($citizenship_id) use ($arRateCitizenshipsRelation, $arCitizenPrices, $arCitizenship) {
                                return !in_array($arCitizenPrices["ITEMS"][$arCitizenship[$citizenship_id]["UF_CITIZEN_PRICE"]], $arRateCitizenshipsRelation);
                            }, ARRAY_FILTER_USE_KEY))) {
                $arBasketFields[] = $basketFields;
                continue;
            }

            $break_flag = false;
            $total_price = $total_discount_price = 0.0;

            $requestClassName = "travelsoft\\booking\\" . $item->type . "\\Request";

            $priceCalculatorClassName = "travelsoft\\booking\\" . $item->type . "\\PriceCalculator";

            $arSourceAllocate = $priceCalculatorClassName::_allocate(\travelsoft\booking\Utils::getServiceById($item->service_id)[0], $item->adults, $item->children, $item->children_age);

            foreach ($arGroupPeopleByCitizenship as $citizenship_id => $arPeople) {

                if (!isset($arPeople["adults"])) {
                    $arPeople["adults"] = 0;
                }
                if (!isset($arPeople["children"])) {
                    $arPeople["children"] = 0;
                }

                $citizenship_price_code = $arCitizenPrices["ITEMS"][$arCitizenship[$citizenship_id]["UF_CITIZEN_PRICE"]];

                $arSourceAllocate = travelsoft\booking\Utils::getChangedSourceAllocate($arSourceAllocate, $arCurrentAllocate = travelsoft\booking\Utils::getCurrentAllocate($arSourceAllocate, $arPeople));

                $arCalculation = [];

                if (1 === $arRates[$item->rate_id]["UF_RF_PRICES"]) {
                    die("1");
                    // расчет
                    $arCalculation = \travelsoft\booking\Utils::recalculateOnlyPriceType(array_merge($basketFields, $arPeople), $arCurrentAllocate, $one_man_in_source_request, $priceCalculatorClassName);
                } else {

                    // поиск по строгому совпадению названий тарифов
                    $arRate = current(travelsoft\booking\datastores\RatesDataStore::get(array(
                                "filter" => array(
                                    array(
                                        "LOGIC" => "OR",
                                        array($citizenship_price_code => 1),
                                        array("UF_EU_PRICES" => 0, "UF_RF_PRICES" => 0, "UF_BR_PRICES" => 0)
                                    ),
                                    "UF_NAME" => $arRates[$item->rate_id]["UF_NAME"],
                                    "UF_SERVICES_ID" => array($item->service_id)
                                ),
                                "select" => array("ID")
                    )));

                    // поиск по не строгому совпадению названий тарифов
                    if (!isset($arRate["ID"])) {
                        $arRate = current(travelsoft\booking\datastores\RatesDataStore::get(array(
                                    "filter" => array(
                                        array(
                                            "LOGIC" => "OR",
                                            array($citizenship_price_code => 1),
                                            array("UF_EU_PRICES" => 0, "UF_RF_PRICES" => 0, "UF_BR_PRICES" => 0)
                                        ),
                                        "UF_NAME" => "%" . $arRates[$item->rate_id]["UF_NAME"] . "%",
                                        "UF_SERVICES_ID" => array($item->service_id)
                                    ),
                                    "select" => array("ID")
                        )));
                    }

                    if (!isset($arRate["ID"])) {
                        $break_flag = true;
                        break;
                    } else {
                        // расчет
                        $arCalculation = \travelsoft\booking\Utils::recalculateOnlyPriceType(array_merge($basketFields, ["rate_id" => $arRate["ID"]], $arPeople), $arCurrentAllocate, $one_man_in_source_request, $priceCalculatorClassName);
                    }
                }

                if (!empty($arCalculation)) {
                    $arPrice = travelsoft\booking\Utils::_searchPriceFromCalculationData($arCalculation);
                    $total_price += travelsoft\booking\Utils::convertCurrency($arPrice["PRICE"], $arPrice["CURRENCY_ID"], $item->currency, true);
                    if (isset($arPrice["DISCOUNT_PRICE"]) && $arPrice["DISCOUNT_PRICE"] > 0) {
                        $total_discount_price += travelsoft\booking\Utils::convertCurrency($arPrice["DISCOUNT_PRICE"], $arPrice["CURRENCY_ID"], $item->currency, true);
                    }
                } else {

                    $break_flag = true;
                    break;
                }
            }

            if ($break_flag) {
                $arBasketFields[] = array_merge($basketFields, array("can_buy" => false));
                break;
            }

            if ($total_price > 0) {
                $basketFields["price"] = $total_price;
                if ($total_discount_price > 0) {
                    $basketFields["discount"] = [$total_discount_price];
                }
                $basketFields["price_after"] = travelsoft\booking\Utils::convertCurrency($total_price, $item->currency);
                $arBasketFields[] = $basketFields;
            }
        }

        // переприменение промокодов
        $arPromo = \travelsoft\booking\Promo::getList();

        foreach ($arPromo as $value) {

            $promo = \travelsoft\booking\Promo::create($value);
            $promo->__apply($arBasketFields);
        }

        // переприменяем программу лояльности
        foreach ($arBasketFields as $k => $item_fields) {
            $arBasketFields[$k] = \travelsoft\booking\LoyalityProgramm::applyForBasketItem($item_fields);
        }


        // подсчет стоимости корзины и скидки после пересчета
        $total_price_after = 0.00;
        $discount_after = 0.00;

        foreach ($arBasketFields as $arFields) {
            if (!$arFields["can_buy"]) {
                continue;
            }
            $total_price_after += travelsoft\booking\Utils::convertCurrency($arFields["price"], $arFields["currency"], null, true);
            if (!empty($arFields["discount"])) {
                foreach ($arFields["discount"] as $d) {
                    $discount_after += travelsoft\booking\Utils::convertCurrency($d, $arFields["currency"], null, true);
                }
            }
        }

        // сохранение временной корзины в виде массива полей
        $basket->setTmpBasketFields($arBasketFields);

        CBitrixComponent::includeComponentClass("travelsoft:travelsoft.booking.do");

        $component = new TravelsoftBookingDo;

        $component->arResult = ["BASKET" => $basket];

        throw new Exception(Json::encode(
                        array(
                            "message" => "",
                            "status" => 21,
                            "cart_items" => $component->getJsCartItemsFromCartElementFields($arBasketFields),
                            "total_cost" => $total_price_after > 0 ? travelsoft\booking\Utils::convertCurrency($total_price_after - $discount_after, travelsoft\booking\Utils::getCurrentCurrency()['iso']) : "0.00",
                            "discount" => $discount_after > 0 ? travelsoft\booking\Utils::convertCurrency($discount_after, travelsoft\booking\Utils::getCurrentCurrency()['iso']) : "0.00"
                        )
                )
        );
    }

    if ($request->getPost("action") === "verification-citizenship-in-services") {

        CBitrixComponent::includeComponentClass("travelsoft:travelsoft.booking.do");

        $component = new TravelsoftBookingDo;

        $component->arParams["_POST"] = ["tourist" => $request->getPost("tourist")];

        $component->arResult = array(
            "ERRORS" => array(),
            "BASKET" => new \travelsoft\booking\Basket
        );

        if ($component->_verificationCitizenshipInServices()) {
            throw new Exception(Json::encode(array("message" => "", "status" => 19)));
        } else {
            throw new Exception(Json::encode(array("message" => "", "status" => 20, "cart_items" => $component->getJsCartItems(), "total_cost" => $component->getTotalBasketCost(), "discount" => $component->getDiscountBasket())));
        }
    }

    if ($request->getPost("action") === "apply_promo") {

        if (strlen($request->getPost('promo')) > 0) {

            \Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools");

            $promo = \travelsoft\booking\Promo::create($request->getPost('promo'));
            if ($promo->id > 0) {

                $basket = new travelsoft\booking\Basket;
                $status = $promo->apply($basket);
                throw new Exception(Json::encode(array(
                            "message" => "",
                            "promocodes" => travelsoft\booking\Promo::getList(),
                            "status" => intVal($status),
                            "cost" => $basket->formattedCost(),
                            "discount" => $basket->formattedDiscount(),
                            "total" => $basket->formattedTotal(),
                )));
            }
            throw new Exception(Json::encode(array("message" => "", "status" => 11)));
        }
        throw new Exception(Json::encode(array("message" => "", "status" => 10)));
    }

    if ($request->getPost("action") === "delete_pos") {

        if (!is_null($request->getPost("position"))) {

            \Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools");

            $basket = new \travelsoft\booking\Basket();

            // проверка наличия позиции в корзине
            if ($basket->isExists($request->getPost("position"))) {

                $basket->delete($request->getPost("position"));

                $basket->clearDiscount();

                if ($basket->count() <= 0) {
                    $basket->clear();
                }

                travelsoft\booking\Promo::clear();

                throw new Exception(Json::encode(array(
                            "message" => "",
                            "status" => 1,
                            "promocodes" => array(),
                            "count_of_people" => $basket->countOfPeople(),
                            "cost" => $basket->formattedCost(),
                            "discount" => $basket->formattedDiscount(),
                            "totalbasket" => $basket->count(),
                            "total" => $basket->formattedTotal())));
            }
        }

        throw new Exception(Json::encode(array("message" => "", "status" => 0)));
    }

    $email = filter_var($request->getPost("email"), FILTER_VALIDATE_EMAIL);

    if ($request->getPost("action") === "check_auth") {

        if ($email) {

            if (bx_user_exists($email)) {

                throw new Exception(Json::encode(array("message" => "", "status" => 9)));
            }

            throw new Exception(Json::encode(array("message" => "", "status" => 8)));
        }

        throw new Exception(Json::encode(array("message" => "Wrong email", "status" => 2)));
    } elseif ($request->getPost("action") === "do_authorize") {

        $error = null;
        if (bx_authorize($email, $password, $error)) {

            $basket = \travelsoft\booking\LoyalityProgramm::apply(new travelsoft\booking\Basket);


            throw new Exception(Json::encode([
                        "message" => null,
                        "status" => 3,
                        "is_agent" => \travelsoft\booking\Utils::isAgent(),
                        "total_cost" => $basket->formattedTotal(),
                        "discount" => $basket->formattedDiscount(),
                        "cost" => $basket->formattedCost()
            ]));
        }

        throw new Exception(Json::encode(array("message" => strip_tags($error), "status" => 4)));
    } elseif ($request->getPost("action") === "do_registration") {

        $error = null;

        $confirm_email = filter_var($request->getPost("confirm_email"), FILTER_VALIDATE_EMAIL);

        if ((string) $email !== (string) $confirm_email) {

            throw new Exception(Json::encode(array("message" => "emails not equals", "status" => 5)));
        }

        if (bx_user_register($email, $error, $message)) {

            throw new Exception(Json::encode(array("message" => strip_tags($message), "status" => 6)));
        }

        throw new Exception(Json::encode(array("message" => strip_tags($error), "status" => 7)));
    }
} catch (\Exception $e) {

    header('Content-Type: application/json; charset=' . SITE_CHARSET);
    echo Json::encode($e->getMessage());
    exit;
}


/* проверка существования пользователя по email */

function bx_user_exists($email) {

    $dbres = Bitrix\Main\UserTable::getList(
                    array(
                        'select' => array('ID'),
                        'filter' => array('EMAIL' => $email)
                    )
            )->fetch();

    if ($dbres["ID"] > 0) {
        return true;
    }

    return false;
}

/** регистрируем нового пользователя * */
function bx_user_register($email, &$error, &$message) {
    global $USER;   
    $result = $USER->SimpleRegister($email);
    if ($result["TYPE"] != "ERROR") {
        $message = $result["MESSAGE"];
        $request = Bitrix\Main\Application::getInstance()->getContext()->getRequest(); 
        if ($request->getPost("male")=='ж') $male = 'F'; else $male = 'M';
        $fields = Array(
              "NAME"              => $request->getPost("name"),
              "LAST_NAME"         => $request->getPost("last_name"),
              "PERSONAL_BIRTHDAY" => $request->getPost("birthdate"),
              "UF_TSCITIZENSHIP"  => $request->getPost("citizenship"),
              "PERSONAL_GENDER"   => $male,  
          );
        $new_userid =  $USER->GetID();
        $user = new CUser;  
        if ($user->Update($new_userid, $fields)) {
            return true;
        }
        else {
            $error = $user->LAST_ERROR;
			file_put_contents("reg_log.txt", "[update error]: " . $error . "\n", FILE_APPEND | LOCK_EX);
            return false;
        }
    }
    $error = $result["MESSAGE"];
	file_put_contents("reg_log.txt", "[register error]: " . $error . "\n", FILE_APPEND | LOCK_EX);
    return false;
}

/** авторизация пользователя * */
function bx_authorize($email, $password, &$error) {

    $result = $GLOBALS['USER']->Login($email, $password, "Y", "Y");

    if ($result === true) {
        return true;
    }

    $error = $result["MESSAGE"];

    return false;
}

/**
 * Получение текущей рассадки на основе исходной полученной рассадки людей
 * @param array $arAllocate
 * @param array $arPeople
 */
function get_current_allocate(array $arAllocate, array $arPeople) {

    $arCurrentAllocate = [
        "main" => [
            "adults" => 0,
            "children" => 0,
            "children_age" => []
        ],
        "additional" => [
            "adults" => 0,
            "children" => 0,
            "children_age" => []
        ]
    ];

    foreach ($arAllocate as $place_type => $arSubPeople) {

        if ($arPeople["adults"] > 0 && $arSubPeople["adults"] > 0) {

            $arCurrentAllocate[$place_type]["adults"] = $arPeople["adults"];
        }

        if ($arPeople["children"] > 0 && $arSubPeople["children"] > 0) {

            $arCurrentAllocate[$place_type]["children"] = $arPeople["children"];
            $arCurrentAllocate[$place_type]["children_age"] = array_slice($arSubPeople["children_age"], 0, $arPeople["children"]);
        }
    }

    return $arCurrentAllocate;
}
