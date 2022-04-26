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

//dm($arResult,false,false,true);

if($GLOBALS["USER"]->IsAuthorized()){
	$arr_user = $GLOBALS["USER"]->GetByID($GLOBALS["USER"]->GetID())->GetNext();
	if ($arr_user["ID"] > 0) {
		$arResult["WORK_COMPANY"] = $arr_user["WORK_COMPANY"];
	}
	$arGroups = CUser::GetUserGroup($GLOBALS["USER"]->GetID());
	if(in_array("7", $arGroups))
		$arResult["USER_PROFILE"] = "/partners/";
	elseif(in_array("9", $arGroups)) 
		$arResult["USER_PROFILE"] = "/agent/private-office/";
	else
		$arResult["USER_PROFILE"] = "/private-office/";
}