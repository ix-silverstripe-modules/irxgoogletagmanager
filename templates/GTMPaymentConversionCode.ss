<% if $isPaymentJustSuccessfulGTM %>
	<!-- Payment Conversion Code -->
	$SiteConfig.PaymentConversion.RAW
	
	$pushPaymentResult
<% end_if %>