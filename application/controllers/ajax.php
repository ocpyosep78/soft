<?php
    
    class ajax extends CI_Controller {
        function __construct() {
            parent::__construct();
        }
        
        function index() {
        echo "Asdas";
            /*
            $_GET['limit'] = 25;
            
            preg_match('/ajax\/([a-z0-9]+)/i', $_SERVER['REQUEST_URI'], $match);
            $method = (empty($match[1])) ? '' : $match[1];
            $this->$method();
            */
        }
        function sss()
        {
            echo "Asdad";
            exit;
        }
        function user() {
            echo "Asdad";
            exit;
            $action = (empty($_POST['action'])) ? '' : $_POST['action'];
            
            $result = array('status' => false, 'message' => '');
            if ($action == 'UpdateUser') {
                $user = $this->User_model->get_by_id(array('email' => $_POST['email']));
                if (count($user) > 0) {
                    $result['message'] = 'Email sudah terdaftar dalam database kami, mohon melakukan login atau reset password.';
                    } else {
                    $_POST['passwd'] = EncriptPassword($_POST['passwd']);
                    $result = $this->User_model->update($_POST);
                }
                } else if ($action == 'Login') {
                $passwd = EncriptPassword($_POST['passwd']);
                $user = $this->User_model->get_by_id(array('email' => $_POST['email']));
                
                $result = array('status' => false, 'message' => 'User dan Password anda tidak ada yang sama dalam data kami.');
                if (count($user) > 0) {
                    if ($user['passwd'] == $passwd) {
                        $result['status'] = true;
                        $result['message'] = '';
                        
                        // get user default store
                        $store = $this->Store_model->get_by_id(array('user_id' => $user['id']));
                        if (count($store) > 0) {
                            $store_session['store_id'] = $store['store_id'];
                            $store_session['title'] = $store['title'];
                            $store_session['name'] = $store['name'];
                            $store_session['option'] = $store['option'];
                            $store_session['domain'] = $store['domain'];
                            $user['store_active'] = $store_session;
                        }
                        
                        // get user list store
                        $param['filter'] = '[{"type":"numeric","comparison":"eq","value":"'.$user['id'].'","field":"UserStore.user_id"}]';
                        $user['store_array'] = $this->User_Store_model->get_array($param);
                        
                        unset($user['passwd']);
                        $this->User_model->set_session($user);
                    }
                }
                } else if ($action == 'ResetPassword') {
                $result['message'] = 'Akan dilanjutkan';
            }
            
            echo json_encode($result);
        }
        
        function address() {
            $action = (empty($_POST['action'])) ? '' : $_POST['action'];
            $user = $this->User_model->get_session();
            
            $result = array('status' => false, 'message' => '');
            if ($action == 'UpdateAddress') {
                $_POST['user_id'] = $user['id'];
                $result = $this->Address_model->update($_POST);
                set_flash_message($result['message']);
                } else if ($action == 'DeleteAddress') {
                $result = $this->Address_model->delete(array('id' => $_POST['id']));
                set_flash_message($result['message']);
            }
            
            echo json_encode($result);
        }
        
        function newsletter() {
            $action = (empty($_POST['action'])) ? '' : $_POST['action'];
            
            // store
            $store_name = get_store();
            $store = $this->Store_Detail_model->get_info(array('store_name' => $store_name));
            
            if ($action == 'SubscribeNewsletter') {
                $newsletter = $this->Newsletter_model->get_by_id(array('email' => $_POST['email'], 'store_id' => $store['store_id']));
                
                $result = array('status' => false);
                if (count($newsletter) == 0) {
                    $param = array(
					'store_id' => $store['store_id'], 'email' => $_POST['email'],
					'register_date' => $this->config->item('current_datetime'), 'is_active' => 1
                    );
                    $result = $this->Newsletter_model->update($param);
                }
            }
            
            echo json_encode($result);
        }
        
        function view() {
            $action = (empty($_POST['action'])) ? '' : $_POST['action'];
            if (empty($action)) {
                exit;
            }
            
            $this->load->view( 'website/store/theme/calisto/'.$action );
        }
        
        function logout() {
            $this->User_model->delete_session();
            header("Location: ".site_url());
            exit;
        }
    }    