<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Store_Detail_model extends CI_Model {
    function __construct() {
        parent::__construct();
		
        $this->field = array('id', 'store_id', 'name', 'title', 'content');
    }

    function update($param) {
        $result = array();
       
        if (empty($param['id'])) {
            $insert_query  = GenerateInsertQuery($this->field, $param, STORE_DETAIL);
            $insert_result = mysql_query($insert_query) or die(mysql_error());
           
            $result['id'] = mysql_insert_id();
            $result['status'] = '1';
            $result['message'] = 'Data berhasil disimpan.';
        } else {
            $update_query  = GenerateUpdateQuery($this->field, $param, STORE_DETAIL);
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
            $select_query  = "SELECT * FROM ".STORE_DETAIL." WHERE id = '".$param['id']."' LIMIT 1";
        }
       
        $select_result = mysql_query($select_query) or die(mysql_error());
        if (false !== $row = mysql_fetch_assoc($select_result)) {
            $array = $this->sync($row);
        }
       
        return $array;
    }
	
    function get_array($param = array()) {
        $array = array();
		
		$string_store = (empty($param['store_id'])) ? "" : "AND StoreDetail.store_id = '".$param['store_id']."'";
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'title ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT SQL_CALC_FOUND_ROWS *
			FROM ".STORE_DETAIL." StoreDetail
			WHERE 1 $string_store $string_filter
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
	
	function get_info($param = array()) {
        $array = array();
       
        if (isset($param['store_name'])) {
            $select_query  = "
				SELECT StoreDetail.*, Store.title store_title
				FROM ".STORE_DETAIL." StoreDetail
				LEFT JOIN ".STORE." Store ON Store.id = StoreDetail.store_id
				WHERE Store.name = '".$param['store_name']."'
				ORDER BY title ASC
			";
        }
		
        $select_result = mysql_query($select_query) or die(mysql_error());
        while (false !== $row = mysql_fetch_assoc($select_result)) {
			$row = $this->sync($row);
            $array[$row['name']] = $row;
			
			$store_temp = $row;
        }
		
		// add store id
		$array['store_id'] = $store_temp['store_id'];
		$array['store_title'] = $store_temp['store_title'];
		
		// remove tags
		$array['store_logo']['content'] = strip_tags($array['store_logo']['content']);
		
        return $array;
	}
	
    function delete($param) {
		$delete_query  = "DELETE FROM ".STORE_DETAIL." WHERE id = '".$param['id']."' LIMIT 1";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';

        return $result;
    }
	
	function sync($row, $column = array()) {
		$row = StripArray($row);
		$row['content_html'] = save_tinymce($row['content']);
		
		if (count($column) > 0) {
			$row = dt_view($row, $column, array('is_edit_only' => 1));
		}
		
		return $row;
	}
}