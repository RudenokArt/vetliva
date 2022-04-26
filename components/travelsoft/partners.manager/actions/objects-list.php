<?php

/**
 * @var \ProvidersManager $this
 * @var array $action_data
 */

$this->arResult['PROVIDER-ID'] = $action_data['provider-id'];

$this->arResult['PROVIDER-TYPE'] = $this->_getProviderTypeById($action_data['provider-id']);

$method = '_get' . ucfirst($this->arResult['PROVIDER-TYPE']) . 'List';

$this->arResult['OBJECTS-LIST'] = $this->$method($action_data['provider-id']);