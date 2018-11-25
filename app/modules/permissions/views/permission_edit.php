<?php if (!isset($webpage)) die('Direct access not allowed'); ?>
<div id="divErrors" class="system_message error"></div>

<div class="edit_wrapper">
	<table cellpadding="0" cellspacing="0" border="0" class="edit_table" style="width:80%">
	<tr>
		<td style="width:140px">
			<label for="txtName"><?php echo $trans['general.name']?>:</label>
		</td>
		<td>
			<input type="text" class="form-control valid" name="txtName" id="txtName"  value="<?php echo $dataView->txtName?>" />
		</td>
	</tr>
	<tr>
		<td>
			<label for="txtDescription"><?php echo $trans['general.description']?>:</label>
		</td>
		<td>
			<input type="text" class="form-control valid" name="txtDescription" id="txtDescription"  value="<?php echo $dataView->txtDescription?>" />
		</td>
	</tr>
	<tr>
		<td>
			<label for="txtPageId"><?php echo $trans['permissions.page_id']?>: </label>
		</td>
		<td><input type="text" name="txtPageId" id="txtPageId" class="form-control" value="<?php echo $dataView->txtPageId?>" /></td>
	</tr>
	</table>
</div>
<div class="grid_buttons">
<tr>
	<?php echo HtmlControls::GenerateFormButtons($trans['general.save'], 'frm.FormSaveData()', $webpage->PageReturnUrl, $trans['permissions.items_list'])?>
	<?php if ($dataView->EditId != 0) { echo HtmlControls::GenerateNewItemButton('permissions', $trans['permissions.new_item']); } ?> 
</div>