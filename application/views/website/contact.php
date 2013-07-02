<?php
	$array_category = $this->Category_model->get_array();
	$array_platform = $this->Platform_model->get_array();
?>

<?php $this->load->view( 'website/common/meta' ); ?>
<body>
<?php $this->load->view( 'website/common/header' ); ?>

<div class="container-fluid sidebar_content"><div class="row-fluid">
	<div class="span8">	
		<br />
		<h2><a href="<?php echo base_url(); ?>">HOME</a> > Hubungi Kami</h2>
		
		<form id="form-contact">
			<input type="hidden" name="action" value="sent_mail" />
			
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group">
						<label class="control-label">Email</label>
						<div class="controls"><input type="text" class="span12 input_tooltips" name="email" rel="twipsy" data-placement="right" title="Masukkan email Anda disini" /></div>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="control-group">
						<label class="control-label">Judul</label>
						<div class="controls"><input type="text" class="span12 input_tooltips" name="subject" data-placement="right" title="Masukkan judul Anda disini"/></div>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="control-group">
						<label class="control-label">Deskripsi</label>
						<div class="controls"><textarea rows="3" class="span12 input_tooltips" name="description" data-placement="right" title="Masukkan deskiripsi Anda disini"></textarea></div>
					</div>
				</div>
			</div>
			<h3>&nbsp;</h3>
			<a class="btn btn-primary btn-large pull-right btn-item-submit input_tooltips" data-placement="right" title="Setelah semua Anda isi, tekan kirim">Kirim</a><br /><br />
		</form>
		
	</div>
	
	<div class="span4 sidebar"><br /><br />
		<?php //$this->load->view( 'website/common/info' ); ?>
	</div>		
</div></div>

<?php $this->load->view( 'website/common/footer' ); ?>

<script>
$(document).ready(function() {
	// form
	$("#form-contact").validate({
		rules: {
			email: { required: true, email: true },
			subject: { required: true },
			description: { required: true }
		},
		messages: {
			email: { required: 'Silakan masukkan email Anda', email: 'Email tidak valid' },
			subject: { required: 'Silakan masukkan judul / subyek yang Anda inginkan' },
			description: { required: 'Silakan tambahkan deskripsi Anda' }
		}
	});
	$('.btn-item-submit').click(function() {
		if (! $("#form-contact").valid()) {
			return false;
		}
		
		var param = Site.Form.GetValue('form-contact');
		Func.ajax({ url: web.host + 'ajax/mail', param: param, callback: function(result) {
			Func.show_notice({ title: 'Informasi', text: result.message });
			if (result.status) {
				$("#form-contact")[0].reset();
			}
		} });
	});
});
</script>

</body>
</html>