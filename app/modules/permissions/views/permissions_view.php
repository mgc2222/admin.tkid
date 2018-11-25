<?php if (!isset($webpage)) die('Direct access not allowed');  ?>
<?php
	if ($dataView->rows != null) 
	{ 
?>
<div class="grid_wrapper">
	<div><?php echo HtmlControls::GenerateSortHidColumn($dataView->dataSort->permissions); ?></div>
	<table cellspacing="2" cellpadding="2" border="0" width="100%" class="grid_view">
		<tr>
			<th style="width:25px"><input type="checkbox" id="chkAll" name="chkAll" value="1" onclick="htmlCtl.ToggleCheckboxes('chkAll','multi_checkbox');" /></th>
			<th>&nbsp;</th>
			<th><?php echo HtmlControls::GenerateSortableColumn($dataView->dataSort->permissions, 'name', $trans['general.sort_by'].$trans['general.name'], $trans['general.name']); ?></th>
			<th><?php echo $trans['general.description']?></th>
			<th><?php echo HtmlControls::GenerateSortableColumn($dataView->dataSort->permissions, 'page_id', $trans['general.sort_by'].$trans['permissions.page_id'], $trans['permissions.page_id']); ?></th>
			<th style="width:150px"><?php echo $trans['general.actions']?></th>
		</tr>
<?php
		$rowIndex = 0;
		foreach ($dataView->rows as &$row)
		{
			$rowIndex++;
?>
		<tr>
			<td><input type="checkbox" name="multipleIds[]" value="<?php echo $row->id;?>" class="multi_checkbox" /></td>
			<td><?php echo $rowIndex?>.</td>
			<td><?php echo $row->name?></td>
			<td><?php echo $row->description?></td>
			<td><?php echo $row->page_id?></td>
			<td class="grid_options">
				<?php echo HtmlControls::GenerateAdminEditLink('permissions', $row->id)?>&nbsp;&nbsp;
				<?php echo HtmlControls::GenerateAdminDeleteLink($row->id)?>
			</td>
		</tr>
<?php
		}
?>
	</table>
</div>	
<?php 
	} 
?>
<div class="grid_buttons">
	<?php echo HtmlControls::GenerateGridButtons('permissions', $trans['permissions.new_item'], $trans['permissions.delete_selected_items'])?>
</div>