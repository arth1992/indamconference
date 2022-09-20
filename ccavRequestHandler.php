<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title>Processing payment..</title>
</head>
<body>
<center>
<?php include('config.php')?>	
<?php include('crypto.php')?>

<?php
	$enc_keys =  array("merchant_id","currency","amount","order_id");
	$post_data = array();
	$false_data = 0;
	// let decrypt data first 
	foreach ($_POST as $key => $value){
		if(in_array($key,$enc_keys)) : 
			$dec_value = make_input_decrypt($value);
			$enc_value = make_input_encrypt($dec_value);
			if($enc_value === $value)  :
				$post_data[$key] = $dec_value;
			else : 
				$false_data += 1;
			endif;
		else :
			$post_data[$key] = $dec_value;
		endif;
	}

	if($false_data > 0) :
		$_SESSION['error'][] = "There is some error processing your request. Please try again.";
		header("Location: " . $_ENV['APP_DOMAIN']);
		exit;
	endif;
	$merchant_data='';
	$working_key = CCA_WORKING_KEY;
	$access_code = CCA_ACCESS_CODE;
	$usd_inr_rate = 'NULL';
	$is_usd = ($post_data['currency'] == "USD" ?  true : false);


	foreach ($post_data as $key => $value){

		if($is_usd && $key == "currency"){
			$merchant_data.= $key.'=INR&';
		}
		elseif($is_usd && $key == "amount"){
			$get_usd_to_inr_rate =  usd_to_inr($value);
			$usd_inr_rate = $get_usd_to_inr_rate[0];
			$merchant_data.= $key.'='.$get_usd_to_inr_rate[1].'&';
		}
		else{
			$merchant_data.= $key.'='.$value.'&';
		}
		
	}

	// insert this transaction into our transactions master 
	$insert = $db->query('INSERT INTO transactions_master (txn_status,usd_to_inr,txn_registration_id,txn_user_email,txn_amount,txn_currency) VALUES (?,?,?,?,?,?)', 'processing',$usd_inr_rate,$_POST['order_id'],$_POST['billing_email'],$_POST['amount'],$_POST['currency']);
	$encrypted_data = encrypt($merchant_data,$working_key);
	
?>
<form method="post" name="redirect" action="https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction">
<input type="hidden" name="encRequest" value="<?=$encrypted_data?>">
<input type="hidden" name="access_code" value="<?=$access_code?>">


</form>
</center>
<script language='javascript'>document.redirect.submit();</script>
</body>
</html>