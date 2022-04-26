<?php
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
if (empty($arResult['CURRENCY'])) return ;
?>
<form id="<?= $arResult['CURRENCY_FORM_ID']?>" action="<?= POST_FORM_ACTION_URI?>" method="post">
    <select onchange="document.getElementById('<?= $arResult['CURRENCY_FORM_ID']?>').submit();" name="currency">
    <?foreach ($arResult['CURRENCY'] as $id => $arCurrency) :?>
        <option <?if ($id == $arResult['CURRENT_CURRENCY']['id']) echo "selected"?> value="<?= $arCurrency['iso']?>"><?= $arCurrency['iso']?></option>
    <? endforeach; ?>
    </select>
</form>