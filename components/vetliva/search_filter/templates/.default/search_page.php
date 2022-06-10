    <div class="search_filter-list">
      <?php foreach ($search_filter->items_list['items'] as $key => $value): ?>
        <div class="search_filter-list_item">
          <div class="search_filter-list_item-slider">
            <?php foreach (SearchFilter::getItemPictures($value['IBLOCK_ID'], $value['ID'], [], ['CODE'=>'PICTURES']) as $key1 => $value1): ?>
              <div style="background-image: url(<?php echo $value1 ?>);" class="search_filter-list_item-slider_image"></div>
            <?php endforeach ?>
          </div>
          <div class="search_filter-list_item-text">
            <?php $detail_page_url = SearchFilter::getDetailPageUrl($value['IBLOCK_CODE'], $value['CODE'], $value['ID']); ?>
            <a href="<?php echo $detail_page_url; ?>"
              class="search_filter-list_item-title">
              <?php echo getTextLanguage($value['NAME'], $value['PROPERTY_NAME_BY_VALUE'], $value['PROPERTY_NAME_EN_VALUE']); ?>
            </a>
            <div>
              <i class="fa fa-map-marker" aria-hidden="true"></i
>              <?php echo getTextLanguage(
                SearchFilter::getItemProperties($value['IBLOCK_ID'], $value['ID'], [], ['CODE'=>'ADDRESS'])[0]['VALUE'],
                SearchFilter::getItemProperties($value['IBLOCK_ID'], $value['ID'], [], ['CODE'=>'ADDRESS_BY'])[0]['VALUE'],
                SearchFilter::getItemProperties($value['IBLOCK_ID'], $value['ID'], [], ['CODE'=>'ADDRESS_EN'])[0]['VALUE']
              );?>
              <?php $arResult['TOWN'] = SearchFilter::getItemProperties($value['IBLOCK_ID'], $value['ID'], [], [
                'CODE'=>'TOWN'
              ])[0]['VALUE'];  ?>
              <?php if ($arResult['TOWN']): ?>
                <i>,
                  <?php echo getTextLanguage(
                    SearchFilter::getItemById($arResult['TOWN'])['NAME'],
                    SearchFilter::getItemProperties(5, $arResult['TOWN'], [], ['CODE'=>'NAME_BY'])[0]['VALUE'],
                    SearchFilter::getItemProperties(5, $arResult['TOWN'], [], ['CODE'=>'NAME_EN'])[0]['VALUE'],
                  );?>
                </i>
                
              <?php endif ?>
              <?php $arResult['REGION'] = SearchFilter::getItemProperties($value['IBLOCK_ID'], $value['ID'], [], [
                'CODE'=>'REGION'
              ])[0]['VALUE'];  ?>
              <?php if ($arResult['REGION']): ?>
                 <i>,
                  <?php echo getTextLanguage(
                    SearchFilter::getItemById($arResult['REGION'])['NAME'],
                    SearchFilter::getItemProperties(4, $arResult['REGION'], [], ['CODE'=>'NAME_BY'])[0]['VALUE'],
                    SearchFilter::getItemProperties(4, $arResult['REGION'], [], ['CODE'=>'NAME_EN'])[0]['VALUE'],
                  );?>
                </i>
              <?php endif ?>
            </div>
            <div>
              <?php $arResult['DISTANCE_AIRPORT'] = SearchFilter::getItemProperties($value['IBLOCK_ID'], $value['ID'], [], [
                'CODE'=>'DISTANCE_AIRPORT'
              ])[0]['VALUE'];  ?>
              <?php if ($arResult['DISTANCE_AIRPORT']): ?>
                <i class="fa fa-plane" aria-hidden="true"></i> 
                <?php echo GetMessage('DISTANCE_AIRPORT'); ?>:
                <?php echo $arResult['DISTANCE_AIRPORT']; ?> км
              <?php endif ?>
            </div>
            <div>
              <?php $arResult['DISTANCE_CENTER'] = SearchFilter::getItemProperties($value['IBLOCK_ID'], $value['ID'], [], [
                'CODE'=>'DISTANCE_CENTER'
              ])[0]['VALUE'];  ?>
              <?php if ($arResult['DISTANCE_CENTER']): ?>
                <i class="fa fa-info-circle" aria-hidden="true"></i>
                <?php echo GetMessage('DISTANCE_CENTER'); ?>:
                <?php echo $arResult['DISTANCE_CENTER']; ?> км
              <?php endif ?>
            </div>
            <div>
              <?php $arResult['DEPARTURE_TIME'] = SearchFilter::getItemProperties($value['IBLOCK_ID'], $value['ID'], [], [
                'CODE'=>'DEPARTURE_TIME'
              ])[0]['VALUE'];  ?>
              <?php if ($arResult['DEPARTURE_TIME']): ?>
                <i class="fa fa-clock-o" aria-hidden="true"></i>
                <?php echo GetMessage('DEPARTURE_TIME'); ?>:
                <?php echo $arResult['DEPARTURE_TIME']; ?>
                <?php echo GetMessage('HOURS'); ?>
              <?php endif ?>
            </div>
            <div>
              <?php $arResult['DURATION_TIME'] = SearchFilter::getItemProperties($value['IBLOCK_ID'], $value['ID'], [], [
                'CODE'=>'DURATION_TIME'
              ])[0]['VALUE'];  ?>
              <?php if ($arResult['DURATION_TIME']): ?>
                <i class="fa fa-hourglass-half" aria-hidden="true"></i>
                <?php echo GetMessage('DURATION_TIME'); ?>:
                <?php echo $arResult['DURATION_TIME']; ?>
                <?php echo GetMessage('HOURS'); ?>
              <?php endif ?>
            </div>
            <div>
              <?php $arResult['FOOD'] = SearchFilter::getItemProperties($value['IBLOCK_ID'], $value['ID'], [], [
                'CODE'=>'FOOD'
              ])[0]['VALUE'];  ?>
              <?php if ($arResult['FOOD']): ?>
                <i class="fa fa-cutlery" aria-hidden="true"></i>
                <?php echo GetMessage('FOOD'); ?>:
                <?php echo SearchFilter::getItemById($arResult['FOOD'])['NAME']; ?>
              <?php endif ?>
            </div>
            <div>
              <?php $arResult['NEAREST_TOWN'] = SearchFilter::getItemProperties($value['IBLOCK_ID'], $value['ID'], [], [
                'CODE'=>'NEAREST_TOWN'
              ])[0]['VALUE'];  ?>
              <?php if ($arResult['NEAREST_TOWN']): ?>
                <i class="fa fa-info-circle" aria-hidden="true"></i>
                <?php echo GetMessage('NEAREST_TOWN'); ?>:
                <?php echo $arResult['NEAREST_TOWN']; ?> км 
              <?php endif ?>
            </div>
            <div>
              <?php $arResult['DISTANCE_MINSK'] = SearchFilter::getItemProperties($value['IBLOCK_ID'], $value['ID'], [], [
                'CODE'=>'DISTANCE_MINSK'
              ])[0]['VALUE'];  ?>
              <?php if ($arResult['DISTANCE_MINSK']): ?>
               <i class="fa fa-info-circle" aria-hidden="true"></i>
               <?php echo GetMessage('DISTANCE_MINSK'); ?>:
               <?php echo $arResult['DISTANCE_MINSK']; ?> км 
             <?php endif ?>
           </div>
           <div>
            <?php $arResult['MAP'] = SearchFilter::getItemProperties($value['IBLOCK_ID'], $value['ID'], [], [
              'CODE'=>'MAP'
            ])[0]['VALUE'];  ?>
            <?php if ($arResult['MAP']): ?>
              <a data-search_filter_map="<?php echo $arResult['MAP'];?>" href="#" class="search_filter-map_show_button">
                <i class="fa fa-map-o" aria-hidden="true"></i>
                <?php echo GetMessage('MAP'); ?>
              </a>
            <?php endif ?>
          </div>
          <div class="search_filter-list_item-text-features">
            <?php $arResult['SERVICES'] = SearchFilter::getItemProperties($value['IBLOCK_ID'], $value['ID'], [], [
              'CODE'=>'SERVICES'
            ]);?>
            <?php if ($arResult['SERVICES']): ?>
              <i class="fa fa-asterisk" aria-hidden="true"></i>
              <?php echo GetMessage('SERVICES'); ?>
              <?php $current_item_sevices_list = SearchFilter::getServicesList($arResult['SERVICES']); ?>
              <?php foreach ($current_item_sevices_list as $key2 => $value2): ?>
                <?php echo getTextLanguage(
                  $value2['NAME'], 
                  $value2['PROPERTY_NAME_BY_VALUE'], 
                  $value2['PROPERTY_NAME_EN_VALUE']
                  );?> |
                <?php endforeach ?>
              <?php endif ?>
            </div>

          </div>
          <div class="search_filter_detail_page_url">
            <a href="<?php echo $detail_page_url; ?>">
             <?php echo GetMessage('DETAIL_PAGE_URL'); ?>
             <i class="fa fa-chevron-right" aria-hidden="true"></i>
           </a>
         </div>
       </div>
       <hr>
     <?php endforeach ?>
   </div>

 </div>
 <div class="search_filter-yandex_map-popup-wrapper">
  <div class="search_filter-yandex_map-popup-inner">
    <div class="search_filter-yandex_map-popup-close_button">
      <i class="fa fa-times" aria-hidden="true"></i>
    </div>
    <div id="yandex_map" class="yandex_map"></div>
  </div>
</div>
