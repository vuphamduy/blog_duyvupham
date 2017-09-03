<?php
Class Json_manager extends MY_controller
{

 var $path_json_one = "application/json/json_one/"; 
 var $path_json_two = "application/json/json_two/"; 

 function __construct()
 {
    parent::__construct();       
        // $this->tmp = 1;
}

function edit_json_footer_4c()
{
         //$file_name = $this->uri->rsegment('3'); //pre($file_name);
    $file_name = 'footer_hosendat.json';
    if(empty($file_name))
    {
        redirect(admin_url('json_manager'));
    }
        $fullfile = $this->path_json_two.$file_name; //.'.json';
        if(!file_exists($fullfile))
        {
            echo 'File này không tồn tại'; die;
            $this->session->set_flashdata('message', 'File này đã tồn tại.');
        }
        else{

            //tao file
            $json_file = fopen($fullfile, "r") or die("Unable to open file!");
            $content_file = file_get_contents($fullfile);

            $obj = json_decode($content_file);
            $arr = json_decode($content_file, true);

            fclose($json_file);
            $this->data['obj'] = $obj;
            $this->data['arr'] = $arr; //pre(count($arr));
            $this->data['file_name'] = $file_name; //.'.json';
        }

       //pre($arr); 

        $this->load->library('form_validation');
        $this->load->helper('form');
        if($this->input->post())
        {
            $this->form_validation->set_rules('name', 'Tiêu đề', 'required|alpha_dash');

            if($this->form_validation->run())
            {

                //$name = $this->input->post('name');
                //tao mang

                /*
                 "col_2": {
                "title": "tiêu đề 3",
                "link_title": "lien ket 3",
                "content": {
                    "row_1":{
                        "name":"name 1",
                        "link":"link 1"
                    },
                    "row_2":{
                        "name":"name 2",
                        "link":"link 2"
                    },
                    "row_3":{
                        "name":"name 3",
                        "link":"link 3"
                    }
                }
                
            },

                "col_3": {
                "title": "tiêu đề 3",
                "link_title": "lien ket 3",
                "content": {
                    "row_1":{
                        "name":"name 1",
                        "link":"link 1"
                    },
                    "row_2":{
                        "name":"name 2",
                        "link":"link 2"
                    },
                    "row_3":{
                        "name":"name 3",
                        "link":"link 3"
                    }
                }
                
            },
            */

            $array = array();
            for($i=1; $i<=count($arr); $i++){  
               if($this->input->post('dong_'.$i) > 0 )
               {
                   $arr_content = array();
                   $num_dong = $this->input->post('dong_'.$i); 
                         //pre(($num_dong)); 
                   for($j=1; $j<=$num_dong; $j++){  
                           // $array['col_'.$i]['content']['row_'.$j] = array(
                       $arr_content['row_'.$j] = array(
                        'name'         => $this->input->post('col_'.$i.'name_'.$j),
                        'link'    => $this->input->post('col_'.$i.'link_'.$j),
                        );
                   } 
                        //bi trung ten con nen lay ten cuoi cung

                         //pre($arr_content);
               }
               else{
                $arr_content = $this->input->post('content_'.$i);
            }

            
            $array['col_'.$i] = array(
                'title'         => $this->input->post('title_'.$i),
                'link_title'    => $this->input->post('link_title_'.$i),
                'content'         => $arr_content
                );
        } 

              // pre($array);
                //$fullfile = $this->path_json_two.$file_name; //.'.json';
        if(!file_exists($fullfile))
        {
            echo 'File này không tồn tại'; die;
                    //$this->session->set_flashdata('message', 'File này không tồn tại.');
        }
        else
        {
                   // echo 'File này không tồn tại';
                    //tao file
            $json_file = fopen($fullfile, "w+") or die("Unable to open file!");

                        //chuyen noi dung array sang dang Object cho json
                    //$json_obj = json_encode($array);
            $json_obj = json_encode($array, JSON_PRETTY_PRINT);
                        //ghi noi dung thanh cong va dong file
            fwrite($json_file, $json_obj);
            fclose($json_file);

                    //RENAME FILE rename($oldname, $newname);
            $name = $this->input->post('name');
            $fullfile_new = $this->path_json_two.$name.'.json';
            rename($fullfile, $fullfile_new);

                    // $this->session->set_flashdata('message', 'Đã cập nhật file: '.$name.'.json');
                    // redirect(admin_url('json_manager'));
                     //   check lai da tao thanh cong chua
            if(file_exists($fullfile_new))
            {
                            //echo 'Đã tạo file: '.$name.'.json'; die;
                $this->session->set_flashdata('message', 'Đã cập nhật file: '.$name.'.json');
                redirect(admin_url('json_manager'));
            }
            else{
                $this->session->set_flashdata('message', 'Chưa tạo được file.');
                redirect(admin_url('json_manager'));
            }

        }


        


    }

}

$message = $this->session->flashdata('message');
$this->data['message'] = $message;
$this->data['temp'] = 'admin/json_manager/edit_json_footer_4c';
$this->load->view('admin/main', $this->data);

}


 function add_row_children_footer()
    {


         //$list = $this->product_model->filter_product($catid, $brandid, $sale, $hold, $shine, $price);  
        // $student = array(
        //     '08T1016' => 'Phan Văn Cương',
        //     '08t1013' => 'Nguyễn Văn Hoàng',
        //     '08t1015' => 'Bùi Việt Đức',
        // );
        // foreach($student as $key=>$value){
        //   echo $key.'=>'.$value;
        // }

        if($this->input->post('tang')!='')  $tang = $this->input->post('tang');
        else $tang = 1;

        if(!$tang)
        {
            echo  "Khong ton tai";
        }
        else
        {
            echo 'vao'; die;
            echo $tang; die;

            $this->data['tang'] = $tang;
            $this->load->view('admin/json_manager/rowchildren_ajax', $this->data);
        }




    }
    

function index()
{
        // echo $this->path_json_one; die;

    $list_json_one = glob($this->path_json_one.'*');
    $list_json_two = glob($this->path_json_two.'*');

         //pre($filelist);
        // echo "<p>$path_json</p>";
        // echo '<ul>';
        // foreach ($filelist as $item) {
        //     echo '<li>' . $item . '</li>';
        // }
        // echo '</ul>';   // die;

    $this->data['path_json_one'] = $this->path_json_one;
    $this->data['list_json_one'] = $list_json_one;

    $this->data['path_json_two'] = $this->path_json_two;
    $this->data['list_json_two'] = $list_json_two;

    $message = $this->session->flashdata('message');
    $this->data['message'] = $message;

    $this->data['temp'] = 'admin/json_manager/index';
    $this->load->view('admin/main', $this->data);
}


function read_file_one()
{


        $file_name = $this->uri->rsegment('3'); //pre($file_name);
        if(empty($file_name))
        {
            redirect(admin_url('json_manager'));
        }
        //$path_json = "application/json/"; //pre($path_json);

        $fullfile = $this->path_json_one.$file_name; //.'.json';

        if(!file_exists($fullfile))
        {
            echo 'File này không tồn tại'; die;
            $this->session->set_flashdata('message', 'File này đã tồn tại.');
        }
        else{
           //echo 'File này đã tồn tại <br>'; //die;
            //tao file
            $json_file = fopen($fullfile, "r") or die("Unable to open file!");
            $content_file = file_get_contents($fullfile);

            $obj = json_decode($content_file);

            $arr = json_decode($content_file, true);
            //chuyen noi dung array sang dang Object cho json
           // $json_obj = json_encode($array);

            fclose($json_file);
            $this->data['obj'] = $obj;
            $this->data['arr'] = $arr;
            $this->data['file_name'] = $file_name; //.'.json';

        }


        // $message = $this->session->flashdata('message');
        // $this->data['message'] = $message;

        $this->data['temp'] = 'admin/json_manager/read_file_one';
        $this->load->view('admin/main', $this->data);
    }

    function read_file_two()
    {


        $file_name = $this->uri->rsegment('3'); //pre($file_name);
        if(empty($file_name))
        {
            redirect(admin_url('json_manager'));
        }
        //$path_json = "application/json/"; //pre($path_json);

        $fullfile = $this->path_json_two.$file_name; //.'.json';

        if(!file_exists($fullfile))
        {
            echo 'File này không tồn tại'; die;
            $this->session->set_flashdata('message', 'File này đã tồn tại.');
        }
        else{
           //echo 'File này đã tồn tại <br>'; //die;
            //tao file
            $json_file = fopen($fullfile, "r") or die("Unable to open file!");
            $content_file = file_get_contents($fullfile);

            $obj = json_decode($content_file);

            $arr = json_decode($content_file, true);
            //chuyen noi dung array sang dang Object cho json
           // $json_obj = json_encode($array);

            fclose($json_file);
            $this->data['obj'] = $obj;
            $this->data['arr'] = $arr;
            $this->data['file_name'] = $file_name; //.'.json';

        }


        // $message = $this->session->flashdata('message');
        // $this->data['message'] = $message;

        $this->data['temp'] = 'admin/json_manager/read_file_two';
        $this->load->view('admin/main', $this->data);
    }

    function ajax_add_content()
    {


         //$list = $this->product_model->filter_product($catid, $brandid, $sale, $hold, $shine, $price);  
        // $student = array(
        //     '08T1016' => 'Phan Văn Cương',
        //     '08t1013' => 'Nguyễn Văn Hoàng',
        //     '08t1015' => 'Bùi Việt Đức',
        // );
        // foreach($student as $key=>$value){
        //   echo $key.'=>'.$value;
        // }

        if($this->input->post('tang')!='')  $tang = $this->input->post('tang');
        else $tang = 1;

        if(!$tang)
        {
            echo  "Khong ton tai";
        }
        else
        {
            //echo $tang; die;

            $this->data['tang'] = $tang;
            $this->load->view('admin/json_manager/add_ajax', $this->data);
        }




    }


    function ajax_create_file_json_2chieu()
    {
        if($this->input->post('name')!='')  $name = $this->input->post('name');
        else $name = false;

        //obj khi qua ajax javascript => array
        if($this->input->post('obj')!='')  $arr = $this->input->post('obj');
        else $arr = '';

        // //echo "demo"; die;
        // prt(json_encode($arr)); die;

        // pre($arr);
        // die;

        if(!$name)
        {
            echo  "chua nhap ten file";
        }
        else
        {
           // echo 'vao 2: ';
           // echo $name; die;

           //check dir and check file
            // $path_json = "application/json/"; //pre($path_json);
             //$name = "sinhvien";
         $fullfile = $this->path_json_two.$name.'.json';
         if(file_exists($fullfile))
         {
                echo 'File này đã tồn tại'; //die;
                // $this->session->set_flashdata('message', 'File này đã tồn tại.');
            }
            else
            {
               // echo 'File này không tồn tại';
                //tao file
                $json_file = fopen($fullfile, "w+") or die("Unable to open file!");

                    //chuyen noi dung array sang dang Object cho json
                $json_obj = json_encode($arr, JSON_PRETTY_PRINT);

                //pre($json_obj);
                    //ghi noi dung
                fwrite($json_file, $json_obj);
                fclose($json_file);
                    //check lai da tao thanh cong chua
                if(file_exists($fullfile))
                {
                    echo 'Đã tạo file: '.$name.'.json'; 
                }
                else{
                    echo "Khong tim thay file nay";
                    
                }

            }

        }
    }






    function create_file_json_one()
    {

        // if(is_dir($path_json)){
        //     echo 'Folder Tồn Tại <br>'; 
        // }
        // else{
        //      echo 'Folder không Tồn Tại <br>';
        // }
        //die;


        $this->load->library('form_validation');
        $this->load->helper('form');
        if($this->input->post())
        {
            $this->form_validation->set_rules('name', 'Tiêu đề', 'required|alpha_dash');
            $this->form_validation->set_rules('title', 'Tiêu đề', 'required|min_length[2]');
            
            if($this->form_validation->run())
            {

                $name = $this->input->post('name');

                $title = $this->input->post('title');
                $image_link = $this->input->post('image_link');
                $link = $this->input->post('link');

                $array = array(  
                    'title'      =>  $title,
                    'image_link'      =>  $image_link,
                    'link'          => $link
                    );


                //check dir and check file
                // $path_json = "application/json/"; //pre($path_json);
                 //$name = "sinhvien";
                $fullfile = $this->path_json_one.$name.'.json';
                if(file_exists($fullfile))
                {
                    echo 'File này đã tồn tại'; die;
                   //$this->session->set_flashdata('message', 'File này đã tồn tại.');
                }
                else
                {
                   // echo 'File này không tồn tại';
                    //tao file
                    $json_file = fopen($fullfile, "w+") or die("Unable to open file!");

                        //chuyen noi dung array sang dang Object cho json
                    $json_obj = json_encode($array, JSON_PRETTY_PRINT);
                        //ghi noi dung
                    fwrite($json_file, $json_obj);
                    fclose($json_file);
                        //check lai da tao thanh cong chua
                    if(file_exists($fullfile))
                    {
                            //echo 'Đã tạo file: '.$name.'.json'; die;
                        $this->session->set_flashdata('message', 'Đã tạo file: '.$name.'.json');
                        redirect(admin_url('json_manager'));
                    }
                    else{
                        $this->session->set_flashdata('message', 'Chưa tạo được file.');
                        redirect(admin_url('json_manager'));
                    }

                }


            }

        }

        $message = $this->session->flashdata('message');
        $this->data['message'] = $message;
        $this->data['temp'] = 'admin/json_manager/create_one';
        $this->load->view('admin/main', $this->data);
    }




    function edit_file_json_two()
    {
         $file_name = $this->uri->rsegment('3'); //pre($file_name);
         if(empty($file_name))
         {
            redirect(admin_url('json_manager'));
        }
        //$path_json = "application/json/"; //pre($path_json);

        $fullfile = $this->path_json_two.$file_name; //.'.json';

        if(!file_exists($fullfile))
        {
            echo 'File này không tồn tại'; die;
            $this->session->set_flashdata('message', 'File này đã tồn tại.');
        }
        else{
           //echo 'File này đã tồn tại <br>'; //die;
            //tao file
            $json_file = fopen($fullfile, "r") or die("Unable to open file!");
            $content_file = file_get_contents($fullfile);

            $obj = json_decode($content_file);

            $arr = json_decode($content_file, true);
            //chuyen noi dung array sang dang Object cho json
           // $json_obj = json_encode($array);

            fclose($json_file);
            $this->data['obj'] = $obj;
            $this->data['arr'] = $arr; //pre(count($arr));
            $this->data['file_name'] = $file_name; //.'.json';

        }

       //pre($arr); 

        $this->load->library('form_validation');
        $this->load->helper('form');
        if($this->input->post())
        {
            $this->form_validation->set_rules('name', 'Tiêu đề', 'required|alpha_dash');

            if($this->form_validation->run())
            {

                //$name = $this->input->post('name');

                //tao mang
                $array = array();
                for($i=1; $i<=count($arr); $i++){  

                    $array['lop_'.$i] = array(
                        'title'         => $this->input->post('title_'.$i),
                        'image_link'    => $this->input->post('image_link_'.$i),
                        'link'         => $this->input->post('link_'.$i)
                        );
                } 

               //pre($array);
                //$fullfile = $this->path_json_two.$file_name; //.'.json';
                if(!file_exists($fullfile))
                {
                    echo 'File này không tồn tại'; die;
                    //$this->session->set_flashdata('message', 'File này không tồn tại.');
                }
                else
                {
                   // echo 'File này không tồn tại';
                    //tao file
                    $json_file = fopen($fullfile, "w+") or die("Unable to open file!");

                        //chuyen noi dung array sang dang Object cho json
                    //$json_obj = json_encode($array);
                    $json_obj = json_encode($array, JSON_PRETTY_PRINT);
                        //ghi noi dung thanh cong va dong file
                    fwrite($json_file, $json_obj);
                    fclose($json_file);

                    //RENAME FILE rename($oldname, $newname);
                    $name = $this->input->post('name');
                    $fullfile_new = $this->path_json_two.$name.'.json';
                    rename($fullfile, $fullfile_new);

                    // $this->session->set_flashdata('message', 'Đã cập nhật file: '.$name.'.json');
                    // redirect(admin_url('json_manager'));
                     //   check lai da tao thanh cong chua
                    if(file_exists($fullfile_new))
                    {
                            //echo 'Đã tạo file: '.$name.'.json'; die;
                        $this->session->set_flashdata('message', 'Đã cập nhật file: '.$name.'.json');
                        redirect(admin_url('json_manager'));
                    }
                    else{
                        $this->session->set_flashdata('message', 'Chưa tạo được file.');
                        redirect(admin_url('json_manager'));
                    }

                }


                


            }

        }

        $message = $this->session->flashdata('message');
        $this->data['message'] = $message;
        $this->data['temp'] = 'admin/json_manager/edit_two';
        $this->load->view('admin/main', $this->data);

    }






    // ok
    function create_file_json_two()
    {

        // if(is_dir($path_json)){
        //     echo 'Folder Tồn Tại <br>'; 
        // }
        // else{
        //      echo 'Folder không Tồn Tại <br>';
        // }
        //die;


        $this->load->library('form_validation');
        $this->load->helper('form');
        if($this->input->post())
        {
            $this->form_validation->set_rules('name', 'Tiêu đề', 'required|alpha_dash');

            if($this->form_validation->run())
            {

                $name = $this->input->post('name');

                $intro = $this->input->post('intro');


            // $image_link = $this->input->post('image_link');
            // $link = $this->input->post('link');

               /* $array = array(  
                'title'      =>  'title_1',
                'image_link'      =>  'image_link_1',
                'link'          => 'link_1'
                );


                //check dir and check file
                 //$path_json = "application/json/"; //pre($path_json);
                 //$name = "sinhvien";
                 $fullfile = $this->path_json_one.$name.'.json';
                 if(file_exists($fullfile))
                 {
                    echo 'File này đã tồn tại'; die;
                     $this->session->set_flashdata('message', 'File này đã tồn tại.');
                 }
                 else
                 {
                       // echo 'File này không tồn tại';
                        //tao file
                        $json_file = fopen($fullfile, "w+") or die("Unable to open file!");

                            //chuyen noi dung array sang dang Object cho json
                      //  $json_obj = json_encode($intro);


                            //ghi noi dung
                        // fwrite($json_file, $json_obj);
                        fwrite($json_file, $intro);
                        fclose($json_file);
                            //check lai da tao thanh cong chua
                        if(file_exists($fullfile))
                        {
                                //echo 'Đã tạo file: '.$name.'.json'; die;
                            $this->session->set_flashdata('message', 'Đã tạo file: '.$name.'.json');
                            redirect(admin_url('json_manager'));
                        }
                        else{
                            $this->session->set_flashdata('message', 'Chưa tạo được file.');
                            redirect(admin_url('json_manager'));
                        }

                    }*/


                }

            }

            $message = $this->session->flashdata('message');
            $this->data['message'] = $message;
            $this->data['temp'] = 'admin/json_manager/create_two';
            $this->load->view('admin/main', $this->data);
        }


        function edit_file_json_one()
        {

        //doc va load file
        $file_name = $this->uri->rsegment('3'); //pre($file_name);
        if(empty($file_name))
        {
            redirect(admin_url('json_manager'));
        }
       // $path_json = "application/json/"; //pre($path_json);

        $fullfile = $this->path_json_one.$file_name; //.'.json'; 
        //pre($fullfile);

        if(!file_exists($fullfile))
        {
            echo 'File này không tồn tại'; die;
           // $this->session->set_flashdata('message', 'File này đã tồn tại.');
        }
        else{
           //echo 'File này đã tồn tại <br>'; //die;
            //chi doc file
            $json_file = fopen($fullfile, "r") or die("Unable to open file!");
            $content_file = file_get_contents($fullfile);

            //$obj = json_decode($content_file);

            $arr = json_decode($content_file, true);
            //chuyen noi dung array sang dang Object cho json
           // $json_obj = json_encode($array);

            fclose($json_file);
            //$this->data['obj'] = $obj;
            $this->data['arr'] = $arr;
            $this->data['file_name'] = $file_name; //.'.json';
            // echo $arr['title'];
            // die;


        }


        $this->load->library('form_validation');
        $this->load->helper('form');
        if($this->input->post())
        {
            $this->form_validation->set_rules('name', 'Tiêu đề', 'required|alpha_dash');
            $this->form_validation->set_rules('title', 'Tiêu đề', 'required|min_length[2]');
            
            if($this->form_validation->run())
            {



                $title = $this->input->post('title');
                $image_link = $this->input->post('image_link');
                $link = $this->input->post('link');

                $array = array(  
                    'title'      =>  $title,
                    'image_link'      =>  $image_link,
                    'link'          => $link
                    );

                //check dir and check file
               //  $path_json = "application/json/"; //pre($path_json);
                 //$name = "sinhvien";
                // $fullfile = $path_json.$file_name;

                if(!file_exists($fullfile))
                {
                    echo 'File này không tồn tại'; die;
                    //$this->session->set_flashdata('message', 'File này không tồn tại.');
                }
                else
                {
                   // echo 'File này không tồn tại';
                    //tao file
                    $json_file = fopen($fullfile, "w+") or die("Unable to open file!");

                        //chuyen noi dung array sang dang Object cho json
                    $json_obj = json_encode($array);
                        //ghi noi dung thanh cong va dong file
                    fwrite($json_file, $json_obj);
                    fclose($json_file);

                    //RENAME FILE rename($oldname, $newname);
                    $name = $this->input->post('name');
                    $fullfile_new = $this->path_json_one.$name.'.json';
                    rename($fullfile, $fullfile_new);

                    // $this->session->set_flashdata('message', 'Đã cập nhật file: '.$name.'.json');
                    // redirect(admin_url('json_manager'));
                     //   check lai da tao thanh cong chua
                    if(file_exists($fullfile_new))
                    {
                            //echo 'Đã tạo file: '.$name.'.json'; die;
                        $this->session->set_flashdata('message', 'Đã cập nhật file: '.$name.'.json');
                        redirect(admin_url('json_manager'));
                    }
                    else{
                        $this->session->set_flashdata('message', 'Chưa tạo được file.');
                        redirect(admin_url('json_manager'));
                    }

                }


            }

        }

        $message = $this->session->flashdata('message');
        $this->data['message'] = $message;
        $this->data['temp'] = 'admin/json_manager/one_edit';
        $this->load->view('admin/main', $this->data);
    }






    function delete_json()
    {

         //doc va load file
        $file_name = $this->uri->rsegment('3'); //pre($file_name);
        if(empty($file_name))
        {
            redirect(admin_url('json_manager'));
        }

         //alert cau canh bao xoa
        if(file_exists($this->path_json_one.$file_name))
        {
            unlink($this->path_json_one.$file_name);
            $this->session->set_flashdata('message', 'Đã xóa thành công file: '.$file_name);
            redirect(admin_url('json_manager#tab_1'));
        }
        elseif(file_exists($this->path_json_two.$file_name))
        {
            unlink($this->path_json_two.$file_name);
            $this->session->set_flashdata('message', 'Đã xóa thành công file: '.$file_name);
            redirect(admin_url('json_manager#tab_2'));
        }
        else{

            echo 'File này không tồn tại'; die;
            $this->session->set_flashdata('message', 'File này đã tồn tại.');

        }

    }



}
