<?php
include('config.php');
include('crypto.php');

// if method is not post
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
	echo "Direct access is not allowed";
	exit;
}

$_SESSION['user_journey'] = [];

// make it
$validation = $validator->make($_POST, [
	'name'              => 'required',
	'registrationType'  => 'required',
	'present_designation' => 'required',
	'affiliation' 			=> 'required',
	'nationality'            => 'required',
	'email'                 => 'required|email',
	'country'               => 'required',
	'indam_member_id'	    => 'required_if:is_member,yes',
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
}else {
	
	$name = preg_replace('/[^A-Za-z ]/i', '', input_cleaner($_POST['name']));
	$email = strtolower(input_cleaner($_POST['email']));
	$registration_type = input_cleaner($_POST['registrationType']);
	$present_designation = preg_replace('/[^A-Za-z ]/i', '', input_cleaner($_POST['present_designation']));
	$nationality = input_cleaner($_POST['nationality']);
	$affiliation = preg_replace('/[^A-Za-z ]/i', '', input_cleaner($_POST['affiliation']));
	$country = preg_replace('/[^A-Za-z ]/i', '', input_cleaner($_POST['country']));
	$dial_code = input_cleaner($_POST['dial_code']);
	$is_member = $_POST['is_member'];
	$member_id = NULL;
	if($is_member == "yes") {
		if(is_array($_POST['indam_member_id']) && count($_POST['indam_member_id'])) :
			$member_id = input_cleaner(implode('-',$_POST['indam_member_id']));
		else : 
			$_SESSION['error'][] = "We could not find the member id you shared. Please try again.";
			header("Location: " . $_SERVER['HTTP_REFERER']);
			exit;
		endif;
	}
	$phone = input_cleaner($_POST['phone_number']);

	// check if email is already registered
	$is_registered = $db->query('SELECT * FROM registrations_master WHERE email = ? and status = 1', $email);
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
					(full_name,email,mobile,country,dial_code,affiliation,designation,registration_type,nationality,is_member,member_id) 
					VALUES (?,?,?,?,?,?,?,?,?,?,?)',
					array(
						$name,$email,$phone,$country,$dial_code,$affiliation,$present_designation,$registration_type,$nationality,$is_member,$member_id,
					));
	if($insert_registration->affectedRows() == 1) : 
		$registration_id = $insert_registration->lastInsertID();
		$db->query('UPDATE registrations_master SET registration_id = "INDAM-CONF-2022-'.$registration_id.'" WHERE id = ? ',$registration_id);
		$_SESSION['user_journey']['reg_id'] = $registration_id;
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
		  <div class="py-5 text-center">
			<img class="d-block mx-auto mb-4" src="assets/images/logo.png" alt="">
			<h2>Payment Details</h2>	
		</div>
		<div class="col-md-12 col-lg-12">
			
			<form name="frmPayment" action="ccavRequestHandler.php" method="POST">

				<input type="hidden" name="merchant_id" value="<?=make_input_encrypt(CCA_MERCHANT_ID)?>">
				<input type="hidden" name="language" value="EN">				
				<input type="hidden" name="currency" value="<?=make_input_encrypt($get_pricing_details[1])?>">
				<input type="hidden" name="amount" value="<?=make_input_encrypt($get_pricing_details[0])?>">
				<input type="hidden" name="redirect_url" value="<?=$_ENV['APP_DOMAIN']?>payment-response.php">
				<input type="hidden" name="cancel_url" value="<?=$_ENV['APP_DOMAIN']?>returnback.php">
				<input type="hidden" name="order_id" value="<?=make_input_encrypt("INDAM-CONF-2022-".$registration_id."")?>">

				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-2 col-form-label">Name</label>
					<div class="col-sm-10">
					<input type="text" class="form-control" id="billing_name" name="billing_name" value="<?=$name?>" readonly >
					</div>
				</div>
				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
					<div class="col-sm-10">
					<input type="text" class="form-control" id="billing_email" name="billing_email" value="<?=$email?>" readonly >
					</div>
				</div>
				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-2 col-form-label">Amount details</label>
					<div class="col-sm-10">
					<input type="text" class="form-control" id="amount_details" value="<?=$get_pricing_details[1]." ".$get_pricing_details[0]?> " readonly>
					</div>
				</div>			
				<div class="form-group row text-center">
    				<div class="col-sm-10 mt-5">
      					<button type="submit" class="btn btn-success">Pay now</button>
						<button type="button" class="btn btn-secondary">Cancel</button>
    				</div>
  				</div>
			</form>
		</div>
	</main>
</div>
<?php include('footer.php'); ?>