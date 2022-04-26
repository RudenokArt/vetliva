<?php

/** @var \PartnersManager $this */
/** @var array $action_data */

$this->arResult['providers-list'] = $this->_getProvidersListGroupedByType();
$this->arResult['current-provider'] = null;
if ($action_data['provider-id'] > 0) {
    $this->arResult['current-provider'] = $action_data['provider-id'];
}
