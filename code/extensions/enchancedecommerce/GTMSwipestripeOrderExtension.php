<?php
/**
 *
 * @author  Jason Zhang <jason.zhang@internetrix.com.au>
 * @package  irxgoogletagmanager
 *
 **/

class GTMSwipestripeOrderExtension extends DataExtension {
	
	public function updateOrderAddItem(&$item, $quantity = null){
		if($quantity === null){
			//if $qty is not set, this is a newly added item and qty is set in Item DataObject.
			$quantity = $item->Quantity;
		}
		
		$product = $item->Product();
		
		if($product && $product->ID){
			$impArray 	= array();
			$impArray['name'] 		= $product->Title;
			$impArray['id'] 		= $product->ID;
			$impArray['price'] 		= $item->UnitAmount()->getAmount();
			$impArray['brand'] 		= $product->getProductBrand();
			$impArray['category'] 	= $product->getNestedCategoryNameForGTM();
			$impArray['quantity'] 	= $quantity;
			
			//check variation.
			$variantDO = $item->Variation();
			if($variantDO && $variantDO->ID){
				$vOptions = $variantDO->Options();
				
				if ($vOptions && $vOptions->exists()) foreach ($vOptions as $option) {
					$temp[] = $option->Title . '(' .  $option->Attribute()->Title . ')';
				}
					
				if( ! empty($temp)){
					$impArray['variant'] 	= implode(', ', $temp);
				}
			}
			
			$shopConfig = ShopConfig::current_shop_config();
			$currencyCode = $shopConfig->BaseCurrency ? $shopConfig->BaseCurrency : Config::inst()->get('ShopConfig', 'GTMCurrencyCode');

			Controller::curr()->insertGTMDataLayer(array(
				'event' => 'irx.newShoppingCartChanged',
				'IRXShoppingCartChange' => array(
					'ecommerce' => array(
						'currencyCode' 	=> $currencyCode,
						'add' => array(
							'products' => $impArray			
						)
					)
				)
			));
		}
		
	}
	
}