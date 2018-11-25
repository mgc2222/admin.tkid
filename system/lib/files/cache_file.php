<?php  
	class CacheFile
	{	
		// $expirePeriod : in hours
		static function ReadFile($fileName, $expirePeriod = 24)
		{
			$fileContent = '';
			$isExpired = false;
			$fileExists = false;
			if (file_exists($fileName))
			{
				$fileExists = true;
				$fileTime = filemtime($fileName);
				$fileTime += $expirePeriod * 3600;
				
				if ($fileTime < time())
					$isExpired = true;
			}
			
			if (!$isExpired && $fileExists)
			{
				$fileContent = file_get_contents($fileName);
			}
			return $fileContent;
		}
		
		static function WriteFile($fileName, $fileContent)
		{
			$fh = fopen($fileName, 'wt');
			fwrite($fh, $fileContent);
			fclose($fh);
			chmod($fileName, 0777); // make it writeable
		}
	}	
?>
