<?php

/* 
 * Компонент списка заказав бронирования
 */

class TravelsoftOrderList extends CBitrixComponent {
    
    protected $statuses = array (
        "unknown", // любой
        "0", // в работе
        "1", // не определен
        "2", // Аннулирован
        "3", // Wait-list,
        "4", // Не подтвержден
        "5", // Wait-лист+Не подтв.
        "6", // Ожидание
        "7", // Ok
        "11", // Web-турагент
        "12", // Web-гость
        "16", // Выставлен Счет
        "17", // Подтверждено
        "18", // Возврат путевки
        "19", // Запрос на аннуляцию
        "20", // Ожидание оплаты
        "21", // К оплате
    );
                        
    
    /** 
     * фильтр для запроса
     * @return array
     */
    protected function __getFilter () {
        
        $filter = array(
            "createdate" => array(),
            "tourdate" => array(),
            "turist" => ""
        );
        
        if ($_REQUEST["order_filter"]["date_create"][0] <> '') {
            $filter["createdate"][0] = $_REQUEST["order_filter"]["date_create"][0];
        } else {
            $filter["createdate"][0] = "";
        }
        
        if ($_REQUEST["order_filter"]["date_create"][1] <> '') {
            $filter["createdate"][1] = $_REQUEST["order_filter"]["date_create"][1];
        } else {
            $filter["createdate"][1] = "";
        }
        
        if ($_REQUEST["order_filter"]["date_from"][0] <> '') {
            $filter["tourdate"][0] = $_REQUEST["order_filter"]["date_from"][0];
        } else {
            $filter["tourdate"][0] = "";
        }
        
        if ($_REQUEST["order_filter"]["date_from"][1] <> '') {
            $filter["tourdate"][1] = $_REQUEST["order_filter"]["date_from"][1];
        } else {
            $filter["tourdate"][1] = "";
        }
        
        if ($_REQUEST["order_filter"]["t_name"] <> '') {
            $filter["turist"] = $_REQUEST["order_filter"]["t_name"];
        }
        
        if (isset($_REQUEST["order_filter"]["status"]) && (string)$_REQUEST["order_filter"]["status"]!== "unknown"
                && in_array((string)$_REQUEST["order_filter"]["status"], $this->statuses)) {
            $filter["statuses"][] = $_REQUEST["order_filter"]["status"];
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
    protected function __getPage () {
        
        return array(
            "page" => $_REQUEST["PAGEN_1"] > 0 ? $_REQUEST["PAGEN_1"] : 1,
            // количество элементов на странице
            "size" => 10
        );
        
    }
    
    /** 
     * сортировка для запроса
     * @return array
     */
    protected function __getSort () {
        
        $this->arResult["SORT_ALLOW"] = array("tourdate", "createdate", "turist", "status");
        
        if (in_array($_REQUEST["order_sort"]["sort"], $this->arResult["SORT_ALLOW"])) {
            $sort[$_REQUEST["order_sort"]["sort"]] = $_REQUEST["order_sort"]["order"] == "asc" ? "asc" : "desc";
        }
        
        else {
            $sort["tourdate"] = "desc";
        }
        
        return $sort;
        
    }
    
    /** установка списка заказов **/
    protected function __setOrderList () {
        
        $response = json_decode(\travelsoft\booking\Gateway::getPmOrderList(array(
            "url" => \Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "tsmo_url"),
            "params" => array(
                "token" => $_SESSION["__TRAVELSOFT"]["TOKEN"],
                "paging" => $this->__getPage(),
                "filter" =>$this->__getFilter(),
                "sort" => $this->__getSort()
            )
        )), true);
        
        if (!$response["result"]) {
            
            $this->arResult["ERRORS"][] = "UNKNOWN_ERROR";
        
        } elseif ($response["result"]) {
            
            $result = $response["result"];
            
            $this->arResult["PAGE_COUNT"] = $result["pageCount"];
            $this->arResult["RECORD_COUNT"] = $result["recordCount"];
            $this->arResult["PAGE"] = $result["page"];
            $this->arResult["RECORDS_ON_PAGE"] = $result["recordsOnPage"];

            if ($result["List"]) {
                
                $this->arResult["LIST"] = $result["List"];
                
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
    
    /** формирвоание колонок таблицы списка **/
    protected function __setTableColumn () {
   
        $this->arResult['COLUMNS'][] = 'ORDER_NUMBER';
        $this->arResult["COLUMNS"][] = "USER_NAME";
        $this->arResult["COLUMNS"][] = "PEOPLE_COUNT";
        $this->arResult["COLUMNS"][] = "DATE_FROM";
        $this->arResult["COLUMNS"][] = "STATUS";
        $this->arResult["COLUMNS"][] = "TICKET_COST";
        $this->arResult["COLUMNS"][] = "TICKET_CURRENCY";
        
        $this->arResult["IS_AGENT"] = in_array(\Bitrix\Main\Config\Option::get("travalsoft.booking.dev.tools", "agents_group_id"), $GLOBALS["USER"]->GetUserGroupArray());
        
        if ($this->arResult["IS_AGENT"]) {
        
            $this->arResult["COLUMNS"][] = "DISCOUNT";
            
        }
            
        $this->arResult["COLUMNS"][] = "TO_PAY";
        $this->arResult["COLUMNS"][] = "PAID";
        $this->arResult["COLUMNS"][] = "DATE_CREATE";
        $this->arResult["COLUMNS"][] = "DETAIL";
        
    }
    
    /** подключение модулей **/
    protected function __include_modules() {
        
        if (!\Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools")) {
            throw new Exception("Не найден модуль \"инструменты бронирования\" ");
        }
        
    } 
    
    protected function __setStatuses () {
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

