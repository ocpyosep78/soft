<?php
	// author
	preg_match('/author\/([a-z0-9]+)$/i', $_SERVER['REQUEST_URI'], $match);
	$user_name = (empty($match[1])) ? '' : $match[1];
	
	// page data
	$page_item = 25;
	$page_active = get_page_active();
	$base_page = base_url('author/'.$user_name);
	
	$param_item['keyword'] = @$_POST['keyword'];
	$param_item['user_name'] = $user_name;
	$param_item['platform_id'] = @$_POST['platform_id'];
	$param_item['category_id'] = @$_POST['category_id'];
	$param_item['item_status_id'] = ITEM_STATUS_APPROVE;
	$param_item['sort'] = '[{"property":"Item.date_update","direction":"DESC"}]';
	$param_item['start'] = ($page_active - 1) * $page_item;
	$param_item['limit'] = $page_item;
	$array_item = $this->Item_model->get_array($param_item);
	$page_count = ceil($this->Item_model->get_count() / $page_item);
?>

<?php $this->load->view( 'website/common/meta' ); ?>
<body>
<?php $this->load->view( 'website/common/header' ); ?>

<div class="home_wrapper">
	<div class="container-fluid home_main_content"><div class="row-fluid">
		<div class="span9"><div class="row-fluid">
			<div class="span12">
				<h2><a href="<?php echo base_url(); ?>">HOME</a> > AUTHOR - <?php echo $user_name; ?></h2>
				<?php if (count($array_item) > 0) { ?>
                    <table class="table table-striped item-table"><tbody>
                        <?php foreach ($array_item as $item) { ?>
							<tr>
								<td style="width: 10%">
									<a href="<?php echo $item['item_link']; ?>">
									<img src="<?php echo $item['thumbnail_link']; ?>" style="width:100%; height:auto;" />
									</a>
								</td>
								<td style="width: 70%;">
									<h2 class="item-title"><a href="<?php echo $item['item_link']; ?>"><?php echo $item['name']; ?></a></h2>
									<p class="item-desc"><?php echo nl2br(limit_words($item['description'], 20)); ?></p>
									
									<p class="meta">
										Oleh <a href="<?php echo $item['author_link']; ?>"><span class="label label-info"><?php echo $item['user_name']; ?></span></a>
										<a href="<?php echo $item['category_link']; ?>"><span class="label label-warning"><?php echo $item['category_name']; ?></span></a>
									</p>
								</td>
								<td style="width: 20%; text-align: center;">
									<div class="item-price"><?php echo $item['price_text']; ?></div>
									<a class="btn btn-success" href="<?php echo $item['item_link']; ?>">Beli</a>
								</td>
							</tr>
                        <?php } ?>
                    </tbody></table>
				
				<div class="pagination pull-right cnt-paging"><ul>
					<?php if ($page_active > 1) { ?>
					<?php $page_prev = $base_page.'/page_'.($page_active - 1); ?>
					<li><a href="<?php echo $page_prev; ?>">Prev</a></li>
					<?php } ?>
					
					<?php for ($i = -5; $i <= 5; $i++) { ?>
					<?php $page_counter = $page_active + $i; ?>
					<?php $page_class = ($i == 0) ? 'active' : ''; ?>
					<?php $page_link = $base_page.'/page_'.$page_counter; ?>
					<?php if ($page_counter >= 1 && $page_counter <= $page_count) { ?>
					<li class="<?php echo $page_class; ?>"><a href="<?php echo $page_link; ?>"><?php echo $page_counter; ?></a></li>
					<?php } ?>
					<?php } ?>
					
					<?php if ($page_active < $page_count) { ?>
					<?php $page_next = $base_page.'/page_'.($page_active + 1); ?>
					<li><a href="<?php echo $page_next; ?>">Next</a></li>
					<?php } ?>
				</ul></div>
				
				<?php } else { ?>
					Tidak ada item yang ditemukan.
				<?php } ?>
			</div>
		</div></div>
		
		<div class="span3">
			<?php $this->load->view( 'website/common/post_button' ); ?>
			<?php $this->load->view( 'website/common/search' ); ?>
			<?php $this->load->view( 'website/common/media' ); ?>
		</div>
	</div></div>
</div>

<?php $this->load->view( 'website/common/footer' ); ?>

</body>
</html>