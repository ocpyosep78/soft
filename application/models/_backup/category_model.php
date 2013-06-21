<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category_model extends CI_Model {
	function __construct() {
		parent::__construct();
		
		$this->field = array('id', 'store_id', 'parent_id', 'name', 'title');
	}
	
	function update($param) {
		$result = array();
		if (empty($param['id'])) {
			$insert_query  = GenerateInsertQuery($this->field, $param, CATEGORY);
			$insert_result = mysql_query($insert_query) or die(mysql_error());
			
			$result['id']       = mysql_insert_id();
			$result['status']   = '1';
			$result['message']  =  'Data berhasil disimpan.';
			} else {
			$update_query  = GenerateUpdateQuery($this->field, $param, CATEGORY);
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
			$select_query  = "SELECT * FROM ".CATEGORY." WHERE id = '".$param['id']."' LIMIT 1";
		} else if (isset($param['name']) && isset($param['store_id'])) {
			$select_query  = "SELECT * FROM ".CATEGORY." WHERE name = '".$param['name']."' AND store_id = '".$param['store_id']."' LIMIT 1";
		} else if (isset($param['name'])) {
			$select_query  = "SELECT * FROM ".CATEGORY." WHERE name = '".$param['name']."' LIMIT 1";
		}
		
		$select_result = mysql_query($select_query) or die(mysql_error());
		if (false !== $row = mysql_fetch_assoc($select_result)) {
			$array = $this->sync($row);
		}
		
		return $array;
	}
	
	function get_array($param = array()) {
		$array = array();
		
		$string_store = (empty($param['store_id'])) ? "" : "AND Category.store_id = '".$param['store_id']."'";
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'title ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT SQL_CALC_FOUND_ROWS Category.*
			FROM ".CATEGORY." Category
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
	
	function get_array_category($param) {
		$array = array();
		
		if (!empty($param['catelog_name'])) {
			$select_query = "
				SELECT Category.title, Category.name category_name, Catalog.name catalog_name
				FROM ".CATEGORY." Category
				LEFT JOIN ".ITEM_CATEGORY." ItemCategory ON ItemCategory.category_id = Category.id
				LEFT JOIN ".ITEM." Item ON Item.id = ItemCategory.item_id
				LEFT JOIN ".ITEM_CATALOG." ItemCatalog ON ItemCatalog.item_id = Item.id
				LEFT JOIN ".CATALOG." Catalog ON Catalog.id = ItemCatalog.catalog_id
				WHERE Catalog.name = '".$param['catelog_name']."'
					AND Catalog.store_id = '".$param['store_id']."'
				GROUP BY Category.title, Category.name, Catalog.name
				ORDER BY Category.title
				LIMIT 0,25
			";
		}
		$select_result = mysql_query($select_query) or die(mysql_error());
		while ( $row = mysql_fetch_assoc( $select_result ) ) {
			$array[] = $this->sync($row);
		}
		
		return $array;
	}
	
	function delete($param) {
		$delete_query  = "DELETE FROM ".CATEGORY." WHERE id = '".$param['id']."' LIMIT 1";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';
		
		return $result;
	}
	
	function sync($row, $column = array()) {
		$row = StripArray($row);
		
		// catalog
		if (!empty($row['catalog_name'])) {
			$row['catalog_link'] = site_url('catalog/'.$row['catalog_name']);
		}
		
		// catalog + category
		if (!empty($row['catalog_name']) && !empty($row['category_name'])) {
			$row['category_link'] = site_url('catalog/'.$row['catalog_name'].'/'.$row['category_name']);
		}
		
		if (count($column) > 0) {
			$row = dt_view($row, $column, array('is_edit' => 1));
		}
		
		return $row;
	}
}