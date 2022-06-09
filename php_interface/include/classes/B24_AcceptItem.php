<?php 


//При запросе на активацию объекта ставится задача в битрикс24 на подразделение "Работа с кабинетами поставщиков (Техническое подразделение)"
class B24_AcceptItem extends B24_class {

        private $elementID;

        function __construct($elementID){
              $this->elementID = $elementID;
          }

        function acceptItem () {
          $users = $this->RestApiRequest('user.search', [
            //ID подразделения
            'UF_DEPARTMENT' => 57,
        ]);

        $accomplices = [];

          $users = json_decode($users, true);
          $creatorID = $users['result'][0]['ID'];
          foreach($users['result'] as $user){
            $accomplices[] = $user['ID'];
          }

          $desc = "Активировать объект: id $this->elementID";

           $this->RestApiRequest('tasks.task.add', [
             'fields'=>[
               'TITLE'=>'Активация объекта',
               'CREATED_BY' => $creatorID,
               'RESPONSIBLE_ID' => $creatorID,
               'ACCOMPLICES' => $accomplices,
               'DESCRIPTION' => $desc,
             ]
           ]);
        }
          
    }
    
    ?>

    