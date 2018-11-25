<?php if (!isset($webpage)) die('Direct access not allowed'); 
	if ($dataView->users != null) 
	{
?>
	<div class="paging_holder"><?php echo $dataView->PagingHtml;?></div>
	<div><?php echo HtmlControls::GenerateSortHidColumn($dataView->dataSort->users); ?></div>
	<div class="grid_wrapper">
		<table cellspacing="0" cellpadding="0" border="0" width="100%" class="grid_view">
			<tr>
				<th style="width:30px"><input type="checkbox" id="chkAll" name="chkAll" value="1" onclick="htmlCtl.ToggleCheckboxes('chkAll','multi_checkbox');" /></th>
				<th><?php echo HtmlControls::GenerateSortableColumn($dataView->dataSort->users, 'username', 'Sorteaza dupa Nume', 'Username'); ?></th>
				<th><?php echo HtmlControls::GenerateSortableColumn($dataView->dataSort->users, 'role', 'Sorteaza dupa Rol', 'Rol'); ?></th>
				<th><?php echo HtmlControls::GenerateSortableColumn($dataView->dataSort->users, 'email', 'Sorteaza dupa Email', 'Email'); ?></th>
				<th><?php echo HtmlControls::GenerateSortableColumn($dataView->dataSort->users, 'lastname', 'Sorteaza dupa Nume', 'Nume'); ?></th>
				<th><?php echo HtmlControls::GenerateSortableColumn($dataView->dataSort->users, 'firstname', 'Sorteaza dupa Prenume', 'Prenume'); ?></th>
				<th  class="grid_options" style="width:80px">Options</th>
			</tr>
			<?php
			$rowIndex = $dataView->rowIndex;
			foreach ($dataView->users as $row ) 
			{ 
				$rowIndex++;
			?>
			<tr>
				<td><input type="checkbox" name="multipleIds[]" value="<?php echo $row->id;?>" class="multi_checkbox" /></td>
				<td><?php echo $row->username?></td>
				<td><?php echo $row->role_name?></td>
				<td><?php echo $row->email?></td>
				<td><?php echo $row->first_name?></td>
				<td><?php echo $row->last_name?></td>
				<td class="grid_options">
					<?php echo HtmlControls::GenerateAdminLink('#', '<i class="fa fa-user fa-lg"></i>', $trans['users.impersonate'], 'class="impersonate-user" data-id="'.$row->id.'"')?>
					<?php echo HtmlControls::GenerateAdminEditLink('users', $row->id, $trans['users.edit_item'])?>
					<?php echo HtmlControls::GenerateAdminDeleteLink($row->id, $trans['users.delete_item'])?>
				</td>
			</tr>
			<?php 
			}
			?>
		</table>
	</div>
<?php 
	}
	else
	{
?>
<div class="system_message info"><span><?php echo $trans['users.no_elements']?></span></div>
<?php
	}
?>

<div class="grid_buttons"><?php echo HtmlControls::GenerateGridButtons('users', $trans['users.new_item'], $trans['users.delete_selected_items'])?></div>