<?php if (!isset($webpage)) die('Direct access not allowed'); ?>
<?php if ($dataView->rows != null) { ?>

<div class="grid_wrapper">
<table cellspacing="0" cellpadding="0" border="0" width="100%" class="grid_view" id="sortable">
	<thead>
	<tr>
		<th style="width:30px"><input type="checkbox" id="chkAll" name="chkAll" value="1" onclick="htmlCtl.ToggleCheckboxes('chkAll','multi_checkbox');" /></th>
		<th>Nr.</th>
		<th>Limba</th>
		<th>Abreviere</th>
		<th>Default</th>
		<th style="width:100px">Options</th>
	</tr>
	</thead>
	<tbody>
<?php
	$rowIndex = 0;
	foreach ($dataView->rows as &$row ) 
	{ 
		$rowIndex++;
?>
	<tr>
		<td><input type="checkbox" name="multipleIds[]" value="<?php echo $row->id;?>" class="multi_checkbox" />
			<input type="hidden" id="hidSortPK_<?php echo $rowIndex;?>" value="<?php echo $row->id;?>" />
		</td>
		<td><?php echo $rowIndex?></td>
		<td><?php echo $row->name?></td>
		<td><?php echo $row->abbreviation?></td>
		<td><?php echo $row->is_default?></td>
		<td class="grid_options">
			<?php echo HtmlControls::GenerateAdminEditLink('languages', $row->id)?>&nbsp;&nbsp;
			<?php echo HtmlControls::GenerateAdminDeleteLink($row->id)?>
		</td>
	</tr>
<?php 
	}
?>
	</tbody>
</table>
</div>
<?php 	} ?>
<div class="grid_buttons"><?php echo HtmlControls::GenerateGridButtons('languages', $trans['languages.new_item'], $trans['languages.delete_selected_items'])?></div>
