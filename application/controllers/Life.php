<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Life extends CI_Controller
{
    // public $webtitle = "ALI";
    public $Param = array();
    function __construct()
    {
        parent::__construct();
        //$this->load->library('form_validation');
        //$this->load->library('session');
        //$this->load->model("sea_model");

        //base64encode_url
        if (!function_exists('base64_encode_url')) {
            function base64_encode_url($plainText)
            {
                $base64 = base64_encode($plainText);
                $base64url = strtr($base64, '+/=', '-_~');
                return $base64url;
            }
        }

        //base64decode_url
        if (!function_exists('base64_decode_url')) {
            function base64_decode_url($encoded)
            {
                $base64 = strtr($encoded, '-_~', '+/=');
                $plainText = base64_decode($base64);
                return $plainText;
            }
        }

        foreach ($_GET as $key => $value) {
            $this->Param[$key] = $this->input->get($key);
        }

        foreach ($_POST as $key2 => $value2) {
            $this->Param[$key2] = $this->input->post($key2);
        }

        //$this->Param['session_username'] = $this->session->userdata('SEASESS_USERNAME');
        //$this->Param['session_userno'] = $this->session->userdata('SEASESS_USERNO');
        //$this->Param['session_authno'] = $this->session->userdata('SEASESS_AUTHNO');
        $this->Param['schooladdress'] = $this->load->view('common/schooladdress','',true);
    }
    function _remap($method, $params = array())
    {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        }
        show_404();
    }
    function common_layout($data = '')
    {
        if (isset($data)) {
            $layout_data['header'] = $this->load->view('common/header', '', true);
            $layout_data['navigation'] = $this->load->view('common/navigation',array("navi_seq"=>"life"), true);
            $layout_data['breadcrumb'] = $data['breadcrumb'];
            $layout_data['content_body'] = $data['contents'];
            $layout_data['footer'] = $this->load->view('common/footer', '', true);
            return $layout_data;
        }
    }
    function index()
    {
        $this->newsletter();
    }

    function newsletter()
    {
        if(!isset($this->Param['ver'])){
            $this->Param['ver']="2016_v3";
        }

        $this->Param['breadcrumb'] = array('HOME' => site_url("/"),'STUDENT LIFE' => null,'News Letter' =>null);
        $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb',$this->Param,true);
        $contents_data['contents'] = $this->load->view('life/newsletter', $this->Param, true);
        $layout_data = $this->common_layout($contents_data);
        $this->load->view('layouts/layout_sub', $layout_data, false);
    }
    function handbooks()
    {
        $this->Param['breadcrumb'] = array('HOME' => site_url("/"),'STUDENT LIFE' => null,'ALI STUDENT HANDBOOK' =>null);
        $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb',$this->Param,true);

        $contents_data['contents'] = $this->load->view('life/handbooks', $this->Param, true);
        $layout_data = $this->common_layout($contents_data);
        $this->load->view('layouts/layout_sub', $layout_data, false);
    }
    function download($mode='list')
    {
        $this->load->library('bulletin');

        $this->Param['breadcrumb'] = array('HOME' => site_url("/"),'STUDENT LIFE' => null,'DOWNLOAD' =>null);
        $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb',$this->Param,true);

        $contents_data['contents'] = $this->bulletin->initialize("download",$mode);
        $layout_data = $this->common_layout($contents_data);
        $this->load->view('layouts/layout_sub',$layout_data,false);
    }
    function aboutdfw()
    {
        $this->Param['breadcrumb'] = array('HOME' => site_url("/"),'STUDENT LIFE' => null,'ABOUT DFW' =>null);
        $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb',$this->Param,true);
        $contents_data['contents'] = $this->load->view('life/aboutdfw', $this->Param, true);
        $layout_data = $this->common_layout($contents_data);
        $this->load->view('layouts/layout_sub', $layout_data, false);
    }
    function schoolactivities()
    {
        $this->Param['breadcrumb'] = array('HOME' => site_url("/"),'STUDENT LIFE' => null,'SCHOOL ACTIVITIES' =>null);
        $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb',$this->Param,true);
        $contents_data['contents'] = $this->load->view('life/schoolactivities', $this->Param, true);
        $layout_data = $this->common_layout($contents_data);
        $this->load->view('layouts/layout_sub', $layout_data, false);
    }
}