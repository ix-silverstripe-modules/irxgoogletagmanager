<% if $EventDataJSON %>
	<script>
		window.IRXDataLayer = window.IRXDataLayer || [];
		IRXDataLayer.push($EventDataJSON);
	</script>
<% end_if %>