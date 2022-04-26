<div class="favorites">
    <? if ($arResult["ACTION"] === "ADD"): ?>
        <button  class="favorites__button bg-star" onclick="BX.Travelsoft.add2favorites('<?= $templateFolder?>/ajax.php', '<?= $arParams["OBJECT_TYPE"] ?>', <?= $arParams["OBJECT_ID"] ?>, <?= $arParams["STORE_ID"] ?>, '<?= $arResult["HASH"]?>', this)" type="button"><?= GetMessage("TRAVELSOFT_FAVORITES_ADD_TO_FAV") ?></button>
    <? elseif ($arResult["ACTION"] === "DELETE"): ?>
        <button class="favorites__button bg-star_filled" onclick="BX.Travelsoft.deleteFromFavorites('<?= $templateFolder?>/ajax.php', '<?= $arParams["OBJECT_TYPE"] ?>', <?= $arParams["OBJECT_ID"] ?>, <?= $arParams["STORE_ID"] ?>, '<?= $arResult["HASH"]?>', this)" type="button"><?= GetMessage("TRAVELSOFT_FAVORITES_REMOVE_FAV") ?></button>
    <? endif ?>
</div>
<script>
    BX.message({
        TRAVELSOFT_FAVORITES_ADD_TO_FAV: "<?= GetMessage("TRAVELSOFT_FAVORITES_ADD_TO_FAV") ?>",
        TRAVELSOFT_FAVORITES_REMOVE_FAV: "<?= GetMessage("TRAVELSOFT_FAVORITES_REMOVE_FAV") ?>",
        TRAVELSOFT_FAVORITES_ADD_ERROR: "<?= GetMessage("TRAVELSOFT_FAVORITES_ADD_ERROR") ?>",
        TRAVELSOFT_FAVORITES_DELETE_ERROR: "<?= GetMessage("TRAVELSOFT_FAVORITES_DELETE_ERROR") ?>"
    });
</script>