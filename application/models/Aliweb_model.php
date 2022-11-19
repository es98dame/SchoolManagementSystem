<?php if (!defined('BASEPATH')) exit('No direct script access allowed');





class Aliweb_model extends CI_Model



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



        switch ($params["grp"]) {



            case '9': //admin



                $q = $this->db->query('select user_ID, CONCAT(firstname," ",lastname) as fullname, email from ali_user where user_ID="' . $params["usr"] . '" or email="' . $params["usr"] . '"');



                if ($q->num_rows() > 0) {



                    $row = $q->row();



                    $userid = $row->user_ID;



                    $fullname = $row->fullname;



                    $email = $row->email;



                }



                break;



            default :



        }



        return array($userid, $fullname, $email);



    }



    function setRecoverPW($uid, $toname, $toemail, $grp)



    {



        $expFormat = mktime(date("H"), date("i") + 5, date("s"), date("m"), date("d"), date("Y"));



        $expDate = date("Y-m-d H:i:s", $expFormat);



        $key = md5($toname . '_' . $toemail . rand(0, 10000) . $expDate . "@^5*(");





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



        $sql = "select UserID,gubun from ali_recoverpwd WHERE Keyval = '" . $key . "' AND expDate >= '" . $curDate . "'";



        $query = $this->db->query($sql);



        if ($query->num_rows() > 0) {



            $row = $query->row();



            return array($row->gubun, $row->UserID);



        }



        return array('', '');



    }



    function setNewPW($params)



    {



        $grp_table = "";



        $grp_uid = "";



        switch ($params["grp"]) {



            case '9': //admin



                $grp_table = "ali_user";

                $grp_uid = "user_ID";



                break;



            default :





        }



        $salt = $this->fn_generate_salt();



        $password = $this->fn_generate_salted_password($params["pw"], $salt);



        $this->db->set('passw', $password, TRUE);



        $this->db->where($grp_uid, $params["uid"]);



        $this->db->update($grp_table);



    }



    //use



    function fn_generate_salt($length = 2)



    {/*



     	$length = $length > 2 ? 2 : $length;



     	$salt = '';



     	for ($i = 0; $i < $length; $i++) {



     		$salt .= chr(rand(33,126));



     	}



*/



        $chars = array_flip(array_merge(range(0, 9), range("A", "Z"), range("a", "z")));



        $salt = '';



        for ($i = 0; $i < $length; $i++) {



            $salt .= array_rand($chars);



        }



        return $salt;



    }



    //use



    function fn_generate_salted_password($password, $salt)



    {



        /*



           $_pass = '';



           if (empty($salt)) {



               $_pass = md5($password);



           } else {



               $_pass = md5(md5($password).md5($salt));



           }*/



        $_pass = hash('sha256', $salt . $password) . ':' . $salt;



        //$_pass = hash('sha256',$password.':'.$salt);



        return $_pass;



    }



    //use



    function record_login_attempt($username, $ip_address, $success, $user_agent, $note)



    {



        // execute query



        $query = $this->db->insert('ali_user_login_attempt', array(



            'username' => $username,



            'ip_address' => $ip_address,



            'success' => $success,



            'user_agent' => $user_agent,



            'note' => $note



        ));





        // return formatted result



        return $this->formatOperationResult($query);



    }



    function formatOperationResult($query, $record = array())



    {



        //$this->chromephp->log($extra_params);



        if ($query) {



            // query successful



            $result = array(



                'success' => true,



                'msg' => 'Operation successful'



            );





            if (count($record) > 0) {



                $rows = array(



                    'rows' => $record



                );



                $result = array_merge($result, $rows);



            }



        } else {



            // database error



            $result = array(



                'success' => false,



                'msg' => 'Database Error: ' . $this->db->_error_message(),



                'num' => $this->db->_error_number()



            );



        }



        return $result;



    }



    //use



    function get_userinfo($sno)

    {



        $userinfo = "";



        if (!empty($sno)) {



            $sql = "select email from ali_user where no=" . $sno;



            $result = $this->db->query($sql);



            if ($result->num_rows() > 0) {



                $row = $result->row();



                $userinfo = $row->email;



            }



        }



        return null;



    }



    //use



    function getExistRemediation($tno)



    {



        $sql = "select count(*) as cnt from ali_remedialclassteachers where teacher_no=" . $tno;



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row5) {



            return $row5["cnt"];



        }



        return 0;



    }





    //use



    function getLoginInfo($login)



    {



        $sql = "select no, user_ID, email, passw, firstname, lastname, roleid from ali_user where user_ID='" . $login . "' limit 1";



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row5) {



            return $row5;



        }



        return null;



    }



    //use



    function update_last_login($userid, $ip_address)



    {



        $this->load->helper('date');





        $this->db->set('last_ip', $ip_address);



        $this->db->set('last_login', date('Y-m-d H:i:s', now()));





        $this->db->where('user_ID', $userid);





        $query = $this->db->update('ali_user');



    }





    function getStudent($sno)

    {



        $sql = "select students_no,firstname,lastname,nickname,birthday,progress,note,country,cellphone,cellphone2,email,email2,address1,address2,items,preschool,transfer,student_ID,gender,emergencyphone,emergencyphone2,etc_memo,register_day,memo from ali_students where students_no=" . $sno;



        $query = $this->db->query($sql);



        if ($query->num_rows() > 0) {



            foreach ($query->result_array() as $row5) {



                return $row5;



            }



        }



        return null;



    }


    function getStudents($params)
    {
        $this->load->helper('xml');
        //201580130
        $columns = array("students_no", "firstname", "lastname", "nickname", "birthday", "progress", "note", "country", "cellphone", "cellphone2", "email", "email2", "address1", "address2", "items", "preschool", "transfer", "student_ID", "gender", "emergencyphone", "emergencyphone2", "etc_memo", "register_day", "memo", "user_ID","probation","probation2","probation3","withd_day","complete");
        $this->db->select("students_no,firstname,lastname,nickname,birthday,progress,note,country,cellphone,cellphone2,email,email2,address1,address2,items,preschool,transfer,student_ID,gender,emergencyphone,emergencyphone2,etc_memo,register_day,memo,user_ID,probation,probation2,probation3,withd_day,complete");
        $this->db->from("ali_students");
        if (isset($params["keyword"])) {
            switch ($params["keyword"]) {
                case 1:
                    $this->db->like("UPPER(email)", strtoupper($params["searchword"]));
                    break;
                case 2:
                    $this->db->like("UPPER(country)", strtoupper($params["searchword"]));
                    break;
                case 3:
                    $this->db->where("birthday", $params["searchword"]);
                    break;
                case 4:
                    $this->db->where("register_day", $params["searchword"]);
                    break;
            }
        }

        if (isset($params["vstatus"])) {
            $pieces = explode(",",$params["vstatus"]);
            $this->db->where_in('progress',$pieces);
        }



        $totalrows = $this->db->count_all_results();

        //20180130
        $this->db->select("students_no,firstname,lastname,nickname,birthday,progress,note,country,cellphone,cellphone2,email,email2,address1,address2,items,preschool,transfer,student_ID,gender,emergencyphone,emergencyphone2,etc_memo,register_day,memo,user_ID,probation,probation2,probation3,withd_day,complete");
        $this->db->from("ali_students");
        if (isset($params["keyword"])) {
            switch ($params["keyword"]) {
                case 1:
                    $this->db->like("UPPER(email)", strtoupper($params["searchword"]));
                    break;
                case 2:
                    $this->db->like("UPPER(country)", strtoupper($params["searchword"]));
                    break;
                case 3:
                    $this->db->where("birthday", $params["searchword"]);
                    break;
                case 4:
                    $this->db->where("register_day", $params["searchword"]);
                    break;
            }
        }

        if (isset($params["vstatus"])) {
            $pieces = explode(",",$params["vstatus"]);
            $this->db->where_in('progress',$pieces);
        }



        if (isset($params["orderby"])) {
            if ($params["direct"] == 'des')
                $direct = "desc";
            else
                $direct = "asc";
            $this->db->order_by($columns[$params["orderby"]], $direct);
        } else {
            $this->db->order_by("students_no", "desc");
        }

        $query = $this->db->get();
        $dom = xml_dom();
        $rows = xml_add_child($dom, 'rows');
        $item24 = xml_add_child($rows, 'userdata', $totalrows, true);
        xml_add_attribute($item24, 'name', "totalrows");
        if ($query->num_rows() == 0) {
            $item = xml_add_child($rows, 'row', NULL, true);
            xml_add_attribute($item, 'id', 1);
            $c0 = xml_add_child($item, 'cell', NULL, true);
            $c1 = xml_add_child($item, 'cell', NULL, true);
            $c2 = xml_add_child($item, 'cell', 'No Data', true);
            $c3 = xml_add_child($item, 'cell', NULL, true);
            $c4 = xml_add_child($item, 'cell', NULL, true);
            $c5 = xml_add_child($item, 'cell', NULL, true);
            $c6 = xml_add_child($item, 'cell', NULL, true);
            $c7 = xml_add_child($item, 'cell', NULL, true);
            $c8 = xml_add_child($item, 'cell', NULL, true);
            $c9 = xml_add_child($item, 'cell', NULL, true);
            $c10 = xml_add_child($item, 'cell', NULL, true);
            $c11 = xml_add_child($item, 'cell', NULL, true);
            $c12 = xml_add_child($item, 'cell', NULL, true);
            $c13 = xml_add_child($item, 'cell', NULL, true);
            $c14 = xml_add_child($item, 'cell', NULL, true);
            $c15 = xml_add_child($item, 'cell', NULL, true);
            $c16 = xml_add_child($item, 'cell', NULL, true);
            $c17 = xml_add_child($item, 'cell', NULL, true);
            $c18 = xml_add_child($item, 'cell', NULL, true);
            $c19 = xml_add_child($item, 'cell', NULL, true);
            $c20 = xml_add_child($item, 'cell', NULL, true);
            $c21 = xml_add_child($item, 'cell', NULL, true);
            $c22 = xml_add_child($item, 'cell', NULL, true);
            $c23 = xml_add_child($item, 'cell', NULL, true);
            $c24 = xml_add_child($item, 'cell', NULL, true);
            $c25 = xml_add_child($item, 'cell', NULL, true); //20180130
            $c26 = xml_add_child($item, 'cell', NULL, true);
            $c27 = xml_add_child($item, 'cell', NULL, true);
            $c28 = xml_add_child($item, 'cell', NULL, true);
            $c29 = xml_add_child($item, 'cell', NULL, true);

        }
        foreach ($query->result_array() as $row5) {
            $dob = $row5['birthday'];
            if (!isset($row5['birthday'])) {
                $dob = "0000-00-00";
            }
            $rday = $row5['register_day'];
            if (!isset($row5['register_day'])) {
                $rday = "";
            }
            if ($row5['register_day'] == "0000-00-00") {
                $rday = "";
            }
            $withdday = $row5['withd_day'];
            if (!isset($row5['withd_day'])) {
                $withdday = "";
            }
            if ($row5['withd_day'] == "0000-00-00") {
                $withdday = "";
            }
            $item = xml_add_child($rows, 'row', NULL, true);
            xml_add_attribute($item, 'id', $row5['students_no']);
            $c0 = xml_add_child($item, 'cell', $row5['students_no'], true);
            $c1 = xml_add_child($item, 'cell', $row5['firstname'], true);
            $c2 = xml_add_child($item, 'cell', $row5['lastname'], true);
            $c3 = xml_add_child($item, 'cell', $row5['nickname'], true);
            $c4 = xml_add_child($item, 'cell', $dob, true);
            $c5 = xml_add_child($item, 'cell', $row5['progress'], true);
            $c6 = xml_add_child($item, 'cell', $row5['note'], true);
            $c7 = xml_add_child($item, 'cell', $row5['country'], true);
            $c8 = xml_add_child($item, 'cell', $row5['cellphone'], true);
            $c9 = xml_add_child($item, 'cell', $row5['cellphone2'], true);
            $c10 = xml_add_child($item, 'cell', $row5['email'], true);
            $c11 = xml_add_child($item, 'cell', $row5['email2'], true);
            $c12 = xml_add_child($item, 'cell', $row5['address1'], true);
            $c13 = xml_add_child($item, 'cell', $row5['address2'], true);
            $c14 = xml_add_child($item, 'cell', $row5['items'], true);
            $c15 = xml_add_child($item, 'cell', $row5['preschool'], true);
            $c16 = xml_add_child($item, 'cell', $row5['transfer'], true);
            $c17 = xml_add_child($item, 'cell', $row5['student_ID'], true);
            $c18 = xml_add_child($item, 'cell', $row5['gender'], true);
            $c19 = xml_add_child($item, 'cell', $row5['emergencyphone'], true);
            $c20 = xml_add_child($item, 'cell', $row5['emergencyphone2'], true);
            $c21 = xml_add_child($item, 'cell', $row5['etc_memo'], true);
            $c22 = xml_add_child($item, 'cell', $rday, true);
            $c23 = xml_add_child($item, 'cell', $row5['memo'], true);
            $c24 = xml_add_child($item, 'cell', $row5['user_ID'], true);
            $c25 = xml_add_child($item, 'cell', $row5['probation'], true);
            $c26 = xml_add_child($item, 'cell', $row5['probation2'], true);
            $c27 = xml_add_child($item, 'cell', $row5['probation3'], true);
            $c27 = xml_add_child($item, 'cell', $withdday, true);
            $c28 = xml_add_child($item, 'cell', $row5['complete'], true);
            //20180130
        }
        return xml_print($dom, true);
    }



    function setStudent($params)
    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $rowId = $params["ids"];



        $this->newId = $rowId;



        $mode = $params[$rowId . "_!nativeeditor_status"];



        switch ($mode) {



            case "inserted":

                $action = $this->add_row_students($rowId, $params);

                break;



            case "deleted":

                $action = $this->delete_row_students($rowId, $params);

                break;



            case "updated":

                $action = $this->update_row_student($rowId, $params);

                break;



        }



        $action2 = xml_add_child($data, 'action', $action, false);



        xml_add_attribute($action2, 'type', $action);



        xml_add_attribute($action2, 'sid', $rowId);



        xml_add_attribute($action2, 'tid', $this->newId);



        return xml_print($dom, true);



    }



    function add_row_students($rowId, $params)

    {



        $userid = explode("-", $params[$rowId . "_c4"]);



        $insdata = array(



            'firstname' => iconv("UTF-8", "CP949", $params[$rowId . "_c1"]),



            'lastname' => iconv("UTF-8", "CP949", $params[$rowId . "_c2"]),



            'nickname' => iconv("UTF-8", "CP949", $params[$rowId . "_c3"]),



            'birthday' => $params[$rowId . "_c4"],



            'progress' => $params[$rowId . "_c5"],



            'note' => iconv("UTF-8", "CP949", $params[$rowId . "_c6"]),



            'country' => iconv("UTF-8", "CP949", $params[$rowId . "_c7"]),



            'cellphone' => iconv("UTF-8", "CP949", $params[$rowId . "_c8"]),



            'cellphone2' => iconv("UTF-8", "CP949", $params[$rowId . "_c9"]),



            'email' => iconv("UTF-8", "CP949", $params[$rowId . "_c10"]),



            'email2' => iconv("UTF-8", "CP949", $params[$rowId . "_c11"]),



            'address1' => iconv("UTF-8", "CP949", $params[$rowId . "_c12"]),



            'address2' => iconv("UTF-8", "CP949", $params[$rowId . "_c13"]),



            'items' => $params[$rowId . "_c14"],



            'preschool' => iconv("UTF-8", "CP949", $params[$rowId . "_c15"]),



            'transfer' => iconv("UTF-8", "CP949", $params[$rowId . "_c16"]),



            'student_ID' => $params[$rowId . "_c17"],



            'gender' => $params[$rowId . "_c18"],



            'emergencyphone' => iconv("UTF-8", "CP949", $params[$rowId . "_c19"]),



            'emergencyphone2' => iconv("UTF-8", "CP949", $params[$rowId . "_c20"]),



            'etc_memo' => iconv("UTF-8", "CP949", $params[$rowId . "_c21"]),



            'register_day' => $params[$rowId . "_c22"],



            'memo' => iconv("UTF-8", "CP949", $params[$rowId . "_c23"]),



            'user_ID' => strtolower(str_replace(' ', '', $params[$rowId . "_c1"])) . $userid[1] . $userid[2],



            'passw' => "4327ae51cf1f9c07bb1096590789ee54a3ef2ff49129722dc07036b58be68e61:Sb",



            'writer' => $this->session->userdata('ALISESS_USERNAME')





        );  //default password : ali123





        $this->db->insert('ali_students', $insdata);



        $this->newId = $this->db->insert_id();



        return "inserted";






    }




    function update_row_student($rowId, $params)

    {



        $updatedata = array(



            'firstname' => iconv("UTF-8", "CP949", $params[$rowId . "_c1"]),



            'lastname' => iconv("UTF-8", "CP949", $params[$rowId . "_c2"]),



            'nickname' => iconv("UTF-8", "CP949", $params[$rowId . "_c3"]),



            'birthday' => $params[$rowId . "_c4"],



            'progress' => iconv("UTF-8", "CP949", $params[$rowId . "_c5"]),



            'note' => iconv("UTF-8", "CP949", $params[$rowId . "_c6"]),



            'country' => iconv("UTF-8", "CP949", $params[$rowId . "_c7"]),



            'cellphone' => iconv("UTF-8", "CP949", $params[$rowId . "_c8"]),



            'cellphone2' => iconv("UTF-8", "CP949", $params[$rowId . "_c9"]),



            'email' => iconv("UTF-8", "CP949", $params[$rowId . "_c10"]),



            'email2' => iconv("UTF-8", "CP949", $params[$rowId . "_c11"]),



            'address1' => iconv("UTF-8", "CP949", $params[$rowId . "_c12"]),



            'address2' => iconv("UTF-8", "CP949", $params[$rowId . "_c13"]),



            'items' => $params[$rowId . "_c14"],



            'preschool' => iconv("UTF-8", "CP949", $params[$rowId . "_c15"]),



            'transfer' => iconv("UTF-8", "CP949", $params[$rowId . "_c16"]),



            'student_ID' => $params[$rowId . "_c17"],



            'gender' => $params[$rowId . "_c18"],



            'emergencyphone' => iconv("UTF-8", "CP949", $params[$rowId . "_c19"]),



            'emergencyphone2' => iconv("UTF-8", "CP949", $params[$rowId . "_c20"]),



            'etc_memo' => iconv("UTF-8", "CP949", $params[$rowId . "_c21"]),



            'register_day' => $params[$rowId . "_c22"],



            'memo' => iconv("UTF-8", "CP949", $params[$rowId . "_c23"]),



            'user_ID' => iconv("UTF-8", "CP949", $params[$rowId . "_c24"]),



            'writer' => $this->session->userdata('ALISESS_USERNAME'),

            //20180130
            'probation' => iconv("UTF-8", "CP949", $params[$rowId . "_c25"]),

            'probation2' => iconv("UTF-8", "CP949", $params[$rowId . "_c26"]),

            'probation3' => iconv("UTF-8", "CP949", $params[$rowId . "_c27"]),


            'withd_day' => $params[$rowId . "_c28"],

            'complete' => iconv("UTF-8", "CP949", $params[$rowId . "_c29"])
        );



        $this->db->set('modified', 'now()', FALSE);



        $this->db->where('students_no', $rowId);



        $this->db->update("ali_students", $updatedata);



        return "updated";



    }



    function delete_row_students($rowId, $params)

    {



        $this->db->where('students_no', $rowId);



        $this->db->delete('ali_students');



        return "deleted";



    }






    function getFamilies($params)



    {



        $this->load->helper('xml');



        $sql = "";

        $wheresql = "";



        $columns = array("name", "birthday", "memo");



        if (isset($params["orderby"])) {



            if ($params["direct"] == 'des')



                $direct = "DESC";



            else



                $direct = "ASC";



            $sql = " Order by " . $columns[$params["orderby"]] . " " . $direct;



        } else {



            $sql = " Order by regdate";



        }





        if (isset($params["sno"])) {



            $wheresql = " where students_no=" . $params['sno'];



        }





        $posStart = "0";



        if (isset($params["posStart"]))



            $posStart = $params['posStart'];





        $sql = "select family_no, name,birthday,memo,regdate,students_no from ali_student_family " . $wheresql . " " . $sql;



        $query = $this->db->query($sql);



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');



        xml_add_attribute($rows, 'total_count', $query->num_rows());



        xml_add_attribute($rows, 'pos', $posStart);



        foreach ($query->result_array() as $row5) {



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $row5['family_no']);



            $c1 = xml_add_child($item, 'cell', $row5['name'], true);



            $c2 = xml_add_child($item, 'cell', $row5['birthday'], true);



            $c3 = xml_add_child($item, 'cell', $row5['memo'], true);



            $c5 = xml_add_child($item, 'cell', $row5['regdate'], true);



            $c6 = xml_add_child($item, 'cell', $row5['students_no'], true);



        }





        return xml_print($dom, true);



    }



    function setFamily($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $rowId = $params["ids"];



        $this->newId = $rowId;



        $mode = $params[$rowId . "_!nativeeditor_status"];



        switch ($mode) {



            case "inserted":

                $action = $this->add_row_family($rowId, $params);

                break;



            case "deleted":

                $action = $this->delete_row_family($rowId, $params);

                break;



            case "updated":

                $action = $this->update_row_family($rowId, $params);

                break;



        }



        $action2 = xml_add_child($data, 'action', $action, false);



        xml_add_attribute($action2, 'type', $action);



        xml_add_attribute($action2, 'sid', $rowId);



        xml_add_attribute($action2, 'tid', $this->newId);



        return xml_print($dom, true);



    }



    function add_row_family($rowId, $params)

    {



        $insdata = array(



            'name' => iconv("UTF-8", "CP949", $params[$rowId . "_c0"]),



            'birthday' => $params[$rowId . "_c1"],



            'memo' => iconv("UTF-8", "CP949", $params[$rowId . "_c2"]),



            'students_no' => $params[$rowId . "_c4"]



        );



        $this->db->set('regdate', 'now()', FALSE);



        $this->db->insert('ali_student_family', $insdata);



        $this->newId = $this->db->insert_id();



        return "inserted";



    }



    function update_row_family($rowId, $params)

    {



        $updatedata = array(



            'name' => iconv("UTF-8", "CP949", $params[$rowId . "_c0"]),



            'birthday' => $params[$rowId . "_c1"],



            'memo' => iconv("UTF-8", "CP949", $params[$rowId . "_c2"])



        );



        $this->db->set('regdate', 'now()', FALSE);



        $this->db->where('family_no', $rowId);



        $this->db->update("ali_student_family", $updatedata);



        return "updated";



    }



    function delete_row_family($rowId, $params)

    {



        $this->db->where('family_no', $rowId);



        $this->db->delete('ali_student_family');



        return "deleted";



    }





    function getConsults($params)



    {



        $this->load->helper('xml');



        $sql = "";

        $wheresql = "";



        $columns = array("recordday", "memo", "writer", "students_no");



        if (isset($params["orderby"])) {



            if ($params["direct"] == 'des')



                $direct = "DESC";



            else



                $direct = "ASC";



            $sql = " Order by " . $columns[$params["orderby"]] . " " . $direct;



        } else {



            $sql = " Order by consult_no desc";



        }





        $posStart = "0";



        if (isset($params["posStart"]))



            $posStart = $params['posStart'];





        $sql = "SELECT consult_no, recordday, memo, writer, students_no FROM ali_student_consult where students_no=" . $params['sno'] . " " . $sql;



        $query = $this->db->query($sql);



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');



        xml_add_attribute($rows, 'total_count', $query->num_rows());



        xml_add_attribute($rows, 'pos', $posStart);



        foreach ($query->result_array() as $row5) {



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $row5['consult_no']);



            $c1 = xml_add_child($item, 'cell', $row5['recordday'], true);



            $c2 = xml_add_child($item, 'cell', $row5['memo'], true);



            $c3 = xml_add_child($item, 'cell', $row5['writer'], true);



            $c5 = xml_add_child($item, 'cell', $row5['students_no'], true);



        }





        return xml_print($dom, true);



    }



    function setConsult($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $rowId = $params["ids"];



        $this->newId = $rowId;



        $mode = $params[$rowId . "_!nativeeditor_status"];



        switch ($mode) {



            case "inserted":

                $action = $this->add_row_consult($rowId, $params);

                break;



            case "deleted":

                $action = $this->delete_row_consult($rowId, $params);

                break;



            case "updated":

                $action = $this->update_row_consult($rowId, $params);

                break;



        }



        $action2 = xml_add_child($data, 'action', $action, false);



        xml_add_attribute($action2, 'type', $action);



        xml_add_attribute($action2, 'sid', $rowId);



        xml_add_attribute($action2, 'tid', $this->newId);



        return xml_print($dom, true);



    }



    function add_row_consult($rowId, $params)

    {



        $insdata = array(



            'recordday' => $params[$rowId . "_c0"],



            'memo' => iconv("UTF-8", "CP949", $params[$rowId . "_c1"]),



            'writer' => $this->session->userdata('ALISESS_USERNAME'),



            'students_no' => $params[$rowId . "_c3"]



        );



        $this->db->set('regdate', 'now()', FALSE);



        $this->db->insert('ali_student_consult', $insdata);



        $this->newId = $this->db->insert_id();



        return "inserted";



    }



    function update_row_consult($rowId, $params)

    {



        $updatedata = array(



            'recordday' => $params[$rowId . "_c0"],



            'memo' => iconv("UTF-8", "CP949", $params[$rowId . "_c1"]),



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('regdate', 'now()', FALSE);



        $this->db->where('consult_no', $rowId);



        $this->db->update("ali_student_consult", $updatedata);



        return "updated";



    }



    function delete_row_consult($rowId, $params)

    {



        $this->db->where('consult_no', $rowId);



        $this->db->delete('ali_student_consult');



        return "deleted";



    }





    function menucontext($params)

    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $menu = xml_add_child($dom, 'menu');

        xml_add_attribute($menu, 'id', "0");



        $item0 = xml_add_child($menu, 'item', NULL, true);

        xml_add_attribute($item0, 'text', "add");

        xml_add_attribute($item0, 'img', "");

        xml_add_attribute($item0, 'id', "edit_add");



        $item1 = xml_add_child($menu, 'item', NULL, true);

        xml_add_attribute($item1, 'text', "remove");

        xml_add_attribute($item1, 'img', "");

        xml_add_attribute($item1, 'id', "edit_remove");



        return xml_print($dom, true);



    }





    function menuRcordContext($params)

    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $menu = xml_add_child($dom, 'menu');

        xml_add_attribute($menu, 'id', "0");



        $item0 = xml_add_child($menu, 'item', NULL, true);

        xml_add_attribute($item0, 'text', "Add Trimester");

        xml_add_attribute($item0, 'img', "");

        xml_add_attribute($item0, 'id', "addTrimester");



        $item1 = xml_add_child($menu, 'item', NULL, true);

        xml_add_attribute($item1, 'text', "Add Placement");

        xml_add_attribute($item1, 'img', "");

        xml_add_attribute($item1, 'id', "addPlacement");



        $item2 = xml_add_child($menu, 'item', NULL, true);

        xml_add_attribute($item2, 'text', "Add Finalization");

        xml_add_attribute($item2, 'img', "");

        xml_add_attribute($item2, 'id', "addFinalization");



        $item3 = xml_add_child($menu, 'item', NULL, true);

        xml_add_attribute($item3, 'text', "Remove");

        xml_add_attribute($item3, 'img', "");

        xml_add_attribute($item3, 'id', "removeRow");



        return xml_print($dom, true);



    }





    function menuRosterContext($params)

    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $menu = xml_add_child($dom, 'menu');

        xml_add_attribute($menu, 'id', "0");



        $item2 = xml_add_child($menu, 'item', NULL, true);

        xml_add_attribute($item2, 'text', "Remove");

        xml_add_attribute($item2, 'img', "");

        xml_add_attribute($item2, 'id', "removeRow");



        return xml_print($dom, true);



    }





    function getAcademicRecords($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');



        $head = xml_add_child($rows, 'head', NULL, true);





        $colm1 = xml_add_child($head, 'column', "Year", true);



        xml_add_attribute($colm1, 'id', "schoolyear");



        xml_add_attribute($colm1, 'width', "60");



        xml_add_attribute($colm1, 'type', "co");



        xml_add_attribute($colm1, 'align', "center");



        xml_add_attribute($colm1, 'color', "white");



        xml_add_attribute($colm1, 'sort', "str");



        $sql = "SELECT schoolyear FROM ali_gradingperiod GROUP BY schoolyear ";



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row1) {



            $o1 = xml_add_child($colm1, 'option', $row1["schoolyear"], false);



            xml_add_attribute($o1, 'value', $row1["schoolyear"]);



        }





        $colm2 = xml_add_child($head, 'column', "Trimester", true);



        xml_add_attribute($colm2, 'id', "trimester");



        xml_add_attribute($colm2, 'width', "60");



        xml_add_attribute($colm2, 'type', "co");



        xml_add_attribute($colm2, 'align', "center");



        xml_add_attribute($colm2, 'color', "white");



        xml_add_attribute($colm2, 'sort', "str");



        $sql = "SELECT gradingperiod FROM ali_gradingperiod GROUP BY gradingperiod ";



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row2) {



            $o2 = xml_add_child($colm2, 'option', $row2["gradingperiod"], false);



            xml_add_attribute($o2, 'value', $row2["gradingperiod"]);



        }





        $colm3 = xml_add_child($head, 'column', "Level", true);



        xml_add_attribute($colm3, 'id', "level");



        xml_add_attribute($colm3, 'width', "100");



        xml_add_attribute($colm3, 'type', "co");



        xml_add_attribute($colm3, 'align', "center");



        xml_add_attribute($colm3, 'color', "white");



        xml_add_attribute($colm3, 'sort', "int");



        $sql = "SELECT levelname,levelvalue FROM ali_level GROUP BY levelname ";



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row3) {



            $o3 = xml_add_child($colm3, 'option', $row3["levelname"], false);



            xml_add_attribute($o3, 'value', $row3["levelvalue"]);



        }





        $colm4 = xml_add_child($head, 'column', "Session", true);



        xml_add_attribute($colm4, 'id', "session");



        xml_add_attribute($colm4, 'width', "60");



        xml_add_attribute($colm4, 'type', "co");



        xml_add_attribute($colm4, 'align', "center");



        xml_add_attribute($colm4, 'color', "white");



        xml_add_attribute($colm4, 'sort', "int");



        $o5 = xml_add_child($colm4, 'option', "AM", false);

        xml_add_attribute($o5, 'value', "1");



        $o5 = xml_add_child($colm4, 'option', "AFT", false);

        xml_add_attribute($o5, 'value', "2");



        $o5 = xml_add_child($colm4, 'option', "PM", false);

        xml_add_attribute($o5, 'value', "3");



        $o5 = xml_add_child($colm4, 'option', "None", false);

        xml_add_attribute($o5, 'value', "4");





        $colm5 = xml_add_child($head, 'column', "Att./Test Score", true);



        xml_add_attribute($colm5, 'id', "att_score");



        xml_add_attribute($colm5, 'width', "80");



        xml_add_attribute($colm5, 'type', "ed");



        xml_add_attribute($colm5, 'align', "right");



        xml_add_attribute($colm5, 'color', "white");



        xml_add_attribute($colm5, 'sort', "str");





        $colm6 = xml_add_child($head, 'column', "LS", true);



        xml_add_attribute($colm6, 'id', "ls_score");



        xml_add_attribute($colm6, 'width', "50");



        xml_add_attribute($colm6, 'type', "ed");



        xml_add_attribute($colm6, 'align', "right");



        xml_add_attribute($colm6, 'color', "white");



        xml_add_attribute($colm6, 'sort', "str");





        $colm7 = xml_add_child($head, 'column', "RW", true);



        xml_add_attribute($colm7, 'id', "rw_score");



        xml_add_attribute($colm7, 'width', "50");



        xml_add_attribute($colm7, 'type', "ed");



        xml_add_attribute($colm7, 'align', "right");



        xml_add_attribute($colm7, 'color', "white");



        xml_add_attribute($colm7, 'sort', "str");





        $colm8 = xml_add_child($head, 'column', "TOEFL", true);



        xml_add_attribute($colm8, 'id', "toefl_score");



        xml_add_attribute($colm8, 'width', "50");



        xml_add_attribute($colm8, 'type', "ed");



        xml_add_attribute($colm8, 'align', "right");



        xml_add_attribute($colm8, 'color', "white");



        xml_add_attribute($colm8, 'sort', "str");





        $colm9 = xml_add_child($head, 'column', "Date", true);



        xml_add_attribute($colm9, 'id', "plt_date");



        xml_add_attribute($colm9, 'width', "80");



        xml_add_attribute($colm9, 'type', "dhxCalendar");



        xml_add_attribute($colm9, 'align', "right");



        xml_add_attribute($colm9, 'color', "white");



        xml_add_attribute($colm9, 'sort', "date");





        $colm11 = xml_add_child($head, 'column', "Note", true);



        xml_add_attribute($colm11, 'id', "plt_note");



        xml_add_attribute($colm11, 'width', "140");



        xml_add_attribute($colm11, 'type', "txt");



        xml_add_attribute($colm11, 'align', "right");



        xml_add_attribute($colm11, 'color', "white");



        xml_add_attribute($colm11, 'sort', "str");





        $colm12 = xml_add_child($head, 'column', "Writer", true);



        xml_add_attribute($colm12, 'id', "writer");



        xml_add_attribute($colm12, 'width', "60");



        xml_add_attribute($colm12, 'type', "ro");



        xml_add_attribute($colm12, 'align', "right");



        xml_add_attribute($colm12, 'color', "white");



        xml_add_attribute($colm12, 'sort', "str");





        $colm13 = xml_add_child($head, 'column', "students_no", true);



        xml_add_attribute($colm13, 'id', "students_no");



        xml_add_attribute($colm13, 'width', "0");



        xml_add_attribute($colm13, 'type', "ro");



        xml_add_attribute($colm13, 'align', "right");



        xml_add_attribute($colm13, 'color', "white");



        xml_add_attribute($colm13, 'sort', "int");





        $colm14 = xml_add_child($head, 'column', "gubun", true);



        xml_add_attribute($colm14, 'id', "gubun");



        xml_add_attribute($colm14, 'width', "0");



        xml_add_attribute($colm14, 'type', "ro");



        xml_add_attribute($colm14, 'align', "right");



        xml_add_attribute($colm14, 'color', "white");



        xml_add_attribute($colm14, 'sort', "int");





        $sql = "SELECT no,students_no,schoolyear,trimester,level,session,att_score,ls_score,rw_score,toefl_score,gubun,plt_date,plt_score,plt_note,writer FROM ali_academicrecords where students_no=" . $params['sno'] . " order by schoolyear,trimester,gubun,level";



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row5) {



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $row5['no']);



            if ($row5['gubun'] == 1) {



                xml_add_attribute($item, 'style', "background-color:#DCDCDC;");



            }



            $c0 = xml_add_child($item, 'cell', $row5['schoolyear'], true);



            $c1 = xml_add_child($item, 'cell', $row5['trimester'], true);



            $c2 = xml_add_child($item, 'cell', $row5['level'], true);



            $c3 = xml_add_child($item, 'cell', $row5['session'], true);



            if ($row5['gubun'] == 1) { //??? ???????..



                $c4 = xml_add_child($item, 'cell', $row5['plt_score'], true);



            } else {



                $c4 = xml_add_child($item, 'cell', $row5['att_score'], true);



            }



            $c5 = xml_add_child($item, 'cell', $row5['ls_score'], true);



            $c6 = xml_add_child($item, 'cell', $row5['rw_score'], true);



            $c7 = xml_add_child($item, 'cell', $row5['toefl_score'], true);



            if ($row5['gubun'] == 1) {



                $c8 = xml_add_child($item, 'cell', $row5['plt_date'], true);



            } else {



                $c8 = xml_add_child($item, 'cell', $row5['plt_date'], true);



            }



            $c9 = xml_add_child($item, 'cell', $row5['plt_note'], true);



            $c10 = xml_add_child($item, 'cell', $row5['writer'], true);



            $c11 = xml_add_child($item, 'cell', $row5['students_no'], true);



            $c12 = xml_add_child($item, 'cell', $row5['gubun'], true);



        }





        return xml_print($dom, true);



    }



    function setRecord($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $rowId = $params["ids"];



        $this->newId = $rowId;



        $mode = $params[$rowId . "_!nativeeditor_status"];



        switch ($mode) {



            case "inserted":

                $action = $this->add_row_record($rowId, $params);

                break;



            case "deleted":

                $action = $this->delete_row_record($rowId, $params);

                break;



            case "updated":

                $action = $this->update_row_record($rowId, $params);

                break;



        }



        $action2 = xml_add_child($data, 'action', $action, false);



        xml_add_attribute($action2, 'type', $action);



        xml_add_attribute($action2, 'sid', $rowId);



        xml_add_attribute($action2, 'tid', $this->newId);



        return xml_print($dom, true);



    }



    function add_row_record($rowId, $params)

    {



        $attortest = array();



        if ($params[$rowId . "_c12"] == 1) {



            $attortest = array('plt_score' => $params[$rowId . "_c4"]);



        }



        if ($params[$rowId . "_c12"] == 2) {



            $attortest = array('att_score' => $params[$rowId . "_c4"]);



        }



        $maindata = array(



            'schoolyear' => $params[$rowId . "_c0"],



            'trimester' => $params[$rowId . "_c1"],



            'level' => $params[$rowId . "_c2"],



            'session' => $params[$rowId . "_c3"],



            'ls_score' => $params[$rowId . "_c5"],



            'rw_score' => $params[$rowId . "_c6"],



            'toefl_score' => $params[$rowId . "_c7"],



            'plt_date' => $params[$rowId . "_c8"],



            'plt_note' => $params[$rowId . "_c9"],



            'writer' => $this->session->userdata('ALISESS_USERNAME'),



            'students_no' => $params[$rowId . "_c11"],



            'gubun' => $params[$rowId . "_c12"]



        );





        $insdata = array_merge($attortest, $maindata);





        $this->db->set('created', 'now()', FALSE);



        $this->db->insert('ali_academicrecords', $insdata);



        $this->newId = $this->db->insert_id();



        return "inserted";



    }



    function update_row_record($rowId, $params)

    {



        $attortest = array();

        $attorgubun = array();



        if ($params[$rowId . "_c12"] == 1) {



            $attortest = array('plt_score' => $params[$rowId . "_c4"]);



        }



        if ($params[$rowId . "_c12"] == 2) {



            $attortest = array('att_score' => $params[$rowId . "_c4"]);



        }



        if ($params[$rowId . "_c2"] == 7) {



            $attorgubun = array('gubun' => 1);



        } else {



            $attorgubun = array('gubun' => 2);



        }





        $maindata = array(



            'schoolyear' => $params[$rowId . "_c0"],



            'trimester' => $params[$rowId . "_c1"],



            'level' => $params[$rowId . "_c2"],



            'session' => $params[$rowId . "_c3"],



            'ls_score' => $params[$rowId . "_c5"],



            'rw_score' => $params[$rowId . "_c6"],



            'toefl_score' => $params[$rowId . "_c7"],



            'plt_date' => $params[$rowId . "_c8"],



            'plt_note' => $params[$rowId . "_c9"],



            'writer' => $this->session->userdata('ALISESS_USERNAME'),



            'students_no' => $params[$rowId . "_c11"]



        );



        $updatedata = array_merge($attortest, $attorgubun, $maindata);



        $this->db->set('modified', 'now()', FALSE);



        $this->db->where('no', $rowId);



        $this->db->update("ali_academicrecords", $updatedata);



        return "updated";



    }



    function delete_row_record($rowId, $params)

    {



        $this->db->where('no', $rowId);



        $this->db->delete('ali_academicrecords');



        return "deleted";



    }





    function menuFinanceContext($params)

    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $menu = xml_add_child($dom, 'menu');

        xml_add_attribute($menu, 'id', "0");



        $item0 = xml_add_child($menu, 'item', NULL, true);

        xml_add_attribute($item0, 'text', "Add Payment");

        xml_add_attribute($item0, 'img', "");

        xml_add_attribute($item0, 'id', "addPayment");



        $item2 = xml_add_child($menu, 'item', NULL, true);

        xml_add_attribute($item2, 'text', "Remove");

        xml_add_attribute($item2, 'img', "");

        xml_add_attribute($item2, 'id', "removeRow");



        return xml_print($dom, true);



    }



    function getFinance($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');



        $head = xml_add_child($rows, 'head', NULL, true);





        $colm1 = xml_add_child($head, 'column', "Year", true);



        xml_add_attribute($colm1, 'id', "schoolyear");



        xml_add_attribute($colm1, 'width', "50");



        xml_add_attribute($colm1, 'type', "co");



        xml_add_attribute($colm1, 'align', "center");



        xml_add_attribute($colm1, 'color', "white");



        xml_add_attribute($colm1, 'sort', "str");



        $sql = "SELECT schoolyear FROM ali_gradingperiod GROUP BY schoolyear ";



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row1) {



            $o1 = xml_add_child($colm1, 'option', $row1["schoolyear"], false);



            xml_add_attribute($o1, 'value', $row1["schoolyear"]);



        }





        $colm2 = xml_add_child($head, 'column', "Trimester", true);



        xml_add_attribute($colm2, 'id', "trimester");



        xml_add_attribute($colm2, 'width', "70");



        xml_add_attribute($colm2, 'type', "co");



        xml_add_attribute($colm2, 'align', "center");



        xml_add_attribute($colm2, 'color', "white");



        xml_add_attribute($colm2, 'sort', "str");



        $sql = "SELECT gradingperiod FROM ali_gradingperiod GROUP BY gradingperiod ";



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row2) {



            $o2 = xml_add_child($colm2, 'option', $row2["gradingperiod"], false);



            xml_add_attribute($o2, 'value', $row2["gradingperiod"]);



        }





        $colm3 = xml_add_child($head, 'column', "PaymentDate", true);



        xml_add_attribute($colm3, 'id', "paiddate");



        xml_add_attribute($colm3, 'width', "90");



        xml_add_attribute($colm3, 'type', "dhxCalendar");



        xml_add_attribute($colm3, 'align', "center");



        xml_add_attribute($colm3, 'color', "white");



        xml_add_attribute($colm3, 'sort', "date");





        $colm4 = xml_add_child($head, 'column', "Description", true);



        xml_add_attribute($colm4, 'id', "description");



        xml_add_attribute($colm4, 'width', "200");



        xml_add_attribute($colm4, 'type', "ed");



        xml_add_attribute($colm4, 'align', "center");



        xml_add_attribute($colm4, 'color', "white");



        xml_add_attribute($colm4, 'sort', "str");





        $colm5 = xml_add_child($head, 'column', "Late Fees(non-refundable)", true);



        xml_add_attribute($colm5, 'id', "latefees");



        xml_add_attribute($colm5, 'width', "160");



        xml_add_attribute($colm5, 'type', "edn");



        xml_add_attribute($colm5, 'align', "right");



        xml_add_attribute($colm5, 'color', "white");



        xml_add_attribute($colm5, 'sort', "str");



        xml_add_attribute($colm5, 'format', "0.00");





        $colm6 = xml_add_child($head, 'column', "AmountPaid", true);



        xml_add_attribute($colm6, 'id', "amountpaid");



        xml_add_attribute($colm6, 'width', "80");



        xml_add_attribute($colm6, 'type', "edn");



        xml_add_attribute($colm6, 'align', "right");



        xml_add_attribute($colm6, 'color', "white");



        xml_add_attribute($colm6, 'sort', "str");



        xml_add_attribute($colm6, 'format', "0.00");





        $colm7 = xml_add_child($head, 'column', "Refunds", true);



        xml_add_attribute($colm7, 'id', "refunds");



        xml_add_attribute($colm7, 'width', "80");



        xml_add_attribute($colm7, 'type', "edn");



        xml_add_attribute($colm7, 'align', "right");



        xml_add_attribute($colm7, 'color', "white");



        xml_add_attribute($colm7, 'sort', "str");



        xml_add_attribute($colm7, 'format', "0.00");





        $colm8 = xml_add_child($head, 'column', "Method of Payment", true);



        xml_add_attribute($colm8, 'id', "method");



        xml_add_attribute($colm8, 'width', "120");



        xml_add_attribute($colm8, 'type', "ed");



        xml_add_attribute($colm8, 'align', "left");



        xml_add_attribute($colm8, 'color', "white");



        xml_add_attribute($colm8, 'sort', "str");





        $colm9 = xml_add_child($head, 'column', "Notes", true);



        xml_add_attribute($colm9, 'id', "notes");



        xml_add_attribute($colm9, 'width', "200");



        xml_add_attribute($colm9, 'type', "txt");



        xml_add_attribute($colm9, 'align', "left");



        xml_add_attribute($colm9, 'color', "white");



        xml_add_attribute($colm9, 'sort', "str");





        $colm14 = xml_add_child($head, 'column', "Updated", true);



        xml_add_attribute($colm14, 'id', "updated");



        xml_add_attribute($colm14, 'width', "120");



        xml_add_attribute($colm14, 'type', "ro");



        xml_add_attribute($colm14, 'align', "right");



        xml_add_attribute($colm14, 'color', "white");



        xml_add_attribute($colm14, 'sort', "date");





        $colm12 = xml_add_child($head, 'column', "Writer", true);



        xml_add_attribute($colm12, 'id', "writer");



        xml_add_attribute($colm12, 'width', "80");



        xml_add_attribute($colm12, 'type', "ro");



        xml_add_attribute($colm12, 'align', "right");



        xml_add_attribute($colm12, 'color', "white");



        xml_add_attribute($colm12, 'sort', "str");





        $colm13 = xml_add_child($head, 'column', "students_no", true);



        xml_add_attribute($colm13, 'id', "students_no");



        xml_add_attribute($colm13, 'width', "0");



        xml_add_attribute($colm13, 'type', "ro");



        xml_add_attribute($colm13, 'align', "right");



        xml_add_attribute($colm13, 'color', "white");



        xml_add_attribute($colm13, 'sort', "int");





        $sql = "SELECT no,schoolyear,trimester,paiddate,description,latefees,amountpaid,refunds,method,notes,updated,writer,students_no FROM ali_finance where students_no=" . $params['sno'] . " order by schoolyear,trimester,paiddate ASC";



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row5) {



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $row5['no']);



            $c0 = xml_add_child($item, 'cell', $row5['schoolyear'], true);



            $c1 = xml_add_child($item, 'cell', $row5['trimester'], true);



            $c2 = xml_add_child($item, 'cell', $row5['paiddate'], true);



            $c3 = xml_add_child($item, 'cell', $row5['description'], true);



            $c5 = xml_add_child($item, 'cell', $row5['latefees'], true);



            $c6 = xml_add_child($item, 'cell', $row5['amountpaid'], true);



            $c7 = xml_add_child($item, 'cell', $row5['refunds'], true);



            $c9 = xml_add_child($item, 'cell', $row5['method'], true);



            $c10 = xml_add_child($item, 'cell', $row5['notes'], true);



            $c11 = xml_add_child($item, 'cell', $row5['updated'], true);



            $c12 = xml_add_child($item, 'cell', $row5['writer'], true);



            $c13 = xml_add_child($item, 'cell', $row5['students_no'], true);





        }





        return xml_print($dom, true);



    }



    function setFinance($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $rowId = $params["ids"];



        $this->newId = $rowId;



        $mode = $params[$rowId . "_!nativeeditor_status"];



        switch ($mode) {



            case "inserted":

                $action = $this->add_row_finance($rowId, $params);

                break;



            case "deleted":

                $action = $this->delete_row_finance($rowId, $params);

                break;



            case "updated":

                $action = $this->update_row_finance($rowId, $params);

                break;



        }



        $action2 = xml_add_child($data, 'action', $action, false);



        xml_add_attribute($action2, 'type', $action);



        xml_add_attribute($action2, 'sid', $rowId);



        xml_add_attribute($action2, 'tid', $this->newId);



        return xml_print($dom, true);



    }



    function add_row_finance($rowId, $params)

    {



        $insdata = array(



            'schoolyear' => $params[$rowId . "_c0"],



            'trimester' => $params[$rowId . "_c1"],



            'paiddate' => $params[$rowId . "_c2"],



            'description' => $params[$rowId . "_c3"],



            'latefees' => $params[$rowId . "_c4"],



            'amountpaid' => $params[$rowId . "_c5"],



            'refunds' => $params[$rowId . "_c6"],



            'method' => $params[$rowId . "_c7"],



            'notes' => $params[$rowId . "_c8"],



            'writer' => $this->session->userdata('ALISESS_USERNAME'),



            'students_no' => $params[$rowId . "_c11"]



        );





        $this->db->set('updated', 'now()', FALSE);



        $this->db->insert('ali_finance', $insdata);



        $this->newId = $this->db->insert_id();



        return "inserted";



    }



    function update_row_finance($rowId, $params)

    {



        $updatedata = array(



            'schoolyear' => $params[$rowId . "_c0"],



            'trimester' => $params[$rowId . "_c1"],



            'paiddate' => $params[$rowId . "_c2"],



            'description' => $params[$rowId . "_c3"],



            'latefees' => $params[$rowId . "_c4"],



            'amountpaid' => $params[$rowId . "_c5"],



            'refunds' => $params[$rowId . "_c6"],



            'method' => $params[$rowId . "_c7"],



            'notes' => $params[$rowId . "_c8"],



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('updated', 'now()', FALSE);



        $this->db->where('no', $rowId);



        $this->db->where('students_no', $params[$rowId . "_c11"]);



        $this->db->update("ali_finance", $updatedata);



        return "updated";



    }



    function delete_row_finance($rowId, $params)

    {



        $this->db->where('no', $rowId);



        $this->db->where('students_no', $params[$rowId . "_c11"]);



        $this->db->delete('ali_finance');



        return "deleted";



    }


    function getTri($stdno)



    {



        $arrtri = array();



        $data[] = array();



        $sql = "SELECT schoolyear, trimester FROM ali_roster WHERE students_no=" . $stdno . " group by schoolyear, trimester";



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row5) {



            $arrtri['tri'] = $row5['trimester'];



            $arrtri['year'] = $row5['schoolyear'];



            $data[] = $arrtri;



        }



        return $data;



    }





    function getCla($stdno, $year, $tri)



    {



        $arrtri = array();



        $data[] = array();



        $sql = "SELECT classtype, class_no FROM ali_roster WHERE students_no=" . $stdno . " and schoolyear='" . $year . "' and trimester='" . $tri . "' group by classtype, class_no";



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row5) {



            $arrtri['classtype'] = $row5['classtype'];



            $arrtri['class_no'] = $row5['class_no'];



            $data[] = $arrtri;



        }



        return $data;



    }

    function getAllAttSheet($params)

    {

        $arratt = array();

        $ssss = array();

        $sql = "select a.no,a.class_no,CONCAT(a.lastname,', ',a.firstname) AS fullname, a.students_no from ali_roster AS a inner join ali_students AS b on a.students_no=b.students_no where b.progress='r' and a.schoolyear='" . $params["vyear"] . "' and a.trimester='" . $params["vtrim"] . "' and a.level=" . $params["vlevel"] . " and a.session=" . $params["vsession"] . " and a.classtype='" . $params["vclasstype"] . "' order by CONCAT(a.lastname,', ',a.firstname)";

        $query = $this->db->query($sql);

        foreach ($query->result_array() as $row5) {

            $arratt["" . $row5['students_no'] . ""]['stdname'] = $row5['fullname'];



            $sql2 = "select class_no,student_no,marks,attendance_day from `ali_attendance_new` where student_no=" . $row5['students_no'] . " and class_no=" . $row5['class_no'] . " and DATE_FORMAT(attendance_day,'%Y/%m') between '" . $params["vyear"] . "/" . $params["vfrommon"] . "' and '" . $params["vyear"] . "/" . $params["vtomon"] . "' ";

            $query2 = $this->db->query($sql2);

            foreach ($query2->result_array() as $row1) {

                $ssss["" . $row1['attendance_day'] . ""]["" . $row5['students_no'] . ""] = $row1['marks'];

            }

        }



        $this->load->helper('xml');

        $dom = xml_dom();

        $rows = xml_add_child($dom, 'rows');



        $head = xml_add_child($rows,'head',NULL,true);

        $h0 = $this->getGridHeaderAtt($head,"Date","gno","80","ro","center","date","true","1");

        $subarr = array(); //header

        $stdlist = array(); $i=0;

        foreach ($arratt as $key => $val) {

            $subarr = $arratt[$key];

            $h1 = $this->getGridHeaderAtt($head,$subarr['stdname'], "studentsno", "80", "ro", "center", "str", "true", "0");

            $stdlist[$i] = $key;

            $i++;

        }



        $ai0 = xml_add_child($head,'afterInit',null,false);

        $frommon = date('F', mktime(0, 0, 0,$params["vfrommon"], 10));

        $tomon = date('F', mktime(0, 0, 0,$params["vtomon"], 10));

        $TITLE = $params["vtrim"]." Trimester ".$params["vyear"]." ".$frommon."-".$tomon."    ".$params["teacher"]."  Level ".$params["vlevel"]." ".$params["vclasstype"]." ".$params["vsession"];

        $ca0 = xml_add_child($ai0,'call',null,false);

        xml_add_attribute($ca0,'command',"attachHeader");

        $pa0 = xml_add_child($ca0,'param',$TITLE.",#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan",false);





        $atta = array(); $h=1;

        foreach ($ssss as $key => $val) {

            $atta = $ssss[$key];



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id',$h);

            $c0 = xml_add_child($item, 'cell',"".$key, true); //attendace day



            for($t=0;$t < count($stdlist); $t++){

                $c1 = xml_add_child($item, 'cell',$atta["".$stdlist[$t].""], true);

            }



            $h++;

        }

        return xml_print($dom, true);

    }



    function getAttSheet($params)
    {
        $arratt = array();
        $sql = "select a.no,a.class_no,CONCAT(a.lastname,', ',a.firstname) AS fullname, a.students_no from ali_roster AS a inner join ali_students AS b on a.students_no=b.students_no where b.progress='r' and a.schoolyear='" . $params["vyear"] . "' and a.trimester='" . $params["vtrim"] . "' and a.level=" . $params["vlevel"] . " and a.session=" . $params["vsession"] . " and a.classtype='" . $params["vclasstype"] . "' order by CONCAT(a.lastname,', ',a.firstname)";
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $row5) {
            $arratt["" . $row5['students_no'] . ""]['stdname'] = $row5['fullname'];
            $sql2 = "select class_no,student_no,marks,attendance_day from `ali_attendance_new` where student_no=" . $row5['students_no'] . " and class_no=" . $row5['class_no'] . " and DATE_FORMAT(attendance_day,'%Y/%m')='" . $params["vyear"] . "/" . $params["vmon"] . "'";
            $query2 = $this->db->query($sql2);
            foreach ($query2->result_array() as $row1) {
                $arratt["" . $row5['students_no'] . ""]['attd']["" . $row1['attendance_day'] . ""] = $row1['marks'];
            }
        }
        $this->load->helper('xml');
        $dom = xml_dom();
        $rows = xml_add_child($dom, 'rows');
        $i = 1;
        $subarr = array();
        foreach ($arratt as $key => $val) {
            $subarr = $arratt[$key];
            $item = xml_add_child($rows, 'row', NULL, true);
            xml_add_attribute($item, 'id', $key);
            $c0 = xml_add_child($item, 'cell', $i, true);
            $c1 = xml_add_child($item, 'cell', $subarr['stdname'], true);
            for ($d = 1; $d <= 41; $d++) {
                $dw = date('w', strtotime($params["vyear"] . "-" . $params["vmon"] . "-" . $d));
                if ($dw == 1 || $dw == 2 || $dw == 3 || $dw == 4) {
                    $vdate = date('Y-m-d', strtotime($params["vyear"] . "-" . $params["vmon"] . "-" . $d));
                    if (ISSET($subarr['attd'][$vdate])) {
                        $strv = $subarr['attd'][$vdate];
                        $c3 = xml_add_child($item, 'cell', substr($strv, 0, 1), true);
                        $c3 = xml_add_child($item, 'cell', substr($strv, 1, 1), true);
                    } else {
                        $c3 = xml_add_child($item, 'cell', '', true);
                        $c3 = xml_add_child($item, 'cell', '', true);
                        xml_add_attribute($item, 'rowspan', '3');
                    }
                }
            }
            $i++;
        }
        return xml_print($dom, true);
    }



    function getAttSheetNew($params)
    {
        $returnData[] = array();
        $returnData["C5"][0] = "";
        $returnData["C5"][1] = "";
        $returnData["C5"][2] = "";
        $returnData["C5"][3] = "";
        $returnData["C5"][4] = "";

        $arratt = array();
        $sql = "select a.no,a.class_no,CONCAT(a.lastname,', ',a.firstname) AS fullname, a.students_no from ali_roster AS a inner join ali_students AS b on a.students_no=b.students_no where b.progress='r' and a.schoolyear='" . $params["vyear"] . "' and a.trimester='" . $params["vtrim"] . "' and a.level=" . $params["vlevel"] . " and a.session=" . $params["vsession"] . " and a.classtype='" . $params["vclasstype"] . "' order by CONCAT(a.lastname,', ',a.firstname)";
        $query = $this->db->query($sql);
        foreach ($query->result_array() as $row5) {
            $arratt["" . $row5['students_no'] . ""]['stdname'] = $row5['fullname'];
            $sql2 = "select class_no,student_no,marks,attendance_day from ali_attendance_new where student_no=" . $row5['students_no'] . " and class_no=" . $row5['class_no'] . " and DATE_FORMAT(attendance_day,'%Y/%m')='" . $params["vyear"] . "/" . $params["vmon"] . "'";
            $query2 = $this->db->query($sql2);
            foreach ($query2->result_array() as $row1) {
                $arratt["" . $row5['students_no'] . ""]['attd']["" . $row1['attendance_day'] . ""] = $row1['marks'];
            }
        }

        $i = 1;
        $subarr = array();
        foreach ($arratt as $key => $val) {
            $subarr = $arratt[$key];

            $returnData["" . $key . ""][0] = $i;
            $returnData["" . $key . ""][1] = $subarr['stdname'];

            $w=2;
            for ($d = 1; $d <= 41; $d++) {
                $dw = date('w', strtotime($params["vyear"] . "-" . $params["vmon"] . "-" . $d));
                if ($dw == 1 || $dw == 2 || $dw == 3 || $dw == 4) {
                    $vdate = date('Y-m-d', strtotime($params["vyear"] . "-" . $params["vmon"] . "-" . $d));
                    if (ISSET($subarr['attd'][$vdate])) {
                        $strv = $subarr['attd'][$vdate];
                        $returnData["" . $key . ""][$w] = substr($strv, 0, 1);
                        $returnData["" . $key . ""][$w+1] = substr($strv, 1, 1);
                    } else {
                        $returnData["" . $key . ""][$w] = "";
                        $returnData["" . $key . ""][$w+1] = "";
                    }

                    $w=$w+2;
                }

            }
            $i++;
        }
        return $returnData;
    }





    function getConStudentList($params)

    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');





        $head = xml_add_child($rows, 'head', NULL, true);



        $colm = xml_add_child($head, 'column', "Student", true);



        xml_add_attribute($colm, 'id', "fullname");



        xml_add_attribute($colm, 'width', "160");



        xml_add_attribute($colm, 'type', "ro");



        xml_add_attribute($colm, 'align', "left");



        xml_add_attribute($colm, 'sort', "str");





        $colm = xml_add_child($head, 'column', "Email", true);



        xml_add_attribute($colm, 'id', "email");



        xml_add_attribute($colm, 'width', "160");



        xml_add_attribute($colm, 'type', "ro");



        xml_add_attribute($colm, 'align', "left");



        xml_add_attribute($colm, 'sort', "str");





        $sql1 = "select aa.students_no,CONCAT(aa.firstname,' ',aa.lastname) AS fullname,st.email from ali_roster as aa INNER JOIN ali_students AS st ON aa.students_no = st.students_no where st.progress='r' and aa.class_no=" . $params["class_no"] . " order by CONCAT(aa.lastname,', ',aa.firstname)";



        $query1 = $this->db->query($sql1);



        foreach ($query1->result_array() as $row5) {



            $item = xml_add_child($rows, 'row', NULL, true);



            xml_add_attribute($item, 'id', $row5["students_no"]);



            $c0 = xml_add_child($item, 'cell', $row5["fullname"], false);



            $c1 = xml_add_child($item, 'cell', $row5["email"], false);



        }



        return xml_print($dom, true);



    }





    function getSchoolAcct($ano)

    {



        $ret = array();



        $sql = "select no,schoolemail,schoolpassword from ali_user where no=" . $ano;



        $query = $this->db->query($sql);



        if ($query->num_rows() > 0) {



            $row2 = $query->row();



            $ret = array($row2->schoolemail, $row2->schoolpassword);



        }



        return $ret;



    }





    function getStudentList($params)

    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');





        $head = xml_add_child($rows, 'head', NULL, true);



        $colm = xml_add_child($head, 'column', "No", true);



        xml_add_attribute($colm, 'id', "students_no");



        xml_add_attribute($colm, 'width', "26");



        xml_add_attribute($colm, 'type', "ro");



        xml_add_attribute($colm, 'align', "center");



        xml_add_attribute($colm, 'sort', "int");





        $colm = xml_add_child($head, 'column', "Student", true);



        xml_add_attribute($colm, 'id', "studentname");



        xml_add_attribute($colm, 'width', "220");



        xml_add_attribute($colm, 'type', "ro");



        xml_add_attribute($colm, 'align', "left");



        xml_add_attribute($colm, 'sort', "str");





        $subquery = "";



        if ($params["level"] != "") {



            $subquery .= " and aa.level=" . $params["level"];



        }



        if ($params["session"] != "") {



            $subquery .= " and aa.session=" . $params["session"];



        }





        $sql1 = "Select aa.students_no, CONCAT(st.lastname,', ',st.firstname) as studentname from ali_academicrecords as aa INNER JOIN ali_students AS st ON aa.students_no = st.students_no  where st.progress='r' and aa.session not in (4) and aa.schoolyear='" . $params["year"] . "' and aa.trimester='" . $params["trim"] . "' " . $subquery . " group by  CONCAT(st.lastname,', ',st.firstname), aa.students_no order by CONCAT(st.lastname,', ',st.firstname) ";



        $query1 = $this->db->query($sql1);



        $k = 1;



        foreach ($query1->result_array() as $row5) {



            $item = xml_add_child($rows, 'row', NULL, true);



            xml_add_attribute($item, 'id', $k);



            $c0 = xml_add_child($item, 'cell', $row5["students_no"], false);



            $c1 = xml_add_child($item, 'cell', $row5["studentname"], false);



            $k++;



        }



        return xml_print($dom, true);



    }





    function getRoster($params)



    {



        $subquery = "";



        if ($params["level"] != "") {



            $subquery .= " and aa.level=" . $params["level"];



        }



        if ($params["session"] != "") {



            $subquery .= " and aa.session=" . $params["session"];



        }





        $scoreArr = array();



        $levelArr = array();



        $sql1 = "SELECT aa.schoolyear, aa.trimester, aa.session, aa.level, aa.studentname, @row_number := CASE WHEN @uno = CONCAT( aa.schoolyear, aa.trimester, aa.session, aa.level ) THEN @row_number +1 ELSE 1 END AS num, @uno := CONCAT( aa.schoolyear, aa.trimester, aa.session, aa.level ) AS Uno FROM (select at.schoolyear, at.trimester, at.session, at.level,CONCAT( st.lastname, ', ', st.firstname ) AS studentname from ali_academicrecords as at INNER JOIN ali_students AS st ON at.students_no = st.students_no where st.progress='r' ORDER BY at.schoolyear, at.trimester, at.session, at.level, CONCAT( st.lastname, ', ', st.firstname ) ) AS aa, (SELECT @uno :=0, @row_number :=0) AS k WHERE aa.level NOT IN ( 5, 6, 7 ) AND aa.session NOT IN ( 4 ) AND aa.schoolyear = '" . $params["year"] . "' AND aa.trimester = '" . $params["trim"] . "' " . $subquery . " ORDER BY CONCAT( aa.schoolyear, aa.trimester, aa.session, aa.level ), aa.studentname";



        $query1 = $this->db->query($sql1);



        foreach ($query1->result_array() as $row5) {



            $scoreArr["" . $row5["session"] . $row5["num"] . ""]["schoolyear"] = $row5["schoolyear"];



            $scoreArr["" . $row5["session"] . $row5["num"] . ""]["trimester"] = $row5["trimester"];



            $scoreArr["" . $row5["session"] . $row5["num"] . ""]["session"] = $row5["session"];



            $scoreArr["" . $row5["session"] . $row5["num"] . ""]["level"]["" . $row5["level"] . ""] = $row5["studentname"];



        }





        $this->load->helper('xml');



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');





        $head = xml_add_child($rows, 'head', NULL, true);



        $colm = xml_add_child($head, 'column', "Year", true);



        xml_add_attribute($colm, 'id', "schoolyear");



        xml_add_attribute($colm, 'width', "60");



        xml_add_attribute($colm, 'type', "coro");



        xml_add_attribute($colm, 'align', "center");



        xml_add_attribute($colm, 'sort', "str");



        $sql = "SELECT schoolyear FROM ali_gradingperiod GROUP BY schoolyear ";



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row1) {



            $o1 = xml_add_child($colm, 'option', $row1["schoolyear"], false);



            xml_add_attribute($o1, 'value', $row1["schoolyear"]);



        }





        $colm = xml_add_child($head, 'column', "Trimester", true);



        xml_add_attribute($colm, 'id', "trimester");



        xml_add_attribute($colm, 'width', "70");



        xml_add_attribute($colm, 'type', "coro");



        xml_add_attribute($colm, 'align', "center");



        xml_add_attribute($colm, 'sort', "str");



        $sql = "SELECT gradingperiod FROM ali_gradingperiod GROUP BY gradingperiod ";



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row2) {



            $o2 = xml_add_child($colm, 'option', $row2["gradingperiod"], false);



            xml_add_attribute($o2, 'value', $row2["gradingperiod"]);



        }





        $colm = xml_add_child($head, 'column', "Session", true);



        xml_add_attribute($colm, 'id', "session");



        xml_add_attribute($colm, 'width', "60");



        xml_add_attribute($colm, 'type', "coro");



        xml_add_attribute($colm, 'align', "center");



        xml_add_attribute($colm, 'sort', "str");



        $o5 = xml_add_child($colm, 'option', "AM", false);

        xml_add_attribute($o5, 'value', 1);



        $o5 = xml_add_child($colm, 'option', "AFT", false);

        xml_add_attribute($o5, 'value', 2);



        $o5 = xml_add_child($colm, 'option', "PM", false);

        xml_add_attribute($o5, 'value', 3);





        $sql = "SELECT levelname,levelvalue FROM ali_level where levelvalue not in (5,6,7) order BY levelname";



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row3) {



            $colm = xml_add_child($head, 'column', "Level " . $row3["levelname"], true);



            xml_add_attribute($colm, 'id', "level" . $row3["levelname"]);



            xml_add_attribute($colm, 'width', "130");



            xml_add_attribute($colm, 'type', "ro");



            xml_add_attribute($colm, 'align', "center");



            xml_add_attribute($colm, 'sort', "str");



        }





        $subarray = array();



        foreach ($scoreArr as $k => $v) {



            $subarray = $scoreArr[$k];



            $item = xml_add_child($rows, 'row', NULL, true);



            xml_add_attribute($item, 'id', $k);



            $c0 = xml_add_child($item, 'cell', $subarray["schoolyear"], false);



            $c1 = xml_add_child($item, 'cell', $subarray["trimester"], false);



            $c2 = xml_add_child($item, 'cell', $subarray["session"], false);





            $sql = "SELECT levelname,levelvalue FROM ali_level where levelvalue not in (5,6,7) order BY levelname";



            $query = $this->db->query($sql);



            foreach ($query->result_array() as $row3) {



                if (ISSET($subarray["level"]["" . $row3["levelvalue"] . ""])) {



                    $c3 = xml_add_child($item, 'cell', $subarray["level"]["" . $row3["levelvalue"] . ""], true);



                } else {



                    $c3 = xml_add_child($item, 'cell', "", true);



                }



            }





        }





        return xml_print($dom, true);



    }



    function getComSchoolYear($params)

    {



        $this->load->helper('xml');



        $sql = "";



        $dom = xml_dom();



        $comp = xml_add_child($dom, 'complete');



        $sql = "SELECT schoolyear FROM ali_gradingperiod GROUP BY schoolyear order by schoolyear desc";



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row5) {



            $item2 = xml_add_child($comp, 'option', $row5['schoolyear'], false);



            xml_add_attribute($item2, 'value', $row5['schoolyear']);



        }



        return xml_print($dom, true);



    }





    function getComLevel($params)

    {



        $this->load->helper('xml');



        $sql = "";



        $dom = xml_dom();



        $comp = xml_add_child($dom, 'complete');



        $sql = "SELECT levelname,levelvalue FROM ali_level where levelvalue not in(5,6,7) order by levelname";



        $query = $this->db->query($sql);



        $item2 = xml_add_child($comp, 'option', '', false);



        xml_add_attribute($item2, 'value', '');

        xml_add_attribute($item2, 'selected', 'true');



        foreach ($query->result_array() as $row5) {



            $item2 = xml_add_child($comp, 'option', $row5['levelname'], false);



            xml_add_attribute($item2, 'value', $row5['levelvalue']);



        }



        return xml_print($dom, true);



    }





    function getMyaccount($params)



    {



        $this->load->helper('xml');



        $sql = "select no, firstname, lastname, roleid,initial,bgcolorone,user_ID,passw, cellphone, email, etc, schoolemail,schoolpassword from ali_user where no=" . $this->session->userdata('ALISESS_USERNO');



        $query = $this->db->query($sql);



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'data');



        foreach ($query->result_array() as $row5) {



            $item1 = xml_add_child($rows, 'id', $row5['no'], true);



            $item2 = xml_add_child($rows, 'firstname', $row5['firstname'], true);



            $item3 = xml_add_child($rows, 'lastname', $row5['lastname'], true);



            $item4 = xml_add_child($rows, 'roleid', $row5['roleid'], true);



            $item5 = xml_add_child($rows, 'initial', $row5['initial'], true);



            $item6 = xml_add_child($rows, 'bgcolorone', $row5['bgcolorone'], true);

            xml_add_attribute($item6, 'style', "background-color:" . $row5['bgcolorone']);



            $item7 = xml_add_child($rows, 'user_ID', $row5['user_ID'], true);



            $item8 = xml_add_child($rows, 'inst_pwd', null, true);



            $item9 = xml_add_child($rows, 'cellphone', $row5['cellphone'], true);



            $item10 = xml_add_child($rows, 'email', $row5['email'], true);



            $item11 = xml_add_child($rows, 'etc', $row5['etc'], true);



            $item12 = xml_add_child($rows, 'schoolemail', $row5['schoolemail'], true);



            $item13 = xml_add_child($rows, 'schoolpassword', $row5['schoolpassword'], true);



        }





        return xml_print($dom, true);



    }



    function setMyaccount($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $rowId = $params["ids"];



        $this->newId = $rowId;



        $mode = $params[$rowId . "_!nativeeditor_status"];



        switch ($mode) {



            case "inserted":

                $action = $mode;

                break;



            case "deleted":

                $action = $mode;

                break;



            case "updated":

                $action = $this->update_row_myaccount($rowId, $params);

                break;



        }



        $action2 = xml_add_child($data, 'action', $action, false);



        xml_add_attribute($action2, 'type', 'updated');



        xml_add_attribute($action2, 'sid', $rowId);



        xml_add_attribute($action2, 'tid', $this->newId);



        return xml_print($dom, true);



    }



    function update_row_myaccount($rowId, $params)



    {



        $updatedata = array(



            'email' => iconv("UTF-8", "CP949", $params[$rowId . "_email"]),



            'firstname' => iconv("UTF-8", "CP949", $params[$rowId . "_firstname"]),



            'lastname' => iconv("UTF-8", "CP949", $params[$rowId . "_lastname"]),



            'nickname' => iconv("UTF-8", "CP949", $params[$rowId . "_nickname"]),



            'initial' => iconv("UTF-8", "CP949", $params[$rowId . "_initial"]),



            'bgcolorone' => $params[$rowId . "_bgcolorone"],



            'roleid' => $params[$rowId . "_roleid"],



            'cellphone' => iconv("UTF-8", "CP949", $params[$rowId . "_cellphone"]),



            'etc' => iconv("UTF-8", "CP949", $params[$rowId . "_etc"]),



            'schoolemail' => iconv("UTF-8", "CP949", $params[$rowId . "_schoolemail"]),



            'schoolpassword' => $params[$rowId . "_schoolpassword"],



            'writer_no' => $this->session->userdata('ALISESS_USERNO'),



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        if (trim($params[$rowId . "_inst_pwd"]) != "") {



            $salt = $this->fn_generate_salt();



            $password = $this->fn_generate_salted_password($params[$rowId . "_inst_pwd"], $salt);



            $this->db->set('passw', "'" . $password . "'", FALSE);



        }





        $this->db->set('modified', 'now()', FALSE);



        $this->db->where('no', $params[$rowId . "_id"]);



        $this->db->update("ali_user", $updatedata);



        return "updated";



    }





    function getStaffs($params)



    {



        $this->load->helper('xml');



        $sql = "";



        $columns = array("roleid", "user_ID", "firstname", "lastname", "initial", "nickname", "cellphone", "email", "bgcolorone", "active", "writer", "created", "etc", "passw");



        if (isset($params["orderby"])) {



            if ($params["direct"] == 'des')



                $direct = "DESC";



            else



                $direct = "ASC";



            $sql = " Order by " . $columns[$params["orderby"]] . " " . $direct;



        } else {



            $sql = " Order by active,firstname,lastname,roleid";



        }





        $posStart = "0";



        if (isset($params["posStart"]))



            $posStart = $params['posStart'];





        $sql = "select no,roleid,user_ID,firstname,lastname,initial,nickname,cellphone,email,bgcolorone,active,etc,writer,created,passw from ali_user where roleid in (2,4) " . $sql;



        $query = $this->db->query($sql);



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');



        xml_add_attribute($rows, 'total_count', $query->num_rows());



        xml_add_attribute($rows, 'pos', $posStart);



        foreach ($query->result_array() as $row5) {





            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $row5['no']);



            $c0 = xml_add_child($item, 'cell', $row5['roleid'], true);



            $c1 = xml_add_child($item, 'cell', $row5['user_ID'], true);



            $c2 = xml_add_child($item, 'cell', $row5['firstname'], true);



            $c3 = xml_add_child($item, 'cell', $row5['lastname'], true);



            $c4 = xml_add_child($item, 'cell', $row5['initial'], true);



            $c5 = xml_add_child($item, 'cell', $row5['nickname'], true);



            $c6 = xml_add_child($item, 'cell', $row5['cellphone'], true);



            $c7 = xml_add_child($item, 'cell', $row5['email'], true);



            $c8 = xml_add_child($item, 'cell', $row5['bgcolorone'], true);

            xml_add_attribute($c8, 'style', "background-color:" . $row5['bgcolorone']);



            $c9 = xml_add_child($item, 'cell', $row5['active'], true);



            $c10 = xml_add_child($item, 'cell', $row5['writer'], true);



            $c11 = xml_add_child($item, 'cell', $row5['created'], true);



            $c12 = xml_add_child($item, 'cell', $row5['etc'], true);



            $c13 = xml_add_child($item, 'cell', $row5['passw'], true);



        }





        return xml_print($dom, true);



    }



    function setStaff($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $rowId = $params["ids"];



        $this->newId = $rowId;



        $mode = $params[$rowId . "_!nativeeditor_status"];



        switch ($mode) {



            case "inserted":

                $action = $this->add_row_staff($rowId, $params);

                break;



            case "deleted":

                $action = $this->delete_row_staff($rowId, $params);

                break;



            case "updated":

                $action = $this->update_row_staff($rowId, $params);

                break;



        }



        $action2 = xml_add_child($data, 'action', $action, false);



        xml_add_attribute($action2, 'type', $action);



        xml_add_attribute($action2, 'sid', $rowId);



        xml_add_attribute($action2, 'tid', $this->newId);



        return xml_print($dom, true);



    }



    function add_row_staff($rowId, $params)

    {





        $insdata = array(



            'roleid' => $params[$rowId . "_c0"],



            'user_ID' => $params[$rowId . "_c1"],



            'firstname' => iconv("UTF-8", "CP949", $params[$rowId . "_c2"]),



            'lastname' => iconv("UTF-8", "CP949", $params[$rowId . "_c3"]),



            'initial' => iconv("UTF-8", "CP949", $params[$rowId . "_c4"]),



            'nickname' => iconv("UTF-8", "CP949", $params[$rowId . "_c5"]),



            'cellphone' => iconv("UTF-8", "CP949", $params[$rowId . "_c6"]),



            'email' => iconv("UTF-8", "CP949", $params[$rowId . "_c7"]),



            'bgcolorone' => $params[$rowId . "_c8"],



            'active' => $params[$rowId . "_c9"],



            'etc' => iconv("UTF-8", "CP949", $params[$rowId . "_c12"]),



            'writer' => $this->session->userdata('ALISESS_USERNAME'),



            'writer_no' => $this->session->userdata('ALISESS_USERNO')



        );



        $this->db->set('created', 'now()', FALSE);



        if (trim($params[$rowId . "_c13"]) != "") {



            $salt = $this->fn_generate_salt();



            $password = $this->fn_generate_salted_password($params[$rowId . "_c13"], $salt);



            $this->db->set('passw', "'" . $password . "'", FALSE);



        }



        $this->db->insert('ali_user', $insdata);



        $this->newId = $this->db->insert_id();



        return "inserted";



    }



    function update_row_staff($rowId, $params)

    {



        $updatedata = array(



            'roleid' => $params[$rowId . "_c0"],



            'user_ID' => $params[$rowId . "_c1"],



            'firstname' => iconv("UTF-8", "CP949", $params[$rowId . "_c2"]),



            'lastname' => iconv("UTF-8", "CP949", $params[$rowId . "_c3"]),



            'initial' => iconv("UTF-8", "CP949", $params[$rowId . "_c4"]),



            'nickname' => iconv("UTF-8", "CP949", $params[$rowId . "_c5"]),



            'cellphone' => iconv("UTF-8", "CP949", $params[$rowId . "_c6"]),



            'email' => iconv("UTF-8", "CP949", $params[$rowId . "_c7"]),



            'bgcolorone' => $params[$rowId . "_c8"],



            'active' => $params[$rowId . "_c9"],



            'etc' => iconv("UTF-8", "CP949", $params[$rowId . "_c12"]),



            'writer_no' => $this->session->userdata('ALISESS_USERNO'),



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('modified', 'now()', FALSE);



        $this->db->set('created', 'now()', FALSE);



        if (trim($params[$rowId . "_c13"]) != "") {



            $salt = $this->fn_generate_salt();



            $password = $this->fn_generate_salted_password($params[$rowId . "_c13"], $salt);



            $this->db->set('passw', "'" . $password . "'", FALSE);



        }



        $this->db->where('no', $rowId);



        $this->db->update("ali_user", $updatedata);



        return "updated";



    }



    function delete_row_staff($rowId, $params)

    {



        $this->db->where('no', $rowId);



        $this->db->delete('ali_user');



        return "deleted";



    }





    function getInstructors($params)



    {



        $this->load->helper('xml');



        $sql = "";



        $columns = array("roleid", "user_ID", "firstname", "lastname", "initial", "nickname", "cellphone", "email", "bgcolorone", "active", "writer", "created", "etc", "passw");



        if (isset($params["orderby"])) {



            if ($params["direct"] == 'des')



                $direct = "DESC";



            else



                $direct = "ASC";



            $sql = " Order by " . $columns[$params["orderby"]] . " " . $direct;



        } else {



            $sql = " Order by active,firstname,lastname,roleid";



        }





        $posStart = "0";



        if (isset($params["posStart"]))



            $posStart = $params['posStart'];





        $sql = "select no,roleid,user_ID,firstname,lastname,initial,nickname,cellphone,email,bgcolorone,active,etc,writer,created,passw from ali_user where roleid in (3) " . $sql;



        $query = $this->db->query($sql);



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');



        xml_add_attribute($rows, 'total_count', $query->num_rows());



        xml_add_attribute($rows, 'pos', $posStart);



        foreach ($query->result_array() as $row5) {





            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $row5['no']);



            $c0 = xml_add_child($item, 'cell', $row5['roleid'], true);



            $c1 = xml_add_child($item, 'cell', $row5['user_ID'], true);



            $c2 = xml_add_child($item, 'cell', $row5['firstname'], true);



            $c3 = xml_add_child($item, 'cell', $row5['lastname'], true);



            $c4 = xml_add_child($item, 'cell', $row5['initial'], true);



            $c5 = xml_add_child($item, 'cell', $row5['nickname'], true);



            $c6 = xml_add_child($item, 'cell', $row5['cellphone'], true);



            $c7 = xml_add_child($item, 'cell', $row5['email'], true);



            $c8 = xml_add_child($item, 'cell', $row5['bgcolorone'], true);

            xml_add_attribute($c8, 'style', "background-color:" . $row5['bgcolorone']);



            $c9 = xml_add_child($item, 'cell', $row5['active'], true);



            $c10 = xml_add_child($item, 'cell', $row5['writer'], true);



            $c11 = xml_add_child($item, 'cell', $row5['created'], true);



            $c12 = xml_add_child($item, 'cell', $row5['etc'], true);



            $c13 = xml_add_child($item, 'cell', $row5['passw'], true);



        }





        return xml_print($dom, true);



    }



    function setInstructor($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $rowId = $params["ids"];



        $this->newId = $rowId;



        $mode = $params[$rowId . "_!nativeeditor_status"];



        switch ($mode) {



            case "inserted":

                $action = $this->add_row_instructor($rowId, $params);

                break;



            case "deleted":

                $action = $this->delete_row_instructor($rowId, $params);

                break;



            case "updated":

                $action = $this->update_row_instructor($rowId, $params);

                break;



        }



        $action2 = xml_add_child($data, 'action', $action, false);



        xml_add_attribute($action2, 'type', $action);



        xml_add_attribute($action2, 'sid', $rowId);



        xml_add_attribute($action2, 'tid', $this->newId);



        return xml_print($dom, true);



    }



    function add_row_instructor($rowId, $params)

    {



        $insdata = array(



            'roleid' => $params[$rowId . "_c0"],



            'user_ID' => $params[$rowId . "_c1"],



            'firstname' => iconv("UTF-8", "CP949", $params[$rowId . "_c2"]),



            'lastname' => iconv("UTF-8", "CP949", $params[$rowId . "_c3"]),



            'initial' => iconv("UTF-8", "CP949", $params[$rowId . "_c4"]),



            'nickname' => iconv("UTF-8", "CP949", $params[$rowId . "_c5"]),



            'cellphone' => iconv("UTF-8", "CP949", $params[$rowId . "_c6"]),



            'email' => iconv("UTF-8", "CP949", $params[$rowId . "_c7"]),



            'bgcolorone' => $params[$rowId . "_c8"],



            'active' => $params[$rowId . "_c9"],



            'etc' => iconv("UTF-8", "CP949", $params[$rowId . "_c12"]),



            'writer' => $this->session->userdata('ALISESS_USERNAME'),



            'writer_no' => $this->session->userdata('ALISESS_USERNO')



        );



        $this->db->set('created', 'now()', FALSE);



        if (trim($params[$rowId . "_c13"]) != "") {



            $salt = $this->fn_generate_salt();



            $password = $this->fn_generate_salted_password($params[$rowId . "_c13"], $salt);



            $this->db->set('passw', $password, TRUE);



        }



        $this->db->insert('ali_user', $insdata);



        $this->newId = $this->db->insert_id();





        $activ = ($params[$rowId . "_c9"] == 1) ? "allow" : "deny";



        $insdata2 = array(



            'type' => 'user',



            'type_id' => $this->newId,



            'resource_id' => 3,



            'action' => $activ



        );



        $this->db->insert('ali_acl', $insdata2);





        return "inserted";



    }



    function update_row_instructor($rowId, $params)

    {



        $updatedata = array(



            'roleid' => $params[$rowId . "_c0"],



            'user_ID' => $params[$rowId . "_c1"],



            'firstname' => iconv("UTF-8", "CP949", $params[$rowId . "_c2"]),



            'lastname' => iconv("UTF-8", "CP949", $params[$rowId . "_c3"]),



            'initial' => iconv("UTF-8", "CP949", $params[$rowId . "_c4"]),



            'nickname' => iconv("UTF-8", "CP949", $params[$rowId . "_c5"]),



            'cellphone' => iconv("UTF-8", "CP949", $params[$rowId . "_c6"]),



            'email' => iconv("UTF-8", "CP949", $params[$rowId . "_c7"]),



            'bgcolorone' => $params[$rowId . "_c8"],



            'active' => $params[$rowId . "_c9"],



            'etc' => iconv("UTF-8", "CP949", $params[$rowId . "_c12"]),



            'writer_no' => $this->session->userdata('ALISESS_USERNO'),



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('modified', 'now()', FALSE);



        $this->db->set('created', 'now()', FALSE);



        if (trim($params[$rowId . "_c13"]) != "") {



            $salt = $this->fn_generate_salt();



            $password = $this->fn_generate_salted_password($params[$rowId . "_c13"], $salt);



            $this->db->set('passw', $password, TRUE);



        }



        $this->db->where('no', $rowId);



        $this->db->update("ali_user", $updatedata);





        $activ = ($params[$rowId . "_c9"] == 1) ? "allow" : "deny";



        $updatedata = array(



            'action' => $activ



        );



        $this->db->where('type', 'user');



        $this->db->where('type_id', $rowId);



        $this->db->where('resource_id', 3);



        $this->db->update("ali_acl", $updatedata);





        return "updated";



    }



    function delete_row_instructor($rowId, $params)

    {



        $this->db->where('no', $rowId);



        $this->db->delete('ali_user');





        $this->db->where('type', 'user');



        $this->db->where('type_id', $rowId);



        $this->db->where('resource_id', 3);



        $this->db->delete('ali_acl');





        return "deleted";



    }





    function getComTeachers($params)

    {



        $this->load->helper('xml');



        $sql = "";



        $dom = xml_dom();



        $comp = xml_add_child($dom, 'complete');



        $sql = "select no,firstname,lastname from ali_user where active=1 AND roleid=3";



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row5) {



            $item2 = xml_add_child($comp, 'option', $row5['firstname'] . " " . $row5['lastname'], false);



            xml_add_attribute($item2, 'value', $row5['no']);



            //if($params["reno"]==$row5['no']):



            //	xml_add_attribute($item2,'selected','true');



            //endif;



        }



        return xml_print($dom, true);



    }



    function getComRooms($params)

    {



        $this->load->helper('xml');



        $sql = "";



        $dom = xml_dom();



        $comp = xml_add_child($dom, 'complete');



        $sql = "select no,name from ali_rooms ";



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row5) {



            $item2 = xml_add_child($comp, 'option', $row5['name'], false);



            xml_add_attribute($item2, 'value', $row5['no']);



        }



        return xml_print($dom, true);



    }





    function getComTrimesters($params)

    {



        $this->load->helper('xml');



        $sql = "";



        $dom = xml_dom();



        $comp = xml_add_child($dom, 'complete');



        $sql = "select no,schoolyear,gradingperiod from ali_gradingperiod where active=1";



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row5) {



            $item2 = xml_add_child($comp, 'option', $row5['schoolyear'] . " GP" . $row5['gradingperiod'], false);



            xml_add_attribute($item2, 'value', $row5['no']);



        }



        return xml_print($dom, true);



    }





    function getClasses($params)



    {



        $this->load->helper('xml');



        $sql = "";



        $columns = array("schoolyear", "trimester", "classname", "teachername");



        if (isset($params["orderby"])) {



            if ($params["direct"] == 'des')



                $direct = "DESC";



            else



                $direct = "ASC";



            $sql = " Order by " . $columns[$params["orderby"]] . " " . $direct;



        } else {



            $sql = " Order by sc.schoolyear desc, sc.trimester, sc.level, sc.session ";



        }





        if ($this->session->userdata('TOPLEVEL_AUTH') == 1 || $this->session->userdata('TOPLEVEL_AUTH') == 2) {



            $sql = "select sc.schoolyear, sc.trimester, ct.no, ct.class_no, sc.name as classname, concat(st.firstname,' ',st.lastname) as teachername from ali_class as sc inner join ali_classteachers as ct on ct.class_no=sc.no inner join ali_user as st on ct.teacher_no=st.no where sc.status=0 and st.roleid=3 and ct.isprimary=1 " . $sql;



        } else {



            $sql = "select sc.schoolyear, sc.trimester, ct.no, ct.class_no, sc.name as classname, concat(st.firstname,' ',st.lastname) as teachername from ali_class as sc inner join ali_classteachers as ct on ct.class_no=sc.no inner join ali_user as st on ct.teacher_no=st.no inner join ali_gradingperiod as ag on sc.schoolyear=ag.schoolyear and sc.trimester=ag.gradingperiod where ct.teacher_no=" . $this->session->userdata('ALISESS_USERNO') . " and sc.status=0 and st.roleid=3 and ag.active=1 " . $sql;



        }



        $query = $this->db->query($sql);



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');



        $j = 0;



        foreach ($query->result_array() as $row5) {



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $row5['no']);



            $c0 = xml_add_child($item, 'cell', $row5['schoolyear'], true);



            $c1 = xml_add_child($item, 'cell', "Tri" . $row5['trimester'], true);



            $c2 = xml_add_child($item, 'cell', $row5['classname'] . "^javascript:self.location.href=\"/index.php/aliweb/assigngrade?classno=" . $row5['class_no'] . "&classname=" . $row5['classname'] . "\";^_self", true);



            $c3 = xml_add_child($item, 'cell', $row5['teachername'], true);



            //$c4 = xml_add_child($item, 'cell',"<div class=\"ops\" style=\"width:16px;height:40px;\"><ul><li id=\"fontgear\"></li><ul><li><a href=\"#\" onClick=\"doOnLoadInfo(".$row5['class_no'].");\">Edit</a></li></ul></ul></div>",true);



            $j++;



        }





        if ($j == 0) {



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', 1);



            $c1 = xml_add_child($item, 'cell', "", true);



            $c2 = xml_add_child($item, 'cell', "No Data.", true);



            $c3 = xml_add_child($item, 'cell', "", true);



            $c3 = xml_add_child($item, 'cell', "", true);



        }





        return xml_print($dom, true);



    }



    function getClass($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $sql = "select ct.class_no, ct.teacher_no, sc.room_no, sc.name, sc.status, sc.schoolyear, sc.trimester, sc.level, sc.session, sc.classtype from ali_classteachers as ct inner join ali_class as sc on ct.class_no=sc.no where sc.status=0 and ct.isprimary=1 AND ct.class_no=" . $params['id'];



        $query = $this->db->query($sql);



        if ($query->num_rows() > 0) {



            $row5 = $query->row();



            $cell1 = xml_add_child($data, 'schoolyear', $row5->schoolyear, true);



            $cell1 = xml_add_child($data, 'trimester', $row5->trimester, true);



            $cell1 = xml_add_child($data, 'level', $row5->level, true);



            $cell1 = xml_add_child($data, 'session', $row5->session, true);



            $cell1 = xml_add_child($data, 'classtype', $row5->classtype, true);



            $cell3 = xml_add_child($data, 'teacher_no', $row5->teacher_no, true);



            $cell3 = xml_add_child($data, 'room_no', $row5->room_no, true);



            $cell3 = xml_add_child($data, 'name', $row5->name, true);



            $cell3 = xml_add_child($data, 'status', $row5->status, true);



            $cell4 = xml_add_child($data, 'id', $row5->class_no, true);



        }



        return xml_print($dom, true);



    }



    function setClass($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $rowId = $params["ids"];



        $this->newId = $rowId;



        $mode = $params[$rowId . "_!nativeeditor_status"];



        switch ($mode) {



            case "inserted":

                $action = $this->add_row_classes($rowId, $params);

                break;



            case "deleted":

                $action = $this->delete_row_classes($rowId);

                break;



            case "updated":

                $action = $this->update_row_classes($rowId, $params);

                break;



        }



        $action2 = xml_add_child($data, 'action', $action, false);



        xml_add_attribute($action2, 'type', $action);



        xml_add_attribute($action2, 'sid', $rowId);



        xml_add_attribute($action2, 'tid', $this->newId);



        return xml_print($dom, true);



    }



    function add_row_classes($rowId, $params)

    {



        $insdata = array(



            'schoolyear' => $params[$rowId . "_schoolyear"],



            'trimester' => $params[$rowId . "_trimester"],



            'level' => $params[$rowId . "_level"],



            'session' => $params[$rowId . "_session"],



            'classtype' => $params[$rowId . "_classtype"],



            'room_no' => $params[$rowId . "_room_no"],



            'name' => $params[$rowId . "_name"],



            'status' => $params[$rowId . "_status"],



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('regdate', 'now()', FALSE);



        $this->db->insert('ali_class', $insdata);



        $this->newId = $this->db->insert_id();





        $sql = "select firstname,lastname from ali_user where roleid=3 and no=" . $params[$rowId . "_teacher_no"];



        $query = $this->db->query($sql);



        if ($query->num_rows() > 0) {



            $row5 = $query->row();



            $insdata2 = array(



                'class_no' => $this->newId,



                'teacher_no' => $params[$rowId . "_teacher_no"],



                'teachername' => $row5->firstname . " " . $row5->lastname,



                'isprimary' => 1,



                'permission' => 1,



                'writer' => $this->session->userdata('ALISESS_USERNAME')



            );



            $this->db->set('regdate', 'now()', FALSE);



            $this->db->insert('ali_classteachers', $insdata2);



        }





        $sql3 = "select name,wpercentage from ali_assign_cate_basic ";



        $query3 = $this->db->query($sql3);



        foreach ($query3->result_array() as $row5) {



            $insdata3 = array(



                'name' => $row5["name"],



                'wpercentage' => $row5["wpercentage"],



                'class_no' => $this->newId,



                'writer' => $this->session->userdata('ALISESS_USERNAME')



            );



            $this->db->set('regdate', 'now()', FALSE);



            $this->db->insert('ali_assign_cate', $insdata3);



        }





        return "inserted";



    }



    function update_row_classes($rowId, $params)

    {



        $updatedata = array(



            'schoolyear' => $params[$rowId . "_schoolyear"],



            'trimester' => $params[$rowId . "_trimester"],



            'level' => $params[$rowId . "_level"],



            'session' => $params[$rowId . "_session"],



            'classtype' => $params[$rowId . "_classtype"],



            'room_no' => $params[$rowId . "_room_no"],



            'name' => $params[$rowId . "_name"],



            'status' => $params[$rowId . "_status"],



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('regdate', 'now()', FALSE);



        $this->db->where('no', $rowId);



        $this->db->update('ali_class', $updatedata);





        return "updated";



    }



    function delete_row_classes($rowId)

    {



        //$this->db->where('class_no', $rowId);  $this->db->delete('ali_grade_new');



        //$this->db->where('class_no', $rowId);  $this->db->delete('ali_assignments');



        //$this->db->where('class_no', $rowId);  $this->db->delete('ali_assign_cate');



        //$this->db->where('class_no', $rowId);  $this->db->delete('ali_classteachers');



        //$this->db->where('class_no', $rowId);  $this->db->delete('ali_roster');



        //$this->db->where('no', $rowId);  $this->db->delete('ali_class');



        //$this->db->where('class_no', $rowId);  $this->db->delete('ali_attendance_new');





        $updatedata = array(



            'status' => 9,



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('regdate', 'now()', FALSE);



        $this->db->where('no', $rowId);



        $this->db->update('ali_class', $updatedata);



        return "deleted";



    }





    /*** Assigngrade Function for Dhtmlx Start****/



    function getGrades($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');





        $head = xml_add_child($rows, 'head', NULL, true);



        $colm = xml_add_child($head, 'column', "Students", true);

        xml_add_attribute($colm, 'width', "160");

        xml_add_attribute($colm, 'type', "link");

        xml_add_attribute($colm, 'align', "center");

        xml_add_attribute($colm, 'sort', "str");



        $colm = xml_add_child($head, 'column', "Grades", true);

        xml_add_attribute($colm, 'width', "100");

        xml_add_attribute($colm, 'type', "ro");

        xml_add_attribute($colm, 'align', "center");

        xml_add_attribute($colm, 'sort', "str");





        $assignArr = array();



        $sql0 = "SELECT sc.name as catname, sc.no as catno, sa.no, sa.name as assignname, sa.isview FROM `ali_assign_cate` as sc left join ali_assignments as sa on sc.no=sa.assigncat_no WHERE sc.class_no=" . $params["classno"];



        $query0 = $this->db->query($sql0);



        $i = 0;



        foreach ($query0->result_array() as $row0) {



            $colors = ($row0["isview"] == 1) ? "grey" : "#008080";



            $colm = xml_add_child($head, 'column', "<span style='color:" . $colors . ";font-size:8.5px'>" . $row0["catname"] . "</span><br/>" . $row0["assignname"], true);

            xml_add_attribute($colm, 'width', "100");

            xml_add_attribute($colm, 'type', "ed");

            xml_add_attribute($colm, 'align', "center");

            xml_add_attribute($colm, 'sort', "int");



            $assignArr[$i] = $row0["no"] . "|" . $row0["catno"];



            $i++;



        }



        $totalassign = count($assignArr);



        if ($totalassign <= 0) $totalassign = 1;





        $sum = 0;



        $scoreArr = array();



        $sql1 = "select sr.students_no, CONCAT(sr.lastname,', ',sr.firstname) AS fullname, sg.assign_no,sg.score,CONCAT(sg.score,'|',sg.no) AS scor from ali_roster as sr left join ali_grade_new  as sg on sr.students_no=sg.student_no and sr.class_no=sg.class_no  inner join ali_students as bb on sr.students_no=bb.students_no where bb.progress='r' and sr.class_no=" . $params["classno"] . " group by  fullname,sr.students_no, sg.assign_no, sg.score, CONCAT(sg.score,'|',sg.no) ";



        $query1 = $this->db->query($sql1);



        foreach ($query1->result_array() as $row5) {



            $scoreArr["" . $row5["students_no"] . ""]["fname"] = $row5["fullname"];



            $scoreArr["" . $row5["students_no"] . ""]["assign"]["" . $row5["assign_no"] . ""] = $row5["scor"];



        }





        $arrav = array();



        foreach ($scoreArr as $k => $v) {



            $arrav = $scoreArr[$k];





            $params['stno'] = $k;





            //Grading Scale



            $gl = "";



            $fg = $this->getTotalGrade($params['classno'], $params['stno']);



            if ($fg >= 0 && $fg < 60) {



                $gl = "F";



            } elseif ($fg >= 60 && $fg < 70) {



                $gl = "D";



            } elseif ($fg >= 70 && $fg < 80) {



                $gl = "C";



            } elseif ($fg >= 80 && $fg < 90) {



                $gl = "B";



            } elseif ($fg >= 90 && $fg <= 100) {



                $gl = "A";



            } else {



                $gl = "error";



            }





            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $k);



            $c1 = xml_add_child($item, 'cell', $arrav["fname"] . "^javascript:self.location.href=\"/index.php/aliweb/studentgrade?stno=" . $k . "\";^_self", true);



            $c2 = xml_add_child($item, 'cell', "<div style='text-align:justify'>" . $gl . "&nbsp;&nbsp;&nbsp;&nbsp;" . $fg . "%</div>", true);



            $inarr = array();



            $inarr = $arrav["assign"];



            for ($j = 0; $j < $totalassign; $j++):





                if (ISSET($assignArr[$j]))



                    $assarr = explode('|', $assignArr[$j]);



                else



                    $assarr = null;





                if (ISSET($inarr["" . $assarr[0] . ""])) {



                    $carr = explode('|', $inarr["" . $assarr[0] . ""]);



                    $c3 = xml_add_child($item, 'cell', $carr[0], true);



                    xml_add_attribute($c3, 'assignno', $assarr[0]);



                    xml_add_attribute($c3, 'assigncatno', $assarr[1]);



                    if (!empty($carr[1])): xml_add_attribute($c3, 'gradeno', $carr[1]); endif;



                } else {



                    $c3 = xml_add_child($item, 'cell', null, true);



                    xml_add_attribute($c3, 'assignno', $assarr[0]);



                    xml_add_attribute($c3, 'assigncatno', $assarr[1]);



                }



                if (($j % 2) == 0) {



                    xml_add_attribute($c3, 'style', "background-color:#f2f5f7;");



                }





            endfor;



        }





        return xml_print($dom, true);



    }



    function setGrades($params)



    {



        $action = "";



        $totalgrade = "";



        $gradeno = "";



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $rowId = $params["ids"];



        $this->newId = $rowId;



        $mode = $params[$rowId . "_!nativeeditor_status"];



        switch ($mode) {



            case "inserted":

                $action = $mode;

                $gradeno = $this->insert_row_grade($rowId, $params);

                break;



            case "updated":

                $action = $this->update_row_grade($rowId, $params);

                break;



            case "deleted":

                $action = $this->delete_row_grade($rowId, $params);

                break;



        }



        $gl = "";



        $fg = $this->getTotalGrade($params[$rowId . "_class_no"], $params[$rowId . "_stdno"]);



        if ($fg >= 0 && $fg < 60) {



            $gl = "F";



        } elseif ($fg >= 60 && $fg < 70) {



            $gl = "D";



        } elseif ($fg >= 70 && $fg < 80) {



            $gl = "C";



        } elseif ($fg >= 80 && $fg < 90) {



            $gl = "B";



        } elseif ($fg >= 90 && $fg <= 100) {



            $gl = "A";



        } else {



            $gl = "error" . $fg;



        }



        $totalgrade = "<div style='text-align:justify'>" . $gl . "&nbsp;&nbsp;&nbsp;&nbsp;" . $fg . "%</div>";





        $action2 = xml_add_child($data, 'action', $action . "|:|" . $params[$rowId . "_stdno"] . "|:|" . $totalgrade . "|:|" . $params[$rowId . "_selcell"] . "|:|" . $gradeno, false);



        xml_add_attribute($action2, 'type', $action);



        xml_add_attribute($action2, 'sid', $rowId);



        xml_add_attribute($action2, 'tid', $this->newId);



        return xml_print($dom, true);



    }



    function insert_row_grade($rowId, $params)

    {



        $sql = "select no from ali_grade_new where student_no=" . $params[$rowId . "_stdno"] . " and assign_no=" . $params[$rowId . "_assignno"] . " and class_no=" . $params[$rowId . "_class_no"];



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row5) {



            $params[$rowId . "_gradeno"] = $row5["no"];



            return $this->update_row_grade($rowId, $params);



        }



        $insdata = array(



            'class_no' => $params[$rowId . "_class_no"],



            'assign_no' => $params[$rowId . "_assignno"],



            'assigncat_no' => $params[$rowId . "_assigncatno"],



            'student_no' => $params[$rowId . "_stdno"],



            'score' => $params[$rowId . "_scorval"],



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('regdate', 'now()', FALSE);



        $this->db->insert('ali_grade_new', $insdata);



        return $this->db->insert_id();



    }



    function update_row_grade($rowId, $params)

    {



        $updatedata = array(



            'score' => $params[$rowId . "_scorval"],



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('regdate', 'now()', FALSE);



        $this->db->where('no', $params[$rowId . "_gradeno"]);



        $this->db->update('ali_grade_new', $updatedata);



        return "updated";



    }



    function delete_row_grade($rowId, $params)

    {



        $this->db->where('no', $params[$rowId . "_gradeno"]);



        $this->db->delete('ali_grade_new');



        return "deleted";



    }



    /*** Assigngrade Function for Dhtmlx End****/





    function getTotalGrade($classno, $stno)

    {



        $sum = 0;



        $grade = 0;





        $arrcate = array();



        $sql0 = "select ac.no, ac.wpercentage from ali_assign_cate as ac inner join ali_assignments as sa on sa.assigncat_no=ac.no where sa.isview=0 and sa.class_no=" . $classno . " group by ac.no, ac.wpercentage";



        $query0 = $this->db->query($sql0);



        foreach ($query0->result_array() as $row0) {



            $arrcate["" . $row0['no'] . ""] = $row0['wpercentage'];



            $sum = $sum + intval($row0['wpercentage']);



        }





        //$sql1 = "select assigncat_no,AVG(score) as scoreavg from ali_grade_new where class_no=".$classno." and student_no=".$stno." group by assigncat_no";



        $sql1 = "select ac.assigncat_no,AVG(ac.score) as scoreavg  from ali_grade_new as ac inner join ali_assignments as sa on sa.no=ac.assign_no where  sa.isview=0 and ac.class_no=" . $classno . " and ac.student_no=" . $stno . "  group by ac.assigncat_no";



        $query1 = $this->db->query($sql1);



        foreach ($query1->result_array() as $row1) {



            if (ISSET($row1["scoreavg"]) && ISSET($arrcate["" . $row1['assigncat_no'] . ""])) {



                $grade = $grade + number_format(($row1["scoreavg"] * (((100 * $arrcate["" . $row1['assigncat_no'] . ""]) / $sum) / 100)), 1);



            } else {



                $grade = $grade + 0;



            }





        }





        return round($grade);



        /*  $yourgrade =0;



        $totalpercentage=0;



        $sql0 = "SELECT no, name, wpercentage FROM ali_assign_cate where class_no=".$classno;



        $query0 = $this->db->query($sql0);



        foreach($query0->result_array() as $row0)



        {



            $totalpercentage = 	$totalpercentage +	$row0['wpercentage'];







            $subtotal = 0;



            $j=0;



            $sql1 = "select sa.no, sa.name,sg.score,sa.points,sa.description from ali_assignments as sa left join (select assign_no,score from ali_grade_new where student_no=".$stno.") as sg on sa.no=sg.assign_no where sa.class_no=".$classno." and sa.isview=0 and sa.assigncat_no=".$row0['no'];



            $query1 = $this->db->query($sql1);



            foreach($query1->result_array() as $row1)



            {



                $subtotal = $subtotal + intval($row1["score"]);



                $j++;



            }







            if($j>0){



                $yourgrade = $yourgrade + ($subtotal/$j)*($row0['wpercentage']/100);



            }



        }







        return floor($yourgrade);



*/



    }



    function getAttRate($stno,$lsno,$rwno,$year,$trim){

        $attrate =0;

        $ls_p = 0;

        $ls_a = 0;

        $ls_t = 0;

        $sql1 = "SELECT marks FROM `ali_attendance_new` where student_no=".$stno." and class_no=".$lsno." ";

        $query1 = $this->db->query($sql1);

        foreach ($query1->result_array() as $row1) {

            $vl = substr($row1["marks"], 0, 1);

            if ($vl == "P") {

                $ls_p++;

            }

            if ($vl == "T") {

                $ls_t++;

            }

            if ($vl == "A") {

                $ls_a++;

            }

            $vr = substr($row1["marks"], 1, 1);

            if ($vr == "P") {

                $ls_p++;

            }

            if ($vr == "T") {

                $ls_t++;

            }

            if ($vr == "A") {

                $ls_a++;

            }

        }

        $sum_ls = $ls_p + $ls_t + $ls_a;



        $rw_p = 0;

        $rw_a = 0;

        $rw_t = 0;

        $sql2 = "SELECT marks FROM `ali_attendance_new` where student_no=".$stno." and class_no=".$rwno." ";

        $query2 = $this->db->query($sql2);

        foreach ($query2->result_array() as $row2) {

            $wl = substr($row2["marks"], 0, 1);

            if ($wl == "P") {

                $rw_p++;

            }

            if ($wl == "T") {

                $rw_t++;

            }

            if ($wl == "A") {

                $rw_a++;

            }

            $wr = substr($row2["marks"], 1, 1);

            if ($wr == "P") {

                $rw_p++;

            }

            if ($wr == "T") {

                $rw_t++;

            }

            if ($wr == "A") {

                $rw_a++;

            }

        }

        $sum_rw = $rw_p + $rw_t + $rw_a;

        $totalabsent = ($ls_a + $rw_a);

        $tardytoabsent = 0;

        $tardyrest = 0;

        $totaltardy = ($ls_t + $rw_t);

        if ($totaltardy > 0) {

            $tardytoabsent = floor($totaltardy / 3);

            $tardyrest = $totaltardy % 3;

        }

        $sumttl = $sum_ls + $sum_rw;

        if ($sumttl > 0) {

            if (($year >= 2017 && $trim > 2) || $year > 2017){

                $attrate = 100 - round((($tardytoabsent + $totalabsent) / 200 * 100)+($tardyrest * 0.3) , 1);

            }else{

                $attrate = 100 - round((($tardytoabsent + $totalabsent) / ($sum_ls + $sum_rw)) * 100, 1);

            }

        } else {

            $attrate = 0;

        }



        return $attrate;

    }



    /*** Add Finalization ***/

    function setFinalization($stno){

        $arratt = array();

        $sql = "select a.schoolyear,a.trimester,a.level,a.session,a.classtype,a.class_no from ali_roster a inner join ali_gradingperiod b on a.schoolyear=b.schoolyear and a.trimester=b.gradingperiod where b.active=0 and a.students_no=".$stno;

        $query = $this->db->query($sql);

        foreach ($query->result_array() as $row5) {

            $lsno=0;

            $rwno=0;

            $rgrade = $this->getTotalGrade($row5["class_no"],$stno);

            if($row5["classtype"] == "RW"){

                $updatedata = array(

                    'rw_score' => round($rgrade)

                );

            }

            if($row5["classtype"] == "LS"){

                $updatedata = array(

                    'ls_score' => round($rgrade)

                );

            }

            // $this->db->set('modified', 'now()', FALSE);

            $this->db->where('students_no', $stno);

            $this->db->where('schoolyear', $row5["schoolyear"]);

            $this->db->where('trimester', $row5["trimester"]);

            $this->db->where('level', $row5["level"]);

            $this->db->where('session', $row5["session"]);

            $this->db->update('ali_academicrecords', $updatedata);



            $arratt["".$row5["schoolyear"].""]["".$row5["trimester"].""]["".$row5["classtype"].""] = $row5["class_no"];

            $arratt["".$row5["schoolyear"].""]["".$row5["trimester"].""]["level"] = $row5["level"];

            $arratt["".$row5["schoolyear"].""]["".$row5["trimester"].""]["session"] = $row5["session"];

        }



        $arrayTrim = array();

        foreach ($arratt as $keyYear => $value){

            $arrayTrim = $arratt[$keyYear];

            foreach ($arrayTrim as $keyTrim => $value2){

                $attrate = $this->getAttRate($stno,$arrayTrim[$keyTrim]["LS"],$arrayTrim[$keyTrim]["RW"],$keyYear,$keyTrim);

                $updatedata2 = array(

                    'att_score' => round($attrate)

                );

                $this->db->where('students_no', $stno);

                $this->db->where('schoolyear', $keyYear);

                $this->db->where('trimester', $keyTrim);

                $this->db->where('level', $arrayTrim[$keyTrim]["level"]);

                $this->db->where('session', $arrayTrim[$keyTrim]["session"]);

                $this->db->update('ali_academicrecords', $updatedata2);



            }

        }



        return 1;

    }



    function setEachFinalization($stno,$year,$trim,$level,$session){

        $arratt = array();

        $sql = "select a.schoolyear,a.trimester,a.level,a.session,a.classtype,a.class_no from ali_roster a inner join ali_gradingperiod b on a.schoolyear=b.schoolyear and a.trimester=b.gradingperiod where b.active=0 and a.schoolyear='".$year."' and  a.trimester='".$trim."' and  a.level=".$level." and a.session=".$session." and a.students_no=".$stno;

        $query = $this->db->query($sql);

        foreach ($query->result_array() as $row5) {

            $lsno=0;

            $rwno=0;

            $rgrade = $this->getTotalGrade($row5["class_no"],$stno);

            if($row5["classtype"] == "RW"){

                $updatedata = array(

                    'rw_score' => round($rgrade)

                );

            }

            if($row5["classtype"] == "LS"){

                $updatedata = array(

                    'ls_score' => round($rgrade)

                );

            }

            // $this->db->set('modified', 'now()', FALSE);

            $this->db->where('students_no', $stno);

            $this->db->where('schoolyear', $row5["schoolyear"]);

            $this->db->where('trimester', $row5["trimester"]);

            $this->db->where('level', $row5["level"]);

            $this->db->where('session', $row5["session"]);

            $this->db->update('ali_academicrecords', $updatedata);



            $arratt["".$row5["schoolyear"].""]["".$row5["trimester"].""]["".$row5["classtype"].""] = $row5["class_no"];

            $arratt["".$row5["schoolyear"].""]["".$row5["trimester"].""]["level"] = $row5["level"];

            $arratt["".$row5["schoolyear"].""]["".$row5["trimester"].""]["session"] = $row5["session"];

        }



        $arrayTrim = array();

        foreach ($arratt as $keyYear => $value){

            $arrayTrim = $arratt[$keyYear];

            foreach ($arrayTrim as $keyTrim => $value2){

                $attrate = $this->getAttRate($stno,$arrayTrim[$keyTrim]["LS"],$arrayTrim[$keyTrim]["RW"],$keyYear,$keyTrim);

                $updatedata2 = array(

                    'att_score' => round($attrate)

                );

                $this->db->where('students_no', $stno);

                $this->db->where('schoolyear', $keyYear);

                $this->db->where('trimester', $keyTrim);

                $this->db->where('level', $arrayTrim[$keyTrim]["level"]);

                $this->db->where('session', $arrayTrim[$keyTrim]["session"]);

                $this->db->update('ali_academicrecords', $updatedata2);

            }

        }



        return 1;

    }





    /*** Assignments Function for Dhtmlx Start****/



    function getAssignments($params)



    {



        $this->load->helper('xml');



        $sql = "";



        $columns = array("classname", "name", "duedate", "catename", "isview");



        if (isset($params["orderby"])) {



            if ($params["direct"] == 'des')



                $direct = "DESC";



            else



                $direct = "ASC";



            $sql = " Order by " . $columns[$params["orderby"]] . " " . $direct;



        } else {



            $sql = " Order by sa.no";



        }



        $sql = "select sa.no,sa.name,sa.duedate,st.name as catename,sa.isview from ali_assignments as sa inner join ali_assign_cate as st on sa.assigncat_no=st.no where sa.class_no=" . $params["classno"] . " " . $sql;



        $query = $this->db->query($sql);



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');





        foreach ($query->result_array() as $row5) {



            $item = xml_add_child($rows, 'row', NULL, true);



            xml_add_attribute($item, 'id', $row5['no']);



            $c2 = xml_add_child($item, 'cell', html_entity_decode($row5['name'], ENT_QUOTES) . "^javascript:self.location.href=\"/index.php/aliweb/assignview?id=" . $row5['no'] . "\";^_self", true);



            $c3 = xml_add_child($item, 'cell', $row5['duedate'], true);



            $c4 = xml_add_child($item, 'cell', $row5['catename'], true);



            if ($row5['isview'] == 1) {



                $c6 = xml_add_child($item, 'cell', 'true', true);



            } else {



                $c6 = xml_add_child($item, 'cell', 'false', true);



            }



        }



        return xml_print($dom, true);



    }



    function setAssignments($params)



    {



        $action = "";



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $ids = explode(",", $params["ids"]);



        for ($i = 0; $i < sizeof($ids); $i++) {



            $rowId = $ids[$i]; //id or row which was updated



            $newId = $rowId; //will be used for insert operation



            $mode = $params[$rowId . "_!nativeeditor_status"]; //get request mode



            switch ($mode) {



                case "inserted":

                    $action = $mode;

                    break;



                case "deleted":

                    $action = $mode;

                    break;



                case "updated":

                    $action = $this->update_row_assignments($rowId, $params);

                    break;



            }



        }



        $action2 = xml_add_child($data, 'action', $action, false);



        xml_add_attribute($action2, 'type', $action);



        xml_add_attribute($action2, 'sid', $rowId);



        xml_add_attribute($action2, 'tid', $newId);



        return xml_print($dom, true);



    }



    function update_row_assignments($rowId, $params)

    {



        $updatedata = array(



            'isview' => strtoupper($params[$rowId . "_c3"]),



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('regdate', 'now()', FALSE);



        $this->db->where('no', $rowId);



        $this->db->update('ali_assignments', $updatedata);



        return "updated";



    }





    function getComCate($params)

    {



        $this->load->helper('xml');



        $sql = "";



        $i = 0;



        $dom = xml_dom();



        $comp = xml_add_child($dom, 'complete');



        $sql = "select no, name from ali_assign_cate where class_no=" . $params['classno'];



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row5) {



            $item2 = xml_add_child($comp, 'option', $row5['name'], false);

            xml_add_attribute($item2, 'value', $row5['no']);



            $i++;



        }



        return xml_print($dom, true);



    }





    function getAssignview($params)



    {





        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $sql = "select sc.name as classname,sa.class_no, sa.no, sa.assigncat_no, sa.points,sa.name,sa.duedate,sa.description,sa.isview,sa.writer,sa.regdate from ali_assignments as sa inner join ali_class as sc on sa.class_no=sc.no inner join ali_assign_cate as st on sa.assigncat_no=st.no where sa.no=" . $params['id'] . " order by sa.no desc";



        $query = $this->db->query($sql);



        if ($query->num_rows() > 0) {



            $row5 = $query->row();



            $cell0 = xml_add_child($data, 'classname', $row5->classname, true);



            $cell1 = xml_add_child($data, 'assigncat_no', $row5->assigncat_no, true);



            $cell3 = xml_add_child($data, 'points', $row5->points, true);



            $cell4 = xml_add_child($data, 'class_no', $row5->class_no, true);



            $cell5 = xml_add_child($data, 'name', $row5->name, true);



            $cell6 = xml_add_child($data, 'duedate', $row5->duedate, true);



            $cell7 = xml_add_child($data, 'description', $row5->description, true);



            $cell8 = xml_add_child($data, 'isview', ($row5->isview) ? "true" : "false", true);



            $cell9 = xml_add_child($data, 'writer', $row5->writer, true);



            $cell10 = xml_add_child($data, 'regdate', $row5->regdate, true);



            $cell12 = xml_add_child($data, 'id', $row5->no, true);



        }





        return xml_print($dom, true);



    }



    function add_row_assignview($params)

    {



        $insdata = array(



            'assigncat_no' => $params["assigncat_no"],



            'points' => $params["points"],



            'class_no' => $params["classno"],



            'name' => $params["name"],



            'duedate' => $params["duedate"],



            'description' => $params["description"],



            'isview' => (($params["isview"]) ? 1 : 0),



            'isdiscuss' => 1,



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('regdate', 'now()', FALSE);



        $this->db->insert('ali_assignments', $insdata);



        return $this->db->insert_id();



    }



    function delete_row_assignview($id)

    {



        $this->db->where('no', $id);



        $this->db->delete('ali_assignments');



        return '';



    }





    function getStudentScores($assigno, $classno)



    {



        $sql = "select sr.students_no,sr.firstname,sr.lastname,sg.score  from ali_roster as sr left join (SELECT class_no, student_no,score FROM ali_grade_new WHERE assign_no=" . $assigno . ") as sg  on sr.students_no=sg.student_no inner join ali_students as bb on sr.students_no=bb.students_no where bb.progress='r' and sr.class_no=" . $classno . " order by sr.lastname,sr.firstname ";



        $query = $this->db->query($sql);



        return $query->result_array();



    }





    function add_row_file($params)

    {



        $insdata = array(



            'gno' => $params["gbnum"],



            'rno' => $params["id"],



            'filename' => $params["newfilename"],



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('regdate', 'now()', FALSE);



        $this->db->insert('ali_files', $insdata);



        $this->newId = $this->db->insert_id();



        return "inserted";



    }



    function getFiles($id, $gbnum)



    {



        $sql = "select no,filename from ali_files where gno=" . $gbnum . " and rno=" . $id . " ";



        $query = $this->db->query($sql);



        return $query->result_array();



    }



    function delete_row_file($id, $gbnum)

    {



        $this->db->where('gno', $gbnum);



        $this->db->where('rno', $id);



        $this->db->delete('ali_files');



        return '';



    }



    function delete_row_scoreall($rowId)

    {



        $this->db->where('assign_no', $rowId);



        $this->db->delete('ali_grade_new');



        return "deleted";



    }



    function delFile($id, $path)



    {



        $sql = "select filename from ali_files where no=" . $id . " ";



        $query = $this->db->query($sql);



        if ($query->num_rows() > 0) {



            $row = $query->row();



            $dirpath = $_SERVER['DOCUMENT_ROOT'] . "/userfiles/uploaded/" . $path . "/";



            unlink($dirpath . $row->filename) or die("fail remove");



            $this->db->where('no', $id);



            $this->db->delete('ali_files');



            return $row->filename;



        }



        return '';



    }





    function getAssignStudent($id, $studentno)



    {



        $sql = "select no,class_no,assign_no,assigncat_no,student_no,score from ali_grade_new where student_no=" . $studentno . " and assign_no=" . $id . " ";



        $query = $this->db->query($sql);



        return ($query->num_rows() > 0) ? true : false;



    }



    function update_row_score($params)

    {



        $updatedata = array(



            'score' => $params["score"],



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('regdate', 'now()', FALSE);



        $this->db->where('assign_no', $params["id"]);



        $this->db->where('student_no', $params["student_no"]);



        $this->db->update('ali_grade_new', $updatedata);



        return $params["id"];



    }



    function add_row_score($params)

    {



        $insdata = array(



            'class_no' => $params["class_no"],



            'assign_no' => $params["id"],



            'assigncat_no' => $params["assigncat_no"],



            'student_no' => $params["student_no"],



            'score' => $params["score"],



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('regdate', 'now()', FALSE);



        $this->db->insert('ali_grade_new', $insdata);



        return $this->db->insert_id();



    }



    function getSchoolRoster($params)



    {



        $this->load->helper('xml');

        $sql = "";



        $columns = array("fullname", "student_ID");



        if (isset($params["orderby"])) {



            if ($params["direct"] == 'des') $direct = "DESC"; else $direct = "ASC";



            $sql = " Order by " . $columns[$params["orderby"]] . " " . $direct;



        } else {



            $sql = " Order by students_no";



        }





        $searchquery = "";



        if (!empty($params["vseach"])) {



            $searchquery = " and CONCAT(lastname,' ',firstname) like '%" . $params["vseach"] . "%'";



        }





        $sql = "SELECT students_no,student_ID,CONCAT(lastname,', ',firstname) AS fullname FROM ali_students WHERE progress in ('r') and students_no not in (select students_no from ali_roster where class_no=" . $params["classno"] . ") " . $searchquery . $sql;



        $query = $this->db->query($sql);



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');



        foreach ($query->result_array() as $row5) {



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $row5['students_no']);



            $c1 = xml_add_child($item, 'cell', $row5['fullname'], true);



            $c3 = xml_add_child($item, 'cell', $row5['student_ID'], true);



        }



        return xml_print($dom, true);



    }



    function getClassStudents($params)



    {



        $this->load->helper('xml');

        $sql = "";



        $columns = array("fullname", "student_ID", "logindate", "");



        if (isset($params["orderby"])) {



            if ($params["direct"] == 'des') $direct = "DESC"; else $direct = "ASC";



            $sql = " Order by " . $columns[$params["orderby"]] . " " . $direct;



        } else {



            $sql = " Order by CONCAT(A.lastname,', ',A.firstname)";



        }





        $sql = "select A.students_no,CONCAT(A.lastname,', ',A.firstname) AS fullname,A.student_ID, IF(B.login is null,'Never Login In',B.login) as logindate, C.email from ali_roster AS A LEFT JOIN (select username, max(attempt_time) AS login from ali_user_login_attempt group by username) AS B ON A.student_ID=B.username LEFT JOIN ali_students AS C ON A.students_no=C.students_no WHERE C.progress='r' AND A.class_no=" . $params["classno"] . " " . $sql;



        $query = $this->db->query($sql);



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');



        foreach ($query->result_array() as $row5) {



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $row5['students_no']);



            $c1 = xml_add_child($item, 'cell', $row5['fullname'] . "^javascript:self.location.href=\"/index.php/aliweb/studentgrade?stno=" . $row5['students_no'] . "\";^_self", true);



            $c2 = xml_add_child($item, 'cell', $row5['student_ID'], true);



            $c3 = xml_add_child($item, 'cell', $row5['logindate'], true);



            $c5 = xml_add_child($item, 'cell', "<div class=\"ops\" style=\"width:16px;height:40px;\"><ul><li id=\"fontgear\"></li><ul><li><a href=\"#\" onClick=\"doOnLoadMessage(" . $row5['students_no'] . ",'" . $row5['fullname'] . "','" . $row5['email'] . "');\">Send Message</a></li></ul></ul></div>", true);



        }



        return xml_print($dom, true);



    }





    function getComClasses($params)

    {



        $this->load->helper('xml');



        $sql = "";



        $dom = xml_dom();



        $comp = xml_add_child($dom, 'complete');





        $sql = "SELECT `no`,`name` FROM `ali_class` WHERE no not in (" . $params["cno"] . ") and classtype='" . $params["classtype"] . "' and `schoolyear`='" . $params["year"] . "' and `trimester`='" . $params["trim"] . "' and status=0";



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row5) {



            $item2 = xml_add_child($comp, 'option', $row5['name'], false);



            xml_add_attribute($item2, 'value', $row5['no']);



        }



        return xml_print($dom, true);



    }





    function getComRClasses($params)

    {



        $this->load->helper('xml');



        $sql = "";



        $dom = xml_dom();



        $comp = xml_add_child($dom, 'complete');





        $sql = "SELECT `no`,`name` FROM `ali_remedialclass` WHERE `schoolyear`='" . $params["year"] . "' and `trimester`='" . $params["trim"] . "' and status=0";



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row5) {



            $item2 = xml_add_child($comp, 'option', $row5['name'], false);



            xml_add_attribute($item2, 'value', $row5['no']);



        }



        return xml_print($dom, true);



    }





    function getComMessageClasses($params)

    {



        $this->load->helper('xml');



        $sql = "";



        $dom = xml_dom();



        $comp = xml_add_child($dom, 'complete');



        $sql = "SELECT ac.name,ac.no FROM `ali_roster` as ar inner join `ali_gradingperiod` as ag on ar.schoolyear=ag.schoolyear and ar.trimester=ag.gradingperiod inner join ali_class as ac on ar.class_no=ac.no where ag.active=1 group by ac.name,ac.no order by ac.name,ac.no ";



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row5) {



            $item2 = xml_add_child($comp, 'option', $row5['name'], false);



            xml_add_attribute($item2, 'value', $row5['no']);



        }



        return xml_print($dom, true);



    }





    function getClassList($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');



        $head = xml_add_child($rows, 'head', NULL, true);





        $colm0 = xml_add_child($head, 'column', "No", true);



        xml_add_attribute($colm0, 'id', "seq");



        xml_add_attribute($colm0, 'width', "30");



        xml_add_attribute($colm0, 'type', "ro");



        xml_add_attribute($colm0, 'align', "center");



        xml_add_attribute($colm0, 'color', "white");



        xml_add_attribute($colm0, 'sort', "int");





        $colm0 = xml_add_child($head, 'column', "Class", true);



        xml_add_attribute($colm0, 'id', "classname");



        xml_add_attribute($colm0, 'width', "120");



        xml_add_attribute($colm0, 'type', "ro");



        xml_add_attribute($colm0, 'align', "center");



        xml_add_attribute($colm0, 'color', "white");



        xml_add_attribute($colm0, 'sort', "str");





        $colm1 = xml_add_child($head, 'column', "Teacher", true);



        xml_add_attribute($colm1, 'id', "teachername");



        xml_add_attribute($colm1, 'width', "100");



        xml_add_attribute($colm1, 'type', "co");



        xml_add_attribute($colm1, 'align', "center");



        xml_add_attribute($colm1, 'color', "white");



        xml_add_attribute($colm1, 'sort', "int");



        $sql = "SELECT no, concat(firstname,' ',lastname) as teachername FROM ali_user WHERE active=1 and roleid=3 ORDER BY concat(firstname,' ',lastname) ";



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row1) {



            $o1 = xml_add_child($colm1, 'option', $row1["teachername"], false);



            xml_add_attribute($o1, 'value', $row1["no"]);



        }





        $colm0 = xml_add_child($head, 'column', "teacher_no", true);



        xml_add_attribute($colm0, 'id', "teacher_no");



        xml_add_attribute($colm0, 'width', "0");



        xml_add_attribute($colm0, 'type', "ro");



        xml_add_attribute($colm0, 'align', "center");



        xml_add_attribute($colm0, 'color', "white");



        xml_add_attribute($colm0, 'sort', "int");





        $sql = "";



        $columns = array("seq", "classname", "teachername", "teacher_no");



        if (isset($params["orderby"])) {



            if ($params["direct"] == 'des') $direct = "DESC"; else $direct = "ASC";



            $sql = " Order by " . $columns[$params["orderby"]] . " " . $direct;



        } else {



            $sql = " Order by sc.session, sc.name";



        }





        $subquery = "";



        if ($params["level"] != "") {



            $subquery .= " and sc.level=" . $params["level"];



        }



        if ($params["session"] != "") {



            $subquery .= " and sc.session=" . $params["session"];



        }





        $sql = "select sc.no, sc.name as classname, ct.teachername, ct.teacher_no from ali_class as sc left join ali_classteachers as ct on sc.no=ct.class_no where (ct.isprimary =1 OR ct.isprimary IS NULL ) and sc.status=0 and sc.schoolyear='" . $params["year"] . "' and sc.trimester='" . $params["trim"] . "' " . $subquery . $sql;



        $query = $this->db->query($sql);



        $i = 1;



        foreach ($query->result_array() as $row5) {





            $item = xml_add_child($rows, 'row', NULL, true);



            xml_add_attribute($item, 'id', $row5['no']);



            $c0 = xml_add_child($item, 'cell', $i, true);



            $c1 = xml_add_child($item, 'cell', $row5['classname'], true);



            $c2 = xml_add_child($item, 'cell', $row5['teachername'], true);



            $c3 = xml_add_child($item, 'cell', $row5['teacher_no'], true);



            $i++;





        }



        return xml_print($dom, true);



    }



    function setClassList($params)



    {



        $action = "";



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $ids = explode(",", $params["ids"]);



        for ($i = 0; $i < sizeof($ids); $i++) {



            $rowId = $ids[$i]; //id or row which was updated



            $newId = $rowId; //will be used for insert operation



            $mode = $params[$rowId . "_!nativeeditor_status"]; //get request mode



            switch ($mode) {



                case "inserted":

                    $action = $mode;

                    break;



                case "deleted":

                    $action = $this->delete_row_classlist($rowId);

                    break;



                case "updated":

                    $action = $this->update_row_classlist($rowId, $params);

                    break;



            }



        }



        $action2 = xml_add_child($data, 'action', $action, false);



        xml_add_attribute($action2, 'type', $action);



        xml_add_attribute($action2, 'sid', $rowId);



        xml_add_attribute($action2, 'tid', $newId);



        return xml_print($dom, true);



    }



    function update_row_classlist($rowId, $params)

    {



        $sql = "select teacher_no from ali_classteachers where class_no=" . $rowId . " and teacher_no=" . $params[$rowId . "_c2"];



        $query = $this->db->query($sql);



        if ($query->num_rows() > 0) {



            $row5 = $query->row();



            $updatedata1 = array(



                'isprimary' => 0,



                'writer' => $this->session->userdata('ALISESS_USERNAME')



            );



            $this->db->set('updatedate', 'now()', FALSE);



            $this->db->where('class_no', $rowId);



            $this->db->update('ali_classteachers', $updatedata1);





            $updatedata1 = array(



                'isprimary' => 1,



                'writer' => $this->session->userdata('ALISESS_USERNAME')



            );



            $this->db->set('updatedate', 'now()', FALSE);



            $this->db->where('class_no', $rowId);



            $this->db->where('teacher_no', $params[$rowId . "_c2"]);



            $this->db->update('ali_classteachers', $updatedata1);



        } else {





            $sql2 = "select CONCAT(firstname,' ',lastname) as fullname from ali_user where active=1 and roleid=3 and no=" . $params[$rowId . "_c2"];



            $query2 = $this->db->query($sql2);



            if ($query2->num_rows() > 0) {



                $updatedata1 = array(



                    'isprimary' => 0,



                    'writer' => $this->session->userdata('ALISESS_USERNAME')



                );



                $this->db->set('updatedate', 'now()', FALSE);



                $this->db->where('class_no', $rowId);



                $this->db->update('ali_classteachers', $updatedata1);





                $row2 = $query2->row();



                $insdata = array(



                    'class_no' => $rowId,



                    'teacher_no' => $params[$rowId . "_c2"],



                    'teachername' => $row2->fullname,



                    'isprimary' => 1,



                    'permission' => 0,



                    'writer' => $this->session->userdata('ALISESS_USERNAME')



                );



                $this->db->set('regdate', 'now()', FALSE);



                $this->db->insert('ali_classteachers', $insdata);



                $this->newId = $this->db->insert_id();



            }



        }





        return "updated";



    }



    function delete_row_classlist($rowId)

    {



        //$this->db->where('class_no', $rowId);  $this->db->delete('ali_grade_new');



        //$this->db->where('class_no', $rowId);  $this->db->delete('ali_assignments');



        $this->db->where('class_no', $rowId);

        $this->db->delete('ali_assign_cate');



        $this->db->where('class_no', $rowId);

        $this->db->delete('ali_classteachers');



        $this->db->where('class_no', $rowId);

        $this->db->delete('ali_roster');



        //$this->db->where('no', $rowId);  $this->db->delete('ali_class');



        //$this->db->where('class_no', $rowId);  $this->db->delete('ali_attendance_new');





        $this->db->where('no', $rowId);



        $this->db->delete('ali_class');



        return "deleted";



    }


    function getExportStudentList($params)
    {
        $arrStatus = array(
            'n'=>"Initial-Transfer in",
            'r'=>"Active",
            'w'=>"Withdrawn-Transfer out",
            'c'=>"Consultations",
            'a'=>"Acceptance",
            'v'=>"Vacation",
            'm'=>"Med-Leave",
            's'=>"COS-Approved",
            'd'=>"Continuing Education",
            'p'=>"Initial-COS Approved",
            'f'=>"Cancelled",
            'l'=>"Complete",
            'o'=>"COS",
            'e'=>"Initial-Visa Interview",
            't'=>"Withdrawn-Terminated. No Show",
            'h'=>"Withdrawn-AEW",
            'n'=>"new",
            'd'=>"transfer"
        );

        $returnData[] = array();
        $returnData["1"][0] = "No";
        $returnData["1"][1] = "FistName";
        $returnData["1"][2] = "LastName";
        $returnData["1"][3] = "D.O.B";
        $returnData["1"][4] = "Status";
        $returnData["1"][5] = "Nationality";
        $returnData["1"][6] = "Email";
        $returnData["1"][7] = "Note";


        $this->db->select("students_no,firstname,lastname,nickname,birthday,progress,note,country,cellphone,cellphone2,email,email2,address1,address2,items,preschool,transfer,student_ID,gender,emergencyphone,emergencyphone2,etc_memo,register_day,memo,user_ID");
        $this->db->from("ali_students");
        if (isset($params["keyword"])) {
            switch ($params["keyword"]) {
                case 1:
                    $this->db->like("UPPER(email)", strtoupper($params["searchword"]));
                    break;
                case 2:
                    $this->db->like("UPPER(country)", strtoupper($params["searchword"]));
                    break;
                case 3:
                    $this->db->where("birthday", $params["searchword"]);
                    break;
                case 4:
                    $this->db->where("register_day", $params["searchword"]);
                    break;
            }
        }
        if (isset($params["vstatus"])) {
            $pieces = explode(",",$params["vstatus"]);
            $this->db->where_in('progress',$pieces);
        }
        $query = $this->db->get();
        $i = 1;
        foreach ($query->result_array() as $row5) {
            $returnData["" . $row5['students_no'] . ""][0] = $i;
            $returnData["" . $row5['students_no'] . ""][1] = $row5['firstname'];
            $returnData["" . $row5['students_no'] . ""][2] = $row5['lastname'];
            $returnData["" . $row5['students_no'] . ""][3] = $row5['birthday'];
            $returnData["" . $row5['students_no'] . ""][4] = $arrStatus["".$row5['progress'].""];
            $returnData["" . $row5['students_no'] . ""][5] = $row5['country'];
            $returnData["" . $row5['students_no'] . ""][6] = $row5['email'];
            $returnData["" . $row5['students_no'] . ""][7] = $row5['note'];
            $i++;
        }
        return $returnData;
    }

    function getExportGrade($params)
    {
        $returnData[] = array();

        $totalCategoryRate=0;
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
            $arravg["".$row9['assigncat_no'].""]["gradeavg"] = $row9["scoreavg"];
        }

        $k=1;
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
            $returnData["1".$k][0] = $row0['name']." : ".$categrade." (counts as ".$row0['wpercentage']."% of grade)";
            $returnData["1".$k][1] = "";
            $returnData["1".$k][2] = "";
            $returnData["1".$k][3] = "";

            $returnData["2".$k][0] = "ASSIGNMENT";
            $returnData["2".$k][1] = "DUE";
            $returnData["2".$k][2] = "SCORE";
            $returnData["2".$k][3] = "POSSIBLE";

            for($s=0; $s < count($data); $s++){
                $returnData["" . $data[$s]['assignno'] . ""][0] = $data[$s]['assignname'];
                $returnData["" . $data[$s]['assignno'] . ""][1] = $data[$s]['duedate'];
                $returnData["" . $data[$s]['assignno'] . ""][2] = $data[$s]['score'];
                $returnData["" . $data[$s]['assignno'] . ""][3] = $data[$s]['points'];
            }
            $k++;
        }

        return $returnData;
    }




    function getExportWLetter($params)
    {
        $returnData[] = array();
        $returnData["1"][0] = "No";
        $returnData["1"][1] = "Student";
        $returnData["1"][2] = "ID";
        $returnData["1"][3] = "Level";
        $returnData["1"][4] = "Session";
        $returnData["1"][5] = "LS(Grade)";
        $returnData["1"][6] = "LS(P)";
        $returnData["1"][7] = "LS(T)";
        $returnData["1"][8] = "LS(A)";
        $returnData["1"][9] = "LS(Total)";
        $returnData["1"][10] = "RW(Grade)";
        $returnData["1"][11] = "RW(P)";
        $returnData["1"][12] = "RW(T)";
        $returnData["1"][13] = "RW(A)";
        $returnData["1"][14] = "RW(Total)";
        $returnData["1"][15] = "Sum of Tardy";
        $returnData["1"][16] = "Sum of Absent";
        $returnData["1"][17] = "Attendance(%)";
        $returnData["1"][18] = "Remediation(P)";
        $returnData["1"][19] = "Remediation(A)";
        $returnData["1"][20] = "Remediation(Total)";
        $returnData["1"][21] = "Attendance(%)";
        $returnData["1"][22] = "Total Att"; //20180208
        $sql = "SELECT CONCAT(aa.lastname,', ',aa.firstname) AS fullname,aa.students_no,level,SUM(CASE WHEN (aa.classtype='LS') THEN aa.class_no ELSE 0 END) AS LSno, SUM(CASE WHEN (aa.classtype='RW') THEN aa.class_no ELSE 0 END) AS RWno,bb.student_ID,bb.email FROM ali_roster AS aa inner join ali_students as bb on aa.students_no=bb.students_no where  bb.progress='r' and aa.schoolyear='" . $params["year"] . "' and aa.trimester='" . $params["trim"] . "' GROUP BY CONCAT(aa.lastname,', ',aa.firstname), aa.students_no, bb.student_ID,bb.email";
        $query = $this->db->query($sql);
        $i = 1;
        foreach ($query->result_array() as $row5) {
            $ls_p = 0;
            $ls_a = 0;
            $ls_t = 0;
            $sql1 = "SELECT marks FROM `ali_attendance_new` where student_no=" . $row5['students_no'] . " and class_no=" . $row5['LSno'] . " ";
            $query1 = $this->db->query($sql1);
            foreach ($query1->result_array() as $row1) {
                $vl = substr($row1["marks"], 0, 1);
                if ($vl == "P") {
                    $ls_p++;
                }
                if ($vl == "T") {
                    $ls_t++;
                }
                if ($vl == "A") {
                    $ls_a++;
                }
                $vr = substr($row1["marks"], 1, 1);
                if ($vr == "P") {
                    $ls_p++;
                }
                if ($vr == "T") {
                    $ls_t++;
                }
                if ($vr == "A") {
                    $ls_a++;
                }
                $level=$row5['level'];
            }
            $sum_ls = $ls_p + $ls_t + $ls_a;
            $rw_p = 0;
            $rw_a = 0;
            $rw_t = 0;
            $sql2 = "SELECT marks FROM `ali_attendance_new` where student_no=" . $row5['students_no'] . " and class_no=" . $row5['RWno'] . " ";
            $query2 = $this->db->query($sql2);
            foreach ($query2->result_array() as $row2) {
                $wl = substr($row2["marks"], 0, 1);
                if ($wl == "P") {
                    $rw_p++;
                }
                if ($wl == "T") {
                    $rw_t++;
                }
                if ($wl == "A") {
                    $rw_a++;
                }
                $wr = substr($row2["marks"], 1, 1);
                if ($wr == "P") {
                    $rw_p++;
                }
                if ($wr == "T") {
                    $rw_t++;
                }
                if ($wr == "A") {
                    $rw_a++;
                }
            }
            $sum_rw = $rw_p + $rw_t + $rw_a;
            $totalabsent = ($ls_a + $rw_a);
            $tardytoabsent = 0;
            $tardyrest = 0;
            $totaltardy = ($ls_t + $rw_t);
            //20180208
            if ($totaltardy > 0) {

                $tardytoabsent = floor($totaltardy / 3);

                $tardyrest = $totaltardy % 3;

            }

            $sumttl = $sum_ls + $sum_rw;

            if ($sumttl > 0) {

                if (($params["year"] >= 2017 && $params["trim"] > 2) || $params["year"] > 2017) {

                    $attrate =round( 100 - (($tardytoabsent + $totalabsent +($tardyrest * 0.3)) / 200 * 100), 0);



                } else {
                    $attrate =100 - round( (($tardytoabsent + $totalabsent) / ($sum_ls + $sum_rw)) * 100, 0);
                }


            } else {
                $attrate = 0;
            }

            //to levelname 20170116

            $sql4 = " SELECT levelname from ali_level where levelvalue=" . $level . " ";


            $query4 = $this->db->query($sql4);

            foreach ($query4->result_array() as $row4) {

                $level = $row4["levelname"];
            }


            $lsclassname = "";
            $rwclassname = "";
            $sql8 = "select name as classname from ali_class where no=" . $row5['LSno'];
            $query8 = $this->db->query($sql8);
            foreach ($query8->result_array() as $row8) {
                $lsclassname = $row8["classname"];
            }
            $sql9 = "select name as classname from ali_class where no=" . $row5['RWno'];
            $query9 = $this->db->query($sql9);
            foreach ($query9->result_array() as $row9) {
                $rwclassname = $row9["classname"];
            }
            $checkam = "AM";
            $checkpm = "PM";
            $session = "";

            if(stristr($lsclassname, $checkam) !== false){
                $session= "AM";
            }elseif(stristr($lsclassname, $checkpm) !== false){
                $session= "PM";
            }else{
                return $session="";
            }


            $LSfg = $this->getTotalGrade($row5['LSno'], $row5['students_no']);
            if ($LSfg >= 0 && $LSfg < 60) {
                $LSgl = "F";
            } elseif ($LSfg >= 60 && $LSfg < 70) {
                $LSgl = "D";
            } elseif ($LSfg >= 70 && $LSfg < 80) {
                $LSgl = "C";
            } elseif ($LSfg >= 80 && $LSfg < 90) {
                $LSgl = "B";
            } elseif ($LSfg >= 90 && $LSfg <= 100) {
                $LSgl = "A";
            } else {
                $LSgl = "error";
            }
            $LSgl = $LSgl . " " . $LSfg;
            $RWfg = $this->getTotalGrade($row5['RWno'], $row5['students_no']);
            if ($RWfg >= 0 && $RWfg < 60) {
                $RWgl = "F";
            } elseif ($RWfg >= 60 && $RWfg < 70) {
                $RWgl = "D";
            } elseif ($RWfg >= 70 && $RWfg < 80) {
                $RWgl = "C";
            } elseif ($RWfg >= 80 && $RWfg < 90) {
                $RWgl = "B";
            } elseif ($RWfg >= 90 && $RWfg <= 100) {
                $RWgl = "A";
            } else {
                $RWgl = "error";
            }
            $RWgl = $RWgl . " " . $RWfg;
            $returnData["" . $row5['students_no'] . ""][0] = $i;
            $returnData["" . $row5['students_no'] . ""][1] = $row5['fullname'];
            $returnData["" . $row5['students_no'] . ""][2] = $row5['student_ID'];
            $returnData["" . $row5['students_no'] . ""][3] = $level;
            $returnData["" . $row5['students_no'] . ""][4] = $session;
            $returnData["" . $row5['students_no'] . ""][5] = $LSgl;
            $returnData["" . $row5['students_no'] . ""][6] = $ls_p;
            $returnData["" . $row5['students_no'] . ""][7] = $ls_t;
            $returnData["" . $row5['students_no'] . ""][8] = $ls_a;
            $returnData["" . $row5['students_no'] . ""][9] = $sum_ls;
            $returnData["" . $row5['students_no'] . ""][10] = $RWgl;
            $returnData["" . $row5['students_no'] . ""][11] = $rw_p;
            $returnData["" . $row5['students_no'] . ""][12] = $rw_t;
            $returnData["" . $row5['students_no'] . ""][13] = $rw_a;
            $returnData["" . $row5['students_no'] . ""][14] = $sum_rw;
            $returnData["" . $row5['students_no'] . ""][15] = $totaltardy;
            $returnData["" . $row5['students_no'] . ""][16] = $totalabsent;
            $returnData["" . $row5['students_no'] . ""][17] = $attrate;
            $sum_p8 = 0;
            $sum_a8 = 0;
            $sum_ls8 = "";
            $rattrate8 = "";
            $totalatt =""; //20180208
            $sql8 = "select bb.marks from ali_remedialattendance  as bb inner join ali_remedialroster as aa on aa.students_no=bb.student_no and aa.class_no=bb.class_no and aa.schoolyear='" . $params["year"] . "' and aa.trimester='" . $params["trim"] . "' and bb.student_no=" . $row5['students_no'];
            $query8 = $this->db->query($sql8);
            foreach ($query8->result_array() as $row8) {
                $v8 = $row8["marks"];
                if ($v8 == "P") {
                    $sum_p8++;
                }
                if ($v8 == "A") {
                    $sum_a8++;
                }
            }
            if ($sum_a8 > 0 || $sum_p8 > 0) {

                $sum_ls8 = $sum_p8 + $sum_a8;

            }


            if ($sum_a8 >= 0 && $sum_ls8 > 0) {



                $rattrate8 =  round(100 -($sum_a8 / $sum_ls8) * 100,0 );



            }

            if (($params["year"] >= 2017 && $params["trim"] > 2) || $params["year"] > 2017) {



                $totalatt = round(((200+$sum_ls8)-($sum_a8+$totalabsent+$tardytoabsent+$tardyrest*0.3))/( 200+$sum_ls8 )*100,0);



            } else {

                $totalatt = round(((200 + $sum_ls + $sum_rw) - ($sum_a8 + $totalabsent + $tardytoabsent + $tardyrest * 0.3)) / (200 + $sum_ls + $sum_rw) * 100, 0);
            }

            $returnData["" . $row5['students_no'] . ""][18] = $sum_p8;
            $returnData["" . $row5['students_no'] . ""][19] = $sum_a8;
            $returnData["" . $row5['students_no'] . ""][20] = $sum_ls8;
            $returnData["" . $row5['students_no'] . ""][21] = $rattrate8;
            $first = 'false';
            $second = 'false';
            $terminate = 'false';
            $sql3 = "SELECT first,second,terminate FROM ali_warningletter_new where students_no=" . $row5['students_no'];
            $query3 = $this->db->query($sql3);
            foreach ($query3->result_array() as $row3) {
                $first = ($row3["first"] == 1) ? 'true' : 'false';
                $second = ($row3["second"] == 1) ? 'true' : 'false';
                $terminate = ($row3["terminate"] == 1) ? 'true' : 'false';
            }
            $returnData["" . $row5['students_no'] . ""][22] = $totalatt; //20180208
            $i++;
        }
        return $returnData;
    }





    function getWarningLetter($params)



    {



        $this->load->helper('xml');

        $sql = "";



        $columns = array("seq", "fullname", "");



        if (isset($params["orderby"])) {



            if ($params["direct"] == 'des') $direct = "DESC"; else $direct = "ASC";



            $sql = " Order by " . $columns[$params["orderby"]] . " " . $direct;



        } else {



            $sql = " Order by CONCAT(aa.lastname,', ',aa.firstname)";



        }





//        $sql = "select aa.no,CONCAT(aa.lastname,', ',aa.firstname) AS fullname,aa.students_no,aa.class_no,aa.classtype,bb.student_ID from ali_roster as aa inner join ali_students as bb on aa.students_no=bb.students_no WHERE bb.progress='r' and aa.schoolyear='".$params["year"]."' and aa.trimester='".$params["trim"]."' ".$sql;



        $sql = "SELECT CONCAT(aa.lastname,', ',aa.firstname) AS fullname,aa.students_no, level,SUM(CASE WHEN (aa.classtype='LS') THEN aa.class_no ELSE 0 END) AS LSno, SUM(CASE WHEN (aa.classtype='RW') THEN aa.class_no ELSE 0 END) AS RWno,bb.student_ID,bb.email FROM ali_roster AS aa inner join ali_students as bb on aa.students_no=bb.students_no where  bb.progress='r' and aa.schoolyear='" . $params["year"] . "' and aa.trimester='" . $params["trim"] . "' GROUP BY CONCAT(aa.lastname,', ',aa.firstname), aa.students_no, bb.student_ID,bb.email";



        $query = $this->db->query($sql);



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');


        $i = 1;



        foreach ($query->result_array() as $row5) {



            $ls_p = 0;



            $ls_a = 0;



            $ls_t = 0;

            //20180108
            $level= $row5['level'];


            $sql1 = "SELECT marks FROM `ali_attendance_new` where student_no=" . $row5['students_no'] . " and class_no=" . $row5['LSno'] . " ";



            $query1 = $this->db->query($sql1);



            foreach ($query1->result_array() as $row1) {



                $vl = substr($row1["marks"], 0, 1);



                if ($vl == "P") {

                    $ls_p++;

                }



                if ($vl == "T") {

                    $ls_t++;

                }



                if ($vl == "A") {

                    $ls_a++;

                }



                $vr = substr($row1["marks"], 1, 1);



                if ($vr == "P") {

                    $ls_p++;

                }



                if ($vr == "T") {

                    $ls_t++;

                }



                if ($vr == "A") {

                    $ls_a++;

                }



            }



            $sum_ls = $ls_p + $ls_t + $ls_a;





            $rw_p = 0;



            $rw_a = 0;



            $rw_t = 0;



            $sql2 = "SELECT marks FROM `ali_attendance_new` where student_no=" . $row5['students_no'] . " and class_no=" . $row5['RWno'] . " ";



            $query2 = $this->db->query($sql2);



            foreach ($query2->result_array() as $row2) {



                $wl = substr($row2["marks"], 0, 1);



                if ($wl == "P") {

                    $rw_p++;

                }



                if ($wl == "T") {

                    $rw_t++;

                }



                if ($wl == "A") {

                    $rw_a++;

                }



                $wr = substr($row2["marks"], 1, 1);



                if ($wr == "P") {

                    $rw_p++;

                }



                if ($wr == "T") {

                    $rw_t++;

                }



                if ($wr == "A") {

                    $rw_a++;

                }



            }



            $sum_rw = $rw_p + $rw_t + $rw_a;




            $totalabsent=0;
            $totalabsent = ($ls_a + $rw_a);





            $tardytoabsent = 0;

            $tardyrest = 0;


            $totaltardy =0;
            $totaltardy = ($ls_t + $rw_t);



            if ($totaltardy > 0) {



                $tardytoabsent = floor($totaltardy / 3);

                $tardyrest = $totaltardy % 3;



            }





            $sumttl = $sum_ls + $sum_rw;



            if ($sumttl > 0) {

                if (($params["year"] >= 2017 && $params["trim"] > 2) || $params["year"] > 2017) {


                    //20180207
                    $attrate =round( 100 - (($tardytoabsent + $totalabsent +($tardyrest * 0.3)) / 200 * 100), 0);



                } else {
                    //20180207
                    $attrate =round(100- (($tardytoabsent + $totalabsent +($tardyrest * 0.3) ) / ($sum_ls + $sum_rw) * 100), 0);

                }



            } else {



                $attrate = 0;



            }





            // list($lsclassname,$gpname) = $this->classGP($row5['LSno']);



            // list($rwclassname,$gpname) = $this->classGP($row5['RWno']);


            //to get levelname 20170108
            $sql4 = " SELECT levelname from ali_level where levelvalue=" . $level . " ";


            $query4 = $this->db->query($sql4);

            foreach ($query4->result_array() as $row4) {



                $level = $row4["levelname"];



            }



            $lsclassname = "";



            $rwclassname = "";



            $sql8 = "select name as classname from ali_class where no=" . $row5['LSno'];



            $query8 = $this->db->query($sql8);



            foreach ($query8->result_array() as $row8) {



                $lsclassname = $row8["classname"];



            }



            $sql9 = "select name as classname from ali_class where no=" . $row5['RWno'];



            $query9 = $this->db->query($sql9);



            foreach ($query9->result_array() as $row9) {



                $rwclassname = $row9["classname"];



            }

            //to get the session in warningletter 20170108
            $checkam = "AM";
            $checkpm = "PM";
            $session = "";

            //to get the session in warningletter 20170116
            if(stristr($lsclassname, $checkam) !== false){
                $session= "AM";
            }elseif(stristr($lsclassname, $checkpm) !== false){
                $session= "PM";
            }else{
                return $session="";
            }



            $LSfg = $this->getTotalGrade($row5['LSno'], $row5['students_no']);



            if ($LSfg >= 0 && $LSfg < 60) {



                $LSgl = "F";



            } elseif ($LSfg >= 60 && $LSfg < 70) {



                $LSgl = "D";



            } elseif ($LSfg >= 70 && $LSfg < 80) {



                $LSgl = "C";



            } elseif ($LSfg >= 80 && $LSfg < 90) {



                $LSgl = "B";



            } elseif ($LSfg >= 90 && $LSfg <= 100) {



                $LSgl = "A";



            } else {



                $LSgl = "error";



            }



            $LSgl = $LSgl . "&nbsp;&nbsp;&nbsp;" . $LSfg;





            $RWfg = $this->getTotalGrade($row5['RWno'], $row5['students_no']);



            if ($RWfg >= 0 && $RWfg < 60) {



                $RWgl = "F";



            } elseif ($RWfg >= 60 && $RWfg < 70) {



                $RWgl = "D";



            } elseif ($RWfg >= 70 && $RWfg < 80) {



                $RWgl = "C";



            } elseif ($RWfg >= 80 && $RWfg < 90) {



                $RWgl = "B";



            } elseif ($RWfg >= 90 && $RWfg <= 100) {



                $RWgl = "A";



            } else {



                $RWgl = "error";



            }



            $RWgl = $RWgl . "&nbsp;&nbsp;&nbsp;" . $RWfg;

            //20180207
            $totalatt ="";



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $row5['students_no']);



            $c1 = xml_add_child($item, 'cell', $i, true);



            $c2 = xml_add_child($item, 'cell', $row5['fullname'], true);



            $c3 = xml_add_child($item, 'cell', $row5['student_ID'], true);



            $c16 = xml_add_child($item, 'cell', $level, true);



            $c17 = xml_add_child($item, 'cell', $session, true);


            $c15 = xml_add_child($item, 'cell', $LSgl, true);

            $c4 = xml_add_child($item, 'cell', $ls_p, true);

            xml_add_attribute($c4, 'title', $lsclassname);



            $c5 = xml_add_child($item, 'cell', $ls_t, true);

            xml_add_attribute($c5, 'title', $lsclassname);



            $c6 = xml_add_child($item, 'cell', $ls_a, true);

            xml_add_attribute($c6, 'title', $lsclassname);



            $c7 = xml_add_child($item, 'cell', $sum_ls, true);



            $c16 = xml_add_child($item, 'cell', $RWgl, true);



            $c8 = xml_add_child($item, 'cell', $rw_p, true);

            xml_add_attribute($c8, 'title', $rwclassname);



            $c9 = xml_add_child($item, 'cell', $rw_t, true);

            xml_add_attribute($c9, 'title', $rwclassname);



            $c10 = xml_add_child($item, 'cell', $rw_a, true);

            xml_add_attribute($c10, 'title', $rwclassname);



            $c11 = xml_add_child($item, 'cell', $sum_rw, true);



            $c12 = xml_add_child($item, 'cell', $totaltardy, true);



            $c13 = xml_add_child($item, 'cell', $totalabsent, true);



            $c14 = xml_add_child($item, 'cell', $attrate, true);







            $first = 'false';



            $second = 'false';



            $terminate = 'false';



            $sql3 = "SELECT first,second,terminate FROM ali_warningletter_new where students_no='" . $row5['students_no']."'and schoolyear='" . $params["year"] . "' and trimester=" . $params["trim"];



            $query3 = $this->db->query($sql3);



            foreach ($query3->result_array() as $row3) {



                $first = ($row3["first"] == 1) ? 'true' : 'false';



                $second = ($row3["second"] == 1) ? 'true' : 'false';



                $terminate = ($row3["terminate"] == 1) ? 'true' : 'false';



            }





            $sum_p8 = 0;



            $sum_a8 = 0;



            $sum_ls8 = 0;



            $rattrate8 = "";



            $sql8 = "select bb.marks from ali_remedialattendance  as bb inner join ali_remedialroster as aa on aa.students_no=bb.student_no and aa.class_no=bb.class_no and aa.schoolyear='" . $params["year"] . "' and aa.trimester='" . $params["trim"] . "' and bb.student_no=" . $row5['students_no'];



            $query8 = $this->db->query($sql8);



            foreach ($query8->result_array() as $row8) {



                $v8 = $row8["marks"];



                if ($v8 == "P") {

                    $sum_p8++;

                }



                if ($v8 == "A") {

                    $sum_a8++;

                }





            }

            if ($sum_a8 > 0 || $sum_p8 > 0) {



                $sum_ls8 = $sum_p8 + $sum_a8;



            }



            if ($sum_a8 >= 0 && $sum_ls8 > 0) {



                $rattrate8 =  round(100 -($sum_a8 / $sum_ls8) * 100,0 );



            }



            if (($params["year"] >= 2017 && $params["trim"] > 2) || $params["year"] > 2017) {



                $totalatt = round(((200+$sum_ls8)-($sum_a8+$totalabsent+$tardytoabsent+$tardyrest*0.3))/( 200+$sum_ls8 )*100,0);



            } else {

                $totalatt = round((($sum_ls8 + $sum_ls + $sum_rw)-($sum_a8+$totalabsent+$tardytoabsent+$tardyrest*0.3))/ ($sum_ls8+$sum_ls + $sum_rw)*100 , 0);




            }


            $c15 = xml_add_child($item, 'cell', $sum_p8, true);



            $c16 = xml_add_child($item, 'cell', $sum_a8, true);



            $c17 = xml_add_child($item, 'cell', $sum_ls8, true);



            $c18 = xml_add_child($item, 'cell', $rattrate8, true);





            $c19 = xml_add_child($item, 'cell', $first, false);

            xml_add_attribute($c19, "disabled", $first);



            $c20 = xml_add_child($item, 'cell', $second, false);

            xml_add_attribute($c20, "disabled", $second);



            $c21 = xml_add_child($item, 'cell', $terminate, false);

            xml_add_attribute($c21, "disabled", $terminate);



            $c22 = xml_add_child($item, 'cell', "<div class=\"ops\" style=\"width:16px;height:40px;\"><ul><li id=\"fontgear\"></li><ul><li><a href=\"#\" onClick=\"doOnLoadInfo(" . $row5['students_no'] . ",'" . $row5['fullname'] . "','" . $row5['email'] . "');\">Edit</a></li></ul></ul></div>", true);

            //20180208
            $c23 = xml_add_child($item, 'cell', $totalatt,true);



            $i++;



        }





        return xml_print($dom, true);


    }






    function update_row_warningletter($params)

    {



        $w1 = 0;



        $w2 = 0;



        $tn = 0;





        $sql3 = "SELECT no,first,second,terminate FROM `ali_warningletter_new` where schoolyear='" . $params['schoolyear'] . "' and  trimester='" . $params['trimester'] . "' and students_no=" . $params['receiver'];



        $query3 = $this->db->query($sql3);



        $result = $query3->result_array();



        if ($query3->num_rows() == 1) {



            //$ret = $result[0];



            $updatedata = array(



                'writer' => $this->session->userdata('ALISESS_USERNAME')



            );



            if (trim($params["wtn"]) == "wt1") {



                $this->db->set('first', 1, TRUE);



            }



            if (trim($params["wtn"]) == "wt2") {



                $this->db->set('second', 1, TRUE);



            }



            if (trim($params["wtn"]) == "ttn") {



                $this->db->set('terminate', 1, TRUE);



            }



            $this->db->set('regdate', 'now()', FALSE);



            $this->db->where('students_no', $params['receiver']);



            $this->db->where('schoolyear', $params['schoolyear']);



            $this->db->where('trimester', $params['trimester']);



            $this->db->update('ali_warningletter_new', $updatedata);





        } else {





            if (trim($params["wtn"]) == "wt1") {



                $w1 = 1;



            }



            if (trim($params["wtn"]) == "wt2") {



                $w2 = 1;



            }



            if (trim($params["wtn"]) == "ttn") {



                $tn = 1;



            }





            $insdata = array(



                'schoolyear' => $params["schoolyear"],



                'trimester' => $params["trimester"],



                'students_no' => $params["receiver"],



                'first' => $w1,



                'second' => $w2,



                'terminate' => $tn,



                'writer' => $this->session->userdata('ALISESS_USERNAME')



            );



            $this->db->set('regdate', 'now()', FALSE);



            $this->db->insert('ali_warningletter_new', $insdata);



        }





        // return $this->db->insert_id();



    }



    function getAssignRoster($params)



    {



        $this->load->helper('xml');

        $sql = "";



        $columns = array("seq", "fullname", "");



        if (isset($params["orderby"])) {



            if ($params["direct"] == 'des') $direct = "DESC"; else $direct = "ASC";



            $sql = " Order by " . $columns[$params["orderby"]] . " " . $direct;



        } else {



            $sql = " Order by CONCAT(aa.lastname,', ',aa.firstname)";



        }





        $sql = "select aa.no,CONCAT(aa.lastname,', ',aa.firstname) AS fullname,aa.students_no,aa.class_no,aa.classtype,bb.progress from ali_roster as aa inner join ali_students as bb on aa.students_no=bb.students_no WHERE aa.class_no=" . $params["cno"] . " " . $sql;



        $query = $this->db->query($sql);



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');



        $i = 1;



        foreach ($query->result_array() as $row5) {



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $row5['no']);



            $c2 = xml_add_child($item, 'cell', $i, true);



            $c2 = xml_add_child($item, 'cell', $row5['fullname'], true);



            $c3 = xml_add_child($item, 'cell', $row5['progress'], true);



            $c5 = xml_add_child($item, 'cell', "<div class=\"ops\" style=\"width:16px;height:40px;\"><ul><li id=\"fontgear\"></li><ul><li><a href=\"#\" onClick=\"doOnLoadInfo(" . $row5['no'] . "," . $row5['students_no'] . "," . $row5['class_no'] . ",'" . $row5['classtype'] . "');\">Edit</a></li></ul></ul></div>", true);



            $i++;



        }





        return xml_print($dom, true);





    }





    function getAssignRosterForm($params)

    {



        $this->load->helper('xml');



        $sql = "select aa.no,aa.students_no,aa.class_no,aa.classtype,bb.name from ali_roster as aa inner join ali_class as bb on bb.no=aa.class_no WHERE aa.no= " . $params["rno"];



        $query = $this->db->query($sql);



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'data');



        foreach ($query->result_array() as $row5) {



            $item1 = xml_add_child($rows, 'id', $row5['no'], true);



            $item2 = xml_add_child($rows, 'oldclassno', $row5['class_no'], true);



            $item3 = xml_add_child($rows, 'studentno', $row5['students_no'], true);



            $item4 = xml_add_child($rows, 'classtype', $row5['classtype'], true);



            $item5 = xml_add_child($rows, 'classname', $row5['name'], true);



            $item6 = xml_add_child($rows, 'newclassno', "", true);



            $item6 = xml_add_child($rows, 'rno', $row5['no'], true);



        }





        return xml_print($dom, true);



    }





    function setAssignRoster($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $rowId = $params["ids"];



        $this->newId = $rowId;



        $mode = $params[$rowId . "_!nativeeditor_status"];



        switch ($mode) {



            case "inserted":

                $action = "inserted";

                break;



            case "deleted":

                $action = $this->delete_row_assignroster($rowId, $params);

                break;



            case "updated":

                $action = $this->update_row_assignroster($rowId, $params);

                break;



        }



        $action2 = xml_add_child($data, 'action', $action, false);



        xml_add_attribute($action2, 'type', "updated");



        xml_add_attribute($action2, 'sid', $rowId);



        xml_add_attribute($action2, 'tid', $this->newId);



        return xml_print($dom, true);



    }



    function update_row_assignroster($rowId, $params)

    {



        $schoolyear = "";

        $trimester = "";

        $level = "";

        $session = "";

        $classtype = "";



        $sql = "select schoolyear,trimester,level,session,classtype from ali_class where no not in (" . $params[$rowId . "_oldclassno"] . ") and classtype='" . $params[$rowId . "_classtype"] . "' and no=" . $params[$rowId . "_newclassno"];



        $query = $this->db->query($sql);



        if ($query->num_rows() > 0) {



            $row5 = $query->row();





            $updatedata = array(



                'schoolyear' => $row5->schoolyear,



                'trimester' => $row5->trimester,



                'level' => $row5->level,



                'session' => $row5->session,



                'classtype' => $row5->classtype,



                'class_no' => $params[$rowId . "_newclassno"],



                'writer' => $this->session->userdata('ALISESS_USERNAME')



            );



            $this->db->set('regdate', 'now()', FALSE);



            $this->db->where('no', $params[$rowId . "_rno"]);



            $this->db->update('ali_roster', $updatedata);



            /*



            if($params[$rowId."_isupdate"]==true){



                $updatedata = array(



                    'level' => $row5->level,



                    'session' => $row5->session,



                    'writer' => $this->session->userdata('ALISESS_USERNAME')



                );



                $this->db->set('modified', 'now()', FALSE);



                $this->db->where('schoolyear',$row5->schoolyear);



                $this->db->where('trimester',$row5->trimester);



                $this->db->where('students_no',$params[$rowId."_studentno"]);



                $this->db->update("ali_academicrecords", $updatedata);



            }



*/





        }



        return "updated";



    }



    function delete_row_assignroster($rowId, $params)

    {



        //$this->db->where('class_no', $rowId);  $this->db->delete('ali_grade_new');



        //$this->db->where('class_no', $rowId);  $this->db->delete('ali_assignments');



        //$this->db->where('class_no', $rowId);  $this->db->delete('ali_assign_cate');



        //$this->db->where('class_no', $rowId);  $this->db->delete('ali_classteachers');



        //$this->db->where('class_no', $rowId);  $this->db->delete('ali_roster');



        //$this->db->where('no', $rowId);  $this->db->delete('ali_class');



        //$this->db->where('class_no', $rowId);  $this->db->delete('ali_attendance_new');





        $this->db->where('no', $params[$rowId . "_rno"]);



        $this->db->delete('ali_roster');





        return "deleted";



    }





    function createClasses($year, $trim)



    {



        $arrsession = array(1 => 'AM', 2 => 'AFT', 3 => 'PM');





        $sql = "SELECT aa.schoolyear, aa.trimester, aa.level, al.levelname, aa.session, ac.classtype as lstype, ad.classtype as rwtype FROM  `ali_academicrecords` AS aa LEFT JOIN  `ali_level` AS al ON al.levelvalue = aa.level LEFT JOIN  `ali_class` AS ac ON aa.schoolyear = ac.schoolyear AND aa.trimester = ac.trimester AND aa.level = ac.level AND aa.session = ac.session AND ac.classtype='LS' LEFT JOIN  `ali_class` AS ad ON aa.schoolyear = ad.schoolyear AND aa.trimester = ad.trimester AND aa.level = ad.level AND aa.session = ad.session AND ad.classtype='RW' WHERE aa.level NOT IN ( 5, 6, 7 ) AND aa.session NOT IN ( 4 ) AND aa.schoolyear =  '" . $year . "' AND aa.trimester =  '" . $trim . "' GROUP BY aa.schoolyear, aa.trimester,aa.session, al.levelname, aa.level, ac.classtype, ad.classtype ORDER BY aa.schoolyear, aa.trimester, aa.session, al.levelname";



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row5) {



            if ($row5["lstype"] == null) {



                $insdata = array(



                    'schoolyear' => $row5["schoolyear"],



                    'trimester' => $row5["trimester"],



                    'level' => $row5["level"],



                    'session' => $row5["session"],



                    'classtype' => "LS",



                    'room_no' => 1,



                    'name' => "Level " . $row5["levelname"] . " " . $arrsession[$row5["session"]] . " LS",



                    'status' => 0,



                    'writer' => $this->session->userdata('ALISESS_USERNAME')



                );



                $this->db->set('regdate', 'now()', FALSE);



                $this->db->insert('ali_class', $insdata);



                $newId = $this->db->insert_id();





                $sql3 = "select name,wpercentage from ali_assign_cate_basic ";



                $query3 = $this->db->query($sql3);



                foreach ($query3->result_array() as $row3) {



                    $insdata3 = array(



                        'name' => $row3["name"],



                        'wpercentage' => $row3["wpercentage"],



                        'class_no' => $newId,



                        'writer' => $this->session->userdata('ALISESS_USERNAME')



                    );



                    $this->db->set('regdate', 'now()', FALSE);



                    $this->db->insert('ali_assign_cate', $insdata3);



                }





            }



            /*



               $insdata = array(



                   'class_no' => $newId,



                   'teacher_no' => 72,



                   'teachername' => 'Test',



                   'isprimary' => 1,



                   'permission' => 0,



                   'writer' => $this->session->userdata('ALISESS_USERNAME')



               );



               $this->db->set('regdate', 'now()', FALSE);



               $this->db->insert('ali_classteachers', $insdata);



*/



            if ($row5["rwtype"] == null) {



                $insdata2 = array(



                    'schoolyear' => $row5["schoolyear"],



                    'trimester' => $row5["trimester"],



                    'level' => $row5["level"],



                    'session' => $row5["session"],



                    'classtype' => "RW",



                    'room_no' => 1,



                    'name' => "Level " . $row5["levelname"] . " " . $arrsession[$row5["session"]] . " RW",



                    'status' => 0,



                    'writer' => $this->session->userdata('ALISESS_USERNAME')



                );



                $this->db->set('regdate', 'now()', FALSE);



                $this->db->insert('ali_class', $insdata2);



                $newId2 = $this->db->insert_id();





                $sql4 = "select name,wpercentage from ali_assign_cate_basic ";



                $query4 = $this->db->query($sql4);



                foreach ($query4->result_array() as $row4) {



                    $insdata4 = array(



                        'name' => $row4["name"],



                        'wpercentage' => $row4["wpercentage"],



                        'class_no' => $newId2,



                        'writer' => $this->session->userdata('ALISESS_USERNAME')



                    );



                    $this->db->set('regdate', 'now()', FALSE);



                    $this->db->insert('ali_assign_cate', $insdata4);



                }



                /*



               $insdata = array(



                   'class_no' => $newId2,



                   'teacher_no' => 72,



                   'teachername' => 'Test',



                   'isprimary' => 1,



                   'permission' => 0,



                   'writer' => $this->session->userdata('ALISESS_USERNAME')



               );



               $this->db->set('regdate', 'now()', FALSE);



               $this->db->insert('ali_classteachers', $insdata);



*/



            }



        }



        return '';



    }



    function assignRoster($year, $trim)



    {





        $sql = "SELECT aa.schoolyear, aa.trimester, aa.level, aa.session, ac.no AS classno, ac.classtype as clatype, aa.students_no, ad.classtype as rostype,at.firstname,at.lastname, at.student_ID FROM  `ali_academicrecords` AS aa INNER JOIN ali_students as at ON aa.students_no=at.students_no INNER JOIN  `ali_class` AS ac ON aa.schoolyear = ac.schoolyear AND aa.trimester = ac.trimester AND aa.level = ac.level AND aa.session = ac.session LEFT JOIN  `ali_roster` AS ad ON ac.schoolyear = ad.schoolyear AND ac.trimester = ad.trimester AND ac.level = ad.level AND ac.session = ad.session AND ac.classtype=ad.classtype and ad.students_no=aa.students_no WHERE aa.level NOT IN ( 5, 6, 7 ) AND aa.session NOT IN ( 4 ) AND aa.schoolyear =  '" . $year . "' AND aa.trimester =  '" . $trim . "'  GROUP BY aa.schoolyear, aa.trimester, aa.level, aa.session, ac.no, ac.classtype, aa.students_no, ad.classtype, at.firstname, at.lastname, at.student_ID ";



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row5) {



            if ($row5["clatype"] == null || $row5["rostype"] == null) {



                $insdata = array(



                    'schoolyear' => $row5["schoolyear"],



                    'trimester' => $row5["trimester"],



                    'level' => $row5["level"],



                    'session' => $row5["session"],



                    'classtype' => $row5["clatype"],



                    'class_no' => $row5["classno"],



                    'students_no' => $row5["students_no"],



                    'firstname' => $row5["firstname"],



                    'lastname' => $row5["lastname"],



                    'student_ID' => $row5["student_ID"],



                    'writer' => $this->session->userdata('ALISESS_USERNAME')



                );



                $this->db->set('regdate', 'now()', FALSE);



                $this->db->insert('ali_roster', $insdata);



            }



        }



        return '';



    }





    function getClassRoster($params)



    {



        $this->load->helper('xml');

        $sql = "";



        $columns = array("fullname", "student_ID");



        if (isset($params["orderby"])) {



            if ($params["direct"] == 'des') $direct = "DESC"; else $direct = "ASC";



            $sql = " Order by " . $columns[$params["orderby"]] . " " . $direct;



        } else {



            $sql = " Order by no";



        }



        $sql = "select students_no,CONCAT(lastname,', ',firstname) AS fullname,student_ID from ali_roster where class_no=" . $params["classno"] . " " . $sql;



        $query = $this->db->query($sql);



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');



        foreach ($query->result_array() as $row5) {



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $row5['students_no']);



            $c1 = xml_add_child($item, 'cell', $row5['fullname'], true);



            $c3 = xml_add_child($item, 'cell', $row5['student_ID'], true);



        }



        return xml_print($dom, true);



    }



    function setClassRoster($params)

    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $rowId = $params["ids"];



        $this->newId = $rowId;



        $mode = $params[$rowId . "_!nativeeditor_status"];



        switch ($mode) {



            case "inserted":

                $action = $this->delete_row_rosters($params[$rowId . "_class_no"]);

                $action = $this->add_row_rosters($rowId, $params);

                break;



            case "deleted":

                $action = $mode;

                break;



            case "updated":

                $action = $this->delete_row_rosters($params[$rowId . "_class_no"]);

                $action = $this->add_row_rosters($rowId, $params);

                break;



        }



        $action2 = xml_add_child($data, 'action', $action, false);



        xml_add_attribute($action2, 'type', $action);



        xml_add_attribute($action2, 'sid', $rowId);



        xml_add_attribute($action2, 'tid', $this->newId);





        return xml_print($dom, true);



    }



    function add_row_rosters($rowId, $params)

    {



        $schoolyear = "";

        $trimester = "";

        $level = 0;

        $session = 0;





        $sql = "select schoolyear,trimester,level,session from ali_class where no=" . $params[$rowId . "_class_no"];



        $query = $this->db->query($sql);



        if ($query->num_rows() > 0) {



            $row5 = $query->row();



            $schoolyear = $row5->schoolyear;



            $trimester = $row5->trimester;



            $level = $row5->level;



            $session = $row5->session;



        }





        $lines = explode(";", $params[$rowId . "_lines"]);



        $cnt = 0;



        foreach ($lines as $v) {



            $nval = explode("|:|", $v);



            if ($nval[0] > 0) {



                $fullname = explode(", ", $nval[1]);



                $insdata = array(



                    'schoolyear' => $schoolyear,



                    'trimester' => $trimester,



                    'level' => $level,



                    'session' => $session,



                    'class_no' => $params[$rowId . "_class_no"],



                    'students_no' => $nval[0],



                    'firstname' => $fullname[1],



                    'lastname' => $fullname[0],



                    'student_ID' => $nval[2],



                    'writer' => $this->session->userdata('ALISESS_USERNAME')



                );



                $this->db->set('regdate', 'now()', FALSE);



                $this->db->insert('ali_roster', $insdata);



                $cnt++;



            }



        }



        if ($cnt > 0) {



            $this->newId = $this->db->insert_id();



        }





        return "inserted";



    }



    function delete_row_rosters($rowId)

    {



        $this->db->where('class_no', $rowId);



        $this->db->delete('ali_roster');



        return "deleted";



    }





    function setClassTeacher($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $rowId = $params["ids"];



        $this->newId = $rowId;



        $mode = $params[$rowId . "_!nativeeditor_status"];



        switch ($mode) {



            case "inserted":

                $action = $this->add_row_classteacher($rowId, $params);

                break;



            case "deleted":

                $action = $this->delete_row_classteacher($rowId, $params);

                break;



            case "updated":

                $action = $this->update_row_classteacher($rowId, $params);

                break;



        }



        $action2 = xml_add_child($data, 'action', $action, false);



        xml_add_attribute($action2, 'type', $action);



        xml_add_attribute($action2, 'sid', $rowId);



        xml_add_attribute($action2, 'tid', $this->newId);



        return xml_print($dom, true);



    }



    function add_row_classteacher($rowId, $params)

    {



        if ($params[$rowId . "_isprimary"] == 1) {



            $updatedata1 = array(



                'isprimary' => 0,



                'writer' => $this->session->userdata('ALISESS_USERNAME')



            );



            $this->db->set('updatedate', 'now()', FALSE);



            $this->db->where('isprimary', 1);



            $this->db->where('class_no', $params[$rowId . "_class_no"]);



            $this->db->update('ali_classteachers', $updatedata1);



        }





        $insdata = array(



            'class_no' => $params[$rowId . "_class_no"],



            'teacher_no' => $params[$rowId . "_teacher_no"],



            'teachername' => $params[$rowId . "_teachername"],



            'isprimary' => $params[$rowId . "_isprimary"],



            'permission' => $params[$rowId . "_permission"],



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('regdate', 'now()', FALSE);



        $this->db->insert('ali_classteachers', $insdata);



        $this->newId = $this->db->insert_id();



        return "inserted";



    }



    function update_row_classteacher($rowId, $params)

    {



        if ($params[$rowId . "_isprimary"] == 1) {



            $updatedata1 = array(



                'isprimary' => 0,



                'writer' => $this->session->userdata('ALISESS_USERNAME')



            );



            $this->db->set('updatedate', 'now()', FALSE);



            $this->db->where('isprimary', 1);



            $this->db->where('class_no', $params[$rowId . "_class_no"]);



            $this->db->update('ali_classteachers', $updatedata1);



        } else {





            if ($params[$rowId . "_oldprimary"] == 1) {



                $updatedata1 = array(



                    'isprimary' => 1,



                    'writer' => $this->session->userdata('ALISESS_USERNAME')



                );



                $this->db->set('updatedate', 'now()', FALSE);



                $ignore = array($rowId);



                $this->db->where_not_in('no', $ignore);



                $this->db->where('class_no', $params[$rowId . "_class_no"]);



                $this->db->limit(1);



                $this->db->update('ali_classteachers', $updatedata1);



            }





        }





        $updatedata = array(



            'class_no' => $params[$rowId . "_class_no"],



            'teacher_no' => $params[$rowId . "_teacher_no"],



            'teachername' => $params[$rowId . "_teachername"],



            'isprimary' => $params[$rowId . "_isprimary"],



            'permission' => $params[$rowId . "_permission"],



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('updatedate', 'now()', FALSE);



        $this->db->where('no', $rowId);



        $this->db->update('ali_classteachers', $updatedata);



        return "updated";



    }



    function delete_row_classteacher($rowId, $params)

    {



        if ($params[$rowId . "_isprimary"] == 1) {



            $updatedata1 = array(



                'isprimary' => 1,



                'writer' => $this->session->userdata('ALISESS_USERNAME')



            );



            $this->db->set('updatedate', 'now()', FALSE);



            $ignore = array($rowId);



            $this->db->where_not_in('no', $ignore);



            $this->db->where('class_no', $params[$rowId . "_class_no"]);



            $this->db->limit(1);



            $this->db->update('ali_classteachers', $updatedata1);



        }





        $this->db->where('no', $rowId);



        $this->db->delete('ali_classteachers');



        return "deleted";



    }



    function getClassTeacher($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $sql = "select no,class_no,teacher_no,teachername,permission,isprimary from ali_classteachers where no=" . $params['id'];



        $query = $this->db->query($sql);



        if ($query->num_rows() > 0) {



            $row5 = $query->row();



            $cell2 = xml_add_child($data, 'class_no', $row5->class_no, true);



            $cell3 = xml_add_child($data, 'teacher_no', $row5->teacher_no, true);



            $cell4 = xml_add_child($data, 'permission', $row5->permission, true);



            $cell5 = xml_add_child($data, 'isprimary', $row5->isprimary, true);



            $cell6 = xml_add_child($data, 'id', $row5->no, true);



        }



        return xml_print($dom, true);



    }



    function getClassTeachers($params)



    {



        $this->load->helper('xml');

        $sql = "";



        $columns = array("teachername", "permission", "isprimary", "logindate", "");



        if (isset($params["orderby"])) {



            if ($params["direct"] == 'des') $direct = "DESC"; else $direct = "ASC";



            $sql = " Order by " . $columns[$params["orderby"]] . " " . $direct;



        } else {



            $sql = " Order by no";



        }



        $sql = "select no,teacher_no, teachername,permission,isprimary, IF(updatedate='0000-00-00','Never Login In',updatedate) as logindate from ali_classteachers WHERE class_no=" . $params["classno"] . " " . $sql;



        $query = $this->db->query($sql);



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');



        $j = 0;



        foreach ($query->result_array() as $row5) {



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $row5['no']);



            $c1 = xml_add_child($item, 'cell', $row5['teachername'], true);



            $c2 = xml_add_child($item, 'cell', $row5['permission'], true);



            $c3 = xml_add_child($item, 'cell', $row5['isprimary'], true);



            $c4 = xml_add_child($item, 'cell', $row5['logindate'], true);



            $c5 = xml_add_child($item, 'cell', "<div class=\"ops\" style=\"width:16px;height:40px;\"><ul><li id=\"fontgear\"></li><ul><li><a href=\"#\" onClick=\"doOnLoadInfo(" . $row5['no'] . "," . $row5['teacher_no'] . "," . $row5['isprimary'] . ");\">Edit</a></li></ul></ul></div>", true);



            $j++;



        }





        if ($j == 0) {



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', 1);



            $c1 = xml_add_child($item, 'cell', "No Data." . $params["classno"], true);



            $c2 = xml_add_child($item, 'cell', 0, true);



            $c3 = xml_add_child($item, 'cell', 0, true);



            $c4 = xml_add_child($item, 'cell', "", true);



            $c5 = xml_add_child($item, 'cell', "", true);



        }





        return xml_print($dom, true);



    }



    function getComClassTeachers($params)

    {



        $this->load->helper('xml');



        $sql = "";



        $dom = xml_dom();



        $comp = xml_add_child($dom, 'complete');





        if ($params["mod"] == "insert") {



            $sql = "select no,firstname,lastname from ali_user where active=1 AND roleid=3 and no not in (select teacher_no from ali_classteachers where class_no=" . $params["classno"] . " )";



        }



        if ($params["mod"] == "update") {



            $sql = "select no,firstname,lastname from ali_user where active=1 AND roleid=3 and no=" . $params["teacher_no"] . "";



        }





        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row5) {



            $item2 = xml_add_child($comp, 'option', $row5['firstname'] . " " . $row5['lastname'], false);



            xml_add_attribute($item2, 'value', $row5['no']);



        }



        return xml_print($dom, true);



    }





    function getAssignCategories($params)



    {



        $this->load->helper('xml');



        $sql = "";



        $columns = array("name", "wpercentage", "Edit");



        if (isset($params["orderby"])) {



            if ($params["direct"] == 'des')



                $direct = "DESC";



            else



                $direct = "ASC";



            $sql = " Order by " . $columns[$params["orderby"]] . " " . $direct;



        } else {



            $sql = " Order by no";



        }



        $sql = "select no,name,wpercentage,writer,regdate,class_no from ali_assign_cate where class_no=" . $params["classno"] . " " . $sql;



        $query = $this->db->query($sql);



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');



        foreach ($query->result_array() as $row5) {



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $row5['no']);



            $c1 = xml_add_child($item, 'cell', $row5['name'], true);



            $c2 = xml_add_child($item, 'cell', $row5['wpercentage'], true);



            $c3 = xml_add_child($item, 'cell', "<div class=\"ops\" style=\"width:16px;height:40px;\"><ul><li id=\"fontgear\"></li><ul><li><a href=\"#\" onClick=\"doOnLoadInfo(" . $row5['no'] . ");\">Edit</a></li></ul></ul></div>", true);



        }



        return xml_print($dom, true);



    }



    function getAssignCategory($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $sql = "select no,name,wpercentage,writer,regdate,class_no from ali_assign_cate where no=" . $params['id'];



        $query = $this->db->query($sql);



        if ($query->num_rows() > 0) {



            $row5 = $query->row();



            $cell1 = xml_add_child($data, 'name', $row5->name, true);



            $cell2 = xml_add_child($data, 'wpercentage', $row5->wpercentage, true);



            $cell3 = xml_add_child($data, 'class_no', $row5->class_no, true);



            $cell4 = xml_add_child($data, 'id', $row5->no, true);



        }



        return xml_print($dom, true);



    }



    function setAssignCategory($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $rowId = $params["ids"];



        $this->newId = $rowId;



        $mode = $params[$rowId . "_!nativeeditor_status"];



        switch ($mode) {



            case "inserted":

                $action = $this->add_row_ascate($rowId, $params);

                break;



            case "deleted":

                $j = $this->getAssignmentNo($rowId, $params);

                if ($j > 0) {

                    $action = "invalid";

                } else {

                    $action = $this->delete_row_ascate($rowId);

                }

                break;



            case "updated":

                $action = $this->update_row_ascate($rowId, $params);

                break;



        }



        $action2 = xml_add_child($data, 'action', $action, false);



        xml_add_attribute($action2, 'type', $action);



        xml_add_attribute($action2, 'sid', $rowId);



        xml_add_attribute($action2, 'tid', $this->newId);



        return xml_print($dom, true);



    }



    function setDefaultCategories($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');





        $sql = "select name,wpercentage from ali_assign_cate_basic ";



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row5) {



            $insdata = array(



                'name' => $row5["name"],



                'wpercentage' => $row5["wpercentage"],



                'class_no' => $params["classno"],



                'writer' => $this->session->userdata('ALISESS_USERNAME')



            );



            $this->db->set('regdate', 'now()', FALSE);



            $this->db->insert('ali_assign_cate', $insdata);



        }





        $action2 = xml_add_child($rows, 'data', "completed", false);



        return xml_print($dom, true);



    }



    function getAssignmentNo($rowId, $params)

    {



        $sql = "select no from ali_assignments where assigncat_no=" . $rowId . " and class_no=" . $params["classno"];



        $query = $this->db->query($sql);



        return $query->num_rows();



    }



    function add_row_ascate($rowId, $params)

    {



        $insdata = array(



            'name' => $params[$rowId . "_name"],



            'wpercentage' => $params[$rowId . "_wpercentage"],



            'class_no' => $params[$rowId . "_class_no"],



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('regdate', 'now()', FALSE);



        $this->db->insert('ali_assign_cate', $insdata);



        $this->newId = $this->db->insert_id();



        return "inserted";



    }



    function update_row_ascate($rowId, $params)

    {



        $updatedata = array(



            'name' => $params[$rowId . "_name"],



            'wpercentage' => $params[$rowId . "_wpercentage"],



            'class_no' => $params[$rowId . "_class_no"],



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('regdate', 'now()', FALSE);



        $this->db->where('no', $rowId);



        $this->db->update('ali_assign_cate', $updatedata);



        return "updated";



    }



    function delete_row_ascate($rowId)

    {



        $this->db->where('no', $rowId);



        $this->db->delete('ali_assign_cate');



        return "deleted";



    }





    /*** Assigngrade Function for Dhtmlx Start****/



    function getStudentGrades($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');





        $studentfullname = "";



        $sql3 = "select CONCAT(firstname,' ',lastname) AS fullname from ali_students where students_no=" . $params['stno'];



        $query3 = $this->db->query($sql3);



        if ($query3->num_rows() > 0) {



            $row3 = $query3->row();



            $studentfullname = $row3->fullname;



        }





        $totalCategoryRate = 0;



        $grade = 0;



        $arrcate = array();



        $sql8 = "select ac.no, ac.wpercentage from ali_assign_cate as ac inner join ali_assignments as sa on sa.assigncat_no=ac.no where sa.isview=0 and sa.class_no=" . $params['classno'] . " group by ac.no, ac.wpercentage";



        $query8 = $this->db->query($sql8);



        foreach ($query8->result_array() as $row8) {



            $arrcate["" . $row8['no'] . ""]["percentage"] = $row8['wpercentage'];





            //Total Category Rate



            $totalCategoryRate = $totalCategoryRate + intval($row8['wpercentage']);



        }





        //$sql9 = "select assigncat_no,AVG(score) as scoreavg from ali_grade_new where class_no=".$params['classno']." and student_no=".$params['stno']." group by assigncat_no";



        $arravg = array();



        $arreach = array();



        $sql9 = "select ac.assigncat_no,AVG(ac.score) as scoreavg  from ali_grade_new as ac inner join ali_assignments as sa on sa.no=ac.assign_no where  sa.isview=0 and ac.class_no=" . $params['classno'] . " and ac.student_no=" . $params['stno'] . "  group by ac.assigncat_no";



        $query9 = $this->db->query($sql9);



        foreach ($query9->result_array() as $row9) {



            $arreach["" . $row9['assigncat_no'] . ""]["eachcat"] = (((100 * $arrcate["" . $row9['assigncat_no'] . ""]["percentage"]) / $totalCategoryRate) / 100);



            $arrcate["" . $row9['assigncat_no'] . ""]["categoryscore"] = ($row9["scoreavg"] * $arreach["" . $row9['assigncat_no'] . ""]["eachcat"]);



            // $grade = $grade + (intval($row9["scoreavg"]) * (((100*intval($arrcate["".$row9['assigncat_no'].""]["percentage"]))/$totalCategoryRate)/100));



            $arravg["" . $row9['assigncat_no'] . ""]["gradeavg"] = $row9["scoreavg"];



            $grade = $grade + number_format($arrcate["" . $row9['assigncat_no'] . ""]["categoryscore"], 1);



        }



        //Grading Scale



        $gl = "";



        $fg = round($grade);



        if ($fg >= 0 && $fg < 60) {



            $gl = "F";



        } elseif ($fg >= 60 && $fg < 70) {



            $gl = "D";



        } elseif ($fg >= 70 && $fg < 80) {



            $gl = "C";



        } elseif ($fg >= 80 && $fg < 90) {



            $gl = "B";



        } elseif ($fg >= 90 && $fg <= 100) {



            $gl = "A";



        } else {



            $gl = "error";



        }





        $itemH = xml_add_child($rows, 'row', NULL, true);



        xml_add_attribute($itemH, 'id', 0);



        xml_add_attribute($itemH, 'style', "background-color:#BDDEFF;");



        $c1 = xml_add_child($itemH, 'cell', "<b>" . $studentfullname . " : " . $gl . " " . $fg . "%</b>", true);



        $c2 = xml_add_child($itemH, 'cell', NULL, true);



        $c3 = xml_add_child($itemH, 'cell', NULL, true);





        $seq = 2;



        $sql0 = "SELECT no, name, wpercentage FROM ali_assign_cate where class_no=" . $params['classno'];



        $query0 = $this->db->query($sql0);



        foreach ($query0->result_array() as $row0) {



            $data = array();



            $sql1 = "select sa.no, sa.name,sg.score,sa.points,sa.description from ali_assignments as sa left join (select assign_no,score from ali_grade_new where student_no=" . $params['stno'] . ") as sg on sa.no=sg.assign_no where sa.class_no=" . $params['classno'] . " and sa.isview=0 and sa.assigncat_no=" . $row0['no'];



            $query1 = $this->db->query($sql1);



            $j = 0;



            foreach ($query1->result_array() as $row1) {



                $data[$j]["assignname"] = $row1["name"];



                $scor = $row1["score"];



                if ($scor == null) {



                    $scor = "-";



                }



                $data[$j]["score"] = $scor;



                $data[$j]["points"] = $row1["points"];



                $j++;



            }





            $categrade = "";



            if (ISSET($arravg["" . $row0['no'] . ""]["gradeavg"])) {



                $categrade = round($arravg["" . $row0['no'] . ""]["gradeavg"] * $arreach["" . $row0['no'] . ""]["eachcat"], 1) . "%";



            }





            $item0 = xml_add_child($rows, 'row', NULL, true);



            xml_add_attribute($item0, 'id', $seq);



            xml_add_attribute($item0, 'style', "background-color:#E0E0E0;");



            $c1 = xml_add_child($item0, 'cell', "<div style='position:absolute; z-index:99;margin-top: -12px;'><b>" . $row0['name'] . " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; " . $categrade . "</b> (counts as " . $row0['wpercentage'] . "% of overall grade)</div>", true);



            $c2 = xml_add_child($item0, 'cell', NULL, true);



            $c3 = xml_add_child($item0, 'cell', NULL, true);





            $seq++;



            for ($s = 0; $s < count($data); $s++, $seq++) {



                $item1 = xml_add_child($rows, 'row', NULL, true);



                xml_add_attribute($item1, 'id', $seq);



                $c5 = xml_add_child($item1, 'cell', html_entity_decode($data[$s]['assignname'], ENT_QUOTES), true);



                $c6 = xml_add_child($item1, 'cell', $data[$s]['score'], true);



                $c7 = xml_add_child($item1, 'cell', $data[$s]['points'], true);



            }



            $seq++;



        }





        $item2 = xml_add_child($rows, 'row', NULL, true);



        xml_add_attribute($item2, 'id', $seq);



        xml_add_attribute($item2, 'style', "background-color:#BDDEFF;");



        $c9 = xml_add_child($item2, 'cell', "<b>" . $studentfullname . " : " . $gl . " " . $fg . "%</b>", true);



        $c10 = xml_add_child($item2, 'cell', NULL, true);



        $c11 = xml_add_child($item2, 'cell', NULL, true);





        $user1 = xml_add_child($rows, 'userdata', $studentfullname, true);



        xml_add_attribute($user1, 'name', "selectstudent");





        $user2 = xml_add_child($rows, 'userdata', round($grade), true);



        xml_add_attribute($user2, 'name', "resultgrade");





        return xml_print($dom, true);



    }





    function getAttendance($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');





        $head = xml_add_child($rows, 'head', NULL, true);



        $colm = xml_add_child($head, 'column', "Students", true);



        xml_add_attribute($colm, 'width', "160");



        xml_add_attribute($colm, 'type', "link");



        xml_add_attribute($colm, 'align', "left");



        xml_add_attribute($colm, 'sort', "str");





        $time = strtotime($params["sday"]);



        $k = date('w', $time);



        if ($k == 1):



            $monv = date('Y-m-d', $time);



            $tuev = date('Y-m-d', strtotime("+1 day", $time));



            $wesv = date('Y-m-d', strtotime("+2 day", $time));



            $thuv = date('Y-m-d', strtotime("+3 day", $time));



            $friv = date('Y-m-d', strtotime("+4 day", $time));



            $satv = date('Y-m-d', strtotime("+5 day", $time));



            $sunv = date('Y-m-d', strtotime("+6 day", $time));



            $sunv = date('Y-m-d', strtotime("+6 day", $time));



            $mon = date('d D', $time);



            $tue = date('d D', strtotime("+1 day", $time));



            $wes = date('d D', strtotime("+2 day", $time));



            $thu = date('d D', strtotime("+3 day", $time));



            $fri = date('d D', strtotime("+4 day", $time));



            $sat = date('d D', strtotime("+5 day", $time));



            $sun = date('d D', strtotime("+6 day", $time));



        else:



            $fsv = strtotime('Last Monday', $time);



            $monv = date('Y-m-d', $fsv);



            $tuev = date('Y-m-d', strtotime("+1 day", $fsv));



            $wesv = date('Y-m-d', strtotime("+2 day", $fsv));



            $thuv = date('Y-m-d', strtotime("+3 day", $fsv));



            $friv = date('Y-m-d', strtotime("+4 day", $fsv));



            $satv = date('Y-m-d', strtotime("+5 day", $fsv));



            $sunv = date('Y-m-d', strtotime("+6 day", $fsv));





            $mon = date('d D', $fsv);



            $tue = date('d D', strtotime("+1 day", $fsv));



            $wes = date('d D', strtotime("+2 day", $fsv));



            $thu = date('d D', strtotime("+3 day", $fsv));



            $fri = date('d D', strtotime("+4 day", $fsv));



            $sat = date('d D', strtotime("+5 day", $fsv));



            $sun = date('d D', strtotime("+6 day", $fsv));



        endif;



        //Monday



        $colm = xml_add_child($head, 'column', $mon, true);



        xml_add_attribute($colm, 'width', "54");



        xml_add_attribute($colm, 'type', "ed");



        xml_add_attribute($colm, 'align', "center");



        xml_add_attribute($colm, 'sort', "str");



        //Tuesday



        $colm = xml_add_child($head, 'column', $tue, true);



        xml_add_attribute($colm, 'width', "54");



        xml_add_attribute($colm, 'type', "ed");



        xml_add_attribute($colm, 'align', "center");



        xml_add_attribute($colm, 'sort', "str");



        //Wednesday



        $colm = xml_add_child($head, 'column', $wes, true);



        xml_add_attribute($colm, 'width', "54");



        xml_add_attribute($colm, 'type', "ed");



        xml_add_attribute($colm, 'align', "center");



        xml_add_attribute($colm, 'sort', "str");



        //Thursday



        $colm = xml_add_child($head, 'column', $thu, true);



        xml_add_attribute($colm, 'width', "54");



        xml_add_attribute($colm, 'type', "ed");



        xml_add_attribute($colm, 'align', "center");



        xml_add_attribute($colm, 'sort', "str");



        //Friday



        $colm = xml_add_child($head, 'column', $fri, true);



        xml_add_attribute($colm, 'width', "54");



        xml_add_attribute($colm, 'type', "ed");



        xml_add_attribute($colm, 'align', "center");



        xml_add_attribute($colm, 'sort', "str");



        //Saturday



        $colm = xml_add_child($head, 'column', $sat, true);



        xml_add_attribute($colm, 'width', "54");



        xml_add_attribute($colm, 'type', "ed");



        xml_add_attribute($colm, 'align', "center");



        xml_add_attribute($colm, 'sort', "str");



        //Sunday



        $colm = xml_add_child($head, 'column', $sun, true);



        xml_add_attribute($colm, 'width', "54");



        xml_add_attribute($colm, 'type', "ed");



        xml_add_attribute($colm, 'align', "center");



        xml_add_attribute($colm, 'sort', "str");





        $sql1 = "SELECT AT.students_no, AT.fullname,MAX( IF(WEEKDAY(AT.attendance_day)=0,CONCAT(AT.marks,'|',AT.no),'') ) AS mon,MAX( IF(WEEKDAY(AT.attendance_day)=1,CONCAT(AT.marks,'|',AT.no),'') ) AS tue,MAX( IF(WEEKDAY(AT.attendance_day)=2,CONCAT(AT.marks,'|',AT.no),'') ) AS wes,MAX( IF(WEEKDAY(AT.attendance_day)=3,CONCAT(AT.marks,'|',AT.no),'') ) AS thu,MAX( IF(WEEKDAY(AT.attendance_day)=4,CONCAT(AT.marks,'|',AT.no),'') ) AS fri,MAX( IF(WEEKDAY(AT.attendance_day)=5,CONCAT(AT.marks,'|',AT.no),'') ) AS sat,MAX( IF(WEEKDAY(AT.attendance_day)=6,CONCAT(AT.marks,'|',AT.no),'') ) AS sun FROM (SELECT ST.no,SS.students_no, CONCAT(SS.lastname,', ',SS.firstname) as fullname,ST.attendance_day, ST.marks FROM `ali_roster` as SS left join (SELECT  no, student_no, attendance_day,marks,class_no FROM `ali_attendance_new` where attendance_day between '" . $monv . "' and '" . $sunv . "' ) as ST ON SS.students_no=ST.student_no and SS.class_no=ST.class_no inner join ali_students as bb on SS.students_no=bb.students_no WHERE bb.progress='r' and SS.class_no=" . $params["classno"] . ") AS AT GROUP BY AT.fullname, AT.students_no ";



        $query1 = $this->db->query($sql1);



        foreach ($query1->result_array() as $row5) {



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $row5['students_no']);





            $c1 = xml_add_child($item, 'cell', $row5['fullname'] . "^javascript:self.location.href=\"/index.php/aliweb/attmonths?stno=" . $row5['students_no'] . "\";^_self", true);





            $carr2 = explode('|', $row5["mon"]);



            $c2 = xml_add_child($item, 'cell', $carr2[0], true);

            xml_add_attribute($c2, 'style', "background-color: #FFFFF0;");



            xml_add_attribute($c2, 'selday', $monv);



            if (!empty($carr2[1])): xml_add_attribute($c2, 'attno', $carr2[1]); endif;





            $carr3 = explode('|', $row5["tue"]);



            $c3 = xml_add_child($item, 'cell', $carr3[0], true);



            xml_add_attribute($c3, 'selday', $tuev);



            if (!empty($carr3[1])): xml_add_attribute($c3, 'attno', $carr3[1]); endif;





            $carr4 = explode('|', $row5["wes"]);



            $c4 = xml_add_child($item, 'cell', $carr4[0], true);

            xml_add_attribute($c4, 'style', "background-color: #FFFFF0;");



            xml_add_attribute($c4, 'selday', $wesv);



            if (!empty($carr4[1])): xml_add_attribute($c4, 'attno', $carr4[1]); endif;





            $carr5 = explode('|', $row5["thu"]);



            $c5 = xml_add_child($item, 'cell', $carr5[0], true);



            xml_add_attribute($c5, 'selday', $thuv);



            if (!empty($carr5[1])): xml_add_attribute($c5, 'attno', $carr5[1]); endif;





            $carr6 = explode('|', $row5["fri"]);



            $c6 = xml_add_child($item, 'cell', $carr6[0], true);

            xml_add_attribute($c6, 'style', "background-color: #FFFFF0;");



            xml_add_attribute($c6, 'selday', $friv);



            if (!empty($carr6[1])): xml_add_attribute($c6, 'attno', $carr6[1]); endif;





            $carr7 = explode('|', $row5["sat"]);



            $c7 = xml_add_child($item, 'cell', $carr7[0], true);



            xml_add_attribute($c7, 'selday', $satv);



            if (!empty($carr7[1])): xml_add_attribute($c7, 'attno', $carr7[1]); endif;





            $carr8 = explode('|', $row5["sun"]);



            $c8 = xml_add_child($item, 'cell', $carr8[0], true);

            xml_add_attribute($c8, 'style', "background-color: #FFFFF0;");



            xml_add_attribute($c8, 'selday', $sunv);



            if (!empty($carr8[1])): xml_add_attribute($c8, 'attno', $carr8[1]); endif;





        }





        return xml_print($dom, true);



    }



    function setAttendance($params)



    {



        $action = "";



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $rowId = $params["ids"];



        $this->newId = $rowId;



        $mode = $params[$rowId . "_!nativeeditor_status"];



        switch ($mode) {



            case "inserted":

                $action = $this->insert_row_attendance($rowId, $params);

                break;



            case "updated":

                $action = $this->update_row_attendance($rowId, $params);

                break;



            case "deleted":

                $action = $this->delete_row_attendance($rowId, $params);

                break;



        }



        $action2 = xml_add_child($data, 'action', $action, false);



        xml_add_attribute($action2, 'type', $action);



        xml_add_attribute($action2, 'sid', $rowId);



        xml_add_attribute($action2, 'tid', $this->newId);



        return xml_print($dom, true);



    }



    function insert_row_attendance($rowId, $params)

    {





        $sql = "select no from ali_attendance_new where attendance_day='" . $params[$rowId . "_selday"] . "' and student_no=" . $params[$rowId . "_stdno"] . " and class_no=" . $params[$rowId . "_classno"];



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row5) {



            $params[$rowId . "_attno"] = $row5["no"];



            return $this->update_row_attendance($rowId, $params);



        }





        $insdata = array(



            'class_no' => $params[$rowId . "_classno"],



            'student_no' => $params[$rowId . "_stdno"],



            'marks' => strtoupper($params[$rowId . "_attval"]),



            'attendance_day' => $params[$rowId . "_selday"],



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('regdate', 'now()', FALSE);



        $this->db->insert('ali_attendance_new', $insdata);



        return "inserted";



    }



    function update_row_attendance($rowId, $params)

    {



        $updatedata = array(



            'marks' => strtoupper($params[$rowId . "_attval"]),



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('regdate', 'now()', FALSE);



        $this->db->where('no', $params[$rowId . "_attno"]);



        $this->db->update('ali_attendance_new', $updatedata);



        return "updated";



    }



    function delete_row_attendance($rowId, $params)

    {



        $this->db->where('no', $params[$rowId . "_attno"]);



        $this->db->delete('ali_attendance_new');



        return "deleted";



    }



    function getAttMonths($params)



    {



        $sum_p = 0;



        $sum_a = 0;



        $sum_t = 0;



        $res = array();



        $sql1 = "SELECT no, DATE_FORMAT(attendance_day,'%Y') as yy,DATE_FORMAT(attendance_day,'%m') as mm,DATE_FORMAT(attendance_day,'%d') as dd, marks FROM `ali_attendance_new` where student_no=" . $params["stno"] . " and class_no=" . $params["classno"] . " ";



        $query1 = $this->db->query($sql1);



        foreach ($query1->result_array() as $row5) {



            $res["" . $row5["yy"] . ""]["" . $row5["mm"] . ""]["" . $row5["dd"] . ""] = $row5["marks"];





            $vl = substr($row5["marks"], 0, 1);



            if ($vl == "P") {

                $sum_p++;

            }



            if ($vl == "T") {

                $sum_t++;

            }



            if ($vl == "A") {

                $sum_a++;

            }



            $vr = substr($row5["marks"], 1, 1);



            if ($vr == "P") {

                $sum_p++;

            }



            if ($vr == "T") {

                $sum_t++;

            }



            if ($vr == "A") {

                $sum_a++;

            }



        }





        $studentfullname = "";



        $sql3 = "select CONCAT(firstname,' ',lastname) AS fullname from ali_students where students_no=" . $params['stno'];



        $query3 = $this->db->query($sql3);



        if ($query3->num_rows() > 0) {



            $row3 = $query3->row();



            $studentfullname = $row3->fullname;



        }



        return array($res, $sum_p, $sum_t, $sum_a, $studentfullname);



    }





    function classGP($classno)

    {



        $sql = "select ac.name as classname,concat(ac.schoolyear,' / ','Tri',ac.trimester) as gp from ali_class as ac where ac.no=" . $classno;



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row5) {



            return array($row5["classname"], $row5["gp"]);



        }



        return array();



    }





    function currentGP()

    {



        $sql = "select no,schoolyear,gradingperiod,startday from ali_gradingperiod where active=1  order by startday desc limit 1";



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row5) {



            return $row5["no"];



        }



        return null;



    }





    function getAdministrators($params)



    {



        $this->load->helper('xml');



        $sql = "";



        $columns = array("roleid", "user_ID", "firstname", "lastname", "initial", "nickname", "cellphone", "email", "bgcolorone", "active", "writer", "created", "etc", "passw");



        if (isset($params["orderby"])) {



            if ($params["direct"] == 'des')



                $direct = "DESC";



            else



                $direct = "ASC";



            $sql = " Order by " . $columns[$params["orderby"]] . " " . $direct;



        } else {



            $sql = " Order by active,firstname,lastname,roleid";



        }





        $posStart = "0";



        if (isset($params["posStart"]))



            $posStart = $params['posStart'];





        $sql = "select no,roleid,user_ID,firstname,lastname,initial,nickname,cellphone,email,bgcolorone,active,etc,writer,created,passw from ali_user where roleid in (1) " . $sql;



        $query = $this->db->query($sql);



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');



        xml_add_attribute($rows, 'total_count', $query->num_rows());



        xml_add_attribute($rows, 'pos', $posStart);



        foreach ($query->result_array() as $row5) {





            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $row5['no']);



            $c0 = xml_add_child($item, 'cell', $row5['roleid'], true);



            $c1 = xml_add_child($item, 'cell', $row5['user_ID'], true);



            $c2 = xml_add_child($item, 'cell', $row5['firstname'], true);



            $c3 = xml_add_child($item, 'cell', $row5['lastname'], true);



            $c4 = xml_add_child($item, 'cell', $row5['initial'], true);



            $c5 = xml_add_child($item, 'cell', $row5['nickname'], true);



            $c6 = xml_add_child($item, 'cell', $row5['cellphone'], true);



            $c7 = xml_add_child($item, 'cell', $row5['email'], true);



            $c8 = xml_add_child($item, 'cell', $row5['bgcolorone'], true);

            xml_add_attribute($c8, 'style', "background-color:" . $row5['bgcolorone']);



            $c9 = xml_add_child($item, 'cell', $row5['active'], true);



            $c10 = xml_add_child($item, 'cell', $row5['writer'], true);



            $c11 = xml_add_child($item, 'cell', $row5['created'], true);



            $c12 = xml_add_child($item, 'cell', $row5['etc'], true);



            $c13 = xml_add_child($item, 'cell', $row5['passw'], true);



        }





        return xml_print($dom, true);



    }



    function setAdministrator($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $rowId = $params["ids"];



        $this->newId = $rowId;



        $mode = $params[$rowId . "_!nativeeditor_status"];



        switch ($mode) {



            case "inserted":

                $action = $this->add_row_administrator($rowId, $params);

                break;



            case "deleted":

                $action = $this->delete_row_administrator($rowId, $params);

                break;



            case "updated":

                $action = $this->update_row_administrator($rowId, $params);

                break;



        }



        $action2 = xml_add_child($data, 'action', $action, false);



        xml_add_attribute($action2, 'type', $action);



        xml_add_attribute($action2, 'sid', $rowId);



        xml_add_attribute($action2, 'tid', $this->newId);



        return xml_print($dom, true);



    }



    function add_row_administrator($rowId, $params)

    {



        $insdata = array(



            'roleid' => $params[$rowId . "_c0"],



            'user_ID' => $params[$rowId . "_c1"],



            'firstname' => iconv("UTF-8", "CP949", $params[$rowId . "_c2"]),



            'lastname' => iconv("UTF-8", "CP949", $params[$rowId . "_c3"]),



            'initial' => iconv("UTF-8", "CP949", $params[$rowId . "_c4"]),



            'nickname' => iconv("UTF-8", "CP949", $params[$rowId . "_c5"]),



            'cellphone' => iconv("UTF-8", "CP949", $params[$rowId . "_c6"]),



            'email' => iconv("UTF-8", "CP949", $params[$rowId . "_c7"]),



            'bgcolorone' => $params[$rowId . "_c8"],



            'active' => $params[$rowId . "_c9"],



            'etc' => iconv("UTF-8", "CP949", $params[$rowId . "_c12"]),



            'writer' => $this->session->userdata('ALISESS_USERNAME'),



            'writer_no' => $this->session->userdata('ALISESS_USERNO')



        );



        $this->db->set('created', 'now()', FALSE);



        if (trim($params[$rowId . "_c13"]) != "") {



            $salt = $this->fn_generate_salt();



            $password = $this->fn_generate_salted_password($params[$rowId . "_c13"], $salt);



            $this->db->set('passw', "'" . $password . "'", FALSE);



        }



        $this->db->insert('ali_user', $insdata);



        $this->newId = $this->db->insert_id();





        $activ = ($params[$rowId . "_c9"] == 1) ? "allow" : "deny";



        $insdata2 = array(



            'type' => 'user',



            'type_id' => $this->newId,



            'resource_id' => 3,



            'action' => $activ



        );



        $this->db->insert('ali_acl', $insdata2);





        return "inserted";



    }



    function update_row_administrator($rowId, $params)

    {



        $updatedata = array(



            'roleid' => $params[$rowId . "_c0"],



            'user_ID' => $params[$rowId . "_c1"],



            'firstname' => iconv("UTF-8", "CP949", $params[$rowId . "_c2"]),



            'lastname' => iconv("UTF-8", "CP949", $params[$rowId . "_c3"]),



            'initial' => iconv("UTF-8", "CP949", $params[$rowId . "_c4"]),



            'nickname' => iconv("UTF-8", "CP949", $params[$rowId . "_c5"]),



            'cellphone' => iconv("UTF-8", "CP949", $params[$rowId . "_c6"]),



            'email' => iconv("UTF-8", "CP949", $params[$rowId . "_c7"]),



            'bgcolorone' => $params[$rowId . "_c8"],



            'active' => $params[$rowId . "_c9"],



            'etc' => iconv("UTF-8", "CP949", $params[$rowId . "_c12"]),



            'writer_no' => $this->session->userdata('ALISESS_USERNO'),



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('modified', 'now()', FALSE);



        $this->db->set('created', 'now()', FALSE);



        if (trim($params[$rowId . "_c13"]) != "") {



            $salt = $this->fn_generate_salt();



            $password = $this->fn_generate_salted_password($params[$rowId . "_c13"], $salt);



            $this->db->set('passw', "'" . $password . "'", FALSE);



        }



        $this->db->where('no', $rowId);



        $this->db->update("ali_user", $updatedata);





        $activ = ($params[$rowId . "_c9"] == 1) ? "allow" : "deny";



        $updatedata1 = array(



            'action' => $activ



        );



        $this->db->where('type', 'user');



        $this->db->where('type_id', $rowId);



        $this->db->where('resource_id', 3);



        $this->db->update("ali_acl", $updatedata1);





        return "updated";



    }



    function delete_row_administrator($rowId, $params)

    {



        $this->db->where('no', $rowId);



        $this->db->delete('ali_user');



        return "deleted";



    }





    function checkDuplicateUserID($params)

    {



        $this->load->helper('xml');



        $cnt = 0;



        $sql = "select user_ID from ali_user where UPPER(user_ID) = UPPER('" . trim($params["newuid"]) . "')";



        $query = $this->db->query($sql);





        if ($query->num_rows() != null) {



            $cnt = $query->num_rows();



        }





        $dom = xml_dom();



        $items = xml_add_child($dom, 'items');



        $item1 = xml_add_child($items, 'item', null, false);



        xml_add_attribute($item1, 'value', $cnt);



        return xml_print($dom, true);



    }





    function getTranscripts($params)



    {



        $this->load->helper('xml');



        $sql = "";



        $columns = array("grade_no", "firstname", "lastname", "class_name", "group_name", "part_score", "home_score", "quiz_score", "midtem_score", "final_score", "record", "att_score", "writer", "regdate");



        if (isset($params["orderby"])) {



            if ($params["direct"] == 'des')



                $direct = "DESC";



            else



                $direct = "ASC";



            $sql = " Order by " . $columns[$params["orderby"]] . " " . $direct;



        } else {



            $sql = " Order by group_name";



        }





        $posStart = "0";



        if (isset($params["posStart"]))



            $posStart = $params['posStart'];





        $sql = "select a.grade_no,a.classes_no, b.firstname,b.lastname,c.class_name,d.group_name, a.part_score,a.home_score,a.quiz_score,a.midtem_score,a.final_score,a.record,a.att_score,a.writer,a.regdate,a.students_no from ali_grade_new as a inner join ali_students as b on a.students_no=b.students_no inner join ali_class as c on a.classes_no=c.classes_no inner join ali_class_group as d on a.semester_no=d.class_group_no " . $sql;



        $query = $this->db->query($sql);



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');



        xml_add_attribute($rows, 'total_count', $query->num_rows());



        xml_add_attribute($rows, 'pos', $posStart);



        foreach ($query->result_array() as $row5) {



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $row5['grade_no']);



            $c0 = xml_add_child($item, 'cell', $row5['grade_no'], true);



            $c1 = xml_add_child($item, 'cell', $row5['firstname'], true);



            $c2 = xml_add_child($item, 'cell', $row5['lastname'], true);



            $c3 = xml_add_child($item, 'cell', $row5['class_name'], true);



            $c4 = xml_add_child($item, 'cell', $row5['group_name'], true);



            $c5 = xml_add_child($item, 'cell', $row5['part_score'], true);



            $c6 = xml_add_child($item, 'cell', $row5['home_score'], true);



            $c7 = xml_add_child($item, 'cell', $row5['quiz_score'], true);



            $c8 = xml_add_child($item, 'cell', $row5['midtem_score'], true);



            $c9 = xml_add_child($item, 'cell', $row5['final_score'], true);



            $c10 = xml_add_child($item, 'cell', $row5['record'], true);



            $c11 = xml_add_child($item, 'cell', $row5['att_score'] . "^javascript:loadData(" . $row5['classes_no'] . "," . $row5['students_no'] . ");^_self", true);



            $c12 = xml_add_child($item, 'cell', $row5['writer'], true);



            $c13 = xml_add_child($item, 'cell', $row5['regdate'], true);



        }





        return xml_print($dom, true);



    }



    function getTranscriptAtt($params)

    {



        $this->load->helper('xml');



        $sql = "";



        $columns = array("attendance_no", "class_name", "teacherfullname", "studentfullname", "items", "attendance_day", "writer", "regdate");



        if (isset($params["orderby"])) {



            if ($params["direct"] == 'des')



                $direct = "DESC";



            else



                $direct = "ASC";



            $sql = " Order by " . $columns[$params["orderby"]] . " " . $direct;



        } else {



            $sql = " Order by a.attendance_day";



        }





        $posStart = "0";



        if (isset($params["posStart"]))



            $posStart = $params['posStart'];





        $sql = "select a.attendance_no, CONCAT(b.firstname,\" \",b.lastname) as studentfullname,c.class_name, CONCAT(d.firstname,\" \",d.name) as teacherfullname, a.items,a.attendance_day,a.writer,a.regdate from ali_attendance_new as a inner join ali_students as b on a.students_no=b.students_no inner join ali_class as c on a.class_no=c.classes_no inner join ali_instructors as d on a.instructors_no=d.instructors_no where a.class_no=" . $params['cno'] . " and a.students_no=" . $params['sno'] . " " . $sql;



        $query = $this->db->query($sql);



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');



        xml_add_attribute($rows, 'total_count', $query->num_rows());



        xml_add_attribute($rows, 'pos', $posStart);



        foreach ($query->result_array() as $row5) {



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $row5['attendance_no']);



            $c0 = xml_add_child($item, 'cell', $row5['attendance_no'], true);



            $c2 = xml_add_child($item, 'cell', $row5['class_name'], true);



            $c3 = xml_add_child($item, 'cell', $row5['teacherfullname'], true);



            $c1 = xml_add_child($item, 'cell', $row5['studentfullname'], true);



            $c4 = xml_add_child($item, 'cell', $row5['items'], true);



            $c5 = xml_add_child($item, 'cell', $row5['attendance_day'], true);



            $c6 = xml_add_child($item, 'cell', $row5['writer'], true);



            $c7 = xml_add_child($item, 'cell', $row5['regdate'], true);



        }





        return xml_print($dom, true);



    }





    function getTrimesters($params)



    {



        $this->load->helper('xml');



        $sql = "";



        $columns = array("schoolyear", "gradingperiod", "startday", "endday", "active", "created", "writer");



        if (isset($params["orderby"])) {



            if ($params["direct"] == 'des')



                $direct = "DESC";



            else



                $direct = "ASC";



            $sql = " Order by " . $columns[$params["orderby"]] . " " . $direct;



        } else {



            $sql = " Order by schoolyear desc, gradingperiod desc";



        }





        $posStart = "0";



        if (isset($params["posStart"]))



            $posStart = $params['posStart'];





        $sql = "select no,schoolyear, gradingperiod,startday,endday,active,created,writer from ali_gradingperiod " . $sql;



        $query = $this->db->query($sql);



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');



        xml_add_attribute($rows, 'total_count', $query->num_rows());



        xml_add_attribute($rows, 'pos', $posStart);



        foreach ($query->result_array() as $row5) {



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $row5['no']);



            $c1 = xml_add_child($item, 'cell', $row5['schoolyear'], true);



            $c2 = xml_add_child($item, 'cell', $row5['gradingperiod'], true);



            $c3 = xml_add_child($item, 'cell', $row5['startday'], true);



            $c4 = xml_add_child($item, 'cell', $row5['endday'], true);



            $c5 = xml_add_child($item, 'cell', $row5['active'], true);



            $c6 = xml_add_child($item, 'cell', $row5['created'], true);



            $c7 = xml_add_child($item, 'cell', $row5['writer'], true);



        }





        return xml_print($dom, true);



    }



    function setTrimester($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $rowId = $params["ids"];



        $this->newId = $rowId;



        $mode = $params[$rowId . "_!nativeeditor_status"];



        switch ($mode) {



            case "inserted":

                $action = $this->add_row_trimester($rowId, $params);

                break;



            case "deleted":

                $action = $this->delete_row_trimester($rowId, $params);

                break;



            case "updated":

                $action = $this->update_row_trimester($rowId, $params);

                break;



        }



        $action2 = xml_add_child($data, 'action', $action, false);



        xml_add_attribute($action2, 'type', $action);



        xml_add_attribute($action2, 'sid', $rowId);



        xml_add_attribute($action2, 'tid', $this->newId);



        return xml_print($dom, true);



    }



    function add_row_trimester($rowId, $params)

    {



        $insdata = array(



            'schoolyear' => iconv("UTF-8", "CP949", $params[$rowId . "_c0"]),



            'gradingperiod' => $params[$rowId . "_c1"],



            'startday' => $params[$rowId . "_c2"],



            'endday' => $params[$rowId . "_c3"],



            'active' => $params[$rowId . "_c4"],



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('created', 'now()', FALSE);



        $this->db->insert('ali_gradingperiod', $insdata);



        $this->newId = $this->db->insert_id();





        //update class status



        $updatedata = array(



            'status' => ($params[$rowId . "_c4"] == 1 ? 0 : 1)



        );



        $this->db->where('schoolyear', iconv("UTF-8", "CP949", $params[$rowId . "_c0"]));



        $this->db->where('trimester', $params[$rowId . "_c1"]);



        $this->db->update("ali_class", $updatedata);





        return "inserted";



    }



    function update_row_trimester($rowId, $params)

    {



        $updatedata = array(



            'schoolyear' => iconv("UTF-8", "CP949", $params[$rowId . "_c0"]),



            'gradingperiod' => $params[$rowId . "_c1"],



            'startday' => $params[$rowId . "_c2"],



            'endday' => $params[$rowId . "_c3"],



            'active' => $params[$rowId . "_c4"],



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('modified', 'now()', FALSE);



        $this->db->where('no', $rowId);



        $this->db->update("ali_gradingperiod", $updatedata);





        //update class status



        $updatedata = array(



            'status' => ($params[$rowId . "_c4"] == 1 ? 0 : 1)



        );



        $this->db->where('schoolyear', iconv("UTF-8", "CP949", $params[$rowId . "_c0"]));



        $this->db->where('trimester', $params[$rowId . "_c1"]);



        $this->db->update("ali_class", $updatedata);





        return "updated";



    }



    function delete_row_trimester($rowId, $params)

    {



        $this->db->where('no', $rowId);



        $this->db->delete('ali_gradingperiod');



        return "deleted";



    }





    function getLevels($params)



    {



        $this->load->helper('xml');



        $sql = "";



        $columns = array("levelname", "levelvalue");



        if (isset($params["orderby"])) {



            if ($params["direct"] == 'des')



                $direct = "DESC";



            else



                $direct = "ASC";



            $sql = " Order by " . $columns[$params["orderby"]] . " " . $direct;



        } else {



            $sql = " Order by levelname";



        }





        $posStart = "0";



        if (isset($params["posStart"]))



            $posStart = $params['posStart'];





        $sql = "select no,levelname, levelvalue from ali_level " . $sql;



        $query = $this->db->query($sql);



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');



        xml_add_attribute($rows, 'total_count', $query->num_rows());



        xml_add_attribute($rows, 'pos', $posStart);



        foreach ($query->result_array() as $row5) {



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $row5['no']);



            $c1 = xml_add_child($item, 'cell', $row5['levelname'], true);



            $c2 = xml_add_child($item, 'cell', $row5['levelvalue'], true);



        }





        return xml_print($dom, true);



    }



    function setLevel($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $rowId = $params["ids"];



        $this->newId = $rowId;



        $mode = $params[$rowId . "_!nativeeditor_status"];



        switch ($mode) {



            case "inserted":

                $action = $this->add_row_level($rowId, $params);

                break;



            case "deleted":

                $action = $this->delete_row_level($rowId, $params);

                break;



            case "updated":

                $action = $this->update_row_level($rowId, $params);

                break;



        }



        $action2 = xml_add_child($data, 'action', $action, false);



        xml_add_attribute($action2, 'type', $action);



        xml_add_attribute($action2, 'sid', $rowId);



        xml_add_attribute($action2, 'tid', $this->newId);



        return xml_print($dom, true);



    }



    function add_row_level($rowId, $params)

    {



        $insdata = array(



            'levelname' => $params[$rowId . "_c0"],



            'levelvalue' => $params[$rowId . "_c1"]



        );



        $this->db->insert('ali_level', $insdata);



        $this->newId = $this->db->insert_id();



        return "inserted";



    }



    function update_row_level($rowId, $params)

    {



        $updatedata = array(



            'levelname' => $params[$rowId . "_c0"],



            'levelvalue' => $params[$rowId . "_c1"]



        );



        $this->db->where('no', $rowId);



        $this->db->update("ali_level", $updatedata);



        return "updated";



    }



    function delete_row_level($rowId, $params)

    {



        $this->db->where('no', $rowId);



        $this->db->delete('ali_level');



        return "deleted";



    }





    function getAssignDefaultCategory($params)



    {



        $this->load->helper('xml');



        $sql = "";



        $columns = array("name", "wpercentage", "wirter", "regdate");



        if (isset($params["orderby"])) {



            if ($params["direct"] == 'des')



                $direct = "DESC";



            else



                $direct = "ASC";



            $sql = " Order by " . $columns[$params["orderby"]] . " " . $direct;



        } else {



            $sql = " Order by name desc";



        }





        $posStart = "0";



        if (isset($params["posStart"]))



            $posStart = $params['posStart'];





        $sql = "select no,name,wpercentage,writer,regdate from ali_assign_cate_basic " . $sql;



        $query = $this->db->query($sql);



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');



        xml_add_attribute($rows, 'total_count', $query->num_rows());



        xml_add_attribute($rows, 'pos', $posStart);



        foreach ($query->result_array() as $row5) {



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $row5['no']);



            $c1 = xml_add_child($item, 'cell', $row5['name'], true);



            $c2 = xml_add_child($item, 'cell', $row5['wpercentage'], true);



            $c3 = xml_add_child($item, 'cell', $row5['writer'], true);



            $c4 = xml_add_child($item, 'cell', $row5['regdate'], true);



        }





        return xml_print($dom, true);



    }



    function setAssignDefaultCategory($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $rowId = $params["ids"];



        $this->newId = $rowId;



        $mode = $params[$rowId . "_!nativeeditor_status"];



        switch ($mode) {



            case "inserted":

                $action = $this->add_row_assignDefaultCategory($rowId, $params);

                break;



            case "deleted":

                $action = $this->delete_row_assignDefaultCategory($rowId, $params);

                break;



            case "updated":

                $action = $this->update_row_assignDefaultCategory($rowId, $params);

                break;



        }



        $action2 = xml_add_child($data, 'action', $action, false);



        xml_add_attribute($action2, 'type', $action);



        xml_add_attribute($action2, 'sid', $rowId);



        xml_add_attribute($action2, 'tid', $this->newId);



        return xml_print($dom, true);



    }



    function add_row_assignDefaultCategory($rowId, $params)

    {



        $insdata = array(



            'name' => iconv("UTF-8", "CP949", $params[$rowId . "_c0"]),



            'wpercentage' => $params[$rowId . "_c1"],



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('regdate', 'now()', FALSE);



        $this->db->insert('ali_assign_cate_basic', $insdata);



        $this->newId = $this->db->insert_id();



        return "inserted";



    }



    function update_row_assignDefaultCategory($rowId, $params)

    {



        $updatedata = array(



            'name' => iconv("UTF-8", "CP949", $params[$rowId . "_c0"]),



            'wpercentage' => $params[$rowId . "_c1"],



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('regdate', 'now()', FALSE);



        $this->db->where('no', $rowId);



        $this->db->update("ali_assign_cate_basic", $updatedata);



        return "updated";



    }



    function delete_row_assignDefaultCategory($rowId, $params)

    {



        $this->db->where('no', $rowId);



        $this->db->delete('ali_assign_cate_basic');



        return "deleted";



    }





    function menucontextUploadGrade($params)

    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $menu = xml_add_child($dom, 'menu');

        xml_add_attribute($menu, 'id', "0");



        $item0 = xml_add_child($menu, 'item', NULL, true);

        xml_add_attribute($item0, 'text', "Import Temp Data");

        xml_add_attribute($item0, 'img', "");

        xml_add_attribute($item0, 'id', "import");



        $item1 = xml_add_child($menu, 'item', NULL, true);

        xml_add_attribute($item1, 'text', "Remove row");

        xml_add_attribute($item1, 'img', "");

        xml_add_attribute($item1, 'id', "remove");



        return xml_print($dom, true);



    }



    function getTrimestersCombo($params)



    {



        $this->load->helper('xml');



        $sql = "select no,schoolyear,gradingperiod from ali_gradingperiod order by schoolyear desc, gradingperiod";



        $query = $this->db->query($sql);



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'complete');



        foreach ($query->result_array() as $row5) {



            $item6 = xml_add_child($rows, 'option', $row5['schoolyear'] . " > GP" . $row5['gradingperiod'], true);



            xml_add_attribute($item6, 'value', $row5['no']);



        }



        return xml_print($dom, true);



    }



    function getImportFiles($params)



    {



        $this->load->helper('xml');



        $sql = "";



        $columns = array("file_no", "title", "filename", "writer", "created");



        if (isset($params["orderby"])) {



            if ($params["direct"] == 'des')



                $direct = "DESC";



            else



                $direct = "ASC";



            $sql = " Order by " . $columns[$params["orderby"]] . " " . $direct;



        } else {



            $sql = " Order by file_no desc";



        }





        $posStart = "0";



        if (isset($params["posStart"]))



            $posStart = $params['posStart'];





        $sql = "select file_no,trimester_no,title,filename,active,writer,created from eg_importfiles_g where trimester_no=" . $params["gno"] . " " . $sql;



        $query = $this->db->query($sql);



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');



        xml_add_attribute($rows, 'total_count', $query->num_rows());



        xml_add_attribute($rows, 'pos', $posStart);



        foreach ($query->result_array() as $row5) {



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $row5['file_no']);



            $c1 = xml_add_child($item, 'cell', $row5['file_no'], true);



            $c2 = xml_add_child($item, 'cell', $row5['trimester_no'], true);



            $c3 = xml_add_child($item, 'cell', $row5['title'], true);



            $c4 = xml_add_child($item, 'cell', $row5['filename'] . "^javascript:loadData(" . $row5['file_no'] . ");^_self", true);



            $c5 = xml_add_child($item, 'cell', $row5['active'], true);



            $c6 = xml_add_child($item, 'cell', $row5['writer'], true);



            $c7 = xml_add_child($item, 'cell', $row5['created'], true);



        }



        return xml_print($dom, true);



    }



    function setImportFiles($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $rowId = $params["ids"];



        $this->newId = $rowId;



        $mode = $params[$rowId . "_!nativeeditor_status"];



        switch ($mode) {



            case "inserted":

                $action = $mode;

                break;



            case "deleted":

                $action = $this->delete_row_importFiles($rowId, $params);

                break;



            case "updated":

                $action = $this->update_row_importFiles($rowId, $params);

                break;



        }



        $action2 = xml_add_child($data, 'action', $action, false);



        xml_add_attribute($action2, 'type', $action);



        xml_add_attribute($action2, 'sid', $rowId);



        xml_add_attribute($action2, 'tid', $this->newId);



        return xml_print($dom, true);



    }



    function update_row_importFiles($rowId, $params)

    {



        $updatedata = array(



            'title' => iconv("UTF-8", "CP949", $params[$rowId . "_c2"]),



            'active' => $params[$rowId . "_c4"],



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('created', 'now()', FALSE);



        $this->db->where('file_no', $rowId);



        $this->db->update("eg_importfiles_g", $updatedata);



        return "updated";



    }



    function delete_row_importFiles($rowId, $params)

    {



        //delet file





        $this->db->where('file_no', $rowId);



        $this->db->delete('eg_importfiles_g');



        return "deleted";



    }





    function setImportFile($params)

    {



        $insdata = array(



            'trimester_no' => $params["trimester_no"],



            'title' => iconv("UTF-8", "CP949", $params["title"]),



            'filename' => $params["newfilename"],



            'active' => 1,



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('created', 'now()', FALSE);



        $this->db->insert('eg_importfiles_g', $insdata);



        $this->newId = $this->db->insert_id();



        return "inserted";



    }





    function delete_row_EGGrades($no)

    {



        $this->db->where('trimester_no', $no);



        $this->db->delete('eg_grades');



        return "deleted";



    }



    function insert_row_EGGrades($params)

    {



        $insdata = array(



            'file_no' => $params["file_no"],



            'trimester_no' => $params["trimester_no"],



            'engradeclassid' => iconv("UTF-8", "CP949", $params["engradeclassid"]),



            'classschoolyear' => $params["classschoolyear"],



            'classgradingperiod' => $params["classgradingperiod"],



            'classname' => iconv("UTF-8", "CP949", $params["classname"]),



            'teachername' => iconv("UTF-8", "CP949", $params["teachername"]),



            'studentfirst' => iconv("UTF-8", "CP949", $params["studentfirst"]),



            'studentlast' => iconv("UTF-8", "CP949", $params["studentlast"]),



            'studentid' => iconv("UTF-8", "CP949", $params["studentid"]),



            'grade' => $params["grade"],



            'percent' => $params["percent"],



            'missing' => $params["missing"],



            'teachercomment' => iconv("UTF-8", "CP949", $params["teachercomment"]),



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('regdate', 'now()', FALSE);



        $this->db->insert('eg_grades', $insdata);



        $this->newId = $this->db->insert_id();



        return "inserted";



    }



    function update_ImportFile($rowId)

    {



        $updatedata = array(



            'active' => 2,



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('created', 'now()', FALSE);



        $this->db->where('file_no', $rowId);



        $this->db->update("eg_importfiles_g", $updatedata);



        return "updated";



    }



    function getEGGrades($params)



    {



        $this->load->helper('xml');



        $sql = "";



        $columns = array("file_no", "engradeclassid", "classschoolyear", "classgradingperiod", "classname", "teachername", "studentfirst", "studentlast", "studentid", "grade", "percent", "missing", "teachercomment", "writer", "regdate");



        if (isset($params["orderby"])) {



            if ($params["direct"] == 'des')



                $direct = "DESC";



            else



                $direct = "ASC";



            $sql = " Order by " . $columns[$params["orderby"]] . " " . $direct;



        } else {



            $sql = " Order by no desc";



        }





        $posStart = "0";



        if (isset($params["posStart"]))



            $posStart = $params['posStart'];





        $sql = "select no,file_no,engradeclassid,classschoolyear,classgradingperiod,classname,teachername,studentfirst,studentlast,studentid,grade,percent,missing,teachercomment,writer,regdate from eg_grades where file_no=" . $params["fno"] . " " . $sql;



        $query = $this->db->query($sql);



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');



        xml_add_attribute($rows, 'total_count', $query->num_rows());



        xml_add_attribute($rows, 'pos', $posStart);



        foreach ($query->result_array() as $row5) {



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $row5['no']);



            $c1 = xml_add_child($item, 'cell', $row5['file_no'], true);



            $c2 = xml_add_child($item, 'cell', $row5['engradeclassid'], true);



            $c3 = xml_add_child($item, 'cell', $row5['classschoolyear'], true);



            $c4 = xml_add_child($item, 'cell', $row5['classgradingperiod'], true);



            $c5 = xml_add_child($item, 'cell', $row5['classname'], true);



            $c6 = xml_add_child($item, 'cell', $row5['teachername'], true);



            $c7 = xml_add_child($item, 'cell', $row5['studentfirst'], true);



            $c8 = xml_add_child($item, 'cell', $row5['studentlast'], true);



            $c9 = xml_add_child($item, 'cell', $row5['studentid'], true);



            $c10 = xml_add_child($item, 'cell', $row5['grade'], true);



            $c11 = xml_add_child($item, 'cell', $row5['percent'], true);



            $c12 = xml_add_child($item, 'cell', $row5['missing'], true);



            $c13 = xml_add_child($item, 'cell', $row5['teachercomment'], true);



            $c14 = xml_add_child($item, 'cell', $row5['writer'], true);



            $c15 = xml_add_child($item, 'cell', $row5['regdate'], true);



        }



        return xml_print($dom, true);



    }



    function getImportfileInfo($rowid)

    {



        $filenam = "";



        if (!empty($rowid)) {



            $sql = "select filename from eg_importfiles_g where file_no=" . $rowid;



            $result = $this->db->query($sql);



            if ($result->num_rows() > 0) {



                $row = $result->row();



                $filenam = $row->filename;



            }



        }



        return $filenam;



    }





    function menucontextUploadAttendace($params)

    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $menu = xml_add_child($dom, 'menu');

        xml_add_attribute($menu, 'id', "0");



        $item0 = xml_add_child($menu, 'item', NULL, true);

        xml_add_attribute($item0, 'text', "Import Temp Data");

        xml_add_attribute($item0, 'img', "");

        xml_add_attribute($item0, 'id', "import");



        $item1 = xml_add_child($menu, 'item', NULL, true);

        xml_add_attribute($item1, 'text', "Remove row");

        xml_add_attribute($item1, 'img', "");

        xml_add_attribute($item1, 'id', "remove");



        return xml_print($dom, true);



    }



    function getImportFiles2($params)



    {



        $this->load->helper('xml');



        $sql = "";



        $columns = array("file_no", "title", "filename", "writer", "created");



        if (isset($params["orderby"])) {



            if ($params["direct"] == 'des')



                $direct = "DESC";



            else



                $direct = "ASC";



            $sql = " Order by " . $columns[$params["orderby"]] . " " . $direct;



        } else {



            $sql = " Order by file_no desc";



        }





        $posStart = "0";



        if (isset($params["posStart"]))



            $posStart = $params['posStart'];





        $sql = "select file_no,trimester_no,title,filename,active,writer,created from eg_importfiles where trimester_no=" . $params["gno"] . " " . $sql;



        $query = $this->db->query($sql);



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');



        xml_add_attribute($rows, 'total_count', $query->num_rows());



        xml_add_attribute($rows, 'pos', $posStart);



        foreach ($query->result_array() as $row5) {



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $row5['file_no']);



            $c1 = xml_add_child($item, 'cell', $row5['file_no'], true);



            $c2 = xml_add_child($item, 'cell', $row5['trimester_no'], true);



            $c3 = xml_add_child($item, 'cell', $row5['title'], true);



            $c4 = xml_add_child($item, 'cell', $row5['filename'] . "^javascript:loadData(" . $row5['file_no'] . ");^_self", true);



            $c5 = xml_add_child($item, 'cell', $row5['active'], true);



            $c6 = xml_add_child($item, 'cell', $row5['writer'], true);



            $c7 = xml_add_child($item, 'cell', $row5['created'], true);



        }



        return xml_print($dom, true);



    }



    function setImportFiles2($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $rowId = $params["ids"];



        $this->newId = $rowId;



        $mode = $params[$rowId . "_!nativeeditor_status"];



        switch ($mode) {



            case "inserted":

                $action = $mode;

                break;



            case "deleted":

                $action = $this->delete_row_importFiles2($rowId, $params);

                break;



            case "updated":

                $action = $this->update_row_importFiles2($rowId, $params);

                break;



        }



        $action2 = xml_add_child($data, 'action', $action, false);



        xml_add_attribute($action2, 'type', $action);



        xml_add_attribute($action2, 'sid', $rowId);



        xml_add_attribute($action2, 'tid', $this->newId);



        return xml_print($dom, true);



    }



    function setImportFile2($params)

    {



        $insdata = array(



            'trimester_no' => $params["trimester_no"],



            'title' => iconv("UTF-8", "CP949", $params["title"]),



            'filename' => $params["newfilename"],



            'active' => 1,



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('created', 'now()', FALSE);



        $this->db->insert('eg_importfiles', $insdata);



        $this->newId = $this->db->insert_id();



        return "inserted";



    }



    function update_row_importFiles2($rowId, $params)

    {



        $updatedata = array(



            'title' => iconv("UTF-8", "CP949", $params[$rowId . "_c2"]),



            'active' => $params[$rowId . "_c4"],



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('created', 'now()', FALSE);



        $this->db->where('file_no', $rowId);



        $this->db->update("eg_importfiles", $updatedata);



        return "updated";



    }



    function delete_row_importFiles2($rowId, $params)

    {



        //delet file





        $this->db->where('file_no', $rowId);



        $this->db->delete('eg_importfiles');



        return "deleted";



    }



    function delete_row_EGAttendances($no)

    {



        $this->db->where('trimester_no', $no);



        $this->db->delete('eg_attendance');



        return "deleted";



    }



    function insert_row_EGAttendances($params)

    {



        $insdata = array(



            'file_no' => $params["file_no"],



            'trimester_no' => $params["trimester_no"],



            'engradeclassid' => iconv("UTF-8", "CP949", $params["engradeclassid"]),



            'classschoolyear' => $params["classschoolyear"],



            'classgradingperiod' => $params["classgradingperiod"],



            'classname' => iconv("UTF-8", "CP949", $params["classname"]),



            'studentfirst' => iconv("UTF-8", "CP949", $params["studentfirst"]),



            'studentlast' => iconv("UTF-8", "CP949", $params["studentlast"]),



            'studentid' => iconv("UTF-8", "CP949", $params["studentid"]),



            'attendancedate' => $params["attendancedate"],



            'mark' => $params["mark"],



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('regdate', 'now()', FALSE);



        $this->db->insert('eg_attendance', $insdata);



        $this->newId = $this->db->insert_id();



        return "inserted";



    }



    function update_ImportFile2($rowId)

    {



        $updatedata = array(



            'active' => 2,



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('created', 'now()', FALSE);



        $this->db->where('file_no', $rowId);



        $this->db->update("eg_importfiles", $updatedata);



        return "updated";



    }



    function getEGAttendances($params)



    {



        $this->load->helper('xml');



        $sql = "";



        $columns = array("file_no", "engradeclassid", "classschoolyear", "classgradingperiod", "classname", "studentfirst", "studentlast", "studentid", "attendancedate", "mark", "writer", "regdate");



        if (isset($params["orderby"])) {



            if ($params["direct"] == 'des')



                $direct = "DESC";



            else



                $direct = "ASC";



            $sql = " Order by " . $columns[$params["orderby"]] . " " . $direct;



        } else {



            $sql = " Order by no desc";



        }





        $posStart = "0";



        if (isset($params["posStart"]))



            $posStart = $params['posStart'];





        $sql = "select no,file_no,engradeclassid,classschoolyear,classgradingperiod,classname,studentfirst,studentlast,studentid,attendancedate,mark,writer,regdate from eg_attendance where file_no=" . $params["fno"] . " " . $sql;



        $query = $this->db->query($sql);



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');



        xml_add_attribute($rows, 'total_count', $query->num_rows());



        xml_add_attribute($rows, 'pos', $posStart);



        foreach ($query->result_array() as $row5) {



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $row5['no']);



            $c1 = xml_add_child($item, 'cell', $row5['file_no'], true);



            $c2 = xml_add_child($item, 'cell', $row5['engradeclassid'], true);



            $c3 = xml_add_child($item, 'cell', $row5['classschoolyear'], true);



            $c4 = xml_add_child($item, 'cell', $row5['classgradingperiod'], true);



            $c5 = xml_add_child($item, 'cell', $row5['classname'], true);



            $c7 = xml_add_child($item, 'cell', $row5['studentfirst'], true);



            $c8 = xml_add_child($item, 'cell', $row5['studentlast'], true);



            $c9 = xml_add_child($item, 'cell', $row5['studentid'], true);



            $c10 = xml_add_child($item, 'cell', $row5['attendancedate'], true);



            $c11 = xml_add_child($item, 'cell', $row5['mark'], true);



            $c14 = xml_add_child($item, 'cell', $row5['writer'], true);



            $c15 = xml_add_child($item, 'cell', $row5['regdate'], true);



        }



        return xml_print($dom, true);



    }



    function getImportfileInfo2($rowid)

    {



        $filenam = "";



        if (!empty($rowid)) {



            $sql = "select filename from eg_importfiles where file_no=" . $rowid;



            $result = $this->db->query($sql);



            if ($result->num_rows() > 0) {



                $row = $result->row();



                $filenam = $row->filename;



            }



        }



        return $filenam;



    }





    function menucontextRoles($params)

    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $menu = xml_add_child($dom, 'menu');

        xml_add_attribute($menu, 'id', "0");



        $item1 = xml_add_child($menu, 'item', NULL, true);

        xml_add_attribute($item1, 'text', "Remove row");

        xml_add_attribute($item1, 'img', "");

        xml_add_attribute($item1, 'id', "remove");



        return xml_print($dom, true);



    }





    function getRoles($params)



    {



        $this->load->helper('xml');



        $sql = "";



        $columns = array("id", "name", "roleorder", "resource");



        if (isset($params["orderby"])) {



            if ($params["direct"] == 'des')



                $direct = "DESC";



            else



                $direct = "ASC";



            $sql = " Order by " . $columns[$params["orderby"]] . " " . $direct;



        } else {



            $sql = " Order by id";



        }





        $posStart = "0";



        if (isset($params["posStart"]))



            $posStart = $params['posStart'];





        $sql = "select id,name,roleorder from ali_aclroles " . $sql;



        $query = $this->db->query($sql);



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');



        xml_add_attribute($rows, 'total_count', $query->num_rows());



        xml_add_attribute($rows, 'pos', $posStart);



        foreach ($query->result_array() as $row5) {



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $row5['id']);



            $c1 = xml_add_child($item, 'cell', $row5['id'], true);



            $c2 = xml_add_child($item, 'cell', $row5['name'], true);



            $c3 = xml_add_child($item, 'cell', $row5['roleorder'], true);



            $c4 = xml_add_child($item, 'cell', "click^javascript:loadData(" . $row5['id'] . ");^_self", true);



        }



        return xml_print($dom, true);



    }





    function setRoles($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $rowId = $params["ids"];



        $this->newId = $rowId;



        $mode = $params[$rowId . "_!nativeeditor_status"];



        switch ($mode) {



            case "inserted":

                $action = $mode;

                break;



            case "deleted":

                $action = $this->delete_row_roles($rowId, $params);

                break;



            case "updated":

                $action = $this->update_row_roles($rowId, $params);

                break;



        }



        $action2 = xml_add_child($data, 'action', $action, false);



        xml_add_attribute($action2, 'type', $action);



        xml_add_attribute($action2, 'sid', $rowId);



        xml_add_attribute($action2, 'tid', $this->newId);



        return xml_print($dom, true);



    }



    function update_row_roles($rowId, $params)

    {



        $updatedata = array(



            'name' => iconv("UTF-8", "CP949", $params[$rowId . "_c1"]),



            'roleorder' => $params[$rowId . "_c2"]



        );



        $this->db->where('id', $rowId);



        $this->db->update("ali_aclroles", $updatedata);



        return "updated";



    }



    function delete_row_roles($rowId, $params)

    {



        $this->db->where('id', $rowId);



        $this->db->delete('ali_aclroles');



        return "deleted";



    }





    function getRClasses($params)



    {



        $this->load->helper('xml');



        $sql = "";



        $columns = array("schoolyear", "trimester", "classname", "teachername");



        if (isset($params["orderby"])) {



            if ($params["direct"] == 'des')



                $direct = "DESC";



            else



                $direct = "ASC";



            $sql = " Order by " . $columns[$params["orderby"]] . " " . $direct;



        } else {



            $sql = " Order by sc.schoolyear desc, sc.trimester, sc.level, sc.session ";



        }





        if ($this->session->userdata('TOPLEVEL_AUTH') == 1 || $this->session->userdata('TOPLEVEL_AUTH') == 2) {



            $sql = "select sc.schoolyear, sc.trimester, ct.no, ct.class_no, sc.name as classname, concat(st.firstname,' ',st.lastname) as teachername from ali_remedialclass as sc inner join ali_remedialclassteachers as ct on ct.class_no=sc.no inner join ali_user as st on ct.teacher_no=st.no where sc.status=0 and st.roleid=3 and ct.isprimary=1 " . $sql;



        } else {



            $sql = "select sc.schoolyear, sc.trimester, ct.no, ct.class_no, sc.name as classname, concat(st.firstname,' ',st.lastname) as teachername from ali_remedialclass as sc inner join ali_remedialclassteachers as ct on ct.class_no=sc.no inner join ali_user as st on ct.teacher_no=st.no inner join ali_gradingperiod as ag on sc.schoolyear=ag.schoolyear and sc.trimester=ag.gradingperiod where ct.teacher_no=" . $this->session->userdata('ALISESS_USERNO') . " and sc.status=0 and st.roleid=3 and ag.active=1 " . $sql;



        }



        $query = $this->db->query($sql);



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');



        $j = 0;



        foreach ($query->result_array() as $row5) {



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $row5['no']);



            $c0 = xml_add_child($item, 'cell', $row5['schoolyear'], true);



            $c1 = xml_add_child($item, 'cell', "Tri" . $row5['trimester'], true);



            $c2 = xml_add_child($item, 'cell', $row5['classname'] . "^javascript:self.location.href=\"/index.php/aliweb/rattweeks?classno=" . $row5['class_no'] . "&classname=" . $row5['classname'] . "\";^_self", true);



            $c3 = xml_add_child($item, 'cell', $row5['teachername'], true);



            $c4 = xml_add_child($item, 'cell', "<div class=\"ops\" style=\"width:16px;height:40px;\"><ul><li id=\"fontgear\"></li><ul><li><a href=\"#\" onClick=\"doOnLoadInfo(" . $row5['class_no'] . ");\">Edit</a></li></ul></ul></div>", true);



            $j++;



        }





        if ($j == 0) {



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', 1);



            $c1 = xml_add_child($item, 'cell', "", true);



            $c2 = xml_add_child($item, 'cell', "No Data.", true);



            $c3 = xml_add_child($item, 'cell', "", true);



            $c3 = xml_add_child($item, 'cell', "", true);



            $c4 = xml_add_child($item, 'cell', "", true);



        }





        return xml_print($dom, true);



    }



    function getRClass($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $sql = "select ct.class_no, ct.teacher_no, sc.room_no, sc.name, sc.status, sc.schoolyear, sc.trimester, sc.level, sc.session, sc.classtype from ali_remedialclassteachers as ct inner join ali_remedialclass as sc on ct.class_no=sc.no where sc.status=0 and ct.isprimary=1 AND ct.class_no=" . $params['id'];



        $query = $this->db->query($sql);



        if ($query->num_rows() > 0) {



            $row5 = $query->row();



            $cell1 = xml_add_child($data, 'schoolyear', $row5->schoolyear, true);



            $cell1 = xml_add_child($data, 'trimester', $row5->trimester, true);



            $cell1 = xml_add_child($data, 'level', $row5->level, true);



            $cell1 = xml_add_child($data, 'session', $row5->session, true);



            $cell1 = xml_add_child($data, 'classtype', $row5->classtype, true);



            $cell3 = xml_add_child($data, 'teacher_no', $row5->teacher_no, true);



            $cell3 = xml_add_child($data, 'room_no', $row5->room_no, true);



            $cell3 = xml_add_child($data, 'name', $row5->name, true);



            $cell3 = xml_add_child($data, 'status', $row5->status, true);



            $cell4 = xml_add_child($data, 'id', $row5->class_no, true);



        }



        return xml_print($dom, true);



    }



    function setRClass($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $rowId = $params["ids"];



        $this->newId = $rowId;



        $mode = $params[$rowId . "_!nativeeditor_status"];



        switch ($mode) {



            case "inserted":

                $action = $this->add_row_rclasses($rowId, $params);

                break;



            case "deleted":

                $action = $this->delete_row_rclasses($rowId);

                break;



            case "updated":

                $action = $this->update_row_rclasses($rowId, $params);

                break;



        }



        $action2 = xml_add_child($data, 'action', $action, false);



        xml_add_attribute($action2, 'type', $action);



        xml_add_attribute($action2, 'sid', $rowId);



        xml_add_attribute($action2, 'tid', $this->newId);



        return xml_print($dom, true);



    }



    function add_row_rclasses($rowId, $params)

    {



        $insdata = array(



            'schoolyear' => $params[$rowId . "_schoolyear"],



            'trimester' => $params[$rowId . "_trimester"],



            'level' => $params[$rowId . "_level"],



            'session' => $params[$rowId . "_session"],



            'classtype' => $params[$rowId . "_classtype"],



            'room_no' => $params[$rowId . "_room_no"],



            'name' => $params[$rowId . "_name"],



            'status' => $params[$rowId . "_status"],



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('regdate', 'now()', FALSE);



        $this->db->insert('ali_remedialclass', $insdata);



        $this->newId = $this->db->insert_id();





        $sql = "select firstname,lastname from ali_user where roleid=3 and no=" . $params[$rowId . "_teacher_no"];



        $query = $this->db->query($sql);



        if ($query->num_rows() > 0) {



            $row5 = $query->row();



            $insdata2 = array(



                'class_no' => $this->newId,



                'teacher_no' => $params[$rowId . "_teacher_no"],



                'teachername' => $row5->firstname . " " . $row5->lastname,



                'isprimary' => 1,



                'permission' => 1,



                'writer' => $this->session->userdata('ALISESS_USERNAME')



            );



            $this->db->set('regdate', 'now()', FALSE);



            $this->db->insert('ali_remedialclassteachers', $insdata2);



        }





        $sql3 = "select name,wpercentage from ali_assign_cate_basic ";



        $query3 = $this->db->query($sql3);



        foreach ($query3->result_array() as $row5) {



            $insdata3 = array(



                'name' => $row5["name"],



                'wpercentage' => $row5["wpercentage"],



                'class_no' => $this->newId,



                'writer' => $this->session->userdata('ALISESS_USERNAME')



            );



            $this->db->set('regdate', 'now()', FALSE);



            $this->db->insert('ali_assign_cate', $insdata3);



        }





        return "inserted";



    }



    function update_row_rclasses($rowId, $params)

    {



        $updatedata = array(



            'schoolyear' => $params[$rowId . "_schoolyear"],



            'trimester' => $params[$rowId . "_trimester"],



            'level' => $params[$rowId . "_level"],



            'session' => $params[$rowId . "_session"],



            'classtype' => $params[$rowId . "_classtype"],



            'room_no' => $params[$rowId . "_room_no"],



            'name' => $params[$rowId . "_name"],



            'status' => $params[$rowId . "_status"],



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('regdate', 'now()', FALSE);



        $this->db->where('no', $rowId);



        $this->db->update('ali_remedialclass', $updatedata);





        return "updated";



    }



    function delete_row_rclasses($rowId)

    {



        //$this->db->where('class_no', $rowId);  $this->db->delete('ali_grade_new');



        //$this->db->where('class_no', $rowId);  $this->db->delete('ali_assignments');



        //$this->db->where('class_no', $rowId);  $this->db->delete('ali_assign_cate');



        //$this->db->where('class_no', $rowId);  $this->db->delete('ali_classteachers');



        //$this->db->where('class_no', $rowId);  $this->db->delete('ali_roster');



        //$this->db->where('no', $rowId);  $this->db->delete('ali_class');



        //$this->db->where('class_no', $rowId);  $this->db->delete('ali_attendance_new');





        $updatedata = array(



            'status' => 9,



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('regdate', 'now()', FALSE);



        $this->db->where('no', $rowId);



        $this->db->update('ali_remedialclass', $updatedata);



        return "deleted";



    }





    function getRAttendance($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');





        $head = xml_add_child($rows, 'head', NULL, true);



        $colm = xml_add_child($head, 'column', "Students", true);



        xml_add_attribute($colm, 'width', "160");



        xml_add_attribute($colm, 'type', "link");



        xml_add_attribute($colm, 'align', "left");



        xml_add_attribute($colm, 'sort', "str");





        $time = strtotime($params["sday"]);



        $k = date('w', $time);



        if ($k == 1):



            $monv = date('Y-m-d', $time);



            $tuev = date('Y-m-d', strtotime("+1 day", $time));



            $wesv = date('Y-m-d', strtotime("+2 day", $time));



            $thuv = date('Y-m-d', strtotime("+3 day", $time));



            $friv = date('Y-m-d', strtotime("+4 day", $time));



            $satv = date('Y-m-d', strtotime("+5 day", $time));



            $sunv = date('Y-m-d', strtotime("+6 day", $time));



            $sunv = date('Y-m-d', strtotime("+6 day", $time));



            $mon = date('d D', $time);



            $tue = date('d D', strtotime("+1 day", $time));



            $wes = date('d D', strtotime("+2 day", $time));



            $thu = date('d D', strtotime("+3 day", $time));



            $fri = date('d D', strtotime("+4 day", $time));



            $sat = date('d D', strtotime("+5 day", $time));



            $sun = date('d D', strtotime("+6 day", $time));



        else:



            $fsv = strtotime('Last Monday', $time);



            $monv = date('Y-m-d', $fsv);



            $tuev = date('Y-m-d', strtotime("+1 day", $fsv));



            $wesv = date('Y-m-d', strtotime("+2 day", $fsv));



            $thuv = date('Y-m-d', strtotime("+3 day", $fsv));



            $friv = date('Y-m-d', strtotime("+4 day", $fsv));



            $satv = date('Y-m-d', strtotime("+5 day", $fsv));



            $sunv = date('Y-m-d', strtotime("+6 day", $fsv));





            $mon = date('d D', $fsv);



            $tue = date('d D', strtotime("+1 day", $fsv));



            $wes = date('d D', strtotime("+2 day", $fsv));



            $thu = date('d D', strtotime("+3 day", $fsv));



            $fri = date('d D', strtotime("+4 day", $fsv));



            $sat = date('d D', strtotime("+5 day", $fsv));



            $sun = date('d D', strtotime("+6 day", $fsv));



        endif;



        //Monday



        $colm = xml_add_child($head, 'column', $mon, true);



        xml_add_attribute($colm, 'width', "54");



        xml_add_attribute($colm, 'type', "ed");



        xml_add_attribute($colm, 'align', "center");



        xml_add_attribute($colm, 'sort', "str");



        //Tuesday



        $colm = xml_add_child($head, 'column', $tue, true);



        xml_add_attribute($colm, 'width', "54");



        xml_add_attribute($colm, 'type', "ed");



        xml_add_attribute($colm, 'align', "center");



        xml_add_attribute($colm, 'sort', "str");



        //Wednesday



        $colm = xml_add_child($head, 'column', $wes, true);



        xml_add_attribute($colm, 'width', "54");



        xml_add_attribute($colm, 'type', "ed");



        xml_add_attribute($colm, 'align', "center");



        xml_add_attribute($colm, 'sort', "str");



        //Thursday



        $colm = xml_add_child($head, 'column', $thu, true);



        xml_add_attribute($colm, 'width', "54");



        xml_add_attribute($colm, 'type', "ed");



        xml_add_attribute($colm, 'align', "center");



        xml_add_attribute($colm, 'sort', "str");



        //Friday



        $colm = xml_add_child($head, 'column', $fri, true);



        xml_add_attribute($colm, 'width', "54");



        xml_add_attribute($colm, 'type', "ed");



        xml_add_attribute($colm, 'align', "center");



        xml_add_attribute($colm, 'sort', "str");



        //Saturday



        $colm = xml_add_child($head, 'column', $sat, true);



        xml_add_attribute($colm, 'width', "54");



        xml_add_attribute($colm, 'type', "ed");



        xml_add_attribute($colm, 'align', "center");



        xml_add_attribute($colm, 'sort', "str");



        //Sunday



        $colm = xml_add_child($head, 'column', $sun, true);



        xml_add_attribute($colm, 'width', "54");



        xml_add_attribute($colm, 'type', "ed");



        xml_add_attribute($colm, 'align', "center");



        xml_add_attribute($colm, 'sort', "str");





        $sql1 = "SELECT AT.students_no, AT.fullname,MAX( IF(WEEKDAY(AT.attendance_day)=0,CONCAT(AT.marks,'|',AT.no),'') ) AS mon,MAX( IF(WEEKDAY(AT.attendance_day)=1,CONCAT(AT.marks,'|',AT.no),'') ) AS tue,MAX( IF(WEEKDAY(AT.attendance_day)=2,CONCAT(AT.marks,'|',AT.no),'') ) AS wes,MAX( IF(WEEKDAY(AT.attendance_day)=3,CONCAT(AT.marks,'|',AT.no),'') ) AS thu,MAX( IF(WEEKDAY(AT.attendance_day)=4,CONCAT(AT.marks,'|',AT.no),'') ) AS fri,MAX( IF(WEEKDAY(AT.attendance_day)=5,CONCAT(AT.marks,'|',AT.no),'') ) AS sat,MAX( IF(WEEKDAY(AT.attendance_day)=6,CONCAT(AT.marks,'|',AT.no),'') ) AS sun FROM (SELECT ST.no,SS.students_no, CONCAT(SS.lastname,', ',SS.firstname) as fullname,ST.attendance_day, ST.marks FROM `ali_remedialroster` as SS left join (SELECT  no, student_no, attendance_day,marks,class_no FROM `ali_remedialattendance` where attendance_day between '" . $monv . "' and '" . $sunv . "' ) as ST ON SS.students_no=ST.student_no and SS.class_no=ST.class_no inner join ali_students as bb on SS.students_no=bb.students_no WHERE bb.progress='r' and SS.class_no=" . $params["classno"] . ") AS AT GROUP BY AT.fullname, AT.students_no ";



        $query1 = $this->db->query($sql1);



        foreach ($query1->result_array() as $row5) {



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $row5['students_no']);





            $c1 = xml_add_child($item, 'cell', $row5['fullname'] . "^javascript:self.location.href=\"/index.php/aliweb/rattmonths?stno=" . $row5['students_no'] . "\";^_self", true);





            $carr2 = explode('|', $row5["mon"]);



            $c2 = xml_add_child($item, 'cell', $carr2[0], true);

            xml_add_attribute($c2, 'style', "background-color: #FFFFF0;");



            xml_add_attribute($c2, 'selday', $monv);



            if (!empty($carr2[1])): xml_add_attribute($c2, 'attno', $carr2[1]); endif;





            $carr3 = explode('|', $row5["tue"]);



            $c3 = xml_add_child($item, 'cell', $carr3[0], true);



            xml_add_attribute($c3, 'selday', $tuev);



            if (!empty($carr3[1])): xml_add_attribute($c3, 'attno', $carr3[1]); endif;





            $carr4 = explode('|', $row5["wes"]);



            $c4 = xml_add_child($item, 'cell', $carr4[0], true);

            xml_add_attribute($c4, 'style', "background-color: #FFFFF0;");



            xml_add_attribute($c4, 'selday', $wesv);



            if (!empty($carr4[1])): xml_add_attribute($c4, 'attno', $carr4[1]); endif;





            $carr5 = explode('|', $row5["thu"]);



            $c5 = xml_add_child($item, 'cell', $carr5[0], true);



            xml_add_attribute($c5, 'selday', $thuv);



            if (!empty($carr5[1])): xml_add_attribute($c5, 'attno', $carr5[1]); endif;





            $carr6 = explode('|', $row5["fri"]);



            $c6 = xml_add_child($item, 'cell', $carr6[0], true);

            xml_add_attribute($c6, 'style', "background-color: #FFFFF0;");



            xml_add_attribute($c6, 'selday', $friv);



            if (!empty($carr6[1])): xml_add_attribute($c6, 'attno', $carr6[1]); endif;





            $carr7 = explode('|', $row5["sat"]);



            $c7 = xml_add_child($item, 'cell', $carr7[0], true);



            xml_add_attribute($c7, 'selday', $satv);



            if (!empty($carr7[1])): xml_add_attribute($c7, 'attno', $carr7[1]); endif;





            $carr8 = explode('|', $row5["sun"]);



            $c8 = xml_add_child($item, 'cell', $carr8[0], true);

            xml_add_attribute($c8, 'style', "background-color: #FFFFF0;");



            xml_add_attribute($c8, 'selday', $sunv);



            if (!empty($carr8[1])): xml_add_attribute($c8, 'attno', $carr8[1]); endif;





        }





        return xml_print($dom, true);



    }



    function setRAttendance($params)



    {



        $action = "";



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $rowId = $params["ids"];



        $this->newId = $rowId;



        $mode = $params[$rowId . "_!nativeeditor_status"];



        switch ($mode) {



            case "inserted":

                $action = $this->insert_row_rattendance($rowId, $params);

                break;



            case "updated":

                $action = $this->update_row_rattendance($rowId, $params);

                break;



            case "deleted":

                $action = $this->delete_row_rattendance($rowId, $params);

                break;



        }



        $action2 = xml_add_child($data, 'action', $action, false);



        xml_add_attribute($action2, 'type', $action);



        xml_add_attribute($action2, 'sid', $rowId);



        xml_add_attribute($action2, 'tid', $this->newId);



        return xml_print($dom, true);



    }



    function insert_row_rattendance($rowId, $params)

    {





        $sql = "select no from ali_remedialattendance where attendance_day='" . $params[$rowId . "_selday"] . "' and student_no=" . $params[$rowId . "_stdno"] . " and class_no=" . $params[$rowId . "_classno"];



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row5) {



            $params[$rowId . "_attno"] = $row5["no"];



            return $this->update_row_attendance($rowId, $params);



        }





        $insdata = array(



            'class_no' => $params[$rowId . "_classno"],



            'student_no' => $params[$rowId . "_stdno"],



            'marks' => strtoupper($params[$rowId . "_attval"]),



            'attendance_day' => $params[$rowId . "_selday"],



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('regdate', 'now()', FALSE);



        $this->db->insert('ali_remedialattendance', $insdata);



        return "inserted";



    }



    function update_row_rattendance($rowId, $params)

    {



        $updatedata = array(



            'marks' => strtoupper($params[$rowId . "_attval"]),



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('regdate', 'now()', FALSE);



        $this->db->where('no', $params[$rowId . "_attno"]);



        $this->db->update('ali_remedialattendance', $updatedata);



        return "updated";



    }



    function delete_row_rattendance($rowId, $params)

    {



        $this->db->where('no', $params[$rowId . "_attno"]);



        $this->db->delete('ali_remedialattendance');



        return "deleted";



    }





    function rclassGP($classno)

    {



        $sql = "select ac.name as classname,concat(ac.schoolyear,' / ','Tri',ac.trimester) as gp from ali_remedialclass as ac where ac.no=" . $classno;



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row5) {



            return array($row5["classname"], $row5["gp"]);



        }



        return array();



    }





    function getRCla($stdno, $year, $tri)



    {



        $sql = "SELECT class_no FROM ali_remedialroster WHERE students_no=" . $stdno . " and schoolyear='" . $year . "' and trimester='" . $tri . "' group by classtype, class_no";



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row5) {



            return $row5["class_no"];



        }



        return 0;



    }





    function getRAttMonths($params)



    {



        $sum_p = 0;



        $sum_a = 0;



        $sum_t = 0;



        $res = array();



        $sql1 = "SELECT no, DATE_FORMAT(attendance_day,'%Y') as yy,DATE_FORMAT(attendance_day,'%m') as mm,DATE_FORMAT(attendance_day,'%d') as dd, marks FROM `ali_remedialattendance` where student_no=" . $params["stno"] . " and class_no=" . $params["classno"] . " ";



        $query1 = $this->db->query($sql1);



        foreach ($query1->result_array() as $row5) {



            $res["" . $row5["yy"] . ""]["" . $row5["mm"] . ""]["" . $row5["dd"] . ""] = $row5["marks"];





            //$vl= substr($row5["marks"],0,1);



            $vl = $row5["marks"];



            if ($vl == "P") {

                $sum_p++;

            }



            // if( $vl == "T" ){ $sum_t++; }



            if ($vl == "A") {

                $sum_a++;

            }



            //$vr= substr($row5["marks"],1,1);



            // $vr= $row5["marks"];



            //if( $vr == "P" ){ $sum_p++; }



            // if( $vr == "T" ){ $sum_t++; }



            //if( $vr == "A" ){ $sum_a++; }



        }



        $sum_ls = $sum_p + $sum_a;



        $tardytoabsent = 0;



        $attrate = 100 - round((($tardytoabsent + $sum_a) / ($sum_ls)) * 100, 1);





        $studentfullname = "";



        $sql3 = "select CONCAT(firstname,' ',lastname) AS fullname from ali_students where students_no=" . $params['stno'];



        $query3 = $this->db->query($sql3);



        if ($query3->num_rows() > 0) {



            $row3 = $query3->row();



            $studentfullname = $row3->fullname;



        }



        return array($res, $sum_p, $sum_t, $sum_a, $studentfullname, $attrate . "%");



    }





    function getRClassStudents($params)



    {



        $this->load->helper('xml');

        $sql = "";



        $columns = array("fullname", "student_ID", "logindate", "");



        if (isset($params["orderby"])) {



            if ($params["direct"] == 'des') $direct = "DESC"; else $direct = "ASC";



            $sql = " Order by " . $columns[$params["orderby"]] . " " . $direct;



        } else {



            $sql = " Order by CONCAT(A.lastname,', ',A.firstname)";



        }





        $sql = "select A.students_no,CONCAT(A.lastname,', ',A.firstname) AS fullname,A.student_ID, IF(B.login is null,'Never Login In',B.login) as logindate, C.email from ali_remedialroster AS A LEFT JOIN (select username, max(attempt_time) AS login from ali_user_login_attempt group by username) AS B ON A.student_ID=B.username LEFT JOIN ali_students AS C ON A.students_no=C.students_no WHERE C.progress='r' AND A.class_no=" . $params["classno"] . " " . $sql;



        $query = $this->db->query($sql);



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');



        foreach ($query->result_array() as $row5) {



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $row5['students_no']);



            $c1 = xml_add_child($item, 'cell', $row5['fullname'], true);



            $c2 = xml_add_child($item, 'cell', $row5['student_ID'], true);



            $c3 = xml_add_child($item, 'cell', $row5['logindate'], true);



            $c5 = xml_add_child($item, 'cell', "<div class=\"ops\" style=\"width:16px;height:40px;\"><ul><li id=\"fontgear\"></li><ul><li><a href=\"#\" onClick=\"doOnLoadMessage(" . $row5['students_no'] . ",'" . $row5['fullname'] . "','" . $row5['email'] . "');\">Send Message</a></li></ul></ul></div>", true);



        }



        return xml_print($dom, true);



    }



    function getRSchoolRoster($params)



    {



        $this->load->helper('xml');

        $sql = "";



        $columns = array("fullname", "student_ID");



        if (isset($params["orderby"])) {



            if ($params["direct"] == 'des') $direct = "DESC"; else $direct = "ASC";



            $sql = " Order by " . $columns[$params["orderby"]] . " " . $direct;



        } else {



            $sql = " Order by students_no";



        }





        $searchquery = "";



        if (!empty($params["vseach"])) {



            $searchquery = " and CONCAT(lastname,' ',firstname) like '%" . $params["vseach"] . "%'";



        }





        $sql = "SELECT students_no,student_ID,CONCAT(lastname,', ',firstname) AS fullname FROM ali_students WHERE progress in ('r') and students_no not in (select students_no from ali_remedialroster where class_no=" . $params["classno"] . ") " . $searchquery . $sql;



        $query = $this->db->query($sql);



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');



        foreach ($query->result_array() as $row5) {



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $row5['students_no']);



            $c1 = xml_add_child($item, 'cell', $row5['fullname'], true);



            $c3 = xml_add_child($item, 'cell', $row5['student_ID'], true);



        }



        return xml_print($dom, true);



    }



    function getRClassRoster($params)



    {



        $this->load->helper('xml');

        $sql = "";



        $columns = array("fullname", "student_ID");



        if (isset($params["orderby"])) {



            if ($params["direct"] == 'des') $direct = "DESC"; else $direct = "ASC";



            $sql = " Order by " . $columns[$params["orderby"]] . " " . $direct;



        } else {



            $sql = " Order by no";



        }



        $sql = "select students_no,CONCAT(lastname,', ',firstname) AS fullname,student_ID from ali_remedialroster where class_no=" . $params["classno"] . " " . $sql;



        $query = $this->db->query($sql);



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');



        foreach ($query->result_array() as $row5) {



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $row5['students_no']);



            $c1 = xml_add_child($item, 'cell', $row5['fullname'], true);



            $c3 = xml_add_child($item, 'cell', $row5['student_ID'], true);



        }



        return xml_print($dom, true);



    }



    function setRClassRoster($params)

    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $rowId = $params["ids"];



        $this->newId = $rowId;



        $mode = $params[$rowId . "_!nativeeditor_status"];



        switch ($mode) {



            case "inserted":

                $action = $this->delete_row_rrosters($params[$rowId . "_class_no"]);

                $action = $this->add_row_rrosters($rowId, $params);

                break;



            case "deleted":

                $action = $mode;

                break;



            case "updated":

                $action = $this->delete_row_rrosters($params[$rowId . "_class_no"]);

                $action = $this->add_row_rrosters($rowId, $params);

                break;



        }



        $action2 = xml_add_child($data, 'action', $action, false);



        xml_add_attribute($action2, 'type', $action);



        xml_add_attribute($action2, 'sid', $rowId);



        xml_add_attribute($action2, 'tid', $this->newId);





        return xml_print($dom, true);



    }



    function add_row_rrosters($rowId, $params)

    {



        $schoolyear = "";

        $trimester = "";

        $level = 0;

        $session = 0;





        $sql = "select schoolyear,trimester,level,session from ali_remedialclass where no=" . $params[$rowId . "_class_no"];



        $query = $this->db->query($sql);



        if ($query->num_rows() > 0) {



            $row5 = $query->row();



            $schoolyear = $row5->schoolyear;



            $trimester = $row5->trimester;



            $level = $row5->level;



            $session = $row5->session;



        }





        $lines = explode(";", $params[$rowId . "_lines"]);



        $cnt = 0;



        foreach ($lines as $v) {



            $nval = explode("|:|", $v);



            if ($nval[0] > 0) {



                $fullname = explode(", ", $nval[1]);



                $insdata = array(



                    'schoolyear' => $schoolyear,



                    'trimester' => $trimester,



                    'level' => $level,



                    'session' => $session,



                    'class_no' => $params[$rowId . "_class_no"],



                    'students_no' => $nval[0],



                    'firstname' => $fullname[1],



                    'lastname' => $fullname[0],



                    'student_ID' => $nval[2],



                    'writer' => $this->session->userdata('ALISESS_USERNAME')



                );



                $this->db->set('regdate', 'now()', FALSE);



                $this->db->insert('ali_remedialroster', $insdata);



                $cnt++;



            }



        }



        if ($cnt > 0) {



            $this->newId = $this->db->insert_id();



        }





        return "inserted";



    }



    function delete_row_rrosters($rowId)

    {



        $this->db->where('class_no', $rowId);



        $this->db->delete('ali_remedialroster');



        return "deleted";



    }





    function setRClassTeacher($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $rowId = $params["ids"];



        $this->newId = $rowId;



        $mode = $params[$rowId . "_!nativeeditor_status"];



        switch ($mode) {



            case "inserted":

                $action = $this->add_row_rclassteacher($rowId, $params);

                break;



            case "deleted":

                $action = $this->delete_row_rclassteacher($rowId, $params);

                break;



            case "updated":

                $action = $this->update_row_rclassteacher($rowId, $params);

                break;



        }



        $action2 = xml_add_child($data, 'action', $action, false);



        xml_add_attribute($action2, 'type', $action);



        xml_add_attribute($action2, 'sid', $rowId);



        xml_add_attribute($action2, 'tid', $this->newId);



        return xml_print($dom, true);



    }



    function add_row_rclassteacher($rowId, $params)

    {



        if ($params[$rowId . "_isprimary"] == 1) {



            $updatedata1 = array(



                'isprimary' => 0,



                'writer' => $this->session->userdata('ALISESS_USERNAME')



            );



            $this->db->set('updatedate', 'now()', FALSE);



            $this->db->where('isprimary', 1);



            $this->db->where('class_no', $params[$rowId . "_class_no"]);



            $this->db->update('ali_remedialclassteachers', $updatedata1);



        }





        $insdata = array(



            'class_no' => $params[$rowId . "_class_no"],



            'teacher_no' => $params[$rowId . "_teacher_no"],



            'teachername' => $params[$rowId . "_teachername"],



            'isprimary' => $params[$rowId . "_isprimary"],



            'permission' => $params[$rowId . "_permission"],



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('regdate', 'now()', FALSE);



        $this->db->insert('ali_remedialclassteachers', $insdata);



        $this->newId = $this->db->insert_id();



        return "inserted";



    }



    function update_row_rclassteacher($rowId, $params)

    {



        if ($params[$rowId . "_isprimary"] == 1) {



            $updatedata1 = array(



                'isprimary' => 0,



                'writer' => $this->session->userdata('ALISESS_USERNAME')



            );



            $this->db->set('updatedate', 'now()', FALSE);



            $this->db->where('isprimary', 1);



            $this->db->where('class_no', $params[$rowId . "_class_no"]);



            $this->db->update('ali_remedialclassteachers', $updatedata1);



        } else {





            if ($params[$rowId . "_oldprimary"] == 1) {



                $updatedata1 = array(



                    'isprimary' => 1,



                    'writer' => $this->session->userdata('ALISESS_USERNAME')



                );



                $this->db->set('updatedate', 'now()', FALSE);



                $ignore = array($rowId);



                $this->db->where_not_in('no', $ignore);



                $this->db->where('class_no', $params[$rowId . "_class_no"]);



                $this->db->limit(1);



                $this->db->update('ali_remedialclassteachers', $updatedata1);



            }





        }





        $updatedata = array(



            'class_no' => $params[$rowId . "_class_no"],



            'teacher_no' => $params[$rowId . "_teacher_no"],



            'teachername' => $params[$rowId . "_teachername"],



            'isprimary' => $params[$rowId . "_isprimary"],



            'permission' => $params[$rowId . "_permission"],



            'writer' => $this->session->userdata('ALISESS_USERNAME')



        );



        $this->db->set('updatedate', 'now()', FALSE);



        $this->db->where('no', $rowId);



        $this->db->update('ali_classteachers', $updatedata);



        return "updated";



    }



    function delete_row_rclassteacher($rowId, $params)

    {



        if ($params[$rowId . "_isprimary"] == 1) {



            $updatedata1 = array(



                'isprimary' => 1,



                'writer' => $this->session->userdata('ALISESS_USERNAME')



            );



            $this->db->set('updatedate', 'now()', FALSE);



            $ignore = array($rowId);



            $this->db->where_not_in('no', $ignore);



            $this->db->where('class_no', $params[$rowId . "_class_no"]);



            $this->db->limit(1);



            $this->db->update('ali_remedialclassteachers', $updatedata1);



        }





        $this->db->where('no', $rowId);



        $this->db->delete('ali_remedialclassteachers');



        return "deleted";



    }



    function getRClassTeacher($params)



    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $data = xml_add_child($dom, 'data');



        $sql = "select no,class_no,teacher_no,teachername,permission,isprimary from ali_remedialclassteachers where no=" . $params['id'];



        $query = $this->db->query($sql);



        if ($query->num_rows() > 0) {



            $row5 = $query->row();



            $cell2 = xml_add_child($data, 'class_no', $row5->class_no, true);



            $cell3 = xml_add_child($data, 'teacher_no', $row5->teacher_no, true);



            $cell4 = xml_add_child($data, 'permission', $row5->permission, true);



            $cell5 = xml_add_child($data, 'isprimary', $row5->isprimary, true);



            $cell6 = xml_add_child($data, 'id', $row5->no, true);



        }



        return xml_print($dom, true);



    }



    function getRClassTeachers($params)



    {



        $this->load->helper('xml');

        $sql = "";



        $columns = array("teachername", "permission", "isprimary", "logindate", "");



        if (isset($params["orderby"])) {



            if ($params["direct"] == 'des') $direct = "DESC"; else $direct = "ASC";



            $sql = " Order by " . $columns[$params["orderby"]] . " " . $direct;



        } else {



            $sql = " Order by no";



        }



        $sql = "select no,teacher_no, teachername,permission,isprimary, IF(updatedate='0000-00-00','Never Login In',updatedate) as logindate from ali_remedialclassteachers WHERE class_no=" . $params["classno"] . " " . $sql;



        $query = $this->db->query($sql);



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');



        $j = 0;



        foreach ($query->result_array() as $row5) {



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $row5['no']);



            $c1 = xml_add_child($item, 'cell', $row5['teachername'], true);



            $c2 = xml_add_child($item, 'cell', $row5['permission'], true);



            $c3 = xml_add_child($item, 'cell', $row5['isprimary'], true);



            $c4 = xml_add_child($item, 'cell', $row5['logindate'], true);



            $c5 = xml_add_child($item, 'cell', "<div class=\"ops\" style=\"width:16px;height:40px;\"><ul><li id=\"fontgear\"></li><ul><li><a href=\"#\" onClick=\"doOnLoadInfo(" . $row5['no'] . "," . $row5['teacher_no'] . "," . $row5['isprimary'] . ");\">Edit</a></li></ul></ul></div>", true);



            $j++;



        }





        if ($j == 0) {



            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', 1);



            $c1 = xml_add_child($item, 'cell', "No Data." . $params["classno"], true);



            $c2 = xml_add_child($item, 'cell', 0, true);



            $c3 = xml_add_child($item, 'cell', 0, true);



            $c4 = xml_add_child($item, 'cell', "", true);



            $c5 = xml_add_child($item, 'cell', "", true);



        }





        return xml_print($dom, true);



    }



    function getComRClassTeachers($params)

    {



        $this->load->helper('xml');



        $sql = "";



        $dom = xml_dom();



        $comp = xml_add_child($dom, 'complete');





        if ($params["mod"] == "insert") {



            $sql = "select no,firstname,lastname from ali_user where active=1 AND roleid=3 and no not in (select teacher_no from ali_remedialclassteachers where class_no=" . $params["classno"] . " )";



        }



        if ($params["mod"] == "update") {



            $sql = "select no,firstname,lastname from ali_user where active=1 AND roleid=3 and no=" . $params["teacher_no"] . "";



        }





        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row5) {



            $item2 = xml_add_child($comp, 'option', $row5['firstname'] . " " . $row5['lastname'], false);



            xml_add_attribute($item2, 'value', $row5['no']);



        }



        return xml_print($dom, true);



    }





    function getRAttSheet($params)

    {



        $arratt = array();



        $sql = "select a.no,a.class_no,CONCAT(a.lastname,', ',a.firstname) AS fullname, a.students_no from ali_remedialroster AS a inner join ali_students AS b on a.students_no=b.students_no where b.progress='r' and a.schoolyear='" . $params["vyear"] . "' and a.trimester='" . $params["vtrim"] . "' and a.class_no=" . $params["vcno"] . " order by CONCAT(a.lastname,', ',a.firstname)";



        $query = $this->db->query($sql);



        foreach ($query->result_array() as $row5) {



            $arratt["" . $row5['students_no'] . ""]['stdname'] = $row5['fullname'];



            $sql2 = "select class_no,student_no,marks,attendance_day from `ali_remedialattendance` where student_no=" . $row5['students_no'] . " and class_no=" . $row5['class_no'] . " and DATE_FORMAT(attendance_day,'%Y/%m')='" . $params["vyear"] . "/" . $params["vmon"] . "'";



            $query2 = $this->db->query($sql2);



            foreach ($query2->result_array() as $row1) {



                $arratt["" . $row5['students_no'] . ""]['attd']["" . $row1['attendance_day'] . ""] = $row1['marks'];



            }



        }





        $this->load->helper('xml');



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');





        $i = 1;



        $subarr = array();



        foreach ($arratt as $key => $val) {



            $subarr = $arratt[$key];





            $item = xml_add_child($rows, 'row', NULL, true);

            xml_add_attribute($item, 'id', $key);



            $c0 = xml_add_child($item, 'cell', $i, true);



            $c1 = xml_add_child($item, 'cell', $subarr['stdname'], true);





            for ($d = 1; $d <= 41; $d++) {



                $dw = date('w', strtotime($params["vyear"] . "-" . $params["vmon"] . "-" . $d));



                if ($dw == 1 || $dw == 2 || $dw == 3 || $dw == 4) {



                    $vdate = date('Y-m-d', strtotime($params["vyear"] . "-" . $params["vmon"] . "-" . $d));





                    if (ISSET($subarr['attd'][$vdate])) {



                        $strv = $subarr['attd'][$vdate];



                        $c3 = xml_add_child($item, 'cell', substr($strv, 0, 1), true);



                        $c3 = xml_add_child($item, 'cell', substr($strv, 1, 1), true);



                    } else {



                        $c3 = xml_add_child($item, 'cell', '', true);



                        $c3 = xml_add_child($item, 'cell', '', true);

                        xml_add_attribute($item, 'rowspan', '3');



                    }





                }



            }





            $i++;



        }





        return xml_print($dom, true);



    }



    function getRStudentList($params)

    {



        $this->load->helper('xml');



        $dom = xml_dom();



        $rows = xml_add_child($dom, 'rows');





        $head = xml_add_child($rows, 'head', NULL, true);



        $colm = xml_add_child($head, 'column', "No", true);



        xml_add_attribute($colm, 'id', "students_no");



        xml_add_attribute($colm, 'width', "26");



        xml_add_attribute($colm, 'type', "ro");



        xml_add_attribute($colm, 'align', "center");



        xml_add_attribute($colm, 'sort', "int");





        $colm = xml_add_child($head, 'column', "Student", true);



        xml_add_attribute($colm, 'id', "studentname");



        xml_add_attribute($colm, 'width', "220");



        xml_add_attribute($colm, 'type', "ro");



        xml_add_attribute($colm, 'align', "left");



        xml_add_attribute($colm, 'sort', "str");





        $subquery = "";



        //    if($params["level"] != ""){



        //       $subquery .= " and aa.level=".$params["level"];



        //   }



        //  if($params["session"] != ""){



        //      $subquery .= " and aa.session=".$params["session"];



        //  }





        $sql1 = "Select aa.students_no, CONCAT(st.lastname,', ',st.firstname) as studentname from ali_remedialroster as aa INNER JOIN ali_students AS st ON aa.students_no = st.students_no  where st.progress='r' and aa.session not in (4) and aa.schoolyear='" . $params["year"] . "' and aa.trimester='" . $params["trim"] . "' " . $subquery . " group by  CONCAT(st.lastname,', ',st.firstname), aa.students_no order by CONCAT(st.lastname,', ',st.firstname) ";



        $query1 = $this->db->query($sql1);



        $k = 1;



        foreach ($query1->result_array() as $row5) {



            $item = xml_add_child($rows, 'row', NULL, true);



            xml_add_attribute($item, 'id', $k);



            $c0 = xml_add_child($item, 'cell', $row5["students_no"], false);



            $c1 = xml_add_child($item, 'cell', $row5["studentname"], false);



            $k++;



        }



        return xml_print($dom, true);



    }





    function getFinancialList($sno)

    {



        $sql = "SELECT no,schoolyear,trimester,paiddate,description,latefees,amountpaid,refunds,method,notes,updated,writer,students_no FROM ali_finance where students_no=" . $sno . " order by schoolyear,trimester,paiddate ASC";



        $query = $this->db->query($sql);



        return $query->result_array();



    }


    function getAllFinance($params)

    {

        $arratt = array();

        $ssss = array();

        $sql = "SELECT DISTINCT a.students_no, CONCAT(b.firstname, ',', b.lastname) AS Fullname FROM ali_finance AS a, ali_students AS b WHERE a.schoolyear ='".$params["year"]."' AND a.trimester ='".$params["trim"]."' AND a.students_no=b.students_no ORDER BY a.students_no ASC";

        $query = $this->db->query($sql);
        $description = "";

        foreach ($query->result_array() as $row5) {

            $arratt["" . $row5['students_no'] . ""]['stdname'] = $row5['Fullname'];


            $sql2 = "SELECT students_no, paiddate, description, rnum FROM ( SELECT a.*, ( CASE @vjob WHEN a.students_no THEN @rownum := @rownum +1 ELSE @rownum := 1 END ) rnum, (@vjob := a.students_no) vjob FROM ali_finance a, ( SELECT @vjob := '', @rownum := 0 FROM DUAL ) b WHERE a.schoolyear ='".$params["year"]."' AND a.trimester ='".$params["trim"]."' AND a.students_no ='" .$row5['students_no']."' ORDER BY a.students_no, a.paiddate ) c";

            $query2 = $this->db->query($sql2);

            foreach ($query2->result_array() as $row1) {
                $description = $row1['description']." (".$row1['paiddate'].")";

                $ssss["" . $row5['students_no'] . ""]["" . $row1['rnum'] . ""] = $description;

            }

        }

        $this->load->helper('xml');

        $dom = xml_dom();

        $rows = xml_add_child($dom, 'rows');

        $head = xml_add_child($rows,'head',NULL,true);

        $h0 = $this->getGridHeaderAtt($head,"Student Name","gno","120","ro","center","date","true","1");

        $subarr = array(); //header

        $stdlist = array(); $i=0;

        $h0 = xml_add_child($head, 'column',"Date",false);

        xml_add_attribute($h0,'id','1');

        xml_add_attribute($h0,'width','240');

        xml_add_attribute($h0,'type','ro');

        xml_add_attribute($h0,'align','center');

        xml_add_attribute($h0,'sort','str');

        xml_add_attribute($h0,'filter','true');

        xml_add_attribute($h0,'xmlcontent','0');

        $h0 = xml_add_child($head, 'column',"",false);

        xml_add_attribute($h0,'id','2');

        xml_add_attribute($h0,'width','240');

        xml_add_attribute($h0,'type','ro');

        xml_add_attribute($h0,'align','center');

        xml_add_attribute($h0,'sort','str');

        xml_add_attribute($h0,'filter','true');

        xml_add_attribute($h0,'xmlcontent','0');

        $h0 = xml_add_child($head, 'column',"",false);

        xml_add_attribute($h0,'id','3');

        xml_add_attribute($h0,'width','240');

        xml_add_attribute($h0,'type','ro');

        xml_add_attribute($h0,'align','center');

        xml_add_attribute($h0,'sort','str');

        xml_add_attribute($h0,'filter','true');

        $h0 = xml_add_child($head, 'column',"",false);

        xml_add_attribute($h0,'id','4');

        xml_add_attribute($h0,'width','240');

        xml_add_attribute($h0,'type','ro');

        xml_add_attribute($h0,'align','center');

        xml_add_attribute($h0,'sort','str');

        xml_add_attribute($h0,'filter','true');

        $h0 = xml_add_child($head, 'column',"",false);

        xml_add_attribute($h0,'id','5');

        xml_add_attribute($h0,'width','240');

        xml_add_attribute($h0,'type','ro');

        xml_add_attribute($h0,'align','center');

        xml_add_attribute($h0,'sort','str');

        xml_add_attribute($h0,'filter','true');



        $ai0 = xml_add_child($head,'afterInit',null,false);

        $TITLE = $params["trim"]." Trimester ".$params["year"];

        $ca0 = xml_add_child($ai0,'call',null,false);

        xml_add_attribute($ca0,'command',"attachHeader");

        $pa0 = xml_add_child($ca0,'param',$TITLE.",#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan",false);

        $atta = array(); $h=1; $nothing=''; $t=0;

        foreach ($arratt as $key => $val) {

            $subarr = $arratt[$key];
            $stdlist[$i] = $key;
            $i++;
        }

        foreach ($arratt as $key => $val) {

            $subarr = $arratt[$key];
            $item = xml_add_child($rows, 'row', NULL, true);
            xml_add_attribute($item, 'id', $h);

            $c0 = xml_add_child($item, 'cell', $subarr['stdname'], true);
            for ($c = 1; $c < 6; $c++) {
                if (ISSET($ssss["".$stdlist[$t].""][$c])) {
                    $c1 = xml_add_child($item, 'cell', $ssss["".$stdlist[$t].""][$c] , true);
                } else {

                    $c1 = xml_add_child($item, 'cell', "", true);
                }
            }

            $t++;


            $h++;
        }

        return xml_print($dom, true);

    }








    function getTranscriptList($sno)

    {


        $sql = "SELECT a.no,a.students_no,a.schoolyear,a.trimester,a.level,a.session,a.att_score,a.ls_score,a.rw_score from ali_academicrecords as a inner join ali_gradingperiod as b on a.schoolyear=b.schoolyear and a.trimester=b.gradingperiod where a.students_no=" . $sno . " and b.active=0 and DATE(b.startday) < DATE(NOW()) order by a.schoolyear,a.trimester";



        $query = $this->db->query($sql);



        return $query->result_array();



    }



    function changeLevel($level)

    {



        $sql = " SELECT levelname from ali_level where levelvalue=" . $level . " ";



        $query = $this->db->query($sql);



        if ($query->num_rows() > 0) {



            $row = $query->row();





            return $row->levelname;



        }

        return '';



    }

    function getGridHeaderAtt($head,$title,$id,$width,$type,$align,$sort,$filter,$xmlcontent){

        $h0 = xml_add_child($head, 'column',$title,false);

        xml_add_attribute($h0,'id',$id);

        xml_add_attribute($h0,'width',$width);

        xml_add_attribute($h0,'type',$type);

        xml_add_attribute($h0,'align',$align);

        xml_add_attribute($h0,'sort',$sort);

        xml_add_attribute($h0,'filter',$filter);

        xml_add_attribute($h0,'xmlcontent',$xmlcontent);



        return $h0;

    }

    function getGridHeaderAttt($head,$title,$id,$width,$type,$align,$sort,$filter,$xmlcontent){

        $h0 = xml_add_child($head, 'column',$title,false);

        xml_add_attribute($h0,'id',$id);

        xml_add_attribute($h0,'width',$width);

        xml_add_attribute($h0,'type',$type);

        xml_add_attribute($h0,'align',$align);

        xml_add_attribute($h0,'sort',$sort);

        xml_add_attribute($h0,'filter',$filter);

        xml_add_attribute($h0,'xmlcontent',$xmlcontent);


        return $h0;

    }




}