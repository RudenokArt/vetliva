<?php

namespace travelsoft\booking;

/**
 * шлюз на удалённый сервер
 */
class Gateway {

    /**
     * @param array $options
     * @return String  ** Json String **
     */
    public static function sendRequest(array $options) {
        
        $result = file_get_contents(
                $options["url"], false, stream_context_create(
                        array(
                            'ssl' => array("verify_peer" => false),
                            'http' => array(
                                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                                'method' => 'POST',
                                'content' => json_encode(
                                        array(
                                            "jsonrpc" => "2.0",
                                            "method" => $options["method"],
                                            "params" => $options["params"],
                                            "id" => 0
                                        )
                                ),
                            ),
                        )
                )
        );

        /**
         * Логи ошибок запросов в МТ
         */
        $resParse = \Bitrix\Main\Web\Json::decode($result);
        if (empty($resParse) || !empty($resParse["error"])) {
            AddMessage2Log($result, 'sendRequestErrorResult');
            AddMessage2Log($resParse, 'sendRequestErrorResponse');
            AddMessage2Log($options, 'sendRequestErrorOptions');
        }
        
        return $result;
    }

    /**
     * Регистрация пользователя в ПК-МастерТур
     * @param array $parameters
     * @return ** Json String ** {"token": "значение"} или ошибка
     */
    public static function createUpdateMTUser(array $parameters) {

        $response = self::sendRequest(array(
                    "url" => $parameters["url"],
                    "method" => "create_new_user",
                    "params" => array(
                        "user_info" => $parameters["user_info"],
                        "isAgent" => $parameters["is_agent"] ? 1 : 0,
                        "update" => $parameters["update"] ? 1 : 0,
                    )
        ));

        return $response;
    }

    /**
     * Метод производит авторизацию пользователя с последующей выдачи ключа
     * (токена) для его дальнейшего использования в методах сервиса ПК-МастерТур.
     * @param array $parameters
     * @return json
     */
    public static function authorizeMtUser(array $parameters) {

        $response = self::sendRequest(array(
                    "url" => $parameters["url"],
                    "method" => "Connect",
                    "params" => array(
                        "login" => $parameters["login"],
                        "password" => $parameters["password"]
                    )
        ));

        return $response;
    }

    /**
     * Создание путёвки в ПК-МастерТур (бронирование)
     * @param array $parameters
     * @return json
     */
    public static function makeBooking(array $parameters) {

        /**
         * Экранируем двойные кавычки в название услуги
         */
        if(!empty($parameters["booking_data"]['services'][0]['parts']['name'])) {
            $parameters["booking_data"]['services'][0]['parts']['name'] = str_replace('%22','%5C%22', $parameters["booking_data"]['services'][0]['parts']['name']);
        }

        /**
         * Экранируем преренос строки в комментарии к заказу
         */
        if(!empty($parameters["booking_data"]['comment'])) {
            $parameters["booking_data"]['comment'] = str_replace('%0D%0A', '%5C%0A', $parameters["booking_data"]['comment']);
        }

        $response = self::sendRequest(array(
                    "url" => $parameters["url"],
                    "method" => "create_new_dogovor",
                    "params" => $parameters["booking_data"]
        ));

        return $response;
    }

    /**
     * получение списка заказов
     * @param array $parameters
     * @return json
     */
    public static function getOrderList(array $parameters) {

        $response = self::sendRequest(array(
                    "url" => $parameters["url"],
                    "method" => "get_dogovors_by_user",
                    "params" => $parameters["params"]
        ));

        return $response;
    }

    /**
     * получение списка заказов для партнера из партнерского модуля
     * @param array $parameters
     * @return json
     */
    public static function getPmOrderList(array $parameters) {

        $response = self::sendRequest(array(
                    "url" => $parameters["url"],
                    "method" => "get_dogovors_by_agent",
                    "params" => $parameters["params"]
        ));

        return $response;
    }

    /**
     * получение списка заказов для поставщика
     * @param array $parameters
     * @return json
     */
    public static function getPartnersServicesList(array $parameters) {

        $response = self::sendRequest(array(
                    "url" => $parameters["url"],
                    "method" => "get_services_by_partner",
                    "params" => $parameters["params"]
        ));

        return $response;
    }

    /**
     * получение информации по заказу
     * @param array $parameters
     * @return json
     */
    public static function getOrderDetail(array $parameters) {

        $response = self::sendRequest(array(
                    "url" => $parameters["url"],
                    "method" => "get_dogovor_info",
                    "params" => $parameters["params"]
        ));

        return $response;
    }

    /**
     * Возвращает статистику покупок услуг партнера
     * @param array $parameters
     * @return json
     */
    public static function getServicesStatisticsByPartner(array $parameters) {

        $parameters['params']['token'] = $_SESSION['__TRAVELSOFT']['TOKEN'];
        $response = self::sendRequest(array(
                    "url" => $parameters["url"],
                    "method" => "get_statistics_quantity_services_to_partner",
                    "params" => $parameters['params']
        ));

        return $response;
    }

    /**
     * получение информации по услуге для поставщика
     * @param array $parameters
     * @return json
     */
    public static function getPartnersServiceDetail(array $parameters) {

        $response = self::sendRequest(array(
                    "url" => $parameters["url"],
                    "method" => "get_service_info",
                    "params" => $parameters["params"]
        ));

        return $response;
    }

    public static function orderToCancel(array $parameters) {

        $response = self::sendRequest(array(
                    "url" => $parameters["url"],
                    "method" => "annulate_dogovor",
                    "params" => $parameters["params"]
        ));

        return $response;
    }

    /**
     * Получение списка сообщений пользователя по бронированию из ПК-МастерТур
     * @param array $parameters
     * @return json
     */
    public static function getBookingMessages(array $parameters) {
        return self::sendRequest(array(
                    "url" => $parameters["url"],
                    "method" => "get_dogovor_messages",
                    "params" => $parameters["params"],
        ));
    }

    /**
     * Отправка сообщения пользоватея по бронированию в ПК-МастерТур
     * @param array $parameters
     * @return json
     */
    public static function sendBookingMessage(array $parameters) {
        return self::sendRequest(array(
                    "url" => $parameters["url"],
                    "method" => "send_message",
                    "params" => $parameters["params"],
        ));
    }
    
    /**
     * Получение детальной информации по бонусам
     * @param array $parameters
     * @return array
     */
    public static function getBonusDetail (array $parameters) {

        return self::sendRequest(array(
                    "url" => $parameters["url"],
                    "method" => "get_bonus_detail",
                    "params" => $parameters["params"],
        ));
        
    }
    
    /**
     * Статус услуги в "Оплачено на месте"
     * @param array $parameters
     * @return json
     */
    public static function serviceToPaidOnTheSpot(array $parameters) {

        $response = self::sendRequest(array(
                    "url" => $parameters["url"],
                    "method" => "service_success_pay_on_spot",
                    "params" => $parameters["params"]
        ));

        return $response;
    }
    
    /**
     * Статус услуги в "Оплачено на месте"
     * @param array $parameters
     * @return json
     */
    public static function serviceNotToPaidOnTheSpot(array $parameters) {

        $response = self::sendRequest(array(
                    "url" => $parameters["url"],
                    "method" => "service_annulating",
                    "params" => $parameters["params"]
        ));

        return $response;
    }

    /**
     * Переводит статус заказа в "Оплачено на месте"
     * @return json
     */
    public static function orderToPaidOnTheSpot(array $parameters) {
        $response = self::sendRequest(array(
                    "url" => $parameters["url"],
                    "method" => "dogovor_pay_on_spot",
                    "params" => $parameters["params"]
        ));

        return $response;
    }

    public static function getTravellineBookings($parameters) {
        
        return self::_getPreparedTravellineResponse(self::_sendRequestForTravelline("get-bookings", $parameters));
    }

    public static function confirmTravellineBookings($parameters) {
        
        return self::_getPreparedTravellineResponse(self::_sendRequestForTravelline("confirm-bookings", $parameters));
    }

    protected static function _getPreparedTravellineResponse($json_response) {
        
        (new \travelsoft\rest\Logger(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/local/php_interface/include/rest_master_to_travelline_log.txt'))->write((string)$json_response);

        $decoded_response = json_decode((string)$json_response, true);

        return $decoded_response["result"][0];
    }

    protected static function _sendRequestForTravelline($method, $parameters) {
        
        return self::sendRequest(array(
                    "url" => "https://online.vetliva.ru/Vetliva/travelline_handler.ashx",
                    "method" => $method,
                    "params" => $parameters
        ));
    }
    
}
