<?php
	// search & catalog page
	$is_search = false;
	$is_catalog = false;
	$request_uri = preg_replace('/page_[\d]+$/i', '', $_SERVER['REQUEST_URI']);
	preg_match('/\/(search|catalog)\/?([a-z0-9\_]+)?/i', $request_uri, $match);
	if (isset($match[1]) && !empty($match[1])) {
		$match[2] = (empty($match[2])) ? '' : $match[2];
		
		if ($match[1] == 'catalog' && empty($match[2])) {
			$is_catalog = true;
		} else if ($match[1] == 'search') {
			$is_search = true;
		}
	}
	
	// store
	$store_name = get_store();
	$store = $this->Store_Detail_model->get_info(array('store_name' => $store_name));
	
	if ($is_search) {
		$search_name = (!empty($match[1])) ? $match[1] : '';
		$search_title = preg_replace('/_/i', ' ', $search_name);
		
		// generate base page
		$item_per_page = 12;
		$page_active = get_page_active();
		$base_page = site_url('search/'.$search_name);
		
		$param_item = array(
			'store_id' => $store['store_id'],
			'filter' => '[{"type":"custom","field":"Item.name LIKE '."'%$search_title%'".'","value":""}]',
			'start' => ($page_active * $item_per_page) - $item_per_page,
			'limit' => $item_per_page
		);
		$array_item = $this->Item_model->get_array($param_item);
		
		// paging
		$item_count = $this->Item_model->get_count();
		$page_count = ceil($item_count / $item_per_page);
	}
	else if ($is_catalog) {
		// generate base page
		$item_per_page = 9;
		$page_active = get_page_active();
		$base_page = site_url('catalog');
		
		$param_catalog = array(
			'store_id' => $store['store_id'],
			'filter' => '[{"type":"numeric","comparison":"eq","value":"'.$store['store_id'].'","field":"store_id"}]',
			'start' => ($page_active * $item_per_page) - $item_per_page,
			'limit' => $item_per_page
		);
		$array_catalog = $this->Catalog_model->get_array($param_catalog);
		
		// paging
		$item_count = $this->Catalog_model->get_count();
		$page_count = ceil($item_count / $item_per_page);
	}
	else {
		$catalog_name = $this->Catalog_model->get_url_catalog();
		$category_name = $this->Catalog_model->get_url_category();
		$catalog = $this->Catalog_model->get_by_id(array('name' => $catalog_name, 'store_id' => $store['store_id']));
		$category = $this->Category_model->get_by_id(array('name' => $category_name, 'store_id' => $store['store_id']));
		
		// generate base page
		$item_per_page = 12;
		$page_active = get_page_active();
		$base_page = site_url('catalog/'.$catalog_name);
		if (!empty($category_name)) {
			$base_page .= '/'.$category_name;
		}
		
		$array_category = $this->Category_model->get_array_category(array('catelog_name' => $catalog_name, 'store_id' => $store['store_id']));
		
		$param_item = array(
			'store_id' => $store['store_id'],
			'filter' => '['.
				'{"type":"numeric","comparison":"eq","value":"'.$catalog['id'].'","field":"ItemCatalog.catalog_id"},'.
				'{"type":"numeric","comparison":"eq_can_empty","value":"'.@$category['id'].'","field":"ItemCategory.category_id"}'.
			']',
			'start' => ($page_active * $item_per_page) - $item_per_page,
			'limit' => $item_per_page
		);
		$array_item = $this->Item_model->get_array($param_item);
		
		// paging
		$item_count = $this->Item_model->get_count();
		$page_count = ceil($item_count / $item_per_page);
	}
?>

<?php $this->load->view( 'website/store/theme/calisto/common/meta' ); ?>
<body class="top">
	<?php $this->load->view( 'website/store/theme/calisto/common/feature' ); ?>
	
		<div class="main-body-wrapper">
			<?php $this->load->view( 'website/store/theme/calisto/common/header' ); ?>
			
			<div class="main-content-wrapper">
				<div class="catalog">
					<?php if ($is_search) { ?>
						<div class="main-title">
							<p class="custom-font-1">
								Search : <?php echo (empty($search_title)) ? ' - ' : $search_title; ?>
							</p>
						</div>
					<?php } else if ($is_catalog) { ?>
						<div class="main-title"><p class="custom-font-1">List Catalog</p></div>
					<?php } else { ?>
						<div class="main-title">
							<p class="custom-font-1">
								All products
								<?php if (count($catalog) > 0) { ?>
									<?php echo ' - '.$catalog['title']; ?>
								<?php } ?>
								<?php if (count($category) > 0) { ?>
									<?php echo ' - '.$category['title']; ?>
								<?php } ?>
							</p>
							<!--
							<a href="#" class="grid-2">4 column view</a>
							<a href="#" class="grid-1">3 column view</a>
							-->
						</div>
					<?php } ?>

					<form action="#" class="navigation">
						<?php if (! $is_search && ! $is_catalog) { ?>
							<label>Browse by category:</label>
							<div class="category">
								<select name="category">
									<?php echo ShowOption(array('Array' => $array_category, 'ArrayID' => 'category_link', 'ArrayTitle' => 'title')); ?>
								</select>
							</div>
						<?php } ?>
						<label class="total"><?php echo $item_count; ?> items total</label>
					</form>
					
					<div class="items-wrapper">
						<div class="items">
							<?php if ($is_catalog) { ?>
								<?php foreach ($array_catalog as $item) { ?>
									<div class="item-block-2">
										<div class="image-wrapper-3">
											<div class="image">
												<a href="<?php echo $item['catalog_link']; ?>"><img class="thumb image-block-2" src="<?php echo $item['image_link']; ?>" /></a>
											</div>
										</div>
										<h3><a href="<?php echo $item['catalog_link']; ?>" class="custom-font-1"><?php echo $item['title']; ?></a></h3>
									</div>
								<?php } ?>
							<?php } else { ?>
								<?php foreach ($array_item as $item) { ?>
									<?php $description = $item['description']; ?>
									<?php unset($item['description']); ?>
									
									<div class="item-block-2">
										<?php if ($item['discount'] > 0) { ?>
											<div class="item-tag tag-off custom-font-1"><span>Discount</span></div>
										<?php } ?>
										<div class="image-wrapper-3">
											<div class="image">
												<div class="image-overlay-1 trans-1">
													<table><tr><td>
														<a href="#" class="button-1 custom-font-1 trans-1"><span>Quick shop</span></a>
														<div class="hide raw"><?php echo json_encode($item); ?></div>
														<div class="hide description"><?php echo $description; ?></div>
													</td></tr></table>
												</div>
												<a href="#"><img class="thumb image-block-2" src="<?php echo $item['thumbnail_link']; ?>" /></a>
											</div>
										</div>
										<h3><a href="<?php echo $item['item_link']; ?>" class="custom-font-1"><?php echo $item['title']; ?></a></h3>
										<p><b class="custom-font-1"><?php echo $item['price_label']; ?></b></p>
									</div>
								<?php } ?>
							<?php } ?>
						</div>
					</div>
					<div class="clear"></div>
					
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
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>
			
			<?php $this->load->view( 'website/store/theme/calisto/common/footer' ); ?>
		</div>
		<script type="text/javascript">
			$('.category select[name="category"]').change(function() {
				window.location = $(this).val();
			});
		</script>
	
	</body>
</html>