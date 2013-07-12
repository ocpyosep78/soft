<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
    class User_model extends CI_Model {
        function __construct() {
            parent::__construct();
            
            $this->field = array(
				'id', 'name', 'email', 'fullname', 'passwd', 'address', 'city', 'propinsi', 'zipcode', 'phone', 'mobile', 'office', 'birthdate',
				'saldo_rupiah', 'saldo_dollar', 'is_active', 'reset',
                'type_account','paypal_email','bank_name','bank_account','bank_account_name'
			);
			
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
                } else if (isset($param['reset'])) {
                $select_query  = "SELECT * FROM ".USER." WHERE reset = '".$param['reset']."' LIMIT 1";
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
            $row = StripArray($row, array('birthdate'));
			$row['display_name'] = (empty($row['fullname'])) ? $row['name'] : $row['fullname'];
            
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
        
		// make sure buyer have email, force add user by email
		function force_login_buyer($param) {
            $is_login = $this->is_login();
            if (!$is_login) {
                $temp = $this->get_by_id(array( 'email' => $param['email']));
                if (count($temp) == 0) {
                    $temp = $this->update(array( 'email' => $param['email'], 'name' => time() ));
                }
                
                $user = $this->get_by_id(array( 'id' => $temp['id'] ));
                $this->set_session($user);
            }
		}
		
		/*	Region Saldo */
		
		function update_saldo($param) {
			$user = $this->get_by_id(array( 'id' => $param['id'] ));
			
			$update['id'] = $user['id'];
			if (isset($param['saldo_rupiah'])) {
				$update['saldo_rupiah'] = $user['saldo_rupiah'] + $param['saldo_rupiah'];
			}
			if (isset($param['saldo_dollar'])) {
				$update['saldo_dollar'] = $user['saldo_dollar'] + $param['saldo_dollar'];
			}
			
			$this->update($update);
		}
		
		function get_saldo($param) {
			$result['kurs_dollar'] = $this->Default_Value_model->get_konversi_rupiah_dolar();
			$result['saldo_rupiah'] = $this->User_Item_model->get_saldo_rupiah(array( 'user_id' => $param['user_id'] ));
			$result['saldo_dollar'] = $this->User_Item_model->get_saldo_dollar(array( 'user_id' => $param['user_id'] ));
			$result['saldo_dollar_at_rupiah'] = $result['kurs_dollar']['value'] * $result['saldo_dollar'];
			$result['saldo_total'] = $result['saldo_rupiah'] + $result['saldo_dollar_at_rupiah'];
			$result['saldo_percent'] = $this->Sales_Percent_model->get_percent(array( 'value' => $result['saldo_total'] ));
			$result['saldo_profit'] = round(($result['saldo_percent']['percent'] * $result['saldo_total']) / 100);
			
			return $result;
		}
		
		/*	End Region Saldo */
		
        /*	Region User Session */
        
        function login_user_required() {
            $user = $this->get_session();
            
            $valid = $this->is_login();
            if (! $valid) {
                header("Location: ".site_url('login'));
                exit;
            }
        }
        
        function login_user_admin_required() {
            $user = $this->get_session();
            
            $valid = $this->is_login();
            if (! $valid) {
                header("Location: ".site_url('panel/login'));
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
			$this->delete_human();
			
            $_SESSION['user_login'] = array();
        }
        
		function is_human() {
			$result = false;
			
			if ($this->is_login()) {
				$result = true;
			} if (isset($_SESSION['is_human']) && $_SESSION['is_human'] = 1) {
				$result = true;
			}
			
			return $result;
		}
		
		function set_human() {
			$_SESSION['is_human'] = 1;
		}
		
		function delete_human() {
			$_SESSION['is_human'] = 0;
		}
		
		function is_owner($param) {
			$user = $this->User_model->get_session();
			$item = $this->Item_model->get_by_id(array( 'id' => $param['item_id'] ));
			
			$is_owner = false;
			if (isset($item['user_id']) && $item['user_id'] == @$user['id']) {
				$is_owner = true;
			}
			
			return $is_owner;
		}
		
		function is_admin($param) {
			$admin_user = $this->config->item('admin_user_id');
			$result = (in_array($param['user_id'], $admin_user)) ? true : false;
			return $result;
		}
		
        /*	End Region User Session */
    }    