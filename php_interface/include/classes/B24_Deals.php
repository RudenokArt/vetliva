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

  function bookingDealDelete ($arFields=[]) {
    $deal = $this->getCrmBookingDealList(['UF_CRM_1652514403572'=>$arFields['ID']['ID']]);
    $this->restApiRequest('crm.deal.delete', [
      'id' => $deal['result'][0]['ID'],
    ]);
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
    // file_put_contents($_SERVER['DOCUMENT_ROOT'].'/test.json', json_encode($arFields));
    $this->restApiRequest('crm.deal.update', [
      'id' => $deal_id,
      'fields' => $arFields,
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
    ??????????????????: '.$arFields['UF_SERVICE']['brutto'].'; 
    ????????????: '.$arFields['UF_SERVICE']['currency'].'; 
    ???????? ????????????: '.$arFields['UF_SERVICE']['dateBegin'].'; 
    ???????? ??????????????????: '.$arFields['UF_SERVICE']['dateEnd'].'; 
    ????????????????????: '.$arFields['UF_SERVICE']['nmen'].'; 
    Email: '.$arFields['UF_BUYER_INFO']['email'].'; 
    ??????.: '.$arFields['UF_BUYER_INFO']['phone'].'; 
    ????????: '.$arFields['UF_BUYER_INFO']['language'].'; 
    ID ???????????????????? ???? vetliva.by: '.$arFields['UF_SERVICE']['partnerId'].'; 
    ??????????????????: '.$arFields['company']['NAME'].' '.$arFields['company']['LAST_NAME'].' '.$arFields['company']['EMAIL'].'; 
    ID ???????????? ???? vetliva.by: '.$arFields['UF_SERVICE']['parts']['roomId'].'; 
    ????????????: '.$arFields['UF_SERVICE_NAME'].'
    ID ?????????? ???? vetliva.by: '.$arFields['UF_IBLOCK_ELEMENT_ID'].'; 
    ??????????: '.$hotel['NAME'].'; 
    ??????????????: '.$arFields['UF_DOGOVOR_CODE'].'; 
    ID ???????????? ???? vetliva.by: '.$arFields['UF_SERVICE']['countryId'].'; 
    ????????????: '.$country['NAME'].'; 
    ID ???????????? ???? vetliva.by: '.$arFields['UF_SERVICE']['cityId'].'; 
    ??????????: '.$city['NAME'].'; ??????????????: ';
    foreach ($arFields['UF_TOURISTS'] as $key => $value) {
      $arFields['CONTENT'] = $arFields['CONTENT'].
      $value['first_name'].' '.$value['last_name'].
      ', ????????????????: '.$value['passport_num'].', ??.??.: '.$value['birth_date'].
      ', ??????????????????????: '.$value['citizenship'].', ??????: '.$value['sex'].';';
    }
    $arFields['tourists_arr'] = [];
    foreach ($arFields['UF_TOURISTS'] as $key => $value) {
      array_push($arFields['tourists_arr'], 
        $value['first_name'].' '.$value['last_name'].', ????????????????: '.$value['passport_num'].', 
        ??.??.: '.$value['birth_date']. ', ??????????????????????: '.$value['citizenship'].', ??????: '.$value['sex']);
    }
    return $arFields;
  }

  function bookingFieldsConstruct ($arFields) {
    $arFields = $this->bookingFieldsNormalize($arFields);
    return [
        'TITLE' => '???????????????????????? ???? VETLIVA - test',
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
    // file_put_contents($_SERVER['DOCUMENT_ROOT'].'/test.json', json_encode($arFields));
    $arFields = $this->bookingFieldsConstruct($arFields);
    $this->restApiRequest('crm.deal.add', [
      'fields' => $arFields,
    ]);
  }

}


?>