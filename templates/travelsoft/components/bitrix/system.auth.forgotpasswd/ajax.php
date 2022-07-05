<?

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$Passwd = $_POST['password'];
$Receiver = $_POST['receiver'];



function changePasswd($Receiver, $Passwd){

    $filter = Array
    (
    "EMAIL" => $Receiver,
    );


    $filter = Array("EMAIL" => $Receiver);
    $rsUser = CUser::GetList(($by="id"), ($order="desc"), $filter);
    $arUser = $rsUser->Fetch();
  
    $userID = $arUser['ID'];



$user = new CUser;
$fields = Array(
 "PASSWORD"          => $Passwd,
 "CONFIRM_PASSWORD"  => $Passwd,
 );

$user->Update($userID, $fields);
$strError = $user->LAST_ERROR;

if($strError){
    var_dump($strError);
}else{
    if($arUser === false){
        echo 'Введенная почта не найдена';
    }else{
        echo 'Новый пароль успешно выслан вам на почту';
    }
}

}


changePasswd($Receiver, $Passwd);