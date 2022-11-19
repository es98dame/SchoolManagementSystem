<?php



defined('BASEPATH') OR exit('No direct script access allowed');







class Aliweb extends CI_Controller



{







    // gbnum - assignment:1 , discussion:2, message:3, send message in popup:4, books:5, assignment basic:6







    public $aliwebRoot="/index.php/aliweb/";



    public $Param = array();







    public $CL_Submenu = array(



        "Gradebook"=>"/index.php/aliweb/assigngrade",



        "Assignments"=>"/index.php/aliweb/assignments",



        "Attendance"=>"/index.php/aliweb/attweeks",



        "Students"=>"/index.php/aliweb/classstudents",



        "Teachers"=>"/index.php/aliweb/classteachers",



        "Categories"=>"/index.php/aliweb/assigncate"



    );



    public $RC_Submenu = array(



        "Attendance"=>"/index.php/aliweb/rattweeks",



        "Students"=>"/index.php/aliweb/rclassstudents",



        "Teachers"=>"/index.php/aliweb/rclassteachers"



    );











    function __construct()



    {



        parent::__construct();







        $this->load->library('form_validation');



        $this->load->library('session');



        $this->load->model("aliweb_model");







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



            $layout_data['header'] = $this->load->view('common/header_aliweb', null, true);



            $layout_data['navigation'] = $this->load->view('common/navigation_aliweb', null, true);



            $layout_data['breadcrumb'] = "";



            if($data['breadcrumb']){



                $layout_data['breadcrumb'] = $data['breadcrumb'];



            }



            $layout_data['content_body'] = $data['contents'];



            $layout_data['footer'] = $this->load->view('common/footer_aliweb', null, true);



            return $layout_data;



        }



    }



    function index()



    {



        if ($this->session->userdata('ALISESS_LOGIN') == true)



            redirect(site_url('aliweb/students'));



        else



            $this->login();







        //$this->load->view('aliweb/login','',false);



    }







    function login()



    {



        $this->load->helper('form');



        $this->load->library('form_validation');







        if ($_SERVER['REQUEST_METHOD'] == 'GET')



        {



            $this->Param['breadcrumb'] = array('Home' => $_SERVER["PHP_SELF"]);



            $init_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



            $init_data['contents'] = $this->load->view('aliweb/login',$init_data,true);



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



                $this->aliweb_model->record_login_attempt($userid, $ip_address, 0, $user_agent, 'form validation: '.validation_errors('[',']'));



                $this->Param['breadcrumb'] = array('Home' => $_SERVER["PHP_SELF"]);



                $init_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $init_data['contents'] = $this->load->view('aliweb/login',$init_data,true);



                $layout_data = $this->common_layout($init_data);



                $this->load->view('layouts/layout_login',$layout_data,false);



            }else {



                $user = $this->aliweb_model->getLoginInfo( trim($this->input->post('userid', TRUE)) );







                if($user!=null){



                    $pass = $this->input->post('userpwd', TRUE);



                    if ($this->checkPassword($pass,$user['passw']))



                    {



                        $this->aliweb_model->record_login_attempt($userid, $ip_address, 1, $user_agent, 'successful login');



                        $this->aliweb_model->update_last_login($userid,$ip_address);







                        $gp = $this->aliweb_model->currentGP();







                        $tnocnt = $this->aliweb_model->getExistRemediation($user['no']);







                        $this->session->set_userdata(array(



                            'ALISESS_USERNO'	=> $user['no'],



                            'ALISESS_USERNAME'	=> $user['firstname'].' '.$user['lastname'],



                            'ALISESS_USERID'	=> $user['user_ID'],



                            'ALISESS_EMAIL'		=> $user['email'],



                            'TOPLEVEL_AUTH'		=> $user['roleid'],



                            'ALISESS_EXREMED'	=> $tnocnt,



                            'ALISESS_LOGIN'	=> true,



                            'GPNO' => $gp



                        ));







                        if($user['roleid']==1 || $user['roleid']==2 || $user['roleid']==4 ){



                            redirect(site_url('aliweb/students'));



                        }else{



                            redirect(site_url('aliweb/classinquiry'));



                        }



                    }else{



                        //$this->form_validation->set_rules('userpwd', 'Password', 'callback__invalid_password');



                        //$this->form_validation->run();



                        $this->session->set_flashdata("successnewpw","<span style='color:red;'>Incorrect password</span>");



                        $this->aliweb_model->record_login_attempt($userid, $ip_address, 0, $user_agent, 'password check: '.validation_errors('[',']'));







                        $this->Param['breadcrumb'] = array('Home' => $_SERVER["PHP_SELF"]);



                        $init_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                        $init_data['contents'] = $this->load->view('aliweb/login',$init_data,true);



                        $layout_data = $this->common_layout($init_data);



                        $this->load->view('layouts/layout_login',$layout_data,false);







                    }



                }else{



                    $this->session->set_flashdata("successnewpw","<span style='color:red;'>Incorrect account</span>");



                    // $this->form_validation->set_rules('userpwd', 'Password', 'callback__invalid_password');



                    // $this->form_validation->run();



                    $this->aliweb_model->record_login_attempt($userid, $ip_address, 0, $user_agent, 'password check: '.validation_errors('[',']'));







                    $this->Param['breadcrumb'] = array('Home' => $_SERVER["PHP_SELF"]);



                    $init_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                    $init_data['contents'] = $this->load->view('aliweb/login',$init_data,true);



                    $layout_data = $this->common_layout($init_data);



                    $this->load->view('layouts/layout_login',$layout_data,false);



                }



            }



        }



        else die('invalid request method:'.$_SERVER['REQUEST_METHOD']);



    }



    function logout()



    {



        $this->session->unset_userdata('ALISESS_USERNO');



        $this->session->unset_userdata('ALISESS_USERNAME');



        $this->session->unset_userdata('ALISESS_USERID');



        $this->session->unset_userdata('ALISESS_EMAIL');



        $this->session->unset_userdata('TOPLEVEL_AUTH');



        $this->session->unset_userdata('GPNO');



        $this->session->unset_userdata('SEL_CLASSNO');



        $this->session->unset_userdata('SEL_CLASSNAME');



        $this->session->set_userdata('ALISESS_LOGIN', false);



        redirect(site_url('aliweb/login'),'refresh');



    }



    //use



    function checkPassword($pass,$dbpassw)



    {



        $salt = explode(':',$dbpassw);



        $wr_rpwd = $this->aliweb_model->fn_generate_salted_password($pass,$salt[1]);







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



            list($isgubun,$uid) = $this->aliweb_model->checkEmailKey($this->Param['ema']);



            if(!empty($isgubun)&&!empty($uid)){



                $init_data['ema'] = $this->Param['ema'];



                $init_data['gpart'] = $isgubun;



                $init_data['uid'] = $uid;



            }else{



                //$init_data['session_top'] = "This reset password link has expired. <a href='/en/contents/lost?grp=".$isgubun."'>Please resend yourself a new link.</a>";



                $init_data['session_top'] = "This reset password link has expired. <a href='".site_url("aliweb/login")."'>Please go back to Login.</a>";



            }



        }else{



            $init_data['session_top'] = "This is wrong URL. <a href='".site_url("aliweb/login")."'>Please go back to Login.</a>";



        }







        switch($mode):



            case 'setNewPassw':



                $this->aliweb_model->setNewPW($this->Param);



                switch($isgubun){



                    case '9': //admin



                        $this->session->set_flashdata('successnewpw','<div class="alert alert-success fade in"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Success!</strong> Your password was updated.</div>');



                        redirect(site_url('aliweb/login')); break;



                    default:



                }



                break;



            default:







                $this->Param['breadcrumb'] = array('Home' => $_SERVER["PHP_SELF"], 'Reset Password' => null);



                $init_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $init_data['contents'] = $this->load->view('aliweb/reset',$init_data,true);



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



                    list($UID, $UFULL, $UEMAIL) = $this->aliweb_model->getValidate($this->Param);



                    if(!empty($UID)):







                        $key = $this->aliweb_model->setRecoverPW($UID, $UFULL, $UEMAIL,$this->Param['grp']);



                        $this->sendNEWPWMail($key, $UID, $UFULL, $UEMAIL);







                        $this->session->set_flashdata('session_top','<div class="alert alert-success fade in"><a href="#" class="close" data-dismiss="alert">&times;</a>A link to reset your password has been emailed to the address on file.</div>');



                    else:



                        $this->session->set_flashdata('session_top','<div class="alert alert-danger fade in"><a href="#" class="close" data-dismiss="alert">&times;</a>There were some errors with your submission.</div>');



                        $init_data['session_txt'] = "<span style='color:red;'>Invalid userID/email</span>";



                    endif;



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



                $init_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $init_data['contents'] = $this->load->view('aliweb/lost',$init_data,true);



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



    function sendNewPWMail($key, $UID, $toname, $toemail){



        $nContents = "<ul>";



        $nContents .= "<li>Hello ".$toname."</li>";



        $nContents .= "<li>Below is the link to reset your password for your account:</li>";



        $nContents .= "<li>Username: ".$UID."</li>";



        $nContents .= "<li>http://".$_SERVER['HTTP_HOST'].substr($_SERVER["PHP_SELF"],0,-4)."reset?ema=".$key."</li>";



        $nContents .= "<li>If you did not request to reset your password, there is no need to be concerned, someone else may have accidentally entered the wrong username when requesting to reset their password. Your account is secure and password will not be changed unless you click the link above.</li>";



        $nContents .= "<li>Thank You,</li>";



        $nContents .= "<li>Institution Name</li>";



        $nContents .= "<li>*********************</li>";



        $nContents .= "<li>DO NOT REPLY TO THIS EMAIL</li>";



        $nContents .= "</ul>";







        $this->email->from("smtp@schooldname.com","ALI");



        $this->email->to($toemail);



        //$this->email->reply_to('my-email@gmail.com', 'Explendid Videos');



        $this->email->bcc("records@schooldname.com");



        $this->email->subject("Reset ALI Password");



        $this->email->message($nContents);







        $this->email->send();



    }



    function sendemail($mode=''){



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));







        switch($mode){



            case "setSend": $this->setSend($this->Param); break;



            default:



                $this->Param['useremail'] =$this->session->userdata('ALISESS_EMAIL');



                $contents_data['breadcrumb'] = "";



                $contents_data['contents'] = $this->load->view('aliweb/sendemail', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_popup', $layout_data, false);



                break;



        }



    }



    function setSend($params){



        $dirpath = $_SERVER['DOCUMENT_ROOT']."/scheduler/emails/";







        $params["newfilename"]="";



        if(!empty($_FILES["uploadfile"])):



            $params["newfilename"]= $this->files_upload($_FILES["uploadfile"]["name"],$_FILES["uploadfile"]["type"],$_FILES["uploadfile"]["error"],$_FILES["uploadfile"]["tmp_name"],$dirpath);



            //if(!empty($params["newfilename"])):



            //    $action = $this->ali_model->add_row_file($params);



            //endif;



        endif;







        if(!empty($params["receiveremail"])):



            //$arrname = explode(",",$params["receivername"]);







            $ss_email = $this->session->userdata('ALISESS_EMAIL');



            $ss_username = $this->session->userdata('ALISESS_USERNAME');



            $this->email->from("smtp@schooldname.com", $ss_username);



            $this->email->to($params["receiveremail"]);



            $this->email->bcc("records@schooldname.com");



            $this->email->subject($params["subject"]);



            $this->email->message($params["FCKeditor1"]);



            $this->email->send();



            $this->session->set_flashdata('scheduleemail','<div class="alert alert-success fade in"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Success!</strong> Your message has been sent successfully.</div>');







        endif;



        $params["newfilename"]="";







        print_r("<SCRIPT>alert('Your message was sent successfully.'); self.close();</SCRIPT>");



    }







    function scheduler()



    {



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));







        $this->load->helper('calendar_functions');



        $this->load->library('alidb');







        $TOPLEVEL_AUTH=$this->session->userdata('TOPLEVEL_AUTH');



        $TOPLEVEL_NO=$this->session->userdata('ALISESS_USERNO');











        $ALIDB = new ALIDb();



        $holidayarr = $ALIDB->queryGetHolidays('','H');







        $nowyear ="";



        if(!empty($this->Param['currentMon']) ):



            $d = intval($this->Param['nextvalue']);



            $nowyear = $this->Param['nowyear'];



            $arr['year'] = date("Y",mktime(0,0,0,$this->Param['currentMon']+$d,1,$nowyear));



            $arr['month'] = date("m",mktime(0,0,0,$this->Param['currentMon']+$d,1,$nowyear));



            $this->Param['currentMon'] = $arr['month'];



            $this->Param['nowyear'] = $arr['year'];



            $this->Param['montitle']  = date('F, Y',strtotime($arr['year']."-".$arr['month']."-01"));







        else:



            $nowyear = date("Y");



            $arr['year']=date("Y");



            $arr['month'] = date("m",mktime(0,0,0,date("n")+0,1,$nowyear));



            $this->Param['currentMon'] = $arr['month'];



            $this->Param['montitle'] = date('F, Y',strtotime($arr['year']."-".$arr['month']."-01"));



            $this->Param['nowyear'] = $nowyear;



            $nextvalue='';



        endif;







        if(!empty($this->Param['currentDay'])):



            $this->Param['currentMon'] = date("m",strtotime($this->Param['currentDay']));



            $this->Param['nowyear'] = date("Y",strtotime($this->Param['currentDay']));



            $this->Param['montitle'] = date('F, Y',strtotime($this->Param['currentDay']));



        else:



            $this->Param['currentDay'] = date("Y-m-d");



        endif;











        if(empty($this->Param['permission'])):



            $permi = "P";



        else:



            $permi = $this->Param['permission'];



        endif;







        if(empty($this->Param['permission'])):



            $permi = "P";



        else:



            $permi = $this->Param['permission'];



        endif;







        $pmode = "";



        if(!empty($this->Param['pmode'])):



            $pmode = $this->Param['pmode'];



        endif;







//=============== reply



        $this->Param['messag']="";







        if($pmode=='REPLYINS' && !empty($this->Param['calboard_no']) && !empty($this->Param['replymessage']) ):



            $ALIDB->query("INSERT INTO `ali_calboard_reply` (reply_no,calboard_no,user_no,contents,thisday,regdate) VALUES(NULL,".$this->Param['calboard_no'].", ".$this->session->userdata('ALISESS_USERNO').",'".htmlspecialchars($this->Param['replymessage'],ENT_QUOTES)."', '".$this->Param['currentDay']."', DATE_ADD(NOW(), INTERVAL 2 HOUR) )");



            $this->Param['messag'] ="alert('Added Reply!');";



        endif;







        if($pmode=='REPLYDEL' && !empty($this->Param['replyno']) ):



            $ALIDB->query("delete from ali_calboard_reply where reply_no=".$this->Param['replyno']."");



            $this->Param['messag']="alert('Deleted Reply!');";



        endif;







        if( $pmode=='INS' && !empty($this->Param['newsubject']) && !empty($this->Param['newcontents']) ):



            $ALIDB->query("INSERT INTO `ali_calboard` (calboard_no, user_no, subject, contents, thisday, permission, regdate ) VALUES(NULL, ".$this->session->userdata('ALISESS_USERNO').",'".htmlspecialchars($this->Param['newsubject'],ENT_QUOTES)."','".htmlspecialchars($this->Param['newcontents'],ENT_QUOTES)."', '".$this->Param['currentDay']."','".$permi."', DATE_ADD(NOW(), INTERVAL 2 HOUR) )");



            $this->Param['messag']="alert('Added Message!');";



        endif;







        if($pmode=='MOD' && !empty($this->Param['renewsubject']) && !empty($this->Param['renewcontents']) && !empty($this->Param['calboard_no']) ):



            $ALIDB->query("update ali_calboard set permission='".$permi."', state='".$this->Param['state']."', subject='".htmlspecialchars($this->Param['renewsubject'],ENT_QUOTES)."',contents='".htmlspecialchars($this->Param['renewcontents'],ENT_QUOTES)."', regdate=DATE_ADD(NOW(), INTERVAL 2 HOUR) where calboard_no=".$this->Param['calboard_no']."");



            $this->Param['messag']="alert('Modified Message!');";



        endif;











        if( $pmode=='DEL' && !empty($this->Param['calboard_no']) ):



            $ALIDB->query("delete from ali_calboard where calboard_no=".$this->Param['calboard_no']."");



            $this->Param['messag']="alert('Deleted Message!');";



        endif;











        $aunum="";



        if($TOPLEVEL_AUTH==3){



            $aunum="3";



        }







        if($TOPLEVEL_AUTH==1 || $TOPLEVEL_AUTH==2 || $TOPLEVEL_AUTH==4 ){



            $aunum="1,2,4,3";



        }











        $topdata = array();



        $indata = array();







        $topdata = $ALIDB->queryGetCalendar($aunum,'S'); //Shared



        $indata = $ALIDB->queryGetCalendar($aunum,'P'); //Personal











        if(empty($this->Param['swmode'])):



            $this->Param['swmode']="calmonth";



        endif;











        $query_search="";







        if(!empty($this->Param['searchword'])):







            if(!empty($this->Param['spart'])):



                $query_search = "and UPPER(a.".$this->Param['spart'].") like UPPER('%".$this->Param['searchword']."%') ";



            else:



                $query_search = "and UPPER(a.subject) like UPPER('%".$this->Param['searchword']."%') ";



            endif;







        else:



            if(!empty($this->Param['currentDay'])):



                $query_search = "and a.thisday='".$this->Param['currentDay']."'";



            endif;



        endif;







        $this->Param['queryGetStaff'] ="";







        if(!empty($this->Param['staffs'])):



            $query_search .= "and a.user_no = ".$this->Param['staffs']." ";



        else:



            $this->Param['staffs'] = $TOPLEVEL_NO;



        endif;







        if(trim($this->Param['swmode'])=="calmonth"):



            $vToday=getToday(0);



            $this->Param['createCalendar'] = createCalendar($arr,$holidayarr,$indata,$topdata,$vToday);



        endif;











        $this->Param['queryGetStaff'] = $ALIDB->queryGetStaff($this->Param['staffs']);











        $mainContents = "";











        $w=0;



        $result2 = $ALIDB->queryAsArray("select b.roleid, a.calboard_no, a.user_no,b.firstname, b.lastname, a.state, a.permission, a.subject, a.contents, a.thisday, a.regdate from ali_calboard a, ali_user b where a.user_no=b.no ".$query_search." order by a.thisday DESC");



        foreach( $result2 as $i => $val ):



            $replycontent="";



            $cnt=0;







            $result3 = $ALIDB->queryAsArray("select a.reply_no,a.calboard_no,a.user_no,b.initial,b.firstname,b.lastname,a.contents,a.thisday,a.regdate from ali_calboard_reply a, ali_user b where a.user_no=b.no  and a.calboard_no=".$val['calboard_no']." order by a.thisday DESC");



            foreach( $result3 as $i => $val3 ): $delreply="";



                if($val3['user_no']==$TOPLEVEL_NO):



                    $delreply = "<span style='cursor:pointer;' onClick='modifydata(".$val3['calboard_no'].",\"REPLYDEL\",\"".$val3['reply_no']."\");'>(x)</span>";



                endif;



                $replycontent .= "<div align='left'><font size=\"-2\"><strong>".$val3['firstname']." ".$val3['lastname']."</strong></font> <span style='font-size:9px;'>(".$val3['regdate'].")</span> : </div> <div align='left' style='margin-left:5px;'>".$val3['contents']." ".$delreply." </div>";



                $cnt++;



            endforeach;











            if($TOPLEVEL_AUTH==$val['roleid'] || ($TOPLEVEL_AUTH=='1' || $TOPLEVEL_AUTH=='2' || $TOPLEVEL_AUTH=='4') ):



                $mainContents = '<form action="scheduler" method="post" name="updateform'.$val['calboard_no'].'" id="updateform'.$val['calboard_no'].'">';



                $mainContents .= '<tr bgcolor="#D5FA56">';



                $mainContents .= '<td width="20%" height="20" style="cursor: pointer;" onClick="javascript:var dee=document.getElementById(\'con_fam_info2\').style.display; if(dee==\'block\'){document.getElementById(\'con_fam_info2\').style.display=\'none\';} if(dee==\'none\'){document.getElementById(\'con_fam_info2\').style.display=\'block\';}" align="right"> '.date("F,d,Y",strtotime($val['thisday'])).' </td>';



                $mainContents .= '<td align="left" width="60%">';



                if($val['user_no']==$TOPLEVEL_NO):



                    $mainContents .= '<table style="font-size: 12px; color: Black; font-family: \'Lucida Sans\';" width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td><input type="text" name="renewsubject" value="'.$val['subject'].'" size="70" ></td><td width="30">';



                    $mainContents .= '<select name="state">';



                    $sel1='';$sel2='';



                    if($val['state']=='P'): $sel1 ='selected'; endif;



                    $mainContents .= '<option value="P" '.$sel1.'>Processing</option>';



                    if($val['state']=='C'): $sel2 ='selected'; endif;



                    $mainContents .= '<option value="C" '.$sel2.'>Completed</option>';



                    $mainContents .= '</select>';



                    $mainContents .= '</td></tr></table>';



                else:



                    $mainContents .= '<table style="font-size: 12px; color: Black; font-family: \'Lucida Sans\';" width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td><strong>'.$val['subject'].'</strong></td><td width="30">';



                    if($val['state']=='P'): $mainContents .= 'Processing'; endif;



                    if($val['state']=='C'): $mainContents .= 'Completed'; endif;



                    $mainContents .= '</td></tr></table>';



                endif;



                $mainContents .= '</td>';



                $mainContents .= '<td width="20%">Reply ('.$cnt.')</td>';



                $mainContents .= '</tr>';



                $mainContents .= '<tr bgcolor="#E7FC9E">';



                $mainContents .= '<td align="right"  valign="top">Message : </td>';



                $mainContents .= '<td align="left" valign="top">&nbsp;';



                if($val['user_no']==$TOPLEVEL_NO):



                    $mainContents .= '<textarea cols="60" rows="8" name="renewcontents" style="background-color: #F5FFDD;">'.$val['contents'].'</textarea> </br> Permission :';



                    $mainContents .= '<select name="permission">';



                    $sel3='';$sel4='';



                    if($val['permission']=='P'): $sel3='selected'; endif;



                    $mainContents .= '<option value="P" '.$sel3.'>Personal</option>';



                    if($val['permission']=='S'): $sel4='selected'; endif;



                    $mainContents .= '<option value="S" '.$sel4.'>Shared</option>';



                    $mainContents .= '</select>';



                    $mainContents .= '<div align="right">';



                    $mainContents .= '<input type="button" name="swmode" value="mod" onclick="modifydata('.$val['calboard_no'].',\'MOD\',\'\');" >';



                    $mainContents .= '<input type="button" name="swmode" value="del"  onclick="modifydata('.$val['calboard_no'].',\'DEL\',\'\');" >';



                    $mainContents .= '</div>';



                else:



                    $mainContents .= "<span >written by <strong>".$val['firstname']." ".$val['lastname']."</strong> ( ".$val['regdate']." )</span>";



                    $mainContents .= "<div style='margin-left:20px;'>".htmlentities($val['contents'])."</div>";



                endif;







                $mainContents .= '<input type="hidden" name="calboard_no" value="'.$val['calboard_no'].'">';



                $mainContents .= '<input type="hidden" name="swmode" value="caldetail">';



                $mainContents .= '<input type="hidden" name="pmode">';



                $mainContents .= '<input type="hidden" name="currentDay" value="'.$val['thisday'].'">';



                $mainContents .= '</td>';



                $mainContents .= '<td valign="top">';



                $mainContents .= $replycontent;



                $mainContents .= '<hr width="95%" size="1">';



                $mainContents .= '<div>';



                $mainContents .= '<table border="0" cellspacing="1" cellpadding="0" bgcolor="#E7FC9E" style="font-size: 12px; color: Black; font-family: \'Lucida Sans\';">';



                $mainContents .= '<tr><td><textarea cols="25" rows="3" name="replymessage"></textarea></td></tr>';



                $mainContents .= '<tr><td align="right"><input type="button" value="add reply" onclick="modifydata('.$val['calboard_no'].',\'REPLYINS\',\'\');"></td></tr>';



                $mainContents .= '<input type="hidden" name="replyno" >';



                $mainContents .= '</table>';



                $mainContents .= '</div>';



                $mainContents .= '</td>';



                $mainContents .= '</tr>';



                $mainContents .= '</form>';



            endif;



            $w++;



        endforeach;



        if($w==0 && $this->Param['swmode']=='calSearch'):



            $mainContents .= '<tr bgcolor="#D2E9FF">';



            $mainContents .= '<td height="300" colspan="3" align="center" style="font-size: 14px;">Your search did not match any data.</td>';



            $mainContents .= '</tr>';



        else:



            $mainContents .= '<form action="scheduler" method="post" name="newform" id="newform">';



            $mainContents .= '<tr bgcolor="#EFF25E">';



            $mainContents .= '<td style="cursor: pointer;" onClick="javascript:var dee=document.getElementById(\'con_fam_info\').style.display; if(dee==\'block\'){document.getElementById(\'con_fam_info\').style.display=\'none\'; } if(dee==\'none\'){document.getElementById(\'con_fam_info\').style.display=\'block\';}" height="20" align="right">'.date("F,d,Y",strtotime($this->Param['currentDay'])).': </td>';



            $mainContents .= '<td ><input type="text" name="newsubject" value="" size="70"></td>';



            $mainContents .= '<td >&nbsp;</td></tr>';



            $mainContents .= '<tr bgcolor="#E7FC9E" >';



            $mainContents .= '<td align="right"  valign="top">New Message : </td>';



            $mainContents .= '<td ><textarea cols="60" rows="8" name="newcontents"></textarea> </br>Permission :';



            $mainContents .= '<select name="permission"><option value="P">Personal</option><option value="S">Shared</option></select>';



            $mainContents .= '<div><input type="button" value="add"  onclick="adddata();"></div></td>';



            $mainContents .= '<td >&nbsp;</td></tr>';



            $mainContents .= '<input type="hidden" name="swmode" value="caldetail">';



            $mainContents .= '<input type="hidden" name="pmode" value="INS">';



            $mainContents .= '<input type="hidden" name="currentDay" value="'.$this->Param['currentDay'].'">';



            $mainContents .= '</form>';



        endif;











        /** ----------------- */



        $this->Param['navi_seq'] = "scheduler";



        $this->Param['breadcrumb'] = array('Home' => $_SERVER["PHP_SELF"], 'Scheduler' => null);



        $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



        $this->Param['mainContents'] =$mainContents;



        $contents_data['contents'] = $this->load->view('aliweb/scheduler', $this->Param, true);



        $layout_data = $this->common_layout($contents_data);



        $this->load->view('layouts/layout_aliweb', $layout_data, false);







        $ALIDB->close();







    }







    function students($mode=''){
        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));
        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));
        $this->Param['username'] = $this->session->userdata('ALISESS_USERNAME');
        switch($mode):
            case 'getStudents': $this->getStudents($this->Param);  break;
            case 'setStudent': $this->setStudent($this->Param);  break;
            case 'getFamilies': $this->getFamilies($this->Param);  break;
            case 'setFamily': $this->setFamily($this->Param);  break;
            case 'getConsults': $this->getConsults($this->Param);  break;
            case 'setConsult': $this->setConsult($this->Param);  break;
            case 'menucontext': $this->menucontext($this->Param);  break;
            case 'menuRcordContext': $this->menuRcordContext($this->Param);  break;
            case 'getAcademicRecords': $this->getAcademicRecords($this->Param);  break;
            case 'setRecord': $this->setRecord($this->Param);  break;
            case 'createexcel': $this->createexcel($this->Param);  break;
            case 'getStudentGrades': $this->getStudentGrades($this->Param);  break;
            case 'printgrade': $this->printgrade($this->Param);  break;
            case 'getFinance': $this->getFinance($this->Param);  break;
            case 'setFinance': $this->setFinance($this->Param);  break;
            case 'menuFinanceContext': $this->menuFinanceContext($this->Param);  break;
            case 'createpdf': $this->createpdf($this->Param);  break;
            case 'exportStudentList': $this->exportStudentList($this->Param);  break;
            case 'exportGrade': $this->exportGrade($this->Param);  break;
            case 'createTranscript': $this->createTranscript($this->Param);  break;
            case 'getstudentview': $this->getstudentview($this->Param);  break;
            default:
                $this->Param['navi_seq'] = "student";
                $this->Param['breadcrumb'] = array('Home' => $_SERVER["PHP_SELF"], 'Manage Student Inquiry' => null);
                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);
                $contents_data['contents'] = $this->load->view('aliweb/students', $this->Param, true);
                $layout_data = $this->common_layout($contents_data);
                $this->load->view('layouts/layout_aliweb', $layout_data, false);
        endswitch;
    }

    function getStudents($params){ $xmlcontents = $this->aliweb_model->getStudents($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;    }
    function setStudent($params){$xmlcontents = $this->aliweb_model->setStudent($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}
    function getFamilies($params){$xmlcontents = $this->aliweb_model->getFamilies($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}
    function setFamily($params){$xmlcontents = $this->aliweb_model->setFamily($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}
    function getConsults($params){$xmlcontents = $this->aliweb_model->getConsults($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}
    function setConsult($params){$xmlcontents = $this->aliweb_model->setConsult($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}
    function menucontext($params){$xmlcontents = $this->aliweb_model->menucontext($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}
    function menuRcordContext($params){$xmlcontents = $this->aliweb_model->menuRcordContext($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}
    function getAcademicRecords($params){$xmlcontents = $this->aliweb_model->getAcademicRecords($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}
    function setRecord($params){$xmlcontents = $this->aliweb_model->setRecord($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}
    function menuFinanceContext($params){$xmlcontents = $this->aliweb_model->menuFinanceContext($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}
    function getFinance($params){$xmlcontents = $this->aliweb_model->getFinance($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}
    function setFinance($params){$xmlcontents = $this->aliweb_model->setFinance($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}
    function createexcel($params){
        if(trim($params['sno'])!="") {
            $row = $this->aliweb_model->getStudent($params['sno']);
            $this->load->library('excel');
            $inputFileType = 'Excel5';
            $inputFileName = $_SERVER['DOCUMENT_ROOT'] . "/userfiles/docm/Transcript.xlsx";
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
            $worksheet = $objPHPExcel->getActiveSheet();
            $objPHPExcel->getActiveSheet()->setCellValue('B9',$row['firstname']); //First Name
            $objPHPExcel->getActiveSheet()->setCellValue('E9', ''); //Middle Name
            $objPHPExcel->getActiveSheet()->setCellValue('G9',$row['lastname']); //Last Name
            $dob = explode("-",$row['birthday']);
            $objPHPExcel->getActiveSheet()->setCellValue('B11',$dob[1]); //Month of DOB
            $objPHPExcel->getActiveSheet()->setCellValue('C11',$dob[2]); //Day of DOB
            $objPHPExcel->getActiveSheet()->setCellValue('D11',$dob[0]); //Year of DOB
            $objPHPExcel->getActiveSheet()->setCellValue('G11',$row['student_ID']); //SEVIS No.
            $objPHPExcel->getActiveSheet()->setCellValue('B13',$row['address1']); //address1
            $objPHPExcel->getActiveSheet()->setCellValue('B14',$row['address2']); //address2
            $objPHPExcel->getActiveSheet()->setCellValue('G13',$row['register_day']); //student's first day in school
            $objPHPExcel->getActiveSheet()->setCellValue('G14',$params['lastdate']); //student's last day in school
            $objPHPExcel->getActiveSheet()->setCellValue('G37',$params['issdate']); //Date of Issuance
            $objPHPExcel->getActiveSheet()->setCellValue('E39',$params['advname'].", ".$params['advtitle']); //Advisorname,title
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment; filename=ALI Official Transcript (".$row['firstname']." ".$row['lastname'].").xlsx");
            header("Pragma: no-cache");
            header("Expires: 0");
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $inputFileType);  //downloadable file is in Excel 2003 format (.xls)
            ob_end_clean();
            $objWriter->save('php://output');
            exit();
        }
        exit();
    }
    function exportStudentList($params)
    {
        $this->load->library('excel');
        $this->excel->getActiveSheet()->setTitle('ALI Student List');
        $this->excel->getActiveSheet()->setCellValue('A1','ALI Student List');
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A1:F1');
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $users = $this->aliweb_model->getExportStudentList($params);
        $this->excel->getActiveSheet()->fromArray($users);
        $filename='ALIStudentList_'.date("YmdHis").'.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0'); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        ob_end_clean();
        $objWriter->save('php://output');
        exit;
    }
    function exportGrade($params){
        $this->load->library('excel');

        list ($params["classname"],$params["gpname"]) = $this->aliweb_model->classGP($params['classno']);
        $studentfullname="";
        $sql3 = "select CONCAT(firstname,' ',lastname) AS fullname from ali_students where students_no=".$params['stno'];
        $query3 = $this->db->query($sql3);
        if($query3->num_rows() > 0){
            $row3=$query3->row();
            $studentfullname = $row3->fullname;
        }

        $gl = "";
        $fg = $this->aliweb_model->getTotalGrade($params['classno'], $params['stno']);
        if ($fg>=0 && $fg<60 ) {
            $gl="F";
        } elseif ($fg>=60 && $fg<70 ) {
            $gl="D";
        } elseif ($fg>=70 && $fg<80 ) {
            $gl="C";
        } elseif ($fg>=80 && $fg<90 ) {
            $gl="B";
        } elseif ($fg>=90 && $fg<=100 ) {
            $gl="A";
        } else {
            $gl="error";
        }

        $this->excel->getActiveSheet()->setTitle($studentfullname);
        $this->excel->getActiveSheet()->setCellValue('A1',$params["gpname"]." / ".$params["classname"]." - ".$studentfullname." ".$gl." (".$fg."%)");
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A1:D1');
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth("60");
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth("10");

        $users = $this->aliweb_model->getExportGrade($params);
        $this->excel->getActiveSheet()->fromArray($users);
        $filename='Grade_'.$studentfullname.'_'.$params["gpname"].'_'.$params["classname"].'_'.date("YmdHis").'.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0'); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        ob_end_clean();
        $objWriter->save('php://output');
        exit;


    }




    function myaccount($mode=''){



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));







        switch($mode):



            case 'getMyaccount': $this->getMyaccount($this->Param);  break;



            case 'setMyaccount': $this->setMyaccount($this->Param); break;



            default:



                $this->Param['navi_seq'] = "myaccount";



                $this->Param['breadcrumb'] = array('Home' => $_SERVER["PHP_SELF"], 'Myaccount' => null);



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/myaccount', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;



    }



    function getMyaccount($params){$xmlcontents = $this->aliweb_model->getMyaccount($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}



    function setMyaccount($params){$xmlcontents = $this->aliweb_model->setMyaccount($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}







    function staffs($mode=''){



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));







        switch($mode):



            case 'getStaffs': $this->getStaffs($this->Param);  break;



            case 'setStaff': $this->setStaff($this->Param); break;



            case 'checkDuplicateUserID': $this->checkDuplicateUserID($this->Param); break;



            default:



                $this->Param['navi_seq'] = "setting";



                $this->Param['breadcrumb'] = array('Home' => $_SERVER["PHP_SELF"],'Setting' => $_SERVER["PHP_SELF"],'User' => null, 'Staffs' => null);



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/staffs', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;



    }



    function getStaffs($params){$xmlcontents = $this->aliweb_model->getStaffs($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}



    function setStaff($params){$xmlcontents = $this->aliweb_model->setStaff($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}



    function checkDuplicateUserID($params){$xmlcontents = $this->aliweb_model->checkDuplicateUserID($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}







    function getstudentview($params){



        $this->load->model("student_model");







        $user = $this->student_model->getLoginInfoBySTNO( $params['stno'] );



        if($user!=null){



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



        }



        //require('Student.php');



        //$student = new Student();



        //$student->login();



    }







    function instructors($mode=''){



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));







        switch($mode):



            case 'getInstructors': $this->getInstructors($this->Param);  break;



            case 'setInstructor': $this->setInstructor($this->Param); break;



            case 'checkDuplicateUserID': $this->checkDuplicateUserID($this->Param); break;



            default:



                $this->Param['navi_seq'] = "setting";



                $this->Param['breadcrumb'] = array('Home' => $_SERVER["PHP_SELF"],'Setting' => $_SERVER["PHP_SELF"],'User' => null, 'Instructors' => null);



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/instructors', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;



    }



    function getInstructors($params){$xmlcontents = $this->aliweb_model->getInstructors($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}



    function setInstructor($params){$xmlcontents = $this->aliweb_model->setInstructor($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}







    function administrators($mode=''){



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));







        switch($mode):



            case 'getAdministrators': $this->getAdministrators($this->Param);  break;



            case 'setAdministrator': $this->setAdministrator($this->Param); break;



            case 'checkDuplicateUserID': $this->checkDuplicateUserID($this->Param); break;



            default:



                $this->Param['navi_seq'] = "setting";



                $this->Param['breadcrumb'] = array('Home' => $_SERVER["PHP_SELF"],'Setting' => $_SERVER["PHP_SELF"],'User' => null, 'Administrators' => null);



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/administrators', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;



    }



    function getAdministrators($params){$xmlcontents = $this->aliweb_model->getAdministrators($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}



    function setAdministrator($params){$xmlcontents = $this->aliweb_model->setAdministrator($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}







    function trimester($mode=''){



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));







        switch($mode):



            case 'getTrimesters': $this->getTrimesters($this->Param);  break;



            case 'setTrimester': $this->setTrimester($this->Param); break;



            case 'checkDuplicateUserID': $this->checkDuplicateUserID($this->Param); break;



            case 'menucontext': $this->menucontext($this->Param);  break;



            default:



                $this->Param['navi_seq'] = "setting";



                $this->Param['breadcrumb'] = array('Home' => $_SERVER["PHP_SELF"],'Setting' => $_SERVER["PHP_SELF"],'Classes' => null, 'Trimesters' => null);



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/trimester', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;



    }



    function getTrimesters($params){$xmlcontents = $this->aliweb_model->getTrimesters($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}



    function setTrimester($params){$xmlcontents = $this->aliweb_model->setTrimester($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}







    function level($mode=''){



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));







        switch($mode):



            case 'getLevels': $this->getLevels($this->Param);  break;



            case 'setLevel': $this->setLevel($this->Param); break;



            case 'menucontext': $this->menucontext($this->Param);  break;



            default:



                $this->Param['navi_seq'] = "setting";



                $this->Param['breadcrumb'] = array('Home' => $_SERVER["PHP_SELF"],'Setting' => $_SERVER["PHP_SELF"],'Classes' => null, 'Levels' => null);



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/level', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;



    }



    function getLevels($params){$xmlcontents = $this->aliweb_model->getLevels($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}



    function setLevel($params){$xmlcontents = $this->aliweb_model->setLevel($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}







    function assignmentdefaultcategory($mode=''){



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));







        switch($mode):



            case 'getAssignDefaultCategory': $this->getAssignDefaultCategory($this->Param);  break;



            case 'setAssignDefaultCategory': $this->setAssignDefaultCategory($this->Param); break;



            //case 'checkDuplicateUserID': $this->checkDuplicateUserID($this->Param); break;



            case 'menucontext': $this->menucontext($this->Param);  break;



            default:



                $this->Param['navi_seq'] = "setting";



                $this->Param['breadcrumb'] = array('Home' => $_SERVER["PHP_SELF"],'Setting' => $_SERVER["PHP_SELF"],'Classes' => null, 'Assignment Categories' => null);



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/assignmentdefaultcategory', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;



    }



    function getAssignDefaultCategory($params){$xmlcontents = $this->aliweb_model->getAssignDefaultCategory($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}



    function setAssignDefaultCategory($params){$xmlcontents = $this->aliweb_model->setAssignDefaultCategory($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}







    function transcripts($mode=''){



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));







        switch($mode):



            case 'getTranscripts': $this->getTranscripts($this->Param);  break;



            case 'getTranscriptAtt': $this->getTranscriptAtt($this->Param); break;



            //case 'checkDuplicateUserID': $this->checkDuplicateUserID($this->Param); break;



            //case 'menucontext': $this->menucontext($this->Param);  break;



            default:



                $this->Param['navi_seq'] = "student";



                $this->Param['breadcrumb'] = array('Home' => $_SERVER["PHP_SELF"],'Student' => $_SERVER["PHP_SELF"],'Transcripts' => null);



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/transcripts', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;



    }



    function getTranscripts($params){$xmlcontents = $this->aliweb_model->getTranscripts($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}



    function getTranscriptAtt($params){$xmlcontents = $this->aliweb_model->getTranscriptAtt($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}







    function roster($mode=''){



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));



        switch($mode):



            case 'getRoster': $this->getRoster($this->Param);  break;



            case 'getComSchoolYear': $this->getComSchoolYear($this->Param);  break;



            case 'getComLevel': $this->getComLevel($this->Param);  break;



            default:



                $this->Param['navi_seq'] = "classes";



                $this->Param['breadcrumb'] = array('Home' => $_SERVER["PHP_SELF"],'Classes' => null, 'School Roster' => null);



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/roster', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;



    }



    function getRoster($params){$xmlcontents = $this->aliweb_model->getRoster($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function getComSchoolYear($params){$xmlcontents = $this->aliweb_model->getComSchoolYear($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function getComLevel($params){$xmlcontents = $this->aliweb_model->getComLevel($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}











    function warningletter($mode=''){



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));



        switch($mode):



            case 'getWarningLetter': $this->getWarningLetter($this->Param);  break;



            case 'getComSchoolYear': $this->getComSchoolYear($this->Param);  break;



            case 'setAssignRoster': $this->setAssignRoster($this->Param);  break;



            case 'setSendWLetter': $this->setSendWLetter($this->Param);  break;



            case 'exportWLetter': $this->exportWLetter($this->Param);  break;



            default:



                $this->Param['navi_seq'] = "classes";



                $this->Param['breadcrumb'] = array('Home' => $_SERVER["PHP_SELF"],'Classes' => null, 'Warning Letter' => null);



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/warningletter', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;



    }



    function exportWLetter($params)
    {
        $file = $_SERVER['DOCUMENT_ROOT'] . "/userfiles/exportexcel/WarningLetterList.xlsx";
        $this->load->library('excel');
        $this->excel->getActiveSheet()->setTitle('Student Attendance Total');
        $this->excel->getActiveSheet()->setCellValue('A1',$params["year"]." ".$params["trim"]." Trimester Student Attendance Total");
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->mergeCells('A1:I1');
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $users = $this->aliweb_model->getExportWLetter($params);
        $this->excel->getActiveSheet()->fromArray($users);
        $filename='StudentAttendanceTotal.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        ob_end_clean();
        $objWriter->save('php://output');
        exit;
    }



    function getWarningLetter($params){$xmlcontents = $this->aliweb_model->getWarningLetter($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function setSendWLetter($params)



    {



        $dirpath = $_SERVER['DOCUMENT_ROOT'] . "/userfiles/uploaded/message/";







        $params["newfilename"] = "";



        if (!empty($_FILES["uploadfile"])):



            $params["newfilename"] = $this->files_upload($_FILES["uploadfile"]["name"], $_FILES["uploadfile"]["type"], $_FILES["uploadfile"]["error"], $_FILES["uploadfile"]["tmp_name"], $dirpath);



        endif;







        if (!empty($params["receiveremail"])) {



            $ss_email = $this->session->userdata('ALISESS_EMAIL');



            $ss_username = $this->session->userdata('ALISESS_USERNAME');



            $this->email->from("smtp@schooldname.com", $ss_username);



            $this->email->to("records@schooldname.com");



            $this->email->bcc($params["receiveremail"]);



            $this->email->reply_to($ss_email,$ss_username);



            $this->email->subject($params["subject"]);



            $this->email->message($params["FCKeditor1"]);



            if (!empty($params["newfilename"])) {



                $this->email->attach($dirpath . $params["newfilename"]);



            }



            //$doc = $this->createpdf($params);



            // $attachment = chunk_split(base64_encode($doc));



            //  $this->email->AddStringAttachment($attachment,'attachment.pdf');







            if ($this->email->send() ) {



                $this->aliweb_model->update_row_warningletter($params);



                $this->session->set_flashdata('scheduleemail', '<div class="alert alert-success fade in"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Success!</strong> Your message has been sent successfully.</div>');



            }else{



                $this->session->set_flashdata('scheduleemail', '<div class="alert alert-danger fade in"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> Contact to Administrator.</div>');



            }



        }



        $params["newfilename"]="";







        print_r("<SCRIPT>parent.showfullmsg(\"msgResult\",\"Your message was sent successfully.\"); parent.myCallBack();</SCRIPT>");



    }











    function classroster($mode=''){



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));



        switch($mode):



            case 'getAssignRoster': $this->getAssignRoster($this->Param);  break;



            case 'getComSchoolYear': $this->getComSchoolYear($this->Param);  break;



            case 'createClasses': $this->createClasses($this->Param['year'],$this->Param['trim']);  break;



            case 'assignRoster': $this->assignRoster($this->Param['year'],$this->Param['trim']);  break;



            case 'getComLevel': $this->getComLevel($this->Param);  break;



            case 'setAssignRoster': $this->setAssignRoster($this->Param);  break;



            case 'getClassList': $this->getClassList($this->Param);  break;



            case 'setClassList': $this->setClassList($this->Param);  break;



            case 'getComClasses': $this->getComClasses($this->Param);  break;



            case 'getAssignRosterForm': $this->getAssignRosterForm($this->Param);  break;



            case 'menuRosterContext': $this->menuRosterContext($this->Param);  break;



            default:



                $this->Param['navi_seq'] = "classes";



                $this->Param['breadcrumb'] = array('Home' => $_SERVER["PHP_SELF"],'Classes' => null, 'Class Roster' => null);



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/classroster', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;



    }



    function menuRosterContext($params){$xmlcontents = $this->aliweb_model->menuRosterContext($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function getComClasses($params){$xmlcontents = $this->aliweb_model->getComClasses($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function getClassList($params){$xmlcontents = $this->aliweb_model->getClassList($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function setClassList($params){$xmlcontents = $this->aliweb_model->setClassList($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function getAssignRoster($params){$xmlcontents = $this->aliweb_model->getAssignRoster($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function setAssignRoster($params){$xmlcontents = $this->aliweb_model->setAssignRoster($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function getAssignRosterForm($params){$xmlcontents = $this->aliweb_model->getAssignRosterForm($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}











    function createClasses($year,$trim){



        $filename = $this->aliweb_model->createClasses($year,$trim);



        header("Content-type:text/xml;charset=utf-8");



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $item = xml_add_child($data,'entry',NULL,true);



        $cell0 = xml_add_child($item,'cell', "The classes were created successfully!",true);



        echo xml_print($dom,true);



    }



    function assignRoster($year,$trim){



        $filename = $this->aliweb_model->assignRoster($year,$trim);



        header("Content-type:text/xml;charset=utf-8");



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $item = xml_add_child($data,'entry',NULL,true);



        $cell0 = xml_add_child($item,'cell', "The roster were assigned successfully!",true);



        echo xml_print($dom,true);



    }


    function allattsheet($mode=''){



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));



        switch($mode):



            case 'getAllAttSheet': $this->getAllAttSheet($this->Param);  break;



            case 'getComSchoolYear': $this->getComSchoolYear($this->Param);  break;



            case 'getComTeachers': $this->getComTeachers($this->Param);  break;
            case 'getComLevel': $this->getComLevel($this->Param);  break;



            default:



                $this->Param['navi_seq'] = "classes";



                $this->Param['breadcrumb'] = array('Home' => $_SERVER["PHP_SELF"],'Classes' => null, 'All Attendance Sheet' => null);



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/allattsheet', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;



    }




    function attsheet($mode=''){



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));



        switch($mode):



            case 'getAttSheet': $this->getAttSheet($this->Param);  break;



            case 'getComSchoolYear': $this->getComSchoolYear($this->Param);  break;



            case 'getComTeachers': $this->getComTeachers($this->Param);  break;



            case 'getStudentList': $this->getStudentList($this->Param);  break;



            case 'getComLevel': $this->getComLevel($this->Param);  break;



            default:



                $this->Param['navi_seq'] = "classes";



                $this->Param['breadcrumb'] = array('Home' => $_SERVER["PHP_SELF"],'Classes' => null, 'Attendance Sheet' => null);



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/attsheet', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;



    }



    function getAttSheet($params){$xmlcontents = $this->aliweb_model->getAttSheet($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}

    function getAllAttSheet($params){$xmlcontents = $this->aliweb_model->getAllAttSheet($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}

    function getStudentList($params){$xmlcontents = $this->aliweb_model->getStudentList($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}







    /*** Classes > Class Inquiry Edit Function for Dhtmlx Start ****/



    function classinquiry($mode=''){



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));







        switch($mode):



            case 'getClasses': $this->getClasses($this->Param);  break;



            case 'getClass': $this->getClass($this->Param);  break;



            case 'setClass': $this->setClass($this->Param);  break;



            // case 'getPeriodLevels': $this->getPeriodLevels($this->Param);  break;



            // case 'getComLevels': $this->getComLevels($this->Param);  break;



            case 'getComTeachers': $this->getComTeachers($this->Param);  break;



            case 'getComRooms': $this->getComRooms($this->Param);  break;



            case 'getComTrimesters': $this->getComTrimesters($this->Param);  break;



            case 'getComSchoolYear': $this->getComSchoolYear($this->Param);  break;



            //case 'setLastClasses': $this->setLastClasses($this->Param);  break;



            //case 'checkDuplicateUserID': $this->checkDuplicateUserID($this->Param); break;



            case 'menucontext': $this->menucontext($this->Param);  break;



            default:



                $this->Param['navi_seq'] = "classes";



                $this->Param['breadcrumb'] = array('Home' => $this->aliwebRoot,'Year / Trimester' => null, 'Class Inquiry' => null);



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/classinquiry', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;



    }



    function getClasses($params){$xmlcontents = $this->aliweb_model->getClasses($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function getClass($params){$xmlcontents = $this->aliweb_model->getClass($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function setClass($params){$xmlcontents = $this->aliweb_model->setClass($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function getComTeachers($params){$xmlcontents = $this->aliweb_model->getComTeachers($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function getComRooms($params){$xmlcontents = $this->aliweb_model->getComRooms($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function getComTrimesters($params){$xmlcontents = $this->aliweb_model->getComTrimesters($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}











    /*** Classes > Class Inquiry Edit Function for Dhtmlx Start ****/



    function sendmessage($mode=''){



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));







        $this->Param['fromemail'] = $this->session->userdata('ALISESS_EMAIL');



        switch($mode):



            case 'getConStudentList': $this->getConStudentList($this->Param);  break;



            case 'getComMessageClasses': $this->getComMessageClasses($this->Param);  break;



            case 'setSendMessage': $this->setSendMessage($this->Param);  break;



            default:



                $this->Param['navi_seq'] = "messages";



                $this->Param['breadcrumb'] = array('Home' => $this->aliwebRoot,'Messages' => null, 'Send Message' => null);



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/sendmessage', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;



    }



    function getConStudentList($params){$xmlcontents = $this->aliweb_model->getConStudentList($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function getComMessageClasses($params){$xmlcontents = $this->aliweb_model->getComMessageClasses($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}







    function teacheremail($mode=''){



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));







        switch($mode):



            default:



                $this->Param['navi_seq'] = "messages";



                $this->Param['breadcrumb'] = array('Home' => $this->aliwebRoot,'Messages' => null, 'Teacher Email' => null);



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->hordecalendar();



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('aliweb/teacheremail', $layout_data, false);



        endswitch;



    }



    function hordecalendar(){



        $sess_id = $this->session->userdata('ALISESS_USERNO');



        list($email,$pw) = $this->aliweb_model->getSchoolAcct($sess_id);







        echo '<form novalidate id="login_form" action="https://secureus4.sgcpanel.com:2096/login/" name="login_form" method="post" target="dashboard_container">



     		<input name="user" id="user" value="'.$email.'" type="hidden">



          	<input name="pass" id="pass" value="'.$pw.'" type="hidden">



          	</form>



    	<script>



			window.onload= function(){



			document.login_form.submit();



			}



    	</script>';



    }







    /*** Classes > Gradebook Edit Function for Dhtmlx Start ****/



    function assigngrade($mode='')



    {



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));







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



                redirect(site_url('aliweb/classinquiry'));



            }



        }







        list($classname,$gpname) = $this->aliweb_model->classGP($this->Param['classno']);



        if ($this->session->userdata('SEL_GPNAME') == true){



            $this->session->set_userdata('SEL_GPNAME',$gpname);



        }else{



            $this->session->set_userdata(array(



                'SEL_GPNAME'	=> $gpname



            ));



        }







        switch($mode):



            case 'getGrades': $this->getGrades($this->Param);  break;



            case 'setGrades': $this->setGrades($this->Param);  break;



            default:



                $this->Param['param']['title'] = "GradeBook";



                $this->Param['param']['menus'] = "";



                foreach($this->CL_Submenu as $key=>$val){



                    $this->Param['param']['menus'].="<li><a href=\"".$this->CL_Submenu[$key]."\">".$key."</a></li>";



                }







                $this->Param['navi_seq'] = "classes";



                $this->Param['breadcrumb'] =$this->getClassBread('Grade Book');



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/assigngrade', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;



    }



    function getGrades($params){$xmlcontents = $this->aliweb_model->getGrades($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function setGrades($params){$xmlcontents = $this->aliweb_model->setGrades($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function getClassBread($cur){



        return array('Home' =>$this->aliwebRoot,$this->session->userdata('SEL_GPNAME') => $this->aliwebRoot.'classinquiry/',$this->session->userdata('SEL_CLASSNAME')=>$this->aliwebRoot.'assigngrade/', $cur => null);



    }







    /*** Assignments Function for Dhtmlx Start ****/



    function assignments($mode='')



    {



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));







        $this->Param['param']['classno']=$this->session->userdata('SEL_CLASSNO');



        $this->Param['param']['classname']=$this->session->userdata('SEL_CLASSNAME');



        $this->Param['classno'] = $this->session->userdata('SEL_CLASSNO');







        switch($mode):



            case 'getAssignments': $this->getAssignments($this->Param);  break;



            case 'setAssignments': $this->setAssignments($this->Param);  break;



            default:



                $this->Param['param']['title'] = "Assignments";



                $this->Param['param']['menus'] = "";



                foreach($this->CL_Submenu as $key=>$val){



                    $this->Param['param']['menus'].="<li><a href=\"".$this->CL_Submenu[$key]."\">".$key."</a></li>";



                }







                $this->Param['navi_seq'] = "classes";



                $this->Param['breadcrumb'] = $this->getClassBread('Assignments');



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/assignments', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;







    }



    function getAssignments($params){$xmlcontents = $this->aliweb_model->getAssignments($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function setAssignments($params){$xmlcontents = $this->aliweb_model->setAssignments($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}











    /*** Classes > Assignment New  Function for Dhtmlx Start ****/



    function assignnew($mode='')



    {



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));







        $this->Param['param']['classno']=$this->session->userdata('SEL_CLASSNO');



        $this->Param['param']['classname']=$this->session->userdata('SEL_CLASSNAME');



        $this->Param['classno'] = $this->session->userdata('SEL_CLASSNO');







        switch($mode):



            case 'getComCate': $this->getComCate($this->Param);  break;



            case 'getComBook': $this->getComBook($this->Param);  break;



            case 'setAssignview': $this->setAssignview($this->Param);  break;



            case 'getComBookFile': $this->getComBookFile($this->Param);  break;



            //case 'getAssignmentBasicPop': $this->getAssignmentBasicPop($this->Param);  break;



            default:



                $this->Param['param']['title'] = "New Assignment";



                $this->Param['param']['menus'] = "";



                foreach($this->CL_Submenu as $key=>$val){



                    $this->Param['param']['menus'].="<li><a href=\"".$this->CL_Submenu[$key]."\">".$key."</a></li>";



                }







                $this->Param['navi_seq'] = "classes";



                $this->Param['breadcrumb'] = $this->getClassBread('New Assignment');



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/assignnew', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;







    }



    function getComCate($params){$xmlcontents = $this->aliweb_model->getComCate($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}











    /*** Classes > Assignment Edit Function for Dhtmlx Start ****/



    function assignview($mode='')



    {



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));







        $this->Param['param']['classno']=$this->session->userdata('SEL_CLASSNO');



        $this->Param['param']['classname']=$this->session->userdata('SEL_CLASSNAME');



        $this->Param['classno'] = $this->session->userdata('SEL_CLASSNO');







        switch($mode):



            case 'getAssignview': $this->getAssignview($this->Param);  break;



            case 'getComCate': $this->getComCate($this->Param);  break;



            case 'setAssignview': $this->setAssignview($this->Param);  break;



            case 'getFiles': $this->getFiles($this->Param,1,"assignview");  break;



            case 'delFile': $this->delFile($this->Param["fno"],"assignview");  break;



            case 'downfile':  $this->downfile($this->Param["filename"],"assignview");  break;



            default:



                $this->Param['param']['title'] = "Edit Assignment";



                $this->Param['param']['menus'] = "";



                foreach($this->CL_Submenu as $key=>$val){



                    $this->Param['param']['menus'].="<li><a href=\"".$this->CL_Submenu[$key]."\">".$key."</a></li>";



                }



                $this->Param["contx"] = $this->getFiles($this->Param,1,"assignview"); //for assignment



                $this->Param["scores"] = $this->getStudentScores($this->Param["id"],$this->Param["classno"]);







                $this->Param['navi_seq'] = "classes";



                $this->Param['breadcrumb'] = $this->getClassBread('Edit Assignment');



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/assignview', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;



    }



    function getAssignview($params){$xmlcontents = $this->aliweb_model->getAssignview($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function getStudentScores($assigno,$classno){



        $res = $this->aliweb_model->getStudentScores($assigno,$classno);



        $scores="";



        foreach($res as $row5){



            $scores .= "<div><div style='float:left; width:160px;'>".$row5["lastname"]." ".$row5["firstname"]."</div > <input type='text' style='width:60px;' name='std[".$row5["students_no"]."][]' value='".$row5["score"]."'></div>";



        }



        return $scores;



    }



    function getFiles($params,$gbnum,$path){



        $res = $this->aliweb_model->getFiles($params["id"],$gbnum);



        $contx="";



        foreach($res as $row5){  $contx .= "<div id='upf".$row5["no"]."'> <a href='/index.php/aliweb/".$path."/downfile?filename=".$row5["filename"]."'>".$row5["filename"]."</a> <span style='cursor:hand;' onClick='delFiles(".$row5["no"].");'>x</span></div>";  }



        return $contx;



    }



    function delFile($fno,$path){



        $filename = $this->aliweb_model->delFile($fno,$path);



        header("Content-type:text/xml;charset=utf-8");



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $item = xml_add_child($data,'entry',NULL,true);



        $cell0 = xml_add_child($item,'cell', "The file(".$filename.") was deleted successfully!",true);



        echo xml_print($dom,true);



    }



    function setAssignview($params){



        $dirpath = $_SERVER['DOCUMENT_ROOT']."/userfiles/uploaded/assignview/";







        $params["gbnum"] = 1;







        switch($params["mode"]):



            case "insert":



                $params["id"] = $this->aliweb_model->add_row_assignview($params);



                $action="inserted";



                break;



            case "update":



                $updatedata = array(



                    'assigncat_no' => $params["assigncat_no"],



                    'points' => $params["points"],



                    'name' => $params["name"],



                    'duedate' => $params["duedate"],



                    'description' => $params["description"],



                    'isview' => (($params["visview"]=='true')?1:0),



                    'isdiscuss' => 0,



                    'writer' => $this->session->userdata('ALISESS_USERNAME')



                );



                $this->db->set('regdate', 'now()', FALSE);



                $this->db->where('no', $params["id"]);



                $this->db->update('ali_assignments', $updatedata);







                $updatedata2 = array(



                    'assigncat_no' => $params["assigncat_no"]



                );



                $this->db->where('class_no', $params["classno"]);



                $this->db->where('assign_no', $params["id"]);



                $this->db->update('ali_grade_new', $updatedata2);



                //update ali_grade_new set assigncat_no=584 where class_no=281 and assigncat_no=583 and assign_no=2540







                $action="updated";



                break;



            case "delete":



                $res = $this->aliweb_model->getFiles($params["id"],$params["gbnum"]);



                foreach($res as $row5){ @unlink($dirpath.$row5["filename"]); }



                $action = $this->aliweb_model->delete_row_file($params["id"],$params["gbnum"]);



                $this->aliweb_model->delete_row_scoreall($params["id"]); //ali_grade_new



                $this->aliweb_model->delete_row_assignview($params["id"]); //ali_assignments



                $action="deleted";



                break;



        endswitch;











        if(!empty($_FILES["myFiles"])):



            $params["newfilename"]= $this->files_upload($_FILES["myFiles"]["name"],$_FILES["myFiles"]["type"],$_FILES["myFiles"]["error"],$_FILES["myFiles"]["tmp_name"],$dirpath);



            if(!empty($params["newfilename"])):



                $action = $this->aliweb_model->add_row_file($params);







            endif;



            $params["newfilename"]="";



        endif;



        if(!empty($_FILES["myFiles2"])):



            $params["newfilename"]= $this->files_upload($_FILES["myFiles2"]["name"],$_FILES["myFiles2"]["type"],$_FILES["myFiles2"]["error"],$_FILES["myFiles2"]["tmp_name"],$dirpath);



            if(!empty($params["newfilename"])):



                $action = $this->aliweb_model->add_row_file($params);



            endif;



        endif;







        if(!empty($params['std'])):



            foreach($params['std'] as $studentno=>$tmpArray) {



                $params['student_no']=$studentno;



                foreach($tmpArray as $thescore) {



                    if(trim($thescore)!=""):



                        $params['score']=$thescore;



                        if($this->aliweb_model->getAssignStudent($params["id"],$studentno)){



                            $assignno = $this->aliweb_model->update_row_score($params);



                        }else{



                            $assignno = $this->aliweb_model->add_row_score($params);



                        }



                    endif;



                }



            }



        endif;







        print_r("<SCRIPT>parent.myCallBack('".$action."');</SCRIPT>");



    }







    /*** Class Students Function for Dhtmlx Start ****/



    function classstudents($mode='')



    {



        $this->Param["gbnum"]=4;  //for send message in popup



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));







        $this->Param['param']['classno']=$this->session->userdata('SEL_CLASSNO');



        $this->Param['param']['classname']=$this->session->userdata('SEL_CLASSNAME');



        $this->Param['classno'] = $this->session->userdata('SEL_CLASSNO');







        switch($mode):



            case 'getClassStudents': $this->getClassStudents($this->Param);  break;



            case 'getSchoolRoster': $this->getSchoolRoster($this->Param);  break;



            case 'getClassRoster': $this->getClassRoster($this->Param);  break;



            case 'setClassRoster': $this->setClassRoster($this->Param);  break;



            case 'setSendMessage': $this->setSendMessage($this->Param);  break;



            default:



                $this->Param['param']['title'] = "Students";



                $this->Param['param']['menus'] = "";



                foreach($this->CL_Submenu as $key=>$val){



                    $this->Param['param']['menus'].="<li><a href=\"".$this->CL_Submenu[$key]."\">".$key."</a></li>";



                }







                $this->Param['navi_seq'] = "classes";



                $this->Param['breadcrumb'] = $this->getClassBread('Students');



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/classstudents', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;







    }



    function getClassStudents($params){$xmlcontents = $this->aliweb_model->getClassStudents($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function getSchoolRoster($params){$xmlcontents = $this->aliweb_model->getSchoolRoster($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function getClassRoster($params){$xmlcontents = $this->aliweb_model->getClassRoster($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function setClassRoster($params){ $xmlcontents = $this->aliweb_model->setClassRoster($params);	header("Content-type:text/xml;charset=utf-8");	echo $xmlcontents; }







    function setSendMessage($params)



    {



        $dirpath = $_SERVER['DOCUMENT_ROOT'] . "/userfiles/uploaded/message/";







        $params["newfilename"] = "";



        if (!empty($_FILES["uploadfile"])):



            $params["newfilename"] = $this->files_upload($_FILES["uploadfile"]["name"], $_FILES["uploadfile"]["type"], $_FILES["uploadfile"]["error"], $_FILES["uploadfile"]["tmp_name"], $dirpath);



        endif;







        if (!empty($params["receiveremail"])) {



            $ss_email = $this->session->userdata('ALISESS_EMAIL');



            $ss_username = $this->session->userdata('ALISESS_USERNAME');



            $this->email->from("smtp@schooldname.com", $ss_username);



            $this->email->to("records@schooldname.com");



            $this->email->bcc($params["receiveremail"]);



            $this->email->reply_to($ss_email,$ss_username);



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







    function uploadgrades($mode=''){



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));







        switch($mode):



            case 'getTrimesters': $this->getTrimesters($this->Param);  break;



            case 'setTrimester': $this->setTrimester($this->Param); break;



            case 'getTrimestersCombo': $this->getTrimestersCombo($this->Param); break;



            case 'menucontextUploadGrade': $this->menucontextUploadGrade($this->Param);  break;



            case 'getImportFiles': $this->getImportFiles($this->Param);  break;



            case 'setImportFile': $this->setImportFile($this->Param);  break;



            case 'setImportFiles': $this->setImportFiles($this->Param);  break;



            case 'setImportTemps': $this->setImportTemps($this->Param);  break;



            case 'getEGGrades': $this->getEGGrades($this->Param);  break;



            default:



                $this->Param['navi_seq'] = "setting";



                $this->Param['breadcrumb'] = array('Home' => $_SERVER["PHP_SELF"],'Classes' => $_SERVER["PHP_SELF"],'Grades' => null, 'Import Engrade Grades' => null);



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/uploadgrades', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;



    }



    function menucontextUploadGrade($params){$xmlcontents = $this->aliweb_model->menucontextUploadGrade($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}



    function getTrimestersCombo($params){$xmlcontents = $this->aliweb_model->getTrimestersCombo($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}



    function getEGGrades($params){$xmlcontents = $this->aliweb_model->getEGGrades($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}



    function getImportFiles($params){$xmlcontents = $this->aliweb_model->getImportFiles($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}



    function setImportFiles($params){$xmlcontents = $this->aliweb_model->setImportFiles($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}



    function setImportFile($params){



        $theFileName="";



        $xmlcontents ="";



        $dirpath = $_SERVER['DOCUMENT_ROOT']."/userfiles/uploaded/";







        if($_FILES["uploadfile"]) {



            $params["newfilename"] = $this->files_upload($_FILES["uploadfile"]["name"], $_FILES["uploadfile"]["type"], $_FILES["uploadfile"]["error"], $_FILES["uploadfile"]["tmp_name"], $dirpath);



            if (trim($params["newfilename"]) != "") {



                $xmlcontents = $this->aliweb_model->setImportFile($params);



            }



            $params["newfilename"] = "";



        }







        print_r("<SCRIPT>parent.showfullmsg(\"msgResult\",\"It has been ".$xmlcontents." successfully.\"); parent.myCallBack();</SCRIPT>");



    }



    function setImportTemps($params){



        $meag = 0;



        if(trim($params["fno"])!=""){







            $filenam = $this->aliweb_model->getImportfileInfo($params["fno"]);







            if(trim($filenam)!=""){







                $delres = $this->aliweb_model->delete_row_EGGrades($params["gno"]);



                if($delres=="deleted") {







                    $this->load->library('excel');



                    $inputFileType = 'CSV';



                    $inputFileName = $_SERVER['DOCUMENT_ROOT']."/userfiles/uploaded/".$filenam;



                    $objReader = PHPExcel_IOFactory::createReader($inputFileType);



                    $objPHPExcel = $objReader->load($inputFileName);



                    $worksheet = $objPHPExcel->getActiveSheet();



                    foreach ($worksheet->getRowIterator() as $row) {







                        if($row->getRowIndex() > 1){



                            $datas = array();



                            $cellIterator = $row->getCellIterator();



                            $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set



                            foreach ($cellIterator as $cell) {



                                $column = PHPExcel_Cell::columnIndexFromString($cell->getColumn());



                                $newData = $cell->getValue();



                                if($column==10){



                                    $newData = $cell->getValue(); $newData = str_replace("%","",$newData);



                                }



                                $datas[$column] = $newData;



                            }







                            if(trim($datas[6])!=''&&trim($datas[7])!=''&&trim($datas[8])!=''){



                                $params["file_no"] = $params["fno"];



                                $params["trimester_no"] = $params["gno"];



                                $params["engradeclassid"] = $datas[1];



                                $params["classschoolyear"] = $datas[2];



                                $params["classgradingperiod"] = $datas[3];



                                $params["classname"] = $datas[4];



                                $params["teachername"] = $datas[5];



                                $params["studentfirst"] = $datas[6];



                                $params["studentlast"] = $datas[7];



                                $params["studentid"] = $datas[8];



                                $params["grade"] = $datas[9];



                                $params["percent"] = $datas[10];



                                $params["missing"] = $datas[11];



                                $params["teachercomment"] = $datas[12];



                                $this->aliweb_model->insert_row_EGGrades($params);



                            }







                        }



                    }



                    $res = $this->aliweb_model->update_ImportFile($params["fno"]);



                    $meag = 1;//"Imported Temp Data!";







                }//end deleted







            }else{



                $meag = -5;//"Error by File Name!";



            }







        }else{



            $meag = -4;//"Error by File No!";



        }







        $this->load->helper('xml');



        header("Content-type:text/xml;charset=euc-kr");



        $dom = xml_dom();



        $items = xml_add_child($dom, 'items');



        $item1 = xml_add_child($items,'item',null,false);



        xml_add_attribute($item1,'value',$meag);



        echo xml_print($dom,true);



    }







    /*** Class Teachers Function for Dhtmlx Start ****/



    function classteachers($mode='')



    {



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));







        $this->Param['param']['classno']=$this->session->userdata('SEL_CLASSNO');



        $this->Param['param']['classname']=$this->session->userdata('SEL_CLASSNAME');



        $this->Param['classno'] = $this->session->userdata('SEL_CLASSNO');



        //list ($classname,$gpname) = $this->aliweb_model->classGP($this->Param['classno']);







        switch($mode):



            case 'getClassTeachers': $this->getClassTeachers($this->Param);  break;



            case 'getClassTeacher': $this->getClassTeacher($this->Param);  break;



            case 'getComClassTeachers': $this->getComClassTeachers($this->Param);  break;



            case 'setClassTeacher': $this->setClassTeacher($this->Param);  break;



            default:



                $this->Param['param']['title'] = "Class Teachers";



                $this->Param['param']['menus'] = "";



                foreach($this->CL_Submenu as $key=>$val){



                    $this->Param['param']['menus'].="<li><a href=\"".$this->CL_Submenu[$key]."\">".$key."</a></li>";



                }







                $this->Param['navi_seq'] = "classes";



                $this->Param['breadcrumb'] = $this->getClassBread('Class Teachers');



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/classteachers', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;







    }



    function getClassTeachers($params){$xmlcontents = $this->aliweb_model->getClassTeachers($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function getClassTeacher($params){$xmlcontents = $this->aliweb_model->getClassTeacher($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function setClassTeacher($params){$xmlcontents = $this->aliweb_model->setClassTeacher($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function getComClassTeachers($params){$xmlcontents = $this->aliweb_model->getComClassTeachers($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}







    /*** Assignment Categories Function for Dhtmlx Start ****/



    function assigncate($mode='')



    {



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));







        $this->Param['param']['classno']=$this->session->userdata('SEL_CLASSNO');



        $this->Param['param']['classname']=$this->session->userdata('SEL_CLASSNAME');



        $this->Param['classno'] = $this->session->userdata('SEL_CLASSNO');



        //$this->Param['param']['gpno'] = $this->aliweb_model->classGP($this->Param['classno']);



        switch($mode):



            case 'getAssignCategories': $this->getAssignCategories($this->Param);  break;



            case 'getAssignCategory': $this->getAssignCategory($this->Param);  break;



            case 'setAssignCategory': $this->setAssignCategory($this->Param);  break;



            case 'setDefaultCategories': $this->setDefaultCategories($this->Param);  break;



            default:



                $this->Param['param']['title'] = "Assignment Categories";



                $this->Param['param']['menus'] = "";



                foreach($this->CL_Submenu as $key=>$val){



                    $this->Param['param']['menus'].="<li><a href=\"".$this->CL_Submenu[$key]."\">".$key."</a></li>";



                }







                $this->Param['navi_seq'] = "classes";



                $this->Param['breadcrumb'] = $this->getClassBread('Assignment Categories');



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/assigncate', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;







    }



    function getAssignCategories($params){$xmlcontents = $this->aliweb_model->getAssignCategories($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function getAssignCategory($params){$xmlcontents = $this->aliweb_model->getAssignCategory($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function setAssignCategory($params){$xmlcontents = $this->aliweb_model->setAssignCategory($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function setDefaultCategories($params){$xmlcontents = $this->aliweb_model->setDefaultCategories($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}







    /*** Each Student Gradebook Edit Function for Dhtmlx Start ****/



    function studentgrade($mode='')



    {



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));







        $this->Param['param']['classno']=$this->session->userdata('SEL_CLASSNO');



        $this->Param['param']['classname']=$this->session->userdata('SEL_CLASSNAME');



        $this->Param['param']['stno'] = $this->Param['stno'];



        $this->Param['classno'] = $this->session->userdata('SEL_CLASSNO');



        switch($mode):



            case 'getStudentGrades': $this->getStudentGrades($this->Param);  break;



            case 'printgrade': $this->printgrade($this->Param);  break;



            //case 'createpdf': $this->createpdf($this->Param);  break;



            default:



                $this->Param['param']['title'] = "Student Grade";



                $this->Param['param']['menus'] = "";



                foreach($this->CL_Submenu as $key=>$val){



                    $this->Param['param']['menus'].="<li><a href=\"".$this->CL_Submenu[$key]."\">".$key."</a></li>";



                }







                $this->Param['navi_seq'] = "classes";



                $this->Param['breadcrumb'] = $this->getClassBread('Student Grade');



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/studentgrade', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;







    }



    function getStudentGrades($params){$xmlcontents = $this->aliweb_model->getStudentGrades($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function printgrade($params){
        list ($params["classname"],$params["gpname"]) = $this->aliweb_model->classGP($params['classno']);
        $studentfullname="";
        $sql3 = "select CONCAT(firstname,' ',lastname) AS fullname from ali_students where students_no=".$params['stno'];
        $query3 = $this->db->query($sql3);
        if($query3->num_rows() > 0){
            $row3=$query3->row();
            $studentfullname = $row3->fullname;
        }
        $totalCategoryRate=0;
        $grade=0;
        $arrcate = array();
        $sql8 = "select ac.no, ac.wpercentage from ali_assign_cate as ac inner join ali_assignments as sa on sa.assigncat_no=ac.no where sa.isview=0 and sa.class_no=".$params['classno']." group by ac.no, ac.wpercentage";
        $query8 = $this->db->query($sql8);
        foreach($query8->result_array() as $row8) {
            $arrcate["".$row8['no'].""]["percentage"] = $row8['wpercentage'];
            $totalCategoryRate = $totalCategoryRate + intval($row8['wpercentage']);
        }
        $arravg = array();
        $arreach = array();
        $sql9 = "select ac.assigncat_no,AVG(ac.score) as scoreavg  from ali_grade_new as ac inner join ali_assignments as sa on sa.no=ac.assign_no where  sa.isview=0 and ac.class_no=".$params['classno']." and ac.student_no=".$params['stno']."  group by ac.assigncat_no";
        $query9 = $this->db->query($sql9);
        foreach($query9->result_array() as $row9)
        {
            $arreach["".$row9['assigncat_no'].""]["eachcat"] = (((100*$arrcate["".$row9['assigncat_no'].""]["percentage"])/$totalCategoryRate)/100);
            $arrcate["".$row9['assigncat_no'].""]["categoryscore"] = ($row9["scoreavg"] * $arreach["".$row9['assigncat_no'].""]["eachcat"] );
            $arravg["".$row9['assigncat_no'].""]["gradeavg"] = $row9["scoreavg"];
            $grade = $grade + number_format($arrcate["".$row9['assigncat_no'].""]["categoryscore"],1);
        }
        //Grading Scale
        $gl = "";
        $fg = round($grade);
        if ($fg>=0 && $fg<60 ) {
            $gl="F";
        } elseif ($fg>=60 && $fg<70 ) {
            $gl="D";
        } elseif ($fg>=70 && $fg<80 ) {
            $gl="C";
        } elseif ($fg>=80 && $fg<90 ) {
            $gl="B";
        } elseif ($fg>=90 && $fg<=100 ) {
            $gl="A";
        } else {
            $gl="error";
        }
        $subtitle = "";
        $sql0 = "SELECT no, name, wpercentage FROM ali_assign_cate where class_no=".$params['classno'];
        $query0 = $this->db->query($sql0);
        foreach($query0->result_array() as $row0)
        {
            $data = array();
            $sql1 = "select sa.no, sa.name,sg.score,sa.points,sa.description,sa.duedate from ali_assignments as sa left join (select assign_no,score from ali_grade_new where student_no=".$params['stno'].") as sg on sa.no=sg.assign_no where sa.class_no=".$params['classno']." and sa.isview=0 and sa.assigncat_no=".$row0['no']." order by sa.duedate";
            $query1 = $this->db->query($sql1);
            $j=0;
            foreach($query1->result_array() as $row1)
            {
                $data[$j]["assignno"] = $row1["no"];
                $data[$j]["assignname"] = $row1["name"];
                $data[$j]["duedate"] = $row1["duedate"];
                $data[$j]["score"] = $row1["score"];
                $data[$j]["points"] = $row1["points"];
                $j++;
            }
            $categrade = "";
            if(ISSET($arravg["".$row0['no'].""]["gradeavg"])) {
                $categrade = round($arravg["" . $row0['no'] . ""]["gradeavg"]*$arreach["" . $row0['no'] . ""]["eachcat"],1)."%";
            }
            $subtitle .= "<tr><td colspan=\"4\">".$row0['name']." : ".$categrade." (counts as ".$row0['wpercentage']."% of grade)</td></tr>";
            $subtitle .= "<tr><td>ASSIGNMENT</td><td>DUE</td><td>SCORE</td><td>POSSIBLE</td></tr>";
            for($s=0; $s < count($data); $s++){
                $subtitle .= "<tr><td>".html_entity_decode($data[$s]['assignname'])."</td><td>".$data[$s]['duedate']."</td><td>".$data[$s]['score']."</td><td>".$data[$s]['points']."</td></tr>";
            }
        }
        $params["tablebody"] = $subtitle;
        $params["tableheader"] = "<tr><th>".$studentfullname."</th><th colspan=\"3\">".$gl." (".$fg."%)</th></tr>";
        $this->load->view('aliweb/printgrade', $params, false);
    }


    /*** Attendance Weeks Function for Dhtmlx Start ****/
    function attweeks($mode='')
    {



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));







        $this->Param['param']['classno']=$this->session->userdata('SEL_CLASSNO');



        $this->Param['param']['classname']=$this->session->userdata('SEL_CLASSNAME');



        $this->Param['classno'] = $this->session->userdata('SEL_CLASSNO');



        switch($mode):



            case 'getAttendance': $this->getAttendance($this->Param);  break;



            case 'setAttendance': $this->setAttendance($this->Param);  break;



            //case 'getComClasses': $this->getComClasses($this->Param);  break;



            default:



                $this->Param['param']['title'] = "Attendance";



                $this->Param['param']['menus'] = "";



                foreach($this->CL_Submenu as $key=>$val){



                    $this->Param['param']['menus'].="<li><a href=\"".$this->CL_Submenu[$key]."\">".$key."</a></li>";



                }







                $this->Param['navi_seq'] = "classes";



                $this->Param['breadcrumb'] = $this->getClassBread('Attendance');



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/attweeks', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;



    }



    function getAttendance($params){$xmlcontents = $this->aliweb_model->getAttendance($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function setAttendance($params){$xmlcontents = $this->aliweb_model->setAttendance($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function attmonths($mode='')



    {



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));







        $this->Param['param']['classno']=$this->session->userdata('SEL_CLASSNO');



        $this->Param['param']['classname']=$this->session->userdata('SEL_CLASSNAME');



        $this->Param['classno'] = $this->session->userdata('SEL_CLASSNO');



        switch($mode):



            default:



                $sump=0;$sumt=0;$suma=0; $arr=array(); $stname="";



                list($arr,$sump,$sumt,$suma,$stname) = $this->aliweb_model->getAttMonths($this->Param);



                $this->Param['param']['title'] = "Attendance";



                $this->Param['param']['menus'] = "";



                $this->Param['sump'] = $sump;



                $this->Param['sumt'] = $sumt;



                $this->Param['suma'] = $suma;



                $this->Param['stname'] = $stname;



                $this->Param['arr'] = $arr;



                list ($this->Param["classname"],$this->Param["gpname"]) = $this->aliweb_model->classGP($this->Param['classno']);



                foreach($this->CL_Submenu as $key=>$val){



                    $this->Param['param']['menus'].="<li><a href=\"".$this->CL_Submenu[$key]."\">".$key."</a></li>";



                }







                $this->Param['navi_seq'] = "classes";



                $this->Param['breadcrumb'] = $this->getClassBread('Attendance');



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/attmonths', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;



    }







    public function addtributton() {



        $data = $this->aliweb_model->getTri($this->input->post("stdno"));



        echo json_encode($data);



    }







    public function addclassbutton() {



        $data = $this->aliweb_model->getCla($this->input->post("stdno"),$this->input->post("year"),$this->input->post("tri"));



        echo json_encode($data);



    }



    public function addFinalization(){

        $data =0;



        if($this->input->post("gubun") == 8){
            //add record through the 'add finalization' button

            $data = $this->aliweb_model->setFinalization($this->input->post("stdno"));

        }

        if($this->input->post("gubun") == 1){
            //add record through the right click
            $data = $this->aliweb_model->setEachFinalization($this->input->post("stdno"),$this->input->post("year"),$this->input->post("trim"),$this->input->post("level"),$this->input->post("session"));

        }

        echo json_encode($data);

    }

    //addRATThtml for the remediation attendance



    public function addRATThtml(){



        $params["stno"] = $this->input->post("stdno");



        $params["classno"] = $this->aliweb_model->getRCla($this->input->post("stdno"),$this->input->post("year"),$this->input->post("trim"));



        // $params["classtype"] = "att".$this->input->post("classtype");



        $sump=0;$sumt=0;$suma=0; $arr=array(); $stname=""; $attrate = "";



        list($arr,$sump,$sumt,$suma,$stname,$attrate) = $this->aliweb_model->getRAttMonths($params);



        list ($classname,$gpname) = $this->aliweb_model->rclassGP($params["classno"]);







        $html = '<table class="formtable" cellspacing="0"><tr><td colspan="2" style="color:#4169E1; font-size:12px;font-weight:bold;">'.$gpname.'/'.$classname.'</td></tr><tr><td colspan="2"><b>'.$stname.'</b></td></tr><tr><td style="padding:6px;line-height:130%" colspan="2">Totals ('.$attrate.') : <b style="color:#">P</b> = '.$sump.'&nbsp;<span style="color:rgb(128,128,128);">|</span>&nbsp;<b style="color:#">A</b> =  '.$suma.' </td></tr></table>';







        $yyy = array();



        foreach ($arr as $k => $v) {



            $mmm = array();



            $yyy = $arr[$k];



            foreach ($yyy as $k2 => $v2) {



                $mmm = $yyy[$k2];



                $mon = date("M Y",strtotime($k."-".$k2."-01"));



                $html .= "<table class='formtable month' cellspacing='0'>



                <tr><td class='tableinfo' colspan='7'>".$mon."</td></tr><tr>



                                <td  class='tablecol'>Mon</td>



                                <td  class='tablecol'>Tue</td>



                                <td  class='tablecol'>Wed</td>



                                <td  class='tablecol'>Thu</td>



                                <td  class='tablecol'>Fri</td>



                                <td  class='tablecol'>Sat</td>



                                <td  class='tablecol'>Sun</td>



                    </tr><tr>";



                $days = cal_days_in_month(CAL_GREGORIAN,$k2,$k);



                $s=0;



                for($m=1; $m <= $days; $m++){



                    $d = date("d",strtotime($k."-".$k2."-".$m) );



                    $w = date("w",strtotime($k."-".$k2."-".$d) );



                    if($m <= 7 && $s==0){



                        if($w==0){



                            $html .=  "<td colspan='6'></td>";



                        }elseif($w==1){



                            $html .=  "";



                        }elseif($w==2){



                            $html .=  "<td></td>";



                        }else{



                            $html .=  "<td colspan='".($w-1)."'></td>";



                        }



                        $s=1;



                    }//end if







                    $nam="";



                    if(isset($mmm["".$d.""])){



                        $nam = $mmm[$d];



                    }







                    $html .=  "<td><i>".$d."</i>&nbsp;<b>".$nam."</b></td>";



                    if($w==0){ //sunday



                        $html .=  "</tr><tr>";



                    }//end if







                }// end for







                $html .=  "</tr></table>";



            }



        }







        $html .= '</div>';







        echo json_encode(array('html' => $html ));



    }







    //addATThtml for the addendance (LS,RW)



    public function addATThtml(){







        $params["stno"] = $this->input->post("stdno");



        $params["classno"] = $this->input->post("classno");



        $params["classtype"] = "att".$this->input->post("classtype");







        $sump=0;$sumt=0;$suma=0; $arr=array(); $stname="";



        list($arr,$sump,$sumt,$suma,$stname) = $this->aliweb_model->getAttMonths($params);



        list ($classname,$gpname) = $this->aliweb_model->classGP($params["classno"]);







        $html = '<table class="formtable" cellspacing="0"><tr><td colspan="2" style="color:#4169E1; font-size:12px;font-weight:bold;">'.$gpname.' / '.$classname.' </td></tr><tr><td colspan="2"><b>'.$stname.'</b></td></tr><tr><td style="padding:6px;line-height:130%" colspan="2">Totals: <b style="color:#">P</b> = '.$sump.'&nbsp;<span style="color:rgb(128,128,128);">|</span>&nbsp;<b style="color:#">T</b> = '.$sumt.' &nbsp;<span style="color:rgb(128,128,128);">|</span>&nbsp;<b style="color:#">A</b> =  '.$suma.'	</td></tr></table>';







        $yyy = array();



        foreach ($arr as $k => $v) {



            $mmm = array();



            $yyy = $arr[$k];



            foreach ($yyy as $k2 => $v2) {



                $mmm = $yyy[$k2];



                $mon = date("M Y",strtotime($k."-".$k2."-01"));



                $html .= "<table class='formtable month' cellspacing='0'>



                <tr><td class='tableinfo' colspan='7'>".$mon."</td></tr><tr>



                                <td  class='tablecol'>Mon</td>



                                <td  class='tablecol'>Tue</td>



                                <td  class='tablecol'>Wed</td>



                                <td  class='tablecol'>Thu</td>



                                <td  class='tablecol'>Fri</td>



                                <td  class='tablecol'>Sat</td>



                                <td  class='tablecol'>Sun</td>



                    </tr><tr>";



                $days = cal_days_in_month(CAL_GREGORIAN,$k2,$k);



                $s=0;



                for($m=1; $m <= $days; $m++){



                    $d = date("d",strtotime($k."-".$k2."-".$m) );



                    $w = date("w",strtotime($k."-".$k2."-".$d) );



                    if($m <= 7 && $s==0){



                        if($w==0){



                            $html .=  "<td colspan='6'></td>";



                        }elseif($w==1){



                            $html .=  "";



                        }elseif($w==2){



                            $html .=  "<td></td>";



                        }else{



                            $html .=  "<td colspan='".($w-1)."'></td>";



                        }



                        $s=1;



                    }//end if







                    $nam="";



                    if(isset($mmm["".$d.""])){



                        $nam = $mmm[$d];



                    }







                    $html .=  "<td><i>".$d."</i>&nbsp;<b>".$nam."</b></td>";



                    if($w==0){ //sunday



                        $html .=  "</tr><tr>";



                    }//end if







                }// end for







                $html .=  "</tr></table>";



            }



        }







        $html .= '</div>';







        echo json_encode(array('html' => $html ));



    }











    public function createTranscript($params){



        $this->load->library('pdf');



        $this->pdf->fontpath = $_SERVER['DOCUMENT_ROOT']."/application/libraries/font/";



        $this->pdf->AddPage();







        $result = $this->aliweb_model->getStudent($params["sno"]);







        $pagecount = $this->pdf->setSourceFile($_SERVER['DOCUMENT_ROOT']."/application/libraries/document/ALITranscript.pdf");



        $tpl = $this->pdf->importPage(1);



        $this->pdf->useTemplate($tpl, 0, 0);







        $this->pdf->SetFont('Arial','',11);



        $this->pdf->SetY(59);



        $this->pdf->Cell(42);



        $this->pdf->Cell(10,7,$result["firstname"]);







        $this->pdf->SetFont('Arial','',11);



        $this->pdf->Cell(28);



        $this->pdf->Cell(10,7,$result["lastname"]);







        $this->pdf->SetFont('Arial','',11);



        $this->pdf->Cell(32);



        $this->pdf->Cell(10,7,$result["student_ID"]);



        $this->pdf->Ln();







        $this->pdf->SetY(114);



        $res = $this->aliweb_model->getTranscriptList($params["sno"]);



        $data = array();



        $arrsession = array("","AM","AFT","PM","None");







        $sumgrade = 0;



        $seqgrade = 0;



        $sumatt = 0;



        $seqatt = 0;



        $tempyear = "";



        $extragrade = 0;



        $extraatt = 0;

        $totalsumgrade= 0;

        //$average = array_sum($array) / count($array);
        $gradecount= 0;




        if(count($res)>0) {



            foreach ($res as $row5) {







                /*echo "DATA SECTION: ";



                echo print_r($data)."<br>";



                */







                if ($tempyear != "" && $tempyear != $row5["schoolyear"]) {



                    $data["" . $tempyear . ""]["avg_grade"] = $sumgrade/($seqgrade-$extragrade);



                    $data["" . $tempyear . ""]["avg_att"] = $sumatt/($seqatt-$extraatt);



                    $sumgrade = 0;



                    $seqgrade = 0;



                    $sumatt = 0;



                    $seqatt = 0;



                    $extragrade = 0;



                    $extraatt = 0;



                }



                $tempyear = $row5["schoolyear"];







                /*



                echo "avg grade : ";



                echo print_r($data["" . $tempyear . ""]["avg_grade"])."<br>";



                echo "avg att : ";



                echo print_r($data["" . $tempyear . ""]["avg_att"]."<br>");



                */







                //vacation class



                if ($row5["level"] == 5) {



                    $data["" . $row5["schoolyear"] . ""]["tri"]["" . $row5["trimester"] . ""]["class"][$seqgrade]["name"] = "Vacation";



                    $data["" . $row5["schoolyear"] . ""]["tri"]["" . $row5["trimester"] . ""]["class"][$seqgrade]["grade"] = "";



                    $data["" . $row5["schoolyear"] . ""]["tri"]["" . $row5["trimester"] . ""]["class"][$seqgrade]["score"] = "";



                    $data["" . $row5["schoolyear"] . ""]["tri"]["" . $row5["trimester"] . ""]["att"] = "";



                    $data["" . $row5["schoolyear"] . ""]["tri"]["" . $row5["trimester"] . ""]["level"] = $row5["level"];



                    $data["" . $row5["schoolyear"] . ""]["avg_grade"] = "";



                    $data["" . $row5["schoolyear"] . ""]["avg_att"] = "";



                    $sumgrade = $sumgrade + 0;



                    $sumatt = $sumatt + 0;



                    $seqgrade++;



                    $seqatt++;



                    $extragrade = 1;



                    $extraatt = 1;



                }







                //regular class



                if ($row5["level"] != 7 && $row5["level"] != 5) {



                    //echo $row5["level"];

                    // the section to change level value to name



                    $row5["level"] = $this->aliweb_model->changeLevel($row5["level"]);

                    //echo $row5["level"];



                    /*



                    if($row5["level"]==10){ //change class name



                        $row5["level"] = '3A';



                    }

                    */



                    $data["" . $row5["schoolyear"] . ""]["tri"]["" . $row5["trimester"] . ""]["class"][$seqgrade]["name"] = "Level " . $row5["level"] . " " . $arrsession[$row5["session"]] . " LS";



                    $fg1 = $row5["ls_score"];
                    $gradecount++;



                    if ($fg1 >= 0 && $fg1 < 60) {



                        $gl1 = "F";



                    } elseif ($fg1 >= 60 && $fg1 < 70) {



                        $gl1 = "D";



                    } elseif ($fg1 >= 70 && $fg1 < 80) {



                        $gl1 = "C";



                    } elseif ($fg1 >= 80 && $fg1 < 90) {



                        $gl1 = "B";



                    } elseif ($fg1 >= 90 && $fg1 <= 100) {



                        $gl1 = "A";



                    } else {



                        $gl1 = "";



                    }



                    $data["" . $row5["schoolyear"] . ""]["tri"]["" . $row5["trimester"] . ""]["class"][$seqgrade]["grade"] = $gl1;



                    $data["" . $row5["schoolyear"] . ""]["tri"]["" . $row5["trimester"] . ""]["class"][$seqgrade]["score"] = $fg1;



                    $sumgrade = $sumgrade + $row5["ls_score"];



                    $seqgrade++;







                    $data["" . $row5["schoolyear"] . ""]["tri"]["" . $row5["trimester"] . ""]["class"][$seqgrade]["name"] = "Level " . $row5["level"] . " " . $arrsession[$row5["session"]] . " RW";



                    $fg1 = $row5["rw_score"];
                    $gradecount++;



                    if ($fg1 >= 0 && $fg1 < 60) {



                        $gl1 = "F";



                    } elseif ($fg1 >= 60 && $fg1 < 70) {



                        $gl1 = "D";



                    } elseif ($fg1 >= 70 && $fg1 < 80) {



                        $gl1 = "C";



                    } elseif ($fg1 >= 80 && $fg1 < 90) {



                        $gl1 = "B";



                    } elseif ($fg1 >= 90 && $fg1 <= 100) {



                        $gl1 = "A";



                    } else {



                        $gl1 = "";



                    }



                    $data["" . $row5["schoolyear"] . ""]["tri"]["" . $row5["trimester"] . ""]["class"][$seqgrade]["grade"] = $gl1;



                    $data["" . $row5["schoolyear"] . ""]["tri"]["" . $row5["trimester"] . ""]["class"][$seqgrade]["score"] = $fg1;



                    $sumgrade = $sumgrade + $row5["rw_score"];



                    $seqgrade++;







                    $data["" . $row5["schoolyear"] . ""]["tri"]["" . $row5["trimester"] . ""]["level"] = $row5["level"];



                    $data["" . $row5["schoolyear"] . ""]["tri"]["" . $row5["trimester"] . ""]["att"] = $row5["att_score"];



                    $sumatt = $sumatt + $row5["att_score"];



                    $seqatt++;



                }



                if($row5["level"]==5) {



                    $data["" . $tempyear . ""]["avg_grade"] ="";



                    $data["" . $tempyear . ""]["avg_att"] = "";



                }



                if($row5["level"]!=7&&$row5["level"]!=5) {



                    //echo $sumgrade."<br>".$seqgrade."<br>".$extragrade;



                    $data["" . $tempyear . ""]["avg_grade"] = $sumgrade/($seqgrade-$extragrade);



                    $data["" . $tempyear . ""]["avg_att"] = $sumatt/($seqatt-$extraatt);



                }



                //echo "turn end";



            }



        }



















        $x= $this->pdf->GetX();



        $y= $this->pdf->GetY();







        $arr_trim = array();



        $arr_trilist = array();



        $arr_class = array();



        if(count($data)>0) {



            foreach ($data as $k => $arr_trim) {



                foreach ($arr_trim["tri"] as $k2 => $arr_trilist) {



                    foreach ($arr_trilist["class"] as $k3 => $arr_class) {



                        $this->pdf->SetFont('Arial', '', 9);



                        $this->pdf->Cell(16);



                        $this->pdf->Cell(12, 5,$k , 1, 0, 'C'); //year



                        $x += 12;



                        $this->pdf->Cell(19, 5,$k2, 1, 0, 'C'); // tri



                        $x += 19;



                        $this->pdf->Cell(48, 5,$arr_class["name"], 1, 0, 'C'); //CLASS NAME



                        $x += 48;



                        $this->pdf->Cell(10, 5,$arr_class["grade"], 1, 0, 'C'); //GRADE - ?????


                        $x += 10;


                        $newscore =trim($arr_class["score"]);

                        $totalsumgrade = $totalsumgrade + $newscore;


                        if($newscore!=""){



                            $newscore = $newscore."%";  //GRADE ????



                        }



                        $this->pdf->Cell(14, 5, $newscore, 1, 0, 'C'); //??????? ???



                        $x += 14;



                        $this->pdf->Ln();



                    }



                }



            }



        }







        $eeee = 0;



        $eeee2 = 0;



        $sumatt = 0;



        $seqatt = 0;



        $lev = "";



        if(count($data)>0) {



            foreach ($data as $k => $arr_trim) {



                $this->pdf->SetFont('Arial', '', 9);



                $this->pdf->Cell(16);







                /*



                echo "arr_trim: ";



                echo print_r($arr_trim)."<br>";



                echo "arr_trilist: ";



                echo print_r($arr_trilist). "<br>";



                echo "arr_class: ";



                echo print_r($arr_class). "<br>";











                echo "DATA SECTION : ";



                echo print_r($data)."<br>";



                */







                foreach ($arr_trim["tri"] as $k2 => $arr_trilist) {



                    foreach ($arr_trilist["class"] as $k3 => $arr_class) {



                    }



                    /*



                    echo "ATT_TRILIST SECTION : ";



                    echo print_r($arr_trilist)."<br>";







                    echo "K2 & K3 SECTION : ";



                    echo "KEY=". $k2.", VALUE=". $k3 ."<br>";;



                    echo "end <br>";



                    */







                    //attendance







                    $this->pdf->SetXY(149, $y + $eeee2);



                    $newatt = trim($arr_trilist["att"]);



                    if ($newatt != "") {



                        $newatt = $newatt . "%";



                    }



                    $this->pdf->MultiCell(21, 5 * count($arr_trilist["class"]), $newatt, 1, 'C'); //



                    $eeee2 = $eeee2 + 5 * count($arr_trilist["class"]);







                    if ($arr_trilist["level"] != 7 && $arr_trilist["level"] != 5) {



                        $sumatt = $sumatt + $arr_trilist["att"];



                        $seqatt++;



                    }



                }
                /* I removed it for the overall grade average 20180327
                //grade average

                $this->pdf->SetXY(129, $y + $eeee);

                // $arr_trim["avg_grade"]= ceil($arr_trim["avg_grade"]);

                $this->pdf->MultiCell(20, 5 * ($k3 + 1), ceil($arr_trim["avg_grade"]) . "%", 1, 'C');
                */

                $eeee = $eeee + 5 * ($k3 + 1);



            }

            /* I added it for the overall grade average 20180327*/
                    $this->pdf->SetXY(129, $y);
                    $this -> pdf -> MultiCell( 20,$eeee,round($totalsumgrade / ($gradecount))."%",1,'C');

                     //attendance average
                    $this->pdf->SetXY(170, $y);
                     $this->pdf->MultiCell(22, $eeee, round(($sumatt/$seqatt)) . "%", 1, 'C');


                     $this->pdf->Ln();



                     /*



                     $seqatt = 0;



                     $sumatt = 0;



                     */







        }







        $this->pdf->Ln();







        $this->pdf->Output("Transcript(".$result["lastname"].",".$result["firstname"].").pdf","D");



        exit();



    }















    public function createpdf($params){



        $this->load->library('pdf');



        $this->pdf->fontpath = $_SERVER['DOCUMENT_ROOT']."/application/libraries/font/";



        // $this->pdf->fontpath = 'font/';



        $this->pdf->AddPage();







        $pagecount = $this->pdf->setSourceFile($_SERVER['DOCUMENT_ROOT']."/application/libraries/document/FinancialsForm.pdf");



        $tpl = $this->pdf->importPage(1);



        $this->pdf->useTemplate($tpl, 0, 0);







        $this->pdf->SetFont('Arial','',11);



        $this->pdf->SetY(60);



        $this->pdf->Cell(14);



        $this->pdf->Cell(20,10,date('M j, Y'));



        $this->pdf->Ln();







        $this->pdf->SetFont('Arial','B',11);



        $this->pdf->Cell(44);



        $this->pdf->Cell(40,7,$params["sevis"]);



        $this->pdf->Ln();







        $this->pdf->SetFont('Arial','B',11);



        $this->pdf->Cell(44);



        $this->pdf->Cell(40,3,$params["stdnam"]);



        $this->pdf->Ln();







        $this->pdf->Ln();



        $this->pdf->Ln();



        $this->pdf->SetFont('Arial','',8);



        $this->pdf->Cell(10);



        $x= $this->pdf->GetX();



        $y= $this->pdf->GetY();



        $this->pdf->MultiCell(10, 8, "Year", 1,'C'); $x += 10; $this->pdf->SetXY($x,$y);



        $this->pdf->MultiCell(14, 8, "Trimester", 1,'C'); $x += 14; $this->pdf->SetXY($x,$y);



        $this->pdf->MultiCell(17, 4, "Payment\nDate", 1,'C'); $x += 17; $this->pdf->SetXY($x,$y);



        $this->pdf->MultiCell(34, 8, "Description", 1,'C'); $x += 34; $this->pdf->SetXY($x,$y);



        $this->pdf->MultiCell(23, 4, "Late Fees\n(non-refundable)",1,'C'); $x += 23; $this->pdf->SetXY($x,$y);



        $this->pdf->MultiCell(14, 4, "Amount\nPaid",1,'C'); $x += 14; $this->pdf->SetXY($x,$y);



        $this->pdf->MultiCell(14, 8, "Refunds", 1,'C'); $x += 14; $this->pdf->SetXY($x,$y);



        $this->pdf->MultiCell(22, 4, "Method of\nPayment", 1,'C'); $x += 22; $this->pdf->SetXY($x,$y);



        $this->pdf->MultiCell(26, 8, "Notes", 1,'C'); $x += 26; $this->pdf->SetXY($x,$y);



        $this->pdf->Ln();



        $res = $this->aliweb_model->getFinancialList($params["sno"]);



        foreach($res as $row5) {



            $this->pdf->Cell(10);



            $x= $this->pdf->GetX();



            $y= $this->pdf->GetY();







            $ddd = wordwrap($row5["notes"],26,"\n",true);



            $lines =  $this->pdf->NbLines(26,$ddd);



            $eee = 5 * $lines;







            $this->pdf->MultiCell(10,$eee, $row5["schoolyear"], 1,'C'); $x += 10; $this->pdf->SetXY($x,$y);



            $this->pdf->MultiCell(14,$eee, $row5["trimester"], 1,'C'); $x += 14; $this->pdf->SetXY($x,$y);



            $this->pdf->MultiCell(17,$eee, $row5["paiddate"], 1,'C'); $x += 17; $this->pdf->SetXY($x,$y);



            $this->pdf->MultiCell(34,$eee, $row5["description"], 1,'C'); $x += 34; $this->pdf->SetXY($x,$y);



            $this->pdf->MultiCell(23,$eee, $row5["latefees"],1,'R'); $x += 23; $this->pdf->SetXY($x,$y);



            $this->pdf->MultiCell(14,$eee, $row5["amountpaid"],1,'R'); $x += 14; $this->pdf->SetXY($x,$y);



            $this->pdf->MultiCell(14,$eee, $row5["refunds"], 1,'R'); $x += 14; $this->pdf->SetXY($x,$y);



            $this->pdf->MultiCell(22,$eee, $row5["method"], 1,'C'); $x += 22; $this->pdf->SetXY($x,$y);



            $this->pdf->MultiCell(26,5, $ddd, 1,'C');



        }



        $this->pdf->Ln();







        $this->pdf->Output("Financials(".$params["stdnam"]."_".$params["sevis"].").pdf","D");



        exit();



    }















    function uploadattendance($mode=''){



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));







        switch($mode):



            case 'getTrimesters': $this->getTrimesters($this->Param);  break;



            case 'setTrimester': $this->setTrimester($this->Param); break;



            case 'getTrimestersCombo': $this->getTrimestersCombo($this->Param); break;



            case 'menucontextUploadAttendace': $this->menucontextUploadAttendace($this->Param);  break;



            case 'getImportFiles2': $this->getImportFiles2($this->Param);  break;



            case 'setImportFile2': $this->setImportFile2($this->Param);  break;



            case 'setImportFiles2': $this->setImportFiles2($this->Param);  break;



            case 'setImportTemps2': $this->setImportTemps2($this->Param);  break;



            case 'getEGAttendances': $this->getEGAttendances($this->Param);  break;



            default:



                $this->Param['navi_seq'] = "setting";



                $this->Param['breadcrumb'] = array('Home' => $_SERVER["PHP_SELF"],'Classes' => $_SERVER["PHP_SELF"],'Attendance' => null, 'Import Engrade Attendances' => null);



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/uploadattendance', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;



    }



    function menucontextUploadAttendace($params){$xmlcontents = $this->aliweb_model->menucontextUploadAttendace($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}



    function getEGAttendances($params){$xmlcontents = $this->aliweb_model->getEGAttendances($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}



    function getImportFiles2($params){$xmlcontents = $this->aliweb_model->getImportFiles2($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}



    function setImportFiles2($params){$xmlcontents = $this->aliweb_model->setImportFiles2($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}



    function setImportFile2($params){



        $theFileName="";



        $xmlcontents ="";



        $dirpath = $_SERVER['DOCUMENT_ROOT']."/userfiles/uploaded/";







        if($_FILES["uploadfile"]) {



            $params["newfilename"] = $this->files_upload($_FILES["uploadfile"]["name"], $_FILES["uploadfile"]["type"], $_FILES["uploadfile"]["error"], $_FILES["uploadfile"]["tmp_name"], $dirpath);



            if (trim($params["newfilename"]) != "") {



                $xmlcontents = $this->aliweb_model->setImportFile2($params);



            }



            $params["newfilename"] = "";



        }







        print_r("<SCRIPT>parent.showfullmsg(\"msgResult\",\"It has been ".$xmlcontents." successfully.\"); parent.myCallBack();</SCRIPT>");



    }



    function setImportTemps2($params){



        $meag = 0;



        if(trim($params["fno"])!=""){







            $filenam = $this->aliweb_model->getImportfileInfo2($params["fno"]);







            if(trim($filenam)!=""){







                $delres = $this->aliweb_model->delete_row_EGAttendances($params["gno"]);



                if($delres=="deleted") {







                    $this->load->library('excel');



                    $inputFileType = 'CSV';



                    $inputFileName = $_SERVER['DOCUMENT_ROOT']."/userfiles/uploaded/".$filenam;



                    $objReader = PHPExcel_IOFactory::createReader($inputFileType);



                    $objPHPExcel = $objReader->load($inputFileName);



                    $worksheet = $objPHPExcel->getActiveSheet();



                    foreach ($worksheet->getRowIterator() as $row) {







                        if($row->getRowIndex() > 1){



                            $datas = array();



                            $cellIterator = $row->getCellIterator();



                            $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set



                            foreach ($cellIterator as $cell) {



                                $column = PHPExcel_Cell::columnIndexFromString($cell->getColumn());



                                $newData = $cell->getValue();



                                if($column==10){



                                    $newData = $cell->getValue(); $newData = str_replace("%","",$newData);



                                }



                                $datas[$column] = $newData;



                            }







                            if(trim($datas[5])!=''&&trim($datas[6])!=''&&trim($datas[7])!=''){



                                $params["file_no"] = $params["fno"];



                                $params["trimester_no"] = $params["gno"];



                                $params["engradeclassid"] = $datas[1];



                                $params["classschoolyear"] = $datas[2];



                                $params["classgradingperiod"] = $datas[3];



                                $params["classname"] = $datas[4];



                                $params["studentfirst"] = $datas[5];



                                $params["studentlast"] = $datas[6];



                                $params["studentid"] = $datas[7];



                                $rdate = DateTime::createFromFormat('m/d/Y', $datas[8]);



                                $params["attendancedate"] = $rdate->format('Y-m-d');



                                $params["mark"] = $datas[9];



                                $this->aliweb_model->insert_row_EGAttendances($params);



                            }







                        }



                    }



                    $res = $this->aliweb_model->update_ImportFile2($params["fno"]);



                    $meag = 1;//"Imported Temp Data!";







                }//end deleted







            }else{



                $meag = -5;//"Error by File Name!";



            }







        }else{



            $meag = -4;//"Error by File No!";



        }







        $this->load->helper('xml');



        header("Content-type:text/xml;charset=euc-kr");



        $dom = xml_dom();



        $items = xml_add_child($dom, 'items');



        $item1 = xml_add_child($items,'item',null,false);



        xml_add_attribute($item1,'value',$meag);



        echo xml_print($dom,true);



    }







    function files_upload($Fname,$Ftype,$Ferror,$Ftmpname,$file_path){ // #?????,?????? ???????



        $errormsg = ""; $file_name1=""; $msgfilenam ="";



        //$msgfilenam = "File# ".($j+1)." (".$Fname.")";



        // 2.?????? ?????? ?????? ?? ??????? ???



        if (isset($Fname) && !$Ferror):



            $file_name1=$this->confirmFname($Fname,$file_path);



            // 4.?????? ????????????? ?????? ????? ???



            if(move_uploaded_file($Ftmpname,$file_path.$file_name1)):



                // 5.?????? ????? ?????? ???



                $errormsg .= $msgfilenam." uploaded successfully<br>"; //Success message



                //$filenam = $file_name1;



            endif; //if , move_uploaded_file



        else:



            //$errormsg="";



            // 6.?????? ????????? �?



            if ($Ferror > 0):



                //echo '<p>???? ????? ???? ????: <strong>';



                // ???? ?????? ???



                switch ($Ferror):



                    case 1: $errormsg .= $msgfilenam."File# (".$Fname.") php.ini ?????? upload_max_filesize ???????? ?????(????? ???? ???)"; break;



                    case 2:	$errormsg .= $msgfilenam."Form???? ?????? MAX_FILE_SIZE ???????? ?????(????? ???? ???)"; break;



                    case 3:	$errormsg .= $msgfilenam."???? ???? ????? ??"; break;



                    case 4:	$errormsg .= $msgfilenam."?????? ?????? ????"; break;



                    case 6:	$errormsg .= $msgfilenam."??????? ????????? ????";	break;



                    case 7:	$errormsg .= $msgfilenam."????? ??????? ????"; break;



                    case 8:	$errormsg .= $msgfilenam."???? ????? ??????"; break;



                    default: $errormsg .= $msgfilenam."?�??? ?????? ???"; break;



                endswitch; // switch



                //echo '</strong></p>';



            endif; // if







            //echo $errormsg;







        endif; //if , isset







        return $file_name1;



    }



    function confirmFname($Fname,$t_MapPath){ // #?????,?????? ???????



        $attach_file = explode(".",$Fname); // #????? ???



        $strName = $attach_file[0];     // #?????



        $strExt =$attach_file[1];       // #?????



        $bExist = true;  //#??? ?????? ???? ??????? ??? ????



        $strFileName = $t_MapPath.$strName.".".$strExt;  #??� ???



        $FileName = $strName.".".$strExt;



        $countFileName = 0;



        If(file_exists($strFileName)):



            while($bExist): // #?? ???? ????



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



    function downfile($flname,$path)



    {



        $filename = $flname;



        $orgin_dir=$_SERVER['DOCUMENT_ROOT']."/userfiles/uploaded/".$path."/";



        $tmp_dir = $_SERVER['DOCUMENT_ROOT']."/userfiles/tmp/";







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











    function roles($mode=''){



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));







        switch($mode):



            case 'getTrimesters': $this->getTrimesters($this->Param);  break;



            case 'setTrimester': $this->setTrimester($this->Param); break;



            case 'getTrimestersCombo': $this->getTrimestersCombo($this->Param); break;



            case 'menucontextRoles': $this->menucontextRoles($this->Param);  break;



            case 'getRoles': $this->getRoles($this->Param);  break;



            case 'setRoles': $this->setRoles($this->Param);  break;



            case 'setImportFiles': $this->setImportFiles($this->Param);  break;



            case 'setImportTemps': $this->setImportTemps($this->Param);  break;



            case 'getEGGrades': $this->getEGGrades($this->Param);  break;



            default:



                $this->Param['navi_seq'] = "setting";



                $this->Param['breadcrumb'] = array('Home' => $_SERVER["PHP_SELF"],'Setting' => $_SERVER["PHP_SELF"],'Roles' => null);



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/roles', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;



    }



    function menucontextRoles($params){$xmlcontents = $this->aliweb_model->menucontextRoles($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}



    function getRoles($params){$xmlcontents = $this->aliweb_model->getRoles($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}



    function setRoles($params){$xmlcontents = $this->aliweb_model->setRoles($params);header("Content-type:text/xml;charset=euc-kr");echo $xmlcontents;}







    /*** Remediation > Class Inquiry Edit Function for Dhtmlx Start ****/



    function rclassinquiry($mode=''){



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));



        switch($mode):



            case 'getRClasses': $this->getRClasses($this->Param);  break;



            case 'getRClass': $this->getRClass($this->Param);  break;



            case 'setRClass': $this->setRClass($this->Param);  break;



            case 'getComTeachers': $this->getComTeachers($this->Param);  break;



            case 'getComRooms': $this->getComRooms($this->Param);  break;



            case 'getComTrimesters': $this->getComTrimesters($this->Param);  break;



            case 'getComSchoolYear': $this->getComSchoolYear($this->Param);  break;



            case 'getComLevel': $this->getComLevel($this->Param);  break;



            case 'menucontext': $this->menucontext($this->Param);  break;



            default:







                $this->Param['navi_seq'] = "rclasses";



                $this->Param['breadcrumb'] = array('Home' => $this->aliwebRoot,'Year / Trimester' => null, 'Remediation Class Inquiry' => null);



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/rclassinquiry', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;



    }



    function getRClasses($params){$xmlcontents = $this->aliweb_model->getRClasses($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function getRClass($params){$xmlcontents = $this->aliweb_model->getRClass($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function setRClass($params){$xmlcontents = $this->aliweb_model->setRClass($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}















    /*** Attendance Weeks Function for Dhtmlx Start ****/



    function getRClassBread($cur){



        return array('Home' =>$this->aliwebRoot,$this->session->userdata('SEL_RGPNAME') => $this->aliwebRoot.'rclassinquiry/',$this->session->userdata('SEL_RCLASSNAME')=>$this->aliwebRoot.'rattweeks/', $cur => null);



    }







    function rattweeks($mode='')



    {



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));







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



                redirect(site_url('aliweb/rclassinquiry'));



            }



        }







        list($classname,$gpname) = $this->aliweb_model->rclassGP($this->Param['classno']);



        if ($this->session->userdata('SEL_RGPNAME') == true){



            $this->session->set_userdata('SEL_RGPNAME',$gpname);



        }else{



            $this->session->set_userdata(array(



                'SEL_RGPNAME'	=> $gpname



            ));



        }



        switch($mode):



            case 'getRAttendance': $this->getRAttendance($this->Param);  break;



            case 'setRAttendance': $this->setRAttendance($this->Param);  break;



            //case 'getComClasses': $this->getComClasses($this->Param);  break;



            default:



                $this->Param['param']['title'] = "Attendance";



                $this->Param['param']['menus'] = "";



                foreach($this->RC_Submenu as $key=>$val){



                    $this->Param['param']['menus'].="<li><a href=\"".$this->RC_Submenu[$key]."\">".$key."</a></li>";



                }







                $this->Param['navi_seq'] = "rclasses";



                $this->Param['breadcrumb'] = $this->getRClassBread('Attendance');



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/rattweeks', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;



    }



    function getRAttendance($params){$xmlcontents = $this->aliweb_model->getRAttendance($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function setRAttendance($params){$xmlcontents = $this->aliweb_model->setRAttendance($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function rattmonths($mode='')



    {



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));







        $this->Param['param']['classno']=$this->session->userdata('SEL_RCLASSNO');



        $this->Param['param']['classname']=$this->session->userdata('SEL_RCLASSNAME');



        $this->Param['classno'] = $this->session->userdata('SEL_RCLASSNO');



        switch($mode):



            default:



                $sump=0;$sumt=0;$suma=0; $arr=array(); $stname="";



                list($arr,$sump,$sumt,$suma,$stname) = $this->aliweb_model->getRAttMonths($this->Param);



                $this->Param['param']['title'] = "Attendance";



                $this->Param['param']['menus'] = "";



                $this->Param['sump'] = $sump;



                $this->Param['sumt'] = $sumt;



                $this->Param['suma'] = $suma;



                $this->Param['stname'] = $stname;



                $this->Param['arr'] = $arr;



                list ($this->Param["classname"],$this->Param["gpname"]) = $this->aliweb_model->rclassGP($this->Param['classno']);



                foreach($this->RC_Submenu as $key=>$val){



                    $this->Param['param']['menus'].="<li><a href=\"".$this->RC_Submenu[$key]."\">".$key."</a></li>";



                }







                $this->Param['navi_seq'] = "rclasses";



                $this->Param['breadcrumb'] = $this->getRClassBread('Attendance');



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/rattmonths', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;



    }











    function rclassstudents($mode='')



    {



        $this->Param["gbnum"]=4;  //for send message in popup



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));







        $this->Param['param']['classno']=$this->session->userdata('SEL_RCLASSNO');



        $this->Param['param']['classname']=$this->session->userdata('SEL_RCLASSNAME');



        $this->Param['classno'] = $this->session->userdata('SEL_RCLASSNO');







        switch($mode):



            case 'getRClassStudents': $this->getRClassStudents($this->Param);  break;



            case 'getRSchoolRoster': $this->getRSchoolRoster($this->Param);  break;



            case 'getRClassRoster': $this->getRClassRoster($this->Param);  break;



            case 'setRClassRoster': $this->setRClassRoster($this->Param);  break;



            case 'setSendMessage': $this->setSendMessage($this->Param);  break;



            default:



                $this->Param['param']['title'] = "Students";



                $this->Param['param']['menus'] = "";



                foreach($this->RC_Submenu as $key=>$val){



                    $this->Param['param']['menus'].="<li><a href=\"".$this->RC_Submenu[$key]."\">".$key."</a></li>";



                }







                $this->Param['navi_seq'] = "rclasses";



                $this->Param['breadcrumb'] = $this->getRClassBread('Students');



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/rclassstudents', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;







    }



    function getRClassStudents($params){$xmlcontents = $this->aliweb_model->getRClassStudents($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function getRSchoolRoster($params){$xmlcontents = $this->aliweb_model->getRSchoolRoster($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function getRClassRoster($params){$xmlcontents = $this->aliweb_model->getRClassRoster($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function setRClassRoster($params){ $xmlcontents = $this->aliweb_model->setRClassRoster($params);	header("Content-type:text/xml;charset=utf-8");	echo $xmlcontents; }







    function rclassteachers($mode='')



    {



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));







        $this->Param['param']['classno']=$this->session->userdata('SEL_RCLASSNO');



        $this->Param['param']['classname']=$this->session->userdata('SEL_RCLASSNAME');



        $this->Param['classno'] = $this->session->userdata('SEL_RCLASSNO');



        //list ($classname,$gpname) = $this->aliweb_model->classGP($this->Param['classno']);







        switch($mode):



            case 'getRClassTeachers': $this->getRClassTeachers($this->Param);  break;



            case 'getRClassTeacher': $this->getRClassTeacher($this->Param);  break;



            case 'getComRClassTeachers': $this->getComRClassTeachers($this->Param);  break;



            case 'setRClassTeacher': $this->setRClassTeacher($this->Param);  break;



            default:



                $this->Param['param']['title'] = "Class Teachers";



                $this->Param['param']['menus'] = "";



                foreach($this->RC_Submenu as $key=>$val){



                    $this->Param['param']['menus'].="<li><a href=\"".$this->RC_Submenu[$key]."\">".$key."</a></li>";



                }







                $this->Param['navi_seq'] = "rclasses";



                $this->Param['breadcrumb'] = $this->getRClassBread('Class Teachers');



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/rclassteachers', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;







    }



    function getRClassTeachers($params){$xmlcontents = $this->aliweb_model->getRClassTeachers($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function getRClassTeacher($params){$xmlcontents = $this->aliweb_model->getRClassTeacher($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function setRClassTeacher($params){$xmlcontents = $this->aliweb_model->setRClassTeacher($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function getComRClassTeachers($params){$xmlcontents = $this->aliweb_model->getComRClassTeachers($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}











    function rattsheet($mode=''){



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));



        switch($mode):



            case 'getRAttSheet': $this->getRAttSheet($this->Param);  break;



            case 'getComSchoolYear': $this->getComSchoolYear($this->Param);  break;



            case 'getComTeachers': $this->getComTeachers($this->Param);  break;



            case 'getRStudentList': $this->getRStudentList($this->Param);  break;



            case 'getComLevel': $this->getComLevel($this->Param);  break;



            case 'getComRClasses':   $this->getComRClasses($this->Param);  break;



            default:



                $this->Param['navi_seq'] = "rclasses";



                $this->Param['breadcrumb'] = array('Home' => $_SERVER["PHP_SELF"],'Remediation' => null, 'Attendance Sheet' => null);



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/rattsheet', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;



    }



    function getRAttSheet($params){$xmlcontents = $this->aliweb_model->getRAttSheet($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function getRStudentList($params){$xmlcontents = $this->aliweb_model->getRStudentList($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}



    function getComRClasses($params){$xmlcontents = $this->aliweb_model->getComRClasses($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}


    function allfinance($mode=''){



        if ($this->session->userdata('ALISESS_LOGIN') == false) redirect(site_url('aliweb/login'));



        if (!$this->zacl->check_acl('edit')) redirect(site_url('aliweb/login'));



        switch($mode):

            case 'getComSchoolYear': $this->getComSchoolYear($this->Param);  break;

            case 'getAllFinance': $this->getAllFinance($this->Param);  break;


            default:



                $this->Param['navi_seq'] = "finance";



                $this->Param['breadcrumb'] = array('Home' => $_SERVER["PHP_SELF"], 'All finance' => null);



                $contents_data['breadcrumb'] = $this->load->view('common/breadcrumb_aliweb', $this->Param, true);



                $contents_data['contents'] = $this->load->view('aliweb/allfinance', $this->Param, true);



                $layout_data = $this->common_layout($contents_data);



                $this->load->view('layouts/layout_aliweb', $layout_data, false);



        endswitch;



    }

    function getAllFinance($params){$xmlcontents = $this->aliweb_model->getAllFinance($params);header("Content-type:text/xml;charset=utf-8");echo $xmlcontents;}






}