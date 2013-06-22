<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->field = array(
			'id', 'user_id', 'item_status_id', 'name', 'description', 'price', 'thumbnail', 'filename', 'date_update'
		);
    }

    function update($param) {
        $result = array();
       
        if (empty($param['id'])) {
            $insert_query  = GenerateInsertQuery($this->field, $param, ITEM);
            $insert_result = mysql_query($insert_query) or die(mysql_error());
           
            $result['id'] = mysql_insert_id();
            $result['status'] = '1';
            $result['message'] = 'Data berhasil disimpan.';
        } else {
            $update_query  = GenerateUpdateQuery($this->field, $param, ITEM);
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
			$select_query  = "
				SELECT
					Item.*, User.fullname user_fullname, User.name user_name,
					Category.name category_name, Platform.name platform_name
				FROM ".ITEM." Item
				LEFT JOIN ".CATEGORY." Category ON Category.id = Item.category_id
				LEFT JOIN ".PLATFORM." Platform ON Platform.id = Item.platform_id
				LEFT JOIN ".USER." User ON User.id = Item.user_id
				WHERE Item.id = '".$param['id']."'
				LIMIT 1
			";
        }
       
        $select_result = mysql_query($select_query) or die(mysql_error());
        if (false !== $row = mysql_fetch_assoc($select_result)) {
            $array = $this->sync($row);
        }
		
        return $array;
    }
	
    function get_array($param = array()) {
        $array = array();
		
		$string_item_status = (!empty($param['item_status_id'])) ? "AND Item.item_status_id = '".$param['item_status_id']."'" : '';
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'Item.id DESC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT
				SQL_CALC_FOUND_ROWS Item.*, User.fullname user_fullname, User.name user_name,
				Category.name category_name, Platform.name platform_name
			FROM ".ITEM." Item
			LEFT JOIN ".CATEGORY." Category ON Category.id = Item.category_id
			LEFT JOIN ".PLATFORM." Platform ON Platform.id = Item.platform_id
			LEFT JOIN ".USER." User ON User.id = Item.user_id
			WHERE 1 $string_item_status $string_filter
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
	
	function get_url_id() {
		$request_uri = $_SERVER['REQUEST_URI'];
		
		preg_match('/\/item\/(\d+)$/i', $request_uri, $match);
		$item_id = (isset($match[1])) ? $match[1] : '';
		
		return $item_id;
	}
	
    function delete($param) {
		$delete_query  = "DELETE FROM ".ITEM." WHERE id = '".$param['id']."' LIMIT 1";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';

        return $result;
    }
	
	function sync($row, $column = array()) {
		$row = StripArray($row);
		
		// set image thumbnail
		$row['thumbnail_link'] = base_url('static/img/images.jpg');
		if (!empty($row['thumbnail'])) {
			$row['thumbnail_link'] = base_url('static/upload/'.$row['thumbnail']);
		}
		
		// item link
		$row['item_link'] = base_url('item/'.$row['id']);
		$row['item_buy_link'] = base_url('item/buy/'.$row['id']);
		
		// item file
		if (!empty($row['filename'])) {
			$row['array_filename'] = json_decode($row['filename']);
		}
		
		// user
		if (!empty($row['user_fullname'])) {
			$row['user_name'] = $row['user_fullname'];
		} else if (!empty($row['user_name'])) {
			$row['user_name'] = $row['user_name'];
		} else {
			$row['user_name'] = 'guest';
		}
		
		if (isset($row['price'])) {
			$row['price_text'] = show_price($row['price']);
		}
		
		/*
		// user dt_view_set for more improvement
		
		$p = array(
			'is_edit' => 1,
			'is_custom' => '<img class="cursor store" src="'.base_url('static/img/store.png').'" /> '
		);
		$row = dt_view($row, $column, $p);
		/*	*/
		
		return $row;
	}
}