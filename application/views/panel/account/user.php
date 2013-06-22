<?php
	$array_menu = array( 'menu' => array('Account', 'User') );
?>

<?php $this->load->view( 'panel/common/meta' ); ?>
<body>
	<div id="loading_layer hide"><img src="<?php echo base_url(); ?>static/img/ajax_loader.gif" alt="" /></div>
	
	<div id="maincontainer" class="clearfix">
		<?php $this->load->view( 'panel/common/header' ); ?>
		<div class="hide">
            <div class="status-user-confirm"><?php echo STATUS_USER_CONFIRM; ?></div>
            <div class="status-user-banned"><?php echo STATUS_USER_BANNED; ?></div>
        </div>
		<div id="WinUser" class="modal modal-big hide fade" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">
			<div class="modal-header">
				<a href="#" class="close" data-dismiss="modal">&times;</a>
				<h3>Form User</h3>
            </div>
			<div class="modal-body" style="padding-left: 0px;">
				<div class="pad-alert" style="padding-left: 15px;"></div>
				<form class="form-horizontal" style="padding-left: 0px;">
					<input type="hidden" name="id" value="0" />
					<div class="control-group">
						<label class="control-label" for="input_fullname">Nama Lengkap</label>
						<div class="controls">
							<input type="text" id="input_fullname" name="fullname" placeholder="Nama Lengkap" class="span5" rel="twipsy" />
                        </div>
                    </div>
					<div class="control-group">
						<label class="control-label" for="input_name">Username</label>
						<div class="controls">
							<input type="text" id="input_name" name="name" placeholder="Username" class="span5" rel="twipsy" />
                        </div>
                    </div>
					<div class="control-group">
						<label class="control-label" for="input_email">Email</label>
						<div class="controls">
							<input type="text" id="input_email" name="email" placeholder="Email" class="span5" rel="twipsy" />
                        </div>
                    </div>
					<div class="control-group">
						<label class="control-label" for="input_address">Address</label>
						<div class="controls">
							<textarea id="input_address" name="address" placeholder="Alamat" class="span5" rel="twipsy" ></textarea>
                        </div>
                    </div>
					<div class="control-group">
						<label class="control-label" for="input_passwd">Password</label>
						<div class="controls">
							<input type="text" id="input_passwd" name="passwd" placeholder="Password" class="span5" rel="twipsy" />
                        </div>
                    </div>
                    
                    <input type="text" id="input_is_active" name="is_active" placeholder="is_active" class="span5 hidden" rel="twipsy" />
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
						<button class="btn btn-gebo AddUser">Tambah</button>
                    </div>
                </div>
				
				<div class="row-fluid">
					<div class="span12">
						<div class="user-message"></div>
						<table id="user" class="table table-striped table-bordered dTableR">
							<thead><tr>
								<th style="width: 85px;">&nbsp;</th>
								<th>Nama</th>
								<th>Username</th>
								<th>Email</th>
								<th>Alamat</th>
								<th>Deposit</th>
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
			var grid_user = null;
			setTimeout('$("html").removeClass("js")', 300);
			//find user status
            var user_status_confirm = $('div.status-user-confirm').text();
            var user_status_banned = $('div.status-user-banned').text();
            
			Func.InitForm({
				Container: '#WinUser',
				rule: { fullname: { required: true }, name: { required: true }, email: { required: true }, address: { required: true } }
            });
			
			$('.AddUser').click(function() {
				$('#WinUser form')[0].reset()
				$('#WinUser input[name="id"]').val(0);
				$('#WinUser').modal();
            });
			$('#WinUser .save').click(function() {
				if (! $('#WinUser form').valid()) {
					return;
                }
				
				var param = Site.Form.GetValue('WinUser');
				param.action = 'update';
				Func.ajax({ url: web.host + 'panel/account/user/action', param: param, callback: function(result) {
					if (result.status == 1) {
						Func.popup_result('.user-message', result.message);
						$('#WinUser').modal('hide');
						grid_user.load();
                        } else {
						Func.popup_error('#WinUser', result.message);
                    }
                } });
            });
			$('#WinUser .cancel').click(function() {
				$('#WinUser').modal('hide');
            });
			
			function init_table() {
				grid_user = $('#user').dataTable( {
					"aaSorting": [[1, 'asc']], "sServerMethod": "POST",
					"bProcessing": true, "bServerSide": true, "sPaginationType": "bootstrap",
					"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
					"sAjaxSource": web.host + 'panel/account/user/grid',
                    "fnRowCallback": function( nRow, aData, iDisplayIndex ) {
                        /* Append the grade to the default row class name */
                        if ( aData[6] == 1 )
                        {
                            $('td:eq(6)', nRow).html( '<b>Confirm</b>' );
                        }else if( aData[6] == 0 )
                        {
                            $('td:eq(6)', nRow).html( '<b>New</b>' );
                        }else   if( aData[6] == 2 )
                        {
                            $('td:eq(6)', nRow).html( '<b>Banned</b>' );
                        }
                    },
					"aoColumns": [
                    { "sClass": "center", "bSortable": false},
                    null,
                    null,
                    null,
                    null,
                    null,
                    null
					]
                } );
				grid_user.load = Func.reload({ id: 'user' });
				
				$('#user').on('click','tbody td img.edit', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					$('#WinUser [name="id"]').val(record.id);
					$('#WinUser [name="name"]').val(record.name);
					$('#WinUser [name="fullname"]').val(record.fullname);
					$('#WinUser [name="email"]').val(record.email);
					$('#WinUser [name="address"]').val(record.address);
					$('#WinUser [name="passwd"]').val('');
					$('#WinUser').modal();
                });
                
				$('#user').on('click','tbody td img.delete', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					
					Func.confirm_delete({
						data: { action: 'delete', id: record.id },
						url: web.host + 'panel/account/user/action',
						grid: grid_user, cnt_mesage: '.user-message'
                    });
                });
                $('#user').on('click','tbody td img.confirm', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					$('#WinUser [name="id"]').val(record.id);
					$('#WinUser [name="name"]').val(record.name);
					$('#WinUser [name="fullname"]').val(record.fullname);
					$('#WinUser [name="email"]').val(record.email);
					$('#WinUser [name="address"]').val(record.address);
					$('#WinUser [name="passwd"]').val('');
					$('#WinUser [name="is_active"]').val(user_status_confirm);
                    if (! $('#WinUser form').valid()) {
                        return;
                    }
                    var param = Site.Form.GetValue('WinUser');
                    console.log(param);
                    param.action = 'update';
                    Func.ajax({ url: web.host + 'panel/account/user/action', param: param, callback: function(result) {
                        if (result.status == 1) {
                            Func.popup_result('.user-message', result.message);
                            grid_user.load();
                            } else {
                            Func.popup_error('#WinUser', result.message);
                        }
                    } });
                });
                $('#user').on('click','tbody td img.banned', function () {
					var raw = $(this).parent('td').find('.hide').text();
					eval('var record = ' + raw);
					$('#WinUser [name="id"]').val(record.id);
					$('#WinUser [name="name"]').val(record.name);
					$('#WinUser [name="fullname"]').val(record.fullname);
					$('#WinUser [name="email"]').val(record.email);
					$('#WinUser [name="address"]').val(record.address);
					$('#WinUser [name="passwd"]').val('');
					$('#WinUser [name="is_active"]').val(user_status_banned);
                    if (! $('#WinUser form').valid()) {
                        return;
                    }
                    var param = Site.Form.GetValue('WinUser');
                    console.log(param);
                    param.action = 'update';
                    Func.ajax({ url: web.host + 'panel/account/user/action', param: param, callback: function(result) {
                        if (result.status == 1) {
                            Func.popup_result('.user-message', result.message);
                            grid_user.load();
                            } else {
                            Func.popup_error('#WinUser', result.message);
                        }
                    } });
                });
            }
			init_table();
        });
    </script>
</body>
</html>