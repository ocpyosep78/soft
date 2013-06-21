<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nota_model extends CI_Model {
    function __construct() {
        parent::__construct();
		
        $this->field = array(
			'id', 'user_id', 'store_id', 'status_nota_id', 'payment_method_id', 'nota_note', 'nota_date', 'nota_name', 'nota_address',
			'nota_phone', 'nota_zipcode', 'nota_city', 'nota_country', 'nota_currency', 'nota_total', 'nota_email', 'nota_tax', 'nota_deposit'
		);
    }
	
    function update($param) {
        $result = array();
       
        if (empty($param['id'])) {
            $insert_query  = GenerateInsertQuery($this->field, $param, NOTA);
            $insert_result = mysql_query($insert_query) or die(mysql_error());
           
            $result['id'] = mysql_insert_id();
            $result['status'] = '1';
            $result['message'] = 'Data berhasil disimpan.';
        } else {
            $update_query  = GenerateUpdateQuery($this->field, $param, NOTA);
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
					Nota.*, PaymentMethod.name payment_method_name, StatusNota.name status_nota_name
				FROM ".NOTA." Nota
				LEFT JOIN ".STATUS_NOTA." StatusNota ON StatusNota.id = Nota.status_nota_id
				LEFT JOIN ".PAYMENT_METHOD." PaymentMethod ON PaymentMethod.id = Nota.payment_method_id
				WHERE Nota.id = '".$param['id']."'
				LIMIT 1";
        }
		
        $select_result = mysql_query($select_query) or die(mysql_error());
        if (false !== $row = mysql_fetch_assoc($select_result)) {
            $array = $this->sync($row);
        }
       
        return $array;
    }
	
    function get_array($param = array()) {
        $array = array();
		$param['field_replace']['id'] = 'Nota.id';
		$param['field_replace']['nota_currency_total'] = 'Nota.nota_total';
		$param['field_replace']['status_nota_name'] = 'StatusNota.name';
		
		$string_store = (empty($param['store_id'])) ? "" : "AND Nota.store_id = '".$param['store_id']."'";
		$string_status_nota = (empty($param['status_nota_id'])) ? "" : "AND Nota.status_nota_id = '".$param['status_nota_id']."'";
		$string_filter = GetStringFilter($param, @$param['column']);
		$string_sorting = GetStringSorting($param, @$param['column'], 'nota_name ASC');
		$string_limit = GetStringLimit($param);
		
		$select_query = "
			SELECT
				SQL_CALC_FOUND_ROWS Nota.*,
				PaymentMethod.name payment_method_name, StatusNota.name status_nota_name
			FROM ".NOTA." Nota
			LEFT JOIN ".STATUS_NOTA." StatusNota ON StatusNota.id = Nota.status_nota_id
			LEFT JOIN ".PAYMENT_METHOD." PaymentMethod ON PaymentMethod.id = Nota.payment_method_id
			WHERE 1 $string_store $string_status_nota $string_filter
			ORDER BY $string_sorting
			LIMIT $string_limit
		";
        $select_result = mysql_query($select_query) or die(mysql_error());
		while ( $row = mysql_fetch_assoc( $select_result ) ) {
//			$array[] = $this->sync($row, @$param['column']);
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
	
	function get_url_nota_id() {
		$request_uri = $_SERVER['REQUEST_URI'];
		
		// matching catalog
		preg_match('/order\/([a-z0-9\_]+)/i', $request_uri, $match);
		$order_id = (isset($match[1])) ? $match[1] : '';
		
		return $order_id;
	}
	
    function delete($param) {
		$delete_query  = "DELETE FROM ".NOTA." WHERE id = '".$param['id']."' LIMIT 1";
		$delete_result = mysql_query($delete_query) or die(mysql_error());
		
		$result['status'] = '1';
		$result['message'] = 'Data berhasil dihapus.';

        return $result;
    }
	
	/*
	function sync($row, $param = array()) {
		$row = StripArray($row);
		$row['nota_link'] = site_url('order/'.$row['id']);
		$row['nota_currency_total'] = $row['nota_currency'].' '.$row['nota_total'];
		
		if (is_array($param['column']) && count($param['column']) > 0) {
			$dt_param = array(
				'is_edit' => 1,
				'is_custom' => '<img class="cursor product" src="'.base_url('static/img/button_product.png').'" style="width: 15px; height: 16px;"> '
			);
			if (! in_array($row['status_nota_id'], array(STATUS_NOTA_CONFIRM, STATUS_NOTA_CANCEL))) {
				$dt_param['is_custom'] .= 
					'<img class="cursor confirm" src="'.base_url('static/img/button_confirm.png').'" style="width: 15px; height: 16px;"> ' .
					'<img class="cursor cancel" src="'.base_url('static/img/button_cancel.png').'" style="width: 15px; height: 16px;"> ';
			}
			$row = dt_view($row, $param['column'], $dt_param);
		}
		
		return $row;
	}
	/*	*/
	
	function sync($row, $param = array()) {
		$row = StripArray($row);
		$row['nota_link'] = site_url('order/'.$row['id']);
		$row['nota_currency_total'] = $row['nota_currency'].' '.$row['nota_total'];
		
		if (! in_array($row['status_nota_id'], array(STATUS_NOTA_CONFIRM, STATUS_NOTA_CANCEL))) {
			$param['is_custom']  = (isset($param['is_custom'])) ? $param['is_custom'] : '';
			$param['is_custom'] .= 
				'<img class="cursor confirm" src="'.base_url('static/img/button_confirm.png').'" style="width: 15px; height: 16px;"> ' .
				'<img class="cursor cancel" src="'.base_url('static/img/button_cancel.png').'" style="width: 15px; height: 16px;"> ';
		}
		
		if (count(@$param['column']) > 0) {
			$row = dt_view_set($row, $param);
		}

		return $row;
	}
}