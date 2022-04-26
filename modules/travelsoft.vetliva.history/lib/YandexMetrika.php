<?php

namespace travelsoft\vetliva;

use \Bitrix\Main\Config\Option;

/**
 * Класс для работы с API яндекс-метрики
 *
 * @author dimabresky
 */
class YandexMetrika {

    const MODULE_ID = "travelsoft.vetliva.history";

    /**
     * @var \Bitrix\Main\Web\HttpClient
     */
    protected $_http_client = '';

    /**
     * @var array
     */
    protected $_default_query_data = [];

    /**
     * @param array $query_parameters
     * @param string $method
     * @return string
     */
    protected function _sendRequest(array $query_parameters, string $method = "get"): string {
        
        $query_parameters = array_merge($this->_default_query_data, $query_parameters);
        
        $url = "https://api-metrika.yandex.net/stat/v1/data";
        switch ($method) {
            case "post":
                $response = $this->_http_client->post($url, $query_parameters);
                break;
            default:
                $response = $this->_http_client->get("{$url}?".http_build_query($query_parameters));
        }
        
        return $response;
    }

    /**
     * @param array $parameters [date_from => YYYY-MM-DD, date_to => YYYY-MM-DD, url => https://...]
     * @throws Exception
     */
    public function __construct(array $parameters) {

        $access_token = Option::get(self::MODULE_ID, "YANDEX_ACCESS_TOKEN");

        if (!$access_token) {
            throw new Exception('travelsoft.vetliva.history: acess token not found.');
        }

        $counter_ids = Option::get(self::MODULE_ID, "YANDEX_COUNTER_ID");

        if (!$counter_ids) {
            throw new Exception('travelsoft.vetliva.history: counter ids not found.');
        }

        $this->_default_query_data = [
            "ids" => $counter_ids,
            "date1" => $parameters['date_from'],
            "date2" => $parameters['date_to'],
            "lang" => "ru",
            "demensions" => ''
        ];

        if ($parameters['url']) {
            $this->_default_query_data['filters'] = rawurldecode("ym:pv:URL=@'{$parameters['url']}'");
        }
        
        $this->_http_client = new \Bitrix\Main\Web\HttpClient;
        $this->_http_client->setHeader("Authorization", "OAuth $access_token", true);
        $this->_http_client->setHeader("Content-Type", "application/x-yametrika+json", true);
    }

    /**
     * @return array
     */
    public function getGeographyStatistic(): array {
        return (array) json_decode($this->_sendRequest([
            'metrics' => "ym:s:visits",
            'dimensions' => "ym:s:regionCountry"
        ]), true);
    }
    
    /**
     * @return array
     */
    public function getAgeStatistic (): array {
        return (array) json_decode($this->_sendRequest([
            'metrics' => "ym:s:under18AgePercentage,ym:s:upTo24AgePercentage,ym:s:upTo34AgePercentage,ym:s:upTo44AgePercentage,ym:s:over44AgePercentage"
        ]), true);
    }
    
    /**
     * @return array
     */
    public function getMaleStatistic(): array {
        return (array) json_decode($this->_sendRequest([
            'metrics' => "ym:s:manPercentage,ym:s:womanPercentage"
        ]), true);
    }

    /**
     * @return array
     */
    public function getDevicesStatistic(): array {
        return (array) json_decode($this->_sendRequest([
            'metrics' => "ym:s:visits",
            'dimensions' => "ym:s:deviceCategory"
        ]), true);
    }

    /**
     * @return array
     */
    public function getTotalStatistics(): array {
        return [
            'geography' => $this->getGeographyStatistic(),
            'age' => $this->getAgeStatistic(),
            'male' => $this->getMaleStatistic(),
            'devices' => $this->getDevicesStatistic(),
        ];
    }

}
