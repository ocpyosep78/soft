<?php
	$user = $this->User_model->get_session();
	$array_menu = array( 'menu' => array('Product', 'Item') );
	//$array_currency = $this->Currency_model->get_array();
	
	
	$array_category = $this->Category_model->get_array(array( 'limit' => 1000 ));
	$array_platform = $this->Platform_model->get_array(array( 'limit' => 1000 ));
    
?>

<?php $this->load->view( 'panel/common/meta' ); ?>

<script type="text/javascript" src="<?php echo base_url('static/js/plupload/browserplus-min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('static/js/plupload/plupload.full.js'); ?>"></script>

<body>
    <div class="hide">
        <iframe name="iframe_thumbnail" src="<?php echo base_url('upload?callback=thumbnail_set'); ?>"></iframe>
        <div id="thumbnail_directory"><?php echo base_url("static/upload/")?></div>
    </div>
    
	<div id="loading_layer" style="display:none"><img src="<?php echo base_url(); ?>static/img/ajax_loader.gif" /></div>
	<div id="maincontainer" class="clearfix">
		<?php $this->load->view( 'panel/common/header' ); ?>
		
		<div id="contentwrapper">
			<div class="main_content">
				<?php $this->load->view( 'panel/common/breadcrumb', array( 'array_menu' => $array_menu ) ); ?>
				<div class="item-message"></div>
				<form id="form-item" class="hide">
                    
					<div class="row-fluid">
						<div class="span12">
							<div class="form-horizontal">
								<h3>Form Item</h3>
								<div class="span10">
									<div class="pad-alert" style="padding-left: 15px;"></div>
                                    <input type="hidden" name="action"/>
									<input type="hidden" name="id" value="0" />
									<input type="hidden" name="user_id" value="<?php echo $user['id'];?>" />
									<div class="control-group">
										<label class="control-label" for="input_title">Nama</label>
										<div class="controls">
											<input type="text" id="input_name" name="name" placeholder="Nama" class="span12" rel="twipsy" data-placement="right" data-original-title="Nama Item Anda" />
                                        </div>
                                    </div>
									<div class="control-group">
										<label class="control-label" for="input_description" >Description</label>
										<div class="controls">
											<textarea id="input_description" name="description" class="span12 tinymce" style="width: 100%; height: 250px;" data-original-title="Deskripsikan Item Anda"></textarea>
                                        </div>
                                    </div>
                                </div>
								<div class="span10">
									<div class="control-group">
										<label class="control-label" for="input_category">Category</label>
										<div class="controls">
											<select id="input_category" name="category_id">
												<?php echo ShowOption(array('Array' => $array_category, 'ArrayID' => 'id', 'ArrayTitle' => 'name', 'WithEmptySelect' => 0)); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
										<label class="control-label" for="input_platform">Platform</label>
										<div class="controls">
											<select id="input_platform" name="platform_id" >
												<?php echo ShowOption(array('Array' => $array_platform, 'ArrayID' => 'id', 'ArrayTitle' => 'name', 'WithEmptySelect' => 0)); ?>
                                            </select>
                                        </div>
                                    </div>
									<div class="control-group">
										<label class="control-label" for="input_price">Price</label>
										<div class="controls">
											<input type="text" id="input_price" name="price" placeholder="Price" class="span12" rel="twipsy" data-placement="right" data-original-title="Harga dari Item Anda" />
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Current Icon</label>
                                        <div class="controls">
                                            <div type="text" class="span12" name="thumbnail_current" id="thumbnail_current" ><img src="" alt="current icon"/></div>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Thumbnail/Icon Aplikasi / Item</label>
                                        <div class="controls">
                                            <input type="text" class="span6" id="input_thumbnail" name="thumbnail" readonly="readonly" style="margin: 0px;" />
                                            <a class="btn btn-primary btn-success btn-thumbnail input_tooltips">Browse</a>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Current Screenshot</label>
                                        <div class="controls">
                                            <div type="text" class="span12" name="screenshot_current" id="screenshot_current" >
                                            </div>
                                        </div>
                                    </div>
									<div class="control-group">
                                        <label class="control-label">Screenshot / Tampilan Preview</label>
                                        <div class="controls">
                                            <div id="uploadcontainer1">
                                                <div id="filelist1" style="padding: 0 0 15px 0;"></div>
                                                <a id="pickfiles1" class="btn btn-primary btn-success input_tooltips">Pilih Gambar</a>
                                                <a id="uploadfiles1" class="hide">Unggah Screenshot</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Current File</label>
                                        <div class="controls">
                                            <div type="text" class="span12" name="current_filename" id="current_filename" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="control-group" id="picture-upload">
										<label class="control-label" for="input_file">Upload your files</label>
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
									<a class="btn btn-primary btn-large pull-center cursor cancel">Cancel</a>
									<!--<a class="btn cursor save btn-primary btn-large pull-center">OK.</a>-->
                                    <a id="btn-item-submit" class="btn btn-primary btn-large pull-center btn-item-submit">OK</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                
				<div id="grid-data">
					<div class="row-fluid">
						<div class="btn-group">
							<button class="btn btn-gebo AddItem">Tambah</button>
                        </div>
                    </div>
					
					<div class="row-fluid">
						<div class="span12">
							
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
        var thumbnail_set = function(p) {
            $('[name="thumbnail"]').val(p.file_name);
        }
        
        var thumbnail_directory = $('#thumbnail_directory').text();
        
        var uploader, uploader2;
        $(document).ready(function() {
            // thumbnail
            $('#form-item .btn-thumbnail').click(function() { window.iframe_thumbnail.browse() });
            
            // upload item config // file item
            uploader = new plupload.Uploader({
                max_file_size : '100mb', 
                url: web.host + 'upload/file',
                browse_button : 'pickfiles', 
				chunk_size: '1mb',
                container : 'uploadcontainer',
                runtimes : 'gears,html5,flash,silverlight,browserplus',
                flash_swf_url: web.base + 'static/js/plupload/plupload.flash.swf',
                silverlight_xap_url : web.base + 'static/js/plupload/plupload.silverlight.xap'
            });
            
            $('#uploadfiles').click(function(e) {
                var platform_id = $("select[name=platform_id]").val();
                if (!platform_id) {
                    Func.show_notice({ title: 'Informasi', text: "Pilih platform aplikasi / item anda sebelum upload" });
                    return false;
                }
                if ( $("#filelist .addedfile").length > 0 ) {
                    uploader.start();
                }
                return false;
            });
            
            uploader.init();
            
            uploader.bind('FilesAdded', function(up, files) {
                $.each(files, function(i, file) {
                    $('#filelist').append('<div class="addedfile uploadfile" id="' + file.id + '"><span class="filename">' + file.name + '</span> (' + plupload.formatSize(file.size) + ') <b></b>' + '</div>');
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
                    Func.show_notice({ title: 'Informasi', text: json.error.message });
                    } else {
                    div.removeClass('addedfile').addClass('completefile').find('b').html("100%");
                    div.after('<input type="hidden" name="item_file[]" value="' + json.new_dir + '/' + json.fileName + '">');
                }
                
                //submit form
                var $form = $("#form-item");
                if ( $("#filelist .addedfile").length == 0 && $form.data('isSubmit') == true ) {
                    $form.removeData('isSubmit');
                    $(".alert").remove();
                    $form.submit();
                }
            });    
            
            // upload item config // screenshot
            uploader2 = new plupload.Uploader({
                max_file_size : '100mb', 
                url: web.host + 'upload/file?screenshot=1',
				chunk_size: '1mb',
                browse_button : 'pickfiles1', 
                container : 'uploadcontainer1',
                runtimes : 'gears,html5,flash,silverlight,browserplus',
                flash_swf_url: web.base + 'static/js/plupload/plupload.flash.swf',
                silverlight_xap_url : web.base + 'static/js/plupload/plupload.silverlight.xap'
            });
            
            $('#uploadfiles1').click(function(e) {
                if ( $("#filelist1 .addedfile").length > 0 ) {
                    uploader2.start();
                }
                return false;
            });
            
            uploader2.init();
            
            uploader2.bind('FilesAdded', function(up, files) {
                $.each(files, function(i, file) {
                    $('#filelist1').append('<div class="addedfile uploadfile" id="' + file.id + '"><span class="filename">' + file.name + '</span> (' + plupload.formatSize(file.size) + ') <b></b>' + '</div>');
                });
                up.refresh(); // Reposition Flash/Silverlight
                $('#uploadfiles1').click();
            });
            
            uploader2.bind('UploadProgress', function(up, file) {
                $('#' + file.id + " b").html(file.percent + "%");
            });
            
            uploader2.bind('Error', function(up, err) {
                $('#filelist1').append("<div class='alert alert-error'>Error: " + err.code + ", Message: " + err.message + (err.file ? ", File: " + err.file.name : "") + "</div>");
                up.refresh(); // Reposition Flash/Silverlight
            });
            
            uploader2.bind('FileUploaded', function(up, file, jsonresp) {
                var div = $("#"+file.id);
                var json = eval('('+jsonresp.response+')');
                
                if (json.error != null && json.error.code != null) {
                    div.remove();
                    Func.show_notice({ title: 'Informasi', text: json.error.message });
                    } else {
                    div.removeClass('addedfile').addClass('completefile').find('b').html("100%");
                    div.append('<br><img src="' + json.relativePath + '/' + json.thumbName + '">');
                    div.after('<input type="hidden" name="item_screenshot[]" value="' + json.new_dir + '/' + json.fileName + '">');
                }
                
                //submit form
                var $form = $("#form-item");
                if ( $("#filelist1 .addedfile").length == 0 && $form.data('isSubmit') == true ) {
                    $form.removeData('isSubmit');
                    $(".alert").remove();
                    $form.submit();
                }
            });
            
            // form
            $("#form-item").validate({
                rules: {
                    name: { required: true },
                    price: { required: true },
                    platform_id: { required: true },
                    category_id: { required: true },
                    description: { required: true }
                },
                messages: {
                    name: { required: 'Silahkan mengisi field ini' },
                    price: { required: 'Silahkan mengisi field ini' },
                    platform_id: { required: 'Silahkan mengisi field ini' },
                    category_id: { required: 'Silahkan mengisi field ini' },
                    description: { required: 'Silahkan mengisi field ini' }
                }
            });
            
            // submit ( ok ) button action
            //$('#form-item').submit(function() {
            $('#btn-item-submit').click(function() {
                var $form = $('#form-item');
                
                if ($form.data('isSubmit') === true) {
                    return false;
                }
                if (! $form.valid()) {
                    return false;
                }
                
                var param = Site.Form.GetValue('#form-item');
              
                /* admin only
                if (param.item_file == null || param.item_file.length < 1) {
                    Func.popup_result('.item-message', 'Silahkan upload file aplikasi anda');
                    return false;
                }*/
                
                
                if ( $("#filelist .addedfile").length > 0 ) {
                    $form.data('isSubmit', true);
                    uploader.start();
                }
                
                if ( $("#filelist1 .addedfile").length > 0 ) {
                    $form.data('isSubmit', true);
                    uploader2.start();
                }
                
                if ($form.data('isSubmit') === true) {
                    $('<div class="alert alert-info">uploading..</div>').insertBefore('.btn-item-submit');
                    return false;
                }
                
                Func.ajax({ url: web.host + 'panel/product/item/action', param: param, callback: function(result) {
                    if (result.status == 1) {
                        Func.popup_result('.item-message', result.message);
                        if(result.status == 1)
                        {
                             $("#form-item").hide();
                             $("#grid-data").show();
                             grid_item.load();
                        }
                    }
                } });
                
                return false;
            });
            
            
            
            
            
            /* var upload = {
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
                
            */
            
            
            
            
            
            // add button action
            $('.AddItem').click(function(){ 
                $('#form-item form')[0].reset();
                $('[name="id"]').val(0);
                $('#filelist').html('');
                //upload.generate({ array_picture: [] });
                
                $("#grid-data").hide();
                $("#form-item").show();
            });
            // cancel button action
            $('#form-item .cancel').click(function() {
                $("#grid-data").show();
                $("#form-item").hide();
            });
            
            
            
            // Func.InitForm({
            //     Container: '#form-item',
            //       rule: { name: { required: true } }
            //  });
            
            // $('#form-item input[name="title"]').keyup(function() { $('#form-item input[name="name"]').val(Func.GetName($(this).val())); });
            
            /*   $('#form-item .save').click(function() {
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
            */
            
            
            // grid tabel
            var grid_item = null;
            setTimeout('$("html").removeClass("js")', 300);
            //init tabel
            function init_table() {
                grid_item = $('#item-grid').dataTable( {
                    "aaSorting": [[1, 'asc']], "sServerMethod": "POST",
                    "bProcessing": true, "bServerSide": true, "sPaginationType": "bootstrap",
                    "sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
                    "sAjaxSource": web.host + 'panel/product/item/grid',
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
                        $('#form-item [name="action"]').val("update");
                        $('#form-item [name="id"]').val(record.id);
                        $('#form-item [name="name"]').val(record.name);
                        $('#form-item [name="description"]').text(record.description);
                        $('#form-item [name="price"]').val(record.price);
                        $('#form-item [name="platform_id"]').val(record.platform_id);
                        $('#form-item [name="category_id"]').val(record.category_id);
                        // thumbnail
                        $('#form-item [name="thumbnail_current"]').find('img').each(function(){
                            $(this).attr('src', thumbnail_directory + "/" +record.thumbnail);
                            if(record.thumbnail=='')
                            {
                                $(this).remove();
                            }
                        });
                        if(record.thumbnail!='')
                        {
                            $('#form-item [name="thumbnail_current"]').append('<img class="del_thumbnail_current" src=\"<?php echo base_url("static/img/delete.png")?>\" />');
                        }
                        //screenshot
                        var current_screenshot = eval(record.screenshot);
                        if( current_screenshot!='')
                        {
                            for(var i=0; i < current_screenshot.length; i++)
                            {
                                //console.log(current_screenshot[i]);
                                var screenshot =  current_screenshot[i].split('.');
                                var screenshot_file_name = screenshot[0];
                                var screenshot_file_ext = screenshot[1];
                                var screenshot_file_thumb_name = screenshot[0]+ "_thumb." + screenshot_file_ext;
                                
                                $('#form-item [name="screenshot_current"]').append('<img style="margin:4px;" class="img-polaroid" id="current_screenshot'+ i +'" src=\"<?php echo base_url("screenshots")?>/'+ screenshot_file_thumb_name +'\" />');
                                
                                $('#form-item [name="screenshot_current"]').append('<img class="del_screeshot_current" id="del_screeshot_current'+ i +'" src=\"<?php echo base_url("static/img/delete.png")?>\" alt="delete screenshot" data-value="'+ current_screenshot[i] +'" data-screenshot_element ="current_screenshot'+ i +'" />');
                            }
                        }
                        //files
                        var current_filename = eval(record.filename);
                        if( current_filename!='')
                        {
                            for(var i=0; i < current_filename.length; i++)
                            {
                                $('#form-item [name="current_filename"]').append('<div id="filname_current'+ i +'">'+ current_filename[i] +'</div>');
                                $('#form-item [name="current_filename"]').append('<img class="del_filname_current" id="del_filname_current'+ i +'" src=\"<?php echo base_url("static/img/delete.png")?>\" alt="delete screenshot" data-value="'+ current_filename[i] +'" data-filename_element ="filname_current'+ i +'" />');
                            }
                        }
                        
                        $("#grid-data").hide();
                        $("#form-item").show();
                    } });
                });
                
                //delete thumbnail
                $('#thumbnail_current').on('click', 'img.del_thumbnail_current', function(){
                    var idItem  = $('#form-item [name="id"]').val();
                    var param = { action: 'del_thumbnail_current', id: idItem };
                    Func.ajax({ url: web.host + 'panel/product/item/action', param: param, callback: function(result) {
                        if (result.status == 1) {
                            Func.popup_result('.item-message', result.message);
                            $('#form-item [name="thumbnail_current"]').find('img').each(function(){
                                $(this).remove();
                            });
                        }
                    } });
                });
                
                // delete screenshot
                $('.del_screeshot_current').live('click',function(a){
                    var del_element = $(this).attr("id"); // id del_image - x -
                    var screenshot_element = a.target.dataset.screenshot_element; //id screenshot
                    
                    var idItem  = $('#form-item [name="id"]').val();
                    var currentScreenshot = a.target.dataset.value;
                    var param = { action: 'del_screenshot_current', id: idItem , screenshot : currentScreenshot};
                    Func.ajax({ url: web.host + 'panel/product/item/action', param: param, callback: function(result) 
                        {
                            if (result.status == 1) {
                                Func.popup_result('.item-message', result.message);
                                $('#'+del_element).remove();
                                $('#'+screenshot_element).remove();
                            }
                        } 
                    });
                });
                
                // delete file
                $('.del_filname_current').live('click',function(a){
                    var del_element = $(this).attr("id"); // id del_image - x -
                    var filename_element = a.target.dataset.filename_element; //id filename_element
                    var idItem  = $('#form-item [name="id"]').val();
                    var currentFilename = a.target.dataset.value;
                    var param = { action: 'del_filename_current', id: idItem , filename : currentFilename};
                    Func.ajax({ url: web.host + 'panel/product/item/action', param: param, callback: function(result) 
                        {
                            if (result.status == 1) {
                                Func.popup_result('.item-message', result.message);
                                $('#'+del_element).remove();
                                $('#'+filename_element).remove();
                            }
                        } 
                    });
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