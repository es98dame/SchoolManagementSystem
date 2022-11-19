<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Bulletin {
    public $per_page_lists = 8;
    public $per_paging_lists = 10;
    public $current_page=0;
    public $total_count =0;
    public $isGallery =0;

    public $CI=0;
    public $Papam=array();

    public function __construct($props = array())
    {
        if (count($props) > 0)
        {
            $this->initialize($props);
        }
        log_message('debug', "Bulletin Class Initialized");
    }
    public function initialize($tb_id = '',$mode='list')
    {
        $this->CI =& get_instance();
        $this->Papam['tb_id'] = $tb_id;
        foreach ( $_POST as $key => $value )
        {
            $this->Papam[$key] = $this->CI->input->post($key);
        }
        $this->Papam['session_username'] = $this->CI->session->userdata('ALISESS_USERNAME');
        $this->Papam['session_userno'] = $this->CI->session->userdata('ALISESS_USERNO');
        $this->Papam['session_authno'] = $this->CI->session->userdata('TOPLEVEL_AUTH');

        $this->Papam['lang'] = 'EN';

        $this->CI->load->model("board_model");
        $this->CI->board_model->MainTB = "ali_board_".$tb_id;
        $this->CI->board_model->CommentTB = "ali_board_".$tb_id."_comment";
        $this->isGallery=0;
        $result='';
        switch($mode):
            case 'list': $result=$this->list_func();  break;
            case 'glist': $this->isGallery=1;  $result=$this->list_func();  break;
            case 'write': $result=$this->write_func();  break;
            case 'gwrite': $this->isGallery=1; $result=$this->write_func();  break;
            case 'insert':if($tb_id=="gallery"){$this->isGallery=1;} $result=$this->insert_func();  break;
            case 'reply': $result=$this->reply_func();  break;
            case 'greply': $this->isGallery=1; $result=$this->reply_func();  break;
            case 'answer': if($tb_id=="gallery"){$this->isGallery=1;} $result=$this->answer_func();  break;
            case 'read':  $result=$this->read_func();  break;
            case 'gread': $this->isGallery=1;  $result=$this->read_func();  break;
            case 'edit': $result=$this->edit_func();  break;
            case 'gedit': $this->isGallery=1; $result=$this->edit_func();  break;
            case 'update':if($tb_id=="gallery"){$this->isGallery=1;} $result=$this->update_func();  break;
            case 'delete': if($tb_id=="gallery"){$this->isGallery=1;} $result=$this->delete_func();  break;
            case 'updatefile': if($tb_id=="gallery"){$this->isGallery=1;} $result=$this->updatefile_func();  break;
            case 'password':
                if($this->CI->session->userdata('TOPLEVEL_AUTH')==1){
                    if(ISSET($this->Papam['tmode'])):
                        if($this->Papam['tmode']=="MOD"):
                            $result=$this->edit_func();
                        elseif($this->Papam['tmode']=="DEL"):
                            $result=$this->pass_func();
                        elseif($this->Papam['tmode']=="COMMENTDEL"):
                            $result=$this->delcom_func();
                        endif;
                    endif;
                }else{
                    $result=$this->pass_func();
                }
                break;
            case 'gpassword': $this->isGallery=1;
                if($this->CI->session->userdata('TOPLEVEL_AUTH')==1){
                    if(ISSET($this->Papam['tmode'])):
                        if($this->Papam['tmode']=="MOD"):
                            $result=$this->edit_func();
                        elseif($this->Papam['tmode']=="DEL"):
                            $result=$this->pass_func();
                        elseif($this->Papam['tmode']=="COMMENTDEL"):
                            $result=$this->delcom_func();
                        endif;
                    endif;
                }else{
                    $result=$this->pass_func();
                }
                break;
            case 'downfile': $result=$this->down_func();  break;
            case 'delcom': if($tb_id=="gallery"){$this->isGallery=1;} $result=$this->delcom_func();  break;
            case 'inscom': if($tb_id=="gallery"){$this->isGallery=1;} $result=$this->inscom_func();  break;
        endswitch;
        return $result;
    }
    function inscom_func()
    {
        $this->CI->board_model->insert_comment($this->Papam);
        return $this->list_func();
    }
    function delcom_func()
    {
        if($this->CI->session->userdata('TOPLEVEL_AUTH') == 1){
            $this->CI->board_model->delete_comment($this->Papam['commentno']);
        }else{
            $res = $this->CI->board_model->check_comm_password($this->Papam);
            if($res){
                $this->CI->board_model->delete_comment($this->Papam['commentno']);
            }
        }
        return $this->list_func();
    }
    function list_func()
    {
        $this->Papam["total_count"] = $this->CI->board_model->total_entry($this->Papam);

        //pagination
        $this->CI->load->library('pagination');
        $config = array();
        if($this->isGallery==1) {
            $config["base_url"] = site_url() . '/apply/' . $this->Papam['tb_id'] . '/glist';
        }else{
            $config["base_url"] = site_url() . '/apply/' . $this->Papam['tb_id'] . '/list';
        }
        $config["total_rows"] = $this->Papam["total_count"];
        $config["per_page"] = $this->per_page_lists;
        $config["uri_segment"] = 4;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = floor($choice);

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $this->CI->pagination->initialize($config);

        $this->Papam["current_page"] = ($this->CI->uri->segment(4)) ? $this->CI->uri->segment(4) : 0;
        if(!isset($this->Papam['sfl'])):  $this->Papam['sfl']=''; endif;
        if(!isset($this->Papam['stx'])):  $this->Papam['stx']=''; endif;

        $this->Papam["result"] = $this->CI->board_model->select($config["per_page"],$this->Papam["current_page"],$this->Papam);
        $this->Papam["links"] = $this->CI->pagination->create_links();
        if($this->isGallery==1){
            return $this->CI->load->view('board/glist_view',$this->Papam,true);
        } else{
            return $this->CI->load->view('board/list_view',$this->Papam,true);
        }

    }
    function write_func()
    {
        if($this->isGallery==1) {
            return $this->CI->load->view('board/gwrite_view', $this->Papam, true);
        }else{
            return $this->CI->load->view('board/write_view', $this->Papam, true);
        }
    }
    function insert_func()
    {
        $this->Papam["wr_filenam1"] = '';
        $this->Papam["wr_filenam2"] = '';
        $this->Papam["groupNum"] = 1;
        $this->Papam["depth"] = 0;
        $this->Papam["orderNo"] = 0;
        $this->Papam["parent"] = 0;
        //upload
        $config['upload_path'] = './userfiles/download/';
        $config['allowed_types'] = 'gif|jpg|png|pdf|doc|xls|ppt|docx|xlsx|pptx';
        $this->CI->upload->initialize($config);
        if(!$this->CI->upload->do_upload('userfile1')){
            $error = array('error' => $this->CI->upload->display_errors());
        }else{
            $nnn = $this->CI->upload->data();
            $this->Papam["wr_filenam1"] = $nnn['file_name'];
        }
        if(!$this->CI->upload->do_upload('userfile2')){
            $error = array('error' => $this->CI->upload->display_errors());
        }else{
            $nnn = $this->CI->upload->data();
            $this->Papam["wr_filenam2"] = $nnn['file_name'];
        }

        if(!empty($this->Papam["orderNo"])){
            $this->CI->board_model->update_ordernum($this->Papam);
        }
        $this->CI->board_model->insert($this->Papam);

        return $this->list_func();
    }
    function reply_func()
    {
        $this->Papam["result"] = $this->CI->board_model->reply($this->Papam['bno']);
        if($this->isGallery==1) {
            return $this->CI->load->view('board/greply_view', $this->Papam, true);
        }else{
            return $this->CI->load->view('board/reply_view', $this->Papam, true);
        }
    }
    function answer_func()
    {
        $this->Papam["wr_filenam1"] = '';
        $this->Papam["wr_filenam2"] = '';

        //upload
        $config['upload_path'] = './userfiles/download/';
        $config['allowed_types'] = 'gif|jpg|png|pdf|doc|xls|ppt|docx|xlsx|pptx';
        $this->CI->upload->initialize($config);
        if(!$this->CI->upload->do_upload('userfile1')){
            $error = array('error' => $this->CI->upload->display_errors());
        }else{
            $nnn = $this->CI->upload->data();
            $this->Papam["wr_filenam1"] = $nnn['file_name'];
        }
        if(!$this->CI->upload->do_upload('userfile2')){
            $error = array('error' => $this->CI->upload->display_errors());
        }else{
            $nnn = $this->CI->upload->data();
            $this->Papam["wr_filenam2"] = $nnn['file_name'];
        }

        if(!empty($this->Papam["orderNo"])):  $this->CI->board_model->update_ordernum($this->Papam);	endif;
        $this->CI->board_model->insert($this->Papam);

        return $this->list_func();
    }
    function read_func()
    {
        $IP =  get_cookie('ALIBB'.$this->Papam["tb_id"]."_".$this->Papam["bno"]);
        $userip = $_SERVER['REMOTE_ADDR'];

        if($IP != $userip){
            $cookie = array(
                'name'   => 'ALIBB'.$this->Papam["tb_id"]."_".$this->Papam["bno"],
                'value'  => $userip,
                'expire' => time()+(24*60*60)
            );
            set_cookie($cookie);
            $this->CI->board_model->update_hit($this->Papam['bno']);
        }
        $this->Papam["result"] = $this->CI->board_model->read($this->Papam['bno']);
        $this->Papam["comres"] = $this->CI->board_model->select_comment($this->Papam['bno']);

        if($this->isGallery==1) {
            return $this->CI->load->view('board/gread_view', $this->Papam, true);
        }else{
            return $this->CI->load->view('board/read_view', $this->Papam, true);
        }
    }
    function edit_func()
    {
        if($this->CI->session->userdata('TOPLEVEL_AUTH') != 1){
            $res = $this->CI->board_model->check_password($this->Papam);
            if(!$res){
                return $this->pass_func();
            }
        }

        if (!get_cookie('GuestCookies'))
        {
            $cookie = array(
                'name'   => 'GuestCookies',
                'value'  => $this->Papam["tb_id"]."_".$this->Papam["bno"],
                'expire' => time()+(24*60*60)
            );
            set_cookie($cookie);
        }

        $this->Papam["result"] = $this->CI->board_model->read($this->Papam['bno']);

        if($this->isGallery==1) {
            return $this->CI->load->view('board/gedit_view', $this->Papam, true);
        }else{
            return $this->CI->load->view('board/edit_view', $this->Papam, true);
        }
    }
    function update_func()
    {
        $this->Papam["wr_filenam1"] = '';
        $this->Papam["wr_filenam2"] = '';

        if($this->CI->session->userdata('TOPLEVEL_AUTH') != 1){
            $res = $this->CI->board_model->check_password($this->Papam);
            if(!$res){
                $this->Papam["result"] = $this->CI->board_model->read($this->Papam['bno']);
                return $this->CI->load->view('board/edit_view',$this->Papam,true);
            }
        }

        //upload
        $config['upload_path'] = './userfiles/download/';
        $config['allowed_types'] = 'gif|jpg|png|pdf|doc|xls|ppt|docx|xlsx|pptx';
        $this->CI->upload->initialize($config);

        $row = $this->CI->board_model->getFileNames($this->Papam['bno']);
        $orgfile1 = $row->uploadfile1;
        $orgfile2 = $row->uploadfile2;

        if(!$this->CI->upload->do_upload('userfile1')){
            $error = array('error' => $this->CI->upload->display_errors());
        }else{
            if(!empty($orgfile1)):
                @chmod("./userfiles/download/".$orgfile1,0777);
                unlink("./userfiles/download/".$orgfile1);
            endif;
            $nnn = $this->CI->upload->data();
            $this->Papam["wr_filenam1"] = $nnn['file_name'];
        }
        if(!$this->CI->upload->do_upload('userfile2')){
            $error = array('error' => $this->CI->upload->display_errors());
        }else{
            if(!empty($orgfile2)):
                @chmod("./userfiles/download/".$orgfile2,0777);
                unlink("./userfiles/download/".$orgfile2);
            endif;
            $nnn = $this->CI->upload->data();
            $this->Papam["wr_filenam2"] = $nnn['file_name'];
        }


        if(!empty($this->Papam["orderNo"])):  $this->CI->board_model->update_ordernum($this->Papam);	endif;
        $this->CI->board_model->update($this->Papam);

        return $this->list_func();
    }
    function delete_func()
    {
        if($this->CI->session->userdata('TOPLEVEL_AUTH') != 1){
            $res = $this->CI->board_model->check_password($this->Papam);
            if(!$res){
                return $this->pass_func();
            }
        }

        $row = $this->CI->board_model->getFileNames($this->Papam['bno']);
        if(!empty($row->uploadfile1)):
            @chmod("./userfiles/download/".$row->uploadfile1,0777);
            unlink("./userfiles/download/".$row->uploadfile1);
        endif;

        $this->CI->board_model->delete($this->Papam['bno']);
        return $this->list_func();
    }
    function pass_func()
    {
        if($this->isGallery==1) {
            return $this->CI->load->view('board/gpass_view', $this->Papam, true);
        }else{
            return $this->CI->load->view('board/pass_view', $this->Papam, true);
        }
    }
    function updatefile_func()
    {
        if(!empty($this->Papam['filename1'])):
            @chmod("./userfiles/download/".$this->Papam['filename1'],0777);
            unlink("./userfiles/download/".$this->Papam['filename1']);
        endif;
        if(!empty($this->Papam['filename2'])):
            @chmod("./userfiles/download/".$this->Papam['filename2'],0777);
            unlink("./userfiles/download/".$this->Papam['filename2']);
        endif;
        $this->CI->board_model->update_file($this->Papam);
        ECHO "<script> alert('Deleted!');history.back();</script>";
        return $this->list_func();
    }
    function down_func()
    {
        $this->CI->load->helper("download");

        if(!empty($this->Papam['filename1'])):
            $filename1 = $this->Papam['filename1'];
            $orgin_dir="./userfiles/download/";
            $filepath = $orgin_dir.$filename1;
            $data = file_get_contents($filepath); // Read the file's contents
            force_download($filename1, $data);
            exit();
        endif;
        if(!empty($this->Papam['filename2'])):
            $filename2 = $this->Papam['filename2'];
            $orgin_dir="./userfiles/download/";
            $filepath = $orgin_dir.$filename2;
            $data = file_get_contents($filepath); // Read the file's contents
            force_download($filename2, $data);
            exit();
        endif;



/*
        $filename = $this->Papam['filename'];
        $orgin_dir="./userfiles/download/";
        $tmp_dir = "./tmpdown/";

        $filepath = $orgin_dir.$filename;

        if(!is_file($filepath)):
            die("<b> 404 File not found!</b>");
        endif;


        $tmp_filepath = $tmp_dir.$filename;

        $len = filesize($filepath);
        $extension = strtolower(substr(strrchr($filename,"."),1));
        copy($filepath, $tmp_filepath) or die("fail copy");

        switch($extension):
            //case "txt": $type="text/plain"; break;
            case "png": $type="image/png"; break;
            case "jpeg": $type="image/jpeg"; break;
            case "jpg": $type="image/jpeg"; break;
            case "gif": $type="image/gif"; break;
            //case "bmp": $type="image/bmp"; break;
            //case "zip": $type="application/zip"; break;
            //case "rar": $type="application/x-rar-compressed"; break;
            case "pdf": $type="application/pdf"; break;
            case "doc": $type="application/msword"; break;
            case "xls": $type="application/vnd.ms-excel"; break;
            case "ppt": $type="application/vnd.ms-powerpoint"; break;
            case "docx": $type="application/vnd.openxmlformats-officedocument.wordprocessingml.document"; break;
            case "xlsx": $type="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"; break;
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
*/
        //return $this->CI->load->view('board/down_view',$this->Papam,true);
    }

}

/* End of file Someclass.php */