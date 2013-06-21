<?php
	// store
	$store_name = get_store();
	$store = $this->Store_Detail_model->get_info(array('store_name' => $store_name));
	
	// catalog
	$param_catalog = array(
		'filter' => '[{"type":"numeric","comparison":"eq","value":"'.$store['store_id'].'","field":"store_id"}]',
		'sort' => '[{"property":"Catalog.title","direction":"ASC"}]'
	);
	$catalog = $this->Catalog_model->get_array($param_catalog);
	
	// user
	$is_login = $this->User_model->is_login();
	
	// cart
	$item_count = $this->Cart_model->get_count();
	
	// blog
	$param_blog = array(
		'filter' => '[{"type":"numeric","comparison":"eq","value":"2","field":"BlogStatus.id"},{"type":"numeric","comparison":"eq","value":"'.$store['store_id'].'","field":"store_id"}]',
		'sort' => '[{"property":"Blog.create_date","direction":"DESC"}]',
		'limit' => 4
	);
	$array_blog = $this->Blog_model->get_array($param_blog);
?>

<div class="main-dock-wrapper">
	<div class="main-dock">
		<ul>
			<li><a href="<?php echo site_url(); ?>" class="home"></a></li>
			<?php if ($is_login) { ?>
				<li><a href="<?php echo site_url('download'); ?>">Download</a></li>
				<li><a href="<?php echo site_url('order'); ?>">Order</a></li>
				<li><a href="<?php echo site_url('ajax/logout'); ?>">Logout</a></li>
			<?php } else { ?>
				<li><a href="<?php echo site_url('login'); ?>">Login</a></li>
			<?php } ?>
			<?php if ($item_count > 0) { ?>
			<li class="checkout"><a href="<?php echo site_url('checkout/step/1'); ?>">Checkout</a></li>
			<?php } ?>
			<li class="cart"><a href="<?php echo site_url('cart'); ?>">Your cart: <b class="header-card"><?php echo $item_count; ?> items</b></a></li>
		</ul>
	</div>
</div>

<div class="main-header">
	<div class="logo">
		<a href="<?php echo site_url(); ?>"><img src="<?php echo base_url(); ?>static/theme/calisto/img/logo-soulage-1.png" alt="" /></a>
		<span class="custom-font-1"><?php echo $store['store_logo']['content']; ?></span>
	</div>
	<div class="search">
		<form action="#" id="form-search" data-link="<?php echo site_url('search/'); ?>">
			<input type="text" name="keyword" class="input-text-1 trans-1" placeholder="search here" />
		</form>
	</div>
	<div class="clear"></div>
	<script type="text/javascript">
		$('#form-search').submit(function() {
			var link = $('#form-search').data('link');
			var search = Func.GetName($('#form-search input[name="keyword"]').val());
			window.location = link + search;
			return false;
		});
	</script>
	
	<div class="main-menu-iphone">
		<div class="categories">
			<span class="icon"></span>
			<select>
				<option>categories</option>
				<option>Homepage</option>
				<option>Featured items</option>
				<option>Catalog</option>
				<option>Blog</option>
				<option>Features</option>
				<option>Contact us</option>
			</select>
		</div>
		<div class="search-iphone">
			<form action="#">
				<input type="text" class="input-text-1 trans-1" placeholder="search here" />
			</form>
		</div>
		<div class="clear"></div>
	</div>
	
	<div class="main-menu custom-font-1">
		<table>
			<tr>
				<td>
					<ul>
						<li><a href="<?php echo site_url(); ?>" class="single">Homepage</a></li>
						<li>
							<a href="<?php echo site_url('catalog'); ?>"><span>Catalog</span></a>
							<ul>
								<?php foreach ($catalog as $array) { ?>
									<li><a href="<?php echo $array['catalog_link']; ?>"><?php echo $array['title']; ?></a></li>
								<?php } ?>
							</ul>
						</li>
						<li>
							<a href="<?php echo site_url('blog'); ?>"><span>Blog</span></a>
							<ul>
								<?php foreach ($array_blog as $blog) { ?>
									<li><a href="<?php echo $blog['blog_link']; ?>"><?php echo $blog['title']; ?></a></li>
								<?php } ?>
							</ul>
						</li>
						<li>
							<a href="<?php echo site_url('contact'); ?>" class="single"><span>Contact us</span></a>
							<ul>
								<li><a href="<?php echo site_url('contact/konfirmasi'); ?>">Konfirmasi Pembayaran</a></li>
							</ul>
						</li>
					</ul>
				</td>
			</tr>
		</table>
	</div>
	<div class="clear"></div>
</div>
