<?php $this->load->view( 'website/common/meta' ); ?>
<body>
<?php $this->load->view( 'website/common/header' ); ?>

<div class="container-fluid sidebar_content"><div class="row-fluid">
	<div class="span8">	
		<br />
		<h2><a href="<?php echo base_url(); ?>">HOME</a> > Hubungi Kami</h2>
		
		<p>Silahkan isi form dibawah untuk mengkontak kami</p>
		
		<form id="form-contact">
			<input type="hidden" name="action" value="sent_mail" />
			
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group">
						<label class="control-label">Alamat E-Mail Anda</label>
						<div class="controls"><input type="text" class="span12 input_tooltips" name="email" rel="twipsy" data-placement="right" title="Masukkan email agar dapat kami balas" /></div>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="control-group">
						<label class="control-label">Judul Surat</label>
						<div class="controls"><input type="text" class="span12 input_tooltips" name="subject" data-placement="right" title="Isikan judul pesan anda"/></div>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="control-group">
						<label class="control-label">Isi Pesan</label>
						<div class="controls"><textarea rows="3" class="span12 input_tooltips" name="description" data-placement="right" title="Masukkan pesan anda disini"></textarea></div>
					</div>
				</div>
			</div>
			<h3>&nbsp;</h3>
			<a class="btn btn-primary btn-large pull-right btn-item-submit input_tooltips" data-placement="right" title="Setelah semua Anda isi, tekan kirim">Kirim</a><br /><br />
		</form>
		
	</div>
	
	<div class="span4 sidebar"><br /><br />
			<div id="simetricontact">
			
			<h2><b>PT Sinar Media Tiga</b></h2>
			<h3><b>Malang, Indonesia</b></h3>
			<p>Raya Sulfat 96C, Malang,<br>
				Indonesia, 65123<br>
				Phone: +62 341 406 633<br>
				Phone: +62 813 30 40 8000<br>
				Phone: +62 813 23 76 9000<br>
			<span class="x">info@lintasapps.com</span></p>
			
			<h2><b>Branch Office</b></h2>
			<h3><b>Balikpapan, Indonesia</b></h3>
			<p>Jalan Mayjend Sutoyo RT. 41 No. 98 (Bukit Sion), Balikpapan,<br>
			Kaltim<br>
			Phone: +62 542 714 8715<br>
			Phone: +62 812 335 5768
			</p>
			
			<br>
			
			<h3><b>Virginia, USA</b></h3>
			<p>3905 Fish Pond Lane,<br>
				Glen Allen, VA, 23060<br>
				USA<br>
			Phone: +1 804 360 2634<br>
			Phone: +1 571 278 0743
			</p>
		</div>
		
		<div class="social">
			<div class="map">
				<iframe width="280" height="280" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.co.id/maps?q=-7.960287,112.649775&amp;num=1&amp;ie=UTF8&amp;t=m&amp;ll=-7.960127,112.649431&amp;spn=0.025501,0.025749&amp;z=14&amp;output=embed"></iframe><br><small><a href="http://maps.google.co.id/maps?q=-7.960287,112.649775&amp;num=1&amp;ie=UTF8&amp;t=m&amp;ll=-7.960127,112.649431&amp;spn=0.025501,0.025749&amp;z=14&amp;source=embed" style="color:#0000FF;text-align:left">View Larger Map</a></small>
			</div>
		</div>
		
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
			email: { required: 'Silahkan mengisi field ini', email: 'Email tidak valid' },
			subject: { required: 'Silahkan mengisi field ini' },
			description: { required: 'Silahkan mengisi field ini' }
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