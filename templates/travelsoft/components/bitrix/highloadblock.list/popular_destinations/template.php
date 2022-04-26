<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!empty($arResult['ERROR']))
{
	echo $arResult['ERROR'];
	return false;
}

//$GLOBALS['APPLICATION']->SetTitle('Highloadblock List');

?>

<div class="title-wrap">
	<div class="container">
		<div class="travel-title">
			<h2><?=GetMessage("TITLE")?></h2>
		</div>		
	</div>
</div>

<div class="container">
	<div class="popular-destinations">
		<? foreach ($arResult['rows'] as $row): ?>
			<div class="col-lg-3 col-lg-3 col-md-6 col-xs-6 pd-item">
				<!-- картинка скидка-новинка-топ -->
				<? if($row['UF_TOP']) :?>
					<div class="top-left-corner red"><?=GetMessage("TOP")?><span class="top-images"><img src="<?=$templateFolder?>/images/top.png"></span></div>
				<? elseif($row['UF_NEW']) : ?>	
					<div class="top-left-corner yellow"><?=GetMessage("NEW")?></div>
				<? elseif($row['UF_STOCK']) : ?>	
					<div class="top-left-corner red"><?=GetMessage("STOCK")?></div>
				<? endif; ?>	
				
				<div class="link">
					<? 		
						if (!empty($arParams['DETAIL_URL'])){
							$url = str_replace('#XML_ID#', $row['UF_XML_ID'], $arParams['DETAIL_URL']);							
						}						
					?>
					<a href="<?=htmlspecialcharsbx($url)?>">
						<?php if(!empty($row['UF_FILE'])) :?>
							<?=$row['UF_FILE']?>
						<?php endif; ?>	
						<span><?= LANGUAGE_ID == "ru" ? $row['UF_NAME'] : $row['UF_NAME_'.strtoupper(LANGUAGE_ID)] ?>
						</span>
					</a>
				</div>
			</div>
		<? endforeach; ?>	
	</div>
</div>