<?php
	$message = '';
	$reset = (empty($_GET['reset'])) ? '' : $_GET['reset'];
	if (!empty($reset)) {
		$user = $this->User_model->get_by_id(array( 'reset' => $reset ));
		if (count($user) == 0) {
			$message = 'Maaf, link ini sudah tidak valid.';
            } else {
			$passwd = substr(EncriptPassword(time()), 0, 8);
			$param_update['id'] = $user['id'];
			$param_update['passwd'] = EncriptPassword($passwd);
			$this->User_model->update($param_update);
			
			$message = 'Password anda berhasil diperbaharui, silahkan memeriksa email anda.';
        }
    }
	
	$next_url = empty($_GET['next']) ? '' : $_GET['next'];
?>

<?php $this->load->view( 'website/common/meta' ); ?>
<body>
	<?php $this->load->view( 'website/common/header' ); ?>
	
	<div class="container-fluid">
        <div class="row-fluid">
            
            <div class="span12">
                <br />
                <div class="row-fluid">
                    <div class="span12">
                        <h2><i class="icon-key"></i>&nbsp; <a href="<?php echo base_url(); ?>">HOME</a> > Login / Daftar </h2>
                        <p>Silahkan login jika anda memiliki akun di LintasApps atau silahkan daftar untuk membuat akun baru.</p>
                    </div>
                </div>
                <hr />
                
                <?php if (!empty($message)) { ?>
                    <div class="row-fluid" style="text-align: center; color: #FF0000;">
                        <?php echo $message; ?>
                    </div>
                <?php } ?>
                
                <div class="row-fluid">
                    <div class="span6">
                        <h2>Daftar account gratis</h2>
                        <form id="form-register">
                            <input type="hidden" name="action" value="register" />
							<input type="hidden" name="next_url" value="<?php echo $next_url; ?>">
                            
                            <fieldset>
                                <div class="control-group">
                                    <label class="control-label">Username</label>
                                    <div class="controls"><input type="text" placeholder="Masukkan username anda" name="name" class="input-xlarge focused input_tooltips" data-placement="right" title="Masukkan username Anda disini"></div>
                                </div>           
                                <div class="control-group">
                                    <label class="control-label">Email</label>
                                    <div class="controls">
                                    <input type="text"  placeholder="Masukkan email anda" name="email" class="input-xlarge focused input_tooltips" data-placement="right" title="Masukkan email Anda disini">
                                        </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Password</label>
                                    <div class="controls"><input type="password" placeholder="Masukkan password anda" name="passwd" id="passwd" class="input-xlarge input_tooltips" data-placement="right" title="Masukkan password Anda disini"></div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Konfirmasi password</label>
                                    <div class="controls"><input type="password" placeholder="Konfirmasi password anda" name="passwd_check" class="input-xlarge input_tooltips" data-placement="right" title="Konfirmasi password Anda disini"></div>
                                </div>
								<input type="submit" class="cursor btn btn-primary input_tooltips" data-placement="right" title="Tekan tombol daftar account, setelah anda melengkapi form daftar account" value="Daftar account" />
								
                                <hr/>
                                <div class="control-group">
                                    <ol>
                                        <li>Dengan mendaftar account, <a href="https://www.lintasapps.com/ketentuan">Anda menyetujui Ketentuan Kami dan bahwa Anda telah membaca Kebijakan Penggunaan Data</a>, termasuk penggunaan Cookie </li>
                                        <li>Link verifikasi akan dikirim ke email Anda </li>
                                        <li>Apabila email tidak terkirim, mohon cek folder spam ada terlebih dahulu, atau hubungi team support LintasAPPS </li>
                                    </ol>
                                </div>
                            </fieldset>
                        </form>
                    </div>      
                    
                    <div class="span6 pull-right">
                        <div id="cnt-login">
                            <h2>Sudah memiliki account?</h2>
                            <form id="form-login">
                                <input type="hidden" name="action" value="login" />
								<input type="hidden" name="next_url" value="<?php echo $next_url; ?>">
                                
                                <fieldset>
                                    <div class="control-group">
                                        <label class="control-label">Username</label>
                                        <div class="controls"><input type="text" placeholder="Masukkan username anda" name="name" class="input-xlarge focused input_tooltips" data-placement="right" title="Masukkan username Anda disini"></div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Password</label>
                                        <div class="controls"><input type="password" placeholder="Masukkan password anda" name="passwd" class="input-xlarge input_tooltips" data-placement="right" title="Masukkan password Anda disini"></div>
                                    </div>
									<input type="submit" class="cursor btn btn-primary input_tooltips" data-placement="right" title="Tekan tombol login, untuk masuk kedalam lintasapps" value="Login" />
                                </fieldset>
                            </form>
                            <h4><a class="cursor show-forgot input_tooltips" data-placement="right" title="Jika Anda lupa password / kata sandi, tekan link ini">Lupa Kata Sandi</a></h4>
                        </div>
                        
                        <div id="cnt-forgot" class="hide">
                            <h2>Lupa Kata Sandi ?</h2>
                            <form id="form-forgot">
                                <input type="hidden" name="action" value="forgot" />
                                
                                <fieldset>
                                    <div class="control-group">
                                        <label class="control-label">Username</label>
                                        <div class="controls"><input type="text" placeholder="Masukkan username anda" name="name" class="input-xlarge focused"></div>
                                    </div>
									
									<input type="submit" class="cursor btn btn-primary" value="Reset" />
                                </fieldset>
                            </form>
                            <h4><a class="cursor show-login">Login</a></h4>
                            
                        </div>
                    </div>
                </div>
            </div>
            
            
        </div>	
    </div>
    <?php $this->load->view( 'website/common/footer' ); ?>
    
    <script>
        $(document).ready(function() {
            $("#form-register").validate({
                rules: {
                    name: { required: true, minlength: 2 },
                    email: { required: true, email: true },
                    passwd: { required: true, minlength: 5 },
                    passwd_check: { required: true, minlength: 5, equalTo: "#passwd" }
                },
                messages: {
                    name: { required: 'Silahkan mengisi field ini', minlength: '2 minimal karakter' },
                    email: { required: 'Silahkan mengisi field ini', email: 'Email anda tidak valid' },
                    passwd: { required: 'Silahkan mengisi field ini', minlength: '5 minimal karakter' },
                    passwd_check: { required: 'Silahkan mengisi field ini', minlength: '5 minimal karakter', equalTo: 'Password anda tidak sama' }
                }
            });
            $("#form-login").validate({
                rules: {
                    name: { required: true, minlength: 2 },
                    passwd: { required: true, minlength: 5 }
                },
                messages: {
                    name: { required: 'Silahkan mengisi field ini', minlength: '2 minimal karakter' },
                    passwd: { required: 'Silahkan mengisi field ini', minlength: '5 minimal karakter' }
                }
            });
            $("#form-forgot").validate({
                rules: {
                    name: { required: true, minlength: 2 }
                },
                messages: {
                    name: { required: 'Silahkan mengisi field ini', minlength: '2 minimal karakter' }
                }
            });
            
			$("#form-register").submit(function() {
                if (! $("#form-register").valid()) {
                    return false;
                }
                
                var param = Site.Form.GetValue('form-register');
                Func.ajax({ url: web.host + 'ajax/user', param: param, callback: function(result) {
                    if (result.status) {
                        window.location = result.link_next;
					} else {
                        Func.show_notice({ title: 'Informasi', text: result.message });
                    }
                } });
				return false;
            });
            $('#form-login').submit(function() {
                if (! $("#form-login").valid()) {
                    return false;
                }
                
                var param = Site.Form.GetValue('form-login');
                Func.ajax({ url: web.host + 'ajax/user', param: param, callback: function(result) {
                    if (result.status) {
                        window.location = result.link_next;
					} else {
                        Func.show_notice({ title: 'Informasi', text: result.message });
                    }
                } });
				return false;
            });
            $('#form-forgot').submit(function() {
                if (! $("#form-forgot").valid()) {
                    return false;
                }
                
                var param = Site.Form.GetValue('form-forgot');
                Func.ajax({ url: web.host + 'ajax/user', param: param, callback: function(result) {
                    Func.show_notice({ title: 'Informasi', text: result.message });
                } });
				return false;
            });
            
            $('.show-forgot').click(function() {
                $('#cnt-login').hide();
                $('#cnt-forgot').show();
            });
            $('.show-login').click(function() {
                $('#cnt-forgot').hide();
                $('#cnt-login').show();
            });
        });
    </script>
    
</body>
</html>