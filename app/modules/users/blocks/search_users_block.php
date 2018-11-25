<li class="sidebar-search">
	<form id="frmSearch" method="post" action="">
	<div class="input-group custom-search-form">
		<input type="text" class="form-control" placeholder="<?php echo $trans['search_master_users.search']?>" id="txtSearch" name="txtSearch" />
		<input type="hidden" id="actionSearch" name="actionSearch" value="1" />
		<span class="input-group-btn">
			<button class="btn btn-default" type="submit">
				<i class="fa fa-search"></i>
			</button>
		</span>
	</div>
	<div class="mt5">
		<select class="form-control" placeholder="<?php echo $trans['search_master_users.hotel']?>" id="ddlSideSearchHotel" name="ddlSideSearchHotel">
		</select>
	</div>
	</form>
	<!-- /input-group -->
</li>