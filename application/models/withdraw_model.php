<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Withdraw_model extends CI_Model {
	function __construct() {
		parent::__construct();
		$this->field = array( 'id', 'user_id', 'request_datetime', 'last_user_item_id', 'amout_rp', 'amount_idr', 'prosentase', 'currency', 'status' );
	}
	
	function update($param) {
		$result = array();
		if (empty($param['id'])) {
			$insert_query  = GenerateInsertQuery($this->field, $param, WITHDRAW);
			$insert_result = mysql_query($insert_query) or die(mysql_error());
			
			$result['id']       = mysql_insert_id();
			$result['status']   = '1';
			$result['message']  =  'Data berhasil disimpan.';
		} else {
			$update_query  = GenerateUpdateQuery($this->field, $param, WITHDRAW);
			$update_result = mysql_query($update_query) or die(mysql_error());
			
			$result['id']       = $param['id'];
			$result['status']   = '1';
			$result['message']  = 'Data berhasil diperbaharui.';
		}
		
		return $result;
	}
	
	function get_by_id($param) {
		$array = array();
		
		if (isset($param['id'])) {
			$select_query  = "SELECT * FROM ".WITHDRAW." WHERE id = '".$param['id']."' LIMIT 1";
		}
		
		$select_result = mysql_query($select_query) or die(mysql_error());
		if (false !== $row = mysql_fetch_assoc($select_result)) {
			$array = $this->sync($row);
		}
		
		return $array;
	}
	
	function get_array($param = array()) {
		$array = array();
		
		$param['field_replace']['user_name'] = 'User.name';
		$param['field_replace']['profit'] = 'Withdraw.amount_idr';
		
		$string_user = (empty($param['user_id'])) ? '' : "AND Withdraw.user_id = '".$param['user_id']."'";
		$string_status = (empty($param['status'])) ? '' : "AND Withdraw.status = '".$param['status']."'";
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'name ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT SQL_CALC_FOUND_ROWS Withdraw.*, User.name user_name
			FROM ".WITHDRAW." Withdraw
			LEFT JOIN ".USER." User ON User.id = Withdraw.user_id
			WHERE 1 $string_status $string_user $string_filter
			ORDER BY $string_sorting
			LIMIT $string_limit 		
		";
		$select_result = mysql_query($select_query) or die(mysql_error());
		while ( $row = mysql_fetch_assoc( $select_result ) ) {
			$array[] = $this->sync($row, $param);
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
	
	function get_last_record($param) {
		$array = array();
		$select_query  = "SELECT * FROM ".WITHDRAW." WHERE user_id = '".$param['user_id']."' ORDER BY request_datetime DESC LIMIT 1";
		$select_result = mysql_query($select_query) or die(mysql_error());
		if (false !== $row = mysql_fetch_assoc($select_result)) {
			$array = $this->sync($row);
		}
		
		return $array;
	}
	
	function delete($param) {
		$delete_query  = "DELETE FROM ".WITHDRAW." WHERE id = '".$param['id']."' LIMIT 1";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';
		
		return $result;
	}
	
	function sync($row, $param = array()) {
		$row = StripArray($row);
		$row['profit'] = ($row['prosentase'] * ($row['amout_rp'] + $row['amount_idr'])) / 100;
		
		$param['is_custom'] = (empty($param['is_custom'])) ? '&nbsp;' : $param['is_custom'];
		if ($row['status'] == 'pending') {
			$param['is_custom'] .= '<img class="cursor confirm" src="'.base_url('static/img/button_confirm.png').'" style="width: 15px; height: 16px;">  ';
			$param['is_custom'] .= '<img class="cursor delete" src="'.base_url('static/img/button_delete.png').'" style="width: 15px; height: 16px;">  ';
		}
		
		if (count(@$param['column']) > 0) {
			$row = dt_view_set($row, $param);
		}
		return $row;
	}
}