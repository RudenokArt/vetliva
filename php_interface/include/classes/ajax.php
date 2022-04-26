<?php
namespace travelsoft;

/**
 * @author dimabresky
 * 
 * Ajax class of the application
 * 
 */
class Ajax {
    
    /**
     * start ajax buffer
     * @global CMain $APPLICATION
     * @param boolean $wrappedInTag
     * @param string $arrAttributes
     */
    public static function start ($label, $wrappedInTag = false, $arrAttributes = array()) {
        
        self::checkException($label, "Start");
        
        global $APPLICATION;
        
        $request = self::getRequest();
       
        foreach ($arrAttributes as $attr => $value) {
            $arr[] = $attr . "=\"" . $value . "\"";
        }
        
        if ($wrappedInTag) {
            echo "<div id='" . $label . "' ".implode(" ", $arr).">";
        } 
        
        
        if($request->get('__compid') == $label)
            $APPLICATION->RestartBuffer();
        
    }
    
    /**
     * end ajax buffer
     * @param string $label
     * @param boolean $wrappedInTag
     */
    public static function end ($label, $wrappedInTag = false) {
        
        self::checkException($label, "End");

        $request = self::getRequest();
        
        if($request->isPost() && $request->getPost('__compid') == $label ) {
            require \Bitrix\Main\Application::getDocumentRoot()."/bitrix/modules/main/include/epilog_after.php";
            die();
        }
        
        if ($wrappedInTag) {
            echo "</div>";  
        }
        
    }
    
    /**
     * get application request
     * @return string
     */
    protected static function getRequest () {
        return \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
    }
    
    /**
     * trigger exception
     * @param string $label
     * @param string $mn
     * @throws \Exception
     */
    protected static function checkException ($label, $mn) {
        if ($label == "") {
            throw new \Exception($mn . ": label of ajax area not setted");
        }
    }
    
}
