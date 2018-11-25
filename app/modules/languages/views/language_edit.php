<?php if (!isset($webpage)) die('Direct access not allowed'); ?>
<div class="page_content">
	<div class="edit_wrapper">
	<table cellpadding="0" cellspacing="0" class="edit_table">
		<tr>
			<td><label for="txtName"><?php echo $trans['general.name']?></label></td>
			<td><input type="text" name="txtName" id="txtName" class="form-control" data-bind="value: name" /></td>
		</tr>
		<tr>
			<td><label for="txtName"><?php echo $trans['languages.abbreviation']?></label></td>
			<td><input type="text" name="txtAbbreviation" id="txtAbbreviation" class="form-control" data-bind="value: abbreviation" /></td>
		</tr>
		<tr>
			<td><label for="chkDefaultLanguage"><?php echo $trans['languages.default_language']?></label></td>
			<td><input type="checkbox" name="chkDefaultLanguage" id="chkDefaultLanguage" data-bind="checked: defaultLanguage" /></td>
		</tr>
		<tr>
			<td><label for="chkIsTranslated"><?php echo $trans['languages.has_translation']?></label></td>
			<td><input type="checkbox" name="chkIsTranslated" id="chkIsTranslated" data-bind="checked: isTranslated" /></td>
		</tr>
		<tr>
			<td></td>
			<td>
				<a data-bind="click: save" class="btn-save"><span class="btn btn-success"><i class="fa fa-fw fa-hand-o-right"></i><?php echo $trans['general.save']?></span></a>
				<?php // echo HtmlControls::GenerateFormButtons($trans['general.save'], 'frm.FormSaveData()', $webpage->PageReturnUrl, $trans['languages.items_list']) ?>
				<a href="<?php echo _SITE_RELATIVE_URL?>languages" class="btn btn-default margin_left15"><i class="fa fa-fw fa-list"></i><?php echo $trans['languages.items_list']?></a>
				<span data-bind="visible: isEdit">
					<?php echo HtmlControls::GenerateNewItemButton('languages', $trans['languages.new_item']); ?>
				</span>
			</td>
		</tr>
	</table>
	</div>
</div>
<?php echo $dataView->modelJson ?>