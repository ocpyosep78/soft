<?php
	$array_file = $this->Item_File_model->get_array(array( 'item_id' => $_POST['item_id'] ));
?>
<div class="row header">
	<div style="float: left; width: 50px;">&nbsp;</div>
	<div style="float: left; width: 500px;">Filename</div>
	<div style="float: left; width: 100px;">Download</div>
</div>
<?php foreach ($array_file as $file) { ?>
<div class="row">
	<div style="float: left; width: 50px;">&nbsp;</div>
	<div style="float: left; width: 500px;"><?php echo basename($file['file_name']); ?></div>
	<div style="float: left; width: 100px;"><a href="<?php echo $file['file_link']; ?>" style="color: #DA0A7B;">Download</a></div>
</div>
<?php } ?>