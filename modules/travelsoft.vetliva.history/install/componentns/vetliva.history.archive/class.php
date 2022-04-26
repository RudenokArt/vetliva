<?php

/**
 * Класс TravelsoftVetlivaHistoryArchive архива истории
 * @author dimabresky
 * @copyright (c) 2019, travelsoft
 */
class TravelsoftVetlivaHistoryArchive extends CBitrixComponent {

    public function executeComponent() {

        global $USER;

        try {

            if (!Bitrix\Main\Loader::includeModule("travelsoft.vetliva.history")) {
                throw new Exception("vetliva history archive: Модуль travelsoft.vetliva.history не найден");
            }

            if (!strlen($this->arParams["OBJECT"])) {
                throw new Exception("vetliva history archive: укажите объект архива");
            }

            $this->arResult["SERVICES"] = [];

            $this->arResult["LOAD_RESULT"] = isset($_REQUEST["HISTORY_ARCHIVE"]["SUBMIT"]) && strlen($_REQUEST["HISTORY_ARCHIVE"]["SUBMIT"]);

            if (Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools")) {

                $services = new \travelsoft\booking\datastores\ServicesDataStore([
                    "filter" => [
                        "UF_USER_ID" => $USER->GetID()
                    ],
                    "select" => [
                        "ID", "UF_IBLOCK_ELEMENT_ID", "UF_NAME"
                    ]
                ]);

                if (in_array(\travelsoft\booking\Utils::getOpt("excursions_provider_group"), $USER->GetUserGroupArray())) {

                    foreach ($services->fetch() as $service) {

                        $this->arResult["SERVICES"][$service["ID"]] = $service["UF_NAME"];
                    }
                } else {

                    $arr_services_grouped_by_iblock_elements = $services->fetch(["UF_IBLOCK_ELEMENT_ID"]);
                    
                    if (!empty($arr_services_grouped_by_iblock_elements)) {


                        $arr_iblock_elements_id = \array_values(\array_keys($arr_services_grouped_by_iblock_elements));

                        $dbList = CIBlockElement::GetList(false, ["ID" => $arr_iblock_elements_id, "ACTIVE" => "Y"], false, false, [
                                    "ID", "NAME"
                        ]);

                        $this->arResult["IBLOCK_ELEMENTS"] = [];
                        while ($arElement = $dbList->Fetch()) {

                            $arr_services = $arr_services_grouped_by_iblock_elements[$arElement["ID"]];
                            
                            foreach ($arr_services as $service) {
                               
                                $this->arResult["IBLOCK_ELEMENTS"][$arElement["ID"]] = $arElement["NAME"];
                                $this->arResult["SERVICES"][$arElement["ID"]][$service["ID"]] = $service["UF_NAME"];
                            }
                        }
                    }
                }
            }

            $this->includeComponentTemplate();
        } catch (\Exception $e) {

            ShowError($e->getMessage());
        }
    }

}
