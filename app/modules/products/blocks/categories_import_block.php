<?php if (!isset($webpage)) die('Direct access not allowed'); ?>
<table cellspacing="0" cellpadding="0" border="0" width="100%" class="grid_view" id="sortable">
	<tr>
		<th style="width:30px"><input type="checkbox" id="chkAll" name="chkAll" value="1" /></th>
		<th><?php echo $trans['categories_import.name']?></th>
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
			<?php echo $row->name?>
		</td>
		<td class="grid_options">
			<?php echo HtmlControls::GenerateAdminDeleteLink($row->id)?>
		</td>
	</tr>
<?php 
	}
?>						
</table>