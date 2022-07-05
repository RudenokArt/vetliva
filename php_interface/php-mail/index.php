<?php
  require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

/**
 * Отправка почты через PHP (SMTP)
 * Сделано в Live-code.ru
 * Автор: Mowshon
 * Дата: 11.11.11
 */

// Подключаем SMTP класс для работы с почтой
include_once('km_smtp_class.php');

// Конфигурационный массив
$SenderConfig = array(
    "SMTP_server"   =>  "smtp.yandex.ru",
    "SMTP_port"     =>  "465",
    "SMTP_email"    =>  "support@vetliva.com",
    "SMTP_pass"     =>  ":EP*(#yfgh",
    "SMTP_type"     =>  "ssl"
);

// Email получателя/Получателей
$Receiver = $_POST['receiver'];
// Тема сообщения
$Subject = $_POST['subject'];
// Текст сообщения (в HTML)
$Text = $_POST['text'];


// Вложение в письме - адрес к файлу
$Attachment = '';




/* $mail = new KM_Mailer(сервер, порт, пользователь, пароль, тип); */
/* Тип может быть: null, tls или ssl */
$mail = new KM_Mailer($SenderConfig['SMTP_server'], $SenderConfig['SMTP_port'], $SenderConfig['SMTP_email'], $SenderConfig['SMTP_pass'], $SenderConfig['SMTP_type']);
if($mail->isLogin) {
    // Прикрепить файл
    if($Attachment) {$mail->addAttachment($Attachment);}

    // Добавить ещё получателей
    // $mail->addRecipient("ad-it@ck.by");
    // $mail->addRecipient('user@mail.ru');
    // $mail->addRecipient('user@yandex.ru');

    /* $mail->send(От, Для, Тема, Текст, Заголовок = опционально) */
    $SendMail = $mail->send($SenderConfig['SMTP_email'], $Receiver, $Subject, $Text);
    
    // Очищаем список получателей
    $mail->clearRecipients();
    $mail->clearCC();
    $mail->clearBCC();
    $mail->clearAttachments();



    echo "success";
}
 else {
    echo "error";
 }
?>