<?php
	$store_name = get_store();
	$store = $this->Store_Detail_model->get_info(array('store_name' => $store_name));
?>

<?php $this->load->view( 'website/store/theme/calisto/common/meta' ); ?>
<body class="top">
	<?php $this->load->view( 'website/store/theme/calisto/common/feature' ); ?>
	
		<div class="main-body-wrapper">
			<?php $this->load->view( 'website/store/theme/calisto/common/header' ); ?>
			
			<!-- BEGIN .main-content-wrapper -->
			<div class="main-content-wrapper" id="contact-page">
				<div class="main-title"><p class="custom-font-1">Contact us</p></div>
				<div class="form-message"></div>
				
				<div class="single-full-width">
					<div class="contact-form">
						<form id="form-contact">
							<p>
								<label>Your name:</label>
								<input type="text" class="input-text-1" name="form_name" />
							</p>
							<p>
								<label>Your e-mail:</label>
								<input type="text" class="input-text-1" name="form_email" />
							</p>
							<p>
								<label>Message topic:</label>
								<select name="form_topic">
									<option>Konfirmasi Pembayaran</option>
									<option>Pengiriman Paket</option>
									<option>Pertanyaan tetang barang</option>
									<option>Masalah Teknis lainnya</option>
								</select>
							</p>
							<p>
								<label>Your message:</label>
								<textarea class="textarea-1" name="form_message"></textarea>
							</p>
							<p class="submit">
								<label></label>
								<a class="button-1 custom-font-1 trans-1 cursor send-message"><span>Send message</span></a>
							</p>
						</form>
						<div class="text"><?php echo $store['contact_us']['content']; ?></div>
					</div>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>
			<script type="text/javascript">
				$('.send-message').click(function() {
					$('.form-message').hide();
					$('.input-error-wrapper').removeClass('input-error-wrapper');
					
					var form_param = Site.Form.GetValue('form-contact');
					form_param.action = 'SendMessage';
					
					var validation = true;
					if (form_param.form_name.length == 0) {
						validation = false;
						$('input[name="form_name"]').parent('p').addClass('input-error-wrapper');
						$('input[name="form_name"]').focus();
					}
					if (form_param.form_email.length == 0) {
						validation = false;
						$('input[name="form_email"]').parent('p').addClass('input-error-wrapper');
						$('input[name="form_email"]').focus();
					}
					if (form_param.form_message.length == 0) {
						validation = false;
						$('textarea[name="form_message"]').parent('p').addClass('input-error-wrapper');
						$('textarea[name="form_message"]').focus();
					}
					if (! validation) {
						return;
					}
					
					Func.ajax({ url: Site.Host + '/contact/ajax', param: form_param, callback: function(result) {
						$('.form-message').text(result.message);
						$('.form-message').slideDown(750);
						
						if (result.status) {
							$('#form-contact')[0].reset();
						}
					} });
				});
			</script>
			
			<?php $this->load->view( 'website/store/theme/calisto/common/footer' ); ?>
		</div>
	</body>
</html>