<?php
	$array_menu = array( 'menu' => array('Master', 'Payment Method') );
?>

<?php $this->load->view( 'panel/common/meta' ); ?>
<body>
	<div id="loading_layer hide"><img src="<?php echo base_url(); ?>static/img/ajax_loader.gif" alt="" /></div>
	
	<div id="maincontainer" class="clearfix">
		<?php $this->load->view( 'panel/common/header' ); ?>
		
		<div id="WinPaymentMethod" class="modal modal-big hide fade" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">
			<div class="modal-header">
				<a href="#" class="close" data-dismiss="modal">&times;</a>
				<h3>Form Payment Method</h3>
            </div>
			<div class="modal-body" style="padding-left: 0px;">
				<div class="pad-alert" style="padding-left: 15px;"></div>
				<form class="form-horizontal" style="padding-left: 0px;">
					<input type="hidden" name="id" value="0" />
					<div class="control-group">
						<label class="control-label" for="input_name">Nama</label>
						<div class="controls">
							<input type="text" id="input_name" name="name" placeholder="Nama Payment Method" class="span5" rel="twipsy" />
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
						<button class="btn btn-gebo AddPaymentMethod">Tambah</button>
                    </div>
                </div>
				
				<div class="row-fluid">
					<div class="span12">
						<div class="payment-method-message"></div>
						<table id="payment-method" class="table table-striped table-bordered dTableR">
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
			var grid_payment_method = null;
			setTimeout('$("html").removeClass("js")', 300);
			
			Func.InitForm({
				Container: '#WinPaymentMethod',
				rule: { name: { required: true } }
            });
			
			$('.AddPaymentMethod').click(function() {
				$('#WinPaymentMethod form')[0].reset()
				$('#WinPaymentMethod [name="id"]').val(0);
				$('#WinPaymentMethod').modal();
            });
			$('#WinPaymentMethod .save').click(function() {
				if (! $('#WinPaymentMethod form').valid()) {
					return;
                }
				
				var param = Site.Form.GetValue('WinPaymentMethod');
				param.action = 'update';
				Func.ajax({ url: web.host + 'panel/master/payment_method/action', param: param, callback: function(result) {
					if (result.status == 1) {
						Func.popup_result('.payment-method-message', result.message);
						$('#WinPaymentMethod').modal('hide');
						grid_payment_method.load();
                    } else {
						Func.popup_error('#WinPaymentMethod', result.message);
					}
                } });
            });
			$('#WinPaymentMethod .cancel').click(function() {
				$('#WinPaymentMethod').modal('hide');
            });
			
			function init_table() {
				grid_payment_method = $('#payment-method').dataTable( {
					"aaSorting": [[1, 'asc']], "sServerMethod": "POST",
					"bProcessing": true, "bServerSide": true, "sPaginationType": "bootstrap",
					"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
					"sAjaxSource": web.host + 'panel/master/payment_method/grid',
					"aoColumns": [
                    { "sClass": "center", "bSortable": false },
                    null
					]
                } );
				grid_payment_method.load = Func.reload({ id: 'payment-method' });
				
				$('#payment-method').on('click','tbody td img.edit', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					$('#WinPaymentMethod [name="id"]').val(record.id);
					$('#WinPaymentMethod [name="name"]').val(record.name);
					$('#WinPaymentMethod').modal();
                });
				$('#payment-method').on('click','tbody td img.delete', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					
					Func.confirm_delete({
						data: { action: 'delete', id: record.id },
						url: web.host + 'panel/master/payment_method/action',
						grid: grid_payment_method, cnt_mesage: '.payment-method-message'
                    });
                });
            }
			init_table();
        });
    </script>
</body>
</html>