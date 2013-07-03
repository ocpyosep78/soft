<?php
	$array_category = $this->Category_model->get_array(array( 'limit' => 100 ));
	$array_platform = $this->Platform_model->get_array();
	$platforms=array();
	foreach($array_platform as $row) {
		list($parent,$child)=array_map('trim', explode('-', $row['name']));
		$platforms[$parent][$row['id']]=$child;
	}
	$categories=array();
	foreach($array_category as $row) {
		list($parent,$child)=array_map('trim', explode('-', $row['name']));
		$categories[$parent][$row['id']]=$child;
	}
?>

<h2>Cari Aplikasi</h2>
<div class="row-fluid"><form method="post" action="<?php echo base_url('browse'); ?>" id="form-search-main">
	<input type="hidden" name="page_no" value="1" />
	
	<div class="span12">
		<input type="text" class="span12 input_tooltips" name="keyword" placeholder="Cari aplikasi" value="<?php echo empty($_POST['keyword'])?'':htmlspecialchars($_POST['keyword']); ?>" />
		
		<select name="platform_id" class="span12 input_tooltips" data-placement="right"  title="Pilih Platform yang ingin Anda cari">
			<option value="">Platform Aplikasi...</option>
			<?php 
			$selected = empty($_POST['platform_id'])?0:intval($_POST['platform_id']);
			foreach($platforms as $parent=>$children): ?>
				<optgroup label="<?php echo htmlspecialchars($parent); ?>">
				<?php foreach($children as $id => $platform): ?>
				<option value="<?php echo $id; ?>"<?php if ($id == $selected) echo ' selected="selected"'; ?>><?php echo htmlspecialchars($platform); ?></option>
				<?php endforeach; ?>
				</optgroup>
			<?php endforeach; ?>
		</select>
		
		<select name="category_id" class="span12 input_tooltips" data-placement="right"  title="Pilih Kategori yang ingin Anda cari">
			<option value="">Kategori...</option>
			<?php 
			$selected = empty($_POST['category_id'])?0:intval($_POST['category_id']);
			foreach($categories as $parent=>$children): ?>
				<optgroup label="<?php echo htmlspecialchars($parent); ?>">
				<?php foreach($children as $id => $category): ?>
				<option value="<?php echo $id; ?>"<?php if ($id == $selected) echo ' selected="selected"'; ?>><?php echo htmlspecialchars($category); ?></option>
				<?php endforeach; ?>
				</optgroup>
			<?php endforeach; ?>
		</select>
		
		<button class="btn btn-primary pull-right">Mulai Mencari</button>
	</div>
</form></div>