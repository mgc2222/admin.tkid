<?php
class SortTableCategories extends SortTable
{
	function SortTableCategories()
	{
		$this->parentChildStructure = true; // specify that this is a parent-child structured table
		parent::__construct();
	}
	
	function GetCategoryParentId($id)
	{
		$sql = 'SELECT parent_id FROM categories WHERE id='.$id;
		return $this->dbo->GetFieldValue($sql);
	}
	
	function GetCategoriesIdsForParentId($id, $sortColumn)
	{
		$sql = 'SELECT id FROM categories WHERE parent_id='.$id.' ORDER BY '.$sortColumn;
		// echo $sql;
		$rows = $this->dbo->GetRows($sql);
		$ret = null;
		if ($rows != null)
		{
			$ret = array();
			foreach ($rows as &$row)
			{
				array_push($ret, $row->id);
			}
		}
		return $ret;
	}
	
	function GetDatabaseRows(&$data)
	{
		$this->parentId = $this->GetCategoryParentId($this->pkId);
		// get a list with category ids, for current pkId parent, sorted by [sortColumn]
		$categoryIds = $this->GetCategoriesIdsForParentId($this->parentId, $this->sortColumn); 
		
		$rows = array();
		foreach ($categoryIds as $categoryId)
		{
			array_push($rows, array($this->sortPKey=>$categoryId, 'sort' => 0));
		}
		
		// get children count for category and prev / next categories
		$sql = 'SELECT COUNT(*) AS CategoryChildrenCount,  
				 (SELECT COUNT(*) FROM categories WHERE parent_id = '.$this->prevId.') AS PrevChildrenCount,
				 (SELECT COUNT(*) FROM categories WHERE parent_id = '.$this->nextId.') AS NextChildrenCount,
				 (SELECT COUNT(*) FROM categories WHERE parent_id = '.$this->parentId.') AS ParentChildrenCount
			FROM categories WHERE parent_id = '.$this->pkId;
		
		$row = $this->dbo->GetFirstRow($sql);
		$this->itemChildrenCount = (int)$row->CategoryChildrenCount;
		$this->parentChildrenCount = (int)$row->ParentChildrenCount;
		$this->prevChildrenCount = ($this->prevId != 0)?(int)$row->PrevChildrenCount:0;
		$this->nextChildrenCount = ($this->nextId != 0)?(int)$row->NextChildrenCount:0;
		
		$this->prevParentIds = $this->GetCategoryHierachicalParents($this->prevId);
		$this->nextParentIds = $this->GetCategoryHierachicalParents($this->nextId);
		
		// if childrenCount > 0, all content will be refreshed
		$data->refresh = ($this->itemChildrenCount > 0);
		
		return $rows;
	}
	
	// get hierachical parents of a group
	function GetCategoryHierachicalParents($categoryId)
	{
		$ret = null;
		if ($categoryId != 0)
		{
			$ret = array();
			do
			{
				$parentId = $this->GetCategoryParentId($categoryId);
				if ($parentId != null)
				{
					$categoryId = $parentId;
					array_push($ret, $parentId);
				}
				else $categoryId = 0;
			}
			while ($categoryId != 0);
		}
		return $ret;
	}	
}
?>