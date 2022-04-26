<?php

/**
 * add object to favorites
 *
 * @author dimabresky
 */
class FavoritesAdd extends CBitrixComponent {

    /**
     * @var \Bitrix\Main\HttpRequest
     */
    public $request = null;

    public function prepareParameters() {

        if (!Bitrix\Main\Loader::includeModule("travelsoft.favorites")) {
            throw new Exception("Travelsoft:favorites module not found.");
        }
        
        $this->arParams["STORE_ID"] = intval($this->arParams["STORE_ID"]);
        if ($this->arParams["STORE_ID"] <= 0) {
            throw new Exception("Travelsoft:favorites store id not found.");
        }
        
        $this->arParams["OBJECT_ID"] = intval($this->arParams["OBJECT_ID"]);
        if ($this->arParams["OBJECT_ID"] <= 0) {
            throw new Exception("Travelsoft:favorites object id not found.");
        }

        if (!\travelsoft\favorites\Utils::checkObjectType($this->arParams["OBJECT_TYPE"])) {
            throw new Exception("Travelsoft:favorites type not found.");
        }

        $this->request = \Bitrix\Main\Context::getCurrent()->getRequest();
    }

    /**
     * @global CUser $USER
     * @return array|null
     */
    public function getFav() {
        global $USER;
        $filter = [
            "UF_STORE_ID" => $this->arParams["STORE_ID"],
            "UF_OBJECT" => $this->arParams["OBJECT_TYPE"],
            "UF_ID" => $this->arParams["OBJECT_ID"],
        ];

        $fav = null;
        if ($USER->IsAuthorized()) {
            // ищем в избранном как у авторизованного пользователя
            $filter["UF_USER_ID"] = $USER->GetID();
            $fav = current(\travelsoft\favorites\Utils::getTable()->get([
                        "filter" => $filter
            ]));
            unset($filter["UF_USER_ID"]);
        }

        if (!$fav) {
            // ищем в избранном по гостевому id пользователя
            $filter["UF_GUEST_ID"] = $this->getGuestId();
            $fav = current(\travelsoft\favorites\Utils::getTable()->get([
                        "filter" => $filter
            ]));
        }

        return $fav;
    }

    /**
     * @return string
     */
    public function getGuestId() {
        static $guest_id = null;

        if (!$guest_id) {
            $guest_id = $_COOKIE["travelsoft_favorites_guest_id"];
            if (!strlen($guest_id)) {
                $guest_id = randString(20);
                setcookie("travelsoft_favorites_guest_id", $guest_id, time() + (30 * 86400), "/");
            }
        }

        return $guest_id;
    }

    /**
     * @global CUser $USER
     * @param string $action
     */
    public function processingRequest() {

        global $USER;

        if (!travelsoft\favorites\Utils::checkHash($this->request->get('HASH'), $this->arParams["OBJECT_TYPE"], $this->arParams["OBJECT_ID"], $this->arParams["STORE_ID"])) {
            travelsoft\favorites\Utils::sendJsonResponse(\json_encode(["error" => true]));
        }

        $action = $this->request->get("ACTION");

        $fav = $this->getFav();

        if (in_array($action, ["ADD", "DELETE"])) {

            if ($action === "ADD") {

                if (empty($fav)) {
                    \travelsoft\favorites\Utils::getTable()->add([
                        "UF_STORE_ID" => $this->arParams["STORE_ID"],
                        "UF_OBJECT" => $this->arParams["OBJECT_TYPE"],
                        "UF_ID" => $this->arParams["OBJECT_ID"],
                        "UF_USER_ID" => $USER->GetID(),
                        "UF_GUEST_ID" => $this->getGuestId(),
                        "UF_DATETIME" => ConvertTimeStamp(time(), "FULL")
                    ]);
                } else {
                    $arr_update = ["UF_GUEST_ID" => $this->getGuestId()];
                    if ($USER->IsAuthorized()) {
                        $arr_update["UF_USER_ID"] = $USER->GetID();
                    }
                    \travelsoft\favorites\Utils::getTable()->update($fav["UF_ID"], $arr_update);
                }
            } elseif ($action === "DELETE") {

                if (!empty($fav)) {

                    \travelsoft\favorites\Utils::getTable()->delete($fav["ID"]);
                }
            }
            travelsoft\favorites\Utils::sendJsonResponse(\json_encode(["error" => false]));
        }

        travelsoft\favorites\Utils::sendJsonResponse(\json_encode(["error" => false]));
    }

    public function executeComponent() {
        global $USER;
        try {
            $this->prepareParameters();

            $this->arResult["HASH"] = travelsoft\favorites\Utils::createHash($this->arParams["OBJECT_TYPE"], $this->arParams["OBJECT_ID"], $this->arParams["STORE_ID"]);

            $fav = $this->getFav();

            if ($fav["ID"] > 0 && !$fav["UF_USER_ID"] && $USER->IsAuthorized()) {
                // привязываем к авторизованному пользователю,
                // если есть в избранном по гостевому ID пользователя
                travelsoft\favorites\Utils::getTable()->update($fav["ID"], ["UF_USER_ID" => $USER->GetID()]);
            }

            $this->arResult["ACTION"] = $this->getFav() ? "DELETE" : "ADD";
            $this->includeComponentTemplate();
        } catch (Exception $ex) {
            ShowError($ex->getMessage());
        }
    }

}
