<?php

namespace Kosmos\Main\Controller;

use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Engine\ActionFilter;

class Captcha extends Controller
{

    public function configureActions()
    {
        return [
            'process' => [
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
        return $GLOBALS['APPLICATION']->CaptchaGetCode();
    }
}