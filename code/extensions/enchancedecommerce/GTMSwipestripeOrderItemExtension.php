<?php
/**
 *
 * @author  Jason Zhang <jason.zhang@internetrix.com.au>
 * @package  irxgoogletagmanager
 *
 **/

class GTMSwipestripeOrderItemExtension extends DataExtension {
	
	public function generateProductItemArray($quantity = null){
		
		$item = $this->owner;
		
		if($quantity === null){
			$quantity = $item->Quantity;
		}
		
		$product = $item->Product();
		
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

		return $impArray;
	}
	
	
	public function generateAddOrRemoveDataLayerArray($action, $quantity = null){
		//$action should be 'add' or 'remove'
		
		$shopConfig = ShopConfig::current_shop_config();
		$currencyCode = $shopConfig->BaseCurrency ? $shopConfig->BaseCurrency : Config::inst()->get('ShopConfig', 'GTMCurrencyCode');
		
		$currentItemArray = $this->generateProductItemArray($quantity);
		
		if($action == 'add'){
			$shoppingEventFunctionName 	= 'newShoppingCartChangeAdd';
			$shoppingActionFunctionName = 'IRXShoppingCartChangeAdd'
		}elseif($action == 'remove'){
			$shoppingEventFunctionName 	= 'newShoppingCartChangeRemove';
			$shoppingActionFunctionName = 'IRXShoppingCartChangeRemove'
		}else{
			$shoppingEventFunctionName 	= 'newShoppingCartChanged';
			$shoppingActionFunctionName = 'IRXShoppingCartChange'
		}
		
		return array(
			'event' => 'irx.' . $shoppingEventFunctionName,
			$shoppingActionFunctionName => array(
				'ecommerce' => array(
					'currencyCode' 	=> $currencyCode,
					$action => array(
						'products' => array(
							$currentItemArray
						)			
					)
				)
			)
		);
	}
	
	/**
	 * for template
	 */
	public function getGTMProductRemoveAttr(){
		$array = array();
		
		$array[] = 'data-gtmaction="product-remove"';
		$array[] = sprintf('data-data2push=\'%s\'', Convert::array2json($this->owner->generateAddOrRemoveDataLayerArray('remove')));
		
		return implode(' ', $array);
	}
	
	/**
	 * for template
	 */
	public function getGTMProductAddAttr(){
		$array = array();
		
		$array[] = 'data-gtmaction="product-add"';
		$array[] = sprintf('data-data2push=\'%s\'', Convert::array2json($this->owner->generateAddOrRemoveDataLayerArray('add')));
		
		return implode(' ', $array);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function GTMVariationNames(){
	
		if ($variation = $this->owner->Variation()){
				
			$vOptions = $variation->Options();
	
				
			if ($vOptions && $vOptions->exists()) foreach ($vOptions as $option) {
				$temp[] = $option->Title . '(' .  $option->Attribute()->Title . ')';
			}
				
			if( ! empty($temp)){
	
				$temp = Convert::raw2js($temp);
	
				return implode(', ', $temp);
			}
		}
	
	}
	
	
	
	
	
	
	
	
	
}