<?php
	$array_menu = array( 'menu' => array('Order', 'Nota') );
	
	$array_status_nota = $this->Status_Nota_model->get_array();
	$array_payment_method = $this->Payment_Method_model->get_array();
?>

<?php $this->load->view( 'panel/common/meta' ); ?>
<body>
	<div id="loading_layer hide"><img src="<?php echo base_url(); ?>static/img/ajax_loader.gif" alt="" /></div>
	
	<div id="maincontainer" class="clearfix">
		<?php $this->load->view( 'panel/common/header' ); ?>
        <div class="hide">
            <div class="status-nota-cancel"><?php echo STATUS_NOTA_CANCEL; ?></div>
        </div>
		<div id="WinNota" class="modal modal-big hide fade" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">
			<div class="modal-header">
				<a href="#" class="close" data-dismiss="modal">&times;</a>
				<h3>Form Nota</h3>
            </div>
			<div class="modal-body" style="padding-left: 0px;">
				<div class="pad-alert" style="padding-left: 15px;"></div>
				<form class="form-horizontal" style="padding-left: 0px;">
					<input type="hidden" name="id" value="0" />
					<div class="control-group">
						<label class="control-label" for="input_payment">Payment</label>
						<div class="controls">
							<select id="input_payment" name="payment_method_id" class="span5" readonly="readonly" disabled="disabled">
								<?php echo ShowOption(array('Array' => $array_payment_method, 'ArrayID' => 'id', 'ArrayTitle' => 'name')); ?>
                            </select>
                        </div>
                    </div>
					<div class="control-group">
						<label class="control-label" for="input_nota_date">Tanggal</label>
						<div class="controls">
							<input type="text" id="input_nota_date" name="nota_date" class="span5" readonly="readonly" />
                        </div>
                    </div>
					<div class="control-group">
						<label class="control-label" for="input_nota_name">Nama Pembeli</label>
						<div class="controls">
							<input type="text" id="input_nota_name" name="nota_name" class="span5" readonly="readonly" />
                        </div>
                    </div>
					<div class="control-group">
						<label class="control-label" for="input_nota_address">Alamat</label>
						<div class="controls">
							<textarea id="input_nota_address" name="nota_address" class="span5" readonly="readonly"></textarea>
                        </div>
                    </div>
					<div class="control-group">
						<label class="control-label" for="input_nota_phone">Telepon</label>
						<div class="controls">
							<input type="text" id="input_nota_phone" name="nota_phone" class="span5" readonly="readonly" />
                        </div>
                    </div>
					<div class="control-group">
						<label class="control-label" for="input_nota_city">Kota</label>
						<div class="controls">
							<input type="text" id="input_nota_city" name="nota_city" class="span5" readonly="readonly" />
                        </div>
                    </div>
					<div class="control-group controls-row">
						<label class="control-label" for="input_nota_zipcode">Kodepos, Negara</label>
						<div class="controls">
							<input type="text" id="input_nota_zipcode" name="nota_zipcode" class="span2" readonly="readonly" style="margin:0 25px 0 0;" />
							<input type="text" id="input_nota_country" name="nota_country" class="span3" readonly="readonly" />
                        </div>
                    </div>
					<div class="control-group">
						<label class="control-label" for="input_nota_note">Catatan</label>
						<div class="controls">
							<textarea id="input_nota_note" name="nota_note" class="span5" readonly="readonly"></textarea>
                        </div>
                    </div>
					<div class="control-group">
						<label class="control-label" for="input_nota_currency_total">Total</label>
						<div class="controls">
							<input type="text" id="input_nota_currency_total" name="nota_currency_total" class="span5" readonly="readonly" />
                        </div>
                    </div>
					<div class="control-group">
						<label class="control-label" for="input_status_nota_id">Status Nota</label>
						<div class="controls">
							<select id="input_status_nota_id" name="status_nota_id" class="span5" readonly="readonly" disabled="disabled">
								<?php echo ShowOption(array('Array' => $array_status_nota, 'ArrayID' => 'id', 'ArrayTitle' => 'name')); ?>
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
		
		<div id="WinProduct" class="modal modal-big hide fade" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">
			<div class="modal-header">
				<a href="#" class="close" data-dismiss="modal">&times;</a>
				<h3>Detail Product</h3>
            </div>
			<div class="modal-body" style="padding-left: 20px;"></div>
        </div>
		
		<div id="contentwrapper">
			<div class="main_content">
				<?php $this->load->view( 'panel/common/breadcrumb', array( 'array_menu' => $array_menu ) ); ?>
				
				<div class="row-fluid">
					<div class="span12">
						<div class="nota-message"></div>
						<table id="nota" class="table table-striped table-bordered dTableR">
							<thead><tr>
								<th style="width: 135px;">&nbsp;</th>
								<th>Order ID</th>
								<th>Tanggal</th>
								<th>Name</th>
								<th>Email</th>
								<th>Total</th>
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
			var grid_bank_account = null;
			setTimeout('$("html").removeClass("js")', 300);
			
			Func.InitForm({
				Container: '#WinNota',
				rule: { }
            });
			
			$('#WinNota .save').click(function() {
				if (! $('#WinNota form').valid()) {
					return;
                }
				
				var param = Site.Form.GetValue('WinNota');
				param.action = 'update';
				Func.ajax({ url: web.host + 'panel/order/nota/action', param: param, callback: function(result) {
					if (result.status == 1) {
						Func.popup_result('.nota-message', result.message);
						$('#WinNota').modal('hide');
						grid_bank_account.load();
                        } else {
						Func.popup_error('#WinNota', result.message);
                    }
                } });
            });
			$('#WinNota .cancel').click(function() {
				$('#WinNota').modal('hide');
            });
			
			function init_table() {
				grid_bank_account = $('#nota').dataTable( {
					"aaSorting": [[1, 'desc']], "sServerMethod": "POST",
					"bProcessing": true, "bServerSide": true, "sPaginationType": "bootstrap",
					"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
					"sAjaxSource": web.host + 'panel/order/nota/grid',
					"aoColumns": [
                    { "sClass": "center", "bSortable": false },
                    null,
                    null,
                    null,
                    null,
                    { "sClass": "center", "bSortable": false },
                    null
					]
                } );
				grid_bank_account.load = Func.reload({ id: 'nota' });
				
				$('#nota').on('click','tbody td img.edit', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					$('#WinNota [name="id"]').val(record.id);
					$('#WinNota [name="payment_method_id"]').val(record.payment_method_id);
					$('#WinNota [name="nota_date"]').val(record.nota_date);
					$('#WinNota [name="nota_name"]').val(record.nota_name);
					$('#WinNota [name="nota_address"]').val(record.nota_address);
					$('#WinNota [name="nota_phone"]').val(record.nota_phone);
					$('#WinNota [name="nota_city"]').val(record.nota_city);
					$('#WinNota [name="nota_zipcode"]').val(record.nota_zipcode);
					$('#WinNota [name="nota_country"]').val(record.nota_country);
					$('#WinNota [name="nota_currency_total"]').val(record.nota_currency_total);
					$('#WinNota [name="status_nota_id"]').val(record.status_nota_id);
					$('#WinNota').modal();
                });
				
				$('#nota').on('click','tbody td img.delete', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					
					Func.confirm_delete({
						data: { action: 'delete', id: record.id },
						url: web.host + 'panel/order/nota/action',
						grid: grid_bank_account, cnt_mesage: '.nota-message'
                    });
                });
                $('#nota').on('click','tbody td img.confirm', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					$('#WinNota [name="id"]').val(record.id);
					$('#WinNota [name="payment_method_id"]').val(record.payment_method_id);
					$('#WinNota [name="nota_date"]').val(record.nota_date);
					$('#WinNota [name="nota_name"]').val(record.nota_name);
					$('#WinNota [name="nota_address"]').val(record.nota_address);
					$('#WinNota [name="nota_phone"]').val(record.nota_phone);
					$('#WinNota [name="nota_city"]').val(record.nota_city);
					$('#WinNota [name="nota_zipcode"]').val(record.nota_zipcode);
					$('#WinNota [name="nota_country"]').val(record.nota_country);
					$('#WinNota [name="nota_currency_total"]').val(record.nota_currency_total);
					$('#WinNota [name="status_nota_id"]').val(<?php echo STATUS_NOTA_CONFIRM; ?>);
                    
                    if (! $('#WinNota form').valid()) {
                        return;
                    }
                    
                    var param = Site.Form.GetValue('WinNota');
                    param.action = 'update';
                    Func.ajax({ url: web.host + 'panel/order/nota/action', param: param, callback: function(result) {
                        if (result.status == 1) {
                            Func.popup_result('.nota-message', result.message);
                            $('#WinNota').modal('hide');
                            grid_bank_account.load();
                            } else {
                            Func.popup_error('#WinNota', result.message);
                        }
                    } });
                });
				
                $('#nota').on('click','tbody td img.cancel', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					$('#WinNota [name="id"]').val(record.id);
					$('#WinNota [name="payment_method_id"]').val(record.payment_method_id);
					$('#WinNota [name="nota_date"]').val(record.nota_date);
					$('#WinNota [name="nota_name"]').val(record.nota_name);
					$('#WinNota [name="nota_address"]').val(record.nota_address);
					$('#WinNota [name="nota_phone"]').val(record.nota_phone);
					$('#WinNota [name="nota_city"]').val(record.nota_city);
					$('#WinNota [name="nota_zipcode"]').val(record.nota_zipcode);
					$('#WinNota [name="nota_country"]').val(record.nota_country);
					$('#WinNota [name="nota_currency_total"]').val(record.nota_currency_total);
					$('#WinNota [name="status_nota_id"]').val(<?php echo STATUS_NOTA_CANCEL; ?>);
                    
                    if (! $('#WinNota form').valid()) {
                        return;
                    }
                    
                    var param = Site.Form.GetValue('WinNota');
                    param.action = 'update';
                    Func.ajax({ url: web.host + 'panel/order/nota/action', param: param, callback: function(result) {
                        if (result.status == 1) {
                            Func.popup_result('.nota-message', result.message);
                            $('#WinNota').modal('hide');
                            grid_bank_account.load();
                            } else {
                            Func.popup_error('#WinNota', result.message);
                        }
                    } });
                });
                
				$('#nota').on('click','tbody td img.product', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					
					Func.ajax({ url: web.host + 'panel/order/nota/view', param: { action: 'product_list', nota_id: record.id }, is_json: 0, callback: function(result) {
						$('#WinProduct .modal-body').html(result);
						$('#WinProduct').modal();
                    } });
                });
            }
			init_table();
        });
    </script>
</body>
</html>