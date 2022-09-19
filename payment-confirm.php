<?php
include('config.php');
include('crypto.php');
// if method is not post
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
	echo "Direct access is not allowed";
	exit;
}

// make it
$validation = $validator->make($_POST, [
	'first_name'            => 'required',
	'last_name'             => 'required',
	'email'                 => 'required|email',
	'country'               => 'required',
]);

if ($validation->fails()) {
	// handling errors
	$_SESSION['error'] = $validation->errors();
	header("Location: " . $_SERVER['HTTP_REFERER']);
	exit;
} elseif (!in_array(input_cleaner($_POST['userNationality']), $allowed_nationality, true) || !in_array(input_cleaner($_POST['userType']), $allowed_category, true)) {
	$_SESSION['error'][] = "You did not select proper input.";
	header("Location: " . $_SERVER['HTTP_REFERER']);
	exit;
} else {
	$first_name = input_cleaner($_POST['first_name']);
	$last_name = input_cleaner($_POST['last_name']);
	$email = input_cleaner($_POST['email']);
	$country = input_cleaner($_POST['country']);
	$user_nationality = input_cleaner($_POST['userNationality']);
	$user_category = input_cleaner($_POST['userType']);
	// check if email is already registered
	$is_registered = $db->query('SELECT * FROM registrations_master WHERE email = ?', $email);
	if ($is_registered->numRows() > 0) :
		$_SESSION['error'][] = "You are already registered.";
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
	endif;

	$get_pricing_details = get_price($user_nationality,$user_category); 
	$merchant_data='';

	$payment_details  = array(
		'merchant_id' => CCA_MERCHANT_ID,
		'currency' => $get_pricing_details[1],
		'amount' => $get_pricing_details[0],
		'redirect_url' => 'payment-response.php',
		'cancel_url' => 'payment-cancel.php',
		'billing_name' => $first_name.' ',$last_name,
		'billing_country' => $country,
		'billing_email' => $email
	);

	foreach($payment_details as $key => $value): 
		$merchant_data.= $key.'='.$value.'&';
	endforeach;

	$insert = $db->query('INSERT INTO transactions_master (txn_status,txn_user_email,txn_amount,txn_currency) VALUES (?,?,?,?)', 'processing',$email,$get_pricing_details[0],$get_pricing_details[1]);
	$merchant_data .= "order_id=".$insert->lastInsertID();
	$encrypted_data = encrypt($merchant_data,"31C0F6A8751F2A5909EC13F0DF2A5661");
}
?>
<?php include('header.php'); ?>
<div class="container">
	<main>
		<div class="col-md-12 col-lg-12">
			
			<form name="frmPayment" action="https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction" method="POST">
				<input type="hidden" name="merchant_id" value="212736">
				<input type="hidden" name="language" value="EN">				
				<input type="hidden" name="currency" value="<?=$get_pricing_details[1]?>">
				<input type="hidden" name="amount" value="<?=$get_pricing_details[0]?>">
				<input type="hidden" name="redirect_url" value="https://localhost/indamconf/payment-response.php">
				<input type="hidden" name="cancel_url" value="https://localhost/indamconf/payment-cancel.php">
				<input type="hidden" name="encRequest" value="<?=$encrypted_data?>">
				<input type="hidden" name="access_code" value="AVYM84GC19BY82MYYB">
				<div class="form-group">
					<input type="text" name="billing_name" value="<?=$first_name." ".$last_name?>" class="form-field" Placeholder="Billing Name" readonly="readonly">
				</div>
				<div class="form-group">
					<input type="text" name="billing_country" value="<?=$country?>" class="form-field" Placeholder="Country" readonly="readonly">
				</div>
				<div class="form-group">
					<input type="text" name="billing_email" value="<?=$email?>" class="form-field" Placeholder="Email" readonly="readonly">
				</div>
				<div>
					<button class="btn-payment" type="submit">Pay Now</button>
				</div>
			</form>
		</div>
	</main>
</div>
<?php include('footer.php'); ?>