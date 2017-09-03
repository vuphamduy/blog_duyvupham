<?php
Class Catalog extends MY_Controller
{
    var $table_name = 'catalog'; //update
    var $table_model = 'catalog_model'; //update

    function table_model_d()   
    {
        $ojd_model = $this->table_model;
        $this->load->model($ojd_model); 
        $kq =   $this->$ojd_model; 
        return $kq;
    }



    function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('form');
    }

  

    function index()
    {
       //  $table_model = $this->table_model_d();
      // pre($table_model);
       // Catalog_model Object
       //  (
       //      [table] => catalog
       //      [key] => id
       //      [cat_name] => cat_name
       //      [order] => 
       //      [select] => 
       //  )

        $input = array();
        $input['where'] = array('parent_id' => 0);
        $list_cha = $this->table_model_d()->get_list($input);

        foreach ($list_cha as $row) 
        {
            $input['where'] = array('parent_id' => $row->id);
            $subs = $this->table_model_d()->get_list($input);
            $row->subs = $subs;
        }
        $this->data['list_cha'] = $list_cha;    
        //pre($list_cha);
    
        $message = $this->session->flashdata('message');
        $this->data['message'] = $message;
        
        $this->data['temp'] = 'admin/'.$this->table_name.'/index';
        $this->load->view('admin/main', $this->data);
    }

     function add()
    {
        
        if($this->input->post())
        {
            //update
            $this->form_validation->set_rules('name', 'Thể loại', 'required');


            if($this->form_validation->run())
            {
                //update
                $data = array(
                    'name'      => $this->input->post('name'),
                    'name_url'  => $this->input->post('name_url'),
                    'parent_id' => $this->input->post('parent_id'),
                    'sort_order' => intval($this->input->post('sort_order')),
                    'status' => intval($this->input->post('status'))
                );
            
                if($this->table_model_d()->create($data))
                {
                    $this->session->set_flashdata('message', 'Thêm mới dữ liệu thành công');
                }else{
                    $this->session->set_flashdata('message', 'Không thêm được');
                }
                redirect(admin_url($this->table_name));
            }
        }
        
        $input = array();
        $input['where'] = array('parent_id' => 0);
        $list = $this->table_model_d()->get_list($input);
        $this->data['list']  = $list;

        $message = $this->session->flashdata('message');
        $this->data['message'] = $message;
        
        $this->data['temp'] = 'admin/'.$this->table_name.'/add';
        $this->load->view('admin/main', $this->data);
    }



      function edit()
    {

        $id = $this->uri->rsegment(3);
        $info = $this->table_model_d()->get_info($id);
        if(!$info)
        {
            $this->session->set_flashdata('message', 'không tồn tại danh mục này');
            redirect(admin_url('catalog'));
        }
        //pre($info);
        $this->data['info'] = $info;
        
        //neu ma co du lieu post len thi kiem tra
        if($this->input->post())
        {
            $this->form_validation->set_rules('name', 'Tên', 'required');
    
            //nhập liệu chính xác
            if($this->form_validation->run())
            {
                $parent_id = $this->input->post('parent_id');
                 $data = array(
                    'name'      => $this->input->post('name'),
                    'name_url'  => $this->input->post('name_url'),
                    'parent_id' => $parent_id,
                    'sort_order' => intval($this->input->post('sort_order')),
                    'status' => intval($this->input->post('status'))
                );

                //kiem tra xem co ton tai the loai con ko: la cha=0 va list >1
                if($info->parent_id == 0) //neu la cha thi lay danh sach con
                {
                    //lay danh sach con
                    $input['where'] = array('parent_id' => $info->id);
                    $list_con = $this->table_model_d()->get_list($input); //dk la parent_id con == id cua cha    

                    if($list_con && $parent_id != 0) //neu ton tai va co thay doi the loai
                    {
                        //tạo ra nội dung thông báo
                        $this->session->set_flashdata('message', 'Thể loại '.$info->name.' có chứa thể loại con, bạn cần di chuyển các thể loại con ra khỏi thể loại này.');
                        
                        redirect(admin_url('catalog'));
                       
                    }             
                }
                

                //update vao csdl
                if($this->table_model_d()->update($id, $data))
                {
                    //tạo ra nội dung thông báo
                    $this->session->set_flashdata('message', 'Cập nhật dữ liệu thành công');
                }else{
                    $this->session->set_flashdata('message', 'Không cập nhật được');
                }
                //chuyen tới trang danh sách
                redirect(admin_url('catalog'));
            }
        }
    
        //lay danh sach danh muc cha
        $input = array();
        $input['where'] = array('parent_id' => 0);
        $list = $this->table_model_d()->get_list($input);
        $this->data['list']  = $list;
        $this->data['get_id']  = $id;

        //lay nội dung của biến message
        $message = $this->session->flashdata('message');
        $this->data['message'] = $message;
    
        $this->data['temp'] = 'admin/catalog/edit';
        $this->load->view('admin/main', $this->data);
    }

    
}

