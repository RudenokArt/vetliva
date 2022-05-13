<?php 
/**
 * 
 */
class B24_Deals extends B24_Leads {

  // function __construct(argument)  {
  //   // code...
  // }

  function getCrmBookingDealList ($filter=[]) {
    $str = $this->restApiRequest('crm.deal.list', [
      'filter' => $filter,
      'select'=> ['UF_*', '*',]
    ]);
    $arr = json_decode($str, true);
    return $arr;
  }

  function newBookingDealAdd ($arFields) {
    $hotel_src = new InfoBlock();
    $city_src = new InfoBlock();
    $country_src = new InfoBlock();
    $arFields['UF_SERVICE'] = json_decode($arFields['UF_SERVICE_JSON'],true);
    $arFields['UF_BUYER_INFO'] = json_decode($arFields['UF_BUYER_INFO_JSON'],true);
    $arFields['UF_TOURISTS'] = json_decode($arFields['UF_TOURISTS_JSON'],true);
    $rsUser = CUser::GetByID($arFields['UF_SERVICE']['partnerId']);
    $arFields['company'] = $rsUser->Fetch();
    $arFields['COMMENTS'] = '
    ID поставщика на vetliva.by: '.$arFields['UF_SERVICE']['partnerId'].'<br>
    Поставщик: '.$arFields['company']['NAME'].' '.$arFields['company']['LAST_NAME'].' '.$arFields['company']['EMAIL'].'<br>
    ID услуги на vetliva.by: '.$arFields['UF_SERVICE']['parts']['roomId'].'<br>
    Услуга: '.$arFields['UF_SERVICE_NAME'].'<br>
    ID отеля на vetliva.by: '.$arFields['UF_IBLOCK_ELEMENT_ID'].'<br>
    Отель: '.$hotel_src->getItemsList([],['ID'=>$arFields['UF_IBLOCK_ELEMENT_ID']],false, false, ['ID','NAME'])[0]['NAME'].'<br>
    Договор: '.$arFields['UF_DOGOVOR_CODE'].'<br>
    Дата начала: '.$arFields['UF_SERVICE']['dateBegin'].'<br>
    Дата окончания: '.$arFields['UF_SERVICE']['dateEnd'].'<br>
    Количество: '.$arFields['UF_SERVICE']['nmen'].'<br>
    Стоимость: '.$arFields['UF_SERVICE']['brutto'].'<br>
    Валюта: '.$arFields['UF_SERVICE']['currency'].'<br>
    ID страны на vetliva.by: '.$arFields['UF_SERVICE']['countryId'].'<br>
    Страна: '.$country_src->getItemsList([],['ID'=>$arFields['UF_SERVICE']['countryId']],false, false, ['ID','NAME'])[0]['NAME'].'<br>
    ID города на vetliva.by: '.$arFields['UF_SERVICE']['cityId'].'<br>
    Город: '.$city_src->getItemsList([],['ID'=>$arFields['UF_SERVICE']['cityId']], false, false, ['ID','NAME'])[0]['NAME'].'<br>
    Email: '.$arFields['UF_BUYER_INFO']['email'].'<br>
    Тел.: '.$arFields['UF_BUYER_INFO']['phone'].'<br>
    Язык: '.$arFields['UF_BUYER_INFO']['language'].'<br> Клиенты: ';
    foreach ($arFields['UF_TOURISTS'] as $key => $value) {
      $arFields['COMMENTS'] = $arFields['COMMENTS'].
      $value['first_name'].' '.$value['last_name'].
      ', Пасспорт: '.$value['passport_num'].', д.р.: '.$value['birth_date'].
      ', Гражданство: '.$value['citizenship'].', пол: '.$value['sex'].'<br>';
    }
    $arFields['tourists_arr'] = [];
    foreach ($arFields['UF_TOURISTS'] as $key => $value) {
      array_push($arFields['tourists_arr'], 
        $value['first_name'].' '.$value['last_name'].', Пасспорт: '.$value['passport_num'].', 
        д.р.: '.$value['birth_date']. ', Гражданство: '.$value['citizenship'].', пол: '.$value['sex']);
    }
    file_put_contents($_SERVER['DOCUMENT_ROOT'].'/test.json', json_encode($arFields));
    $this->restApiRequest('crm.deal.add', [
      'fields' => [
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
        'COMMENTS' => $arFields['COMMENTS'],
        'OPPORTUNITY' => $arFields['UF_SERVICE']['brutto'],
        'CURRENCY_ID' => $arFields['UF_SERVICE']['currency'],
        'BEGINDATE' => $arFields['UF_SERVICE']['dateBegin'],
        'CLOSEDATE' => $arFields['UF_SERVICE']['dateEnd'],
        'UF_CRM_1542117473'=> $arFields['UF_DOGOVOR_CODE'],
        'UF_CRM_5D36B393C64A2' => $arFields['UF_SERVICE']['nmen'],
        'UF_CRM_5D36B393D5BAB'=> $arFields['UF_BUYER_INFO']['phone'],
        'UF_CRM_5F686CC47E58B'=> $hotel_src->getItemsList([],['ID'=>$arFields['UF_IBLOCK_ELEMENT_ID']],false, false, ['ID','NAME'])[0]['NAME'],
        'UF_CRM_60C086FF07A2B' => $city_src->getItemsList([],['ID'=>$arFields['UF_SERVICE']['cityId']], false, false, ['ID','NAME'])[0]['NAME'],
        'UF_CRM_60C086FF7B089' => $arFields['UF_BUYER_INFO']['email'],
        'UF_CRM_610D4FF12DEF5' => $arFields['tourists_arr'],
        'UF_CRM_610D4FF176FB9' => $arFields['UF_BUYER_INFO']['language'],
        'UF_CRM_1652427908902' => $country_src->getItemsList([],['ID'=>$arFields['UF_SERVICE']['countryId']],false, false, ['ID','NAME'])[0]['NAME'],
        'UF_CRM_1652429037694' => $arFields['UF_SERVICE_NAME'],
        'UF_CRM_610D4FF1E1945' => json_encode($arFields['UF_SERVICE'],JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
      ],
    ]);
  }

}


?>