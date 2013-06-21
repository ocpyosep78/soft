<?php
	$is_login = $this->User_model->is_login();
	$user = $this->User_model->get_session();
?>
<header id="cnt-header">
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container-fluid">
				<a class="brand" href="<?php echo site_url('panel/home'); ?>"><i class="icon-home icon-white"></i> Shop Admin</a>
				<ul class="nav user_menu pull-right">
					<?php if ($is_login) { ?>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $user['fullname']; ?> <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<?php foreach ($user['store_array'] as $store) { ?>
									<li><a href="<?php echo $store['domain']; ?>"><?php echo $store['title']; ?></a></li>
								<?php } ?>
								<?php if (isset($user['store_active']) && isset($user['store_active']['domain'])) { ?>
									<li class="divider"></li>
									<li><a href="<?php echo site_url(); ?>">Go to Store</a></li>
								<?php } ?>
								<li class="divider"></li>
								<li><a href="<?php echo site_url('ajax/logout'); ?>">Log Out</a></li>
							</ul>
						</li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>
</header>