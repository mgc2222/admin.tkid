function CompanyEdit()
{
	this.Init = function()
	{
		$(".info").colorbox({inline:true, width:"400px"});
		$(".schimba-parola").colorbox({inline:true, width:"290px", height: "400px"});
		$(".schimba-logo").colorbox({inline:true, width:"290px", height: "260px"});
		
		$('#btnSave').click(function() {
			frm.FormSaveData();
		});
		
		$('#btnChangePassword').click(function() {
			$('.password-changed-message').removeClass('success error');
			
			var crtPassword = $('#txtCurrentPassword').val();
			var newPassword = $('#txtNewPassword').val();
			var repeatPassword = $('#txtConfirmPassword').val();
			
			var errorMessage = '';
			
			if (crtPassword == '')
				errorMessage = 'Specificati parola curenta';
				
			if (newPassword == '')
			{
				if (errorMessage != '') errorMessage += '<br/>';
				errorMessage += 'Specificati noua parola';
			}
				
			if (newPassword != repeatPassword)
			{
				if (errorMessage != '') errorMessage += '<br/>';
				errorMessage = 'Noua parola nu se potriveste cu parola repetata';
			}
			
			if (errorMessage != '')
			{
				$('.password-changed-message').addClass('error');
				$('.password-changed-message').html(errorMessage);
				$('.password-changed-message').show();
				return;
			}
			
			$('.password-changed-message').html('');
			
			$.post('actions.php', { ajax_action: 'change_password', crtPassword: crtPassword, newPassword: newPassword, repeatPassword:repeatPassword },
				function(dataJson) {
					var data = jQuery.parseJSON(dataJson);
					$('.password-changed-message').addClass(data.status);
					$('.password-changed-message').html(data.message);
					$('.password-changed-message').show();
					if (data.status == 'success')
					{
						$('#txtCurrentPassword').val('');
						$('#txtNewPassword').val('');
						$('#txtConfirmPassword').val('');
						setTimeout(function() { $('.password-changed-message').hide(); $('.schimba-parola').colorbox.close(); }, 3000);
					}
				});
		});
	}
}

var objCompanyEdit = new CompanyEdit();
objCompanyEdit.Init();