<?php
	$array_menu = array( 'menu' => array('Order', 'Transaction') );
?>

<?php $this->load->view( 'panel/common/meta' ); ?>
<body>
	<style>
		.right { text-align: right !important; }
	</style>
	<div id="loading_layer hide"><img src="<?php echo base_url(); ?>static/img/ajax_loader.gif" alt="" /></div>
	
	<div id="maincontainer" class="clearfix">
		<?php $this->load->view( 'panel/common/header' ); ?>
		
		<div id="WinProduct" class="modal modal-big hide fade" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">
			<div class="modal-header">
				<a href="#" class="close" data-dismiss="modal">&times;</a>
				<h3>Detail Product</h3>
            </div>
			<div class="modal-body" style="padding-left: 20px;"></div>
        </div>
		
		<div id="contentwrapper">
			<div class="main_content">
				<?php $this->load->view( 'panel/common/breadcrumb', array( 'array_menu' => $array_menu ) ); ?>
				
				<div class="row-fluid">
					<div class="span12">
						<div class="nota-message"></div>
						<table id="nota" class="table table-striped table-bordered dTableR">
							<thead><tr>
								<th style="width: 50px;">&nbsp;</th>
								<th>Tanggal</th>
								<th>No Nota</th>
								<th>Nama</th>
								<th>Email</th>
								<th>Tax</th>
								<th>Discount</th>
								<th>Final Price</th>
								<th>Total</th>
                            </tr></thead>
							<tbody><tr><td class="dataTables_empty">Loading data from server</td></tr></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
		<?php $this->load->view( 'panel/common/sidebar' ); ?>
    </div>
	
	<?php $this->load->view( 'panel/common/js' ); ?>
	<script>
		$(document).ready(function() {
			var grid_transaction = null;
			setTimeout('$("html").removeClass("js")', 300);
			
			
			function init_table() {
				grid_transaction = $('#nota').dataTable( {
					"aaSorting": [[1, 'desc']], "sServerMethod": "POST",
					"bProcessing": true, "bServerSide": true, "sPaginationType": "bootstrap",
					"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
					"sAjaxSource": web.host + 'panel/order/transaction/grid',
					"aoColumns": [
                    { "sClass": "center", "bSortable": false },
                    null,
                    null,
                    null,
                    null,
                    { "sClass": "right", "bSortable": false },
                    { "sClass": "right", "bSortable": false },
                    { "sClass": "right", "bSortable": false },
                    { "sClass": "right", "bSortable": false }
					]
                } );
				grid_transaction.load = Func.reload({ id: 'nota' });
                
				$('#nota').on('click','tbody td img.product', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					
					Func.ajax({ url: web.host + 'panel/order/nota/view', param: { action: 'product_list', nota_id: record.id }, is_json: 0, callback: function(result) {
						$('#WinProduct .modal-body').html(result);
						$('#WinProduct').modal();
                    } });
                });
            }
			init_table();
        });
    </script>
</body>
</html>