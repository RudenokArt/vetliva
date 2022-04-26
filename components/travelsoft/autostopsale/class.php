<?php

/**
 * @author dimabresky
 */
class AutostopsaleComponent extends CBitrixComponent {

    protected function _setServicesData() {
        
        $services_data_store = new travelsoft\booking\datastores\ServicesDataStore(array(
                            "filter" => array("UF_USER_ID" => $GLOBALS["USER"]->GetID()),
                            "select" => array("ID", "UF_NAME")
                        ));
        
        $this->arResult["SERVICES"] = $services_data_store->fetch(array("ID"));
    }

    protected function _setAutostopsaleData() {

        $this->arResult["AUTOSTOPSALE"] = array();
        foreach (\travelsoft\booking\datastores\Autostopsale::get(
                array(
                    "filter" => array("UF_USER_ID" => $GLOBALS["USER"]->GetID()),
        ))
        as $arr_autostopsale) {
            $this->arResult["AUTOSTOPSALE"][$arr_autostopsale["UF_SERVICE_ID"]] = $arr_autostopsale;
        }
    }

    protected function _save($req) {

        if ($req["service_id"] > 0 && $req["hours"] > 0) {

            $arr_save = array(
                "UF_USER_ID" => $GLOBALS["USER"]->GetID(),
                "UF_SERVICE_ID" => $req["service_id"],
                "UF_HOURS" => intval($req["hours"]),
                "UF_ACTIVE" => $req["active"] > 0 ? 1 : 0
            );

            $id = $this->arResult["AUTOSTOPSALE"][$req["service_id"]]["ID"] ?: NULL;

            return travelsoft\booking\datastores\Autostopsale::save($arr_save, $id);
        }

        return false;
    }

    protected function _processingRequest() {

        $this->arResult["ERRORS"] = array();
        $req = $_REQUEST["autostopsale"];

        if (!check_bitrix_sessid) {
            $this->arResult["ERRORS"][] = "Запрос не может быть обработан. Ваша сессия истекла или Вы незаконно проникли на страницу.";
            return;
        }

        if ($_REQUEST["delete"] > 0) {
            if (current(travelsoft\booking\datastores\Autostopsale::get(array(
                                "filter" => array("ID" => $_REQUEST["delete"], "UF_USER_ID" => $GLOBALS["USER"]->GetID()),
                                "select" => array("ID")
                    )))["ID"] > 0) {
                travelsoft\booking\datastores\Autostopsale::delete($_REQUEST["delete"]);
                $this->_redirect();
            }
        }

        if (is_array($req) && !empty($req)) {


            if ($req["massedit"]) {

                $data = array();
                $results_pool = array();
                foreach ($req["massedit"]["services_id"] as $service_id) {

                    $data["service_id"] = $service_id;
                    $data["hours"] = $req["massedit"]["hours"];
                    $data["active"] = $req["massedit"]["active"] > 0 ? 1 : 0;

                    $results_pool[] = $this->_save($data) > 0 ? 1 : 0;
                }

                $this->_sendResponse(in_array(1, $results_pool));
            } else {
                 $this->_sendResponse($this->_save($req));
            }
        }
    }

    protected function _sendResponse($ok) {
        if ($ok) {
            $this->_redirect();
        } else {
            $this->arResult["ERRORS"][] = "Ошибка сохранения данных. Обратитесь в техническую поддержку";
        }
    }

    protected function _redirect() {
        LocalRedirect($GLOBALS["APPLICATION"]->GetCurPageParam("", array("delete", "sessid"), false));
    }

    public function executeComponent() {

        if (Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools")) {

            $this->_setServicesData();

            $this->_setAutostopsaleData();

            $this->_processingRequest();

            $this->IncludeComponentTemplate();
        } else {
            ShowError("Модуль travelsoft.booking.dev.tools не найден");
        }
    }

}
