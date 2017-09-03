<?php
Class Admin extends MY_Controller
{
  
    function index()
    {
        /*$input = array();
        $list = $this->admin_model->get_list($input);
        $this->data['list'] = $list;
    
        $total = $this->admin_model->get_total();
        $this->data['total'] = $total;
        
        //lay nội dung của biến message
        $message = $this->session->flashdata('message');
        $this->data['message'] = $message; */;
        
        $this->data['temp'] = 'admin/admin/index';
        $this->load->view('admin/main', $this->data);
    }
    /*
     * Thuc hien dang xuat
     */
    function logout()
    {
        if($this->session->userdata('login'))
        {
           
        }
        redirect(admin_url('login'));
    }
    
   
    
   
}



