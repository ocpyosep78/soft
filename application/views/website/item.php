<?php
	preg_match('/([\d]+)/i', $_SERVER['REQUEST_URI'], $match);
	$item_id = (isset($match[1])) ? $match[1] : 0;
	
	$is_buy = false;
	$is_login = $this->User_model->is_login();
	$item = $this->Item_model->get_by_id(array( 'id' => $item_id ));
	
	if ($is_login) {
		$user = $this->User_model->get_session();
		$is_buy = $this->User_Item_model->is_buy(array( 'user_id' => $user['id'], 'item_id' => $item['id'] ));
	}
?>

<?php $this->load->view( 'website/common/meta' ); ?>
<body>
<?php $this->load->view( 'website/common/header' ); ?>

<div class="hide">
	<input type="hidden" name="item_id" value="<?php echo $item['id']; ?>" />
</div>

<div class="container-fluid sidebar_content"><div class="row-fluid">
	<div class="span8">	
		<br />
		<h2><i class="icon-suitcase"></i>&nbsp;&nbsp;<?php echo $item['name']; ?></h2>
		
		<div class="row-fluid form-tooltip">	
			<div class="span12">
				<?php if (!empty($item['thumbnail'])) { ?>
					<img src="<?php echo $item['thumbnail_link']; ?>" style="width: 50%; padding: 0 0 10px 0;"/>
					<div class="clear"></div>
				<?php } ?>
				
				<?php echo nl2br($item['description']); ?>
			</div>	
		</div>
	</div>
	
	<div class="span4 sidebar"><br /><br />
		<div style="text-align: center; padding: 0 0 20px 0;">
			<?php if ($is_buy) { ?>
			<a class="cursor btn btn-primary btn-success btn-download">Download</a>
			<?php } else if ($item['item_status_id'] == ITEM_STATUS_APPROVE) { ?>
			<a href="<?php echo $item['item_buy_link']; ?>" class="btn btn-primary btn-success">Beli</a>
			<?php } else { ?>
			<a class="btn btn-primary btn-success">Menunggu Persetujuan</a>
			<?php } ?>
		</div>
		
		<div class="row-fluid form-tooltip">	
			<div class="span12">
				<h4>Detail</h4>
				<div>Platform : <?php echo $item['platform_name']; ?></div>
				<div>Category : <?php echo $item['category_name']; ?></div>
				<div>Owner : <a href="<?php echo $item['author_link']; ?>"><?php echo $item['user_name']; ?></a></div>
			</div>	
		</div>
	</div>		
</div></div>

<?php $this->load->view( 'website/common/footer' ); ?>

<script>
$(document).ready(function() {
	$('.btn-download').click(function() {
		Func.force_download({ item_id: $('[name="item_id"]').val() });
	});
});
</script>

</body>
</html>