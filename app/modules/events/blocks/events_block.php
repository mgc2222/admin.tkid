<?php if (!isset($webpage)) die('Direct access not allowed'); ?>
<table cellspacing="0" cellpadding="0" border="0" width="100%" class="grid_view" id="sortable">
	<tr>
		<th style="width:30px"><input type="checkbox" id="chkAll" name="chkAll" value="1" /></th>
		<th><?php echo $trans['categories.name']?></th>
		<th style="width:180px"><?php echo $trans['general.actions']?></th>
	</tr>
<?php
	$rowIndex = 0;
	foreach ($dataView->rows as $row ) 
	{ 
?>
	<tr>
		<td><input type="checkbox" name="multipleIds[]" value="<?php echo $row->id;?>" class="multi_checkbox" />
		<input type="hidden" id="hidSortPK_<?php echo $rowIndex;?>" value="<?php echo $row->id;?>" />
		</td>
		<td class="display_categories">
			<?php echo $row->Indent?>
			<?php echo $row->DisplayName?>
		</td>
		<td class="grid_options">
			<?php echo HtmlControls::GenerateAdminLink(_SITE_RELATIVE_URL.'products/cid='.$row->id, $trans['categories.edit_articles_items'],'','ico_edit_articles.png','')?>&nbsp;&nbsp;&nbsp;
			<?php echo HtmlControls::GenerateAdminEditLink('categories', $row->id)?>&nbsp;&nbsp;
			<?php echo HtmlControls::GenerateAdminDeleteLink($row->id)?>
		</td>
	</tr>
<?php 
	}
?>						
</table>