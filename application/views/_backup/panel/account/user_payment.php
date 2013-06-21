<?php
	$array_menu = array( 'menu' => array('Account', 'User Payment') );
	
	$array_user = $this->User_model->get_array(array( 'limit' => 100 ));
?>

<?php $this->load->view( 'panel/common/meta' ); ?>
<body>
	<div id="loading_layer hide"><img src="<?php echo base_url(); ?>static/img/ajax_loader.gif" alt="" /></div>
	
	<div id="maincontainer" class="clearfix">
		<?php $this->load->view( 'panel/common/header' ); ?>
		
		<div id="WinPayment" class="modal modal-big hide fade" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-header">
				<a href="#" class="close" data-dismiss="modal">&times;</a>
				<h3>Form Bank Account</h3>
            </div>
			<div class="modal-body" style="padding-left: 0px;">
				<div class="pad-alert" style="padding-left: 15px;"></div>
				<form class="form-horizontal" style="padding-left: 0px;">
					<input type="hidden" name="action" value="update" />
					
					<div class="control-group">
						<label class="control-label" for="input_user_id">Nama User</label>
						<div class="controls">
                            <select id="input_user_id" name="user_id" class="span5">
                            <?php echo ShowOption(array('Array' => $array_user, 'ArrayID' => 'id', 'ArrayTitle' => 'fullname')); ?>
                            </select>
                        </div>
                    </div>
					<div class="control-group">
						<label class="control-label" for="input_value">Nilai</label>
						<div class="controls">
							<input type="text" id="input_value" name="value" placeholder="Nilai Payment" class="span5" rel="twipsy" />
                        </div>
                    </div>
					<div class="control-group">
						<label class="control-label" for="input_note">Catatan</label>
						<div class="controls">
							<input type="text" id="input_note" name="note" placeholder="Catatan Tambahan" class="span5" rel="twipsy" />
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
						<button class="btn btn-gebo AddPayment">Tambah</button>
                    </div>
                </div>
				
				<div class="row-fluid">
					<div class="span12">
						<div class="payment-message"></div>
						<table id="grid-payment" class="table table-striped table-bordered dTableR">
							<thead><tr>
								<th style="width: 125px;">&nbsp;</th>
								<th>Tanggal</th>
								<th>Nama</th>
								<th>Email</th>
								<th>Nilai</th>
								<th>Catatan</th>
								<th>Status</th>
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
			var grid_payment = null;
			setTimeout('$("html").removeClass("js")', 300);
			
			Func.InitForm({
				Container: '#WinPayment',
				rule: { user_id: { required: true }, value: { required: true } }
            });
			
			$('.AddPayment').click(function() {
				$('#WinPayment form')[0].reset();
				$('#WinPayment').modal();
            });
			$('#WinPayment .save').click(function() {
				if (! $('#WinPayment form').valid()) {
					return;
                }
				
				var param = Site.Form.GetValue('WinPayment');
				Func.ajax({ url: web.host + 'panel/account/user_payment/action', param: param, callback: function(result) {
					if (result.status == 1) {
						grid_payment.load();
						$('#WinPayment').modal('hide');
						Func.popup_result('.payment-message', result.message);
                    } else {
						Func.popup_error('#WinPayment', result.message);
					}
                } });
            });
			$('#WinPayment .cancel').click(function() {
				$('#WinPayment').modal('hide');
            });
			
			function init_table() {
				grid_payment = $('#grid-payment').dataTable( {
					"aaSorting": [[1, 'asc']], "sServerMethod": "POST",
					"bProcessing": true, "bServerSide": true, "sPaginationType": "bootstrap",
					"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
					"sAjaxSource": web.host + 'panel/account/user_payment/grid',
					"aoColumns": [ { "sClass": "center", "bSortable": false }, null, null, null, null, null, null ]
                } );
				grid_payment.load = Func.reload({ id: 'grid-payment' });
				
				$('#grid-payment').on('click','tbody td img.delete', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					
					Func.confirm_delete({
						data: { action: 'delete', id: record.id },
						url: web.host + 'panel/account/user_payment/action',
						grid: grid_payment, cnt_mesage: '.payment-message'
                    });
                });
				
				$('#grid-payment').on('click','tbody td img.confirm', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					
					var param = { action: 'update_deposit', id: record.id, status: 'confirm' }
					Func.ajax({ url: web.host + 'panel/account/user_payment/action', param: param, callback: function(result) {
						Func.popup_result('.payment-message', result.message);
						if (result.status == 1) {
							grid_payment.load();
							$('#WinPayment').modal('hide');
						}
					} });
                });
				
				$('#grid-payment').on('click','tbody td img.cancel', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					
					var param = { action: 'update_deposit', id: record.id, status: 'cancel' }
					Func.ajax({ url: web.host + 'panel/account/user_payment/action', param: param, callback: function(result) {
						Func.popup_result('.payment-message', result.message);
						if (result.status == 1) {
							grid_payment.load();
							$('#WinPayment').modal('hide');
						}
					} });
                });
            }
			init_table();
        });
    </script>
</body>
</html>