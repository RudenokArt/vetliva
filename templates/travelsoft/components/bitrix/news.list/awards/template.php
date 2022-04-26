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

if($arParams['NO_SHOW_WATERMARK'] !== 'Y'){
    $arWaterMark = [
        [
            'name' => 'watermark',
            'position' => 'topright',
            'type' => 'image',
            'size' => 'real',
            'file' => NO_PHOTO_PATH_WATERMARK,
            'fill' => 'exact'
        ]
    ];
}
else{
    $arWaterMark = [];
}
?>

<?php if(!empty($arResult['ITEMS'])):?>
    <?php $this->SetViewTarget('awards-menu'); ?>
    <div class="check-rates">
        <div class="detail-sidebar hidden-sm hidden-xs">
            <div class="scrolly scrollspy-sidebar sidebar-detail scrolly-year scroll-heading" role="complementary" data-offset="20">
                <ul class="nav">
                    <?php
                    $currentYear = false;
                    foreach($arResult["ITEMS"] as $arItem){

                        switch(LANGUAGE_ID){

                            case 'ru':

                                $name = $arItem['NAME'];

                                break;

                            default:

                                $name = $arItem['DISPLAY_PROPERTIES']['NAME' . POSTFIX_PROPERTY]['DISPLAY_VALUE'];

                                break;

                        }

                        if(!$name){
                            continue;
                        }

                        if(!$currentYear || $currentYear != $arItem['PROPERTIES']['YEAR']['VALUE']){
                            $currentYear = $arItem['PROPERTIES']['YEAR']['VALUE'];
                            ?>
                            <li>
                                <a href="#year-<?=$currentYear?>" title="" class="anchor"><?=$currentYear?></a>
                            </li>
                            <?php
                        }
                    }
                    ?>
                </ul>
                <div style="width: 100%; height: 20px;"></div>
            </div>
        </div>
    </div>
    <?php $this->EndViewTarget()?>
<?php endif?>

<?php if(!empty($arResult['ITEMS'])):?>
    <div class="awards__list">
        <?php
        $currentYear = false;
        foreach($arResult["ITEMS"] as $arItem):?>
            <?php
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

            switch(LANGUAGE_ID){

                case 'ru':

                    $name = $arItem['NAME'];
                    $description = $arItem['DISPLAY_PROPERTIES']['HD_DESC']['DISPLAY_VALUE'];

                    break;

                default:

                    $name = $arItem['DISPLAY_PROPERTIES']['NAME' . POSTFIX_PROPERTY]['DISPLAY_VALUE'];
                    $description = $arItem['DISPLAY_PROPERTIES']['HD_DESC' . POSTFIX_PROPERTY]['DISPLAY_VALUE'];

                    break;

            }

            if(!$name){
                continue;
            }

            if(!$currentYear || $currentYear != $arItem['PROPERTIES']['YEAR']['VALUE']){
                $currentYear = $arItem['PROPERTIES']['YEAR']['VALUE'];
                echo '<div id="year-'. $currentYear .'"></div>';
            }


            ?>
            <div id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="awards__item">
                <h3 class="awards__title"><?=$name?></h3>
                <?php if($description):?>
                    <div class="awards__description"><?=$description?></div>
                <?php endif?>
                <?php if(!empty($arItem['DISPLAY_PROPERTIES']['PICTURES']['VALUE'])):?>
                    <div class="slider-img">
                        <?php foreach($arItem['DISPLAY_PROPERTIES']['PICTURES']['VALUE'] as $key => $value):
                            $img = \CFile::ResizeImageGet($value, ['width' => 860, 'height' => 394], BX_RESIZE_IMAGE_PROPORTIONAL, true, $arWaterMark);
                            $big = \CFile::ResizeImageGet($value, ['width' => 2000, 'height' => 2000], BX_RESIZE_IMAGE_PROPORTIONAL, true, $arWaterMark);
                            ?>
                            <div class="slider-img__item">
                                <a href="<?=$big['src']?>" data-fancybox="gallery-1" title="">
                                    <img src="<?=$img['src']?>" alt="<?= $arItem['DISPLAY_PROPERTIES']['PICTURES']['DESCRIPTION'][$key]?>"/>
                                </a>
                            </div>
                        <?php endforeach?>
                    </div>
                <?php endif?>
            </div>

        <?php endforeach?>
    </div>
<?php endif?>