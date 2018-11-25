<div id="outer" class="image-tooltip-holder">	

	<ul id="holderSortable" class="images_grid">
	<?php 
	if ($dataView->rows != null)
	{
		$itemIndex = 1;
		foreach ($dataView->rows as &$row)
		{
	?>
		<li class="ui-state-default"><img src="<?php echo $row->thumb.'?id='.time();?>" data-image="<?php echo $row->thumb_med?>" class="image-tooltip-target" />
			<a href="javascript:;" class="delete" data="<?php echo $row->id;?>">Sterge</a> | 
			<a style="margin-top:10px" target="_blank" href="crop/id=<?php echo $row->id;?>"><strong>Crop</strong></a>
			<input type="hidden" id="hidImageId_<?php echo $row->id?>" value="<?php echo $row->id?>" />
			<div style="margin-top: 5px;" onclick="">
				<?php echo HtmlControls::GenerateRadioList($row->radioData)?>
					
			</div>
		</li>
	<?php 
			$itemIndex++;

		}
	?>
	</ul>

	<div class="clr"></div>
	<div style="margin:20px 0;">
		<a href="javascript:;" class="delete_all">Sterge toate pozele de la acest produs</a>
	</div>
	<?php
	}
	else 
	{
	?>
		<div class="err" width="100%" align="center">Nu sunt poze upload-ate pentru acesta categorie</div>
	<?php } ?>
<div style="width:750px;">
	<div class="box" style="margin:10px 0;">
		<span class="box_title">Upload poze (selectare multipla de poze)</span>
		
		<div class="plupload_filelist_header"><div class="plupload_file_name">Poza</div><div class="plupload_file_action">&nbsp;</div><div class="plupload_file_status"><span>Status</span></div><div class="plupload_file_size">Dimensiune</div><div class="plupload_clearer">&nbsp;</div></div>
		
		<div id="container">
			<div id="filelist"></div>
			<br />
			<a id="pickfiles" href="javascript:;">Selecteaza fisierele</a>&nbsp;&nbsp;&nbsp;
			<a id="uploadfiles" href="javascript:;" >Uploadeaza</a>
		</div>
	</div>
</div>
<div class="clr"></div>

<script type="text/javascript">
	<?php 
	if(isset($dataView->productId)){
	?>
		var elementId = <?php echo $dataView->productId ?>;
		var elementName = 'productId';
	<?php
	}	
	?>;
</script>

</div>