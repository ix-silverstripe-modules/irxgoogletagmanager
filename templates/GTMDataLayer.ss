window.IRXDataLayer = window.IRXDataLayer || [];
<% if CallbackEvent %>
	<% include GTMDataLayerCallback %>
<% else %>	
	IRXDataLayer.push($EventDataJSON);
<% end_if %>