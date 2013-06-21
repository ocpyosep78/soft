<?php
	$store_name = get_store();
	$store = $this->Store_Detail_model->get_info(array('store_name' => $store_name));
	
	// image slide
	$param_image_slide = array(
    'filter' => '[{"type":"numeric","comparison":"eq","value":"'.$store['store_id'].'","field":"store_id"},{"type":"numeric","comparison":"eq","value":"1","field":"active"}]',
    'sort' => '[{"property":"StoreImageSlide.title","direction":"ASC"}]'
	);
	$array_image_slide = $this->Store_Image_Slide_model->get_array($param_image_slide);
	
	// lastest item
	$param_item = array(
    'filter' => '[{"type":"numeric","comparison":"eq","value":"'.$store['store_id'].'","field":"store_id"}]',
    'sort' => '[{"property":"Item.update_date","direction":"DESC"}]',
    'limit' => 12
	);
	$array_item = $this->Item_model->get_array($param_item);
	
	// blog list
	$param_blog = array(
    'filter' => '[{"type":"numeric","comparison":"eq","value":"2","field":"BlogStatus.id"},{"type":"numeric","comparison":"eq","value":"'.$store['store_id'].'","field":"store_id"}]',
    'sort' => '[{"property":"Blog.create_date","direction":"DESC"}]',
    'limit' => 4
	);
	$array_blog = $this->Blog_model->get_array($param_blog);
	
	// best seller
	$array_best_seller = $this->Item_model->get_best_seller(array('store_id' => $store['store_id'], 'limit' => 3));
?>

<?php $this->load->view( 'website/store/theme/calisto/common/meta' ); ?>
<body class="top">
	<?php $this->load->view( 'website/store/theme/calisto/common/feature' ); ?>
	
	<div class="main-body-wrapper">
		<?php $this->load->view( 'website/store/theme/calisto/common/header' ); ?>
		
		<div class="main-content-wrapper">
			<div class="homepage-slider">
				<div id="hompage-slider_content">
					<?php foreach ($array_image_slide as $image) { ?>
						<div class="item">
							<div class="title">
								<h3 class="custom-font-1"><?php echo $image['title']; ?></h3>
								<p><?php echo $image['content']; ?></p>
                            </div>
							<a><img src="<?php echo $image['image_link']; ?>" alt="" width="944" /></a>
                        </div>
                    <?php } ?>
                </div>
				<div class="navigation custom-font-1">
					<table><tr><td>
						<a href="#" class="previous">Previous</a>
						<span id="pager">
							<?php foreach ($array_image_slide as $image) { ?>
								<a href="#" class="bullet"></a>
                            <?php } ?>
                        </span>
						<a href="#" class="next">Next</a>
                    </td></tr></table>
                </div>
            </div>
			
			<!--
                <div class="message-welcome">
				<h3 class="custom-font-1">Welcome to Soulage - responisive Shopify theme</h3>
				<p><b>Congratulations on starting your own e-commerce store!</b></p>
				<p>Soulage is an elegant &amp; responsive Shopify theme which dynamically adjusts for all screen sizes and devices.</p>
				<p>Your shop will look great and will be easy to use on desktops, laptops, tablets and mobile smartphones. <a href="#">Learn more about Soulage</a></p>
                </div>
            -->
			
			<div class="featured-items">
				<div class="main-title">
					<p class="custom-font-1">Lastest items</p>
					<!-- <a href="#" class="view">view more featured items</a> -->
                </div>
				<div class="items-wrapper">
					<div class="items">
						<?php foreach ($array_item as $item) { ?>
							<?php $description = $item['description']; ?>
							<?php unset($item['description']); ?>
							
							<div class="item-block-1">
								<?php if ($item['discount'] > 0) { ?>
									<div class="item-tag tag-off custom-font-1"><span>Discount</span></div>
                                <?php } ?>
								<div class="image-wrapper-3">
									<div class="image">
										<div class="image-overlay-1 trans-1">
											<table>
                                                <tr>
                                                    <td>
                                                        <a class="cursor button-1 custom-font-1 trans-1"><span>Quick shop</span></a>
                                                        <div class="hide raw"><?php echo json_encode($item); ?></div>
                                                        <div class="hide description"><?php echo $description; ?></div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
										<a href="#"><img class="thumb" src="<?php echo $item['thumbnail_link']; ?>" /></a>
                                    </div>
                                </div>
								<h3><a href="<?php echo $item['item_link']; ?>" class="custom-font-1"><?php echo $item['title']; ?></a></h3>
								<p><b class="custom-font-1"><?php echo $item['price_label']; ?></b></p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
				<div class="clear"></div>
            </div>
			
			<div class="homepage-about">
				<div class="main-title"><p class="custom-font-1"><?php echo $store['about_us']['title']; ?></p></div>
				<p class="caps"><?php echo $store['about_us']['content']; ?></p>
            </div>
			
			<div class="homepage-latest-news">
				<div class="main-title">
					<p class="custom-font-1">Latest news</p>
					<a href="<?php echo site_url('blog'); ?>" class="view">view all blog posts</a>
                </div>
                
				<div class="items">
					<?php foreach ($array_blog as $blog) { ?>
						<div class="item">
							<div class="text">
								<h3><a href="<?php echo $blog['blog_link']; ?>" class="custom-font-1"><?php echo $blog['title']; ?></a></h3>
								<div class="title-legend">
									<a class="date"><?php echo GetFormatDate($blog['create_date'], array('FormatDate' => 'F d, Y')); ?></a>
									<!-- <a href="#" class="comments">9</a> -->
                                </div>
								<p><?php echo GetLengthChar($blog['content'], 300, ' <a href="'.$blog['blog_link'].'" class="more-link">read more</a>'); ?></p>
                            </div>
                        </div>
                    <?php } ?>
					<!--
                        <div class="item">
						<div class="text">
                        <h3><a href="#" class="custom-font-1">Morbi scelerisque iaculis sodales</a></h3>
                        <div class="title-legend">
                        <a href="#" class="date">May 23, 2012</a>
                        <a href="#" class="comments">0</a>
                        </div>
                        <p>Aliquam ligula odio, eleifend at condimentum in, condimentum vitae augue. Morbi eget mattis lectus. Donec et felis ac purus vehicula tempor in vitae dui. Ut id elit in tortor malesuada interdum. Integer pulvinar. <a href="#" class="more-link">Read more</a></p>
						</div>
                        </div>
                    -->
                </div>
            </div>
			
			<div class="homepage-best-sellers">
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
								<p class="nr custom-font-1"><?php echo ($key + 1); ?>.</p>
								<p><b class="custom-font-1"><?php echo $item['price_label']; ?></b></p>
								<p class="more-link-wrapper"><a href="<?php echo $item['item_link']; ?>" class="more-link">Details</a></p>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
			<div class="clear"></div>
        </div>
		
		<?php $this->load->view( 'website/store/theme/calisto/common/footer' ); ?>
    </div>
</body>
</html>