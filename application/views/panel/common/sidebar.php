<?php
	$user = $this->User_model->get_session();
	$admin_user = $this->config->item('admin_user_id');
	
	preg_match('/panel\/([a-z0-9]+)\//', $_SERVER['REQUEST_URI'], $match);
	$group_name = (!empty($match[1])) ? $match[1] : 'product';
?>
<a href="javascript:void(0)" class="sidebar_switch on_switch ttip_r" title="Hide Sidebar">Sidebar switch</a>
<div class="sidebar"><div class="antiScroll"><div class="antiscroll-inner"><div class="antiscroll-content"><div class="sidebar_inner">
	<div id="side_accordion" class="accordion">
		<div class="accordion-group">
			<div class="accordion-heading">
				<a href="#sub-1" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
                <i class="icon-folder-close"></i> Product
				</a>
            </div>
			<div class="accordion-body collapse <?php echo ($group_name == 'product') ? 'in' : ''; ?>" id="sub-1">
				<div class="accordion-inner">
					<ul class="nav nav-list">
						<!--<li><a href="<?php echo site_url('panel/product/blog'); ?>">Blog</a></li>
                            <li><a href="<?php echo site_url('panel/product/catalog'); ?>">Catalog</a></li>
                        <li><a href="<?php echo site_url('panel/product/category'); ?>">Category</a></li>-->
						<li><a href="<?php echo site_url('panel/product/item'); ?>">Item</a></li>
                        <?php if (in_array($user['id'], $admin_user)) { ?>
                            <li><a href="<?php echo site_url('panel/product/item/item_pending'); ?>">Item Pending</a></li>
                            <li><a href="<?php echo site_url('panel/product/item/item_approve'); ?>">Item Approve</a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
		<div class="accordion-group">
			<div class="accordion-heading">
				<a href="#sub-2" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle"><i class="icon-th"></i> Order</a>
            </div>
			<div class="accordion-body collapse <?php echo ($group_name == 'order') ? 'in' : ''; ?>" id="sub-2">
				<div class="accordion-inner">
					<ul class="nav nav-list">
                        <?php if (in_array($user['id'], $admin_user)) { ?>
                            <li><a href="<?php echo site_url('panel/order/nota'); ?>">Nota</a></li>
                        <?php } ?>
                        <li><a href="<?php echo site_url('panel/order/transaction'); ?>">Transaction Summary</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!--
            <div class="accordion-group">
			<div class="accordion-heading">
            <a href="#sub-3" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle"><i class="icon-th"></i> Store</a>
            </div>
			<div class="accordion-body collapse <?php echo ($group_name == 'store') ? 'in' : ''; ?>" id="sub-3">
            <div class="accordion-inner">
            <ul class="nav nav-list">
            <li><a href="<?php echo site_url('panel/store/store'); ?>">Config</a></li>
            <!--	<li><a href="<?php echo site_url('panel/store/theme'); ?>">Theme</a></li>	
            <li><a href="<?php echo site_url('panel/store/store_image_slide'); ?>">Image Slide</a></li>
            <?php if (in_array($user['id'], $admin_user)) { ?>
                <li><a href="<?php echo site_url('panel/store/store_payment_method'); ?>">Payment</a></li>
                <li><a href="<?php echo site_url('panel/store/bank_account'); ?>">Bank Account</a></li>
            <?php } ?>
            </ul>
            </div>
            </div>
            </div>
        -->
		<?php if (in_array($user['id'], $admin_user)) { ?>
            <div class="accordion-group">
                <div class="accordion-heading">
                    <a href="#sub-4" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle"><i class="icon-th"></i> Account</a>
                </div>
                <div class="accordion-body collapse <?php echo ($group_name == 'account') ? 'in' : ''; ?>" id="sub-4">
                    <div class="accordion-inner">
                        <ul class="nav nav-list">
                            <li><a href="<?php echo site_url('panel/account/user'); ?>">User</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="accordion-group">
                <div class="accordion-heading">
                    <a href="#sub-5" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle"><i class="icon-th"></i> Master</a>
                </div>
                <div class="accordion-body collapse <?php echo ($group_name == 'master') ? 'in' : ''; ?>" id="sub-5">
                    <div class="accordion-inner">
                        <ul class="nav nav-list">
                            <li><a href="<?php echo site_url('panel/master/category'); ?>">Category</a></li>
                            <li><a href="<?php echo site_url('panel/master/platform'); ?>">Platform</a></li>
                            <li><a href="<?php echo site_url('panel/master/item_status'); ?>">Item Status</a></li>
                            <li><a href="<?php echo site_url('panel/master/pages'); ?>">Pages</a></li>
                            <!--
                                <li><a href="<?php echo site_url('panel/master/store'); ?>">Store</a></li>
                                <li><a href="<?php echo site_url('panel/master/blog_status'); ?>">Blog Status</a></li>
                                
                                <li><a href="<?php echo site_url('panel/master/currency'); ?>">Currency</a></li>
                                <li><a href="<?php echo site_url('panel/master/payment_method'); ?>">Payment Method</a></li>
                                <li><a href="<?php echo site_url('panel/master/bank'); ?>">Bank</a></li>
                                <li><a href="<?php echo site_url('panel/master/shipment'); ?>">Shipment</a></li>
                                <li><a href="<?php echo site_url('panel/master/default_value'); ?>">Default Value</a></li>
                            -->
                        </ul>
                    </div>
                </div>
            </div>
        <?php } ?>
		
    </div>
	<div class="push"></div>
</div></div></div></div></div>