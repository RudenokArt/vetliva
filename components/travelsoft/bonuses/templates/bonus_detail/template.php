<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

$arr_colors = array(
    1 => "grey",
    2 => "green",
    3 => "red",
    4 => "red",
)
?>

    <div class="total-head col-md-4">
        Текущий баланс
        <br>
        <b><?= $arResult["BONUSES_DETAIL_INFO"]["common"]["active"]?></b>
    </div>
    <div class="total-head col-md-4">
        Заработано всего
        <br>
        <b><?= $arResult["BONUSES_DETAIL_INFO"]["common"]["total"]?></b>
    </div>
    <div class="total-head col-md-4">
        Израсходовано
        <br>
        <b><?= $arResult["BONUSES_DETAIL_INFO"]["common"]["expend"]?></b>
    </div>

<div class="bonuses-history col-md-12">
     <h3>История</h3>
     <table class="table">
         <tr>
             <th>Услуга</th>
             <th>Дата</th>
             <th>Бонус</th>
             <th>Статус</th>
         </tr>
         <?foreach ($arResult["BONUSES_DETAIL_INFO"]["details"] as $arr_service_data):
             
             $arr_service = array();
             foreach ($arResult["BONUSES_DETAIL_INFO"]["services"] as $arr_serv) {

                 if ($arr_serv["id"] == $arr_service_data["serviceId"]) {
                     $arr_service = $arr_serv;
                     break;
                 }
             }
             
             $arr_staus = array();
             foreach ($arResult["BONUSES_DETAIL_INFO"]["statuses"] as $arr_st) {
                 if ($arr_st["id"] == $arr_service_data["statusId"]) {
                     $arr_staus = $arr_st;
                     break;
                 }
             }
             if (!empty($arr_service) && !empty($arr_staus)):
             ?>
         <tr>
             <td <?if (isset($arr_colors[$arr_service_data["statusId"]])):?>style="background-color: <?= $arr_colors[$arr_service_data["statusId"]]?>"<?endif;?>><?= $arr_service[LANGUAGE_ID]?></td>
             <td <?if (isset($arr_colors[$arr_service_data["statusId"]])):?>style="background-color: <?= $arr_colors[$arr_service_data["statusId"]]?>"<?endif;?>><?= $arr_service_data["date"]?></td>
             <td <?if (isset($arr_colors[$arr_service_data["statusId"]])):?>style="background-color: <?= $arr_colors[$arr_service_data["statusId"]]?>"<?endif;?>><?= $arr_service_data["total"]?></td>
             <td <?if (isset($arr_colors[$arr_service_data["statusId"]])):?>style="background-color: <?= $arr_colors[$arr_service_data["statusId"]]?>"<?endif;?>><?= $arr_staus[LANGUAGE_ID]?></td>
         </tr>
         <?endif; endforeach?>
     </table>
</div>


    
