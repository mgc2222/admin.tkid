<div class="footer">
	<div class="footer_top">&copy;<?php echo date('Y');?> <?php echo $trans['general.all_rights_reserved']?></div>
<?php if ($auth->isAuthenticated && false) { ?>
	<div class="footer_menu">
		<span class="menu_item_footer"><a href="home.php" class="menu_link">Home</a></span>
	</div>
<?php } ?>	
</div>
