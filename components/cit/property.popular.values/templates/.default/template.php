<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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

//echo '<pre>';
//var_dump($arParams);
//var_dump($arResult);
//echo '</pre>';
//var_dump($arResult['d']);
?>

<ul class="widget-ul">
    <? foreach ($arResult['ITEMS'] as $item): ?>
        <li>
            <div class="radio-checkbox">
                <input id="facilities-<?= $item['ID'] ?>" type="checkbox" name="<?=$item['CONTROL_NAME']?>" value="<?= $item['HTML_VALUE'] ?>"
                       <?=(!empty($item['CHECKED']) ? 'checked' : '')?> class="checkbox">
                <label for="facilities-<?= $item['ID'] ?>"><?= $item['NAME'] ?></label>
            </div>
        </li>
    <? endforeach ?>
</ul>
