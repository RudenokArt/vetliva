<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
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
?>
<div class="search-page">
<form action="" method="get">
	<input type="text" name="q" class="form-control" value="<?=$arResult["REQUEST"]["QUERY"]?>" size="40" />
    <div>
        <?= GetMessage("SEARCH_ONLY")?>:
        <?foreach ($arResult['SEARCH_FILTER'] as $key => $filter):?>
            <label><input type="checkbox" name="filter[<?=$key?>]" <?= empty($_REQUEST['filter'][$key]) ? '' : 'checked' ?>><?=$filter?></label>
        <?endforeach;?>
    </div>
	&nbsp;<input type="submit" class="btn btn-primary" value="<?=GetMessage("SEARCH_GO")?>" />
	<input type="hidden" name="how" value="<?echo $arResult["REQUEST"]["HOW"]=="d"? "d": "r"?>" />

</form><br />

<?if(isset($arResult["REQUEST"]["ORIGINAL_QUERY"])):
	?>
	<div class="search-language-guess">
		<?echo GetMessage("CT_BSP_KEYBOARD_WARNING", array("#query#"=>'<a href="'.$arResult["ORIGINAL_QUERY_URL"].'">'.$arResult["REQUEST"]["ORIGINAL_QUERY"].'</a>'))?>
	</div><br /><?
endif;?>

<?if($arResult["REQUEST"]["QUERY"] === false && $arResult["REQUEST"]["TAGS"] === false):?>
<?elseif($arResult["ERROR_CODE"]!=0):?>
	<p><?=GetMessage("SEARCH_ERROR")?></p>
	<?ShowError($arResult["ERROR_TEXT"]);?>
	<p><?=GetMessage("SEARCH_CORRECT_AND_CONTINUE")?></p>
	<br /><br />
	<p><?=GetMessage("SEARCH_SINTAX")?><br /><b><?=GetMessage("SEARCH_LOGIC")?></b></p>
	<table border="0" cellpadding="5">
		<tr>
			<td align="center" valign="top"><?=GetMessage("SEARCH_OPERATOR")?></td><td valign="top"><?=GetMessage("SEARCH_SYNONIM")?></td>
			<td><?=GetMessage("SEARCH_DESCRIPTION")?></td>
		</tr>
		<tr>
			<td align="center" valign="top"><?=GetMessage("SEARCH_AND")?></td><td valign="top">and, &amp;, +</td>
			<td><?=GetMessage("SEARCH_AND_ALT")?></td>
		</tr>
		<tr>
			<td align="center" valign="top"><?=GetMessage("SEARCH_OR")?></td><td valign="top">or, |</td>
			<td><?=GetMessage("SEARCH_OR_ALT")?></td>
		</tr>
		<tr>
			<td align="center" valign="top"><?=GetMessage("SEARCH_NOT")?></td><td valign="top">not, ~</td>
			<td><?=GetMessage("SEARCH_NOT_ALT")?></td>
		</tr>
		<tr>
			<td align="center" valign="top">( )</td>
			<td valign="top">&nbsp;</td>
			<td><?=GetMessage("SEARCH_BRACKETS_ALT")?></td>
		</tr>
	</table>
<?elseif($arResult["COUNT"] > 0):?>
    <?if($arParams["DISPLAY_TOP_PAGER"] != "N") echo $arResult["NAV_STRING"]?>

        <?if(!empty($arResult["TYPE"]['SANATORIUM'])):?>
            <h3><?=GetMessage('SANATORIUM')?></h3>
            <? $GLOBALS['searchResult']['ID'] = $arResult["TYPE"]['SANATORIUM']; ?>
            <?
            $APPLICATION->IncludeComponent(
                "travelsoft:travelsoft.news.list", "search-placements-list", Array(
                "IBLOCK_TYPE" => 'Dictionaries',
                "IBLOCK_ID" => SANATORIUM_IBLOCK_ID,
                "NEWS_COUNT" => $arParams["PAGE_RESULT_COUNT"],
                "SORT_BY1" => 'SORT',
                "SORT_ORDER1" => 'ASC',
                "SORT_BY2" => 'NAME',
                "SORT_ORDER2" => 'ASC',
                "FIELD_CODE" => [],
                "PROPERTY_CODE"=>["COUNTRY","REGION","TOWN","ADDRESS","TYPE_SAN","MAP","DISTANCE_MINSK","HD_DESC","FEATURES","TYPE","SERVICES","YOUTUBE","VIMEO","FOR_SPOT_PAYMENT",
                    "CALK","REGIONS","MAP_SCALE","PICTURES","NAME_BY","ADDRESS_BY","HD_DESC_BY","NAME_EN","ADDRESS_EN","HD_DESC_EN"],
                "DETAIL_URL" => "/tourism/health-tourism/#ELEMENT_CODE#/",
                "SECTION_URL" => "/tourism/health-tourism/",
                "IBLOCK_URL" => "/tourism/health-tourism/",
                "DISPLAY_PANEL" => NULL,
                "SET_TITLE" => "N",
                "SET_LAST_MODIFIED" => "N",
                "MESSAGE_404" => "",
                "SET_STATUS_404" => "Y",
                "SHOW_404" => "Y",
                "FILE_404" => "",
                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "86400",
                "CACHE_FILTER" => "N",
                "CACHE_GROUPS" => "N",
                "DISPLAY_TOP_PAGER" => "N",
                "DISPLAY_BOTTOM_PAGER" => "N",
                "PAGER_TITLE" => "Новости",
                "PAGER_TEMPLATE" => "modern",
                "PAGER_SHOW_ALWAYS" => "N",
                "PAGER_DESC_NUMBERING" => "N",
                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                "PAGER_SHOW_ALL" => "Y",
                "PAGER_BASE_LINK_ENABLE" => "N",
                "PAGER_BASE_LINK" => NULL,
                "PAGER_PARAMS_NAME" => NULL,
                "DISPLAY_DATE" => "N",
                "DISPLAY_NAME" => "Y",
                "DISPLAY_PICTURE" => "Y",
                "DISPLAY_PREVIEW_TEXT" => "Y",
                "PREVIEW_TRUNCATE_LEN" => "",
                "ACTIVE_DATE_FORMAT" => "d.m.Y",
                "USE_PERMISSIONS" => "N",
                "GROUP_PERMISSIONS" => NULL,
                "FILTER_NAME" => "searchResult",
                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                "CHECK_DATES" => "Y",
                "CALCULATION_PRICE_RESULT" => [],
                "TYPE" => "sanatorium",
//                "__BOOKING_REQUEST" => $arParams["__BOOKING_REQUEST"]
            ));
            ?>
        <?endif;?>

        <?if(!empty($arResult["TYPE"]['PLACEMENTS'])):?>
            <h3><?=GetMessage('PLACEMENTS')?></h3>
            <? $GLOBALS['searchResult']['ID'] = $arResult["TYPE"]['PLACEMENTS']; ?>
            <?
            $APPLICATION->IncludeComponent(
                "travelsoft:travelsoft.news.list", "search-placements-list", Array(
                "IBLOCK_TYPE" => 'Dictionaries',
                "IBLOCK_ID" => PLACEMENTS_IBLOCK_ID,
                "NEWS_COUNT" => $arParams["PAGE_RESULT_COUNT"],
                "SORT_BY1" => 'SORT',
                "SORT_ORDER1" => 'ASC',
                "SORT_BY2" => 'NAME',
                "SORT_ORDER2" => 'ASC',
                "FIELD_CODE" => [],
                "PROPERTY_CODE"=>["COUNTRY","REGION","TOWN","ADDRESS","TYPE_SAN","MAP","DISTANCE_MINSK","HD_DESC","FEATURES","TYPE","SERVICES","YOUTUBE","VIMEO","FOR_SPOT_PAYMENT",
                    "CALK","REGIONS","MAP_SCALE","PICTURES","NAME_BY","ADDRESS_BY","HD_DESC_BY","NAME_EN","ADDRESS_EN","HD_DESC_EN"],
                "DETAIL_URL" => "/tourism/where-to-stay/#ELEMENT_CODE#/",
                "SECTION_URL" => "/tourism/where-to-stay/",
                "IBLOCK_URL" => "/tourism/where-to-stay/",
                "DISPLAY_PANEL" => NULL,
                "SET_TITLE" => "N",
                "SET_LAST_MODIFIED" => "N",
                "MESSAGE_404" => "",
                "SET_STATUS_404" => "Y",
                "SHOW_404" => "Y",
                "FILE_404" => "",
                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "86400",
                "CACHE_FILTER" => "N",
                "CACHE_GROUPS" => "N",
                "DISPLAY_TOP_PAGER" => "N",
                "DISPLAY_BOTTOM_PAGER" => "N",
                "PAGER_TITLE" => "Новости",
                "PAGER_TEMPLATE" => "modern",
                "PAGER_SHOW_ALWAYS" => "N",
                "PAGER_DESC_NUMBERING" => "N",
                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                "PAGER_SHOW_ALL" => "Y",
                "PAGER_BASE_LINK_ENABLE" => "N",
                "PAGER_BASE_LINK" => NULL,
                "PAGER_PARAMS_NAME" => NULL,
                "DISPLAY_DATE" => "N",
                "DISPLAY_NAME" => "Y",
                "DISPLAY_PICTURE" => "Y",
                "DISPLAY_PREVIEW_TEXT" => "Y",
                "PREVIEW_TRUNCATE_LEN" => "",
                "ACTIVE_DATE_FORMAT" => "d.m.Y",
                "USE_PERMISSIONS" => "N",
                "GROUP_PERMISSIONS" => NULL,
                "FILTER_NAME" => "searchResult",
                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                "CHECK_DATES" => "Y",
                "CALCULATION_PRICE_RESULT" => [],
                "TYPE" => "placements",
//                "__BOOKING_REQUEST" => $arParams["__BOOKING_REQUEST"]
            ));
            ?>
        <?endif;?>

    <?if(!empty($arResult["TYPE"]['EXCURSION'])):?>
        <h3><?=GetMessage('EXCURSION')?></h3>
        <? $GLOBALS['searchResult']['ID'] = $arResult["TYPE"]['EXCURSION']; ?>
        <?
        $APPLICATION->IncludeComponent(
            "travelsoft:travelsoft.news.list", "search-excursions-list", Array(
            "IBLOCK_TYPE" => 'Tourproduct',
            "IBLOCK_ID" => EXCURSION_IBLOCK_ID,
            "NEWS_COUNT" => $arParams["PAGE_RESULT_COUNT"],
            "SORT_BY1" => 'SORT',
            "SORT_ORDER1" => 'ASC',
            "SORT_BY2" => 'NAME',
            "SORT_ORDER2" => 'ASC',
            "FIELD_CODE" => [],

            "PROPERTY_CODE"=>["COUNTRY","REGIONS","DAYS","TOWN","ROUTE","FOOD","HOTEL","HD_DESC","YOUTUBE","VIMEO","DEPARTURE_TIME","TOURS_DATE","TOURS_DATE_BY","TOURTYPE",
                "TRANSPORT","SERVICES","ADDRESS","MAP","TYPE","MAP_SCALE","PICTURES","NAME_BY","ADDRESS_BY","HD_DESC_BY","NAME_EN","ADDRESS_EN","HD_DESC_EN"],
            "DETAIL_URL" => "/tourism/cognitive-tourism/#ELEMENT_CODE#/",
            "SECTION_URL" => "/tourism/cognitive-tourism/",
            "IBLOCK_URL" => "/tourism/cognitive-tourism/",
            "TOURS_URL" => "/tourism/tours-in-belarus/",
            "DISPLAY_PANEL" => NULL,
            "SET_TITLE" => "N",
            "SET_LAST_MODIFIED" => "N",
            "MESSAGE_404" => "",
            "SET_STATUS_404" => "Y",
            "SHOW_404" => "Y",
            "FILE_404" => "",
            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "86400",
            "CACHE_FILTER" => "N",
            "CACHE_GROUPS" => "N",
            "DISPLAY_TOP_PAGER" => "N",
            "DISPLAY_BOTTOM_PAGER" => "N",
            "PAGER_TITLE" => "Новости",
            "PAGER_TEMPLATE" => "modern",
            "PAGER_SHOW_ALWAYS" => "N",
            "PAGER_DESC_NUMBERING" => "N",
            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
            "PAGER_SHOW_ALL" => "Y",
            "PAGER_BASE_LINK_ENABLE" => "N",
            "PAGER_BASE_LINK" => NULL,
            "PAGER_PARAMS_NAME" => NULL,
            "DISPLAY_DATE" => "N",
            "DISPLAY_NAME" => "Y",
            "DISPLAY_PICTURE" => "Y",
            "DISPLAY_PREVIEW_TEXT" => "Y",
            "PREVIEW_TRUNCATE_LEN" => "",
            "ACTIVE_DATE_FORMAT" => "d.m.Y",
            "USE_PERMISSIONS" => "N",
            "GROUP_PERMISSIONS" => NULL,
            "FILTER_NAME" => "searchResult",
            "HIDE_LINK_WHEN_NO_DETAIL" => "N",
            "CHECK_DATES" => "Y",
            "CALCULATION_PRICE_RESULT" => [],
            "TYPE" => "excursions",
//            "__BOOKING_REQUEST" => NULL
        ));
        ?>
    <?endif;?>

    <?if(!empty($arResult["TYPE"]['ABOUT_BELARUS']['TEXT']) || !empty($arResult["TYPE"]['ABOUT_BELARUS']['IBLOCK'])):?>
        <h3><?=GetMessage('ABOUT_BELARUS')?></h3>
    <?endif;?>

    <?if(!empty($arResult["TYPE"]['ABOUT_BELARUS']['IBLOCK'])):?>
        <?foreach($arResult["TYPE"]['ABOUT_BELARUS']['IBLOCK'] as $iblockID => $arElementId):?>
            <? $GLOBALS['searchResult']['ID'] = $arElementId["ID"]; ?>
            <?$APPLICATION->IncludeComponent(
                "bitrix:news.list",
                "search-list-news-line",
                array(
                    "IBLOCK_TYPE" => "",
                    "IBLOCK_ID" => $iblockID,
                    "NEWS_COUNT" => "100",
                    "SORT_BY1" => "ACTIVE_FROM",
                    "SORT_ORDER1" => "DESC",
                    "SORT_BY2" => "SORT",
                    "SORT_ORDER2" => "ASC",
                    "FIELD_CODE" => array(),
                    "PROPERTY_CODE"=>array("TOWN","REGION","ADDRESS","PREVIEW_TEXT","DETAIL_TEXT","MAP","MAP_SCALE","YOUTUBE","VIMEO","TYPE","COUNTRY","REGIONS","HD_DESC","PICTURES",
                        "KITCHEN2","NAME_BY","ADDRESS_BY","HD_DESC_BY","NAME_EN","ADDRESS_EN","HD_DESC_EN"),
                    "DETAIL_URL" => "",
                    "SECTION_URL" => "",
                    "IBLOCK_URL" => "",
                    "DISPLAY_PANEL" => NULL,
                    "SET_TITLE" => "N",
                    "SET_LAST_MODIFIED" => "N",
                    "MESSAGE_404" => "",
                    "SET_STATUS_404" => "N",
                    "SHOW_404" => "N",
                    "FILE_404" => NULL,
                    "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "86400",
                    "CACHE_FILTER" => "N",
                    "CACHE_GROUPS" => "N",
                    "DISPLAY_TOP_PAGER" => "N",
                    "DISPLAY_BOTTOM_PAGER" => "Y",
                    "PAGER_TITLE" => "Новости",
                    "PAGER_TEMPLATE" => "modern",
                    "PAGER_SHOW_ALWAYS" => "N",
                    "PAGER_DESC_NUMBERING" => "N",
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                    "PAGER_SHOW_ALL" => "N",
                    "PAGER_BASE_LINK_ENABLE" => "N",
                    "PAGER_BASE_LINK" => NULL,
                    "PAGER_PARAMS_NAME" => NULL,
                    "DISPLAY_DATE" => empty($arElementId["DISPLAY_DATE"]) ? "N" : "Y",
                    "DISPLAY_NAME" => "Y",
                    "DISPLAY_PICTURE" => "Y",
                    "DISPLAY_PREVIEW_TEXT" => "Y",
                    "PREVIEW_TRUNCATE_LEN" => "",
                    "ACTIVE_DATE_FORMAT" => "d.m.Y",
                    "USE_PERMISSIONS" => "N",
                    "GROUP_PERMISSIONS" => NULL,
                    "FILTER_NAME" => "searchResult",
                    "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                    "CHECK_DATES" => "Y",
                )
            );?>
        <?endforeach;?>
    <?endif;?>

    <?if(!empty($arResult["TYPE"]['ABOUT_BELARUS']['TEXT'])):?>
        <?foreach($arResult["TYPE"]['ABOUT_BELARUS']['TEXT'] as $arItem):?>
            <a href="<?echo $arItem["URL"]?>"><?echo $arItem["TITLE_FORMATED"]?></a>
            <p><?echo $arItem["BODY_FORMATED"]?></p>
            <small><?=GetMessage("SEARCH_MODIFIED")?> <?=$arItem["DATE_CHANGE"]?></small><br /><?
            if($arItem["CHAIN_PATH"]):?>
                <small><?=GetMessage("SEARCH_PATH")?>&nbsp;<?=$arItem["CHAIN_PATH"]?></small><?
            endif;
            ?><hr />
        <?endforeach;?>
    <?endif;?>

    <? if($arParams["DISPLAY_BOTTOM_PAGER"] != "N") echo $arResult["NAV_STRING"]; ?>
<?else:?>
	<?ShowNote(GetMessage("SEARCH_NOTHING_TO_FOUND"));?>
<?endif;?>
</div>