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
	$merchant_data='';
	$working_key = CCA_WORKING_KEY;
	$access_code = CCA_ACCESS_CODE;
	$usd_inr_rate = 'NULL';
	$is_usd = ($_POST['currency'] == "USD" ?  true : false);


	foreach ($_POST as $key => $value){
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