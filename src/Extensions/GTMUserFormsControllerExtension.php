<?php
/**
 *
 * @author  Guy Watson <guy.watson@internetrix.com.au>
 * @package  irxgoogletagmanager
 *
 **/

namespace Internetrix\GoogleTagManager;

use SilverStripe\ORM\DataExtension;

class GTMUserFormsControllerExtension extends DataExtension {
	// catch form data here in case the submission isn't being saved.
	public function updateEmailData( $emailData,  $attachments){
        $session = $this->owner->sessionGet();
        $session->set('GTM-capturedFields-'. $this->owner->ID, $emailData);
	}
}