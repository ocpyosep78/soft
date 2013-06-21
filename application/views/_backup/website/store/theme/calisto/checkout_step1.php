<?php
	$store_name = get_store();
	$store = $this->Store_Detail_model->get_info(array('store_name' => $store_name));
	
	// user
	$user = $this->User_model->get_session();
	$is_login = $this->User_model->is_login();
	
	// user address
	/*
	$param_address = array(
		'filter' => '[{"type":"numeric","comparison":"eq","value":"'.@$user['id'].'","field":"Address.user_id"}]',
		'sort' => '[{"property":"Address.name","direction":"ASC"}]'
	);
	$array_address = $this->Address_model->get_array($param_address);
	/*	*/
	$array_address = array();
	
	// item
	$array_item = $this->Cart_model->get_array();
	
	// price
	$price = $this->Cart_model->get_total_price();
	
	// country
	$array_country = $this->Country_model->get_array(array('limit' => 150));
	$array_country = array( array('id' => 99, 'code' => 'IDN', 'title' => 'Indonesia') );
?>

<?php $this->load->view( 'website/store/theme/calisto/common/meta', array('is_checkout' => 1) ); ?>
<body class="top">
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
						<h3>exclude shipping</h3>
						<h4>Step 1 of 2</h4>
					</div>
					<div class="clear"></div>
				</div>

				<form action="#">
					<?php if (! $is_login) { ?>
					<div class="checkout-item contact-email">
						<p><a href="<?php echo site_url('login'); ?>">Sign in as a member</a></p>
					</div>
					<?php } ?>
					
					<div class="checkout-item billing-address">
						<div class="main-title"><p class="custom-font-1">Billing address</p></div>
						<div class="items">
							<?php if ($is_login && count($array_address) > 0) { ?>
							<p>
								<label>&nbsp;</label>
								<select name="address_select">
									<?php echo ShowOption(array('Array' => $array_address, 'ArrayID' => 'id', 'ArrayTitle' => 'address_title')); ?>
								</select>
							</p>
							<?php } ?>
							
							<div id="billing-address">
								<p class="input_name">
									<label>Nama:</label>
									<input type="text" class="required input-text-1" name="nota_name" alt="nota_name" />
								</p>
								<p class="input_address">
									<label>Alamat:</label>
									<textarea type="text" class="required input-text-1" name="nota_address" alt="nota_address" style="height: 80px;"></textarea>
								</p>
								<p class="input_phone">
									<label>Telepon:</label>
									<input type="text" class="required input-text-1" name="nota_phone" alt="nota_phone" />
								</p>
								<p class="input_city">
									<label>Kota:</label><input type="text" class="required input-text-1" name="nota_city" alt="nota_city" />
								</p>
								<p>
									<label>Kodepos:</label>
									<input type="text" class="required input-text-1" name="nota_zipcode" alt="nota_zipcode" style="width: 100px; margin: 0 5px 0 0;" />
								</p>
								<p class="input_zipcode">
									<label>Negara:</label>
									<select style="width: 226px;" class="required" name="nota_country" alt="nota_country">
										<?php echo ShowOption(array('Array' => $array_country, 'ArrayID' => 'title', 'ArrayTitle' => 'title')); ?>
									</select>
								</p>
								<p class="input_agree checkbox">
									<label style="width: 420px;">
										<input type="checkbox" name="nota_agree" value="1" />
										Barang akan dikirim dengan tujuan alamat diatas.
									</label>
								</p>
							</div>
						</div>
					</div>

					<div class="checkout-item shipping-address">
						<div class="main-title"><p class="custom-font-1">Shipping address</p></div>
						<div class="items">
							<p class="message"><span>Items(s) will be shipped to your billing address.</span></p>
						</div>
					</div>
					<div class="clear"></div>
					
					<div class="next-step">
						<table>
							<tr>
								<td>
									<a data-link="<?php echo site_url('checkout/step/2'); ?>" class="cart-step1-submit cursor button-1 custom-font-1 trans-1"><span>Continue to next step</span></a>
									<b>or <a href="<?php echo site_url(); ?>">Return to store</a></b>
								</td>
							</tr>
						</table>
					</div>
				</form>
				<div class="clear"></div>
				<script type="text/javascript">
					$('input[name="nota_city"]').autocomplete(web.host + '/autocomplete/city');
					$('select[name="address_select"]').change(function() {
						var p = { action: 'GetByID', id: $(this).val() };
						Func.ajax({ url: Site.Host + '/address/ajax', param: p, callback: function(result) {
							$('[name="nota_name"]').val(result.name);
							$('[name="nota_address"]').val(result.address);
							$('[name="nota_phone"]').val(result.phone);
							$('[name="nota_city"]').val(result.city);
							$('[name="nota_zipcode"]').val(result.zipcode);
							$('[name="nota_country"]').val(result.country);
							$.uniform.update('select');
						} });
					});
					
					$('.cart-step1-submit').click(function() {
						$('.error').removeClass('error');
						
						// common validation
						var valid_check = true;
						var validation = Site.Form.Validation('billing-address', { });
						if (validation.length > 0) {
							valid_check = false;
							for (var i = 0; i < validation.length; i++) {
								$('#billing-address [name="' + validation[i] + '"]').parents('p').addClass('error');
							}
						}
						
						// aggrement
						if ($('input[name="nota_agree"]').attr('checked') == null) {
							valid_check = false;
							$('.input_agree').addClass('error');
						}
						
						// check validation
						if (! valid_check) {
							return;
						}
						
						var button = $(this);
						var p = Site.Form.GetValue('billing-address');
						p.action = 'UpdateCartNote';
						delete p.address_select;
						delete p.nota_agree;
						
						Func.ajax({ url: Site.Host + '/cart/ajax', param: p, callback: function(result) {
							if (result.status)
								window.location = button.data('link');
						} });
					});
				</script>
			</div>
		</div>
	</div>
</body>
</html>