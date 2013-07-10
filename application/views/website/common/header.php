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
				<div class="my-title">Hallo <?php echo $user['display_name']; ?>, <span class="show-profile" style="cursor: pointer;">ubah profile</span></div>
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
			<input type="hidden" name="action" value="update" />
			<input type="hidden" name="id" value="<?php echo @$user['id']; ?>" />
			<input type="submit" name="submit" value="submit" class="hide" />
			
			<div class="control-group">
				<label class="control-label">Email</label>
				<div class="controls"><input type="text" name="email" placeholder="Email" class="span4" rel="twipsy" /></div>
			</div>
			<div class="control-group">
				<label class="control-label">Nama</label>
				<div class="controls"><input type="text" name="fullname" placeholder="Nama" class="span4" rel="twipsy" /></div>
			</div>
			<div class="control-group">
				<label class="control-label">Alamat</label>
				<div class="controls"><input type="text" name="address" placeholder="Alamat" class="span4" rel="twipsy" /></div>
			</div>
			<div class="control-group">
				<label class="control-label">Kota</label>
				<div class="controls"><input type="text" name="city" placeholder="Kota" class="span4" rel="twipsy" /></div>
			</div>
			<div class="control-group">
				<label class="control-label">Password</label>
				<div class="controls"><input type="password" name="passwd" placeholder="Biarkan kosong jika anda tidak ingin merubahnya" class="span4" rel="twipsy" /></div>
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
		Func.ajax({ url: web.host + 'ajax/user', param: { action: 'get_user', id: $('#win-profile [name="id"]').val()  }, callback: function(result) {
			$('#win-profile [name="email"]').val(result.email);
			$('#win-profile [name="fullname"]').val(result.fullname);
			$('#win-profile [name="address"]').val(result.address);
			$('#win-profile [name="city"]').val(result.city);
			$('#win-profile [name="passwd"]').val('');
			$('#win-profile').modal();
		} });
	});
	$('#win-profile .save').click(function() {
		$('#win-profile form').submit();
	} );
	$('#win-profile form').submit(function() {
		if (! $('#win-profile form').valid()) {
			return;
		}
		
		var param = Site.Form.GetValue('win-profile');
		Func.ajax({ url: web.host + 'ajax/user', param: param, callback: function(result) {
			Func.show_notice({ title: 'Informasi', text: result.message });
			if (result.status) {
				$('#win-profile').modal('hide');
			}
		} });
		return false;
	});
	$('#win-profile .cancel').click(function() {
		$('#win-profile').modal('hide');
	});
});
</script>