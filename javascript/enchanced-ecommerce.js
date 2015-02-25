//GTM product remove
$('[data-gtmaction=product-remove]').livequery(function(){
	$(this).click(function(e){
		e.preventDefault();
		
		IRXDataLayer.push($(this).data('data2push'));
		
		return true;
	});
});

//GTM product add or update
$('[data-gtmaction=product-add]').livequery(function(){
	$(this).click(function(e){
		e.preventDefault();
		
		var QTY = parseInt($(this).closest('tr').find('input').val()),
		Data2Push = $(this).data('data2push'),
		OriginalQTY = parseInt(Data2Push.IRXShoppingCartChange.ecommerce.add.products[0].quantity);
		
		//update qty
		//only trigger the push function only if new qty is not null and not equal to original one.
		if(QTY && (OriginalQTY != QTY)){
			if(OriginalQTY > QTY){
				//less item
				//find the data array for removing product.
				var RemoveTag = $(this).closest('tr').find('a[data-gtmaction=product-remove]');
				Data2Push = null;//clear this object to prevent conflict.
				Data2Push = RemoveTag.data('data2push');
				Data2Push.IRXShoppingCartChangeRemove.ecommerce.remove.products[0].quantity = OriginalQTY - QTY;
			}else{
				//more item
				//update the 'add' item qty
				Data2Push.IRXShoppingCartChangeAdd.ecommerce.add.products[0].quantity = QTY - OriginalQTY;
			}
			
			IRXDataLayer.push(Data2Push);
		}
		
		return true;
	});
});


//GTM checkout steps
$('[data-gtmaction=checkout-update]').livequery(function(){
	$(this).click(function(){
		var Data2Push = $(this).data('data2push'),
		StepNumber = $(this).data('gtmstep'),
		formIsValid = true,
		ThisForm = $(this).closest('form');
		
		if(StepNumber == 1 && $(this).attr('name') != 'dologin'){
			
			var checkoutOption = ThisForm.find('[name=Method]:checked').val()
			
			Data2Push.IRXCheckoutStep.ecommerce.checkout.actionField.option = checkoutOption;
			
		}else if(StepNumber == 4){
			//update option for payment.
			var PaymentOption = ThisForm.find('select[name=PaymentMethod] option:checked').text().trim();
			
			Data2Push.IRXCheckoutStep.ecommerce.checkout.actionField.option = PaymentOption;
			
		}else if (StepNumber == 5){
			//validation the fields
			ThisForm.find('[required=required]').each(function(){
				if($(this).is('visible') && formIsValid && ! $(this).val()){
					formIsValid = false;
				}
			});
		}
		
		if(formIsValid){
			IRXDataLayer.push(Data2Push);
		}
	});
});






