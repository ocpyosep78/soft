<?php
	// store
	$store_name = get_store();
	$store = $this->Store_Detail_model->get_info(array('store_name' => $store_name));
	
	// item
	$item_id = $this->Item_model->get_url_id();
	$item = $this->Item_model->get_by_id(array('id' => $item_id));
	
	// related item
	$param_item = array(
    'filter' => '['.
    '{"type":"numeric","comparison":"eq","value":"'.$store['store_id'].'","field":"store_id"},'.
    '{"type":"numeric","comparison":"eq","value":"'.$item['catalog_id'].'","field":"ItemCatalog.catalog_id"}'.
    ']',
    'sort' => '[{"property":"RAND()","direction":""}]',
    'limit' => 4
	);
	$related_item = $this->Item_model->get_array($param_item);
?>

<?php $this->load->view( 'website/store/theme/calisto/common/meta' ); ?>
<body class="top">
	<?php $this->load->view( 'website/store/theme/calisto/common/feature' ); ?>
	
    <div class="main-body-wrapper">
        <?php $this->load->view( 'website/store/theme/calisto/common/header' ); ?>
        
        <div class="main-content-wrapper" id="item-detail">
            <input type="hidden" name="item_id" value="<?php echo $item_id; ?>" />
            
            <div class="main-item-wrapper">
                <div class="main-title">
                    <p class="custom-font-1">
                        <a href="<?php echo site_url(); ?>">Home</a>
                        <span>/</span>
                        <a href="<?php echo site_url('catalog'); ?>">Catalog</a>
						<?php if (!empty($item['catalog_title'])) { ?>
							<span>/</span>
							<a href="<?php echo $item['catalog_link']; ?>"><?php echo $item['catalog_title']; ?></a>
						<?php } ?>
						<?php if (!empty($item['category_title'])) { ?>
							<span>/</span>
							<a href="<?php echo $item['category_link']; ?>"><?php echo $item['category_title']; ?></a>
						<?php } ?>
                        <span>/</span>
                        <a href="<?php echo $item['item_link']; ?>" class="active"><?php echo $item['title']; ?></a>
                    </p>
                    <a href="<?php echo $item['facebook_link']; ?>" class="share"><img src="<?php echo base_url('static/img/icon_fb.png'); ?>" /></a>
                    <a href="<?php echo $item['twitter_link']; ?>" class="share"><img src="<?php echo base_url('static/img/icon_twitter.png'); ?>" /></a>
                </div>
                <div class="message hide"></div>
                
                <div class="main-image">
                    <div class="image-wrapper-3">
                        <div id="single-product-slider">
                            <?php if (count($item['array_picture']) > 0) { ?>
                                <?php foreach ($item['array_picture'] as $picture) { ?>
                                    <div class="image">
                                        <a href="#"><img src="<?php echo $picture['picture_link']; ?>" width="470" /></a>
                                    </div>
                                <?php } ?>
								<?php } else { ?>
                                <div class="image">
                                    <a href="#"><img src="<?php echo base_url('static/img/images.jpg'); ?>" width="470" /></a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <table><tr>
                        <?php if (count($item['array_picture']) > 0) { ?>
                            <?php foreach ($item['array_picture'] as $key => $picture) { ?>
                                <?php $class_active = ($key == 0) ? 'active' : ''; ?>
                                <td>
                                    <div class="image-wrapper-4 <?php echo $class_active; ?>"><div class="image">
                                        <a href="#"><img src="<?php echo $picture['picture_link']; ?>" width="60" /></a>
                                    </div></div>
                                </td>
                            <?php } ?>
							<?php } else { ?>
                            <td>
                                <div class="image-wrapper-4 active"><div class="image">
                                    <a href="#"><img src="<?php echo base_url('static/img/images.jpg'); ?>" width="60" /></a>
                                </div></div>
                            </td>
                        <?php } ?>
                    </tr></table>
                </div>
                
                <div class="text">
                    <h2 class="custom-font-1"><a href="<?php echo $item['item_link']; ?>"><?php echo $item['title']; ?></a></h2>
                    <div class="price custom-font-1">
                        <div>
                            <p><?php echo $item['currency_name'].' '.$item['price_final']; ?></p>
                            <?php if ($item['discount'] > 0) { ?>
                                <p><s><?php echo $item['currency_name'].' '.$item['price']; ?></s></p>
                            <?php } ?>
                        </div>
                        <a class="item-buy cursor button-3 custom-font-1 trans-1"><span>Add to cart</span></a>
                        <div class="clear"></div>
                    </div>
                    <div class="options">
                        <form action="">
                            <!--
								<div class="item">
                                <label>Choose your size:</label>
                                <div class="select">
                                <select>
                                <option>Extra large (XL)</option>
                                <option>Large (L)</option>
                                <option>Extra small (XS)</option>
                                </select>
                                </div>
								</div>
								<div class="item">
                                <label>Choose your color:</label>
                                <div class="select">
                                <select>
                                <option>Blue with white stripes</option>
                                <option>Red with white stripes</option>
                                <option>White with black stripes</option>
                                </select>
                                </div>
								</div>
                            -->
                            <div class="item">
                                <label>Quantity:</label>
                                <div class="select">
                                    <input type="text" class="text" name="quantity" value="1" />
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div class="description">
                        <div class="button-navigation custom-font-1">
                            <table>
                                <tr>
                                    <td>
                                        <a href="#" class="active"><span>Description</span></a>
                                        <!-- <a href="#"><span>Specification table</span></a>	-->
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="items">
                            <div id="description_slider">
                                <div class="item">
                                    <p><?php echo $item['description']; ?></p>
                                    <br/>
                                    <br/>
                                    <p>
                                        <span class='st_facebook_vcount' displayText='Facebook'></span>
                                        <span class='st_twitter_vcount' displayText='Tweet'></span>
                                        <span class='st_linkedin_vcount' displayText='LinkedIn'></span>
                                        <span class='st_pinterest_vcount' displayText='Pinterest'></span>
                                        <span class='st_googleplus_vcount' displayText='Google +'></span>
                                        <span class='st_fblike_vcount' displayText='Facebook Like'></span>
                                        <span class='st_email_vcount' displayText='Email'></span>
                                    </p>
                                </div>
                                <!--
									<div class="item">
                                    <table>
                                    <tr>
                                    <td>Option 1</td>
                                    <td>Option 2</td>
                                    <td>Option 3</td>
                                    <td>Option 4</td>
                                    <td>Option 5</td>
                                    </tr>
                                    <tr>
                                    <td>Entry 1</td>
                                    <td>Entry 2</td>
                                    <td>Entry 3</td>
                                    <td>Entry 4</td>
                                    <td>Entry 5</td>
                                    </tr>
                                    <tr>
                                    <td>Row 1</td>
                                    <td>Row 2</td>
                                    <td>Row 3</td>
                                    <td>Row 4</td>
                                    <td>Row 5</td>
                                    </tr>
                                    </table>
									</div>
                                -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            
            <div class="featured-items related-items">
                <div class="main-title"><p class="custom-font-1">Related items</p></div>
                <div class="items-wrapper">
                    <div class="items">
                        <?php foreach ($related_item as $array) { ?>
                            <?php $description = $array['description']; ?>
                            <?php unset($array['description']); ?>
                            
                            <div class="item-block-1">
                                <?php if ($array['discount'] > 0) { ?>
                                    <div class="item-tag tag-off custom-font-1"><span>Discount</span></div>
                                <?php } ?>
                                <div class="image-wrapper-3">
                                    <div class="image">
                                        <div class="image-overlay-1 trans-1">
                                            <table><tr><td>
                                                <a class="button-1 custom-font-1 trans-1 cursor"><span>Quick shop</span></a>
                                                <div class="hide raw"><?php echo json_encode($array); ?></div>
                                                <div class="hide description"><?php echo $description; ?></div>
                                            </td></tr></table>
                                        </div>
                                        <a class="image-relared"><img src="<?php echo $array['thumbnail_link']; ?>" /></a>
                                    </div>
                                </div>
                                <h3><a href="<?php echo $array['item_link']; ?>" class="custom-font-1"><?php echo $array['title']; ?></a></h3>
                                <p><b class="custom-font-1"><?php echo $array['price_label']; ?></b></p>
                            </div>
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
        $('.item-buy').click(function() {
            var param = {
                item_id: $('#item-detail input[name="item_id"]').val(),
                quantity: $('#item-detail input[name="quantity"]').val(),
                callback: function(result) {
                    $('#item-detail .message').text(result.message);
                    $('#item-detail .message').slideDown(500);
                }
            }
            soulage.updateCart(param);
        });
    </script>
    
</body>
</html>