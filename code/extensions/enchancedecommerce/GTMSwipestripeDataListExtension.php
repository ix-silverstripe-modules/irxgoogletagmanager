<?php
/**
 *
 * @author  Jason Zhang <jason.zhang@internetrix.com.au>
 * @package  irxgoogletagmanager
 *
 **/

class GTMSwipestripeDataListExtension extends DataExtension {
	
	public function GenerateProductsGTMDataLayer($listName = 'Category'){
		
		$dataSet = $this->owner->getIterator();
		
		if($dataSet->count()){
			$productDataArray = array();
			
			//get the offset number for this query. 
			//we need this number for setting 'position' value
			$listQuery 	= $this->owner->dataQuery()->query();
			$queryLimit	= $listQuery->getLimit();
			$position 	= 1;
			if(is_array($queryLimit) && isset($queryLimit['start'])){
				$start = $queryLimit['start'] ? $queryLimit['start'] + 1 : 1;
			}

			foreach ($dataSet as $productDO){
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