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
            if ($action == 'get_item_by_id') {
                $result = $this->Item_model->get_by_id(array('id' => $_POST['id']));
            }
            else if ($action == 'update') {
                
                // picture
                if (isset($_POST['item_picture']) && is_array($_POST['item_picture'])) {
                    foreach($_POST['item_picture'] as $value) {
                        //$picture = $this->Picture_model->update(array('picture_name' => $value));
                        $picture_name = $value;
                        
                        $this->load->helper('resize');
                        $this->load->helper('watermark');
                        //watermark original image
                        //WatermarkImage($this->config->item('base_path').'/static/upload/'.$value,null,get_store(),null,null);
                        
                        // small / _s
                        // 60px X 60px
                        $originalImages = $this->config->item('base_path').'/static/upload/'.$value;
                        $imagesInfo     = pathinfo($originalImages);
                        
                        $smallNewImages = $imagesInfo['dirname']."/".$imagesInfo['filename']."_s.".$imagesInfo['extension'];
                        $mediumNewImages = $imagesInfo['dirname']."/".$imagesInfo['filename']."_m.".$imagesInfo['extension'];
                        $largeNewImages = $imagesInfo['dirname']."/".$imagesInfo['filename']."_l.".$imagesInfo['extension'];
                        $imageSize      = getimagesize($originalImages);
                        $widthImage     = $imageSize[0];
                        $heightImage    = $imageSize[1];
                        if($widthImage != $heightImage)
                        {
                            ImageResize($originalImages, $smallNewImages, 60, 60, 1);
                            ImageResize($originalImages, $mediumNewImages, 470, 470, 1);
                            ImageResize($originalImages, $largeNewImages, 800, 800, 1);
                        }
                        else
                        {
                            ImageResize($originalImages, $smallNewImages, 60, 60, 0);
                            ImageResize($originalImages, $mediumNewImages, 470, 470, 0);
                            ImageResize($originalImages, $largeNewImages, 800, 800, 0);
                        }
                        WatermarkImage($this->config->item('base_path').'/static/upload/'.$value,'_s','Olshop',60,60,7);
                        
                        // medium / _m
                        // 470px X 470px
                        WatermarkImage($this->config->item('base_path').'/static/upload/'.$value,'_m','Olshop',470,470,12);
                        
                        // large / _l
                        // 800px X 800px
                        WatermarkImage($this->config->item('base_path').'/static/upload/'.$value,'_l','Olshop',800,800,16);
                        
                        //$this->Item_Picture_model->update(array('item_id' => $result['id'], 'picture_id' => $picture['id']));
                    }
                    
                    // update thumbnail
                    // $this->Item_model->update(array('id' => $result['id'], 'thumbnail' => $value));
                    $_POST['thumbnail'] = $value;
                }
                // insert file
				if (isset($_POST['item_file']) && is_array($_POST['item_file'])) {
                    $_POST['filename'] = "";
                    $file ="";
                    $file .="[";
					foreach($_POST['item_file'] as $value) {
						$file .="\"".$value."\",";
                    }
                    $file .="]";
                    $_POST['filename'] = $file;
                }
                
                $_POST['date_update'] = $this->config->item('current_datetime');
                $result = $this->Item_model->update($_POST);
                
                
                /*
                    // insert category
                    $this->Item_Category_model->delete(array('item_id' => $result['id']));
                    if (is_array($_POST['category_id'])) {
                    foreach($_POST['category_id'] as $value) {
                    $this->Item_Category_model->update(array('item_id' => $result['id'], 'category_id' => $value));
                    }
                    }
                    
                    // insert price
                    $this->Item_Price_model->delete(array('item_id' => $result['id']));
                    $this->Item_Price_model->update(array('item_id' => $result['id'], 'currency_id' => $_POST['currency_id'], 'price' => $_POST['price']));
                    
                    // insert picture
                    $this->Item_Picture_model->delete(array('item_id' => $result['id']));
                    if (isset($_POST['item_picture']) && is_array($_POST['item_picture'])) {
                    foreach($_POST['item_picture'] as $value) {
                    $picture = $this->Picture_model->update(array('picture_name' => $value));
                    
                    $this->load->helper('resize');
                    $this->load->helper('watermark');
                    //watermark original image
                    //WatermarkImage($this->config->item('base_path').'/static/upload/'.$value,null,get_store(),null,null);
                    
                    // small / _s
                    // 60px X 60px
                    $originalImages = $this->config->item('base_path').'/static/upload/'.$value;
                    $imagesInfo     = pathinfo($originalImages);
                    
                    $smallNewImages = $imagesInfo['dirname']."/".$imagesInfo['filename']."_s.".$imagesInfo['extension'];
                    $mediumNewImages = $imagesInfo['dirname']."/".$imagesInfo['filename']."_m.".$imagesInfo['extension'];
                    $largeNewImages = $imagesInfo['dirname']."/".$imagesInfo['filename']."_l.".$imagesInfo['extension'];
                    $imageSize      = getimagesize($originalImages);
                    $widthImage     = $imageSize[0];
                    $heightImage    = $imageSize[1];
                    if($widthImage != $heightImage)
                    {
                    ImageResize($originalImages, $smallNewImages, 60, 60, 1);
                    ImageResize($originalImages, $mediumNewImages, 470, 470, 1);
                    ImageResize($originalImages, $largeNewImages, 800, 800, 1);
                    }
                    else
                    {
                    ImageResize($originalImages, $smallNewImages, 60, 60, 0);
                    ImageResize($originalImages, $mediumNewImages, 470, 470, 0);
                    ImageResize($originalImages, $largeNewImages, 800, 800, 0);
                    }
                    WatermarkImage($this->config->item('base_path').'/static/upload/'.$value,'_s','Olshop',60,60,7);
                    
                    // medium / _m
                    // 470px X 470px
                    WatermarkImage($this->config->item('base_path').'/static/upload/'.$value,'_m','Olshop',470,470,12);
                    
                    // large / _l
                    // 800px X 800px
                    WatermarkImage($this->config->item('base_path').'/static/upload/'.$value,'_l','Olshop',800,800,16);
                    
                    $this->Item_Picture_model->update(array('item_id' => $result['id'], 'picture_id' => $picture['id']));
                    }
                    
                    // update thumbnail
                    $this->Item_model->update(array('id' => $result['id'], 'thumbnail' => $value));
                    }
                    
                    // insert file
                    $this->Item_File_model->delete(array('item_id' => $result['id']));
                    if (isset($_POST['item_file']) && is_array($_POST['item_file'])) {
					foreach($_POST['item_file'] as $value) {
                    $file = $this->File_model->get_by_id(array( 'file_name' => $value, 'force_insert' => 1 ));
                    $this->Item_File_model->update(array( 'item_id'=> $result['id'], 'file_id' => $file['id'] ));
					}
                    }
                */
            }
            else if ($action == 'delete') {
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