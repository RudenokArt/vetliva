<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();
?>

<?if(!empty($arResult)):?>
	<ul class="menu-list text-uppercase">
	
	<?
	$last_dl = $c_dl = 1;
	$li = $p = "";
	foreach($arResult as $item):
		$c_dl = $item["DEPTH_LEVEL"];
		$p = "<a" . ($item["SELECTED"] ? " class='act'" : "") . " href='" . $item["LINK"] . "'>" . $item["TEXT"] . "</a>";
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
<?endif;return;?>
