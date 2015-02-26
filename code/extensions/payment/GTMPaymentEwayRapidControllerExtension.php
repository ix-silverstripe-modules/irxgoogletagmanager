<?php
/**
 *
 * @author  Jason Zhang <jason.zhang@internetrix.com.au>
 * @package  irxgoogletagmanager
 *
 **/

class GTMPaymentEwayRapidControllerExtension extends DataExtension {
	
	public function contentcontrollerInit(Rapid $controller){
		if($controller instanceof Rapid){
			Requirements::javascript(FRAMEWORK_DIR .'/thirdparty/jquery/jquery.js');
			Requirements::javascript('irxgoogletagmanager/javascript/enchanced-ecommerce-payment-eway-form.js');
		}
	}
	
}