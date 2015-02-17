<?php
/**
 *
 * @author  Jason Zhang <jason.zhang@internetrix.com.au>
 * @package  irxgoogletagmanager
 *
 **/

class GTMSwipestripeControllerExtension extends DataExtension {
	
	public function contentcontrollerInit(CartPage_Controller $controller){
		Requirements::javascript(FRAMEWORK_DIR .'/thirdparty/jquery/jquery.js');
		Requirements::javascript('framework/thirdparty/jquery-livequery/jquery.livequery.min.js');
		Requirements::javascript('irxgoogletagmanager/javascript/enchanced-ecommerce.js');
	}
	
}