<?php 
class ExportToCsv
{
	function GetExportData(&$rows, &$fields, &$displayFields)
	{
		$data = '';
		$headers = '';
		foreach ($displayFields as $field)
		{
			$headers .= $field."\t";
		}
		
		if ($rows != null)
		{
			$rowIndex = 0;
			foreach ($rows as $row)
			{   
				$rowIndex++;
				$line = '';
				
				// treat exceptions
				foreach ($fields as $dbField)
				{
					$val = $row->{$dbField};
					$line .= $this->FormatValue($val);
				}
				$data .= trim($line) . "\n";
			}
			$data = str_replace("\r", "", $data);

			return $headers."\n".$data;
		}
		else
			return false;
	}
	
	function FormatValue($val)
	{
		if ($val !='' && $val !== null)
			return '"' .str_replace('"', '""', $val)  . '"' . "\t";
		else return "\t";
	}
	
	function OutputExportData($content, $fileName)
	{
		header("Content-type: application/x-msdownload");
		header("Content-Disposition: attachment; filename={$fileName}");
		echo $content;
		exit();
	}
}
?>