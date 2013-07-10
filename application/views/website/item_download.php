<?php
?>

<?php $this->load->view( 'website/common/meta' ); ?>
<body>
<?php $this->load->view( 'website/common/header' ); ?>

<style>
	.fl .lbl { width: 80px; display: block; float: left; font-weight: bold; }
	.fl .r { padding:5px; border-bottom:1px solid #ccc; }
	.fl .r:nth-child(even) { background-color:#eee; }
	.registerorlogin { background-color:#eee; padding:10px; margin: 10px 0;}
</style>

<div class="container-fluid sidebar_content"><div class="row-fluid">
	<div class="span8">	
		<br />
		<h2><a href="<?php echo base_url(); ?>">HOME</a> > DOWNLOAD ITEM > <?php echo $item['name']; ?></h2>
		
		<div class="row-fluid form-tooltip">
			<div class="span12 fl">
				<h3>Berikut list file yang dapat Anda download :</h3>
				
				<ul style="padding-bottom: 30px;">
					<?php foreach ($item['array_download'] as $file) { ?>
					<li><a href="<?php echo $file['file_link']; ?>"><?php echo $file['file_basename']; ?></a></li>
					<?php } ?>
				</ul>
				
				<div>Selamat download</div>
			</div>
		</div>
	</div>
	
	<div class="span4 sidebar"><br /><br />
		<div class="row-fluid form-tooltip">	
			<div class="span12">
				&nbsp;
			</div>	
		</div>
	</div>		
</div></div>

<?php $this->load->view( 'website/common/footer' ); ?>
</body>
</html>