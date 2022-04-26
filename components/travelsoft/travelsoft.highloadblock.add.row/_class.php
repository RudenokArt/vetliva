<?php

/**
 * Добавление/редактирование записи сущности highloadblok'a
 */
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

class TravelsoftHighloadRowAddEdit extends CBitrixComponent {

    /**
     * Проверка прав пользователя
     * @global CUser $USER
     * @param string $user_link_field_name
     * @param array $allow_user_groups
     * @return boolean
     * @throws Exception
     */
    public function checkUser($user_link_field_name = "", $allow_user_groups) {

        global $USER;

        $allow_user_groups = (array) $allow_user_groups;

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
            if (in_array($allow_user_groups[$i], $arUserGroupsID))
                return true;
        }

        throw new Exception("Для данной группы пользователей доступ для добавления/редактирования закрыт");
    }

    /**
     * Получаем объект сущности hiloadblock'a
     * @param integer $hlblock_id
     * @return Bitrix\Main\Entiry
     * @throws Exception
     */
    public function getEntity($hlblock_id = -1) {

        if (empty($hlblock_id)) {
            throw new Exception("Ошибка в системе. Обратитесь к администрации сайта");
        }

        $hlblock = HL\HighloadBlockTable::getById($hlblock_id)->fetch();

        if (empty($hlblock)) {
            throw new Exception("Ошибка в системе. Обратитесь к администрации сайта");
        }

        return HL\HighloadBlockTable::compileEntity($hlblock);
    }

    /**
     * Получаем инфу по строке
     * @return array
     */
    public function getEntityRowData($entity, $row_id) {

        global $USER_FIELD_MANAGER;

        // row data
        $main_query = new Entity\Query($entity);
        $main_query->setSelect(array('*'));
        $main_query->setFilter(array('=ID' => (int) $row_id));

        $result = new CDBResult($main_query->exec());
        $row = $result->Fetch();

        $fields = $USER_FIELD_MANAGER->getUserFieldsWithReadyData('HLBLOCK_' . $entity->hlblock_id, $row, LANGUAGE_ID);

        foreach ($fields as &$arField) {
            if ($arField['USER_TYPE_ID'] == "enumeration") {
                $dbRes = CUserFieldEnum::GetList(array(), array(
                            "USER_FIELD_ID" => $arField['ID'],
                ));
                while ($res = $dbRes->Fetch()) {
                    $arField['VALUES'][$res['ID']] = $res;
                }
            } elseif ($arField['USER_TYPE_ID'] == "file") {
                if (is_array($arField['VALUE'])) {
                    foreach ($arField['VALUE'] as $key => $id) {
                        $arField['VALUE_DETAIL_INFO'][$key] = CFile::GetFileArray($id);
                        $arField['VALUE_DETAIL_INFO'][$key]["IS_IMAGE"] = CFile::IsImage($arField['VALUE_DETAIL_INFO'][$key]["FILE_NAME"], $arField['VALUE_DETAIL_INFO'][$key]["CONTENT_TYPE"]);
                    }
                } elseif ($arField['VALUE'] > 0) {
                    $arField['VALUE_DETAIL_INFO'] = CFile::GetFileArray($arField['VALUE']);
                    $arField['VALUE_DETAIL_INFO']["IS_IMAGE"] = CFile::IsImage($arField['VALUE_DETAIL_INFO']["FILE_NAME"], $arField['VALUE_DETAIL_INFO']["CONTENT_TYPE"]);
                }
            }
        }

        return array("fields" => $fields, "row" => $row);
    }

    /**
     * Обработка запроса формы
     * @param Bitrix\Main\Entity $entity
     * @param array $row_data
     * @param string $form_id
     * @param string $redirect_url
     * @param string $messok
     * @param string $user_field_name
     * @param boolean $multiple_user_field
     * @return array
     */
    public function proccessRequest($entity, $row_data, $form_id, $redirect_url = "", $messok = "", $user_field_name = "") {

        global $USER_FIELD_MANAGER;

        $request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();

        if ($request->isPost() && (strlen($request->getPost('save')) > 0 || strlen($request->getPost('apply')) > 0) &&
                check_bitrix_sessid() && $request->getPost('form_id') == $form_id) {

            $data = array();

            $USER_FIELD_MANAGER->EditFormAddFields('HLBLOCK_' . $entity->hlblock_id, $data);

            $ID = $row_data['row']['ID'];

            $entity_data_class = $entity->getDataClass();

            if ($ID > 0) {
                //EDIT
                $res = $entity_data_class::update($ID, $data);
            } else {
                //ADD
                $data[$user_field_name] = $row_data['fields']['UF_USER_ID']['MULTIPLE'] == "N" ? $GLOBALS['USER']->GetID() : (array) $GLOBALS['USER']->GetID();
                $res = $entity_data_class::add($data);
                $ID = $res->getId();

                // ТАРИФ + ТИП ЦЕНЫ СОХРАНЕНИЕ В БАЗУ
                // привязываем к одному типу цены
                $ptr_data_class = HL\HighloadBlockTable::compileEntity(HL\HighloadBlockTable::getById(\Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "ptr_hl_id"))
                                        ->fetch())->getDataClass();
                $ptr_data_class::add(array(
                    "UF_RATE_CATEGORY_ID" => $ID,
                    "UF_RATE_ID" => 4 // тип цены "ЦЕНА"
                ));
            }

            if ($res->isSuccess()) {

//                                // ТАРИФ + ТИП ЦЕНЫ СОХРАНЕНИЕ В БАЗУ
//                                $_postPriceTypes = (array)$request->getPost("PRICE_TYPES");
//                                if (!empty($_postPriceTypes) && $this->arParams['SERVICE_TYPES_PLUSE_RATES_HLBLOCK_ID'] > 0) {
//                                    
//                                    // удаляем старые привязки типа цены и тарифа
//                                    $datta_class = HL\HighloadBlockTable::compileEntity(HL\HighloadBlockTable::getById($this->arParams['SERVICE_TYPES_PLUSE_RATES_HLBLOCK_ID'])->fetch())->getDataClass();
//                                    
//                                    $ress = $datta_class::getList(array(
//                                            'filter' => array(
//                                                    "UF_RATE_CATEGORY_ID" => $ID
//                                            )
//                                    ));
//                                    
//                                    while ($resss = $ress->fetch()) {
//                                         $key = array_search($resss['UF_RATE_ID'], $_postPriceTypes);
//                                         if ($key !== false) {
//                                             unset($_postPriceTypes[$key]);
//                                         } else {
//                                            $datta_class::delete($resss['ID']);
//                                         }
//                                    }
//                                    
//                                    // создаём новые привязки
//                                    foreach ($_postPriceTypes as $rpt) {
//                                             if (!$rpt) continue;
//                                             $datta_class::add(array(
//                                                    "UF_RATE_CATEGORY_ID" => $ID,
//                                                    "UF_RATE_ID" => $rpt
//                                                ));
//                                    }
//                                   
//                                }

                $_SESSION['MESSOK'] = $messok;
                if (strlen($request->getPost('save')) > 0) {
                    LocalRedirect($redirect_url);
                } else {
                    LocalRedirect("?edit=Y&row_id=" . $ID);
                }
            } else {
                return $res->getErrorMessages();
            }
        }

        return array();
    }

    public function getFieldsForShow($uf_fields = array(), $show_uf_fields = array()) {

        $fields = array();
        if (!empty($uf_fields)) {
            if (is_array($show_uf_fields) && !empty($show_uf_fields)) {
                foreach ($uf_fields as $arField) {
                    if (in_array($arField['ID'], $show_uf_fields)) {
                        $fields[] = $arField['FIELD_NAME'];
                    }
                }
            } else {
                $fields = array_keys($uf_fields);
            }
        }

        return $fields;
    }

    /**
     * Component body
     */
    public function executeComponent() {

        try {

            Bitrix\Main\Loader::includeModule("highloadblock");

            $this->checkUser($this->arParams['USER_LINK_FIELD_NAME'], $this->arParams['ALLOW_USER_GROUPS']);

            $hlblock_id = (int) $this->arParams['BLOCK_ID'];

            $entity = $this->getEntity($hlblock_id);

            $entity->hlblock_id = $hlblock_id;

            $this->arResult['ROW_DATA'] = $this->getEntityRowData($entity, (int) $this->arParams['ROW_ID']);
            
            // поля для показа в форме
            $this->arResult['FIELDS_FOR_SHOW'] = $this->getFieldsForShow($this->arResult['ROW_DATA']['fields'], $this->arParams['SHOW_UF_FIELDS']);

            // CAN EDIT
            if (!empty($this->arResult['ROW_DATA']['row'])) {

                if (!(in_array($GLOBALS['USER']->GetID(), (array) $this->arResult['ROW_DATA']['row'][$this->arParams['USER_LINK_FIELD_NAME']]) || $GLOBALS['USER']->IsAdmin())) {
                    throw new Exception("Доступ закрыт");
                }
                
            } else {
                
                // CAN COPY
                $this->arResult['ROW_DATA'] = $this->getEntityRowData($entity, (int) $this->arParams['COPY_ID']);
                
                if ($this->arResult['ROW_DATA']['row']["ID"]) {
                    
                    if (in_array($GLOBALS['USER']->GetID(), (array) $this->arResult['ROW_DATA']['row'][$this->arParams['USER_LINK_FIELD_NAME']]) || $GLOBALS['USER']->IsAdmin()) {
                        // УДАЛЕНИЕ ID ДЛЯ СОЗДАНИЯ НОВОГО ЭЛЕМЕНТА
                        unset($this->arResult['ROW_DATA']['row']["ID"]);
                    } else {
                        throw new Exception("Вы не можете копировать объект");
                    }
                    
                }
                
            }

            $this->arResult['FORM_ID'] = md5(serialize($this->arParams));

            $this->arResult['ERRORS'] = $this->proccessRequest(
                    $entity, $this->arResult['ROW_DATA'], $this->arResult['FORM_ID'], $this->arParams['LIST_URL'], $this->arParams['MESSOK'], $this->arParams['USER_LINK_FIELD_NAME']
            );

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
