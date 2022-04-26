$(function () {

  getAvailableLocations();
  setPassangersQuantity(getPassangersQuantity());

  $('#belavia-search-form-iframe').attr('src', '');

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
    console.log(BelaviaSearchForm.DestinationLocation);
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
  document.location.href = url;
}

function belaviaIframeShow () {
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
  $('#belavia-search-form-iframe').attr('src', url);
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