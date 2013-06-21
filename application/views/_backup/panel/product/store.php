<?php
	$array_menu = array( 'menu' => array('Product', 'Store') );
?>
<?php
    
    // sementara / temporary
    $user_id    = 1;
    $name       = "Johny Smith";
    
?>
<?php $this->load->view( 'panel/common/meta' ); ?>
<body>
	<div id="loading_layer" style="display:none"><img src="<?php echo base_url(); ?>static/img/ajax_loader.gif" alt="" /></div>
    <div id="maincontainer" class="clearfix">
		<?php $this->load->view( 'panel/common/header' ); ?>
        <div id="WinStore" class="modal modal-big hide fade" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">
            <div class="modal-header">
                <a href="#" class="close" data-dismiss="modal">&times;</a>
                <h3>Form Store</h3>
            </div>
            <div class="modal-body" style="padding-left: 0px;">
                <div class="pad-alert" style="padding-left: 15px;"></div>
                <form class="form-horizontal" style="padding-left: 0px;">
                    <input type="hidden" name="id" value="0" />
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                    <div class="control-group">
                        <label class="control-label" for="input_name">Nama Store</label>
                        <div class="controls">
                            <input type="text" id="input_name" name="name" placeholder="Nama Store" class="span5" rel="twipsy" data-placement="right" data-original-title="Nama Store" />
                        </div>
                        <label class="control-label" for="input_domain">Domain</label>
                        <div class="controls">
                            <input type="text" id="input_domain" name="domain" placeholder="Domain" class="span5" rel="twipsy" data-placement="right" data-original-title="Domain" />
                        </div>
                        <label class="control-label" for="input_option">Option</label>
                        <div class="controls">
                            <textarea id="input_option" name="option" placeholder="Option" class="span5" rel="twipsy" data-placement="right" data-original-title="Option" ></textarea>
                        </div>
                        <label class="control-label" for="input_title">Theme Store</label>
                        <div class="controls">
                            <select name="theme_id" id="theme_id">
                                <option value="0">Select</option>
                                <?php foreach($theme_id as $key=>$value):?>
                                <option value="<?php echo $value['id']?>"><?php echo $value['name']?></option>
                                <?php endforeach; ?>
                            </select>
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
						<button class="btn btn-gebo AddStore">Tambah</button>
                    </div>
                </div>
				
				<div class="row-fluid">
					<div class="span12">
						<div class="message"></div>
                        <table id="store" class="table table-striped table-bordered dTableR">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">&nbsp;</th>
									<th>Nama Store</th>
                                    <th>Domain</th>
                                    <th>Option</th>
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
        var grid_category = null;
        wooe = grid_category;
        
        setTimeout('$("html").removeClass("js")', 300);
        
        Func.InitForm({
            Container: '#WinStore',
            rule: { title: { required: true } }
        });
        
        $('.AddStore').click(function() {
            $('#WinStore form')[0].reset()
            $('#WinStore input[name="id"]').val(0);
            $('#WinStore').modal();
        });
        $('#WinStore .save').click(function() {
            if (! $('#WinStore form').valid()) {
                return;
            }
            
            var param = Site.Form.GetValue('WinStore');
            param.action = 'update';
            Func.ajax({ url: web.host + 'panel/product/store/action', param: param, callback: function(result) {
                if (result.status == 1) {
                    $('.message').html('<div class="alert alert-info"><a data-dismiss="alert" class="close">&times;</a>' + result.message + '</div>');
                    $('#WinStore').modal('hide');
                    grid_category.load();
                }
            } });
        });
        $('#WinStore .cancel').click(function() {
            $('#WinStore').modal('hide');
        });
        
        function init_table() {
            grid_category = $('#store').dataTable( {
                "aaSorting": [[1, 'asc']], 
                "sServerMethod": "POST",
                "bProcessing": true, 
                "bServerSide": true, 
                "sPaginationType": "bootstrap",
                "sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
                "sAjaxSource": web.host + 'panel/product/store/grid',
                "aoColumns": [
                { "sClass": "center", "bSortable": false },
                null,
                null,
                null
                ]
            } );
            grid_category.load = Func.reload({ id: 'store' });
            
            $('#store').on('click','tbody td img.edit', function () {
                var raw = $(this).parent('td').find('.hide').text();
                console.log(raw);
                eval('var record = ' + raw);
                $('#WinStore input[name="id"]').val(record.id);
                $('#WinStore input[name="name"]').val(record.name);
                $('#WinStore input[name="domain"]').val(record.domain);
                $('#WinStore textarea[name="option"]').val(record.option);
                $('#WinStore').modal();
            });
            $('#store').on('click','tbody td img.delete', function () {
                var raw = $(this).parent('td').find('.hide').text();
                eval('var record = ' + raw);
                
                Func.confirm_delete({
                    data: { action: 'delete', id: record.id },
                    url: web.host + 'panel/product/store/action', grid: grid_category
                });
            });
        }
        init_table(); 
    });
</script>
</body>        