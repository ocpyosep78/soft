<?php
	// store
	$store_name = get_store();
	$store = $this->Store_Detail_model->get_info(array('store_name' => $store_name));
	
	// generate base page
	$item_per_page = 6;
	$page_active = get_page_active();
	$base_page = site_url('blog');
	
	// list blog
	$param_blog = array(
		'filter' => '[{"type":"numeric","comparison":"eq","value":"2","field":"BlogStatus.id"},{"type":"numeric","comparison":"eq","value":"'.$store['store_id'].'","field":"store_id"}]',
		'sort' => '[{"property":"Blog.create_date","direction":"DESC"}]',
		'start' => ($page_active * $item_per_page) - $item_per_page,
		'limit' => $item_per_page
	);
	$array_blog = $this->Blog_model->get_array($param_blog);
	
	// popular blog
	$param_blog = array(
		'filter' => '[' .
			'{"type":"numeric","comparison":"eq","value":"2","field":"BlogStatus.id"},' .
			'{"type":"numeric","comparison":"eq","value":"'.$store['store_id'].'","field":"Blog.store_id"},' .
			'{"type":"numeric","comparison":"gt","value":"'.date("Y-m-d", strtotime("-1 Month")).'","field":"Blog.create_date"}' .
		']',
		'sort' => '[{"property":"Blog.page_view","direction":"DESC"}]',
		'limit' => 3
	);
	$popular_blog = $this->Blog_model->get_array($param_blog);
	
	// recent blog
	$param_blog = array(
		'filter' => '[{"type":"numeric","comparison":"eq","value":"2","field":"BlogStatus.id"},{"type":"numeric","comparison":"eq","value":"'.$store['store_id'].'","field":"store_id"}]',
		'sort' => '[{"property":"Blog.create_date","direction":"DESC"}]',
		'limit' => 3
	);
	$recent_blog = $this->Blog_model->get_array($param_blog);
	
	// paging
	$blog_count = $this->Blog_model->get_count();
	$page_count = ceil($blog_count / $item_per_page);
	
	// best seller
	$array_best_seller = $this->Item_model->get_best_seller(array('store_id' => $store['store_id']));
?>

<?php $this->load->view( 'website/store/theme/calisto/common/meta' ); ?>
<body class="top">
	<?php $this->load->view( 'website/store/theme/calisto/common/feature' ); ?>
	
		<div class="main-body-wrapper">
			<?php $this->load->view( 'website/store/theme/calisto/common/header' ); ?>
			
			<div class="main-content-wrapper">
				<div class="main-left-wrapper">
					<div class="main-title"><p class="custom-font-1">Latest blog posts</p></div>
					
					<div class="blog-list">
						<?php foreach ($array_blog as $key => $blog) { ?>
							<div class="item">
								<div class="title custom-font-1">
									<a href="<?php echo $blog['blog_link']; ?>"><?php echo $blog['title']; ?></a>
								</div>
								<div class="title-legend">
									<a class="date"><?php echo GetFormatDate($blog['create_date'], array('FormatDate' => 'F d, Y')); ?></a>
									<!-- <a href="#" class="comments">9</a> -->
									<!-- <a href="#" class="share">Share this post</a> -->
								</div>
								<div class="text">
									<p><?php echo GetLengthChar($blog['content'], 600, ' ...'); ?></p>
									<p><a href="<?php echo $blog['blog_link']; ?>" class="more-link">Read more</a></p>
								</div>
							</div>
						<?php } ?>
					</div>

					<div class="pages custom-font-1">
						<div>
							<?php for ($i = -4; $i <= 4; $i++) { ?>
								<?php $page_counter = $page_active + $i; ?>
								<?php if ($page_counter > 0 && $page_counter <= $page_count) { ?>
									<?php $active = ($i == 0) ? 'active' : ''; ?>
									<a href="<?php echo $base_page.'/page_'.$page_counter; ?>" class="<?php echo $active; ?>"><span><?php echo $page_counter; ?></span></a>
								<?php } ?>
							<?php } ?>
						</div>
						<div>
							<?php if ($page_active < $page_count) { ?>
								<?php $page_next = $base_page.'/page_'.($page_active + 1); ?>
								<a href="<?php echo $page_next; ?>" class="next">Next</a>
							<?php } else { ?>
								<a class="next disabled">Next</a>
							<?php } ?>
							
							<?php if ($page_active > 1) { ?>
								<?php $page_prev = $base_page.'/page_'.($page_active - 1); ?>
								<a href="<?php echo $page_prev; ?>" class="previous">Previous</a>
							<?php } else { ?>
								<a class="previous disabled">Previous</a>
							<?php } ?>
						</div>
					</div>
				</div>
				
				<div class="main-sidebar-wrapper">
					<!--
					<div class="shop-by-category sidebar-item">
						<div class="main-title">
							<p class="custom-font-1">Shop by category</p>
						</div>
						<form action="#">
							<select>
								<option>select category</option>
								<option>Pull &amp; Bear</option>
								<option>Reserved</option>
								<option>United Colors of Benetton</option>
							</select>
						</form>
					</div>
					-->
					
					<div class="recent-activity sidebar-item">
						<div class="main-title"><p class="custom-font-1">Recent activity</p></div>
						
						<div class="button-navigation custom-font-1">
							<table><tr><td>
								<a href="#" class="active"><span>Popular</span></a>
								<a href="#"><span>Recent</span></a>
							</td></tr></table>
						</div>
						
						<div class="items"><div id="description_slider">
							<div class="item">
								<?php foreach ($recent_blog as $blog) { ?>
									<div class="text" style="padding: 0 0 25px 0;">
										<h3><a href="<?php echo $blog['blog_link']; ?>" class="custom-font-1"><?php echo $blog['title']; ?></a></h3>
										<div class="title-legend">
											<a class="date"><?php echo GetFormatDate($blog['create_date'], array('FormatDate' => 'F d, Y')); ?></a>
											<!-- <a href="#" class="comments">9</a> -->
											<!-- <a href="#" class="share">Share</a> -->
										</div>
										<a href="<?php echo $blog['blog_link']; ?>" class="more-link">Read more</a>
									</div>
									<div class="clear"></div>
								<?php } ?>
							</div>
							
							<div class="item">
								<?php foreach ($popular_blog as $blog) { ?>
									<div class="text" style="padding: 0 0 25px 0;">
										<h3><a href="<?php echo $blog['blog_link']; ?>" class="custom-font-1"><?php echo $blog['title']; ?></a></h3>
										<div class="title-legend">
											<a class="date"><?php echo GetFormatDate($blog['create_date'], array('FormatDate' => 'F d, Y')); ?></a>
											<!-- <a href="#" class="comments">9</a> -->
											<!-- <a href="#" class="share">Share</a> -->
										</div>
										<a href="<?php echo $blog['blog_link']; ?>" class="more-link">Read more</a>
									</div>
									<div class="clear"></div>
								<?php } ?>
							</div>
						</div></div>
					</div>
					
					<div class="sidebar-best-sellers sidebar-item">
						<div class="main-title"><p class="custom-font-1">Best sellers</p></div>
						
						<div class="items">
							<?php foreach ($array_best_seller as $key => $item) { ?>
								<div class="item">
									<?php if ($item['discount'] > 0) { ?>
										<div class="item-tag tag-off custom-font-1"><span>Discount</span></div>
									<?php } ?>
									<div class="image-wrapper-1">
										<div class="image">
											<div class="image-overlay-1 trans-1">
												<table><tr><td>
													<a href="<?php echo $item['item_link']; ?>" class="button-2 trans-1"></a>
												</td></tr></table>
											</div>
											<a href="#"><img src="<?php echo $item['thumbnail_link']; ?>" /></a>
										</div>
									</div>
									<div class="text">
										<h3><a href="<?php echo $item['item_link']; ?>" class="custom-font-1"><?php echo $item['title']; ?></a></h3>
										<p><b class="custom-font-1"><?php echo $item['price_label']; ?></b></p>
										<p class="more-link-wrapper"><a href="<?php echo $item['item_link']; ?>" class="more-link">Details</a></p>
									</div>
								</div>
							<?php } ?>
							<div class="clear"></div>
						</div>
					</div>
					
					<!--
					<div class="sidebar-twitter">
						<div class="main-title">
							<p class="custom-font-1">Twitter</p>
							<a href="#" class="follow">follow us</a>
						</div>

						<div class="items">

							<div class="item">
								<div class="tweet">
									<div>
										<a href="#">@Crasnon</a> sem in felis consequat curss vitae pla cerat erat. Ut purus ipsum, laoreet at iaculis non, iaculis.
									</div>
								</div>
								<div class="title-legend">
									<a href="#" class="date">8 hours ago</a>
									<a href="#" class="view">View tweet</a>
								</div>
							</div>

							<div class="item">
								<div class="tweet">
									<div>
										Quisque fringilla, enim volutpat commodo gravida, mi eros faucibus massa, sed pellentesque est nisl neque.
									</div>
								</div>
								<div class="title-legend">
									<a href="#" class="date">16 hours ago</a>
									<a href="#" class="view">View tweet</a>
								</div>
							</div>

							<div class="item">
								<div class="tweet">
									<div>
										Suspendisse ut cursus ligula. Nam quis tellus quis <a href="#">tortor suscipit</a> tincidunt.
									</div>
								</div>
								<div class="title-legend">
									<a href="#" class="date">2 days ago</a>
									<a href="#" class="view">View tweet</a>
								</div>
							</div>

						</div>
					</div>
					-->
				</div>
				<div class="clear"></div>
			</div>
			
			<?php $this->load->view( 'website/store/theme/calisto/common/footer' ); ?>
		</div>

	</body>
</html>