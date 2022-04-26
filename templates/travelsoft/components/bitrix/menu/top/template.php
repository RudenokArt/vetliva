<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();
?>

<?/*if(!empty($arResult)):?>
	<ul class="menu-list text-uppercase ">
	
	<?
	$last_dl = $c_dl = 1;
	$li = $p = "";
	foreach($arResult as $item):
		$c_dl = $item["DEPTH_LEVEL"];
$p = "<a ".($item["PARAMS"]["target"] !== "" ? "target='".$item["PARAMS"]["target"]."'" : ""). " " . ($item["SELECTED"] ? " class='act'" : "") . " href='" . $item["LINK"] . "'>" . $item["TEXT"] . "</a>";
		$l = $item["SELECTED"] ? " class='current-menu-parent'" : "";
	?>
		<?if($last_dl == $c_dl):?>
			
			<?= $li?>
			<li <?$l?>><?= $p?>
			
		<?elseif($last_dl < $item["DEPTH_LEVEL"]):?>
			
			<ul class="sub-menu">
				<li ><?= $p?>	
			
		<?else:?>
				</li>
			</ul>
			<li><?= $p?>

		<?endif;

		$last_dl = $item["DEPTH_LEVEL"];
		$li = "</li>";
		?>
	<?endforeach?>

	<?= str_repeat("</li></ul>", $c_dl)?>
<?endif;return;*/?>
<?if (!empty($arResult)):?>
<ul class="menu-list text-uppercase ">

<?
$previousLevel = 0;
$i = 1;
foreach($arResult as $arItem):?>

    <?if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):?>
        <?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
    <?endif?>

    <?if ($arItem["IS_PARENT"]):?>

			<?if($arItem["SELECTED"]):?>
				<li class="current-menu-parent <?if ($arItem["PARAMS"]["class"] !== ""):?> <?=$arItem["PARAMS"]["class"]?> <?endif;?>">
					<a <?if ($arItem["PARAMS"]["target"] !== ""):?>target="<?=$arItem["PARAMS"]["target"]?>"<?endif;?> href="<?=$arItem["LINK"]?>" class="act"><?=$arItem["TEXT"]?></a><ul class="sub-menu">
			<?else:?>
				<li class="<?if ($arItem["PARAMS"]["class"] !== ""):?> <?=$arItem["PARAMS"]["class"]?> <?endif;?>"><a <?if ($arItem["PARAMS"]["target"] !== ""):?>target="<?=$arItem["PARAMS"]["target"]?>"<?endif;?> href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a><ul class="sub-menu">
			<?endif?>

    <?else:?>



		<?if($arItem["SELECTED"]):?>
			<li class="current-menu-parent <?if ($arItem["PARAMS"]["class"] !== ""):?> <?=$arItem["PARAMS"]["class"]?> <?endif;?>">
				<a  <?if ($arItem["PARAMS"]["target"] !== ""):?>target="<?=$arItem["PARAMS"]["target"]?>"<?endif;?> href="<?=$arItem["LINK"]?>" class="act"><?=$arItem["TEXT"]?></a>
			</li>
		<?else:?>
			<li class="<?if ($arItem["PARAMS"]["class"] !== ""):?> <?=$arItem["PARAMS"]["class"]?> <?endif;?>"><a <?if ($arItem["PARAMS"]["target"] !== ""):?>target="<?=$arItem["PARAMS"]["target"]?>"<?endif;?> href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
		<?endif?>


    <?endif?>

    <?$previousLevel = $arItem["DEPTH_LEVEL"];?>
    <?$i++;?>
<?endforeach?>

<?if ($previousLevel > 1)://close last item tags?>
    <?=str_repeat("</ul></li>", ($previousLevel-1) );?>
<?endif?>

</ul>
<?endif?>
