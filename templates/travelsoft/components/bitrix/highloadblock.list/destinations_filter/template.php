<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!empty($arResult['ERROR']))
{
	echo $arResult['ERROR'];
	return false;
}

//получаем внешние коды опубликованных направлений
use Bitrix\Highloadblock\HighloadBlockTable as HLBT;

CModule::IncludeModule('highloadblock');

$hlblock = HLBT::getById(DESTINATIONS_HL_BLOCK)->fetch();
$entity = HLBT::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();

$rsData = $entity_data_class::getList(array(
   'select' => array('UF_XML_ID'),
   'filter' => array('UF_PUBLISHED' => 1)
));

$publishedDest = array();

while($el = $rsData->fetch()){
    $publishedDest[] = $el['UF_XML_ID'];
}
//--------------------------------------

//получаем выбранные внешние коды направлений из адресной строки для фильтра
$request = Bitrix\Main\Context::getCurrent()->getRequest();
$destPropertyFilter = $request->get('destination_id');

//проверяем выбрано ли хотя бы одно опубликованное в админке направление
$atLeastOnePublished = !empty(array_intersect($destPropertyFilter, $publishedDest)) ? true : false;

//получаем выбранные услуги из адресной строки
$servicePropertyFilter = $request->get('service_id');

?>

<form action="/popular-destinations/" method="get" id="destination-filter-form">
	<div class="popular-destinations-item"> <h3><?=GetMessage("TITLE")?></h3>

	<? foreach ($arResult['rows'] as $row): ?>
		<div class="chouse"><input type="checkbox" class="pick-value" id="destination-<?=$row['ID']?>" name="destination_id[]" value='<?=$row['UF_XML_ID']?>' 
			<?if (in_array($row['UF_XML_ID'], $destPropertyFilter) || empty($destPropertyFilter) || !$atLeastOnePublished) :?>
				checked
			<?endif;?>
		/>
		<label for="destination-<?=$row['ID']?>"><?= LANGUAGE_ID == "ru" ? $row['UF_NAME'] : $row['UF_NAME_'.strtoupper(LANGUAGE_ID)] ?></label></div>			
	<? endforeach; ?></div>

	<h3><?=GetMessage("SERVICES_TITLE")?></h3>

	<div class="chouse"><input type="checkbox" class="pick-value" id="service-<?=PLACEMENTS_IBLOCK_ID?>" name="service_id[]" value="<?=PLACEMENTS_IBLOCK_ID?>" 
	<?if (in_array(PLACEMENTS_IBLOCK_ID, $servicePropertyFilter) || empty($servicePropertyFilter)) :?>
		checked
	<?endif;?>
	/>
	<label for="service-<?=PLACEMENTS_IBLOCK_ID?>"><?=GetMessage("SERVICES_ACCOMODATIONS")?></label></div>
	
	<div class="chouse"><input type="checkbox" class="pick-value" id="service-<?=SANATORIUM_IBLOCK_ID?>" name="service_id[]" value="<?=SANATORIUM_IBLOCK_ID?>" 
	<?if (in_array(SANATORIUM_IBLOCK_ID, $servicePropertyFilter) || empty($servicePropertyFilter)) :?>
		checked
	<?endif;?>
	/>
	<label for="service-<?=SANATORIUM_IBLOCK_ID?>"><?=GetMessage("SERVICES_SANATORIUMS")?></label></div>

	<div class="chouse"><input type="checkbox" class="pick-value" id="service-<?=EXCURSION_IBLOCK_ID?>" name="service_id[]" value="<?=EXCURSION_IBLOCK_ID?>" 
	<?if (in_array(EXCURSION_IBLOCK_ID, $servicePropertyFilter) || empty($servicePropertyFilter)) :?>
		checked
	<?endif;?>
	/>
	<label for="service-<?=EXCURSION_IBLOCK_ID?>"><?=GetMessage("SERVICES_TOURS_EXCURSIONS")?></label></div>
</form>

<script>
	$('.pick-value').change(function(){
		$('#destination-filter-form').submit();
	});
</script>