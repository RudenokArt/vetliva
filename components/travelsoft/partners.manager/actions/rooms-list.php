<?php

/**
 * @var \ProvidersManager $this
 * @var array $action_data
 */

$this->arResult['PROVIDER-ID'] = $action_data['provider-id'];

$this->arResult['IS-SANATORIUM'] = $this->_getProviderTypeById($action_data['provider-id']) === 'sanatorium';

$this->arResult['ROOMS-LIST'] = \travelsoft\booking\datastores\ServicesDataStore::get([
    'filter' => ['UF_IBLOCK_ELEMENT_ID' => $action_data['object-id']],
    'select' => ['UF_NAME', 'ID']
]);

$this->arResult['OBJECT-NAME'] = (CIBlockElement::GetByID($action_data['object-id'])->Fetch())['NAME'];