<?php

/**
 * Список записей highloadblok'a
 */
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

class TravelsoftHighloadListRows extends CBitrixComponent {

    /**
     * Проверка прав пользователя
     * @global CUser $USER
     * @param array $allow_user_groups
     * @param string $user_link_field_name
     * @return boolean
     * @throws Exception
     */
    public function checkUser(Array $allow_user_groups, $user_link_field_name = null) {

        global $USER;

        if ($user_link_field_name == "")
            throw new Exception("Не указано поле для связи с пользователем");

        if ($USER->IsAdmin()) {
            return;
        }

        if (!$USER->IsAuthorized()) {
            throw new Exception("Доступ закрыт. Для дальнейшей работы Вам следует авторизоваться");
        }

        $arUserGroupsID = $USER->GetUserGroupArray();

        $cnt = count($allow_user_groups);

        for ($i = 0; $i < $cnt; $i++) {
            if (in_array($allow_user_groups[$i], $arUserGroupsID)) {
                return true;
            }
        }

        throw new Exception("Для данной группы пользователей доступ для добавления/редактирования закрыт");
    }

    /**
     * Проверка параметров компонента
     * @throws Exception
     */
    protected function checkInputParameters() {

        $this->arParams['BLOCK_ID'] = (int) $this->arParams['BLOCK_ID'];
        if ($this->arParams['BLOCK_ID'] <= 0) {
            throw new Exception("Неизвестный ID hiloadblock");
        }

        $this->checkUser($this->arParams['ALLOW_USER_GROUPS'], $this->arParams['USER_LINK_FIELD_NAME']);

        $this->arParams['CNT_ELS'] = $this->arParams['CNT_ELS'] > 0 ? $this->arParams['CNT_ELS'] : null;

        if (!strlen($this->arParams['NAV_TEMPLATE'])) {
            $this->arParams['NAV_TEMPLATE'] = "modern";
        }

        if ($this->arParams['EDIT_URL'] == "") {
            throw new Exception("Укажите страницу добавления/редактирования");
        }
    }

    /**
     * Получаем строки highloadblock
     * @param integer $hlblock_id
     * @param array $arFilter
     * @throws Exception
     */
    public function getHLRows($hlblock_id, $arFilter = null, $show_pager = true) {

        $hlblock = HL\HighloadBlockTable::getById($hlblock_id)->fetch();

        if (empty($hlblock)) {
            throw new Exception("Highloadblock не найден");
        }

        $entity = HL\HighloadBlockTable::compileEntity($hlblock);

        // uf info
        $fields = $GLOBALS['USER_FIELD_MANAGER']->GetUserFields('HLBLOCK_' . $hlblock['ID'], 0, LANGUAGE_ID);

        $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

        // sort
        $sort_id = $request->get('sort_id') ? $request->get('sort_id') : 'ID';
        $sort_type = strtoupper($request->get('sort_type')) === "ASC" ? "ASC" : "DESC";

        // execute query
        $main_query = new Entity\Query($entity);

        //filter
        if (!empty($arFilter)) {
            $main_query->setFilter($arFilter);
        }

        $main_query->setSelect(array('*'));
        $main_query->setOrder(array($sort_id => $sort_type));

        $result = null;

        $result = new CDBResult($main_query->exec());

        $arResult = array();

        $canEdit = !($this->arParams["CAN'T_EDIT"] == "Y");
        $canDelete = !($this->arParams["CAN'T_DELETE"] == "Y");
        $canCopy = !($this->arParams["CAN'T_COPY"] == "Y");

        if ($this->arParams['CNT_ELS'] && $show_pager) {
            $result->NavStart($this->arParams['CNT_ELS']);
            $arResult["NAV_STRING"] = $result->GetPageNavStringEx($navComponentObject, null, $this->arParams['NAV_TEMPLATE']);
            $arResult["NAV_PARAMS"] = $result->GetNavParams();
            $arResult["NAV_NUM"] = $result->NavNum;
        }

        while ($res = $result->Fetch()) {

            $res['CAN_EDIT'] = $canEdit;
            $res['CAN_DELETE'] = $canDelete;
            $res['CAN_COPY'] = $canCopy;

            $arResult['ITEMS'][$res['ID']] = $res;
        }

        $arResult['ENTITY'] = $entity;

        if (!$arResult['ITEMS']) {
            return $arResult;
        }

        $arResult['UF_FIELDS'] = $fields;

        return $arResult;
    }

    /**
     *  Обработка действия, которое пришло с запросом
     * @param Bitrix\Main\Entity\Base $entity
     * @param array $arItems
     * @return string
     */
    public function doAction(Bitrix\Main\Entity\Base $entity, &$arItems) {
        $resMessage = "";
        if (check_bitrix_sessid()) {
            $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
            $rowID = $request->get('row_id');
            $data_class = $entity->getDataClass();
            if ($request->get('delete') == "Y" && $rowID > 0 && $arItems[$rowID]['CAN_DELETE']) {
                $result = $data_class::delete($rowID);
                if ($result->isSuccess()) {
                    unset($arItems[$rowID]);
                    $resMessage = "";
                } else {
                    $resMessage = $result->getErrorMessages();
                }
            } elseif ($request->isPost() && !empty($request->getPost("rows_for_delete")) && is_array($request->getPost("rows_for_delete"))) {

                foreach ($request->getPost("rows_for_delete") as $row_id) {
                    if ($arItems[$row_id]['CAN_DELETE']) {
                        $result = $data_class::delete($row_id);
                        if ($result->isSuccess()) {
                            unset($arItems[$row_id]);
                            $resMessage = "";
                        } else {
                            $resMessage = $result->getErrorMessages();
                        }
                    }
                }
                LocalRedirect($GLOBALS["APPLICATION"]->GetCurPageParam("", array(), false));
            }
        }

        return $resMessage;
    }

    /** фильтр по пользователю * */
    private function __filterByUser() {
        if ($this->arParams['FILTER_SERVICE_ID'] > 0){
            return array($this->arParams["USER_LINK_FIELD_NAME"] => $this->arParams['FILTER_SERVICE_ID']);
        }

        return !$GLOBALS['USER']->IsAdmin() ? array($this->arParams["USER_LINK_FIELD_NAME"] => $GLOBALS['USER']->GetID()) : array();
    }

    /**
     * фильтр по фрме запроса
     * @return array | null
     */
    private function __filterByQuery() {

        $filter = array();

        if ($this->arResult["FILTER"]["_GET"]["SERVICES"]) {
            $filter = array("UF_SERVICES_ID" => $this->arResult["FILTER"]["_GET"]["SERVICES"]);
        } elseif ($this->arResult["FILTER"]["_GET"]["OBJECTS"]) {
            $filter = array("UF_IBLOCK_ELEMENT_ID" => $this->arResult["FILTER"]["_GET"]["OBJECTS"]);
        }
        elseif (is_array($this->arParams['ADDITIOANL_FILTR_TYPE'])) {
            $true_services_id = [];
            foreach ($this->arResult["FILTER"]["SERVICES"] as $tmp) $true_services_id = array_merge($true_services_id, array_keys($tmp));
            $filter = array("UF_SERVICES_ID" => $true_services_id);
        }

        return $filter;
    }

    /**
     * фильтр по типу услуги
     * @return array | null
     */
    private function __filterByServiceType() {
        return $this->arParams['SERVICE_TYPE_ID'] > 0 ? array("UF_SERVICE_TYPE_NAME" => $this->arParams['SERVICE_TYPE_ID']) : array();
    }
    
    private function __filterByServiceTypes() {
        if (is_array($this->arParams['ADDITIOANL_FILTR_TYPE'])) {
           return  array("UF_SERVICE_TYPE_NAME" => $this->arParams['ADDITIOANL_FILTR_TYPE']);
        } 
        else {
            return array();
        }        
    }

    /**
     * занчения фильтра для html 
     */
    private function __setHtmlFilterVals() {

        if ($this->arParams["SHOW_FILTER_BY_SERVICES"] == "Y" || $this->arParams["SHOW_FILTER_BY_OBJECTS"] == "Y") {

            $result = $this->getHLRows(\Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "service_hl_id"), array_merge($this->__filterByUser(), $this->__filterByServiceType(),$this->__filterByServiceTypes()), false);

            if ($result["ITEMS"]) {

                foreach ($result["ITEMS"] as $id => $res) {

                    $arServices[$res["UF_IBLOCK_ELEMENT_ID"]][$id] = $res["UF_NAME"];
                }

                \Bitrix\Main\Loader::includeModule("iblock");

                $db_res = CIBlockElement::GetList(false, array("ID" => array_keys($arServices)), false, false, array("ID", "NAME"));

                while ($res = $db_res->Fetch()) {
                    $arElements[$res["ID"]] = $res["NAME"];
                }
            }

            if ($this->arParams["SHOW_FILTER_BY_SERVICES"] == "Y" && $arServices) {

                if ($_REQUEST["SERVICES"]) {
                    $this->arResult["FILTER"]["_GET"]["SERVICES"] = array_filter($_REQUEST["SERVICES"], function ($val) {
                        return $val > 0;
                    });
                }

                $this->arResult["FILTER"]["SERVICES"] = $arServices;
                $this->arResult["IBLOCK_ELEMENTS"] = $arElements;
            } elseif ($this->arParams["SHOW_FILTER_BY_OBJECTS"] == "Y") {

                if ($_REQUEST["OBJECTS"]) {
                    $this->arResult["FILTER"]["_GET"]["OBJECTS"] = array_filter($_REQUEST["OBJECTS"], function ($val) {
                        return $val > 0;
                    });
                }

                $this->arResult["FILTER"]["OBJECTS"] = $arElements;
            }
        }

        if ($this->arResult["FILTER"]["_GET"]) {
            $this->arResult["IS_SET_FILTER"] = true;
        }
    }

    /**
     * Component body
     */
    public function executeComponent() {

        try {

            $this->checkInputParameters();

            Bitrix\Main\Loader::includeModule("highloadblock");

            $this->__setHtmlFilterVals();

            $this->arResult["DB"] = $this->getHLRows(
                    $this->arParams['BLOCK_ID'], array_merge(
                            $this->__filterByUser(), $this->__filterByServiceType(), $this->__filterByQuery()
                    )
            );


            $this->arResult['ERRORS'] = $this->doAction($this->arResult["DB"]['ENTITY'], $this->arResult["DB"]['ITEMS']);

            if ($_SESSION['MESSOK']) {
                $this->arResult['MESSOK'] = $_SESSION['MESSOK'];
                unset($_SESSION['MESSOK']);
            }

            $this->IncludeComponentTemplate();
        } catch (Exception $ex) {
            ShowError($ex->getMessage());
        }
    }

}
