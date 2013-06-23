<?php
	$array_category = $this->Category_model->get_array();
	$array_platform = $this->Platform_model->get_array();
?>

<h2>Browse jobs</h2>
<div class="row-fluid"><form method="post" action="<?php echo base_url('browse'); ?>" id="form-search-main">
	<input type="hidden" name="page_no" value="1" />
	
	<div class="span12">
		<input type="text" class="span12" name="keyword" placeholder="Keyword" value="<?php echo @$_POST['keyword']; ?>" />
		<select name="category_id" class="span12">
			<option value="">Category...</option>
			<?php echo ShowOption(array( 'Array' => $array_category, 'ArrayID' => 'id', 'ArrayTitle' => 'name', 'WithEmptySelect' => 0, 'Selected' => @$_POST['category_id'] )); ?>
		</select>
		
		<select name="platform_id" class="span12">
			<option value="">Platform...</option>
			<?php echo ShowOption(array( 'Array' => $array_platform, 'ArrayID' => 'id', 'ArrayTitle' => 'name', 'WithEmptySelect' => 0, 'Selected' => @$_POST['platform_id'] )); ?>
		</select>
		<button class="btn btn-large pull-right search_btn" style="width: 110px; height: 30px;">Search</button>
	</div>
</form></div>