<?php

namespace travelsoft;

class Bx24
{
    protected static $queryBaseUrl = 'https://bitrix.vetliva.by/rest/25270/nt6hm7xvb08l79te/';

    public static function createLead(array $arFields)
    {
        $data = [
            'ASSIGNED_BY_ID' => 25279,
            //'UF_CRM_1546942437770' => 78,
            'TITLE' => 'Оставлена заявка на VETLIVA',
            'UF_CRM_1623748725' => $arFields['NAME'],
            'UF_CRM_1623748901' => $arFields['DETAIL_TEXT'],
            'UF_CRM_1623748827' => $arFields['PROPERTY_VALUES']['EMAIL'],
            'UF_CRM_1623748767' => $arFields['PROPERTY_VALUES']['PHONE'],
            'UF_CRM_1623748870' => $arFields['PROPERTY_VALUES']['DATE'],
            'UF_CRM_1624281105' => $arFields['PROPERTY_VALUES']['PAGE'],
            'UF_CRM_1624281053' => $arFields['PROPERTY_VALUES']['OBJECTNAME'], //новое поле для CRM
        ];

        $lead = self::sendQuery('crm.lead.add', $data);
    }


    private static function sendQuery(string $method, array $fields, $id = null, array $data = [])
    {
        if ($id != null) {
            $data['id'] = $id;
        }

        $data['fields'] = $fields;
        $data['params'] = ['REGISTER_SONET_EVENT' => 'Y'];

        $result = file_get_contents(self::$queryBaseUrl . $method, false, stream_context_create([
            'ssl' => ["verify_peer" => false],
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ]));
        
        return json_decode($result, true);
    }

    //region vd создание лида из заказа в Мастер-Тур
    public static function createLeadFromBooking($arFields)
    {
        $fields = [
            'TITLE' => 'Бронирование на VETLIVA',
            'ASSIGNED_BY_ID' => 25279,
            'STATUS_ID' => "NEW",
            'SOURCE_ID' => 9,
        ];
        $fields = array_merge($fields, $arFields);

        $arReferer = self::getArReferer();
        if (!empty($arReferer)) {

            $link = $arReferer['URL_FROM'];

            $res = preg_replace('#^http://#', '', $link);
            $res = preg_replace('#^https://#', '', $res);
            $res = preg_replace('#^www\.#', '', $res);

            $link = explode("/", $res)[0];
            $link = preg_replace('#:443$#', '', $link);

            $refFields = [
                'UF_URL_FROM' => $link,
            ];
            $fields = array_merge($fields, $refFields);
        }

        $result = self::sendQuery('crm.lead.add', $fields);
    }

    public static function getArReferer()
    {
        $arReferer = [];

        if (!isset($GLOBALS["USER"]) || !\Bitrix\Main\Loader::includeModule('statistic')) return $arReferer;
        if ($GLOBALS["USER"]->IsAuthorized()) $userId = $GLOBALS["USER"]->getId();
        if (!empty($userId)) {

            $arFilterG = [
                'REGISTERED' => 'Y',
                "USER_ID" => $userId,
                'USER_ID_EXACT_MATCH' => 'Y',
            ];

            $arGuest = \CGuest::GetList(
                ($by = "s_id"),
                ($order = "desc"),
                $arFilterG,
                $is_filtered
            )->Fetch();
        }

        if (!empty($arGuest) && !empty($arGuest['LAST_SESSION_ID'])) {

            $arFilterS = [
                "ID" => $arGuest['LAST_SESSION_ID'],
                'ID_EXACT_MATCH' => 'Y',
            ];

            $arSession = \CSession::GetList(
                ($by = "s_id"),
                ($order = "desc"),
                $arFilterS,
                $is_filtered
            )->Fetch();
        }

        if (!empty($arSession)) {

            $arFilterR = [
                'SESSION_ID' => $arSession['ID'],
                'SESSION_ID_EXACT_MATCH' => 'Y'
            ];

            $arReferer = \CReferer::GetList(
                ($by = "id"),
                ($order = "desc"),
                $arFilterR,
                $is_filtered,
                $total,
                $group_by,
                $max
            )->Fetch();
        }

        if (empty($arGuest['FIRST_URL_FROM'])) {

            $arGuest['URL_FROM'] = $arGuest['FIRST_URL_TO'];
        } else {

            $arGuest['URL_FROM'] = $arGuest['FIRST_URL_FROM'];
        }

        if (empty($arReferer['URL_FROM'])) {

            $arReferer = $arSession;

            if (empty($arSession['URL_FROM'])) {

                $arReferer = $arGuest;
            }
        }

        $res = preg_match('#vetliva#', $arReferer['URL_FROM'], $matches);

        if ($res) {

            $arReferer = $arGuest;
        }

        return $arReferer;
    }

    public static function getLeadFieldsFromBooking($_arr_booking_data, $responseData)
    {
        $arSecvicesData = $_arr_booking_data['services'];
        $arServiceTypes = [];
        foreach ($arSecvicesData as &$service)
        {
            if (array_key_exists('route', $service['parameters'])) $service['parameters']['route'] = urldecode($service['parameters']['route']);
            if (array_key_exists('dep_point', $service['parameters'])) $service['parameters']['dep_point'] = urldecode($service['parameters']['dep_point']);

            foreach ($service['parameters']['hotels'] as &$hotel)
            {
                $hotel['name'] = urldecode($hotel['name']);
                $hotel['address'] = urldecode($hotel['address']);
                $hotel['phone'] = urldecode($hotel['phone']);
            }

            $type = '';
            switch ($service['type']) {
                case '319':
                case '4':
                    $type = 'Санаторий';
                    break;
                case '5':
                    $type = 'Проживание';
                    break;
                case '6':
                    $type = 'Экскурсия';
                    break;
                case '7':
                    $type = 'Трансфер';
                    break;
                case '8':
                    $type = 'Тур';
                    break;
            }
            if (!empty($type)) $arServiceTypes[] = $type;

            $service['parts']['name'] = urldecode($service['parts']['name']);
        }
        $arData['UF_DOGOVOR_SERVICES_DATA'] = json_encode($arSecvicesData, JSON_UNESCAPED_UNICODE);

        $arData['UF_DOGOVOR_BUYER_EMAIL'] = urldecode($_arr_booking_data['buyer_info']['email']);
        $arData['UF_DOGOVOR_BUYER_PHONE'] = urldecode($_arr_booking_data['buyer_info']['phone']);
        $arData['UF_DOGOVOR_SITE_LANGUAGE'] = urldecode($_arr_booking_data['buyer_info']['language']);

        $arData['UF_DOGOVOR_COMMENT'] = urldecode($_arr_booking_data['comment']);

        $arTourists = [];
        foreach ($_arr_booking_data['turists'] as $turistData)
        {
            $tStr = 'Имя: ' . urldecode($turistData['first_name']) . ' ; ' .
                'Фамилия: ' . urldecode($turistData['last_name']) . ' ; ' .
                'Пол: ' . urldecode($turistData['sex']) . ' ; ' .
                'Гражданство: ' . urldecode($turistData['citizenship']) . ' ; ' .
                'Номер паспорта: ' . urldecode($turistData['passport_num']) . ' ; ' .
                'Дата рождения: ' . urldecode($turistData['birth_date']) . ' ;';

            $arTourists[] = $tStr;
        }
        if (!empty($arTourists)) $arData['UF_DOGOVOR_TURISTS'] = $arTourists;

        $arData['UF_DOGOVOR_CODE'] = $responseData["result"]["dogovor_code"];
        if ($GLOBALS["USER"]->IsAuthorized()) {

            $userId = $GLOBALS["USER"]->getId();
            $arData['UF_USER_ID'] = $userId;
        }
        $arData['UF_DOGOVOR_PRICE_RUB'] = round($responseData["result"]["price"]['RUB'], 2) . '|RUB';
        $arData['UF_DOGOVOR_PRICE_USD'] = round($responseData["result"]["price"]['USD'], 2) . '|USD';
        $arData['UF_DOGOVOR_PRICE_EUR'] = round($responseData["result"]["price"]['EUR'], 2). '|EUR';
        $arData['UF_DOGOVOR_PRICE_BYN'] = round($responseData["result"]["price"]['BYN'], 2) . '|BYN';

        $arData['OPPORTUNITY'] = round($responseData["result"]["price"]['BYN'], 2);
        $arData['CURRENCY_ID'] = 'BYN';

        if (!empty($userId)) {

            $mtUserData = self::getMtAuthData($userId);
            $arData['UF_MT_USER_LOGIN'] = $mtUserData['LOGIN'];
            $arData['UF_MT_USER_PASSWORD'] = $mtUserData['PASSWORD'];
        }

        if (!empty($arServiceTypes)) $arData['UF_DOGOVOR_SERVICE_TYPE'] = $arServiceTypes;

        return $arData;
    }

    private static function randomString($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);

        $randomString = '';
        for ($m = 0; $m < $length; $m++)
        {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    private static function getMtAuthData($userId)
    {
        global $DB;

        try {

            $sql = 'select ID, USER_ID, LOGIN, PASSWORD from mt_users where USER_ID=' . $userId;
            $res = $DB->Query($sql);

            $result = [];
            while($item = $res->fetch())
            {
                $result = $item;
            }
        } catch (\Exception $e) {
            $result = null;
        }

        return $result;
    }

    private static function saveMtAuthData($mtUserData, $userId, $login, $passwordS)
    {
        global $DB;

        try {
            if (empty($mtUserData)) {

                $sql1 = "INSERT INTO mt_users (USER_ID, LOGIN, PASSWORD) VALUES (" . $userId . ", '" . $login . "', '" . $passwordS . "')";
                $DB->Query($sql1);

            } else {

                $sql2 = "UPDATE mt_users SET LOGIN='" . $login . "', PASSWORD='" . $passwordS . "' WHERE ID=" . intval($mtUserData['ID']);
                $DB->Query($sql2);
            }
        } catch (\Exception $e) {

        }
    }

    public static function saveMtUser($arFields)
    {
        $userId = intval($arFields['USER_ID']);
        $login = $arFields['LOGIN'];
        $password = $arFields['PASSWORD'];

        $original = $arFields['PASSWORD_ORIGINAL'];
        if ($original == 'Y' && !empty($password)) {

            $passAr = str_split($password);

            $arStr = [];
            for ($i = 0; $i < count($passAr); $i++)
            {
                if ($i % 2 == 0) {

                    $randomString = self::randomString(4);
                    $arStr[] = $passAr[$i] . $randomString;

                } else {

                    $randomString = self::randomString(3);
                    $randomString1 = self::randomString(1);
                    $arStr[] = $randomString1 . $passAr[$i] . $randomString;
                }
            }

            $passwordS = implode('', $arStr);
        }

        if (!empty($passwordS) && !empty($userId)) {

            $mtUserData = self::getMtAuthData($userId);

            self::saveMtAuthData($mtUserData, $userId, $login, $passwordS);
        }
    }
    //endregion vd
}
