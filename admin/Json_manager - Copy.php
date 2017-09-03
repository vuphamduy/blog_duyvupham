<?php
Class Json_manager extends MY_controller
{

    function index()
    {

        $path_json = "application/json/"; //pre($path_json);

        $filelist = glob($path_json.'*');
         //pre($filelist);
        // echo "<p>$path_json</p>";
        // echo '<ul>';
        // foreach ($filelist as $item) {
        //     echo '<li>' . $item . '</li>';
        // }
        // echo '</ul>';
        // die;
        $this->data['path_json'] = $path_json;
        $this->data['filelist'] = $filelist;


        $message = $this->session->flashdata('message');
        $this->data['message'] = $message;

        $this->data['temp'] = 'admin/json_manager/index';
        $this->load->view('admin/main', $this->data);
    }


    function read_file()
    {

        
        $file_name = $this->uri->rsegment('3'); //pre($file_name);
        if(empty($file_name))
        {
            redirect(admin_url('json_manager'));
        }
        $path_json = "application/json/"; //pre($path_json);

        $fullfile = $path_json.$file_name; //.'.json';

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

        $this->data['temp'] = 'admin/json_manager/read_file';
        $this->load->view('admin/main', $this->data);
    }





    function create_file_json()
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
                 $path_json = "application/json/"; //pre($path_json);
                 //$name = "sinhvien";
                 $fullfile = $path_json.$name.'.json';
                 if(file_exists($fullfile))
                 {
                    //echo 'File này đã tồn tại'; die;
                   $this->session->set_flashdata('message', 'File này đã tồn tại.');
               }
               else
               {
                   // echo 'File này không tồn tại';
                    //tao file
                    $json_file = fopen($fullfile, "w+") or die("Unable to open file!");

                        //chuyen noi dung array sang dang Object cho json
                    $json_obj = json_encode($array);
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
        $this->data['temp'] = 'admin/json_manager/add';
        $this->load->view('admin/main', $this->data);
    }


     function edit_file_json()
    {

        //doc va load file
        $file_name = $this->uri->rsegment('3'); //pre($file_name);
        if(empty($file_name))
        {
            redirect(admin_url('json_manager'));
        }
        $path_json = "application/json/"; //pre($path_json);

        $fullfile = $path_json.$file_name; //.'.json'; 
        //pre($fullfile);

        if(!file_exists($fullfile))
        {
            echo 'File này không tồn tại'; die;
            $this->session->set_flashdata('message', 'File này đã tồn tại.');
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
                    'lop1'      =>  $title,
                    'lop2'      =>  $image_link,
                    'lop3'          => $link
                    );

                //them mot phan tu trong mang mot chieu
                //$array['tacgia']= "Trần Thị Hằng";

                //THEM MANG MOT CHIEU
                 // $array[] = array(  
                 //    'title'      =>  "pham duy vu",
                 //    'image_link'      =>  "anh pham duy vu",
                 //    'link'          => "blogduyvupham"
                 //    );
                 //ket qua
                //  stdClass Object
                // (
                //     [title] => Đỗ Công Kiên - KT
                //     [image_link] => 
                //     [link] => link do cong lien
                //     [0] => stdClass Object
                //         (
                //             [title] => pham duy vu
                //             [image_link] => anh pham duy vu
                //             [link] => blogduyvupham
                //         )

                // )

                //THEM MANG 2 CHIEU

                  $array['lop1'] = array(  
                    'title'      =>  "pham duy vu",
                    'image_link'      =>  "anh pham duy vu",
                    'link'          => "blogduyvupham"
                    );

                   $array['lop2'] = array(  
                    'title'      =>  "pham duy vu",
                    'image_link'      =>  "anh pham duy vu",
                    'link'          => "blogduyvupham"
                    );

                    $array['lop3'] = array(  
                    'title'      =>  "pham duy vu",
                    'image_link'      =>  "anh pham duy vu",
                    'link'          => "blogduyvupham"
                    );


                 // pre($array);

                // $obj = json_encode($array);
                echo "<pre>";
                 pre(json_encode($array));

                 echo "</pre>";

                 die;
                //check dir and check file
                 $path_json = "application/json/"; //pre($path_json);
                 //$name = "sinhvien";
                 $fullfile = $path_json.$file_name;



                 if(!file_exists($fullfile))
                 {
                    echo 'File này không tồn tại'; die;
                   $this->session->set_flashdata('message', 'File này không tồn tại.');
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
                    $fullfile_new = $path_json.$name.'.json';
                    rename($fullfile, $fullfile_new);

                    $this->session->set_flashdata('message', 'Đã cập nhật file: '.$name.'.json');
                    redirect(admin_url('json_manager'));
                        //check lai da tao thanh cong chua
                    // if(file_exists($fullfile))
                    // {
                    //         //echo 'Đã tạo file: '.$name.'.json'; die;
                    //     $this->session->set_flashdata('message', 'Đã cập nhật file: '.$name.'.json');
                    //     redirect(admin_url('json_manager'));
                    // }
                    // else{
                    //     $this->session->set_flashdata('message', 'Chưa tạo được file.');
                    //     redirect(admin_url('json_manager'));
                    // }

                }


            }

        }

        $message = $this->session->flashdata('message');
        $this->data['message'] = $message;
        $this->data['temp'] = 'admin/json_manager/edit';
        $this->load->view('admin/main', $this->data);
    }







}
