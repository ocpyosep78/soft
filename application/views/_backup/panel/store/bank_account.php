<?php
	$array_menu = array( 'menu' => array('Store', 'Bank Account') );
	
	$user = $this->User_model->get_session();
    $array_bank = $this->Bank_model->get_array();
?>

<?php $this->load->view( 'panel/common/meta' ); ?>
<body>
	<div id="loading_layer hide"><img src="<?php echo base_url(); ?>static/img/ajax_loader.gif" alt="" /></div>
	
	<div id="maincontainer" class="clearfix">
		<?php $this->load->view( 'panel/common/header' ); ?>
		<div class="hide">
			<div id="RawUser"><?php echo json_encode($user); ?></div>
		</div>
		
		<div id="WinBankAccount" class="modal modal-big hide fade" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">
			<div class="modal-header">
				<a href="#" class="close" data-dismiss="modal">&times;</a>
				<h3>Form Bank Account</h3>
            </div>
			<div class="modal-body" style="padding-left: 0px;">
				<div class="pad-alert" style="padding-left: 15px;"></div>
				<form class="form-horizontal" style="padding-left: 0px;">
					<input type="hidden" name="id" value="0" />
					<input type="hidden" name="store_id" value="0" />
					<div class="control-group">
						<label class="control-label" for="input_bank_name">Nama</label>
						<div class="controls">
                            <select id="input_bank_id" name="bank_id" class="span5">
                            <?php echo ShowOption(array('Array' => $array_bank, 'ArrayID' => 'id', 'ArrayTitle' => 'title')); ?>
                            </select>
                        </div>
                    </div>
					<div class="control-group">
						<label class="control-label" for="input_no_rekening">No Rekening</label>
						<div class="controls">
							<input type="text" id="input_no_rekening" name="no_rekening" placeholder="No Rekening" class="span5" rel="twipsy" />
                        </div>
                    </div>
					<div class="control-group">
						<label class="control-label" for="input_pemilik">Nama Pemilik</label>
						<div class="controls">
							<input type="text" id="input_pemilik" name="pemilik" placeholder="Nama Pemilik" class="span5" rel="twipsy" />
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
						<button class="btn btn-gebo AddBankAccount">Tambah</button>
                    </div>
                </div>
				
				<div class="row-fluid">
					<div class="span12">
						<div class="bank-account-message"></div>
						<table id="blog_status" class="table table-striped table-bordered dTableR">
							<thead><tr>
								<th style="width: 50px;">&nbsp;</th>
								<th>Nama Bank</th>
								<th>No Rekening</th>
								<th>Nama Pemilik</th>
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
			
			// user
			var RawUser = $('#RawUser').text();
			eval('var user = ' + RawUser);
			
			Func.InitForm({
				Container: '#WinBankAccount',
				rule: { bank_name: { required: true }, no_rekening: { required: true }, pemilik: { required: true } }
            });
			
			$('.AddBankAccount').click(function() {
				$('#WinBankAccount form')[0].reset()
				$('#WinBankAccount [name="id"]').val(0);
				$('#WinBankAccount [name="store_id"]').val(user.store_active.store_id);
				$('#WinBankAccount').modal();
            });
			$('#WinBankAccount .save').click(function() {
				if (! $('#WinBankAccount form').valid()) {
					return;
                }
				
				var param = Site.Form.GetValue('WinBankAccount');
				param.action = 'update';
				Func.ajax({ url: web.host + 'panel/store/bank_account/action', param: param, callback: function(result) {
					if (result.status == 1) {
						Func.popup_result('.bank-account-message', result.message);
						$('#WinBankAccount').modal('hide');
						grid_bank_account.load();
                    } else {
						Func.popup_error('#WinBankAccount', result.message);
					}
                } });
            });
			$('#WinBankAccount .cancel').click(function() {
				$('#WinBankAccount').modal('hide');
            });
			
			function init_table() {
				grid_bank_account = $('#blog_status').dataTable( {
					"aaSorting": [[1, 'asc']], "sServerMethod": "POST",
					"bProcessing": true, "bServerSide": true, "sPaginationType": "bootstrap",
					"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
					"sAjaxSource": web.host + 'panel/store/bank_account/grid',
					"aoColumns": [
                    { "sClass": "center", "bSortable": false },
                    null,
                    null,
                    null
					]
                } );
				grid_bank_account.load = Func.reload({ id: 'blog_status' });
				
				$('#blog_status').on('click','tbody td img.edit', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					$('#WinBankAccount [name="id"]').val(record.id);
					$('#WinBankAccount [name="store_id"]').val(record.store_id);
					$('#WinBankAccount [name="bank_id"]').val(record.bank_id);
					$('#WinBankAccount [name="no_rekening"]').val(record.no_rekening);
					$('#WinBankAccount [name="pemilik"]').val(record.pemilik);
					$('#WinBankAccount').modal();
                });
				
				$('#blog_status').on('click','tbody td img.delete', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					
					Func.confirm_delete({
						data: { action: 'delete', id: record.id },
						url: web.host + 'panel/store/bank_account/action',
						grid: grid_bank_account, cnt_mesage: '.bank-account-message'
                    });
                });
            }
			init_table();
        });
    </script>
</body>
</html>