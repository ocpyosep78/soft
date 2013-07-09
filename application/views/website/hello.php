<?php $this->load->view( 'website/common/meta' ); ?>
<body>
<?php $this->load->view( 'website/common/header' ); ?>

<div class="container-fluid">
	<div class="row-fluid">
		
		<div class="span12">
			<br />
			<div class="row-fluid">
				<div class="span12"><form id="form-human">
					<h2><a href="<?php echo base_url(); ?>">HOME</a> > Mulai Berjualan</h2>
					
					<h1>Income Tambahan dari Program Anda</h1>
					
					<p>Halo para coders dan designer, <br><br>
					LintasApps ditujukan buat anda, programmer dan designer yang kreatif dan tidak kenal lelah 
					menghasilkan software kreatif lokal</p>
					
					<p>Dengan LintasApps, kami berharap dapat membantu para programmer dan designer lokal
					mendapatkan income tambahan dari berjualan software. Kami sediakan penyimpanan data untuk aplikasi dan desain anda.
					Kami juga sediakan mekanisme pembayaran.</p>
					
					<p>Jadi, tunggu apa lagi, pasang aplikasi kebanggaan kamu di LintasApps dan dapatkan
					income pasif tanpa harus repot-repot.</p>
					
					<h2>Isikan teks yang tertera di gambar bawah sebelum memulai upload aplikasi.</p>
					<p style="text-align:center;">
						<img id="captchaimg" src="<?php echo base_url('static/lib/captcha/index.php'); ?>" /><br>
						<input type="text" name="captcha" />
					</p>
					<div style="clear: both;"></div>
					<p style="text-align: center;">
						<input type="submit" name="action" value="Lanjutkan" class="btn btn-primary btn-large" />
					</p>
				</div>
			</div>
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
				captcha: { required: 'Silahkan mengisikan teks di gambar yang tertera diatas' }
			}
		});
		
		$('#form-human').submit(function() {
			if (! $("#form-human").valid()) {
				return false;
			}
			
			var param = Site.Form.GetValue('form-human');
			Func.ajax({ url: web.host + 'hello/set_human', param: param, callback: function(result) {
				if (result.status) {
					window.location = web.host + 'post';
				} else {
					Func.show_notice({ title: 'Informasi', text: result.message });
					$("#captchaimg").attr('src', '<?php echo base_url('static/lib/captcha/index.php'); ?>?reload='+Math.random());
				}
			} });
			return false;
		});
	});
</script>
</body>
</html>