<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if ($_POST['pass']!='') {
    $trueerrors  = [];
    $security = \CUser::GetGroupPolicy([10]);
    $password = $_POST['pass'];
    $checksymbols = ['+', '=', '%', '$'];
    
    $errors = (new \CUser)->CheckPasswordAgainstPolicy($password, $security);
    foreach ($errors as $error) $trueerrors[] = str_replace($checksymbols,'',$error);
    foreach ($checksymbols as $test) if (strpos($password, $test)!==false) {
        $trueerrors[] = 'Символы +, =, %, $ запрещены';
        break;
    }
    echo json_encode($trueerrors);
    exit();
}

                            