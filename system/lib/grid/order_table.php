<?php
	class OrderTable
	{
		// change elements order on insert
		static function ChangeOrder($table, $idField, $idValue, $orderField, $orderValue, $whereClause=null)
		{
			$sql1 = "UPDATE {$table} SET {$orderField} = {$orderField} - 1 WHERE {$orderField} > {$orderValue}";
			if ($whereClause != null)
				$sql1 .= " AND {$whereClause}";
				   
			$sql2 = "UPDATE {$table} SET {$orderField} = {$orderField} + 1 WHERE {$orderField} >= {$orderValue}";
			if ($whereClause != null)
				$sql2 .= " AND {$whereClause}";
					   
			$sql3 = "UPDATE {$table} SET {$orderField} = {$orderValue} WHERE {$idField} = {$idValue}";
			
			if (mysql_query($sql1) && mysql_query($sql2) && mysql_query($sql3))
				return true;
			else
				return false;
		}
		
		static function Reorder($table="", $idField="", $idValue="", $orderField="", $operation="up", $whereClause=NULL)
		{			
			$sql = "UPDATE {$table} t SET {$orderField} = (SELECT @rownum:=@rownum+1 AS newpos FROM  (SELECT @rownum:=0) r ) ORDER BY t.{$orderField}";
			if ($whereClause != null)
				$sql .= " WHERE {$whereClause}";
			
			mysql_query($sql);
		}
		
		/**
		* parameter: $table
		*/
		static function SwitchOrder($table, $idField, $idValue, $orderField, $operation="up", $whereClause=null)
		{
			$dbo = DBO::global_instance();
			$condition = " {$idField} = {$idValue} ";
			if ($whereClause != null)
				$condition .= " AND ".$whereClause;
			
			$sql = SqlBuilder::BuildSelectSql($table, "{$idField}, {$orderField}", $condition, null, '1');
			$record = $dbo->GetFirstRow($sql);
		
			if ($record != null)
			{
				$orderValue = $record->{$orderField};
				$conditionSign = ($operation == 'up')? '<':'>';
				$orderSort = ($operation == 'up')? ' DESC':' ASC';
				$condition = " {$orderField} {$conditionSign} {$orderValue} ";
				if ($whereClause != null)
					$condition .= " AND ".$whereClause;
				
				$sql = SqlBuilder::BuildSelectSql($table, "{$idField}, {$orderField}", $condition, $orderField.$orderSort, '1');
				$recordSwitch = $dbo->GetFirstRow($sql);
				
				if ($recordSwitch != null)
				{
					$switchOrderValue = $recordSwitch->{$orderField};
					$switchIdValue = $recordSwitch->{$idField};
					
					$sql = "UPDATE {$table} SET {$orderField} = {$switchOrderValue} WHERE {$idField} = {$idValue}";
					mysql_query($sql);
					$sql = "UPDATE {$table} SET {$orderField} = {$orderValue} WHERE {$idField} = {$switchIdValue}";
					mysql_query($sql);
				}
			}
		}
	}
?>
