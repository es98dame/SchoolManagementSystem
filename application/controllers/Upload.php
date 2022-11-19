<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Upload extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
    }

    public function index() {
    }

    public function do_upload()
    {
        $config = array(
            'upload_path' => "./userfiles/uploaded/assignview/",
            'allowed_types' => "gif|jpg|png|jpeg|pdf",
            'overwrite' => TRUE,
            'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
            'max_height' => "768",
            'max_width' => "1024"
        );

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('myFiles'))
        {
            $error = array('error' => $this->upload->display_errors());
            return $this->upload->display_errors();
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());
            $res = $this->upload->data();
            return $res['file_name'];
        }
    }


}
?>