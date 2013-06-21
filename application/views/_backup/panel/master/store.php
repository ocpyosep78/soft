<?php
	$array_menu = array( 'menu' => array('Master', 'Store') );
	$array_user = $this->User_model->get_array(array('limit' => 100));
?>

<?php $this->load->view( 'panel/common/meta' ); ?>
<body>
	<div id="loading_layer hide"><img src="<?php echo base_url(); ?>static/img/ajax_loader.gif" alt="" /></div>
	
	<div id="maincontainer" class="clearfix">
		<?php $this->load->view( 'panel/common/header' ); ?>
		
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
							<input type="text" id="input_name" name="name" placeholder="Alias Store" class="span5" rel="twipsy" />
                        </div>
                    </div>
					<div class="control-group">
						<label class="control-label" for="input_domain">Domain</label>
						<div class="controls">
							<input type="text" id="input_domain" name="domain" placeholder="Domain Store" class="span5" rel="twipsy" />
                        </div>
                    </div>
					<div class="control-group cnt-user">
						<label class="control-label" for="input_user">User</label>
						<div class="controls">
                            <select id="input_user" name="user_id" class="span5">
								<?php echo ShowOption(array('Array' => $array_user, 'ArrayID' => 'id', 'ArrayTitle' => 'email')); ?>
                            </select>
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
				
				<div class="row-fluid">
					<div class="btn-group">
						<button class="btn btn-gebo AddStore">Tambah</button>
                    </div>
                </div>
				
				<div class="row-fluid">
					<div class="span12">
						<div class="store-message"></div>
						<table id="store" class="table table-striped table-bordered dTableR">
							<thead><tr>
								<th style="width: 50px;">&nbsp;</th>
								<th>Nama</th>
								<th>Domain</th>
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
			var grid_store = null;
			setTimeout('$("html").removeClass("js")', 300);
			
			Func.InitForm({
				Container: '#WinStore',
				rule: { name: { required: true }, domain: { required: true } }
            });
			
			$('.AddStore').click(function() {
				$('.cnt-user').show();
				$('#WinStore form')[0].reset()
				$('#WinStore [name="id"]').val(0);
				$('#WinStore [name="user_id"]').val(0);
				$('#WinStore').modal();
            });
			$('#WinStore .save').click(function() {
				if (! $('#WinStore form').valid()) {
					return;
                }
				
				var param = Site.Form.GetValue('WinStore');
				param.action = 'update';
				Func.ajax({ url: web.host + 'panel/master/store/action', param: param, callback: function(result) {
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
			
			function init_table() {
				grid_store = $('#store').dataTable( {
					"aaSorting": [[1, 'asc']], "sServerMethod": "POST",
					"bProcessing": true, "bServerSide": true, "sPaginationType": "bootstrap",
					"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
					"sAjaxSource": web.host + 'panel/master/store/grid',
					"aoColumns": [
                    { "sClass": "center", "bSortable": false },
                    null,
                    null
					]
                } );
				grid_store.load = Func.reload({ id: 'store' });
				
				$('#store').on('click','tbody td img.edit', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					$('.cnt-user').hide();
					$('#WinStore [name="id"]').val(record.id);
					$('#WinStore [name="user_id"]').val(0);
					$('#WinStore [name="title"]').val(record.title);
					$('#WinStore [name="name"]').val(record.name);
					$('#WinStore [name="domain"]').val(record.domain);
					$('#WinStore').modal();
                });
				$('#store').on('click','tbody td img.delete', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					
					Func.confirm_delete({
						data: { action: 'delete', id: record.id },
						url: web.host + 'panel/master/store/action',
						grid: grid_store, cnt_mesage: '.store-message'
                    });
                });
            }
			init_table();
        });
    </script>
</body>
</html>