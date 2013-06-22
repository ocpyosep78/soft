<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->field = array(
			'id', 'user_id', 'item_status_id', 'name', 'description', 'price', 'thumbnail', 'filename'
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
				SELECT Item.*, ItemPrice.price, Currency.name currency_name,
					Catalog.id catalog_id, Catalog.name catalog_name, Catalog.title catalog_title,
					Category.name category_name, Category.title category_title
				FROM ".ITEM." Item
				LEFT JOIN ".ITEM_PRICE." ItemPrice ON ItemPrice.item_id = Item.id
				LEFT JOIN ".ITEM_CATALOG." ItemCatalog ON ItemCatalog.item_id = Item.id
				LEFT JOIN ".CATALOG." Catalog ON Catalog.id = ItemCatalog.catalog_id
				LEFT JOIN ".ITEM_CATEGORY." ItemCategory ON ItemCategory.item_id = Item.id
				LEFT JOIN ".CATEGORY." Category ON Category.id = ItemCategory.category_id
				LEFT JOIN ".CURRENCY." Currency ON Currency.id = ItemPrice.currency_id
				WHERE Item.id = '".$param['id']."'
				LIMIT 1
			";
        }
       
        $select_result = mysql_query($select_query) or die(mysql_error());
        if (false !== $row = mysql_fetch_assoc($select_result)) {
            $array = $this->sync($row);
			
			// add catalog & category
			$param = array(
				'filter' => '[' .
					'{"type":"numeric","comparison":"eq","value":"'.$array['id'].'","field":"item_id"},' .
					'{"type":"numeric","comparison":"eq","value":"'.$array['store_id'].'","field":"store_id"}' .
				']'
			);
			$array['array_catalog'] = $this->Item_Catalog_model->get_array($param);
			$array['array_category'] = $this->Item_Category_model->get_array($param);
			$array['array_picture'] = $this->Item_Picture_model->get_array(array('item_id' => $array['id']));
			$array['array_file'] = $this->Item_File_model->get_array(array('item_id' => $array['id']));
        }
		
        return $array;
    }
	
    function get_array($param = array()) {
        $array = array();
		
		$string_store = (!empty($param['store_id'])) ? "AND Item.store_id = '".$param['store_id']."'" : '';
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'Item.update_date ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT
				SQL_CALC_FOUND_ROWS Item.*,
				ItemPrice.price, Currency.name currency_name
			FROM ".ITEM." Item
			LEFT JOIN ".ITEM_PRICE." ItemPrice ON ItemPrice.item_id = Item.id
			LEFT JOIN ".ITEM_CATALOG." ItemCatalog ON ItemCatalog.item_id = Item.id
			LEFT JOIN ".ITEM_CATEGORY." ItemCategory ON ItemCategory.item_id = Item.id
			LEFT JOIN ".CURRENCY." Currency ON Currency.id = ItemPrice.currency_id
			WHERE 1 $string_store $string_filter
			GROUP BY
				Item.id, Item.store_id, Item.item_status_id, Item.code, Item.name, Item.title, Item.description, Item.stock, Item.stock_min, Item.tax, Item.discount, Item.thumbnail, Item.update_date,
				price, currency_name
			ORDER BY $string_sorting
			LIMIT $string_limit
		";
        $select_result = mysql_query($select_query) or die(mysql_error());
		while ( $row = mysql_fetch_assoc( $select_result ) ) {
			$array[] = $this->sync($row, @$param['column']);
		}
		
		if (!empty($param['add_detail'])) {
			$array = $this->get_item_detail($array);
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
		$row['item_link'] = site_url('item/'.$row['id']);
		
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