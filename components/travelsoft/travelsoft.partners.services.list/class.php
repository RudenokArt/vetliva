<?php

/*
 * Компонент списка заказав бронирования
 */

class TravelsoftOrderList extends CBitrixComponent {
    /*
      Статусы по оплате
      0    В работе
      1    Не определен
      2    Аннулирован
      3    Wait-лист
      4    Не подтвержден
      5    Wait-лист+Не подтв.
      6    Ожидание
      7    Ok
      11    Web-турагент
      12    Web-гость
      16    Выставлен Счет
      17    Подтверждено
      18    Возврат путевки
      19    Запрос на аннуляцию
      20    Ожидание оплаты
      21    К оплате
      22    К оплате на месте
      23    Оплачено на месте
      24    Аннулирован (штраф)
      25    Аннулирован (удерж)
     */

    /**
     * Статусы заказа
     * @var array
     */
    protected $statuses = array(
        "unknown", // любой
        "0", // Ок
        "1", // Заказ на бронирование
        "2", // Подтверждение
        "4", // Послать список в Авиакомп
        "11", // Web-турагент
        "13", // Confirmed
        "14", // Not confirmed
        "15", // Wait
        "16", // Возврат путевки
        "17", // Выставлен счет
        "18", // Аннулирован
        "19", // К оплате на месте
        "20", // Оплата на месте,
        "21"// запрос на аннуляцию
    );

    /**
     * фильтр для запроса
     * @return array
     */
    protected function __getFilter() {

        $filter = array(
            "createdate" => array(),
            "begindate" => array(),
            "turist" => ""
        );

        if ($_REQUEST["order_filter"]["date_create"][0] <> '') {
            $filter["createdate"][0] = $_REQUEST["order_filter"]["date_create"][0];
        }

        if ($_REQUEST["order_filter"]["date_create"][1] <> '') {
            $filter["createdate"][1] = $_REQUEST["order_filter"]["date_create"][1];
        }

        if ($_REQUEST["order_filter"]["date_from"][0] <> '') {
            $filter["begindate"][0] = $_REQUEST["order_filter"]["date_from"][0];
        }

        if ($_REQUEST["order_filter"]["date_from"][1] <> '') {
            $filter["begindate"][1] = $_REQUEST["order_filter"]["date_from"][1];
        }

        if ($_REQUEST["order_filter"]["t_name"] <> '') {
            $filter["turist"] = $_REQUEST["order_filter"]["t_name"];
        }

        if (isset($_REQUEST["order_filter"]["status"]) && (string) $_REQUEST["order_filter"]["status"] !== "unknown" && in_array((string) $_REQUEST["order_filter"]["status"], $this->statuses)) {
            $filter["status"][] = $_REQUEST["order_filter"]["status"];
        }

        if ($_REQUEST["order_filter"]) {
            $this->arResult["IS_SET_FILTER"] = true;
        }

        return $filter;
    }

    /**
     * настройка постарничной навигации для запроса
     * @return array
     */
    protected function __getPage() {

        return array(
            "page" => $_REQUEST["PAGEN_1"] > 0 ? $_REQUEST["PAGEN_1"] : 1,
            // количество элементов на странице
            "size" => 20
        );
    }

    /**
     * сортировка для запроса
     * @return array
     */
    protected function __getSort() {

        $this->arResult["SORT_ALLOW"] = array("begindate", "createdate", "status");

        if (in_array($_REQUEST["order_sort"]["sort"], $this->arResult["SORT_ALLOW"])) {
            $sort[$_REQUEST["order_sort"]["sort"]] = $_REQUEST["order_sort"]["order"] == "asc" ? "asc" : "desc";
        } else {
            $sort["createdate"] = "desc";
        }

        return $sort;
    }

    /** установка списка заказов * */
    protected function __setOrderList() {

        $response = \Bitrix\Main\Web\Json::decode(\travelsoft\booking\Gateway::getPartnersServicesList(array(
                            "url" => \Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "tsmo_url"),
                            "params" => array(
                                "token" => $_SESSION["__TRAVELSOFT"]["TOKEN"],
                                "paging" => $this->__getPage(),
                                "where" => $this->__getFilter(),
                                "sort" => $this->__getSort()
                            )
        )));

        if (!$response["result"]) {

            $this->arResult["ERRORS"][] = "UNKNOWN_ERROR";
        } elseif ($response["result"]) {

            $result = $response["result"];
            $this->arResult["PAGE_COUNT"] = $result["pageCount"];
            $this->arResult["RECORD_COUNT"] = $result["recordCount"];
            $this->arResult["PAGE"] = $result["page"];
            $this->arResult["RECORDS_ON_PAGE"] = $result["recordsOnPage"];

            if ($result["List"]) {

                $this->arResult["LIST"] = $this->__processingList($result["List"]);

                $navResult = new CDBResult();
                $navResult->NavPageCount = $this->arResult["PAGE_COUNT"];
                $navResult->NavPageNomer = $_REQUEST["PAGEN_1"] > 0 ? $_REQUEST["PAGEN_1"] : 1;
                $navResult->NavNum = 1;
                $navResult->NavPageSize = $this->arResult["RECORDS_ON_PAGE"];
                $navResult->NavRecordCount = $this->arResult["RECORD_COUNT"];

                ob_start();
                $GLOBALS["APPLICATION"]->IncludeComponent('bitrix:system.pagenavigation', 'modern', array(
                    'NAV_RESULT' => $navResult,
                ));
                $this->arResult["NAV_STRING"] = ob_get_contents();
                ob_end_clean();
            } else {
                $this->arResult["ERRORS"][] = "NO_ORDERS";
            }
        }
    }

    protected function __processingList($list) {
        /*
          Статусы по оплате
          0    В работе
          1    Не определен
          2    Аннулирован
          3    Wait-лист
          4    Не подтвержден
          5    Wait-лист+Не подтв.
          6    Ожидание
          7    Ok
          11    Web-турагент
          12    Web-гость
          16    Выставлен Счет
          17    Подтверждено
          18    Возврат путевки
          19    Запрос на аннуляцию
          20    Ожидание оплаты
          21    К оплате
          22    К оплате на месте
          23    Оплачено на месте
          24    Аннулирован (штраф)
          25    Аннулирован (удерж)
         */
        /* 
          Статусы заказа
          0    Ok
          1    Заказ на бронирование
          2    Подтверждение
          4    Послать список в Авиакомп
          11    Web-турагент
          13    Confirmed
          14    Not confirmed
          15    Wait
          16    Возврат путевки
          17    Выставлен счет
          18    Аннулирована
          19    К оплате на месте
          20    Оплачено на месте
          21    Запрос на аннуляцию
         */
        foreach ($list as &$row) {
            
            $row_class = "row--white";
            $s = $row["status"]["key"];
            $ps = $row["status_dogovor"]["key"];
            if ( ($s == 0 || $s == 20) && ($ps == 7 || $ps == 23) ) {
                $row_class = "row--green";
            } elseif ( ($s == 0 || $s == 19) && ($ps == 21 || $ps == 22 || $ps == 20) ) {
                $row_class = "row--yellow";
            } elseif ( $s == 18 && ($ps == 2 || $ps == 24 || $ps == 25) ) {
                $row_class = "row--red";
            }
            $row["row_class"] = $row_class;
        }
        return $list;
    }

    /** формирвоание колонок таблицы списка * */
    protected function __setTableColumn() {

        $this->arResult['COLUMNS'][] = 'ORDER_NUMBER';
        $this->arResult["COLUMNS"][] = "PEOPLE_COUNT";
        $this->arResult["COLUMNS"][] = "DATE_CREATE";
        $this->arResult["COLUMNS"][] = "STATUS";
        $this->arResult["COLUMNS"][] = "STATUS_DOGOVOR";
        $this->arResult["COLUMNS"][] = "GROSS";
        $this->arResult["COLUMNS"][] = "COMMISSION";
        $this->arResult["COLUMNS"][] = "TICKET_COST";
        $this->arResult["COLUMNS"][] = "TICKET_CURRENCY";
        $this->arResult["COLUMNS"][] = "TYPE_SERVICE";
        $this->arResult["COLUMNS"][] = "NAME_SERVICE";
        $this->arResult["COLUMNS"][] = "DATE_FROM";
        $this->arResult["COLUMNS"][] = "DETAIL";
    }

    /** подключение модулей * */
    protected function __include_modules() {

        if (!\Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools")) {
            throw new Exception("Не найден модуль \"инструменты бронирования\" ");
        }
    }

    protected function __setStatuses() {
        $this->arResult["STATUSES"] = $this->statuses;
    }

    public function executeComponent() {

        try {

            $this->__include_modules();

            $this->__setOrderList();

            $this->__setTableColumn();

            $this->__setStatuses();

            $this->IncludeComponentTemplate();
        } catch (Exception $e) {
            ShowError($e->getMessage());
        }
    }

}
