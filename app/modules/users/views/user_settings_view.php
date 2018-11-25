<?php if (!isset($webpage)) die('Direct access not allowed'); ?>
<div id="divErrors" class="system_message error">
	<span id="txtEmail_required">Introduceti email valid</span>
	<span id="txtCurrentPassword_required">Introduceti parola actuala</span>
	<span id="txtPassword_required">Introduceti noua parola</span>
	<span id="txtPasswordRepeat_required">Repetati noua parola</span>
	<span id="txtPassword_match">Noua Parola nu este la fel cu cea repetata</span>
</div>
<div class="edit_wrapper">
<table cellpadding="0" cellspacing="0" border="0" class="edit_table">
<tr>
	<td><?php echo $trans['users.email']?>:</td>
	<td><?php echo $dataView->txtEmail?></td>
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
	<td><?php echo $trans['users.current_password']?>:</td>
	<td><input type="password" name="txtCurrentPassword" id="txtCurrentPassword" class="form-control" value="" /></td>
</tr>
<tr>
	<td><?php echo $trans['users.password']?>:</td>
	<td><input type="password" name="txtPassword" id="txtPassword" class="form-control" value="" /></td>
</tr>
<tr>
	<td><?php echo $trans['users.repeat_password']?>:</td>
	<td><input type="password" name="txtPasswordRepeat" id="txtPasswordRepeat" class="form-control" value="" /></td>
</tr>
</table>
</div>
<div class="grid_buttons">
	<?php echo HtmlControls::GenerateFormButtons('Salveaza', 'frm.FormSaveData()')?>
</div>