<?php
	// page data
	$page_item = 25;
	$page_active = get_page_active();
	
	$param_item['keyword'] = @$_POST['keyword'];
	$param_item['platform_id'] = @$_POST['platform_id'];
	$param_item['category_id'] = @$_POST['category_id'];
	$param_item['item_status_id'] = ITEM_STATUS_APPROVE;
	$param_item['sort'] = '[{"property":"Item.date_update","direction":"DESC"}]';
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
				<h2>latest jobs</h2>
				<?php if (count($array_item) > 0) { ?>
				<table class="table table-striped"><tbody>
					<?php foreach ($array_item as $item) { ?>
						<tr>
							<td style="width: 90%;">
								<strong><a href="<?php echo $item['item_link']; ?>"><?php echo $item['name']; ?></a></strong><br />
								<?php echo $item['description']; ?><br />
								By <a><?php echo $item['user_name']; ?></a> | <?php echo $item['category_name']; ?> | <?php echo $item['price_text']; ?></td>
							<td style="width: 10%; text-align: center;"><a href="<?php echo $item['item_buy_link']; ?>"><span class="label label-success">Buy</span></a></td></tr>
					<?php } ?>
				</tbody></table>
				
				<div class="pagination pull-right cnt-paging"><ul>
					<?php if ($page_active > 1) { ?>
					<?php $page_prev = $page_active - 1; ?>
					<li><a class="cursor" data-page_no="<?php echo $page_prev; ?>">Prev</a></li>
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
					<li><a class="cursor" data-page_no="<?php echo $page_next; ?>">Next</a></li>
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
		$('#form-search-main [name="page_no"]').val($(this).data('page_no'));
		$('#form-search-main').submit();
	});
});
</script>

</body>
</html>