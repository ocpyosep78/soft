<?php
	preg_match('/([\d]+)/i', $_SERVER['REQUEST_URI'], $match);
	$item_id = (isset($match[1])) ? $match[1] : 0;
	if (!$item_id) {
		show_404();
		exit;
	}
	
	$is_buy = false;
	$is_login = $this->User_model->is_login();
	$item = $this->Item_model->get_by_id(array( 'id' => $item_id ));
	if (!$item) {
		show_404();
		exit;
	}
	
    $item_screenshot = json_decode($item['screenshot']);
	if ($is_login) {
		$user = $this->User_model->get_session();
		$is_buy = $this->User_Item_model->is_buy(array( 'user_id' => $user['id'], 'item_id' => $item['id'] ));
    }
	
	$is_owner = $this->User_model->is_owner(array( 'item_id' => $item_id ));
	
	//SEMENTARA
	$cdata = array('item_id' => $item['id']);
	$checkout_id = empty($_SESSION['checkout_id']) ? 0 : $_SESSION['checkout_id'];
	if (!$checkout_id)
	{
		mysql_query( "INSERT INTO checkout_data (data) VALUES ('".mysql_escape_string(json_encode($cdata))."')" );
		$checkout_id = mysql_insert_id();
		$_SESSION['checkout_id'] = $checkout_id;
	} 
	else 
	{
		$r = mysql_query("SELECT * FROM checkout_data WHERE id = '$checkout_id'");
		if ($row = mysql_fetch_assoc($r))
		{
			$cdata = json_decode( $row['data'], true );
		}
	}
	
	if (!isset($_SESSION['usdidr']))
	{
		$html = file_get_contents("http://www.klikbca.com/");
		if ( preg_match_all( '~<td align="(left|right)" class="kurs" bgcolor="#(dcdcdc|f0f0f0)">(.*?)</td>~', $html, $matches ) )
		{
			$jual = preg_replace('~[^\.0-9]+~', '', $matches[0][1]);
			$beli = preg_replace('~[^\.0-9]+~', '', $matches[0][2]);
			$beli -= 100;
			$konversi_rupiah=array('value' => floatval($beli));
			$_SESSION['usdidr'] = $konversi_rupiah;
		}
		
		if (!isset($konversi_rupiah))
		{
			$konversi_rupiah = $this->Default_Value_model->get_konversi_rupiah_dolar();
		}
	}
	else
	{
		$konversi_rupiah = $_SESSION['usdidr'];
	}
	
	$harga_dolar_asli = $item['price'] / $konversi_rupiah['value'];
	$paypal_add = 0; //ceil(($harga_dolar_asli * (3.4/100)) + 0.30);
	$harga_dolar = number_format($harga_dolar_asli + $paypal_add, 2, '.', '');
	
	$cdata['item_id'] = $item_id;
	$cdata['dolar'] = $harga_dolar_asli;
	$cdata['paypal_add'] = $paypal_add;
	$cdata['paypal_dolar'] = $harga_dolar;
	$cdata['konversi_rupiah'] = $konversi_rupiah['value'];
	
	$ipaymu_add = 0; //ceil(($item['price'] * (3.4/100)) + 0.30);
	$ipaymu_price = $item['price'] + $ipaymu_add;
	$cdata['ipaymu_add'] = $ipaymu_add;
	$cdata['ipaymu_price'] = $ipaymu_price;
	
	$doku_add = 0; //ceil(($item['price'] * (3.4/100)) + 0.30);
	$doku_price = $item['price'] + $doku_add;
	$doku_money = number_format($doku_price, 2, '.', '');
	$cdata['doku_add'] = $doku_add;
	$cdata['doku_price'] = $doku_price;
	
	$strdata = mysql_escape_string( json_encode($cdata) );
	mysql_query("UPDATE checkout_data SET data = '$strdata' WHERE id = '$checkout_id'");
	
	$email = '';
	if (isset($_SESSION['email'])) {
		$email = $_SESSION['email'];
		unset($_SESSION['email']);
	}
	
	if (!$email && $is_login) {
		$email = $user['email'];
	}
	
	//END OF SEMENTARA
	
?>

<?php $this->load->view( 'website/common/meta' ); ?>
<body>
    <?php $this->load->view( 'website/common/header' ); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('static/theme/job_board/css/shadowbox.css');?>">
    <script type="text/javascript" src="<?php echo base_url('static/theme/job_board/js/shadowbox.js');?>"></script>
    <script type="text/javascript">
        Shadowbox.init({
            // a darker overlay looks better on this particular site
            handleOversize: "drag",
            modal: true,
        });
    </script>
    <div class="hide">
        <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>" />
    </div>
    
    <div class="container-fluid sidebar_content"><div class="row-fluid">
        <div class="span8">	
            <br />
            <h2><i class="icon-suitcase"></i>&nbsp;&nbsp;<?php echo $item['name']; ?></h2>
            
            <div class="row-fluid form-tooltip">	
                <div class="span12 control-group">
                    <?php if (!empty($item['thumbnail'])) { ?>
                        <img src="<?php echo $item['thumbnail_link']; ?>" style="width: 50%; padding: 0 0 10px 0;"/>
                        <div class="clear"></div>
                    <?php } ?>
					
					<?php if ( !empty($_GET['error']) ): ?>
					<div class="alert alert-error">
						<h3>Pembayaran anda tertunda atau bermasalah</h3>
						<p>Mohon maaf, mohon cek dan ulangi kembali pembayaran anda, karena kemungkinan pembayaran anda tertunda atau bermasalah.</p>
					</div>
					<?php endif; ?>
                    
                    <p><?php echo nl2br($item['description']); ?></p>
                </div>	
                <div class="span12 control-group"><h4>File Screenshot Aplikasi / Item </h4></div>
                <div class="span12 control-group">
                    <?php 
                        if(isset($item_screenshot)){
                        foreach($item_screenshot as $key=>$screenshot):?>
                        <a href="<?php echo base_url('screenshots/'.$screenshot);?>" rel="shadowbox[Mixed]" title="screenshot <?php echo $key+1;?>">
                        <?php
                            $screenshot_mini = pathinfo(base_url('screenshots/'.$screenshot));
                            $screenshot_no_ext = basename($screenshot_mini['basename'],".".$screenshot_mini['extension']);
                            $screenshot_mini_file = $screenshot_no_ext."_thumb.".$screenshot_mini['extension'];
                            $full_path_screenshot_mini_file = $screenshot_mini['dirname']."/".$screenshot_mini_file;
                        ?>
                        <img style="margin:2px;" class="img-polaroid" src="<?php echo $full_path_screenshot_mini_file ?>" alt="screnshot" />
                        </a>
                        <?php endforeach; 
                        }else
                        {?>  
                        <div class="span12 control-group"><h4>Tidak ada Screenshot Aplikasi / Item </h4></div>
                        <?php
                        }?>
                </div>
                <br />
                <br />
            </div>
        </div>
        
        <div class="span4 sidebar"><br /><br />
            <div style="text-align: center; padding: 0 0 20px 0;">
                <?php if ($is_buy) { ?>
                    
					<a class="cursor btn btn-primary btn-success btn-download">Download</a>
					
				<?php } else if ($item['item_status_id'] == ITEM_STATUS_APPROVE) { ?>
				
					<div class="item-price"><span class="label-success" style="color:#fff;"><?php echo rupiah($item['price']); ?></span></div>
					
					<p>Kurs: US $1 = Rp<?php echo number_format($konversi_rupiah['value'], 2, '.', ','); ?></p>
					<p><b>Silahkan <a href="https://www.lintasapps.com/login?next=<?php echo rawurlencode($_SERVER['REQUEST_URI']); ?>">Login atau daftar</a> agar pembelian anda tercatat.</b></p>
					
					<form method="post" action="https://www.paypal.com/cgi-bin/webscr" class="paypal-button" target="_top">
						<input type="hidden" id="custom_email" name="custom" value="<?php echo $email; ?>">
						<input type="hidden" name="button" value="buynow">
						<input type="hidden" name="item_name" value="<?php echo $item['name']; ?>">
						<input type="hidden" name="amount" value="<?php echo $harga_dolar; ?>">
						<input type="hidden" name="currency_code" value="USD">
						<input type="hidden" name="item_number" value="1">
						<input type="hidden" name="lc" value="id_ID">
						<input type="hidden" name="notify_url" value="<?php echo base_url('item/paypalnotify?id='.$checkout_id); ?>">
						<input type="hidden" name="return" value="<?php echo base_url('item/thanks?tipe=paypal&tmp=1&id='.$checkout_id); ?>">
						<input type="hidden" name="cancel_return" value="<?php echo base_url('item/'.$item['id']); ?>">
						<input type="hidden" name="cmd" value="_xclick">
						<input type="hidden" name="business" value="MTNRJT46ZBX9W">
						<input type="hidden" name="bn" value="SIMETRI_BuyNow_WPS_ID">
						<input type="hidden" name="env" value="www">
						<input type="hidden" name="no_shipping" value="1">
						<button type="submit" class="btn btn-large btn-primary btn-xlarge">
							<span class="paypal1">$<?php echo substr($harga_dolar, -3) == '.00' ? round($harga_dolar) : $harga_dolar; ?></span>
						</button>
					</form>
                    
					<?php } else { ?>
                    <a class="btn btn-primary btn-success">Menunggu Persetujuan</a>
                <?php } ?>
            </div>
            
            <div class="row-fluid form-tooltip">
                <div class="span12">
                    <h4>Detail</h4>
                    <div>Nama :  <?php echo $item['name']; ?></div>
                    <div>Platform : <?php echo $item['platform_name']; ?></div>
                    <div>Kategori : <?php echo $item['category_name']; ?></div>
                    <div>Owner : <a href="<?php echo $item['author_link']; ?>"><?php echo $item['user_name']; ?></a></div>
                </div>	
            </div>
			
			<?php if ($is_owner) { ?>
            <div class="row-fluid form-tooltip">
                <div class="span12">
                    <h4>Pilihan</h4>
                    <a href="<?php echo base_url('post/'.$item['id']); ?>">Ubah Data</a>
                </div>	
            </div>
			<?php } ?>
        </div>		
    </div></div>
    
    <?php $this->load->view( 'website/common/footer' ); ?>
    
    <script>
        $(document).ready(function() {
			$("form.paypal-button").submit(function() {
				var email = $("#custom_email").val();
				if (!email) {
					email = prompt("Link download akan dikirimkan ke alamat e-mail anda, silahkan masukkan alamat email", "");
					if ( !/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/.test(email) ) {
						alert("Maaf, alamat e-mail anda tidak valid, silahkan coba lagi");
						return false;
					}
					$("#custom_email").val(email);
				}
				$("input[name=return]").val( $("input[name=return]").val()+'&email='+encodeURIComponent(email) );
				return true;
			});
		
            $('.btn-download').click(function() {
                Func.force_download({ item_id: $('[name="item_id"]').val() });
            });
        });
    </script>
    
</body>
</html>