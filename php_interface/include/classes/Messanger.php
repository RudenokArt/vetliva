<?php
namespace travelsoft\rest;

/**
 * rest messager object
 *
 * @author dimabresky
 */
class Messanger {
    
    /**
     * @var array
     */
    protected $messages = array(
        0 => "Ошибка rest сервиса",
        1 => "Сервис не найден",
        2 => "Доступные методы запроса: #methods#",
        3 => "Несоответствие параметров serviceId (#serviceId#) и partnerId (#partnerId#)",
        4 => "Неверный формат даты параметра dateFrom ('dateFrom' => 'DD.MM.YYYY')",
        5 => "Параметр serviceId должен быть > 0",
        6 => "Параметр nightCnt должен быть > 0",
        7 => "Параметр quantity должен быть > 0",
        8 => "Параметр partnerId должен быть > 0",
        9 => "Доступ запрещен. Подпись запроса не соответствует действительности",
        10 => "Квота для параметров [serviceId => #serviceId#, dateFrom => #dateFrom#, nightCnt => #nightCnt#] не найдена",
        11 => "Ok",
        12 => "Доступ запрещен. Попытка авторизации провалена",
        13 => "Запрашиваемый объект размещения не существует",
        14 => "Размещения по данному объекту отсутсвуют",
        15 => "Тарифы по данному объекту отсутствуют",
        16 => "Типы размещений по данному объекту отсутствуют",
        17 => "Следующие данные не смогли быть обработаны #DATA#",
        18 => "Квота для параметров [serviceId => #serviceId#, dateFrom => #dateFrom#, nightCnt => #nightCnt#] исчерпалась.",
        19 => "Неизвестная услуга (key = #key#)",
        20 => "Не удалось расчитать цену (key = #key#)",
        22 => "#text_error#",
        375 => "Hotel is not connected to TravelLine Channel Manager",
        730 => "Invalid room type",
        436 => "Invalid rate plan",
        61 => "Invalid Сurrency Code",
        361 => "Hotel with such credentials is not exist in channel",
    );
    
    /**
     * Возвращает массив сообщений
     * @param array $codes
     * @return array
     */
    public function getMessages (array $codes = null) {
        
        if (!empty($codes)) {
            $result = array();
            foreach ($codes as $code) {
                $result[] = $this->messages[$code];
            }
            return $result;
        } else {
            return $this->messages;
        }
    }
    
    /**
     * Возвращает сообщение по коду
     * @param int $code
     * @return string|null
     */
    public function getMessage(int $code) {
        return $this->messages[$code];
    }
    
}
