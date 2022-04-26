<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<div class="container">
  <div class="belavia-search-form">
    <div class="belavia-search-form-flex-wrapper">
      <div>
        <div class="belavia-search-form-from_where">
          <div class="belavia-search-form-from_where-item">
            <span><?php echo GetMessage('from');?>:</span>
            <input type="text" name="OriginLocation">
            <div id="OriginLocation_list" class="locations_list"></div>
          </div>
          <div class="belavia-search-form-from_where-item">
            <span><?php echo GetMessage('where');?>:</span>
            <input type="text" name="DestinationLocation">
            <div id="DestinationLocation_list" class="locations_list">
              <span><?php echo GetMessage('departure_point_warning');?>:</span>
            </div>
          </div>
        </div>

        <div class="belavia-search-form-flex-wrapper">
          <div class="belavia-search-form-calendar">
            <div>
              <span><?php echo GetMessage('departure_date');?>:</span>
              <input type="text" name="DepartureDate">
            </div>
            <div>
              <span><?php echo GetMessage('return_date');?>:</span>
              <input type="text" name="ReturnDate">
            </div>
          </div>
          <div class="belavia-search-form-passangers">
            <div>
              <span><?php echo GetMessage('adults');?>:</span>
              <div class="belavia-search-form-passangers-item">
                <button name="adults_passanger_delete">
                  <i class="fa fa-chevron-left" aria-hidden="true"></i>
                </button>
                <input type="text" value="1" name="adults_passanger_quantity" readonly>
                <button name="adults_passanger_add">
                  <i class="fa fa-chevron-right" aria-hidden="true"></i>
                </button>
              </div>
            </div>
            <div>
              <span><?php echo GetMessage('children');?>:</span>
              <div class="belavia-search-form-passangers-item">
                <button name="childrens_passanger_delete">
                  <i class="fa fa-chevron-left" aria-hidden="true"></i>
                </button>
                <input type="text" value="0" readonly name="childrens_passanger_quantity">
                <button name="childrens_passanger_add">
                  <i class="fa fa-chevron-right" aria-hidden="true"></i>
                </button>
              </div>
            </div>
            <div>
              <span><?php echo GetMessage('infants');?>:</span>
              <div class="belavia-search-form-passangers-item">
                <button name="infants_passanger_delete">
                  <i class="fa fa-chevron-left" aria-hidden="true"></i>
                </button>
                <input type="text" value="0" readonly name="infants_passanger_quantity">
                <button name="infants_passanger_add">
                  <i class="fa fa-chevron-right" aria-hidden="true"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div>
        <div class="belavia-search-form-submit-button">
          <button name="belavia-search-form-submit-button">
            1
            <!-- <i class="fa fa-search" aria-hidden="true"></i> -->
          </button>
          <button name="belavia-search-form-iframe-button">
            2
            <!-- <i class="fa fa-search" aria-hidden="true"></i> -->
          </button>
        </div>   
      </div>
      
    </div>
    <iframe src="" frameborder="0" id="belavia-search-form-iframe"></iframe>
  </div>
</div>
<script>
  var BelaviaSearchForm = {};
  BelaviaSearchForm.lang = '<?php echo LANGUAGE_ID; ?>';
  BelaviaSearchForm.locations_list = JSON.parse('<?php echo $arResult["locations_list"]; ?>');
  BelaviaSearchForm.availible_locations = JSON.parse('<?php echo $arResult["availible_locations"]; ?>');
</script>