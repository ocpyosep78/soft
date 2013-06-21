<?php
	$array_menu = array( 'menu' => array('Product', 'Blog') );
	$array_blog_status = $this->Blog_Status_model->get_array();
?>

<?php $this->load->view( 'panel/common/meta' ); ?>
<body>
	<div id="loading_layer" style="display:none"><img src="<?php echo base_url(); ?>static/img/ajax_loader.gif" /></div>
	
	<div id="maincontainer" class="clearfix">
		<?php $this->load->view( 'panel/common/header' ); ?>
		
		<div id="contentwrapper">
			<div class="main_content">
				<?php $this->load->view( 'panel/common/breadcrumb', array( 'array_menu' => $array_menu ) ); ?>
				
				<div class="row-fluid">
					<div class="btn-group">
						<button class="btn btn-gebo AddBlog">Tambah</button>
                    </div>
                </div>
				
                <div id="WinBlog" class="row-fluid" >
                    <div class="span12">
                        <form class="form-horizontal">
                            <h3>Form Blog</h3>
                            <input type="hidden" name="id" value="0" />
                            <div class="control-group">
                                <label class="control-label" for="input_title">Nama</label>
                                <div class="controls">
                                    <input type="text" id="input_title" name="title" placeholder="Nama Blog" class="span5" rel="twipsy" data-placement="right" data-original-title="Nama Blog" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="input_name">Alias</label>
                                <div class="controls">
                                    <input type="text" id="input_name" name="name" placeholder="Alias Blog" class="span5" rel="twipsy" readonly="readonly" data-placement="right" data-original-title="Alias Blog" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="input_content">Content</label>
                                <div class="controls">
									<textarea id="input_content" name="content" class="tinymce" style="height: 300px; width: 90%;"></textarea>
                                </div>
                            </div>
							<div class="control-group">
								<label class="control-label" for="input_blog_status">Status</label>
								<div class="controls">
									<select id="input_blog_status" name="blog_status_id">
										<?php echo ShowOption(array('Array' => $array_blog_status, 'ArrayID' => 'id', 'ArrayTitle' => 'name')); ?>
									</select>
								</div>
							</div>
                            <div class="span12">
                                <a class="btn cursor cancel">Cancel</a>
                                <a class="btn cursor save btn-primary">OK</a>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="row-fluid" id="grid-data">
                    <div class="span12">
                        <div class="message"></div>
                        <table id="blog" class="table table-striped table-bordered dTableR">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">&nbsp;</th>
                                    <th>Title</th>  
                                    <th style="width: 75px;">View</th>
                                </tr>
                            </thead>
                            <tbody><tr><td class="dataTables_empty" colspan="3">Loading data from server</td></tr></tbody>
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
            var grid_blog = null;
            setTimeout('$("html").removeClass("js")', 300);
            
            Func.InitForm({
                Container: '#WinBlog',
                rule: { title: { required: true } }
            });
            $("#WinBlog").hide();
            $('.AddBlog').click(function() {
                $('#WinBlog form')[0].reset()
                $('#WinBlog input[name="id"]').val(0);
                $("#grid-data").hide();
                $("#WinBlog").show();
            });
            $('#WinBlog input[name="title"]').keyup(function() { $('#WinBlog input[name="name"]').val(Func.GetName($(this).val())); });
            $('#WinBlog .save').click(function() {
                if (! $('#WinBlog form').valid()) {
                    return;
                }
                
                var param = Site.Form.GetValue('WinBlog');
                param.action = 'update';
                Func.ajax({ url: web.host + 'panel/product/blog/action', param: param, callback: function(result) {
                    if (result.status == 1) {
                        $('.message').html('<div class="alert alert-info"><a data-dismiss="alert" class="close">&times;</a>' + result.message + '</div>');
                        $("#grid-data").show();
                        $("#WinBlog").hide();
                        grid_blog.load();
                    }
                } });
            });
            $('#WinBlog .cancel').click(function() {
                grid_blog.load();
                $("#grid-data").show();
                $("#WinBlog").hide();
            });
            
            function init_table() {
                grid_blog = $('#blog').dataTable( {
                    "aaSorting": [[1, 'asc']], "sServerMethod": "POST",
                    "bProcessing": true, "bServerSide": true, "sPaginationType": "bootstrap",
                    "sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
                    "sAjaxSource": web.host + 'panel/product/blog/grid',
                    "aoColumns": [
                    { "sClass": "center", "bSortable": false },
                    null,
                    { "sClass": "center" }
                    ]
                } );
                grid_blog.load = Func.reload({ id: 'blog' });
                
                $('#blog').on('click','tbody td img.edit', function () {
                    var raw = $(this).parent('td').find('.hide').text();
                    eval('var record = ' + raw);
					
                    $('#WinBlog [name="id"]').val(record.id);
                    $('#WinBlog [name="title"]').val(record.title);
                    $('#WinBlog [name="name"]').val(record.name);
                    $('#WinBlog [name="content"]').val(record.content_html);
                    $('#WinBlog [name="blog_status_id"]').val(record.blog_status_id);
                    
                    $("#grid-data").hide();
                    $("#WinBlog").show();
                });
                
                $('#blog').on('click','tbody td img.delete', function () {
                    var raw = $(this).parent('td').find('.hide').text();
                    eval('var record = ' + raw);
                    
                    Func.confirm_delete({
                        data: { action: 'delete', id: record.id },
                        url: web.host + 'panel/product/blog/action', grid: grid_blog
                    });
                });
                
                
            }
            init_table();
        });
    </script>
</body>
</html>