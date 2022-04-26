<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$session = \Bitrix\Main\Application::getInstance()->getSession();

if (isset($_POST['WORK_COUNTRY'])) {
  $session->set('WORK_COUNTRY', $_POST['WORK_COUNTRY']);
  print_r($session['WORK_COUNTRY']);
}

if (isset($_POST['PROVIDER_GROUPS'])) {
  $arr_provider_groups = [];
  $b24_provider_groups = ['2146','2147','2148','2149','2150',];
  foreach ($_POST['PROVIDER_GROUPS'] as $key => $value) {
    $arr_provider_groups[$key] = $b24_provider_groups[$value];
  }
  $session->set('PROVIDER_GROUPS',$arr_provider_groups);
  print_r($session['PROVIDER_GROUPS']);
}

if ($_POST['pass']!='') {
  $trueerrors  = [];
  $security = \CUser::GetGroupPolicy([26]);
  $password = $_POST['pass'];
  $checksymbols = ['+', '=', '%', '$', '\''];

  $errors = (new \CUser)->CheckPasswordAgainstPolicy($password, $security);
  foreach ($errors as $error) $trueerrors[] = str_replace($checksymbols,'',$error);
  foreach ($checksymbols as $test) if (strpos($password, $test)!==false) {
    $trueerrors[] = 'Символы +, =, %, $, \' запрещены';
    break;
  }
  echo json_encode($trueerrors);
  exit();
}


