<div class="favorites">
    <?global $USER; if ($USER->IsAuthorized()):?>
    <? if ($arResult["ACTION"] === "ADD"): ?>
            <button  class="favorites__button bg-star" onclick="BX.Travelsoft.add2favorites('<?= $templateFolder?>/ajax.php', '<?= $arParams["OBJECT_TYPE"] ?>', <?= $arParams["OBJECT_ID"] ?>, <?= $arParams["STORE_ID"] ?>, '<?= $arResult["HASH"]?>', this, '<?=$arParams['SHORT_DISPLAY']?>')" type="button"><?if ($arParams['SHORT_DISPLAY']!='Y'):?><?= GetMessage("TRAVELSOFT_FAVORITES_ADD_TO_FAV") ?><?endif;?></button>
        <? elseif ($arResult["ACTION"] === "DELETE"): ?>
            <button class="favorites__button bg-star_filled" onclick="BX.Travelsoft.deleteFromFavorites('<?= $templateFolder?>/ajax.php', '<?= $arParams["OBJECT_TYPE"] ?>', <?= $arParams["OBJECT_ID"] ?>, <?= $arParams["STORE_ID"] ?>, '<?= $arResult["HASH"]?>', this, '<?=$arParams['SHORT_DISPLAY']?>')" type="button"><?if ($arParams['SHORT_DISPLAY']!='Y'):?><?= GetMessage("TRAVELSOFT_FAVORITES_REMOVE_FAV") ?><?endif;?></button>
        <? endif ?>
    <?else:?>
    <button  class="favorites__button bg-star" onclick="$('.show-header-auth-popup.auth-link').click()" type="button"><?if ($arParams['SHORT_DISPLAY']!='Y'):?><?= GetMessage("TRAVELSOFT_FAVORITES_ADD_TO_FAV") ?><?endif;?></button>
    <?endif;?>
</div>
<script>
    BX.message({
        TRAVELSOFT_FAVORITES_ADD_TO_FAV: "<?= GetMessage("TRAVELSOFT_FAVORITES_ADD_TO_FAV") ?>",
        TRAVELSOFT_FAVORITES_REMOVE_FAV: "<?= GetMessage("TRAVELSOFT_FAVORITES_REMOVE_FAV") ?>",
        TRAVELSOFT_FAVORITES_ADD_ERROR: "<?= GetMessage("TRAVELSOFT_FAVORITES_ADD_ERROR") ?>",
        TRAVELSOFT_FAVORITES_DELETE_ERROR: "<?= GetMessage("TRAVELSOFT_FAVORITES_DELETE_ERROR") ?>"
    });
</script>