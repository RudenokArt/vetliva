<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)die();
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
$this->setFrameMode(false);

if (empty($arResult["POINTS"])) {
    return false;
}

$options = "";

foreach($arResult["POINTS"] as $id => $arNames) {
    $options .= "<option value=\"".$id."\">".$arNames["NAME"]."</option>";
}

$selectPointsArea = "<div class=\"mt-10 select-points-area\">";
$selectPointsArea .= "<select class=\"select fx-min-width-150px\" name=\"transfers[fix][~cnt~][point_a]\" placeholder=\"Кликните для выбора точек\" placeholder=\"Точка A\">";
$selectPointsArea .= "<option>Точка A</option>";
$selectPointsArea .= $options;
$selectPointsArea .= "</select>";
$selectPointsArea .= "<select class=\"pl-15 select fx-min-width-150px\" name=\"transfers[fix][~cnt~][point_b]\" placeholder=\"Точка B\">";
$selectPointsArea .= "<option>Точка B</option>";
$selectPointsArea .= $options;
$selectPointsArea .= "</select>";
$selectPointsArea .= "</div>";
?>

<div class="panel panel-flat">
    <div class="form-with-select">
        <form action="<?= POST_FORM_ACTION_URI?>" method="post">
        <?= bitrix_sessid_post()?>

            <div class="form-group">

                <h5><b>Укажите точки из которых возможен выезд</b></h5>
                <select class="select" name="transfers[nofix][]" multiple="" placeholder="Кликните для выбора точек">
                    <?= $options?>
                </select>

            </div>
            
            <div class="form-group">

                <h5><b>или укажите фиксированные маршруты</b></h5>
                
                <?= str_replace("~cnt~", 0, $selectPointsArea)?>      
                
                <div class="mt-10 text-right" id="add-select-points-area">
                    <button type="button" class="btn btn-primary">+ Добавить</button>
                </div>
                
            </div>
            
            <div class="mt-10 text-right">
                <button type="submit" name="save" value="save" class="btn btn-primary">Сохранить</button>
            </div>
            
        </form>
    </div>
</div>

<script>
!function ($) {
    
    var cnt = 1, spa = '<?= $selectPointsArea?>';
    
    $("#add-select-points-area").on("click", function (e) {
        
        var html = spa;
        
        while (/~cnt~/.test(html)) {
            html = html.replace("~cnt~", cnt);
        }
        
        $(this).before(html);
        
        $("select[name^='transfers[fix]["+cnt+"]']").select2();
        
        cnt++;
        
        e.preventDefault();
    });
    
} (jQuery);
</script>
