<!-- Modal -->
<div class="modal fade" id="viewMessageModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="viewModalLabel"><?php echo $trans['messages.view_message_title']?></h4>
			</div>
			<div class="modal-body">
				<div id="subjectHolder"></div>
				<br/>
				<div id="messageHolder"></div>				
			</div>
			<div class="modal-footer">
				<div id="attachmentsHolder" class="message-attachments"></div>
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $trans['general.close']?></button>
				<!-- <button type="button" class="btn btn-primary" id="btnReplyMessage"><?php echo $trans['messages.send_message']?></button> -->
			</div>
		</div>
	</div>
</div>