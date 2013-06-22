<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Item_model extends CI_Model {
	function __construct() {
		parent::__construct();
		
		$this->field = array( 'id', 'user_id', 'item_id' );
	}
	
	function update($param) {
		$result = array();
		
		if (empty($param['id'])) {
			$insert_query  = GenerateInsertQuery($this->field, $param, USER_ITEM);
			$insert_result = mysql_query($insert_query) or die(mysql_error());
			
			$result['id'] = mysql_insert_id();
			$result['status'] = '1';
			$result['message'] = 'Data berhasil disimpan.';
		} else {
			$update_query  = GenerateUpdateQuery($this->field, $param, USER_ITEM);
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
			$select_query  = "SELECT * FROM ".USER_ITEM." WHERE id = '".$param['id']."' LIMIT 1";
		}
		
		$select_result = mysql_query($select_query) or die(mysql_error());
		if (false !== $row = mysql_fetch_assoc($select_result)) {
			$array = $this->sync($row);
		}
		
		return $array;
	}
	
	function get_array($param = array()) {
		$array = array();
		
		$string_user = (!empty($param['user_id'])) ? "AND UserItem.user_id = '".$param['user_id']."'" : '';
		$string_item = (!empty($param['item_id'])) ? "AND UserItem.item_id = '".$param['item_id']."'" : '';
		$string_store = (!empty($param['store_id'])) ? "AND UserItem.store_id = '".$param['store_id']."'" : '';
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'item_id ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT SQL_CALC_FOUND_ROWS UserItem.*, Item.code item_code, Item.name item_name, Item.title item_title
			FROM ".USER_ITEM." UserItem
			LEFT JOIN ".ITEM." Item ON Item.id = UserItem.item_id
			WHERE 1 $string_user $string_item $string_store $string_filter
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
		if (isset($param['id'])) {
			$delete_query  = "DELETE FROM ".USER_ITEM." WHERE id = '".$param['id']."' LIMIT 1";
			$delete_result = mysql_query($delete_query) or die(mysql_error());
		}
		
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';
		
		return $result;
	}
	
	function sync($row, $column = array()) {
		$row = StripArray($row);
		
		// link item
		$row['item_link'] = site_url('item/'.$row['id']);
		
		if (count($column) > 0) {
			$row = dt_view($row, $column, array('is_edit' => 1));
		}
		
		return $row;
	}
}