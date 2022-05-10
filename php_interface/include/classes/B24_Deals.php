<?php 
/**
 * 
 */
class B24_Deals extends B24_Leads {

  // function __construct(argument)  {
  //   // code...
  // }

  function newBookingDealAdd ($arFields) {
    $arFields['UF_SERVICE'] = json_decode($arFields['UF_SERVICE_JSON'],true);
    $arFields['UF_BUYER_INFO'] = json_decode($arFields['UF_BUYER_INFO_JSON'],true);
    $arFields['UF_TOURISTS'] = json_decode($arFields['UF_TOURISTS_JSON'],true);
    $rsUser = CUser::GetByID($arFields['UF_SERVICE']['partnerId']);
    $arFields['company'] = $rsUser->Fetch();
    $arFields['COMMENTS'] = '
    Поставщик: '.$arFields['company']['NAME'].' '.$arFields['company']['LAST_NAME'].' '.$arFields['company']['EMAIL'].'<br>
    Услуга: '.$arFields['UF_SERVICE_NAME'].'<br>
    Дата начала: '.$arFields['UF_SERVICE']['dateBegin'].'<br>
    Дата окончания: '.$arFields['UF_SERVICE']['dateEnd'].'<br>
    Количество: '.$arFields['UF_SERVICE']['nmen'].'<br>
    Стоимость: '.$arFields['UF_SERVICE']['brutto'].'<br>
    Валюта: '.$arFields['UF_SERVICE']['currency'].'<br>
    Страна: '.$arFields['UF_SERVICE']['cityId'].'<br>
    Город: '.$arFields['UF_SERVICE']['cityId'].'<br>
    Email: '.$arFields['UF_BUYER_INFO']['email'].'<br>
    Тел.: '.$arFields['UF_BUYER_INFO']['phone'].'<br>
    Язык: '.$arFields['UF_BUYER_INFO']['language'].'<br> Клиенты: ';
    foreach ($arFields['UF_TOURISTS'] as $key => $value) {
      $arFields['COMMENTS'] = $arFields['COMMENTS'].
      $value['first_name'].' '.$value['last_name'].
      ', Пасспорт: '.$value['passport_num'].', д.р.: '.$value['birth_date'].
      ', Гражданство: '.$value['citizenship'].', пол: '.$value['sex'].'<br>';
    }

    $this->restApiRequest('crm.deal.add', [
      'fields' => [
        'TITLE' => $arFields['UF_SERVICE_NAME'],
        'STAGE_ID' => 'C4:NEW',
        'TYPE_ID' => 'SERVICES',
        'ASSIGNED_BY_ID' => 27352,
        'CREATED_BY_ID' => 27352,
        // 'MODIFY_BY_ID' => 27352,
        'CATEGORY_ID' => 4,
        'IS_NEW' => 'Y',
        'OPENED' => 'Y',
        'COMMENTS' => $arFields['COMMENTS'],
      ],
    ]);
  }

}


?>