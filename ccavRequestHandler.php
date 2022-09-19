<html>
<head>
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

	foreach ($_POST as $key => $value){
		$merchant_data.= $key.'='.$value.'&';
	}
	// insert this transaction into our transactions master 
	$insert = $db->query('INSERT INTO transactions_master (txn_status,txn_registration_id,txn_user_email,txn_amount,txn_currency) VALUES (?,?,?,?,?)', 'processing',$_POST['order_id'],$_POST['billing_email'],$_POST['amount'],$_POST['currency']);
	$merchant_data .= "currency=".$_POST['currency'];
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