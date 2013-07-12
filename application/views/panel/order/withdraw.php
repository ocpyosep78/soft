<?php
	$array_menu = array( 'menu' => array('Order', 'Withdraw') );
?>

<?php $this->load->view( 'panel/common/meta' ); ?>
<body>
	<div id="loading_layer hide"><img src="<?php echo base_url(); ?>static/img/ajax_loader.gif" alt="" /></div>
	
	<div id="maincontainer" class="clearfix">
		<?php $this->load->view( 'panel/common/header' ); ?>
        <div class="hide">
			
        </div>
		
		<div id="contentwrapper">
			<div class="main_content">
				<?php $this->load->view( 'panel/common/breadcrumb', array( 'array_menu' => $array_menu ) ); ?>
				
				<div class="row-fluid">
					<div class="span12">
						<div class="nota-message"></div>
						<table id="nota" class="table table-striped table-bordered dTableR">
							<thead><tr>
								<th>&nbsp;</th>
								<th>Tanggal</th>
								<th>User</th>
								<th>Nilai Rupiah</th>
								<th>Nilai Konversi Dollar</th>
								<th>%</th>
								<th>Profit</th>
								<th>Currency</th>
								<th>Status</th>
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
			var grid_withdraw = null;
			setTimeout('$("html").removeClass("js")', 300);
            
			function init_table() {
				grid_withdraw = $('#nota').dataTable( {
					"aaSorting": [[1, 'desc']], "sServerMethod": "POST",
					"bProcessing": true, "bServerSide": true, "sPaginationType": "bootstrap",
					"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
					"sAjaxSource": web.host + 'panel/order/withdraw/grid',
					"aoColumns": [
                    { "sClass": "center", "bSortable": false, sWidth: "7%" },
                    null,
                    null,
                    null,
                    null,
                    { "sClass": "center", "bSortable": false },
                    { "bSortable": false },
                    null,
                    null
					]
                } );
				grid_withdraw.load = Func.reload({ id: 'nota' });
				
				$('#nota').on('click','tbody td img.confirm', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					
                    var param = { action: 'update', id: record.id, status: 'confirm' };
                    Func.ajax({ url: web.host + 'panel/order/withdraw/action', param: param, callback: function(result) {
						Func.popup_result('.nota-message', result.message);
						grid_withdraw.load();
                    } });
                });
				
				$('#nota').on('click','tbody td img.delete', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					
					Func.confirm_delete({
						data: { action: 'delete', id: record.id },
						url: web.host + 'panel/order/withdraw/action',
						grid: grid_withdraw, cnt_mesage: '.nota-message'
                    });
                });
            }
			init_table();
        });
    </script>
</body>
</html>