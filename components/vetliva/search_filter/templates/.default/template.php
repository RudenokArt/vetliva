<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$search_filter = new SearchFilter();
?>
<script src="https://api-maps.yandex.ru/2.1/?apikey=fef1e22c-2d6e-4fbb-9eac-c5a1ab864609&lang=ru_RU" type="text/javascript"></script>
<div class="search_filter-wrapper">
  <?php include_once 'filter.php'; ?>
  <?php if ($_SERVER['SCRIPT_URL'] == '/search/') : ?>
    <?php if (!empty($_GET['search']) and $search_filter->items_list['items']) : ?>
      <?php include 'pagination.php'; ?>
      <?php include_once 'search_page.php'; ?>
      <?php include 'pagination.php'; ?>
    <?php else : ?>
      <div class="search_filter-info">
        <?php echo getMessage('no_results'); ?>
      </div>
    <?php endif ?>
  <?php endif ?>
</div>


<script>
  radioLabel();
  $('.search_filter-label input[type="radio"]').change(function() {
    $('.search_filter-label').removeClass('active');
    radioLabel();
    ressetFilterData();
  });

  if ($('select[name="region"]').prop('value')) {
    search_filter_city_select(document.querySelector('select[name="region"]'));
  }

  $('select[name="region"]').change(function() {
    $('select[name="city"]').prop('value', 'N');
    search_filter_city_select(this);
  });

  $('#search_filter-form').bind('submit', function(e) {
    e.preventDefault();
    var arr = $('input[name="filter"]');
    var flag = false;
    for (var i = 0; i < arr.length; i++) {
      if (arr[i].checked) {
        flag = true;
      }
    }
    if (!flag) {
      var warning = $(arr).parent('label');
      $(arr).change(function() {
        $(warning).removeClass('warning');
      });
      $(warning).addClass('warning');
      setTimeout(function() {
        $(warning).removeClass('warning');
      }, 10000);
    } else {
      this.submit();
    }
  });
  
  var search_filter_timeout;

  $('#search_input').focus(function(){
    $('#search_filter-live_search-wrapper').show();
  });

  $('#search_input').blur(function(){
    setTimeout(function () {
      $('#search_filter-live_search-wrapper').hide();
      $('#search_filter-live_search').html('');
    }, 1000);
  });


  $('input[name="search"]').bind('input', function() {
    $('#search_filter-live_search').html('<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>');
    var node = this;
    clearTimeout(search_filter_timeout);
    search_filter_timeout = setTimeout(function() {
      var url = '/local/components/vetliva/search_filter/ajax-2_0.php';
      var region_data = $('select[name="region"]').prop('value');
      var city_data = $('select[name="city"]').prop('value');
      var med_profiles_data = $('select[name="med_profiles"]').prop('value');
      var med_services_data = $('select[name="med_services"]').prop('value');
      var arr = $('input[name="filter"]');
      for (var i = 0; i < arr.length; i++) {
        if (arr[i].checked) {
          var filter_data = arr[i].value;
        }
      }
      $.get(url, {
        search: node.value,
        filter: filter_data,
        region: region_data,
        city: city_data,
        med_profiles: med_profiles_data,
        med_services: med_services_data,
      },
      function(data) {
        $('#search_filter-live_search').html(data);
      });
    }, 500);
  });


  $('.search_filter-list_item-slider').slick();

  var yandex_map_point = [];
  $('.search_filter-map_show_button').click(function() {
    $('.search_filter-yandex_map-popup-wrapper').css({
      'display': 'flex'
    });
    yandex_map_point = $(this).attr('data-search_filter_map').split(',');
    ymaps.ready(init);
  });

  $('.search_filter-yandex_map-popup-close_button').click(function() {
    $('.search_filter-yandex_map-popup-wrapper').css({
      'display': 'none'
    });
  });

  function ressetFilterData() {
    $('select[name="med_profiles"]').prop('value', 'N');
    $('select[name="med_services"]').prop('value', 'N');
  }

  function radioLabel() {
    var arr = $('.search_filter-label');
    var flag = false;
    for (var i = 0; i < arr.length; i++) {
      if ($(arr[i]).children('input[type="radio"]')[0].checked) {
        $(arr[i]).addClass('active');
        if ($(arr[i]).children('input[type="radio"]')[0].value == 'sanatorium') {
          flag = true;
        }
      }
    }
    if (flag) {
      $('#med_services, #med_profiles').show();
    } else {
      $('#med_services, #med_profiles').hide();
    }
  }

  function init() {
    var search_filter_map = new ymaps.Map("yandex_map", {
      center: yandex_map_point,
      zoom: 12,
      controls: ['zoomControl'],
    });
    var myPlacemark = new ymaps.Placemark(yandex_map_point);
    search_filter_map.geoObjects.add(myPlacemark);
    $('.search_filter-yandex_map-popup-close_button').click(function() {
      $('.search_filter-yandex_map-popup-wrapper').css({
        'display': 'none'
      });
      search_filter_map.destroy();
    });
  }

  function search_filter_city_select(node) {
    $('#search_filter-city').css({
      'display': 'block'
    });
    var arr = $('select[name="city"]').children('option');
    $(arr).css({
      'display': 'none'
    });
    for (var i = 0; i < arr.length; i++) {
      if (node.value === arr[i].getAttribute('data-region')) {
        $(arr[i]).css({
          'display': 'block'
        });
      }
      if (arr[i].value == 'N') {
        $(arr[i]).css({
          'display': 'block'
        });
      }
    }
  }
</script>