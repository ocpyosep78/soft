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
			<a href="index.html" class="brand">lintasapps</a>
			<div id="main-menu" class="nav-collapse collapse">
				<ul id="main-menu-right" class="nav pull-right">
					<?php if ($is_login) { ?>
					<li><a href="<?php echo base_url('history'); ?>">History</a></li>
					<?php } ?>
					<li><a href="<?php echo base_url('browse'); ?>">Browse</a></li>
					<li><a href="<?php echo base_url('post'); ?>">Mulai Berjualan</a></li>
					<?php if (! $is_login) { ?>
					<li><a href="<?php echo base_url('login'); ?>">Login</a></li>
					<?php } else { ?>
					<li><a href="<?php echo base_url('logout'); ?>">Logout</a></li>
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
					<?php if ($is_login) { ?>
					<li><a href="<?php echo base_url('history'); ?>">History</a></li>
					<?php } ?>
					<li><a href="<?php echo base_url('browse'); ?>">Browse</a></li>
					<li><a href="<?php echo base_url('post'); ?>">Mulai Berjualan</a></li>
					<?php if (! $is_login) { ?>
					<li><a href="<?php echo base_url('login'); ?>">Login</a></li>
					<?php } else { ?>
					<li><a href="<?php echo base_url('logout'); ?>">Logout</a></li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>
</div>