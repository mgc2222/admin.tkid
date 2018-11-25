// Custom example logic
$(function() {
	/* var sizeSettings = getSizeSettings(); */
	var uploader = new plupload.Uploader({
		// add to runtimes, if wanted:  gears,html5,
		runtimes : 'html5,flash,silverlight,browserplus',
		browse_button : 'pickfiles',
		container : 'container',
		max_file_size : '10mb',
		url : SITE_RELATIVE_URL + 'pictures/upload/propertyId='+currentPropertyId+';rtype='+currentRoomType,
		flash_swf_url : '../scripts/upload/plupload.flash.swf',
		silverlight_xap_url : '../scripts/upload/plupload.silverlight.xap',
		filters : [
			{title : "Image files", extensions : "jpg"},
			{title : "Zip files", extensions : "zip"}
		],
		/* resize : sizeSettings */
	});

	uploader.bind('Init', function(up, params) {
		// $('#filelist').html("<div>Current runtime: " + params.runtime + "</div>");
	});

	$('#uploadfiles').click(function(e) {
		uploader.start();
		e.preventDefault();
	});

	uploader.init();

	uploader.bind('FilesAdded', function(up, files) {
		$.each(files, function(i, file) {
			$('#filelist').append(
				'<div id="' + file.id + '">' +
				file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' +
			'</div>');
		});

		up.refresh(); // Reposition Flash/Silverlight
	});

	uploader.bind('UploadProgress', function(up, file) {
		$('#' + file.id + " b").html(file.percent + "%");
	});

	uploader.bind('Error', function(up, err) {
		$('#filelist').append("<div>Error: " + err.code +
			", Message: " + err.message +
			(err.file ? ", File: " + err.file.name : "") +
			"</div>"
		);

		up.refresh(); // Reposition Flash/Silverlight
	});

	uploader.bind('FileUploaded', function(up, file) {
		$('#' + file.id + " b").html("100%");
	});
	
	uploader.bind('UploadComplete', function(up, files) {
		window.location.reload(true); // refresh
    });
	
	/* function getSizeSettings()
	{
		var resize = {width : 1024, height : 768, quality : 90};
		return resize;
	} */
});