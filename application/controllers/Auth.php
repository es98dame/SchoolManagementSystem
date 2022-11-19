<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {
    function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model("aliweb_model");
    }

    public function _remap($action)
    {
        switch ($action)
        {
            case 'login': $this->login(); break;
            case 'logout': $this->logout(); break;
            default: $this->index(); break;
        }
    }

    function index()
    {
        $this->load->view('aliweb/','',false);
    }

    function login()
    {
        if(!@$this->uri->segment(3)) {
            $returnUrl = site_url('aliweb/login');
        } else {
            $returnUrl = @$this->uri->segment(3);
        }

        $this->form_validation->set_rules('userid', 'Username', 'trim|required|xss_clean');
        $this->form_validation->set_rules('userpwd', 'Password', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE){
            redirect($returnUrl);
        }else{
            $id = $this->input->post('userid');
            $pass = $this->input->post('userpwd');
            $grp = $this->input->post('grp');
            $success = $this->aliweb_model->logincheck($id, $pass, $grp);
            redirect(site_url('aliweb/login'));
            /*

            if($success){
                switch($grp ){
                    case '9': //admin
                        redirect(site_url('onlineclass/')); break;
                    case '1' : //student
                        redirect(site_url('onlinestudent/')); break;
                    case '7' : //teacher
                        redirect(site_url('onlineteacher/')); break;
                    case '5' : //parent
                        redirect(site_url('onlineparent/')); break;
                    default :
                        redirect(site_url('contents/student'));
                }
            }else{
                switch($grp ){
                    case '9': //admin
                        redirect(site_url('contents/admin')); break;
                    case '1' : //student
                        redirect(site_url('contents/student')); break;
                    case '7' : //teacher
                        redirect(site_url('contents/teacher')); break;
                    case '5' : //parent
                        redirect(site_url('contents/parent')); break;
                    default :
                        redirect(site_url('contents/student'));
                }
            }

            */
        }
    }

    function logout()
    { $grpurl = "";

        $s1 = $this->session->userdata('SEASESS_USERNO');
        $s2 = $this->session->userdata('SSTC_USERNO');
        $s3 = $this->session->userdata('SSPR_USERNO');
        $s4 = $this->session->userdata('SSST_USERNO');
        if(!empty($s1))
            $grpurl = "admin";
        else if(!empty($s2))
            $grpurl = "teacher";
        else if(!empty($s3))
            $grpurl = "parent";
        else if(!empty($s4))
            $grpurl = "student";

        $this->session->unset_userdata('SEASESS_USERNAME');
        $this->session->unset_userdata('SEASESS_USERNO');
        $this->session->unset_userdata('SEASESS_AUTHNO');
        $this->session->unset_userdata('SEASESS_EMAIL');

        //teacher
        $this->session->unset_userdata('SSTC_USERNAME');
        $this->session->unset_userdata('SSTC_USERNO');
        $this->session->unset_userdata('SSTC_EMAIL');


        //student
        $this->session->unset_userdata('SSST_USERNAME');
        $this->session->unset_userdata('SSST_USERNO');
        $this->session->unset_userdata('SSST_EMAIL');
//		$this->session->sess_destroy();
        redirect(site_url('contents/'.$grpurl), 'refresh');
    }



}