<?php
	$array_menu = array( 'menu' => array('Store', 'Image Slide') );
	
	$user = $this->User_model->get_session();
?>

<?php $this->load->view( 'panel/common/meta' ); ?>
<body>
	<div id="loading_layer hide"><img src="<?php echo base_url(); ?>static/img/ajax_loader.gif" /></div>
	
	<div id="maincontainer" class="clearfix">
		<?php $this->load->view( 'panel/common/header' ); ?>
		<div class="hide">
			<div id="RawUser"><?php echo json_encode($user); ?></div>
		</div>
		
		<div id="WinImage" class="modal modal-big hide fade" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">
			<div class="modal-header">
				<a href="#" class="close" data-dismiss="modal">&times;</a>
				<h3>Form Image Slide</h3>
            </div>
			<div class="modal-body" style="padding-left: 0px;">
				<div class="pad-alert" style="padding-left: 15px;"></div>
				<form class="form-horizontal" style="padding-left: 0px;">
					<input type="hidden" name="id" value="0" />
					<input type="hidden" name="store_id" value="0" />
					<div class="control-group">
						<label class="control-label" for="input_title">Judul</label>
						<div class="controls">
							<input type="text" id="input_title" name="title" placeholder="Judul" class="span5" rel="twipsy" />
                        </div>
                    </div>
					<div class="control-group">
						<label class="control-label" for="input_content">Deskripsi</label>
						<div class="controls">
							<input type="text" id="input_content" name="content" placeholder="Deskripsi Singkat" class="span5" rel="twipsy" />
                        </div>
                    </div>
					<div class="control-group">
						<label class="control-label">Image</label>
						<div class="controls relative">
							<input type="text" name="image" placeholder="Image Slide" class="span4" />
							<div class="upload_single">
								<iframe frameborder="0" src="<?php echo site_url('panel/upload/upload_single?callback=image_slide'); ?>" class="iframe" scrolling="no"></iframe>
							</div>
                        </div>
                    </div>
					<div class="control-group">
						<label class="control-label">&nbsp;</label>
						<div class="controls"><label class="checkbox"><input type="checkbox" value="1" name="active"> Active</label></div>
                    </div>
                </form>
            </div>
			<div class="modal-footer">
				<a class="btn cursor cancel">Cancel</a>
				<a class="btn cursor save btn-primary">OK</a>
            </div>
        </div>
		
		<div id="contentwrapper">
			<div class="main_content">
				<?php $this->load->view( 'panel/common/breadcrumb', array( 'array_menu' => $array_menu ) ); ?>
				
				<div class="row-fluid">
					<div class="btn-group">
						<button class="btn btn-gebo AddBlogStatus">Tambah</button>
                    </div>
                </div>
				
				<div class="row-fluid">
					<div class="span12">
						<div class="store-image-message"></div>
						<table id="image_slide" class="table table-striped table-bordered dTableR">
							<thead><tr>
								<th style="width: 50px;">&nbsp;</th>
								<th>Nama</th>
								<th>Deskripsi</th>
								<th>Active</th>
							</tr></thead>
							<tbody><tr><td class="dataTables_empty">Loading data from server</td></tr></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
		<?php $this->load->view( 'panel/common/sidebar' ); ?>
    </div>
	
	<?php $this->load->view( 'panel/common/js' ); ?>
	<script>
		$(document).ready(function() {
			var grid_store_image = null;
			setTimeout('$("html").removeClass("js")', 300);
			
			// user
			var RawUser = $('#RawUser').text();
			eval('var user = ' + RawUser);
			
			image_slide = function(p) {
				$('[name="image"]').val(p.filename);
			}
			
			Func.InitForm({
				Container: '#WinImage',
				rule: { title: { required: true }, image: { required: true } }
            });
			
			$('.AddBlogStatus').click(function() {
				$('#WinImage form')[0].reset()
				$('#WinImage [name="id"]').val(0);
				$('#WinImage [name="store_id"]').val(user.store_active.store_id);
				$('#WinImage').modal();
            });
			$('#WinImage .save').click(function() {
				if (! $('#WinImage form').valid()) {
					return;
                }
				
				var param = Site.Form.GetValue('WinImage');
				param.action = 'update';
				Func.ajax({ url: web.host + 'panel/store/store_image_slide/action', param: param, callback: function(result) {
					if (result.status == 1) {
						Func.popup_result('.store-image-message', result.message);
						$('#WinImage').modal('hide');
						grid_store_image.load();
                    } else {
						Func.popup_error('#WinImage', result.message);
					}
                } });
            });
			$('#WinImage .cancel').click(function() {
				$('#WinImage').modal('hide');
            });
			
			function init_table() {
				grid_store_image = $('#image_slide').dataTable( {
					"aaSorting": [[1, 'asc']], "sServerMethod": "POST",
					"bProcessing": true, "bServerSide": true, "sPaginationType": "bootstrap",
					"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
					"sAjaxSource": web.host + 'panel/store/store_image_slide/grid',
					"aoColumns": [
                    { "sClass": "center", "bSortable": false },
                    null,
                    null,
                    null
					]
                } );
				grid_store_image.load = Func.reload({ id: 'image_slide' });
				
				$('#image_slide').on('click','tbody td img.edit', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					$('#WinImage [name="id"]').val(record.id);
					$('#WinImage [name="store_id"]').val(record.store_id);
					$('#WinImage [name="title"]').val(record.title);
					$('#WinImage [name="content"]').val(record.content);
					$('#WinImage [name="image"]').val(record.image);
					$('#WinImage [name="active"]').attr('checked', (record.active == 1))
					$('#WinImage').modal();
                });
				$('#image_slide').on('click','tbody td img.delete', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					
					Func.confirm_delete({
						data: { action: 'delete', id: record.id, image : record.image },
						url: web.host + 'panel/store/store_image_slide/action',
						grid: grid_store_image, cnt_mesage: '.store-image-message'
                    });
                });
            }
			init_table();
        });
    </script>
</body>
</html>