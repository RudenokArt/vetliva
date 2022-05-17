<?php 
/**
 * 
 */
class B24_Deals extends B24_Leads {

  // function __construct(argument)  {
  //   // code...
  // }

  function getCrmBookingDealList ($filter=[], $select=['UF_*', '*',]) {
    $str = $this->restApiRequest('crm.deal.list', [
      'filter' => $filter,
      'select'=> $select,
    ]);
    $arr = json_decode($str, true);
    return $arr;
  }

  function bookingDealUpdate ($arFields) {
    foreach ($arFields as $key => $value) {
      if ($key == 'ID') {
        $arFields[$key] = $value['ID'];
      } else {
        $arFields[$key] = $value['VALUE'];
      }
    }
    $deal_id = $this->getCrmBookingDealList(['UF_CRM_1652514403572'=>$arFields['ID']])['result'][0]['ID'];
    $arFields = $this->bookingFieldsConstruct($arFields);
    file_put_contents($_SERVER['DOCUMENT_ROOT'].'/test.json', json_encode($arFields));
    $this->restApiRequest('crm.deal.update', [
      'id' => $deal_id,
      // 'fields' => ['TITLE' => 'Бронирование на VETLIVA - test332',],
      'fields' => $arFields,
      
      // 'UF_CRM_1542117473'=> $arFields['UF_DOGOVOR_CODE'].'22222222222',

    ]);
  }

  function bookingFieldsNormalize ($arFields) {
    $hotel_src = new InfoBlock();
    $city_src = new InfoBlock();
    $country_src = new InfoBlock();
    $arFields['UF_SERVICE'] = json_decode($arFields['UF_SERVICE_JSON'],true);
    $arFields['UF_BUYER_INFO'] = json_decode($arFields['UF_BUYER_INFO_JSON'],true);
    $arFields['UF_TOURISTS'] = json_decode($arFields['UF_TOURISTS_JSON'],true);
    $rsUser = CUser::GetByID($arFields['UF_SERVICE']['partnerId']);
    $arFields['company'] = $rsUser->Fetch();
    $hotel = $hotel_src->getItemsList([],['ID'=>$arFields['UF_IBLOCK_ELEMENT_ID']],false, false, ['ID','NAME'])[0];
    $city = $city_src->getItemsList([],['ID'=>$arFields['UF_SERVICE']['cityId']], false, false, ['ID','NAME'])[0];
    $country = $country_src->getItemsList([],['ID'=>$arFields['UF_SERVICE']['countryId']],false, false, ['ID','NAME'])[0];
    $arFields['CONTENT'] = '
    Стоимость: '.$arFields['UF_SERVICE']['brutto'].'; 
    Валюта: '.$arFields['UF_SERVICE']['currency'].'; 
    Дата начала: '.$arFields['UF_SERVICE']['dateBegin'].'; 
    Дата окончания: '.$arFields['UF_SERVICE']['dateEnd'].'; 
    Количество: '.$arFields['UF_SERVICE']['nmen'].'; 
    Email: '.$arFields['UF_BUYER_INFO']['email'].'; 
    Тел.: '.$arFields['UF_BUYER_INFO']['phone'].'; 
    Язык: '.$arFields['UF_BUYER_INFO']['language'].'; 
    ID поставщика на vetliva.by: '.$arFields['UF_SERVICE']['partnerId'].'; 
    Поставщик: '.$arFields['company']['NAME'].' '.$arFields['company']['LAST_NAME'].' '.$arFields['company']['EMAIL'].'; 
    ID услуги на vetliva.by: '.$arFields['UF_SERVICE']['parts']['roomId'].'; 
    Услуга: '.$arFields['UF_SERVICE_NAME'].'
    ID отеля на vetliva.by: '.$arFields['UF_IBLOCK_ELEMENT_ID'].'; 
    Отель: '.$hotel['NAME'].'; 
    Договор: '.$arFields['UF_DOGOVOR_CODE'].'; 
    ID страны на vetliva.by: '.$arFields['UF_SERVICE']['countryId'].'; 
    Страна: '.$country['NAME'].'; 
    ID города на vetliva.by: '.$arFields['UF_SERVICE']['cityId'].'; 
    Город: '.$city['NAME'].'; Туристы: ';
    foreach ($arFields['UF_TOURISTS'] as $key => $value) {
      $arFields['CONTENT'] = $arFields['CONTENT'].
      $value['first_name'].' '.$value['last_name'].
      ', Пасспорт: '.$value['passport_num'].', д.р.: '.$value['birth_date'].
      ', Гражданство: '.$value['citizenship'].', пол: '.$value['sex'].';';
    }
    $arFields['tourists_arr'] = [];
    foreach ($arFields['UF_TOURISTS'] as $key => $value) {
      array_push($arFields['tourists_arr'], 
        $value['first_name'].' '.$value['last_name'].', Пасспорт: '.$value['passport_num'].', 
        д.р.: '.$value['birth_date']. ', Гражданство: '.$value['citizenship'].', пол: '.$value['sex']);
    }
    return $arFields;
  }

  function bookingFieldsConstruct ($arFields) {
    $arFields = $this->bookingFieldsNormalize($arFields);
    return [
        'TITLE' => 'Бронирование на VETLIVA - test',
        'STAGE_ID' => 'C4:NEW',
        'STAGE_SEMANTIC_ID' => 'S',
        'TYPE_ID' => 'SERVICES',
        'ASSIGNED_BY_ID' => 27352,
        'CREATED_BY_ID' => 27352,
        'SOURCE_ID' => 9,
        'CATEGORY_ID' => 1,
        'IS_NEW' => 'Y',
        'OPENED' => 'Y',
        'OPPORTUNITY' => $arFields['UF_SERVICE']['brutto'],
        'CURRENCY_ID' => $arFields['UF_SERVICE']['currency'],
        'BEGINDATE' => $arFields['UF_SERVICE']['dateBegin'],
        'CLOSEDATE' => $arFields['UF_SERVICE']['dateEnd'],
        'UF_CRM_5D36B393C64A2' => $arFields['UF_SERVICE']['nmen'],
        'UF_CRM_5D36B393D5BAB'=> $arFields['UF_BUYER_INFO']['phone'],
        'UF_CRM_60C086FF7B089' => $arFields['UF_BUYER_INFO']['email'],
        'UF_CRM_610D4FF176FB9' => $arFields['UF_BUYER_INFO']['language'],
        'UF_CRM_1542117473'=> $arFields['UF_DOGOVOR_CODE'],
        'UF_CRM_610D4FF12DEF5' => $arFields['tourists_arr'],
        'UF_CRM_1652792126355' => $arFields['CONTENT'],
        'UF_CRM_1652514403572' => $arFields['ID'],
      ];
  }

  function newBookingDealAdd ($arFields) {
    file_put_contents($_SERVER['DOCUMENT_ROOT'].'/test.json', json_encode($arFields));
    $arFields = $this->bookingFieldsConstruct($arFields);
    $this->restApiRequest('crm.deal.add', [
      'fields' => $arFields,
    ]);
  }

}


?>