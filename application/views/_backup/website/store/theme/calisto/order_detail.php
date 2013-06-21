<?php
	// store
	$store_name = get_store();
	$store = $this->Store_Detail_model->get_info(array('store_name' => $store_name));
	
	// Nota
	$nota_id = $this->Nota_model->get_url_nota_id();
	$nota = $this->Nota_model->get_by_id(array('id' => $nota_id));
	
	// Transaction
	$param_item = array(
		'filter' => '[{"type":"numeric","comparison":"eq","value":"'.$nota['id'].'","field":"Transaction.nota_id"}]',
		'sort' => '[{"property":"Item.title","direction":"ASC"}]'
	);
	$array_item = $this->Transaction_model->get_array($param_item);
?>

<?php $this->load->view( 'website/store/theme/calisto/common/meta' ); ?>
<body class="top">
	<?php $this->load->view( 'website/store/theme/calisto/common/feature' ); ?>
	
	<div class="main-body-wrapper">
		<?php $this->load->view( 'website/store/theme/calisto/common/header' ); ?>
		
		<div class="main-content-wrapper">
			<div class="single-full-width customer customer-order">
				<div class="main-title">
					<p class="custom-font-1">
						<a href="<?php echo site_url('order'); ?>">Account details and order history</a>
						<span>/</span>
						<a href="<?php echo $nota['nota_link']; ?>" class="active">Order #<?php echo $nota['id']; ?></a>
					</p>
					<a href="<?php echo site_url('order'); ?>" class="continue">back to order history</a>
				</div>
				
				<div class="main-content item-block-3">
					<div class="content">
						<div class="checkout-item">
							<div class="main-title"><p class="custom-font-1">Order #<?php echo $nota['id']; ?></p></div>
							<p>Placed on <?php echo GetFormatDate($nota['nota_date'], array('FormatDate' => 'd F Y, H:i')); ?></p>
						</div>
						
						<div class="order-history">
							<div class="row title">
								<div class="product">Product</div>
								<div class="sku">&nbsp;</div>
								<div class="price">Price</div>
								<div class="quantity">Quantity</div>
								<div class="total">Total</div>
							</div>
							<?php foreach ($array_item as $item) { ?>
								<div class="row">
									<div class="product"><a href="<?php echo $item['item_link']; ?>"><?php echo $item['title']; ?></a></div>
									<div class="sku">&nbsp;</div>
									<div class="price"><?php echo $item['currency'].' '.$item['price_final']; ?></div>
									<div class="quantity"><?php echo $item['quantity']; ?></div>
									<div class="total"><?php echo $item['currency'].' '.$item['total']; ?></div>
								</div>
							<?php } ?>
							<div class="row">
								<div class="product">Total:</div>
								<div class="sku">&nbsp;</div>
								<div class="price">&nbsp;</div>
								<div class="quantity">&nbsp;</div>
								<div class="total"><?php echo $nota['nota_currency'].' '.$nota['nota_total']; ?></div>
							</div>
						</div>
						
						<div class="next-step">
							<table><tr><td>
								<a href="<?php echo site_url(); ?>" class="button-1 custom-font-1 trans-1"><span>Continue shopping</span></a>
								<b>or <a href="<?php echo site_url('order'); ?>">Return to order history</a></b>
							</td></tr></table>
						</div>
						<div class="clear"></div>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>
		
		<?php $this->load->view( 'website/store/theme/calisto/common/footer' ); ?>
	</div>
</body>
</html>