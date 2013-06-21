<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Payment_model extends CI_Model {
	function __construct() {
		parent::__construct();
		
		$this->field = array( 'id', 'user_id', 'value', 'note', 'status', 'payment_date' );
	}
	
	function update($param) {
		$result = array();
		
		if (empty($param['id'])) {
			$insert_query  = GenerateInsertQuery($this->field, $param, USER_PAYMENT);
			$insert_result = mysql_query($insert_query) or die(mysql_error());
			
			$result['id'] = mysql_insert_id();
			$result['status'] = '1';
			$result['message'] = 'Data berhasil disimpan.';
		} else {
			$update_query  = GenerateUpdateQuery($this->field, $param, USER_PAYMENT);
			$update_result = mysql_query($update_query) or die(mysql_error());
			
			$result['id'] = $param['id'];
			$result['status'] = '1';
			$result['message'] = 'Data berhasil diperbaharui.';
		}
		
		return $result;
	}
	
	function get_by_id($param) {
		$array = array();
		
		if (isset($param['id'])) {
			$select_query  = "SELECT * FROM ".USER_PAYMENT." WHERE id = '".$param['id']."' LIMIT 1";
		}
		
		$select_result = mysql_query($select_query) or die(mysql_error());
		if (false !== $row = mysql_fetch_assoc($select_result)) {
			$array = $this->sync($row);
		}
		
		return $array;
	}
	
	function get_array($param = array()) {
		$array = array();
		
		// overwrite field name
		$param['field_replace']['email'] = 'User.email';
		$param['field_replace']['fullname'] = 'User.fullname';
		
		$string_user = (!empty($param['user_id'])) ? "AND UserPayment.user_id = '".$param['user_id']."'" : '';
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'bank_id ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT SQL_CALC_FOUND_ROWS UserPayment.*, User.email, User.fullname 	
			FROM ".USER_PAYMENT." UserPayment
			LEFT JOIN ".USER." User ON User.id = UserPayment.user_id
			WHERE 1 $string_user $string_filter
			ORDER BY $string_sorting
			LIMIT $string_limit
		";
		$select_result = mysql_query($select_query) or die(mysql_error());
		while ( $row = mysql_fetch_assoc( $select_result ) ) {
			$array[] = $this->sync($row, @$param['column']);
		}
		
		return $array;
	}
	
	function get_count($param = array()) {
		$select_query = "SELECT FOUND_ROWS() TotalRecord";
		$select_result = mysql_query($select_query) or die(mysql_error());
		$row = mysql_fetch_assoc($select_result);
		$TotalRecord = $row['TotalRecord'];
		
		return $TotalRecord;
	}
	
	function delete($param) {
		$delete_query  = "DELETE FROM ".USER_PAYMENT." WHERE id = '".$param['id']."' LIMIT 1";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';
		
		return $result;
	}
	
	function sync($row, $column = array()) {
		$row = StripArray($row);
		
		if (count($column) > 0) {
			$row['status_nota_id'] = 0;
			
			if ($row['status'] == 'pending') {
				$param = array( 'is_delete' => 1 );
				$param['is_custom'] = 
					'<img class="cursor confirm" src="'.base_url('static/img/button_confirm.png').'" style="width: 15px; height: 16px;"> ' .
					'<img class="cursor cancel" src="'.base_url('static/img/button_cancel.png').'" style="width: 15px; height: 16px;"> ';
			} else {
				$param['is_custom'] = '&nbsp;';
			}
			
			$row = dt_view($row, $column, $param);
		}
		
		return $row;
	}
}