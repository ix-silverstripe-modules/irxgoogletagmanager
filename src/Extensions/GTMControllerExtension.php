<?php
/**
 *
 * @author  Guy Watson <guy.watson@internetrix.com.au>
 * @package  irxgoogletagmanager
 *
 **/

namespace Internetrix\GoogleTagManager;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Core\Convert;

class GTMControllerExtension extends DataExtension {
	
	public function onBeforeInit(){
		GTMDataLayer::loadDataFromSession();
	}
	
	public function DataLayerExists(){
		return GTMDataLayer::hasData();
	}
	
	public function DataLayerJSON(){
		$dataArray = GTMDataLayer::getDataForTemplate();
		
		$this->owner->extend('updateDataLayerBeforeFrontEnd', $dataArray);
		
		return empty($dataArray) ? false : Convert::array2json($dataArray);
	}
	
}