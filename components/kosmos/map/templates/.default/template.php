<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

$this->addExternalCss('/local/templates/travelsoft/css/icomoon/style.min.css');
$this->addExternalCss('/local/templates/travelsoft/css/select2/select2.min.css');

$this->addExternalJs('/local/templates/travelsoft/js/select2/select2.min.js');
$this->addExternalJs('https://api-maps.yandex.ru/2.1/?lang=' . $arParams['MAP_LANGUAGE'] . '&apikey=' . $arParams['MAP_API_KEY']);
?>

<?
$is_mobile = check_smartphone();
if ($is_mobile):?>
<script>
(function ($) {
$(document).ready(function (){
	$(".magnificbutton").magnificPopup({
        type: "inline",
		mainClass: 'mfp-wide-mobile',
        midClick: true
    });
});
})(jQuery, document);
</script>


<?endif;?>

<div class="col-md-3 col-md-push-0">
	<div class="sidebar-cn">
		<?if ($is_mobile):?>
			<div class="filter-block map_filter_block  mobile-filtr-sort-block"><a class="magnificbutton show-filter-link" href="#filter_area"><?= Loc::getMessage('T_FILTER_NAME') ?></a> </div>
		<?endif;?>
		<form id="filter_area" action="" class="js-form-filter-map <?if ($is_mobile):?>header-auth-form mfp-hide<?endif;?>">
			<?php if (!empty($arResult['REGION'])): ?>
				<div class="widget-sidebar facilities-sidebar map-filter <?if ($is_mobile):?>header-auth-form mfp-hide<?endif;?>">
					<h4 class="title-sidebar"><?= Loc::getMessage('T_MAP_REGION') ?></h4>
					<div class="checkbox-map-wrp">
						<?php foreach ($arResult['REGION'] as $arItem): ?>
							<div class="radio-checkbox">
								<input
										class="checkbox"
										type="checkbox"
										id="checkbox_region_<?= $arItem['ID'] ?>"
										name="REGION"
										value="<?= $arItem['ID'] ?>"
									<?= (($arResult['REQUEST']['REGION'] == $arItem['ID']) || in_array($arItem['ID'], $arResult['REQUEST']['REGION'])) ? 'checked' : '' ?>
								>
								<label class="label-checkbox" for="checkbox_region_<?= $arItem['ID'] ?>"><?= $arItem['NAME'] ?></label>
							</div>
						<?php endforeach ?>
					</div>
				</div>
			<?php endif ?>
			<?php if (!empty($arParams['TYPES'])): ?>
				<div class="widget-sidebar facilities-sidebar map-filter">
					<h4 class="title-sidebar"><?= Loc::getMessage('T_MAP_TYPE') ?></h4>
					<div class="checkbox-map-wrp">

						<?php foreach ($arParams['TYPES'] as $arType): ?>

							<?php $haveType = ($arType['TYPE'] && !empty($arResult['DATA'][$arType['TYPE']])); ?>

							<div class="radio-checkbox<?= ($haveType) ? ' radio-checkbox-wrp' : '' ?>">
								<input
										class="checkbox<?= ($haveType) ? ' js-checkbox-all' : '' ?>"
										type="checkbox"
										id="checkbox_type_<?= $arType['IBLOCK_ID'] ?>"
										name="TYPE"
										value="<?= $arType['IBLOCK_ID'] ?>"
									<?= (($arResult['REQUEST']['TYPE'] == $arType['IBLOCK_ID']) || in_array($arType['IBLOCK_ID'], $arResult['REQUEST']['TYPE'])) ? 'checked' : '' ?>
								>
								<label class="label-checkbox" for="checkbox_type_<?= $arType['IBLOCK_ID'] ?>"><?= Loc::getMessage('T_MAP_TYPE_' . $arType['IBLOCK_ID']) ?></label>
								<?php if ($haveType): ?>
									<select placeholder="<?= Loc::getMessage('T_MAP_TYPE_' . $arType['TYPE']) ?>" name="TYPE_<?= $arType['TYPE'] ?>" class="select2-smart-filter js-new-select new-select" multiple="multiple">
										<?php foreach ($arResult['DATA'][$arType['TYPE']] as $arItem): ?>
											<option value="<?= $arItem['ID'] ?>"><?= $arItem['NAME'] ?></option>
										<?php endforeach ?>
									</select>
								<?php endif ?>
							</div>

						<?php endforeach ?>
					</div>
				</div>
			<?php endif ?>
		</form>
	</div>
</div>
<div class="col-md-9 col-md-pull-0 content-page-detail">
	<h1><?= Loc::getMessage('T_MAP_TITLE') ?></h1>
	<div id="map" style="height: 500px"></div>
</div>
<?//php var_dump($arResult['FEATURE_COLLECTION'])?>
<style>
	select.new-select {
		opacity: 0;
		width: 1px !important;
		height: 1px !important;
	}

	.radio-checkbox-wrp {
		margin-bottom: 10px;
	}

	.map-filter .title-sidebar {
		margin-bottom: 15px;
	}

	.js-not-all-check + .label-checkbox:before {
		border-color: #264B87;
	}

	.checkbox-wrp .checkbox {
		display: inline-block;
	}

	.checkbox-wrp .checkbox-map-wrp {
		margin-left: 20px;
	}
</style>
<script>
	var ymaps;

	// Инициализировать карту
	function init() {
		var winWidth = $(window).width(),
			myMap = document.getElementById("map");
		if (!myMap) return;

		myMap = new ymaps.Map(myMap, {
			center: [52.858248, 27.701393],
			zoom: 6,
			controls: ['zoomControl', 'fullscreenControl']
		}), objectManager = new ymaps.ObjectManager({
			clusterize: true
		});

		//myMap.behaviors.disable('scrollZoom');
		if (winWidth <= 1024) {
			myMap.behaviors.disable('drag');
		}

		objectManager.add(<?=$arResult['FEATURE_COLLECTION']?>);
		objectManager.clusters.options.set({
			clusterIconLayout: 'default#pieChart',
			clusterIconPieChartRadius: 25,
			clusterIconPieChartCoreRadius: 16,
			clusterIconPieChartStrokeWidth: 2,
			hasBalloon: false
		});


		myMap.geoObjects.add(objectManager);
		if (myMap.geoObjects.getBounds()) {
			myMap.setBounds(myMap.geoObjects.getBounds(), {checkZoomRange: true, zoomMargin: 40});
		}


		//*****************
		function loadBalloonData(objectId) {
			var dataDeferred = ymaps.vow.defer();

			function resolveData() {

				var obj = objectManager.objects.getById(objectId);

				BX.ajax.runComponentAction('kosmos:map',
					'getInfo', {
						mode: 'class',
						data: {
							post: {
								id: objectId,
								iblockId: obj.properties.typeObj
							}
						},
					})
					.then(function (response) {
						if (response.status === 'success') {
							dataDeferred.resolve(response.data.html);
						}
					});

			}

			resolveData();
			return dataDeferred.promise();
		}

		function hasBalloonData(objectId) {
			return objectManager.objects.getById(objectId).properties.balloonContent;
		}

		objectManager.objects.events.add('click', function (e) {
			var objectId = e.get('objectId'),
				obj = objectManager.objects.getById(objectId);
			if (hasBalloonData(objectId)) {
				objectManager.objects.balloon.open(objectId);
			} else {
				obj.properties.balloonContent = "Загрузка...";
				objectManager.objects.balloon.open(objectId);
				loadBalloonData(objectId).then(function (data) {
					obj.properties.balloonContent = data;
					objectManager.objects.balloon.setData(obj);
				});
			}
		});
		//*********************

		$(".js-new-select").select2({
			allowClear: true
		});

		setMapFilter();

		$('body').on('change', '.js-form-filter-map .checkbox,.js-form-filter-map select', function () {
			setMapFilter($(this));
		});
		setMapFilter();

		function checkedSelect(that) {
			var parent = that.closest('.radio-checkbox-wrp'),
				selectField = parent.find('select.js-new-select'),
				checkboxAll = parent.find('.js-checkbox-all');

			checkboxAll.removeClass('js-not-all-check');


			if (that.hasClass('js-checkbox-all') && that.prop('checked')) {
				var selected = [];
				selectField.find("option").each(function (i, e) {
					selected[selected.length] = $(e).attr("value");
				});
				selectField.select2('val', selected);
			} else if (that.hasClass('js-checkbox-all') && !that.prop('checked')) {
				selectField.select2('val', '');
			}


			if (that.hasClass('js-new-select')) {
				var selected = false,
					noselected = false;

				selectField.find("option").each(function (i, e) {
					if ($(e).prop('selected')) {
						selected = true;
					} else {
						noselected = true;
					}
				});

				if (selected && !noselected) {
					checkboxAll.removeClass('js-not-all-check').prop('checked', 'checked');
				} else if (selected && noselected) {
					checkboxAll.addClass('js-not-all-check').prop('checked', '');
				} else {
					checkboxAll.removeClass('js-not-all-check').prop('checked', '');
				}
			}
		}

		function setMapFilter(objectEventChange = false) {
			if (!objectEventChange) {
				$(".radio-checkbox-wrp .checkbox").each(function () {
					checkedSelect($(this));
				});
			} else {
				checkedSelect(objectEventChange);
			}



			var data = $('.js-form-filter-map').serializeArray(),
				arData = {};

			$.each(data, function () {
				this.value = String(this.value);
				if(this.name == 'TYPE_ABODE' || this.name == 'TYPE_LIONS'){
					this.name = 'SUB_TYPE';
				}
				if (arData[this.name]) {
					if (!arData[this.name].push) {
						arData[this.name] = [arData[this.name]];
					}
					arData[this.name].push(this.value);
				} else {
					arData[this.name] = [this.value];
				}
			});

			objectManager.setFilter(function (object) {

				var flagRegion = true,
					flagType = true,
					flagSubtype = true;

				if(arData['REGION']){
					flagRegion = arData['REGION'].indexOf(String(object.properties.region)) !== -1;
				}

				if(arData['TYPE']){
					flagType = arData['TYPE'].indexOf(String(object.properties.typeObj)) !== -1;
				}
				else if(arData['SUB_TYPE']){
                  flagType = false;
                }

				if(arData['SUB_TYPE']){
					var resSubtype = arData['SUB_TYPE'].filter(value => -1 !== object.properties.subtypeObj.indexOf(value));
					flagSubtype = resSubtype.length > 0;
				}
				else{
                  flagSubtype = false;
                }

				return flagRegion && (flagType || flagSubtype);

			});


			if (myMap.geoObjects.getBounds()) {
				myMap.setBounds(myMap.geoObjects.getBounds(), {checkZoomRange: true, zoomMargin: 40});
			}
		}
	}


	$(document).ready(function () {

		if ($('.header') == undefined) {
			document.location.href = '/';
		}
		if (ymaps != undefined) ymaps.ready(init);
	});
</script>