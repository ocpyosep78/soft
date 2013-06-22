<?php
	preg_match('/([\d]+)/i', $_SERVER['REQUEST_URI'], $match);
	$invoice_no = (isset($match[1])) ? $match[1] : 0;
	$item = $this->User_Item_model->get_by_id(array( 'invoice_no' => $invoice_no ));
?>

<?php $this->load->view( 'website/common/meta' ); ?>
<body>
<?php $this->load->view( 'website/common/header' ); ?>

<div class="container-fluid sidebar_content"><div class="row-fluid">
	<div class="span8">	
		<br />
		<h2><i class="icon-suitcase"></i>&nbsp;&nbsp; INVOICE NO <?php echo $item['invoice_no']; ?></h2>
		
		<div class="row-fluid form-tooltip">
			<div class="span12">
				<h4>Terima kasih, berikut invoice anda:</h4>
				<div>No : <?php echo $item['invoice_no']; ?></div>
				<div>Nama : <?php echo $item['user_name']; ?></div>
				<div>Item : <?php echo $item['item_name']; ?> | <?php echo $item['price_text']; ?></div>
				<div>Bayar melalui : <?php echo $item['payment_name']; ?></div>
				
				<h4><a href="">Download</a></h4>
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
	$('.btn-pay').click(function() {
		var param = Site.Form.GetValue('form-payment');
		param.payment = $('input[name=payment]:checked').val();
		if (param.payment == null) {
			Func.show_notice({ title: 'Informasi', text: 'Harap memilih cara pembayaran' });
			return false;
		}
		
		$('.btn-pay').parent('div').text('Harap tunggu sebentar, pembayaran anda sedang diproses.');
		Func.ajax({ url: web.host + 'item/payment', param: param, callback: function(result) {
			Func.show_notice({ title: 'Informasi', text: result.message });
			if (result.status) {
				window.location = result.link_next;
			}
		} });
	});
});
</script>

</body>
</html>