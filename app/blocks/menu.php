<nav class="navbar-default navbar-static-side" role="navigation">
	<div class="sidebar-collapse">
		<ul class="nav" id="side-menu">
			<?php if (isset($webpage->SearchBlock)) include ($webpage->SearchBlock); ?>
			<?php if ($auth->IsAuth()) { echo $menu->RenderContent($auth->userPermissions, $auth->Role); }?>
		</ul>
		<!-- /#side-menu -->
	</div>
	<!-- /.sidebar-collapse -->
</nav>