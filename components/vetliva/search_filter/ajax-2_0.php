<?php require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
include_once 'class.php';
$search_filter = new SearchFilter();
// print_r($search_filter->items_list['items']);
// echo json_encode($search_filter->items_list['items']);
// exit();
?>

<?php 
 foreach ($search_filter->items_list['items'] as $key => $value): ?>
 
 <?

    // [IBLOCK_CODE] - тип [CODE] - страница
    //  print_r;

    if($value['IBLOCK_CODE'] == 'sanatorium'){
      $link = '/tourism/health-tourism/'.$value['CODE'].'/';
    }elseif($value['IBLOCK_CODE'] == 'accomodation'){
      $link = '/tourism/where-to-stay/'.$value['CODE'].'/';
    }else{
      $link = '/tourism/tours-in-belarus/'.$value['CODE'].'/';
    }
    ?>
    
  <a href="<? echo $link?>"> 
<div class="search_filter-resultItem">
  <?php print_r ($value['NAME']);?>
</div>
</a>




  <?php endforeach ?>