<?php
	$array_menu = array( 'menu' => array('Master', 'Currency') );
?>

<?php $this->load->view( 'panel/common/meta' ); ?>
<body>
	<div id="loading_layer hide"><img src="<?php echo base_url(); ?>static/img/ajax_loader.gif" alt="" /></div>
	
	<div id="maincontainer" class="clearfix">
		<?php $this->load->view( 'panel/common/header' ); ?>
		
		<div id="WinCurrency" class="modal modal-big hide fade" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">
			<div class="modal-header">
				<a href="#" class="close" data-dismiss="modal">&times;</a>
				<h3>Form Currency</h3>
            </div>
			<div class="modal-body" style="padding-left: 0px;">
				<div class="pad-alert" style="padding-left: 15px;"></div>
				<form class="form-horizontal" style="padding-left: 0px;">
					<input type="hidden" name="id" value="0" />
					<div class="control-group">
						<label class="control-label" for="input_name">Nama</label>
						<div class="controls">
							<input type="text" id="input_name" name="name" placeholder="Nama Currency" class="span5" rel="twipsy" />
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
						<button class="btn btn-gebo AddCurrency">Tambah</button>
                    </div>
                </div>
				
				<div class="row-fluid">
					<div class="span12">
						<div class="currency-message"></div>
						<table id="currency" class="table table-striped table-bordered dTableR">
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
			var grid_currency = null;
			setTimeout('$("html").removeClass("js")', 300);
			
			Func.InitForm({
				Container: '#WinCurrency',
				rule: { name: { required: true } }
            });
			
			$('.AddCurrency').click(function() {
				$('#WinCurrency form')[0].reset()
				$('#WinCurrency [name="id"]').val(0);
				$('#WinCurrency').modal();
            });
			$('#WinCurrency .save').click(function() {
				if (! $('#WinCurrency form').valid()) {
					return;
                }
				
				var param = Site.Form.GetValue('WinCurrency');
				param.action = 'update';
				Func.ajax({ url: web.host + 'panel/master/currency/action', param: param, callback: function(result) {
					if (result.status == 1) {
						Func.popup_result('.currency-message', result.message);
						$('#WinCurrency').modal('hide');
						grid_currency.load();
                    } else {
						Func.popup_error('#WinCurrency', result.message);
					}
                } });
            });
			$('#WinCurrency .cancel').click(function() {
				$('#WinCurrency').modal('hide');
            });
			
			function init_table() {
				grid_currency = $('#currency').dataTable( {
					"aaSorting": [[1, 'asc']], "sServerMethod": "POST",
					"bProcessing": true, "bServerSide": true, "sPaginationType": "bootstrap",
					"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
					"sAjaxSource": web.host + 'panel/master/currency/grid',
					"aoColumns": [
                    { "sClass": "center", "bSortable": false },
                    null
					]
                } );
				grid_currency.load = Func.reload({ id: 'currency' });
				
				$('#currency').on('click','tbody td img.edit', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					$('#WinCurrency [name="id"]').val(record.id);
					$('#WinCurrency [name="name"]').val(record.name);
					$('#WinCurrency').modal();
                });
				$('#currency').on('click','tbody td img.delete', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					
					Func.confirm_delete({
						data: { action: 'delete', id: record.id },
						url: web.host + 'panel/master/currency/action',
						grid: grid_currency, cnt_mesage: '.currency-message'
                    });
                });
            }
			init_table();
        });
    </script>
</body>
</html>