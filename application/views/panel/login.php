<?php $this->load->view( 'panel/common/meta' ); ?>
<body>
<style>
	html, body { height: auto; overflow: hidden; }
</style>
<?php $this->load->view( 'panel/common/header' ); ?>

<div class="container-fluid login_page" id="maincontent">
	<br/>
	<div class="login_box">
		<form method="post" id="login_form">
			<div class="top_b">Sign in to Admin Panel</div>
			<div id="alert" class="alert alert-info alert-login">Enter Username and Password.</div>
			
			<div class="cnt_b">
				<div class="formRow">
					<div class="input-prepend">
						<span class="add-on"><i class="icon-user"></i></span><input type="text" name="name" placeholder="Name" />
					</div>
				</div>
				<div class="formRow">
					<div class="input-prepend">
						<span class="add-on"><i class="icon-lock"></i></span><input type="password" name="passwd" placeholder="Password" />
					</div>
				</div>
			</div>
			<div class="btm_b clearfix">
				<button class="btn btn-inverse pull-right login" type="submit" style="margin: 0 10px 0 0;">Login</button>
			</div>
		</form>
		
		<form method="post" id="pass_form" class="hide">
			<div class="top_b">Can't sign in?</div>    
				<div class="alert alert-info alert-login">
				Please enter your email address. You will receive a link to create a new password via email.
			</div>
			<div class="cnt_b">
				<div class="formRow clearfix">
					<div class="input-prepend">
						<span class="add-on">@</span><input type="text" placeholder="Your email address" />
					</div>
				</div>
			</div>
			<div class="btm_b tac">
				<button class="btn btn-inverse" type="submit">Request New Password</button>
			</div>  
		</form>
	</div>
	
	<footer class="footer center">
		<p>&copy; <?php echo date("Y"); ?></p>
	</footer>
</div>

<?php $this->load->view( 'panel/common/js' ); ?>
<script type="text/javascript">
	$(document).ready(function() {
		setTimeout('$("html").removeClass("js")', 100);
		//alert( web.host + 'ajax/user');
		$('#login_form').submit( function( e ) {
			var param = Site.Form.GetValue('login_form');
			param.action = 'login';
			Func.ajax({ url: web.host + 'website/ajax/user', param: param, callback: function(result) {
				if (result.status == 1) {
					window.location.href = web.host + 'panel/home';
				} else {
					$('#alert').html(result.message);
				}
			} });
			
			return false;
		});
	});
</script>
</body>
</html>