<?php
    $isParam = $this->uri->segment(1);
    $isParamCategory = $this->uri->segment(2);
    $isParamItem = $this->uri->segment(1);
    if(!empty($isParam) && $isParam == 'author')
    {
        $titlePages = urldecode($this->uri->segment(2));
        $titlePages .= "Download Aplikasi Milik ".$titlePages." | LintasApps.com ";
        $descriptionPages = "Anda memilih aplikasi dengan author ". $titlePages;
        
    }else if(!empty($isParamCategory) && $isParamCategory == 'category')
    {
        $categoryId =  mysql_real_escape_string(urldecode($this->uri->segment(3)));
        //get category 
        $categoryData = $this->Category_model->get_by_id(array( 'id' => $categoryId ));
        if(!empty($categoryData))
        {
            $titlePages   = "Download Aplikasi Dengan Category ". $categoryData['name'] ." | LintasApps.com ";
            $descriptionPages = "Anda memilih aplikasi dengan category ". $categoryData['name'];
        }else
        {
            $titlePages = "Portal Download Aplikasi,Foto,Video | LintasApps.com";
            $descriptionPages = "Portal Download Aplikasi,Foto,Video | LintasApps.com";
        }    
    }else if(!empty($isParamItem) && $isParamItem == 'item' && $this->uri->segment(2)!='buy')
    {
        $itemId = mysql_real_escape_string(urldecode($this->uri->segment(2)));
        //get item detail
        $itemData  = $this->Item_model->get_by_id(array( 'id' => $itemId ));
        if(!empty($itemData))
        {
            $titlePages   = "Download Aplikasi ". $itemData['name'] ." dengan harga ". rupiah($itemData['price']) ."| LintasApps.com ";
            $descriptionPages = nl2br(limit_words($itemData['description'], 20));
        }else
        {
            $titlePages = "Portal Download Aplikasi,Foto,Video | LintasApps.com";
            $descriptionPages = "Portal Download Aplikasi,Foto,Video | LintasApps.com";
        }
    }else if(!empty($isParamItem) && $isParamItem == 'post')
    {
        $titlePages   = "Upload Aplikasi Milik Anda di | LintasApps.com ";
        $descriptionPages = "Dapatkan tambahan penghasilan dari aplikasi yang Anda buat, kami yang akan mengurus penjualannya";
    }else if(!empty($isParamItem) && $isParamItem == 'login')
    {
        $titlePages   = "Mendaftar Atau Login di | LintasApps.com";
        $descriptionPages = "Daftarkan diri Anda di  | LintasApps.com  atau silakan Login bagi Anda yang telah terdaftar";
    }else if(!empty($isParamItem) && $isParamItem == 'contact')
    {
        $titlePages   = "Hubungi Kami di | LintasApps.com";
        $descriptionPages = "Jika Anda ingin bertanya atau mengalami kesulitan tanyakan pada kami";
    }else if(!empty($isParamItem) && $isParamItem == 'browse')
    {
        $titlePages   = "Download Aplikasi di | LintasApps.com ";
        $descriptionPages = "Pilihlah aplikasi dari | LintasApps.com, semudah anda menekan tombol beli untuk Anda miliki";
    }
    else
    {
        $titlePages = "Portal Download Aplikasi,Foto,Video | LintasApps.com";
        $descriptionPages = "Portal Download Aplikasi,Foto,Video | LintasApps.com";
    }
    ?>
<!DOCTYPE html>
<html lang="en"><head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<title><?php echo $titlePages;?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="<?php echo $descriptionPages;?>">
	<meta name="author" content="LintasApps">
	<script>var web = { host: '<?php echo base_url(); ?>' } </script>
	<link rel="stylesheet" href="<?php echo base_url('static/theme/job_board/css/job_blue.responsive.css'); ?>" title="job_blue" type="text/css">
	<link rel="stylesheet" href="<?php echo base_url('static/lib/gritter/jquery.gritter.css'); ?>" title="job_blue" type="text/css">
	<link rel="icon shortcut" href="<?php echo base_url('static/img/favicon.ico'); ?>" type="image/x-icon" />
	
    <!--[if lt IE 9]>
		<link rel="stylesheet" href="<?php echo base_url('static/theme/job_board/css/bootstrap_ie7.css'); ?>" title="job_blue" type="text/css">
		<link rel="stylesheet" href="<?php echo base_url('static/theme/job_board/css/ie7.css'); ?>" title="job_blue" type="text/css">
		<link rel="stylesheet" href="<?php echo base_url('static/theme/job_board/js/html5.js'); ?>" title="job_blue" type="text/css">
	<![endif]-->
<script type="text/javascript" src="<?php echo base_url('static/theme/job_board/js/jquery.js'); ?>"></script>
</head>