<?php
	// page data
	$page_item = 25;
	$page_active = get_page_active();
	
	// category
	preg_match('/category\/(\d+)$/i', $_SERVER['REQUEST_URI'], $match);
	$category_id = (isset($match[1])) ? $match[1] : @$_POST['category_id'];
	
	$param_item['keyword'] = @$_POST['keyword'];
	$param_item['platform_id'] = @$_POST['platform_id'];
	$param_item['category_id'] = $category_id;
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

<div class="hide">
	<form id="form-search-hidden" method="post">
		<input type="hidden" name="keyword" value="<?php echo @$_POST['keyword']; ?>" />
		<input type="hidden" name="platform_id" value="<?php echo @$_POST['platform_id']; ?>" />
		<input type="hidden" name="category_id" value="<?php echo @$category_id; ?>" />
		<input type="hidden" name="page_no" value="1" />
	</form>
</div>

<div class="home_wrapper">
	<div class="container-fluid home_main_content"><div class="row-fluid">
		<div class="span9"><div class="row-fluid">
			<div class="span12">
				<h2>APPS TERBARU</h2>
				<?php if (count($array_item) > 0) { ?>
				<table class="table table-striped"><tbody>
					<?php foreach ($array_item as $item) { ?>
						<tr>
							<td style="width: 80%;">
								<img src="<?php echo $item['thumbnail_link']; ?>" style="float: left; width: 80px; height: 55px; margin: 0 20px 0 0;" />
								<strong><a href="<?php echo $item['item_link']; ?>"><?php echo $item['name']; ?></a></strong><br />
								<?php echo nl2br($item['description']); ?><br />
								Oleh
								<a href="<?php echo $item['author_link']; ?>"><?php echo $item['user_name']; ?></a> |
								<a href="<?php echo $item['category_link']; ?>"><?php echo $item['category_name']; ?></a></td>
							<td style="width: 20%; text-align: center;">
								<b><?php echo $item['price_text']; ?></b><br>
								<a class="btn btn-success" href="<?php echo $item['item_buy_link']; ?>">Beli</a>
							</td>
						</tr>
					<?php } ?>
				</tbody></table>
				
				<div class="pagination pull-right cnt-paging"><ul>
					<?php if ($page_active > 1) { ?>
					<?php $page_prev = $page_active - 1; ?>
					<li><a class="cursor" data-page_no="<?php echo $page_prev; ?>">Sebelumnya</a></li>
					<?php } ?>
					
					<?php for ($i = -5; $i <= 5; $i++) { ?>
					<?php $page_counter = $page_active + $i; ?>
					<?php $page_class = ($i == 0) ? 'active' : ''; ?>
					<?php if ($page_counter >= 1 && $page_counter <= $page_count) { ?>
					<li class="<?php echo $page_class; ?>"><a class="cursor" data-page_no="<?php echo $page_counter; ?>"><?php echo $page_counter; ?></a></li>
					<?php } ?>
					<?php } ?>
					
					<?php if ($page_active < $page_count) { ?>
					<?php $page_next = $page_active + 1; ?>
					<li><a class="cursor" data-page_no="<?php echo $page_next; ?>">Selanjutnya</a></li>
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

<script>
$(document).ready(function() {
	$('.cnt-paging li a').click(function() {
		$('#form-search-hidden [name="page_no"]').val($(this).data('page_no'));
		$('#form-search-hidden').submit();
	});
});
</script>

</body>
</html>