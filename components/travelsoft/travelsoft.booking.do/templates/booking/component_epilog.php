<?
use Bitrix\Main\Page\Asset,
    Bitrix\Main\Localization\Loc;

// �������� ���������� ��������� �����
$ARJSMESS = Loc::LoadLanguageFile($_SERVER["DOCUMENT_ROOT"].$templateFolder."/template.php");
if (!empty($ARJSMESS)) {
    // ��������� ������ � ����������.
    Asset::getInstance()->AddString("<script type=\"text/javascript\">BX.message(".CUtil::PhpToJSObject($ARJSMESS).")</script>");
}
?>