# IRX Google Tag Manager

## Requirements

* SilverStripe 3.1

## Maintainers

* support@internetrix.com.au

## Description

This module contains a couple of handy FormFields for managing sitewide scripts, such as analytics javascript.

### Usage

In your page template, you can render the data by including these three tags:

	$SiteConfig.Placeholder1.RAW (just before the </head> tag)
	$SiteConfig.Placeholder2.RAW (just after the <body> tag)
	$SiteConfig.Placeholder3.RAW (just before the </body> tag)
	
You should also add the following code before the $SiteConfig.Placeholder1.RAW placeholder

    <script>
    	window.IRXDataLayer = window.IRXDataLayer || [];
    	<% if $DataLayerJSON %>
    		IRXDataLayer.push($DataLayerJSON);
    	<% end_if %>
    </script>

The UserForms module has a ReceivedFormSubmission.ss template which needs additional code, there is an updated version of that template in this module. Copy the template from this module into ~/mysite/templates/ so that it takes priority. Alternatively, if you have a customised template it will need to reference these two tags: 

	<% if ReceivedFormPlaceholder %>
		$ReceivedFormPlaceholder.RAW
	<% else %>
		$SiteConfig.ReceivedFormPlaceholder.RAW
	<% end_if %>

