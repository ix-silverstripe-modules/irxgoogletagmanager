<?php
/**
 *
 * @author  Jason Zhang <jason.zhang@internetrix.com.au>
 * @package  irxgoogletagmanager
 *
 **/

class GTMSwipestripeAccountPageExtension extends DataExtension {
	
	public function contentcontrollerInit(AccountPage_Controller $controller){
		
		$paymentID 	= Session::get('PaymentResultPaymentID');
		
		if($paymentID){
			Session::clear('PaymentResultPaymentID');
			Session::save();
			
			$payment	= DataObject::get_by_id('Payment', $paymentID);
			if($payment && $payment->ID){
				$orderDO = $payment->Order();
				if($orderDO && $orderDO->ID){
					//check payment source
					if($payment->Method == 'EwayRapid'){
						$errorSource = 'Eway';
					}elseif ($payment->Method == 'PayPalExpress'){
						$errorSource = 'Paypal';
					}elseif ($payment->Method == 'Cheque'){	
						$errorSource = 'Invoice';
					}else{
						$errorSource = 'N/A';
					}
					
					//check payment status
					if($payment->Status == 'Success'){
						$errorMessage 	= 'OK';
						$errorCode 		= null;
					}elseif($payment->Status == 'Failure'){
						//payment failed
						$paymentErrors = $payment->Errors();
						if($paymentErrors && $paymentErrors->Count()){
							//errro message found
							$errorDO = $paymentErrors->first();
							
							$errorMessage 	= $errorDO->ErrorMessage;
							$errorCode 		= $errorDO->ErrorCode;
						}else{
							// no errro message 
							$errorMessage 	= $errorDO->ErrorMessage;
							$errorCode 		= $errorDO->ErrorCode;
						}
					}else{
						$errorMessage 	= 'Order Pending';
						$errorCode 		= null;
					}
					
					$data = array(
						'event' => 'irx.newTransactionError',
						'IRXTransactionError' => array(
							array(
								'orderId' 		=> $orderDO->ID,        //(optional)
								'errorCode'		=> $errorCode,            	//(optional)
								'errorMessage'	=> $errorMessage,            	//(required)
								'errorSource'	=> $errorSource,
							)
						)
					);
					
					if($payment->PaidByID){
						$data['userID'] = $payment->PaidByID;
					}
					
					$controller->insertGTMDataLayer($data);
				}
			}
		}
		
	}
	
	
	public function isPaymentJustSuccessfulGTM(){
		
		$paymentID = Session::get('PaymentID');
		
		if($paymentID){
			$paymentDO = Payment::get()->byID($paymentID);
			
			if($paymentDO && $paymentDO->ID && $paymentDO->Status == 'Success'){
				
				Session::clear('PaymentID');
				Session::save();
				
				//generate Item array for GTM
				$orderDO = Controller::curr()->request->param('ID');
				if ($orderDO) {
					$orderDO = Order::get()
						->where("\"Order\".\"ID\" = " . Convert::raw2sql($orderDO))
						->First();
				}
				
				if($orderDO && $orderDO->ID){
					
					$productsArray = array();
					$items = $orderDO->Items();
					if($items && $items->Count()){
						foreach ($items as $itemDO){
							$productsArray[] = $itemDO->generateProductItemArray();
						}
					}	
					
					//check coupon code if applicable
					$couponMod 	= CouponModification::get()->filter('OrderID',$orderDO->ID)->first();
					$couponCode	= '';
					if($couponMod && $couponMod->ID){
						$couponDO = $couponMod->Coupon();
						if($couponDO && $couponDO->ID){
							$couponCode = $couponDO->Code;
						}
					}
					
					$data = array(
						'event' => 'irx.newEnhancedTransaction',
						'IRXEnhancedTransaction' => array(
							'ecommerce' => array(
								'purchase' 	=> array(
									'actionField' => array(
										'id' 			=> $orderDO->ID,
										'affiliation'	=> 'Online Store',
										'revenue' 		=> $orderDO->TotalPrice()->getAmount(),
										'tax' 			=> $orderDO->TaxPrice()->getAmount(),
										'shipping' 		=> $orderDO->ShippingPriceForGA()->getAmount(),	
										'coupon' 		=> $couponCode
									),
									'products' => $productsArray
								)
							)
						)
					);
					
					//add purchaser ID if exists.
					if($orderDO->MemberID){
						$data['userID'] = $orderDO->MemberID;
					}
					
					Controller::curr()->insertGTMDataLayer($data);
				}
				
				return true;
			}
		}
		
		return false;	
	}
	
	
}