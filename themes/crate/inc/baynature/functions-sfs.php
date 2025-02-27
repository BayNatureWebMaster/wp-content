<?php
/****************************************************************************************
* A set of functions used in the interface to our Fullfilment Service (SFS)
*
* Initial Deployment to baynature.org (test) 1/31/22
*
*
****************************************************************************************/



/****************************************************************************************
* The Digital Editions are behind a paywall. To naviage to any of these pages the user
* must be logged in the their baynature.org account AND have an active Digital Edition
* Subscription.
****************************************************************************************/
function get_current_id() {
	if ( ! function_exists ( 'wp_get_current_user') ) {
		return 0;
	}
	$user = wp_get_current_user();
	return ( isset($user) ? (int) $user->ID : 0);
}

function sfsAPI_isDES ($emailAddress) {
	//$emailAddress = "frnkcox6@gmail.com";//"laurencetietz@gmail.com"; //"alansiegelphd@gmail.com";
	//bn_print_r ($emailAddress);
	$data = array ( "Email" =>$emailAddress );
	
	if ( isWhiteListEmail( $emailAddress) ) {
		return 2;
	}
	if ( isBayNatureEmail( $emailAddress ) ) {
		return 2;
	}
	//bn_print_r( $data );
	$result = callAPIGet( "GET", $data );
	bn_print_r( $result );

	// Get Subscriber Details From SFS via API
	
	$dcResult = json_decode( $result );
	bn_print_r($dcResult);
	//echo " Edition = ".$dcResult->Edition;
	
	switch ( $dcResult->Edition ) {
		case "D":
			echo "This is a DE";
			if ( 0 === strcmp("A",  $dcResult->Status)) {
				echo " is active";
				return 2;
			}
			break;
		case "B":
			echo "This is a Bundle";
			if ( 0 === strcmp("A",  $dcResult->Status)) {
				echo " is active";
				return 2;
			}
			break;
	}
	
	// Determin If There Is An Active Digital Edition Subscription
	return 1;
}

function isWhiteListEmail ( $emailAddress ) {
	//echo "white list email " . $emailAddress;

	if ( 0 === strcmp( "melissadhero@gmail.com", $emailAddress) ) {
		//echo "white list email " . $emailAddress;
		return true; 
	}
	if ( 0 === strcmp( "clroskosz@me.com", $emailAddress)) {
		return true;
	}
	if ( 0 === strcmp( "frnkcox6@gmail.com", $emailAddress)) {
		return true;
	}
	return false;
}

function isBayNatureEmail ( $emailAddress ) {
	$subStr = explode( "@", $emailAddress );
	if ( 0 === strcmp( $subStr[1], "baynature.org" ) ) {
		return true;
	}
	return false;
}
function isVistorActiveDigitalSubscriber () {
	
	if ( is_user_logged_in() ) {
		// get user ID
		$userID = get_current_id();
		
		if ( $userID > 0 ) {
			$userData = get_userdata($userID);
			//bn_print_r($userData);
			//bn_print_r($userData->user_login);
			$emailAddress = $userData->user_email;
			return sfsAPI_isDES ($emailAddress);
		}
		else {
			echo "ID is wrong " . $userID;
		}
	}
	return 0;
}

function isVistorActiveDemoDigitalSubscriber () {
	if ( is_user_logged_in() ) {
		// get user ID
		$userID = get_current_id();
		
		if ( $userID > 0 ) {
			$userData = get_userdata($userID);
			//bn_print_r($userData);
			//bn_print_r($userData->user_login);
			$emailAddress = $userData->user_email;
			//echo $emailAddress;
			if (0 === strcmp( $emailAddress , "de-promo@baynature.org" )) {
				return 2;
			}		
		}
		else {
			echo "ID is wrong " . $userID;
		}
	}
	else {
		echo "Please login to your account to access the Digital Edition. <a href='/my-account/'>Click here to login</a>";
	}
	return 0;
}
/***************************************************************************************
* The following functions are used to POST subscription orders to SFS
*
*
*
*****************************************************************************************/

/***************************************************************************************
* This is the main function and is called from the admin-new-order.php. The 
* admin-new-order.php file is the template for the new order notification email.
*
* Determine if the Order contains any of the 4 baynature subscription products using
* the product ID. If a subscription product is included in the order gather the
* data associated with the subscription and pass it forward to the specific subscription
* product type to be posted to SFS database.
*
*
****************************************************************************************/
function processSubscriptionOrders ( $order ) {
	// Subscription Product ID's
	$printDigitalBundleProductID 	= 159828;
	$printOnlyProductID 			= 160548;
	$digitalOnlyProductID 			= 159832;
	$giftSubscrionProductID 		= 160549;

	//$orderID = $order->get_id();
	/*
	if ( get_post_meta( $order->id, 'Opt Out Status', true ) === "1" ) $sharInfo = "Do not share my address";
	else $sharInfo = "Ok to share my address";

	if ( get_post_meta( $order->id, 'I\'d like to receive Bay Nature\'s free weekly email newsletter ', true ) === "1" ) $newsletter = "Yes I'd like to recieve the Bay Nature Newsletter";
	else $newsletter = "No I do not want to recieve Bay Nature newsletter";
	*/
	$newsletter = "";
	$sharInfo = "";
	$orderItems = $order->items;
	$oData = $order->data;
	if( $order->get_coupon_codes() ) {
		bn_print_r($order->get_coupon_codes());
		$coupons_count = count( $order->get_coupon_codes() );
		$i = 1;
		$coupons_list = '';
		$coupon_amount = '';
		foreach( $order->get_coupon_codes() as $coupon) {
			$coupons_list .= $coupon;
			if( $i < $coupons_count )
				$coupons_list .= ', ';

			$couponObj = new WC_Coupon($coupon);

    		//$discount_type = $couponObj->get_discount_type(); // Get coupon discount type
    		$coupon_amount = $couponObj->get_amount(); // Get coupon amount
			$i++;
			}
		echo '<p>Coupons used (' . $coupons_count . ') : ' . $coupons_list . '</p>';
		echo '<p>Coupon Amount '.$coupon_amount.'</p>';
	}

 	$shippingAddress = $oData["shipping"];
 	$billingAddress = $oData["billing"];
	
	foreach ($orderItems as $item) {
		//echo "<br> New Item <br>";
		//bn_print_r( $item );
		$iDecode = json_decode($item);
		//bn_print_r ($iDecode);
		$pName = $iDecode->name;
		$productID =  $iDecode->product_id;
		$priceTotal = $iDecode->subtotal; //total;
		echo "SubTotal = ".$priceTotal."<br>";
		switch ( $productID ) {
			case $printOnlyProductID:
				$numberOfYears = getPrintOnlyNYears($iDecode);
				$issueTerm = 4 * $numberOfYears;
				$roundUpDonation = getRoundUpDonation ( $iDecode );
				$message = "<h2>Post SFS: Print Subscription</h2>";
				$sType = "P";
				$ret = postSubscription ( $message, $sType ,$shippingAddress , $billingAddress , $issueTerm , $priceTotal , $roundUpDonation , $shareInfo , $newsletter , $coupons_list, $coupon_amount, "No", $order->id );
				$coupons_list .= '-TO';
				break;
			case $digitalOnlyProductID:
				$variationID = $iDecode->variation_id;
				$roundUpDonation = getRoundUpDonation ( $iDecode );
				// this product uses variations to select the number of years
				switch ( $variationID) {
					case 159835: // one year
						$issueTerm = 4;
						break;
					case 159834: // two years
						$issueTerm = 8;
						break;
					case 159833: // 3 years years
						$issueTerm = 12;
						break;
					default:
						$issueTerm = 0;
				}
				$message = "<h2>Post SFS: Digital Only subscription</h2>";
				$sType = "D";
				$ret = postSubscription ( $message, $sType ,$shippingAddress , $billingAddress , $issueTerm , $priceTotal , $roundUpDonation , $shareInfo , $newsletter , $coupons_list, $coupon_amount, "No", $order->id );
				$coupons_list .= '-TO';
				break;
			case $printDigitalBundleProductID:
				$variationID = $iDecode->variation_id;
				$roundUpDonation = getRoundUpDonation ( $iDecode );
				// this product uses variations to select the number of years
				switch ( $variationID) {
					case 159829: // one year
						$issueTerm = 4;
						break;
					case 159830: // two years
						$issueTerm = 8;
						break;
					case 159831: // three years
						$issueTerm = 12;
						break;
					default:
						$issueTerm = 0;
				}
				$message = "<h2>Post SFS: Print + Digital Bundle subscription</h2>";
				$sType = "B";
				$ret = postSubscription ( $message, $sType ,$shippingAddress , $billingAddress , $issueTerm , $priceTotal , $roundUpDonation , $shareInfo , $newsletter , $coupons_list, $coupon_amount, "No",  $order->id );

				$coupons_list .= '-TO';
				break;
			case $giftSubscrionProductID:
				$ret = postGiftSubscription ( $iDecode , $shippingAddress , $billingAddress , $shareInfo , $newsletter , $coupons_list, $coupon_amount);
				$coupons_list .= '-TO';
				break;
			default:
				break;
		}
	}
}



function getPostSubscriptionData ( $priceTotal, $issueTerm, $edition, $isGift, $billingAddress, $shippingAddress , $coupons_list ) {
	//if ( strlen($coupons_list) > 0) {
		$coupons_list = "9-".$coupons_list;
	//}
	$product = [array (		"Code" 			=> $coupons_list,
						 	"Amount" 		=> $priceTotal,
						 	"IssueTerm" 	=> $issueTerm,
						 	"Edition" 		=> $edition,
						 	"IsGift" 		=> $isGift,
						 	"First" 		=> $shippingAddress["first_name"],
						 	"Last" 			=> $shippingAddress["last_name"],
						 	"Company" 		=> $shippingAddress["company"],
						 	"Address1" 		=> $shippingAddress["address_1"],
						 	"Address2" 		=> $shippingAddress["address_2"],
						 	"City" 			=> $shippingAddress["city"],
						 	"Postal" 		=> $shippingAddress["postcode"],
						 	"State" 		=> $shippingAddress["state"],
						 	"CountryCode" 	=> $shippingAddress["country"],
						 	"Email" 		=> $shippingAddress["email"],
						 	"Phone" 		=> $shippingAddress["phone"],
		)];
	$data = array (
							"Products" 		=> $product,
							"First" 		=> $billingAddress["first_name"],
						 	"Last" 			=> $billingAddress["last_name"],
						 	"Company" 		=> $billingAddress["company"],
						 	"Address1" 		=> $billingAddress["address_1"],
						 	"Address2" 		=> $billingAddress["address_2"],
						 	"City" 			=> $billingAddress["city"],
						 	"Postal" 		=> $billingAddress["postcode"],
						 	"State" 		=> $billingAddress["state"],
						 	"CountryCode" 	=> $billingAddress["country"],
						 	"Email" 		=> $billingAddress["email"],
		);
	bn_print_r($data);
	return $data;
}

function postSubscription ( $message, $stype ,$shippingAddress , $billingAddress , $issueTerm , $priceTotal , $roundUpDonation , $shareInfo , $newsletter , $coupons_list, $coupon_amount, $isGift, $wooOrderId ) {
	echo $message;
	$ru = substr($roundUpDonation, 1  , 5 );
	$ap = $priceTotal - floatval($ru);
	//echo ("is = " .$ap);
	if ($ap < 0) $ap =0;
	$priceTotal = strval($ap);
	$priceTotal	= adjustPriceForCoupon( $priceTotal, $coupon_amount );

	//echo ("now = " .$ap);//

	//echo "Test for cURL <br>";
	if ( !function_exists("curl_init") && !function_exists("curl_setopt") && !function_exists("curl_exec") && !function_exists("curl_close") ) {
		echo "cURL DOES NOT exists <br/>";
	}
	else {
		//echo "cURL exists <br/>";
		$data = getPostSubscriptionData ( $priceTotal, $issueTerm, $stype, false, $billingAddress, $shippingAddress , $coupons_list );
		$ret = callAPI( "POST" , $data );
		//recordSFSPostData( $ret, $stype, $issueTerm, $priceTotal, $coupons_list, $isGift, $wooOrderId );
	}
	echo "POST subscription to SFS STATUS (Live)<br>";
	return true;
}

/**************************************************************************************************************
* Gift Subscription. The gift subscription product is special in that it uses a gravity form to collect up to
* five gift subscriptions in a single product.
***************************************************************************************************************/
function postGiftSubscription ( $iDecode , $shippingAddress , $billingAddress , $shareInfo , $newsletter , $coupons_list, $coupon_amount ) {
	//return true;
	echo "<h2>Post SFS: Gift Subscription</h2>";

	$metaD = $pName = $iDecode->meta_data;
	$gForm = $metaD[0];
	$gValue = $gForm->value;
	$gLead = $gValue->_gravity_form_lead;
	//bn_print_r( $gLead );
	$gFVars = get_object_vars($gLead);	
	bn_print_r($gFVars);
	// get the first gift recipant's name
	$shippingAddress = array();

	$shippingAddress["first_name"]  = $gFVars["2.3"];
	$shippingAddress["last_name"]   = $gFVars["2.6"];
	$shippingAddress["address_1"]  	= $gFVars["4.1"];
	$shippingAddress["address_2"]	= $gFVars["4.2"];
	$shippingAddress["city"]       	= $gFVars["4.3"];
	$shippingAddress["state"]      	= getStateCode($gFVars["4.4"]);
	$shippingAddress["postcode"] 	= $gFVars["4.5"];
	$shippingAddress["country"]    	= "US"; //$gFVars["4.6"];
	//$gift_1_Send_Note_To		= $gFVars["49"];
	//$gift_1_Note 				= $gFVars["50"];
	$gift_1_Number_of_Years		= intval( substr($gFVars["101"],0,1));
	$priceTotal					= substr($gFVars["101"],-5); //$gFVars["28"];
	echo "price for sub 1 = ".$priceTotal;
	$priceTotal					= adjustPriceForCoupon( $priceTotal, $coupon_amount );
	//$priceTotal = strval($ap);

	echo("<h4>First Gift Recipient</h4>");
	echo( "1st Gift Number of Years = ".$gift_1_Number_of_Years . "<br />" );
	$issueTerm = 4*$gift_1_Number_of_Years;
	//bn_print_r($shippingAddress);
	//echo $gift_1_Number_of_Years;
	//echo $priceTotal;
	//$priceTotal =  $gFVars["28"];;
	$data = getPostSubscriptionData ( $priceTotal, $issueTerm, "P", true, $billingAddress, $shippingAddress , $coupons_list);
	$ret = callAPI( "POST" , $data );
	return true;
	
	if ( 0 === strcmp("Yes", $gFVars["7.1"]) ) {
		// get the second gift recipant's name
		$shippingAddress["first_name"]  = $gFVars["8.3"];
		$shippingAddress["last_name"]   = $gFVars["8.6"];
		$shippingAddress["address_1"]  	= $gFVars["9.1"];
		$shippingAddress["address_2"]  	= $gFVars["9.2"];
		$shippingAddress["city"]       	= $gFVars["9.3"];
		$shippingAddress["state"]      	= getStateCode($gFVars["9.4"]);
		$shippingAddress["postcode"] 	= $gFVars["9.5"];
		$shippingAddress["country"]    	= "US"; //$gFVars["9.6"];
		$gift_2_Number_of_Years			= intval( substr($gFVars["29"],0,1));
		$priceTotal						= substr($gFVars["29"],-5);

		echo( "2st Gift Number of Years = ".$gift_2_Number_of_Years . "<br />" );
		$issueTerm = 4*$gift_2_Number_of_Years;
		$data = getPostSubscriptionData ( $priceTotal, $issueTerm, "P", true, $billingAddress, $shippingAddress, $coupons_list ."-TO-2" );
		$ret = callAPI( "POST" , $data );
	}

	if ( 0 === strcmp("Add a 3rd Gift Subscription", $gFVars["11.1"]) ) {
		// get the third gift recipant's name
		$shippingAddress["first_name"]  = $gFVars["12.3"];
		$shippingAddress["last_name"]   = $gFVars["12.6"];
		$shippingAddress["address_1"]  	= $gFVars["13.1"];
		$shippingAddress["address_2"]  	= $gFVars["13.2"];
		$shippingAddress["city"]       	= $gFVars["13.3"];
		$shippingAddress["state"]      	= getStateCode($gFVars["13.4"]);
		$shippingAddress["postcode"] 	= $gFVars["13.5"];
		$shippingAddress["country"]   	= "US"; //$gFVars["13.6"];
		$gift_3_Number_of_Years			= intval( substr($gFVars["30"],0,1));
		$priceTotal						= substr($gFVars["30"],-5);


		echo("<h4>Third Gift Recipient</h4>");
		echo("3th Gift Number of Years = ".$gift_3_Number_of_Years . "<br />" );
		$issueTerm = 4*$gift_3_Number_of_Years;
		$data = getPostSubscriptionData ( $priceTotal, $issueTerm, "P", true, $billingAddress, $shippingAddress, $coupons_list . "-TO-3" );
		$ret = callAPI( "POST" , $data );
	}
	if ( 0 === strcmp("Add a 4th Gift Subscription", $gFVars["15.1"]) ) {
		// get the fourth gift recipant's name
		$shippingAddress["first_name"]  = $gFVars["16.3"];
		$shippingAddress["last_name"]   = $gFVars["16.6"];
		$shippingAddress["address_1"]  	= $gFVars["17.1"];
		$shippingAddress["address_2"]  	= $gFVars["17.2"];
		$shippingAddress["city"]       	= $gFVars["17.3"];
		$shippingAddress["state"]      	= getStateCode($gFVars["17.4"]);
		$shippingAddress["postcode"] 	= $gFVars["17.5"];
		$shippingAddress["country"]    	= "US"; //$gFVars["17.6"];
		$gift_4_Number_of_Years			= intval( substr($gFVars["31"],0,1));
		$priceTotal						= substr($gFVars["31"],-5);


		echo("<h4>Forth Gift Recipient</h4>");
		echo("4th Gift Number of Years = ".$gift_4_Number_of_Years . "<br />" );
		$issueTerm = 4*$gift_4_Number_of_Years;
		$data = getPostSubscriptionData ( $priceTotal, $issueTerm, "P", true, $billingAddress, $shippingAddress, $coupons_list ."-TO-4" );
		$ret = callAPI( "POST" , $data );
	}


	if ( 0 === strcmp("Send a 5th Gift Subscription", $gFVars["23.1"]) ) {
		// get the fifth gift recipant's name
		$shippingAddress["first_name"]  = $gFVars["24.3"];
		$shippingAddress["last_name"]   = $gFVars["24.6"];
		$shippingAddress["address_1"]  	= $gFVars["25.1"];
		$shippingAddress["address_2"]  	= $gFVars["25.2"];
		$shippingAddress["city"]       	= $gFVars["25.3"];
		$shippingAddress["state"]      	= getStateCode($gFVars["25.4"]);
		$shippingAddress["postcode"] 	= $gFVars["25.5"];
		$shippingAddress["country"]    	= "US"; //$gFVars["25.6"];
		$gift_5_Number_of_Years			= intval( substr($gFVars["32"],0,1));
		$priceTotal						= substr($gFVars["32"],-5);


		echo("<h4>Fith Gift Recipient</h4>");
		echo("5th Gift Number of Years = ".$gift_5_Number_of_Years . "<br />" );
		$issueTerm = 4*$gift_5_Number_of_Years;
		$data = getPostSubscriptionData ( $priceTotal, $issueTerm, "P", true, $billingAddress, $shippingAddress, $coupons_list . "-5" );
		$ret = callAPI( "POST" , $data );
	}	

	return true;
}

/**********************************************************************************************
* The following functions are helper functions used in determining the attributes associated
* with an subscription order.
***********************************************************************************************/
function getPrintOnlyNYears( $oItem ) {
	// the print product uses a gravity form to select the number of years - this should be changed to use variations like the D and B products
	$metaData = $oItem->meta_data;
	//bn_print_r( $metaData );
	if ( count ( $metaData ) > 0 ) {
		$nYears = $metaData[1];
		$years = $nYears->value;
		$subStr = substr( $years , 0 , 6);
		//echo "substr =" . $subStr;
		if (0 === strcmp("1 Year" , $subStr)) {
			return 1;
		}
		if (0 === strcmp("2 Year" , $subStr)) {
			return 2;
		}
		if (0 === strcmp("3 Year" , $subStr)) {
			return 3;
		}	
	}
	return -1;
}

function getRoundUpDonation ( $oItem ) {
	$metaData = $oItem->meta_data;
	//bn_print_r( $metaData );
	if ( count ( $metaData ) > 2 ) {
		$roundUp = $metaData[2];
		if ( 0 === strcmp($roundUp->value, "Other")) {
			// fetch value from other field
			$roundUp = $metaData[3];
			$nDolars = $roundUp->value; 
		}
		else {
			$nDolars = $roundUp->value; 
		}
		//echo "ROUND UP = ".$nDolars."<br>";
		return $nDolars;
	}
	return -1;
}

function adjustPriceForCoupon( $priceTotal, $coupon_amount ) {
	$priceTotalFloat = floatval( $priceTotal );
	bn_print_r($priceTotalFloat);
	$coupon_amountFloat = floatval( $coupon_amount );
	bn_print_r($coupon_amountFloat);

	$discountPriceFloat = $priceTotalFloat - $coupon_amountFloat;
	if ( $discountPriceFloat <= 0.0 ) $discountPriceFloat = 0.0;
	$priceTotal = strval( $discountPriceFloat );
	echo "price after discount = ".$priceTotal;
	return $priceTotal;
}

function getStateCode( $stateName ) {
	$stateCode = array (
	"Alabama"=>"AL",
	"Alaska"=>"AK",
	"Arizona"=>"AZ",
	"Arkansas"=>"AR",
	"California"=>"CA",
	"Colorado"=>"CO",
	"Connecticut"=>"CT",
	"Delaware"=>"DE",
	"District of Columbia"=>"DC",
	"Florida"=>"FL",
	"Georgia"=>"GA",
	"Hawaii"=>"HI",
	"Idaho"=>"ID",
	"Illinois"=>"IL",
	"Indiana"=>"IN",
	"Iowa"=>"IA",
	"Kansas"=>"KS",
	"Kentucky"=>"KY",
	"Louisiana"=>"LA",
	"Maine"=>"ME",
	"Maryland"=>"MD",
	"Massachusetts"=>"MA",
	"Michigan"=>"MI",
	"Minnesota"=>"MN",
	"Mississippi"=>"MS",
	"Missouri"=>"MO",
	"Montana"=>"MT",
	"Nebraska"=>"NE",
	"Nevada"=>"NV",
	"New Hampshire"=>"NH",
	"New Jersey"=>"NJ",
	"New Mexico"=>"NM",
	"New York"=>"NY",
	"North Carolina"=>"NC",
	"North Dakota"=>"ND",
	"Ohio"=>"OH",
	"Oklahoma"=>"OK",
	"Oregon"=>"OR",
	"Pennsylvania"=>"PA",
	"Rhode Island"=>"RI",
	"South Carolina"=>"SC",
	"South Dakota"=>"SD",
	"Tennessee"=>"TN",
	"Texas"=>"TX",
	"Utah"=>"UT",
	"Vermont"=>"VT",
	"Virginia"=>"VA",
	"Washington"=>"WA",
	"West Virginia"=>"WV",
	"Wisconsin"=>"WI",
	"Wyoming"=>"WY",
	);
	return $stateCode [$stateName];
}


/******************************************************************************************
* Interface functions to the SFS API using the php cURL library
*
* Refine this section: combine the two functions into one.
********************************************************************************************/
function callAPIGet ( $method , $data ) {
	//$data ="Email:melissadhero@gmail.com";
	$url_test 	= "https://sfsdata.com/BNApi/api/Transaction/ProcessTransTest";
	$url_live 		= "https://sfsdata.com/BNApi/api/Info/FindByEmail";
	$jData = json_encode( $data );
	//echo "callAPI FindByEmail<br/>";
	//echo $jData;
	$curl = curl_init();
	
	curl_setopt($curl, CURLOPT_POST, 1);  // CURLOPT_HTTPGET
	curl_setopt($curl, CURLOPT_POSTFIELDS, $jData );
	//echo curl_getinfo( $curl );
	curl_setopt($curl, CURLOPT_URL, $url_live);
	curl_setopt($curl, CURLOPT_SSLVERSION, 6 );
	
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'X-Auth-Token: lps9Wa+4qwwiC72rAGau7w==',
		)
	);
	
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

	$result = curl_exec($curl);
	if (curl_errno($curl)) {
		echo "ERROR ? ";
    	bn_print_r (curl_error($curl));
	}
	//if (!$result) { die("Connection Failed"); }
	
	curl_close($curl);
	//echo "POST to SFS STATUS FindByEmail (Live)<br>";
	//echo curl_getinfo( $curl );

	//bn_print_r($result); 
	return $result;
  
}

function callAPI ( $method , $data ) {
	//return true;
	$url_test 	= "https://sfsdata.com/BNApi/api/Transaction/ProcessTransTest";
	$url_live 		= "https://sfsdata.com/BNApi/api/Transaction/ProcessTrans";
	$jData = json_encode( $data );
	//echo "callAPI <br/>";
	//echo $jData;
	$curl = curl_init();
	
	curl_setopt($curl, CURLOPT_POST, 1);  // CURLOPT_HTTPGET
	curl_setopt($curl, CURLOPT_POSTFIELDS, $jData );
	//echo curl_getinfo( $curl );
	curl_setopt($curl, CURLOPT_URL, $url_live);
	curl_setopt($curl, CURLOPT_SSLVERSION, 6 );
	/*
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'X-Auth-Token: TestKeyBayNature',
		)
	);
*/
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'X-Auth-Token: lps9Wa+4qwwiC72rAGau7w==',
		)
	);
	
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

	$result = curl_exec($curl);
	if (curl_errno($curl)) {
		echo "ERROR  ";
    	bn_print_r (curl_error($curl));
	}
	//if (!$result) { die("Connection Failed"); }
	
	curl_close($curl);
	//echo "POST subscription to SFS STATUS (Live)<br>";
	//echo curl_getinfo( $curl );

	bn_print_r($result); 
	return $result;
}

/*
function recordSFSPostData ( $result , $stype , $issueTerm , $priceTotal , $coupons_list, $isGift, $wooOrderId ) {
	global $wpdb;   
	// sfsRecord 
	//     	id
	//     	data
	//     	results
	//     	date_time
	//     	woo order id
	//     	sfs order id
	//		issue Term
	//		price
	//		edition
	//		source key
	//		is Gift? true / false
	//
	if ( 0 === strlen($coupon_list) ) {
		$coupon_list = "9-";
	}

	$sfsOrderID 	= "863445"; //$result->OrderID;
	$sfsOrderStatus = "true"; //$result->Success;

	echo ( "{".$wooOrderId." : ".$sfsOrderID."}" );
	echo ("<br>Amount : ".$priceTotal);
	echo ("<br>Edition : ".$stype);
	echo ("<br>Issue Term : ".$issueTerm);
	echo ("<br>Source Key : ".$coupon_list);
	echo ("<br>Is Gift? : ".$isGift);
	//echo ("<br>Is Gift ? : ".$isGift);
	//echo ("<br>First Name : ".$firstName);
	//echo ("<br>Last Name : ".$lastName);
	echo ("<br>SFS Order Status : ".$sfsOrderStatus);
	echo ("<br>");

	// add a record to the database     
 	$table_name = $wpdb->prefix . 'sfsPostLog';     
  	$message = 'Insert to database tutorial.';     
  	$date = date('Y-m-d H:i:s');     

  	$wpdb->insert($table_name, array('sfsOrderID' => $sfsOrderID, 'wooOrderID' => $wooOrderId, 'post_date' => $date, 'issueTerm' => $issueTerm, 'edition' => $stype, 'price' => $priceTotal, 'sourceKey' => $coupon_list, 'isGift' => $isGift)); 

}
*/

function isVistorActiveSoilMatersDigitalSubscriber () {
	// test to see if this user is logged in
	if ( is_user_logged_in() ) {
		// get user ID
		$userID = get_current_id();
		if ( $userID > 0 ) {
			$userData = get_userdata($userID);
			//bn_print_r($userData);
			//bn_print_r($userData->user_login);
			$emailAddress = $userData->user_email;
			if ( isDigitalEditionMember( "soil-matters-digital-edition" , $emailAddress ) ) return 2;
			else return 1;
		}
		else {
			echo "ID is wrong " . $userID;
		}
	}
	return 0;
}

function isVistorActiveGardening4WildlifeDigitalSubscriber () {
	// test to see if this user is logged in
	if ( is_user_logged_in() ) {
		// get user ID
		$userID = get_current_id();
		if ( $userID > 0 ) {
			$userData = get_userdata($userID);
			//bn_print_r($userData);
			//bn_print_r($userData->user_login);
			$emailAddress =  $userData->user_email;
			if ( isDigitalEditionMember( "gardening-for-wildlife-with-native-plants-digital-edition" , $emailAddress ) ) return 2;
			else return 1;
		}
		else {
			echo "ID is wrong " . $userID;
		}
	}
	return 0;
}

function isDigitalEditionMember ( $publication, $memberEmail ) {
	//echo $memberEmail;
	$gardingWildLifeMemberList = array (
		"markgcox@gmail.com",
		"arnette.davis@gmail.com",
		"bduffy898@gmail.com",
		"tmessier@telus.net",
		"janedurant@comcast.net",
		"karenalden22@gmail.com",
		"laurence@baynature.org",
		"margenefilson@gmail.com",
		"riche@stanfordalumni.org",
		"kates@sontagfilm.org",
		"casey0803@gmail.com",
		"mimitranzambetti@gmail.com",
		"fred@geomorph.com",
		"patrick.hoge@gmail.com",
		"johanna.silver@gmail.com",
		"enanoria@gmail.com",
		"luz.calvo@csueastbay.edu",
		"heatheredawes@gmail.com",
		"fmellos@hotmail.com",
		"fiddleheadgardens@yahoo.com", // end page 1
		"sueserrone@comcast.net",
		"ericalynnb@gmail.com",
		"waggingwilson@gmail.com",
		"althea.wasow@gmail.com",
		"marthacerda0@gmail.com",
		"thespiralmom@gmail.com",
		"weevriam@gmail.com",
		"brekke@gmail.com",
		"gaileva@earthlink.net",
		"tomoestk@yahoo.com",
		"laurie.aguirre@gmail.com",
		"pius@nightjuggler.com",
		"wendy_bartlett@sbcglobal.net",
		"upphotoman@hotmail.com",
		"brandymike@gmail.com",
		"kerkhoff@sbcglobal.net",
		"kristyabueche@gmail.com",
		"jo@coastsidelandtrust.org",
		"lewisstringer@hotmail.com",
		"leah.duran44@gmail.com", // end page 2
		"gilljmc@sonic.net",
		"iskaredoff@gmail.com",
		"dcorliss@gmail.com",
		"Mary.jean.roscoe@gmail.com",
		"liseciolino@yahoo.com",
		"hildelargay@gmail.com",
		"daku@pobox.com",
		"alicefroststudio@gmail.com",
		"paustin26@yahoo.com",
		"tiff.loe@gmail.com",
		"kevinsea@yahoo.com",
		"sfdlong@ix.netcom.com",
		"tsuga.agua@gmail.com",
		"simons6589@comcast.net",
		"eileenc@farallonmedia.com",
		"kelleyberg@gmail.com",
		"msmunn@ail.com",
		"annacbird@yahoo.com",
		"awettrich@me.com",
		"linda@lrlart.com",    // end  page 3
		"beth@baynature.org",
		"laurencetietz@studiosoftware.com",
		"laurencefirth@yahoo.com",
		"katja.hebestreit@gmail.com",
		"carney.biz@scubadoo.com",
		"vitalcyclesinfo@gmail.com",
		"jmhorne@earthlink.net",
		"glynn@sonic.net",
		"devatara@marinorientalmedicine.com",
		"jen.rudd@gmail.com",
		"mary@landible.com",
		"herndal@gmail.com",
		);

$soilMattersMemberList = array (
		"markgcox@gmail.com",
		"arnette.davis@gmail.com",
		"tamara@swiftwingstudio.com",
		"bduffy898@gmail.com",
		"tmessier@telus.net",
		"janedurant@comcast.net",
		"karenalden22@gmail.com",
		"margenefilson@gmail.com",
		"riche@stanfordalumni.org",
		"kates@sontagfilm.org",
		"casey0803@gmail.com",
		"mimitranzambetti@gmail.com",
		"fred@geomorph.com",
		"patrick.hoge@gmail.com",
		"johanna.silver@gmail.com",
		"enanoria@gmail.com	",
		"luz.calvo@csueastbay.edu",
		"heatheredawes@gmail.com",
		"fmellos@hotmail.com",
		"fiddleheadgardens@yahoo.com", // end page 1
		"sueserrone@comcast.net",
		"ericalynnb@gmail.com",
		"waggingwilson@gmail.com",
		"althea.wasow@gmail.com",
		"marthacerda0@gmail.com",
		"thespiralmom@gmail.com",
		"minama@tnc.org",
		"weevriam@gmail.com",
		"brekke@gmail.com",
		"gaileva@earthlink.net",
		"tomoestk@yahoo.com",
		"laurie.aguirre@gmail.com",
		"pius@nightjuggler.com",
		"wendy_bartlett@sbcglobal.net",
		"upphotoman@hotmail.com",
		"brandymike@gmail.com",
		"kerkhoff@sbcglobal.net",
		"kristyabueche@gmail.com",
		"jo@coastsidelandtrust.org",
		"lewisstringer@hotmail.com", // end page 2
		"leah.duran44@gmail.com",
		"gilljmc@sonic.net",
		"iskaredoff@gmail.com",
		"dcorliss@gmail.com",
		"Mary.jean.roscoe@gmail.com",
		"liseciolino@yahoo.com",
		"hildelargay@gmail.com",
		"daku@pobox.com",
		"alicefroststudio@gmail.com",
		"paustin26@yahoo.com",
		"tiff.loe@gmail.com",
		"kevinsea@yahoo.com",
		"sfdlong@ix.netcom.com",
		"tsuga.agua@gmail.com",
		"simons6589@comcast.net",
		"rlmyers@me.com",
		"lewisyunbills@gmail.com",
		"eileenc@farallonmedia.com",
		"kelleyberg@gmail.com",
		"msmunn@ail.com", // end page 3
		"annacbird@yahoo.com",
		"awettrich@me.com",
		"linda@lrlart.com",
		"beth@baynature.org",
		"p.k.peirce@att.net",
		"laurence@baynature.org",
		"sandi@rearwin.com",
		"vitalcyclesinfo@gmail.com",
		"glynn@sonic.net",
		"bernard@berkeleyrc.com",
		"herndal@gmail.com",
		);



	switch ($publication) {
		case "gardening-for-wildlife-with-native-plants-digital-edition":
			 return testMembership ( $memberEmail, $gardingWildLifeMemberList );
			break;
		case "soil-matters-digital-edition":
			return testMembership ( $memberEmail, $soilMattersMemberList );
			break;
	}

}

function testMembership ( $memberEmail, $publicationMemberList ) {
	$listLength = count ( $publicationMemberList );
	for ($i = 0; $i < $listLength; $i++) {
		//echo $publicationMemberList[ $i ] . "<br>";
		if ( 0 === strcmp ( $publicationMemberList[ $i ], $memberEmail ) ) {
			return true;
		}
	}
	return false;
}

?>