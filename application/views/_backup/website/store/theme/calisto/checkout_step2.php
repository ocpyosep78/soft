<?php
	$store_name = get_store();
	$store = $this->Store_Detail_model->get_info(array('store_name' => $store_name));
	
	// item
	$array_item = $this->Cart_model->get_array();
	
	// price
	$price = $this->Cart_model->get_total_price();
	
	// payment method
	$param_payment_method = array( 'filter' => '[{"type":"numeric","comparison":"eq","value":"'.STORE_ID.'","field":"store_id"}]' );
	$array_payment_method = $this->Store_Payment_Method_model->get_array($param_payment_method);
	
	// bank account
	$param_bank_account = array( 'filter' => '[{"type":"numeric","comparison":"eq","value":"'.STORE_ID.'","field":"store_id"}]' );
	$array_bank_account = $this->Bank_Account_model->get_array($param_bank_account);
?>

<?php $this->load->view( 'website/store/theme/calisto/common/meta', array('is_checkout' => 1) ); ?>
<body class="top">
	<input type="hidden" name="PAYPAL_ID" value="<?php echo PAYPAL_ID; ?>" />
	<div class="main-wrapper">
		<div class="main-header-ie">
			<table><tr><td>
				<div class="logo">
					<a href="<?php echo site_url(); ?>" class="logo-blank custom-font-1"><span><?php echo $store['store_title']; ?></span></a>
					<span class="custom-font-1"><?php echo $store['store_logo']['content']; ?></span>
				</div>
			</td></tr></table>
		</div>
		
		<div class="main-header">
			<div class="logo">
				<a href="<?php echo site_url(); ?>" class="logo-blank custom-font-1"><span><?php echo $store['store_title']; ?></span></a>
				<span class="custom-font-1"><?php echo $store['store_logo']['content']; ?></span>
			</div>
		</div>
		
		<div class="main-content item-block-3">
			<div class="content">
				<div class="header">
					<div class="left">
						<h6>You're purchasing this</h6>
						
						<?php foreach ($array_item as $key => $item) { ?>
							<div class="item">
								<div class="image-wrapper-1">
									<div class="image">
										<a href="<?php echo $item['item_link']; ?>">
											<img src="<?php echo $item['thumbnail_link']; ?>" alt="" width="50" height="50" /></a>
									</div>
								</div>
								<div class="text">
									<h3><a href="<?php echo $item['item_link']; ?>" class="custom-font-1"><?php echo $item['title']; ?></a></h3>
									<p><?php echo $item['quantity'].' x '.$item['currency_name'].' '.$item['price_final']; ?></p>
								</div>
							</div>
						<?php } ?>
					</div>
					<div class="right custom-font-1">
						<h2><?php echo $price['price_label']; ?></h2>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="checkout-item payment" id="cart-complete">
					<div class="main-title"><p class="custom-font-1">How would you like to pay for you order?</p></div>
					<div class="payment-error hide">Maaf, toko ini belum memiliki cara pembayaran yang ada.</div>
					<div class="payment-type">
						<?php foreach ($array_payment_method as $payment) { ?>
							<?php if ($payment['payment_method_id'] == PAYPAL_ID) { ?>
								<p><label><input type="radio" name="payment_method_id" value="<?php echo $payment['payment_method_id']; ?>" /><img src="<?php echo base_url(); ?>static/theme/calisto/img/ico-paypal-2.png" /></label></p>
							<?php } else { ?>
								<p><label><input type="radio" name="payment_method_id" value="<?php echo $payment['payment_method_id']; ?>" checked="checked" /><?php echo $payment['name']; ?></label></p>
							<?php } ?>
						<?php } ?>
					</div>
					<div class="payment-details">
						<?php foreach ($array_payment_method as $payment) { ?>
							<?php if ($payment['payment_method_id'] == BANK_PAYMENT_ID) { ?>
								<div class="bank-transfer">
									<div class="account">
										<div class="bold">Daftar Bank yang tersedia</div>
									</div>
									<?php foreach ($array_bank_account as $account) { ?>
										<div class="account">
											<div class="left">Bank :</div>
											<div class="right"><?php echo $account['title']; ?></div>
											<div class="clear"></div>
											<div class="left">No Rek :</div>
											<div class="right"><?php echo $account['no_rekening']; ?></div>
											<div class="clear"></div>
											<div class="left">a.n. :</div>
											<div class="right"><?php echo $account['pemilik']; ?></div>
											<div class="clear"></div>
											<div class="logo"><img src="<?php echo $account['bank_image_link']; ?>" /></div>
										</div>
									<?php } ?>
								</div>
							<?php } ?>
						<?php } ?>
					</div>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
				
				<div class="next-step">
					<table>
						<tr>
							<td>
								<a data-link="<?php echo site_url('checkout/complete/'); ?>" class="cart-step2-submit cursor button-1 custom-font-1 trans-1"><span>Complete my purchase</span></a>
								<b>or <a href="<?php echo site_url(); ?>">Return to store</a></b>
							</td>
							<td class="hide">
								<b><a href="<?php echo site_url(); ?>">Return to store</a></b>
							</td>
						</tr>
					</table>
				</div>
				<div class="clear"></div>
			</div>
		</div>
		<script type="text/javascript">
			$('[name="payment_method_id"]').click(function() {
				var PAYPAL_ID = $('[name="PAYPAL_ID"]').val();
				
				$('.bank-transfer').show();
				if (PAYPAL_ID == $(this).val()) {
					$('.bank-transfer').hide();
				}
			});
			
			$('.cart-step2-submit').click(function() {
				var payment_method_id = $('[name=payment_method_id]:checked').val();
				
				if (payment_method_id == 2) {
					Func.ajax({ url: Site.Host + '/cart/paypal', param: { action: 'SetPaypalPayment' }, callback: function(result) {
						if (result.status)
							window.location = result.paypal_approval_url;
					} });
				} else 
                {
               
					var button = $(this);
					var p = Site.Form.GetValue('cart-complete');
                    p.payment_method_id = payment_method_id;
					p.action = 'CompleteCart';
					Func.ajax({ url: Site.Host + '/cart/ajax', param: p, callback: function(result) {
						if (result.status)
							window.location = button.data('link') + result.order_id;
					} });
				}
			});
			
			if ($('[name="payment_method_id"]').length == 0) {
				$('.payment-error').show();
				$('.next-step').find('td').eq(0).hide();
				$('.next-step').find('td').eq(1).show();
			}
		</script>
	</div>
</body>
</html>