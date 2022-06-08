<?php 

/**
 * 
 */
class B24_TechnicalSupport extends B24_class {

  function __construct($user_mail_data=[])  {
    $this->user_mail_data = $user_mail_data;
    $this->user_data = $this->getUserData($user_mail_data['user_id']);
    $this->b24_contact = $this->checkContact($this->user_data);
    $this->b24_assigned = $this->getAssigned();
  }

  function getUserData ($user_id) {
    $src = CUser::GetList([],[],['ID'=>$user_id],['FIELDS'=>[
      'ID','NAME','LAST_NAME','EMAIL','PERSONAL_PHONE','PERSONAL_MOBILE','WORK_PHONE',
    ]]);
    return (new InfoBlock)->getList_fetch($src)[0];
  }

  function checkContact () {
    $arr1 = json_decode($this->RestApiRequest('crm.contact.list',[
      'filter'=>['PHONE' => $this->user_data['WORK_PHONE']],
      'select' => ['PHONE', 'EMAIL']
    ]), true)['result'];
    $arr2 = json_decode($this->RestApiRequest('crm.contact.list',[
      'filter'=>['PHONE' => $this->user_data['PERSONAL_PHONE']],
      'select' => ['PHONE', 'EMAIL']
    ]), true)['result'];
    $arr3 = json_decode($this->RestApiRequest('crm.contact.list',[
      'filter'=>['PHONE' => $this->user_data['PERSONAL_MOBILE']],
      'select' => ['PHONE', 'EMAIL']
    ]), true)['result'];
    $arr4 = json_decode($this->RestApiRequest('crm.contact.list',[
      'filter'=>['EMAIL' => $this->user_data['EMAIL']],
      'select' => ['PHONE', 'EMAIL']
    ]), true)['result'];
    return array_merge($arr1, $arr2, $arr3, $arr4);
  }

  function getAssigned () {
     return json_decode($this->RestApiRequest('user.get', [
        'filter'=>[
          'ACTIVE' => true,
          'UF_DEPARTMENT' => 48,
        ]
      ]), true)['result'][0]['ID'];
  }

  function userMail () {
    if (sizeof($this->b24_contact) > 0) {
      $contact = $this->b24_contact[0]['ID'];
    } else {
      $arContact = $this->RestApiRequest('crm.contact.add', [
        'fields'=>[
          'NAME'=> $this->user_data['NAME'],
          'LAST_NAME'=>$this->user_data['LAST_NAME'],
          'PHONE' => [
            ['VALUE'=> $this->user_data['WORK_PHONE'], 'VALUE_TYPE'=>'WORK'],
            ['VALUE'=> $this->user_data['PERSONAL_PHONE'], 'VALUE_TYPE'=>'PERSONAL_PHONE'],
            ['VALUE'=> $this->user_data['PERSONAL_MOBILE'], 'VALUE_TYPE'=>'PERSONAL_MOBILE'],
          ],
          'EMAIL'=> $this->user_data['EMAIL'],
        ]
      ]);
      $contact = json_decode($arContact, true)['result'];
    }
    $this->RestApiRequest('crm.deal.add', [
      'fields'=>[
        'TITLE'=>'Обращение в техподдержку(1-я линия) с формы обратной связи vetliva',
        'CATEGORY_ID' => 10,
        'CONTACT_ID' => $contact,
        'COMMENTS' => $this->user_mail_data['user_data']['TEXT'],
        'ASSIGNED_BY_ID'=>$this->b24_assigned,
      ]
    ]);
  }

}


?>