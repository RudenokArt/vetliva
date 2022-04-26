<?php

/**
 * Компонент управления поставщиками услуг
 *
 * @package vetliva
 * @author dimabresky
 */
class PartnersManager extends CBitrixComponent {

    public function prepareParameters() {

        \Bitrix\Main\Loader::includeModule('travelsoft.booking.dev.tools');

        CJSCore::Init();

        $this->arParams["IS_AJAX"] = $this->request->isAjaxRequest() && check_bitrix_sessid();
//        unset($_SESSION['partners.manager:actions-data-store']);
        if (!isset($_SESSION['partners.manager:actions-data-store']) || !is_array($_SESSION['partners.manager:actions-data-store'])) {
            $_SESSION['partners.manager:actions-data-store'] = [
                'providers-selection' => [
                    'provider-id' => 0,
                    'title' => 'Выбор поставщика услуг',
                    'js-render-method' => 'providersSelectionRender',
                    'js-breadcrumbs-handler-method' => 'showProvidersList'
                ]
            ];
        }
    }

    public function executeComponent() {

        try {

            $this->prepareParameters();

            $this->_setActionsDataStore();

            if ($this->arParams['IS_AJAX']) {
                /*
                 * устанавливаем текущее состояние хранилища данных
                 * для дальнейшей обработки и отрисовки на странице
                 */
                $_SESSION['partners.manager:actions-data-store'] = $this->request->get('partners_manager_actions_data_store');

                $this->arResult['current-action'] = $this->request->get('action');

                if (!file_exists(__DIR__ . "/actions/{$this->arResult['current-action']}.php")) {
                    throw new Exception("partners.manager: файл {$this->arResult['current-action']}.php");
                }

                $action_data = $this->arResult['partners.manager:actions-data-store'][$this->arResult['current-action']];

                // получаем данные по текущему экшену
                include "actions/{$this->arResult['current-action']}.php";
            }

            $this->includeComponentTemplate();
            CJSCore::Init(['date']);
        } catch (\Exception $ex) {
            ShowError($ex->getMessage());
        }
    }

    /**
     * @return array
     */
    protected function _getProvidersListGroupedByType() {

        $cache = \Bitrix\Main\Data\Cache::createInstance();

        $providers = [];

        if ($cache->initCache(3600, "partners.manager:providers")) {
            return $cache->getVars();
        } elseif ($cache->startDataCache()) {

            $providers = [
                "placements" => $this->_getProvidersListByGroupId(\travelsoft\booking\Utils::getOpt("placements_provider_group")),
                "sanatorium" => $this->_getProvidersListByGroupId(\travelsoft\booking\Utils::getOpt("sanatorium_provider_group")),
                "excursiontours" => $this->_getProvidersListByGroupId(\travelsoft\booking\Utils::getOpt("excursions_provider_group")),
                "transfers" => $this->_getProvidersListByGroupId(\travelsoft\booking\Utils::getOpt("transfers_provider_group"))
            ];

            $cache->endDataCache($providers);

            return $providers;
        }
    }

    /**
     * @return void
     */
    protected function _setActionsDataStore() {
        $this->arResult = [
            'partners.manager:actions-data-store' => &$_SESSION['partners.manager:actions-data-store']
        ];
    }

    /**
     * @global \CUser $USER
     * @param int $group_id
     * @return array
     */
    protected function _getProvidersListByGroupId(int $group_id) {
        global $USER;
        $db_providers = $USER->GetList(($by = "personal_country"), ($order = "desc"), [
            "GROUPS_ID" => [$group_id]
                ], [
            "SELECT" => ["UF_LEGAL_NAME"],
            "FIELDS" => ["ID", "NAME", "LAST_NAME"]
        ]);

        $arr_providers = [];
        while ($provider = $db_providers->Fetch()) {

            if ($provider["UF_LEGAL_NAME"]) {
                $arr_providers[$provider["ID"]] = $provider["UF_LEGAL_NAME"];
            } else {
                $arr_providers[$provider["ID"]] = "{$provider["NAME"]} {$provider["LAST_NAME"]}";
            }
        }
        return $arr_providers;
    }

    /**
     * @param int $provider_id
     * @return string
     */
    protected function _getProviderTypeById(int $provider_id) {

        foreach ($this->_getProvidersListGroupedByType() as $provider_type => $arr_provider) {

            if (isset($arr_provider[$provider_id])) {
                return $provider_type;
            }
        }

        return '';
    }

    /**
     * @param int $provider_id
     * @return array
     */
    protected function _getPlacementsList(int $provider_id) {

        $list = [];
        foreach (\travelsoft\booking\datastores\PlacementsDataStore::get([
            'filter' => ['PROPERTY_USER' => $provider_id],
            'select' => ['ID', 'NAME', 'DATE_CREATE', 'TIMESTAMP_X', 'ACTIVE']
        ]) as &$item) {
            $item['edit-page'] = '/partners/partners-manager/placements-edit/?CODE=' . $item['ID'];
            $list[] = $item;
        }
        return $list;
    }

    /**
     * @param int $provider_id
     * @return array
     */
    protected function _getSanatoriumList(int $provider_id) {

        $list = [];
        foreach (\travelsoft\booking\datastores\SanatoriumDataStore::get([
            'filter' => ['PROPERTY_USER' => $provider_id],
            'select' => ['ID', 'NAME', 'DATE_CREATE', 'TIMESTAMP_X', 'ACTIVE']
        ]) as &$item) {
            $item['edit-page'] = '/partners/partners-manager/sanatorium-edit/?CODE=' . $item['ID'];
            $list[] = $item;
        }

        return $list;
    }

    /**
     * @param int $provider_id
     * @return array
     */
    protected function _getExcursiontoursList(int $provider_id) {

        $list = [];
        foreach (travelsoft\booking\datastores\ExcursionsDataStore::get([
            'filter' => ['PROPERTY_USER_ID' => $provider_id],
            'select' => ['ID', 'NAME', 'ACTIVE', 'PROPERTY_IS_EXCURSION_TOUR', 'DATE_CREATE', 'TIMESTAMP_X']
        ]) as $exctour) {
            $exctour['AUTOSTOPSALE'] = false;
            $exctour['edit-page'] = '/partners/partners-manager/excursiontour-edit/?CODE=' . $exctour['ID'];
            $exctour['SERVICE-ID'] = 0;
            $exctour['IS-MULTIPLE'] = $exctour['PROPERTY_IS_EXCURSION_TOUR_VALUE'] === 'Y';
            $list[$exctour['ID']] = $exctour;
        }

        if (!empty($list)) {

            $services = new \travelsoft\booking\datastores\ServicesDataStore([
                'filter' => ['UF_IBLOCK_ELEMENT_ID' => \array_values(\array_keys($list))],
                'select' => ['ID', 'UF_IBLOCK_ELEMENT_ID']
            ]);

            $autostopsale = (new \travelsoft\booking\datastores\Autostopsale([
                        'filter' => ['UF_SERVICE_ID' => \array_values(\array_keys($services->fetch(['ID'])))],
                        'select' => ['ID', 'UF_SERVICE_ID']
                            ]))->fetch(['UF_SERVICE_ID']);

            foreach ($services->fetch(['UF_IBLOCK_ELEMENT_ID']) as $exctour_id => $data) {
                if (isset($list[$exctour_id])) {
                    $list[$exctour_id]["PRICES-TABLE-DETAIL-LINK"] = "/partners/partners-manager/" . (!$list[$exctour_id]['IS-MULTIPLE'] ? "common-prices-table" : "excursiontours-prices-table") . "/?row_id={$data[0]['ID']}&provider_id={$provider_id}&getDate=" . MakeTimestamp("01." . date("m") . "." . date("Y"));
                    $list[$exctour_id]['SERVICE-ID'] = $data[0]['ID'];
                    $list[$exctour_id]['AUTOSTOPSALE'] = isset($autostopsale[$data[0]['ID']]);
                }
            }
        }

        return $list;
    }

    /**
     * @param int $provider_id
     * @return array
     */
    protected function _getTransfersList(int $provider_id) {

        $list = [];

        foreach (\travelsoft\booking\datastores\ServicesDataStore::get([
            'filter' => [
                'UF_USER_ID' => $provider_id,
                'UF_SERVICE_TYPE_NAME' => 7
            ],
            'select' => [
                'ID', 'UF_NAME'
            ]
        ]) as $transfer) {
            $list[$transfer['ID']] = $transfer['UF_NAME'];
        }

        return $list;
    }

    /**
     * @param array $action_data
     * @return array
     */
    protected function _getCommonSimplePricesManageData($action_data) {
        if (!$action_data['date-from']) {
            $action_data['date-from'] = date('d.m.Y');
        }

        if (!$action_data['date-to']) {
            $action_data['date-to'] = date('d.m.Y', time() + (86400 * 30));
        }

        $filter = ['UF_IBLOCK_ELEMENT_ID' => $action_data['object-id']];

        $services = new \travelsoft\booking\datastores\ServicesDataStore([
            'filter' => $filter
        ]);

        $simple_prices_manage_data = [
            'OBJECT-ID' => $action_data['object-id'],
            'FORMATTED-DATES' => \travelsoft\booking\Utils::getFormattedIntervalDate(MakeTimestamp($action_data['date-from']), MakeTimestamp($action_data['date-to']), 86400, "getDaysFormat"),
            'OBJECT-NAME' => (CIBlockElement::GetByID($action_data['object-id'])->Fetch())['NAME'],
            'DATE-FROM' => $action_data['date-from'],
            'DATE-TO' => $action_data['date-to'],
            'ALL-SERVICES' => $services->fetch(['ID']),
            'CURRENT-SERVICE' => $action_data['service-id']
        ];

        $arr_services_list = $action_data['service-id'] > 0 ? [$action_data['service-id'] => ($services->fetch(['ID']))[$action_data['service-id']]] : $services->fetch(['ID']);

        $arr_services_id_list = array_keys($arr_services_list);

        if (!empty($arr_services_id_list)) {

            $quotas = new travelsoft\booking\datastores\QuotasDataStore([
                'filter' => ['UF_SERVICE_ID' => $arr_services_id_list, '><UF_DATE' => [MakeTimestamp($action_data['date-from']), MakeTimestamp($action_data['date-to'])]],
                'order' => ['UF_DATE' => 'ASC'],
                'select' => ['ID', 'UF_DATE', 'UF_SERVICE_ID', 'UF_QUOTE', 'UF_STOP']
            ]);

            $prices = new \travelsoft\booking\datastores\PricesDataStore([
                'filter' => ['UF_SERVICE_ID' => $arr_services_id_list, '><UF_DATE' => [MakeTimestamp($action_data['date-from']), MakeTimestamp($action_data['date-to'])]],
                'order' => ['UF_DATE' => 'ASC'],
                'select' => ['ID', 'UF_DATE', 'UF_SERVICE_ID', 'UF_GROSS', 'UF_NO_ARRIVALS']
            ]);

            $arr_quotas = $quotas->fetch(['UF_DATE', 'UF_SERVICE_ID']);
            $arr_prices = $prices->fetch(['UF_DATE', 'UF_SERVICE_ID']);

            $simple_prices_manage_data['DATA'] = [];
            $data = &$simple_prices_manage_data['DATA'];

            foreach ($arr_services_list as $arr_service) {

                $data[$arr_service[0]['ID']] = [
                    'NAME' => $arr_service[0]['UF_NAME'],
                    'PRICES-TABLE-DETAIL-LINK' => "/partners/partners-manager/common-prices-table/?row_id={$arr_service[0]['ID']}&provider_id={$action_data['provider-id']}&getDate=" . MakeTimestamp("01." . date("m", strtotime($action_data['date-from'])) . "." . date("Y", strtotime($action_data['date-from']))),
                    'DATA' => []
                ];

                $start = MakeTimestamp($action_data['date-from']);
                $end = MakeTimestamp($action_data['date-to']);

                while ($start <= $end) {

                    $data[$arr_service[0]['ID']]['DATA'][$start] = [
                        'QUOTA' => NULL,
                        'IN_SALE' => NULL,
                        'PRICE' => false
                    ];

                    if (isset($arr_quotas[$start][$arr_service[0]['ID']])) {

                        $data[$arr_service[0]['ID']]['DATA'][$start]['QUOTA'] = intval($arr_quotas[$start][$arr_service[0]['ID']][0]['UF_QUOTE']);
                        
                        $data[$arr_service[0]['ID']]['DATA'][$start]['STOP_SALE'] = intval($arr_quotas[$start][$arr_service[0]['ID']][0]['UF_STOP']) > 0;

                        $data[$arr_service[0]['ID']]['DATA'][$start]['IN_SALE'] = $arr_quotas[$start][$arr_service[0]['ID']][0]['UF_QUOTE'] - $arr_quotas[$start][$arr_service[0]['ID']][0]['UF_SOLD_NUMBER'];
                    }
                    
                    $data[$arr_service[0]['ID']]['DATA'][$start]['PRICE'] = isset($arr_prices[$start][$arr_service[0]['ID']]);
                    $data[$arr_service[0]['ID']]['DATA'][$start]['NO_ARRIVALS'] = isset($arr_prices[$start][$arr_service[0]['UF_NO_ARRIVALS']]) && $arr_prices[$start][$arr_service[0]['UF_NO_ARRIVALS']] > 0;

                    $start += 86400;
                }
            }

            return $simple_prices_manage_data;
        }
    }

    /**
     * @param array $action_data
     * @return array
     */
    protected function _getPlacementsSimplePricesManageData($action_data) {
        return $this->_getCommonSimplePricesManageData($action_data);
    }

    /**
     * @param array $action_data
     * @return array
     */
    protected function _getSanatoriumSimplePricesManageData($action_data) {
        return $this->_getCommonSimplePricesManageData($action_data);
    }

    /**
     * @param array $action_data
     * @return array
     */
    protected function _getExcursiontoursSimplePricesManageData($action_data) {
        if (!$action_data['date-from']) {
            $action_data['date-from'] = date('d.m.Y');
        }

        if (!$action_data['date-to']) {
            $action_data['date-to'] = date('d.m.Y', time() + (86400 * 30));
        }

        $filter = ['UF_IBLOCK_ELEMENT_ID' => $action_data['object-id']];

        $services = new \travelsoft\booking\datastores\ServicesDataStore([
            'filter' => $filter
        ]);

        $simple_prices_manage_data = [
            'OBJECT-ID' => $action_data['object-id'],
            'FORMATTED-DATES' => \travelsoft\booking\Utils::getFormattedIntervalDate(MakeTimestamp($action_data['date-from']), MakeTimestamp($action_data['date-to']), 86400, "getDaysFormat"),
            'OBJECT-NAME' => (CIBlockElement::GetByID($action_data['object-id'])->Fetch())['NAME'],
            'DATE-FROM' => $action_data['date-from'],
            'DATE-TO' => $action_data['date-to']
        ];

        $arr_services_list = $services->fetch(['ID']);

        $arr_services_id_list = array_keys($arr_services_list);

        if (!empty($arr_services_id_list)) {

            $quotas = new travelsoft\booking\datastores\QuotasDataStore([
                'filter' => ['UF_SERVICE_ID' => $arr_services_id_list, '><UF_DATE' => [MakeTimestamp($action_data['date-from']), MakeTimestamp($action_data['date-to'])]],
                'order' => ['UF_DATE' => 'ASC'],
                'select' => ['ID', 'UF_DATE', 'UF_SERVICE_ID', 'UF_QUOTE', 'UF_STOP']
            ]);

            $prices = new \travelsoft\booking\datastores\PricesDataStore([
                'filter' => ['UF_SERVICE_ID' => $arr_services_id_list, '><UF_DATE' => [MakeTimestamp($action_data['date-from']), MakeTimestamp($action_data['date-to'])]],
                'order' => ['UF_DATE' => 'ASC'],
                'select' => ['ID', 'UF_DATE', 'UF_SERVICE_ID', 'UF_GROSS', 'UF_NO_ARRIVALS']
            ]);

            $arr_quotas = $quotas->fetch(['UF_DATE', 'UF_SERVICE_ID']);
            $arr_prices = $prices->fetch(['UF_DATE', 'UF_SERVICE_ID']);

            $simple_prices_manage_data['DATA'] = [];
            $data = &$simple_prices_manage_data['DATA'];

            foreach ($arr_services_list as $arr_service) {

                $data[$arr_service[0]['ID']] = [
                    'NAME' => $arr_service[0]['UF_NAME'],
                    'PRICES-TABLE-DETAIL-LINK' => "/partners/partners-manager/common-prices-table/?row_id={$arr_service[0]['ID']}&provider_id={$action_data['provider-id']}&getDate=" . MakeTimestamp("01." . date("m", strtotime($action_data['date-from'])) . "." . date("Y", strtotime($action_data['date-from']))),
                    'DATA' => []
                ];

                $start = MakeTimestamp($action_data['date-from']);
                $end = MakeTimestamp($action_data['date-to']);

                while ($start <= $end) {

                    $data[$arr_service[0]['ID']]['DATA'][$start] = [
                        'QUOTA' => NULL,
                        'IN_SALE' => NULL,
                        'PRICE' => false
                    ];

                    if (isset($arr_quotas[$start][$arr_service[0]['ID']])) {

                        $data[$arr_service[0]['ID']]['DATA'][$start]['QUOTA'] = intval($arr_quotas[$start][$arr_service[0]['ID']][0]['UF_QUOTE']);
                        
                        $data[$arr_service[0]['ID']]['DATA'][$start]['STOP_SALE'] = intval($arr_quotas[$start][$arr_service[0]['ID']][0]['UF_STOP']) > 0;

                        $data[$arr_service[0]['ID']]['DATA'][$start]['IN_SALE'] = $arr_quotas[$start][$arr_service[0]['ID']][0]['UF_QUOTE'] - $arr_quotas[$start][$arr_service[0]['ID']][0]['UF_SOLD_NUMBER'];
                    }

                    $data[$arr_service[0]['ID']]['DATA'][$start]['PRICE'] = isset($arr_prices[$start][$arr_service[0]['UF_NO_ARRIVALS']]) && $arr_prices[$start][$arr_service[0]['UF_NO_ARRIVALS']] > 0;

                    $start += 86400;
                }
            }

            return $simple_prices_manage_data;
        }
    }

    /**
     * @param array $action_data
     * @return array
     */
    protected function _getTransfersSimplePricesManageData($action_data) {
        
    }

}
