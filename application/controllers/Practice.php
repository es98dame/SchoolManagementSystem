<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Practice extends CI_Controller {

    // public $webtitle = "ALI";

    public $Param=array();

    /**
     *
     */
    function __construct()
    {
        parent::__construct();
        //$this->load->library('form_validation');
        //$this->load->library('session');
        //$this->load->model("sea_model");

        //base64encode_url
        if ( ! function_exists('base64_encode_url'))
        {
            function base64_encode_url($plainText){
                $base64 = base64_encode($plainText);
                $base64url = strtr($base64, '+/=', '-_~');
                return $base64url;
            }
        }

        //base64decode_url
        if ( ! function_exists('base64_decode_url'))
        {
            function base64_decode_url($encoded)
            {
                $base64 = strtr($encoded,'-_~','+/=');
                $plainText = base64_decode($base64);
                return $plainText;
            }
        }

        foreach( $_GET as $key => $value )
        {
            $this->Param[$key] = $this->input->get($key);
        }

        foreach ( $_POST as $key2 => $value2 )
        {
            $this->Param[$key2] = $this->input->post($key2);
        }

        //$this->Param['session_username'] = $this->session->userdata('SEASESS_USERNAME');
        //$this->Param['session_userno'] = $this->session->userdata('SEASESS_USERNO');
        //$this->Param['session_authno'] = $this->session->userdata('SEASESS_AUTHNO');
        $this->Param['schooladdress'] = $this->load->view('common/schooladdress','',true);


    }
    function _remap($method, $params = array())
    {
        if (method_exists($this, $method))
        {
            return call_user_func_array(array($this, $method), $params);
        }
        show_404();
    }
    function common_layout($data=''){
        if(isset($data)){
            $layout_data['header'] = $this->load->view('common/header',null, true);
            $layout_data['navigation'] = $this->load->view('common/navigation',array("navi_seq"=>"about"), true);
            $layout_data['breadcrumb'] = $data['breadcrumb'];
            $layout_data['content_body'] = $data['contents'];
            $layout_data['footer'] = $this->load->view('common/footer',null, true);
            return $layout_data;
        }
    }
    function index()
    {
        $this->welcome();
    }

    function first(){
        $this->load->view('first');
    }
    function welcome()
    {
        //$this->lang->load('sample');
        $this->Param['breadcrumb'] = array('HOME' => site_url("/"),'ABOUT' => null,'WELCOME' =>null);
        $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb',$this->Param,true);

        $contents_data['contents'] = $this->load->view('about/welcome',$this->Param,true);
        $layout_data = $this->common_layout($contents_data);
        $this->load->view('layouts/layout_sub',$layout_data,false);
    }
    function instructors()
    {
        $this->Param['breadcrumb'] = array('HOME' => site_url("/"),'ABOUT' => null,'INSTRUCTORS' =>null);
        $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb',$this->Param,true);

        $contents_data['contents'] = $this->load->view('about/instructors', $this->Param, true);
        $layout_data = $this->common_layout($contents_data);
        $this->load->view('layouts/layout_sub', $layout_data, false);
    }
    function overview()
    {
        //$this->lang->load('sample');
        $this->Param['breadcrumb'] = array('HOME' => site_url("/"),'ABOUT' => null,'PROGRAM OVERVIEW' =>null);
        $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb',$this->Param,true);

        $contents_data['contents'] = $this->load->view('about/overview', $this->Param, true);
        $layout_data = $this->common_layout($contents_data);
        $this->load->view('layouts/layout_sub', $layout_data, false);
    }

    function staff()
    {
        $this->Param['breadcrumb'] = array('HOME' => site_url("/"),'ABOUT' => null,'STAFF' => null);
        $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb',$this->Param,true);

        $contents_data['contents'] = $this->load->view('about/staff', $this->Param, true);
        $layout_data = $this->common_layout($contents_data);
        $this->load->view('layouts/layout_sub', $layout_data, false);
    }
    function tuition()
    {
        $this->Param['breadcrumb'] = array('HOME' => site_url("/"),'ABOUT' => null,'TUITION' => null);
        $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb',$this->Param,true);

        $contents_data['contents'] = $this->load->view('about/tuition',$this->Param,true);
        $layout_data = $this->common_layout($contents_data);
        $this->load->view('layouts/layout_sub',$layout_data,false);
    }
    function calendar($mode='')
    {
        $this->Param['breadcrumb'] = array('HOME' => site_url("/"),'ABOUT' => null,'CALENDAR' => null);
        $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb',$this->Param,true);

        $contents_data['contents'] = $this->load->view('about/calendar', $this->Param, true);
        $layout_data = $this->common_layout($contents_data);
        $this->load->view('layouts/layout_sub', $layout_data, false);
    }
    function toefl($mode='')
    {
        $this->Param['breadcrumb'] = array('HOME' => site_url("/"),'ABOUT' => null,'TOEFL' => null);
        $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb',$this->Param,true);

        $contents_data['contents'] = $this->load->view('about/toefl', $this->Param, true);
        $layout_data = $this->common_layout($contents_data);
        $this->load->view('layouts/layout_sub', $layout_data, false);
    }
    function downfile($flname,$path)
    {
        $filename = $flname;
        $orgin_dir=$_SERVER['DOCUMENT_ROOT']."/uploaded/".$path."/";
        $tmp_dir = $_SERVER['DOCUMENT_ROOT']."/tmpdown/";

        $filepath = $orgin_dir.$filename;

        if(!is_file($filepath)):
            die("<b> 404 File not found!</b>");
        endif;


        $tmp_filepath = $tmp_dir.$filename;

        $len = filesize($filepath);
        $extension = strtolower(substr(strrchr($filename,"."),1));
        copy($filepath, $tmp_filepath) or die("fail copy");

        switch($extension):
            case "txt": $type="text/plain"; break;
            case "png": $type="image/png"; break;
            case "jpeg": $type="image/jpeg"; break;
            case "gif": $type="image/gif"; break;
            case "bmp": $type="image/bmp"; break;
            case "zip": $type="application/zip"; break;
            case "rar": $type="application/x-rar-compressed"; break;
            case "pdf": $type="application/pdf"; break;
            case "doc": $type="application/msword"; break;
            case "docx": $type="application/vnd.openxmlformats-officedocument.wordprocessingml.document"; break;
            case "xls": $type="application/vnd.ms-excel"; break;
            case "xlsx": $type="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"; break;
            case "ppt": $type="application/vnd.ms-powerpoint"; break;
            case "pptx": $type="application/vnd.openxmlformats-officedocument.presentationml.presentation"; break;
            default: $type= "application/force-download";
        endswitch;


        header("Expires: 0");
        header("Cache-Control:must-revalidate,post-check=0, pre-check=0");
        header("Cache-Control:public");
        header("Content-Description:File Transfer");
        header("Content-Type: ".$type);
        header("Content-Disposition: attachment; filename=".$filename.";");
        header("Content-Transfer-Encoding:binary");
        header("Content-Length:".@filesize($tmp_filepath));
        @readfile($tmp_filepath);

        unlink($tmp_filepath) or die("fail remove");
        flush();
        exit();

        //return $this->CI->load->view('board/down_view',$this->Param,true);
    }

}
