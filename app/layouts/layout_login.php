<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<?php include(_APPLICATION_FOLDER.'blocks/html_head_block.php'); ?>
<body>
<?php include(_APPLICATION_FOLDER.'blocks/header.php')?>
<div class="page_wrapper">	
	<div class="content_wrapper">
		<h1 class="page_title"><?php echo $webpage->PageHeadTitle?> <?php if (isset($webpage->SearchBlock)) { include($webpage->SearchBlock); } ?> </h1>
		<form id="mainForm" method="post" action="" <?php echo $webpage->FormAttributes?>>
		<?php if ($webpage->Message!='') { ?><div class="system_message <?php echo $webpage->MessageCss?>"><span><?php echo $webpage->Message?></span></div><?php } ?>
		<?php if ($webpage->ContentInclude != null)
		{
			foreach ($webpage->ContentInclude as $contentInclude)
			{
				include($contentInclude);
			}
		}
		echo $webpage->FormHtml;
		?>
		</form>
	</div>
</div>
<?php include(_APPLICATION_FOLDER.'blocks/footer.php')?>
<?php include(_APPLICATION_FOLDER.'blocks/html_footer_scripts.php'); ?>
</body>
</html>