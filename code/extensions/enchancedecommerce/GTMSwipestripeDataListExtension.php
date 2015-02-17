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
	
	public function GenerateProductsGTMDataLayer($listName = 'Category', $position = 1){
		
		//check if there is a master list name.
		//master list name is useful for ajax request.
		$dataQuery = $this->owner->dataQuery();
		$masterListName = $dataQuery->getQueryParam('GTM.MasterListName');
		if($masterListName !== null){
			$listName = $masterListName;
		}
		
		if($this->owner->Count()){
			$productDataArray = array();
			
			//get the offset number for this query. 
			//we need this number for setting 'position' value
// 			$listQuery 	= $this->owner->dataQuery()->query();
// 			$queryLimit	= $listQuery->getLimit();

// 			if(is_array($queryLimit) && isset($queryLimit['start'])){
// 				$position = $queryLimit['start'] ? $queryLimit['start'] + 1 : 1;
// 			}

			foreach ($this->owner as $productDO){
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

		return $this->owner;
	}
	
	
	
}