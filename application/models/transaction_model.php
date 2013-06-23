<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transaction_model extends CI_Model {
    function __construct() {
        parent::__construct();
		
        $this->field = array('id', 'item_id', 'nota_id', 'quantity', 'tax', 'discount', 'price', 'price_final', 'currency', 'deposit', 'total');
    }
	
    function update($param) {
        $result = array();
       
        if (empty($param['id'])) {
            $insert_query  = GenerateInsertQuery($this->field, $param, TRANSACTION);
            $insert_result = mysql_query($insert_query) or die(mysql_error());
           
            $result['id'] = mysql_insert_id();
            $result['status'] = '1';
            $result['message'] = 'Data berhasil disimpan.';
        } else {
            $update_query  = GenerateUpdateQuery($this->field, $param, TRANSACTION);
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
            $select_query  = "SELECT * FROM ".TRANSACTION." WHERE id = '".$param['id']."' LIMIT 1";
        }
       
        $select_result = mysql_query($select_query) or die(mysql_error());
        if (false !== $row = mysql_fetch_assoc($select_result)) {
            $array = $this->sync($row);
        }
       
        return $array;
    }
	
    function get_array($param = array()) {
        $array = array();
		
		$string_nota = (!empty($param['nota_id'])) ? "AND Transaction.nota_id = '".$param['nota_id']."'" : '';
        $string_store_nota = (empty($param['store_id'])) ? "" : "AND Nota.store_id = '".$param['store_id']."'";
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'nota_id ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT SQL_CALC_FOUND_ROWS Transaction.*, Item.title, Item.thumbnail
			FROM ".TRANSACTION." Transaction
			LEFT JOIN ".ITEM." Item ON Item.id = Transaction.item_id
            LEFT JOIN ".NOTA." Nota ON Nota.id = Transaction.nota_id
			WHERE 1 $string_store_nota $string_nota $string_filter
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
		$delete_query  = "DELETE FROM ".TRANSACTION." WHERE id = '".$param['id']."' LIMIT 1";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';

        return $result;
    }
	
	function sync($row, $column = array()) {
		$row = StripArray($row);
		
		// currency total
		if (isset($row['currency']) && isset($row['total'])) {
			$row['currency_total'] = $row['currency'].' '.$row['total'];
		}
		
		// set image thumbnail
		$row['thumbnail_link'] = base_url('static/img/images.jpg');
		if (!empty($row['thumbnail'])) {
			$row['thumbnail_link'] = base_url('static/upload/'.$row['thumbnail']);
		}
		
		// item link
		$row['item_link'] = site_url('item/'.$row['item_id']);
		
		if (count($column) > 0) {
			$row = dt_view($row, $column, array('is_detail' => 1));
		}
		
		return $row;
	}
}