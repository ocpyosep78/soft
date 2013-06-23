<?php
	preg_match('/([\d]+)/i', $_SERVER['REQUEST_URI'], $match);
	$invoice_no = (isset($match[1])) ? $match[1] : 0;
	$item = $this->User_Item_model->get_by_id(array( 'invoice_no' => $invoice_no ));
	
	$user = $this->User_model->get_session();
	
	$param_invoice = array(
		'user_id' => $user['id'],
		'sort' => '[{"property":"UserItem.invoice_no","direction":"DESC"}]',
		'limit' => 5
	);
	$array_invoice = $this->User_Item_model->get_array($param_invoice);
?>

<?php $this->load->view( 'website/common/meta' ); ?>
<body>
<?php $this->load->view( 'website/common/header' ); ?>

<div class="container-fluid sidebar_content"><div class="row-fluid">
	<div class="span8">	
		<br />
		<h2><i class="icon-suitcase"></i>&nbsp;&nbsp; INVOICE NO <?php echo $item['invoice_no']; ?></h2>
		
		<div class="row-fluid form-tooltip">
			<input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>" />
			
			<div class="span12">
				<h4>Terima kasih, berikut invoice anda:</h4>
				<div>No : <?php echo $item['invoice_no']; ?></div>
				<div>Nama : <?php echo $item['user_name']; ?></div>
				<div>Item : <?php echo $item['item_name']; ?> | <?php echo $item['price_text']; ?></div>
				<div>Bayar melalui : <?php echo $item['payment_name']; ?></div>
				
				<h4><a class="cursor btn-download">Download</a></h4>
			</div>
			
			<div class="span12" style="padding: 30px 0 0 0;">
				<h3>5 Invoice Terbaru<h3>
				<ul>
					<?php foreach ($array_invoice as $invoice) { ?>
						<li>
							<a href="<?php echo $invoice['invoice_link']; ?>">Invoice <?php echo $invoice['invoice_no']; ?></a> -
							<a href="<?php echo $invoice['item_link']; ?>"><?php echo $invoice['item_name']; ?></a>
						</li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>
	
	<div class="span4 sidebar"><br /><br />
		<div class="row-fluid form-tooltip">	
			<div class="span12">
				&nbsp;
			</div>	
		</div>
	</div>		
</div></div>

<?php $this->load->view( 'website/common/footer' ); ?>

<script>
$(document).ready(function() {
	$('.btn-download').click(function() {
		Func.force_download({ item_id: $('[name="item_id"]').val() });
	});
});
</script>

</body>
</html>