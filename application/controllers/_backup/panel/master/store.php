<?php
    
    class store extends PANEL_Controller {
        function __construct() {
            parent::__construct();
        }
        
        function index() {
            $this->load->view( 'panel/master/store' );
        }
        
		function action() {
            $action = (isset($_POST['action'])) ? $_POST['action'] : '';
            unset($_POST['action']);
            
            $result = array();
            if ($action == 'update') {
                $is_new = (empty($_POST['id'])) ? true : false;
                $result = $this->Store_model->update($_POST);
                
                if ($is_new) {
                    // add user store
                    if (!empty($_POST['user_id'])) {
                        $param = array('store_id' => $result['id'], 'user_id' => $_POST['user_id']);
                        $this->User_Store_model->update($param);
                    }
                    
                    // store config
                    $array_store_config = array(
						array(
							'name' => 'about_us',
							'title' => 'About Us',
							'content' => 'Mecenas neque est, feugiat quis porta in, condimentum eget arcu. Fringilla aliquam ultricies pellente sque vel turpis nec leo tincidunt sollicitudin ac non risus. Ves tibu lum ultrices feugiat velit, quis tincidunt velit volutpat nec. Vivamus pharetra fringilla augue, elementum ante ultrices tincidunt.'
						),
						array(
							'name' => 'newsletter',
							'title' => 'Newsletter',
							'content' => 'Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.'
						),
						array(
							'name' => 'contact_us',
							'title' => 'Contact Us',
							'content' => '<p><b>Nam velit nulla, egestas sit amet luctus eu, aliquam vel enim. Proin tortor est, ornare sit amet fringilla id, lacinia a velit. Aliquam erat volutpat.</b></p>\r\n<p>Quisque pharetra libero at orci lacinia elementum ultricies nisi imperdiet.</p>'
						),
						array(
							'name' => 'store_logo',
							'title' => 'Store Logo',
							'content' => '<p>we sell quality products at affordable prices seeep ;)</p>'
						)
                    );
                    foreach ($array_store_config as $param) {
                        $param['store_id'] = $result['id'];
                        $this->Store_Detail_model->update($param);
                    }
                    
                    // store image slide
                    $array_store_image_slide = array(
						array( 'title' => 'Brand new hats & nitwear', 'content' => 'Lorem ipsum dolor sit amet', 'active' => 1 ),
						array( 'title' => 'New 2012 summer apparel collection', 'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'active' => 1 ),
						array( 'title' => 'Brand new hats & nitwear', 'content' => 'Lorem ipsum dolor sit amet', 'active' => 1 ),
						array( 'title' => 'New 2012 summer apparel collection', 'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'active' => 1 )
                    );
                    foreach ($array_store_image_slide as $param) {
                        $param['store_id'] = $result['id'];
                        $this->Store_Image_Slide_model->update($param);
                    }
                    
                    // category
                    $array_category = array( array( 'name' => 'utama', 'title' => 'Utama' ) );
                    $category_id = array();
                    foreach ($array_category as $param) {
                        $param['store_id'] = $result['id'];
                        $category_id[] = $this->Category_model->update($param);
                    }
					
                    // catalog
                    $catalog_id = array();
                    $array_catalog = array( array( 'name' => 'utama', 'title' => 'Utama' ) );
                    foreach ($array_catalog as $param) {
                        $param['store_id'] = $result['id'];
                        $catalog_id[] = $this->Catalog_model->update($param);
                    }
                    
                    // item
                    $array_item = array(
						array( 'name' => 'software_1', 'title' => 'Software 1', 'description'=>'Penjelasan Software 1')
                    );
                    foreach ($array_item as $param) {
                        $param['store_id'] = $result['id'];
                        $item_id[] = $this->Item_model->update($param);
                    }
					
                    // item catalog & item category & item price
                    foreach ($item_id as $key => $value) {
                        $param = array( 'item_id'=> $value['id'], 'catalog_id' => $catalog_id[rand(0, count($catalog_id) - 1)]['id']);
                        $this->Item_Catalog_model->update($param);
						
                        $param = array('item_id'=> $value['id'],'category_id'=> $category_id[rand(0, count($category_id) - 1)]['id']);
                        $this->Item_Category_model->update($param);
						
                        $param = array( 'item_id' => $value['id'], 'currency_id' => 1, 'price'=> (rand(1, 50) * 1000));
                        $this->Item_Price_model->update($param);
                    }
                }
			} else if ($action == 'delete') {
				$result = $this->Store_model->delete($_POST);
				
				/*
DELETE FROM store WHERE id >= 2 
DELETE FROM user_store WHERE store_id >= 2 

DELETE FROM store_image_slide WHERE store_id >= 2 
DELETE FROM category WHERE store_id >= 2 
DELETE FROM catalog WHERE store_id >= 2 
DELETE FROM item WHERE store_id >= 2 
DELETE FROM item_catalog WHERE item_id >= 2
DELETE FROM item_category WHERE item_id >= 2
DELETE FROM item_price WHERE item_id >= 2
				/*	*/
            }
            
            echo json_encode($result);
        }
        
        function grid() {
            $_POST['column'] = array(  'name', 'domain' );
            $output = array(
			"sEcho" => intval($_POST['sEcho']),
			"aaData" => $this->Store_model->get_array($_POST),
			"iTotalDisplayRecords" => $this->Store_model->get_count()
            );
            echo json_encode( $output );
        }
    }
