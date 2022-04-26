<?php

namespace Kosmos\Main\Controller;

use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Engine\ActionFilter;

class Map extends Controller
{

    public function configureActions()
    {
        return [
            'get' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        [ActionFilter\HttpMethod::METHOD_POST]
                    ),
                    new ActionFilter\Csrf(),
                ],
            ],
        ];
    }

    public function getAction()
    {
        return \Kosmos\Main\Helpers\Map::process($this->getRequest());
    }
}