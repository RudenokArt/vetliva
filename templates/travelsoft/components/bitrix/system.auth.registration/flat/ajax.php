<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

//Loc::loadLanguageFile($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/pages.php");
if ($_POST['pass']!='') {
    $MESS['by'] = "Сімвалы +, =, %, $ забаронены";
    $MESS['en'] = "Characters +, =, %, $ are forbidden";
    $MESS['ru'] = "Символы +, =, %, $ запрещены";
    
    $trueerrors  = [];
    $security = \CUser::GetGroupPolicy([28]);
    $password = $_POST['pass'];
    $checksymbols = ['+', '=', '%', '$'];
    
    $errors = (new \CUser)->CheckPasswordAgainstPolicy($password, $security);
    foreach ($errors as $error) $trueerrors[] = str_replace($checksymbols,'',$error);
    foreach ($checksymbols as $test) if (strpos($password, $test)!==false) {
        $trueerrors[] = $MESS[$_POST['lang']];
        break;
    }
    echo json_encode($trueerrors);
    exit();
}

                            