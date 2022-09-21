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
$response_email  = $responseMap['billing_email'];
$tracking_id = $responseMap['tracking_id'];
$bank_ref_no = $responseMap['bank_ref_no'];
$payment_mode = $responseMap['payment_mode'];

// check if the order id sent has payment status processing
$txn_details = $db->query('SELECT * FROM transactions_master WHERE txn_registration_id = ? AND txn_user_email = ?  LIMIT 1', $order_id, $response_email)->fetchArray();
if ($txn_details['txn_status']  !== "processing") :
    $_SESSION['error'][] = "There is some error processing your request. Please contact adminsitrator.";
    header("Location: " . $_ENV['APP_DOMAIN']);
    exit;
endif;

// UPDATE Payment status
$final_pay_status = "failed";
$failure_message = $responseMap['status_message'];

// UPDATE payment STATUS
$update_txn = $db->query('UPDATE transactions_master  SET
		 txn_status = "' . $final_pay_status . '",
		 txn_payment_id = "' . $tracking_id . '",
		 txn_remarks = "' . $failure_message . '",
		 txn_updated_at = "' . date('Y-m-d H:i:s') . '",
		 txn_bank_ref = "' . $bank_ref_no . '",
		 txn_amount_paid = "'.$responseMap['mer_amount'].'",
		 txn_payment_mode = "' . $payment_mode . '"
		 WHERE txn_id = ' . $txn_details['txn_id'] . '  LIMIT 1');
?>
<?php include('header.php'); ?>
<div class="container">
    <main>
        <div class="py-5 text-center">
            <img class="d-block mx-auto mb-4" src="assets/images/logo.png" alt="">
        </div>

        <div class="jumbotron text-center">
            <h1 class="display-3">Oops! Something went wrong.</h1>
            <p class="lead"><strong>Payment Gateweay response : </strong> <?= $failure_message ?></p>
            <hr>
            <p class="lead">
                <a class="btn btn-primary btn-sm" href="<?= $_ENV['APP_DOMAIN'] ?>" role="button">Continue to homepage</a>
            </p>
        </div>
    </main>
</div>
<?php session_destroy();
include('footer.php'); ?>