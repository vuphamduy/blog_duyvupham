<?php
Class Filemanager extends MY_Controller
{
  
    public function __construct ()
    {
        parent::__construct();
        
    }
    public function index()
    {
        
        //  $path_view = $this->base_view_admin->get_path_view('admin/filemanager');
        // $content = $this->load->view($path_view,'',true);
        // $this->base_view_admin
        //         ->set_content($content)
        //         ->set_layout('main')
        //         ->view();

        //echo 'ok'; die;
        $this->data['temp'] = 'admin/filemanager/view';
        $this->load->view('admin/main', $this->data);
    }
    public function show()
    {
        $ckeditor_js ='';
        if($this->input->get('extend'))
        {
            $extend = $this->input->get('extend');
            $ckeditor_js = "<script src='/public/filemanager/".$extend.".js'></script>";
            
            // include("public/filemanager/ckeditor.js");
        }
        $this->data['ckeditor_js'] = $ckeditor_js;
        $this->data['temp'] = 'admin/filemanager/view';
        $this->load->view('admin/filemanager/layout', $this->data);
    }

    public function deleteFile() {
        $this->load->helper('file');
        $pathFile = $this->input->post('pathFile');
        $result = array();
        //  echo $pathFile;die();
        $check = unlink("." . $pathFile);
        if ($check) {
            $result['status'] = "success";
            $result['message'] = "Delete success";
        } else {
            $result['status'] = "error";
            $result['message'] = "Delete error";
        }
        echo json_encode($result);
    }

    public function upload() {
        $config['upload_path'] = './' . $this->input->post('path');
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '102400';
        $config['max_width'] = '102400';
        $config['max_height'] = '76800';

        $this->load->library('upload', $config);
        $result = array();
        $nameInput = $this->input->post('name');
        if (!$this -> upload -> do_upload($nameInput)) {
            $result['status'] = "error";
        } else {
            $result['status'] = "success";
        }
        echo json_encode($result);
    }

    public function renameFile() {
        $path = $this->input->post('path');

        $oldname = $this->input->post('oldname');
        $newname = $this->input->post('newname');
        $extention = $this->input->post('extention');
        $oldFile = './' . $path . '/' . $oldname . $extention;
        $newFile = './' . $path . '/' . $newname . $extention;

        $check = rename($oldFile, $newFile);
        if ($check) {
            $result['status'] = "success";
            $result['message'] = "Rename file success";
        } else {
            $result['status'] = "error";
            $result['message'] = "Rename file error";
        }

        echo json_encode($result);
    }

    public function delTree($dir) {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }

    public function deleteFolder() {
        $this->load->helper('file');
        $pathFolder = $this->input->post('pathFolder');
        $result = array();

        $check = $this->delTree("." . $pathFolder);

        if ($check) {
            $result['status'] = "success";
            $result['message'] = "Delete success";
        } else {
            $result['status'] = "error";
            $result['message'] = "Delete error";
        }
        echo json_encode($result);
    }

    public function renameFolder() {
        $path = $this->input->post('path');
        $oldname = $this->input->post('oldname');
        $newname = $this->input->post('newname');

        $oldFolder = './' . $path . '/' . $oldname;
        $newFolder = './' . $path . '/' . $newname;

        $check = rename($oldFolder, $newFolder);
        if ($check) {
            $result['status'] = "success";
            $result['message'] = "Rename folder success";
        } else {
            $result['status'] = "error";
            $result['message'] = "Rename folder error";
        }

        echo json_encode($result);
    }
    public function createFolder(){
        $path = $this->input->post('path');
        $name = $this->input->post('name');

        $pathFolder = "./".$path."/".$name;
        if(!is_dir($pathFolder)) //create the folder if it's not already exists
        {
            $check =  mkdir($pathFolder,0755,TRUE);
            if($check)
            {
              $result['status'] = "success";
              $result['message'] = "Create folder success";
            }
            else{
              $result['status'] = "error";
              $result['message'] = "Delete error";
            }
        }
        else{
            $result['status'] = "error";
            $result['message'] = "Folder already exists";
        }

        echo json_encode($result);
    }
    public function folder()
    {
      $this->load->helper('directory');

      $path = $this->input->post('path');
      $root = $this->input->post('root');

      if(!$path) $path = $root;
      $result = directory_map($path,1); // pre($path.'   '.$root);
         foreach ($result as  &$item) {$item = str_replace("\\","",$item);}
      echo json_encode($result);
    }

    public function copyFile()
    {
        $files = $this->input->post('files');
        $path = $this->input->post('path');
        try{
            foreach ($files as $file) {
                if(!copy($file,$path.basename($file)))
                {
                    $result['status'] = "error";
                    $result['message'] = "Copy file error ".$file;
                     echo json_encode($result);die();
                }
            }
            $result['status'] = "success";
            $result['message'] = "Copy file success";
             echo json_encode($result);die();
        }
        catch(Exception $e)
        {
            $result['status'] = "error";
            $result['message'] = "Copy file error";
             echo json_encode($result);die();
        }

    }


    public function cutFile()
    {
        $files = $this->input->post('files');
        $path = $this->input->post('path');
        try{
            foreach ($files as $file) {
                if(!rename($file,$path.basename($file)))
                {
                    $result['status'] = "error";
                    $result['message'] = "Cut file error ".$file;
                     echo json_encode($result);die();
                }
            }
            $result['status'] = "success";
            $result['message'] = "Cut file success";
             echo json_encode($result);die();
        }
        catch(Exception $e)
        {
            $result['status'] = "error";
            $result['message'] = "Cut file error";
             echo json_encode($result);die();
        }

    }

    public function deleteListFiles()
    {
        $files = $this->input->post('files');
        try{
            foreach ($files as $file) {
                if(!unlink("./".$file))
                {
                    $result['status'] = "error";
                    $result['message'] = "Delete file error ".$file;
                     echo json_encode($result);die();
                }
            }
            $result['status'] = "success";
            $result['message'] = "Delete file success";
             echo json_encode($result);die();
        }
        catch(Exception $e)
        {
            $result['status'] = "error";
            $result['message'] = "Delete file error";
             echo json_encode($result);die();
        }
    }
    
   
    
   
}



