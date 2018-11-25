<?php if (!isset($webpage)) die('Direct access not allowed');  ?>
<?php
	if ($dataView->rows != null) 
	{ 
?>
<div class="grid_wrapper">
	<div><?php echo HtmlControls::GenerateSortHidColumn($dataView->dataSort->roles); ?></div>
	<table cellspacing="2" cellpadding="2" border="0" width="100%" class="grid_view">
		<tr>
			<th style="width:25px"><input type="checkbox" id="chkAll" name="chkAll" value="1" onclick="htmlCtl.ToggleCheckboxes('chkAll','multi_checkbox');" /></th>
			<th>&nbsp;</th>
			<th><?php echo HtmlControls::GenerateSortableColumn($dataView->dataSort->roles, 'name', $trans['general.sort_by'].$trans['general.name'], $trans['general.name']); ?></th>
			<th>Descriere</th>
			<th><?php echo HtmlControls::GenerateSortableColumn($dataView->dataSort->roles, 'status', $trans['general.sort_by'].$trans['roles.status'], $trans['roles.status']); ?></th>
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
			<td><?php echo $row->status_display?></td>
			<td class="grid_options">
				<?php echo HtmlControls::GenerateAdminEditLink('roles', $row->id)?>&nbsp;&nbsp;
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
	<?php echo HtmlControls::GenerateGridButtons('roles', $trans['roles.new_item'], $trans['roles.delete_selected_items'])?>
</div>