<?php
    // pages
    $pages = $this->Pages_model->get_array();
?>
<footer>
	<div class="container-fluid">
		<div class="row-fluid footer_section_pre">
			<div class="span12">
                <div class="row text-center">
                    <?php
                        if(!empty($pages))
                        {
                            foreach($pages as $key=>$value)
                            {
                            ?>
                            <p class="" style="width:100px;display:inline-block;">
                                <a href="<?php echo base_url('pages/'.$value['name']); ?>"><?php echo $value['title']?></a>
                            </p>
                            <?php
                            }
                        }
                    ?>
                    <p class="" style="width:100px;display:inline-block;"><a href="<?php echo base_url('contact'); ?>">Hubungi Kami</a></p>
                </div>
                <div class="row">
				<p class="span12 text-center">&copy; 2013 <a href="http://simetri.in/">Simetri</a></p>
                </div>
            </div>
        </div>
    	
    </div>
</footer>

<script type="text/javascript" src="<?php echo base_url('static/theme/job_board/js/jquery-ui.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('static/theme/job_board/js/jquery.flot.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('static/theme/job_board/bootstrap/js/bootstrap.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('static/theme/job_board/js/global.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('static/theme/job_board/js/stylesheetToggle.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('static/theme/job_board/js/jquery.validate.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('static/theme/job_board/js/public.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('static/lib/gritter/jquery.gritter.min.js'); ?>"></script>
<script>
    
    
    $(function() {
        $('.input_tooltips').tooltip();
        $.stylesheetInit();
        $('#theme_switcher').val('job_blue');
        $('#theme_switcher_btn').bind('click', function(e) {
            $.stylesheetSwitch($('#theme_switcher').val());
            return false;
        } );
    });
</script>