<?php $this->load->view( 'website/common/meta' ); ?>
<body>
	<?php $this->load->view( 'website/common/header' ); ?>
	
	<div class="container-fluid sidebar_content">
	<div class="row-fluid">
		
		<div class="span8">
			<br />
			<div class="row-fluid">
				<div class="span12">
					<h2><i class="icon-key"></i>&nbsp;&nbsp;Login/Register</h2>
					<p>You must login or create an account in order to post a job - this will enable you to view, remove, or relist your listing in the future.</p>
				</div>
			</div>
			<hr />
			
			<div class="row-fluid">
				<div class="span6">
					<h2>Create a free account</h2>
					<form id="form-register">
						<input type="hidden" name="action" value="register" />
						
						<fieldset>
							<div class="control-group">
								<label class="control-label">Username</label>
								<div class="controls"><input type="text" placeholder="Enter your username" name="name" class="input-xlarge focused"></div>
							</div>           
							<div class="control-group">
								<label class="control-label">Email</label>
								<div class="controls"><input type="text" placeholder="Enter your username" name="email" class="input-xlarge focused"></div>
							</div>
							<div class="control-group">
								<label class="control-label">Password</label>
								<div class="controls"><input type="password" placeholder="Enter your password" name="passwd" id="passwd" class="input-xlarge"></div>
							</div>
							<div class="control-group">
								<label class="control-label">Confirm password</label>
								<div class="controls"><input type="password" placeholder="Enter your password" name="passwd_check" class="input-xlarge"></div>
							</div>
							<a class="cursor btn btn-primary btn-register">Create an account</a>
						</fieldset>
					</form>
				</div>      
				
				<div class="span6 pull-right">
					<h2>Already have an account?</h2>
					<form id="form-login">
						<input type="hidden" name="action" value="login" />
						
						<fieldset>
							<div class="control-group">
								<label class="control-label">Username</label>
								<div class="controls"><input type="text" placeholder="Enter your username" name="name" class="input-xlarge focused"></div>
							</div>
							<div class="control-group">
								<label class="control-label">Password</label>
								<div class="controls"><input type="password" placeholder="Enter your password" name="passwd" class="input-xlarge"></div>
							</div>
							<a class="cursor btn btn-login btn-primary">Login</a>
						</fieldset>
					</form>
					
				</div>
			</div>
		</div>
		
		<div class="span4 sidebar"> 
			<br />
			<h2>Posting a job is Free!!!</h2>
			
			<div class="row-fluid form-tooltip">  
				<div class="span12">
					<h4>Reach thousands of users</h4>
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. In congue turpis sed enim posuere malesuada. Aliquam a urna et dolor blandit tincidunt.
				</div>  
			</div>  
		
			<div class="row-fluid form-tooltip"> 
				<div class="span12">
					<h4>View CVs instantly</h4>
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. In congue turpis sed enim posuere malesuada. Aliquam a urna et dolor blandit tincidunt.
				</div>      
			</div>  
			
			<div class="row-fluid form-tooltip"> 
				<div class="span12">
					<h4>Integrated analytics</h4>
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. In congue turpis sed enim posuere malesuada. Aliquam a urna et dolor blandit tincidunt.
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
	
	$('.btn-register').click(function() {
		if (! $("#form-register").valid()) {
			return false;
		}
		
		var param = Site.Form.GetValue('form-register');
		Func.ajax({ url: web.host + 'ajax/user', param: param, callback: function(result) {
			Func.show_notice({ title: 'Informasi', text: result.message });
		} });
	});
	$('.btn-login').click(function() {
		if (! $("#form-login").valid()) {
			return false;
		}
		
		var param = Site.Form.GetValue('form-login');
		Func.ajax({ url: web.host + 'ajax/user', param: param, callback: function(result) {
			Func.show_notice({ title: 'Informasi', text: result.message });
			window.location = result.link_next;
		} });
	});
});
</script>

</body>
</html>