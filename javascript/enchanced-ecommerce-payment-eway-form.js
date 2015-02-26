;jQuery(function($) {
	var pForm = $('#Form_PayForm');
	if(pForm.length){
		pForm.find('input[type=submit]').on('click', function(){
			if( ! pForm.valid()){
				var errorMSG = pForm.find('div.field div.middleColumn label.error'),
				OrderIDInput = pForm.find('input[name=OrderID]'),
				OrderID = null,
				MemberIDInput = pForm.find('input[name=MemberID]'),
				MemberID = null,
				gtmErrorMSG = [];
				
				if(OrderIDInput.length){
					OrderID = OrderIDInput.val();
				}
				
				if(MemberIDInput.length){
					MemberID = MemberIDInput.val();
				}
				
				if(errorMSG.length){
					errorMSG.each(function(){
						var MSG = $(this).text();
						var field = $(this).closest('div.field').find('label.left');
						var title = null;

						if(field.length){
							title = field.text();
							title = title.trim();
						}
						
						MSG = MSG.trim();
						
						gtmErrorMSG.push({
							'orderId': OrderID,                //(optional)
							'errorMessage': MSG,            //(required)
							'errorField': title,       //(optional)
							'errorSource': 'page',     
						});
					});
				}else{
					//not sure the error.
					gtmErrorMSG.push({
						'orderId': OrderID,                	//(optional)
						'errorMessage': 'Undefined Error',  //(required)
						'errorSource': 'page',     
					});
				}
				
				
				//push to DataLayer
				window.IRXDataLayer = window.IRXDataLayer || [];
				IRXDataLayer.push({
				    'event': 'irx.newTransactionError',
				    'userID': MemberID,                   //(optional) user ID, if available
				    'IRXTransactionError': gtmErrorMSG
			   });
			}
		});
	}
}); 