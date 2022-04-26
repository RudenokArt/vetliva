<?php

use Bitrix\Main\Config\Option;

/**
 * Компонент добавления трансферов
 *
 * @author dima
 */
class TravelsoftAddTransfer extends CBitrixComponent {

    protected $_moduleId = "travelsoft.booking.dev.tools";
    
    protected $_tdc = null;
    
    protected $_tarray = null;


    /**
     * @throws Exception
     */
    protected function gettingStarted() {

        if (!\Bitrix\Main\Loader::includeModule("iblock")) {
            throw new Exception("Модуль iblock не найден");
        }

        if (!\Bitrix\Main\Loader::includeModule("highloadblock")) {
            throw new Exception("Модуль highloadblock не найден");
        }

        if (!in_array(Option::get($this->_moduleId, "service_provider_group_id"), $GLOBALS["USER"]->GetUserGroupArray())) {
            throw new Exception("Страница доступна только для поставщиков услуг");
        }

        if (!is_array($this->arParams["IBLOCK_ID"]) || empty($this->arParams["IBLOCK_ID"])) {
            throw new Exception("Выберите инфоблоки точек трансферов");
        }

        if ($this->arParams["SERVICE_TYPE"] <= 0) {
            throw new Exception("Укажите тип услуги");
        }
    }

    public function executeComponent() {
        
        try {
        
            $this->gettingStarted();

            $this->arResult["POINTS"] = null;

            $IB = $this->arParams["IBLOCK_ID"];

            for ($i = 0, $cnt = count($IB); $i < $cnt; $i++) {
                $dbList = CIBlockElement::GetList(false, array("IBLOCK_ID" => $IB[$i], "ACTIVE" => "Y"), false, false, array("ID", "NAME", "PROPERTY_NAME_EN", "PROPERTY_NAME_BY"));
                while ($arRes = $dbList->fetch()) {
                    $this->arResult["POINTS"][$arRes["ID"]]["NAME"] = $arRes["NAME"];
                    $this->arResult["POINTS"][$arRes["ID"]]["NAME_EN"] = $arRes["PROPERTY_NAME_EN_VALUE"];
                    $this->arResult["POINTS"][$arRes["ID"]]["NAME_BY"] = $arRes["PROPERTY_NAME_BY_VALUE"];
                }
            }

            $this->processingRequest();

            $this->includeComponentTemplate();
            
        } catch (Exception $e) {
            ShowError($e->getMessage());
        }
    }
    
    /**
     * @param int $a
     * @param int $b
     * @return null
     */
    protected function processingTransfer (int $a, int $b = null) {
        
        $filter = array("UF_POINT_A" => $a, "UF_POINT_B" => null);
        $add = array("UF_POINT_A" => $a);
        $save = array("a" => $a);
        if ($b) {
            $add["UF_POINT_B"] = $save["b"] = $b;
            $filter = array(
                "LOGIC" => "OR",
                array("UF_POINT_A" => $a, "UF_POINT_B" => $b),
                array("UF_POINT_A" => $b, "UF_POINT_B" => $a)
            );
        }
        $dbPoints = $this->_tdc::getList(array(
                                "filter" => $filter,
                                "select" => array("ID")
                            ))->fetchAll();

                    if (!$dbPoints[0]["ID"]) {
                        // create transfer
                        $transferId = $this->_tdc::add($add)->getId();
                        if (!$transferId) {
                            return;
                        }
                    } else {
                        $transferId = $dbPoints[0]["ID"];
                    }

                    $save["transferId"] = $transferId;
                    $this->_tarray[] = $save;
        
    }
    
    protected function processingRequest() {

        if (check_bitrix_sessid() && strlen($this->arParams["_POST"]["save"]) > 0) {

            $transfers = $this->arParams["_POST"]["transfers"];

            $this->_tdc = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity(
                            \Bitrix\Highloadblock\HighloadBlockTable::getById(Option::get($this->_moduleId, "transfer_hl_id"))->fetch())->getDataClass();

            $sdc = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity(
                            \Bitrix\Highloadblock\HighloadBlockTable::getById(Option::get($this->_moduleId, "service_hl_id"))->fetch())->getDataClass();

            for ($i = 0, $cnt = count($transfers["fix"]); $i < $cnt; $i++) {
                $transferId = null;
                if (isset($this->arResult["POINTS"][$transfers["fix"][$i]["point_a"]]) &&
                        isset($this->arResult["POINTS"][$transfers["fix"][$i]["point_b"]]) &&
                        $transfers["fix"][$i]["point_a"] != $transfers["fix"][$i]["point_b"]) {
                    
                    $this->processingTransfer((int)$transfers["fix"][$i]["point_a"], (int)$transfers["fix"][$i]["point_b"]);
                    
                }
            }

            for ($i = 0, $cnt = count($transfers["nofix"]); $i < $cnt; $i++) {
                $transferId = null;
                if (isset($this->arResult["POINTS"][$transfers["nofix"][$i]])) {

                    $this->processingTransfer((int)$transfers["nofix"][$i]);
                    
                }
            }

            for ($i = 0, $cnt = count($this->_tarray); $i < $cnt; $i++) {

                $dbService = $sdc::getList(array(
                            "filter" => array(
                                "UF_IBLOCK_ELEMENT_ID" => $this->_tarray[$i]["transferId"],
                                "UF_USER_ID" => $GLOBALS["USER"]->GetID(),
                                "UF_SERVICE_TYPE_NAME" => $this->arParams["SERVICE_TYPE"]
                            ),
                            "select" => array("ID")
                        ))->fetchAll();

                if ($dbService[0]["ID"] > 0) {
                    continue;
                }

                $NAME = $this->arResult["POINTS"][$this->_tarray[$i]["a"]]["NAME"];
                $NAME_BY = $this->arResult["POINTS"][$this->_tarray[$i]["a"]]["NAME_BY"];
                $NAME_EN = $this->arResult["POINTS"][$this->_tarray[$i]["a"]]["NAME_EN"];

                if ($this->_tarray[$i]["b"]) {
                    $NAME = $NAME . " - " . $this->arResult["POINTS"][$this->_tarray[$i]["b"]]["NAME"] . "(".$this->arResult["POINTS"][$this->_tarray[$i]["b"]]["NAME"]. " - " . $NAME .")";
                    $NAME_BY = $NAME_BY . " - " . $this->arResult["POINTS"][$this->_tarray[$i]["b"]]["NAME_BY"] . "(".$this->arResult["POINTS"][$this->_tarray[$i]["b"]]["NAME_BY"]. " - " . $NAME_BY .")";
                    $NAME_EN = $NAME_EN . " - " . $this->arResult["POINTS"][$this->_tarray[$i]["b"]]["NAME_EN"] . "(".$this->arResult["POINTS"][$this->_tarray[$i]["b"]]["NAME_EN"]. " - " . $NAME_EN .")";
                }
                // save like service
                $sdc::add(
                        array(
                            "UF_NAME" => $NAME,
                            "UF_NAME_BY" => $NAME_BY,
                            "UF_NAME_EN" => $NAME_EN,
                            "UF_IBLOCK_ELEMENT_ID" => $this->_tarray[$i]["transferId"],
                            "UF_USER_ID" => $GLOBALS["USER"]->GetID(),
                            "UF_SERVICE_TYPE_NAME" => $this->arParams["SERVICE_TYPE"]
                        )
                );
                $someAdded = true;
            }

            if ($someAdded) {
                $_SESSION['MESSOK'] = "Данные успешно сохранены";
            }
            LocalRedirect($this->arParams["LIST_PAGE"]);
        }
    }

}
