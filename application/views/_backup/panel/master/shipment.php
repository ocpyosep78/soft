<?php
	$array_menu = array( 'menu' => array('Master', 'Shipment    ') );
	
	$user = $this->User_model->get_session();
	
	// id 	store_id 	title 	content 	image 	active
?>

<?php $this->load->view( 'panel/common/meta' ); ?>
<body>
	<div id="loading_layer hide"><img src="<?php echo base_url(); ?>static/img/ajax_loader.gif" /></div>
	
	<div id="maincontainer" class="clearfix">
		<?php $this->load->view( 'panel/common/header' ); ?>
		<div class="hide">
			<div id="RawUser"><?php echo json_encode($user); ?></div>
		</div>
		
		<div id="WinShipment" class="modal modal-big hide fade" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">
			<div class="modal-header">
				<a href="#" class="close" data-dismiss="modal">&times;</a>
				<h3>Form Shipment</h3>
            </div>
			<div class="modal-body" style="padding-left: 0px;">
				<div class="pad-alert" style="padding-left: 15px;"></div>
				<form class="form-horizontal" style="padding-left: 0px;">
					<input type="hidden" name="id" value="0" />
					<input type="hidden" name="store_id" value="0" />
					<div class="control-group">
						<label class="control-label" for="input_title">Title</label>
						<div class="controls">
							<input type="text" id="input_title" name="title" placeholder="Title" class="span5" rel="twipsy" />
                        </div>
                    </div>
					<div class="control-group">
						<label class="control-label">Image</label>
						<div class="controls relative">
							<input type="text" name="image" placeholder="Shipment" class="span4" />
							<div class="upload_single">
								<iframe frameborder="0" src="<?php echo site_url('panel/upload/upload_single?callback=image_slide'); ?>" class="iframe"></iframe>
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
			var grid_shipment = null;
			setTimeout('$("html").removeClass("js")', 300);
			
			// user
			var RawUser = $('#RawUser').text();
			eval('var user = ' + RawUser);
			
			image_slide = function(p) {
				$('[name="image"]').val(p.filename);
			}
			
			Func.InitForm({
				Container: '#WinShipment',
				rule: { title: { required: true }, image: { required: true } }
            });
			
			$('.AddBlogStatus').click(function() {
				$('#WinShipment form')[0].reset()
				$('#WinShipment [name="id"]').val(0);
				$('#WinShipment').modal();
            });
			$('#WinShipment .save').click(function() {
				if (! $('#WinShipment form').valid()) {
					return;
                }
				
				var param = Site.Form.GetValue('WinShipment');
				param.action = 'update';
				Func.ajax({ url: web.host + 'panel/master/shipment/action', param: param, callback: function(result) {
					if (result.status == 1) {
						Func.popup_result('.store-image-message', result.message);
						$('#WinShipment').modal('hide');
						grid_shipment.load();
                    } else {
						Func.popup_error('#WinShipment', result.message);
					}
                } });
            });
			$('#WinShipment .cancel').click(function() {
				$('#WinShipment').modal('hide');
            });
			
			function init_table() {
				grid_shipment = $('#image_slide').dataTable( {
					"aaSorting": [[1, 'asc']], "sServerMethod": "POST",
					"bProcessing": true, "bServerSide": true, "sPaginationType": "bootstrap",
					"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
					"sAjaxSource": web.host + 'panel/master/shipment/grid',
					"aoColumns": [
                    { "sClass": "center", "bSortable": false },
                    null,
                    null
					]
                } );
				grid_shipment.load = Func.reload({ id: 'image_slide' });
				
				$('#image_slide').on('click','tbody td img.edit', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					$('#WinShipment [name="id"]').val(record.id);
					$('#WinShipment [name="title"]').val(record.title);
					$('#WinShipment [name="image"]').val(record.image);
					$('#WinShipment [name="active"]').attr('checked', (record.active == 1))
					$('#WinShipment').modal();
                });
				$('#image_slide').on('click','tbody td img.delete', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					
					Func.confirm_delete({
						data: { action: 'delete', id: record.id , image : record.image},
						url: web.host + 'panel/master/shipment/action',
						grid: grid_shipment, cnt_mesage: '.store-image-message'
                    });
                });
            }
			init_table();
        });
    </script>
</body>
</html>