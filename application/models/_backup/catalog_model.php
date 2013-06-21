<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Catalog_model extends CI_Model {
	function __construct() {
		parent::__construct();
		
		$this->field = array('id', 'store_id', 'title', 'name', 'image');
	}
	
	function update($param) {
		$result = array();
		
		if (empty($param['id'])) {
			$insert_query  = GenerateInsertQuery($this->field, $param, CATALOG);
			$insert_result = mysql_query($insert_query) or die(mysql_error());
			
			$result['id'] = mysql_insert_id();
			$result['status'] = '1';
			$result['message'] = 'Data berhasil disimpan.';
			} else {
			$update_query  = GenerateUpdateQuery($this->field, $param, CATALOG);
			$update_result = mysql_query($update_query) or die(mysql_error());
			
			$result['id'] = $param['id'];
			$result['status'] = '1';
			$result['message'] = 'Data berhasil diperbaharui.';
		}
		
		$this->resize_image($param);
		
		return $result;
	}
	
	function get_by_id($param) {
		$array = array();
		
		if (isset($param['id'])) {
			$select_query  = "SELECT * FROM ".CATALOG." WHERE id = '".$param['id']."' LIMIT 1";
		} else if (isset($param['name']) && isset($param['store_id'])) {
			$select_query  = "SELECT * FROM ".CATALOG." WHERE name = '".$param['name']."' AND store_id = '".$param['store_id']."' LIMIT 1";
		} else if (isset($param['name'])) {
			$select_query  = "SELECT * FROM ".CATALOG." WHERE name = '".$param['name']."' LIMIT 1";
		}
		
		$select_result = mysql_query($select_query) or die(mysql_error());
		if (false !== $row = mysql_fetch_assoc($select_result)) {
			$array = $this->sync($row);
		}
		
		return $array;
	}
	
	function get_selected_column($paramColumn=null, $paramWhere=null) {
		$whereClause = "";
		if($paramWhere != "" ||  $paramWhere != null)
		{
			foreach($paramWhere as $key=>$value)
			{
				$whereValue   = $key." ".$value['operator']." ".$value['value'];
				$whereClause .= ($whereClause != '')? "AND $whereValue" : " $whereValue";
			}
			$selectQuery = "SELECT ".$paramColumn." FROM ".CATALOG." Catalog  WHERE ". $whereClause ." ORDER BY id ASC ";
		}else
		{
			$selectQuery = "SELECT ".$paramColumn." FROM ".CATALOG." Catalog ORDER BY id ASC ";
		}
		$select_result = mysql_query($selectQuery) or die(mysql_error());
		if(mysql_num_rows($select_result)>0)
		{
			while($result = mysql_fetch_assoc($select_result))
			{
				$array[] = $result;
			}
			
			return $array;
		}else
		{
			return;
		}  
	}
	
	function get_array($param = array()) {
		$array = array();
		
		$string_store = (empty($param['store_id'])) ? "" : "AND Catalog.store_id = '".$param['store_id']."'";
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'title ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT SQL_CALC_FOUND_ROWS Catalog.*
			FROM ".CATALOG." Catalog
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
	
	function get_url_catalog() {
		$request_uri = $_SERVER['REQUEST_URI'];
		$request_uri = preg_replace('/\/page_(\d+)/i', '', $request_uri);
		
		// matching catalog
		preg_match('/catalog\/([a-z0-9\_]+)/i', $request_uri, $match);
		$catalog_name = (isset($match[1])) ? $match[1] : '';
		
		return $catalog_name;
	}
	
	function get_url_category() {
		$request_uri = $_SERVER['REQUEST_URI'];
		$request_uri = preg_replace('/\/page_(\d+)/i', '', $request_uri);
		
		// matching category
		preg_match('/catalog\/[a-z0-9\_]+\/([a-z0-9\_]+)$/i', $request_uri, $match);
		$category_name = (isset($match[1])) ? $match[1] : '';
		
		return $category_name;
	}
	
	function delete($param) {
		$delete_query  = "DELETE FROM ".CATALOG." WHERE id = '".$param['id']."' LIMIT 1";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';
		
		return $result;
	}
	
	function sync($row, $column = array()) {
		$row = StripArray($row);
		$row['catalog_link'] = site_url('catalog/'.$row['name']);
		
		$row['image_link'] = base_url('static/img/images.jpg');
		if (!empty($row['image'])) {
			$row['image_s'] = preg_replace('/\.(jpg|jpeg|png|gif)/i', '_s.$1', $row['image']);
			$row['image_link'] = base_url('static/upload/'.$row['image_s']);
		}
		
		if (count($column) > 0) {
			$row = dt_view($row, $column, array('is_edit' => 1));
		}
		
		return $row;
	}
	
	function resize_image($param) {
		if (!empty($param['image'])) {
			$image_path = $this->config->item('base_path') . '/static/upload/';
			$image_source = $image_path . $param['image'];
			
			$image_result = preg_replace('/\.(jpg|jpeg|png|gif)/i', '_s.$1', $param['image']);
			$image_result_path = $image_path . $image_result;
			
			ImageResize($image_source, $image_result_path, 294, 294, 1);
		}
	}
}