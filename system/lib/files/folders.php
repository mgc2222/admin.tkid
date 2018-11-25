<?php
class Folders
{
	static function DeleteDirRecursive($dir) 
	{
		if ($dir == '') return;
		if (!is_dir($dir)) return;
		
		$mydir = opendir($dir);
		while(false !== ($file = readdir($mydir))) 
		{
			if($file != "." && $file != "..") 
			{
				chmod($dir.$file, 0777);
				if(is_dir($dir.$file)) 
				{
					chdir('.');
					Folders::DeleteDirRecursive($dir.$file.'/');
					// rmdir($dir.$file) or die("couldn't delete {$dir}{$file}<br />");
				}
				else
					unlink($dir.$file) or die("couldn't delete {$dir}{$file}<br />");
			}
		}
		closedir($mydir);
		rmdir($dir) or die("couldn't delete {$dir}<br />");
	}
	
	static function CreateDir($dir)
	{
		if (!file_exists($dir))
			mkdir($dir);
	}
}
?>