<?php
/**
 *
 * @author  Guy Watson <guy.watson@internetrix.com.au>
 * @package  irxgoogletagmanager
 *
 **/

class GTMPageExtension extends DataExtension {
	
	/**
	 * print js array for template
	 */
	public function getDataLayerJSON(){
		
		$ArrayData = $this->owner->loadDataLayerForTemplate();
		
		return ViewableData::create()->renderWith(
			'GTMDataLayer', 
			$ArrayData
		);
	}
	
	
	/**
	 * 
	 * @param string $jsArrayName
	 * @param boolean $toJSON
	 * @return ArrayData
	 */
	public function loadDataLayerForTemplate($toJSON = false){
		
		$data = $this->owner->getGTMDataLayer($toJSON, true);
		
		if( ! $data) return false;
		
		$ArrayData = $this->convertDataToNestedArrayData($data);
		
		return $ArrayData;
	}
	
	/**
	 * Convert all event(s) data array into nested formatted GTM array.
	 * 
	 * It's very important to convert the format for multiple GTM events.
	 * For multi events example.
	 * 
	 *	IRXDataLayer.push({
	 *	    'event': 'irx.newRefund',
	 *	    'IRXRefund': {
	 *	        'ecommerce': {
	 *	            'refund': {
	 *	                'actionField': { 'refund': 'T12345'}
	 *	            }
	 *	        }
	 *	    },
	 *	    'eventCallback': function() {
	 *	        IRXDataLayer.push({
	 *	           'event': 'irx.newProductsImpressions',
	 *	           'IRXProductsImpressions': {'ecommerce': { ...... }},
	 *	           'eventCallback': function() { ..... }
	 *	        });
	 *	    }
	 *	});
	 *
	 * @param Array
	 */
	public function convertDataToNestedArrayData($array){
		
		if(count($array) > 1){
			//more than 2 events. process it recursively.
			$currentArray = array_shift($array);
			
			$callBackArray = $this->convertDataToNestedArrayData($array);
			
			$currentArray['eventCallback'] = "ToBeReplace";
			
			$JSON = Convert::array2json($currentArray);
			
			$callBackFunctionString = ViewableData::create()->renderWith('GTMDataLayerCallback', $callBackArray);
			
			$JSON = str_ireplace('"ToBeReplace"', $callBackFunctionString, $JSON);
			
			return new ArrayData(array(
				'EventDataJSON' => $JSON
			));
		}

		return new ArrayData(array(
			'EventDataJSON' => Convert::array2json(array_shift($array)),
		));
	}
	
	
	
	
	public function getGTMDataLayer ($toJSON = false, $clearSessionAfterGet = false) {
		$data = Session::get('GTMDataLayerArray');
		
		if($data === null) return false;
		
		if($clearSessionAfterGet === true){
			//clear session data
			Session::clear('GTMDataLayerArray');
			Session::save();
		}
		
		return $toJSON ? Convert::array2json($data) : $data;
	}
	
	
	
	public function saveGTMDataLayer ($array, $validateArray = true) {
		
		if( $validateArray && ! ArrayLib::is_associative($array)){
			throw new InvalidArgumentException('The parameter has to be an associative array');
		}
		
		return Session::set('GTMDataLayerArray', $array);
		
	}
	
	
	
	public function insertGTMDataLayer($array){
		
		if( ! ArrayLib::is_associative($array)){
			throw new InvalidArgumentException('The parameter has to be an associative array');
		}
		
		$data = $this->owner->getGTMDataLayer();
		
		$data[] = $array;
		
		return $this->owner->saveGTMDataLayer($data, false);
	}
	
}
