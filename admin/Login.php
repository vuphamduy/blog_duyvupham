<?php
Class Login extends MY_controller
{
    
    function index()
    {
        $this->load->library('form_validation');
        $this->load->helper('form');
        if($this->input->post())
        {
            $this->form_validation->set_rules('login' ,'login', 'callback_check_login');
            if($this->form_validation->run())
            {
                 //echo 'dang nhap thanh cong'; die;
                $this->session->set_userdata('login', true);
                
                redirect(admin_url('home'));
               
            }
        }
        
        $this->load->view('admin/login/index');
    }
    /*
     * Kiem tra username va password co chinh xac khong
     */
    function check_login()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $password = md5($password);
        
        $this->load->model('admin_model');
        $where = array('username' => $username , 'password' => $password);
        if($this->admin_model->check_exists($where))
        {
            $this->session->set_userdata('username', $username);
            return true;
        }
        $this->form_validation->set_message(__FUNCTION__, 'Không đăng nhập thành công');
        return false;
    }

    function logout()
    {
        if($this->session->userdata('user_id_login'))
        {
            $this->session->unset_userdata('user_id_login');
            $this->session->unset_userdata("username");
        }
        $this->session->set_flashdata('message', 'Đăng xuất thành công');
        redirect();
    }


}
