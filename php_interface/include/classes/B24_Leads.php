<?php 
/**
 *  test@mail.ru
 */
class B24_Leads extends B24_Partners {
  
  // function __construct()  {
  //   $this->request_form_data = $this->getRequestFormData();
  // }

  function getLeadList ($filter=[]) {
    $arr = $this->restApiRequest('crm.lead.list', [
      'filter' => $filter,
      'select' => ['*', 'UF_*'],
    ]);
    return $arr;
  }

  function restApiRequest ($method, $query) { 
    $api_method = $method.'?'; 
    $api_query = http_build_query($query); 
    $result = file_get_contents(self::WEB_HOOK.$api_method.$api_query); 
    return $result;
  }

  function getRequestFormData () { // получение данных и  создание лида для формы заявки на экскурсию
    // file_put_contents($_SERVER['DOCUMENT_ROOT'].'/test.json', json_encode($_POST));
    $this->restApiRequest('crm.lead.add', [
      'fields' =>[
        'TITLE' => 'Заявка на экскурсию: '.$_POST['object_name'],
        'NAME' => $_POST['full_name'],
        'STATUS_ID' => 'NEW',
        'OPENED' => 'Y',
        'ASSIGNED_BY_ID' => 27186,
        'SOURCE_ID' => 10,
        'COMMENTS'=> $_POST['comment'],
        'PHONE' => [['VALUE'=> $_POST['phone'], 'VALUE_TYPE'=>'WORK']],
        'EMAIL' => [['VALUE'=> $_POST['email'], 'VALUE_TYPE'=>'WORK'],],
      ],
    ]);
    return ($_POST);
  }


}


?>