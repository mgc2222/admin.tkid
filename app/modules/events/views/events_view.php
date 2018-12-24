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
include(_APPLICATION_FOLDER.'blocks/calendar.php');
?>
<div class="grid_buttons">
    <a role="button" data-toggle="modal" data-target="#new-events-modal" class="calendar-event-toggle-modal">
        <span class="btn btn-success"><i class="fa fa-fw fa-edit"></i> <?php echo $trans['events.new_item']?></span>
    </a>
<?php echo HtmlControls::GenerateDeleteSelected($trans['events.delete_selected_items']);?>
</div>
