<?php
/**
 *
 * @author  Guy Watson <guy.watson@internetrix.com.au>
 * @package  irxgoogletagmanager
 *
 **/

class GTMSiteConfigExtension extends DataExtension {
	
	private static $db = array (
		'Placeholder1'				=>'Text',
		'Placeholder2'				=>'Text',
		'Placeholder3'				=>'Text',
		'ReceivedFormPlaceholder'	=>'Text'
	);
	
	public function updateCMSFields(FieldList $fields) {
		$fields->addFieldToTab ( "Root", Tab::create('GTM', 'GTM'), 'Access');
		$fields->addFieldsToTab ( "Root.GTM", 					
			array(
				TextareaField::create('Placeholder1', 'Placeholder 1')
					->setRows(20)
					->addExtraClass('gtm')
					->setDescription('before &lt;/HEAD&gt; tag'),
				TextareaField::create('Placeholder2', 'Placeholder 2')
					->setRows(20)
					->addExtraClass('gtm')
					->setDescription('after &lt;BODY&gt; tag'),
				TextareaField::create('Placeholder3', 'Placeholder 3')
					->setRows(20)
					->addExtraClass('gtm')
					->setDescription('before &lt;/BODY&gt; tag'),
				TextareaField::create('ReceivedFormPlaceholder', 'Received Form Placeholder')
					->setRows(30)
					->addExtraClass('gtm')
					->setDescription("This code is injected into the page after a userforms submission. Specific (per form) code can be added on the form page and will override this content")
			)
		);
	}
}
