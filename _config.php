<?php
//save non-returned data into session.

use Internetrix\GoogleTagManager\GTMDataLayer;

function onShutdownSaveDataLayer(){
	
	GTMDataLayer::saveDataIntoSession();
	
}
register_shutdown_function('onShutdownSaveDataLayer');
