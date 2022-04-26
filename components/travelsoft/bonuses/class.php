<?php

class TravelsoftBonuses extends CBitrixComponent {

    /** подключение модулей * */
    protected function __include_modules() {

        if (!\Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools")) {
            throw new Exception("Не найден модуль \"инструменты бронирования\" ");
        }
    }

    public function executeComponent() {

        try {

            $this->__include_modules();

            $this->arResult["BONUSES_DETAIL_INFO"] = array(
                "common" => array(
                    "total" => "1000",
                    "expend" => "200",
                    "waiting" => "300",
                    "active" => "500"
                ),
                "details" => array(
                    array(
                        "serviceId" => 1,
                        "statusId" => 1,
                        "date" => "12.07.2018",
                        "total" => 388.9,
                        "expend" => 0,
                        "waiting" => 200,
                        "active" => 388.9
                    )
                ),
                "services" => array(
                    array(
                        "id" => 1,
                        "en" => "Agreement",
                        "ru" => "Заказ",
                        "by" => "Заказ"
                    ),
                    array(
                        "id" => 2,
                        "en" => "Registration in the system",
                        "ru" => "Регистрация в системе",
                        "by" => "Регистрация в системе"
                    )
                ),
                "statuses" => array(
                    array(
                        "id" => 1,
                        "en" => "Waiting for enrollment",
                        "ru" => "Ожидает зачисления",
                        "by" => "Чакае залічэння"
                    ),
                    array(
                        "id" => 2,
                        "en" => "Credited to the bonus account",
                        "ru" => "Зачислено на бонусный счет",
                        "by" => "Залічана на бонусны рахунак"
                    ),
                    array(
                        "id" => 3,
                        "en" => "Written off",
                        "ru" => "Списано",
                        "by" => "Спісана"
                    ),
                    array(
                        "id" => 4,
                        "en" => "Order canceled",
                        "ru" => "Заказ аннулирован",
                        "by" => "Заказ ануляваны"
                    )
                )
            );

//            $this->arResult["BONUS_DETAIL_INFO"] = travelsoft\booking\Gateway::getBonusDetail(array(
//                "url" => \Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "tsmo_url"),
//                "params" => array("token" => $_SESSION["__TRAVELSOFT"]["TOKEN"])
//            ));

            $this->IncludeComponentTemplate();
        } catch (Exception $e) {
            ShowError($e->getMessage());
        }
    }

}
