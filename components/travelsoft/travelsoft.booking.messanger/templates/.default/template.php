<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
CJSCore::Init();
?>

    <div id="form-box">
        <form action="<?= $APPLICATION->GetCurPageParam("", array(), false)?>" method="post" <?if ($arParams["USE_AJAX"] == "Y"):?>onsubmit="window.bookingMessanger.sendMessage(this.message.value); return false; "<?endif?>>
            <?= bitrix_sessid_post()?>
            <div class="form-group">
                <textarea maxlength="<?= $arParams['MAX_LENGTH']?>" name="message" class="form-control"></textarea>
            </div>
            <div class="form-group text-right">
                <button value="<?= GetMessage("SEND_MESSAGE_BTN_TITLE")?>" type="submit" name="send_message" value="submit" class="btn btn-primary"><?= GetMessage("SEND_MESSAGE_BTN_TITLE")?></button>
            </div>
        </form>
    </div>
    <div id="message-box">
        <?foreach ($arResult["MESSAGES"] as $arMessage):?>
        <div class="person">
            <b><?if ($arMessage["IS_MANAGER"]):?><?= GetMessage("FORM_MANAGER_MESSAGE_TITLE")?><?else:?><?= GetMessage("FORM_CLIENT_MESSAGE_TITLE")?><?endif?><? echo $arMessage["DATE"]?></b>
        </div>
        <div class="message">
            <?= $arMessage["MESSAGE"]?>
        </div>
        <?endforeach?>
    </div>
<?if ($arParams["USE_AJAX"] == "Y"):?>
<script>
$(document).ready(function () {
    window.bookingMessanger = new window.Travelsoft.BookingMessanger({
        freqRequest: <?= $arParams["FREQREQUEST"]?>,
        ajaxUrl: "<?= $componentPath?>/ajax.php",
        dateFrom: "<?= $arParams["DATE_FROM"]?>",
        onAjaxFailure: null,
        onAjaxonAjaxBefore: null,
        onAjaxSuccess: function (data) {
            var html = "";
            if (typeof data.error === "undefined" && typeof data.MESSAGES !== "undefined" && data.MESSAGES.length > 0) {
                
                for (var i=0; i<data.MESSAGES.length; i++) {
                    html += '<div class="person"><b>'
                    html += data.MESSAGES[i].IS_MANAGER ? '<?= GetMessage("FORM_MANAGER_MESSAGE_TITLE")?>' : '<?= GetMessage("FORM_CLIENT_MESSAGE_TITLE")?>'
                    html += data.MESSAGES[i].DATE
                    html += '</b></div>'
                    html += '<div class="message">'
                    html += data.MESSAGES[i].MESSAGE
                    html += '</div>'
                }
                
                var messageBox = document.getElementById('message-box');
                messageBox.innerHTML = html + messageBox.innerHTML;
                
            }
        }
    })
    window.bookingMessanger.watch();
});
</script>
<?endif?>
