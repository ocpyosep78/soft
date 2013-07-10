<?php
	preg_match('/([\d]+)/i', $_SERVER['REQUEST_URI'], $match);
	$item_id = (isset($match[1])) ? $match[1] : 0;
	if (!$item_id) {
		show_404();
		exit;
	}
	
	$is_buy = false;
	$is_login = $this->User_model->is_login();
	$item = $this->Item_model->get_by_id(array( 'id' => $item_id, 'download' => true ));
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
	
	<!-- doku -->
	<script type="text/javascript" src="<?php echo base_url('static/js/dateformat.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('static/js/sha-1.js'); ?>"></script>
	
    <div class="hide">
        <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>" />
		<input type="hidden" name="is_login" value="<?php echo ($is_login) ? 1 : 0; ?>" />
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
					<a class="btn btn-primary btn-large btn-item-submit" href="<?php echo $item['link_download']; ?>">Download</a>
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
						
						<button type="button" class="btn btn-large btn-primary btn-xlarge btn-paypal">
							<span class="paypal1">$<?php echo substr($harga_dolar, -3) == '.00' ? round($harga_dolar) : $harga_dolar; ?></span>
						</button>
						<button type="submit" class="btn btn-large btn-primary btn-xlarge btn-paypal-submit hide">
							<span class="paypal1">$<?php echo substr($harga_dolar, -3) == '.00' ? round($harga_dolar) : $harga_dolar; ?></span>
						</button>
					</form>
                    
					<form method="post" action="<?php echo DOKU_HOST; ?>" id="form-doku">
						<input type="hidden" name="BASKET" value="<?php echo $item['name'].','.$doku_money.',1,'.$doku_money; ?>" />
						<input type="hidden" name="MALLID" value="616" />
						<input type="hidden" name="CHAINMERCHANT" value="NA" />
						<input type="hidden" name="CURRENCY" value="360" />
						<input type="hidden" name="PURCHASECURRENCY" value="360" />
						<input type="hidden" name="AMOUNT" value="<?php echo $doku_money; ?>" />
						<input type="hidden" name="PURCHASEAMOUNT" value="<?php echo $doku_money; ?>" />
						<input type="hidden" name="TRANSIDMERCHANT" />
						<input type="hidden" name="WORDS" />
						<input type="hidden" name="REQUESTDATETIME" />
						<input type="hidden" name="SESSIONID" />
						<input type="hidden" name="PAYMENTCHANNEL" />
						<input type="hidden" name="EMAIL" value="<?php echo @$user['email']; ?>" />
						<input type="hidden" name="NAME" value="<?php echo @$user['fullname']; ?>" />
						<input type="hidden" name="ADDRESS" value="<?php echo @$user['address']; ?>" />
						<input type="hidden" name="COUNTRY" value="360" />
						<input type="hidden" name="STATE" value="<?php echo @$user['propinsi']; ?>" />
						<input type="hidden" name="CITY" value="<?php echo @$user['city']; ?>" />
						<input type="hidden" name="PROVINCE" value="<?php echo @$user['propinsi']; ?>" />
						<input type="hidden" name="ZIPCODE" value="<?php echo @$user['zipcode']; ?>" />
						<input type="hidden" name="HOMEPHONE" value="<?php echo @$user['phone']; ?>" />
						<input type="hidden" name="MOBILEPHONE" value="<?php echo @$user['mobile']; ?>" />
						<input type="hidden" name="WORKPHONE" value="<?php echo @$user['office']; ?>" />
						<input type="hidden" name="BIRTHDATE" value="<?php echo preg_replace('/([^0-9]+)/i', '', @$user['birthdate']); ?>" />
						
						<button type="button" class="btn btn-large btn-warning btn-xlarge btn-prepare"><span>Credit Card</span></button>
						<button type="submit" class="btn btn-large btn-warning btn-xlarge hide"><span class="doku">Credit Card</span></button>
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
	
	<div id="win-paypal" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-header">
			<a href="#" class="close" data-dismiss="modal">&times;</a>
			<h3>Isikan email anda</h3>
		</div>
		<div class="modal-body" style="padding-left: 0px;">
			<div class="pad-alert" style="padding-left: 15px;"></div>
			<form class="form-horizontal" style="padding-left: 0px;">
				<div class="control-group">
					<label class="control-label">Email</label>
					<div class="controls">
						<input type="text" name="email" placeholder="Email" class="span4" rel="twipsy" />
					</div>
				</div>
			</form>
		</div>
		<div class="modal-footer">
			<a class="btn cursor cancel">Cancel</a>
			<a class="btn cursor save btn-primary">OK</a>
		</div>
	</div>
	
	<div id="win-doku" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">
		<div class="modal-header">
			<a href="#" class="close" data-dismiss="modal">&times;</a>
			<h3>isikan biodata anda sesuai dengan credit card</h3>
		</div>
		<div class="modal-body" style="padding-left: 0px;">
			<div class="pad-alert" style="padding-left: 15px;"></div>
			<form class="form-horizontal" style="padding-left: 0px;">
				<div class="control-group">
					<label class="control-label">Email</label>
					<div class="controls">
						<input type="text" name="email" placeholder="Email" class="span4" rel="twipsy" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="input_fullname">Nama</label>
					<div class="controls">
						<input type="text" id="input_fullname" name="fullname" placeholder="Nama" class="span4" rel="twipsy" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="input_address">Alamat</label>
					<div class="controls">
						<input type="text" id="input_address" name="address" placeholder="Alamat" class="span4" rel="twipsy" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="input_city">Kota</label>
					<div class="controls">
						<input type="text" id="input_city" name="city" placeholder="Kota" class="span4" rel="twipsy" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="input_propinsi">Propinsi</label>
					<div class="controls">
						<input type="text" id="input_propinsi" name="propinsi" placeholder="Propinsi" class="span4" rel="twipsy" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="input_zipcode">Kodepos</label>
					<div class="controls">
						<input type="text" id="input_zipcode" name="zipcode" placeholder="Kodepos" class="span4" rel="twipsy" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="input_phone">Telp Rumah</label>
					<div class="controls">
						<input type="text" id="input_phone" name="phone" placeholder="Telepon Rumah" class="span4" rel="twipsy" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="input_mobile">Telp Genggam</label>
					<div class="controls">
						<input type="text" id="input_mobile" name="mobile" placeholder="Telepon Genggam" class="span4" rel="twipsy" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="input_office">Telp Kantor</label>
					<div class="controls">
						<input type="text" id="input_office" name="office" placeholder="Telepon Kantor" class="span4" rel="twipsy" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="input_birthdate">Tanggal Lahir</label>
					<div class="controls">
						<input type="text" id="input_birthdate" name="birthdate" placeholder="DD-MM-YYYY" class="span4" rel="twipsy" />
					</div>
				</div>
			</form>
		</div>
		<div class="modal-footer">
			<a class="btn cursor cancel">Cancel</a>
			<a class="btn cursor save btn-primary">OK</a>
		</div>
	</div>
    
    <script>
		function randomString(STRlen) {
			var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
			var string_length = STRlen;
			var randomstring = '';
			for (var i=0; i<string_length; i++) {
				var rnum = Math.floor(Math.random() * chars.length);
				randomstring += chars.substring(rnum,rnum+1);
			}

			return randomstring;
		}
		
        $(document).ready(function() {
			var is_login = ($('[name="is_login"]').val() == 1) ? true : false;
			
			// paypal helper
			var paypal = {
				init: function() {
					$("#win-paypal form").validate({
						rules: {
							email: { required: true, email: true }
						},
						messages: {
							email: { required: 'Silakan masukkan alamat e-mail Anda, alamat download akan dikirimkan ke e-mail anda', email: 'Alamat e-mail yang Anda masukkan tidak valid, mohon ulangi lagi' }
						}
					});
				}
			}
			paypal.init();
			
			// doku helper
			var doku = {
				get_invoice: function() {
					var random = randomString(12);
					$('#form-doku [name="TRANSIDMERCHANT"]').val(random);
				},
				get_request_datetime: function() {
					var now = new Date();
					$('#form-doku [name="REQUESTDATETIME"]').val(dateFormat(now, "yyyymmddHHMMss"));
				},
				get_session: function() {
					var random = randomString(20);
					$('#form-doku [name="SESSIONID"]').val(random);
				},
				get_word: function() {
					var code = $('#form-doku [name="AMOUNT"]').val() + $('#form-doku [name="MALLID"]').val() + "4a7MKC7shZw9" + $('#form-doku [name="TRANSIDMERCHANT"]').val();
					var code_secret = SHA1(code);
					$('#form-doku [name="WORDS"]').val(code_secret);
				},
				init: function() {
					doku.get_invoice();
					doku.get_request_datetime();
					doku.get_session();
					
					// form validation
					$("#win-doku form").validate({
						rules: {
							email: { required: true, email: true },
							fullname: { required: true },
							address: { required: true },
							city: { required: true },
							propinsi: { required: true },
							zipcode: { required: true },
							phone: { required: true },
							mobile: { required: true },
							office: { required: true },
							birthdate: { required: true }
						},
						messages: {
							email: { required: 'Silakan masukkan alamat e-mail Anda, alamat download akan dikirimkan ke e-mail anda', email: 'Alamat e-mail yang Anda masukkan tidak valid, mohon ulangi lagi' },
							fullname: { required: 'Mahon memasukkan nama' },
							address: { required: 'Mahon memasukkan alamat' },
							city: { required: 'Mahon memasukkan kota' },
							propinsi: { required: 'Mahon memasukkan propinsi' },
							zipcode: { required: 'Mahon memasukkan kodepos' },
							phone: { required: 'Mahon memasukkan telepon rumah' },
							mobile: { required: 'Mahon memasukkan telepon genggam' },
							office: { required: 'Mahon memasukkan telepon kantor' },
							birthdate: { required: 'Mahon memasukkan tanggal lahir' }
						}
					});
				}
			}
			doku.init();
			
			// paypal
			$("form .btn-paypal").click(function() {
				if (is_login) {
					$("form .btn-paypal-submit").click();
				} else {
					$('#win-paypal').modal();
				}
			});
			$('#win-paypal .save').click(function() {
				if (! $('#win-paypal form').valid()) {
					return;
				}
				
				$("form .btn-paypal-submit").click();
			});
			$('#win-paypal .cancel').click(function() {
				$('#win-paypal').modal('hide');
			});
			$('#win-paypal form').submit(function() {
				return false;
			});
			$("form.paypal-button").submit(function() {
				if (! is_login) {
					var param = Site.Form.GetValue('win-paypal');
					$("#custom_email").val(param.email);
					$("input[name=return]").val( $("input[name=return]").val()+'&email='+encodeURIComponent(param.email) );
				}
			});
			
			// doku
			$('#form-doku .btn-prepare').click(function() {
				// set data to form
				var param = Site.Form.GetValue('form-doku');
				$('#win-doku [name="email"]').val(param.EMAIL);
				$('#win-doku [name="fullname"]').val(param.NAME);
				$('#win-doku [name="address"]').val(param.ADDRESS);
				$('#win-doku [name="city"]').val(param.CITY);
				$('#win-doku [name="propinsi"]').val(param.PROVINCE);
				$('#win-doku [name="zipcode"]').val(param.ZIPCODE);
				$('#win-doku [name="phone"]').val(param.HOMEPHONE);
				$('#win-doku [name="mobile"]').val(param.MOBILEPHONE);
				$('#win-doku [name="office"]').val(param.WORKPHONE);
				$('#win-doku [name="birthdate"]').val(param.BIRTHDATE);
				$('#win-doku').modal();
			});
			$('#win-doku .save').click(function() {
				if (! $('#win-doku form').valid()) {
					return;
				}
				
				// set config doku
				doku.get_word();
				
				var param = Site.Form.GetValue('win-doku');
				param.birthdate = Func.SwapDate(param.birthdate);
				param.WORDS = $('#form-doku [name="WORDS"]').val();
				param.TRANSIDMERCHANT = $('#form-doku [name="TRANSIDMERCHANT"]').val();
				
				Func.ajax({ url: web.host + 'item/doku_prepare', param: param, callback: function(result) {
					if (result.status) {
						var birthdate = param.birthdate;
						birthdate = Func.SwapDate(birthdate);
						birthdate = birthdate.replace(new RegExp(/[^0-9]/gi), '');
						
						$('#form-doku [name="EMAIL"]').val(param.email);
						$('#form-doku [name="NAME"]').val(param.fullname);
						$('#form-doku [name="ADDRESS"]').val(param.address);
						$('#form-doku [name="CITY"]').val(param.city);
						$('#form-doku [name="STATE"]').val(param.propinsi);
						$('#form-doku [name="PROVINCE"]').val(param.propinsi);
						$('#form-doku [name="ZIPCODE"]').val(param.zipcode);
						$('#form-doku [name="HOMEPHONE"]').val(param.phone);
						$('#form-doku [name="MOBILEPHONE"]').val(param.mobile);
						$('#form-doku [name="WORKPHONE"]').val(param.office);
						$('#form-doku [name="BIRTHDATE"]').val(birthdate);
						$("#form-doku").submit();
					}
				} });
			});
			$('#win-doku .cancel').click(function() {
				$('#win-doku').modal('hide');
			});
			
			/*
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
			/*	*/
        });
    </script>
    
</body>
</html>