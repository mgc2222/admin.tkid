<?php
// usage example: 
// $fileUpload = new FileUpload();

// for simple upload, use :
// $fileUpload->ProcessUploadFile($fileInputId, $filePath, $fileName);

// for upload with multiple actions, use:
// $options = new stdClass();
// $options->actions = array(
		// array('action'=>'resize_image', 'width'=>800,'height'=>600, 'mentain_aspect_ratio'=>true) , 
		// array('action'=>'resize_image', 'width'=>150,'height'=>90, 'fileName'=>'thumb', 'mentain_aspect_ratio'=>true) , 
		// array('action'=>'crop_ratio_image', 'width'=>150,'height'=>90, 'fileName'=>'crop') , 
		// array('action'=>'crop_ratio_and_resize_image', 'width'=>150,'height'=>90, 'fileName'=>'thumbCropped', 'quality'=>0.9));

// $fileUpload->ProcessUploadFile('file', 'images', 'firstImage', $options);

// note: in options, width and height can be passed as '*', if you want to resize only for a fixed width / height

class FileUpload
{
	var $FileSizeDisplay;
	var $FileExtension;
	var $OriginalFileName;
	var $ImageWidth;
	var $ImageHeight;
	
	var $Message;
	var $lastError = '';
	var $lastErrorParam = '';
	
	function __construct()
	{
		$this->ImageWidth = 0;
		$this->ImageHeight = 0;
	}
		
	// $fileInputId -> id of the file element from html 
	// $uploadFolder  -> folder where file will be uploaded
	// $saveName -> how will be the file saved as : without extensions, the extension will be extracted from original file name
	// $fileExtension -> if set, then will use this extension, otherwise will get the extension from original file name
	// $options  -> an object for possible options
	//			allowedTypes : an array with allowed types, to be checked
	//			fileMaxSize : max size in bytes allowed for file
	//			actions : an array with additional actions to perform and the params for it: 
	//				[action, width, height, quality, mentain_aspect_ratio, force_resize, fileName, filePath, fileRelativePath]
	//				 action can be: save_file, resize_image, crop_ratio_image, crop_ratio_and_resize_image
	function ProcessUploadFile($fileInputId, $uploadFolder, $saveName, $options = null, $fileExtension = '')
	{
		$this->lastError = '';
		$this->lastErrorParam = '';
		
		$ignoreNoFileSelected = false;
		if ($options != null && isset($options->ignoreNoFileSelected)) {
			$ignoreNoFileSelected = $options->ignoreNoFileSelected;
		}
		
		if (!isset($_FILES[$fileInputId]) || $_FILES[$fileInputId]['name'] == '')
		{
			$this->lastError = 'error_no_file_selected';
			return $ignoreNoFileSelected; // if ignoreNoFileSelected was set, return true, else return false
		}
		
		$this->OriginalFileName = $_FILES[$fileInputId]['name'];
		$fileParts = pathinfo($this->OriginalFileName);
		
		$isTypeAllowed = true;
		if ($options != null && isset($options->allowedTypes)) {
			$isTypeAllowed = in_array($_FILES[$fileInputId]['type'], $options->allowedTypes);
		}
		
		if (!$isTypeAllowed)
		{
			$this->lastError = 'error_file_type_not_allowed';
			$this->lastErrorParam = $_FILES[$fileInputId]['type'];
			return false;
		}
		
		$srcFilePath = $_FILES[$fileInputId]['tmp_name'];
		if ($options != null && isset($options->fileMaxSize))
		{
			$fileSizeInfo = filesize($srcFilePath);
			if ($fileSizeInfo > $options->fileMaxSize)
			{
				$this->lastError = 'error_file_max_size';
				$this->lastErrorParam = $this->FormatSizeDisplay($options->fileMaxSize, 0);
			}
		}
		
		if (is_uploaded_file($srcFilePath))
		{
			$this->GetFileImageSize($srcFilePath);
			
			$uploadFolder = $this->addFolderSlash($uploadFolder);		
			$this->FileExtension = ($fileExtension != '')?$fileExtension:$fileParts['extension'];
			$dstFilePath = $uploadFolder.$saveName;
			
			if ($options == null || !isset($options->actions) )
				$this->SaveFile($srcFilePath, $dstFilePath);
			else
				$this->processActions($srcFilePath, $dstFilePath, $options);
		}
		else 
			$this->lastError = 'error_not_uploaded';
		
		return ($this->lastError == '');
	}
	
	// $srcFilePath -> source file path
	// $options  -> an object for possible options
	//			allowedTypes : an array with allowed types, to be checked
	//			fileMaxSize : max size in bytes allowed for file
	//			actions : an array with additional actions to perform and the params for it: 
	//				[action, width, height, quality, mentain_aspect_ratio, force_resize, fileName, filePath, fileRelativePath]
	//				 action can be: save_file, resize_image, crop_ratio_image, crop_ratio_and_resize_image
	function ProcessFile($srcFilePath, $options = null)
	{		
		$this->OriginalFileName = $srcFilePath;
		$fileParts = pathinfo($srcFilePath);
		
		if (file_exists($srcFilePath))
		{
			$this->GetFileImageSize($srcFilePath);
			
			$fileFolder = $fileParts['dirname'];
			$destinationFolder = $this->addFolderSlash($fileFolder);
			$this->FileExtension = $fileParts['extension'];
			
			$this->processActions($srcFilePath, $destinationFolder.$fileParts['filename'], $options);
		}
		else 
			$this->lastError = 'error_file_not_exists';
	}
	
	function processActions($srcFilePath, $dstFilePath, &$options)
	{
		foreach ($options->actions as &$params)
		{
			$dstFilePath = $this->getFileNameFromParams($params, $dstFilePath, $this->FileExtension);
			
			switch ($params['action'])
			{
				case 'save_file':
					$this->SaveFile($srcFilePath, $dstFilePath);
					$srcFilePath = $dstFilePath; // after save, file is moved, therefor we need to change path for the rest of the actions
				break;
				case 'resize_image':
					if (!isset($params['quality'])) $params['quality'] = 0.75;
					if (!isset($params['force_resize'])) $params['force_resize'] = false;
					if (!isset($params['mentain_aspect_ratio'])) $params['mentain_aspect_ratio'] = false;
					$this->resizeImageFromFile($srcFilePath, $dstFilePath, $params['width'], $params['height'], $params['quality'], $params['mentain_aspect_ratio'], $params['force_resize']);
				break;
				case 'crop_ratio_image':
					if (!isset($params['quality'])) $params['quality'] = 0.75;
					$this->cropRatioImageFromFile($srcFilePath, $dstFilePath, $params['width'], $params['height'], $params['quality']);
				break;
				case 'crop_ratio_and_resize_image':
					if (!isset($params['quality'])) $params['quality'] = 0.75;
					if (!isset($params['force_resize'])) $params['force_resize'] = false;
					if (!isset($params['mentain_aspect_ratio'])) $params['mentain_aspect_ratio'] = false;
					$this->cropRatioAndResizeImageFromFile($srcFilePath, $dstFilePath, $params['width'], $params['height'], $params['quality'], $params['mentain_aspect_ratio'], $params['force_resize']);
				break;
				case 'crop_image':
					if (!isset($params['quality'])) $params['quality'] = 0.75;
					$this->cropImageFromFile($srcFilePath, $dstFilePath, $params['start_x'], $params['start_y'], $params['width'], $params['height'], $params['quality']);
				break;
			}
		}
	}
	
	function getFileNameFromParams(&$params, $dstFilePath, $fileExtension)
	{
		$dstFileName = '';
		if (isset($params['filePath']) && $params['filePath'] != '')
			$dstFileName = $params['filePath'];
		else $dstFileName = $dstFilePath;
				
		// remove extension, if it was specified
		$fileParts = pathinfo($dstFileName);
		if (isset($fileParts['extension']) && $fileParts['extension'] != '')
			$dstFileName = $fileParts['dirname'].'/'.$fileParts['filename'];

		return $dstFileName.'.'.$fileExtension;
	}
	
	function addFolderSlash($folder)
	{
		if (strlen($folder) > 0)
			if ($folder[strlen($folder)-1] != '/')
				$folder .= '/';
		return $folder;
	}
	
	function FormatSizeDisplay($fileSizeInfo, $precision = 2)
	{
		// if ($fileSizeInfo < 1024)
			// $this->fileSizeDisplay .= $fileSizeInfo." (bytes)" ;
		// elseif ($fileSizeInfo >= 1024 && $fileSizeInfo < 1<<20)
			// $this->fileSizeDisplay = number_format($fileSizeInfo/1024,2,".","")." (Kb)";
		// elseif ($fileSizeInfo >= 1<<20)
			// $this->fileSizeDisplay = number_format($fileSizeInfo/(1<<20),2,".","")." (Mb)";
			
		if ($fileSizeInfo < 1000000)
			$fileSizeDisplay = number_format($fileSizeInfo/1000, $precision,".","")." Kb";
		else
			$fileSizeDisplay = number_format($fileSizeInfo/1000000, $precision,".","")." Mb";
			
		return $fileSizeDisplay;
	}
	
	function SaveFile($tempFileName, $saveFileName)
	{
		if (file_exists($saveFileName))
			@unlink($saveFileName);
		
		$filePath = dirname($saveFileName);
		if (!is_dir($filePath))
			mkdir($filePath, 0777, true);
		
		move_uploaded_file($tempFileName, $saveFileName);
		chmod($saveFileName, 0777);
	}
	
	// get an image object from a file image
	// returns an object with the image object and the image info
	function getImageObjectFromFile($fileName)
	{
		if(is_file($fileName))
		{
			// Get Image size info
			$imageInfo = new stdClass();
			$fileInfo = @getimagesize($fileName);
			
			if ($fileInfo == false)
			{
				$this->lastError = 'error_reading_source_file';
				$this->lastErrorParam = $fileName;
				return false;
			}
			
			list($imageInfo->width, $imageInfo->height, $imageInfo->type) = $fileInfo;
			
			$objImage = null;
			switch ($imageInfo->type)
			{
				case 1: $objImage = @imagecreatefromgif($fileName); break;
				case 2: $objImage = @imagecreatefromjpeg($fileName); break;
				case 3: $objImage = @imagecreatefrompng($fileName); break;
			}
			
			if (!$objImage)
			{
				$this->lastError = 'error_create_image_from_file';
				return false;
			}
			
			$ret = new stdClass();
			$ret->image = $objImage;
			$ret->imageInfo = $imageInfo;
			return $ret;
		}
		return false;
	}
	
	function saveImageObjectToFile($objImage, $imageType, $fileName, $quality)
	{
		// create the directory if needed
		$filePath = dirname($fileName);
		if (!is_dir($filePath))
			mkdir($filePath, 0777, true);
		
		// If the file already exists
		if(is_file($fileName))
			@unlink($fileName);

				
		switch ($imageType)
		{
			case 1: $res = @imagegif($objImage,$fileName); break;
			case 2: $res = @imagejpeg($objImage,$fileName,intval($quality*100));  break;
			case 3: $res = @imagepng($objImage,$fileName,intval($quality*9)); break;
		}
				
		if (!$res)
			$this->lastError = 'error_saving_image_file';
		
		chmod($fileName, 0777);
		
		return $res;
	}
	
	function resizeImageFromFile($sourceFileName, $destinationFileName, $max_width, $max_height, $quality = 0.75, $mentainAspectRatio = false, $forceResize = false)
	{
		$this->lastError = '';
		$this->lastErrorParam = '';
		$data = $this->getImageObjectFromFile($sourceFileName);
		if ($data == false) return false;
		
		return $this->resizeImageAndSave($data, $destinationFileName, $max_width, $max_height, $quality, $mentainAspectRatio, $forceResize = false);
	}
	
	function cropRatioAndResizeImageFromFile($sourceFileName, $destinationFileName, $width, $height, $quality = 0.75, $mentainAspectRatio, $forceResize = false)
	{
		$this->lastError = '';
		$this->lastErrorParam = '';
		$data = $this->getImageObjectFromFile($sourceFileName);
		if ($data == false) return false;
		$data->image = $this->cropRatioImage($data->image, $data->imageInfo->width, $data->imageInfo->height, $width, $height);
		if ($data->image == false) return false;
		// set new original width  and height
		$data->imageInfo->width = imagesx($data->image);
		$data->imageInfo->height = imagesy($data->image);
		return $this->resizeImageAndSave($data, $destinationFileName, $width, $height, $quality, $mentainAspectRatio, $forceResize = false);
	}
	
	function cropRatioImageFromFile($sourceFileName, $destinationFileName, $width, $height, $quality = 0.75)
	{
		$this->lastError = '';
		$this->lastErrorParam = '';
		$data = $this->getImageObjectFromFile($sourceFileName);
		if ($data == false) return false;
		$data->image = $this->cropRatioImage($data->image, $data->imageInfo->width, $data->imageInfo->height, $width, $height);
		if ($data->image == false) return false;
		$this->saveImageObjectToFile($data->image, $data->imageInfo->type, $destinationFileName, $quality);
	}
	
	function cropImageFromFile($sourceFileName, $destinationFileName, $startX, $startY, $width, $height, $quality = 0.75)
	{
		$this->lastError = '';
		$this->lastErrorParam = '';
		$data = $this->getImageObjectFromFile($sourceFileName);
		if ($data == false) return false;
		$data->image = $this->cropImage($data->image, $startX, $startY, $width, $height);
		if ($data->image == false) return false;
		$this->saveImageObjectToFile($data->image, $data->imageInfo->type, $destinationFileName, $quality);
	}

	
	/**
	 * Resize an image based on input file name, output filename, max width and max height proportional
	 *
	 * @param type $imageData - an object resulted from the call of: getImageObjectFromFile
	 * @param type $destinationFileName - the output file name
	 * @param type $max_width   - maximum width of the new image
	 * @param type $max_height  - maximum height of the new image
	 * @param type $quality     - quality of the resize
	 * @param type $forceResize - resize the image even if the width and height are smaller than max_width, max_height
	 * @return true or false      - result of opperation
	 */
	function resizeImageAndSave(&$imageData, $destinationFileName, $max_width, $max_height, $quality = 0.75, $mentainAspectRatio = false, $forceResize = false)
	{
		$srcImage = $imageData->image;
		$imageInfo = $imageData->imageInfo;
		
		// if only one size is specified
		if ($max_width == '*' || $max_height == '*')
		{
			$new_size = $this->calculateSize($imageInfo->width, $imageInfo->height, $max_width, $max_height);
			$max_width = $new_size['width'];
			$max_height = $new_size['height'];
		}
		
		// if no resize needed, save original file
		if ($imageInfo->height < $max_height && $imageInfo->width < $max_width && !$forceResize)
			return $this->saveImageObjectToFile($srcImage, $imageInfo->type, $destinationFileName, $quality);
		
		$ratio = (($imageInfo->height/$imageInfo->width) - ($max_height/$max_width));
		
		if ($mentainAspectRatio)
		{
			if ($ratio < 0)
			{
				$new_width = $max_width;
				$new_height = ceil($imageInfo->height * $max_width / $imageInfo->width);
			}
			else
			{
				$new_height = $max_height;
				$new_width = ceil($imageInfo->width * $max_height / $imageInfo->height);
			}
		}
		else
		{
			$new_width = $max_width;
			$new_height = $max_height;
		}
						
		$dstImage = $this->resizeImage($srcImage, $imageInfo->width, $imageInfo->height, $new_width, $new_height);
		
		if ($dstImage == false)
			return false;
		return $this->saveImageObjectToFile($dstImage, $imageInfo->type, $destinationFileName, $quality);
	}
	
	// crop an image based on desired width and desired height (aspect ratio)
	function cropRatioImage(&$srcImage, $src_width, $src_height, $desiredWidth = 0, $desiredHeight = 0)
	{
		// if only one size is specified
		if ($desiredWidth == '*' || $desiredHeight == '*')
		{
			$new_size = $this->calculateSize($src_width, $src_height, $desiredWidth, $desiredHeight);
			$desiredWidth = $new_size['width'];
			$desiredHeight = $new_size['height'];
		}
		
		// get aspect ratio
		$ratioW = $src_width / $desiredWidth;
		$ratioH = $src_height / $desiredHeight;
		
		$ratio = ($ratioW  < $ratioH)?$ratioW:$ratioH;
			
		// calculate new height and width based on the ratio
		$new_width = floor($desiredWidth * $ratio);
		$new_height = floor($desiredHeight * $ratio);
		
		// calculate crop start position
		$startX = floor(($src_width - $new_width) / 2);
		$startY = floor(($src_height - $new_height) / 2);
		
		return $this->cropImage($srcImage, $startX, $startY, $new_width, $new_height);
	}
	
	function cropImage(&$srcImage, $startX, $startY, $dest_width, $dest_height)
	{
		$dstImg = imagecreatetruecolor($dest_width, $dest_height);
		if (!$dstImg)
		{
			$this->lastError = 'error_image_create_truecolor';
			return false;
		}
		
		$res = imagecopy($dstImg, $srcImage, 0, 0, $startX, $startY, $dest_width, $dest_height); 
		if(!$res)
		{
			$this->lastError = 'error_image_copy';
			return false;
		}
		
		return $dstImg;
	}
	
	function resizeImage(&$srcImage, $src_width, $src_height, $dest_width, $dest_height)
	{
		$dstImg = @imagecreatetruecolor($dest_width, $dest_height);
		if(!$dstImg)
		{
			$this->lastError = 'error_image_create_truecolor';
			return false;
		}
		
		$res = @imagecopyresampled($dstImg, $srcImage, 0,0,0,0,$dest_width,$dest_height,$src_width,$src_height);
		
		if(!$res)
		{
			$this->lastError = 'error_image_copy_resampled';
			return false;
		}
		return $dstImg;
	}
	
	function calculateSize($src_width, $src_height, $dest_width, $dest_height, $mode='-')
	{
		if($dest_width == "*" && $dest_height == "*")
		{
			$new_width = $src_width;
			$new_height = $src_height;
			return array('width'=>$new_width, 'height'=>$new_height);
		}
		
		$ratio = $src_width / $src_height;

		if ($dest_width == "*")
		{
			$new_height = $dest_height;
			$new_width = $ratio * $dest_height;
		}
		elseif ($dest_height == "*")
		{
				$new_height = $dest_width / $ratio;
				$new_width =  $dest_width;
		}
		
		return array('width'=>$new_width, 'height'=>$new_height);
	}
	
	function GetFileImageSize($filePath)
	{
		try {
			$arrImageSize = getimagesize($filePath);
			list($this->ImageWidth, $this->ImageHeight) = $arrImageSize;
		}
		catch (Exception $e) {
		}		
	}
}
?>