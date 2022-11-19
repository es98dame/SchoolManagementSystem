<script language="JavaScript" src="/jscss/main.js" type="text/javascript"></script>
<?php
echo form_open_multipart('contents/bulletin/'.$tb_id.'/insert');
echo form_hidden('tmode', 'INS');
echo form_hidden('page', '');
echo form_hidden('MAX_FILE_SIZE', '8388608');
echo form_hidden('upload_check', 'true'); ?>
<table width="97%" align=center cellpadding=0 cellspacing=0><tr><td>

            <div style="border:1px solid #ddd; height:34px; background:url(/images/bbs/title_bg.gif) repeat-x;">
                <div style="font-weight:bold; font-size:14px; margin:7px 0 0 10px;">:: <?=lang('bulletin.write');?> ::</div>
            </div>
            <div style="height:3px; background:url(/images/bbs/title_shadow.gif) repeat-x; line-height:1px; font-size:1px;"></div>

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <colgroup width=90>
                <colgroup width=''>
                    <tr><td colspan="2" style="background:url(/images/bbs/title_bg.gif) repeat-x; height:3px;"></td></tr>
                    <tr>
                        <td class=write_head><?=lang('bulletin.writer');?></td>
                        <td><?php echo form_input(array('name'=>'wr_name','class'=>'ed','maxlength'=>'20','size'=>'15','required'=>'required'),$this->session->userdata('SEASESS_USERNAME') ); ?></td></tr>
                    <tr><td colspan=2 height=1 bgcolor=#e7e7e7></td></tr>

                    <tr>
                        <td class=write_head><?=lang('bulletin.password');?></td>
                        <td><?php echo form_password(array('name'=>'wr_password','class'=>'ed','maxlength'=>'20','size'=>'15','required'=>'required'), ''); ?></td></tr>
                    <tr><td colspan=2 height=1 bgcolor=#e7e7e7></td></tr>

                    <tr>
                        <td class=write_head><?=lang('bulletin.email');?></td>
                        <td><?php echo form_input(array('name'=>'wr_email','class'=>'ed','maxlength'=>'100','size'=>'50'), ''); ?></td></tr>
                    <tr><td colspan=2 height=1 bgcolor=#e7e7e7></td></tr>

                    <tr>
                        <td class=write_head><?=lang('bulletin.subject');?></td>
                        <td><?php echo form_input(array('name'=>'wr_subject','class'=>'ed','style'=>'width:100%;','required'=>'required'), ''); ?></td></tr>
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
                            <?php echo form_textarea(array('name'=>'wr_content','class'=>'tx','rows'=>'10','id'=>'wr_content','style'=>'width:100%;','required'=>'required'), ''); ?>
                        </td>
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
                        <td style='padding:5 0 5 0;'><table id="variableFiles" cellpadding=0 cellspacing=0></table>
                            <script language="JavaScript">
                                var flen = 0;
                                function add_file(delete_code,filnam)
                                {
                                    var upload_count = 2;
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
                                    objCell.innerHTML = " <input type='file' style='width:400px;' class='ed' name='userfile' title='It is available to upload file size less than 8,388,608 bytes'> (txt,png,jpeg,jpg,gif,bmp,zip,rar,pdf,doc,xls,ppt)";
                                    if (delete_code)
                                        objCell.innerHTML += delete_code;
                                    else
                                    {
                                        ;
                                    }

                                    flen++;
                                }

                                add_file('','');
                            </script>
                        </td>
                    </tr>
                    <tr><td colspan=2 height=1 bgcolor=#e7e7e7></td></tr>
            </table>

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="100%" align="center" valign="top" style="padding-top:30px;">
                        <?php echo form_submit('btn_submit', lang('bulletin.save')); ?>&nbsp;
                        <?php
                        if($tb_id=="gallery"){
                            echo form_button('btn_list', lang('bulletin.list'),'onClick=self.location.href="./glist"');
                        }else{
                            echo form_button('btn_list', lang('bulletin.list'),'onClick=self.location.href="./glist"');
                        }

                        ?>
                        <?php echo form_button('btn_list', lang('bulletin.goback'),'onClick=window.history.go(-1);'); ?>
                    </td>
                </tr>
            </table>

        </td></tr></table>
<?php echo form_close();?>
<div style="height: 20px;"></div>