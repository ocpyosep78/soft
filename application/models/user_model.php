<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
    class User_model extends CI_Model {
        function __construct() {
            parent::__construct();
            
            $this->field = array('id', 'name', 'email', 'fullname', 'passwd', 'address', 'deposit','is_active');
            
            /*	User Info */
            /*	User Session
                name : user_login => array user
            /*	*/
        }
        
        function update($param) {
            $result = array();
            
            if (empty($param['id'])) {
                $insert_query  = GenerateInsertQuery($this->field, $param, USER);
                $insert_result = mysql_query($insert_query) or die(mysql_error());
                
                $result['id'] = mysql_insert_id();
                $result['status'] = '1';
                $result['message'] = 'Data berhasil disimpan.';
                } else {
                $update_query  = GenerateUpdateQuery($this->field, $param, USER);
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
                $select_query  = "SELECT * FROM ".USER." WHERE id = '".$param['id']."' LIMIT 1";
                } else if (isset($param['email'])) {
                $select_query  = "SELECT * FROM ".USER." WHERE email = '".$param['email']."' LIMIT 1";
                } else if (isset($param['name'])) {
                $select_query  = "SELECT * FROM ".USER." WHERE name = '".$param['name']."' LIMIT 1";
            }
            
            $select_result = mysql_query($select_query) or die(mysql_error());
            if (false !== $row = mysql_fetch_assoc($select_result)) {
                $array = $this->sync($row);
            }
            
            return $array;
        }
        
        function get_array($param = array()) {
            $array = array();
            
            //		$string_store = (empty($param['store_id'])) ? "" : "AND UserStore.store_id = '".$param['store_id']."'";
            $string_filter = GetStringFilter($param, @$param['column']);
            $string_sorting = GetStringSorting($param, @$param['column'], 'fullname ASC');
            $string_limit = GetStringLimit($param);
            
            $select_query = "
			SELECT SQL_CALC_FOUND_ROWS User.*
			FROM ".USER." User
			WHERE 1 $string_filter
			ORDER BY $string_sorting
			LIMIT $string_limit
            ";
            //			LEFT JOIN ".USER_STORE." UserStore ON UserStore.user_id = User.id
            //			$string_store 
            $select_result = mysql_query($select_query) or die(mysql_error());
            while ( $row = mysql_fetch_assoc( $select_result ) ) {
                //$array[] = $this->sync($row, @$param['column']);
                $array[] = $this->sync($row, $param);
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
            $delete_query  = "DELETE FROM ".USER." WHERE id = '".$param['id']."' LIMIT 1";
            $delete_result = mysql_query($delete_query) or die(mysql_error());
            
            $result['status'] = '1';
            $result['message'] = 'Data berhasil dihapus.';
            
            return $result;
        }
        
        function sync($row, $param = array()) {
            $row = StripArray($row);
            
            $param['is_edit'] = 1;
            if (in_array($row['is_active'], array(STATUS_USER_NEW))) {
                $param['is_custom']  = (isset($param['is_custom'])) ? $param['is_custom'] : '';
                $param['is_custom'] .= 
				'<img class="cursor confirm" src="'.base_url('static/img/button_confirm.png').'" style="width: 15px; height: 16px;" alt="confirm"> ' .
				'<img class="cursor banned" src="'.base_url('static/img/button_cancel.png').'" style="width: 15px; height: 16px;" alt="banned"> ';
            }elseif (in_array($row['is_active'], array(STATUS_USER_CONFIRM))) {
                $param['is_custom']  = (isset($param['is_custom'])) ? $param['is_custom'] : '';
                $param['is_custom'] .=
				'<img class="cursor banned" src="'.base_url('static/img/button_cancel.png').'" style="width: 15px; height: 16px;" alt="banned"> ';
            }elseif (in_array($row['is_active'], array(STATUS_USER_BANNED))) {
                $param['is_custom']  = (isset($param['is_custom'])) ? $param['is_custom'] : '';
                $param['is_custom'] .= 
				'<img class="cursor confirm" src="'.base_url('static/img/button_confirm.png').'" style="width: 15px; height: 16px;" alt="confirm"> ';
            }
            
            if (count(@$param['column']) > 0) {
                $row = dt_view_set($row, $param);
            }
            
            
            return $row;
        }
        
        /*	Region User Session */
        
        function login_user_required() {
            $user = $this->get_session();
            
            $valid = $this->is_login();
            if (! $valid) {
                header("Location: ".site_url('login'));
                exit;
            }
        }
        
        function is_login() {
            $user = $this->get_session();
			$is_login = (count($user) == 0) ? false : true;
            return $is_login;
        }
        
        function set_session($param) {
            $_SESSION['user_login'] = $param;
        }
        
        function get_session() {
            $user = (isset($_SESSION['user_login'])) ? $_SESSION['user_login'] : array();
            return $user;
        }
        
        function delete_session() {
            $_SESSION['user_login'] = array();
        }
        
        /*	End Region User Session */
    }    