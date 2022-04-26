<?php

/** @var \PartnersManager $this */
/** @var array $action_data */
$this->arResult['breadcrumbs'] = [];
$actions = array_keys($this->arResult['partners.manager:actions-data-store']);
$last_action_name = $actions[count($actions)-1];
foreach ($this->arResult['partners.manager:actions-data-store'] as $action_name => $action_data) {
    $this->arResult['breadcrumbs'][] = [
        'title' => $action_data['title'],
        'have-link' => $action_name !== $last_action_name,
        'js-breadcrumbs-handler-method' => $action_data['js-breadcrumbs-handler-method']
    ];
}