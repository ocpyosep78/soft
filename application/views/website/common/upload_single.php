<?php
	$callback = (!empty($_GET['callback'])) ? $_GET['callback'] : 'callback';
	$file_upload = Upload('document', $this->config->item('base_path') . '/static/upload');
	
	$file_name = $file_link = '';
	if ($file_upload['Result'] == 1) {
		$file_name = $file_upload['FileDirName'];
		$file_link = base_url('static/upload/'.$file_name);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<head>
	<script type="text/javascript" src="<?php echo base_url('static/theme/job_board/js/jquery.js'); ?>"></script>
	<script type="text/javascript">
		var callback = window.parent.<?php echo $callback; ?>;
	</script>
</head>
<body>
	<form method="post" enctype="multipart/form-data" id="form-upload">
		<input type="hidden" name="callback" value="<?php echo (strlen($file_name) == 0) ? 0 : 1; ?>">
		<input type="hidden" name="file_name" value="<?php echo $file_name; ?>">
		<input type="hidden" name="file_link" value="<?php echo $file_link; ?>">
		
		<div style="padding: 10px 0 0 0;">
			<a class="btn cursor browse btn-primary">Select File</a>
		</div>
		<div class="hide">
			<div class="line"><input type="file" name="document" size="50" /></div>
			<div class="line"><input type="submit" name="Submit" value="Ganti Foto" /></div>
		</div>
	</form>
	<script type="text/javascript">
		$('.browse').click(function() { $('[name="document"]').click(); });
		$('[name="document"]').change(function() { $('#form-upload').submit(); });
		
		// it will call by parent window
		var browse = function() { $('[name="document"]').click(); }
		
		if ($('[name="callback"]').val() == 1) {
			if (callback != null) { 
				callback({ file_name: $('[name="file_name"]').val(), file_link: $('[name="file_link"]').val() });
			}
		}
	</script>
</body>
</html>