<?php $this->load->view( 'website/common/meta' ); ?>
<body>
	<?php $this->load->view( 'website/common/header' );
    $pages_name = $this->uri->segment(2);
    if(!empty($pages_name))
    {
        $pages_detail = $this->Pages_model->get_by_id(array('name'=>$pages_name));
    }
    ?>
	
	<div class="container-fluid">
        <div class="row-fluid">
            
            <div class="span12">
                <br />
                <div class="row-fluid">
                    <div class="span12">
                        <?php if(!empty($pages_detail)){?>
                            <h2><i class="icon-key"></i>&nbsp;&nbsp;<?php echo $pages_detail['title']?></h2>
							<div class="page-content">
								<?php echo $pages_detail['content']; ?>
							</div>
                        <?php } else {?>
                            <p>
                            <h3>Halaman yang Anda cari tidak ada.</h3>   
                            </p>
                            <br/>
                            <p><a href="<?php echo base_url('contact'); ?>">Hubungi Kami</a> untuk keterangan lebih lanjut.</p> 
                            <br/><br/><br/>
                        <?php } ?>
                    </div>
                </div>
            </div>
            
        </div>	
    </div>
    <?php $this->load->view( 'website/common/footer' ); ?>
</body>
</html>