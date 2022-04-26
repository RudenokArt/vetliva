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
?>
<?if (!empty($arResult["ITEMS"])):?>
<div class="specialoffer-cn">
	<div class="">
		<div id="specialoffer-slide" class="<?if(count($arResult["ITEMS"]) > 1):?>specialoffer_slide owl-carousel <?endif?> ">
		<?$i=0;?>
		<?foreach($arResult["ITEMS"] as $arItem):
            if (LANGUAGE_ID!='ru') {
                if (!empty($arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"])) $arItem["NAME"] = $arItem["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"];
                if (!empty($arItem["PROPERTIES"]["PREVIEW_TEXT".POSTFIX_PROPERTY]["VALUE"])) $arItem["~PREVIEW_TEXT"] = $arItem["PROPERTIES"]["PREVIEW_TEXT".POSTFIX_PROPERTY]["~VALUE"]['TEXT'];
                if (!empty($arItem["PROPERTIES"]["LINK".POSTFIX_PROPERTY]["VALUE"])) $arItem["PROPERTIES"]["LINK"]["VALUE"] = $arItem["PROPERTIES"]["LINK".POSTFIX_PROPERTY]["VALUE"];
            }
        ?>
			<?$i++;?>
				
			<div style="padding: 0px" class="specialoffer-item <?if (!empty($arItem["PREVIEW_PICTURE"])):?>specialoffer-img<?endif;?>" data-merge="1">
					 <?if ($arItem["PROPERTIES"]["LINK_YT"]["VALUE"]!=''):?>
                    <a class="owl-video" onclick="setclick('<?=$arItem['ID']?>')" href="<?=$arItem["PROPERTIES"]["LINK_YT"]["VALUE"]?>?autoplay=1">
						<?/*<div class="block-with-frame">
						<?=$arItem["PROPERTIES"]["FRAME"]["~VALUE"]['TEXT']?></div>*/?>
					</a>
					
                    <?else:?>
					 <?
                        if (!empty($arItem["PROPERTIES"]["PICTURES".POSTFIX_PROPERTY]["VALUE"])):
    					$an_file = CFile::ResizeImageGet($arItem["PROPERTIES"]["PICTURES".POSTFIX_PROPERTY]["VALUE"], array('width'=>1400, 'height'=>470), BX_RESIZE_IMAGE_EXACT, true, array(), false, 70);
    					$pre_photo=$an_file["src"];
                        elseif (!empty($arItem["PREVIEW_PICTURE"])):
    					$an_file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width'=>1400, 'height'=>470), BX_RESIZE_IMAGE_EXACT, true, array(), false, 70);
    					$pre_photo=$an_file["src"];     
                        else:
                        $pre_photo=SITE_TEMPLATE_PATH."/images/nophoto-292x180.jpg";                   
    					endif;
			         ?>
					<a onclick="setclick('<?=$arItem['ID']?>')" href="<?=$arItem["PROPERTIES"]["LINK"]["VALUE"]?>" title="">
            			<img  src="<?=$pre_photo?>">
					</a>
					
					<?endif;?>		
                    <div class="home-specialoffer-text <?if ($arItem["PROPERTIES"]["FRAME"]["VALUE"]['TEXT']!=''):?>frame-text<?endif;?>">
					       <div class="home-specialoffer-name">
								 <a class="specialofferlink" onclick="setclick('<?=$arItem['ID']?>')" href="<?=$arItem["PROPERTIES"]["LINK"]["VALUE"]?>" title="">
									<?=$arItem["NAME"];?>
								</a>
							</div>
                            <?if ($arItem["~PREVIEW_TEXT"]!=''):?>
	                       <div class="home-specialoffer-subtext">
								 <a class="specialofferlink" onclick="setclick('<?=$arItem['ID']?>')" href="<?=$arItem["PROPERTIES"]["LINK"]["VALUE"]?>" title="">
									<?=$arItem["~PREVIEW_TEXT"];?>
								</a>
							</div>
                            <?endif;?>
                            <?if ($arItem["PROPERTIES"]["LINK"]["VALUE"]!=''):?>
                            <a class="specialofferlink more-btn" onclick="setclick('<?=$arItem['ID']?>')" href="<?=$arItem["PROPERTIES"]["LINK"]["VALUE"]?>" title=""><?=GetMessage("MORE")?></a>
                            <?endif;?>
						</div>
				 </div>
		<?endforeach;?>
		</div>
	</div>
</div>
<script>
    function setclick(id) {
        var formData = new FormData();  
        formData.append('action','increase_query');
        formData.append('element_id',id);
        var xhr = new XMLHttpRequest();
    	xhr.open("POST", "<?=$templateFolder?>/ajax.php");
    
    	xhr.onreadystatechange = function() {
    		if (xhr.readyState == 4) {
    			if(xhr.status == 200) {
    				data = xhr.responseText;
                    var result = BX.parseJSON(data);
                    console.log(result);
                }
    		}
    	};
        xhr.send(formData); 
    }
    $('.specialoffer_slide').owlCarousel({
        items: 1,
        loop:true,
        margin:20,
		video:true,
        nav:true,
        navText: ['<span class="prev-next-room prev-room"></span>','<span class="prev-next-room next-room"></span>'],
        dots: true,
		//lazyLoad:true,
		pagination : true,
		responsive : {	
				0 : {
					items: 1,
					margin: 10,
					slideBy:1,
					stagePadding: 20,
				},
				
				480 : {
					items: 2,
					slideBy:1,
					margin: 10,
					stagePadding: 20,
				},

				768 : {
					items: 1,
					slideBy:1,
				}
				,
				991 : {
					items: 1,
				}
		}
    })
	
</script>
<?else:?>
<script>
$('section.specialoffers').remove();
</script>
<?endif;?>