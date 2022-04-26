<?php
namespace travelsoft;

/**
 * Класс для работы с csv-файлами
 *
 *  @author SharlovaJuliya
 */
class CSV {

    protected $_csv_file = null;
    static protected $instance;

    protected $exceptions = array(
        1 => 'Файл #replace# не найден'
    );

    static public function getInstance() {

        if (self::$instance === null)
            self::$instance = new self();

        return self::$instance;

    }

    protected function e($mid, $replace) {

        $mess = str_replace("#replace#", $replace, $this->exceptions[$mid]);

        throw new \Exception('Исключение: ' . $mess);
    }

    /**
     * @param string $csv_file  - путь до csv-файла
     */

    public function start ($csv_file) {
		if (file_exists($csv_file)) { //Если файл существует
            $this->_csv_file = $csv_file; //Записываем путь к файлу в переменную

        }
        else { //Если файл не найден то вызываем исключение
            $this->e(1, $csv_file);
        }

    }

    public function setCSV(Array $csv) {
        //Открываем csv для до-записи,
        $handle = fopen($this->_csv_file, "a");

        foreach ($csv as $value) { //Проходим массив
            //Записываем, 3-ий параметр - разделитель поля
            fputcsv($handle, $value, ",");
        }

        fclose($handle); //Закрываем
    }

    /**
     * Метод для чтения из csv-файла. Возвращает массив с данными из csv
     * @return array;
     */
    public function getCSV() {

        $handle = fopen($this->_csv_file, "r"); //Открываем csv для чтения
        $array_line_full = array(); //Массив будет хранить данные из csv

        //Проходим весь csv-файл, и читаем построчно. 3-ий параметр разделитель поля
        /*while (($line = fgetcsv($handle, 0, ";")) !== FALSE) {
            $array_line_full[] = $line; //Записываем строчки в массив
        }
        fclose($handle); //Закрываем файл
        return $array_line_full; //Возвращаем прочтенные данные*/
    }


}