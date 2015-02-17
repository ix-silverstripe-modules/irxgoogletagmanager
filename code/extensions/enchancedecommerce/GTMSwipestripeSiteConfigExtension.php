<?php
/**
 *
 * @author  Guy Watson <guy.watson@internetrix.com.au>
 * @package  irxgoogletagmanager
 *
 **/

class GTMSwipestripeSiteConfigExtension extends DataExtension {
	
	private static $db = array (
		'PaymentConversion'	=>'Text'
	);
	
	public function updateCMSFields(FieldList $fields) {
		$fields->addFieldsToTab ( 
			"Root.GTM", 					
			array(
				TextareaField::create('PaymentConversion', 'Payment Conversion Code')
					->setRows(10)
					->addExtraClass('gtm')
					->setDescription('this code will appear before IRXDataLayer and Placeholder1.'),
			),
			'Placeholder1'
		);
	}
}
