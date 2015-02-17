<?php
/**
 *
 * @author  Jason Zhang <jason.zhang@internetrix.com.au>
 * @package  irxgoogletagmanager
 *
 **/

class GTMSwipestripeOrderExtension extends DataExtension {
	
	protected $cachedItemsArray = null;
	
	public function updateOrderAddItem(&$item, $quantity = null){

		$DataLayerArray = $item->generateAddOrRemoveDataLayerArray('add', $quantity);
	
		Controller::curr()->insertGTMDataLayer($DataLayerArray);
		
		return;
	}
	
	
	public function checkoutStepGTMDataArray($stepNumber, $option = null){
		
		$productsArray = array();
		
		if($this->cachedItemsArray !== null){
			$productsArray = $this->cachedItemsArray;
		}else{
			$order = Cart::get_current_order();
			if($order && $order->ID){
				$items = $order->Items();
				foreach ($items as $item){
					$productsArray[] = $item->generateProductItemArray();
				}
			}
		}
		
		//TODO should we return empty product array when there is no product added?
// 		if(empty($productsArray)){
// 			return false;
// 		}
		
		$dataArray = array(
			'event' => 'irx.newCheckoutStep',
			'IRXCheckoutStep' => array(
				'ecommerce' => array(
					'checkout' 	=> array(
						'actionField' => array('step' => $stepNumber, 'option' => $option),
						'products' => $productsArray
					)
				)
			)
		);
		
		if(Member::currentUserID()){
			$dataArray['userID'] = Member::currentUserID();
		}
		
		return $dataArray;
	}
	
	
	public function GTMCheckoutDataAttr($stepNumber, $option = null){
		$array = array();
		
		$array[] = 'data-gtmaction="checkout-update"';
		$array[] = 'data-gtmstep="'.$stepNumber.'"';
		$array[] = sprintf('data-data2push=\'%s\'', Convert::array2json($this->owner->checkoutStepGTMDataArray($stepNumber, $option)));
		
		return implode(' ', $array);
	}
	
}