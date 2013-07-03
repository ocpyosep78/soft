<?php $this->load->view( 'website/common/meta' ); ?>
<body>
<?php $this->load->view( 'website/common/header' ); ?>

<div class="container-fluid sidebar_content">
	<div class="row-fluid">
		
		<div class="span8"><br />
			<div class="row-fluid">
				<div class="span12"><form id="form-human">
					<h2><a href="<?php echo base_url(); ?>">HOME</a> > Validasi</h2>
					<p>Maaf kami harus bertanya, mesukkan capthca barikut :</p>
					<p>
						<img src="<?php echo base_url('static/lib/captcha/index.php'); ?>" style="float: left;" />
						<input type="text" name="captcha" />
					</p>
					<div style="clear: both;"></div>
					<p style="text-align: center;">
						<input type="submit" name="action" value="Check" />
					</p>
				</div>
			</div>
		</div>
		
		<div class="span4 sidebar"><br />
			<?php //$this->load->view( 'website/common/info' ); ?>
		</div>
		
	</div>	
</div>
<?php $this->load->view( 'website/common/footer' ); ?>

<script>
	$(document).ready(function() {
		$("#form-human").validate({
			rules: {
				captcha: { required: true }
			},
			messages: {
				captcha: { required: 'Silahkan mengisi field ini' }
			}
		});
		
		$('#form-human').submit(function() {
			if (! $("#form-human").valid()) {
				return false;
			}
			
			var param = Site.Form.GetValue('form-human');
			Func.ajax({ url: web.host + 'capthca/set_human', param: param, callback: function(result) {
				if (result.status) {
					window.location = web.host + 'post';
				} else {
					Func.show_notice({ title: 'Informasi', text: result.message });
				}
			} });
			return false;
		});
	});
</script>
</body>
</html>