<?php
	preg_match('/\/(\d+)$/i', $_SERVER['REQUEST_URI'], $match);
	$nota_id = (isset($match[1])) ? $match[1] : 0;
	if (empty($nota_id)) {
		exit;
	}
	
	// store
	$store_name = get_store();
	$store = $this->Store_Detail_model->get_info(array('store_name' => $store_name));
	
	// nota
	$nota = $this->Nota_model->get_by_id(array('id' => $nota_id));
	
	// item
	$param = array(
		'filter' => '[{"type":"numeric","comparison":"eq","value":"'.$nota_id.'","field":"Transaction.nota_id"}]',
		'sort' => '[{"property":"Item.title","direction":"ASC"}]',
	);
	$array_item = $this->Transaction_model->get_array($param);
?>
<?php $this->load->view( 'website/store/theme/calisto/common/meta', array('is_checkout' => 1) ); ?>
<body class="top">
	<div class="main-wrapper checkout-success">
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
									<p><?php echo $item['quantity'].' x '.$item['currency'].' '.$item['price_final']; ?></p>
								</div>
							</div>
						<?php } ?>
					</div>
					
					<!--
					<div class="right custom-font-1">
						<h2>$260.99</h2>
						<h3>(including shipping for $20)</h3>
						<h4>Step 2 of 2</h4>
					</div>
					-->
					<div class="clear"></div>
				</div>
				
				<form action="#">
					<table class="message-success custom-font-1">
						<tr><td>Thank you! Your order was placed successfully!</td></tr>
					</table>
					<div class="order-id">
						Your order ID is:
						<a href="<?php echo $nota['nota_link']; ?>">#<?php echo $nota['id']; ?></a>
					</div>
					<div class="checkout-item instructions">
						<div class="main-title"><p class="custom-font-1"><?php echo $nota['payment_method_name']; ?></p></div>
						<p>Thank you for shopping at <a href="<?php echo site_url(); ?>"><?php echo $store['store_title']; ?></a></p>
					</div>

					<div class="clear"></div>

					<div class="next-step">
						<table>
							<tr>
								<td>
									<a href="<?php echo site_url(); ?>" class="button-1 custom-font-1 trans-1"><span>Continue shopping</span></a>
								</td>
							</tr>
						</table>
					</div>
				</form>
				<div class="clear"></div>
			</div>
		<!-- END .main-content -->
		</div>

	<!-- END .main-wrapper -->
	</div>
</body>
</html>