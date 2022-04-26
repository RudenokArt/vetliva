<?php

namespace travelsoft\booking\abstractions;

/**
 * Абстрактный Getter
 *
 * @author dimabresky
 */
abstract class Getter {
    
    public function __construct(array $parameters) {
       foreach ($parameters as $property => $val) {
           $this->_checkPropertyExists($property);
           $this->_container[$property] = $val;
       }
    }
    
    /**
     * @param string $property
     */
    protected function _checkPropertyExists (string $property) {
        if (!key_exists($property, $this->_container)) {
            throw new \Exception(get_class($this) . ": Unknown proeprty \"".$property."\"");
        }
    }
    
    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name) {
        $this->_checkPropertyExists($name);
        return $this->_container[$name];
    }
    
    /**
     * Возвращает поля объекта в виде массива
     * @return array
     */
    public function getPropertiesLikeArray() {
        return $this->_container;
    }
      
}
