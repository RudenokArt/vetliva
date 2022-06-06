<a href="/about/exchange_rates/">
  <div class="current-exchange_rates-wrapper">
    <div class="current-exchange_rates-inner">
      <?php if ($arResult['current']): ?>
        <div class="current-exchange_rates-item">
          <i class="fa fa-rub" aria-hidden="true"></i>
          <?php echo round($arResult['current'][0]['PROPERTY_RUB_VALUE'],4);?>
        </div>
        <div class="current-exchange_rates-item">
          <i class="fa fa-usd" aria-hidden="true"></i>
          <?php echo round($arResult['current'][0]['PROPERTY_USD_VALUE'],4);?>
        </div>
        <div class="current-exchange_rates-item">
          <i class="fa fa-eur" aria-hidden="true"></i>
          <?php echo round($arResult['current'][0]['PROPERTY_EUR_VALUE'],4);?>
        </div>
      <?php endif ?>
    </div>
  </div>
</a>


