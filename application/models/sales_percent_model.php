<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sales_Percent_model extends CI_Model {
	function __construct() {
		parent::__construct();
		$this->field = array('id', 'percent', 'rupiah');
	}
	
	function update($param) {
		$result = array();
		if (empty($param['id'])) {
			$insert_query  = GenerateInsertQuery($this->field, $param, SALES_PERCENT);
			$insert_result = mysql_query($insert_query) or die(mysql_error());
			
			$result['id'] = mysql_insert_id();
			$result['status'] = '1';
			$result['message'] = 'Data berhasil disimpan.';
		} else {
			$update_query  = GenerateUpdateQuery($this->field, $param, SALES_PERCENT);
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
			$select_query  = "SELECT * FROM ".SALES_PERCENT." WHERE id = '".$param['id']."' LIMIT 1";
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
		$string_sorting = GetStringSorting($param, @$param['column'], 'id ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT SQL_CALC_FOUND_ROWS SalesPercent.*
			FROM ".SALES_PERCENT." SalesPercent
			WHERE 1 $string_filter
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
	
	function get_percent($param) {
		$result = array();
		$select_query  = "SELECT * FROM ".SALES_PERCENT." WHERE rupiah <= '".$param['value']."' ORDER BY rupiah DESC LIMIT 1";
		$select_result = mysql_query($select_query) or die(mysql_error());
		if (false !== $row = mysql_fetch_assoc($select_result)) {
			$result = $this->sync($row);
		}
		
		return $result;
	}
	
	function delete($param) {
		$delete_query  = "DELETE FROM ".SALES_PERCENT." WHERE id = '".$param['id']."' LIMIT 1";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';
		
		return $result;
	}
	
	function sync($row, $param = array()) {
		$row = StripArray($row);
		$row['percent_text'] = $row['percent'].' %';
		
		if (count(@$param['column']) > 0) {
            $paramCol = @$param;
            $paramCol['is_edit'] = 1;
			$row = dt_view_set($row, $paramCol);
		}
		/*if (count($column) > 0) {
			$row = dt_view($row, $column, array('is_edit' => 1));
		}*/
		return $row;
	}
}