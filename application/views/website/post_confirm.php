<?php
	preg_match('/(\d+)$/i', $_SERVER['REQUEST_URI'], $match);
	$item_id = (isset($match[1])) ? $match[1] : '';
	
	$item = $this->Item_model->get_by_id(array( 'id' => $item_id ));
?>

<?php $this->load->view( 'website/common/meta' ); ?>
<body>
<?php $this->load->view( 'website/common/header' ); ?>

<div class="container-fluid sidebar_content"><div class="row-fluid">
	<div class="span8">	
		<br />
		<h2><i class="icon-suitcase"></i>&nbsp;&nbsp;Confirm Item</h2>
		
		<div class="row-fluid form-tooltip">	
			<div class="span12">
				<h4>Terima Kasih</h4>
				Berikut adalah halaman item anda,<br />
				<a href="<?php echo $item['item_link']; ?>"><?php echo $item['item_link']; ?></a><br /><br />
				
				<h4>Setelah software anda kami setujui, akan kami kabarkan via email.</h4>
				Kontak CS kami jika tidak mendapat kabar selama X hari
			</div>	
		</div>
	</div>
	
	<div class="span4 sidebar">	
		<br />
		<br />
		<h3>Posting a job is Free!!!</h3>
		
		<div class="row-fluid form-tooltip">	
			
			<div class="span12">
				<h4>Reach thousands of users</h4>
				Lorem ipsum dolor sit amet, consectetur adipiscing elit. In congue turpis sed enim posuere malesuada. Aliquam a urna et dolor blandit tincidunt.
			</div>	
		</div>	
		<div class="row-fluid">
			
			<div class="span12">
				<h4>View CVs instantly</h4>
				Lorem ipsum dolor sit amet, consectetur adipiscing elit. In congue turpis sed enim posuere malesuada. Aliquam a urna et dolor blandit tincidunt.
			</div>			
		</div>			
		<div class="row-fluid">
			
			<div class="span12">
				<h4>Integrated analytics</h4>
				Lorem ipsum dolor sit amet, consectetur adipiscing elit. In congue turpis sed enim posuere malesuada. Aliquam a urna et dolor blandit tincidunt.
			</div>
		</div>
		
	</div>		
</div></div>

<?php $this->load->view( 'website/common/footer' ); ?>

</body>
</html>