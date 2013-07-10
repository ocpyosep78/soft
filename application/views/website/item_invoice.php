<?php
	preg_match('/([\d]+)/i', $_SERVER['REQUEST_URI'], $match);
	$invoice_no = (isset($match[1])) ? $match[1] : 0;
	if (!$invoice_no) {
		show_404();
		exit;
	}
	
	$user = $this->User_model->get_session();
	$item_invoice = $this->User_Item_model->get_by_id(array( 'invoice_no' => $invoice_no ));
	$item = $this->Item_model->get_by_id(array( 'id' => $item_invoice['item_id'], 'download' => true ));
	
	//hanya pembeli atau invoice blank yg bisa diakses
	if ( $item_invoice['user_id'] != 0 && (empty($user) || $user['id'] != $item_invoice['user_id']) ) {
		header("Location: " . base_url('login'));
		exit;
	}
	
	//koneksikan account setelah login
	if ( $item_invoice['user_id'] == 0 ) {
		if (!$user) {
			$_SESSION['check'] = sha1(uniqid(mt_rand()));
		} else if ( !empty($_GET['check']) && $_SESSION['check'] == $_GET['check'] ) {
			unset($_SESSION['check']);
			$rs = mysql_query("SELECT * FROM user_item WHERE user_id = '$user[id]' AND item_id = '$item_invoice[item_id]' LIMIT 1");
			$row = mysql_fetch_assoc($rs);
			if (!$row) {
				mysql_query("UPDATE user_item SET user_id = '$user[id]' WHERE id = '$item_invoice[id]'");
				$item_invoice = $this->User_Item_model->get_by_id(array( 'invoice_no' => $invoice_no ));
			}
		}
	}
	
?>

<?php $this->load->view( 'website/common/meta' ); ?>
<body>
<?php $this->load->view( 'website/common/header' ); ?>

<style>
	.fl .lbl { width: 80px; display: block; float: left; font-weight: bold; }
	.fl .r { padding:5px; border-bottom:1px solid #ccc; }
	.fl .r:nth-child(even) { background-color:#eee; }
	.registerorlogin { background-color:#eee; padding:10px; margin: 10px 0;}
</style>

<div class="container-fluid sidebar_content"><div class="row-fluid">
	<div class="span8">	
		<br />
		<h2><a href="<?php echo base_url(); ?>">HOME</a> > INVOICE #<?php echo $item_invoice['invoice_no']; ?></h2>
		
		<div class="row-fluid form-tooltip">
			<input type="hidden" name="item_id" value="<?php echo $item_invoice['item_id']; ?>" />
			
			<div class="span12 fl">
				<h2>Terima kasih, untuk pembelian anda:</h2>
				
				<div class="r"><span class="lbl">No:</span> #<?php echo $item_invoice['invoice_no']; ?></div>
				<div class="r"><span class="lbl">Tanggal:</span> <?php echo date('d-m-Y H:i', strtotime($item_invoice['payment_date'])); ?></div>
				<div class="r"><span class="lbl">Terbayar: </span> <?php if ( $item_invoice['payment_name'] == 'paypal' ) {
					echo $item_invoice['terbayar'] ? 'US $'.number_format( $item_invoice['terbayar'], 2, '.', ',' ) : 'Rp'.number_format( $item_invoice['price'], 2, '.', ',' );
				} else {
					echo 'Rp'.number_format( $item_invoice['price'], 2, '.', ',' );
				}; ?></div>
				<div class="r"><span class="lbl">Item:</span> <?php echo $item_invoice['item_name']; ?></div>
				<div class="r"><span class="lbl">Item No:</span> #<?php echo $item_invoice['item_id']; ?></div>
				<div class="r"><span class="lbl">Harga:</span> Rp<?php echo number_format($item_invoice['price'], 2, '.', ''); ?></div>
				
				<div style="text-align:center; margin:10px;">
					<?php if ($item['single_file']) { ?>
					<a class="btn btn-primary btn-large btn-item-submit" href="<?php echo $item['array_download'][0]['file_link']; ?>">DOWNLOAD APLIKASI ANDA</a>
					<?php } else { ?>
					<a class="btn btn-primary btn-large btn-item-submit" href="<?php echo $item['link_download']; ?>">DOWNLOAD APLIKASI ANDA</a>
					<?php } ?>
				</div>
				
				<?php if (!$user): ?>
				<div class="registerorlogin">
				<h3>PENTING</h3>
				<p>Catat pembelian anda untuk arsip, silahkan <a href="<?php echo base_url('login').'?next='.rawurlencode( base_url( 'item/invoice/'.$invoice_no )."?check=".(isset($_SESSION['check'])?$_SESSION['check']:'')  ); ?>" class="btn btn-item-submit">DAFTAR/LOGIN</a></p>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	
	<div class="span4 sidebar"><br /><br />
		<div class="row-fluid form-tooltip">	
			<div class="span12">
				&nbsp;
			</div>	
		</div>
	</div>		
</div></div>

<?php $this->load->view( 'website/common/footer' ); ?>

</body>
</html>