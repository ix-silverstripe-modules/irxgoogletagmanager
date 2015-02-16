<?php
/**
 *
 * @author  Jason Zhang <jason.zhang@internetrix.com.au>
 * @package  irxgoogletagmanager
 *
 **/

class GTMSwipestripeProductExtension extends DataExtension {
	
	/**
	 * Global brand name for GTM Data. Please define your brand name in your mysite/_config yaml file.
	 * 
	 * @var string
	 */
	private static $GTMBrand = '';
	
	private static $db = array(
		'GTMBrand' => 'Varchar(64)'	
	);
	
	public function getCurrentCategory(){
		
		$currentCategoryID = Session::get('GTMCurrentCategory');
		
		if($currentCategoryID){
			$currentCategory = ProductCategory::get()->byID($currentCategoryID);
		}
		
		if( ! $currentCategoryID || ! $currentCategory){
			$currentCategory = ProductListPage::get()->first();
		}
		
		return $currentCategory;
	}
	
	public function getNestedCategoryNameForGTM($glue = ' / '){
		
		$currentCategory = $this->owner->getCurrentCategory();
		
		$parents = $currentCategory->getAncestors();
		if($parents && $parents->Count()){
			$parentsArray = $parents->map('ID', 'Title');
			
			$parentsArray = array_reverse($parentsArray);
			
			return implode($glue, $parentsArray);
		}
		
		return $currentCategory->Title;
	}
	
	
	public function getProductBrand(){
		if($this->owner->GTMBrand){
			return $this->owner->GTMBrand;
		}
		
		return Config::inst()->get('Product', 'GTMBrand');
	}
	
	
	
	
	
	/**
	 * Product Details view.
	 * This DataLayer will be generated each time product details are presented.
	 * 
	 * @param Controller $controller
	 */
	public function contentcontrollerInit($controller){
		if($controller instanceof Product_Controller){
			$controller->insertGTMDataLayer(array(
				'event' => 'irx.newProductDetails',
				'IRXProductDetails' => array(
					'ecommerce' => array(
						'details' 	=> array(
							'actionField' => array('list' => 'Category'),
							'products' => $this->owner->getImpressionData()
						)
					)
				)
			));
		}
	}
	
	
	public function getImpressionData($toJSON = false, $listName = 'Category', $position = false){
		//check if there is Alternative method for this function.
		//you can define Alternative method in your project extension.
		//'getImpressionDataAlternative'
		$alternativeMethodName = __FUNCTION__ . 'Alternative';
		if($this->owner->hasMethod($alternativeMethodName)){
			//PS. in your Alternative method, please use the same logic to return the data.
			return $this->owner->$alternativeMethodName($toJSON, $listName, $position);
		}
		
		$impArray 	= array();
		$impArray['name'] 		= $this->owner->Title;
		$impArray['id'] 		= $this->owner->ID;
		$impArray['price'] 		= $this->owner->Amount();
		$impArray['brand'] 		= $this->owner->getProductBrand();
		$impArray['category'] 	= $this->owner->getNestedCategoryNameForGTM();
		$impArray['list'] 		= $listName;
		
		//check position - optional
		if($position > 0){
			$impArray['position'] = $position;
		}
		
		//check variation - optional
		//TODO
		
		return $toJSON ? Convert::array2json($impArray) : $impArray;
	}
	
	
	public function getProductClickGTMAttr($listName = 'Category'){
		$array = array();
		
		$array[] = sprintf('data-productName="%s"', $this->owner->Title);
		$array[] = sprintf('data-productID="%d"', $this->owner->ID);
		$array[] = sprintf('data-category="%s"', $this->owner->getNestedCategoryNameForGTM());
		$array[] = sprintf('data-list="%s"', $listName);
		
		return implode(' ', $array);
	}
	
}