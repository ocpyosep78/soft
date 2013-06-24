<?php
	preg_match('/([\d]+)/i', $_SERVER['REQUEST_URI'], $match);
	$item_id = (isset($match[1])) ? $match[1] : 0;
	$item = $this->Item_model->get_by_id(array( 'id' => $item_id ));
	
	$is_login = $this->User_model->is_login();
?>

<?php $this->load->view( 'website/common/meta' ); ?>
<body>
<?php $this->load->view( 'website/common/header' ); ?>

<div class="container-fluid sidebar_content"><div class="row-fluid">
	<div class="span8">	
		<br />
		<h2><i class="icon-suitcase"></i>&nbsp;&nbsp;<?php echo $item['name']; ?></h2>
		
		<div class="row-fluid form-tooltip"><form id="form-payment">
			<input type="hidden" name="id" value="<?php echo $item['id']; ?>" />
			<input type="hidden" name="is_login" value="<?php echo ($is_login) ? 1 : 0; ?>" />
			
			<div class="span12">
				<div>Item : <?php echo $item['name']; ?></div>
				<div>Harga : <?php echo $item['price_text']; ?></div>
				
				<?php if (! $is_login) { ?>
				<h4>Email</h4>
				Masukkan email anda :<br />
				<input type="text" name="email" value="" />
				<?php } ?>
				
				<h4>Pilih Pembayaran</h4>
				<label><input type="radio" value="paypal" name="payment" style="margin: -3px 0 0 0;" /> Paypal</label>
				
				<div style="text-align: center; padding: 0 0 20px 0;">
					<a class="cursor btn btn-primary btn-pay">Bayar</a>
				</div>
			</div>
		</form></div>
	</div>
	
	<div class="span4 sidebar"><br /><br />
		<div class="row-fluid form-tooltip">	
			<div class="span12">
				<h4>Detail</h4>
				<div>Platform : <?php echo $item['platform_name']; ?></div>
				<div>Category : <?php echo $item['category_name']; ?></div>
				<div>Pemilik : <?php echo $item['user_name']; ?></div>
			</div>	
		</div>
	</div>		
</div></div>

<?php $this->load->view( 'website/common/footer' ); ?>

<script>
$(document).ready(function() {
	var is_login = ($('[name="is_login"]').val() == 1) ? true : false;
	if (! is_login) {
		$("#form-payment").validate({
			rules: {
				email: { required: true, email: true }
			},
			messages: {
				email: { required: 'Silahkan mengisi field ini', email: 'Email anda tidak valid' }
			}
		});
	}
	
	$('.btn-pay').click(function() {
		var param = Site.Form.GetValue('form-payment');
		param.payment = $('input[name=payment]:checked').val();
		if (param.payment == null) {
			Func.show_notice({ title: 'Informasi', text: 'Harap memilih cara pembayaran' });
			return false;
		}
		
		if (! is_login && ! $("#form-payment").valid()) {
			return false;
		}
		
		$('.btn-pay').parent('div').text('Harap tunggu sebentar, pembayaran anda sedang diproses.');
		Func.ajax({ url: web.host + 'item/payment', param: param, callback: function(result) {
			if (result.status) {
				window.location = result.link_next;
			} else {
				Func.show_notice({ title: 'Informasi', text: result.message });
			}
		} });
	});
});
</script>

</body>
</html>