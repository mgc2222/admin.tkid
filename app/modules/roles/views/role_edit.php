<?php if (!isset($webpage)) die('Direct access not allowed'); ?>
<div id="divErrors" class="system_message error"></div>

<div class="edit_wrapper">
	<table cellpadding="0" cellspacing="0" border="0" class="edit_table">
	<tr>
		<td><?php echo $trans['roles.name']?>: </td>
		<td><input type="text" name="txtName" id="txtName" class="form-control" value="<?php echo $dataView->txtName?>" /></td>
	</tr>
	<tr>
		<td><?php echo $trans['roles.description']?>: </td>
		<td><input type="text" name="txtDescription" id="txtDescription" class="form-control" value="<?php echo $dataView->txtDescription?>" /></td>
	</tr>
	<tr>
		<td><?php echo $trans['roles.status']?>: </td>
		<td><input type="checkbox" name="chkStatus" id="chkStatus"  class="" value="1" <?php echo $dataView->chkStatus?> /></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<?php echo HtmlControls::GenerateFormButtons($trans['general.save'], 'frm.FormSaveData()', $webpage->PageReturnUrl, $trans['roles.items_list'])?>
			<?php if ($dataView->EditId != 0) { echo HtmlControls::GenerateNewItemButton('roles', $trans['roles.new_item']); } ?> 
		</td>
	</tr>
	</table>
</div>