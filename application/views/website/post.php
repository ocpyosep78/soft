<?php
	$array_category = $this->Category_model->get_array();
	$array_platform = $this->Platform_model->get_array();
?>

<?php $this->load->view( 'website/common/meta' ); ?>
<body>
<?php $this->load->view( 'website/common/header' ); ?>

<div class="hide">
	<iframe name="iframe_thumbnail" src="<?php echo base_url('upload?callback=thumbnail_set'); ?>"></iframe>
</div>

<div class="container-fluid sidebar_content"><div class="row-fluid">
	<div class="span8">	
		<br />
		<h2><i class="icon-suitcase"></i>&nbsp;&nbsp;Upload Item</h2>
		
		<!--	'id', 'user_id', 'item_status_id', 'name', 'description', 'price', 'thumbnail', 'filename'	-->
		
		<form id="form-item">
			<h3>Item Detail</h3>
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group">
						<label class="control-label">Nama Software</label>
						<div class="controls"><input type="text" class="span12"></div>
					</div>
				</div>
				<div class="span6">
					<div class="control-group">
						<label class="control-label">Harga</label>
						<div class="controls">
							<div class="input-prepend">
								<span class="add-on">Rp.</span>
								<input class="span12" id="prependedInput" type="text" placeholder="">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group">
						<label class="control-label">Platform</label>
						<div class="controls">
							<select id="job_type" name="job_type" class="span12" >
								<?php echo ShowOption(array( 'Array' => $array_platform, 'ArrayID' => 'id', 'ArrayTitle' => 'name' )); ?>
							</select>
						</div>
					</div>
				</div>
				<div class="span6">
					<div class="control-group">
						<label class="control-label">Kategori</label>
						<div class="controls">
							<select id="job_type" name="job_type" class="span12" >
								<?php echo ShowOption(array( 'Array' => $array_category, 'ArrayID' => 'id', 'ArrayTitle' => 'name' )); ?>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="control-group">
						<label class="control-label">Description</label>
						<div class="controls"><textarea rows="3" class="span12"></textarea></div>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="control-group">
						<label class="control-label">Screenshot</label>
						<div class="controls">
							<input type="text" class="span6" name="thumbnail" readonly="readonly" style="margin: 0px;" />
							<a class="btn btn-primary btn-success btn-thumbnail">Browse</a>
						</div>
					</div>
				</div>
			</div>
			<h3>&nbsp;</h3>
			<a class="btn btn-primary btn-large pull-right" href="post-job.html">Submit</a><br /><br />
		</form>
		
	</div>
	
	<div class="span4 sidebar">	
		<br />
		<br />
		<h3>Posting a job is Free!!!</h3>
		
		<div class="row-fluid form-tooltip">	
			
			<div class="span12">
				<h4>Reach thousands of users</h4>
				Lorem ipsum dolor sit amet, consectetur adipiscing elit. In congue turpis sed enim posuere malesuada. Aliquam a urna et dolor blandit tincidunt.
			</div>	
		</div>	
		<div class="row-fluid">
			
			<div class="span12">
				<h4>View CVs instantly</h4>
				Lorem ipsum dolor sit amet, consectetur adipiscing elit. In congue turpis sed enim posuere malesuada. Aliquam a urna et dolor blandit tincidunt.
			</div>			
		</div>			
		<div class="row-fluid">
			
			<div class="span12">
				<h4>Integrated analytics</h4>
				Lorem ipsum dolor sit amet, consectetur adipiscing elit. In congue turpis sed enim posuere malesuada. Aliquam a urna et dolor blandit tincidunt.
			</div>
		</div>
		
	</div>		
</div></div>

<?php $this->load->view( 'website/common/footer' ); ?>

<script>
var thumbnail_set = function(p) {
	$('[name="thumbnail"]').val(p.file_name);
}

$(document).ready(function() {
	$('#form-item .btn-thumbnail').click(function() { window.iframe_thumbnail.browse() });
});
</script>

</body>
</html>