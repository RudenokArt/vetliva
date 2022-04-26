<?php

/**
 * request application
 */
class Requset {
    
    /**
     *  request application object
     * @var \Bitrix\Main\HttpRequest
     */
    private static $inst = null;
    
    private function __clone() {}
    
    private function __construct() {}
    

    /**
     * get application request object
     */
    static public function getInstance () {
        
       if (!self::$inst) {
            self::$inst = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
        }
        
        return self::$inst;
        
    }
    
}