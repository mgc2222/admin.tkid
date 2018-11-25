<?php
class CategoriesMap
{
	var $treeCategories;
	var $arrCategoryIndex; // map for categoryID
	var $arrUrlKeyIndex; // map for urlKey
	var $dbo;
	private static $instance;
	
	function __construct()
	{
		$this->dbo = DBO::global_instance();
	}
	
	// singleton
	static function GetInstance()
	{
		if (!isset(self::$instance)) 
		{
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
	}

	// ===================== Categories mapping - BEGIN ======================== //
	
	// create a mapping of the categories, using a non recursive method
	public function MapCategories($mainCategoryName = 'Main')
	{
		$this->treeCategories = array();
		$this->arrCategoryIndex = array();
		$this->arrUrlKeyIndex = array();

		$lstCategories = $this->GetCategories();

		
		$mainCategory = $this->GetMainCategoryItem($mainCategoryName);
		if ($lstCategories == null)
			$lstCategories = array(0=>$mainCategory);
		else
			array_unshift($lstCategories, $mainCategory);
		
		$this->MapChildren($lstCategories);
		$this->SetRecursiveChildren();
		$this->SetLevelsAndIndent();
		$this->SetArticlesCounts();
	}
	
	private function GetMainCategoryItem($categoryName)
	{
		$category = new stdClass();
		$category->id = 0;
		$category->name = $categoryName;
		$category->parent_id = -1;
		$category->level = 0;
		$category->url_key = 'main_category';
		$category->order_index = 0;
		$category->status = 1;
		$category->display_separate_status = 0;
		
		return $category;
	}

	private function GetIndexes(&$lstCategories)
	{
		$arrIndexesEnd = array();
		$arrIndexesStart = array();
		$currentParentId = -1;
		$rowIndex = 0;
		
		// build an array with the mapped items
		foreach ($lstCategories as &$row)
		{
			$mappedItem = new CategoryMapItem($row->id, $row->parent_id, $row->name, $row->url_key, $row->order_index, $row->status, 
				$row->display_separate_status);
			array_push($this->treeCategories, $mappedItem);
			$this->arrCategoryIndex[$row->id] = $rowIndex;
			$this->arrUrlKeyIndex[$row->url_key] = $rowIndex;
			
			// index the parent ids
			if ($currentParentId != $row->parent_id)
			{
				$arrIndexesStart[$row->parent_id] = $rowIndex;
				if ($currentParentId != -1)
					$arrIndexesEnd[$currentParentId] = $rowIndex;
				$currentParentId = $row->parent_id;
			}
			$rowIndex++;
		}
		$arrIndexesEnd[$currentParentId] = $rowIndex; // add last index

		return array('start'=>$arrIndexesStart, 'end'=>$arrIndexesEnd);
	}
	
	private function SetArticlesCounts()
	{
		$rows = $this->GetArticlesCount();
		if ($rows == null) return;
		foreach ($rows as &$row)
		{
			
			if (isset($this->arrCategoryIndex[$row->category_id]))
			{
				$catIndex = $this->arrCategoryIndex[$row->category_id];
				$this->treeCategories[$catIndex]->ArticlesCount = $row->cnt;
				$this->treeCategories[$catIndex]->ArticlesIds = $row->articlesIds;
				if($row->cnt){
					$this->treeCategories[$catIndex]->Articles  = $this->GetArticlesByIds($row->articlesIds);
				}
				
			}
		}
	}
	
	private function MapChildren(&$lstCategories)
	{
		$indexes = $this->GetIndexes($lstCategories);
		$arrIndexesStart = $indexes['start'];
		$arrIndexesEnd = $indexes['end'];
		
		// make the mappings for children
		foreach ($this->treeCategories as &$row)
		{
			// if there is an index for this category id, meaning this category has children
			if (isset($arrIndexesStart[$row->id]))
			{
				// add all children to it
				for ($rowIndex = $arrIndexesStart[$row->id]; $rowIndex < $arrIndexesEnd[$row->id]; $rowIndex++)
				{
					// $row->AddChild($this->treeCategories[$rowIndex]);
					$this->treeCategories[$rowIndex]->parentRef = &$row; // set the parentRef for the children
					array_push($row->DirectChildrenIdsArray, $this->treeCategories[$rowIndex]->id);
					$row->DirectChildrenIds .= $this->treeCategories[$rowIndex]->id.',';
					$row->DirectChildrenCount++;
				}
				if (strlen($row->DirectChildrenIds != 0)) // remove last ','
					$row->DirectChildrenIds = substr($row->DirectChildrenIds, 0, strlen($row->DirectChildrenIds) - 1);
			}
		}
	}
	
	// create the Recursive children indexes, starting from the items with no children and surfing to their parents
	// also set their level, according to the number of parents
	private function SetRecursiveChildren()
	{
		foreach ($this->treeCategories as &$row)
		{
			if ($row->DirectChildrenCount == 0 && $row->parentRef != null)
			{
				$level = 0;
				$currentParent = $row->parentRef;
				$recursiveChildrenIds = array();
				array_push($recursiveChildrenIds, $row->id);
				// $recursiveChildrenCount = 1;

				// for each category parent, add the ids of the found subcategories
				while($currentParent != null)
				{
					foreach ($recursiveChildrenIds as $childId)
					{
						array_push($currentParent->RecursiveChildrenIdsArray, $childId);
					}
					
					array_push($recursiveChildrenIds, $currentParent->id); 
					$currentParent = $currentParent->parentRef;
					$level++;
				}
				$row->level = $level;
				
				// set level for parents
				$currentParent = $row->parentRef;
				while($currentParent != null)
				{
					$level--;
					$currentParent->level = $level;
					$currentParent = $currentParent->parentRef;
				}
			}
		}
	}
	
	private function SetLevelsAndIndent()
	{
		// remove the last comma from the recursive children ids and set the level
		foreach ($this->treeCategories as &$row)
		{
			$row->SetIndent();
			if (count($row->RecursiveChildrenIdsArray) > 0)
			{
				$row->RecursiveChildrenIdsArray = array_unique($row->RecursiveChildrenIdsArray);
				$row->RecursiveChildrenCount = count($row->RecursiveChildrenIdsArray);
				$row->RecursiveChildrenIds = implode(',', $row->RecursiveChildrenIdsArray);
			}
		}
	}
	
	// ===================== Categories mapping - END ======================== //
	
	public function GetCategoryItemByUrlKey($urlKey)
	{
		if (isset($this->arrUrlKeyIndex[$urlKey]))
			return $this->treeCategories[$this->arrUrlKeyIndex[$urlKey]];
		else return null;
	}
	
	public function GetCategoryItemById($id)
	{
		if (isset($this->arrCategoryIndex[$id]))
			return $this->treeCategories[$this->arrCategoryIndex[$id]];
		else return null;
	}
	
	public function GetTreeListByUrlKey($urlKey)
	{
		$this->GetTreeList($this->treeCategories[$this->arrUrlKeyIndex[$urlKey]]->id);
	}
	
	// returns the hierachical structure for the given category id, as following:
	// displays all its siblings, and all the parents with their siblings
	public function GetTreeList($id, $addCategory = false)
	{

		$arrTreeIds = array(); // array for the tree list		
		if (count($this->arrCategoryIndex) == 0) return null;
		
		if (!isset($this->arrCategoryIndex[$id]))
			return null;
		
		$row = $this->treeCategories[$this->arrCategoryIndex[$id]]; // get category Item for the given category Id	

		$rowParent = $row; // set the parent category item
		
		if ($row->parentRef == null) // if no parent, get the direct categories from current record
		{
			if ($row->DirectChildrenIds == '')
				$arrTreeIds = null;
			else
				$arrTreeIds = explode(',', $row->DirectChildrenIds);
		}
		else
		{
			if ($rowParent->DirectChildrenCount > 0)
				$arrTreeIds = explode(',', $rowParent->DirectChildrenIds); // get children of the searched category
			$currentId = $rowParent->id; // set current category id
			while ($rowParent != null)
			{
				if ($rowParent->parentRef == null) break; // if no more parents, exit while
				$rowParent = $rowParent->parentRef;
				$arrSiblingsIds = explode(',', $rowParent->DirectChildrenIds); // get the siblings of the category
				
				$childPos = 0;
				// search the position of the current category in the siblings
				for ($childIndex = 0; $childIndex < $rowParent->DirectChildrenCount; $childIndex++)
				{
					if ($arrSiblingsIds[$childIndex] == $currentId) 
					{
						$childPos = $childIndex;
						break;
					}
				}
				
				$currentId = $rowParent->id;
				
				// insert the previous found categories ids right after their parent id
				if ( ($childPos >= 0) && ($childPos != count($arrSiblingsIds) - 1) )
				{
					$arrSlice1 = array_slice($arrSiblingsIds, 0, $childPos + 1);
					$arrSlice2 = array_slice($arrSiblingsIds, $childPos + 1);
					
					$arrTreeIds = array_merge($arrSlice1, $arrTreeIds, $arrSlice2);
				}
				else // add the previous categories ids at the end of their parent siblings
					$arrTreeIds = array_merge($arrSiblingsIds, $arrTreeIds);				
			}			
		}
		//echo'<pre>';print_r($arrTreeIds);echo'</pre>';die;	
		// create a list with the category items, from the list of the tree ids
		$arrTreeList = array();
		if ($addCategory)
			array_push($arrTreeList, $this->treeCategories[$this->arrCategoryIndex[$id]]);
		
		if ($arrTreeIds != null)
		{
			foreach ($arrTreeIds as $catId)
			{
				array_push($arrTreeList, $this->treeCategories[$this->arrCategoryIndex[$catId]]);
			}
		}
		
		return $arrTreeList;
	}
	
	// returns the direct children of given category
	public function GetCategoryChildrenList($id)
	{
		if (count($this->arrCategoryIndex) == 0) return null;
		if (!isset($this->arrCategoryIndex[$id]))
			return null;
		
		$row = $this->treeCategories[$this->arrCategoryIndex[$id]]; // get category Item for the given category Id
		
		if ($row->DirectChildrenCount == 0) 
			return null;
		
		$arrTreeList = array();
		$arrTreeIds = explode(',', $row->DirectChildrenIds); // get children of the searched category
		foreach ($arrTreeIds as $catId)
		{
			array_push($arrTreeList, $this->treeCategories[$this->arrCategoryIndex[$catId]]);
		}
		
		return $arrTreeList;
	}
	
	
	// returns all children of given category, in a tree order
	// if skipId specified, will not include the category with that id and all it's children
	// if $includeCategory, it will include the category with $id
	public function GetCategoryTreeRecursive($id, $skipId = null, $includeCategory = false)
	{
		$arrTreeList = array();
		if ($includeCategory)
		{
			$row = $this->treeCategories[$this->arrCategoryIndex[$id]]; // get category Item for the given category Id
			array_push($arrTreeList, $row);
		}
		$this->GetChildrenRecursive($id, $arrTreeList, $skipId);
		
		return $arrTreeList;
	}
	
	// returns all children of given category, in a tree order
	public function GetChildrenRecursive($id, &$arrTreeList, $skipId = null)
	{
		$row = $this->treeCategories[$this->arrCategoryIndex[$id]]; // get category Item for the given category Id
		if ($row->DirectChildrenCount == 0) 
			return;
		
		$arrTreeIds = explode(',', $row->DirectChildrenIds); // get children of the searched category
		
		foreach ($arrTreeIds as $catId)
		{
			$row = $this->treeCategories[$this->arrCategoryIndex[$catId]];
			$row->parentRef = null;
			if ($skipId != null && $row->id == $skipId) continue;
			array_push($arrTreeList, $row);
			if ($row->DirectChildrenCount > 0)
				$this->GetChildrenRecursive($row->id, $arrTreeList);
		}
	}
	
	public function GetBreadcrumbs($id, $articleId = 0, $prefix = 'category')
	{
		$arrItems = array();
		$itemsCount = 0;
		
		$categoryItem = $this->GetCategoryItemById($id);
		while ($categoryItem != null)
		{
			$arrItems[$itemsCount] = $categoryItem;
			$categoryItem = $categoryItem->parentRef;		
			$itemsCount++;
		}
		
		if ($articleId != 0)
		{
			$item = new stdClass();
			$item->name = $this->GetArticleName($articleId);
			$item->url_key = '';
			array_push($arrItems, $item);
			$itemsCount++;
		}
		else 
			$arrItems[0]->url_key = '';
		
		$strCat = '';
		
		if ($itemsCount > 0)
		{
			for ($itemIndex = $itemsCount - 1; $itemIndex >= 0; $itemIndex--)
			{
				if ($arrItems[$itemIndex]->url_key != '')
					$strCat .= '<a href="'.$prefix.'-'.$arrItems[$itemIndex]->url_key.'.html">'.$arrItems[$itemIndex]->name.'</a>';
				else
					$strCat .= '<span>'.$arrItems[$itemIndex]->name.'</span>';
				
				if ($itemIndex < $itemsCount - 1)
					$strCat .= "<em></em>"; // separator					
			}
		}		
		return $strCat;
	}
	
	public function GetParentAtLevel($id, $level)
	{
		$categoryItem = $this->GetCategoryItemById($id);
		$parentItem = null;
		while ($categoryItem != null)
		{
			$categoryItem = $categoryItem->parentRef;
			if ($categoryItem->level == $level)
			{
				$parentItem = $categoryItem;
				break;
			}
		}
		
		return $parentItem;
	}
	
	public function GetCategoriesAsJson()
	{
		$arrCategories = $this->PrepareCategoriesForJson();
		return json_encode($arrCategories);
	}
	
	public function ReadCategoriesFromJson($jsonContent)
	{
		$rowIndex = 0;
		$arrCategories = json_decode($jsonContent);
		$this->treeCategories = array();
		foreach ($arrCategories as &$category)
		{
			$newCat = new CategoryMapItem($category->id, $category->parent_id, $category->name, $category->url_key, $category->order_index, $category->status, 
				$category->display_separate_status);
			$this->CopyObjectAttributes($category, $newCat);
			array_push($this->treeCategories, $newCat);
			
			// rebuild indexes
			$this->arrCategoryIndex[$category->id] = $rowIndex;
			$this->arrUrlKeyIndex[$category->url_key] = $rowIndex;
			$rowIndex++;
		}
		
		foreach ($this->treeCategories as &$category)
		{
			$category->parentRef = $this->GetCategoryItemById($category->parent_id); // set parent ref
		}		
	}
	
	public function PrepareCategoriesForJson()
	{
		
		$arrCategories = array();
		// from current categories, remove parent reference so that will not be added to json
		foreach ($this->treeCategories as &$category)
		{
			$newCat = new CategoryMapItem($category->id, $category->parent_id, $category->name, $category->url_key, $category->order_index, $category->status, 
				$category->display_separate_status );
			$this->CopyObjectAttributes($category, $newCat);
			$newCat->parentRef = null; // remove parent ref
			array_push($arrCategories, $newCat);
		}
		return $arrCategories;
	}
	
	private function CopyObjectAttributes(&$objSrc, &$objDest)
	{
		 foreach (get_object_vars($objSrc) as $key => $value) {
            $objDest->{$key} = $value;
        }
	}

	// DB functions
	
	protected function GetCategories()
	{
		$sql = 'SELECT id,name,parent_id,level,url_key,order_index,status,display_separate_status FROM categories ORDER BY parent_id, order_index, name';
		return $this->dbo->GetRows($sql);
	}

	public function GetArticleName($articleId)
	{
		$sql = 'SELECT name FROM products WHERE id = '.$articleId;
		return $this->dbo->GetFieldValue($sql);
	}
	
	public function GetArticlesCount()
	{
		$sql = 'SELECT category_id, COUNT(*) as cnt, GROUP_CONCAT(product_id) as articlesIds FROM product_categories GROUP BY category_id';
		return $this->dbo->GetRows($sql);
	}

	public function GetArticlesByIds($articlesIds)
	{
		$sql = 'SELECT * FROM products WHERE id IN ('.$articlesIds.') AND status=1';
		return $this->dbo->GetRows($sql);
	}
}

class CategoryMapItem
{
	var $id;
	var $parent_id;
	var $name;
	var $url_key;
	var $order_index;
	var $status;
	var $display_separate_status;
	var $startLevel;
	var $level;
	var $Indent;
	var $parentRef;
	//var $Children;
	var $DirectChildrenIdsArray;
	var $DirectChildrenIds;
	var $DirectChildrenCount;
	var $RecursiveChildrenIdsArray;
	var $RecursiveChildrenIds;
	var $RecursiveChildrenCount;
	var $ArticlesCount;
	
	
	public function __construct($categoryId, $parentId, $name, $urlKey, $orderIndex, $status, $display_separate_status)
	{
		$this->id = $categoryId;
		$this->parent_id = $parentId;
		$this->name = $name;
		$this->url_key = $urlKey;
		$this->DirectChildrenIdsArray = array();
		$this->DirectChildrenIds = '';
		$this->DirectChildrenCount = 0;
		$this->DirectChildrenIds = '';
		$this->RecursiveChildrenIds = '';
		$this->RecursiveChildrenIdsArray = array();
		$this->RecursiveChildrenCount = 0;
		$this->ArticlesCount = 0;
		$this->startLevel = 1; // since products category has level 1, consider startLevel to be 1, to correctly Indent
		$this->parentRef = null;
		$this->level = 0;
		$this->Indent = '';
		$this->order_index = $orderIndex;
		$this->status = $status;
		$this->display_separate_status = $display_separate_status;
	}
	
	// function AddChild($obj)
	// {
		// if ($this->Children == null) $this->Children = array();
		// array_push($this->Children, $obj);
	// }
	
	public function SetIndent()
	{
		for ($levelIndex = $this->startLevel; $levelIndex < $this->level; $levelIndex++)
			$this->Indent .= '&nbsp; &nbsp; &nbsp;';
	}
}
?>