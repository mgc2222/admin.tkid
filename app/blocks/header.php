<div class="header">
	<div class="logo"><i class="fa fa-briefcase fa-3x"></i><a href="<?php echo _SITE_RELATIVE_URL?>"><?php echo $webpage->HeaderTitle?></a></div>
	<ul class="nav navbar-top-links navbar-right">
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
				<i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
			</a>
			<ul class="dropdown-menu dropdown-user">
				<li><a href="<?php echo _SITE_RELATIVE_URL?>user_settings"><i class="fa fa-gear fa-fw"></i> <?php echo $trans['header.settings']?></a></li>
				<li class="divider"></li>
				<li><a href="<?php echo _SITE_RELATIVE_URL?>logout"><i class="fa fa-sign-out fa-fw"></i> <?php echo $trans['header.logout']?></a></li>
			</ul>
			<!-- /.dropdown-user -->
		</li>
		<!-- /.dropdown -->
	</ul>
</div>
