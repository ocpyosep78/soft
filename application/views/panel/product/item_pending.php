<?php
	$user = $this->User_model->get_session();
	$array_menu = array( 'menu' => array('Product', 'Item Pending') );
	//$array_currency = $this->Currency_model->get_array();
	
	
	$array_category = $this->Category_model->get_array(null);
	$array_platform = $this->Platform_model->get_array(null);
    
?>

<?php $this->load->view( 'panel/common/meta' ); ?>

<script type="text/javascript" src="<?php echo base_url('static/js/plupload/browserplus-min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('static/js/plupload/plupload.full.js'); ?>"></script>

<body>
	<div id="loading_layer" style="display:none"><img src="<?php echo base_url(); ?>static/img/ajax_loader.gif" /></div>
	
	<div id="maincontainer" class="clearfix">
		<?php $this->load->view( 'panel/common/header' ); ?>
		
		<div id="contentwrapper">
			<div class="main_content">
				<?php $this->load->view( 'panel/common/breadcrumb', array( 'array_menu' => $array_menu ) ); ?>
				
				<div id="form-item" class="hide">
					<div class="row-fluid">
						<div class="span12">
							<form class="form-horizontal">
								<h3>Form Item</h3>
                                <input type="text" id="input_item_status_id" name="item_status_id" class="hide" value="<?php echo STATUS_ITEM_APPROVE?>" />
								<div class="span7">
									<div class="pad-alert" style="padding-left: 15px;"></div>
									<input type="hidden" name="id" value="0" />
									<input type="hidden" name="user_id" value="<?php echo $user['id'];?>" />
									<div class="control-group">
										<label class="control-label" for="input_title">Nama</label>
										<div class="controls">
											<input type="text" id="input_name" name="name" placeholder="Nama" class="span12" rel="twipsy" data-placement="right" data-original-title="Nama Catalog" />
                                        </div>
                                    </div>
									<div class="control-group">
										<label class="control-label" for="input_name" >Description</label>
										<div class="controls">
											<textarea id="input_description" name="description" class="span12 tinymce" style="width: 100%; height: 250px;"></textarea>
                                        </div>
                                    </div>
                                </div>
								<div class="span4">
                                    
									<div class="control-group">
										<label class="control-label" for="input_category">Category</label>
										<div class="controls">
											<select id="input_category" name="category_id">
												<?php echo ShowOption(array('Array' => $array_category, 'ArrayID' => 'id', 'ArrayTitle' => 'name', 'WithEmptySelect' => 0)); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
										<label class="control-label" for="input_category">Platform</label>
										<div class="controls">
											<select id="input_category" name="platform_id" >
												<?php echo ShowOption(array('Array' => $array_platform, 'ArrayID' => 'id', 'ArrayTitle' => 'name', 'WithEmptySelect' => 0)); ?>
                                            </select>
                                        </div>
                                    </div>
									<div class="control-group">
										<label class="control-label" for="input_price">Price</label>
										<div class="controls">
											<input type="text" id="input_price" name="price" placeholder="Price" class="span12" rel="twipsy" data-placement="right" data-original-title="Price" />
                                        </div>
                                    </div>
									<div class="control-group" id="picture-upload">
										<label class="control-label" for="input_price">Upload Screenshoot</label>
										<div class="controls">
											<div class="upload_multi">
												<div class="upload-list"></div>
												<div class="clear"></div>
												<iframe frameborder="0" class="iframe" src="<?php echo site_url('upload/upload_single?callback=image_item'); ?>" scrolling="no"></iframe>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="control-group" id="picture-upload">
										<label class="control-label" for="input_price">Upload your files</label>
                                        <div class="controls">
                                            
                                            <div id="uploadcontainer">
                                                <div id="filelist">
                                                    
                                                </div>
                                                <a id="pickfiles" class="btn btn-info btn-small" href="#">Select files</a>
                                                <a id="uploadfiles" class="btn btn-small hide" href="#">Upload files</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								<div class="span12 center">
									<a class="btn cursor cancel">Cancel</a>
									<a class="btn cursor save btn-primary">OK</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
				<div id="grid-data">
                    <!--
                        <div class="row-fluid">
						<div class="btn-group">
                        <button class="btn btn-gebo AddItem">Tambah</button>
                        </div>
                        </div>
                    -->
					<div class="row-fluid">
						<div class="span12">
							<div class="item-message"></div>
							<table id="item-grid" class="table table-striped table-bordered dTableR">
								<thead>
									<tr>
										<th style="width: 50px;">&nbsp;</th>
										<th>Name</th>
										<th style="width: 100px;">Description</th>
										<th style="width: 100px;">Price</th>
                                    </tr>
                                </thead>
								<tbody><tr><td class="dataTables_empty">Loading data from server</td></tr></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php $this->load->view( 'panel/common/sidebar' ); ?>
    </div>
    <?php $this->load->view( 'panel/common/js' ); ?>
    
    <script>
        $(document).ready(function() {
            var isSubmit = false,
            getExt = function(filename) {
                var index = filename.lastIndexOf('.'), ext = '';
                if (index > 0) ext = filename.toLowerCase().substring( index+1 );
                if (!ext) {
                    ext = 'file';
                    } if (/txt|doc|ppt|xls|pdf/i.test( ext )) {
                    ext = 'document ' + ext;
                    } else if (/png|jpg|jpeg/i.test( ext )) {
                    ext = 'image ' + ext;
                    } else if (/mp3|aac|wav|au|ogg|wma/i.test( ext )) {
                    ext = 'audio ' + ext;
                    } else if (/mpg|wmv|mov|flv/i.test( ext )) {
                    ext = 'video ' + ext;
                }
                return ext;
            };
            var uploader = new plupload.Uploader({
                runtimes : 'gears,html5,flash,silverlight,browserplus',
                browse_button : 'pickfiles',
                container : 'uploadcontainer',
                max_file_size : '100mb',
                url: web.host + 'upload/file',
                flash_swf_url: web.base + 'static/js/plupload/plupload.flash.swf',
                silverlight_xap_url : web.base + 'static/js/plupload/plupload.silverlight.xap'
            });
            $('#uploadfiles').click(function(e) {
                if ( $("#filelist .addedfile").length > 0 )
                uploader.start();
                return false;
            });
            uploader.init();
            
            uploader.bind('FilesAdded', function(up, files) {
                $.each(files, function(i, file) {
                    var ext = getExt(file.name);
                    $('#filelist').append('<div class="addedfile uploadfile '+ext+'" id="' + file.id + '"><span class="filename">' + file.name + '</span> (' + plupload.formatSize(file.size) + ') <b></b>' + '</div>');
                });
                up.refresh(); // Reposition Flash/Silverlight
                $('#uploadfiles').click();
            });
            uploader.bind('UploadProgress', function(up, file) {
                $('#' + file.id + " b").html(file.percent + "%");
            });
            uploader.bind('Error', function(up, err) {
                $('#filelist').append("<div class='alert alert-error'>Error: " + err.code + ", Message: " + err.message + (err.file ? ", File: " + err.file.name : "") + "</div>");
                up.refresh(); // Reposition Flash/Silverlight
            });
            uploader.bind('FileUploaded', function(up, file, jsonresp) {
                var div = $("#"+file.id);
                var json = eval('('+jsonresp.response+')');
                
                if (json.error != null && json.error.code != null) {
                    div.remove();
                    $.sticky(json.error.message, {autoclose : 2500, position: "top-right" });
                    } else {
                    div.removeClass('addedfile').addClass('completefile').find('b').html("100%");
                    div.after('<input type="hidden" name="item_file[]" value="' + json.new_dir + '/' + json.fileName + '">');
                }
            });
            
            var grid_item = null;
            setTimeout('$("html").removeClass("js")', 300);
            
            var upload = {
                get_template: function(p) {
                    var content = '';
                    content += '<div class="item">';
                    content += '<input type="hidden" name="item_picture[]" value="' + p.filename + '" />';
                    content += '<div class="picture"><img src="' + web.base + '/static/upload/' + p.filename + '" /></div>';
                    content += '<div class="delete"><img src="' + web.base + '/static/img/delete.png" alt="Delete Picture" title="Delete Picture" /></div>';
                    //					content += '<div class="thumbail"><img src="' + web.base + '/static/img/thumbnail.png" alt="Set as Thumbnail" title="Set as Thumbnail" /></div>';
                    content += '</div>';
                    
                    return content;
                },
                generate: function(p) {
                    if (typeof(p.array_picture) == 'undefined') {
                        return;
                    }
                    
                    // generate content
                    var cnt_picture = '';
                    for (var i = 0; i < p.array_picture.length; i++) {
                        cnt_picture += upload.get_template({ filename: p.array_picture[i].picture_name });
                    }
                    
                    // set content
                    $('.upload-list .item').remove();
                    $('.upload-list').prepend(cnt_picture);
                    upload.delete_picture();
                },
                add_picture: function(p) {
                    var content = upload.get_template({ filename: p.filename });
                    $('.upload-list').append(content);
                    upload.delete_picture();
                },
                delete_picture: function() {
                    $('.upload-list .delete').click(function() {
                        $(this).parent('.item').remove();
                    });
                }
            }
            image_item = function(p) {
                upload.add_picture({ filename: p.filename })
            }
            
            
            $('.AddItem').click(function(){ 
                $('#form-item form')[0].reset();
                $('[name="id"]').val(0);
				$('#filelist').html('');
				upload.generate({ array_picture: [] });
				
                $("#grid-data").hide();
                $("#form-item").show();
            });
            
            $('#form-item .cancel').click(function() {
                $("#grid-data").show();
                $("#form-item").hide();
            });
            
            Func.InitForm({
                Container: '#form-item',
                rule: { title: { required: true } }
            });
            
            $('#form-item input[name="title"]').keyup(function() { $('#form-item input[name="name"]').val(Func.GetName($(this).val())); });
            $('#form-item .save').click(function() {
                if (! $('#form-item form').valid()) {
                    return;
                }
                
                var param = Site.Form.GetValue('form-item');
                param.action = 'update';
                Func.ajax({ url: web.host + 'panel/product/item/action', param: param, callback: function(result) {
                    if (result.status == 1) {
                        Func.popup_result('.item-message', result.message);
                        $("#grid-data").show();
                        $("#form-item").hide();
                        grid_item.load();
                    }
                } });
            });
            
            $('#item-grid').on('click','tbody td img.confirm', function () {
                var raw = $(this).parent('td').find('.hide').text();
                eval('var temp = ' + raw);
                
                var param = { action: 'get_item_by_id', id: temp.id };
                Func.ajax({ url: web.host + 'panel/product/item/action', param: param, callback: function(record) {
                    // common data
                    $('#form-item [name="id"]').val(record.id);
                    $('#form-item [name="name"]').val(record.name);
                    $('#form-item [name="description"]').text(record.description);
                    $('#form-item [name="price"]').val(record.price);
                    $('#form-item [name="platform_id"]').val(record.platform_id);
                    $('#form-item [name="category_id"]').val(record.category_id);
                    
                } });
                
                if (! $('#form-item').valid()) {
                    return;
                }
                
                var param = Site.Form.GetValue('form-item');
                param.action = 'update';
                Func.ajax({ url: web.host + 'panel/order/nota/action', param: param, callback: function(result) {
                    if (result.status == 1) {
                        Func.popup_result('.nota-message', result.message);
                        $('#form-item').modal('hide');
                        item-grid.load();
                        } else {
                        Func.popup_error('#form-item', result.message);
                    }
                } });
            });
            
            function init_table() {
                grid_item = $('#item-grid').dataTable( {
                    "aaSorting": [[1, 'asc']], "sServerMethod": "POST",
                    "bProcessing": true, "bServerSide": true, "sPaginationType": "bootstrap",
                    "sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
                    "sAjaxSource": web.host + 'panel/product/item/grid_pending',
                    "aoColumns": [
                    { "sClass": "center", "bSortable": false },
                    null,
                    { "sClass": "center" },
                    { "sClass": "center" }
                    ]
                } );
                grid_item.load = Func.reload({ id: 'item-grid' });
                
                $('#item-grid').on('click','tbody td img.edit', function () {
                    var raw = $(this).parent('td').find('.hide').text();
                    eval('var temp = ' + raw);
                    
                    var param = { action: 'get_item_by_id', id: temp.id };
                    Func.ajax({ url: web.host + 'panel/product/item/action', param: param, callback: function(record) {
                        // common data
                        $('#form-item [name="id"]').val(record.id);
                        $('#form-item [name="name"]').val(record.name);
                        $('#form-item [name="description"]').text(record.description);
                        $('#form-item [name="price"]').val(record.price);
                        
                        $('#form-item [name="platform_id"]').val(record.platform_id);
                        $('#form-item [name="category_id"]').val(record.category_id);
                        /*
                            // catalog
                            var array_platform = [];
                            for (var i = 0; i < record.array_platform.length; i++) {
                            array_platform.push(record.array_platform[i].id);
                            }
                            $('#form-item [name="platform_id_id"]').val(array_platform);
                            
                            // category
                            var array_category = [];
                            for (var i = 0; i < record.array_category.length; i++) {
                            array_category.push(record.array_category[i].id);
                            }
                            $('#form-item [name="category_id"]').val(array_category);
                            
                        */
						
                        $("#grid-data").hide();
                        $("#form-item").show();
                    } });
                });
                
                
                
                $('#item-grid').on('click','tbody td img.delete', function () {
                    var raw = $(this).parent('td').find('.hide').text();
                    eval('var record = ' + raw);
                    
                    Func.confirm_delete({
                        data: { action: 'delete', id: record.id },
                        url: web.host + 'panel/product/item/action', grid: grid_item
                    });
                });
            }
            init_table();
        });
    </script>
</body>
</html>