<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$APPLICATION->AddHeadScript($this->GetFolder() . '/ru/script.js');
?>
<?=ShowError($arResult["ERROR_MESSAGE"]);?>


<? 
/*$hkInst=CHotKeys::getInstance();
$arHK = array("B", "I", "U", "QUOTE", "CODE", "TRANSLIT");
foreach($arHK as $n => $s)
{		
	$arExecs = $hkInst->GetCodeByClassName("TICKET_EDIT_$s");
	echo $hkInst->PrintJSExecs($arExecs);
}*/

if (!empty($arResult["TICKET"])):
?>


<?
	if (!empty($arResult["ONLINE"]))
	{
?>
<p>
	<?$time = intval($arResult["OPTIONS"]["ONLINE_INTERVAL"]/60)." ".GetMessage("SUP_MIN");?>
	<?=str_replace("#TIME#",$time,GetMessage("SUP_USERS_ONLINE"));?>:<br />
	<?foreach($arResult["ONLINE"] as $arOnlineUser):?>
	<small>(<?=$arOnlineUser["USER_LOGIN"]?>) <?=$arOnlineUser["USER_NAME"]?> [<?=$arOnlineUser["TIMESTAMP_X"]?>]</small><br />
	<?endforeach?>
</p>
<?
	}
?>


<p><b><?=$arResult["TICKET"]["TITLE"]?></b></p>

<table class="support-ticket-edit data-table">

	<tr>
		<th><?=GetMessage("SUP_TICKET")?></th>
	</tr>

	<tr>
		<td>
		
		<?=GetMessage("SUP_SOURCE")." / ".GetMessage("SUP_FROM")?>:

			<?if (strlen($arResult["TICKET"]["SOURCE_NAME"])>0):?>
				[<?=$arResult["TICKET"]["SOURCE_NAME"]?>]
			<?else:?>
				[web]
			<?endif?>

			<?if (strlen($arResult["TICKET"]["OWNER_SID"])>0):?>
				<?=$arResult["TICKET"]["OWNER_SID"]?>
			<?endif?>

			<?if (intval($arResult["TICKET"]["OWNER_USER_ID"])>0):?>
				[<?=$arResult["TICKET"]["OWNER_USER_ID"]?>] 
				(<?=$arResult["TICKET"]["OWNER_LOGIN"]?>) 
				<?=$arResult["TICKET"]["OWNER_NAME"]?>
			<?endif?>
		<br />

		
		<?=GetMessage("SUP_CREATE")?>: <?=$arResult["TICKET"]["DATE_CREATE"]?> 

		<?if (strlen($arResult["TICKET"]["CREATED_MODULE_NAME"])<=0 || $arResult["TICKET"]["CREATED_MODULE_NAME"]=="support"):?>
			[<?=$arResult["TICKET"]["CREATED_USER_ID"]?>] 
			(<?=$arResult["TICKET"]["CREATED_LOGIN"]?>) 
			<?=$arResult["TICKET"]["CREATED_NAME"]?>
		<?else:?>
			<?=$arResult["TICKET"]["CREATED_MODULE_NAME"]?>
		<?endif?>
		<br />

		
		<?if ($arResult["TICKET"]["DATE_CREATE"]!=$arResult["TICKET"]["TIMESTAMP_X"]):?>
				<?=GetMessage("SUP_TIMESTAMP")?>: <?=$arResult["TICKET"]["TIMESTAMP_X"]?>

				<?if (strlen($arResult["TICKET"]["MODIFIED_MODULE_NAME"])<=0 || $arResult["TICKET"]["MODIFIED_MODULE_NAME"]=="support"):?>
					[<?=$arResult["TICKET"]["MODIFIED_USER_ID"]?>] 
					(<?=$arResult["TICKET"]["MODIFIED_BY_LOGIN"]?>) 
					<?=$arResult["TICKET"]["MODIFIED_BY_NAME"]?>
				<?else:?>
					<?=$arResult["TICKET"]["MODIFIED_MODULE_NAME"]?>
				<?endif?>

				<br />
		<?endif?>

		
		<? if (strlen($arResult["TICKET"]["DATE_CLOSE"])>0): ?>
			<?=GetMessage("SUP_CLOSE")?>: <?=$arResult["TICKET"]["DATE_CLOSE"]?>
		<?endif?>

		
		<?if (strlen($arResult["TICKET"]["STATUS_NAME"])>0) :?>
				<?=GetMessage("SUP_STATUS")?>: <span title="<?=$arResult["TICKET"]["STATUS_DESC"]?>"><?=$arResult["TICKET"]["STATUS_NAME"]?></span><br />
		<?endif;?>

		
		<?if (strlen($arResult["TICKET"]["CATEGORY_NAME"]) > 0):?>
				<?=GetMessage("SUP_CATEGORY")?>: <span title="<?=$arResult["TICKET"]["CATEGORY_DESC"]?>"><?=$arResult["TICKET"]["CATEGORY_NAME"]?></span><br />
		<?endif?>

		
		<?if(strlen($arResult["TICKET"]["CRITICALITY_NAME"])>0) :?>
				<?=GetMessage("SUP_CRITICALITY")?>: <span title="<?=$arResult["TICKET"]["CRITICALITY_DESC"]?>"><?=$arResult["TICKET"]["CRITICALITY_NAME"]?></span><br />
		<?endif?>

		
		<?if (intval($arResult["RESPONSIBLE_USER_ID"])>0):?>
			<?=GetMessage("SUP_RESPONSIBLE")?>: [<?=$arResult["TICKET"]["RESPONSIBLE_USER_ID"]?>]
			(<?=$arResult["TICKET"]["RESPONSIBLE_LOGIN"]?>) <?=$arResult["TICKET"]["RESPONSIBLE_NAME"]?><br />
		<?endif?>

		
		<?if (strlen($arResult["TICKET"]["SLA_NAME"])>0) :?>
			<?=GetMessage("SUP_SLA")?>: 
			<span title="<?=$arResult["TICKET"]["SLA_DESCRIPTION"]?>"><?=$arResult["TICKET"]["SLA_NAME"]?></span>
		<?endif?>


		</td>
	</tr>


	<tr>
		<th><?=GetMessage("SUP_DISCUSSION")?></th>
	</tr>


	<tr>
		<td>
	<?=$arResult["NAV_STRING"]?>

	<?foreach ($arResult["MESSAGES"] as $arMessage):?>
		<div class="ticket-edit-message">

		<div class="support-float-quote">[&nbsp;<a href="#postform" OnMouseDown="javascript:SupQuoteMessage('quotetd<? echo $arMessage["ID"]; ?>')" title="<?=GetMessage("SUP_QUOTE_LINK_DESCR");?>"><?echo GetMessage("SUP_QUOTE_LINK");?></a>&nbsp;]</div>

		
		<div align="left"><b><?=GetMessage("SUP_TIME")?></b>: <?=$arMessage["DATE_CREATE"]?></div>
		<b><?=GetMessage("SUP_FROM")?></b>:

		
		<?=$arMessage["OWNER_SID"]?>

		<?if (intval($arMessage["OWNER_USER_ID"])>0):?>
			[<?=$arMessage["OWNER_USER_ID"]?>] 
			(<?=$arMessage["OWNER_LOGIN"]?>) 
			<?=$arMessage["OWNER_NAME"]?>
		<?endif?>
		<br />

		
		<?
		$aImg = array("gif", "png", "jpg", "jpeg", "bmp");
		foreach ($arMessage["FILES"] as $arFile):
		?>
		<div class="support-paperclip"></div>
		<?if(in_array(strtolower(GetFileExtension($arFile["NAME"])), $aImg)):?>
			<a title="<?=GetMessage("SUP_VIEW_ALT")?>" href="<?=$componentPath?>/ticket_show_file.php?hash=<?echo $arFile["HASH"]?>&amp;lang=<?=LANG?>"><?=$arFile["NAME"]?></a> 
		<?else:?>
			<?=$arFile["NAME"]?>
		<?endif?>
		(<? echo CFile::FormatSize($arFile["FILE_SIZE"]); ?>)
		[ <a title="<?=str_replace("#FILE_NAME#", $arFile["NAME"], GetMessage("SUP_DOWNLOAD_ALT"))?>" href="<?=$componentPath?>/ticket_show_file.php?hash=<?=$arFile["HASH"]?>&amp;lang=<?=LANG?>&amp;action=download"><?=GetMessage("SUP_DOWNLOAD")?></a> ]
		<br class="clear" />
		<?endforeach?>

		
		<br /><div id="quotetd<? echo $arMessage["ID"]; ?>"><?=$arMessage["MESSAGE"]?></div>

		</div>
	<?endforeach?>

	<?=$arResult["NAV_STRING"]?>

		</td>

	</tr>
</table>



<br />
<?endif;?>


<form name="support_edit" method="post" action="<?=$arResult["REAL_FILE_PATH"]?>" enctype="multipart/form-data">
	<div class="panel panel-flat">
		<div class="panel-body">
			<?=bitrix_sessid_post()?>
			<input type="hidden" name="set_default" value="Y" />
			<input type="hidden" name="ID" value=<?=(empty($arResult["TICKET"]) ? 0 : $arResult["TICKET"]["ID"])?> />
			<input type="hidden" name="lang" value="<?=LANG?>" />
			<input type="hidden" name="edit" value="1" />
				<?if (empty($arResult["TICKET"])):?>
				<div class="form-group">
					<label><b><span class="starrequired">*</span><?=GetMessage("SUP_TITLE")?></b></label>
					<input class="form-control" type="text" name="TITLE" value="<?=htmlspecialcharsbx($_REQUEST["TITLE"])?>" size="25" value=""><br>
				</div>
				<?else:?>
				<div class="form-group">
					<?=GetMessage("SUP_ANSWER")?>
				</div>
			
				<?endif?>
			
			
				<?if (strlen($arResult["TICKET"]["DATE_CLOSE"]) <= 0):?>
				<div class="form-group">
					<label><b><span class="starrequired">*</span><?=GetMessage("SUP_MESSAGE")?></b></label>
					<input accesskey="b" type="button" value="<?=GetMessage("SUP_B")?>" onClick="insert_tag('B', document.forms['support_edit'].elements['MESSAGE'])"  name="B" id="B" title="<? echo GetMessage("SUP_B_ALT"); ?>" />
					<input accesskey="i" type="button" value="<?=GetMessage("SUP_I")?>" onClick="insert_tag('I', document.forms['support_edit'].elements['MESSAGE'])" name="I" id="I" title="<? echo GetMessage("SUP_I_ALT"); ?>" />
					<input accesskey="u" type="button" value="<?=GetMessage("SUP_U")?>" onClick="insert_tag('U', document.forms['support_edit'].elements['MESSAGE'])" name="U" id="U" title="<? echo GetMessage("SUP_U_ALT"); ?>" />
					<input accesskey="q" type="button" value="<?=GetMessage("SUP_QUOTE")?>" onClick="insert_tag('QUOTE', document.forms['support_edit'].elements['MESSAGE'])" name="QUOTE" id="QUOTE" title="<? echo GetMessage("SUP_QUOTE_ALT"); ?>" />
					<input accesskey="c" type="button" value="<?=GetMessage("SUP_CODE")?>" onClick="insert_tag('CODE', document.forms['support_edit'].elements['MESSAGE'])" name="CODE" id="CODE" title="<? echo GetMessage("SUP_CODE_ALT"); ?>" />
					<?if (LANG == "ru"):?>
						<input accesskey="t" type="button" accesskey="t" value="<?=GetMessage("SUP_TRANSLIT")?>" onClick="translit(document.forms['support_edit'].elements['MESSAGE'])" name="TRANSLIT" id="TRANSLIT" title="<? echo GetMessage("SUP_TRANSLIT_ALT"); ?>" />
					<?endif?>
				</div>
				<div class="form-group">
					<textarea name="MESSAGE" id="MESSAGE" rows="20" style="width:100%" wrap="virtual"><?=htmlspecialcharsbx($_REQUEST["MESSAGE"])?></textarea>
				</div>
				<div class="form-group">
					<label><b>
						<?=GetMessage("SUP_ATTACH")?><br />
						(max - <?=$arResult["OPTIONS"]["MAX_FILESIZE"]?> <?=GetMessage("SUP_KB")?>):
						<input type="hidden" name="MAX_FILE_SIZE" value="<?=($arResult["OPTIONS"]["MAX_FILESIZE"]*1024)?>">
					</b></label>
				</div>
				<div class="form-group">
							<input name="FILE_0" size="30" type="file" class="file-styled"/>
				</div>
				<div class="form-group">
							<input name="FILE_1" size="30" type="file" class="file-styled"/>
				</div>
				<div class="form-group">
							<input name="FILE_2" size="30" type="file" class="file-styled"/>
				</div>
				<div class="form-group">
							<span id="files_table_2"></span>
							<input type="button" value="<?=GetMessage("SUP_MORE")?>" OnClick="AddFileInput('<?=GetMessage("SUP_MORE")?>')" class="btn btn-primary"/>
							<input type="hidden" name="files_counter" id="files_counter" value="2" />
				</div>
				<?endif?>

				
				<div class="form-group">
					<label><b><?=GetMessage("SUP_CRITICALITY")?></b></label>
						<?
						if (empty($arResult["TICKET"]) || strlen($arResult["ERROR_MESSAGE"]) > 0 )
						{
							if (strlen($arResult["DICTIONARY"]["CRITICALITY_DEFAULT"]) > 0 && strlen($arResult["ERROR_MESSAGE"]) <= 0)
								$criticality = $arResult["DICTIONARY"]["CRITICALITY_DEFAULT"];
							else
								$criticality = htmlspecialcharsbx($_REQUEST["CRITICALITY_ID"]);
						}
						else
							$criticality = $arResult["TICKET"]["CRITICALITY_ID"];
						?>
						<select name="CRITICALITY_ID" id="CRITICALITY_ID" class="select">
							<option value="">&nbsp;</option>
						<?foreach ($arResult["DICTIONARY"]["CRITICALITY"] as $value => $option):?>
							<option value="<?=$value?>" <?if($criticality == $value):?>selected="selected"<?endif?>><?=$option?></option>
						<?endforeach?>
						</select>
				</div>
			
				<?if (empty($arResult["TICKET"])):?>
				<div class="form-group">
					<label><b><?=GetMessage("SUP_CATEGORY")?></b></label>
						<?
						if (strlen($arResult["DICTIONARY"]["CATEGORY_DEFAULT"]) > 0 && strlen($arResult["ERROR_MESSAGE"]) <= 0)
							$category = $arResult["DICTIONARY"]["CATEGORY_DEFAULT"];
						else
							$category = htmlspecialcharsbx($_REQUEST["CATEGORY_ID"]);
						?>
						<select name="CATEGORY_ID" id="CATEGORY_ID" class="select">
							<option value="">&nbsp;</option>
						<?foreach ($arResult["DICTIONARY"]["CATEGORY"] as $value => $option):?>
							<option value="<?=$value?>" <?if($category == $value):?>selected="selected"<?endif?>><?=$option?></option>
						<?endforeach?>
						</select>
				</div>
				<?else:?>
				<div class="form-group">
					<label><b><?=GetMessage("SUP_MARK")?></b></label>
						<?$mark = (strlen($arResult["ERROR_MESSAGE"]) > 0 ? htmlspecialcharsbx($_REQUEST["MARK_ID"]) : $arResult["TICKET"]["MARK_ID"]);?>
						<select name="MARK_ID" id="MARK_ID" class="select">
							<option value="">&nbsp;</option>
						<?foreach ($arResult["DICTIONARY"]["MARK"] as $value => $option):?>
							<option value="<?=$value?>" <?if($mark == $value):?>selected="selected"<?endif?>><?=$option?></option>
						<?endforeach?>
						</select>
				</div>
				<?endif?>
			
			
			
				<?if (strlen($arResult["TICKET"]["DATE_CLOSE"])<=0):?>
				<div class="form-group">
					<label><b><?=GetMessage("SUP_CLOSE_TICKET")?></b></label>
					<input type="checkbox" name="CLOSE" value="Y" <?if($arResult["TICKET"]["CLOSE"] == "Y"):?>checked="checked" <?endif?>/>
				</div>
				<?else:?>
				<div class="form-group">
					<label><b><?=GetMessage("SUP_OPEN_TICKET")?></b></label>
					<td><input type="checkbox" name="OPEN" value="Y" <?if($arResult["TICKET"]["OPEN"] == "Y"):?>checked="checked" <?endif?>/>
					</td>
				</div>
				<?endif;?>
				<?if ($arParams['SHOW_COUPON_FIELD'] == 'Y' && $arParams['ID'] <= 0){?>
				<div class="form-group">
					<label><b><?=GetMessage("SUP_COUPON")?></b></label>
					<input type="text" name="COUPON" value="<?=htmlspecialcharsbx($_REQUEST["COUPON"])?>" size="48" maxlength="255" />
				</div>
				<?}?>
				
				<?
					global $USER_FIELD_MANAGER;
					if( isset( $arParams["SET_SHOW_USER_FIELD_T"] ) )
					{
						foreach( $arParams["SET_SHOW_USER_FIELD_T"] as $k => $v )
						{
							$v["ALL"]["VALUE"] = $arParams[$k];
							echo '<tr><td  class="field-name">' . htmlspecialcharsbx( $v["NAME_F"] ) . ':</td><td>';
							$APPLICATION->IncludeComponent(
									'bitrix:system.field.edit',
									$v["ALL"]['USER_TYPE_ID'],
									array(
										'arUserField' => $v["ALL"],
									),
									null,
									array('HIDE_ICONS' => 'Y')
							);
							echo '</td></tr>';
						}
					}
				?>
					
				</tbody>
			</table>
			<br />
			<input type="submit" name="save" value="<?=GetMessage("SUP_SAVE")?>"  class="btn btn-primary"/>&nbsp;
			<!-- <input type="submit" name="apply" value="<?=GetMessage("SUP_APPLY")?>" class="btn btn-primary"/>&nbsp; -->
			<input type="reset" value="<?=GetMessage("SUP_RESET")?>" class="btn btn-danger"/>
			<input type="hidden" value="Y" name="apply" />
			
			<script type="text/javascript">
			BX.ready(function(){
				var buttons = BX.findChildren(document.forms['support_edit'], {attr:{type:'submit'}});
				for (i in buttons)
				{
					BX.bind(buttons[i], "click", function(e) {
						setTimeout(function(){
							var _buttons = BX.findChildren(document.forms['support_edit'], {attr:{type:'submit'}});
							for (j in _buttons)
							{
								_buttons[j].disabled = true;
							}
			
						}, 30);
					});
				}
			});
			</script>
		</div>
	</div>
</form>
<p><span class="starrequired">*</span><?=GetMessage("SUP_REQ")?></p>