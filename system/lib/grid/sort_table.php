<?php
// Class for making the database modification, after an ajax request coming from a sortable table
// example of using this class

//  include the js files: '../scripts/jquery/jquery-1.7.1.min.js','../scripts/jquery/jquery-ui-1.8.17.custom.min.js','../scripts/jquery/jquery.base64.js','../scripts/utils.js?id=2','../scripts/form_trigger.js','../scripts/sortable_init.js'

// in the view: 
//	<input type="hidden" id="hidSortPK_[$rowIndex]" value="[PrimaryKey Value]" />
// <div id="sort_holder">
//  <table id="sortable">....</table>



// intercept ajax call
// $sortTable = new SortTableCategories();
// $sortTable->itemsCount = $someModel->GetRecordsCount($sql);
// $rowsIds = $someModel->GetRecordsIds($sql);
// $rows = $sortTable->GetRowsForOrder($rowsIds, 'id');
// $data = $sortTable->PerformSort('categories','id','sort', $rows);

// A) ajax call which does not require refresh
// if ($data->isAjaxCall)
// {
	// if ($data->status == error || !$data->refresh )
	// {
		// echo json_encode($data);
		// exit();
	// }
//		else if ($data->refresh)
//		{
//			// if include a file:
			// ob_start(); // only if inlcuding a file
			// include "table_view.php";
			//$data->content = ob_get_contents();
			// ob_end_clean();

//			// if generate table on the fly:
//			$data->content = getTableContent(); // get the html of the table


// 			$data->content = base64_encode($data->content); // base64_encode the content
// 			echo stripslashes(json_encode($data));  // stripslashes the json encoded data, since base64_encode may add '/', which are escaped by a slash
// 			exit();
//		}
// }


class SortTable
{
	var $sortTable; // table to sort
	var	$sortPKeys; // array with primary columns
	var	$sortPKey; // primary column, if only one specified
	var $sortColumn; // sort column
	var $startIndex; // row index of the dragged item - this is set from ajax
	var $endIndex; // end row index where dragged item was dropped - this is set from ajax
	var $parentId; // parent id of the dragged item
	var $parentChildrenCount = 0; // children count from current dragged item parent
	var $itemChildrenCount = 0; // current dragged item children count
	var $prevChildrenCount = 0; // previous item children count -> for groups with parents 
	var $prevParentIds = 0; // previous item hierachical parents -> for groups with parents 
	var $nextParentIds = 0; // next item hierachical parents -> for groups with parents 
	var $nextChildrenCount = 0; // next item children count -> for groups with parents 
	
	var $itemsCount = 0; // total items count -> for liniar tables
	
	var $parentChildStructure = false;
	var $dbo;
	
	// make the sorting , for the specified table, arrPKeys, sortColumn
	// $tableName -> table to update
	// $arrPKeys  -> primary key name or array with primary keys names; if only one primary key is specified, pass it as string, not as array
	// $sortColumn -> column name which stores the sorting
	//  returns an object with following variables:
	//			isAjaxCall -> tells if the there was an ajax call or not, based on the parameters received in $_GET
	//			status -> can be "success"  or "error"
	//			refresh -> specify if the content should be completely refreshed, in this case after calling this fucntion, set $data->content with the required content
	// 			error -> specify the error, if any
	
	function __construct()
	{
		$this->dbo = DBO::global_instance();
	}
	
	function PerformSort($tableName, $arrPKeys, $sortColumn, &$rows)
	{
		$data = new stdClass();
		$data->isAjaxCall = false;
		$data->refresh = false;
		
		if (!$this->GetAjaxParams())
			return $data;
			
		$data->isAjaxCall = true;
		$data->status = 'success';
		
		$this->sortTable = $tableName;
		
		if (!is_array($arrPKeys))
		{
			$this->sortPKey = $arrPKeys;
			$this->sortPKeys = null;
		}
		else 
		{
			$this->sortPKeys = $arrPKeys;
			$this->sortPKey = null;
		}
		
		$this->sortColumn = $sortColumn;

		if ($this->parentChildStructure)
			$isValid = $this->GetRelativeIndexesParentChildren($rows, $data);
		else
			$isValid = $this->GetRelativeIndexes($rows, $data);
					
		if (!$isValid)
			$data->status = 'error'; // drop was not in a good place
		else
		{
			$this->SetSortableIndex($rows, $data);
			$this->StoreSortable($rows);
		}

		return $data;
		
	}
	
	// function to get the relative indexes based on the dragged item, for a hierachical structured table, with parent - children relationship
	// can be overwritten if necessary; in this case, set the variables $data->startRelativeIndex and $data->endRelativeIndex
	// return false if the new position is not valid
	// this function works with only one primary key; can be extended if necessary, as in function GetRelativeIndexes
	function GetRelativeIndexesParentChildren(&$rows, &$data)
	{
		$startRelativeIndex = -1; $prevIndexRelative = -1; $nextIndexRelative = -1;
		$rowIndex = 0;
		// get relative start index and the indexes for the previousIds and nextIds of dragged item ( relative to item order)
		foreach ($rows as &$row)
		{
			if ($row[$this->sortPKey] == $this->pkId)
				$startRelativeIndex = $rowIndex;
			if ($this->prevId != 0 && $row[$this->sortPKey] == $this->prevId)
				$prevIndexRelative = $rowIndex;
			if ($this->nextId != 0 && $row[$this->sortPKey] == $this->nextId)
				$nextIndexRelative = $rowIndex;
			
			// if all indexes found
			if ($startRelativeIndex != -1 && ($prevIndexRelative != -1 || $this->prevId == 0)  && ($nextIndexRelative != -1 || $this->prevId == 0))
				break;
			$rowIndex++;
		}
		
		// check if previous item and next item have as parent the parent of the dragged item
		$prevHasSiblingParent = $this->prevParentIds != null && in_array($this->parentId, $this->prevParentIds);
		$nextHasSiblingParent = $this->nextParentIds != null && in_array($this->parentId, $this->nextParentIds);
		// if item is dropped on last position in the item, but after a child of a sibling then allow the sorting
		// also verify that is not dropped between children of the same parent
		$lastPosAllowed = ($prevIndexRelative == -1 && $this->prevId != 0 && $prevHasSiblingParent && 
						$nextIndexRelative == -1 && $this->nextId != 0 && !$nextHasSiblingParent) 
				|| ($this->nextId == 0 && $prevHasSiblingParent);
		
		// if item was not dropped before or after an item from the same parent
		if ( $nextIndexRelative == -1 && $this->nextId != 0 && $prevIndexRelative == -1 && $this->prevId != 0 && !$lastPosAllowed)
		{
			// $data->error = 'item was not dropped before or after an item from the same parent';
			// return false;
		}
		
		// if item was dropped after an item from the same parent, but that item has children
		if ($prevIndexRelative != -1 && $this->prevId != 0 && $this->prevChildrenCount > 0)
		{
			$data->error = 'item was dropped after an item with children from the same parent';
			return false;
		}
		
		// if an item is dropped on first visual place or on last visual place, and does not belong to same parent
		if ( ($this->prevId == 0 && $nextIndexRelative == -1 && $this->nextId != 0) ||
			($this->nextId == 0 && $prevIndexRelative == -1 && $this->prevId != 0 && !$lastPosAllowed) )
		{
			$data->error = 'item is dropped on first visual place or on last visual place, and does not belong to same parent';
			return false;
		}
		
		$deltaIndex = $this->endIndex - $this->startIndex; // get deltaIndex, to see if item was moved higher or lower
		if ($deltaIndex > 0) // item moved lower (ie. from pos. 3 to pos 7)
			$endRelativeIndex = ($nextIndexRelative != -1)? $nextIndexRelative : $prevIndexRelative + 1; // next index priority
		else $endRelativeIndex = ($prevIndexRelative != -1)? $prevIndexRelative : $nextIndexRelative - 1;  // prev index priority
				
		// if item dropped on last position, set endRelativeIndex to be the parent children count
		if ($lastPosAllowed) $endRelativeIndex = $this->parentChildrenCount;
		
		// set variables required for update
		$data->startRelativeIndex = $startRelativeIndex;
		$data->endRelativeIndex = $endRelativeIndex;
					
		return true;
	}
	
	
	// function to get the relative indexes based on the dragged item, for a liniar table
	// can be overwritten if necessary; in this case, set the variables $data->startRelativeIndex and $data->endRelativeIndex
	// return false if the new position is not valid
	function GetRelativeIndexes(&$rows, &$data)
	{
		$startRelativeIndex = -1; $prevIndexRelative = -1; $nextIndexRelative = -1;
		$rowIndex = 0;
		// get relative start index and the indexes for the previousIds and nextIds of dragged group ( relative to item order)
		foreach ($rows as &$row)
		{
			// if more primary keys specified
			if ($this->sortPKeys != null)
			{
				// get an array from the values separated by ','
				$arrPKValues = explode(',', $this->pkId);
				$arrPKValuesPrev = explode(',', $this->prevId);
				$arrPKValuesNext = explode(',', $this->nextId);
				
				// check if count of specified primary columns is different than the count of specified key values
				if (count($arrPKValues) != count ($this->sortPKeys))
				{
					$data->error = sprintf('number of specified primary columns (%d) differs from the number of provided keys values(%d)', count($arrPKValues), count ($this->sortPKeys));
					return false;
				}
				
				$pkIndex = 0;
				$arrFound = array('current'=>true, 'prev'=>true,'next'=>true); // array which will indicate if the items are found
				
				// for all specified primary keys, get the rows which match the dragged item / next item / prev item
				foreach ($this->sortPKeys as $sortPkId)
				{
					if ($row[$sortPkId] != $arrPKValues[$pkIndex])
						$arrFound['current'] = false;
						
					if ($this->prevId != 0 && $row[$sortPkId] != $arrPKValuesPrev[$pkIndex])
						$arrFound['prev'] = false;
						
					if ($this->nextId != 0 && $row[$this->sortPKey] != $arrPKValuesNext[$pkIndex])
						$arrFound['next'] = false;
						
					$pkIndex++;
				}
				
				if ($arrFound['current'])
					$startRelativeIndex = $rowIndex;
				if ($arrFound['prev'])
					$prevIndexRelative = $rowIndex;
				if ($arrFound['next'])
					$nextIndexRelative = $rowIndex;
					
				// if all indexes found, break
				if ($startRelativeIndex != -1 && ($prevIndexRelative != -1 || $this->prevId == 0)  && ($nextIndexRelative != -1 || $this->prevId == 0))
					break;
			}
			else if ($this->sortPKey != null) // only one primary key
			{
				if ($row[$this->sortPKey] == $this->pkId)
					$startRelativeIndex = $rowIndex;
				if ($this->prevId != 0 && $row[$this->sortPKey] == $this->prevId)
					$prevIndexRelative = $rowIndex;
				if ($this->nextId != 0 && $row[$this->sortPKey] == $this->nextId)
					$nextIndexRelative = $rowIndex;
					
				// if all indexes found, break
				if ($startRelativeIndex != -1 && ($prevIndexRelative != -1 || $this->prevId == 0)  && ($nextIndexRelative != -1 || $this->prevId == 0))
					break;
			}
			
			$rowIndex++;
		}
		
		$deltaIndex = $this->endIndex - $this->startIndex; // get deltaIndex, to see if item was moved higher or lower
		if ($deltaIndex > 0) // item moved lower (ie. from pos. 3 to pos 7)
			$endRelativeIndex = ($nextIndexRelative != -1)? $nextIndexRelative : $prevIndexRelative + 2; // next index priority
		else $endRelativeIndex = ($prevIndexRelative != -1)? $prevIndexRelative : $nextIndexRelative - 1;  // prev index priority
		
		if ($this->nextId == 0) $endRelativeIndex = $this->itemsCount; // if last place
			
		// set variables required for update
		$data->startRelativeIndex = $startRelativeIndex;
		$data->endRelativeIndex = $endRelativeIndex;

		return true;
	}
	
	function SetSortableIndex(&$rows, &$data)
	{
		$rowsCount = count($rows);
		for ($rowIndex = 0; $rowIndex < $rowsCount; $rowIndex++)
		{
			$row = &$rows[$rowIndex];
			if ($this->startIndex < $this->endIndex) // row was moved lower (i.e.: from row index 5 to order 10)
			{
				// all rows between startRelativeIndex and endRelativeIndex are shifted down
				if ($rowIndex > $data->startRelativeIndex && $rowIndex < $data->endRelativeIndex)
					$row['sort'] = $rowIndex;
				else if ($rowIndex == $data->startRelativeIndex)
					$row['sort'] = $data->endRelativeIndex; // becomes end index
				else $row['sort'] = $rowIndex + 1; // keep their index
			}
			else // row was moved higher (i.e.: from row index 10 to order 7)
			{
				// all rows beteween endRelativeIndex and startRelative index are shifted up
				if ($rowIndex > $data->endRelativeIndex && $rowIndex < $data->startRelativeIndex)
					$row['sort'] = $rowIndex + 2;
				else if ($rowIndex == $data->startRelativeIndex)
					$row['sort'] = $data->endRelativeIndex + 2;
				else $row['sort'] = $rowIndex + 1; // keep their positions
			}
		}
	}	
	
	function StoreSortable(&$rows)
	{
		if ($this->sortPKeys != null)
			$arrPkIds = explode(',', $this->pkId);
		
		foreach ($rows as &$row)
		{
			$sort = $row['sort'];
			$arrWhere = array();
			if ($this->sortPKey != null) { // only one primary key
				$arrWhere = array($this->sortPKey => $row[$this->sortPKey]);
			}
			else if ($this->sortPKeys != null) // multiple primary key
			{
				foreach ($this->sortPKeys as $sortPkId)	{
					$arrWhere[$sortPkId] = $row[$arrPkIds[$pkIndex]];
				}
			}
			if (count($arrWhere) > 0) {
				$this->dbo->UpdateRow($this->sortTable, array($this->sortColumn => $sort), $arrWhere);
			}
		}
	}
	
	function GetAjaxParams()
	{
		$inputJSON = file_get_contents('php://input');
		if (strlen($inputJSON) == 0) 
			return null;
		
		$input = json_decode($inputJSON, TRUE ); //convert JSON into array

		if ($input == null)
			return false;
		
		if ( isset($input['ajaxAction']) && $input['ajaxAction'] == 'change_order' && isset($input['pkId']) && isset($input['prevId'])  && isset($input['nextId']) && isset($input['startIndex']) && isset($input['endIndex']) )
		{
			$this->pkId = (int)$input['pkId'];
			$this->prevId = (int)$input['prevId'];
			$this->nextId = (int)$input['nextId'];
			$this->startIndex = (int)$input['startIndex'];
			$this->endIndex = (int)$input['endIndex'];
			return true;
		}
		else return false;
	}
	
	function GetRowsForOrder(&$itemsIds, $idField)
	{
		$rows = array();
		foreach ($itemsIds as &$row)
		{
			array_push($rows, array($idField=>$row->{$idField}, 'sort' => 0));
		}
		return $rows;
	}
}


?>