<?php
	$blog_name = $this->Blog_model->get_url_blog_name();
	
	// blog
	$blog = $this->Blog_model->get_by_id(array('name' => $blog_name));
	
	// update blog
	$update_param = array( 'id' => $blog['id'], 'page_view' => $blog['page_view'] + 1 );
	$this->Blog_model->Update($update_param);
?>

<?php $this->load->view( 'website/store/theme/calisto/common/meta' ); ?>
<body class="top">
	<?php $this->load->view( 'website/store/theme/calisto/common/feature' ); ?>
	
		<div class="main-body-wrapper">
			<?php $this->load->view( 'website/store/theme/calisto/common/header' ); ?>
			
			<div class="main-content-wrapper">
				<div class="main-title">
					<p class="custom-font-1"><?php echo $blog['title']; ?></p>
				</div>
				
				<div class="single-full-width">
					<?php echo $blog['content']; ?>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>
			
			<?php $this->load->view( 'website/store/theme/calisto/common/footer' ); ?>
		</div>

	</body>
</html>