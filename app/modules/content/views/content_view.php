<?php if (!isset($webpage)) die('Direct access not allowed'); ?>
<div id="divErrors" class="system_message error"></div>
<?php //echo '<pre>'; print_r($dataView);die(); ?>
<strong><?php echo $trans['app_images.app_categories']?>:</strong><br/>
<select id="categoryId" name="categoryId" class="form-control" style="width:200px">
    <?php echo $dataView->categoriesContentListDropDown?>
</select>
<?php include(_APPLICATION_FOLDER.'blocks/language_list.php'); ?>

<div class="edit_wrapper">
    <table class="edit_table">
        <tr>
            <td><?php echo $trans['categories.content']?>: </td>
            <td>
                <textarea name="html"  id="html" class="tinymce" rows="20" cols="60"><?php echo $dataView->categoryContent->html?></textarea>
                <input type="hidden" name="categoryName" id="categoryName" value="<?php echo $dataView->category->name?>" class="form-control" />
                <input type="hidden" name="categoryUrlKey" id="categoryUrlKey" value="<?php echo $dataView->category->url_key?>" class="form-control" />
            </td>
        </tr>
        <!--<tr>
            <td><?php /*echo $trans['categories.image']*/?>: <span class="required_field">*</span> <br/></td>
            <td>
                <?php /*if (empty($dataView->category->file)) { */?>
                    <input type="file" name="fileUpload" id="fileUpload" value="" class="form-control" />
                <?php /*} else
                {
                    */?>
                    <img src="<?php /*echo $dataView->category->file*/?>" alt="" />
                    <a href="javascript:;" onclick="frm.FormSubmitAction('DeleteFile')"><?php /*echo $trans['general.delete']*/?></a>
                <?php /*} */?>
            </td>
        </tr>-->
    </table>
</div>
<div class="grid_buttons"><?php echo HtmlControls::GenerateFormButtons($trans['general.save'], "frm.FormSubmitAction('Save')")?></div>
