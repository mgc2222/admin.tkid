function PermissionEdit()
{
	this.Init = function()
	{
		var rules = { txtName: { required:true}, 
			txtPageId: { required:true,
				remote:{  	url: SITE_RELATIVE_URL  + 'ajax',
						type: 'post',
						data: {
							ajax_action: 'verify_permission',
							pageId: function() { return $( "#txtPageId" ).val(); },
							editId: function() { return frm.EditId(); }
						}
					}
				} 
			};
		var messages = { txtName: 'Specificati numele permisiunii', txtPageId : { required: 'Specificati pagina permisiunii', remote: 'Aceasta pagina exista deja' } };
				
		var vW = new ValidatorWrapper();
		vW.InitValidator('mainForm', rules, messages);
	}
}
var ctlPermissionEdit = new PermissionEdit();
ctlPermissionEdit.Init();
