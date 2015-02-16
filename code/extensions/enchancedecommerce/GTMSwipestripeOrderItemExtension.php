<?php
/**
 *
 * @author  Jason Zhang <jason.zhang@internetrix.com.au>
 * @package  irxgoogletagmanager
 *
 **/

class GTMSwipestripeOrderItemExtension extends DataExtension {
	
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