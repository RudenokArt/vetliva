<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="container">
  <form action="" name="booking" method="get">
    <input type="hidden" name="booking[date_from]" id="date_from">
    <input type="hidden" name="booking[date_to]" id="date_to">
    <div class="price_calculator_form_inner">
      <div class="price_calc_form_item">
        <label for="placement">Санаторий/Отель ID</label>
        <input type="text" name="booking[id][0]" placeholder="Санаторий/отель">
      </div>
      <div class="price_calc_form_item">
       <label for="">Дата с:</label>
       <input type="text" id="price_calc_date_from" readonly>
     </div>
     <div class="price_calc_form_item">
      <label for="">Дата по:</label>
      <input type="text" id="price_calc_date_to" readonly>
    </div>
    <div class="price_calc_form_item">
      <label for="adults">Кол-во взрослых</label>
      <select type="text" name="booking[adults]" placeholder="взрослые"> 
        <option value="1" selected>1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
      </select>  
    </div>
    <div class="price_calc_form_item">
      <label for="childs" >Кол-во детей</label>
      <select type="text" name="booking[children]" id='childs' placeholder="Дети">
        <option value="0">0</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
      </select> 
    </div>
    <div class="price_calc_form_item">
      <label for="">Search</label>
      <button class='submit-button'>
        <i class="fa fa-search" aria-hidden="true"></i>
      </button>
    </div>
  </div>
  <div id="children_age" class="children_age"></div>
</form>
</div>




<pre>
  <?php 
  print_r($arResult['total_arr']); 
  print_r($arResult['rates_category_name']);
  ?>
</pre>
<pre><?php print_r($arResult['booking_placement']); ?></pre>
<pre><?php print_r($arResult['period']); ?></pre>
<pre><?php print_r($arParams); ?></pre>


<script>

  agesSelect  = `
  <select type="text" name='booking[children_age][]' class='agesSelect' placeholder="Дети">
  <option value="1">1</option>
  <option value="2">2</option>
  <option value="3">3</option>
  <option value="4">4</option>
  <option value="5">5</option>
  <option value="6">6</option>
  <option value="7">7</option>
  <option value="8">8</option>
  <option value="9">9</option>
  <option value="10">10</option>
  <option value="11">11</option>
  <option value="12">12</option>
  <option value="13">13</option>
  <option value="14">14</option>
  <option value="15">15</option>
  <option value="16">16</option>
  <option value="17">17</option>
  <option value="18">18</option>
  </select>
  `;

  setTimeout(function () {
    $('#childs').unbind(); 
    $('#childs').change(function () {
      console.log(this.value);
      var str = 'Возраст детей:';
      for (var i = 0; i < this.value; i++) {
        str = str + agesSelect;
      }
      $('#children_age').html(str);
    });         
  }, 1000);


  $('#price_calc_date_from').change(function () {
    $('#date_from').prop('value', convertToUnixTime(this.value));
  });
  $('#price_calc_date_to').change(function () {
    $('#date_to').prop('value', convertToUnixTime(this.value));
  });

  $( function() {
    $( "#price_calc_date_from" ).datepicker({dateFormat: 'dd.mm.yy'});
    $( "#price_calc_date_to" ).datepicker({dateFormat: 'dd.mm.yy'});
  } );

  function convertToUnixTime (str) {
    var myDate = str;
    myDate = myDate.split(".");
    var newDate = new Date( myDate[2], myDate[1] - 1, myDate[0]);
    return newDate.getTime()/1000;
  }

</script>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>