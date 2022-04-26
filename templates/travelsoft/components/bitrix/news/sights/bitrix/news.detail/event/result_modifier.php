<?

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

require_once 'functions.php';

//$arCode = array("SEARCH".POSTFIX_PROPERTY,"HD_DESCROOM".POSTFIX_PROPERTY,"HD_DESC".POSTFIX_PROPERTY,"MAP".POSTFIX_PROPERTY);

$cp = $this->__component; // объект компонента

if (is_object($cp))
{
    $cp->arResult['PROPERTIES'] = $arResult["PROPERTIES"];
    $cp->SetResultCacheKeys(array('PROPERTIES'));
}
