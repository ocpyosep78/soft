<?php
	preg_match('/([\d]+)/i', $_SERVER['REQUEST_URI'], $match);
	$item_id = (isset($match[1])) ? $match[1] : 0;
	if (!$item_id) {
		show_404();
		exit;
	}
	
	$user = $this->User_model->get_session();
	$item = $this->Item_model->get_by_id(array( 'id' => $item_id ));
	
	$is_login = $this->User_model->is_login();
	
	//perlu untuk nyimpen data temporary, silahkan di model kan jika perlu,
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
	
	$konversi_rupiah = $this->Default_Value_model->get_konversi_rupiah_dolar();
	$harga_dolar_asli = $item['price'] / $konversi_rupiah['value'];
	$paypal_add = 0; //ceil(($harga_dolar_asli * (3.4/100)) + 0.30);
	$harga_dolar = number_format($harga_dolar_asli + $paypal_add, 2, '.', '');
	
	$cdata['item_id'] = $item_id;
	$cdata['dolar'] = $harga_dolar_asli;
	$cdata['paypal_add'] = $paypal_add;
	$cdata['paypal_dolar'] = $harga_dolar;
	$cdata['konversi_rupiah'] = $konversi_rupiah['value'];
	
	$ipaymu_add = 0; //ceil(($item['price'] * (3.4/100)) + 0.30);
	//if ($ipaymu_add<1000) $ipaymu_add = 1000;
	$ipaymu_price = $item['price'] + $ipaymu_add;
	$cdata['ipaymu_add'] = $ipaymu_add;
	$cdata['ipaymu_price'] = $ipaymu_price;
	
	$doku_add = 0; //ceil(($item['price'] * (3.4/100)) + 0.30);
	//if ($doku_add<1000) $doku_add = 1000;
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
	
?>

<?php $this->load->view( 'website/common/meta' ); ?>
<body>
    <?php $this->load->view( 'website/common/header' ); ?>
	
	<!-- doku -->
	
	<script type="text/javascript" src="<?php echo base_url('static/js/dateformat.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('static/js/sha-1.js'); ?>"></script>
	
	<style>
		.pilihbayar form {margin:0;}
        .pilihbayar div{padding-top:10px; }
			
		/*
		.paypal-button { white-space: nowrap; padding:0 10px; }
		.paypal-button button { white-space: nowrap; overflow: hidden; border-radius: 13px; font-family: "Arial", bold, italic; font-weight: bold; font-style: italic; border: 1px solid #ffa823; color: #0E3168; background: #ffa823; position: relative; text-shadow: 0 1px 0 rgba(255,255,255,.5); cursor: pointer; z-index: 0; }
		.paypal-button button:before { content: " "; position: absolute; width: 100%; height: 100%; border-radius: 11px; top: 0; left: 0; background: #ffa823; background: -webkit-linear-gradient(top, #FFAA00 0%,#FFAA00 80%,#FFF8FC 100%); background: -moz-linear-gradient(top, #FFAA00 0%,#FFAA00 80%,#FFF8FC 100%); background: -ms-linear-gradient(top, #FFAA00 0%,#FFAA00 80%,#FFF8FC 100%); background: linear-gradient(top, #FFAA00 0%,#FFAA00 80%,#FFF8FC 100%); z-index: -2; }
		.paypal-button button:after { content: " "; position: absolute; width: 98%; height: 60%; border-radius: 40px 40px 38px 38px; top: 0; left: 0; background: -webkit-linear-gradient(top, #fefefe 0%, #fed994 100%); background: -moz-linear-gradient(top, #fefefe 0%, #fed994 100%); background: -ms-linear-gradient(top, #fefefe 0%, #fed994 100%); background: linear-gradient(top, #fefefe 0%, #fed994 100%); z-index: -1; -webkit-transform: translateX(1%);-moz-transform: translateX(1%); -ms-transform: translateX(1%); transform: translateX(1%); }
		.paypal-button button.small { padding: 3px 15px; font-size: 12px; }
		.paypal-button button.large { padding: 4px 19px; font-size: 14px; }
		*/
		
		.via { font-weight:bold; display:block; padding:5px 0;}
		.paypal-option { padding:5px 0; }
		
		@media (max-width: 767px) {
			.pilihbayar div.lx { border-right: none; border-bottom:1px solid #bbb; }
			.pilihbayar div.span4 { margin:0; text-align:center; padding:10px 0; }
		}
	</style>
    
    <div class="container-fluid sidebar_content"><div class="row-fluid">
        <div class="span8">	
            <br />
            <h2><a href="<?php echo base_url(); ?>">HOME</a> > Beli > <?php echo $item['name']; ?></h2>
            
			<div class="row-fluid form-tooltip">
				<input type="hidden" name="id" value="<?php echo $item['id']; ?>" />
				<input type="hidden" name="is_login" value="<?php echo ($is_login) ? 1 : 0; ?>" />
				
				<form id="form-payment">
				<div class="span12">
					<?php if ( !empty($_GET['error']) ): ?>
					<div class="alert alert-error">
						<h3>Pembayaran anda tertunda atau bermasalah</h3>
						<p>Mohon maaf, mohon cek dan ulangi kembali pembayaran anda, karena kemungkinan pembayaran anda tertunda atau bermasalah</p>
					</div>
					<?php endif; ?>
				
					<h1><?php echo $item['name']; ?></h1>
					<div class="item-price">
						<span class="label-info" style="color:#fff;"><?php echo $item['price_text']; ?></span>
						<span class="label-success" style="color:#fff;">US $<?php echo number_format($harga_dolar_asli, 2, '.', ','); ?></span>
					</div>
					
					<?php if (! $is_login) { ?>
					<div style="margin-top:20px;">
						<label for="user_email1">Masukkan alamat e-mail anda atau <a href="https://www.lintasapps.com/login?next=<?php echo rawurlencode($_SERVER['REQUEST_URI']); ?>">login</a> untuk membeli</label>
						<input type="text" name="email" id="user_email1" value="<?php echo $email; ?>" class="input_tooltips" data-placement="right" title="Untuk mencatat pembelian anda, mohon masukkan alamat e-mail anda. Link download juga akan dikirim ke e-mail anda."/>
					</div>
					<?php } ?>
					
					<!--
					<h4>Pilih Pembayaran</h4>
					<label><input type="radio" value="paypal" name="payment" style="margin: -3px 0 0 0;" /> <img src="<?php echo base_url('static/img/logo-paypal.png'); ?>" alt="paypal" /></label>
					<label><input type="radio" value="ipaymupay" name="payment" style="margin: -3px 0 0 0;" /> <img src="<?php echo base_url('static/img/logo-ipaymu.png'); ?>" alt="ipaymu" /></label>
					
					<div style="text-align: center; padding: 0 0 20px 0;">
						<a class="cursor btn btn-primary btn-pay">Bayar</a>
					</div>
					-->
				</div>
				</form>
			</div>
				
			<div class="row-fluid">
				<div class="span12">
					<h3>Pilih Pembayaran</h3>
				</div>
			</div>
			
			<div class="row-fluid pilihbayar">
				<div class="span4 text-center lx">
					<form method="post" action="https://www.paypal.com/cgi-bin/webscr" class="paypal-button" target="_top">
						<input type="hidden" id="custom_email" name="custom" value="">
						<input type="hidden" name="button" value="buynow">
						<input type="hidden" name="item_name" value="<?php echo $item['name']; ?>">
						<input type="hidden" name="amount" value="<?php echo $harga_dolar; ?>">
						<input type="hidden" name="currency_code" value="USD">
						<input type="hidden" name="item_number" value="1">
						<input type="hidden" name="lc" value="id_ID">
						<input type="hidden" name="notify_url" value="<?php echo base_url('item/paypalnotify?id='.$checkout_id); ?>">
						<input type="hidden" name="return" value="<?php echo base_url('item/thanks?tipe=paypal&id='.$checkout_id); ?>">
						<input type="hidden" name="cancel_return" value="<?php echo base_url('item/buy/'.$item['id']); ?>">
						<input type="hidden" name="cmd" value="_xclick">
						<input type="hidden" name="business" value="MTNRJT46ZBX9W">
						<input type="hidden" name="bn" value="SIMETRI_BuyNow_WPS_ID">
						<input type="hidden" name="env" value="www">
						<input type="hidden" name="no_shipping" value="1">
						
						<button type="submit" class="btn btn-large btn-primary btn-xlarge"><span class="paypal">&nbsp;</span></button>
						<!--
						<button type="submit" class="paypal-button large">Beli dengan PayPal</button>
						<span class="via">(+$<?php echo $paypal_add; ?> biaya)</span>
						<div class="paypal-option">
							<img src="<?php echo base_url('static/img/paypal_options.jpg'); ?>">
						</div>
						-->
					</form>
				</div>
				<div class="span4 text-center lx">
					<form method="post" action="<?php echo base_url('item/ipaymu2'); ?>" id="formipaymu">
						<input type="hidden" id="emailsaya" name="email" value="">
						<input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
						<input type="hidden" name="item_price" value="<?php echo $ipaymu_price; ?>">
						<input type="hidden" name="item_add" value="<?php echo $ipaymu_add; ?>">
						<input type="hidden" name="checkout_id" value="<?php echo $checkout_id; ?>">
						
						<button type="submit" class="btn btn-large btn-biasa btn-xlarge"><span class="ipaymu">&nbsp;</span></button>
						
						<!--
						<span class="via">
							<img src="<?php echo base_url('static/img/logo-ipaymu.png'); ?>" alt="ipaymu" style="height:12px; width:auto;" />
							biaya Rp<?php echo number_format($ipaymu_add, 0, '.', ','); ?>
						</span>
						<div class="paypal-option">
							<img src="<?php echo base_url('static/img/ipaymu_options.jpg'); ?>">
						</div>
						-->
					</form>
				</div>
				
			
					<div class="span4 text-center ll">
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
							
							<!--
							<input type="submit" class="btn btn-medium btn-primary" name="submit" value="Beli di Doku">
							<span class="via">
								<img src="<?php echo base_url('static/img/logo_doku.gif'); ?>" alt="doku" style="height:20px; width:auto;" />
								biaya Rp<?php echo number_format($doku_add, 0, '.', ','); ?>
							</span>
							<div class="paypal-option">
								<img src="<?php echo base_url('static/img/doku_options.jpg'); ?>">
							</div>
							-->
						</form>
					</div>
				
			</div>
        </div>
        
        <div class="span4 sidebar">
            <div class="row-fluid form-tooltip">	
                <div class="span12">
					<br /><br />
					
                    <h4>Detail</h4>
                    <div>Platform : <?php echo $item['platform_name']; ?></div>
                    <div>Category : <?php echo $item['category_name']; ?></div>
                    <div>Pemilik : <?php echo $item['user_name']; ?></div>
                </div>	
            </div>
        </div>		
    </div></div>
    
    <?php $this->load->view( 'website/common/footer' ); ?>
	
	<div id="win-doku" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">
		<div class="modal-header">
			<a href="#" class="close" data-dismiss="modal">&times;</a>
			<h3>Form Pembayaran Doku</h3>
		</div>
		<div class="modal-body" style="padding-left: 0px;">
			<div class="pad-alert" style="padding-left: 15px;"></div>
			<form class="form-horizontal" style="padding-left: 0px;">
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
    
	<script type="text/javascript">
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
		
		$(function() {
			// doku
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
			
			$("form.paypal-button").submit(function() {
				var form_email = $("#user_email1");
				if (form_email.length > 0) {
					var form = $("#form-payment");
					form.validate({
						rules: {
							email: { required: true, email: true }
						},
						messages: {
							email: { 
								required: 'Mohon masukkan alamat e-mail anda untuk link download dan pencatatan pembelian', 
								email: 'Email yang Anda masukkan tidak valid, mohon ulangi lagi' 
							}
						}
					});
					
					if ( !form.valid() ) {
						return false;
					}
					
					$("#custom_email").val(form_email.val());
					$("input[name=return]").val( $("input[name=return]").val()+'&email='+encodeURIComponent(form_email.val()) );
				}
				return true;
			});
			
			$("#formipaymu").submit(function() {
				var form_email = $("#user_email1");
				if (form_email.length > 0) {
					var form = $("#form-payment");
					form.validate({
						rules: {
							email: { required: true, email: true }
						},
						messages: {
							email: { 
								required: 'Mohon masukkan alamat e-mail anda untuk link download dan pencatatan pembelian', 
								email: 'Email yang Anda masukkan tidak valid, mohon ulangi lagi' 
							}
						}
					});
					
					if ( !form.valid() ) {
						return false;
					}
					
					$("#emailsaya").val(form_email.val());
				}
				return true;
			});
			
			$('#form-doku .btn-prepare').click(function() {
				var form_email = $("#user_email1");
				if (form_email.length > 0) {
					var form = $("#form-payment");
					form.validate({
						rules: {
							email: { required: true, email: true }
						},
						messages: {
							email: { 
								required: 'Mohon masukkan alamat e-mail anda untuk link download dan pencatatan pembelian', 
								email: 'Email yang Anda masukkan tidak valid, mohon ulangi lagi' 
							}
						}
					});
					
					if ( !form.valid() ) {
						return false;
					}
					
					// set email from email form
					$('#form-doku [name="EMAIL"]').val(form_email.val());
				}
				
				// set data to form
				var param = Site.Form.GetValue('form-doku');
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
				param.email = $('#form-doku [name="EMAIL"]').val();
				param.birthdate = Func.SwapDate(param.birthdate);
				param.TRANSIDMERCHANT = $('#form-doku [name="TRANSIDMERCHANT"]').val();
				param.WORDS = $('#form-doku [name="WORDS"]').val();
				
				Func.ajax({ url: web.host + 'item/doku_prepare', param: param, callback: function(result) {
					if (result.status) {
						var birthdate = param.birthdate;
						birthdate = Func.SwapDate(birthdate);
						birthdate = birthdate.replace(new RegExp(/[^0-9]/gi), '');
						
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
		});	
	
        $(document).ready(function() {
            var is_login = ($('[name="is_login"]').val() == 1) ? true : false;
            if (! is_login) {
                $("#form-payment").validate({
                    rules: {
                        email: { required: true, email: true }
                    },
                    messages: {
                        email: { required: 'Silakan masukkan alamat e-mail Anda, alamat download akan dikirimkan ke e-mail anda', email: 'Alamat e-mail yang Anda masukkan tidak valid, mohon ulangi lagi' }
                    }
                });
            }
            
            $('.btn-pay').click(function() {
                var param = Site.Form.GetValue('form-payment');
                param.payment = $('input[name=payment]:checked').val();
                if (param.payment == null) {
                    Func.show_notice({ title: 'Informasi', text: 'Harap memilih salah satu metode pembayaran' });
                    return false;
                }
                
                if (! is_login && ! $("#form-payment").valid()) {
                    return false;
                }
                
                $('.btn-pay').parent('div').text('Harap tunggu sebentar, pembayaran Anda sedang diproses.');
                Func.ajax({ url: web.host + 'item/payment', param: param, callback: function(result) {
                    if (result.status) {
                        window.location = result.link_next;
					} else {
						Func.show_notice({ title: 'Informasi', text: result.message });
                    }
                } });
            });
        });
    </script>
    
</body>
</html>