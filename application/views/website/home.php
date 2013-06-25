<?php
	$array_platform = $this->Platform_model->get_array();
	
	$param_item['item_status_id'] = ITEM_STATUS_APPROVE;
	$param_item['sort'] = '[{"property":"Item.date_update","direction":"DESC"}]';
	$array_item = $this->Item_model->get_array($param_item);
?>

<?php $this->load->view( 'website/common/meta' ); ?>
<body>
<?php $this->load->view( 'website/common/header' ); ?>
	
<div class="home_wrapper">
	<div class="container-fluid home_content"><div class="row-fluid hero_bar"><div class="span12">
		<div class="row-fluid"><br />
			<div class="span9 offset1 home-hero"><div class="row-fluid"><div class="span11 offset1">
				<div class="row-fluid">
					<div class="span5"><h3>Saya mencari...</h3></div>				
					<div class="span5"><h3>Platform</h3></div>
				</div>
				
				<div class="row-fluid"><form id="form-search-short" action="<?php echo base_url('browse'); ?>" method="post">
					<div class="span5"><input type="text" class="span12 search_input" name="keyword" placeholder="Apa jenis perangkat lunak yang Anda cari?" /></div>
					<div class="span4"><select class="home_select" name="platform_id">
						<?php echo ShowOption(array( 'Array' => $array_platform, 'ArrayID' => 'id', 'ArrayTitle' => 'name', 'Selected' => 1 )); ?>
					</select></div>
					<div class="span2"><a class="cursor btn btn-primary btn-success search_btn btn-search-short">Cari ...</a></div>
				</form></div>
			</div></div></div>
		</div>
	</div></div></div>
	
	<div class="container-fluid home_main_content"><div class="row-fluid">
		<div class="span9"><div class="row-fluid">
			<div class="span12">
				<h2>Apps terbaru</h2>
				<table class="table table-striped"><tbody>
					<?php foreach ($array_item as $item) { ?>
						<tr>
							<td style="width: 90%;">
								<img src="<?php echo $item['thumbnail_link']; ?>" style="float: left; width: 80px; height: 55px; margin: 0 20px 0 0;" />
								<strong><a href="<?php echo $item['item_link']; ?>"><?php echo $item['name']; ?></a></strong><br />
								<?php echo $item['description']; ?><br />
								Oleh <a href="<?php echo $item['author_link']; ?>"><?php echo $item['user_name']; ?></a> | <?php echo $item['category_name']; ?> | <?php echo $item['price_text']; ?></td>
							<td style="width: 10%; text-align: center;">
								<a href="<?php echo $item['item_buy_link']; ?>"><span class="label label-success">Beli</span></a>
							</td></tr>
					<?php } ?>
				</tbody></table>
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
	$('.btn-search-short').click(function() {
		$('#form-search-short').submit();
	});
});
</script>

</body>
</html>