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
<div id="items-map" class="items-map popup-map"></div>
<script src="https://api-maps.yandex.ru/2.1/?lang=<?=$lang?>&onload=itemsMap" async defer></script>
<script>

  function itemsMap(){

    var mapCenter = [53.902496, 27.561481],
      map = new ymaps.Map('items-map', {
        center: mapCenter,
        zoom: 9,
        controls: []
      }),
        iblockId = <?=$this->iblockId?>;

    var objectManager = new ymaps.ObjectManager({
      clusterize: true
    });

    objectManager.add(<?= $objectManager?>);

    var center = false;
    objectManager.objects.each(function(object){
      if(object.properties.selected === 'Y'){
        objectManager.objects.setObjectOptions(object.id, {
          preset: 'islands#darkOrangeDotIcon'
        });
        center = object.geometry.coordinates;

      }
    });

    map.geoObjects.add(objectManager);

    if(center){

      var zoom;

      switch(iblockId){
        case 8:
          zoom = 14;
          break;
        default:
          zoom = 16;
          break;
      }

      map.setCenter(center, zoom);
    }

    //map.behaviors.disable('scrollZoom');

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