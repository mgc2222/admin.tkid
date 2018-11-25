<?php if (!isset($webpage)) die('Direct access not allowed'); ?>
<div class="edit_wrapper">
<input type="password" style="display:none" /> <!-- hack for chrome to no longer autocomplete password -->
<table cellpadding="0" cellspacing="0" class="edit_table">
<tr>
	<td><?php echo $trans['users.username']?>: <span class="required_field">*</span></td>
	<td><input type="text" name="txtUsername" id="txtUsername" class="form-control" value="<?php echo $dataView->txtUsername?>" /></td>
</tr>
<tr>
	<td><?php echo $trans['users.role']?>: <span class="required_field">*</span></td>
	<td><select name="ddlRoleId" id="ddlRoleId" class="form-control">
		<?php echo $dataView->rolesList?>
		</select>
	</td>
</tr>
<tr>
	<td><?php echo $trans['users.email']?>:<span class="required_field">*</span></td>
	<td><input type="text" name="txtEmail" id="txtEmail" class="form-control" value="<?php echo $dataView->txtEmail?>" /></td>
</tr>
<tr>
	<td><?php echo $trans['users.password']?>: <span class="required_field">*</span> </td>
	<td><input type="password" name="txtPassword" id="txtPassword" class="form-control" value="" autocomplete="off" /></td>
</tr>
<tr>
	<td><?php echo $trans['users.repeat_password']?>: <span class="required_field">*</span> </td>
	<td><input type="password" name="txtPasswordRepeat" id="txtPasswordRepeat" class="form-control" value="" /></td>
</tr>
<tr>
	<td><?php echo $trans['users.first_name']?>:</td>
	<td><input type="text" name="txtFirstName" id="txtFirstName" class="form-control" value="<?php echo $dataView->txtFirstName?>" /></td>
</tr>
<tr>
	<td><?php echo $trans['users.last_name']?>:</td>
	<td><input type="text" name="txtLastName" id="txtLastName" class="form-control" value="<?php echo $dataView->txtLastName?>" /></td>
</tr>
<tr>
	<td><?php echo $trans['users.limit_ip']?>:</td>
	<td><input type="text" name="txtIpAddress" id="txtIpAddress" class="form-control" value="<?php echo $dataView->txtIpAddress?>" /></td>
</tr>
<tr>
	<td><?php echo $trans['users.active']?>:</td>
	<td><input type="checkbox" name="chkStatus" id="chkStatus" <?php echo $dataView->chkStatus?> /></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>
		<?php echo HtmlControls::GenerateFormButtons($trans['general.save'], 'frm.FormSaveData()', $webpage->PageReturnUrl, $trans['users.items_list'])?>
		<?php if ($dataView->EditId != 0) { echo HtmlControls::GenerateNewItemButton('users', $trans['users.new_item']); } ?> 
	</td>
</tr>
</table>
</div>