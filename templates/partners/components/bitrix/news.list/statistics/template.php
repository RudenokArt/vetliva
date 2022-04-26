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

if (empty($arResult["ITEMS"])) {
    return false;
}

?>
<div class="panel panel-flat col-lg-6 mr-20">
		<div class="panel-heading">
			<h6 class="panel-title"><?= $arParam["TITLE"]?></h6>
			<div class="heading-elements">
				<ul class="icons-list">
					<li><a data-action="collapse" ></a></li>
					<li><a data-action="reload" ></a></li>
					<li><a data-action="close" ></a></li>
				</ul>
			</div>
		</div>
		<div class="panel-body">
			<div class="row">
<div class="table-responsive">
		<table class="table text-nowrap">
		<thead>
		<tr>
			<th>
				 Объект
			</th>
			<th>
				 Дата начала показа
			</th>
			<th>
				 Количество показов
			</th>
		</tr>
		</thead>
		<tbody>
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>

		<tr>
			<td>
				<div class="media-body">
					<div class="media-heading">
 <a href="<?echo $arItem["DETAIL_PAGE_URL"]?>" target="_blank" class="letter-icon-title"><?echo $arItem["NAME"]?></a>
					</div>
				</div>
			</td>
			<td>
 <span class="text-muted text-size-small"><?echo $arItem['SHOW_COUNTER_START']?></span>
			</td>
			<td>
				<h6 class="text-semibold no-margin"><?echo $arItem["SHOW_COUNTER"]?></h6>
			</td>
		</tr>
<?endforeach;?>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
		</tr>
		</tbody>
		</table>
	</div>

			</div>
		</div>
	</div>