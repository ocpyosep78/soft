<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog_model extends CI_Model {
    function __construct() {
        parent::__construct();
		
        $this->field = array('id', 'store_id', 'blog_status_id', 'name', 'title', 'content', 'page_view', 'create_date');
    }

    function update($param) {
        $result = array();
       
        if (empty($param['id'])) {
            $insert_query  = GenerateInsertQuery($this->field, $param, BLOG);
            $insert_result = mysql_query($insert_query) or die(mysql_error());
           
            $result['id'] = mysql_insert_id();
            $result['status'] = '1';
            $result['message'] = 'Data berhasil disimpan.';
        } else {
            $update_query  = GenerateUpdateQuery($this->field, $param, BLOG);
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
            $select_query  = "SELECT * FROM ".BLOG." WHERE id = '".$param['id']."' LIMIT 1";
        } else if (isset($param['name'])) {
            $select_query  = "SELECT * FROM ".BLOG." WHERE name = '".$param['name']."' LIMIT 1";
        }
       
        $select_result = mysql_query($select_query) or die(mysql_error());
        if (false !== $row = mysql_fetch_assoc($select_result)) {
            $array = $this->sync($row);
        }
       
        return $array;
    }
	
    function get_array($param = array()) {
        $array = array();
		$string_store = (empty($param['store_id'])) ? "" : "AND Blog.store_id = '".$param['store_id']."'";
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'title ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT SQL_CALC_FOUND_ROWS Blog.*, BlogStatus.name blog_status_name
			FROM ".BLOG." Blog
			LEFT JOIN ".BLOG_STATUS." BlogStatus ON BlogStatus.id = Blog.blog_status_id
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
	
	function get_url_blog_name() {
		$request_uri = $_SERVER['REQUEST_URI'];
		
		preg_match('/\/blog\/([a-z0-9\_]+)$/i', $request_uri, $match);
		$blog_name = (isset($match[1])) ? $match[1] : '';
		
		return $blog_name;
	}
	
    function delete($param) {
		$delete_query  = "DELETE FROM ".BLOG." WHERE id = '".$param['id']."' LIMIT 1";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';

        return $result;
    }
	
	function sync($row, $column = array()) {
		$row = StripArray($row);
		$row['blog_link'] = site_url('blog/'.$row['name']);
		$row['content_html'] = save_tinymce($row['content']);
		
		if (count($column) > 0) {
			$row = dt_view($row, $column, array('is_edit' => 1));
		}
		
		return $row;
	}
}