<?php
class AppImageEdit extends AdminController
{
	protected $appImagesModel;
	
	function __construct()
	{
		parent::__construct();
		$this->module = 'pictures';
		$this->pageId = 'app_image_edit';
		$this->translationPrefix = 'app_images';
		
		$this->Auth();
		$this->appImagesModel = $this->LoadModel('app_pictures');
	}
	
	function HandleAjaxRequest()
	{
		$data = $this->GetAjaxJson();
		$ajaxAction = $data['ajaxAction'];
		//echo'<pre>';print_r($data);echo'</pre>';die;
		unset($data['ajaxAction']);
		
		$response = null;
		switch ($ajaxAction)
		{
			case 'save_order': 
				$saveId = $this->UpdateImagesOrder((int)$data['productId'], explode(',', $data['img_ids']));
				$this->UpdateProductDefaultImage((int)$data['productId']);
				$message = $this->trans[$this->translationPrefix.'.order_save_success'];
				$response = $this->GetDefaultResponse($message, $saveId);
			break;
		}
		
		if ($response != null)
		{
			$this->WriteResponse($response);
			die();
		}
	}
	
	function GetEditData($query = '')
	{
		$this->HandleAjaxRequest();
		
		$dataSearch = $this->GetQueryItems($query, array('id'));
		$editId = (int)$dataSearch->id;
		array_push(
			$this->webpage->StyleSheets,
			'jquery/jquery-ui-1.8.17.custom.css',
			'jquery/jquery-ui.css'
			//'tooltip/jquery.tooltip.css'
		);
		array_push(
			$this->webpage->ScriptsFooter,
			'lib/jquery/jquery-ui-1.8.17.custom.min.js',
			'lib/jquery/jquery-ui.min.js'
			//'lib/tooltip/jquery.tooltip.min.js'
		);

		parent::SetWebpageData($this->pageId);
		
		$form = new Form('SaveAppImagesMeta');
		$formData = $form->data;
		$formData->EditId = $editId;
		$formData->metaEditId = $this->appImagesModel->GetAppImageMetaIdByAppImageId($formData->EditId);
		//echo'<pre>';print_r($formData->EditId);echo'</pre>';
		if ($formData->EditId == 0) {
			Session::SetFlashMessage($this->trans['app_images.no_images'], _SITE_RELATIVE_URL.'app_images');
		}
		$this->ProcessFormAction($formData);
		if (!$form->IsPostback()){
			if (!$this->appImagesModel->GetFormData($formData->metaEditId, $formData)){
				Session::SetFlashMessage($this->trans[$this->translationPrefix.'.item_not_exists'], 'warning', $this->webpage->PageReturnUrl);
			}
		}
		
		$data = $formData;
		$data->row = $this->appImagesModel->GetAppImageById($formData->EditId);
		//$data->advancedSearchBlock = $this->GetBlockPath($this->pageId.'_advanced_search_block');
		//echo'<pre>';print_r($data);echo'</pre>';die;
		
		
		$data->appCategoryName = preg_replace('/-\d+\.'. $data->row->extension.'/', '', $data->row->file);
		//echo'<pre>';print_r($data->appCategoryName);echo'</pre>';die;
		$this->webpage->PageHeadTitle = $this->trans['app_image_edit.page_title'].' '.ucfirst($data->row->file);
		
		//echo'<pre>';print_r($data->appCategoriesListContent);echo'</pre>';die;
	

		
		$this->FormatRows($data->appCategoryName, $data->row);
		//echo'<pre>';print_r($data);echo'</pre>';die;
		//$data->pageId  = 'app_images';
		$this->webpage->PageUrl = $this->webpage->AppendQueryParams($this->webpage->PageUrl);
		
		return $data;
	}			
	
	function FormatRows($categoryName, &$row)
	{
		if ($row == null) {
			return;
		}
		$categoryName = StringUtils::UrlTitle($categoryName);
		$filePath = _SITE_RELATIVE_URL.'app_thumb/'.$categoryName.'-';
		$row->thumb = $filePath.$row->id.'-120x120.'.$row->extension;
		$row->thumb_med = $filePath.$row->id.'-320x240.'.$row->extension;
		
	}

	function ProcessFormAction(&$formData)
	{
		//echo'<pre>';print_r($formData);echo'</pre>';die;
		switch($formData->Action)
		{
			case 'FilterResults':
				$this->webpage->RedirectPostToGet($this->webpage->PageUrl, 'sys_Action', 'FilterResults',
				array('appCategoryId'), array('appCategoryId'));
			break;
			case 'SaveAppImagesMeta':
				//echo'<pre>';print_r($formData);echo'</pre>';die;
				$mapping = $this->SetMapping($formData);
				$appImageMetaId = $this->appImagesModel->UpdateAppImagesMeta($formData->EditId, $formData->appCategoryId, $mapping);
				if ($appImageMetaId != 0)
				{
					//echo'<pre>';print_r($formData);echo'</pre>';die;
					Session::SetFlashMessage($this->trans[$this->translationPrefix.'.save_success'], 'success', $this->webpage->PageUrl.'/id='.$formData->EditId);
				}
			break;
		}
	}

	function SetMapping($formData){
		$mapping = array(
			//'image_alt'=>$formData->txtAlt,
			'image_alt'=>$formData->txtTitle,
			'image_title'=>$formData->txtTitle,
			'image_caption'=>$formData->txtCaption,
			'image_description'=>$formData->txtDescription,
			//'image_button_link_text'=>$formData->txtButtonText,
			//'image_button_link_href'=>$formData->txtButtonHref,
			'order_index'=>$formData->txtOrder);
		$this->appImagesModel->MakeSafeData($mapping);
		return $mapping;
	}
	
}
?>
