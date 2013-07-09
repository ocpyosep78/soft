<?php $this->load->view( 'website/common/meta' ); ?>
<body>
	<?php $this->load->view( 'website/common/header' ); ?>
	
	<div class="container-fluid">
        <div class="row-fluid">
            
            <div class="span12">
                <br />
                <div class="row-fluid">
                    <div class="span12">
                        <h2><i class="icon-key"></i>&nbsp;&nbsp;Terima Kasih</h2>
                        <p>Selamat, Anda sudah memiliki account LintasApps.com. E-mail konfirmasi yang berisi info akun dan link login telah dikirimkan ke alamat e-mail anda.</p>
						<p>Jika Anda memiliki pertanyaan, silahkan menghubungi kami pada link dibawah ini.</p>
                        <p><a href="<?php echo base_url('contact'); ?>">Hubungi Kami</a></p>
						
						<p>Anda bisa <a href="<?php echo base_url('login'); ?>">login disini</a>.</p>
                    </div>
                </div>
            </div>
            
        </div>	
    </div>
    <?php $this->load->view( 'website/common/footer' ); ?>
</body>
</html>