<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$search_filter = new SearchFilter();
?>

<script src="https://api-maps.yandex.ru/2.1/?apikey=fef1e22c-2d6e-4fbb-9eac-c5a1ab864609&lang=ru_RU" type="text/javascript"></script>
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
        <label>
          <span><?php echo GetMessage('REGION');?>:</span>
          <select name="region">
            <option value="">---</option>
            <?php foreach ($search_filter->regions_list as $key => $value): ?>
              <option
              <?php if (isset($_GET['region']) and $_GET['region']===$value['ID']): ?>
                selected
                <?php endif ?> value="<?php echo $value['ID']; ?>" >
                <?php echo getTextLanguage($value['NAME'],$value['PROPERTY_NAME_BY_VALUE'],$value['PROPERTY_NAME_EN_VALUE']); ?>              
              </option>
            <?php endforeach ?>
          </select>
        </label>
        <label id="search_filter-city" style="display:none">
          <span><?php echo GetMessage('CITY');?>:</span>
          <select name="city">
            <option value="N">---</option>
            <?php foreach ($search_filter->cities_list as $key => $value): ?>
              <option data-region="<?php echo $value['PROPERTY_REGION_VALUE'] ?>"
                <?php if (isset($_GET['city']) and $_GET['city']===$value['ID']): ?>
                  selected
                  <?php endif ?> value="<?php echo $value['ID']; ?>">
                  <?php echo getTextLanguage($value['NAME'],$value['PROPERTY_NAME_BY_VALUE'],$value['PROPERTY_NAME_EN_VALUE']); ?>            
                </option>
              <?php endforeach ?>
            </select>
          </label>
        </div>
      </form>
    </div>
    <?php include 'pagination.php' ?>

  



    <div class="search_filter-list">
      <?php foreach ($search_filter->items_list['items'] as $key => $value): ?>
        <div class="search_filter-list_item">
          <div class="search_filter-list_item-slider">
            <?php foreach (SearchFilter::getItemPictures($value['IBLOCK_ID'], $value['ID'], [], ['CODE'=>'PICTURES']) as $key1 => $value1): ?>
              <div style="background-image: url(<?php echo $value1 ?>);" class="search_filter-list_item-slider_image"></div>
            <?php endforeach ?>
            
          </div>
          <div class="search_filter-list_item-text">
            <a href="<?php echo SearchFilter::getDetailPageUrl($value['IBLOCK_CODE'], $value['CODE'], $value['ID']) ?>"
              class="search_filter-list_item-title">
              <?php echo getTextLanguage($value['NAME'], $value['PROPERTY_NAME_BY_VALUE'], $value['PROPERTY_NAME_EN_VALUE']); ?>
            </a>
            <div>
              <i class="fa fa-map-marker" aria-hidden="true"></i>
              <?php echo getTextLanguage(
                  SearchFilter::getItemProperties($value['IBLOCK_ID'], $value['ID'], [], ['CODE'=>'ADDRESS'])[0]['VALUE'],
                  SearchFilter::getItemProperties($value['IBLOCK_ID'], $value['ID'], [], ['CODE'=>'ADDRESS_BY'])[0]['VALUE'],
                  SearchFilter::getItemProperties($value['IBLOCK_ID'], $value['ID'], [], ['CODE'=>'ADDRESS_EN'])[0]['VALUE']
                );?>
            </div>
            <div>
              <i class="fa fa-plane" aria-hidden="true"></i> 
            </div>
            <div>
              <i class="fa fa-info-circle" aria-hidden="true"></i>
            </div>
            <div>
              <i class="fa fa-clock-o" aria-hidden="true"></i>
            </div>
            <div>
              <i class="fa fa-hourglass-half" aria-hidden="true"></i>
            </div>
            <div>
              <i class="fa fa-cutlery" aria-hidden="true"></i>
            </div>
            <div>
              <i class="fa fa-info-circle" aria-hidden="true"></i>
            </div>
            <div>
              <i class="fa fa-info-circle" aria-hidden="true"></i>
            </div>
            <div>
              <a data-search_filter_map="<?php echo $value['MAP'];?>" href="#" class="search_filter-map_show_button">
                <i class="fa fa-map-o" aria-hidden="true"></i> 
              </a>
            </div>
            <div class="search_filter-list_item-text-features">
              <i class="fa fa-asterisk" aria-hidden="true"></i>
            </div>

          </div>
          <div class="search_filter_detail_page_url">
            <a href="">
              <i class="fa fa-chevron-right" aria-hidden="true"></i>
            </a>
          </div>
        </div>
        <hr>
      <?php endforeach ?>
      
    </div>
    <?php include 'pagination.php'; ?>
  </div>
  <pre><?php 
// print_r($search_filter->regions_list);
// print_r($search_filter->cities_list);
// print_r($search_filter->search_filter_by_name_adress_en);
  // print_r($search_filter->search_filter_by_adress);
print_r($search_filter->items_list);
?></pre>
  <div class="search_filter-yandex_map-popup-wrapper">
    <div class="search_filter-yandex_map-popup-inner">
      <div class="search_filter-yandex_map-popup-close_button">
        <i class="fa fa-times" aria-hidden="true"></i>
      </div>
      <div id="yandex_map" class="yandex_map"></div>
    </div>
  </div>
</div>


<script>
  if ($('select[name="region"]').prop('value')) {
    search_filter_city_select(document.querySelector('select[name="region"]'));
  }
  $('select[name="region"]').change(function () {
    $('select[name="city"]').prop('value','N');
    search_filter_city_select(this);
  });

  function search_filter_city_select (node) {
    $('#search_filter-city').css({'display':'inline'});
    var arr = $('select[name="city"]').children('option');
    $(arr).css({'display':'none'});
    for (var i = 0; i < arr.length; i++) {
      if (node.value === arr[i].getAttribute('data-region')) {
        $(arr[i]).css({'display':'block'});
      }
      if (arr[i].value=='N') {$(arr[i]).css({'display':'block'});}
    }
  }

  $('.search_filter-list_item-slider').slick();
</script>
