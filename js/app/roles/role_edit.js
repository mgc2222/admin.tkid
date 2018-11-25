function RoleEdit()
{
	this.Init = function()
	{
		var rules = { txtName: { required:true, 
			remote:{  	url: SITE_RELATIVE_URL  + 'ajax',
						type: 'post',
						data: {
							ajax_action: 'verify_role',
							name: function() { return $( "#txtName" ).val(); },
							editId: function() { return frm.EditId(); }
						}
					}
				} 
			};
		var messages = { txtName: { required: 'Specificati numele rolului', remote: 'Acest rol exista deja' } };
				
		var vW = new ValidatorWrapper();
		vW.InitValidator('mainForm', rules, messages);
	}
}
var ctlRoleEdit = new RoleEdit();
ctlRoleEdit.Init();
