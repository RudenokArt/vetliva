<?php

use travelsoft\booking\transfers\TransfersBuilder;

class TravelsoftTransferDetail extends CBitrixComponent {

    protected function getStarted() {
        if ($this->arParams["POINT_A"] <= 0) {
            throw new \Exception("Неизвестна точка начала трансфера");
        }
        if ($this->arParams["POINT_B"] <= 0) {
            throw new \Exception("Неизвестна точка конца трансфера");
        }

        \Bitrix\Main\Loader::includeModule("iblock");
        \Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools");
    }

    public function executeComponent() {
        try {
            
            $this->getStarted();
            
            $this->arResult["TRANSFER"] = TransfersBuilder::build($this->arParams["POINT_A"], $this->arParams["POINT_B"]);
            
            $this->includeComponentTemplate();
            
        } catch (\Exception $e) {
            ShowError($e->getMessage());
        }
    }

}
