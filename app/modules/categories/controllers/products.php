<?php
class Products extends AdminController
{
	private $productsModel;
	private $categoriesModel;
	private $productsCategoriesModel;
	
	function __construct()
	{
		parent::__construct();
		$this->module = 'categories';
		$this->pageId = $this->module;
		$this->translationPrefix = $this->module;
		
		$this->Auth();
		$this->productsModel = $this->LoadModel('products');
		$this->categoriesModel = $this->LoadModel('categories');
		$this->productsCategoriesModel = $this->LoadModel('product_categories');
		$this->IncludeClasses(array('system/lib/enum/abstract_enum.php'));
		//$this->LoadEnum('currency','currency');
		//$this->LoadEnum('attributes');
		$this->LoadEnum('product', 'categories');
		$this->LoadEnum('category', 'categories');
	}
	
	function HandleAjaxRequest()
	{
		$data = $this->GetAjaxJson();
		if ($data == null) {
			return;
		}
		$ajaxAction = $data['ajaxAction'];
		unset($data['ajaxAction']);

		$response = null;
		switch ($ajaxAction)
		{
			case 'VerifyProduct': 
				$response = $this->VerifyProduct($data);
			break;
		}
		
		if ($response != null)
		{
			$this->WriteResponse($response);
			die();
		}
	}
	
	function VerifyProduct(&$data)
	{

		$name = $this->categoriesModel->GetSafeValue($data['name']);
		$urlKey = $this->categoriesModel->GetSafeValue($data['urlKey']);
		$editId = (int)$data['editId'];
		
		$response = new stdClass();
		$response->categoryExists = ($this->productsModel->GetRecordExists('name', $name, $editId))?1:0;
		$response->urlKeyExists = ($this->productsModel->GetRecordExists('url_key', $urlKey, $editId))?1:0;
		
		return $response;
	}
	
	
	function GetViewData($query = '')
	{
		$this->HandleAjaxRequest();
		
		$dataSearch = $this->GetQueryItems($query, array('search', 'cid'));
		
		// page initializations
		array_push($this->webpage->ScriptsFooter, 'lib/gridsort/grid_sort.js', 'lib/paging/paging.js',_JS_APPLICATION_FOLDER.$this->module.'/product_list.js');
		parent::SetWebpageData($this->pageId);
		//$this->webpage->SearchBlock = $this->GetGeneralBlockPath('search_block');
		$category = null;
		if (isset($dataSearch->cid)){
			$category = $this->categoriesModel->GetRecordById($dataSearch->cid);
			if ($category) {
				$this->webpage->PageHeadTitle .= ' '.$category->name;
			}
		}
		
		// if search
		$this->webpage->RedirectPostToGet($this->webpage->PageUrl, 'actionSearch', '1', array('txtSearch'), array('search'));
		
		$this->IncludeClasses(array('system/lib/grid/sort_grid.php'));
		$sortGridAdmin = $this->LoadUserLibrary('sort_grid', 'sort_grid_admin');
		$dataSort = $sortGridAdmin->AddSort($this->queryList, $this->pageId, 'name', 'sc');
	
		$form = new Form();
		
		$formData = $form->data;
		$this->ProcessFormAction($formData);
		
		//$indexedCategories = $this->GetIndexedCategories();
		$productsCategories = $this->GetProductsCategories();
		//echo'<pre>';print_r($productsCategories);echo'</pre>';die;
		$data = new stdClass();
		$recordsCount = $this->productsModel->GetRecordsListCount($dataSearch);
		$limit = $this->GetPagingCode($data, $recordsCount);
		$data->rows = $this->productsModel->GetRecordsList($dataSearch, $dataSort->{$this->pageId}->Sql, $limit);

		//$this->FormatRows($data->rows, $indexedCategories);
		$this->FormatRows($data->rows, $productsCategories);
		
		$data->dataSort = $dataSort;
		$data->editCategory = ($category)? '/cid='.$category->id:'';
		// $data->producerList = $this->GetProducerList($formData->producer_id);
		// $data->currencyList = $this->GetCurrencyList($formData->currency_id);
		
		$this->webpage->PageUrl = $this->webpage->AppendQueryParams($this->webpage->PageUrl);
		
		// print_r($data);
		// die();
				
		return $data;
	}
	
	function GetProducerList($selectedId)
	{
		$producersModel = $this->LoadModel('producers');
		$rows = $producersModel->GetRecordsForDropdown();
		return HtmlControls::GenerateDropDownList($rows, 'id', 'name', $selectedId);
	}
	
	function GetCategoryList($selectedRows, $categoriesValuesSelected=false)
	{
		$categoriesModel = $this->LoadModel('categories');
		$rows = $categoriesModel->GetRecordsForDropdown();
		
		
		if  (!$selectedRows) {
			$selectedValues = null;
		}
		else{
			$selectedValues = array();
			
			foreach ($selectedRows as &$row) {
				array_push($selectedValues, $row->category_id);
			}
		}
		if  ($categoriesValuesSelected) {
			$arrCategValues = explode(',', $categoriesValuesSelected);
			if($arrCategValues){
				$selectedValues = $arrCategValues;
			}
			else{
				$selectedValues = array();
				array_push($selectedValues, $categoriesValuesSelected);
			}
				
		}


			
		
		//echo'<pre>';print_r($selectedValues);echo'</pre>';die;
		
		return HtmlControls::GenerateCheckListFromArray($rows, 'id', 'name', $selectedValues, 'chkCategoryList');
	}
	
	function GetSizeList($selectedValues)
	{
		$attributesModel = $this->LoadModel('attributes');
		$rows = $attributesModel->GetRecordsForDropdown(Attributes::Size);
		return HtmlControls::GenerateCheckListFromArray($rows, 'id', 'value', $selectedValues, 'chkSizeList');
	}
	
	function GetColorList($selectedValues)
	{
		$attributesModel = $this->LoadModel('attributes');
		$rows = $attributesModel->GetRecordsForDropdown(Attributes::Color);
		return HtmlControls::GenerateCheckListFromArray($rows, 'id', 'value', $selectedValues, 'chkColorList');
	}
	
	function GetCurrencyList($selectedId)
	{
		$currencyController = $this->LoadController('currency', 'currency');
		$rows = $currencyController->GetCurrencyList($this->trans);
		return HtmlControls::GenerateDropDownList($rows, 'key', 'val', $selectedId);
	}
	
	function GetPagingCode(&$data, $recordsCount)
	{
		$paging = $this->LoadLibrary('grid', 'paging');
		$paging->SetDefaultOptions($this->queryList);
		$paging->allowedQueries = array('search','sex','email','user','fname','sc');
		
		$itemsPerPage = ($paging->ddlItemsPageSelectedValue != '')?$paging->ddlItemsPageSelectedValue:_ITEMS_PER_PAGE;
		$paging->SetPaging($recordsCount, $itemsPerPage);

		$data->PagingHtml = $paging->GetPagingCode($this->webpage->PageUrl);
		
		$data->rowIndex = ($paging->selectedPageIndex - 1) * $paging->itemsPerPage;
		
		return $paging->limit;
	}

	function FormatSelectedCategoriesList($rows)
	{
		if ($rows == null) {
			return;
		}
		
		$categoriesString = '';
		foreach ($rows as $categ) {
			$categoriesString .= ','.$categ->category_id;
		}
		if ($categoriesString) {
			$categoriesString = substr($categoriesString, 1);
		}
		
		return $categoriesString;
	}

	
	function FormatRows(&$rows, &$productsCategories)
	{
		if ($rows == null) {
			return;
		}
		
		foreach ($rows as &$row)
		{
			$row->imageLink = '<a href="'.$row->default_image.'" target="_blank">'.$row->default_image.'</a>';
			// $row->currency = CurrencyItems::GetName($row->currency_id, $this->translation, 'currency.');
			//echo'<pre>';print_r($row);echo'</pre>';die;
			$row->categories = '';
			$row->categories = $this->GetProductCategories($row->id, $productsCategories);
			$row->statusEnum = ProductStatus::GetName($row->status, $this->trans, 'products.status.');
		}
	}
	
	function GetProductCategories($productId, $productsCategories)
	{
		$categories = '';
		if($productsCategories){
			foreach ($productsCategories as $productCategory) {
				if ($productCategory->product_id==$productId) {
					$categories .= ','.$productCategory->name;
				}
			}
			if ($categories) {
				$categories = substr($categories, 1);
			}
		}
		return $categories;
	}
	
	function GetProductsCategories(){
		return $this->productsCategoriesModel->GetProductsCategories();
	}

	function GetIndexedCategories()
	{
		$rows = $this->categoriesModel->GetRecordsForDropdown();
		if ($rows == null) {
			return null;
		}
		$categories = array();
		foreach ($rows as &$row)
		{
			$categories[$row->id] = $row;
		}
		return $categories;
	}
	
	
	function GetEditData($query = '')
	{
		$dataSearch = $this->GetQueryItems($query, array('id', 'cid'));
		$editId = (int)$dataSearch->id;
		$categoryId = $dataSearch->cid;
		// page initializations
		array_push($this->webpage->ScriptsFooter, 'lib/validator/jquery.validate.min.js', 'lib/wrappers/validator/validator.js', 'lib/tinymce/tinymce.min.js', 'lib/wrappers/tinymce/tinymce.js',_JS_APPLICATION_FOLDER.$this->module.'/product_edit.js');
		parent::SetWebpageData('product_edit', $this->pageId);

		$form = new Form('Save'); // get data from post
		$formData = $form->data;
		$formData->EditId = $editId;
		
		$formData->chkCategoryList = (isset($formData->chkCategoryList)) ? $formData->chkCategoryList : '';
		$this->ProcessFormAction($formData);
		
		$this->webpage->FormHtml .= HtmlControls::GenerateHiddenField('sys_EditId', $formData->EditId); // add the hidden edit id
		
		// $this->webpage->SetMessage('test messages', 'success');
		
		if (!$form->IsPostback())
			if (!$this->productsModel->GetFormData($formData->EditId, $formData))
				Session::SetFlashMessage($this->trans[$this->translationPrefix.'.item_not_exists'], 'warning', $this->webpage->PageReturnUrl);
		//echo'<pre>';print_r($formData);echo'</pre>';die;
		if($formData->txtStatus==''){
				$formData->txtStatus = 1; //set default status for product display
		}
		if($formData->txtAmountUnit==''){
				$formData->txtAmountUnit = 'g'; //set default txtAmountUnit for product display
		}
		
		
			
		if ($formData->EditId == 0) {
			$this->webpage->PageHeadTitle = $this->trans[$this->translationPrefix.'.new_item'];
		}
		else {
			$this->webpage->PageHeadTitle = $this->trans[$this->translationPrefix.'.edit_item'].' :'.$formData->txtName;
		}			
		$data = $formData;
		$data->amountUnitListContent = HtmlControls::GenerateDropDownListStrings(array('grame', 'mililitri'), array('g', 'ml'), $formData->txtAmountUnit);
		//echo'<pre>';print_r($formData);echo'</pre>';die;
		
		// $data->producerList = $this->GetProducerList($formData->ddlProducerId);
		//echo'<pre>';print_r($formData->chkCategoryList);echo'</pre>';die;
		$data->selectedCategoriesList = $this->FormatSelectedCategoriesList($formData->chkCategoryList);
		$data->categoryList = $this->GetCategoryList($formData->chkCategoryList, $categoryId);

		$this->DistributeAttributes($data, $formData->chkAttributeList);
		$data->sizeList = $this->GetSizeList($formData->chkSizeList);
		//echo'<pre>';print_r($data);echo'</pre>';die;
		return $data;
	}
	
	function ProcessFormAction(&$formData)
	{
		//print_r($formData);die;
		switch($formData->Action)
		{
			case 'Save':
				if(!isset($formData->txtStatus)){
					$formData->txtStatus = 0;
				}
				//echo'<pre>';print_r($formData);echo'</pre>';die;
				$productId = $this->productsModel->SaveRecord($formData, true);
				if ($productId != 0 && isset($formData->chkCategoryList))
				{
					//echo'<pre>';print_r($formData);echo'</pre>';die;
					$this->SaveProductCategories($productId, $formData->chkCategoryList);
					//$this->SaveProductAttributes($productId, Attributes::Size, 'size_ids', $formData->chkSizeList);
					Session::SetFlashMessage($this->trans[$this->translationPrefix.'.save_success'], 'success', $this->webpage->PageUrl.'/id='.$productId);
				}
				else
					$this->webpage->SetMessage($this->trans['general.save_error'], 'error');
			break;
			case 'Delete':
				$id = (int)$formData->Params;
				//echo'<pre>';print_r($formData);echo'</pre>';die;
				$this->productsModel->DeleteRecord($id);
				$this->productsCategoriesModel->DeleteProductCategories($id);
				Session::SetFlashMessage($this->trans[$this->translationPrefix.'.delete_success'], 'success', $this->webpage->PageUrl);
			break;
			case 'DeleteSelected':
			
				$selectedRecords = $this->GetSelectedRecords();
				//echo'<pre>';print_r($selectedRecords);echo'</pre>';die;
				if ($selectedRecords != '')
				{
					$this->productsModel->DeleteSelectedRecords($selectedRecords);
					$this->productsCategoriesModel->DeleteProductCategories($selectedRecords);
					Session::SetFlashMessage($this->trans[$this->translationPrefix.'.delete_selected_success'], 'success', $this->webpage->PageUrl);
				}
				else $this->webpage->SetMessage($this->trans[$this->translationPrefix.'.error_selected_elements'], 'error');
			break;
			case 'SortColumn':
				$this->webpage->RedirectPostToGet($this->webpage->PageUrl, 'sys_Action', 'SortColumn', array('hidSortColumn_'.$this->pageId), array('sc'));
			break;
		}
	}

	function GetImagePath($fileName)
	{
		$filePath = $this->GetBasePath()._PRODUCTS_PATH;
		if ($fileName != '')
		{
			$filename = $filePath.$fileName;
			if (file_exists($filename)) {
				$fileName = _SITE_RELATIVE_URL._PRODUCTS_PATH.$fileName; 
			}
		}
		return $fileName;
	}
	
	function CompleteFormDataPost(&$formData, &$row)
	{
		$formData->txtFile = '';
	}
	
	function DistributeAttributes(&$data, &$rows)
	{
		if (!$rows) {
			$data->chkSizeList = null;
			$data->chkColorList = null;
		}
		else {
			$data->chkSizeList = array();
			$data->chkColorList = array();
			
			foreach ($rows as &$row) {
				switch ($row->attribute_id) {
					case Attributes::Size: array_push($data->chkSizeList, $row->attribute_value_id); break;
					case Attributes::Color: array_push($data->chkColorList, $row->attribute_value_id); break;
				}
			}
		}
	}
	
	private function SaveProductCategories($productId, $categoryIdList)
	{
		$rows = null;
		if ($categoryIdList) {
			$rows = array();
			
			foreach ($categoryIdList as &$id) {
				$row = array($productId, $id);
				array_push($rows, $row);
			}
		}
		$this->productsModel->SaveProductCategories($productId, array('product_id', 'category_id'), $rows);
	}
	
	private function SaveProductAttributes($productId, $attributeId, $attributeFieldName, $attributeValueIdList)
	{
		$rows = null;
		$model = $this->LoadModel('product_attributes');
		if ($attributeValueIdList) {
			$rows = array();
			
			foreach ($attributeValueIdList as &$id) {
				$row = array($productId, $attributeId, $id);
				array_push($rows, $row);
			}
		}
		$model->SaveProductAttributes($productId, $attributeId, array('product_id', 'attribute_id', 'attribute_value_id'), $rows);
		$attributeValueIds = ($attributeValueIdList) ? implode(',', $attributeValueIdList) : '';
		$model->SaveProductAttributesSummary($productId, $attributeFieldName, $attributeValueIds);
	}
}
?>
