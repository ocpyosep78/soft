<?php
	$array_menu = array( 'menu' => array('Product', 'Category') );
?>

<?php $this->load->view( 'panel/common/meta' ); ?>
<body>
	<div id="loading_layer" style="display:none"><img src="<?php echo base_url(); ?>static/img/ajax_loader.gif" /></div>
	
    <div id="maincontainer" class="clearfix">
		<?php $this->load->view( 'panel/common/header' ); ?>
		
        <div id="WinCategory" class="modal modal-big hide fade" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">
            <div class="modal-header">
                <a href="#" class="close" data-dismiss="modal">&times;</a>
                <h3>Form Category</h3>
            </div>
            <div class="modal-body" style="padding-left: 0px;">
                <div class="pad-alert" style="padding-left: 15px;"></div>
                <form class="form-horizontal" style="padding-left: 0px;">
                    <input type="hidden" name="id" value="0" />
                    <div class="control-group">
						<label class="control-label" for="input_title">Title</label>
                        <div class="controls">
                            <input type="text" id="input_title" name="title" placeholder="Nama Category" class="span5" rel="twipsy" />
                        </div>
                    <div class="control-group">
                    </div>
                        <label class="control-label" for="input_name">Alias</label>
                        <div class="controls">
                            <input type="text" id="input_name" name="name" placeholder="Alias" readonly="readonly"  class="span5" rel="twipsy" />
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
                        <table id="category" class="table table-striped table-bordered dTableR">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">&nbsp;</th>
									<th>Nama</th>
                                    <th>Alias</th>
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
        
        setTimeout('$("html").removeClass("js")', 300);
        
        Func.InitForm({
            Container: '#WinCategory',
            rule: { title: { required: true } }
        });
        
        $('.AddCategory').click(function() {
            $('#WinCategory form')[0].reset()
            $('#WinCategory input[name="id"]').val(0);
            $('#WinCategory').modal();
        });
        $('#WinCategory input[name="title"]').keyup(function() { $('#WinCategory input[name="name"]').val(Func.GetName($(this).val())); });
        $('#WinCategory .save').click(function() {
            if (! $('#WinCategory form').valid()) {
                return;
            }
            
            var param = Site.Form.GetValue('WinCategory');
            param.action = 'update';
            Func.ajax({ url: web.host + 'panel/product/category/action', param: param, callback: function(result) {
                if (result.status == 1) {
                    $('.message').html('<div class="alert alert-info"><a data-dismiss="alert" class="close">&times;</a>' + result.message + '</div>');
                    $('#WinCategory').modal('hide');
                    grid_category.load();
                }
            } });
        });
        $('#WinCategory .cancel').click(function() {
            $('#WinCategory').modal('hide');
        });
		
        function init_table() {
            grid_category = $('#category').dataTable( {
                "aaSorting": [[1, 'asc']], 
                "sServerMethod": "POST",
                "bProcessing": true, 
                "bServerSide": true, 
                "sPaginationType": "bootstrap",
                "sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
                "sAjaxSource": web.host + 'panel/product/category/grid',
                "aoColumns": [
                { "sClass": "center", "bSortable": false },
                null,
                null
                ]
            } );
            grid_category.load = Func.reload({ id: 'category' });
          
            $('#category').on('click','tbody td img.edit', function () {
                var raw = $(this).parent('td').find('.hide').text();
                //console.log($(this).parent('td').find('.hide'));
                //console.log(eval(raw));
                eval('var record = ' + raw);
                $('#WinCategory input[name="id"]').val(record.id);
                $('#WinCategory input[name="title"]').val(record.title);
                $('#WinCategory input[name="name"]').val(record.name);
                $('#WinCategory').modal();
            });
            $('#category').on('click','tbody td img.delete', function () {
                var raw = $(this).parent('td').find('.hide').text();
                eval('var record = ' + raw);
                
                Func.confirm_delete({
                    data: { action: 'delete', id: record.id },
                    url: web.host + 'panel/product/category/action', grid: grid_category
                });
            });
        }
        init_table(); 
    });
</script>
</body>        