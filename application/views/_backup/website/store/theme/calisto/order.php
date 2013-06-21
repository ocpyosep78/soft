<?php
	$store_name = get_store();
	$store = $this->Store_Detail_model->get_info(array('store_name' => $store_name));
	
	// user
	$user = $this->User_model->get_session();
	$is_login = $this->User_model->is_login();
	
	// user nota
	$param_nota = array(
		'filter' => '['.
			'{"type":"numeric","comparison":"eq","value":"'.$user['id'].'","field":"Nota.user_id"},'.
			'{"type":"numeric","comparison":"eq","value":"'.$store['store_id'].'","field":"Nota.store_id"}'.
		']',
		'sort' => '[{"property":"Nota.id","direction":"DESC"}]'
	);
	$array_nota = $this->Nota_model->get_array($param_nota);
?>

<?php $this->load->view( 'website/store/theme/calisto/common/meta' ); ?>
<body class="top">
	<?php $this->load->view( 'website/store/theme/calisto/common/feature' ); ?>
	
	<div class="main-body-wrapper">
		<?php $this->load->view( 'website/store/theme/calisto/common/header' ); ?>
		
		<div class="main-content-wrapper">
			<div class="main-title"><p class="custom-font-1">Order history</p></div>
			
			<div class="single-full-width customer">
				<div class="order-history" style="margin: 0 auto; float: none;">
					<div class="row title">
						<div class="order">Order</div>
						<div class="date">Date</div>
						<div class="payment">Payment status</div>
						<div class="fulfillment">Pembayaran</div>
						<div class="total">Total</div>
					</div>
					<?php foreach ($array_nota as $nota) { ?>
						<div class="row">
							<div class="order">
								<a href="<?php echo $nota['nota_link']; ?>">#<?php echo $nota['id']; ?></a></div>
							<div class="date"><?php echo GetFormatDate($nota['nota_date']); ?></div>
							<div class="payment"><?php echo $nota['status_nota_name']; ?></div>
							<div class="fulfillment"><?php echo $nota['payment_method_name']; ?></div>
							<div class="total"><?php echo $nota['nota_currency'].' '.$nota['nota_total']; ?></div>
						</div>
					<?php } ?>
				</div>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>
		
		<?php $this->load->view( 'website/store/theme/calisto/common/footer' ); ?>
	</div>

</body>
</html>