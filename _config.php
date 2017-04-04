<?php
//save non-returned data into session.
function onShutdownSaveDataLayer(){
	
	GTMDataLayer::saveDataIntoSession();
	
}
register_shutdown_function('onShutdownSaveDataLayer');
