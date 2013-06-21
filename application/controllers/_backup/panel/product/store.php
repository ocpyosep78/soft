<?php
    
    class store extends PANEL_Controller {
        function __construct() {
            parent::__construct();
        }
        
        function index() {
            //load theme
            $data['theme_id'] = $this->Theme_model->get_selected_column("id,name",null);
            $this->load->view( 'panel/product/store',$data);
        }
        
        function action() {
            $action = (isset($_POST['action'])) ? $_POST['action'] : '';
            unset($_POST['action']);
            
            $result = array();
            if ($action == 'update') {
                $result = $this->Store_model->update($_POST);
                } else if ($action == 'delete') {
                $result = $this->Store_model->delete($_POST);
            }
            
            echo json_encode($result);
        }
        
        function grid() {
            $_POST['column'] = array('name','domain','option','theme_id');
            $output = array(
			"sEcho" => intval($_POST['sEcho']),
			"aaData" => $this->Store_model->get_array($_POST),
			"iTotalDisplayRecords" => $this->Store_model->get_count()
            );
            echo json_encode( $output );
        }
        
        function test()
        {
            $xxx = mysql_query("select COLUMN_NAME, data_type
            from information_schema.columns 
            where table_schema = 'olshop'
            and table_name = 'category'");
            while($data = mysql_fetch_assoc($xxx))
            {
                print_r($data);
                echo "<br/>";   
             }
        }
    }    