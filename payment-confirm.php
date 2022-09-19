<?php
include('config.php');

// if method is not post
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
	echo "Direct access is not allowed";
	exit;
}


// make it
$validation = $validator->make($_POST, [
	'name'              => 'required',
	'registrationType'  => 'required',
	'present_designation' => 'required',
	'affiliation' 			=> 'required',
	'nationality'            => 'required',
	'email'                 => 'required|email',
	'country'               => 'required',
	'member_id'	            => 'required_if:is_member,yes',
	'phone'					=> 'required|digits_between:10,13'
]);

if ($validation->fails()) {
	// handling errors
	$_SESSION['error'] = $validation->errors();
	header("Location: " . $_SERVER['HTTP_REFERER']);
	exit;
} elseif (!in_array(input_cleaner($_POST['nationality']), $allowed_nationality, true) || !in_array(input_cleaner($_POST['registrationType']), $allowed_category, true)) {
	$_SESSION['error'][] = "You did not select proper input.";
	header("Location: " . $_SERVER['HTTP_REFERER']);
	exit;
} else {

	$name = input_cleaner($_POST['name']);
	$email = strtolower(input_cleaner($_POST['email']));
	$registration_type = input_cleaner($_POST['registrationType']);
	$present_designation = input_cleaner($_POST['present_designation']);
	$nationality = input_cleaner($_POST['nationality']);
	$affiliation = input_cleaner($_POST['affiliation']);
	$is_member = $_POST['is_member'];
	$member_id = NULL;
	if($is_member == "yes") {
		$member_id = input_cleaner($_POST['member_id']);
	}
	$phone = input_cleaner($_POST['phone']);

	// check if email is already registered
	$is_registered = $db->query('SELECT * FROM registrations_master WHERE email = ?', $email);
	if ($is_registered->numRows() > 0) :
		$_SESSION['error'][] = "You are already registered.";
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
	endif;

	// check if member id is valid or not  
	$is_valid_member = false;
	if ($is_member == "yes") :
		$member_details = $db->query('SELECT email,unique_member_id,next_renewal_date FROM user_master WHERE email = ? and unique_member_id = ?', array($email, $member_id))->fetchArray();
		if (is_array($member_details) && !empty($member_details)) :
			// check if membership is not expired
			if($member_details['next_renewal_date'] > date('Y-m-d')): 
				$is_valid_member = true;
			else : 
				$_SESSION['error'][] = "Your membership is expired. Please fill the form again or renew your membership.";
				header("Location: " . $_SERVER['HTTP_REFERER']);
				exit;
			endif;
		else : 
			$_SESSION['error'][] = "We could not find the member id you shared. Please try again.";
			header("Location: " . $_SERVER['HTTP_REFERER']);
			exit;
		endif;
	endif;

	$get_pricing_details = get_price_details($nationality,$registration_type,$is_valid_member); 

	if($get_pricing_details[0] == 0){
		$_SESSION['error'][] = "There was error fetching price. Please try again.";
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
	}

	// insert into registration master with status inactive 
	$insert_registration = $db->query('INSERT INTO registrations_master 
					(full_name,email,mobile,affiliation,designation,registration_type,nationality,is_member,member_id) 
					VALUES (?,?,?,?,?,?,?,?,?)',
					array(
						$name,$email,$phone,$affiliation,$present_designation,$registration_type,$nationality,$is_member,$member_id,
					));
	if($insert_registration->affectedRows() == 1) : 
		$registration_id = $insert_registration->lastInsertID();
		// insert into transactions master 
		$insert_transaction = $db->query('INSERT INTO transactions_master 
					(txn_user_email,txn_status,txn_registration_id,txn_amount,txn_currency) 
					VALUES (?,?,?,?,?)',
					array(
						$email,'processing',$registration_id,$get_pricing_details[0],$get_pricing_details[1]
					));
		if($insert_transaction->affectedRows() != 1) : 
			$db->query('DELETE from registrations_master where id = ? LIMIT 1',$registration_id);
		endif;
	else : 
		$_SESSION['error'][] = "Something went wrong. Please try again.";
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
	endif;
}
?>
<?php include('header.php'); ?>
<div class="container">
	<main>
		<div class="col-md-12 col-lg-12">
			
			<form name="frmPayment" action="https://test.ccavenue.com/transaction/transaction.do?command=initiateTransaction" method="POST">
				<input type="hidden" name="merchant_id" value="<?=CCA_MERCHANT_ID?>">
				<input type="hidden" name="language" value="EN">				
				<input type="hidden" name="currency" value="<?=$get_pricing_details[1]?>">
				<input type="hidden" name="amount" value="<?=$get_pricing_details[0]?>">
				<input type="hidden" name="redirect_url" value="<?=$_ENV['APP_DOMAIN']?>payment-response.php">
				<input type="hidden" name="cancel_url" value="<?=$_ENV['APP_DOMAIN']?>payment-cancel.php">
				<div class="form-group">
					<input type="text" name="billing_name" value="<?=$name?>" class="form-field" Placeholder="Billing Name" readonly="readonly">
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