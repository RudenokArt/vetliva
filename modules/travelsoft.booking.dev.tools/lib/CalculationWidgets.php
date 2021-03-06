<?php

namespace travelsoft\booking;

/**
 * Класс виджетов для расчета цен
 *
 * @author dimabresky
 * @copyright (c) 2017, travelsoft
 */
class CalculationWidgets
{

    /**
     * Виджет расчета для типа цены "Стоимость за номер"
     * @param array $parameters
     * @return array
     */
    public static function priceForRoom(array $parameters): array
    {


        $countPeopleOnMainPlaces = $parameters['allocate']['main']['adults'] + $parameters['allocate']['main']['children'];

        $parameters['allocate'] = array(
            "main" => array(
                "adults" => 0,
                "children" => 0,
                "children_age" => array()
            ),
            'additional' => array(
                "adults" => $parameters['allocate']['additional']['adults'],
                "children" => $parameters['allocate']['additional']['children'],
                "children_age" => $parameters['allocate']['additional']['children_age'],
            ),
            "without_place" => array(
                "adults" => $parameters['allocate']['without_place']['adults'],
                "children" => $parameters['allocate']['without_place']['children'],
                "children_age" => $parameters['allocate']['without_place']['children_age']
            )
        );


        if ($parameters['calc_by_arrival']) {
            // print_r((float) $parameters['prices'][$parameters['id']][0]);
            $price = (float) $parameters['prices'][$parameters['id']][0] * count($parameters['prices'][$parameters['id']]);
        } else {
            $price = (float) array_sum($parameters['prices'][$parameters['id']]);
        }


        $pricesdiscount = 0;
        foreach ($parameters['prices'][$parameters['id']] as $key => $val) {
            if ($parameters['pricesdiscount'][$parameters['id']][$key]['abs'] > 0) {
                $pricesdiscount += $val - $parameters['pricesdiscount'][$parameters['id']][$key]['abs'];
                // var_dump('if discount abs');
                print_r('');
            } elseif ($parameters['pricesdiscount'][$parameters['id']][$key]['percent'] > 0) {
                $pricesdiscount += $val * (100 - $parameters['pricesdiscount'][$parameters['id']][$key]['percent']) / 100;
                // var_dump('elseif discount percent');
                print_r('');
                // print_r('percent');
            } else
                print_r('');
            $pricesdiscount += $val;
        }


        //Фикс расчета цены по дате заезда
        if ($parameters['calc_by_arrival']) {
            // print_r($parameters['prices'][$parameters['id']][0]);
            if ($parameters['pricesdiscount'][$parameters['id']][0]['percent'] > 0) {
                $pricesdiscount = ($parameters['prices'][$parameters['id']][0] * (100 - $parameters['pricesdiscount'][$parameters['id']][0]['percent']) / 100) * (count($parameters['prices'][$parameters['id']]));
                // print_r('percent');
            } elseif ($parameters['pricesdiscount'][$parameters['id']][0]['abs'] > 0) {
                $pricesdiscount = ($parameters['prices'][$parameters['id']][0] - $parameters['pricesdiscount'][$parameters['id']][0]['abs']) * (count($parameters['prices'][$parameters['id']]));
            } else {
                $pricesdiscount = $parameters['prices'][$parameters['id']][0] * (count($parameters['prices'][$parameters['id']]));
            }
        }

        // var_dump((float) $parameters['prices'][$parameters['id']][0]);
        // var_dump($pricesdiscount);
        // var_dump(count($parameters['prices'][$parameters['id']]) - 1);
        print_r('');

        if (@$parameters["share_price"]['flag']) {
            $price = ($price / (float)$parameters["share_price"]["count_main_places"]) * $countPeopleOnMainPlaces;
            $pricesdiscount = ($pricesdiscount / (float)$parameters["share_price"]["count_main_places"]) * $countPeopleOnMainPlaces;
        }

        $result = array(
            "price" => $price,
            "pricesdiscount" => $pricesdiscount,
            "allocate" => $parameters['allocate']
        );

        foreach ($parameters['sub_price_types'] as $id) {

            if (
                ($result['allocate']['additional']['children'] > 0 ||
                    $result['allocate']['additional']['adults'] > 0) &&
                $parameters['price_types'][$id][0]['UF_CALC_WIDGET'] != ''
            ) {

                $localResult = call_user_func_array(
                    str_replace("\\\\", "\\", $parameters['price_types'][$id][0]['UF_CALC_WIDGET']),
                    array(array(
                        'id' => $id,
                        'prices' => $parameters['prices'],
                        'pricesdiscount' => $parameters['pricesdiscount'],
                        'price_types' => $parameters['price_types'],
                        'sub_price_types' => $parameters['sub_price_types'],
                        'allocate' => $parameters['allocate'],
                        'calc_by_arrival' => $parameters['calc_by_arrival']
                    ))
                );

                $result['price'] += $localResult['price'];
                $result['pricesdiscount'] += $localResult['pricesdiscount'];
                $result['allocate'] = $parameters['allocate'] = $localResult['allocate'];
            }
        }
        $used_discount = false;
        foreach ($parameters['pricesdiscount'][$parameters['id']] as $tmpdata)
            if ($tmpdata['abs'] || $tmpdata['percent']) $used_discount = true;
        if (!$used_discount) $result['pricesdiscount'] = $result['price'];
        return $result;


        // print_r("Стоимость за номер взр");
    }

    /**
     * Виджет расчета для типа цены "Стоимость за номер для 1 человека"
     * @param array $parameters
     * @return array
     */
    public static function priceForRoomForOnePerson(array $parameters): array
    {



        $result = array(
            "price" => 0,
            "pricesdiscount" => 0,
            "allocate" => array(
                "main" => array(
                    "adults" => $parameters['allocate']['main']['adults'],
                    "children" => $parameters['allocate']['main']['children'],
                    "children_age" => $parameters['allocate']['main']['children_age']
                ),
                "additional" => array(
                    "adults" => $parameters['allocate']['additional']['adults'],
                    "children" => $parameters['allocate']['additional']['children'],
                    "children_age" => $parameters['allocate']['additional']['children_age']
                ),
                "without_place" => array(
                    "adults" => $parameters['allocate']['without_place']['adults'],
                    "children" => $parameters['allocate']['without_place']['children'],
                    "children_age" => $parameters['allocate']['without_place']['children_age']
                )
            )
        );

        if (
            $result['allocate']['main']['adults'] == 1 &&
            $result['allocate']['additional']['adults'] == 0 &&
            $result['allocate']['main']['children'] == 0 &&
            $result['allocate']['additional']['children'] == 0
        ) {

            if ($parameters['calc_by_arrival']) {
                // print_r((float) $parameters['prices'][$parameters['id']][0] * count($parameters['prices'][$parameters['id']]));
                $result['price'] = (float) $parameters['prices'][$parameters['id']][0] * count($parameters['prices'][$parameters['id']]);
            } else {
                $result['price'] = (float) array_sum($parameters['prices'][$parameters['id']]);
            }
            $pricesdiscount = 0;
            foreach ($parameters['prices'][$parameters['id']] as $key => $val) {
                if ($parameters['pricesdiscount'][$parameters['id']][$key]['abs'] > 0) {
                    $pricesdiscount += $val - $parameters['pricesdiscount'][$parameters['id']][$key]['abs'];
                    print_r('');
                    // var_dump('if discount abs');
                } elseif ($parameters['pricesdiscount'][$parameters['id']][$key]['percent'] > 0) {
                    $pricesdiscount += $val * (100 - $parameters['pricesdiscount'][$parameters['id']][$key]['percent']) / 100;
                    print_r('');
                }
                //Фикс расчета цены по дате заезда
                // elseif($parameters['calc_by_arrival']){
                //     $pricesdiscount = 0;
                // }
                else
                    print_r('');
                $pricesdiscount += $val;
            }

            //Фикс расчета цены по дате заезда
            if ($parameters['calc_by_arrival']) {
                if ($parameters['pricesdiscount'][$parameters['id']][0]['percent'] > 0) {
                    $pricesdiscount = ($parameters['prices'][$parameters['id']][0] * (100 - $parameters['pricesdiscount'][$parameters['id']][0]['percent']) / 100) * (count($parameters['prices'][$parameters['id']]));
                    // print_r('percent');
                } elseif ($parameters['pricesdiscount'][$parameters['id']][0]['abs'] > 0) {
                    $pricesdiscount = ($parameters['prices'][$parameters['id']][0] - $parameters['pricesdiscount'][$parameters['id']][0]['abs']) * (count($parameters['prices'][$parameters['id']]));
                } else {
                    $pricesdiscount = $parameters['prices'][$parameters['id']][0] * (count($parameters['prices'][$parameters['id']]));
                }
            }



            // var_dump('test for 1 person');
            print_r('');
            $result['pricesdiscount'] = $pricesdiscount;

            $result['allocate']['main']['adults'] = 0;
        }
        return $result;

        // print_r("Стоимость за номер для 1 человека взр");
    }

    /**
     * Виджет расчета для типа цены "Стоимость за место (взрослый)"
     * @param array $parameters
     * @return array
     */
    public static function priceForPlaceAdult(array $parameters): array
    {

        // print_r('Взрослый за место');


        $result = array(
            "price" => 0,
            "pricesdiscount" => 0,
            "allocate" => array(
                "main" => array(
                    "adults" => $parameters['allocate']['main']['adults'],
                    "children" => $parameters['allocate']['main']['children'],
                    "children_age" => $parameters['allocate']['main']['children_age']
                ),
                "additional" => array(
                    "adults" => $parameters['allocate']['additional']['adults'],
                    "children" => $parameters['allocate']['additional']['children'],
                    "children_age" => $parameters['allocate']['additional']['children_age']
                ),
                "without_place" => array(
                    "adults" => $parameters['allocate']['without_place']['adults'],
                    "children" => $parameters['allocate']['without_place']['children'],
                    "children_age" => $parameters['allocate']['without_place']['children_age']
                )
            )
        );

        if ($parameters['calc_by_arrival']) {
            $result['price'] += count($parameters['prices'][$parameters['id']]) * $parameters['prices'][$parameters['id']][0] * $parameters['allocate']['main']['adults'];
        } else {

            foreach ($parameters['prices'][$parameters['id']] as $key => $price) {
                $result['price'] += ($price * $parameters['allocate']['main']['adults']);
            }
        }
        foreach ($parameters['prices'][$parameters['id']] as $key => $price) {
            $pricesdiscount = $price;
            if ($parameters['pricesdiscount'][$parameters['id']][$key]['abs'] > 0) {
                $pricesdiscount = $pricesdiscount - $parameters['pricesdiscount'][$parameters['id']][$key]['abs'];
                print_r('');
                // var_dump('if discount abs');
            } elseif ($parameters['pricesdiscount'][$parameters['id']][$key]['percent'] > 0) {
                $pricesdiscount = $pricesdiscount * (100 - $parameters['pricesdiscount'][$parameters['id']][$key]['percent']) / 100;
                // var_dump('if discount abs');
                print_r('');
            }
            //Фикс расчета цены по дате заезда
            // elseif($parameters['calc_by_arrival']){
            //     $pricesdiscount = 0;
            // }
            // var_dump('if discount abs');
            print_r('');
            $result['pricesdiscount'] += ($pricesdiscount * $parameters['allocate']['main']['adults']);
        }

        //Фикс расчета цены по дате заезда
        if ($parameters['calc_by_arrival']) {
            // print_r($parameters['prices'][$parameters['id']][0]);
            if ($parameters['pricesdiscount'][$parameters['id']][0]['percent'] > 0) {
                $pricesdiscount = ($parameters['prices'][$parameters['id']][0] * (100 - $parameters['pricesdiscount'][$parameters['id']][0]['percent']) / 100) * (count($parameters['prices'][$parameters['id']]));
            } elseif ($parameters['pricesdiscount'][$parameters['id']][0]['abs'] > 0) {
                $pricesdiscount = ($parameters['prices'][$parameters['id']][0] - $parameters['pricesdiscount'][$parameters['id']][0]['abs']) * (count($parameters['prices'][$parameters['id']]));
            }
            $result['pricesdiscount'] = ($pricesdiscount * $parameters['allocate']['main']['adults']) * (count($parameters['prices'][$parameters['id']]));
        }

        // print_r($result['pricesdiscount']);


        $parameters['allocate']['main']['adults'] = $result['allocate']['main']['adults'] = 0;

        foreach ($parameters['sub_price_types'] as $id) {

            if ($parameters['price_types'][$id][0]['UF_CALC_WIDGET'] != '') {

                $parameters['id'] = $id;
                $localResult = call_user_func_array(
                    str_replace("\\\\", "\\", $parameters['price_types'][$id][0]['UF_CALC_WIDGET']),
                    array($parameters)
                );

                $result['price'] += $localResult['price'];
                $result['pricesdiscount'] += $localResult['pricesdiscount'];
                $result['allocate'] = $parameters['allocate'] = $localResult['allocate'];
            }
        }

        return $result;
    }

    /**
     * Виджет расчета для типа цены "Стоимость за место (ребенок)"
     * @param array $parameters
     * @return array
     */
    public static function priceForPlaceChild(array $parameters): array
    {

        // print_r("Стоимость за место ребенок");


        $result = array(
            "price" => 0,
            "pricesdiscount" => 0,
            "allocate" => array(
                "main" => array(
                    "adults" => $parameters['allocate']['main']['adults'],
                    "children" => $parameters['allocate']['main']['children'],
                    "children_age" => $parameters['allocate']['main']['children_age']
                ),
                "additional" => array(
                    "adults" => $parameters['allocate']['additional']['adults'],
                    "children" => $parameters['allocate']['additional']['children'],
                    "children_age" => $parameters['allocate']['additional']['children_age']
                ),
                "without_place" => array(
                    "adults" => $parameters['allocate']['without_place']['adults'],
                    "children" => $parameters['allocate']['without_place']['children'],
                    "children_age" => $parameters['allocate']['without_place']['children_age']
                )
            )
        );

        self::_childrenCommonCalculateByAge("main", $parameters, $result);

        return $result;
    }

    /**
     * Виджет расчета для типа цены "Стоимость за дополнительное место (взрослый)"
     * @param array $parameters
     * @return array
     */
    public static function priceForAdditionalPlaceAdult(array $parameters): array
    {

        // print_r("Стоимость за доп место взрослый");



        $result = array(
            "price" => 0,
            "pricesdiscount" => 0,
            "allocate" => array(
                "main" => array(
                    "adults" => $parameters['allocate']['main']['adults'],
                    "children" => $parameters['allocate']['main']['children'],
                    "children_age" => $parameters['allocate']['main']['children_age']
                ),
                "additional" => array(
                    "adults" => $parameters['allocate']['additional']['adults'],
                    "children" => $parameters['allocate']['additional']['children'],
                    "children_age" => $parameters['allocate']['additional']['children_age']
                ),
                "without_place" => array(
                    "adults" => $parameters['allocate']['without_place']['adults'],
                    "children" => $parameters['allocate']['without_place']['children'],
                    "children_age" => $parameters['allocate']['without_place']['children_age']
                )
            )
        );

        if ($result['allocate']['additional']['adults'] > 0 && !empty($parameters['prices'][$parameters['id']])) {

            if ($parameters['calc_by_arrival']) {

                $result['price'] = count($parameters['prices'][$parameters['id']]) * $parameters['prices'][$parameters['id']][0] * $parameters['allocate']['additional']['adults'];
            } else {

                foreach ($parameters['prices'][$parameters['id']] as $key => $price) {

                    $result['price'] += ($price * $parameters['allocate']['additional']['adults']);
                }
            }
            foreach ($parameters['prices'][$parameters['id']] as $key => $price) {

                $pricesdiscount = $price;
                if ($parameters['pricesdiscount'][$parameters['id']][$key]['abs'] > 0) {
                    $pricesdiscount = $pricesdiscount - $parameters['pricesdiscount'][$parameters['id']][$key]['abs'];
                    print_r('');
                    // var_dump('if discount abs');

                } elseif ($parameters['pricesdiscount'][$parameters['id']][$key]['percent'] > 0) {
                    $pricesdiscount = $pricesdiscount * (100 - $parameters['pricesdiscount'][$parameters['id']][$key]['percent']) / 100;
                    print_r('');
                    // var_dump('if discount abs');
                }
                // var_dump($pricesdiscount);
                // var_dump('if discount abs');
                print_r('');
                $result['pricesdiscount'] += ($pricesdiscount * $parameters['allocate']['additional']['adults']);
            }


            //Фикс расчета цены по дате заезда
            if ($parameters['calc_by_arrival']) {
                $pricesdiscount = 0;
                foreach ($parameters['prices'][$parameters['id']] as $key => $price) {
                    if ($parameters['pricesdiscount'][$parameters['id']][0]['percent'] > 0) {
                        $pricesdiscount += ($parameters['prices'][$parameters['id']][0] * (100 - $parameters['pricesdiscount'][$parameters['id']][0]['percent']) / 100) * (count($parameters['prices'][$parameters['id']]));
                    } elseif ($parameters['pricesdiscount'][$parameters['id']][0]['abs'] > 0) {
                        $pricesdiscount += ($parameters['prices'][$parameters['id']][0] - $parameters['pricesdiscount'][$parameters['id']][0]['abs']) * (count($parameters['prices'][$parameters['id']]));
                    } else {
                        $pricesdiscount += $parameters['prices'][$parameters['id']][0] * (count($parameters['prices'][$parameters['id']]));
                        $result['pricesdiscount'] = $pricesdiscount;
                    }
                }
            }


            print_r('');
            $result['allocate']['additional']['adults'] = 0;
        }

        return $result;
    }

    /**
     * Виджет расчета для типа цены "Стоимость за дополнительное место (ребенок)"
     * @param array $parameters
     * @return array
     */
    public static function priceForAdditionalPlaceChild(array $parameters): array
    {

        // print_r("Стоимость за доп место ребенок");


        $result = array(
            "price" => 0,
            "pricesdiscount" => 0,
            "allocate" => array(
                "main" => array(
                    "adults" => $parameters['allocate']['main']['adults'],
                    "children" => $parameters['allocate']['main']['children'],
                    "children_age" => $parameters['allocate']['main']['children_age']
                ),
                "additional" => array(
                    "adults" => $parameters['allocate']['additional']['adults'],
                    "children" => $parameters['allocate']['additional']['children'],
                    "children_age" => $parameters['allocate']['additional']['children_age']
                ),
                "without_place" => array(
                    "adults" => $parameters['allocate']['without_place']['adults'],
                    "children" => $parameters['allocate']['without_place']['children'],
                    "children_age" => $parameters['allocate']['without_place']['children_age']
                )
            )
        );

        self::_childrenCommonCalculateByAge("additional", $parameters, $result);

        return $result;
    }

    /**
     * Виджет расчета для типа цены "Стоимость ребенок без места"
     * @param array $parameters
     * @return array
     */
    public static function priceForChildWithoutPlace(array $parameters): array
    {
        // print_r("Стоимость за ребенок без места");


        $result = array(
            "price" => 0,
            "pricesdiscount" => 0,
            "allocate" => array(
                "main" => array(
                    "adults" => $parameters['allocate']['main']['adults'],
                    "children" => $parameters['allocate']['main']['children'],
                    "children_age" => $parameters['allocate']['main']['children_age']
                ),
                "additional" => array(
                    "adults" => $parameters['allocate']['additional']['adults'],
                    "children" => $parameters['allocate']['additional']['children'],
                    "children_age" => $parameters['allocate']['additional']['children_age']
                ),
                "without_place" => array(
                    "adults" => $parameters['allocate']['without_place']['adults'],
                    "children" => $parameters['allocate']['without_place']['children'],
                    "children_age" => $parameters['allocate']['without_place']['children_age']
                )
            )
        );

        self::_childrenCommonCalculateByAge("without_place", $parameters, $result);
        return $result;
    }

    /**
     * Производит расчет стоимости по детям
     * @param string $placeType
     * @param array $parameters
     * @param array $result
     */
    protected static function _childrenCommonCalculateByAge(string $placeType, array &$parameters, array &$result)
    {

        // print_r("Стоимость по детям");



        if ($result['allocate'][$placeType]['children'] > 0 && !empty($parameters['prices'][$parameters['id']])) {


            $children = $result['allocate'][$placeType]['children'];
            $ages = $result['allocate'][$placeType]['children_age'];

            for ($i = 0; $i < $children; $i++) {

                if (
                    $ages[$i] >= $parameters['price_types'][$parameters['id']][0]['UF_AGE_MIN'] &&
                    $ages[$i] <= $parameters['price_types'][$parameters['id']][0]['UF_AGE_MAX']
                ) {
                    if ($parameters['calc_by_arrival']) {

                        $result['price'] += $parameters['prices'][$parameters['id']][0] * count($parameters['prices'][$parameters['id']]);
                    } else {

                        foreach ($parameters['prices'][$parameters['id']] as $key => $price) {

                            $result['price'] += $price;
                        }
                    }

                    foreach ($parameters['prices'][$parameters['id']] as $key => $price) {

                        $pricesdiscount = $price;
                        if ($parameters['pricesdiscount'][$parameters['id']][$key]['abs'] > 0) {
                            $pricesdiscount = $pricesdiscount - $parameters['pricesdiscount'][$parameters['id']][$key]['abs'];
                            print_r('');
                            // var_dump('if discount abs');
                        } elseif ($parameters['pricesdiscount'][$parameters['id']][$key]['percent'] > 0) {
                            $pricesdiscount = $pricesdiscount * (100 - $parameters['pricesdiscount'][$parameters['id']][$key]['percent']) / 100;
                            print_r('');
                            // var_dump('if discount abs');
                        }

                        print_r('');
                        $result['pricesdiscount'] += $pricesdiscount;
                    }


                    //Фикс расчета цены по дате заезда
                    if ($parameters['calc_by_arrival']) {
                        $pricesdiscount = 0;
                        foreach ($parameters['prices'][$parameters['id']] as $key => $price) {
                            if ($parameters['pricesdiscount'][$parameters['id']][0]['percent'] > 0) {
                                $pricesdiscount += ($parameters['prices'][$parameters['id']][0] * (100 - $parameters['pricesdiscount'][$parameters['id']][0]['percent']) / 100) * (count($parameters['prices'][$parameters['id']]));
                            } elseif ($parameters['pricesdiscount'][$parameters['id']][0]['abs'] > 0) {
                                $pricesdiscount += ($parameters['prices'][$parameters['id']][0] - $parameters['pricesdiscount'][$parameters['id']][0]['abs']) * (count($parameters['prices'][$parameters['id']]));
                            } else {
                                $pricesdiscount += $parameters['prices'][$parameters['id']][0] * (count($parameters['prices'][$parameters['id']]));
                                $result['pricesdiscount'] = $pricesdiscount;
                            }
                        }
                    }

                    // print_r(count($parameters['prices'][$parameters['id']]));


                    $result['allocate'][$placeType]['children']--;
                    unset($result['allocate'][$placeType]['children_age'][$i]);
                }
            }
            $result['allocate'][$placeType]['children_age'] = array_values($result['allocate'][$placeType]['children_age']);
        }
    }
}
