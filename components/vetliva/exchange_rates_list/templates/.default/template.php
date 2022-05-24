<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="exchange_rates">
  <table>
    <tr>
      <th><i class="fa fa-calendar" aria-hidden="true"></i></th>
      <th>USD</th>
      <th>EUR</th>
      <th>RUB</th>
    </tr>
    <?php foreach ($arResult['items'] as $key => $value): ?>
      <tr>
        <td><?php echo $value['NAME'] ?></td>
        <td><?php echo round($value['PROPERTY_USD_VALUE'],4); ?></td>
        <td><?php echo round($value['PROPERTY_EUR_VALUE'],4) ?></td>
        <td><?php echo round($value['PROPERTY_RUB_VALUE'],4) ?></td>
      </tr>    
    <?php endforeach ?>
  </table>
  <div class="pagination_wrapper">
    <div class="pagination_inner">
      <?php  
      if ($arResult['pagination']['page_number']>1): ?>
        <a href="?page_number=1">1</a>...
      <?php endif ?>
      <?php if ($arResult['pagination']['page_number']>2): ?>
        <a href="?page_number=<?php echo$arResult['pagination']['page_number']-1;?>">
          <?php echo $arResult['pagination']['page_number']-1;?>
        </a>
      <?php endif ?>
      <?php echo $arResult['pagination']['page_number'];?>
      <?php if ($arResult['pagination']['page_number']<$arResult['pagination']['page_count']): ?>
       <a href="?page_number=<?php echo $arResult['pagination']['page_number']+1;?>">
        <?php echo $arResult['pagination']['page_number']+1;?>
      </a> 
    <?php endif ?>
    ...
    <a href="?page_number=<?php echo $arResult['pagination']['page_count'];?>">
      <?php echo $arResult['pagination']['page_count'];?>
    </a>
  </div>  
</div>
</div>
