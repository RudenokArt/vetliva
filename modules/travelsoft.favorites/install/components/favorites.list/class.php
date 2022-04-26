<?php

/**
 * favorites list
 *
 * @author dimabresky
 */
class FavoritesList extends CBitrixComponent {

    /**
     * @var \Bitrix\Main\HttpRequest
     */
    public $request = null;

    public function prepareParameters() {

        if (!Bitrix\Main\Loader::includeModule("travelsoft.favorites")) {
            throw new Exception("Travelsoft:favorites module not found.");
        }

        $this->request = \Bitrix\Main\Context::getCurrent()->getRequest();
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
     * @param array $fav
     * @return array
     */
    public function extractDetailInfo($fav) {

        $fav["DETAIL_INFO"] = [];
        switch ($fav["UF_OBJECT"]) {

            case "IBLOCK_ELEMENT":
                $fav["DETAIL_INFO"] = \travelsoft\favorites\adapters\Iblock::createTable($fav["UF_STORE_ID"])->getById($fav["UF_ID"]);
                break;
            case "HIGHLOADBLOCK_ELEMENT":
                $fav["DETAIL_INFO"] = \travelsoft\favorites\adapters\Highloadblock::createTable($fav["UF_STORE_ID"])->getById($fav["UF_ID"]);
                break;
        }

        return $fav;
    }

    public function setFavoritesList() {

        global $USER;
        $this->arResult["FAVORITES_LIST"] = [];

        if ($USER->IsAuthorized()) {
            // Избранное для авторизоаванного пользователя
            $this->arResult["FAVORITES_LIST"] = travelsoft\favorites\Utils::getTable()->get([
                "filter" => ['UF_USER_ID' => $USER->GetID()]
                    ], true, function (&$fav) {

                $fav = $this->extractDetailInfo($fav);
            });
        }

        foreach (travelsoft\favorites\Utils::getTable()->get([
            "filter" => ['UF_GUEST_ID' => $this->getGuestId()]
                ], true, function (&$fav) {

            $fav = $this->extractDetailInfo($fav);
        }) as $fav) {
            // Избранное для гостевого пользователя
            $this->arResult["FAVORITES_LIST"][$fav["ID"]] = $fav;
        }
    }

    public function executeComponent() {

        $this->prepareParameters();

        /**
         * Данный метод позволяет получить список элементов избранного c 
         * 
         * детальной информацией по полям по каждому элементу
         * 
         * Обработку данных полей необходимо костомно производить в шаблоне компонента
         * 
         * в зависимости от требований отображения на проекте
         */
        $this->setFavoritesList();

        $this->includeComponentTemplate();
    }

}
