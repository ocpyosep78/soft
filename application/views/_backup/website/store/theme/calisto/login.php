<?php
?>

<?php $this->load->view( 'website/store/theme/calisto/common/meta' ); ?>
<body class="top">
	<?php $this->load->view( 'website/store/theme/calisto/common/feature' ); ?>
	
		<div class="main-body-wrapper">
			<?php $this->load->view( 'website/store/theme/calisto/common/header' ); ?>
			
			<div class="main-content-wrapper">
				<div class="message-slide"></div>
				
				<div class="single-full-width customer">
					<form><div class="login" id="form-login">
						<div class="main-title"><p class="custom-font-1">Customer login</p></div>
						<p>
							<label>E-mail address:</label>
							<input type="text" class="input-text-1 required email" name="email" alt="email" />
						</p>
						<p>
							<label>Password:</label>
							<input type="password" class="input-text-1 required" name="passwd" alt="passwd" />
						</p>
						<p>
							<label></label>
							<a class="cursor show-form-reset">Forgot your password?</a>
						</p>
						<p class="sign-in">
							<label></label>
							<a class="login-send cursor button-1 custom-font-1 trans-1 submit"><span>Sign in</span></a>
						</p>
						<div class="hide"><input type="submit" value="submit" /></div>
					</div></form>
					
					<form><div class="login hide" id="form-reset">
						<div class="main-title"><p class="custom-font-1">Reset Password</p></div>
						<p>
							<label>E-mail address:</label>
							<input type="text" class="input-text-1 required email" name="email" />
						</p>
						<p>
							<label></label>
							<a class="cursor show-form-login">Back to login?</a>
						</p>
						<p class="sign-in">
							<label></label>
							<a class="reset-send cursor button-1 custom-font-1 trans-1 submit"><span>Reset Password</span></a>
						</p>
						<div class="hide"><input type="submit" value="submit" /></div>
					</div></form>
					
					<form><div class="guest-login" id="form-register">
						<div class="main-title"><p class="custom-font-1">New customers</p></div>
						<p>
							<label>Email:</label>
							<input type="text" class="input-text-1 required email" name="email" alt="email" />
						</p>
						<p>
							<label>Nama Lengkap:</label>
							<input type="text" class="input-text-1 required" name="fullname" alt="fullname" />
						</p>
						<p>
							<label>Password:</label>
							<input type="password" class="input-text-1 required" name="passwd" alt="passwd" />
						</p>
						<p>
							<label>&nbsp;</label>
							<a class="register cursor button-1 custom-font-1 trans-1 submit"><span>Sign Up</span></a>
						</p>
						<div class="hide"><input type="submit" value="submit" /></div>
					</div></form>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>
			<script type="text/javascript">
				$('form').submit(function() {
					$(this).find('.submit').click();
					return false;
				});
				
				$('.show-form-reset').click(function() {
					$('#form-login').hide();
					$('#form-reset').show();
				});
				$('.show-form-login').click(function() {
					$('#form-reset').hide();
					$('#form-login').show();
				});
				
				// login
				$('.login-send').click(function() {
					$('.input-error-wrapper').removeClass('input-error-wrapper');
					var validation = Site.Form.Validation('form-login', { });
					if (validation.length > 0) {
						for (var i = 0; i < validation.length; i++) {
							$('#form-login input[name="' + validation[i] + '"]').parent('p').addClass('input-error-wrapper');
						}
						return;
					}
					
					$('.message-slide').hide();
					var p = Site.Form.GetValue('form-login');
					p.action = 'Login';
					Func.ajax({ url: Site.Host + '/ajax/user', param: p, callback: function(result) {
						if (result.status) {
							window.location = web.host;
						} else {
							$('.message-slide').text(result.message)
							$('.message-slide').slideDown(500)
						}
					} });
				});
				
				// reset
				$('.reset-send').click(function() {
					$('.input-error-wrapper').removeClass('input-error-wrapper');
					var validation = Site.Form.Validation('form-reset', { });
					if (validation.length > 0) {
						for (var i = 0; i < validation.length; i++) {
							$('#form-reset input[name="' + validation[i] + '"]').parent('p').addClass('input-error-wrapper');
						}
						return;
					}
					
					$('.message-slide').hide();
					var p = Site.Form.GetValue('form-reset');
					p.action = 'ResetPassword';
					Func.ajax({ url: Site.Host + '/ajax/user', param: p, callback: function(result) {
						$('.message-slide').text(result.message)
						$('.message-slide').slideDown(500)
					} });
				});
				
				// register
				$('.register').click(function() {
					$('.input-error-wrapper').removeClass('input-error-wrapper');
					var validation = Site.Form.Validation('form-register', { });
					if (validation.length > 0) {
						for (var i = 0; i < validation.length; i++) {
							$('#form-register input[name="' + validation[i] + '"]').parent('p').addClass('input-error-wrapper');
						}
						return;
					}
					
					$('.message-slide').hide();
					var p = Site.Form.GetValue('form-register');
					p.action = 'UpdateUser';
					Func.ajax({ url: Site.Host + '/ajax/user', param: p, callback: function(result) {
						if (result.status) {
							result.message = 'Registrasi anda berhasil silahkan login untuk melanjutkan.';
						}
						$('.message-slide').text(result.message)
						$('.message-slide').slideDown(500)
					} });
				});
			</script>
			
			<?php $this->load->view( 'website/store/theme/calisto/common/footer' ); ?>
		</div>
	</body>
</html>