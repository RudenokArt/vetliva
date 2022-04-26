<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if ($_POST['action']=='add_user' && $_POST['USER_EMAIL']!='') {
    $result = [];
    $dbres = Bitrix\Main\UserTable::getList(
                    array(
                        'select' => array('ID'),
                        'filter' => array('EMAIL' => $_POST['USER_EMAIL'])
                    )
            )->fetch();

    if ($dbres["ID"] > 0) {
        $result = ['success'=>false, 'type'=>'user_exist'];
        echo json_encode($result);
        exit();
    }
    else {
        global $USER;   
        $result = $USER->SimpleRegister($_POST['USER_EMAIL']);
        if ($result["TYPE"] != "ERROR") {
            $result = ['success'=>true];
            $_SESSION['NEW_USER_REGISTER'][$USER->GetID()] = 'YES';
            echo json_encode($result);
            exit();
        }
        else {
            $result = ['success'=>false, 'type'=>'user_error', 'message'=>$result["MESSAGE"]];
            echo json_encode($result);
            exit();
        }
    }
}                     