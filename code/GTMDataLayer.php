<?php
class GTMDataLayer extends ViewableData {
	
	private static $data_session_name = 'GTMDataLayerData';
	
	private static $data = array();
	
	public static function hasData(){
		return ! empty(self::$data);
	}
	
	/**
	 * @return array
	 */
	public static function getDataForTemplate(){
		$layerData = self::$data;
		
		self::$data = array();
		
		return $layerData;
	}
	
	/**
	 * call this function to save data into session before redirecting to another page.
	 */
	public static function saveDataIntoSession(){
		if( ! empty(self::$data)){
			Session::set(self::config()->data_session_name, serialize(self::$data));
			Session::save();
		}
	}
	
	public static function loadDataFromSession(){
		$data = Session::get(self::config()->data_session_name);
		if($data){
			$data = unserialize($data);
			if( ! empty($data)){
				Session::clear(self::config()->data_session_name);
				Session::save();
				self::$data = $data;
			}
		}
	}
	
	/**
	 * @param array $data
	 * 
	 * e.g.
	 * 
	 *     
	 *     
		$data = [
	        'transactionStatus': '[TRANSACTION_STATUS]',
	        'transactionId': '[SERVER_SIDE_TRANSACTION_ID]',
	        'transactionAffiliation': '[PARTNER_OR_STORE-_AFFILIATION_RELATIONSHIP]',
	        'transactionTotal': [SERVER_SIDE_TRANSACTION_TOTAL], //23.34
	        'transactionTax': [SERVER_SIDE_TRANSACTION_TAX], //1.34
	        'transactionShipping': [SERVER_SIDE_TRANSACTION_SHIPPING], //5
	        'transactionProducts': [{ //One entry for each product - Product 1
	            'sku': '[SERVER_SIDE_PRODUCT_SKU]', //Product 1
	            'name': '[SERVER_SIDE_PRODUCT_NAME]',
	            'category': '[SERVER_SIDE_PRODUCT_CATEGORY]',
	            'price': [SERVER_SIDE_PRODUCT_PRICE], //11.99
	            'quantity': [SERVER_SIDE_PRODUCT_QUANTITY] //1
	        },[
	            'sku': 'AA1243544', //Product 2
	            'name': 'Socks',
	            'category': 'Apparel',
	            'price': 9.99,
	            'quantity': 2
	        ]]
	    ]
	 * 
	 */
	public static function pushSuccessTransaction($data) {
		self::$data[] = array(
			'event' 			=> 'IRX Ecommerce Transaction',
			'IRXTransaction' 	=> $data
		);
	}
	
	/**
	 * @param array $data
	 * 
	    $data = [
	        'transactionId': '[SERVER_SIDE_TRANSACTION_ID]',
	        'transactionStatus': '[TRANSACTION_STATUS]',
	        'transactionHumanReadableMessage': [TRANSACTION_STATUS_MESSAGE], //23.34
	    ]
	 */
	public static function pushFailedTransaction($data) {
		self::$data[] = array(
			'event' 			=> 'IRX Ecommerce Transaction',
			'IRXTransaction' 	=> $data
		);
	}
	
	public static function pushSubmittedForm($data){
		self::$data[] = array(
			'event' 			=> 'irx.newData.form',
			'IRXSubmittedForm' 	=> $data
		);
	}
	
}