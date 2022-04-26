<?php

namespace travelsoft\booking\abstractions\commons;

use \travelsoft\booking\abstractions\PriceCalculator as AbstractPriceCalculator;

/**
 * Общий класс для расчёта цен
 *
 * @author dimabresky
 */
abstract class PriceCalculator extends AbstractPriceCalculator {

    /**
     * Тип цены "Цена" для совместимости со старой версией
     */
    const MAIN_PTID_OLD_VERSION = 4;

    /**
     * Тип цены "Стоимость за номер для одного человека"
     */
    const PRICE_FOR_ONE_ADULT_BY_ROOM = 8;
    
    /**
     * Тип цены "Стоимость за номер"
     */
    const PRICE_BY_ROOM = 7;

    /**
     * Производит расчёт цен
     * @param array $id
     * @return array|null
     */
    public function calculate(array $id = null) {

        $arResult = null;

        if ($this->_dataProvider->prices && !empty($this->_dataProvider->prices->fetch())) {

            $arServices = $this->_dataProvider->services->fetch(array("UF_IBLOCK_ELEMENT_ID"));

            if (!$id) {
                $id = array_keys($arServices);
            }

            for ($i = 0, $cnt = count($id); $i < $cnt; $i++) {
                $servicesID = null;
                for ($j = 0, $cntt = count($arServices[$id[$i]]); $j < $cntt; $j++) {
                    if ($arServices[$id[$i]][$j]["ID"]) {
                        $servicesID[] = $arServices[$id[$i]][$j]["ID"];
                    }
                }

                if ($servicesID && ($arPriceCalcResult = $this->pricesByServices($servicesID))) {
                    $arResult[$id[$i]] = $arPriceCalcResult;
                }
            }
        }

        return $arResult;
    }

    /**
     * Производит рассадку людей и возвращает массив результата
     * @param int $serviceId
     * @return array
     * @throws \Exception
     */
    public function allocate(int $serviceId): array {

        $arServices = $this->_dataProvider->services->fetch(array('ID'));

        if (!isset($arServices[$serviceId])) {

            throw new \Exception('Unknow service with id="' . $serviceId . '"');
        }
        $adults = $this->_dataProvider->request->adults;
        $children = $this->_dataProvider->request->children;
        if (is_array($this->_dataProvider->request->children_age)) {
            $arAge = $this->_dataProvider->request->children_age;
        } else {
            $arAge = [];
        }

        return $this->_allocate($arServices[$serviceId][0], $adults, $children, $arAge);
    }

    /**
     * Производит рассадку людей и возвращает массив результата
     * @param array $arService
     * @param int $adults
     * @param int $children
     * @param array $arAge
     * @return array
     */
    public static function _allocate(array $arService, int $adults, int $children, array $arAge = null) {

        
        if ($arAge) {
            rsort($arAge);
        }

        if ($arService['UF_PLACES_MAIN'] <= 0) {
            $arService['UF_PLACES_MAIN'] = $arService['UF_PEOPLE'];
        }

        $arAllocate = array(
            'main' => array(
                'adults' => 0,
                'children' => 0,
                'children_age' => array()
            ),
            'additional' => array(
                'adults' => 0,
                'children' => 0,
                'children_age' => array()
            )
        );

        $deltaMainPlaces = $arService['UF_PLACES_MAIN'] - $adults;

        if ($deltaMainPlaces >= 0) {

            $arAllocate['main']['adults'] = $adults;

            if ($children > 0) {

                if ($deltaMainPlaces == 0) {

                    $arAllocate['additional']['children'] = $children;
                    $arAllocate['additional']['children_age'] = $arAge;
                } else {

                    $deltaAdditPlaces = $deltaMainPlaces - $children;

                    if ($deltaAdditPlaces >= 0) {

                        $arAllocate['main']['children'] = $children;
                        $arAllocate['main']['children_age'] = $arAge;
                    } else {

                        $delta = $children + $deltaAdditPlaces;
                        $arAllocate['main']['children'] = $delta;
                        for ($i = 0; $i < $delta; $i++) {
                            $arAllocate['main']['children_age'][] = $arAge[$i];
                            unset($arAge[$i]);
                        }

                        $arAllocate['additional']['children'] = abs($deltaAdditPlaces);
                        $arAllocate['additional']['children_age'] = array_values($arAge);
                    }
                }
            }
        } else {

            $arAllocate['main']['adults'] = $arService['UF_PLACES_MAIN'];

            $deltaAdditPlaces = $arService['UF_PLACES_ADD'] + $deltaMainPlaces;

            if ($deltaAdditPlaces >= 0) {

                $arAllocate['additional']['adults'] = abs($deltaMainPlaces);

                if ($children > 0) {
                    $arAllocate['additional']['children'] = $children;
                    $arAllocate['additional']['children_age'] = $arAge;
                }
            }
        }
        return $arAllocate;
    }
}
