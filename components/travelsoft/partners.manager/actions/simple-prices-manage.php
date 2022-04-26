<?php

/**
 * @var \ProvidersManager $this
 * @var array $action_data
 */

$this->arResult['PROVIDER-TYPE'] = $this->_getProviderTypeById($action_data['provider-id']);

$method = '_get' . ucfirst($this->arResult['PROVIDER-TYPE']) . 'SimplePricesManageData';

$this->arResult['SIMPLE-PRICES-MANAGE'] = $this->$method($action_data);