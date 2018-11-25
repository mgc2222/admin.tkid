<div id="outer">
	<div class="jcExample">
		<div class="article">
			<!-- This is the image we're attaching Jcrop to -->
			<img src="<?php echo $dataView->image;?>?id=<?php echo time();?>" id="cropbox" />
			<!-- This is the form that our event handler fills -->
			<div style="margin-top:5px;">
				<input type="hidden" id="x" name="x"/>
				<input type="hidden" id="y" name="y"/>
				<input type="hidden" id="x2" name="x2"/>
				<input type="hidden" id="y2" name="y2"/>
				<input type="hidden" id="w" name="w"/>
				<input type="hidden" id="h" name="h"/>
				<button type="submit" class="btn btn-success"><i class="fa fa-fw fa-crop"></i> Crop Imagine</button>
			</div>
		</div>
	</div>
</div>
