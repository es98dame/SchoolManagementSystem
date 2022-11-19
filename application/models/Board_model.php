<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Board_model extends CI_Model
{
    var $MainTB = '';
    var $CommentTB = '';

    function board_model()
    {
        parent::__construct();
    }

    function total_entry($data='')
    {
        $conditionquery="";
        if(!empty($data['sfl'])&&!empty($data['stx'])):
            $conditionquery = "where UPPER(".$data['sfl'].") like UPPER('%".$data['stx']."%')";
        endif;
        $sql = "SELECT * FROM ".$this->MainTB." ".$conditionquery;
        $query = $this->db->query($sql);
        return $query->num_rows();
    }
    function select($limit,$start,$data='')
    {
        $conditionquery="";
        if(!empty($data['sfl'])&&!empty($data['stx'])):
            $conditionquery = " AND  UPPER(AA.".$data['sfl'].") like UPPER('%".$data['stx']."%')";
        endif;
        $sql = "select  AA.board_no, AA.subject, AA.writer, CAST(AA.regdate AS DATE) AS regdate, AA.uploadfile1, AA.uploadfile2, AA.depth,(SELECT count(*) FROM ".$this->CommentTB." where board_no=AA.board_no) as commenttotal from ".$this->MainTB." as AA where AA.lang in ('".$data['lang']."') ".$conditionquery." order by AA.groupNum desc, AA.orderNo asc limit ".$start.",".$limit;
        $query = $this->db->query($sql);
        return $query->result();
    }
    function select_comment($bno)
    {
        $sql = "select comment_no,board_no,writer,passw,contents,ipAddr,regdate from ".$this->CommentTB." where board_no= ".$bno." ";
        $query = $this->db->query($sql);
        return $query->result();
    }
    function insert_comment($val){
        $insdata = array(
            'comment_no' => NULL,
            'board_no' => $val['bno'],
            'writer' => $val['wr_name'],
            'contents' => $val['wr_content'],
            'ipAddr' => $_SERVER['REMOTE_ADDR']
        );
        $this->db->set('passw', 'PASSWORD("'.$val['wr_password'].'")', FALSE);
        $this->db->set('regdate', 'now()', FALSE);
        $this->db->insert($this->CommentTB, $insdata);
    }
    function delete_comment($cno){
        $this->db->where('comment_no', $cno);
        $this->db->delete($this->CommentTB);
    }
    function check_comm_password($val)
    {
        $sql = "select passw from ".$this->CommentTB." where passw=PASSWORD('".$val['wr_password']."') and comment_no=".$val['commentno'];
        $query = $this->db->query($sql);
        return $query->num_rows();
    }
    function getMaxGroupNum(){
        $this->db->select_max('groupNum', 'maxgroupnum');
        $query = $this->db->get($this->MainTB);
        return intval($query->row()->maxgroupnum) + 1;
    }
    function update($val){
        $updatedata = array(
            'writer' => $val['wr_name'],
            'subject' => $val['wr_subject'],
            'email' => $val['wr_email'],
            'contents' => $val['wr_content']
        );
        if(!empty($val['wr_filenam1'])){
            $this->db->set('uploadfile1',"'".$val['wr_filenam1']."'", FALSE);
        }
        if(!empty($val['wr_filenam2'])){
            $this->db->set('uploadfile2',"'".$val['wr_filenam2']."'", FALSE);
        }
        $this->db->set('regdate', 'now()', FALSE);
        $this->db->where('board_no', $val['bno']);
        $this->db->update($this->MainTB, $updatedata);
    }
    function update_file($val){
        if(!empty($val['filename1'])){
            $this->db->set('uploadfile1',"NULL", FALSE);
        }
        if(!empty($val['filename2'])){
            $this->db->set('uploadfile2',"NULL", FALSE);
        }
        $this->db->set('regdate', 'now()', FALSE);
        $this->db->where('board_no', $val['bno']);
        $this->db->update($this->MainTB);
    }
    function delete($bno){
        $this->db->where('board_no', $bno);
        $this->db->delete($this->CommentTB);

        $this->db->where('board_no', $bno);
        $this->db->delete($this->MainTB);
    }
    function insert($val){
        if($val['tmode']=='INS'){
            $gropunum = $this->getMaxGroupNum();
        }else{
            $gropunum = $val['groupNum'];
        }
        $insdata = array(
            'board_no' => NULL ,
            'writer' => $val['wr_name'],
            'subject' => $val['wr_subject'],
            'email' => $val['wr_email'],
            'contents' => $val['wr_content'],
            'lang' => $val['lang'],
            'uploadfile1' => $val['wr_filenam1'],
            'uploadfile2' => $val['wr_filenam2'],
            'ipAddr' => $_SERVER['REMOTE_ADDR'],
            'groupNum' => $gropunum,
            'depth' => $val['depth'],
            'orderNo' => $val['orderNo'],
            'parent' => $val['parent']
        );
        $this->db->set('passw', 'PASSWORD("'.$val['wr_password'].'")', FALSE);
        $this->db->set('regdate', 'now()', FALSE);
        $this->db->insert($this->MainTB, $insdata);
    }
    function update_ordernum($val)
    {
        $this->db->where('groupNum', $val['groupNum']);
        $this->db->where('orderNo >=', $val['orderNo']);
        $this->db->set('orderNo','orderNo+1', FALSE);
        $this->db->update($this->MainTB);
    }
    function update_hit($bno)
    {
        $this->db->where('board_no', $bno);
        $this->db->set('hit','hit+1', FALSE);
        $this->db->update($this->MainTB);
    }
    function getFileNames($bno){
        $sql = "select uploadfile1,uploadfile2 from ".$this->MainTB." where board_no=".$bno;
        $query = $this->db->query($sql);
        return $query->row();
    }
    function read($bno)
    {
        $sql = "select board_no, subject,writer,contents,email,uploadfile1,uploadfile2,hit,regdate,passw from ".$this->MainTB."  where board_no=".$bno;
        $query = $this->db->query($sql);
        return $query->row();
    }
    function reply($bno)
    {
        $sql = "select subject, groupNum,depth,orderNo from ".$this->MainTB."  where board_no=".$bno;
        $query = $this->db->query($sql);
        return $query->row();
    }
    function check_password($val)
    {
        $sql = "select passw from ".$this->MainTB." where passw=PASSWORD('".$val['wr_password']."') and board_no=".$val['bno'];
        $query = $this->db->query($sql);
        return $query->num_rows();
    }


}
?>