<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Checkout_Data_model extends CI_Model {
	function __construct() {
		parent::__construct();
		$this->field = array( 'id', 'tanggal', 'data' );
	}
	
	function update($param) {
		$result = array();
		if (empty($param['id'])) {
			$insert_query  = GenerateInsertQuery($this->field, $param, CHECKOUT_DATA);
			$insert_result = mysql_query($insert_query) or die(mysql_error());
			
			$result['id']       = mysql_insert_id();
			$result['status']   = '1';
			$result['message']  =  'Data berhasil disimpan.';
			} else {
			$update_query  = GenerateUpdateQuery($this->field, $param, CHECKOUT_DATA);
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
			$select_query  = "SELECT * FROM ".CHECKOUT_DATA." WHERE id = '".$param['id']."' LIMIT 1";
		}
		
		$select_result = mysql_query($select_query) or die(mysql_error());
		if (false !== $row = mysql_fetch_assoc($select_result)) {
			$array = $this->sync($row);
		}
		
		return $array;
	}
	
	function get_array($param = array()) {
		$array = array();
		
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'name ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT SQL_CALC_FOUND_ROWS CheckoutData.*
			FROM ".CHECKOUT_DATA." CheckoutData
			WHERE 1 $string_filter
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
	
	function get_session() {
		$id = empty($_SESSION['checkout_id']) ? 0 : $_SESSION['checkout_id'];
		$row = $this->get_by_id(array( 'id' => $id ));
		
		return $row;
	}
	
	function delete($param) {
		$delete_query  = "DELETE FROM ".CHECKOUT_DATA." WHERE id = '".$param['id']."' LIMIT 1";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';
		
		return $result;
	}
	
	function sync($row, $column = array()) {
		$row = StripArray($row);
		$row['detail'] = json_decode($row['data']);
		
		return $row;
	}
}