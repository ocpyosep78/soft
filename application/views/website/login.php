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
					
					<form class="">
						<fieldset>
							<div class="control-group">
								<label  class="control-label">Username</label>
								<div class="controls">
									<input type="text" placeholder="Enter your username" id="username" class="input-xlarge focused">
								</div>
							</div>           
							<div class="control-group">
								<label  class="control-label">Email</label>
								<div class="controls">
									<input type="text" placeholder="Enter your username" id="email" class="input-xlarge focused">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Password</label>
								<div class="controls">
									<input type="password" placeholder="Enter your password" id="password" class="input-xlarge">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Confirm password</label>
								<div class="controls">
									<input type="password" placeholder="Enter your password" id="confirm_password" class="input-xlarge">
								</div>
							</div>
							
							<a class="btn btn-primary" href="dashboard.html">Create an account</a>
						</fieldset>
					</form>
					
					
				</div>      
				
				<div class="span6 pull-right">
					<h2>Already have an account?</h2>
					
					<form class="">
						<fieldset>
							<div class="control-group">
								<label  class="control-label">Username</label>
								<div class="controls">
									<input type="text" placeholder="Enter your username" id="username2" class="input-xlarge focused">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Password</label>
								<div class="controls">
									<input type="password" placeholder="Enter your password" id="confirm_password2" class="input-xlarge">
								</div>
								</div>
							
							<a class="btn btn-primary" href="dashboard.html">Login</a>
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
</body>
</html>