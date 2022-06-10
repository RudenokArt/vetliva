<?php CModule::IncludeModule("support");

/**
 * 
 */
class B24_PartnersSupport extends B24_class {

  function __construct($arFields)  {
    $this->request_data = $arFields;
    $this->partner = $this->getPartnerData($arFields);
    $this->b24_contact = $this->checkContact();
    $this->b24_assigned = $this->getAssigned();
    $this->partner_request = $this->sendPartnerRequest();
  }

  function sendPartnerRequest () {
    $ticket = (new CTicket)->GetByID($this->request_data['ID'])->Fetch();
    $this->RestApiRequest('crm.deal.add', [
      'fields'=>[
        'TITLE'=>'Обращение в техподдержку(1-я линия) из кабинета поставщика vetliva',
        'CATEGORY_ID' => 10,
        'CONTACT_ID' => $this->b24_contact,
        'COMMENTS' => $ticket['TITLE'],
        'ASSIGNED_BY_ID'=>$this->b24_assigned,
      ]
    ]);
    // file_put_contents($_SERVER['DOCUMENT_ROOT'].'/test.json', json_encode($ticket));
  }

  function getAssigned () {
     return json_decode($this->RestApiRequest('user.get', [
        'filter'=>[
          'ACTIVE' => true,
          'UF_DEPARTMENT' => 48,
        ]
      ]), true)['result'][0]['ID'];
  }

  function getPartnerData ($arFields) {
    $rsUser = CUser::GetByID($arFields['MESSAGE_AUTHOR_USER_ID']);
    $arUser = $rsUser->Fetch();
    // file_put_contents($_SERVER['DOCUMENT_ROOT'].'/test.json', json_encode($arUser));
    return $arUser;
  }

  function checkContact () {
   $arr = $this->RestApiRequest('crm.contact.list',[
    'filter'=>['EMAIL' => $this->partner['EMAIL']],
      // 'filter'=>['EMAIL' => 'elena_07@inbox.ru'],
    'select' => ['NAME','PHONE', 'EMAIL']
  ]);
   $contact_id = json_decode($arr,true)['result'][0]['ID'];
   if (count(json_decode($arr, true)['result']) < 1) {
     $arContact = $this->RestApiRequest('crm.contact.add', [
      'fields'=>[
        'NAME'=> $this->partner['NAME'],
        'LAST_NAME'=>$this->partner['LAST_NAME'],
        'EMAIL'=> [['VALUE'=> $this->partner['EMAIL'], 'VALUE_TYPE'=>'WORK'],],
        'PHONE' => [
          ['VALUE'=> $this->partner['WORK_PHONE'], 'VALUE_TYPE'=>'WORK'],
          ['VALUE'=> $this->partner['PERSONAL_PHONE'], 'VALUE_TYPE'=>'PERSONAL_PHONE'],
          ['VALUE'=> $this->partner['PERSONAL_MOBILE'], 'VALUE_TYPE'=>'PERSONAL_MOBILE'],
        ],
      ]
    ]);
     $contact_id = json_decode($arContact,true)['result'];
   }
   // file_put_contents($_SERVER['DOCUMENT_ROOT'].'/test.json', $contact_id);
   return $contact_id;
 }


}


?>