<?php
	$array_menu = array( 'menu' => array('Product', 'Catalog') );
?>

<?php $this->load->view( 'panel/common/meta' ); ?>
<body>
	<div id="loading_layer" style="display:none"><img src="<?php echo base_url(); ?>static/img/ajax_loader.gif" alt="" /></div>
	
	<div id="maincontainer" class="clearfix">
		<?php $this->load->view( 'panel/common/header' ); ?>
		
		<div id="WinCatalog" class="modal modal-big hide fade" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">
			<div class="modal-header">
				<a href="#" class="close" data-dismiss="modal">&times;</a>
				<h3>Form Catalog</h3>
			</div>
			<div class="modal-body" style="padding-left: 0px;">
				<div class="pad-alert" style="padding-left: 15px;"></div>
				<form class="form-horizontal" style="padding-left: 0px;">
					<input type="hidden" name="id" value="0" />
					<div class="control-group">
						<label class="control-label" for="input_title">Nama</label>
						<div class="controls">
							<input type="text" id="input_title" name="title" placeholder="Nama Catalog" class="span5" rel="twipsy" data-placement="right" data-original-title="Nama Catalog" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="input_name">Alias</label>
						<div class="controls">
							<input type="text" id="input_name" name="name" placeholder="Alias Catalog" class="span5" rel="twipsy" readonly="readonly" data-placement="right" data-original-title="Alias Catalog" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Image</label>
						<div class="controls relative">
							<input type="text" name="image" placeholder="Image" class="span4" readonly="readonly" />
							<div class="upload_single">
								<iframe frameborder="0" src="<?php echo site_url('panel/upload/upload_single?callback=image_catalog'); ?>" class="iframe" scrolling="no"></iframe>
							</div>
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
						<button class="btn btn-gebo AddCatalog">Tambah</button>
					</div>
				</div>
				
				<div class="row-fluid">
					<div class="span12">
						<div class="message"></div>
						<table id="catalog" class="table table-striped table-bordered dTableR">
							<thead>
								<tr><th style="width: 50px;">&nbsp;</th>
									<th>Nama</th>
									<th>Alias</th></tr>
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
			var grid_catalog = null;
			setTimeout('$("html").removeClass("js")', 300);
			
			image_catalog = function(p) {
				$('[name="image"]').val(p.filename);
			}
			
			Func.InitForm({
				Container: '#WinCatalog',
				rule: { title: { required: true } }
			});
			
			$('.AddCatalog').click(function() {
				$('#WinCatalog form')[0].reset()
				$('#WinCatalog input[name="id"]').val(0);
				$('#WinCatalog').modal();
			});
			$('#WinCatalog input[name="title"]').keyup(function() { $('#WinCatalog input[name="name"]').val(Func.GetName($(this).val())); });
			$('#WinCatalog .save').click(function() {
				if (! $('#WinCatalog form').valid()) {
					return;
				}
				
				var param = Site.Form.GetValue('WinCatalog');
				param.action = 'update';
				Func.ajax({ url: web.host + 'panel/product/catalog/action', param: param, callback: function(result) {
					if (result.status == 1) {
						$('.message').html('<div class="alert alert-info"><a data-dismiss="alert" class="close">&times;</a>' + result.message + '</div>');
						$('#WinCatalog').modal('hide');
						grid_catalog.load();
					}
				} });
			});
			$('#WinCatalog .cancel').click(function() {
				$('#WinCatalog').modal('hide');
			});
			
			function init_table() {
				grid_catalog = $('#catalog').dataTable( {
					"aaSorting": [[1, 'asc']], "sServerMethod": "POST",
					"bProcessing": true, "bServerSide": true, "sPaginationType": "bootstrap",
					"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
					"sAjaxSource": web.host + 'panel/product/catalog/grid',
					"aoColumns": [
						{ "sClass": "center", "bSortable": false },
						null,
						null
					]
				} );
				grid_catalog.load = Func.reload({ id: 'catalog' });
				
				$('#catalog').on('click','tbody td img.edit', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					$('#WinCatalog input[name="id"]').val(record.id);
					$('#WinCatalog input[name="title"]').val(record.title);
					$('#WinCatalog input[name="name"]').val(record.name);
					$('#WinCatalog input[name="image"]').val(record.image);
					$('#WinCatalog').modal();
				});
				$('#catalog').on('click','tbody td img.delete', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					
					Func.confirm_delete({
						data: { action: 'delete', id: record.id },
						url: web.host + 'panel/product/catalog/action', grid: grid_catalog
					});
				});
			}
			init_table();
		});
	</script>
</body>
</html>