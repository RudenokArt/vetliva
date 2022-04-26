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
$this->setFrameMode(false);

if (empty($arResult['ITEMS'])) return;

foreach ($arResult['ITEMS'] as $arItem) {

	$label = $arItem['NAME'];
	if(LANGUAGE_ID != "ru") {
		$label = $arItem["PROPERTIES"]['NAME'.POSTFIX_PROPERTY]["VALUE"];
	}

    $source[] = array("label" => $label, "value" => $label, "id" => $arItem['ID']);
    $data[$arItem['ID']] = $arItem['DETAIL_PAGE_URL'] . "?booking[id][]=" . $arItem['ID'];
    
}

?>

<div class="narrow-results">
        <h6><?=GetMessage("NAME")?></h6>
        <div class="narrow-form">
            <form id="search__by__name" action="" method="post">
                <input id="search__by__name_input" type="text" name="" class="narrow-input" placeholder="<?=GetMessage("ENTERTITLE")?>"> 
                <button class="submit-narrow"></button>
            </form>
        </div>
</div>

<script>
(function ($) {
    
    var 
        
        highlight = function (ul, it) {
    
            var v = $(this.element[0]).val(), w = it.label;

            if (v != '') 
                w = w.replace(new RegExp("("+$.ui.autocomplete.escapeRegex(v)+")", "ig" ), "<strong>$1</strong>");

            return $( "<li></li>" )
                  .data( "ui-autocomplete-item", it)
                  .append( w )
                  .appendTo( ul );
        },
                
        data = <?= \Bitrix\Main\Web\Json::encode($data)?>;
    
        $("#search__by__name_input").autocomplete({

            source: <?= \Bitrix\Main\Web\Json::encode($source)?>,
            
            select: function ( event, ui ) {
                
                if (data[ui.item.id]) {
                    $("#search__by__name").attr("action", data[ui.item.id]);
                }
                
            }

        }).on('focus', function () { $(this).autocomplete('search'); }).data( "ui-autocomplete" )._renderItem = highlight;
        
        $("#search__by__name").on("submit", function () {
            
            if($(this).attr('action') == "") {
                return false;
            };
            
        });
        
})(jQuery);
</script>