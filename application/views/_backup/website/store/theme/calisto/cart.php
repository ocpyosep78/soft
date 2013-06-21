<?php
	$store_name = get_store();
	$store = $this->Store_Detail_model->get_info(array('store_name' => $store_name));
	
	$array_cart = $this->Cart_model->get_array();
	$cart_note = $this->Cart_model->get_cart_note();
	
	// cart
	$item_count = $this->Cart_model->get_count();
?>

<?php $this->load->view( 'website/store/theme/calisto/common/meta' ); ?>
<body class="top">
	<?php $this->load->view( 'website/store/theme/calisto/common/feature' ); ?>
	
	<div class="main-body-wrapper">
		<?php $this->load->view( 'website/store/theme/calisto/common/header' ); ?>
		
		<div class="main-content-wrapper">
			<div class="cart-wrapper">
				<div class="main-title">
					<p class="custom-font-1">Shopping cart</p>
					<a href="<?php echo site_url(); ?>" class="continue">continue shopping</a>
				</div>
				<div class="cart-titles">
					<p class="item-image">Product name</p>
					<p class="quantity">Quantity</p>
					<p class="price">Unit Price</p>
				</div>
				
				<form action="">
					<div class="cart-items">
						<?php foreach ($array_cart as $item) { ?>
							<div class="row">
								<div class="item-image">
									<div class="image-wrapper-1">
										<div class="image">
											<div class="image-overlay-1 trans-1">
												<table><tr><td>
													<a href="<?php echo $item['item_link']; ?>" class="button-2 trans-1"></a>
												</td></tr></table>
											</div>
											<a href="<?php echo $item['item_link']; ?>"><img src="<?php echo $item['thumbnail_link']; ?>" alt="" width="94" height="94" /></a>
										</div>
									</div>
									<div class="clear"></div>
								</div>
								<div class="desc">
									<h3 class="custom-font-1"><a href="<?php echo $item['item_link']; ?>"><?php echo $item['title']; ?></a></h3>
									<h4 class="custom-font-1">
										<a href="<?php echo site_url(); ?>">Home</a>
										<?php if (!empty($item['catalog_title'])) { ?>
											<span>/</span>
											<a href="<?php echo $item['catalog_link']; ?>"><?php echo $item['catalog_title']; ?></a>
										<?php } ?>
										<?php if (!empty($item['category_link'])) { ?>
											<span>/</span>
											<a href="<?php echo $item['category_link']; ?>"><?php echo $item['category_title']; ?></a>
										<?php } ?>
									</h4>
								</div>
								<div class="quantity">
									<input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>" />
									<input type="text" class="input-text-1 count" name="quantity" value="<?php echo $item['quantity']; ?>" readonly="readonly" />
									<!--
									<a href="#" class="button-1 custom-font-1 trans-1 plus"><span></span></a>
									<a href="#" class="button-1 custom-font-1 trans-1 minus"><span></span></a>
									-->
								</div>
								<div class="price custom-font-1"><?php echo $item['price_label']; ?></div>
								<div class="remove"><a class="cursor">remove</a></div>
								<div class="clear"></div>
							</div>
						<?php } ?>
						
						<div class="row">
							<div class="note">
								<div>
									<p><b>Note for the seller:</b></p>
									<p>(optional)</p>
								</div>
								<textarea class="textarea-1" name="nota_note"><?php echo @$cart_note['nota_note']; ?></textarea>
							</div>
							<div class="total">
								<div class="checkout">
									<p>
										<s>Total payment:</s>
										<b class="custom-font-1 _total_payment">0</b>
									</p>
									<?php if ($item_count > 0) { ?>
									<p>
										<a data-link="<?php echo site_url('checkout/step/1'); ?>" class="cart-note-submit cursor button-3 custom-font-1 trans-1"><span>Proceed to checkout</span></a>
									</p>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="clear"></div>
		</div>
		<script type="text/javascript">
			$('.cart-note-submit').click(function() {
				var button = $(this);
				var p = { action: 'UpdateCartNote', nota_note: $('textarea[name="nota_note"]').val() };
				Func.ajax({ url: Site.Host + '/cart/ajax', param: p, callback: function(result) {
					if (result.status)
						window.location = button.data('link');
				} });
			});
		</script>
		
		<?php $this->load->view( 'website/store/theme/calisto/common/footer' ); ?>
	</div>
</body>
</html>