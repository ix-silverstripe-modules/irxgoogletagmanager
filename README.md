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
    	<% if $DataLayerJSON %>
    		window.IRXDataLayer = window.IRXDataLayer || [];
    		IRXDataLayer.push($DataLayerJSON);
    	<% end_if %>
    </script>

In your UserForms module the ReceivedFormSubmission.ss template is automatically adding the necessary tags, however if you have a customised template then it will need to reference these two tags: 

	<% if  ReceivedFormPlaceholder %>
		$ReceivedFormPlaceholder.RAW
	<% else %>
		$SiteConfig.ReceivedFormPlaceholder.RAW
	<% end_if %>

