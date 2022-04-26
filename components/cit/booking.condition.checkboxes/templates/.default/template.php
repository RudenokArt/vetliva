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
?>

<? foreach ( $arResult['LIST'] as $item):?>
    <div class="radio-checkbox">
        <input data-error-type="WRONG_ACCEPT" name="make_booking[wrong_accept_<?=$item['ID']?>]" type="checkbox" class="checkbox to-validate" id="wrong_accept_<?=$item['ID']?>" data-error-text="<?=$item['ERROR']?>">
        <label for="wrong_accept_<?=$item['ID']?>">
            <?if($item['LINK_TYPE'] == 'PAGE'):?>
                <a class="show-popup" href="<?=$item['LINK']?>"><?= $item['NAME'] ?></a>
            <?else:?>
                <a href="<?=$item['LINK']?>" target="_blank"><?= $item['NAME'] ?></a>
            <?endif;?>
            <br></label>
        <?if(!empty($item['SIGNATURE'])):?>
            <p><?= $item['SIGNATURE'] ?></p>
        <?endif;?>
    </div>
<?endforeach;?>
