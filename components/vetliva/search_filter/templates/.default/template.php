<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
// $APPLICATION->SetAdditionalCSS("/bitrix/css/main/font-awesome.css");
?>

<!-- <script src="https://api-maps.yandex.ru/2.1/?apikey=fef1e22c-2d6e-4fbb-9eac-c5a1ab864609&lang=ru_RU" type="text/javascript"></script> -->
<!-- <div id="yandex_map" class="yandex_map"></div> -->

<div class="container">
  <div class="search_filter">
    <form action="" method="get">
      <div class="search_filter-search">
        <input placeholder="<?php echo GetMessage('search_placeholder');?>"
        <?php if (isset($_GET['search'])): ?>
          value="<?php echo $_GET['search']; ?>"
        <?php endif ?>
        type="text" name="search">
      <button class="search_filter-search_button">
        <i class="fa fa-search" aria-hidden="true"></i>
      </button>
      </div>
      <div class="search_filter-filter">
        <label>
          <input <?php if (isset($_GET['accomodation']) and $_GET['accomodation'] == 'on'): ?>
            checked="checked"
          <?php endif ?> type="checkbox" name="accomodation">
          <span><?php echo GetMessage('accomodation');?></span>
        </label>
        <label>
          <input <?php if (isset($_GET['sanatorium']) and $_GET['sanatorium'] == 'on'): ?>
            checked="checked"
          <?php endif ?> type="checkbox"  name="sanatorium">
          <span><?php echo GetMessage('sanatorium');?></span>
        </label>
        <label>
          <input <?php if (isset($_GET['tours']) and $_GET['tours'] == 'on'): ?>
            checked="checked"
          <?php endif ?> type="checkbox"  name="tours">
          <span><?php echo GetMessage('tours');?></span>
        </label>
      </div>
    </form>
  </div>
  <?php include 'pagination.php'; ?>

<div class="search_filter-list">
  <?php foreach ($arResult['page'] as $key => $value): ?>
    <?php if ($value): ?>
      <div class="search_filter-list_item">
        <div class="search_filter-list_item-slider">
          <?php foreach ($value['pictures'] as $subkey => $subvalue): ?>
            <div style="background-image: url(<?php echo $subvalue; ?>);" class="search_filter-list_item-slider_image"></div>
          <?php endforeach ?>
        </div>
        <div class="search_filter-list_item-text">
          <a href="<?php echo searchFilterGetDetailPageUrl($value['IBLOCK_CODE'], $value['CODE'], $value['ID']);?>"
            class="search_filter-list_item-title">
            <?php echo getTextLanguage($value['NAME'], $value['NAME_BY'], $value['NAME_EN']); ?>
          </a>
          <?php if ( $value['ADDRESS']): ?>
            <div>
              <i class="fa fa-map-marker" aria-hidden="true"></i>
              <?php echo getTextLanguage($value['ADDRESS'], $value['ADDRESS_EN'], $value['ADDRESS_BY']); ?>
            </div>
          <?php endif ?>
          <?php if ($value['DISTANCE_AIRPORT']): ?>
            <div>
              <i class="fa fa-plane" aria-hidden="true"></i> 
              <?php echo GetMessage('DISTANCE_AIRPORT'); ?>
              <?php echo $value['DISTANCE_AIRPORT']; ?> км
            </div>
          <?php endif ?>
          <?php if ($value['DISTANCE_CENTER']): ?>
            <div>
              <i class="fa fa-info-circle" aria-hidden="true"></i>
              <?php echo GetMessage('DISTANCE_CENTER'); ?>
              <?php echo $value['DISTANCE_CENTER']; ?> км
            </div>
          <?php endif ?>
          <?php if ($value['DEPARTURE_TIME']): ?>
            <div>
              <i class="fa fa-clock-o" aria-hidden="true"></i>
              <?php echo GetMessage('DEPARTURE_TIME'); ?>:
              <?php echo $value['DEPARTURE_TIME']; ?>
            </div>
          <?php endif ?>
          <?php if ($value['DURATION_TIME']): ?>
            <div>
              <i class="fa fa-hourglass-half" aria-hidden="true"></i>
              <?php echo GetMessage('DURATION_TIME'); ?>:
              <?php echo $value['DURATION_TIME']; ?>
              <?php echo GetMessage('HOURS'); ?>
            </div>
          <?php endif ?>
          <?php if ($value['FOOD']): ?>
            <div>
              <i class="fa fa-cutlery" aria-hidden="true"></i>
              <?php echo GetMessage('FOOD'); ?>:
              <?php echo $value['FOOD']; ?>
            </div>
          <?php endif ?>
          <?php if ($value['DISTANCE_MINSK']): ?>
            <div>
              <i class="fa fa-info-circle" aria-hidden="true"></i>
              <?php echo GetMessage('DISTANCE_MINSK'); ?>:
              <?php echo $value['DISTANCE_MINSK']; ?> км
            </div>
          <?php endif ?>
          <?php if ($value['NEAREST_TOWN']): ?>
            <div>
              <i class="fa fa-info-circle" aria-hidden="true"></i>
              <?php echo GetMessage('NEAREST_TOWN'); ?>:
              <?php echo $value['NEAREST_TOWN']; ?> км
            </div>
          <?php endif ?>
          <?php if ($value['MAP']): ?>
            <div>
              <a data-search_filter_map="<?php echo $value['MAP'];?>" href="#" class="search_filter-map_show_button">
                <i class="fa fa-map-o" aria-hidden="true"></i> 
                <?php echo GetMessage('MAP'); ?>
              </a>
            </div>
          <?php endif ?>
          <?php if ($value['SERVICES']): ?>
            <div class="search_filter-list_item-text-features">
              <i class="fa fa-asterisk" aria-hidden="true"></i>
              <?php foreach ($value['SERVICES'] as $key1 => $value1): ?>
                <i><?php echo $value1; ?>, </i>
              <?php endforeach ?>
            </div>
          <?php endif ?>

        </div>
        <div class="search_filter_detail_page_url">
          <a href="<?php echo searchFilterGetDetailPageUrl($value['IBLOCK_CODE'], $value['CODE'], $value['ID']);?>">
            <?php echo GetMessage('DETAIL_PAGE_URL'); ?>
            <i class="fa fa-chevron-right" aria-hidden="true"></i>
          </a>
        </div>
      </div>
      <hr>
    <?php endif ?>
  <?php endforeach ?>
</div>
<?php include 'pagination.php'; ?>
</div>

<div class="search_filter-yandex_map-popup-wrapper">
  <div class="search_filter-yandex_map-popup-inner">
    <div class="search_filter-yandex_map-popup-close_button">
      <i class="fa fa-times" aria-hidden="true"></i>
    </div>
    <div id="yandex_map" class="yandex_map"></div>
  </div>
</div>


<script>
  var yandex_map_point = [];
  $('.search_filter-list_item-slider').slick();
  $('.search_filter-map_show_button').click(function () {
    $('.search_filter-yandex_map-popup-wrapper').css({'display':'flex'});
    yandex_map_point = $(this).attr('data-search_filter_map').split(',');
    ymaps.ready(init);
  });

  $('.search_filter-yandex_map-popup-close_button').click(function(){
    $('.search_filter-yandex_map-popup-wrapper').css({'display':'none'});
  });
  
  function init(){
    var search_filter_map = new ymaps.Map("yandex_map", {
      center: yandex_map_point,
      zoom: 12,
      controls: ['zoomControl'],
    });
    var myPlacemark = new ymaps.Placemark(yandex_map_point);
    search_filter_map.geoObjects.add(myPlacemark);
    $('.search_filter-yandex_map-popup-close_button').click(function(){
      $('.search_filter-yandex_map-popup-wrapper').css({'display':'none'});
      search_filter_map.destroy();
    });
  }
  
</script>
