<?php
if (!isset($webpage)) die('Direct access not allowed');
if ($dataView->rows != null)
{
?>
<div class="grid_wrapper" id="sort_holder">
	<?php //include($dataView->categoriesBlock); ?>
</div>
<?php 
}

?>

<?php
include(_APPLICATION_FOLDER.'blocks/calendar.php');
?>

