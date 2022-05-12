<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<style>
  .belavia-search-form {
    width: 100%;
}
.belavia-search-form-calendar {
 width: 300px;
 display: flex;
 flex-wrap: nowrap;
}
.belavia-search-form-calendar div {
  padding-right: 25px
}

.belavia-search-form input[type="text"] {
  border: 1px solid #d5dadc;
  /*border-radius: 5px;*/
  height: 30px;
  padding-left: 10px;
}

.belavia-search-form-from_where {
  display: flex;
  flex-wrap: wrap;
  max-width: 700px;
  /*height: 75px;*/
}
.belavia-search-form-from_where-item {
  width: 300px;
  padding-right: 25px;
  position: relative;
}
.belavia-search-form .locations_list {
  display: none;
  position: absolute;
  height: 300px;
  width: 275px;
  overflow: auto;
  background-color: white;
  border: 1px solid rgba(0, 0, 0, 0.1);
  box-shadow: 0px 0px 5px 0px #000000;
  z-index: 100;
}
.belavia-search-form .locations_list p {
  cursor: pointer;
  padding: 10px;
}
.belavia-search-form .locations_list p:hover {
  background-color: rgba(0, 0, 0, 0.1);
}
.belavia-search-form-passangers,
.belavia-search-form-flex-wrapper {
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
}
.belavia-search-form-passangers-item {
  display: flex;
  width: 90px;
}
.belavia-search-form-passangers-item button {
  width: 25px;
  border: none;
  background: transparent;
  font-weight: bold;
}
.belavia-search-form-passangers-item input {
  width: 40px;
  padding: 0 !important;
  text-align: center;
}

.belavia-search-form-submit-button {
  padding-top: 20px;
}

.belavia-search-form-submit-button button {
  height: 30px;
  background-color: #264b87;
  color: white;
  border: none;
  width: 100px;
}

.belavia-search-form iframe {
  height: 800px;
  width: 100%;
  padding-top: 20px;
}
</style>

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
            <i class="fa fa-search" aria-hidden="true"></i>
          </button>
        </div>   
      </div>
    </div>
  </div>
</div>

<script>
  var BelaviaSearchForm = {};
  BelaviaSearchForm.lang = '<?php echo LANGUAGE_ID; ?>';
  BelaviaSearchForm.locations_list = JSON.parse('<?php echo $arResult["locations_list"]; ?>');
  BelaviaSearchForm.availible_locations = JSON.parse('<?php echo $arResult["availible_locations"]; ?>');
  getAvailableLocations();
  setPassangersQuantity(getPassangersQuantity());

  // $('#belavia-search-form-iframe').attr('src', '');

  $('input[name="DepartureDate"], input[name="ReturnDate"]').datepicker({
    minDate: "+0d",
    dateFormat: "yy-mm-dd",
  });

  $('input[name="DepartureDate"]').change(function () {
    BelaviaSearchForm.DepartureDate = this.value;
  });

  $('input[name="ReturnDate"]').change(function () {
    BelaviaSearchForm.ReturnDate = this.value;
  });

  $('input[name="OriginLocation"]').focusin(function(){
    $('#OriginLocation_list').show();
  });

  $('input[name="DestinationLocation"]').focusin(function(){
    $('#DestinationLocation_list').show();
  });

  $('input[name="OriginLocation"]').focusout(function(){
    setTimeout(function () {
      $('#OriginLocation_list').hide();
    }, 500);
  });

  $('input[name="DestinationLocation"]').focusout(function(){
    setTimeout(function () {
      $('#DestinationLocation_list').hide();
    }, 500);
  });

  $('input[name="OriginLocation"]').bind('input', function () {
    var arr = $('#OriginLocation_list').find('.OriginLocation-item');
    for (var i = 0; i < arr.length; i++) {
      if ($(arr[i]).html().toLowerCase().includes(this.value.toLowerCase())) {
        $(arr[i]).show();
      } else {
        $(arr[i]).hide();
      }
    }
  });

  $('input[name="DestinationLocation"]').bind('input', function () {
    var arr = $('#DestinationLocation_list').find('.DestinationLocation-item');
    for (var i = 0; i < arr.length; i++) {
      if ($(arr[i]).html().toLowerCase().includes(this.value.toLowerCase())) {
        $(arr[i]).show();
      } else {
        $(arr[i]).hide();
      }
    }
  });

  $('body').delegate('.OriginLocation-item','click', function () {
    $('input[name="OriginLocation"]').prop('value', $(this).html());
    BelaviaSearchForm.OriginLocation = $(this).attr('data-OriginLocation');
    getAvailableRoutes(BelaviaSearchForm.availible_locations[BelaviaSearchForm.OriginLocation]);
  });

  $('body').delegate('.DestinationLocation-item','click', function () {
    $('input[name="DestinationLocation"]').prop('value', $(this).html());
    BelaviaSearchForm.DestinationLocation = $(this).attr('data-DestinationLocation');
  });

  $('button[name="adults_passanger_delete"]').click(function () {
    var passengersQuantity = getPassangersQuantity();
    if (passengersQuantity[0] > 1) {
      passengersQuantity[0] = passengersQuantity[0]-1;
    } 
    setPassangersQuantity(passengersQuantity);
  });

  $('button[name="adults_passanger_add"]').click(function () {
    var passengersQuantity = getPassangersQuantity();
    if ((passengersQuantity[0]+passengersQuantity[1]) < 5) {
      passengersQuantity[0] = passengersQuantity[0]+1;
    } 
    setPassangersQuantity(passengersQuantity);
  });

  $('button[name="childrens_passanger_delete"]').click(function () {
    var passengersQuantity = getPassangersQuantity();
    if (passengersQuantity[1] > 0) {
      passengersQuantity[1] = passengersQuantity[1]-1;
    } 
    setPassangersQuantity(passengersQuantity);
  });

  $('button[name="childrens_passanger_add"]').click(function () {
    var passengersQuantity = getPassangersQuantity();
    if ((passengersQuantity[0]+passengersQuantity[1]) < 5) {
      passengersQuantity[1] = passengersQuantity[1]+1;
    } 
    if (passengersQuantity[0] == 1 && passengersQuantity[1] > 3) {
      passengersQuantity[1] = 3;
    }
    setPassangersQuantity(passengersQuantity);
  });

  $('button[name="infants_passanger_delete"]').click(function () {
    var passengersQuantity = getPassangersQuantity();
    if (passengersQuantity[2] > 0) {
      passengersQuantity[2] = passengersQuantity[2]-1;
    } 
    setPassangersQuantity(passengersQuantity);
  });

  $('button[name="infants_passanger_add"]').click(function () {
    var passengersQuantity = getPassangersQuantity();
    if (passengersQuantity[0] > passengersQuantity[2]) {
      passengersQuantity[2] = passengersQuantity[2]+1;
    } 
    setPassangersQuantity(passengersQuantity);
  });

  $('button[name="belavia-search-form-submit-button"]').click(function () {
    BelaviaSearchForm.JourneySpan = 'Rt';
    if (BelaviaSearchForm.ReturnDate) {
      BelaviaSearchForm.JourneySpan = 'Ow';
    }
    if (validationBelaviaSearchForm()) {
      belaviaUrlCreate();
    };
  });
  $('button[name="belavia-search-form-iframe-button"]').click(function () {
    BelaviaSearchForm.JourneySpan = 'Rt';
    if (BelaviaSearchForm.ReturnDate) {
      BelaviaSearchForm.JourneySpan = 'Ow';
    }
    if (validationBelaviaSearchForm()) {
      belaviaIframeShow();
    };
  });


// https://belavia.by/redirect.php?OriginLocation=MSQ&DestinationLocation=MOW&DepartureDate=2022-04-29&lang=ru&JourneySpan=Ow&Adults=1&Infants=1

function belaviaUrlCreate () {
  var url = 'https://belavia.by/redirect.php?';
  if (BelaviaSearchForm.OriginLocation) {
    url = url + 'OriginLocation=' + BelaviaSearchForm.OriginLocation;
  }
  if (BelaviaSearchForm.DestinationLocation) {
    url = url + '&DestinationLocation=' + BelaviaSearchForm.DestinationLocation;
  }
  if (BelaviaSearchForm.DepartureDate) {
    url = url + '&DepartureDate=' + BelaviaSearchForm.DepartureDate;
  }
  if (BelaviaSearchForm.ReturnDate) {
    url = url + '&ReturnDate=' + BelaviaSearchForm.ReturnDate;
  }
  if (BelaviaSearchForm.JourneySpan) {
    url = url + '&JourneySpan=' + BelaviaSearchForm.JourneySpan;
  }
  if (BelaviaSearchForm.lang) {
    if (BelaviaSearchForm.lang == 'by') {
      BelaviaSearchForm.lang = 'be';
    }
    url = url + '&lang=' + BelaviaSearchForm.lang;
  }
  if (BelaviaSearchForm.Adults) {
    url = url + '&Adults=' + BelaviaSearchForm.Adults;
  }
  if (BelaviaSearchForm.Children) {
    url = url + '&Children=' + BelaviaSearchForm.Children;
  }
  if (BelaviaSearchForm.Infants) {
    url = url + '&Infants=' + BelaviaSearchForm.Infants;
  }
  // document.location.href = url;
  window.open(url);
}

function validationBelaviaSearchForm () {
  var flag = true;
  if (!BelaviaSearchForm.OriginLocation) {
    $('input[name="OriginLocation"]').css({'border':'1px solid red'});
    flag = false;
  } else {
    $('input[name="OriginLocation"]').css({'border':'1px solid #d5dadc'});
  }
  if (!BelaviaSearchForm.DestinationLocation) {
    $('input[name="DestinationLocation"]').css({'border':'1px solid red'});
    flag = false;
  } else {
    $('input[name="DestinationLocation"]').css({'border':'1px solid #d5dadc'});
  }
  if (!BelaviaSearchForm.DepartureDate) {
    $('input[name="DepartureDate"]').css({'border':'1px solid red'});
    flag = false;
  } else {
    $('input[name="DepartureDate"]').css({'border':'1px solid #d5dadc'});
  }
  return flag;
}

function setPassangersQuantity (passengersQuantity) {
  $('input[name="adults_passanger_quantity"]').prop('value', passengersQuantity[0]);
  $('input[name="childrens_passanger_quantity"]').prop('value', passengersQuantity[1]);
  $('input[name="infants_passanger_quantity"]').prop('value', passengersQuantity[2]);
  BelaviaSearchForm.Adults = passengersQuantity[0];
  BelaviaSearchForm.Children = passengersQuantity[1];
  BelaviaSearchForm.Infants = passengersQuantity[2];
}

function getPassangersQuantity () {
  var adults = $('input[name="adults_passanger_quantity"]').prop('value');
  var childrens = $('input[name="childrens_passanger_quantity"]').prop('value');
  var infants = $('input[name="infants_passanger_quantity"]').prop('value');
  return [Number(adults), Number(childrens), Number(infants)];
}

function getAvailableRoutes (arr) {
  var str = '';
  for (key in arr) {
    str = str + '<p class="DestinationLocation-item" data-DestinationLocation="'+
    arr[key]+'">'+'('+arr[key]+') '+BelaviaSearchForm.locations_list[arr[key]]+''+'</p>';
  }
  $('#DestinationLocation_list').html(str);
}

function getAvailableLocations () {
  var str = '';
  for (key in BelaviaSearchForm.availible_locations) {
    str = str + '<p class="OriginLocation-item" data-OriginLocation="'+
    key+'">'+'('+key+') '+BelaviaSearchForm.locations_list[key]+''+'</p>';
  }
  $('#OriginLocation_list').html(str);
} 
</script>

