<?php
Class Nhanvien extends MY_Controller
{

    public $m_nhanvien = "nhanvien_model";
   

    public function table_model()
    {
        $this->load->model($this->m_nhanvien);

        return $this;

    }
     function __construct()
    {
        parent::__construct();
      //  $this->table_model(); //load model
       //  $this->load->model('nhanvien_model');
       
    }

    function index()
    {
        // $model = $this->table_model($this->m_nhanvien); //load model

        // $input = array();
        // $input = array();
        // $input['where'] = array('parent_id' => 0);

       // $list_cha = $this->nhanvien_model->get_list($input);
       // $list = $model->get_list();





       pre($model);




        $this->data['temp'] = 'admin/nhanvien/index';

        $this->load->view('admin/main', $this->data);
    }
    
 
}

