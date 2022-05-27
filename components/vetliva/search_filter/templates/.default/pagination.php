
<?php 
$arResult['pagination']['page_count'] = sizeof($arResult['nav']);
$arResult['pagination']['page'] = searchFilterCurrentPageNumber();
?>
<div class="search_filter-pagination">
 <?php if ($arResult['pagination']['page']>1): ?>
  <a href="?page=1<?php echo searchFilterUrlData();?>">1</a>
<?php endif ?>
<?php if ($arResult['pagination']['page']!=2 and $arResult['pagination']['page']!=1): ?>
  <span>...</span>
<?php endif ?>
<?php if ($arResult['pagination']['page']>3): ?>
  <a href="?page=<?php echo $arResult['pagination']['page']-1;?><?php echo searchFilterUrlData();?>">
    <?php echo$arResult['pagination']['page']-2;?>
  </a>
<?php endif ?>

<?php if ($arResult['pagination']['page']>2): ?>
  <a href="?page=<?php echo $arResult['pagination']['page']-1;?><?php echo searchFilterUrlData();?>">
    <?php echo$arResult['pagination']['page']-1;?>
  </a>
<?php endif ?>
<span class="search_filter-pagination-current_page">
  <?php echo $arResult['pagination']['page'];?>
</span>
<?php if ($arResult['pagination']['page']<$arResult['pagination']['page_count']): ?>
 <a href="?page=<?php echo $arResult['pagination']['page']+1;?><?php echo searchFilterUrlData();?>">
  <?php echo $arResult['pagination']['page']+1;?>
</a> 
<?php endif ?>
<?php if ($arResult['pagination']['page']<$arResult['pagination']['page_count']-1): ?>
 <a href="?page=<?php echo $arResult['pagination']['page']+2;?><?php echo searchFilterUrlData();?>">
  <?php echo $arResult['pagination']['page']+2;?>
</a> 
<?php endif ?>
<span>...</span>
<a href="?page=<?php echo $arResult['pagination']['page_count'];?><?php echo searchFilterUrlData();?>">
  <?php echo $arResult['pagination']['page_count'];?>
</a> 
</div>

