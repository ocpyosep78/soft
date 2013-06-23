<?php
	$param_transaction = array('filter' => '[{"type":"numeric","comparison":"eq","value":"'.$_POST['nota_id'].'","field":"Transaction.nota_id"}]');
	$array_transaction = $this->Transaction_model->get_array($param_transaction);
?>

<table class="table table-bordered">
<thead><tr>
	<th>#</th>
	<th>Nama Item</th>
	<th>Quantity</th>
	<th>Total</th>
</tr></thead>
<tbody>
	<?php $counter = 1; $total = 0; $currency = ''; ?>
	<?php foreach ($array_transaction as $item) { ?>
		<?php $total += $item['total']; ?>
		<?php $currency = $item['currency']; ?>
		<tr>
			<td><?php echo $counter++; ?></td>
			<td><a href="<?php echo $item['item_link']; ?>" target="_blank"><?php echo $item['title']; ?></a></td>
			<td><?php echo $item['quantity']; ?></td>
			<td><?php echo $item['currency_total']; ?></td></tr>
	<?php } ?>
		<tr>
			<td>&nbsp;</td>
			<td>Total</td>
			<td>&nbsp;</td>
			<td><?php echo $currency.' '.$total; ?></td></tr>
</tbody>
</table>