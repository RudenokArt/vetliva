<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>

<!-- Main navigation -->
<div class="sidebar-category sidebar-category-visible">
        <div class="category-content no-padding">
            <ul class="navigation navigation-main navigation-accordion">
<?
$previousLevel = 0;
foreach($arResult as $arItem):?>
                
                <?if ($arItem["PERMISSION"] <= "D") { continue; }?>

	<?if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):?>
		<?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
	<?endif?>

	<?if ($arItem["IS_PARENT"]):?>
                        
                        <li <?if ($arItem["SELECTED"]):?>class="active"<?endif?>>
                            <a  class="has-sub-menu" href=""><?if ($arItem['PARAMS']['icon-class']):?><i class="<?= $arItem['PARAMS']['icon-class']?>"></i><?endif?> <span><?=$arItem["TEXT"]?></span></a>
                                <ul>
		
	<?else:?>

                        <?if ($arItem["PERMISSION"] > "D"):?>

                                <li <?if ($arItem["SELECTED"]):?>class="active"<?endif?>><a  href="<?= $arItem['LINK']?>" ><?if ($arItem['PARAMS']['icon-class']):?><i class="<?= $arItem['PARAMS']['icon-class']?>"></i><?endif?> <span><?= $arItem['TEXT']?></span></a></li>

                        <?/*else:*/?>

                        <?endif?>

	<?endif?>

	<?$previousLevel = $arItem["DEPTH_LEVEL"];?>

<?endforeach?>

<?if ($previousLevel > 1)://close last item tags?>
	<?=str_repeat("</ul></li>", ($previousLevel-1) );?>
<?endif?>
                                
            </ul>
        </div>
</div>
<!-- /main-navigation -->                 
<?endif?>