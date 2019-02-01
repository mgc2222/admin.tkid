<?php if (!isset($webpage)) die('Direct access not allowed');  ?>
<?php
	if ($dataView->rows != null) 
	{ 
?>
<div class="paging_holder"><?php echo $dataView->PagingHtml?></div>
<div class="grid_wrapper">
	<div><?php echo HtmlControls::GenerateSortHidColumn($dataView->dataSort->products); ?></div>
	<table cellspacing="2" cellpadding="2" border="0" width="100%" class="grid_view">
		<tr>
			<th style="width:25px"><input type="checkbox" id="chkAll" name="chkAll" value="1" onclick="htmlCtl.ToggleCheckboxes('chkAll','multi_checkbox');" /></th>
			<th>&nbsp;</th>
			<th><?php echo HtmlControls::GenerateSortableColumn($dataView->dataSort->products, 'name', $trans['general.sort_by'].$trans['general.name'], $trans['general.name']); ?></th>
			<!-- <th><?php //echo $trans['products.model']?></th> -->
			<!-- <th><?php //echo $trans['products.product_code']?></th> -->
			<th><?php echo $trans['products.categories']?></th>
			<th><?php echo $trans['products.description']?></th>
			<th><?php echo $trans['products.price']?></th>
			<th><?php echo $trans['products.price_before']?></th>
			<th><?php echo $trans['products.status']?></th>
		 	<th><?php echo $trans['products.amount']?></th>
		 	<th><?php echo $trans['products.amount_unit']?></th>
			<th style="width:150px"><?php echo $trans['general.actions']?></th>
		</tr>
<?php
		$rowIndex = $dataView->rowIndex;
		foreach ($dataView->rows as &$row)
		{
			$rowIndex++;
?>
		<tr>
			<td><input type="checkbox" name="multipleIds[]" value="<?php echo $row->id;?>" class="multi_checkbox" /></td>
			<td><?php echo $rowIndex?>.</td>
			<td><?php echo $row->name?></td>
			<!-- <td><?php //echo $row->model?></td>
			<td><?php //echo $row->product_code?></td> -->
			<td><?php echo $row->categories?></td>
			<td><?php echo $row->description?></td>
			<td><?php echo $row->price?></td>
			<td><?php echo $row->price_before?></td>
			<td><?php echo $row->statusEnum?></td>
			<td><?php echo $row->amount?></td>
			<td><?php echo $row->amount_unit?></td>
			<td class="grid_options">
				<?php echo HtmlControls::GenerateAdminEditLink('products', 'id='.$row->id)?>&nbsp;&nbsp;
				<?php echo HtmlControls::GenerateAdminLink(_SITE_RELATIVE_URL.'pictures/id='.$row->id, '<i class="fa fa-image"></i>', $trans['products.images'])?>&nbsp;&nbsp;
				<?php echo HtmlControls::GenerateAdminDeleteLink($row->id)?>
			</td>
		</tr>
<?php
		}
?>
	</table>
</div>	
<?php 
	} 
?>
<div class="grid_buttons">
	<?php echo HtmlControls::GenerateGridNewButtons('products', $dataView->editCategory, $trans['products.new_item'], $trans['products.delete_selected_items'])?>
</div>