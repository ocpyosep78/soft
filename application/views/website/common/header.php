<?php
	$is_login = $this->User_model->is_login();
?>

<div class="navbar navbar-fixed-top custom-theme">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</a>
			<a href="index.html" class="brand">Job Board</a>
			<div id="main-menu" class="nav-collapse collapse">
				<ul id="main-menu-right" class="nav pull-right">
					<li class=""><a href="<?php echo base_url('browse'); ?>">Browse jobs</a></li>
					<li class=""><a href="<?php echo base_url('post'); ?>">Post</a></li>
					<?php if (! $is_login) { ?>
					<li class=""><a href="<?php echo base_url('login'); ?>">Login</a></li>
					<?php } else { ?>
					<li class=""><a href="<?php echo base_url('logout'); ?>">Logout</a></li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>
</div>
<div class="row-fluid header_bar navbar navbar-fixed-top main-theme">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<div class="logo">
				<a href="<?php echo base_url(); ?>">
					<img src="<?php echo base_url('static/img/logo.png'); ?>" style="width: 200px;" />
				</a>
			</div>
			
			<div class="nav-collapse pull-right">
				<ul class="nav nav-pills">
					<li class=""><a href="<?php echo base_url('browse'); ?>">Browse jobs</a></li>
					<li class=""><a href="<?php echo base_url('post'); ?>">Post</a></li>
					<?php if (! $is_login) { ?>
					<li class=""><a href="<?php echo base_url('login'); ?>">Login</a></li>
					<?php } else { ?>
					<li class=""><a href="<?php echo base_url('logout'); ?>">Logout</a></li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>
</div>