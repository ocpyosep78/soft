<?php
	$array_menu = array( 'menu' => array('Store', 'Payment') );
	
	$user = $this->User_model->get_session();
	$array_payment_method = $this->Payment_Method_model->get_array();
?>

<?php $this->load->view( 'panel/common/meta' ); ?>
<body>
	<div id="loading_layer hide"><img src="<?php echo base_url(); ?>static/img/ajax_loader.gif" alt="" /></div>
	
	<div id="maincontainer" class="clearfix">
		<?php $this->load->view( 'panel/common/header' ); ?>
		<div class="hide">
			<div id="RawUser"><?php echo json_encode($user); ?></div>
		</div>
		
		<div id="WinPayment" class="modal modal-big hide fade" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">
			<div class="modal-header">
				<a href="#" class="close" data-dismiss="modal">&times;</a>
				<h3>Form Payment</h3>
            </div>
			<div class="modal-body" style="padding-left: 0px;">
				<div class="pad-alert" style="padding-left: 15px;"></div>
				<form class="form-horizontal" style="padding-left: 0px;">
					<input type="hidden" name="id" value="0" />
					<input type="hidden" name="store_id" value="0" />
					<div class="control-group">
						<label class="control-label" for="input_payment">Payment</label>
						<div class="controls">
							<select id="input_payment" name="payment_method_id" class="span5">
								<?php echo ShowOption(array('Array' => $array_payment_method, 'ArrayID' => 'id', 'ArrayTitle' => 'name')); ?>
							</select>
						</div>
                    </div>
					<div class="control-group cnt-paypal">
						<label class="control-label" for="input_client_id">Client ID</label>
						<div class="controls">
							<input type="text" id="input_client_id" name="client_id" placeholder="Client ID" class="span5" rel="twipsy" />
						</div>
					</div>
					<div class="control-group cnt-paypal">
						<label class="control-label" for="input_client_secret">Client Secret</label>
						<div class="controls">
							<input type="text" id="input_client_secret" name="client_secret" placeholder="Client Secret" class="span5" rel="twipsy" />
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
						<div class="store-payment-message"></div>
						<table id="store-payment" class="table table-striped table-bordered dTableR">
							<thead><tr>
								<th style="width: 50px;">&nbsp;</th>
								<th>Nama</th>
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
			var grid_store_payment = null;
			setTimeout('$("html").removeClass("js")', 300);
			
			// user
			var RawUser = $('#RawUser').text();
			eval('var user = ' + RawUser);
			
			Func.InitForm({
				Container: '#WinPayment',
				rule: { payment_method_id: { required: true } }
            });
			
			$('.AddPayment').click(function() {
				$('#WinPayment form')[0].reset()
				$('#WinPayment [name="id"]').val(0);
				$('#WinPayment [name="store_id"]').val(user.store_active.store_id);
				$('#input_payment').change();
				$('#WinPayment').modal();
            });
			$('#WinPayment .save').click(function() {
				if (! $('#WinPayment form').valid()) {
					return;
                }
				
				var param = Site.Form.GetValue('WinPayment');
				param.action = 'update';
				Func.ajax({ url: web.host + 'panel/store/store_payment_method/action', param: param, callback: function(result) {
					if (result.status == 1) {
						Func.popup_result('.store-payment-message', result.message);
						$('#WinPayment').modal('hide');
						grid_store_payment.load();
                    } else {
						Func.popup_error('#WinPayment', result.message);
					}
                } });
            });
			$('#WinPayment .cancel').click(function() {
				$('#WinPayment').modal('hide');
            });
			$('#input_payment').change(function() {
				var value = $('#input_payment').val();
				if (value == 2) {
					$('.cnt-paypal').show();
				} else {
					$('.cnt-paypal').hide();
				}
			});
			
			function init_table() {
				grid_store_payment = $('#store-payment').dataTable( {
					"aaSorting": [[1, 'asc']], "sServerMethod": "POST",
					"bProcessing": true, "bServerSide": true, "sPaginationType": "bootstrap",
					"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
					"sAjaxSource": web.host + 'panel/store/store_payment_method/grid',
					"fnServerParams": function ( aoData ) {
						aoData.push( { "name": "store_id", "value": user.store_active.store_id } );
					},
					"aoColumns": [
                    { "sClass": "center", "bSortable": false },
                    null
					]
                } );
				grid_store_payment.load = Func.reload({ id: 'store-payment' });
				
				$('#store-payment').on('click','tbody td img.edit', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					$('#WinPayment [name="id"]').val(record.id);
					$('#WinPayment [name="store_id"]').val(record.store_id);
					$('#WinPayment [name="payment_method_id"]').val(record.payment_method_id);
					$('#WinPayment [name="client_id"]').val(record.client_id);
					$('#WinPayment [name="client_secret"]').val(record.client_secret);
					$('#input_payment').change();
					$('#WinPayment').modal();
                });
				$('#store-payment').on('click','tbody td img.delete', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					
					Func.confirm_delete({
						data: { action: 'delete', id: record.id },
						url: web.host + 'panel/store/store_payment_method/action',
						grid: grid_store_payment, cnt_mesage: '.store-payment-message'
                    });
                });
            }
			init_table();
        });
    </script>
</body>
</html>