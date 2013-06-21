<?php
	$array_menu = array( 'menu' => array('Store', 'Config') );
	
	$user = $this->User_model->get_session();
?>

<?php $this->load->view( 'panel/common/meta' ); ?>
<body>
	<style>
		#store .delete { display: none; }
	</style>
	<div id="loading_layer hide"><img src="<?php echo base_url(); ?>static/img/ajax_loader.gif" alt="" /></div>
	
	<div id="maincontainer" class="clearfix">
		<?php $this->load->view( 'panel/common/header' ); ?>
		<div class="hide">
			<div id="RawUser"><?php echo json_encode($user); ?></div>
		</div>
		
		<div id="WinStore" class="modal modal-big hide fade" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">
			<div class="modal-header">
				<a href="#" class="close" data-dismiss="modal">&times;</a>
				<h3>Form Store</h3>
            </div>
			<div class="modal-body" style="padding-left: 0px;">
				<div class="pad-alert" style="padding-left: 15px;"></div>
				<form class="form-horizontal" style="padding-left: 0px;">
					<input type="hidden" name="id" value="0" />
					<div class="control-group">
						<label class="control-label" for="input_title">Nama</label>
						<div class="controls">
							<input type="text" id="input_title" name="title" placeholder="Nama Store" class="span5" rel="twipsy" />
                        </div>
                    </div>
					<div class="control-group">
						<label class="control-label" for="input_name">Alias</label>
						<div class="controls">
							<input type="text" id="input_name" name="name" placeholder="Alias Store" class="span5" rel="twipsy" readonly="readonly" />
                        </div>
                    </div>
					<div class="control-group">
						<label class="control-label" for="input_domain">Domain</label>
						<div class="controls">
							<input type="text" id="input_domain" name="domain" placeholder="Domain Store" class="span5" rel="twipsy" />
                        </div>
                    </div>
                </form>
            </div>
			<div class="modal-footer">
				<a class="btn cursor cancel">Cancel</a>
				<a class="btn cursor save btn-primary">OK</a>
            </div>
        </div>
		
		<div id="WinStoreDetail" class="modal modal-bigest hide fade" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">
			<div class="modal-header">
				<a href="#" class="close" data-dismiss="modal">&times;</a>
				<h3>Form Config Detail</h3>
            </div>
			<div class="modal-body" style="padding-left: 0px;">
				<div class="pad-alert" style="padding-left: 15px;"></div>
				<form class="form-horizontal" style="padding-left: 0px;">
					<input type="hidden" name="id" value="0" />
					<div class="control-group">
						<label class="control-label" for="input_detail_title">Nama</label>
						<div class="controls">
							<input type="text" id="input_detail_title" name="title" placeholder="Nama Config" class="span7" rel="twipsy" readonly="readonly" />
                        </div>
                    </div>
					<div class="control-group">
						<label class="control-label" for="input_detail_name">Alias</label>
						<div class="controls">
							<input type="text" id="input_detail_name" name="name" placeholder="Alias Store" class="span7" rel="twipsy" readonly="readonly" />
                        </div>
                    </div>
					<div class="control-group">
						<label class="control-label" for="input_detail_content">Content</label>
						<div class="controls">
							<textarea id="input_detail_content" name="content" class="tinymce" style="height: 300px; width: 90%;"></textarea>
                        </div>
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
				
				<div class="label-big">Store</div>
				<div class="row-fluid">
					<div class="span12">
						<div class="store-message"></div>
						<table id="store" class="table table-striped table-bordered dTableR">
							<thead><tr>
								<th style="width: 50px;">&nbsp;</th>
								<th>Nama</th>
								<th>Alias</th>
								<th>Domain</th>
							</tr></thead>
							<tbody><tr><td class="dataTables_empty">Loading data from server</td></tr></tbody>
                        </table>
                    </div>
                </div>
				
				<div class="label-big">Store Detail</div>
				<div class="row-fluid">
					<div class="span12">
						<div class="store-detail-message"></div>
						<table id="store-detail" class="table table-striped table-bordered dTableR">
							<thead><tr>
								<th style="width: 50px;">&nbsp;</th>
								<th style="width: 150px;">Nama</th>
								<th>Content</th>
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
			var grid_store = grid_store_detail = null;
			setTimeout('$("html").removeClass("js")', 300);
			
			// user
			var RawUser = $('#RawUser').text();
			eval('var user = ' + RawUser);
			
			// region store
			Func.InitForm({
				Container: '#WinStore',
				rule: { title: { required: true }, name: { required: true }, domain: { required: true } }
            });
			
			$('#WinStore input[name="title"]').keyup(function() { $('#WinStore input[name="name"]').val(Func.GetName($(this).val())); });
			$('#WinStore .save').click(function() {
				if (! $('#WinStore form').valid()) {
					return;
                }
				
				var param = Site.Form.GetValue('WinStore');
				param.action = 'update';
				Func.ajax({ url: web.host + 'panel/store/store/action', param: param, callback: function(result) {
					if (result.status == 1) {
						Func.popup_result('.store-message', result.message);
						$('#WinStore').modal('hide');
						grid_store.load();
                    } else {
						Func.popup_error('#WinStore', result.message);
					}
                } });
            });
			$('#WinStore .cancel').click(function() {
				$('#WinStore').modal('hide');
            });
			
			function init_store() {
				grid_store = $('#store').dataTable( {
					"aaSorting": [[1, 'asc']], "sServerMethod": "POST",
					"bProcessing": true, "bServerSide": true, "sPaginationType": "bootstrap",
					"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
					"sAjaxSource": web.host + 'panel/store/store/grid',
					"fnServerParams": function ( aoData ) {
						aoData.push( { "name": "user_id", "value": user.id } );
					},
					"aoColumns": [
                    { "sClass": "center", "bSortable": false },
                    null,
                    null,
                    null
					]
                } );
				grid_store.load = Func.reload({ id: 'store' });
				
				$('#store').on('click','tbody td img.edit', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					$('#WinStore [name="id"]').val(record.store_id);
					$('#WinStore [name="name"]').val(record.name);
					$('#WinStore [name="title"]').val(record.title);
					$('#WinStore [name="domain"]').val(record.domain);
					$('#WinStore').modal();
                });
				$('#store').on('click','tbody td img.delete', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					
					Func.confirm_delete({
						data: { action: 'delete', id: record.id },
						url: web.host + 'panel/store/store/action',
						grid: grid_store, cnt_mesage: '.store-message'
                    });
                });
            }
			init_store();
			
			// region store detail
			Func.InitForm({
				Container: '#WinStoreDetail',
				rule: { title: { required: true }, name: { required: true } }
            });
			
			$('#WinStoreDetail input[name="title"]').keyup(function() { $('#WinStoreDetail input[name="name"]').val(Func.GetName($(this).val())); });
			$('#WinStoreDetail .save').click(function() {
				if (! $('#WinStoreDetail form').valid()) {
					return;
                }
				
				var param = Site.Form.GetValue('WinStoreDetail');
				param.action = 'update';
				
				// validation
				if (param.content.length >= 600) {
					Func.popup_error('#WinStoreDetail', 'Isi content maksimal 600 html karakter');
					return false;
				}
				
				Func.ajax({ url: web.host + 'panel/store/store_detail/action', param: param, callback: function(result) {
					if (result.status == 1) {
						Func.popup_result('.store-detail-message', result.message);
						$('#WinStoreDetail').modal('hide');
						grid_store_detail.load();
                    } else {
						Func.popup_error('#WinStoreDetail', result.message);
					}
                } });
            });
			$('#WinStoreDetail .cancel').click(function() {
				$('#WinStoreDetail').modal('hide');
            });
			
			function init_store_detail() {
				grid_store_detail = $('#store-detail').dataTable( {
					"aaSorting": [[1, 'asc']], "sServerMethod": "POST",
					"bProcessing": true, "bServerSide": true, "sPaginationType": "bootstrap",
					"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
					"sAjaxSource": web.host + 'panel/store/store_detail/grid',
					"fnServerParams": function ( aoData ) {
						aoData.push( { "name": "store_id", "value": user.store_active.store_id } );
					},
					"aoColumns": [
                    { "sClass": "center", "bSortable": false },
                    null,
                    null
					]
                } );
				grid_store_detail.load = Func.reload({ id: 'store-detail' });
				
				$('#store-detail').on('click','tbody td img.edit', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					
					$('#WinStoreDetail [name="id"]').val(record.id);
					$('#WinStoreDetail [name="name"]').val(record.name);
					$('#WinStoreDetail [name="title"]').val(record.title);
					$('#WinStoreDetail [name="content"]').val(record.content_html);
					$('#WinStoreDetail').modal();
                });
            }
			init_store_detail();
        });
    </script>
</body>
</html>