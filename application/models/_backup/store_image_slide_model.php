<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Store_Image_Slide_model extends CI_Model {
    function __construct() {
        parent::__construct();
		
        $this->field = array('id', 'store_id', 'title', 'content', 'image', 'active');
    }

    function update($param) {
        $result = array();
       
        if (empty($param['id'])) {
            $insert_query  = GenerateInsertQuery($this->field, $param, STORE_IMAGE_SLIDE);
            $insert_result = mysql_query($insert_query) or die(mysql_error());
           
            $result['id'] = mysql_insert_id();
            $result['status'] = '1';
            $result['message'] = 'Data berhasil disimpan.';
        } else {
            $update_query  = GenerateUpdateQuery($this->field, $param, STORE_IMAGE_SLIDE);
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
            $select_query  = "SELECT * FROM ".STORE_IMAGE_SLIDE." WHERE id = '".$param['id']."' LIMIT 1";
        }
       
        $select_result = mysql_query($select_query) or die(mysql_error());
        if (false !== $row = mysql_fetch_assoc($select_result)) {
            $array = $this->sync($row);
        }
       
        return $array;
    }
	
    function get_array($param = array()) {
        $array = array();
		$string_store = (empty($param['store_id'])) ? "" : "AND StoreImageSlide.store_id = '".$param['store_id']."'";
		// replace field
		$param['field_replace']['active_text'] = '';
		
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'title ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT SQL_CALC_FOUND_ROWS *
			FROM ".STORE_IMAGE_SLIDE." StoreImageSlide
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
	
    function delete($param) {
		$delete_query  = "DELETE FROM ".STORE_IMAGE_SLIDE." WHERE id = '".$param['id']."' LIMIT 1";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
		
		if (!empty($_POST['image'])) {
			$file_name = $this->config->item('base_path').'/static/upload/'.$_POST['image'];
			@unlink($file_name);
		}
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';

        return $result;
    }
	
	function sync($row, $column = array()) {
		$row = StripArray($row);
		$row['active_text'] = (empty($row['active'])) ? 'Inactive' : 'Active';
		
		$row['image_link'] = base_url('static/img/image_slide.jpg');
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
			
			ImageResize($image_source, $image_result_path, 944, 324, 1);
		}
	}
}