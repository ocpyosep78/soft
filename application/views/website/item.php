<?php
	preg_match('/([\d]+)/i', $_SERVER['REQUEST_URI'], $match);
	$item_id = (isset($match[1])) ? $match[1] : 0;
	$item = $this->Item_model->get_by_id(array( 'id' => $item_id ));
?>

<?php $this->load->view( 'website/common/meta' ); ?>
<body>
<?php $this->load->view( 'website/common/header' ); ?>

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
				
				<?php echo $item['description']; ?>
			</div>	
		</div>
	</div>
	
	<div class="span4 sidebar"><br /><br />
		<div style="text-align: center; padding: 0 0 20px 0;">
			<a href="<?php echo $item['item_buy_link']; ?>" class="btn btn-primary btn-success">Download</a>
		</div>
		
		<div class="row-fluid form-tooltip">	
			<div class="span12">
				<h4>Detail</h4>
				<div>Platform : <?php echo $item['platform_name']; ?></div>
				<div>Category : <?php echo $item['category_name']; ?></div>
				<div>Owner : <?php echo $item['user_name']; ?></div>
			</div>	
		</div>
	</div>		
</div></div>

<?php $this->load->view( 'website/common/footer' ); ?>

<script>
</script>

</body>
</html>