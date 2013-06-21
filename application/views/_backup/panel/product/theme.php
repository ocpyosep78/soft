<?php
	$array_menu = array( 'menu' => array('Product', 'Theme') );
?>

<?php $this->load->view( 'panel/common/meta' ); ?>
<body>
	<div id="loading_layer" style="display:none"><img src="<?php echo base_url(); ?>static/img/ajax_loader.gif" alt="" /></div>
	
	<div id="maincontainer" class="clearfix">
		<?php $this->load->view( 'panel/common/header' ); ?>
		
		<div id="WinTheme" class="modal modal-big hide fade" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">
			<div class="modal-header">
				<a href="#" class="close" data-dismiss="modal">&times;</a>
				<h3>Form Theme</h3>
            </div>
			<div class="modal-body" style="padding-left: 0px;">
				<div class="pad-alert" style="padding-left: 15px;"></div>
				<form class="form-horizontal" style="padding-left: 0px;">
					<input type="hidden" name="id" value="0" />
					<div class="control-group">
						<label class="control-label" for="input_title">Nama</label>
						<div class="controls">
							<input type="text" id="input_title" name="name" placeholder="Nama Theme" class="span5" rel="twipsy" data-placement="right" data-original-title="Nama Theme" />
                        </div>
                    </div>
					<div class="control-group">
						<label class="control-label" for="input_name">Code</label>
						<div class="controls">
							<input type="text" id="input_code" name="code" placeholder="Theme Code" class="span5" rel="twipsy" readonly="readonly" data-placement="right" data-original-title="Theme Code" />
                        </div>
                    </div>
                    <div class="control-group">
						<label class="control-label" for="input_name">Is Premium ?</label>
						<div class="controls">
							<input type="checkbox" name="is_premium" class="span1" rel="twipsy"  data-placement="left" data-original-title="Is Premium">
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
						<button class="btn btn-gebo AddTheme">Tambah</button>
                    </div>
                </div>
				
				<div class="row-fluid">
					<div class="span12">
						<div class="message"></div>
						<table id="theme" class="table table-striped table-bordered dTableR">
							<thead>
								<tr>
                                    <th style="width: 50px;">&nbsp;</th>
									<th>Nama</th>
									<th>Code</th>
									<th>Premium ?</th>
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
			var grid_theme = null;
			wooe = grid_theme;
			setTimeout('$("html").removeClass("js")', 300);
			
			Func.InitForm({
				Container: '#WinTheme',
				rule: { title: { required: true } }
            });
			
			$('.AddTheme').click(function() {
				$('#WinTheme form')[0].reset()
				$('#WinTheme input[name="id"]').val(0);
				$('#WinTheme').modal();
            });
			$('#WinTheme input[name="name"]').keyup(function() { $('#WinTheme input[name="code"]').val(Func.GetName($(this).val())); });
			$('#WinTheme .save').click(function() {
				if (! $('#WinTheme form').valid()) {
					return;
                }
				var param = Site.Form.GetValue('WinTheme');
                if(param.is_premium == "on")
                {
                    param.is_premium = 1;
                }else
                {
                    param.is_premium = 0;
                }
				param.action = 'update';
				Func.ajax({ url: web.host + 'panel/product/theme/action', param: param, callback: function(result) {
					if (result.status == 1) {
						$('.message').html('<div class="alert alert-info"><a data-dismiss="alert" class="close">&times;</a>' + result.message + '</div>');
						$('#WinTheme').modal('hide');
						grid_theme.load();
                    }
                } });
            });
			$('#WinTheme .cancel').click(function() {
				$('#WinTheme').modal('hide');
            });
			
			function init_table() {
				grid_theme = $('#theme').dataTable( {
					"aaSorting": [[1, 'asc']], "sServerMethod": "POST",
					"bProcessing": true, "bServerSide": true, "sPaginationType": "bootstrap",
					"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
					"sAjaxSource": web.host + 'panel/product/theme/grid',
					"aoColumns": [
                    { "sClass": "center", "bSortable": false },
                    null,
                    null,
                    { "sName": "is_premium", fnRender: make_is_premium, "bSortable": true, "bSearchable": true }
					]
                } );
				grid_theme.load = Func.reload({ id: 'theme' });
				
                function make_is_premium(oObj) 
                {  
                    var is_parent = oObj.aData[3];
                    if(is_parent==1)
                    {
                        return "Premium";
                    }else
                    {
                        return "Not Premium";
                    }
                }
                
				$('#theme').on('click','tbody td img.edit', function () {
					var raw = $(this).parent('td').find('.hide').text();
                    console.log(raw);
					eval('var record = ' + raw);
					$('#WinTheme input[name="id"]').val(record.id);
					$('#WinTheme input[name="name"]').val(record.name);
					$('#WinTheme input[name="code"]').val(record.code);
					if(record.is_premium==1)
                    {
                        $('#WinTheme input[name="is_premium"]').attr('checked',true);
                    }else
                    {
                        $('#WinTheme input[name="is_premium"]').attr('checked',false)
                    }
					$('#WinTheme').modal();
                });
				$('#theme').on('click','tbody td img.delete', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					
					Func.confirm_delete({
						data: { action: 'delete', id: record.id },
						url: web.host + 'panel/product/theme/action', grid: grid_theme
                    });
                });
            }
			init_table();
        });
    </script>
</body>
</html>