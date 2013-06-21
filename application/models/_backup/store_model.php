<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
    class Store_model extends CI_Model {
        function __construct() {
            parent::__construct();
            $this->field = array('id', 'theme_id', 'name', 'title', 'domain', 'option');
        }
        
        function update($param) {
            $result = array();
            if (empty($param['id'])) {
                $insert_query  = GenerateInsertQuery($this->field, $param, STORE);
                $insert_result = mysql_query($insert_query) or die(mysql_error());
                
                $result['id'] = mysql_insert_id();
                $result['status']   = '1';
                $result['message']  = 'Data berhasil disimpan.';
                } else {
                $update_query  = GenerateUpdateQuery($this->field, $param, STORE);
                $update_result = mysql_query($update_query) or die(mysql_error());
                
                $result['id'] = $param['id'];
                $result['status'] = '1';
                $result['message'] = 'Data berhasil diperbaharui.';
            }
            
            return $result;
        }
        
        function get_by_id($param) {
            $array = array();
            
			if (isset($param['user_id']) && isset($param['store_name'])) {
                $select_query  = "
					SELECT Store.*, UserStore.*, User.name user_name, User.email user_email
					FROM ".STORE." Store
					LEFT JOIN ".USER_STORE." UserStore ON UserStore.store_id = Store.id
					LEFT JOIN ".USER." User ON User.id = UserStore.user_id
					WHERE
						UserStore.user_id = '".$param['user_id']."'
						AND Store.name = '".$param['store_name']."'
					LIMIT 1
				";
            } else if (isset($param['id'])) {
                $select_query  = "SELECT * FROM ".STORE." WHERE id = '".$param['id']."' LIMIT 1";
			} else if (isset($param['name'])) {
                $select_query  = "
					SELECT Store.*, UserStore.*, User.name user_name, User.email user_email
					FROM ".STORE." Store
					LEFT JOIN ".USER_STORE." UserStore ON UserStore.store_id = Store.id
					LEFT JOIN ".USER." User ON User.id = UserStore.user_id
					WHERE Store.name = '".$param['name']."'
					LIMIT 1
				";
			} else if (isset($param['user_id'])) {
                $select_query  = "
					SELECT Store.*, UserStore.*, User.name user_name, User.email user_email
					FROM ".STORE." Store
					LEFT JOIN ".USER_STORE." UserStore ON UserStore.store_id = Store.id
					LEFT JOIN ".USER." User ON User.id = UserStore.user_id
					WHERE UserStore.user_id = '".$param['user_id']."'
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
            
			$string_store = (empty($param['store_id'])) ? "" : "AND Store.id = '".$param['store_id']."'";
            $string_filter = GetStringFilter($param, @$param['column']);
            $string_sorting = GetStringSorting($param, @$param['column'], 'title ASC');
            $string_limit = GetStringLimit($param);
            
            $select_query = "
				SELECT SQL_CALC_FOUND_ROWS *
				FROM ".STORE." Store
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
            $delete_query  = "DELETE FROM ".STORE." WHERE id = '".$param['id']."' LIMIT 1";
            $delete_result = mysql_query($delete_query) or die(mysql_error());
            
            $result['status'] = '1';
            $result['message'] = 'Data berhasil dihapus.';
            
            return $result;
        }
        
        function sync($row, $column = array()) {
            $row = StripArray($row);
            
            if (count($column) > 0) {
				$row = dt_view($row, $column, array('is_edit' => 1));
            }
            
            return $row;
        }
		
		function add_deposit($param) {
			$store = $this->User_Store_model->get_by_id(array( 'store_id' => $param['store_id']));
			$user = $this->User_model->get_by_id(array( 'id' => $store['user_id'] ));
			
			// tax
			$tax_percent = $this->Default_Value_model->get_tax_store();
			
			$tax = ($tax_percent['value'] * $param['nota_total']) / 100;
			$deposit_nota = $param['nota_total'] - $tax;
			$deposit = $user['deposit'] + $deposit_nota;
			
			$param_user = array( 'id' => $user['id'], 'deposit' => $deposit );
			$this->User_model->update($param_user);
		}
		
		/*	Region Panel */
		
		function get_store_name() {
			return $_SERVER['store_name'];
		}
		
		/*	End Region Panel */
    }