<?php
	$user = $this->User_model->get_session();
	$date_start = (isset($_POST['date_start'])) ? $_POST['date_start'] : date("01-m-Y");
	$date_end = (isset($_POST['date_end'])) ? $_POST['date_end'] : date("d-m-Y");
	
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
	<div class="row-fluid">
		<div class="span8"><br />
			<div class="row-fluid">
				<div class="span12">
					<h2><a href="<?php echo base_url(); ?>">HOME</a> >Hasil Jualan</h2>
					
					<div><form method="post" id="form-rekap">
						<div style="float: left; width: 150px;">Tanggal Mulai</div>
						<div style="float: left; width: 150px;"><input type="text" name="date_start" value="<?php echo $date_start; ?>" /></div>
						<div style="clear: both;"></div>
						<div style="float: left; width: 150px;">Tanggal Selesai</div>
						<div style="float: left; width: 150px;"><input type="text" name="date_end" value="<?php echo $date_end; ?>" /></div>
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
						<td style="text-align: center;"><?php echo $item['payment_date']; ?></td>
						<td style="text-align: center;"><?php echo $item['ref_id']; ?></td>
						<td style="text-align: center;"><?php echo rupiah($item['price']); ?></td>
						<td style="text-align: center; min-width: 70px;"><?php echo dollar($item['terbayar']); ?></td></tr>
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
			<?php //$this->load->view( 'website/common/info' ); ?>
		</div>
	</div>	
</div>
<?php $this->load->view( 'website/common/footer' ); ?>
</body>
</html>