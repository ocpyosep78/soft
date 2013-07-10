<?php
    class item extends PANEL_Controller {
        function __construct() {
            parent::__construct();
            
        }
        
        function index() {
            $this->load->view( 'panel/product/item');
        }
        function item_pending() {
            $this->load->view( 'panel/product/item_pending' );  
        }
        function item_approve() {
            $this->load->view( 'panel/product/item_approve' );  
        }	       
        function action() {
            $action = (isset($_POST['action'])) ? $_POST['action'] : '';
            unset($_POST['action']);
			// user
			$user = $this->User_model->get_session();
			
            $result = array();
            if ($action == 'get_item_by_id') 
            {
                $result = $this->Item_model->get_by_id(array('id' => $_POST['id']));
            }
            else if($action == 'del_thumbnail_current')
            {
                $dataItem = $this->Item_model->get_by_id(array('id' => $_POST['id']));
                $thumbnail_current = $dataItem['thumbnail'];
                //delete thumbnail image
                if(file_exists($this->config->item('base_path')."/static/upload/".$thumbnail_current))
                {
                    unlink($this->config->item('base_path')."/static/upload/".$thumbnail_current);
                    $_POST['thumbnail']='';
                }
                else
                {
                    $_POST['thumbnail']='';
                }
                
                $result = $this->Item_model->update($_POST);
                //print_r($dataItem['array_screenshot']);exit;
            }
            else if($action == 'del_screenshot_current')
            {
                $dataItem = $this->Item_model->get_by_id(array('id' => $_POST['id']));
                $screenshot_current = json_decode($dataItem['screenshot']);
                $arrRestScreenshot = array();
                foreach($screenshot_current as $key=>$value)
                {
                    if($value == $_POST['screenshot']){
                        if(file_exists($this->config->item('base_path')."/screenshots/".$value))
                        {
                            // file gambar besar
                            unlink($this->config->item('base_path')."/screenshots/".$value);
                            
                        }
                        else{
                            $arrRestScreenshot[]=$value;
                        }
                        // file thumbnail
                        $screenshot_mini = pathinfo($this->config->item('base_path')."/screenshots/".$value);
                        $screenshot_no_ext = basename($screenshot_mini['basename'],".".$screenshot_mini['extension']);
                        $screenshot_mini_file = $screenshot_mini['dirname']."/".$screenshot_no_ext."_thumb.".$screenshot_mini['extension'];
                        if(file_exists($screenshot_mini_file))
                        {
                            unlink($screenshot_mini_file);
                        }
                    }else
                    {
                        $arrRestScreenshot[]=$value;
                    }
                }
                //exit;
                $_POST['screenshot'] = json_encode($arrRestScreenshot);
                $result = $this->Item_model->update($_POST);
            }
            else if($action == 'del_filename_current')
            {
                $dataItem = $this->Item_model->get_by_id(array('id' => $_POST['id']));
                $filename_current = json_decode($dataItem['filename']);
                
                $arrFilename = array();
                foreach($filename_current as $key=>$value)
                {
                    if($value == $_POST['filename'])
                    {
                        if(file_exists($this->config->item("upload_directory").$value))
                        {
                            // unlink file
                            unlink($this->config->item("upload_directory").$value);
                        }else
                        {
                            $arrFilename[]=$value;
                        }
                    }else
                    {
                        $arrFilename[]=$value;
                    }
                }
                $_POST['filename'] = json_encode($arrFilename);
                $result = $this->Item_model->update($_POST);
            }
            else if ($action == 'update') 
            {
               // print_r($_POST);
               // exit;
                $dataItem = $this->Item_model->get_by_id(array('id' => $_POST['id']));
                $currentFilename = json_decode($dataItem['filename']);
                if(!empty($currentFilename) && !empty($_POST['item_file']))
                {
                    $_POST['item_file'] = array_merge($currentFilename,$_POST['item_file']);
                }
                $currentScreenshot = json_decode($dataItem['screenshot']);
                if(!empty($currentScreenshot) && !empty($_POST['item_screenshot']))
                {
                    $_POST['item_screenshot'] = array_merge ($currentScreenshot,$_POST['item_screenshot']);
                }
                $currentThumbnail = json_decode($dataItem['thumbnail']);
                if(!empty($_POST['thumbnail']))
                {
                    if(!empty($currentThumbnail))
                    {
                        if(file_exists($this->config->item('base_path')."/static/upload/".$currentThumbnail))
                        {
                            unlink($this->config->item('base_path')."/static/upload/".$currentThumbnail);
                        }
                    }
                }
                
                /*if (empty($_POST['id']) || !empty($_POST['id'])) {
                    //$_POST['item_status_id'] = ITEM_STATUS_PENDING;
                }
                */
                if (isset($_POST['item_file'])) {
                    $_POST['filename'] = json_encode($_POST['item_file']);
                }
                if (isset($_POST['item_screenshot'])) {
                    $_POST['screenshot'] = json_encode($_POST['item_screenshot']);
                }
                $_POST['date_update'] = date('Y-m-d');
                //$_POST['user_id'] = empty($user['id'])?0:$user['id']; // admin pages, no need id
                
                // Strip HTML and PHP tags from a string
                $_POST['description'] = strip_tags($_POST['description']);
                $result = $this->Item_model->update($_POST);
            }
            else if ($action == 'delete') 
            {
                $result = $this->Item_model->delete($_POST);
            }
            
            echo json_encode($result);
        }
        
		function grid() {
            // user
            $user = $this->User_model->get_session();
            $_POST['column'] = array('name', 'description', 'price' );
            $_POST['user_name'] = $user['name'];
            
            $output = array(
			"sEcho" => intval($_POST['sEcho']),
			"aaData" => $this->Item_model->get_array($_POST,$pendingApprove = false,null,null),
			"iTotalDisplayRecords" => $this->Item_model->get_count()
            );
            echo json_encode( $output );
        }
        
        function grid_pending() {
            // user
            $user = $this->User_model->get_session();
            $_POST['column'] = array('name', 'description', 'price' );
            $_POST['item_status_id'] = STATUS_ITEM_PENDING;
            $_POST['is_custom']  = '<img class="cursor product" src="'.base_url('static/img/button_confirm.png').'" style="width: 15px; height: 16px;">  ';
            $output = array(
			"sEcho" => intval($_POST['sEcho']),
			"aaData" => $this->Item_model->get_array($_POST,$pendingApprove = true,true,false),
			"iTotalDisplayRecords" => $this->Item_model->get_count()
            );
            echo json_encode( $output );
        }
        function grid_appprove() {
            // user
            $user = $this->User_model->get_session();
            $_POST['column'] = array('name', 'description', 'price' );
            $_POST['item_status_id'] = STATUS_ITEM_APPROVE;
            $_POST['is_custom']  = '<img class="cursor product" src="'.base_url('static/img/button_product.png').'" style="width: 15px; height: 16px;">  ';
            $output = array(
			"sEcho" => intval($_POST['sEcho']),
			"aaData" => $this->Item_model->get_array($_POST,$pendingApprove = true,false,true),
			"iTotalDisplayRecords" => $this->Item_model->get_count()
            );
            echo json_encode( $output );
        }
    }                                    