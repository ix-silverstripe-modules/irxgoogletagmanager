<?php
/**
 *
 * @author  Jason Zhang <jason.zhang@internetrix.com.au>
 * @package  irxgoogletagmanager
 *
 **/

class GTMSwipestripeCategoryExtension extends DataExtension {
	
	public function contentcontrollerInit($controller){
		
		Session::set('GTMCurrentCategory', $this->owner->ID);
		
	}
	
}