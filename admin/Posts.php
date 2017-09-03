<?php
Class Posts extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        //load ra file model
        $this->load->model('posts_model');
        $this->load->model('catalog_model');
    }



    function index()
    {
        // $username = $this->session->userdata("username");
        // echo $username; die;
         if($this->input->get('id')=="" && $this->input->get('title')=="" && $this->input->get('catalog')=="")
        {
            //pre("rong");
            $total_rows = $this->posts_model->get_total();
            $this->data['total_rows'] = $total_rows;            
            //load ra thu vien phan trang
            $this->load->library('pagination');
            $config = array();
            $config['total_rows'] = $total_rows;//tong tat ca cac san pham tren website
            $config['base_url']   = admin_url('posts/index'); //link hien thi ra danh sach san pham
            //$page=$this->uri->segment(4)?$this->uri->segment(4):1;    
            if($this->uri->segment(4) && $this->uri->segment(4)>0)
            {
                 $page=$this->uri->segment(4);
            }
            else
            {
                 $page = 1;
            } 
            $config['per_page']   = 20;//so luong san pham hien thi tren 1 trang
            $config['uri_segment'] = 4;//phan doan hien thi ra so trang tren url
            $config['use_page_numbers']=true;

             //CSS
          //$config['suffix'] = '.html'; //phai giong trong file config
            $config['next_link']   = 'Next';
            $config['prev_link']   = 'Prev';
            $config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
            $config['full_tag_close'] = '</ul>';
            $config['first_link'] = 'First';
            $config['last_link'] = 'Last';
            $config['first_tag_open'] = '<li>';
            $config['first_tag_close'] = '</li>';
            $config['last_tag_open'] = '<li>';
            $config['last_tag_close'] = '</li>';
            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';
            $config['prev_tag_open'] = '<li>';
            $config['prev_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li><a style="color: #fff; background-color: #337ab7; border-color: #337ab7;">';
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';
            //$config['attributes'] = array('class' => 'pagination pagination-sm no-margin pull-right');       

            //khoi tao cac cau hinh phan trang
            $this->pagination->initialize($config);
           // $page=$this->uri->segment(4)?$this->uri->segment(4):1;
            $page = intval($page);
            $start=($page-1)*$config['per_page']; 
            //pre($start);
            $input = array();
            $input['limit'] = array($config['per_page'], $start);
              //stt
            $this->data['stt']= ($page*$config['per_page'])-$config['per_page']+1;

            $this->data['LinkPage']=$this->pagination->create_links();
            //end phan trang
         }
         else
         {
            $this->data['stt']=1;
         }
        
	   //$this->data['stt']= ($page*$config['per_page'])-$config['per_page']+1;
         //kiem tra co thuc hien loc du lieu hay khong
        $id = $this->input->get('id');
        //pre($id);
        $id = intval($id);
        $input['where'] = array();
        if($id > 0)
        {
            $input['where']['id'] = $id;
        }

        $title = $this->input->get('title');
        if($title)
        {
            $input['like'] = array('title', $title);
        }

        $catalog_id = $this->input->get('catalog');
        
        $catalog_id = intval($catalog_id);
        if($catalog_id > 0)
        {
            $input['where']['catalog_id'] = $catalog_id;
        }        
        //lay danh sach bai viet theo the loai
        $list = $this->posts_model->get_list($input);
        $this->data['list'] = $list;
        

        //do list catalog 
        $cat = $this->catalog_model->get_list();
        $this->data['cat'] = $cat;
        //pre($cat);
       
        //lay the loai
        $input = array();
        $input['where'] = array('parent_id' => 0);
        $catalogs = $this->catalog_model->get_list($input); //$input chi la dieu kien loc
        foreach ($catalogs as $row)
        {
            $input['where'] = array('parent_id' => $row->id);
            $subs = $this->catalog_model->get_list($input);
            $row->subs = $subs;
        }
        $this->data['catalogs'] = $catalogs;


        //$this->data['LinkPage']=$this->pagination->create_links();
        //lay nội dung của biến message
        $message = $this->session->flashdata('message');
        $this->data['message'] = $message;
        
        //load view
        $this->data['temp'] = 'admin/posts/index';
        $this->load->view('admin/main', $this->data);
    }

    function add()
    {
		
        $this->load->model('catalog_model');
        $input = array();
        $input['where'] = array('parent_id' => 0);
        $catalogs = $this->catalog_model->get_list($input);
        foreach ($catalogs as $row)
        {
            $input['where'] = array('parent_id' => $row->id);
            $subs = $this->catalog_model->get_list($input);
            $row->subs = $subs;
        }
        $this->data['catalogs'] = $catalogs;
        
        //load thư viện validate dữ liệu
        $this->load->library('form_validation');
        $this->load->helper('form');
        
        //neu ma co du lieu post len thi kiem tra
        if($this->input->post())
        {
            $this->form_validation->set_rules('title', 'Tiêu đề', 'required|min_length[2]');
            $this->form_validation->set_rules('title_url', 'URL tiêu đề', 'required|min_length[2]');
            $this->form_validation->set_rules('catalog_id', 'Thể loại', 'required');


            //nhập liệu chính xác
            if($this->form_validation->run())
            {
               
              
                // //lay ten file anh minh hoa duoc update len
                // $this->load->library('upload_library');
                // $upload_path = './upload/posts';
                // $upload_data = $this->upload_library->upload($upload_path, 'image_link');  //ten form
                // $image_link = '';
                // if(isset($upload_data['file_name']))
                // {
                //     $image_link = $upload_data['file_name'];
                // }
                //upload cac anh kem theo
                // $image_list = array();
                // $image_list = $this->upload_library->upload_file($upload_path, 'image_list');
                // $image_list = json_encode($image_list);
                
                $status         = $this->input->post('status'); //ko checked thi ko co gia tri
                $intro         = $this->input->post('intro'); //ko checked thi ko co gia tri
                $meta_key         = $this->input->post('meta_key'); //ko checked thi ko co gia tri
               if(!$status)
               {
                    $status = 0;
               }
               // if($meta_key==''){
               //  $meta_key =''
               // }

                //$replace_content = $this->input->post('content');

                // /////////////// TIM VA THAY THE ////////////////////
                //////////////////////////////////////

                $replace_content = str_replace('h2', 'h4', $this->input->post('content'));
                $replace_content = str_replace('h3', 'b', $replace_content);
                ////////////////////////////////////////

                //luu du lieu can them
                $data = array(
                    'title'          => $this->input->post('title'),                    
                    'title_url'      => $this->input->post('title_url'),
                    'image_link'      => $this->input->post('image_link'),
                    'link_url'      => $this->input->post('link_url'),
                     'link_demo'      => $this->input->post('link_demo'),
                    'catalog_id'     => $this->input->post('catalog_id'),
                    'intro'          => $intro,
                    'content'        => $replace_content,
                    'status'        => $status,
                    'meta_desc'     => $intro,
                    'meta_key'      => $meta_key,
                    'created'       => date('Y-m-d')
                );
                //them moi vao csdl
                if($this->posts_model->create($data))
                {
                    //tạo ra nội dung thông báo
                    $this->session->set_flashdata('message', 'Thêm mới dữ liệu thành công');
                }
                else
                {
                    $this->session->set_flashdata('message', 'Không thêm được');
                }
                //chuyen tới trang danh sách
                redirect(admin_url('posts'));            }
        }
        //load view
        $this->data['temp'] = 'admin/posts/add';
        $this->load->view('admin/main', $this->data);
    }
    
      /*
     * Chinh sua bài viết
     */
    function edit()
    {
        $id = $this->uri->rsegment('3');
        $posts = $this->posts_model->get_info($id);
        if(!$posts)
        {
            //tạo ra nội dung thông báo
            $this->session->set_flashdata('message', 'Không tồn tại bài viết này');
            redirect(admin_url('posts'));
        }
        $this->data['posts'] = $posts; 
         //pre($posts);
          //lay danh sach the loai theo nhom cha
        $this->load->model('catalog_model');
        $input = array();
        $input['where'] = array('parent_id' => 0);
        $catalogs = $this->catalog_model->get_list($input);
        foreach ($catalogs as $row)
        {
            $input['where'] = array('parent_id' => $row->id);
            $subs = $this->catalog_model->get_list($input);
            $row->subs = $subs;
        }
        $this->data['catalogs'] = $catalogs;  
       
        //load thư viện validate dữ liệu
        $this->load->library('form_validation');
        $this->load->helper('form');
        
        //neu ma co du lieu post len thi kiem tra
        if($this->input->post())
        {
            $this->form_validation->set_rules('title', 'Tiêu đề', 'required');
            $this->form_validation->set_rules('catalog_id', 'Thể loại', 'required');
            
            //nhập liệu chính xác
            if($this->form_validation->run())
            {
               
                // //lay ten file anh minh hoa duoc update len
                // $this->load->library('upload_library');
                // $upload_path = './upload/posts';

                // $upload_data = $this->upload_library->upload($upload_path, 'image_link');  //ten form
                // //neu upload ko duong thi in duong dan ra
                // //pre( $upload_data);
                // $image_link = '';
                // if(isset($upload_data['file_name']))
                // {
                //     $image_link = $upload_data['file_name'];
                // }
                // //upload cac anh kem theo
                // $image_list = array();
                // $image_list = $this->upload_library->upload_file($upload_path, 'image_list');
                // $image_list = json_encode($image_list);

               
                $intro         = $this->input->post('intro'); //ko checked thi ko co gia tri
                $meta_key         = $this->input->post('meta_key'); //ko checked thi ko co gia tri

                // /////////////// TIM VA THAY THE ////////////////////
                //////////////////////////////////////
                // $replace_content = $this->input->post('content');
                 $replace_content = str_replace('h2', 'h4', $this->input->post('content'));
                 $replace_content = str_replace('h3', 'b', $replace_content);
                ////////////////////////////////////////

                //luu du lieu can them
                $data = array(
                    'title'      => $this->input->post('title'),                    
                    'title_url'  => $this->input->post('title_url'),
                    'catalog_id' => $this->input->post('catalog_id'),
                    
                     'link_url'      => $this->input->post('link_url'),
                     'link_demo'      => $this->input->post('link_demo'),
                    'intro'      => $intro,
                    'content'    => $replace_content,
                    'status'     => $this->input->post('status'),
                    'meta_desc'  => $intro,
                    'meta_key'   => $meta_key,
                    'updated'    => date('Y-m-d')
                    );
                
                 if($this->input->post('image_link') !='')
                {
                   // $data = array('image_link' => $this->input->post('image_link'));
                     $data['image_link'] = $this->input->post('image_link');
                }
                
                //cap nhat vao csdl
                if($this->posts_model->update($posts->id, $data))
                {
                    //tạo ra nội dung thông báo
                    $this->session->set_flashdata('message', 'Cập nhật dữ liệu thành công');
                }
                else
                {
                    $this->session->set_flashdata('message', 'Không cập nhật được');
                }
                //chuyen tới trang danh sách
                redirect(admin_url('posts?id='.$posts->id));
            }
        }    
        //load view
        $this->data['temp'] = 'admin/posts/edit';
        $this->load->view('admin/main', $this->data);
    }   

     /*
     * Xoa du lieu
     */
    function del()
    {
        $id = $this->uri->rsegment(3);
        $this->_del($id);
        
        //tạo ra nội dung thông báo
        $this->session->set_flashdata('message', 'Xóa bài viết thành công');
        redirect(admin_url('posts'));
    }

     /*
     *Xoa san pham
     */
    private function _del($id)
    {
        $posts = $this->posts_model->get_info($id);
        if(!$posts)
        {
            //tạo ra nội dung thông báo
            $this->session->set_flashdata('message', 'không tồn tại bài viết này');
            redirect(admin_url('posts'));
        }
        //thuc hien xoa posts
        $this->posts_model->delete($id);

         //xoa cac anh cua san pham
        $image_link = './upload/posts/'.$posts->image_link;
        if(file_exists($image_link))
        {
            unlink($image_link);
        }
        //xoa cac anh kem theo cua san pham
        $image_list = json_decode($posts->image_list);
        if(is_array($image_list))
        {
            foreach ($image_list as $img)
            {
                $image_link = './upload/posts/'.$img;
                if(file_exists($image_link))
                {
                    unlink($image_link);
                }
            }
        }

        //echo "is_array da vuot"; die;

    }
    
    /*
     * Xóa nhiều sản phẩm
     */
    function delete_all()
    {
        $ids = $this->input->post('ids');
        foreach ($ids as $id)
        {
            $this->_del($id);
        }
    }
    
   
    
}
