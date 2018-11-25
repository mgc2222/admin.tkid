function FileUpload(options)
{
	var _errorList = [];
	var _options = getDefaultOptions();
	_options = $.extend({}, _options, options);
	
	var _files = [];
	
	this.Errors = function() { return _errorList; }
	this.ClearErrors = function() { _errorList = []; }
	this.Files = function() { return _files; }
	this.AddFile = function(file) { addFileItem(file); }
	
	this.ClearFiles = function() {
		_errorList = [];
		_files = [];
		$(_options.filesList).html('');
	}
	
	function output(msg) {
		$("#messages").append(msg)
	}
	
	function getDefaultOptions()
	{
		// dropZone is the element on which can be dropped
		// dropTarget is the element that appears when hovering the dropZone
		return { fileInput: '#fileInput', filesList: '#filesList', templateItem: getTemplateItem(), dropZone: '#filedrag', dropTarget: '.drop-wrapper', triggerSelect: '.trigger-select-files', hideClass: 'none', maxFileSize: 0, uploadUrl: 'upload.php', previewFile: false, uploadParams: null, fileIdPrefix: '', errorMessageUpload: 'Error uploading file', errorMessageServer: 'Server error'  }
	}

	function addFileItem(file) {
		
		_files.push({ id: file.id, name: file.name, size: file.size, type: file.type });
		
		var itemContent = _options.templateItem.replace(/{file.name}/g, file.name);
		itemContent = itemContent.replace(/{file.type}/g, file.type);
		itemContent = itemContent.replace(/{file.size}/g, formatFileSize(file.size));
		itemContent = itemContent.replace(/{file.id}/g, file.id);
		
		var fileItem = $(_options.filesList).append(itemContent);
		return fileItem;
	}
	
	function formatFileSize(fileSize)
	{
		var fileSizeFormat = '';
		var megaByteStart = 1000000;
		if (fileSize < megaByteStart)
		{
			fileSizeFormat = Math.ceil(fileSize / 1000);
			fileSizeFormat += 'Kb';
		}
		else
		{
			fileSizeFormat = (fileSize / 1000000).toFixed(1);
			fileSizeFormat += 'Mb';
		}
		
		return fileSizeFormat;
	}


	// file drag hover
	function FileDragHover(e) {
		e.stopPropagation();
		e.preventDefault();

		// $('#debug').html(e.target.id + ':'+$(e.target).closest(_options.dropZone).attr('class'));
		if (!$(e.target).is($(_options.dropZone)) && $(e.target).closest(_options.dropZone).length == 0 )
			return;
		
		showDropView(_options.dropZone);
		// showDropView(e.target);
	}
	
	function FileDragLeave(e)
	{
		e.stopPropagation();
		e.preventDefault();
		if (!$(e.target).is($(_options.dropTarget)) && !$(e.target).is($(_options.dropZone)) && $(e.target).closest(_options.dropZone).length == 0)
		{
			// $('#debug').html(e.target.id + ':'+$(e.target).attr('class') + ':' + $(e.target).closest(_options.dropZone).attr('class'));
			// $('#debug').html($('#debug').html() + '<br/>'+e.target.id + ':'+$(e.target).attr('class') + ':' + $(e.target).closest(_options.dropZone).attr('class'));
			$(_options.dropTarget).addClass(_options.hideClass);
		}
	}
	
	function showDropView(target)
	{
		$(_options.dropTarget).css('width', $(target).outerWidth() + 'px');
		$(_options.dropTarget).css('height', $(target).outerHeight() + 'px');
		$(_options.dropTarget).css('top', $(target).offset().top + 'px');
		$(_options.dropTarget).css('left', $(target).offset().left + 'px');
		$(_options.dropTarget).removeClass(_options.hideClass);
	}

	// file selection
	// e can be either a drop event or a change event
	function FileSelectHandler(e) {

		if (e.type == 'drop') // if is a drop event, verify drop target
		{
			// if dropped target is not dropZone or dropTarget 
			if (!$(e.target).is($(_options.dropTarget)) && $(e.target).closest(_options.dropTarget).length == 0 && !$(e.target).is($(_options.dropZone)))
				return;
			
			// cancel event and hover styling
			FileDragLeave(e);
		}
		
		// fetch FileList object
		var files = e.target.files || e.dataTransfer.files;

		// process all File objects
		for (var i = 0, f; f = files[i]; i++) {
			if (_options.previewFile)
			{
				previewFile(f);
			}
			uploadFile(f);
		}
		$(_options.fileInput).trigger('files_uploaded');
	}


	// output file information
	function previewFile(file) {

		output(
			"<p>File information: <strong>" + file.name +
			"</strong> type: <strong>" + file.type +
			"</strong> size: <strong>" + file.size +
			"</strong> bytes</p>"
		);

		// display an image
		if (file.type.indexOf("image") == 0) {
			var reader = new FileReader();
			reader.onload = function(e) {
				output(
					"<p><strong>" + file.name + ":</strong><br />" +
					'<img src="' + e.target.result + '" /></p>'
				);
			}
			reader.readAsDataURL(file);
		} 
		else if (file.type.indexOf("text") == 0) {
			var reader = new FileReader();
			reader.onload = function(e) {
				output(
					"<p><strong>" + file.name + ":</strong></p><pre>" +
					e.target.result.replace(/</g, "&lt;").replace(/>/g, "&gt;") +
					"</pre>"
				);
			}
			reader.readAsText(file);
		}
	}

	function uploadFile(file) {
		if (!testFile(file))
			return;
		
		var formData = new FormData();
		
		formData.append('file', file);
		
		file.id = 'fileItem_' + _options.fileIdPrefix + '_' + uniqueNumber();
		
		if (_options.uploadParams != null)
		{
			$.extend(_options.uploadParams, { fileId: file.id });
			_.forEach(_options.uploadParams, function(val, key) {
				formData.append(key, val);
			});
		}
		
		var fileItem = addFileItem(file);
		var progressBar = fileItem.find('.file-item-progress-bar');
		
		$.ajax({
			url: _options.uploadUrl,
			type: 'POST',
			data: formData,
			async: true,
			xhr: function() {  
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload){ 
                    myXhr.upload.addEventListener('progress', function(e) { progressHandle(progressBar, e) }, false); // for handling the progress of the upload
                }
                return myXhr;
            },
			fail: function (data) {
				$(progressBar).addClass('error')
			},
			complete: function (jqXHR, textStatus) {
				handleUploadComplete(jqXHR, file);
			},
			cache: false,
			contentType: false,
			processData: false,
			dataType: 'json'
		});		
	}
	
	function handleUploadComplete(jqXHR, file)
	{
		var data = { status: 'error', usedMessage: _options.errorMessageServer};
		if (jqXHR.readyState == 4)
		{
			data = JSON.parse(jqXHR.responseText);
			data.usedMessage = _options.errorMessageUpload;
		}
		
		var fileItem = $(_options.filesList).find('#'+file.id);
		var progressBar = fileItem.find('.file-item-progress-bar');
		$(progressBar).addClass(data.status);
		
		if (data.status == 'error')
		{
			// remove from _files
			_.remove(_files, { id: file.id } );
			var errMessage = data.usedMessage + ': '+file.name;
			if (data.upload_message != '')
				errMessage += '<br/>'+data.upload_message;
			fileItem.find('.file-name').html(errMessage);
		}
	}

	function progressHandle(progressBar, e)
	{
		if (e.lengthComputable){
			var pc = parseInt(100 - (e.loaded / e.total * 100));
			$(progressBar).css('background-position', pc + "% 0");
        }
	}
	
	function testFile(file)
	{
		if (_options.maxFileSize == 0)
			return true;
		
		if (file.size > _options.maxFileSize)
			_errorList.push( { fileName:file.name, error: 'upload.error_max_file_size', param: formatFileSize(_options.maxFileSize)});
		
		return (file.size <= _options.maxFileSize);
	}

	function getTemplateItem()
	{
		var template = '<div class="file-item clearfix" id="{file.id}"><div class="file-name-wrapper"><span class="file-name">{file.name}</span><span class="file-size">({file.size})</span></div><div class="file-item-progress-bar"></div><i class="fa fa-times file-delete"></i></div>';
		return template;
	}
	
	function uniqueNumber() {
		var date = Date.now();
		
		// If created at same millisecond as previous
		if (date <= uniqueNumber.previous) {
			date = ++uniqueNumber.previous;
		} else {
			uniqueNumber.previous = date;
		}
		
		return date;
	}

	// initialize
	function Init() {

		if (!(window.File && window.FileList && window.FileReader)) {
			alert('Your browser does not support ajax upload. Download Chrome browser');
			return;
		}
			
		var fileselect = $(_options.fileInput).get(0);
		// file select
		fileselect.addEventListener("change", FileSelectHandler, false);

		// is XHR2 available?
		var xhr = new XMLHttpRequest();
		if (xhr.upload) {
			document.addEventListener("dragover", FileDragHover, false);
			document.addEventListener("dragleave", FileDragLeave, false);
			document.addEventListener("drop", FileSelectHandler, false);
		}
		else {
			alert('Your browser does not support ajax upload. Download Chrome browser');
		}
		
		uniqueNumber.previous = 0;
		
		// delegate file delete
		$(_options.filesList).on('click', '.file-delete', function() {
			var fileItem = $(this).closest('.file-item');
			var id = fileItem.attr('id');
			fileItem.remove();
			
			// remove also from _files
			_.remove(_files, { id: id } );
		});
		
		// trigger click 
		$(_options.triggerSelect).click(function(){
			$(_options.fileInput).click();
		});
	}

	// call initialization file
	Init();
	
	
}