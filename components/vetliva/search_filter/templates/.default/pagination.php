
<div class="search_filter-pagination">
 <?php if ($search_filter->items_list['pagination']['page_number']>1): ?>
  <a href="?page_number=1<?php echo SearchFilter::getPaginationUrlData();?>">1</a>
<?php endif ?>
<?php if ($search_filter->items_list['pagination']['page_number']!=2 and $search_filter->items_list['pagination']['page_number']!=1): ?>
  <span>...</span>
<?php endif ?>
<?php if ($search_filter->items_list['pagination']['page_number']>3): ?>
  <a href="?page_number=<?php echo $search_filter->items_list['pagination']['page_number']-1;?><?php echo SearchFilter::getPaginationUrlData();?>">
    <?php echo $search_filter->items_list['pagination']['page_number']-2;?>
  </a>
<?php endif ?>

<?php if ($search_filter->items_list['pagination']['page_number']>2): ?>
  <a href="?page_number=<?php echo $search_filter->items_list['pagination']['page_number']-1;?><?php echo SearchFilter::getPaginationUrlData();?>">
    <?php echo $search_filter->items_list['pagination']['page_number']-1;?>
  </a>
<?php endif ?>
<span class="search_filter-pagination-current_page">
  <?php echo $search_filter->items_list['pagination']['page_number'];?>
</span>
<?php if ($search_filter->items_list['pagination']['page_number']<$search_filter->items_list['pagination']['page_count']): ?>
 <a href="?page_number=<?php echo $search_filter->items_list['pagination']['page_number']+1;?><?php echo SearchFilter::getPaginationUrlData();?>">
  <?php echo $search_filter->items_list['pagination']['page_number']+1;?>
</a> 
<?php endif ?>
<?php if ($search_filter->items_list['pagination']['page_number']<$search_filter->items_list['pagination']['page_count']-1): ?>
 <a href="?page_number=<?php echo $search_filter->items_list['pagination']['page_number']+2;?><?php echo SearchFilter::getPaginationUrlData();?>">
  <?php echo $search_filter->items_list['pagination']['page_number']+2;?>
</a> 
<?php endif ?>
<span>...</span>
<a href="?page_number=<?php echo $search_filter->items_list['pagination']['page_count'];?><?php echo SearchFilter::getPaginationUrlData();?>">
  <?php echo $search_filter->items_list['pagination']['page_count'];?>
</a> 
</div>

