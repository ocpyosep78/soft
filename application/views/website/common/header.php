<?php
	$is_login = $this->User_model->is_login();
	$user = $this->User_model->get_session();
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
				<?php if ($is_login) { ?>
				<div class="my-title">Hallo <?php echo $user['name']; ?>, <span class="show-profile" style="cursor: pointer;">ubah profile</span></div>
				<?php } else { ?>
				<div class="my-title">&nbsp;</div>
				<?php } ?>
				
				<ul class="nav nav-pills">
					<?php if ($is_login) { ?>
					<li><a href="<?php echo base_url('rekap'); ?>">Hasil Jualan</a></li>
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

<div id="win-profile" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">
	<div class="modal-header">
		<a href="#" class="close" data-dismiss="modal">&times;</a>
		<h3>Update Profile</h3>
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
				<label class="control-label" for="input_propinsi">Password</label>
				<div class="controls">
					<input type="text" id="input_propinsi" name="propinsi" placeholder="Biarkan kosong jika anda tidak ingin merubahnya" class="span4" rel="twipsy" />
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
$(document).ready(function() {
	$("#win-profile form").validate({
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
	
	$('.show-profile').click(function() {
		$('#win-profile').modal();
	});
	
	$('#win-profile .save').click(function() {
		if (! $('#win-profile form').valid()) {
			return;
		}
		
		Func.ajax({ url: web.host + 'item/doku_prepare', param: param, callback: function(result) {
			if (result.status) {
				$('#win-profile').modal('hide');
			}
		} });
	});
	$('#win-profile .cancel').click(function() {
		$('#win-profile').modal('hide');
	});
});
</script>