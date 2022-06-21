<?php 

/**
 * 
 */
class SmtpImap {
  
  function __construct($imap, $login, $password)  {
    $this->connection = imap_open($imap, $login, $password);
  }

  function travelLineActivateDealAdd () {
    $assigned = $this->getTravelLineAssignedUser();
    foreach ($this->getTravelLineMessages() as $key => $value) {
      $result = (new B24_class)->RestApiRequest('crm.deal.add', [
        'fields'=>[
          'TITLE'=>$value['subject'],
          'CATEGORY_ID' => 4,
          'COMMENTS' => $value['subject'],
          'ASSIGNED_BY_ID'=>$assigned,
        ],
      ]);
    }
  }

  function getTravelLineAssignedUser () {
    $arr = json_decode((new B24_class)->RestApiRequest('user.get', [
      'sort'=>'ID',
      'order'=> 'desc',
      'filter'=>[
        'ACTIVE' => true,
        'UF_DEPARTMENT' => 41,
      ]
    ]), true)['result'];
    $list=[];
    foreach ($arr as $key => $value) {
      if ($value['ID'] != 22819 and $value['ID'] != 27427) {
        array_push($list, $value['ID']);
      }
    }
    return $list[0];
  }

  function getTravelLineMessages () {
    $arr = $this->getMessages('yesterday');
    $list = [];
    foreach ($arr as $key => $value) {
      if ($value['mailbox'] == 'xml-distribution.tech') {
        array_push($list, $value);
      }
    }
    return $list;
  }

  function getPeriod ($period='yesterday') {
    if ($period =='yesterday') {
      $arr = getdate(strtotime('yesterday'));
      $str = 'ON "'.$arr['mday'].' '.$arr['month'].' '.$arr['year'].'"';
    }
    return $str;
  }

  function closeConnection () {
    imap_close($this->connection);
  }

  function getMessages ($period) {
    // $arr = imap_search($this->connection, 'ON "6 June 2022"');
    // $arr = imap_search($this->connection, 'ON "7 June 2022"');
    $arr = imap_search($this->connection, $this->getPeriod($period));
    foreach ($arr as $key => $value) {
      $arr[$key] =  [
        'from' => iconv_mime_decode(imap_headerinfo($this->connection, $value)->senderaddress),
        'subject' => imap_utf8(imap_headerinfo($this->connection, $value)->subject),
        'mailbox' => iconv_mime_decode(imap_headerinfo($this->connection, $value)->from[0]->mailbox),
      ];
    }
    return $arr;
  }
}


?>