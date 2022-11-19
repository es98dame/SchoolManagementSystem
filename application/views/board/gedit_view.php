<script language="JavaScript" src="/jscss/main.js" type="text/javascript"></script>
<?php
echo form_open_multipart('contents/bulletin/'.$tb_id.'/update');
echo form_hidden('page', '');
echo form_hidden('tmode', $tmode);
echo form_hidden('MAX_FILE_SIZE', '8388608');
echo form_hidden('upload_check', 'true'); ?>
<table width="97%" align=center cellpadding=0 cellspacing=0><tr><td>

            <div style="border:1px solid #ddd; height:34px;">
                <div style="font-weight:bold; font-size:14px; margin:7px 0 0 10px;">:: <?=lang('bulletin.modify');?> ::</div>
            </div>
            <div style="height:3px; line-height:1px; font-size:1px;"></div>


            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <colgroup width=90>
                <colgroup width=''>
                    <tr><td colspan="2" style="height:3px;"></td></tr>
                    <tr>
                        <td class=write_head><?=lang('bulletin.writer');?></td>
                        <td><?php echo form_input(array('name'=>'wr_name','class'=>'ed','maxlength'=>'20','size'=>'15','required'=>'required'), $result->writer); ?></td></tr>
                    <tr><td colspan=2 height=1 bgcolor=#e7e7e7></td></tr>
                    <?php
                    if($this->session->userdata('SEASESS_AUTHNO')!=1){ ?>
                        <tr>
                            <td class=write_head><?=lang('bulletin.password');?></td>
                            <td><?php echo form_password(array('name'=>'wr_password','class'=>'ed','maxlength'=>'20','size'=>'15','required'=>'required'), ''); ?></td></tr>
                        <tr><td colspan=2 height=1 bgcolor=#e7e7e7></td></tr>
                    <?php }?>
                    <tr>
                        <td class=write_head><?=lang('bulletin.email');?></td>
                        <td><?php echo form_input(array('name'=>'wr_email','class'=>'ed','maxlength'=>'100','size'=>'50'), $result->email); ?></td></tr>
                    <tr><td colspan=2 height=1 bgcolor=#e7e7e7></td></tr>

                    <tr>
                        <td class=write_head><?=lang('bulletin.subject');?></td>
                        <td><?php echo form_input(array('name'=>'wr_subject','class'=>'ed','style'=>'width:100%;','required'=>'required'), $result->subject); ?></td></tr>
                    <tr><td colspan=2 height=1 bgcolor=#e7e7e7></td></tr>
                    <tr>
                        <td class=write_head style='padding-left:20px;'><?=lang('bulletin.contents');?></td>
                        <td style='padding:5 0 5 0;'>
                            <table width=100% cellpadding=0 cellspacing=0>
                                <tr>
                                    <td width=50% align=left valign=bottom>
                                        <span style="cursor: pointer;" onclick="textarea_decrease('wr_content', 10);"><img src="/images/bbs/up.gif"></span>
                                        <span style="cursor: pointer;" onclick="textarea_original('wr_content', 10);"><img src="/images/bbs/start.gif"></span>
                                        <span style="cursor: pointer;" onclick="textarea_increase('wr_content', 10);"><img src="/images/bbs/down.gif"></span></td>
                                    <td width=50% align=right></td>
                                </tr>
                            </table>
                            <?php echo form_textarea(array('name'=>'wr_content','class'=>'tx','rows'=>'10','id'=>'wr_content','style'=>'width:100%;','required'=>'required'), $result->contents); ?></td>
                    </tr>
                    <tr><td colspan=2 height=1 bgcolor=#e7e7e7></td></tr>

                    <tr>
                        <td align="center" class="write_head">
                            <table cellpadding=0 cellspacing=0>
                                <tr>
                                    <td class=write_head style="padding-top:10px; line-height:20px;"><?=lang('bulletin.attachedfile');?></td>
                                </tr>
                            </table>
                        </td>
                        <td style='padding:5 0 5 0;'><table id="variableFiles" cellpadding=0 cellspacing=0></table> <script language="JavaScript">
                                var flen = 0;
                                function add_file(delete_code,filnam)
                                {
                                    var upload_count = 1;
                                    if (upload_count && flen >= upload_count)
                                    {
                                        alert("It is available to attach less then "+upload_count+" files.");
                                        return;
                                    }

                                    var objTbl;
                                    var objRow;
                                    var objCell;
                                    if (document.getElementById)
                                        objTbl = document.getElementById("variableFiles");
                                    else
                                        objTbl = document.all["variableFiles"];

                                    objRow = objTbl.insertRow(objTbl.rows.length);
                                    objCell = objRow.insertCell(0);
                                    var subfil="";
                                    if(filnam){
                                        subfil="&nbsp;&nbsp;<img src=\"/images/bbs/icon_file.gif\" align=\"absmiddle\"><a href=\"#\" onclick=\"javascript:document.downform.filename.value='"+filnam+"';document.downform.submit();\">"+filnam+"</a> <span style=\"cursor:pointer;\" onclick=\"javascript:if(!confirm('Do you want to delete this file?')){ return false; } document.deleteform.filename.value='"+filnam+"'; document.deleteform.submit();\">[del]</span>";
                                    }
                                    objCell.innerHTML = subfil+" <input type='file' style='width:400px;' class='ed' name='userfile' title='It is available to upload file size less than 8,388,608 bytes'> (txt,png,jpeg,jpg,gif,bmp,zip,rar,pdf,doc,xls,ppt)";
                                    if (delete_code)
                                        objCell.innerHTML += delete_code;
                                    else
                                    {
                                        ;
                                    }

                                    flen++;
                                }

                                add_file('','<?php echo $result->uploadfile1;?>');

                            </script>
                        </td>
                    </tr>
                    <tr><td colspan=2 height=1 bgcolor=#e7e7e7></td></tr>
            </table>

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" align="center" valign="top" style="padding-top:30px;">
                        <?php echo form_submit('btn_submit', lang('bulletin.save')); ?>&nbsp;
                        <?php echo form_button('btn_list', lang('bulletin.list'),'onClick=self.location.href="./glist"'); ?>
                        <?php echo form_button('btn_list', lang('bulletin.goback'),'onClick=window.history.go(-1);'); ?>
                    </td>
                </tr>
            </table>

        </td></tr></table>

<?php
echo form_hidden('bno', $bno);
echo form_close();

$attributes1 = array('name' => 'downform', 'id' => 'downform');
echo form_open('contents/bulletin/'.$tb_id.'/download', $attributes1);
echo form_hidden('filename', '');
echo form_close();

if(!empty($wr_password)&&!empty($bno)):
    $attributes2 = array('name' => 'deleteform', 'id' => 'deleteform');
    echo form_open('contents/bulletin/'.$tb_id.'/updatefile',$attributes2);
    echo form_hidden('bno', $bno);
    echo form_hidden('filename', '');
    echo form_close();
endif;
?>

<div style="height: 20px;"></div>