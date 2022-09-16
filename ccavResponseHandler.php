<?php include('Crypto.php');?>
<?php
error_reporting ( 0 );

$workingKey = CCA_WORKING_KEY; // Working Key should be provided here.
$encResponse = $_POST ["encResp"]; // This is the response sent by the CCAvenue Server
$rcvdString = decrypt ( $encResponse, $workingKey ); // Crypto Decryption used as per the specified working key.
$order_status = "";
$decryptValues = explode ( '&', $rcvdString );
$dataSize = sizeof ( $decryptValues );
for($i = 0; $i < $dataSize; $i ++) {
	$information = explode ( '=', $decryptValues [$i] );
	$responseMap [$information [0]] = $information [1];
}
$order_status = $responseMap ['order_status'];

$pymtOrderObj = new PymtOrder ();
$paymentOrder = $pymtOrderObj->getPaymentOrderByOrderId ( $responseMap ['order_id'] );

// this is to fool-proof check - checking if the paymentstatus is already posted
// this can happen on page refresh in success page.
$paymentStatus = $pymtOrderObj->getPaymentStatusByOrderId ( $responseMap ['order_id'] );
if (empty ( $paymentStatus )) {
	$pymtOrderObj->addPymtStatus ( $responseMap ['order_id'], $rcvdString, $responseMap ['order_status'], $responseMap ['amount'] );
}
?>

<?php require_once("common/SessionValidate.php"); ?>
<?php
$pageTitle = "Payment Status ";
$menuGroup = "Payment Status ";
$menuPage = "Payment Status ";
?>
<!doctype html>
<html class="no-js" lang="">

<head>
<title><?php echo $pageTitle; ?></title>
<meta name="description" content="">
	<?php require_once('view/common-html-head.php'); ?>  
	<link rel="stylesheet" href="<?php echo WORK_ROOT; ?>view/styles/style.css">
</head>

<body>
	<div class="app layout-fixed-header">
	<?php require_once("view/sidebar.php"); ?>  

    <!-- content panel -->
		<div class="main-panel">
	<?php require_once("view/header.php"); ?>  
  <!-- main area -->
			<div class="main-content">
				<div class="panel">
					<div class="panel-heading border">
						<ol class="breadcrumb mb0 no-padding">
							<li><?php echo $menuGroup; ?></li>
							<li class="active">Thank You!</li>
						</ol>
					</div>
					<div class="row">
						<div class="col-md-10">
							<div class="widget bg-white">
								<div class="row row-margin">
									<span class="col-md-10">

<?php

if ($order_status === "Success") {
	$institutionObj = new Institution ();
	if (empty ( $paymentStatus )) {
		$staffObj = new Staff ();
		$numberOfSms = $responseMap ['amount'] / SMS_COST;
		$newSmsCredit = $numberOfSms + $currentInstitution [0] ["sms_credit"];
		$institutionObj->updateSmsCredit ( $newSmsCredit, $currentInstitution [0] ["id"] );
		
		$pymtOrderObj = new PymtOrder ();
		$pymtOrderObj->updateSmsCredit ( $newSmsCredit, $responseMap ["order_id"] );
	}
	$instNow = $institutionObj->getByID ( $currentInstitution [0] ["id"]);
	echo "Thank you for shopping with us. Your transaction is successful and the Order ID is " . $responseMap ['order_id'] . 
	". Your current SMS credit balance is " . $instNow [0] ["sms_credit"].".";
} else if ($order_status === "Aborted") {
	echo "Thank you for shopping with us. We will keep you posted regarding the status of your order.";
} else if ($order_status === "Failure") {
	echo "Thank you for shopping with us. However, the transaction has been declined.";
} else {
	echo "Security Error. Illegal access detected";
}

?>
</span>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
			<!-- /main area -->
		</div>
		<!-- /content panel -->

	<?php require_once("view/footer.php"); ?>
  </div>

	<?php require_once("view/common-html-body-end.php"); ?>

	<!-- initialize page scripts -->
	<script src="<?php echo WORK_ROOT; ?>view/scripts/pages/dashboard.js"></script>

	<!-- /initialize page scripts -->

</body>

</html>

