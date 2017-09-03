<?php
Class Catalog extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('catalog_model');
    }
    
    /*
     * Lay ra danh sach the loai
     */
    function index()
    {

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
        
        //lay nội dung của biến message
        $message = $this->session->flashdata('message');
        $this->data['message'] = $message;
        
        //load view
        $this->data['temp'] = 'admin/catalog/index';
        $this->load->view('admin/main', $this->data);
    }
    
    /*
     * Them moi du lieu
     */
    function add()
    {
        //load thư viện validate dữ liệu
        $this->load->library('form_validation');
        $this->load->helper('form');
        
        //neu ma co du lieu post len thi kiem tra
        if($this->input->post())
        {
            $this->form_validation->set_rules('name', 'Thể loại', 'required');
            
            //nhập liệu chính xác
            if($this->form_validation->run())
            {
                //them vao csdl                
                //luu du lieu can them
                $parent_id = $this->input->post('parent_id');
                $data = array(
                    'name'      => $this->input->post('name'),
                    'name_url'  => $this->input->post('name_url'),
                    'parent_id' => $parent_id,
                    'sort_order' => intval($this->input->post('sort_order')),
                    'status' => intval($this->input->post('status'))
                );
                /***********************************************
                //kiem tra xem co bai viet va chua the loai con hay ko
                $this->load->model('posts_model');
                $post = $this->posts_model->get_info_rule(array('catalog_id' => $parent_id)); //pre($post->title) ;             
                if($post)
                {
                    //tạo ra nội dung thông báo
                    $this->session->set_flashdata('message', 'Thể loại '.$this->input->post('name').' không thể thêm vào một thể loại có chứa bài viết.');
                   
                    redirect(admin_url('catalog/add')); //o lai trang nay va thong bao
                   
                }
                ******************************************/
                //them moi vao csdl
                if($this->catalog_model->create($data))
                {
                    //tạo ra nội dung thông báo
                    $this->session->set_flashdata('message', 'Thêm mới dữ liệu thành công');
                }else{
                    $this->session->set_flashdata('message', 'Không thêm được');
                }
                //chuyen tới trang danh sách
                redirect(admin_url('catalog'));
            }
        }
        
        //lay danh sach danh muc cha
        $input = array();
        $input['where'] = array('parent_id' => 0);
        $list = $this->catalog_model->get_list($input);
        $this->data['list']  = $list;

        //lay nội dung của biến message
        $message = $this->session->flashdata('message');
        $this->data['message'] = $message;
        
        $this->data['temp'] = 'admin/catalog/add';
        $this->load->view('admin/main', $this->data);
    }
    
    /*
     * Cập nhật du lieu
     */
    function edit()
    {
        //load thư viện validate dữ liệu
        $this->load->library('form_validation');
        $this->load->helper('form');
    
        //lay id danh mục
        $id = $this->uri->rsegment(3);
        $info = $this->catalog_model->get_info($id);
        if(!$info)
        {
            //tạo ra nội dung thông báo
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
                    $list_con = $this->catalog_model->get_list($input); //dk la parent_id con == id cua cha    

                    if($list_con && $parent_id != 0) //neu ton tai va co thay doi the loai
                    {
                        //tạo ra nội dung thông báo
                        $this->session->set_flashdata('message', 'Thể loại '.$info->name.' có chứa thể loại con, bạn cần di chuyển các thể loại con ra khỏi thể loại này.');
                        
                        redirect(admin_url('catalog'));
                       
                    }             
                }
                
                /***********************************************
                 //kiem tra xem co bai viet va chua the loai con hay ko
                $this->load->model('posts_model');
                $post = $this->posts_model->get_info_rule(array('catalog_id' => $parent_id)); //pre($post->title) ;             
                if($post)
                {
                    //tạo ra nội dung thông báo
                    $this->session->set_flashdata('message', 'Thể loại '.$info->name.' không thể thêm vào một thể loại có chứa bài viết.');
                   
                    redirect(admin_url('catalog/edit/'.$id)); //o lai trang nay va thong bao
                   
                }
               ***********************************************/
               

                //update vao csdl
                if($this->catalog_model->update($id, $data))
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
        $list = $this->catalog_model->get_list($input);
        $this->data['list']  = $list;
        $this->data['get_id']  = $id;

        //lay nội dung của biến message
        $message = $this->session->flashdata('message');
        $this->data['message'] = $message;
    
        $this->data['temp'] = 'admin/catalog/edit';
        $this->load->view('admin/main', $this->data);
    }
    
    /*
     * Xoa danh mục
     */
    function delete()
    {
        //lay id danh mục
        $id = $this->uri->rsegment(3);
        //pre($id);
        $this->_del($id);
        
        //tạo ra nội dung thông báo
        $this->session->set_flashdata('message', 'Xóa dữ liệu thành công');
        redirect(admin_url('catalog'));
    }
    
   /*
     * Thuc hien xoa
     */
    private function _del($id, $rediect = true)
    {
        //lay thong tin catalog
        $info = $this->catalog_model->get_info($id);
        if(!$info)
        {
            //tạo ra nội dung thông báo
            $this->session->set_flashdata('message', 'không tồn tại danh mục này');
            if($rediect)
            {
                redirect(admin_url('catalog'));
            }else{
                return false;
            }
        }
        
        //kiem tra xem co bai viet nao trong the loai nay ko
        $this->load->model('posts_model');
        $posts = $this->posts_model->get_info_rule(array('catalog_id' => $id), 'id'); 
        if($posts)
        {
            //tạo ra nội dung thông báo
            $this->session->set_flashdata('message', 'Thể loại '.$info->name.' có chứa bài viết, bạn cần xóa các bài viết trước khi xóa thể loại này');
            if($rediect)
            {
                redirect(admin_url('catalog'));
            }
            else
            {
                return false;
            }
        }
        
        //kiem tra xem co the loai con nao trong the loai nay ko
        //$this->load->model('posts_model');
        //lay danh sach parent_id giong voi id tu url 
        $sub = $this->catalog_model->get_info_rule(array('parent_id' => $id), 'name');  //pre($sub);        
        if($sub)
        {
            //tạo ra nội dung thông báo
            $this->session->set_flashdata('message', 'Thể loại '.$info->name.' có chứa thể loại con, bạn cần di chuyển các thể loại con ra khỏi thể loại này.');
            if($rediect)
            {
                redirect(admin_url('catalog'));
            }
            else
            {
                return false;
            }
        }


        //xoa du lieu
        $this->catalog_model->delete($id);
        
    }
     /*
     * Xoa nhieu danh muc san pham
     */
    function delete_all()
    {
        $ids = $this->input->post('ids');
        foreach ($ids as $id)
        {
            $this->_del($id , false);
        }
    }
}

