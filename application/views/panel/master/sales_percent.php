<?php
	$array_menu = array( 'menu' => array('Master', 'Sales Percent') );
?>

<?php $this->load->view( 'panel/common/meta' ); ?>
<body>
	<div id="loading_layer" style="display:none"><img src="<?php echo base_url(); ?>static/img/ajax_loader.gif" /></div>
	
    <div id="maincontainer" class="clearfix">
		<?php $this->load->view( 'panel/common/header' ); ?>
		
        <div id="WinSalesPercent" class="modal modal-big hide fade" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">
            <div class="modal-header">
                <a href="#" class="close" data-dismiss="modal">&times;</a>
                <h3>Form Sales Percent</h3>
            </div>
            <div class="modal-body" style="padding-left: 0px;">
                <div class="pad-alert" style="padding-left: 15px;"></div>
                <form class="form-horizontal" style="padding-left: 0px;">
                    <input type="hidden" name="id" value="0" />
                    
                    <div class="control-group">
                        <label class="control-label" for="input_name">Sales Percent</label>
                        <div class="controls">
                            <input type="text" id="percent" name="percent" placeholder="Percent" class="span5" rel="twipsy" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="input_name">Rupiah</label>
                        <div class="controls">
                            <input type="text" id="rupiah" name="rupiah" placeholder="Rupiah" class="span5" rel="twipsy" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a class="btn cursor cancel">Cancel</a>
                <a class="btn cursor save btn-primary">OK</a>
            </div>
        </div>
        
        <div id="contentwrapper">
			<div class="main_content">
				<?php $this->load->view( 'panel/common/breadcrumb', array( 'array_menu' => $array_menu ) ); ?>
				
				<div class="row-fluid">
					<div class="btn-group">
						<button class="btn btn-gebo AddCategory">Tambah</button>
                    </div>
                </div>
				
				<div class="row-fluid">
					<div class="span12">
						<div class="message"></div>
                        <table id="sales_percent" class="table table-striped table-bordered dTableR">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">&nbsp;</th>
									<th>Percent</th>
									<th>Rupiah</th>
                                </tr>
                            </thead>
                            <tbody><tr><td class="dataTables_empty" colspan="1">Loading data from server</td></tr></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php $this->load->view( 'panel/common/sidebar' ); ?>
    </div>
</div>
<?php $this->load->view( 'panel/common/js' ); ?>
<script>
    $(document).ready(function() {
        var grid_sales_percent = null;
        
        setTimeout('$("html").removeClass("js")', 300);
        
        Func.InitForm({
            Container: '#WinSalesPercent',
            rule: { percent: { required: true } }
        });
        
        $('.AddCategory').click(function() {
            $('#WinSalesPercent form')[0].reset()
            $('#WinSalesPercent input[name="id"]').val(0);
            $('#WinSalesPercent').modal();
        });
        
        $('#WinSalesPercent .save').click(function() {
            if (! $('#WinSalesPercent form').valid()) {
                return;
            }
            
            var param = Site.Form.GetValue('WinSalesPercent');
            param.action = 'update';
            Func.ajax({ url: web.host + 'panel/master/sales_percent/action', param: param, callback: function(result) {
                if (result.status == 1) {
                    $('.message').html('<div class="alert alert-info"><a data-dismiss="alert" class="close">&times;</a>' + result.message + '</div>');
                    $('#WinSalesPercent').modal('hide');
                    grid_sales_percent.load();
                }
            } });
        });
        $('#WinSalesPercent .cancel').click(function() {
            $('#WinSalesPercent').modal('hide');
        });
		
        function init_table() {
            grid_sales_percent = $('#sales_percent').dataTable( {
                "aaSorting": [[1, 'asc']], 
                "sServerMethod": "POST",
                "bProcessing": true, 
                "bServerSide": true, 
                "sPaginationType": "bootstrap",
                "sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
                "sAjaxSource": web.host + 'panel/master/sales_percent/grid',
                "aoColumns": [
                { "sClass": "center", "bSortable": false },
                null,
                null
                ]
            } );
            grid_sales_percent.load = Func.reload({ id: 'sales_percent' });
          
            $('#sales_percent').on('click','tbody td img.edit', function () {
                var raw = $(this).parent('td').find('.hide').text();
                //console.log($(this).parent('td').find('.hide'));
                //console.log(eval(raw));
                eval('var record = ' + raw);
                $('#WinSalesPercent input[name="id"]').val(record.id);
                $('#WinSalesPercent input[name="percent"]').val(record.percent);
                $('#WinSalesPercent input[name="rupiah"]').val(record.rupiah);
                $('#WinSalesPercent').modal();
            });
            $('#sales_percent').on('click','tbody td img.delete', function () {
                var raw = $(this).parent('td').find('.hide').text();
                eval('var record = ' + raw);
                
                Func.confirm_delete({
                    data: { action: 'delete', id: record.id },
                    url: web.host + 'panel/master/sales_percent/action', grid: grid_sales_percent
                });
            });
        }
        init_table(); 
    });
</script>
</body>        