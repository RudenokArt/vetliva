
<?
echo implode(" / ", array_map(function ($arr_crumb) { if ($arr_crumb['have-link']) {return "<a href=\"javascript:partnersManager.".$arr_crumb['js-breadcrumbs-handler-method']."()\">{$arr_crumb['title']}</a>";} else { return $arr_crumb['title'];}}, $arResult['breadcrumbs']));