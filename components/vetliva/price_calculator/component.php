<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/**
 * 
 */
class PriceCalculator {

  function __construct($arParams)  {
    $this->arParams = $arParams;
    $this->arResult['period'] = ($this->arParams['DATE_TO']-$this->arParams['DATE_FROM'])/60/60/24;
    $this->arResult['booking_placement'] = $this->getBookingEntity();
    $this->arResult['items'] = $this->filterBookingServices();
    $this->arResult['rates_category_name'] = [];
    $this->arResult['total_arr'] = $this->getTotalArr();
    $this->arResult['test'] = $this->getDailyPersonalTotal(0,0);
  }

  function getTotalArr () {
    $arr = [];
    foreach ($this->arResult['items'] as $key => $value) {
      $arr[$key]['service'] = $this->arResult['items'][$key]['UF_NAME'];
      $arr[$key]['price_list'] = $this->getItemTotal($key);
    }
    return $arr;
  }

  function getItemTotal ($item) {
    $arr = $this->arResult['items'][$item];
    $total = [];
    if ($this->arResult['period'] > 1 or !$this->arResult['booking_placement']['CALC_BY_DAY']) { // расчет по ночам
      unset($arr['quotas'][sizeof($arr['quotas'])-1]);
    }
    foreach ($arr['quotas'] as $key => $value) {
      if ($this->arResult['booking_placement']['CALC_BY_ARRIVAL'] == 'Y') {
        $quota = 0; // расчет по дате заезда
      } else {
        $quota = $key;
      }
      $total[$key] = $this->getDailyTotal($item, $quota)['total'];
    }
    $amount = [];
    foreach ($total as $key => $value) {
      foreach ($value as $key1 => $value1) {
        foreach ($value1 as $key2 => $value2) {
          $amount[$key1]['~'.$key2] = $this->arResult['rates_category_name'][$key2];
          $amount[$key1][$key2] = $amount[$key1][$key2]+$total[$key][$key1][$key2];
        }
      }
    }
    return $amount;
  }

  function getDailyPersonalTotal ($service, $quota) {
    $total = [];
    $arr = $this->getBookingPrices($this->arResult['items'][$service],
      $this->arResult['items'][$service]['quotas'][$quota]['UF_DATE'] );
    $adults_arr = $this->arParams['SPECIFYING_RATES_ADULTS'];
    foreach ($arr as $key => $value) {
      foreach ($adults_arr as $key1 => $value1) {
        if (
          $key1 < $value['UF_PLACES_MAIN'] 
          and 
          $adults_arr[$key1] == $value['rates_category']['ID']
          and 
          $value['rate']['UF_FOR_ROOM'] and !$value['rate']['UF_FOR_PLACE'] and $value['rate']['UF_MAIN']
          and 
          !$value['rate']['UF_AGE_MIN'] and !$value['rate']['UF_AGE_MIN']
        ) {
          $this->dailyTotalCalcHelper($total, $value);
          if ($value['UF_PLACES_MAIN'] > 1) {
            $total[$value['currency']][$value['rates_category']['ID']] = 
            $total[$value['currency']][$value['rates_category']['ID']] / $value['UF_PLACES_MAIN'];
          }
        }
        elseif (
          $key1 >= $value['UF_PLACES_MAIN']
          and
          $adults_arr[$key1] == $value['rates_category']['ID']
          and 
          $value['rate']['UF_FOR_ROOM'] and $value['rate']['UF_FOR_PLACE'] and !$value['rate']['UF_MAIN']
          and 
          !$value['rate']['UF_AGE_MIN'] and !$value['rate']['UF_AGE_MIN']
        ) {
          $this->dailyTotalCalcHelper($total, $value);
        }
      }
      foreach ($this->arParams['CHILDREN_AGE'] as $key1 => $value1) {
        if (
          $key1 + $this->arParams['ADULTS'] < $value['UF_PLACES_MAIN'] 
          and 
          $this->arParams['SPECIFYING_RATES_CHILDREN'][$key1] == $value['rates_category']['ID']
          and 
          $value['rate']['UF_FOR_ROOM'] and $value['rate']['UF_FOR_PLACE'] and !$value['rate']['UF_MAIN']
          and 
          $value['rate']['UF_AGE_MIN'] <= $value1 and $value['rate']['UF_AGE_MAX'] >= $value1
        ) {
          $this->dailyTotalCalcHelper($total, $value);
          if ($value['UF_PLACES_MAIN'] > 1) {
            $total[$value['currency']][$value['rates_category']['ID']] = 
            $total[$value['currency']][$value['rates_category']['ID']] / $value['UF_PLACES_MAIN'];
          }
        }
        elseif (
          $key1 + $this->arParams['ADULTS'] >= $value['UF_PLACES_MAIN']
          and
          $this->arParams['SPECIFYING_RATES_CHILDREN'][$key1] == $value['rates_category']['ID']
          and 
          $value['rate']['UF_FOR_ROOM'] and $value['rate']['UF_FOR_PLACE'] and !$value['rate']['UF_MAIN']
          and 
          $value['rate']['UF_AGE_MIN'] <= $value1 and $value['rate']['UF_AGE_MAX'] >= $value1
        ) {
          $this->dailyTotalCalcHelper($total, $value);
        }

      }
    }
    return [
      'total' => $total, 
      'test' => $adults_arr,
      'amount' => array_sum($total['BYN']),
      'prices' => $arr,
    ];
  }

  function getDailyTotal ($service, $quota) {
    $total = [];
    $price_for_room_flag = true;
    $arr = $this->getBookingPrices($this->arResult['items'][$service],
      $this->arResult['items'][$service]['quotas'][$quota]['UF_DATE'] );
    foreach ($arr as $key => $value) {
      $this->arResult['rates_category_name'][$value['rates_category']['ID']] = $value['rates_category']['UF_NAME'];
      // не более одного человека в номере
      if (
        $this->arParams['ADULTS'] + $this->arParams['CHILDREN'] == 1
        and 
        (($value['rate']['UF_FOR_ROOM'] and $value['rate']['UF_FOR_PLACE'] and $value['rate']['UF_MAIN'])
          or
          (!$value['rate']['UF_FOR_ROOM'] and $value['rate']['UF_FOR_PLACE'] and $value['rate']['UF_MAIN'])) 
      ) {
        $this->dailyTotalCalcHelper($total, $value);
        $price_for_room_flag = false; 
      }
      // всего человек не более количества основных мест в номере
      elseif ( 
        $this->arParams['ADULTS'] + $this->arParams['CHILDREN'] <= $value['UF_PLACES_MAIN'] 
        and
        $this->arParams['ADULTS'] + $this->arParams['CHILDREN'] != 1
      ) { 
      // Стоимость за номер
        if ($value['rate']['UF_FOR_ROOM'] and !$value['rate']['UF_FOR_PLACE'] and $value['rate']['UF_MAIN']) {
          $this->dailyTotalCalcHelper($total, $value);
        }
        // Стоимость за место
        elseif (!$value['rate']['UF_FOR_ROOM'] and $value['rate']['UF_FOR_PLACE'] and $value['rate']['UF_MAIN']) {
          for ($i=0; $i < $this->arParams['ADULTS']; $i++) { 
            $this->dailyTotalCalcHelper($total, $value);
          }
        }
      }
      // Всего человек больше чем основных мест в номере
      elseif ( 
        $this->arParams['ADULTS'] + $this->arParams['CHILDREN'] > $value['UF_PLACES_MAIN'] 
      ) {
        // Стоимость за номер
        if ($value['rate']['UF_FOR_ROOM'] and !$value['rate']['UF_FOR_PLACE'] and $value['rate']['UF_MAIN']) {
          $this->dailyTotalCalcHelper($total, $value);
        }
        // Стоимость за место
        elseif (!$value['rate']['UF_FOR_ROOM'] and $value['rate']['UF_FOR_PLACE'] and $value['rate']['UF_MAIN']) {
          for ($i=0; $i < $value['UF_PLACES_MAIN']; $i++) { 
            $this->dailyTotalCalcHelper($total, $value);
          }
        }
        // взрослых больше чем основных мест в номере
        if ( 
          $this->arParams['ADULTS'] > $value['UF_PLACES_MAIN']
          and
          $value['rate']['UF_FOR_ROOM'] and $value['rate']['UF_FOR_PLACE'] and !$value['rate']['UF_MAIN']
          and 
          !$value['rate']['UF_AGE_MIN'] and !$value['rate']['UF_AGE_MIN']
        ) {
          $adults_diff = $this->arParams['ADULTS'] - $value['UF_PLACES_MAIN'];
          for ($i=0; $i < $adults_diff ; $i++) { 
            $this->dailyTotalCalcHelper($total, $value);
          }
        } 
        // детей  больше чем основных мест в номере
        elseif (($this->arParams['ADULTS'] + $this->arParams['CHILDREN']) > $value['UF_PLACES_MAIN']) {
          $childrens_diff = $this->arParams['ADULTS'] + $this->arParams['CHILDREN'] - $value['UF_PLACES_MAIN'];
          $childrens_arr = $this->arParams['CHILDREN_AGE'];
          asort($childrens_arr);
          $childrens_arr = array_slice($childrens_arr, 0, $childrens_diff);
          foreach ($childrens_arr as $key1 => $value1) {
            if (
              $value['rate']['UF_FOR_ROOM'] and $value['rate']['UF_FOR_PLACE'] and !$value['rate']['UF_MAIN']
              and $value['rate']['UF_AGE_MIN'] <= $value1 and $value['rate']['UF_AGE_MAX'] >= $value1
            ) {
              $this->dailyTotalCalcHelper($total, $value);
            }
          }
        }
      }
    }
    foreach ($arr as $key => $value) {
      // не более одного человека в номере если не указана цена за номер для одного человека
      if (
        $price_for_room_flag
        and
        $this->arParams['ADULTS'] + $this->arParams['CHILDREN'] == 1
        and
        $total[$value['currency']][$value['rates_category']['ID']]<1
      ) {
        if ($value['rate']['UF_FOR_ROOM'] and !$value['rate']['UF_FOR_PLACE'] and $value['rate']['UF_MAIN']) {
          $this->dailyTotalCalcHelper($total, $value);
        }
      }
    }
    return ['total' => $total, 'prices' => $arr];
    // return $total;
  }

  function dailyTotalCalcHelper (&$total, $value) {
    $total[$value['currency']][$value['rates_category']['ID']] = 
    $total[$value['currency']][$value['rates_category']['ID']] + $value['UF_GROSS'];
  }

  function getBookingRate ($item_id) {
    $arr = $this->getHighloadBlockItems('ts_rates', [
      'ID' => $item_id,
      'UF_ACTIVE' => true,
    ], 
    [
      'UF_NAME',
      'UF_ACTIVE',
      'UF_DATE_ACTIVE_FROM',
      'UF_DATE_ACTIVE_TO',
      'UF_FOR_MDEXC', // Использовать при расчете стоимости за многодневный тур
      'UF_FOR_ODEXC', // Использовать при расчете стоимости за однодневный тур
      'UF_FOR_ROOM',
      'UF_FOR_PLACE',
      'UF_MAIN',
      'UF_AGE_MIN',
      'UF_AGE_MAX',
      'UF_ADCHP', // ип цены за дополнительное место ребенка
      'UF_MCHP', // Тип цены за основное место ребенка
    ]
  );
    return $arr[0];
  }

  function ratesPluseCategory ($item_id) {
    $arr = $this->getHighloadBlockItems('ts_rates_pluse_category', [
      'ID' => $item_id,
    ])[0];
    return ['UF_RATE_CATEGORY_ID' => $arr['UF_RATE_CATEGORY_ID'], 'UF_RATE_ID' => $arr['UF_RATE_ID']];
  }

  function getBookingPrices ($service, $quota_date) {
    if (!$service or !$quota_date) {
      return 'N';
    }
    if ($this->arResult['booking_placement']['IBLOCK_CODE']=='sanatorium') {
      $filter =[
        'UF_SERVICE_ID' => $service['ID'],
        'UF_DATE' => $quota_date,
        'UF_NO_ARRIVALS' => false,
        ['LOGIC' => 'OR',[
          'UF_LIFE_PERIOD' => $this->arResult['period'],
        ],[
          'UF_LIFE_PERIOD' => '',
        ]]
      ];
    } else {
      $filter =[
        'UF_SERVICE_ID' => $service['ID'],
        'UF_DATE' => $quota_date,
      ] ;
    }

    $arr = $this->getHighloadBlockItems('ts_prices', $filter, 
      [
        'UF_GROSS',
        'UF_PTPR_ID',
        'UF_DISCOUNT_PERCENT',
        'UF_DISCOUNT_ABS',
        'UF_LIFE_PERIOD',
      ]
    );
    foreach ($arr as $key => $value) {
      $arr[$key]['UF_PEOPLE'] = $service['UF_PEOPLE'];
      $arr[$key]['UF_ADULTS'] = $service['UF_ADULTS'];
      $arr[$key]['UF_CHILDREN'] = $service['UF_CHILDREN'];
      $arr[$key]['UF_MIN_PEOPLE'] = $service['UF_MIN_PEOPLE'];
      $arr[$key]['UF_PLACES_MAIN'] = $service['UF_PLACES_MAIN'];
    }
    return $arr;
  }

  function getBookingEntity () {
    $select = [
      'ID',
      'NAME',
      'IBLOCK_CODE',
      'PROPERTY_740',
    ];
    $calc_by_arrival = 'PROPERTY_740_VALUE';
    if ($this->arResult['booking_placement']['IBLOCK_CODE'] == 'sanatorium') {
     $select = [
      'ID',
      'NAME',
      'IBLOCK_CODE',
      'PROPERTY_738',
    ];
    $calc_by_arrival = 'PROPERTY_738_VALUE';
  }
  $src = CIBlockElement::GetList([],[
    'ID' => $this->arParams['IBLOCK_ELEMENT_ID'],
  ], false, false, [
    'ID',
    'NAME',
    'IBLOCK_ID',
    'IBLOCK_CODE',
  ]);
  $arr = $src->Fetch();
  if ($arr['IBLOCK_CODE'] == 'sanatorium') {
    $calc_by_arrival_id = 738;
    $calc_by_days_id = 737;
  } else {
    $calc_by_arrival_id = 740;
    $calc_by_days_id = 739;
  }
  $res_calc_by_arrival = CIBlockElement::GetProperty($arr['IBLOCK_ID'], $arr['ID'], [], ['ID'=>$calc_by_arrival_id]);
  $calc_by_arrival = $res_calc_by_arrival->Fetch();
  $res_calc_by_days = CIBlockElement::GetProperty($arr['IBLOCK_ID'], $arr['ID'], [], ['ID'=>$calc_by_days_id]);
  $calc_by_days = $res_calc_by_days->Fetch();
  $arr['CALC_BY_ARRIVAL'] = $calc_by_arrival['VALUE_ENUM'];
  $arr['CALC_BY_DAY'] = $calc_by_days['VALUE_ENUM'];
  return $arr;
}

function getBookingCurrency ($currency_id) {
  $currency_res = CIBlockElement::GetList([],[
    'IBLOCK_CODE'=>'currency',
    'ID'=>$currency_id,
  ], false, false, ['ID', 'NAME']);
  $currency_item = $currency_res->Fetch();
  return $currency_item['NAME'];
}


function getRatesCategory ($item_id) {
  return $this->getHighloadBlockItems('ts_rates_category', [
    'ID'=>$item_id,
  ],[
    'ID',
    'UF_NAME',
    'UF_CURRENCY_ID',
      // 'UF_ADULTS',
      // 'UF_PEOPLE',
      // 'UF_AGE_CAT_1_MIN',
      // 'UF_AGE_CAT_1_MAX',
      // 'UF_AGE_CAT_2_MIN',
      // 'UF_AGE_CAT_2_MAX',
      // 'UF_AGE_CAT_3_MIN',
      // 'UF_AGE_CAT_3_MAX',
    'UF_FOR_PLACE',
    'UF_MAIN_PLACES',
    'UF_ADD_PLACES',
    'UF_MIN_PEOPLE',
    'UF_DISCOUNT',
    'UF_TYPE_TARIF',
    'UF_BR_PRICES',
    'UF_RF_PRICES',
    'UF_EU_PRICES',
    'UF_DISCOUNT_BY_DAYS',
  ])[0];
}

function filterBookingServices () {
  $arr = $this->getBookingQuotas();
  $services = [];
  foreach ($arr as $key => $value) {
    if (sizeof($value['quotas']) >= $this->arResult['period']) {
      array_push($services, $value);
    }
  }
  return $services;
}

function getBookingQuotas () {
  $arr = $this->getBookingServices();
  foreach ($arr as $key => $value) {
    $filter = [
      'UF_SERVICE_ID' => $value['ID'],
      '>=UF_DATE' => $this->arParams['DATE_FROM'],
      '<=UF_DATE' => $this->arParams['DATE_TO'],
      'UF_STOP' => false,
    ];
    $arr[$key]['quotas'] = $this->getHighloadBlockItems('ts_quotas', $filter);
  }
  return $arr;
}

function getBookingServices () {
  $filter = [
    'UF_IBLOCK_ELEMENT_ID' => $this->arParams['IBLOCK_ELEMENT_ID'],
    '>=UF_ADULTS' => $this->arParams['ADULTS'],
    '>=UF_CHILDREN' => $this->arParams['CHILDREN'],
    '<=UF_MIN_PEOPLE' => $this->arParams['ADULTS'] + $this->arParams['CHILDREN'],
    '>=UF_PEOPLE' => $this->arParams['ADULTS'] + $this->arParams['CHILDREN'],
  ];
  return $this->getHighloadBlockItems('ts_services', $filter, 
    [
      'ID',
      'UF_NAME',
      'UF_USER_ID',
      'UF_SERVICE_TYPE_NAME',
      'UF_PEOPLE',
      'UF_ADULTS',
      'UF_CHILDREN',
      'UF_MIN_PEOPLE',
      'UF_PLACES_MAIN',
    ]
  );
}

function getBookingPlacement () {
  $src = CIBlockElement::GetList([],[
    'IBLOCK_TYPE' => 'Dictionaries',
    'ID' => $this->arParams['IBLOCK_ELEMENT_ID'],
  ],false, false,[
    'ID',
    'IBLOCK_ID',
    'NAME',
  ]);
  $arr = $src->Fetch();
  $scr_prop = CIBlockElement::GetProperty($arr['IBLOCK_ID'], $arr['ID'], [], ['CODE' => 'CALC_BY_DAY']);
  $arr['CALC_BY_DAY'] = $scr_prop->Fetch()['VALUE'];
  return  $arr;
}

function getHighloadBlockItems ($table_name, $filter=[], $select=['*','UF_*']) {
  $hl = \Bitrix\Highloadblock\HighloadBlockTable::getList([
    'filter'=>['TABLE_NAME' => $table_name,],
  ]);
  $highloadblock=$hl->Fetch();
  $items = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($highloadblock);
  $entity_data_class = $items->getDataClass();
  $rsData = $entity_data_class::getList([
    'filter'=>$filter,
    'select' => $select,
  ]);
  $arr = [];
  foreach ($rsData as $key => $value) {
    if ($table_name == 'ts_prices') {
      $value['rates_pluse_category'] = $this->ratesPluseCategory($value['UF_PTPR_ID']);
      $value['rates_category'] = $this->getRatesCategory($value['rates_pluse_category']['UF_RATE_CATEGORY_ID']);
      $value['currency'] = $this->getBookingCurrency($value['rates_category']['UF_CURRENCY_ID']);
      $value['rate'] = $this->getBookingRate($value['rates_pluse_category']['UF_RATE_ID']);
      array_push($arr, $value);
    } elseif ($table_name == 'ts_quotas') {
      $value['date'] = ConvertTimeStamp($value['UF_DATE']);
      $check_quotes = $value['UF_QUOTE'] - $value['UF_SOLD_NUMBER'];
      if ($check_quotes > 0) {
        array_push($arr, $value);
      }
    } else {
      array_push($arr, $value);
    }
  }
  return $arr;
}

}



$arResult = (new PriceCalculator($arParams))->arResult;
$this->IncludeComponentTemplate();
?>