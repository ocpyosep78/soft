<?php
	preg_match('/(\d+)$/i', $_SERVER['REQUEST_URI'], $match);
	$item_id = (isset($match[1])) ? $match[1] : '';
	if (!$item_id) {
		show_404();
		exit;
	}
	
	$item = $this->Item_model->get_by_id(array( 'id' => $item_id ));
	if (!$item || $item['item_status_id'] == 2) {
		show_404();
		exit;
	}
	
	$user = $this->User_model->get_session();
	if ( $item['user_id'] == 0 ) {
		if (!$user) {
			$_SESSION['check'] = sha1(uniqid(mt_rand()));
		} else if ( !empty($_GET['check']) && $_SESSION['check'] == $_GET['check'] ) {
			unset($_SESSION['check']);
			mysql_query("UPDATE item SET user_id = '$user[id]' WHERE id = '$item[id]'");
			
			mail( $user['email'], 'Aplikasi Menunggu Persetujuan | LintasApps.com', "Hallo

Terima kasih telah mengupload aplikasi anda di LintasApps.com
Saat ini aplikasi anda menunggu persetujuan dari kami.
ID Aplikasi : $item[id]
Nama: $item[name]

Proses persetujuan paling lama adalah 7 hari kerja, jika tidak ada respon dalam jangka waktu 7 hari kerja, silahkan kontak kami di info@lintasapps.com atau 0341-406633.
Sertakan ID Aplikasi anda untuk mempercepat proses eskalasi.

Terima kasih,
--
LintasApps.com", "From: info@lintasapps.com" );
		}
	}
	
?>

<?php $this->load->view( 'website/common/meta' ); ?>
<body>
<?php $this->load->view( 'website/common/header' ); ?>

<div class="container-fluid sidebar_content">
	<div class="row-fluid">
		<div class="span8">	
			<br />
			<h2><i class="icon-suitcase"></i>&nbsp;&nbsp;Upload Sukses</h2>
			
			<div class="row-fluid form-tooltip">	
				<div class="span12">
					<h2>Terima Kasih</h2>
					<p>Aplikasi anda sudah sukses terupload. Kami akan melakukan proses validasi dan testing aplikasi anda.</p>
					
					<h2>ID APLIKASI: #<?php echo $item['id']; ?></h2>
					
					<p>Maksimal proses validasi adalah 7 hari kerja. Jika tidak ada konfirmasi setelah 7 hari kerja, silahkan kontak kami di info@lintasapps.com / 0341-406644. Sebutkan ID item anda untuk membantu eskalasi.</p>
					
					<?php if ( !$user ): ?>
					<hr>
					<h4>Untuk mencatat profil anda, silahkan <a class="btn btn-primary" href="<?php echo base_url('login'); ?>?next=<?php echo rawurlencode(base_url('post/confirm/'.$item_id).'?check='.$_SESSION['check']); ?>">login atau registrasi</a></h4>
					<?php endif; ?>
				</div>	
			</div>
		</div>
		
		<div class="span4 sidebar">	
			&nbsp;
		</div>		
	</div>
</div>

<?php $this->load->view( 'website/common/footer' ); ?>

</body>
</html>