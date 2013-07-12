<?php
	$this->User_model->login_user_required();
	$user = $this->User_model->get_session();
	$date_start = (isset($_POST['date_start'])) ? $_POST['date_start'] : date("01-m-Y");
	$date_end = (isset($_POST['date_end'])) ? $_POST['date_end'] : date("d-m-Y");
	
	// prepare data
	$saldo = $this->User_model->get_saldo(array( 'user_id' => $user['id'] ));
	
	$param_rekap['item_user_id'] = $user['id'];
	$param_rekap['date_start'] = SwapDate($date_start);
	$param_rekap['date_end'] = SwapDate($date_end);
	$param_rekap['limit'] = 1000;
	$array_rekap = $this->User_Item_model->get_array($param_rekap);
?>

<?php $this->load->view( 'website/common/meta' ); ?>
<body>
<?php $this->load->view( 'website/common/header' ); ?>

<style>
#table-rekap td { border: 1px solid #CCCCCC; }
</style>

<div class="container-fluid sidebar_content">
	<div class="hide">
		<div class="cnt-saldo"><?php echo json_encode($saldo); ?></div>
	</div>
	
	
	
	<div class="row-fluid">
		<div class="span8"><br />
			<div class="row-fluid">
				<div class="span12">
					<h2><a href="<?php echo base_url(); ?>">HOME</a> >Hasil Jualan</h2>
					
					<div><form method="post" id="form-rekap">
						<div style="float: left; width: 150px;">Tanggal Mulai</div>
						<div style="float: left; width: 150px;"><input type="text" name="date_start" class="datepicker" value="<?php echo $date_start; ?>" /></div>
						<div style="clear: both;"></div>
						<div style="float: left; width: 150px;">Tanggal Selesai</div>
						<div style="float: left; width: 150px;"><input type="text" name="date_end" class="datepicker" value="<?php echo $date_end; ?>" /></div>
						<div style="clear: both;"></div>
						<div style="float: left; width: 150px;">&nbsp;</div>
						<div style="float: left; width: 150px;"><input type="submit" name="Submit" value="Submit" class="btn btn-success" /></div>
						<div style="clear: both;"></div>
					</form></div>
					
					<?php if (count($array_rekap) > 0) { ?>
					<table style="border: 1px solid #CCCCCC; border-collapse: collapse;" id="table-rekap">
					<tr style="text-align: center; font-weight: bold;">
						<td class="span3">Pembayaran</td>
						<td class="span3">Waktu</td>
						<td class="span3">Ref ID</td>
						<td class="span3">Rupiah</td>
						<td class="span3">$</td></tr>
					<?php $total_rupiah = $total_dollar = 0; ?>
					<?php foreach ($array_rekap as $item) { ?>
					<?php $total_rupiah += $item['price']; ?>
					<?php $total_dollar += $item['terbayar']; ?>
					<tr>
						<td><?php echo $item['payment_name']; ?></td>
						<td style="text-align: left;"><?php echo $item['payment_date']; ?></td>
						<td style="text-align: left;"><?php echo $item['ref_id']; ?></td>
						<td style="text-align: right;"><?php echo rupiah($item['price']); ?></td>
						<td style="text-align: right; min-width: 70px;"><?php echo dollar($item['terbayar']); ?></td></tr>
					<?php } ?>
					<tr>
						<td style="text-align: center;">Total</td>
						<td style="text-align: center;">&nbsp;</td>
						<td style="text-align: center;">&nbsp;</td>
						<td style="text-align: center;"><?php echo rupiah($total_rupiah); ?></td>
						<td style="text-align: center; min-width: 70px;"><?php echo dollar($total_dollar); ?></td></tr>
					</table>
					<?php } else { ?>
					<div>Jualan nya belum laku bro, upload yang banyak untuk peluang yang lebih baser</div>
					<?php } ?>
				</div>
			</div>
		</div>
		
		<div class="span4 sidebar"><br />
			<h2>Informasi Saldo :</h2>
			<div style="text-align: right; font-weight: bold;">Total</div>
			<div>Sales : <?php echo $user['display_name']; ?></div>
			
			<?php if (count($saldo['last_withdraw']) > 0) { ?>
			<div>Sejak Withdraw pada : <?php echo GetFormatDate($saldo['last_withdraw']['request_datetime'], array( 'FormatDate' => "d/m/Y" )); ?></div>
			<?php } else { ?>
			<div>Sejak Mulai Bergabung</div>
			<?php } ?>
			
			<div>Revenue (Rp) : <?php echo rupiah($saldo['saldo_rupiah']); ?></div>
			<div>Revenue (USD) : <?php echo dollar($saldo['saldo_dollar']); ?></div>
			<div>Total Revenue : <?php echo rupiah($saldo['saldo_total']); ?></div>
			<div style="text-align: right; font-weight: bold;">Hasil Anda</div>
			<div>Prosentase : <?php echo $saldo['saldo_percent']['percent_text']; ?></div>
			<div>Profit (Rp) : <?php echo rupiah($saldo['saldo_profit']); ?></div>
			
			<div style="padding: 35px 0 0 0;">
				<a class="btn show-withdraw">Penarikan</a>
			</div>
		</div>
	</div>
	
	<div id="win-withdraw" class="modal hide fade" tabindex="-1" role="dialog">
		<div class="modal-header">
			<a href="#" class="close" data-dismiss="modal">&times;</a>
			<h3>Form Penarikan</h3>
		</div>
		<div class="modal-body" style="">
			Penarikan revenue seluruh hasil penjualan dengan :<br />
			Prosentase : <?php echo $saldo['saldo_percent']['percent_text']; ?><br />
			Profit (Rp) : <?php echo rupiah($saldo['saldo_profit']); ?><br /><br />
			Apa anda yakin akan melakukan menarikan sekarang ?
		</div>
		<div class="modal-footer">
			<a class="btn cursor cancel">Cancel</a>
			<a class="btn cursor save btn-primary">OK</a>
		</div>
	</div>
</div>
<?php $this->load->view( 'website/common/footer' ); ?>

<script>
$(document).ready(function() {
	$('.datepicker').datepicker({ format: 'dd-mm-yyyy' });
	
	// withdraw
	$('.show-withdraw').click(function() {
		$('#win-withdraw').modal();
	});
	$('#win-withdraw .save').click(function() {
		Func.ajax({ url: web.host + 'rekap/action', param: { action: 'add_withdraw' }, callback: function(result) {
			Func.show_notice({ title: 'Informasi', text: result.message });
			if (result.status) {
				$('#win-withdraw').modal('hide');
			}
		} });
	});
	$('#win-withdraw .cancel').click(function() { $('#win-withdraw').modal('hide'); });
});
</script>
</body>
</html>