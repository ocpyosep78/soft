<?php
	// user
	$user = $this->User_model->get_session();
	
	// user item
	$array_item = $this->User_Item_model->get_array(array( 'user_id' => $user['id'] ));
?>

<?php $this->load->view( 'website/store/theme/calisto/common/meta' ); ?>
<body class="top">
<?php $this->load->view( 'website/store/theme/calisto/common/feature' ); ?>

<div class="main-body-wrapper">
	<?php $this->load->view( 'website/store/theme/calisto/common/header' ); ?>
	
	<div class="main-content-wrapper">
		<div class="single-full-width customer customer-order">
			<div class="main-title">
				<p class="custom-font-1"><a class="active">Download</a></p>
				<a href="<?php echo site_url('order'); ?>" class="continue">back to order history</a>
			</div>
			
			<div class="main-content item-block-3">
				<div class="content">
					<div class="checkout-item"><div class="main-title" style="background: none;"><p class="custom-font-1">Download List</p></div></div>
					
					<?php if (count($array_item) > 0) { ?>
					<div class="order-history">
						<div class="row title">
							<div class="sku">&nbsp;</div>
							<div class="product">Product</div>
							<div class="price">&nbsp;</div>
							<div class="quantity center">Action</div>
							<div class="total">&nbsp;</div>
						</div>
						<?php foreach ($array_item as $item) { ?>
						<div class="row">
							<div class="sku">&nbsp;</div>
							<div class="product"><a href="<?php echo $item['item_link']; ?>"><?php echo $item['item_title']; ?></a></div>
							<div class="price">&nbsp;</div>
							<div class="quantity center">
								<a class="show-dialog-download cursor">Download</a>
								<div class="hide"><?php echo json_encode($item); ?></div>
							</div>
							<div class="total">&nbsp;</div>
						</div>
						<?php } ?>
					</div>
					<?php } else { ?>
						<div class="order-history">
							<div class="center"><a>Anda belum memiliki item untuk didownload</a></div>
						</div>
					<?php } ?>
					
					<div class="next-step">
						<table><tr><td>
							<a href="<?php echo site_url(); ?>" class="button-1 custom-font-1 trans-1"><span>Continue shopping</span></a>
						</td></tr></table>
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>
	<script type="text/javascript">
		$('.show-dialog-download').click(function() {
			var raw = $(this).parent('div').find('.hide').text();
			eval('var record = ' + raw);
			
			Func.ajax({ url: web.host + '/ajax/view', param: { action: 'download_list', item_id: record.item_id }, is_json: 0, callback: function(view) {
				$('.quick-down .list-file').html(view);
				soulage.adjustQuickShopPopupPosition();
				$('.quick-down').css('display', 'block');
			} });
		});
	</script>
	
	<?php $this->load->view( 'website/store/theme/calisto/common/footer' ); ?>
</div>
</body>
</html>