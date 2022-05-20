<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
// $APPLICATION->SetAdditionalCSS("/bitrix/css/main/font-awesome.css");
?>

<div class="container">
  <div class="search_filter">
    <form action="" method="get">
      <input type="text" name="search">
      <button class="search_filter-search_button">
        <i class="fa fa-search" aria-hidden="true"></i>
      </button>
    </form>
  </div>
  <div class="search_filter-list">
    <?php foreach (pagination_php($arResult,10)['page'] as $key => $value): ?>
      <?php if ($value): ?>
        <div class="search_filter-list_item">
          <div class="search_filter-list_item-slider">
            <?php foreach ($value['pictures'] as $subkey => $subvalue): ?>
              <div style="background-image: url(<?php echo $subvalue; ?>);" class="search_filter-list_item-slider_image"></div>
            <?php endforeach ?>
          </div>
          <div class="search_filter-list_item-text">
            <a href="#"  class="search_filter-list_item-title">
              <?php echo $value['NAME'] ?>
            </a>
            <?php if ( $value['adress']): ?>
              <div class="search_filter-list_item-adress">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
                <?php echo $value['adress'] ?>
              </div>
            <?php endif ?>
          </div>
        </div>
        <pre><?php print_r($value); ?></pre>
      <?php endif ?>
    <?php endforeach ?>
  </div>
</div>


<script>
  $('.search_filter-list_item-slider').slick();
</script>
