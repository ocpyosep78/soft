<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages_model extends CI_Model {
	static $cache=array();

	function __construct() {
		parent::__construct();
		$this->field = array( 'id','title', 'name','content' );
	}
	
	function update($param) {
		self::$cache=array();
		
		$result = array();
		if (empty($param['id'])) {
			$insert_query  = GenerateInsertQuery($this->field, $param, PAGES);
			$insert_result = mysql_query($insert_query) or die(mysql_error());
			
			$result['id']       = mysql_insert_id();
			$result['status']   = '1';
			$result['message']  =  'Data berhasil disimpan.';
			} else {
			$update_query  = GenerateUpdateQuery($this->field, $param, PAGES);
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
			$select_query  = "SELECT * FROM ".PAGES." WHERE id = '".$param['id']."' LIMIT 1";
		} else if (isset($param['name'])) {
			$select_query  = "SELECT * FROM ".PAGES." WHERE name = '".$param['name']."' LIMIT 1";
		}
		
		$select_result = mysql_query($select_query) or die(mysql_error());
		if (false !== $row = mysql_fetch_assoc($select_result)) {
			$array = $this->sync($row);
		}
		
		return $array;
	}
	
	function get_array($param = array()) {
		$key=md5(json_encode($param));
		if ( !empty(self::$cache[$key]) )
			return self::$cache[$key];
			
		$array = array();
		
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'name ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT SQL_CALC_FOUND_ROWS Pages.*
			FROM ".PAGES." Pages
			WHERE 1 $string_filter
			ORDER BY $string_sorting
			LIMIT $string_limit 		
		";
       // echo $select_query;
		$select_result = mysql_query($select_query) or die(mysql_error());
		while ( $row = mysql_fetch_assoc( $select_result ) ) {
			$array[] = $this->sync($row, @$param['column']);
		}
		
		self::$cache[$key]=$array;
		
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
		self::$cache=array();
		
		$delete_query  = "DELETE FROM ".PAGES." WHERE id = '".$param['id']."' LIMIT 1";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';
		
		return $result;
	}
	
	function sync($row, $column = array()) {
		$row = StripArray($row);
		$row['content_html'] = save_tinymce($row['content']);
		
		if (count($column) > 0) {
			$row = dt_view($row, $column, array('is_edit' => 1));
		}
		
		return $row;
	}
}