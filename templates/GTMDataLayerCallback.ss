<% if CallbackEvent %>
	IRXDataLayer.push({
		{$EventDataJSON},
		'eventCallback': function() {	
			<% loop CallbackEvent %>
				<% include GTMDataLayerCallback %>
			<% end_loop %>
		}
<% else %>	
	IRXDataLayer.push($EventDataJSON);
<% end_if %>