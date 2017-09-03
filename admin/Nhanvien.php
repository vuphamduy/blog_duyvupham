<?php
Class Nhanvien extends MY_Controller
{

     function __construct()
     {
      parent::__construct();
      $this->load->model('nhanvien_model');   
      $this->data['title_head'] = "Danh sách nhân viên";
      $this->data['table'] = "nhanvien";
    }

    function index()
    {

    //form search
     //   pre($this->load->model('nhanvien_model'));

    //table
     // $list_nv = $this->nhanvien_model->get_list();
     // $this->data['table_head'] = $this->nhanvien_model->table_head();
     // $this->data['table_body'] = $list_nv;


    $this->nhanvien_model->get_list();

     $this->data['temp'] = 'admin/nhanvien/index';
     $this->load->view('admin/main', $this->data);
    }


    function add()
    {

       
    }


}

