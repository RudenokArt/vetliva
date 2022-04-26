<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @var $objectManager
 * @var $lang
 *
 */
?>
<?if ($this->filter['minmap']=='Y'):?>
<div id="items-map" style="width: 100%; height: 380px"></div>
<?else:?>
<div id="items-map" class="items-map popup-map"></div>
<?endif;?>
<script src="https://api-maps.yandex.ru/2.1/?lang=<?=$lang?>&apikey=b9173d0e-3418-4811-9139-577afbfa0a9b&onload=itemsMap" async defer></script>
<script>

  function itemsMap(){
    console.log(<?= $objectManager?>);
    var mapCenter = [53.902496, 27.561481],
      map = new ymaps.Map('items-map', {
        center: mapCenter,
        zoom: 9,
        controls: []
      }),
        iblockId = <?=$this->iblockId?>;

    var objectManager = new ymaps.ObjectManager({
      clusterize: false
    });

    objectManager.add(<?= $objectManager?>);

    var center = false;
    var point = [];
    objectManager.objects.each(function(object){
      if(object.properties.selected === 'Y'){
        objectManager.objects.setObjectOptions(object.id, {
          preset: 'islands#darkOrangeDotIcon'
        });
        center = object.geometry.coordinates;

      }
      point.push(object.geometry.coordinates);
    });
    // маршрут
    var multiRoute = new ymaps.multiRouter.MultiRoute({
            referencePoints: point,
            params: {
                results: 1
            }
        }, {
            boundsAutoApply: true,
            routeStrokeColor: "333",
            wayPointFinish: {preset:'islands#orangeDotIcon'},
            routeActiveStrokeColor: "264В87",
        });
        map.geoObjects.add(multiRoute);
        multiRoute.model.events.add('requestsuccess', function() {
            var wayPoints = multiRoute.getWayPoints();  
            wayPoints.each(function (point) {   
                point.options.set({
                    visible: false,
                });
            });    
        });
  
	map.geoObjects.add(objectManager);	


    map.controls.add('zoomControl');

    objectManager.objects.events

      .add('click', function (e) {
        var objectId = e.get('objectId');
        if(objectManager.objects.getById(objectId).properties.selected !== 'Y'){
          window.open( objectManager.objects.getById(objectId).properties.url );
        }
      })

      .add('mouseenter', function(e){
        var objectId = e.get('objectId');
        objectManager.objects.balloon.open(objectId);
      })

    ;

  }

</script>