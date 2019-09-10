<?php
/**
 *
 * @author  Guy Watson <guy.watson@internetrix.com.au>
 * @package  irxgoogletagmanager
 *
 **/

namespace Internetrix\GoogleTagManager;

use SilverStripe\ORM\DataExtension;
use SilverStripe\UserForms\Control\UserDefinedFormController;

class GTMUserFormsControllerExtension extends DataExtension {
	// catch form data here in case the submission isn't being saved.
	public function updateEmailData( $emailData,  $attachments){
	    /* @var $owner UserDefinedFormController */
	    $owner = $this->owner;
	    $request = $owner->getRequest();
	    try {
            $session = $request->getSession();
            $session->set('GTM-capturedFields-'. $owner->data()->ID, $emailData['Fields']);
            $session->save($request);
        } catch (\Exception $e) {
        }
	}
}
