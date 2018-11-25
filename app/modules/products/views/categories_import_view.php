<?php
if (!isset($webpage)) die('Direct access not allowed');
if ($dataView->rows != null)
{
?>
<div class="grid_wrapper" id="sort_holder">
	<?php include($dataView->categoriesImportBlock); ?>
</div>
<?php 
} 
?>
<div class="grid_buttons"><?php echo HtmlControls::GenerateGridButtons('category_import_edit/pid='.$dataView->categoryId, $trans['categories_import.new_item'], $trans['categories_import.delete_selected_items'])?></div>
