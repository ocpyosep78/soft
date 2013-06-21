<?php
	// store
	$store_name = get_store();
	$store = $this->Store_Detail_model->get_info(array('store_name' => $store_name));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php echo $store['store_title'].' - '.$store['store_logo']['content']; ?></title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=100%; initial-scale=1; minimum-scale=1;" />
        <link rel="shortcut icon" href="<?php echo base_url(); ?>static/theme/calisto/img/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>static/theme/calisto/font/RopaSans.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>static/theme/calisto/css/jquery.autocomplete.css" type="text/css" />
        
        <?php if (!empty($is_checkout)) { ?>
            <link rel="stylesheet" href="<?php echo base_url(); ?>static/theme/calisto/css/checkout.css" type="text/css" />
            <!--[if lt IE 9]><link rel="stylesheet" href="<?php echo base_url(); ?>static/theme/calisto/css/ie-checkout.css" type="text/css" type="text/css" /><![endif]-->
            <?php } else { ?>
            <link rel="stylesheet" href="<?php echo base_url(); ?>static/theme/calisto/css/main-stylesheet.css" type="text/css" />
            <!--[if lt IE 9]><link rel="stylesheet" href="<?php echo base_url(); ?>static/theme/calisto/css/ie.css" type="text/css" type="text/css" /><![endif]-->
            <!--[if lt IE 9]><link rel="stylesheet" href="<?php echo base_url(); ?>static/theme/calisto/css/ie-dark.css" type="text/css" type="text/css" /><![endif]-->
        <?php } ?>
        
        <script type="text/javascript">var web = { host: '<?php echo site_url(); ?>' };</script>
        <script src="<?php echo base_url(); ?>static/theme/calisto/js/jquery.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>static/theme/calisto/js/jquery.placeholder.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>static/theme/calisto/js/jquery.uniform.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>static/theme/calisto/js/global.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>static/theme/calisto/js/jquery.cycle.all.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>static/theme/calisto/js/jquery.autocomplete.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>static/js/panel/common.js" type="text/javascript"></script>
        
		<?php if ($this->config->item('share_media') == 'on') { ?>
        <!--- share social media --->
        <script type="text/javascript">var switchTo5x=true;</script>
        <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
        <script type="text/javascript">stLight.options({publisher: "197a8ec9-397b-44bc-82fe-213368f14997", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
        <!--- end share social media --->
		<?php } ?>
    </head>    