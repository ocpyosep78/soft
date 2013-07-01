<?php
	$this->User_model->login_user_required();
	$user = $this->User_model->get_session();
	
	// page data
	$page_item = 25;
	$page_active = get_page_active();
	$base_page = base_url('history');
	
	$param_item['user_id'] = $user['id'];
	$param_item['sort'] = '[{"property":"Item.name","direction":"ASC"}]';
	$param_item['start'] = ($page_active - 1) * $page_item;
	$param_item['limit'] = $page_item;
	$array_item = $this->User_Item_model->get_array($param_item);
	$page_count = ceil($this->User_Item_model->get_count() / $page_item);
?>

<?php $this->load->view( 'website/common/meta' ); ?>
<body>
<?php $this->load->view( 'website/common/header' ); ?>

<div class="home_wrapper">
	<div class="container-fluid home_main_content"><div class="row-fluid">
		<div class="span9"><div class="row-fluid">
			<div class="span12">
				<h2>Download Anda</h2>
				<?php if (count($array_item) > 0) { ?>
				<table class="table table-striped"><tbody>
					<?php foreach ($array_item as $item) { ?>
						<?php 
							$item['price_text'] = show_price($item['price']);
							$item['thumbnail_link'] = base_url('static/img/images.jpg');
							if (!empty($item['thumbnail'])) {
								$item['thumbnail_link'] = base_url('static/upload/'.$item['thumbnail']);
							}
						?>
						<tr>
							<td style="width: 80%;">
								<img src="<?php echo $item['thumbnail_link']; ?>" style="float: left; width: 80px; height: 55px; margin: 0 20px 0 0;" />
								<strong><a href="<?php echo $item['item_link']; ?>"><?php echo $item['item_name']; ?></a></strong><br />
								<?php echo $item['item_description']; ?><br />
								By
								<a href="<?php echo $item['author_link']; ?>"><?php echo $item['user_name']; ?></a> |
								<a href="<?php echo $item['category_link']; ?>"><?php echo $item['category_name']; ?></a>
							</td>
							<td style="width: 20%; text-align: center;">
								<a href="<?php echo $item['invoice_link']; ?>"><span class="label label-success">Download</span></a>
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
					Kamu belum membeli sama sekali, kami punya banyak aplikasi keren, silahkan cek <a href="<?php echo base_url('browse'); ?>">disini</a>
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