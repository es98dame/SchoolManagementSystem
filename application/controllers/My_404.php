<?php
class  My_404 extends CI_Controller
{
    public function __construct()   {
        parent::__construct();
    }
    public function index()
    {
        $this->output->set_status_header('404');
        $data['heading'] = "Home Page";
        $data['message'] = "error_page";   //View file name
        $this->load->view('errors/html/error_404', $data);   //show in your template
    }
}