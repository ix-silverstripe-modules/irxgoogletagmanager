<?php
/**
 *
 * @author  Guy Watson <guy.watson@internetrix.com.au>
 * @package  irxgoogletagmanager
 *
 **/

class GTMUserFormsExtension extends DataExtension {
	
	public $insertJSON = false;
	
	private static $db = array (
		'ReceivedFormPlaceholder' =>'Text',
	);
	
	public function updateCMSFields(FieldList $fields) {

		$fields->addFieldToTab("Root.FormOptions", TextareaField::create("ReceivedFormPlaceholder", "Received Form Placeholder")
			->addExtraClass('gtm')
			->setDescription('This code is injected into the page after a userforms submission. Will override any code (if any) defined at a global level for this page')
		); 
	}
	
	public function contentcontrollerInit($controller) {
		if(Session::get('FormProcessed')){
			$this->owner->insertJSON = true;
		}
	}
	
	public function DataLayerJSON(){
		if($this->owner->insertJSON){
			$submittedForm 	= $this->owner->Submissions()->sort('Created', 'DESC')->first();
			$irxDataLayer 	= array();
			$irxDataLayer['event'] = 'irx.newData.form';
			$irxDataLayer['IRXSubmittedForm'] = array(
				'name' 				=> "Page - " . $this->owner->MenuTitle,
				'submissionId'		=> $submittedForm->ID,
				'submissionStatus'	=> "",
			);
			$fields = array();
			foreach($submittedForm->Values() as $formField){
				$fields[$formField->Title] = $formField->Value;
			}
			
			$irxDataLayer['IRXSubmittedForm']['fields'] = $fields;
			
			return Convert::array2json($irxDataLayer);
		}else{
			return false;
		}
	}
}
