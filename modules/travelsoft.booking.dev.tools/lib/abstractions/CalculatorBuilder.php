<?php
namespace travelsoft\booking\abstractions;

/**
 * Абстрактный класс "строителя" калькулятора
 *
 * @author dimabresky
 */
abstract class CalculatorBuilder {
    abstract static public function build ();
}
