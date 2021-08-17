<?php include(APPPATH.'libraries/Crypto.php')?>
<?php

    // print_r($response);exit;
	error_reporting(0);
	$workingKey=$response['working_key'];		//Working Key should be provided here.
	$encResponse=$response["encResp"];			//This is the response sent by the CCAvenue Server
	$rcvdString=decrypt($encResponse,$workingKey);		//Crypto Decryption used as per the specified working key.
	$order_status="";
	$decryptValues=explode('&', $rcvdString);

	$dataSize=sizeof($decryptValues);
	echo "<center>";

	for($i = 0; $i < $dataSize; $i++) 
	{
		$information=explode('=',$decryptValues[$i]);
		if($i==3)	$order_status=$information[1];
		$values.="'$information[1]'".',';
		if($i==0)
		{
			$order_id=$information[1];
		}

	}
	// print_r($values);exit;
	$rtrinString=rtrim($values,',');

	$string="INSERT INTO `payment_txns`(`order_id`, `tracking_id`, `bank_ref_no`, `order_status`, `failure_msg`, `payment_mode`, `card_name`, `status_code`, `status_messege`, `currency`, `amount`, `billing_name`, `billing_address`, `billing_city`, `billing_state`, `billing_zip`, `billing_country`, `billing_mobile`, `billing_email`, `delivery_name`, `delivery_address`, `delivery_city`, `delivery_state`, `delivery_zip`, `delivery_country`, `delivery_phn`, `merchant_param1`, `merchant_param2`, `merchant_param3`, `merchant_param4`, `merchant_param5`, `valuat`, `offer_type`, `offer_code`, `discount_value`, `mer_amount`, `eci_value`, `retry`, `response_code`, `billing_note`,`txn_date`,`bin_country`) VALUES($rtrinString)";
	$this->db->query($string);

	if($order_status==="Success")
	{
		$string1="Update tbl_order_detail_for_restaurant set order_status='Closed',status='7',payment_status=1 WHERE order_id='$order_id' and status !=0";
		$string2="Update tbl_sub_order_detail_for_restaurant set order_status='Closed',status='7',payment_status=1 WHERE order_id='$order_id' AND  status !=0";
		$this->db->query($string1);
		$this->db->query($string2);
	}
	if($order_status==="Success")
	{
		echo "<br>Thank you for shopping with us. Your credit card has been charged and your transaction is successful. We will be shipping your order to you soon.";
		
	}
	else if($order_status==="Aborted")
	{
		echo "<br>Thank you for shopping with us.We will keep you posted regarding the status of your order through e-mail";
	
	}
	else if($order_status==="Failure")
	{
		echo "<br>Thank you for shopping with us.However,the transaction has been declined.";
	}
	else
	{
		echo "<br>Security Error. Illegal access detected";
	
	}

	echo "<br><br>";

	echo "<table cellspacing=4 cellpadding=4>";
	for($i = 0; $i < $dataSize; $i++) 
	{
		$information=explode('=',$decryptValues[$i]);
	    	echo '<tr><td>'.$information[0].'</td><td>'.$information[1].'</td></tr>';

	}

	echo "</table><br>";
	echo "</center>";
?>
