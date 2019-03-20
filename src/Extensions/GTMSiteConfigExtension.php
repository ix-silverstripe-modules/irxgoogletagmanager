<?php
/**
 *
 * @author  Guy Watson <guy.watson@internetrix.com.au>
 * @package  irxgoogletagmanager
 *
 **/

namespace Internetrix\GoogleTagManager;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Tab;
use SilverStripe\Forms\TextareaField;

class GTMSiteConfigExtension extends DataExtension {
	
	private static $db = array (
		'Placeholder1'				=>'Text',
		'Placeholder2'				=>'Text',
		'Placeholder3'				=>'Text',
		'ReceivedFormPlaceholder'	=>'Text'
	);
	
	public function updateCMSFields(FieldList $fields) {
		$fields->addFieldToTab ( "Root", Tab::create('GTM', 'Scripts'), 'Access');
		$fields->addFieldsToTab ( "Root.GTM", 					
			array(
				TextareaField::create('Placeholder1', 'Script inside <HEAD>')
					->setRows(15)
					->addExtraClass('gtm stacked')
					->setDescription("This code is injected into all pages at the top, before the </HEAD> tag."),
				TextareaField::create('Placeholder2', 'Script after <BODY>')
					->setRows(15)
					->addExtraClass('gtm stacked')
					->setDescription("This code is injected into all pages at the top, after the <BODY> tag."),
				TextareaField::create('Placeholder3', 'Script before </BODY>')
					->setRows(15)
					->addExtraClass('gtm stacked')
					->setDescription("This code is injected into all pages at the bottom, before the </BODY> tag."),
				TextareaField::create('ReceivedFormPlaceholder', 'Script after form submitted')
					->setRows(15)
					->addExtraClass('gtm stacked')
					->setDescription("This code is injected into the page after a userforms submission. Specific (per form) code can be added on the form and will override this content."),
			)
		);
	}
}
