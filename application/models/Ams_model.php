<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ams_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }

    function checksession(){
        $sess_id = $this->session->userdata('SEASESS_USERNO');
        if(empty($sess_id))
        {
            echo '<script language="javascript">';
            echo 'top.location.href = "'.site_url().'/auth/logout";';
            echo '</script>';
            exit();
            return false;
        }
        return true;
    }

    function test(){
        $sql = "select name from ali_instructors WHERE instructors_no = 4";
        $query = $this->db->query($sql);
        if($query->num_rows() > 0){
            $row=$query->row();
            return $row->name;
        }
        return "error";
    }

    function getMyaccount($params)
    {
        $this->load->helper('xml');
        $dom = xml_dom();
        $data = xml_add_child($dom, 'data');
        $sql = "select name from ali_instructors where instructors_no=4";
        $query = $this->db->query($sql);
        if($query->num_rows() > 0){
            $row5=$query->row();
            $cell1 = xml_add_child($data, 'name',$row5->user_ID,true);
            $cell2 = xml_add_child($data, 'firstname',$row5->firstname,true);
            $cell3 = xml_add_child($data, 'lastname',$row5->lastname,true);
            $cell4 = xml_add_child($data, 'initial',$row5->initial,true);
            $cell5 = xml_add_child($data, 'nickname',$row5->nickname,true);
            $cell6 = xml_add_child($data, 'cellphone',$row5->cellphone,true);
            $cell7 = xml_add_child($data, 'email',$row5->email,true);
            $cell8 = xml_add_child($data, 'id',$params['session_userno'],true);
        }
        return xml_print($dom,true);
    }


    function getStaffs($params)
    {
        $this->load->helper('xml');
        $sql="";
        $columns = array("authority","user_ID","firstname","name","initial","nickname","cellphone","email","bgcolorone","status","etc","writer","regdate","passw");
        if(isset($params["orderby"])){
            if($params["direct"]=='des')
                $direct = "DESC";
            else
                $direct = "ASC";
            $sql =" Order by ".$columns[$params["orderby"]]." ".$direct;
        }else{
            $sql =" Order by status,authority";
        }
        $sql = "select instructors_no,authority,user_ID,firstname,name,initial,nickname,cellphone,email,bgcolorone,status,etc,writer,regdate,passw from ali_instructors ".$sql;
        $query = $this->db->query($sql);
        $dom = xml_dom();
        $rows = xml_add_child($dom, 'rows');
        foreach($query->result_array() as $row5)
        {
            $item = xml_add_child($rows,'row',NULL,true); xml_add_attribute($item,'id',$row5['instructors_no']);
            $c1 = xml_add_child($item, 'cell',$row5['authority'],true);
            $c3 = xml_add_child($item, 'cell',$row5['user_ID'],true);
            $c5 = xml_add_child($item, 'cell',$row5['firstname'],true);
            $c5 = xml_add_child($item, 'cell',$row5['name'],true);
            $c5 = xml_add_child($item, 'cell',$row5['initial'],true);
            $c5 = xml_add_child($item, 'cell',$row5['nickname'],true);
            $c5 = xml_add_child($item, 'cell',$row5['cellphone'],true);
            $c5 = xml_add_child($item, 'cell',$row5['email'],true);
            $c5 = xml_add_child($item, 'cell',$row5['bgcolorone'],true);
            $c5 = xml_add_child($item, 'cell',$row5['status'],true);
            $c5 = xml_add_child($item, 'cell',$row5['etc'],true);
            $c5 = xml_add_child($item, 'cell',$row5['writer'],true);
            $c5 = xml_add_child($item, 'cell',$row5['regdate'],true);
            $c5 = xml_add_child($item, 'cell',$row5['passw'],true);
        }
        return xml_print($dom,true);
    }


    function setStaffs($params)
    {
        $this->load->helper('xml');
        $dom = xml_dom();
        $data = xml_add_child($dom,'data');
        $rowId = $params["ids"];
        $this->newId = $rowId;
        $mode = $params[$rowId."_!nativeeditor_status"];
        switch($mode){
            case "inserted":
                $c = $this->getValidateUserId("teacher",$params[$rowId."_user_ID"]);
                if($c==0){
                    $action = $this->add_row_teacher($rowId,$params);
                }else{
                    $action = "invalid";
                }
                break;
            case "deleted": $action =  $this->delete_row_teacher($rowId); break;
            case "updated": $action = $this->update_row_teacher($rowId,$params); break;
            case "invalid": $action = $mode; break;
        }
        $action2 = xml_add_child($data,'action',$action,false);
        xml_add_attribute($action2,'type',$action);
        xml_add_attribute($action2,'sid',$rowId);
        xml_add_attribute($action2,'tid',$this->newId);
        return xml_print($dom,true);
    }

    function add_row_teacher($rowId,$params){
        $insdata = array(
            'user_ID' => $params[$rowId."_user_ID"],
            'firstname' => $params[$rowId."_firstname"],
            'lastname' => $params[$rowId."_lastname"],
            'nickname' => $params[$rowId."_nickname"],
            'cellphone' => $params[$rowId."_cellphone"],
            'email' => $params[$rowId."_email"],
            'status' => $params[$rowId."_status"],
            'writer' => $this->session->userdata('SEASESS_USERNAME')
        );
        $this->db->set('regdate', 'now()', FALSE);
        $salt = $this->fn_generate_salt();
        $password = $this->fn_generate_salted_password($params[$rowId."_passw"],$salt);
        $this->db->set('passw',"'".$password."'", FALSE);
        $this->db->insert('sea_teachers', $insdata);
        $this->newId = $this->db->insert_id();
        return "inserted";
    }
    function update_row_teacher($rowId,$params){
        $updatedata = array(
            'user_ID' => $params[$rowId."_user_ID"],
            'firstname' => $params[$rowId."_firstname"],
            'lastname' => $params[$rowId."_lastname"],
            'nickname' => $params[$rowId."_nickname"],
            'cellphone' => $params[$rowId."_cellphone"],
            'email' => $params[$rowId."_email"],
            'status' => $params[$rowId."_status"],
            'writer' => $this->session->userdata('SEASESS_USERNAME')
        );
        $this->db->set('regdate', 'now()', FALSE);
        $passquery ="";
        if(!empty($params[$rowId."_passw"])):
            $salt = $this->fn_generate_salt();
            $password = $this->fn_generate_salted_password($params[$rowId."_passw"],$salt);
            $this->db->set('passw',"'".$password."'", FALSE);
        endif;
        $this->db->where('no', $rowId);
        $this->db->update('sea_teachers', $updatedata);
        return "updated";
    }
    function delete_row_teacher($rowId){
        $this->db->where('teacher_no', $rowId); $this->db->delete('sea_gradereports');
        $this->db->where('teacher_no', $rowId); $this->db->delete('sea_classteachers');
        $this->db->where('no', $rowId); $this->db->delete('sea_teachers');
        return "deleted";
    }


}