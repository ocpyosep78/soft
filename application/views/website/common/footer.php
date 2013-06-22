<footer>
	<div class="container-fluid">
		<div class="row-fluid footer_section_pre">
			
			<div class="span12">
				<p class="copy center"><a href="typography.html">Terms</a> | <a href="typography.html">Privacy</a> | <a href="typography.html">Typography</a> | <a href="typography.html">About</a></p>
				<p class="copy center">All content &copy; 2013 <a href="http://appsarea.com/">AppsArea</a></p>
				<br />
			</div>
		</div>
    	
	</div>
</footer>

<script type="text/javascript" src="<?php echo base_url('static/theme/job_board/js/jquery.js'); ?>"></script>
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
	$.stylesheetInit();
	$('#theme_switcher').val('job_blue');
	$('#theme_switcher_btn').bind('click', function(e) {
		$.stylesheetSwitch($('#theme_switcher').val());
		return false;
	} );
});
</script>