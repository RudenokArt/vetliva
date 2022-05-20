<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->SetAdditionalCSS("/bitrix/css/main/font-awesome.css");
?>
<pre><?php print_r($_SERVER); ?></pre><hr>
<pre><?php print_r($_GET); ?></pre>
<input type="text" id="search_filter_input">
<a href="<?php echo $_SERVER['SCRIPT_URI']; ?>" id="search_filter_link">search</a>
<pre><?php print_r($arResult); ?></pre>

<script>
  BX.ready(function(){
  BX.showWait = function() {
    console.log('start');
  };
  BX.closeWait = function() {
    console.log('finish');
  };
});

$(function () {
  $('#search_filter_input').bind('input', function () {
    $('#search_filter_link').attr('href', "<?php echo $_SERVER['SCRIPT_URL'];?>?search="+this.value);
    setCurrentUrl();
  });
});

function setCurrentUrl () {
  history.pushState(null, 'search', $('#search_filter_link').attr('href'));
}
</script>