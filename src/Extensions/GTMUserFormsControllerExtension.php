<?php
/**
 *
 * @author  Guy Watson <guy.watson@internetrix.com.au>
 * @package  irxgoogletagmanager
 *
 **/

namespace Internetrix\GoogleTagManager;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Control\Controller;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Convert;


class GTMUserFormsControllerExtension extends DataExtension {
	// catch form data here in case the submission isn't being saved.
	public function updateEmailData( $emailData,  $attachments){
        $session = $this->owner->sessionGet();
        $session->set('GTM-capturedFields-'. $this->owner->ID, $emailData);
	}
}