<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_File_model extends CI_Model {
    function __construct() {
        parent::__construct();
		
        $this->field = array('id', 'item_id', 'file_id');
    }

    function update($param) {
        $result = array();
       
        if (empty($param['id'])) {
            $insert_query  = GenerateInsertQuery($this->field, $param, ITEM_FILE);
            $insert_result = mysql_query($insert_query) or die(mysql_error());
           
            $result['id'] = mysql_insert_id();
            $result['status'] = '1';
            $result['message'] = 'Data berhasil disimpan.';
        } else {
            $update_query  = GenerateUpdateQuery($this->field, $param, ITEM_FILE);
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
            $select_query  = "SELECT * FROM ".ITEM_FILE." WHERE id = '".$param['id']."' LIMIT 1";
        }
       
        $select_result = mysql_query($select_query) or die(mysql_error());
        if (false !== $row = mysql_fetch_assoc($select_result)) {
            $array = $this->sync($row);
        }
       
        return $array;
    }
	
    function get_array($param = array()) {
        $array = array();
		
		$string_item = (!empty($param['item_id'])) ? "AND item_id = '".$param['item_id']."'" : '';
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'file_name ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT SQL_CALC_FOUND_ROWS ItemFile.*, File.file_name
			FROM ".ITEM_FILE." ItemFile
			LEFT JOIN ".FILE." File ON File.id = ItemFile.file_id
			WHERE 1 $string_item $string_filter
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
		if (isset($param['item_id'])) {
			$delete_query  = "DELETE FROM ".ITEM_FILE." WHERE item_id = '".$param['item_id']."'";
			$delete_result = mysql_query($delete_query) or die(mysql_error());
		} else if (isset($param['id'])) {
			$delete_query  = "DELETE FROM ".ITEM_FILE." WHERE id = '".$param['id']."' LIMIT 1";
			$delete_result = mysql_query($delete_query) or die(mysql_error());
		}
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';

        return $result;
    }
	
	function sync($row, $column = array()) {
		$row = StripArray($row);
		$row['base_name'] = basename($row['file_name']);
		$row['file_link'] = site_url('download/request/'.$row['file_id']);
		
		if (count($column) > 0) {
			$row = dt_view($row, $column, array('is_edit' => 1));
		}
		
		return $row;
	}
}