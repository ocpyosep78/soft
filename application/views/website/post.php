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
		
		<form id="form-item">
			<input type="hidden" name="action" value="update" />
			
			<h3>Item Detail</h3>
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group">
						<label class="control-label">Nama Software</label>
						<div class="controls"><input type="text" class="span12" name="name" /></div>
					</div>
				</div>
				<div class="span6">
					<div class="control-group">
						<label class="control-label">Harga</label>
						<div class="controls">
							<div class="input-prepend">
								<span class="add-on">Rp.</span>
								<input class="span12" id="prependedInput" type="text" name="price" />
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
							<select class="span12" name="platform_id">
								<?php echo ShowOption(array( 'Array' => $array_platform, 'ArrayID' => 'id', 'ArrayTitle' => 'name' )); ?>
							</select>
						</div>
					</div>
				</div>
				<div class="span6">
					<div class="control-group">
						<label class="control-label">Kategori</label>
						<div class="controls">
							<select class="span12" name="category_id">
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
						<div class="controls"><textarea rows="3" class="span12" name="description"></textarea></div>
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
			<div class="row-fluid">
				<div class="span12">
					<div class="control-group">
						<label class="control-label">Upload Source</label>
						<div class="controls">
							<div id="uploadcontainer">
								<div id="filelist" style="padding: 0 0 15px 0;"></div>
								<a id="pickfiles" class="btn btn-primary btn-success">Select files</a>
								<a id="uploadfiles" class="hide">Upload files</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<h3>&nbsp;</h3>
			<a class="btn btn-primary btn-large pull-right btn-item-submit">Submit</a><br /><br />
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
	// thumbnail
	$('#form-item .btn-thumbnail').click(function() { window.iframe_thumbnail.browse() });
	
	// upload item config
	var uploader = new plupload.Uploader({
		max_file_size : '100mb', url: web.host + 'upload/file',
		browse_button : 'pickfiles', container : 'uploadcontainer',
		runtimes : 'gears,html5,flash,silverlight,browserplus',
		flash_swf_url: web.base + 'static/js/plupload/plupload.flash.swf',
		silverlight_xap_url : web.base + 'static/js/plupload/plupload.silverlight.xap'
	});
	$('#uploadfiles').click(function(e) {
		if ( $("#filelist .addedfile").length > 0 )
		uploader.start();
		return false;
	});
	uploader.init();
	
	// upload item event
	uploader.bind('FilesAdded', function(up, files) {
		$.each(files, function(i, file) {
			$('#filelist').append('<div class="addedfile uploadfile" id="' + file.id + '"><span class="filename">' + file.name + '</span> (' + plupload.formatSize(file.size) + ') <b></b>' + '</div>');
		});
		up.refresh(); // Reposition Flash/Silverlight
		$('#uploadfiles').click();
	});
	uploader.bind('UploadProgress', function(up, file) {
		$('#' + file.id + " b").html(file.percent + "%");
	});
	uploader.bind('Error', function(up, err) {
		$('#filelist').append("<div class='alert alert-error'>Error: " + err.code + ", Message: " + err.message + (err.file ? ", File: " + err.file.name : "") + "</div>");
		up.refresh(); // Reposition Flash/Silverlight
	});
	uploader.bind('FileUploaded', function(up, file, jsonresp) {
		var div = $("#"+file.id);
		var json = eval('('+jsonresp.response+')');
		
		if (json.error != null && json.error.code != null) {
			div.remove();
			Func.show_notice({ title: 'Informasi', text: json.error.message });
		} else {
			div.removeClass('addedfile').addClass('completefile').find('b').html("100%");
			div.after('<input type="hidden" name="item_file[]" value="' + json.new_dir + '/' + json.fileName + '">');
		}
	});
	
	// form
	$("#form-item").validate({
		rules: {
			name: { required: true },
			price: { required: true },
			platform_id: { required: true },
			category_id: { required: true },
			description: { required: true },
		},
		messages: {
			name: { required: 'Silahkan mengisi field ini' },
			price: { required: 'Silahkan mengisi field ini' },
			platform_id: { required: 'Silahkan mengisi field ini' },
			category_id: { required: 'Silahkan mengisi field ini' },
			description: { required: 'Silahkan mengisi field ini' },
		}
	});
	$('.btn-item-submit').click(function() {
		if (! $("#form-item").valid()) {
			return false;
		}
		
		var param = Site.Form.GetValue('form-item');
		if (param.item_file == null || param.item_file.length < 1) {
			Func.show_notice({ title: 'Informasi', text: 'Silahkan upload source file anda.' });
			return false;
		}
		
		Func.ajax({ url: web.host + 'ajax/item', param: param, callback: function(result) {
			Func.show_notice({ title: 'Informasi', text: result.message });
			if (result.status) {
				window.location = result.link_next;
			}
		} });
	});
});
</script>

</body>
</html>