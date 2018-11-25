<?php if (!isset($webpage)) die('Direct access not allowed');  ?>
<div class="fl">
	<strong><?php echo $trans['users_permissions.select_user']?>:</strong><br/><br/>
	<select id="ddlUser" name="ddlUser">
		<option value=""><?php echo $trans['users_permissions.select_user_option']?></option>
		<?php echo $dataView->usersListContent?>
	</select>
</div>
<div class="permissions_list">
	<strong><?php echo $trans['users_permissions.select_permissions']?>:</strong><br/><br/>
	<input type="checkbox" id="chkAll" /><label for="chkAll"><?php echo $trans['users_permissions.check_all']?></label><br/>
	<?php echo $dataView->permissionsListContent?>
</div>
<div class="clr"></div>
<div class="grid_buttons">
	<?php echo HtmlControls::GenerateFormButtons($trans['users_permissions.save'], 'frm.FormSaveData()');?>
</div>