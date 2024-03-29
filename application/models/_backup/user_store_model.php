<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Store_model extends CI_Model {
	function __construct() {
		parent::__construct();
		
		$this->field = array( 'id', 'store_id', 'user_id' );
	}
	
	function update($param) {
		$result = array();
		
		if (empty($param['id'])) {
			$insert_query  = GenerateInsertQuery($this->field, $param, USER_STORE);
			$insert_result = mysql_query($insert_query) or die(mysql_error());
			
			$result['id'] = mysql_insert_id();
			$result['status'] = '1';
			$result['message'] = 'Data berhasil disimpan.';
		} else {
			$update_query  = GenerateUpdateQuery($this->field, $param, USER_STORE);
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
			$select_query  = "SELECT * FROM ".USER_STORE." WHERE id = '".$param['id']."' LIMIT 1";
		} else if (isset($param['store_id'])) {
			$select_query  = "SELECT * FROM ".USER_STORE." WHERE store_id = '".$param['store_id']."' ORDER BY user_id ASC LIMIT 1";
		}
		
		$select_result = mysql_query($select_query) or die(mysql_error());
		if (false !== $row = mysql_fetch_assoc($select_result)) {
			$array = $this->sync($row);
		}
		
		return $array;
	}
	
	function get_array($param = array()) {
		$array = array();
		
		$string_user = (empty($param['user_id'])) ? "" : "AND UserStore.user_id = '".$param['user_id']."'";
		$string_store = (empty($param['store_id'])) ? "" : "AND UserStore.store_id = '".$param['store_id']."'";
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'Store.title ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT SQL_CALC_FOUND_ROWS
				Store.theme_id, Store.name, Store.title, Store.domain, Store.option,
				UserStore.id, UserStore.store_id, UserStore.user_id
			FROM ".USER_STORE." UserStore
			LEFT JOIN ".STORE." Store ON Store.id = UserStore.store_id
			WHERE 1 $string_user $string_store $string_filter
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
		$delete_query  = "DELETE FROM ".USER_STORE." WHERE id = '".$param['id']."' LIMIT 1";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';
		
		return $result;
	}
	
	function sync($row, $column = array()) {
		$row = StripArray($row);
		
		if (isset($row['domain']))
			$row['domain_panel'] = $row['domain'].'panel';
		
		if (count($column) > 0) {
			$row = dt_view($row, $column, array('is_delete' => 1));
		}
		
		return $row;
	}
}