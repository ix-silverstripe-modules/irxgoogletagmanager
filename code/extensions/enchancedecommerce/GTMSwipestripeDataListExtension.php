<?php
/**
 *
 * @author  Jason Zhang <jason.zhang@internetrix.com.au>
 * @package  irxgoogletagmanager
 *
 **/

class GTMSwipestripeDataListExtension extends DataExtension {
	
	/**
	 * Set a master list name which is a global list name.
	 * It will overwritten the one set in template or as PHP function parameter.
	 */
	public function setMasterListName($masterListName){
		
		return $this->owner->setDataQueryParam('GTM.MasterListName', $masterListName);
		
	}
	
	public function getMasterListName(){
		return $this->owner->dataQuery()->getQueryParam('GTM.MasterListName');
	}
	
	public function GenerateProductsGTMDataLayer($listName = 'Category', $position = 1){
		
		//check if there is a master list name.
		//master list name is useful for ajax request.
		$dataQuery = $this->owner->dataQuery();
		$masterListName = $dataQuery->getQueryParam('GTM.MasterListName');
		if($masterListName !== null){
			$listName = $masterListName;
		}
		
		$dataList = $this->owner;
		
		//get default page limits
		$request 	= Controller::curr()->request;
		$start 		= $request->getVar('start');
		$start		= ($start === null) ? 0 : $start;
		$length 	= $request->getVar('length');
		if($length === null){
			$length = $dataQuery->getQueryParam('GTM.PageLength');
			if( ! $length){
				$length = 9;
			}
		}
		
		$dataList = $dataList->limit($length, $start);

		$position = $start ? ($start + 1) : 1;
		
		if($dataList->Count()){
			$productDataArray = array();
			
			foreach ($dataList as $productDO){
				$data = $productDO->getImpressionData(false, $listName, $position);
				if( ! empty($data)){
					$productDataArray[] = $data;
				}
				$position ++;
			}
			
			if( ! empty($productDataArray) && Controller::curr() instanceof Page_Controller){
				
				$shopConfig = ShopConfig::current_shop_config();
				$currencyCode = $shopConfig->BaseCurrency ? $shopConfig->BaseCurrency : Config::inst()->get('ShopConfig', 'GTMCurrencyCode');
				
				//generate data. e.g. https://developers.google.com/tag-manager/enhanced-ecommerce
				Controller::curr()->insertGTMDataLayer(array(
					'event' => 'irx.newProductsImpressions',
					'IRXProductsImpressions' => array(
						'ecommerce' => array(
							'currencyCode' 	=> $currencyCode,
							'impressions' 	=> $productDataArray
						)
					)
				));
			}
		}
		
		Session::set('TMPListName', $listName);
		
		return $this->owner;
	}
	
	
	
}