<?php if (!isset($webpage)) die('Direct access not allowed'); ?>
<div id="divErrors" class="system_message error"></div>
<?php //echo '<pre>'; print_r($dataView);die(); ?>
<div class="edit_wrapper">
	<ul id="holderSortable" class="images_grid">
		<?php 
		if ($dataView->row != null)
		{
		?>
			<li class="ui-state-default"><img src="<?php echo $dataView->row->thumb.'?id='.time(); ?>" data-image="<?php echo $dataView->row->thumb_med; ?>" class="image-tooltip-target" />
				
			</li>
		<?php 
		}
		?>
	</ul>
	<table cellpadding="0" cellspacing="0" border="0" class="edit_table">
	<!-- <tr>
		<td><label for="ddlProducerId"><?php echo $trans['products.producer']?></td>
		<td><select class="form-control" id="ddlProducerId" name="ddlProducerId"><?php echo $dataView->producerList;?></select></td>
	</tr>
	-->
    <tr>
        <td><label for="txtTitle"><?php echo $trans['app_image.title']?></td>
        <td><input type="text" class="form-control" id="txtTitle" name="txtTitle" value="<?php echo $dataView->txtTitle; ?>" /></td>
        <td><input type="hidden" class="form-control" id="txtAlt" name="txtAlt" value="<?php echo $dataView->txtAlt; ?>" /></td>
    </tr>
    <!--<tr>
        <td><label for="txtAlt"><?php /*echo $trans['app_image.alt']*/?></td>
        <td><input type="text" class="form-control" id="txtAlt" name="txtAlt" value="<?php /*echo $dataView->txtAlt; */?>" /></td>
    </tr>-->
<?php if($dataView->appCategoryName =='slider')
        {
?>
            <!--<tr>
                <td><label for="txtCaption"><?php /*echo $trans['app_image.caption']*/?></td>
                <td><input type="text" class="form-control" id="txtCaption" name="txtCaption" value="<?php /*echo $dataView->txtCaption; */?>" /></td>
            </tr>-->
            <tr>
                <td><label for="txtDescription"><?php echo $trans['app_image.description']?></td>
                <td><textarea class="form-control" id="txtDescription" name="txtDescription"><?php echo $dataView->txtDescription?></textarea></td>
            </tr>

            <!--<tr>
                <td><label for="txtButtonText"><?php /*echo $trans['app_image.button_link_text']*/?></td>
                <td><input type="text" class="form-control" id="txtButtonText" name="txtButtonText" value="<?php /*echo $dataView->txtButtonText; */?>" /></td>
            </tr>
            <tr>
                <td><label for="txtButtonHref"><?php /*echo $trans['app_image.button_link_hfer']*/?></td>
                <td><input type="text" class="form-control" id="txtButtonHref" name="txtButtonHref" value="<?php /*echo $dataView->txtButtonHref; */?>" /></td>
            </tr>-->
<?php   } ?>
	<tr>
		<td><label for="txtOrder"><?php echo $trans['app_image.order_index']?></td>
		<td><input type="text" class="form-control" id="txtOrder" name="txtOrder" value="<?php echo $dataView->txtOrder; ?>" /></td>
	</tr>
        <input type="hidden" id="appCategoryId" name="appCategoryId" value="<?php echo $dataView->row->app_category_id; ?>" />
	</table>
</div>
<div class="grid_buttons">
<tr>
	<?php echo HtmlControls::GenerateFormButtons($trans['general.save'], 'frm.FormSaveData()')?>
	<?php if ($dataView->row->id != 0) { 
		//echo HtmlControls::GenerateFormButton($trans['products.images'], '', _SITE_RELATIVE_URL.'pictures/id='.$dataView->row->id); 
		//echo HtmlControls::GenerateNewItemButton('products', $trans['products.new_item']);
	} ?> 
</div>