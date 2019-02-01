<?php if (!isset($webpage)) die('Direct access not allowed'); ?>
<div id="divErrors" class="system_message error"></div>
<?php //echo '<pre>'; print_r($dataView);die(); ?>
<strong><?php echo $trans['app_images.app_categories']?>:</strong><br/><br/>
<select id="categoryId" name="categoryId" class="form-control" style="width:200px">
    <?php echo $dataView->contentListContent?>
</select>
<div class="edit_wrapper">
    <table class="edit_table">
        <tr>
            <td><?php echo $trans['categories.description']?>: </td>
            <td><textarea name="txtDescription"  id="txtDescription" class="tinymce" rows="20" cols="60"><?php echo $dataView->txtDescription?></textarea></td>
        </tr>
        <tr>
            <td><?php echo $trans['categories.short_description']?>: </td>
            <td><textarea name="txtShortDescription"  id="txtShortDescription" class="tinymce" rows="10" cols="60"><?php echo $dataView->txtShortDescription?></textarea></td>
        </tr>
        <tr>
            <td><?php echo $trans['categories.image']?>: <span class="required_field">*</span> <br/></td>
            <td>
                <?php if ($dataView->txtFile == '') { ?>
                    <input type="file" name="fileUpload" id="fileUpload" value="" class="form-control" />
                <?php } else
                {
                    ?>
                    <img src="<?php echo $dataView->txtFile?>" alt="" />
                    <a href="javascript:;" onclick="frm.FormSubmitAction('DeleteFile')"><?php echo $trans['general.delete']?></a>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td><?php echo $trans['categories.order']?>: </td>
            <td><input type="number" name="txtOrderIndex" id="txtOrderIndex"  class="" value="<?php echo $dataView->txtOrderIndex?>"  /></td>
        </tr>
    </table>
</div>
<div class="grid_buttons"><?php echo HtmlControls::GenerateFormButtons($trans['general.save'], 'frm.FormSaveData()', $webpage->PageReturnUrl, $trans['categories.items_list'])?></div>
<script type="text/javascript">
    <?php
    if(isset($dataView->categoryId)){
    ?>
    var elementId = <?php echo $dataView->categoryId ?>;
    var elementName = 'categoryId';
    <?php
        }
        ?>;
</script>