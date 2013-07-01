<?php $this->load->view( 'website/common/meta' ); ?>
<body>
	<?php $this->load->view( 'website/common/header' ); ?>
	
	<div class="container-fluid sidebar_content">
        <div class="row-fluid">
            
            <div class="span8">
                <br />
                <div class="row-fluid">
                    <div class="span12">
                        <h2><i class="icon-key"></i>&nbsp;&nbsp;Terima Kasih</h2>
                        <p>Selamat, Anda sudah memiliki account Lintas Apps. Jika Anda memiliki pertanyaan, silahkan menghubungi kami pada link dibawah ini.</p>
                        <p><a href="<?php echo base_url('contact'); ?>">Hubungi Kami</a></p>
                    </div>
                </div>
            </div>
            
            <div class="span4 sidebar"><br />
                <?php //$this->load->view( 'website/common/info' ); ?>
            </div>
            
        </div>	
    </div>
    <?php $this->load->view( 'website/common/footer' ); ?>
</body>
</html>