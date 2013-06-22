<?php
	$array_menu = (isset($array_menu)) ? $array_menu : array( 'menu' => array('Home') );
?>
<nav>
	<div id="jCrumbs" class="breadCrumb module">
		<ul>
			<li><a href="#"><i class="icon-home"></i></a></li>
			<?php foreach ($array_menu['menu'] as $value) { ?>
				<li><?php echo $value; ?></li>
			<?php } ?>
		</ul>
	</div>
</nav>