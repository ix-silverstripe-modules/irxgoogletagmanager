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
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Control\HTTPRequest;

class GTMControllerExtension extends DataExtension {
	
	public function onBeforeInit(){
        $request = $this->owner->getRequest();
        $session = $request->getSession();
		GTMDataLayer::loadDataFromSession($session);
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