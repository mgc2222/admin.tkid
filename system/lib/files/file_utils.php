<?php 
class FileUtils
{
	static function DownloadFile($filePath, $saveFileName)
	{
		header("Content-Type: application/octet-stream");
		header("Content-Disposition: attachment; filename=".$saveFileName.";" );
		header("Pragma: no-cache");
		header("Expires: 0");
		readfile($filePath);
	}
	
	static function ZipFilesAndDownload($file_names, $archive_file_name)
	{
		//create the object
		$zip = new ZipArchive();
		//create the file and throw the error if unsuccessful
		if ($zip->open($archive_file_name, ZIPARCHIVE::CREATE )!==TRUE)  
			return "cannot open <{$archive_file_name}>\n";

		//add each files of $file_name array to archive
		foreach($file_names as $file)
		{
			$zip->addFile($file);
		}
		
		$zip->close();


		//then send the headers to foce download the zip file
		header("Content-type: application/zip");
		header("Content-Disposition: attachment; filename={$archive_file_name}");
		header("Pragma: no-cache");
		header("Expires: 0");
		readfile($archive_file_name);
		
		return 'success';
	}
	
	// $fileExtensions, separated by |  ; ex:  jpg|png|jpeg  ...
	static function ReadFolderFiles($path, $fileExtensions = '*')
	{
		$arrFiles = array();
		if ($handle = opendir($path))
		{
			$arrExtensions = explode('|', strtolower($fileExtensions));
			while (false !== ($entry = readdir($handle))) 
			{
				if ($entry != "." && $entry != "..") 
				{
					$addFile = false;
					if ($fileExtensions == '*')
						$addFile = true;
					else
					{
						$extension = pathinfo($entry, PATHINFO_EXTENSION);
						$extension = strtolower($extension);
						if (in_array($extension, $arrExtensions))
							$addFile = true;
					}
					
					if ($addFile)
						array_push($arrFiles, $entry);
				}
			}
			closedir($handle);
		}
		if (count($arrFiles) == 0) $arrFiles = null;
		return $arrFiles;
	}
	
	// returns a file name which doesn't already exists, by adding a numeric sufix 
	static function GetSafeFileNameForSave($filePath, $fileName)
	{
		if (!$fileName) return '';
		$fileInfo = pathinfo($fileName);

		if ($fileInfo == null)
		{
			return '';
		}
		
		$fileNameSave = StringUtils::UrlTitle($fileInfo['filename']);
		if ($fileInfo['extension'] != '')
			$fileNameSave .= '.'.$fileInfo['extension'];
		
		$fileTest = $filePath.$fileNameSave;

		$addIndex = 0;

		while (file_exists($fileTest))
		{
			$suffix = time() + $addIndex;
			$fileNameSave = StringUtils::UrlTitle($fileInfo['filename']).'_'.$suffix;
			if ($fileInfo['extension'] != '')
				$fileNameSave .= '.'.$fileInfo['extension'];
		
			$fileTest = $filePath.$fileNameSave;
			$addIndex++;
		}
				
		return $fileNameSave;
	}
}
?>