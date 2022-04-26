<?php
namespace travelsoft\rest;

require 'classes/slim/vendor/autoload.php';
require 'classes/Validator.php';
require 'classes/Logger.php';
require 'classes/TravelLineRest.php';

#####################
#   ИНСТРУМЕНТЫ REST
#####################

# подключаем ядро битрикс
# определяем специальные констаеты ядра
function bxCoreInit () {
    define("NO_KEEP_STATISTIC", true);
    define("NOT_CHECK_PERMISSIONS",true);
    define("NO_AGENT_STATISTIC",true);
    define('NO_AGENT_CHECK', true);
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
}

# подключаем модуль бронирования
function bmInc () {
    \Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools");
}

# di-container
$container = new \Slim\Container();
# валидатор
$container["validator"] = new Validator();
# logger
$container["logger"] = new Logger(__DIR__ . '/rest_log.txt');

# определяем общий обработчик ошибок
$container["errorHandler"] = function ($container) {
    return function ($request, $response, $exception) use ($container) {
        
        return $container["response"]->withJson($container["validator"]->resetResult()->triggerResult(0)->getResult(), 500);
    };
};

# определяем обработчик 404
$container["notFoundHandler"] = function ($container) {
    return function ($request, $response) use ($container) {
        return $container["response"]->withJson($container["validator"]->resetResult()->triggerResult(1)->getResult(), 404);
    };
};

# определяем обработчик 405
$container["notAllowedHandler"] = function ($container) {
    return function ($request, $response, $methods) use ($container) {
        $methodsString = implode(', ', $methods);
        return $container["response"]->withHeader('Allow', $methodsString)->withJson($container["validator"]->resetResult()->triggerResult(2, array("#methods#" =>$methodsString))->getResult(), 405);
    };
};

$app = new \Slim\App($container);

# определяем обработчик php runtime error
$diContainer = $app->getContainer();
$diContainer["phpErrorHandler"] = function ($diContainer) {
    return function ($request, $response, $error) use ($diContainer) {
        dm($error->getTraceAsString(), false, false, false);die;
        return $diContainer["response"]->withJson($diContainer["validator"]->resetResult()->triggerResult(0)->getResult(), 500);
    };
};

$app->add(function ($request, $response, $next) use ($app) {
    
    bxCoreInit();
    
    bmInc();
    
    $diContainer = $app->getContainer();
    $diContainer['validator']->setParameters((array)$request->getParsedBody());
    return $next($request, $response);
});


# корневая дирректория rest
$restRoot = "/rest";

