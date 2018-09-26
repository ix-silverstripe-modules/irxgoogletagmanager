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
use SilverStripe\Control\Session;
use SilverStripe\Forms\FieldList;

class GTMUserFormsExtension extends DataExtension {
	
	public $insertJSON = false;
	
	private static $db = array (
		'ReceivedFormPlaceholder' =>'Text',
	);
	
	public function updateCMSFields(FieldList $fields) {

		$fields->addFieldToTab("Root.FormOptions", TextareaField::create("ReceivedFormPlaceholder", "Script after form submitted")
			->addExtraClass('gtm stacked')
			->setDescription('This code is injected into the page after a userforms submission. Will override code (if any) defined at a global level for userforms.')
		); 
	}
	
	public function contentcontrollerInit($controller) {
		if(Session::get('GTM-capturedFields-'. $this->owner->ID)){
			$this->owner->insertJSON = true;
		}
	}
	
	public function DataLayerJSON(){
		if($this->owner->insertJSON){
			$submittedID = Session::get('userformssubmission'. $this->owner->ID);
			$submittedForm 	= $this->owner->Submissions()->byID($submittedID);
			$irxDataLayer 	= array();
			$irxDataLayer['event'] = 'irx.newData.form';
			$irxDataLayer['IRXSubmittedForm'] = array(
				'name' 				=> "Page - " . $this->owner->MenuTitle,
				'submissionId' 		=> 0,
				'submissionStatus'	=> "not saved",
			);
			
			if(!$submittedForm){ 
				$submittedForm = Session::get('GTM-capturedFields-'. $this->owner->ID);
				$submittedForm = $submittedForm['Fields'];
				$fields = array();
				foreach($submittedForm as $formField){
					$fields[$formField->Title] = $formField->Value;
				}
			} else {
				$irxDataLayer['IRXSubmittedForm']['submissionId'] = $submittedForm->ID;
				$irxDataLayer['IRXSubmittedForm']['submissionStatus'] = "saved";
				$fields = array();
				foreach($submittedForm->Values() as $formField){
					$fields[$formField->Title] = $formField->Value;
				}
			}
			
			$counta = Session::get('GTM-capturedRecipients-'. $this->owner->ID);
			$irxDataLayer['IRXSubmittedForm']['submissionStatus'] .= ", send $counta email";
			
			$irxDataLayer['IRXSubmittedForm']['fields'] = $fields;
			Session::clear('GTM-capturedRecipients-'. $this->owner->ID);
			Session::clear('GTM-capturedFields-'. $this->owner->ID);
			Session::clear('userformssubmission'. $this->owner->ID); // tidy up userforms!
			return Convert::array2json($irxDataLayer);
		}else{
			return false;
		}
	}
	// count the number of emails to be sent
	public function updateFilteredEmailRecipients( $recipients, $data, $form){
		Session::set('GTM-capturedRecipients-'. $this->owner->ID, $recipients->Count() );
	}
	
}

class GTMUserFormsControllerExtension extends DataExtension {
	// catch form data here in case the submission isn't being saved.
	public function updateEmailData( $emailData,  $attachments){
		Session::set('GTM-capturedFields-'. $this->owner->ID, $emailData);
	}
}