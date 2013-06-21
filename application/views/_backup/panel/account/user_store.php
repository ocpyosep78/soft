<?php
	$user_id = (isset($_GET['user_id'])) ? $_GET['user_id'] : 0;
	if (empty($user_id)) {
		header("Location: ".site_url('panel/account/user'));
		exit;
	}
	
	$user = $this->User_model->get_by_id(array('id' => $user_id));
	$array_menu = array( 'menu' => array('Account', 'User', 'Store', $user['fullname']) );
	$array_store = $this->Store_model->get_array();
?>

<?php $this->load->view( 'panel/common/meta' ); ?>
<body>
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
					<input type="hidden" name="user_id" value="<?php echo $user['id']; ?>" />
					<div class="control-group">
						<label class="control-label" for="input_store">Store</label>
						<div class="controls">
							<select id="input_store" name="store_id" class="span5">
								<?php echo ShowOption(array('Array' => $array_store, 'ArrayID' => 'id', 'ArrayTitle' => 'title')); ?>
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
						<table id="user" class="table table-striped table-bordered dTableR">
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
            </div>
        </div>
		<?php $this->load->view( 'panel/common/sidebar' ); ?>
    </div>
	
	<?php $this->load->view( 'panel/common/js' ); ?>
	<script>
		$(document).ready(function() {
			var grid_user = null;
			setTimeout('$("html").removeClass("js")', 300);
			
			// user
			var RawUser = $('#RawUser').text();
			eval('var user = ' + RawUser);
			
			Func.InitForm({
				Container: '#WinStore',
				rule: { store_id: { required: true } }
            });
			
			$('.AddStore').click(function() {
				$('#WinStore form')[0].reset()
				$('#WinStore input[name="id"]').val(0);
				$('#WinStore').modal();
            });
			$('#WinStore .save').click(function() {
				if (! $('#WinStore form').valid()) {
					return;
                }
				
				var param = Site.Form.GetValue('WinStore');
				param.action = 'update';
				Func.ajax({ url: web.host + 'panel/account/user_store/action', param: param, callback: function(result) {
					if (result.status == 1) {
						Func.popup_result('.store-message', result.message);
						$('#WinStore').modal('hide');
						grid_user.load();
                    } else {
						Func.popup_error('#WinStore', result.message);
					}
                } });
            });
			$('#WinStore .cancel').click(function() {
				$('#WinStore').modal('hide');
            });
			
			function init_table() {
				grid_user = $('#user').dataTable( {
					"aaSorting": [[1, 'asc']], "sServerMethod": "POST",
					"bProcessing": true, "bServerSide": true, "sPaginationType": "bootstrap",
					"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
					"sAjaxSource": web.host + 'panel/account/user_store/grid',
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
				grid_user.load = Func.reload({ id: 'user' });
				
				$('#user').on('click','tbody td img.delete', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					
					Func.confirm_delete({
						data: { action: 'delete', id: record.id },
						url: web.host + 'panel/account/user_store/action',
						grid: grid_user, cnt_mesage: '.store-message'
                    });
                });
            }
			init_table();
        });
    </script>
</body>
</html>