<?php 
/**
 * 
 */
class B24_Partners {

  const WEB_HOOK = 'https://bitrix.vetliva.by/rest/1/1hfn9tdkh923zrq6/';

  public 
  $partner_data, 
  $partner_data_json, 
  $contact_id,
  $company_id,
  $test;

  function __construct($partner_data=[]) {
    $this->partner_data = $partner_data;
    $this->partner_data_json = json_encode($partner_data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $this->test = $_POST;
  }

  function companyList () { 
    $api_method = 'crm.company.list?'; 
    $api_query = http_build_query([
      'filter' =>[
        'ID' => 121,
      ],
      'select' => ['ID', 'UF_CRM_5D9B3BBFE176C', ]
    ]); 
    $result = file_get_contents(self::WEB_HOOK.$api_method.$api_query); 
    return json_decode($result);
  }

  function dealList () { 
    $api_method = 'crm.deal.list?'; 
    $api_query = http_build_query([
      'filter' =>[
        // 'ID' => 2371,
        'STAGE_ID' => 'C4:NEW',
      ],
      'select' => ['ID', 'UF_CRM_1650892238173', ]
    ]); 
    $result = file_get_contents(self::WEB_HOOK.$api_method.$api_query); 
    return json_decode($result);
  }

  function dealDelete ($id) { 
    $api_method = 'crm.deal.delete?'; 
    $api_query = http_build_query([
      'ID' => $id,
    ]); 
    $result = file_get_contents(self::WEB_HOOK.$api_method.$api_query); 
    return json_decode($result);
  }

  function companyFields () { 
    $api_method = 'crm.company.fields?'; 
    $api_query = http_build_query([
      'ID' => 121,
    ]); 
    $result = file_get_contents(self::WEB_HOOK.$api_method.$api_query); 
    return json_decode($result);
  }

  function dealFields ($id) { 
    $api_method = 'crm.deal.fields?'; 
    $api_query = http_build_query([
      'ID' => $id,
    ]); 
    $result = file_get_contents(self::WEB_HOOK.$api_method.$api_query); 
    return json_decode($result);
  }

  function dealAdd () { 
    $session = \Bitrix\Main\Application::getInstance()->getSession();
    $api_method = 'crm.deal.add?'; 
    $api_query = http_build_query([
      'fields' =>[
        'TITLE' => 'Сделка - '.$this->partner_data['UF_LEGAL_NAME'],
        'STAGE_ID' => 'C4:NEW',
        'TYPE_ID' => 'SERVICES',
        'ASSIGNED_BY_ID' => 27352,
        'CREATED_BY_ID' => 27352,
        'MODIFY_BY_ID' => 27352,
        'CATEGORY_ID' => 4,
        'IS_NEW' => 'Y',
        'STAGE_SEMANTIC_ID' => 'P',
        'COMPANY_ID' => $this->company_id,
        'CONTACT_ID' => $this->contact_id,
        'OPENED' => 'Y',
        'UF_CRM_1650892238173'=>$session['PROVIDER_GROUPS'],
        'UF_CRM_1650547022911' => $session['WORK_COUNTRY'],
      ],
    ]); 
    $result = file_get_contents(self::WEB_HOOK.$api_method.$api_query); 
    return $result;
  }

  function contactAdd () { 
    $api_method = 'crm.contact.add?'; 
    $api_query = http_build_query([
      'fields' =>[
        'NAME'=> $this->partner_data['NAME'],
        'LAST_NAME' => $this->partner_data['LAST_NAME'],
        'PHONE' => [['VALUE'=>$this->partner_data['WORK_PHONE'], 'VALUE_TYPE'=>'WORK']],
        'EMAIL' => [['VALUE'=>$this->partner_data['EMAIL'], 'VALUE_TYPE'=>'WORK'],],
      ],
    ]); 
    $result = file_get_contents(self::WEB_HOOK.$api_method.$api_query); 
    $this->contact_id = json_decode($result)->result;
  }

  function companyAdd () { 
    $api_method = 'crm.company.add?'; 
    $api_query = http_build_query([
      'fields' =>[
        'ADDRESS'=> $this->partner_data['UF_LEGAL_ADDRESS'],
        'TITLE' => $this->partner_data['UF_LEGAL_NAME'],
        'PHONE' => [['VALUE'=>$this->partner_data['WORK_PHONE'], 'VALUE_TYPE'=>'WORK']],
        'EMAIL' => [['VALUE'=>$this->partner_data['EMAIL'], 'VALUE_TYPE'=>'WORK'],],
      ],
    ]); 
    $result = file_get_contents(self::WEB_HOOK.$api_method.$api_query);
    $this->company_id = json_decode($result)->result;
  }

}


?>