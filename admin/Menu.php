<?php
Class Menu extends MY_Controller
{
    var $table = 'menu';
    public $field = array(
        'id' => array(
            'field' => 'id',
            'label' => 'Mã'
            ),
        'name' => array(
            'field' => 'name',
            'label' => 'Tên menu',
            'rules' => 'required',
            'input' => 'form_input'
            ),
        'parent_id' => array(
            'field' => 'parent_id',
            'label' => 'Menu cha',
            'input' => 'form_dropdown'
            ),  
        'url' => array(
            'field' => 'url',
            'label' => 'Link',
            'input' => 'form_input'
            ),     
        'sort_order' => array(
            'field' => 'sort_order',
            'label' => 'Thứ tự',
            'input' => 'form_input'
            ),
        'status' => array(
            'field' => 'status',
            'label' => 'Hiển thị',
            'input' => 'form_checkbox',
            'value' => 1
            )
        );

    function __construct()
    {
        parent::__construct();
        $this->load->model('menu_model');        
        $this->load->model('catalog_model');

        $this->data['title_manager'] = "Quản lý Menu";
        //$this->data['table'] = $this->menu_model->table;
    }
    
    function index()
    {
        $this->data['title_head'] = "Danh sách Menu";  
        $this->data['table'] = $this->table;
        $this->data['table_head'] = $this->menu_model->table_head();    
         //lay danh sach cha
        $input = array();
        $input['where'] = array('parent_id' => 0);
        $list_cat = $this->catalog_model->get_list($input);
         //lay danh sach con thuoc cha
        foreach ($list_cat as $row) 
        {
            $input['where'] = array('parent_id' => $row->id);
            $subs = $this->catalog_model->get_list($input);
            $row->subs = $subs;
        }
        $this->data['list_cat'] = $list_cat;  


        $list = $this->menu_model->get_list();   
        $input = array();     
        $input['where']= array('parent_id' => 0);
        $list_parent = $this->menu_model->get_list($input);  //pre($list_parent);
         foreach ($list as $row) {
                if($row->parent_id == 0){
                    $row->parent_id = 'Là menu cha';
                }
                else{
                    $info = $this->menu_model->get_info($row->parent_id); //pre($info);
                     $row->parent_id = $info->name;
                }
        }  

        
        $this->data['table_body'] = $list;      

        $this->data['message'] = $this->session->flashdata('message');            
        $this->data['button_add'] = $this->table.'/add';
        $this->data['temp'] = 'admin/'.$this->table.'/index';
        $this->load->view('admin/main', $this->data);
    }
    

    function add()
    {   
        $this->data['title_head'] = "Thêm menu mới";    
        $input = array();
        $input['where'] = array('parent_id' => 0);
        $list_parent = $this->menu_model->get_list($input);
        $arr_parent = array();
        foreach ($list_parent as $row) {
           $arr_parent[$row->id] = $row->name;
        }            
        $this->data['arr_parent']  = $arr_parent; 
        $this->data['field']  = $this->field;
        $this->menu_model->add_form($this->field); 
         $this->data['message'] = $this->session->flashdata('message'); 

        $this->data['temp'] = 'admin/'.$this->table.'/add';
        $this->load->view('admin/main', $this->data);       
    }

     function edit()
    {   
        $this->data['title_head'] = "Cập nhật menu";    
        $input = array();
        $input['where'] = array('parent_id' => 0);
        $list_parent = $this->menu_model->get_list($input);
        $arr_parent = array();
        foreach ($list_parent as $row) {
           $arr_parent[$row->id] = $row->name;
        }            
        $this->data['arr_parent']  = $arr_parent; 
        $this->data['field']  = $this->field;
        $this->data['info_array']  =  $this->menu_model->check_info_array(); 
        $this->menu_model->edit_form($this->field); 

       // pre($this->menu_model->check_info());
         $this->data['message'] = $this->session->flashdata('message'); 

        $this->data['temp'] = 'admin/'.$this->table.'/edit';
        $this->load->view('admin/main', $this->data);       
    }

    function del()
    {
        $this->menu_model->_delele_one(); 
    }

     function delete_all()
     {
        $this->menu_model->_delele_all(); 
    }
    

}

