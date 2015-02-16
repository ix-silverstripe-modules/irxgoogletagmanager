<?php
/**
 *
 * @author  Jason Zhang <jason.zhang@internetrix.com.au>
 * @package  irxgoogletagmanager
 *
 **/

class GTMSwipestripeCategoryExtension extends DataExtension {
	
	public function contentcontrollerInit($controller){
		
		if($this->owner instanceof ProductCategory){
			Session::set('GTMCurrentCategory', $this->owner->ID);
		}else{
			if( ! ($this->owner instanceof Product)){
				Session::clear('GTMCurrentCategory');
			}
		}
		
	}
	
}