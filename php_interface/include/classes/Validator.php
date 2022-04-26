<?php
namespace travelsoft\rest;

require 'Messanger.php';

/**
 * rest validator
 *
 * @author dimabresky
 */
class Validator {
    
    /**
     * @var array
     */
    protected $parameters = null;
    
    /**
     * @var array
     */
    protected $result = null;
    
    /**
     * @var travelsoft\rest\Messager
     */
    protected $messager = null;
    
    /**
     * @var boolean
     */
    protected $isMultiple = false;
    
    /**
     * @var string
     */
    protected $signature = null;
    
    /**
     * @var string
     */
    protected $signatureKey = "13636e98b5a4287871dbc6bc56a1fb3f";
    
    /**
     * @var array
     */
    protected $checkPool = null;
    
    public function __construct() {
        $this->messager = new Messanger();
    }
    
    /**
     * Добавляет проверку имени пользователя и пароля
     * @return $this
     */
    public function addAuthCheck () {
        $this->checkPool[] = "checkAuth";
        return $this;
    }
    
    /**
     * Добавляет проверку параметра dateFrom
     * @return $this
     */
    public function addDateCheck () {
        $this->checkPool[] = "checkDate";
        return $this;
    }
    
    /**
     * Добавляет проверку подписи (параметра signature)
     */
    public function addSignatureCheck () {
        $this->checkPool[] = "checkSignature";
        return $this;
    }
    
    /**
     * Добавляет проверку параметра "serviceId"
     * @return $this
     */
    public function addServiceIdCheck () {
        $this->checkPool[] = "checkServiceId";
        return $this;
    }
    
    /**
     * Добавляет проверку параметра "nightCnt"
     * @return $this
     */
    public function addNightCntCheck () {
        $this->checkPool[] = "checkNightCnt";
        return $this;
    }
    
    /**
     * Добавляет проверку параметра "quantity"
     * @return $this
     */
    public function addQuantityCheck () {
        $this->checkPool[] = "checkQuantity";
        return $this;
    }
    
    /**
     * Проверка принадлежности партнера услуге
     * @return this
     */
    public function addAffiliationPartnerCheck () {
       $this->checkPool[] = "checkAffiliationPartner";
       return $this;
    }
    
    /**
     * Добавляет проверку параметра "partnerId"
     * @return $this
     */
    public function addPartnerIdCheck () {
        $this->checkPool[] = "checkPartnerId";
        return $this;
    }
    
    /**
     * Устанавливает признак того, что массив входных мараметров множественный
     * @return $this
     */
    public function isMultiple () {
        $this->isMultiple = true;
        return $this;
    }
    
    /**
     * Проверка параметров
     * @return boolean
     */
    public function validate () {
        
        if ($this->isMultiple) {
            foreach ($this->parameters as $parameters) {
                $this->_validate((array)$parameters);
            }
        } else {
            $this->_validate((array)$this->parameters);
        }
        
        return empty($this->result);
        
    }
    
    /**
     * Возвращает массив результата проверок
     * @return array
     */
    public function getResult () {
        $result = array("messages" => array(), "codes" => array());
        foreach ($this->result as $message) {
            $result["messages"][] = current($message);
            $result["codes"][] = key($message);
        }
        return $result;
    }
    
    /**
     * Возвращает результата проверок в виде ассоциативного массива
     * @return array|null
     */
    public function getAssocResult () {
        
        $result = array();
        
        foreach ($this->result as $message) {
            
            $result[] = array(
                "code" => key($message),
                "message" => current($message)
            );
            
        }
        
        return $result;
    }
    
    /**
     * Возвращает параметры для проверки
     * @return array
     */
    public function getParameters () {
        return $this->parameters;
    }
    
    /**
     * Сбрасывает пул проверок
     * @return $this
     */
    public function resetCheckPool () {
        $this->checkPool = null;
        return $this;
    }
    
    /**
     * Устанавливает значение входных параметров
     * @param array $parameters
     * @return $this
     */
    public function setParameters (array $parameters) {
        $this->parameters = $parameters;
        return $this;
    }
    
    /**
     * Устанавливает значение подписи
     * @param string $signature
     * @return $this
     */
    public function setSignature (string $signature) {
        $this->signature = $signature;
        return $this;
    }
    
    /**
     * Проверка соответсвия параметров serviceId и partnerId
     * @param int $serviceId
     * @param int $partnerId
     * @return boolean
     */
    public function checkAffiliationPartner (int $serviceId, int $partnerId) {
        
        $arService = \travelsoft\booking\datastores\ServicesDataStore::get(array(
            "filter" => array("UF_USER_ID" => $partnerId, "ID" => $serviceId),
            "select" => array("ID")
        ));

        if ($arService[0]["ID"] <= 0) {
            $this->triggerResult(3, array("#serviceId#" => $serviceId, "#partnerId#" => $partnerId));
            return false;
        }
        return true;
    }
    
    /**
     * Проверка существования объекта по id
     * @param int $parentId
     * @return false
     */
    public function checkServiceParentExists (int $parentId) {
        
        $parent = \CIBlockElement::GetByID($parentId)->Fetch();
        
        return $parent['ID'] > 0;
    }
    
    /**
     * Очистка массива результата
     * @return $this
     */
    public function resetResult() {
        $this->result = null;
        return $this;
    }
    
    /**
     * Добавляет сообщение о результате в массив результатов по коду
     * @param int $code
     * @param array $replace
     * @return $this
     */
    public function triggerResult (int $code, array $replace = null) {
        if (!empty($replace)) {
            $this->result[][$code] = str_replace(array_keys($replace), array_values($replace), $this->messager->getMessage($code));
        } else {
            $this->result[][$code] = $this->messager->getMessage($code);
        }
        return $this;
    }
    
    /**
     * 
     * @param array $parameters
     * @return string
     */
    public function createSignature (array $parameters){
        
        return md5(\Bitrix\Main\Web\Json::encode($parameters) . $this->signatureKey);
    }
    
    /**
     * @param array $parameters
     */
    protected function _validate(array $parameters) {
        
        foreach ($this->checkPool as $validation) {
            
            switch ($validation) {

                case "checkDate":
                    
                    if (preg_match("#^\d{2}\.\d{2}\.\d{4}$#", $parameters["dateFrom"]) !== 1) {
                        $this->triggerResult(4);
                    }

                    break;

                case "checkServiceId":
                    
                    if ($parameters["serviceId"] <= 0) {
                        $this->triggerResult(5);
                    }

                    break;

                case "checkNightCnt":
                    
                    if ($parameters["nightCnt"] < 0) {
                        $this->triggerResult(6);
                    }

                    break;

                case "checkQuantity":
                    
                    if ($parameters["quantity"] <= 0) {
                        $this->triggerResult(7);
                    }

                    break;
                    
                case "checkPartnerId":
                    
                    if ($parameters["partnerId"] <= 0) {
                        $this->triggerResult(8);
                    }

                    break;
                    
                    
                case "checkAffiliationPartner":
                   
                    $this->checkAffiliationPartner((int)$parameters["serviceId"], (int)$parameters["partnerId"]);
                    
                    break;
                    
                case "checkSignature":
                    
                    if (md5(\Bitrix\Main\Web\Json::encode($parameters) . $this->signatureKey) !== $this->signature) {
                        $this->triggerResult(9);
                    }

                    break;
                
                case "checkAuth":
                    
                    $user = \CUser::GetByLogin($parameters['username'])->Fetch();

//                    $salt = substr($user['PASSWORD'], 0, (strlen($user['PASSWORD']) - 32));
//
//                    $realPassword = substr($user['PASSWORD'], -32);
//                    $password = md5($salt.$parameters['password']);

                    if (empty($parameters['password']) || empty($user['PASSWORD'])) {
                        $passwordCorrect = false;
                    } else {
                        $passwordCorrect = \Bitrix\Main\Security\Password::equals($user['PASSWORD'], $parameters['password']);
                    }
                    
                    if ($passwordCorrect == false || !in_array(17, $GLOBALS["USER"]->GetUserGroup($user["ID"]))) {
                        $this->triggerResult(12);
                    }
                    
                    break;

                default: break;    

            }
        }
    }
    
}
