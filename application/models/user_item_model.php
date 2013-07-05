<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Item_model extends CI_Model {
	function __construct() {
		parent::__construct();
		$this->field = array( 'id', 'user_id', 'item_id', 'price', 'invoice_no', 'payment_name', 'payment_date', 'konversi', 'currency', 'terbayar', 'ref_id' );
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
		} else if (isset($param['user_id']) && isset($param['item_id'])) {
			$select_query  = "SELECT * FROM ".USER_ITEM." WHERE user_id = '".$param['user_id']."' AND item_id = '".$param['item_id']."' LIMIT 1";
		} else if (isset($param['invoice_no'])) {
			$select_query  = "
				SELECT UserItem.*, Item.name item_name, User.fullname user_fullname, User.name user_name
				FROM ".USER_ITEM." UserItem
				LEFT JOIN ".ITEM." Item ON Item.id = UserItem.item_id
				LEFT JOIN ".USER." User ON User.id = UserItem.user_id
				WHERE UserItem.invoice_no = '".$param['invoice_no']."'
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
		
		$string_item_user = (!empty($param['item_user_id'])) ? "AND Item.user_id = '".$param['item_user_id']."'" : '';
		$string_user = (!empty($param['user_id'])) ? "AND UserItem.user_id = '".$param['user_id']."'" : '';
		$string_item = (!empty($param['item_id'])) ? "AND UserItem.item_id = '".$param['item_id']."'" : '';
		$string_date_start = (!empty($param['date_start'])) ? "AND DATE(UserItem.payment_date) >= '".$param['date_start']."'" : '';
		$string_date_end   = (!empty($param['date_end'])) ? "AND DATE(UserItem.payment_date) <= '".$param['date_end']."'" : '';
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'item_id ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT SQL_CALC_FOUND_ROWS UserItem.*, Item.name item_name, Item.description item_description,
				Author.name user_name, Category.name category_name, Category.id category_id
			FROM ".USER_ITEM." UserItem
			LEFT JOIN ".ITEM." Item ON Item.id = UserItem.item_id
			LEFT JOIN ".USER." Author ON Author.id = Item.user_id
			LEFT JOIN ".CATEGORY." Category ON Category.id = Item.category_id
			WHERE 1 $string_item_user $string_user $string_item $string_date_start $string_date_end $string_filter
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
	
	function get_max_no() {
		$select_query = "SELECT MAX(invoice_no) invoice_no FROM ".USER_ITEM."";
		$select_result = mysql_query($select_query) or die(mysql_error());
		$row = mysql_fetch_assoc($select_result);
		$invoice_no = $row['invoice_no'];
		$invoice_no = (empty($invoice_no)) ? 1 : $invoice_no + 1;
		
		return $invoice_no;
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
		
		if (!empty($row['price'])) {
			$row['price_text'] = show_price($row['price']);
		}
		
		// link item
		$row['item_link'] = base_url('item/'.$row['item_id']);
		if (isset($row['category_id'])) {
			$row['category_link'] = base_url('browse/category/'.$row['category_id']);
		}
		
		// link invoice
		if (isset($row['invoice_no'])) {
			$row['invoice_link'] = base_url('item/invoice/'.$row['invoice_no']);
		}
		
		// link author
		$row['author_link'] = '';
		if (!empty($row['user_name'])) {
			$row['author_link'] = base_url('author/'.$row['user_name']);
		}
		
		// user
		if (!empty($row['user_fullname'])) {
			$row['user_name'] = $row['user_fullname'];
		} else if (!empty($row['user_name'])) {
			$row['user_name'] = $row['user_name'];
		} else {
			$row['user_name'] = 'guest';
		}
		
		if (count($column) > 0) {
			$row = dt_view($row, $column, array('is_edit' => 1));
		}
		
		return $row;
	}
	
	function is_buy($param) {
		$array = $this->get_by_id($param);
		$result = (count($array) > 0) ? true : false;
		return $result;
	}
}