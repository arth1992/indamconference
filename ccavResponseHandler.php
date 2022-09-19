<?php
include('config.php');
include('crypto.php');

$workingKey = CCA_WORKING_KEY; // Working Key should be provided here.
$encResponse = $_POST["encResp"]; // This is the response sent by the CCAvenue Server
$rcvdString = decrypt($encResponse, $workingKey); // Crypto Decryption used as per the specified working key.
$order_status = "";
$decryptValues = explode('&', $rcvdString);
$dataSize = sizeof($decryptValues);
for ($i = 0; $i < $dataSize; $i++) {
	$information = explode('=', $decryptValues[$i]);
	$responseMap[$information[0]] = $information[1];
}
$order_status = $responseMap['order_status'];
$order_id = $responseMap['order_id'];
$response_email  = $responseMap['email'];
$tracking_id = $responseMap['tracking_id'];
$bank_ref_no = $responseMap['bank_ref_no'];
$failure_message = NULL;
$final_pay_status = NULL;
$payment_mode = $responseMap['payment_mode'];

// check if the order id sent has payment status processing
$txn_details = $db->query('SELECT * FROM transactions_master WHERE txn_registration_id = ? AND txn_user_email = ?  LIMIT 1', $order_id, $response_email);
if ($txn_details['txn_status']  !== "processing") :
	$_SESSION['error'][] = "There is some error processing your request. Please contact adminsitrator.";
	header("Location: " . $_ENV['APP_DOMAIN']);
	exit;
endif;


// UPDATE Payment status
switch($responseMap['order_status']) {
	case "Success" :
		$final_pay_status = "success";
		break;
	default :
		$final_pay_status = "failed";
		$failure_message = $responseMap['failure_message'];
	break;
}

// UPDATE payment STATUS
$update_txn = $db->query('UPDATE transactions_master  SET
		 txn_status = "'.$final_pay_status.'",
		 txn_payment_id = "'.$tracking_id.'",
		 txn_remarks = "'.$failure_message.'",
		 txn_updated_at = "'.date('Y-m-d H:i:s').'",
		 txn_bank_ref = "'.$bank_ref_no.'",
		 txn_payment_mode = "'.$payment_mode.'",
		 WHERE txn_id = ?  LIMIT 1', $txn_details['txn_id']);
if($update_txn->affectedRows() == 1) : 
	
	$registration_id = explode('-',$order_id);
	$registration_id = intval($registration_id[3]);
	$db->query('UPDATE registrations_master  SET status = 1 WHERE id = ?  LIMIT 1', $registration_id);

	$_SESSION['error'][] = "You have sucessfully registered for the conference. Thank you!";
	header("Location: " . $_ENV['APP_DOMAIN']);
	exit;
endif
?>