<?
use Bitrix\Main\Page\Asset,
    Bitrix\Main\Localization\Loc;

// Получаем содержимое языкового файла
$ARJSMESS = Loc::LoadLanguageFile($_SERVER["DOCUMENT_ROOT"].$templateFolder."/template.php");
if (!empty($ARJSMESS)) {
    // Добавляем объект с переводами.
    Asset::getInstance()->AddString("<script type=\"text/javascript\">BX.message(".CUtil::PhpToJSObject($ARJSMESS).")</script>");
}
?>