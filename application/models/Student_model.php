<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }

    function getValidate($params)
    {
        $userid = "";
        $fullname = "";
        $email = "";
        switch(	$params["grp"] ) {
            case '6':
                $q = $this->db->query('select user_ID, CONCAT(firstname," ",lastname) as fullname, email from ali_students where user_ID="' . $params["usr"] . '" or email="' . $params["usr"] . '"');
                if ($q->num_rows() > 0) {
                    $row = $q->row();
                    $userid = $row->user_ID;
                    $fullname = $row->fullname;
                    $email = trim($row->email);
                }
                break;
            default :
        }
        return array($userid,$fullname,$email);
    }
    function setRecoverPW($uid,$toname,$toemail,$grp)
    {
        $expFormat = mktime(date("H"), date("i")+5, date("s"), date("m"), date("d"), date("Y"));
        $expDate = date("Y-m-d H:i:s",$expFormat);
        $key = md5($toname . '_' . $toemail . rand(0,10000) .$expDate."@^5*(");

        $insdata = array(
            'UserID' => $uid,
            'Keyval' => $key,
            'expDate' => $expDate,
            'gubun' => $grp
        );
        $this->db->insert('ali_recoverpwd', $insdata);
        $this->newId = $this->db->insert_id();
        return $key;
    }
    function checkEmailKey($key)
    {
        $curDate = date("Y-m-d H:i:s");
        $sql = "select UserID,gubun from ali_recoverpwd WHERE Keyval = '".$key."' AND expDate >= '".$curDate."'";
        $query = $this->db->query($sql);
        if($query->num_rows() > 0){
            $row=$query->row();
            return array($row->gubun,$row->UserID);
        }
        return array('','');
    }
    function setNewPW($params)
    {
        $grp_table="";
        $grp_uid="";
        switch(	$params["grp"] ){
            case '6': //admin
                $grp_table="ali_students"; $grp_uid="user_ID";
                break;
            default :

        }
        $salt = $this->fn_generate_salt();
        $password = $this->fn_generate_salted_password($params["pw"],$salt);
        $this->db->set('passw',$password,TRUE);
        $this->db->where($grp_uid, $params["uid"]);
        $this->db->update($grp_table);
    }

    function fn_generate_salt($length = 2)
    {
        $chars=array_flip(array_merge(range(0,9),range("A","Z"),range("a","z")));
        $salt='';
        for($i=0;$i<$length;$i++)
        {
            $salt .= array_rand($chars);
        }
        return $salt;
    }

    function fn_generate_salted_password($password, $salt)
    {
        $_pass = hash('sha256',$salt.$password).':'.$salt;
        return $_pass;
    }
    function record_login_attempt($username, $ip_address, $success, $user_agent, $note)
    {
        $query = $this->db->insert('ali_user_login_attempt', array(
            'username'		=> $username,
            'ip_address'	=> $ip_address,
            'success'		=> $success,
            'user_agent'	=> $user_agent,
            'note'			=> $note
        ));

        return $this->formatOperationResult($query);
    }
    function formatOperationResult($query, $record = array())
    {
        //$this->chromephp->log($extra_params);
        if ($query)
        {
            // query successful
            $result = array(
                'success'   => true,
                'msg'       => 'Operation successful'
            );

            if (count($record)>0)
            {
                $rows = array(
                    'rows'	=> $record
                );
                $result = array_merge($result,$rows);
            }
        }
        else
        {
            // database error
            $result = array (
                'success' => false,
                'msg' => 'Database Error: '.$this->db->_error_message(),
                'num' => $this->db->_error_number()
            );
        }
        return $result;
    }
    function get_userinfo($sno){
        $userinfo ="";
        if(!empty($sno)){
            $sql = "select email from ali_students where no=".$sno;
            $result = $this->db->query($sql);
            if($result->num_rows() > 0) {
                $row = $result->row();
                $userinfo = $row->email;
            }
        }
        return null;
    }

    //use
    function getExistRemediation($sno)
    {
        $sql = "select count(*) as cnt  from ali_remedialroster where students_no=".$sno;
        $query = $this->db->query($sql);
        foreach($query->result_array() as $row5) {
            return $row5["cnt"];
        }
        return 0;
    }

    function getLoginInfoBySTNO($no)
    {
        $sql = "select students_no, user_ID, email, passw, firstname, lastname from ali_students where students_no=".$no." limit 1" ;
        $query = $this->db->query($sql);
        foreach($query->result_array() as $row5) {
            return $row5;
        }
        return null;
    }

    function getLoginInfo($login)
    {
        $sql = "select students_no, user_ID, email, passw, firstname, lastname from ali_students where user_ID='".$login."' limit 1" ;
        $query = $this->db->query($sql);
        foreach($query->result_array() as $row5) {
            return $row5;
        }
        return null;
    }
    function update_last_login($userid,$ip_address)
    {
        $this->load->helper('date');
        $this->db->set('last_ip',$ip_address);
        $this->db->set('last_login',date('Y-m-d H:i:s',now()));
        $this->db->where('user_ID',$userid);
        $query = $this->db->update('ali_students');
    }

    function currentGP(){
        $sql = "select no,schoolyear,gradingperiod,startday from ali_gradingperiod where active=1  order by startday desc limit 1";
        $query = $this->db->query($sql);
        foreach($query->result_array() as $row5) {
            return $row5["no"];
        }
        return null;
    }

    function getClasses($params)
    {
        $this->load->helper('xml');
        $sql="";
        $columns = array("schoolyear","trimester","classname","teachername","grade","attendance");
        if(isset($params["orderby"])){
            if($params["direct"]=='des')
                $direct = "DESC";
            else
                $direct = "ASC";
            $sql =" Order by ".$columns[$params["orderby"]]." ".$direct;
        }else{
            $sql =" Order by ar.schoolyear desc, ar.trimester, ar.level, ar.session ";
        }

        $sql = "select ar.schoolyear, ar.trimester, ct.no, ct.class_no, sc.name as classname, concat(st.firstname,' ',st.lastname) as teachername from ali_roster as ar inner join ali_class as sc on sc.no=ar.class_no inner join ali_classteachers as ct on ct.class_no=sc.no inner join ali_user as st on ct.teacher_no=st.no inner join ali_gradingperiod as ag on ar.schoolyear=ag.schoolyear and  ar.trimester=ag.gradingperiod where ar.students_no=".$this->session->userdata('STDSESS_USERNO')." and sc.status=0 and ag.active=1 AND ct.isprimary =1 ".$sql;
        $query = $this->db->query($sql);
        $dom = xml_dom();
        $rows = xml_add_child($dom, 'rows');
        $j=0;

        $totalgrade = 0;
        $totalatt = 0;
        foreach($query->result_array() as $row5)
        {
            $cgrade = $this->getGrade($this->session->userdata('STDSESS_USERNO'), $row5['class_no']);

            $catt = $this->getAttRate($this->session->userdata('STDSESS_USERNO'), $row5['class_no']);
            $item = xml_add_child($rows,'row',NULL,true); xml_add_attribute($item,'id',$row5['no']);
            $c0 = xml_add_child($item, 'cell',$row5['schoolyear'],true);
            $c1 = xml_add_child($item, 'cell',"Tri".$row5['trimester'],true);
            $c2 = xml_add_child($item, 'cell',$row5['classname']."^javascript:self.location.href=\"/index.php/student/studentgrade?classno=".$row5['class_no']."&classname=".$row5['classname']."\";^_self",true);
            $c3 = xml_add_child($item, 'cell',$row5['teachername'],true);
            $c4 = xml_add_child($item, 'cell',$cgrade,true);
            $c5 = xml_add_child($item, 'cell',$catt,true);

            $cgrade = str_replace("A", "", $cgrade);
            $cgrade = str_replace("B", "", $cgrade);
            $cgrade = str_replace("C", "", $cgrade);
            $cgrade = str_replace("D", "", $cgrade);
            $cgrade = str_replace("F", "", $cgrade);
            $cgrade = str_replace("%", "", $cgrade);

            $totalgrade=$totalgrade+$cgrade;
            $totalatt=$totalatt+$catt;
            $j++;
        }

        if($j !== 0 ) {
            $totalgrade= $totalgrade/2;
            $totalatt= $totalatt/2;
            $c6 = xml_add_child($item, 'cell', round($totalgrade)."%", true);
            $c7 = xml_add_child($item, 'cell', round($totalatt)."%", true);
        }
        if($j == 0 ){
            $item = xml_add_child($rows,'row',NULL,true); xml_add_attribute($item,'id',1);
            $c1 = xml_add_child($item, 'cell',"",true);
            $c2 = xml_add_child($item, 'cell',"No Data.".$this->session->userdata('STDSESS_USERNO'),true);
            $c3 = xml_add_child($item, 'cell',"",true);
            $c4 = xml_add_child($item, 'cell',"",true);
            $c5 = xml_add_child($item, 'cell',"",true);
            $c6 = xml_add_child($item, 'cell',"",true);
            $c7 = xml_add_child($item, 'cell',"",true);
        }

        return xml_print($dom,true);
    }

    function getAcademicRecords($params)
    {
        $this->load->helper('xml');
        $dom = xml_dom();
        $rows = xml_add_child($dom, 'rows');
        $head = xml_add_child($rows,'head',NULL,true);

        $colm1 = xml_add_child($head,'column',"Year",true);
        xml_add_attribute($colm1,'id',"schoolyear");
        xml_add_attribute($colm1,'width',"60");
        xml_add_attribute($colm1,'type',"coro");
        xml_add_attribute($colm1,'align',"center");
        xml_add_attribute($colm1,'color',"white");
        xml_add_attribute($colm1,'sort',"str");
        $sql = "SELECT schoolyear FROM ali_gradingperiod GROUP BY schoolyear ";
        $query = $this->db->query($sql);
        foreach($query->result_array() as $row1) {
            $o1 = xml_add_child($colm1, 'option',$row1["schoolyear"], false);
            xml_add_attribute($o1, 'value', $row1["schoolyear"]);
        }

        $colm2 = xml_add_child($head,'column',"Trimester",true);
        xml_add_attribute($colm2,'id',"trimester");
        xml_add_attribute($colm2,'width',"60");
        xml_add_attribute($colm2,'type',"coro");
        xml_add_attribute($colm2,'align',"center");
        xml_add_attribute($colm2,'color',"white");
        xml_add_attribute($colm2,'sort',"str");
        $sql = "SELECT gradingperiod FROM ali_gradingperiod GROUP BY gradingperiod ";
        $query = $this->db->query($sql);
        foreach($query->result_array() as $row2) {
            $o2 = xml_add_child($colm2, 'option',$row2["gradingperiod"], false);
            xml_add_attribute($o2, 'value', $row2["gradingperiod"]);
        }


        $colm3 = xml_add_child($head,'column',"Level",true);
        xml_add_attribute($colm3,'id',"level");
        xml_add_attribute($colm3,'width',"100");
        xml_add_attribute($colm3,'type',"coro");
        xml_add_attribute($colm3,'align',"center");
        xml_add_attribute($colm3,'color',"white");
        xml_add_attribute($colm3,'sort',"int");
        $sql = "SELECT levelname,levelvalue FROM ali_level GROUP BY levelname ";
        $query = $this->db->query($sql);
        foreach($query->result_array() as $row3) {
            $o3 = xml_add_child($colm3, 'option',$row3["levelname"], false);
            xml_add_attribute($o3, 'value', $row3["levelvalue"]);
        }

        $colm4 = xml_add_child($head,'column',"Session",true);
        xml_add_attribute($colm4,'id',"session");
        xml_add_attribute($colm4,'width',"60");
        xml_add_attribute($colm4,'type',"coro");
        xml_add_attribute($colm4,'align',"center");
        xml_add_attribute($colm4,'color',"white");
        xml_add_attribute($colm4,'sort',"int");
        $o5 = xml_add_child($colm4, 'option',"AM", false); xml_add_attribute($o5, 'value',"1");
        $o5 = xml_add_child($colm4, 'option',"AFT", false); xml_add_attribute($o5, 'value',"2");
        $o5 = xml_add_child($colm4, 'option',"PM", false); xml_add_attribute($o5, 'value',"3");
        $o5 = xml_add_child($colm4, 'option',"None", false); xml_add_attribute($o5, 'value',"4");


        $colm5 = xml_add_child($head,'column',"Att./Test Score",true);
        xml_add_attribute($colm5,'id',"att_score");
        xml_add_attribute($colm5,'width',"80");
        xml_add_attribute($colm5,'type',"ro");
        xml_add_attribute($colm5,'align',"right");
        xml_add_attribute($colm5,'color',"white");
        xml_add_attribute($colm5,'sort',"str");

        $colm6 = xml_add_child($head,'column',"LS",true);
        xml_add_attribute($colm6,'id',"ls_score");
        xml_add_attribute($colm6,'width',"50");
        xml_add_attribute($colm6,'type',"ro");
        xml_add_attribute($colm6,'align',"right");
        xml_add_attribute($colm6,'color',"white");
        xml_add_attribute($colm6,'sort',"str");

        $colm7 = xml_add_child($head,'column',"RW",true);
        xml_add_attribute($colm7,'id',"rw_score");
        xml_add_attribute($colm7,'width',"50");
        xml_add_attribute($colm7,'type',"ro");
        xml_add_attribute($colm7,'align',"right");
        xml_add_attribute($colm7,'color',"white");
        xml_add_attribute($colm7,'sort',"str");

        $colm8 = xml_add_child($head,'column',"TOEFL",true);
        xml_add_attribute($colm8,'id',"toefl_score");
        xml_add_attribute($colm8,'width',"50");
        xml_add_attribute($colm8,'type',"ro");
        xml_add_attribute($colm8,'align',"right");
        xml_add_attribute($colm8,'color',"white");
        xml_add_attribute($colm8,'sort',"str");

        $colm9 = xml_add_child($head,'column',"Date",true);
        xml_add_attribute($colm9,'id',"plt_date");
        xml_add_attribute($colm9,'width',"80");
        xml_add_attribute($colm9,'type',"ro");
        xml_add_attribute($colm9,'align',"right");
        xml_add_attribute($colm9,'color',"white");
        xml_add_attribute($colm9,'sort',"date");


        $colm11 = xml_add_child($head,'column',"Note",true);
        xml_add_attribute($colm11,'id',"plt_note");
        xml_add_attribute($colm11,'width',"140");
        xml_add_attribute($colm11,'type',"ro");
        xml_add_attribute($colm11,'align',"right");
        xml_add_attribute($colm11,'color',"white");
        xml_add_attribute($colm11,'sort',"str");


        $colm12 = xml_add_child($head,'column',"Writer",true);
        xml_add_attribute($colm12,'id',"writer");
        xml_add_attribute($colm12,'width',"70");
        xml_add_attribute($colm12,'type',"ro");
        xml_add_attribute($colm12,'align',"right");
        xml_add_attribute($colm12,'color',"white");
        xml_add_attribute($colm12,'sort',"str");


        $colm13 = xml_add_child($head,'column',"students_no",true);
        xml_add_attribute($colm13,'id',"students_no");
        xml_add_attribute($colm13,'width',"0");
        xml_add_attribute($colm13,'type',"ro");
        xml_add_attribute($colm13,'align',"right");
        xml_add_attribute($colm13,'color',"white");
        xml_add_attribute($colm13,'sort',"int");


        $colm14 = xml_add_child($head,'column',"gubun",true);
        xml_add_attribute($colm14,'id',"gubun");
        xml_add_attribute($colm14,'width',"0");
        xml_add_attribute($colm14,'type',"ro");
        xml_add_attribute($colm14,'align',"right");
        xml_add_attribute($colm14,'color',"white");
        xml_add_attribute($colm14,'sort',"int");

        $sql = "SELECT no,students_no,schoolyear,trimester,level,session,att_score,ls_score,rw_score,toefl_score,gubun,plt_date,plt_score,plt_note,writer FROM ali_academicrecords where students_no=".$params['sno']." order by schoolyear,trimester,gubun,level";
        $query = $this->db->query($sql);
        foreach($query->result_array() as $row5)
        {
            $item = xml_add_child($rows,'row',NULL,true); xml_add_attribute($item,'id',$row5['no']);
            if($row5['gubun']==1){
                xml_add_attribute($item,'style',"background-color:#DCDCDC;");
            }
            $c0 = xml_add_child($item, 'cell',$row5['schoolyear'],true);
            $c1 = xml_add_child($item, 'cell',$row5['trimester'],true);
            $c2 = xml_add_child($item, 'cell',$row5['level'],true);
            $c3 = xml_add_child($item, 'cell',$row5['session'],true);
            if($row5['gubun']==1){
                $c4 = xml_add_child($item, 'cell',$row5['plt_score'],true);
            }else{
                $c4 = xml_add_child($item, 'cell',$row5['att_score'],true);
            }
            $c5 = xml_add_child($item, 'cell',$row5['ls_score'],true);
            $c6 = xml_add_child($item, 'cell',$row5['rw_score'],true);
            $c7 = xml_add_child($item, 'cell',$row5['toefl_score'],true);
            if($row5['gubun']==1) {
                $c8 = xml_add_child($item, 'cell', $row5['plt_date'], true);
            }else{
                $c8 = xml_add_child($item, 'cell', $row5['plt_date'], true);
            }
            $c9 = xml_add_child($item, 'cell',"",true);
            $c10 = xml_add_child($item, 'cell',$row5['writer'],true);
            $c11 = xml_add_child($item, 'cell',$row5['students_no'],true);
            $c12 = xml_add_child($item, 'cell',$row5['gubun'],true);
        }


        return xml_print($dom,true);
    }



    function getAttRate($stno,$classno)
    {
            $ls_p=0;
            $ls_a=0;
            $ls_t=0;
            $sql1 = "SELECT marks FROM `ali_attendance_new` where student_no=".$stno." and class_no=".$classno." ";
            $query1 = $this->db->query($sql1);
            foreach($query1->result_array() as $row1)
            {
                $vl= substr($row1["marks"],0,1);
                if( $vl == "P" ){ $ls_p++; }
                if( $vl == "T" ){ $ls_t++; }
                if( $vl == "A" ){ $ls_a++; }
                $vr= substr($row1["marks"],1,1);
                if( $vr == "P" ){ $ls_p++; }
                if( $vr == "T" ){ $ls_t++; }
                if( $vr == "A" ){ $ls_a++; }
            }
            $sum_ls = $ls_p+$ls_t+$ls_a;

        $totalabsent = ($ls_a);
        $tardyrest =0;
        $tardytoabsent =0;
        $totaltardy = ($ls_t);
        if($totaltardy>0){
            $tardytoabsent = floor($totaltardy/3);
            $tardyrest = $totaltardy % 3;
        }

        if($sum_ls!=0) {

            $attrate = round(100 - (($tardytoabsent + $totalabsent + ($tardyrest * 0.3)) / 100 * 100), 0);
        }else{
            $attrate = 0;
        }

        return $attrate."%";
    }


    function getMyaccount($params)
    {
        $this->load->helper('xml');
        $sql = "select students_no, firstname,lastname,nickname,country,cellphone,cellphone2,email,email2,address1,address2,preschool,transfer,memo,student_ID,birthday,gender,emergencyphone,emergencyphone2,note,etc_memo,register_day,user_ID,passw from ali_students where students_no=".$this->session->userdata('STDSESS_USERNO');
        $query = $this->db->query($sql);
        $dom = xml_dom();
        $rows = xml_add_child($dom, 'data');
        foreach($query->result_array() as $row5)
        {
            $item1 = xml_add_child($rows,'id',$row5['students_no'],true);
            $item2 = xml_add_child($rows,'firstname',$row5['firstname'],true);
            $item3 = xml_add_child($rows,'lastname',$row5['lastname'],true);
            $item4 = xml_add_child($rows,'nickname',$row5['nickname'],true);
            $item5 = xml_add_child($rows,'country',$row5['country'],true);
            $item6 = xml_add_child($rows,'cellphone',$row5['cellphone'],true);
            $item7 = xml_add_child($rows,'cellphone2',$row5['cellphone2'],true);
            $item8 = xml_add_child($rows,'email',$row5['email'],true);
            $item9 = xml_add_child($rows,'email2',$row5['email2'],true);
            $item10 = xml_add_child($rows,'address1',$row5['address1'],true);
            $item11 = xml_add_child($rows,'address2',$row5['address2'],true);
            $item12 = xml_add_child($rows,'preschool',$row5['preschool'],true);
            $item13 = xml_add_child($rows,'transfer',$row5['transfer'],true);
            $item14 = xml_add_child($rows,'memo',$row5['memo'],true);
            $item15 = xml_add_child($rows,'student_ID',$row5['student_ID'],true);
            $item16 = xml_add_child($rows,'birthday',$row5['birthday'],true);
            $item17 = xml_add_child($rows,'gender',$row5['gender'],true);
            $item18 = xml_add_child($rows,'emergencyphone',$row5['emergencyphone'],true);
            $item19 = xml_add_child($rows,'emergencyphone2',$row5['emergencyphone2'],true);
            $item20 = xml_add_child($rows,'note',$row5['note'],true);
            $item21 = xml_add_child($rows,'etc_memo',$row5['etc_memo'],true);
            $item22 = xml_add_child($rows,'register_day',$row5['register_day'],true);
            $item23 = xml_add_child($rows,'user_ID',$row5['user_ID'],true);
            $item24 = xml_add_child($rows,'inst_pwd',null,true);
        }

        return xml_print($dom,true);
    }
    function setMyaccount($params)
    {
        $this->load->helper('xml');
        $dom = xml_dom();
        $data = xml_add_child($dom,'data');
        $rowId = $params["ids"];
        $this->newId = $rowId;
        $mode = $params[$rowId."_!nativeeditor_status"];
        switch($mode){
            case "inserted": $action = $mode; break;
            case "deleted": $action = $mode; break;
            case "updated": $action = $this->update_row_myaccount($rowId,$params); break;
        }
        $action2 = xml_add_child($data,'action',$action,false);
        xml_add_attribute($action2,'type','updated');
        xml_add_attribute($action2,'sid',$rowId);
        xml_add_attribute($action2,'tid',$this->newId);
        return xml_print($dom,true);
    }
    function update_row_myaccount($rowId,$params)
    {
        $updatedata = array(
            'email' => iconv("UTF-8", "CP949", $params[$rowId . "_email"]),
            'firstname' => iconv("UTF-8", "CP949", $params[$rowId . "_firstname"]),
            'lastname' => iconv("UTF-8", "CP949", $params[$rowId . "_lastname"]),
            'nickname' => iconv("UTF-8", "CP949", $params[$rowId . "_nickname"]),
            'cellphone' => iconv("UTF-8", "CP949", $params[$rowId . "_cellphone"]),
            'note' => iconv("UTF-8", "CP949", $params[$rowId . "_note"]),
            'writer' => $this->session->userdata('STDSESS_USERNAME')
        );
        if (trim($params[$rowId . "_inst_pwd"]) != "") {
            $salt = $this->fn_generate_salt();
            $password = $this->fn_generate_salted_password($params[$rowId."_inst_pwd"], $salt);
            $this->db->set('passw', "'" . $password . "'", FALSE);
        }

        $this->db->set('modified', 'now()', FALSE);
        $this->db->where('students_no', $params[$rowId . "_id"]);
        $this->db->update("ali_students", $updatedata);
        return "updated";
    }


    /*** Assigngrade Function for Dhtmlx Start****/
    function getGrade($stno,$classno)
    {
        //$studentfullname="";
        $sql3 = "select CONCAT(firstname,' ',lastname) AS fullname from ali_students where students_no=".$stno;
        $query3 = $this->db->query($sql3);
        if($query3->num_rows() > 0){
            $row3=$query3->row();
           // $studentfullname = $row3->fullname;
        }
        $totalCategoryRate=0;
        $grade=0;
        $arrcate = array();
        $sql8 = "select ac.no, ac.wpercentage from ali_assign_cate as ac inner join ali_assignments as sa on sa.assigncat_no=ac.no where sa.isview=0 and sa.class_no=".$classno." group by ac.no, ac.wpercentage";
        $query8 = $this->db->query($sql8);
        foreach($query8->result_array() as $row8) {
            $arrcate["".$row8['no'].""]["percentage"] = $row8['wpercentage'];
            $totalCategoryRate = $totalCategoryRate + intval($row8['wpercentage']);
        }

//        $sql9 = "select assigncat_no,AVG(score) as scoreavg from ali_grade_new where class_no=".$classno." and student_no=".$stno." group by assigncat_no";
        $sql9 = "select ac.assigncat_no,AVG(ac.score) as scoreavg  from ali_grade_new as ac inner join ali_assignments as sa on sa.no=ac.assign_no where  sa.isview=0 and ac.class_no=".$classno." and ac.student_no=".$stno."  group by ac.assigncat_no";
        $query9 = $this->db->query($sql9);
        foreach($query9->result_array() as $row9)
        {
            /*
            if(ISSET($arrcate["".$row9['assigncat_no'].""]["percentage"])) {
                //$arrcate["" . $row9['assigncat_no'] . ""]["categoryscore"] = (intval($row9["scoreavg"]) * (((100 * intval($arrcate["" . $row9['assigncat_no'] . ""]["percentage"])) / $sum) / 100));
                if(ISSET($row9["scoreavg"])) {
                    $grade = $grade + (intval($row9["scoreavg"]) * (((100 * intval($arrcate["" . $row9['assigncat_no'] . ""]["percentage"])) / $totalCategoryRate) / 100));
                }else{
                    $grade = $grade + 0;
                }
            }else{
               // $arrcate["".$row9['assigncat_no'].""]["categoryscore"] = 0;
            }*/
            $grade = $grade + number_format( ($row9["scoreavg"] * (((100*$arrcate["".$row9['assigncat_no'].""]["percentage"])/$totalCategoryRate)/100)),1);

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

        return $gl." ".$fg."%";
    }

    function getStudentGrades($params)
    {
        $this->load->helper('xml');
        $dom = xml_dom();
        $rows = xml_add_child($dom, 'rows');

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

            //Total Category Rate
            $totalCategoryRate = $totalCategoryRate + intval($row8['wpercentage']);
        }

       // $sql9 = "select assigncat_no,AVG(score) as scoreavg from ali_grade_new where class_no=".$params['classno']." and student_no=".$params['stno']." group by assigncat_no";
        $arravg = array();
        $arreach = array();
        $sql9 = "select ac.assigncat_no,AVG(ac.score) as scoreavg  from ali_grade_new as ac inner join ali_assignments as sa on sa.no=ac.assign_no where  sa.isview=0 and ac.class_no=".$params['classno']." and ac.student_no=".$params['stno']."  group by ac.assigncat_no";
        $query9 = $this->db->query($sql9);
        foreach($query9->result_array() as $row9)
        {
            /*
            if(ISSET($arrcate["".$row9['assigncat_no'].""]["percentage"])) {
                $arrcate["" . $row9['assigncat_no'] . ""]["categoryscore"] = (intval($row9["scoreavg"]) * (((100 * intval($arrcate["" . $row9['assigncat_no'] . ""]["percentage"])) / $totalCategoryRate) / 100));
                if(ISSET($row9["scoreavg"])) {
                    $grade = $grade + (intval($row9["scoreavg"]) * (((100 * intval($arrcate["" . $row9['assigncat_no'] . ""]["percentage"])) / $totalCategoryRate) / 100));
                }else{
                    $grade = $grade + 0;
                }
            }else{
                $arrcate["".$row9['assigncat_no'].""]["categoryscore"] = 0;
            }*/
            $arreach["".$row9['assigncat_no'].""]["eachcat"] = (((100*$arrcate["".$row9['assigncat_no'].""]["percentage"])/$totalCategoryRate)/100);
            $arrcate["".$row9['assigncat_no'].""]["categoryscore"] = ($row9["scoreavg"] * $arreach["".$row9['assigncat_no'].""]["eachcat"] );
            $arravg["".$row9['assigncat_no'].""]["gradeavg"] = $row9["scoreavg"];
            $grade = $grade + number_format($arrcate["".$row9['assigncat_no'].""]["categoryscore"],1);
        }
        //Grading Scale
        $gl = "";
        $fg = floor($grade);
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


        $itemH = xml_add_child($rows,'row',NULL,true);
        xml_add_attribute($itemH,'id',0);
        xml_add_attribute($itemH,'style',"background-color:#BDDEFF;");
        $c1 = xml_add_child($itemH, 'cell',"<b>".$studentfullname." : ".$gl." ".$fg."%</b>",true);
        $c2 = xml_add_child($itemH, 'cell',NULL,true);
        $c3 = xml_add_child($itemH, 'cell',NULL,true);

        $seq=2;
        $sql0 = "SELECT no, name, wpercentage FROM ali_assign_cate where class_no=".$params['classno'];
        $query0 = $this->db->query($sql0);
        foreach($query0->result_array() as $row0)
        {
            $data = array();
            $sql1 = "select sa.no, sa.name,sg.score,sa.points,sa.description from ali_assignments as sa left join (select assign_no,score from ali_grade_new where student_no=".$params['stno'].") as sg on sa.no=sg.assign_no where sa.class_no=".$params['classno']." and sa.isview=0 and sa.assigncat_no=".$row0['no'];
            $query1 = $this->db->query($sql1);
            $j=0;
            foreach($query1->result_array() as $row1)
            {
                $data[$j]["assignname"] = $row1["name"];
                $scor = $row1["score"];
                if($scor==null){
                    $scor = "-";
                }
                $data[$j]["score"] = $scor;
                $data[$j]["points"] = $row1["points"];
                $j++;
            }

            $categrade = "";
            if(ISSET($arravg["".$row0['no'].""]["gradeavg"])) {
                $categrade = round($arravg["" . $row0['no'] . ""]["gradeavg"]*$arreach["" . $row0['no'] . ""]["eachcat"],1)."%";
            }

            $item0 = xml_add_child($rows,'row',NULL,true);
            xml_add_attribute($item0,'id',$seq);
            xml_add_attribute($item0,'style',"background-color:#E0E0E0;");
            $c1 = xml_add_child($item0, 'cell',"<div style='position:absolute; z-index:99;margin-top: -12px;'><b>".$row0['name']." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ".$categrade."</b> (counts as ".$row0['wpercentage']."% of overall grade)</div>",true);
            $c2 = xml_add_child($item0, 'cell',NULL,true);
            $c3 = xml_add_child($item0, 'cell',NULL,true);

            $seq++;
            for($s=0; $s < count($data); $s++,$seq++){
                $item1 = xml_add_child($rows,'row',NULL,true);
                xml_add_attribute($item1,'id',$seq);
                $c5 = xml_add_child($item1, 'cell',html_entity_decode($data[$s]['assignname'],ENT_QUOTES),true);
                $c6 = xml_add_child($item1, 'cell',$data[$s]['score'],true);
                $c7 = xml_add_child($item1, 'cell',$data[$s]['points'],true);
            }
            $seq++;
        }

        $item2 = xml_add_child($rows,'row',NULL,true);
        xml_add_attribute($item2,'id',$seq);
        xml_add_attribute($item2,'style',"background-color:#BDDEFF;");
        $c9 = xml_add_child($item2, 'cell',"<b>".$studentfullname." : ".$gl." ".$fg."%</b>",true);
        $c10 = xml_add_child($item2, 'cell',NULL,true);
        $c11 = xml_add_child($item2, 'cell',NULL,true);

        $user1 = xml_add_child($rows, 'userdata',$studentfullname,true);
        xml_add_attribute($user1,'name',"selectstudent");

        $user2 = xml_add_child($rows, 'userdata',floor($grade),true);
        xml_add_attribute($user2,'name',"resultgrade");

        return xml_print($dom,true);

    }

    function rclassGP($classno){
        $sql = "select ac.name as classname,concat(ac.schoolyear,' / ','Tri',ac.trimester) as gp from ali_remedialclass as ac where ac.no=".$classno;
        $query = $this->db->query($sql);
        foreach($query->result_array() as $row5) {
            return array($row5["classname"],$row5["gp"]);
        }
        return array();
    }

    function classGP($classno){
        $sql = "select ac.name as classname,concat(ac.schoolyear,' / ','Tri',ac.trimester) as gp from ali_class as ac where ac.no=".$classno;
        $query = $this->db->query($sql);
        foreach($query->result_array() as $row5) {
            return array($row5["classname"],$row5["gp"]);
        }
        return array();
    }

    function getAttMonths($params)
    {
        $sum_p=0;
        $sum_a=0;
        $sum_t=0;
        $res = array();
        $sql1 = "SELECT no, DATE_FORMAT(attendance_day,'%Y') as yy,DATE_FORMAT(attendance_day,'%m') as mm,DATE_FORMAT(attendance_day,'%d') as dd, marks FROM `ali_attendance_new` where student_no=".$params["stno"]." and class_no=".$params["classno"]." ";
        $query1 = $this->db->query($sql1);
        foreach($query1->result_array() as $row5)
        {
            $res["".$row5["yy"].""]["".$row5["mm"].""]["".$row5["dd"].""] = $row5["marks"];

            $vl= substr($row5["marks"],0,1);
            if( $vl == "P" ){ $sum_p++; }
            if( $vl == "T" ){ $sum_t++; }
            if( $vl == "A" ){ $sum_a++; }
            $vr= substr($row5["marks"],1,1);
            if( $vr == "P" ){ $sum_p++; }
            if( $vr == "T" ){ $sum_t++; }
            if( $vr == "A" ){ $sum_a++; }
        }

        $studentfullname="";
        $sql3 = "select CONCAT(firstname,' ',lastname) AS fullname from ali_students where students_no=".$params['stno'];
        $query3 = $this->db->query($sql3);
        if($query3->num_rows() > 0){
            $row3=$query3->row();
            $studentfullname = $row3->fullname;
        }
        return array($res,$sum_p,$sum_t,$sum_a,$studentfullname);
    }

    function getComTeachers($params){
        $this->load->helper('xml');
        $sql="";
        $dom = xml_dom();
        $comp = xml_add_child($dom,'complete');
        $sql = "select no,firstname,lastname,email from ali_user where active=1 AND roleid=3";
        $query = $this->db->query($sql);
        $item2 = xml_add_child($comp,'option',"ALI Office",false);
        xml_add_attribute($item2,'value','signparkpsign@gmail.com');
        foreach($query->result_array() as $row5)
        {
            $item2 = xml_add_child($comp,'option',$row5['firstname']." ".$row5['lastname'],false);
            xml_add_attribute($item2,'value',$row5['email']);
        }
        return xml_print($dom,true);
    }

    function getRAttRate($stno,$classno)
    {
        $ls_p=0;
        $ls_a=0;
        $ls_t=0;
        $sql1 = "SELECT marks FROM `ali_remedialattendance` where student_no=".$stno." and class_no=".$classno." ";
        $query1 = $this->db->query($sql1);
        foreach($query1->result_array() as $row1)
        {
            //$vl= substr($row1["marks"],0,1);
            $vl = $row1["marks"];
            if( $vl == "P" ){ $ls_p++; }
           // if( $vl == "T" ){ $ls_t++; }
            if( $vl == "A" ){ $ls_a++; }
           // $vr= substr($row1["marks"],1,1);
           // $vr = $row1["marks"];
           // if( $vr == "P" ){ $ls_p++; }
           // if( $vr == "T" ){ $ls_t++; }
           // if( $vr == "A" ){ $ls_a++; }
        }
        $sum_ls = $ls_p+$ls_t+$ls_a;

        $totalabsent = ($ls_a);

        $tardytoabsent =0;
       // $totaltardy = ($ls_t);
       // if($totaltardy>0){
       //     $tardytoabsent = floor($totaltardy/3);
       // }

        $attrate = 100-round( (($tardytoabsent+$totalabsent)/($sum_ls))*100 ,1);

        return $attrate."%";
    }

    function getRTotalAttRate($stno,$classno)
    {

        $classnoregular =0;
        $sql0 = "select ar.schoolyear, ar.trimester, ct.no, ct.class_no, sc.name as classname, concat(st.firstname,' ',st.lastname) as teachername, SUM(CASE WHEN (ar.classtype='LS') THEN ar.class_no ELSE 0 END) AS LSno, SUM(CASE WHEN (ar.classtype='RW') THEN ar.class_no ELSE 0 END) AS RWno from ali_roster as ar inner join ali_class as sc on sc.no=ar.class_no inner join ali_classteachers as ct on ct.class_no=sc.no inner join ali_user as st on ct.teacher_no=st.no inner join ali_gradingperiod as ag on ar.schoolyear=ag.schoolyear and  ar.trimester=ag.gradingperiod where ar.students_no=".$this->session->userdata('STDSESS_USERNO')." and sc.status=0 and ag.active=1 AND ct.isprimary =1";
        $query0 = $this->db->query($sql0);
        foreach($query0->result_array() as $row0) {
            $classnoregular = $row0['class_no'];
        }
        $ls_p=0;
        $ls_a=0;
        $ls_t=0;
        $sql1 = "SELECT marks FROM `ali_attendance_new` where student_no=".$stno." and class_no=". $row0['LSno'] .  " ";
        $query1 = $this->db->query($sql1);
        foreach($query1->result_array() as $row1)
        {
            $vl= substr($row1["marks"],0,1);
            if( $vl == "P" ){ $ls_p++; }
            if( $vl == "T" ){ $ls_t++; }
            if( $vl == "A" ){ $ls_a++; }
            $vr= substr($row1["marks"],1,1);
            if( $vr == "P" ){ $ls_p++; }
            if( $vr == "T" ){ $ls_t++; }
            if( $vr == "A" ){ $ls_a++; }
        }
        //$sum_ls = $ls_p+$ls_t+$ls_a;
        $sql3 = "SELECT marks FROM `ali_attendance_new` where student_no=".$stno." and class_no=". $row0['RWno'] .  " ";
        $query3 = $this->db->query($sql3);
        foreach($query3->result_array() as $row3)
        {
            $vl= substr($row3["marks"],0,1);
            if( $vl == "P" ){ $ls_p++; }
            if( $vl == "T" ){ $ls_t++; }
            if( $vl == "A" ){ $ls_a++; }
            $vr= substr($row3["marks"],1,1);
            if( $vr == "P" ){ $ls_p++; }
            if( $vr == "T" ){ $ls_t++; }
            if( $vr == "A" ){ $ls_a++; }
        }

        $totalabsent = ($ls_a);

        $tardytoabsent =0;
        $totaltardy = ($ls_t);

        $tardyrest =0;
        if($totaltardy>0){
            $tardytoabsent = floor($totaltardy/3);
            $tardyrest = $totaltardy % 3;
        }


        $ls_rp=0;
        $ls_ra=0;
        $sql2 = "SELECT marks FROM `ali_remedialattendance` where student_no=".$stno." and class_no=".$classno." ";
        $query2 = $this->db->query($sql2);
        foreach($query2->result_array() as $row2)
        {
            //$vl= substr($row1["marks"],0,1);
            $vl = $row2["marks"];
            if( $vl == "P" ){ $ls_rp++; }
            // if( $vl == "T" ){ $ls_t++; }
            if( $vl == "A" ){ $ls_ra++; }
            // $vr= substr($row1["marks"],1,1);
            // $vr = $row1["marks"];
            // if( $vr == "P" ){ $ls_p++; }
            // if( $vr == "T" ){ $ls_t++; }
            // if( $vr == "A" ){ $ls_a++; }
        }
        $sum_ls = $ls_rp+$ls_ra;

        $totalrabsent = ($ls_ra);

        $attrate = round(((200+$sum_ls)-($totalrabsent+$totalabsent+$tardytoabsent+($tardyrest*0.3)))/( 200+$sum_ls )*100);

        //$sum_ls."&".$totalrabsent."&".$totalabsent."&".$tardytoabsent."&".$tardyrest."&".$stno."&".$classno."&".$classnoregular;

        return $attrate."%";
    }

    function getRClasses($params)
    {
        $this->load->helper('xml');
        $sql="";
        $columns = array("schoolyear","trimester","classname","teachername","attendance");
        if(isset($params["orderby"])){
            if($params["direct"]=='des')
                $direct = "DESC";
            else
                $direct = "ASC";
            $sql =" Order by ".$columns[$params["orderby"]]." ".$direct;
        }else{
            $sql =" Order by ar.schoolyear desc, ar.trimester, ar.level, ar.session ";
        }

        $totalatt = 0;

        $sql1 = "select ar.schoolyear, ar.trimester, ct.no, ct.class_no, sc.name as classname, concat(st.firstname,' ',st.lastname) as teachername from ali_remedialroster as ar inner join ali_remedialclass as sc on sc.no=ar.class_no inner join ali_remedialclassteachers as ct on ct.class_no=sc.no inner join ali_user as st on ct.teacher_no=st.no inner join ali_gradingperiod as ag on ar.schoolyear=ag.schoolyear and  ar.trimester=ag.gradingperiod where ar.students_no=".$this->session->userdata('STDSESS_USERNO')." and sc.status=0 and ag.active=1 AND ct.isprimary =1 ".$sql;
        $query1 = $this->db->query($sql1);
        $dom = xml_dom();
        $rows = xml_add_child($dom, 'rows');
        $j=0;
        foreach($query1->result_array() as $row5)
        {
            $catt = $this->getRAttRate($this->session->userdata('STDSESS_USERNO'), $row5['class_no']);
            $totalatt = $this->getRTotalAttRate($this->session->userdata('STDSESS_USERNO'), $row5['class_no']);
            $item = xml_add_child($rows,'row',NULL,true); xml_add_attribute($item,'id',$row5['no']);
            $c0 = xml_add_child($item, 'cell',$row5['schoolyear'],true);
            $c1 = xml_add_child($item, 'cell',"Tri".$row5['trimester'],true);
            $c2 = xml_add_child($item, 'cell',$row5['classname']."^javascript:self.location.href=\"/index.php/student/rattmonths?classno=".$row5['class_no']."&classname=".$row5['classname']."\";^_self",true);
            $c3 = xml_add_child($item, 'cell',$row5['teachername'],true);
            $c5 = xml_add_child($item, 'cell',$catt,true);
            $c6 = xml_add_child($item, 'cell',$totalatt,true);
            $j++;
        }

        if($j == 0 ){
            $item = xml_add_child($rows,'row',NULL,true); xml_add_attribute($item,'id',1);
            $c1 = xml_add_child($item, 'cell',"",true);
            $c2 = xml_add_child($item, 'cell',"No Data.".$this->session->userdata('STDSESS_USERNO'),true);
            $c3 = xml_add_child($item, 'cell',"",true);
            $c5 = xml_add_child($item, 'cell',"",true);
            $c6 = xml_add_child($item, 'cell',"",true);
        }

        return xml_print($dom,true);
    }


    function getRAttMonths($params)
    {
        $sum_p=0;
        $sum_a=0;
        $sum_t=0;
        $res = array();
        $sql1 = "SELECT no, DATE_FORMAT(attendance_day,'%Y') as yy,DATE_FORMAT(attendance_day,'%m') as mm,DATE_FORMAT(attendance_day,'%d') as dd, marks FROM `ali_remedialattendance` where student_no=".$params["stno"]." and class_no=".$params["classno"]." ";
        $query1 = $this->db->query($sql1);
        foreach($query1->result_array() as $row5)
        {
            $res["".$row5["yy"].""]["".$row5["mm"].""]["".$row5["dd"].""] = $row5["marks"];

            $vl= substr($row5["marks"],0,1);
            if( $vl == "P" ){ $sum_p++; }
            if( $vl == "T" ){ $sum_t++; }
            if( $vl == "A" ){ $sum_a++; }
            $vr= substr($row5["marks"],1,1);
            if( $vr == "P" ){ $sum_p++; }
            if( $vr == "T" ){ $sum_t++; }
            if( $vr == "A" ){ $sum_a++; }
        }

        $studentfullname="";
        $sql3 = "select CONCAT(firstname,' ',lastname) AS fullname from ali_students where students_no=".$params['stno'];
        $query3 = $this->db->query($sql3);
        if($query3->num_rows() > 0){
            $row3=$query3->row();
            $studentfullname = $row3->fullname;
        }
        return array($res,$sum_p,$sum_t,$sum_a,$studentfullname);
    }


}