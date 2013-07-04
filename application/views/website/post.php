<?php
	$is_human = $this->User_model->is_human();
	if (! $is_human) {
		header("Location: ".base_url("capthca"));
		exit;
	}
	
	preg_match('/\/post\/([\d]+)$/i', $_SERVER['REQUEST_URI'], $match);
	$item_id = (isset($match[1])) ? $match[1] : 0;
	$item = $this->Item_model->get_by_id(array( 'id' => $item_id ));
	
	$is_owner = $this->User_model->is_owner(array( 'item_id' => $item_id ));
	if (! empty($item_id) && ! $is_owner) {
		header("Location: ".base_url('login'));
		exit;
	}
	
	$array_category = $this->Category_model->get_array(array( 'limit' => 1000 ));
	$array_platform = $this->Platform_model->get_array(array( 'limit' => 1000 ));
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

<?php $this->load->view( 'website/common/meta' ); ?>
<style>
	.cursor { cursor: pointer !important; }
    fieldset.detail .control-group {margin-bottom:0;}
    fieldset.screenshot { background-color:#eee; padding:0 5px;}
    fieldset.files { background-color:#ddd; padding-left:10px;}
    h3.xx { margin-bottom:0; }
</style>
<body>
    <?php $this->load->view( 'website/common/header' ); ?>
    
    <div class="hide">
        <iframe name="iframe_thumbnail" src="<?php echo base_url('upload?callback=thumbnail_set'); ?>"></iframe>
		<div class="cnt-item"><?php echo json_encode($item); ?></div>
    </div>
    
    <div class="container-fluid sidebar_content"><div class="row-fluid">
        <div class="span10">	
            <h2><i class="icon-suitcase"></i>&nbsp;&nbsp;Unggah Aplikasi/Item</h2>
			
			<div class="hero">
				<p>Upload hasil kreasi anda, registrasikan akun anda, mulai berjualan di LintasApps.com, GRATIS</p>
			</div>
            
            <form id="form-item">
                <input type="hidden" name="id" value="0" />
                <input type="hidden" name="action" value="update" />
                
                <h3>Detail Aplikasi Anda</h3>
				
                <fieldset class="detail">
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Nama Aplikasi/Item</label>
                                <div class="controls"><input type="text" class="span12 input_tooltips" name="name" data-placement="right" title="Masukkan nama item atau aplikasi anda disini"/></div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Harga</label>
                                <div class="controls">
                                    <div class="input-prepend">
                                        <span class="add-on">Rp.</span>
                                        <input class="input_tooltips" id="prependedInput" type="text" name="price" data-placement="right" title="Harga aplikasi yang ingin anda jual" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Platform Aplikasi/Item</label>
                                <div class="controls">
                                    <select class="span12 input_tooltips" name="platform_id" data-placement="right" title="Pilih platform dimana Aplikasi / Item anda dapat berjalan">
                                        <option value="">--Pilih platform aplikasi--</option>
                                        <?php foreach($platforms as $parent=>$children): ?>
										<optgroup label="<?php echo htmlspecialchars($parent); ?>">
                                            <?php foreach($children as $id => $platform): ?>
                                            <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($platform); ?></option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label">Kategori</label>
                                <div class="controls">
                                    <select class="span12 input_tooltips" name="category_id" data-placement="right" title="Kategori item atau aplikasi anda">
                                        <option value="">--Pilih kategori--</option>
                                        <?php foreach($categories as $parent=>$children): ?>
										<optgroup label="<?php echo htmlspecialchars($parent); ?>">
                                            <?php foreach($children as $id => $category): ?>
                                            <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($category); ?></option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="control-group">
                                <label class="control-label">Deskripsi / Keterangan</label>
                                <div class="controls"><textarea rows="3" class="span12 input_tooltips" name="description" data-placement="right" title="Tambahkan deskripsi untuk aplikasi atau item anda, akan sangat membantu penjualan atau pencarian aplikasi"></textarea></div>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="control-group">
                                <label class="control-label">Thumbnail/Icon Aplikasi / Item</label>
                                <div class="controls">
                                    <input type="text" class="span6" name="thumbnail" readonly="readonly" style="margin: 0px;" />
                                    <a class="btn btn-primary btn-success btn-thumbnail input_tooltips">Browse</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                
                <h3 class="xx">File Screenshot Aplikasi / Item Anda</h3>
                <fieldset class="screenshot">
                    <div class="row-fluid">
                        <div class="span12">
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
                        </div>
                    </div>
                </fieldset>
                
                <h3 class="xx">File Aplikasi / Item Anda</h3>
                <fieldset class="files">
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="control-group">
                                <label class="control-label">Unggah File Anda</label>
                                <div class="controls">
                                    <div id="uploadcontainer">
                                        <div id="filelist" style="padding: 0 0 15px 0;"></div>
                                        <a id="pickfiles" class="btn btn-primary btn-success input_tooltips">Pilih file</a>
                                        <a id="uploadfiles" class="hide">Unggah file</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                
                <hr>
                
                <button type="submit" class="btn btn-primary btn-large pull-right btn-item-submit">Kirim</button>
                
                <br /><br />
            </form>
            
        </div>
        
    </div></div>
    
    <?php $this->load->view( 'website/common/footer' ); ?>
	<script type="text/javascript" src="<?php echo base_url('static/js/plupload/browserplus-min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('static/js/plupload/plupload.full.js'); ?>"></script>
    
    <script>
        var thumbnail_set = function(p) {
            $('[name="thumbnail"]').val(p.file_name);
        }
        var uploader, uploader2;
        
        $(document).ready(function() {
            // thumbnail
            $('#form-item .btn-thumbnail').click(function() { window.iframe_thumbnail.browse() });
            
            // upload item config
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
                    $('#filelist').append('<div class="addedfile uploadfile" id="' + file.id + '"><a class="cursor remove-uploadfile">(remove)</a> <span class="filename">' + file.name + '</span> <b></b>' + '</div>');
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
                    div.append('<input type="hidden" name="item_file[]" value="' + json.new_dir + '/' + json.fileName + '">');
                }
				
				div.find('.remove-uploadfile').click(function() {
					$(this).parents('.uploadfile').remove();
				});
                
                //submit form
                var $form = $("#form-item");
                if ( $("#filelist .addedfile").length == 0 && $form.data('isSubmit') == true ) {
                    $form.removeData('isSubmit');
                    $(".alert").remove();
                    $form.submit();
                }
            });
            
            // upload item config
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
                    $('#filelist1').append('<div class="addedfile uploadfile" id="' + file.id + '"><a class="cursor remove-uploadfile">(remove)</a> <span class="filename">' + file.name + '</span> <b></b>' + '</div>');
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
                    div.append('<input type="hidden" name="item_screenshot[]" value="' + json.new_dir + '/' + json.fileName + '">');
                }
                
				div.find('.remove-uploadfile').click(function() {
					$(this).parents('.uploadfile').remove();
				});
				
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
            
            $("select[name=platform_id]").change(function() {
                var platform_id = $(this).val();
                uploader.settings.multipart_params = { platform_id: platform_id };
                if ( $("#filelist .addedfile").length > 0 ) {
                    uploader.start();
                }
            });
            
            $('#form-item').submit(function() {
                var $form = $(this);
                
                if ($form.data('isSubmit') === true) {
                    return false;
                }
                
                if (! $form.valid()) {
                    return false;
                }
                
                var param = Site.Form.GetValue('form-item');
                if (param.item_file == null || param.item_file.length < 1) {
                    Func.show_notice({ title: 'Informasi', text: 'Silahkan upload file aplikasi anda' });
                    return false;
                }
                
                
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
                
                Func.ajax({ 
                    url: web.host + 'ajax/item', 
                    param: param, 
                    callback: function(result) {
                        Func.show_notice({ title: 'Informasi', text: result.message });
                        if (result.status) {
                            window.location = result.link_next;
                        }
                    } 
                });
                
                return false;
            });
        
			// edit
			var raw = $('.cnt-item').text();
			if (raw.length > 10) {
				eval('var item = ' + raw);
				
				$('#form-item [name="id"]').val(item.id);
				$('#form-item [name="name"]').val(item.name);
				$('#form-item [name="price"]').val(item.price);
				$('#form-item [name="platform_id"]').val(item.platform_id);
				$('#form-item [name="category_id"]').val(item.category_id);
				$('#form-item [name="description"]').val(item.description);
				$('#form-item [name="thumbnail"]').val(item.thumbnail);
				$('#form-item [name="platform_id"]').change();
				
				// screenshot
				var content = '';
				for (var i = 0; i < item.array_screenshot.length; i++) {
					var file = item.array_screenshot[i];
					content += '<div class="uploadfile">';
					content += '<span class="filename"><a class="cursor remove-uploadfile">(remove)</a> ' + file.basename + '</span> <strong>100%</strong><br>';
					content += '<img src="' + file.link + '" />';
					content += '<input type="hidden" name="item_screenshot[]" value="' + file.name + '">';
					content += '</div>';
				}
				$('#filelist1').html(content);
				
				// file
				var content = '';
				for (var i = 0; i < item.array_filename.length; i++) {
					var file = item.array_filename[i];
					var filename = file.replace(new RegExp(/^\d+\/\d+\/\d+\//gi), '');
					
					content += '<div class="uploadfile">';
					content += '<span class="filename"><a class="cursor remove-uploadfile">(remove)</a> ' + filename + '</span> <strong>100%</strong><br>';
					content += '<input type="hidden" name="item_file[]" value="' + file.name + '">';
					content += '</div>';
				}
				$('#filelist').html(content);
				
				$('.remove-uploadfile').click(function() {
					$(this).parents('.uploadfile').remove();
				});
			}
		});
    </script>
    
</body>
</html>