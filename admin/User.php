<?php
Class User extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        //$this->load->model('user_model');

    }
    
   
    function check_email()
    {
        $email = $this->input->post('email');
        $where = array('email' => $email);
        
        if($this->user_model->check_exists($where))
        {
           
            $this->form_validation->set_message(__FUNCTION__, 'Email đã tồn tại');
            return false;
        }
        return true;
    }
    
  
    function register()
    {
       
        if($this->session->userdata('user_id_login'))
        {
            redirect(site_url('user'));
        }
        
        
     
        if($this->input->post())
        {
           
           
            
            $password = $this->input->post('password');
            $password = md5($password);
            
            $data = array(
                'name'     => $this->input->post('name'),
                'email'    => $this->input->post('email'),
                'phone'    => $this->input->post('phone'),
                'address'  => $this->input->post('address'),
                'password' => $password,
                'created'  => date('Y-m-d')
            );
            if($this->user_model->create($data))
            {
                
                echo 'Bạn đã đăng ký thành viên thành công';
            }
            else
            {
               
                echo 'Chưa đăng ký được.';
            }
        
           
        }
        

    }
  
    function login()
    {
       
        if($this->session->userdata('user_id_login'))
        {
            redirect(site_url('user'));
        }

        $this->load->library('form_validation');
        $this->load->helper('form');
    
        
        if($this->input->post())
        {
            $this->form_validation->set_rules('login' ,'login', 'callback_check_login'); // check_login
            //$this->check_login();
            if($this->form_validation->run())
            {
                
                $user = $this->_get_user_info();
              
                $this->session->set_userdata('user_id_login', $user->id);
                echo 'Đăng nhập thành công.';
                //redirect();
            }
            
        }       
       
    }

  
    function check_login()
    {
        $user = $this->_get_user_info();
        if($user)
        {
            return true;
        }
      
        return false;
    }
    
   
    private function _get_user_info()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $password = md5($password);
        
        $where = array('email' => $email , 'password' => $password);
        $user = $this->user_model->get_info_rule($where);
        return $user;
    }
    
  
    function edit()
    {
        if(!$this->session->userdata('user_id_login'))
        {
            redirect(site_url('user/login'));
        }
       
        $user_id = $this->session->userdata('user_id_login');
        $user = $this->user_model->get_info($user_id);
        if(!$user)
        {
            redirect();
        }
        $this->data['user']  = $user;
        

        $this->load->library('form_validation');
        $this->load->helper('form');
        
       
        if($this->input->post())
        {
            $password = $this->input->post('password');
            
            $this->form_validation->set_rules('name', 'Tên', 'required|min_length[8]');
            if($password)
            {
                $this->form_validation->set_rules('password', 'Mật khẩu', 'required|min_length[6]');
                $this->form_validation->set_rules('re_password', 'Nhập lại mật khẩu', 'matches[password]');
            }
            
            $this->form_validation->set_rules('phone', 'Số điện thoại', 'required');
            $this->form_validation->set_rules('address', 'Địa chỉ', 'required');
        

            if($this->form_validation->run())
            {

                $data = array(
                    'name'     => $this->input->post('name'), 
                    'phone'    => $this->input->post('phone'),
                    'address'  => $this->input->post('address'),
                );
                if($password)
                {
                    $data['password'] = md5($password);
                }
                if($this->user_model->update($user_id, $data))
                {

                    $this->session->set_flashdata('message', 'Chỉnh sửa thông tin thành công');
                }
                else{
                    $this->session->set_flashdata('message', 'Không thành công');
                }

                redirect(site_url('user'));
            }
        }
        

        $this->data['temp'] = 'site/user/edit';
        $this->load->view('site/layout', $this->data);
    }
    
   
    function index()
    {
        if(!$this->session->userdata('user_id_login'))
        {
            redirect();
        }
        $user_id = $this->session->userdata('user_id_login');
        $user = $this->user_model->get_info($user_id);
        if(!$user)
        {
            redirect();
        }
        $this->data['user']  = $user;
        
       
        $this->data['temp'] = 'site/user/index';
        $this->load->view('site/layout', $this->data);
    }

  
    function logout()
    {
        if($this->session->userdata('user_id_login'))
        {
            $this->session->unset_userdata('user_id_login');
        }
        $this->session->set_flashdata('message', 'Đăng xuất thành công');
        redirect();
    }



}

