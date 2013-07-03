<?php
	$order_number = $_POST['TRANSIDMERCHANT'];
	$purchase_amt = $_POST['AMOUNT'];
	$status_code = $_POST['STATUSCODE'];
	$words = $_POST['WORDS'];
	$paymentchannel = $_POST['PAYMENTCHANNEL'];
	$session_id = $_POST['SESSIONID'];
	$paymentcode = $_POST['PAYMENTCODE'];
?>

<body>
	<form name="param_pass" id="param_pass" method="post" action="<?php echo base_url('item/doku_result'); ?>">
		<input name="WORDS" type="hidden" value="<?php echo @$_POST['WORDS']; ?>">
		<input name="AMOUNT" type="hidden" value="<?php echo @$_POST['AMOUNT']; ?>">
		<input name="SESSIONID" type="hidden" value="<?php echo @$_POST['SESSIONID']; ?>">
		<input name="STATUSCODE" type="hidden" value="<?php echo @$_POST['STATUSCODE']; ?>">
		<input name="PAYMENTCODE" type="hidden" value="<?php echo @$_POST['PAYMENTCODE']; ?>">
		<input name="PAYMENTCHANNEL" type="hidden" value="<?php echo @$_POST['PAYMENTCHANNEL']; ?>">
		<input name="TRANSIDMERCHANT" type="hidden" value="<?php echo @$_POST['TRANSIDMERCHANT']; ?>">
	</form>
	<script language="JavaScript" type="text/javascript">
		document.getElementById('param_pass').submit();
	</script>
	
	<noscript>
		If you are not redirected please <a href="<?php echo base_url('contact'); ?>">click here</a> to confirm your order.
	</noscript>
</body>