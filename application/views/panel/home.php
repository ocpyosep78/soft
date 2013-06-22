<?php
	$user_session = $this->User_model->get_session();
	$user = $this->User_model->get_by_id(array( 'id' => $user_session['id'] ));
?>

<?php $this->load->view( 'panel/common/meta' ); ?>
<body>
	<div id="loading_layer" style="display:none"><img src="<?php echo base_url(); ?>static/img/ajax_loader.gif" alt="" /></div>
	
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
				<?php $this->load->view( 'panel/common/breadcrumb' ); ?>
				
				<div class="dash-message"></div>
				
				<div class="row-fluid"><div class="span12">
					<h3 class="heading">Selamat Datang</h3>
					<div class="alert alert-info">
						Deposit hasil penjualan Anda saat ini Rp. <?php echo MoneyFormat($user['deposit']); ?>
						<div style="float: right;">
							<a href="javascript:void(0)" class="add-payment btn btn-gebo btn-mini">Request Payment</a>
						</div>
					</div>
				</div></div>
			</div>
		</div>
		<?php $this->load->view( 'panel/common/sidebar' ); ?>
	</div>
	
	<?php $this->load->view( 'panel/common/js' ); ?>
	<script>
		$(document).ready(function() {
			setTimeout('$("html").removeClass("js")', 300);
			
			Func.InitForm({
				Container: '#WinPayment',
				rule: { value: { required: true } }
            });
			
			$('.add-payment').click(function() {
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
						Func.popup_result('.dash-message', 'Harap menunggu konfirmasi admin untuk pengiriman dana Anda.');
						$('#WinPayment').modal('hide');
                    } else {
						Func.popup_error('#WinPayment', result.message);
					}
                } });
            });
			$('#WinPayment .cancel').click(function() {
				$('#WinPayment').modal('hide');
            });
		});
	</script>
</body>
</html>