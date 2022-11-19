<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends CI_Controller
{
    public $studentRoot="/index.php/student/";
    public $Param = array();

    public $CL_Submenu = array(
        "Grade"=>"/index.php/student/studentgrade",
        "Attendance"=>"/index.php/student/attmonths"
    );

    public $RC_Submenu = array(
        "Attendance"=>"/index.php/student/rattmonths"
    );

    function __construct()
    {
        parent::__construct();

        $this->load->library('form_validation');
        $this->load->model("student_model");

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
            $layout_data['header'] = $this->load->view('common/header_student', null, true);
            $layout_data['navigation'] = $this->load->view('common/navigation_student', null, true);
            $layout_data['breadcrumb'] = "";
            if($data['breadcrumb']){
                $layout_data['breadcrumb'] = $data['breadcrumb'];
            }
            $layout_data['content_body'] = $data['contents'];
            $layout_data['footer'] = $this->load->view('common/footer_student', null, true);
            return $layout_data;
        }
    }
    function index()
    {
        if ($this->session->userdata('STDSESS_LOGIN') == true)
            redirect(site_url('student/classinquiry'));
        else
            $this->login();

        //$this->load->view('student/login','',false);
    }

    function login()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        if ($_SERVER['REQUEST_METHOD'] == 'GET')
        {
            $this->Param['breadcrumb'] = array('Home' => $_SERVER["PHP_SELF"]);
            $init_data['breadcrumb'] = $this->load->view('common/breadcrumb_student', $this->Param, true);
            $init_data['contents'] = $this->load->view('student/login',$init_data,true);
            $layout_data = $this->common_layout($init_data);
            $this->load->view('layouts/layout_login',$layout_data,false);
        }
        else if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $this->form_validation->set_rules('userid', 'User ID', 'trim|required');
            $this->form_validation->set_rules('userpwd', 'Password', 'trim|required');
            $userid 	= $this->input->post('userid', TRUE);
            $ip_address	= $this->input->ip_address();
            $user_agent	= $this->input->user_agent();

            if ($this->form_validation->run() == FALSE){
                $this->session->set_flashdata("successnewpw","<span style='color:red;'>Error form validation.</span>");
                $this->student_model->record_login_attempt($userid, $ip_address, 0, $user_agent, 'form validation: '.validation_errors('[',']'));
                $this->Param['breadcrumb'] = array('Home' => $_SERVER["PHP_SELF"]);
                $init_data['breadcrumb'] = $this->load->view('common/breadcrumb_student', $this->Param, true);
                $init_data['contents'] = $this->load->view('student/login',$init_data,true);
                $layout_data = $this->common_layout($init_data);
                $this->load->view('layouts/layout_login',$layout_data,false);
            }else {
                $user = $this->student_model->getLoginInfo( trim($this->input->post('userid', TRUE)) );
                if($user!=null){
                    $pass = $this->input->post('userpwd', TRUE);
                    if ($this->checkPassword($pass,$user['passw']))
                    {
                        $this->student_model->record_login_attempt($userid, $ip_address, 1, $user_agent, 'successful login');
                        $this->student_model->update_last_login($userid,$ip_address);

                        $gp = $this->student_model->currentGP();
                        $tnocnt = $this->student_model->getExistRemediation($user['students_no']);
                        $this->session->set_userdata(array(
                            'STDSESS_USERNO'	=> $user['students_no'],
                            'STDSESS_USERNAME'	=> $user['firstname'].' '.$user['lastname'],
                            'STDSESS_USERID'	=> $user['user_ID'],
                            'STDSESS_EMAIL'		=> $user['email'],
                            'STDSESS_EXREMED'	=> $tnocnt,
                            'STDSESS_LOGIN'	=> true,
                            'GPNO' => $gp
                        ));

                         redirect(site_url('student/classinquiry'));
                    }else{
                        //$this->form_validation->set_rules('userpwd', 'Password', 'callback__invalid_password');
                        //$this->form_validation->run();
                        $this->session->set_flashdata("successnewpw","<span style='color:red;'>Incorrect password</span>");
                        $this->student_model->record_login_attempt($userid, $ip_address, 0, $user_agent, 'password check: '.validation_errors('[',']'));

                        $this->Param['breadcrumb'] = array('Home' => $_SERVER["PHP_SELF"]);
                        $init_data['breadcrumb'] = $this->load->view('common/breadcrumb_student', $this->Param, true);
                        $init_data['contents'] = $this->load->view('student/login',$init_data,true);
                        $layout_data = $this->common_layout($init_data);
                        $this->load->view('layouts/layout_login',$layout_data,false);

                    }
                }else{
                    $this->session->set_flashdata("successnewpw","<span style='color:red;'>Incorrect account</span>");
                    // $this->form_validation->set_rules('userpwd', 'Password', 'callback__invalid_password');
                    // $this->form_validation->run();
                    $this->student_model->record_login_attempt($userid, $ip_address, 0, $user_agent, 'password check: '.validation_errors('[',']'));

                    $this->Param['breadcrumb'] = array('Home' => $_SERVER["PHP_SELF"]);
                    $init_data['breadcrumb'] = $this->load->view('common/breadcrumb_student', $this->Param, true);
                    $init_data['contents'] = $this->load->view('student/login',$init_data,true);
                    $layout_data = $this->common_layout($init_data);
                    $this->load->view('layouts/layout_login',$layout_data,false);
                }
            }
        }
        else die('invalid request method:'.$_SERVER['REQUEST_METHOD']);
    }
    function logout()
    {
        $this->session->unset_userdata('STDSESS_USERNO');
        $this->session->unset_userdata('STDSESS_USERNAME');
        $this->session->unset_userdata('STDSESS_USERID');
        $this->session->unset_userdata('STDSESS_EMAIL');
        $this->session->unset_userdata('GPNO');
        $this->session->unset_userdata('SEL_CLASSNO');
        $this->session->unset_userdata('SEL_CLASSNAME');
        $this->session->set_userdata('STDSESS_LOGIN', false);
        redirect(site_url('student/login'),'refresh');
    }
    //use
    function checkPassword($pass,$dbpassw)
    {
        $salt = explode(':',$dbpassw);
        $wr_rpwd = $this->student_model->fn_generate_salted_password($pass,$salt[1]);

        if(!strcmp($dbpassw,$wr_rpwd) ) {
            return true;
        }else{
            $this->session->set_flashdata("successnewpw","<span style='color:red;'>Incorrect password.</span>");
            return false;
        }
    }
    function reset($mode='')
    {
        $init_data['session_top'] = "";
        $init_data['ema'] = "";
        $init_data['gpart'] = "";
        $isgubun="";
        $uid="";
        if(isset($this->Param['ema']))
        {
            list($isgubun,$uid) = $this->student_model->checkEmailKey($this->Param['ema']);
            if(!empty($isgubun)&&!empty($uid)){
                $init_data['ema'] = $this->Param['ema'];
                $init_data['gpart'] = $isgubun;
                $init_data['uid'] = $uid;
            }else{
                $init_data['session_top'] = "This reset password link has expired. <a href='".site_url("student/login")."'>Please go back to Login.</a>";
            }
        }else{
            $init_data['session_top'] = "This is wrong URL. <a href='".site_url("student/login")."'>Please go back to Login.</a>";
        }

        switch($mode):
            case 'setNewPassw':
                $this->student_model->setNewPW($this->Param);
                switch($isgubun){
                    case '6': //admin
                        $this->session->set_flashdata('successnewpw','<div class="alert alert-success fade in"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Success!</strong> Your password was updated.</div>');
                        redirect(site_url('student/login')); break;
                    default:
                }
                break;
            default:

                $this->Param['breadcrumb'] = array('Home' => $_SERVER["PHP_SELF"], 'Reset Password' => null);
                $init_data['breadcrumb'] = $this->load->view('common/breadcrumb_student', $this->Param, true);
                $init_data['contents'] = $this->load->view('student/reset',$init_data,true);
                $layout_data = $this->common_layout($init_data);
                $this->load->view('layouts/layout_login',$layout_data,false);

        endswitch;

    }
    function lost($mode='')
    {
        $init_data['session_top'] = "";
        $init_data['session_txt']="Enter your userID or email address";
        $init_data['session_cap']="Enter the numbers in the box";
        $init_data['grp'] ="";

        if(isset($this->Param['grp']))
        {
            if($this->Param['grp']!=""){
                $init_data['grp'] = $this->Param['grp'];
            }
        }

        if(isset($this->Param['usr']))
        {
            if($this->Param['usr']==""){
                $init_data['session_txt'] ="<span style='color:red;'>Invalid userID/email</span>";
            }
        }
        $key = ""; $UID =""; $UFULL=""; $UEMAIL="";
        if(isset($this->Param['captcha'])&&isset($this->Param['usr'])&&isset($this->Param['grp']) )
        {
            if($this->Param['captcha']!=""&&$this->Param['usr']!=""){
                if( $this->session->userdata('code')==$this->Param['captcha'] ){
                    list($UID,$UFULL,$UEMAIL) = $this->student_model->getValidate($this->Param);
                    if($UID!=""&&$UEMAIL!=""){
                        $key = $this->student_model->setRecoverPW($UID,$UFULL,$UEMAIL,$this->Param['grp']);
                        $nContents = "<ul>";
                        $nContents .= "<li>Hello ".$UFULL."</li>";
                        $nContents .= "<li>Below is the link to reset your password for your account:</li>";
                        $nContents .= "<li>Username: ".$UID."</li>";
                        $nContents .=  "<li>http://".$_SERVER['HTTP_HOST'].substr($_SERVER["PHP_SELF"],0,-4)."reset?ema=".$key."</li>";
                        $nContents .= "<li>If you did not request to reset your password, there is no need to be concerned, someone else may have accidentally entered the wrong username when requesting to reset their password. Your account is secure and password will not be changed unless you click the link above.</li>";
                        $nContents .= "<li>Thank You,</li>";
                        $nContents .= "<li>Institution Name</li>";
                        $nContents .= "<li>*********************</li>";
                        $nContents .= "<li>DO NOT REPLY TO THIS EMAIL</li>";
                        $nContents .= "</ul>";

                        $this->email->from("smtp@schooldname.com","ALI");
                        $this->email->to($UEMAIL);
                        $this->email->bcc("records@schooldname.com");
                        $this->email->subject("Reset ALI Password");
                        $this->email->message($nContents);
                        $this->email->send();

                        $this->session->set_flashdata('session_top','<div class="alert alert-success fade in"><a href="#" class="close" data-dismiss="alert">&times;</a>A link to reset your password has been emailed to the address on file.</div>');
                    }else{
                        $this->session->set_flashdata('session_top','<div class="alert alert-danger fade in"><a href="#" class="close" data-dismiss="alert">&times;</a>There were some errors with your submission.</div>');
                        $init_data['session_txt'] = "<span style='color:red;'>Invalid userID/email</span>";
                    }

                }else{
                    $init_data['session_cap'] ="<span style='color:red;'>Invalid code, try again</span>";
                }
            }else{
                if($this->Param['captcha']==""){
                    $init_data['session_cap'] ="<span style='color:red;'>Invalid code, try again</span>";
                }
                if($this->Param['usr']==""){
                    $init_data['session_txt'] ="<span style='color:red;'>Invalid userID/email</span>";
                }
            }

        }

        $this->session->unset_userdata('code');

        switch($mode):
            case 'captcha': $this->captcha();  break;
            default:
                $this->Param['breadcrumb'] = array('Home' => $_SERVER["PHP_SELF"], 'Lost Password' => null);
                $init_data['breadcrumb'] = $this->load->view('common/breadcrumb_student', $this->Param, true);
                $init_data['contents'] = $this->load->view('student/lost',$init_data,true);
                $layout_data = $this->common_layout($init_data);
                $this->load->view('layouts/layout_login',$layout_data,false);
        endswitch;
    }
    function captcha(){
        $code=rand(1000,9999);
        $newdata = array(
            'code'  => $code,
            'eeeee'     => "11"
        );
        $this->session->set_userdata($newdata);

        $im = imagecreatetruecolor(50, 24);
        $bg = imagecolorallocate($im, 22, 86, 165); //background color blue
        $fg = imagecolorallocate($im, 255, 255, 255);//text color white
        imagefill($im, 0, 0, $bg);
        imagestring($im, 5, 5, 5,  $code, $fg);
        header("Cache-Control: no-cache, must-revalidate");
        header('Content-type: image/png');
        imagepng($im);
        imagedestroy($im);
    }
    function classinquiry($mode=''){
        if ($this->session->userdata('STDSESS_LOGIN') == false) redirect(site_url('student/login'));
        switch($mode):
            case 'getClasses': $this->getClasses($this->Param);  break;
            default:
                $this->Param['navi_seq'] = "classes";
                $this->Param['breadcrumb'] = array('Home' => $this->studentRoot,'Year / Trimester' => null, 'Class Inquiry' => null);
                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_student', $this->Param, true);
                $contents_data['contents'] = $this->load->view('student/classinquiry', $this->Param, true);
                $layout_data = $this->common_layout($contents_data);
                $this->load->view('layouts/layout_student', $layout_data, false);
        endswitch;
    }
    function getClasses($params){$xmlcontents = $this->student_model->getClasses($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}

    /*** Remediation > Class Inquiry Edit Function for Dhtmlx Start ****/
    function rclassinquiry($mode=''){
        if ($this->session->userdata('STDSESS_LOGIN') == false) redirect(site_url('student/login'));
        //if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));
        switch($mode):
            case 'getRClasses': $this->getRClasses($this->Param);  break;
            //case 'getRClass': $this->getRClass($this->Param);  break;
            //case 'setRClass': $this->setRClass($this->Param);  break;
            //case 'getComTeachers': $this->getComTeachers($this->Param);  break;
            //case 'getComRooms': $this->getComRooms($this->Param);  break;
            //case 'getComTrimesters': $this->getComTrimesters($this->Param);  break;
            //case 'getComSchoolYear': $this->getComSchoolYear($this->Param);  break;
            //case 'getComLevel': $this->getComLevel($this->Param);  break;
            //case 'menucontext': $this->menucontext($this->Param);  break;
            default:
                $this->Param['navi_seq'] = "rclasses";
                $this->Param['breadcrumb'] = array('Home' => $this->studentRoot,'Year / Trimester' => null, 'Remediation Class Inquiry' => null);
                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_student', $this->Param, true);
                $contents_data['contents'] = $this->load->view('student/rclassinquiry', $this->Param, true);
                $layout_data = $this->common_layout($contents_data);
                $this->load->view('layouts/layout_student', $layout_data, false);
        endswitch;
    }
    function getRClasses($params){$xmlcontents = $this->student_model->getRClasses($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}
    //function getRClass($params){$xmlcontents = $this->student_model->getRClass($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}
    //function setRClass($params){$xmlcontents = $this->student_model->setRClass($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}
    /*** Attendance Weeks Function for Dhtmlx Start ****/
    function rattmonths($mode='')
    {
        if ($this->session->userdata('STDSESS_LOGIN') == false) redirect(site_url('student/login'));
        //if (!$this->zacl->check_acl('edit')) redirect(site_url('student/login'));
        if(ISSET($this->Param['classno'])&&ISSET($this->Param['classname'])){
            if ($this->session->userdata('SEL_RCLASSNO') == true){
                $this->session->set_userdata('SEL_RCLASSNO',trim($this->Param['classno']));
                $this->session->set_userdata('SEL_RCLASSNAME',trim($this->Param['classname']));

                $this->Param['param']['classno']=$this->session->userdata('SEL_RCLASSNO');
                $this->Param['param']['classname']=$this->session->userdata('SEL_RCLASSNAME');
                $this->Param['classno'] = $this->session->userdata('SEL_RCLASSNO');
            }else{
                $this->session->set_userdata(array(
                    'SEL_RCLASSNO'	=> $this->Param['classno'],
                    'SEL_RCLASSNAME'	=> $this->Param['classname']
                ));
                $this->Param['param']['classno']=$this->session->userdata('SEL_RCLASSNO');
                $this->Param['param']['classname']=$this->session->userdata('SEL_RCLASSNAME');
                $this->Param['classno'] = $this->session->userdata('SEL_RCLASSNO');
            }

        }else{
            if ($this->session->userdata('SEL_RCLASSNO') == true){
                $this->Param['param']['classno']=$this->session->userdata('SEL_RCLASSNO');
                $this->Param['param']['classname']=$this->session->userdata('SEL_RCLASSNAME');
                $this->Param['classno'] = $this->session->userdata('SEL_RCLASSNO');
            }else{
                redirect(site_url('student/rclassinquiry'));
            }
        }

        list($classname,$gpname) = $this->student_model->rclassGP($this->Param['classno']);
        if ($this->session->userdata('SEL_RGPNAME') == true){
            $this->session->set_userdata('SEL_RGPNAME',$gpname);
        }else{
            $this->session->set_userdata(array(
                'SEL_RGPNAME'	=> $gpname
            ));
        }
        //$this->Param['param']['classno']=$this->session->userdata('SEL_CLASSNO');
        //$this->Param['param']['classname']=$this->session->userdata('SEL_CLASSNAME');
        $this->Param['stno'] = $this->session->userdata('STDSESS_USERNO');

       switch($mode):
            default:
                $sump=0;$sumt=0;$suma=0; $arr=array(); $stname="";
                list($arr,$sump,$sumt,$suma,$stname) = $this->student_model->getRAttMonths($this->Param);
                $this->Param['param']['title'] = "Remediation Attendance";
                $this->Param['param']['menus'] = "";
                $this->Param['sump'] = $sump;
                $this->Param['sumt'] = $sumt;
                $this->Param['suma'] = $suma;
                $this->Param['stname'] = $stname;
                $this->Param['arr'] = $arr;
                list ($this->Param["classname"],$this->Param["gpname"]) = $this->student_model->rclassGP($this->Param['classno']);
                foreach($this->RC_Submenu as $key=>$val){
                    $this->Param['param']['menus'].="<li><a href=\"".$this->RC_Submenu[$key]."\">".$key."</a></li>";
                }

                $this->Param['navi_seq'] = "rclasses";
                $this->Param['breadcrumb'] = $this->getClassBread('Remediation Attendance');
                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_student', $this->Param, true);
                $contents_data['contents'] = $this->load->view('student/rattmonths', $this->Param, true);
                $layout_data = $this->common_layout($contents_data);
                $this->load->view('layouts/layout_student', $layout_data, false);
        endswitch;
    }


    function myaccount($mode=''){
        if ($this->session->userdata('STDSESS_LOGIN') == false) redirect(site_url('student/login'));
        //if (!$this->zacl->check_acl('edit')) redirect(site_url('student/login'));
        switch($mode):
            case 'getMyaccount': $this->getMyaccount($this->Param);  break;
            case 'setMyaccount': $this->setMyaccount($this->Param); break;
            default:
                $this->Param['navi_seq'] = "myaccount";
                $this->Param['breadcrumb'] = array('Home' => $_SERVER["PHP_SELF"], 'Myaccount' => null);
                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_student', $this->Param, true);
                $contents_data['contents'] = $this->load->view('student/myaccount', $this->Param, true);
                $layout_data = $this->common_layout($contents_data);
                $this->load->view('layouts/layout_student', $layout_data, false);
        endswitch;
    }
    function getMyaccount($params){$xmlcontents = $this->student_model->getMyaccount($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}
    function setMyaccount($params){$xmlcontents = $this->student_model->setMyaccount($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}

    function studentgrade($mode='')
    {
        if ($this->session->userdata('STDSESS_LOGIN') == false) redirect(site_url('student/login'));
        //if (!$this->zacl->check_acl('edit')) redirect(site_url('student/login'));
        if(ISSET($this->Param['classno'])&&ISSET($this->Param['classname'])){
            if ($this->session->userdata('SEL_CLASSNO') == true){
                $this->session->set_userdata('SEL_CLASSNO',trim($this->Param['classno']));
                $this->session->set_userdata('SEL_CLASSNAME',trim($this->Param['classname']));

                $this->Param['param']['classno']=$this->session->userdata('SEL_CLASSNO');
                $this->Param['param']['classname']=$this->session->userdata('SEL_CLASSNAME');
                $this->Param['classno'] = $this->session->userdata('SEL_CLASSNO');
            }else{
                $this->session->set_userdata(array(
                    'SEL_CLASSNO'	=> $this->Param['classno'],
                    'SEL_CLASSNAME'	=> $this->Param['classname']
                ));
                $this->Param['param']['classno']=$this->session->userdata('SEL_CLASSNO');
                $this->Param['param']['classname']=$this->session->userdata('SEL_CLASSNAME');
                $this->Param['classno'] = $this->session->userdata('SEL_CLASSNO');
            }

        }else{
            if ($this->session->userdata('SEL_CLASSNO') == true){
                $this->Param['param']['classno']=$this->session->userdata('SEL_CLASSNO');
                $this->Param['param']['classname']=$this->session->userdata('SEL_CLASSNAME');
                $this->Param['classno'] = $this->session->userdata('SEL_CLASSNO');
            }else{
                redirect(site_url('student/classinquiry'));
            }
        }

        list($classname,$gpname) = $this->student_model->classGP($this->Param['classno']);
        if ($this->session->userdata('SEL_GPNAME') == true){
            $this->session->set_userdata('SEL_GPNAME',$gpname);
        }else{
            $this->session->set_userdata(array(
                'SEL_GPNAME'	=> $gpname
            ));
        }

        //$this->Param['param']['classno']=$this->session->userdata('SEL_CLASSNO');
        //$this->Param['param']['classname']=$this->session->userdata('SEL_CLASSNAME');
        $this->Param['stno'] = $this->session->userdata('STDSESS_USERNO');

        //$this->Param['classno'] = $this->session->userdata('SEL_CLASSNO');
        switch($mode):
            case 'getStudentGrades': $this->getStudentGrades($this->Param);  break;
            //case 'printgrade': $this->printgrade($this->Param);  break;
            //case 'createpdf': $this->createpdf($this->Param);  break;
            default:
                $this->Param['param']['title'] = "Student Grade";
                $this->Param['param']['menus'] = "";
                foreach($this->CL_Submenu as $key=>$val){
                    $this->Param['param']['menus'].="<li><a href=\"".$this->CL_Submenu[$key]."\">".$key."</a></li>";
                }

                $this->Param['navi_seq'] = "classes";
                $this->Param['breadcrumb'] = $this->getClassBread('Student Grade');
                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_student', $this->Param, true);
                $contents_data['contents'] = $this->load->view('student/studentgrade', $this->Param, true);
                $layout_data = $this->common_layout($contents_data);
                $this->load->view('layouts/layout_student', $layout_data, false);
        endswitch;

    }
    function getStudentGrades($params){$xmlcontents = $this->student_model->getStudentGrades($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}
    function getClassBread($cur){
        return array('Home' =>$this->studentRoot,$this->session->userdata('SEL_GPNAME') => $this->studentRoot.'classinquiry/',$this->session->userdata('SEL_CLASSNAME')=>$this->studentRoot.'studentgrade/', $cur => null);
    }

    function academicrecords($mode=''){
        if ($this->session->userdata('STDSESS_LOGIN') == false) redirect(site_url('student/login'));
        switch($mode):
            case 'getAcademicRecords': $this->Param['sno'] = $this->session->userdata('STDSESS_USERNO'); $this->getAcademicRecords($this->Param);  break;
            default:
                $this->Param['navi_seq'] = "academicrecords";
                $this->Param['breadcrumb'] = array('Home' => $this->studentRoot,'Academic Records' => null);
                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_student', $this->Param, true);
                $contents_data['contents'] = $this->load->view('student/academicrecords', $this->Param, true);
                $layout_data = $this->common_layout($contents_data);
                $this->load->view('layouts/layout_student', $layout_data, false);
        endswitch;
    }
    function getAcademicRecords($params){$xmlcontents = $this->student_model->getAcademicRecords($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}

    function attmonths($mode='')
    {
        if ($this->session->userdata('STDSESS_LOGIN') == false) redirect(site_url('student/login'));
       // if (!$this->zacl->check_acl('edit')) redirect(site_url('student/login'));
        $this->Param['param']['classno']=$this->session->userdata('SEL_CLASSNO');
        $this->Param['param']['classname']=$this->session->userdata('SEL_CLASSNAME');
        $this->Param['classno'] = $this->session->userdata('SEL_CLASSNO');
        $this->Param['stno'] = $this->session->userdata('STDSESS_USERNO');

        switch($mode):
            default:
                $sump=0;$sumt=0;$suma=0; $arr=array(); $stname="";
                list($arr,$sump,$sumt,$suma,$stname) = $this->student_model->getAttMonths($this->Param);
                $this->Param['param']['title'] = "Attendance";
                $this->Param['param']['menus'] = "";
                $this->Param['sump'] = $sump;
                $this->Param['sumt'] = $sumt;
                $this->Param['suma'] = $suma;
                $this->Param['stname'] = $stname;
                $this->Param['arr'] = $arr;
                list ($this->Param["classname"],$this->Param["gpname"]) = $this->student_model->classGP($this->Param['classno']);
                foreach($this->CL_Submenu as $key=>$val){
                    $this->Param['param']['menus'].="<li><a href=\"".$this->CL_Submenu[$key]."\">".$key."</a></li>";
                }

                $this->Param['navi_seq'] = "classes";
                $this->Param['breadcrumb'] = $this->getClassBread('Attendance');
                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_student', $this->Param, true);
                $contents_data['contents'] = $this->load->view('student/attmonths', $this->Param, true);
                $layout_data = $this->common_layout($contents_data);
                $this->load->view('layouts/layout_student', $layout_data, false);
        endswitch;
    }

    /*** Classes > Class Inquiry Edit Function for Dhtmlx Start ****/
    function sendmessage($mode=''){
        if ($this->session->userdata('STDSESS_LOGIN') == false) redirect(site_url('student/login'));
       // if (!$this->zacl->check_acl('edit')) redirect(site_url('student/login'));
        $this->Param['stno'] = $this->session->userdata('STDSESS_USERNO');

        $this->Param['fromemail'] = $this->session->userdata('STDSESS_EMAIL');
        switch($mode):
            //case 'getConStudentList': $this->getConStudentList($this->Param);  break;
            case 'getComTeachers': $this->getComTeachers($this->Param);  break;
            case 'setSendMessage': $this->setSendMessage($this->Param);  break;
            default:
                $this->Param['navi_seq'] = "messages";
                $this->Param['breadcrumb'] = array('Home' => $this->studentRoot,'Messages' => null, 'Send Message' => null);
                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_student', $this->Param, true);
                $contents_data['contents'] = $this->load->view('student/sendmessage', $this->Param, true);
                $layout_data = $this->common_layout($contents_data);
                $this->load->view('layouts/layout_student', $layout_data, false);
        endswitch;
    }
    function getComTeachers($params){$xmlcontents = $this->student_model->getComTeachers($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}

    function setSendMessage($params)
    {
        $dirpath = $_SERVER['DOCUMENT_ROOT'] . "/userfiles/uploaded/message/";

        $params["newfilename"] = "";
        if (!empty($_FILES["uploadfile"])):
            $params["newfilename"] = $this->files_upload($_FILES["uploadfile"]["name"], $_FILES["uploadfile"]["type"], $_FILES["uploadfile"]["error"], $_FILES["uploadfile"]["tmp_name"], $dirpath);
        endif;

        if (!empty($params["receiveremail"])) {

            $ss_email = $this->session->userdata('STDSESS_EMAIL');
            $ss_username = $this->session->userdata('STDSESS_USERNAME');
            $this->email->from('smtp@schooldname.com', $ss_username);
            $this->email->to($params["receiveremail"]);
            $this->email->bcc("records@schooldname.com");
            $this->email->subject($params["subject"]);
            $this->email->message($params["FCKeditor1"]);
            if (!empty($params["newfilename"])) {
                $this->email->attach($dirpath . $params["newfilename"]);
            }
            $this->email->send();
            $this->session->set_flashdata('scheduleemail', '<div class="alert alert-success fade in"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Success!</strong> Your message has been sent successfully.</div>');
        }
        $params["newfilename"]="";

        print_r("<SCRIPT>parent.showfullmsg(\"msgResult\",\"Your message was sent successfully.\"); parent.myCallBack();</SCRIPT>");
    }

    function files_upload($Fname,$Ftype,$Ferror,$Ftmpname,$file_path){ // #���ϸ�,������ �Ű�����
        $errormsg = ""; $file_name1=""; $msgfilenam ="";
        //$msgfilenam = "File# ".($j+1)." (".$Fname.")";
        // 2.���ε�� ������ ���翩�� �� ���ۻ��� Ȯ��
        if (isset($Fname) && !$Ferror):
            $file_name1=$this->confirmFname($Fname,$file_path);
            // 4.����ϴ� �̹��������̶�� ������ ��ġ�� �̵�
            if(move_uploaded_file($Ftmpname,$file_path.$file_name1)):
                // 5.���ε�� �̹��� ������ ���
                $errormsg .= $msgfilenam." uploaded successfully<br>"; //Success message
                //$filenam = $file_name1;
            endif; //if , move_uploaded_file
        else:
            //$errormsg="";
            // 6.������ �����ϴ��� üũ
            if ($Ferror > 0):
                //echo '<p>���� ���ε� ���� ����: <strong>';
                // ���� ������ ���
                switch ($Ferror):
                    case 1: $errormsg .= $msgfilenam."File# (".$Fname.") php.ini ������ upload_max_filesize �������� �ʰ���(���ε� �ִ�뷮 �ʰ�)"; break;
                    case 2:	$errormsg .= $msgfilenam."Form���� ������ MAX_FILE_SIZE �������� �ʰ���(���ε� �ִ�뷮 �ʰ�)"; break;
                    case 3:	$errormsg .= $msgfilenam."���� �Ϻθ� ���ε� ��"; break;
                    case 4:	$errormsg .= $msgfilenam."���ε�� ������ ����"; break;
                    case 6:	$errormsg .= $msgfilenam."��밡���� �ӽ������� ����";	break;
                    case 7:	$errormsg .= $msgfilenam."��ũ�� �����Ҽ� ����"; break;
                    case 8:	$errormsg .= $msgfilenam."���� ���ε尡 ������"; break;
                    default: $errormsg .= $msgfilenam."�ý��� ������ �߻�"; break;
                endswitch; // switch
                //echo '</strong></p>';
            endif; // if

            //echo $errormsg;

        endif; //if , isset

        return $file_name1;
    }
    function confirmFname($Fname,$t_MapPath){ // #���ϸ�,������ �Ű�����
        $attach_file = explode(".",$Fname); // #���ϸ� �и�
        $strName = $attach_file[0];     // #���ϸ�
        $strExt =$attach_file[1];       // #Ȯ����
        $bExist = true;  //#�ϴ� ������ �ִٰ� �����ϴ� �Ҹ� ����
        $strFileName = $t_MapPath.$strName.".".$strExt;  #��ü ���
        $FileName = $strName.".".$strExt;
        $countFileName = 0;
        If(file_exists($strFileName)):
            while($bExist): // #�켱 �ִٰ� ����
                if(file_exists($strFileName)):
                    $countFileName = $countFileName + 1 ;
                    $FileName1 = $strName."_".$countFileName.".".$strExt;
                    $strFileName  = $t_MapPath.$FileName1;
                else:
                    $bExist = false;
                endif;
            endwhile;
            return $FileName1;
        else:
            return $FileName;
        endif;
    }


}