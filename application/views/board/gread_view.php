<script>
    function gopassword(mo,cno){
        var frm = document.passform;
        frm.tmode.value = mo;
        frm.commentno.value = cno;
        frm.submit();
    }
</script>
<table width="97%" align="center" cellpadding="0" cellspacing="0"><tr><td>

            <div style="clear:both; height:30px;">
                <div style="float:left; margin-top:6px;">
                    <img src="/images/bbs/icon_date.gif" align=absmiddle border='0'>
                    <span style="color:#888888;"><?=lang('bulletin.regdate');?>: <?php echo $result->regdate;?></span>
                </div>

                <div style="float:right;">
                    <a href="#" onClick="javascript:document.listform.submit();"><?=lang('bulletin.list');?></a>&nbsp;&nbsp;
                    <?php if(!empty($session_userno)&&!empty($session_authno)):?>
                        <a href="#" onClick="gopassword('MOD',0);"><?=lang('bulletin.modify');?></a>&nbsp;&nbsp;
                        <a href="#" onClick="gopassword('DEL',0);"><?=lang('bulletin.delete');?></a>&nbsp;&nbsp;
                        <a href="#" onClick="javascript:document.replyform.submit();"><?=lang('bulletin.reply');?></a>&nbsp;&nbsp;
                        <?php echo anchor('contents/bulletin/'.$tb_id.'/gwrite',lang('bulletin.write'));?>
                    <?php endif;?>
                </div>
            </div>
            <div style="border:1px solid #ddd; clear:both; height:34px; background:url(/images/bbs/title_bg.gif) repeat-x;">
                <table border=0 cellpadding=0 cellspacing=0 width=100%>
                    <tr>
                        <td style="padding:8px 0 0 10px;">
                            <div style="color:#505050; font-size:13px; font-weight:bold; word-break:break-all;"><?php echo $result->subject;?></div>
                        </td>
                        <td align="right" style="padding:6px 6px 0 0;" width=120>
                        </td>
                    </tr>
                </table>
            </div>
            <div style="height:3px; background:url(/images/bbs/title_shadow.gif) repeat-x; line-height:1px; font-size:1px;"></div>


            <table border=0 cellpadding=0 cellspacing=0 width=97%>
                <tr>
                    <td height=30 background="/images/bbs/view_dot.gif" style="color:#888;">
                        <div style="float:left;">
                            &nbsp;<?=lang('bulletin.writer');?> :
                            <span class='guest'><?php echo $result->writer;?></span>        </div>
                        <div style="float:right;">
                            <img src="/images/bbs/icon_view.gif" border='0' align=absmiddle> <?=lang('bulletin.hit');?> : <?php echo $result->hit;?>                       &nbsp;
                        </div>
                    </td>
                </tr>

                <?php if(!empty($result->uploadfile1) || !empty($result->uploadfile2)):?>
                <tr><td height=30 background="../../skin/board/ey/img/view_dot.gif">
                        <?php if(!empty($result->uploadfile1)):?>
                            <div class="col-sm-6 col-md-3">
                            <img src="<?php echo base_url("./userfiles/".$result->uploadfile1);?>"  class="img-responsive">
                            </div>
                        <?php endif;?>
                        <?php if(!empty($result->uploadfile2)):?>
                            <div class="col-sm-6 col-md-3">
                                <img src="<?php echo base_url("./userfiles/".$result->uploadfile2);?>"  class="img-responsive">
                            </div>
                        <?php endif;?>
                    </td></tr><tr><?php endif;?>
                <tr>
                    <td height="150" valign="top" style="word-break:break-all; padding:10px;"><span id="writeContents"><?php echo nl2br($result->contents);?></span></td>
                </tr>
            </table>

            <?php
            $attributes1 = array('name' => 'replyform', 'id' => 'replyform');
            echo form_open('contents/bulletin/'.$tb_id.'/greply', $attributes1);
            echo form_hidden('bno',$bno);
            echo form_hidden('current_page', $current_page);
            echo form_hidden('tmode', 'REPLY');
            echo form_close();

            $attributes2 = array('name' => 'listform', 'id' => 'listform');
            echo form_open('contents/bulletin/'.$tb_id.'/glist', $attributes2);
            echo form_hidden('current_page', $current_page);
            echo form_close();

            $attributes3 = array('name' => 'passform', 'id' => 'passform');
            echo form_open('contents/bulletin/'.$tb_id.'/gpassword', $attributes3);
            echo form_hidden('bno',$bno);
            echo form_hidden('tmode','');
            echo form_hidden('commentno','');
            echo form_close();

            $attributes4 = array('name' => 'downform', 'id' => 'downform');
            echo form_open('contents/bulletin/'.$tb_id.'/download', $attributes4);
            echo form_hidden('filename','');
            echo form_close();
            ?>
            <br>



            <!-- 코멘트 리스트 -->
            <script>
                function comment_delete(cno)
                {
                    if (!confirm("Are you sure to delete this comment?")) return false;
                    var frm = document.passform;
                    frm.commentno.value = cno;
                    frm.submit();
                }
            </script>
            <div id="commentContents">
                <?php
                foreach ($comres as $row)
                {

                    $comment_no = $row->comment_no;
                    $writer = $row->writer;
                    $contents = $row->contents;
                    $regdate = $row->regdate;
                    $new_ipAddr = explode(".",$row->ipAddr);
                    $ipAddr = $new_ipAddr[0].".♡.".$new_ipAddr[2].".".$new_ipAddr[3];

                    ?>
                    <table width=100% cellpadding=0 cellspacing=0 border=0>
                        <tr>
                            <td></td>
                            <td width='100%'>

                                <table border=0 cellpadding=0 cellspacing=0 width=100%>
                                    <tr>
                                        <td height=1 colspan=3 bgcolor="#dddddd"><td>
                                    </tr>
                                    <tr>
                                        <td height=1 colspan=3></td>
                                    </tr>
                                    <tr>
                                        <td valign=top>
                                            <div style="height:28px; clear:both; line-height:28px;">
                                                <div style="float:left; margin:2px 0 0 2px;">
                                                    <strong><span style="color: #ADD8E6; font-size: 11px;"> Written by <?php echo $writer;?> (<?php echo $regdate;?>)</span></strong>
                                                </div>
                                                <div style="float:right; margin-top:5px;">
                                                    &nbsp;<span style="color:#B2B2B2; font-size:11px;"><?php echo $ipAddr;?></span> <?php if($row->ipAddr == $_SERVER['REMOTE_ADDR'] ):?>  <a href="#"><img src='/images/bbs/co_btn_delete.gif' border=0 onClick="gopassword('COMMENTDEL',<?php echo $comment_no;?>);" align=absmiddle alt='delete'></a> &nbsp;<?php endif;?>
                                                </div>
                                            </div>

                                            <!-- 코멘트 출력 -->
                                            <div style='line-height:20px; padding:7px; word-break:break-all; overflow:hidden; clear:both; '>
                                                <?php echo nl2br(htmlspecialchars($contents));?></div>
                                        </td>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height=5 colspan=3></td>
                                    </tr>
                                </table>

                            </td>
                        </tr>
                    </table>
                <?php }?>
            </div>
            <!-- 코멘트 리스트 -->

            <div id=comment_write style="display:block;">
                <table width=100% border=0 cellpadding=1 cellspacing=0 bgcolor="#dddddd"><tr><td>
                            <?php echo form_open('contents/bulletin/'.$tb_id.'/inscom');
                            echo form_hidden('bno', $bno);
                            echo form_hidden('tmode', 'INS');

                            ?>
                            <table width=100% cellpadding=3 height=156 cellspacing=0 bgcolor="#ffffff" style="border:1px solid #fff; background:url(/images/bbs/co_bg.gif) x-repeat;">
                                <tr>
                                    <td colspan="2" style="padding:5px 0 0 5px;">
                                        <span style="cursor: pointer;" onclick="textarea_decrease('wr_content', 8);"><img src="/images/bbs/co_btn_up.gif" border='0'></span>
                                        <span style="cursor: pointer;" onclick="textarea_original('wr_content', 8);"><img src="/images/bbs/co_btn_init.gif" border='0'></span>
                                        <span style="cursor: pointer;" onclick="textarea_increase('wr_content', 8);"><img src="/images/bbs/co_btn_down.gif" border='0'></span>
                                        <?=lang('bulletin.writer');?> <?php echo form_input(array('name'=>'wr_name','class'=>'ed','maxlength'=>'20','size'=>'10','required'=>'required'), ''); ?>
                                        <?=lang('bulletin.password');?> <?php echo form_password(array('name'=>'wr_password','class'=>'ed','maxlength'=>'20','size'=>'10','required'=>'required'), ''); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td width=95%>
                                        <?php echo form_textarea(array('name'=>'wr_content','class'=>'tx','rows'=>'8','id'=>'wr_content','style'=>'width:100%;','required'=>'required'), ''); ?>
                                    </td>
                                    <td width=85 align=center>
                                        <div><?php echo form_submit('btn_submit',lang('bulletin.save')); ?></div>
                                    </td>
                                </tr>
                            </table>
                            <?php echo form_close(); ?>
                        </td></tr></table>
            </div>


            <div style="height:1px; line-height:1px; font-size:1px; background-color:#ddd; clear:both;">&nbsp;</div>


            <div style="clear:both; height:43px;">
                <div style="float:left; margin-top:10px;">
                </div>

                <div style="float:right; margin-top:10px;">
                    <a href="#" onClick="javascript:document.listform.submit();"><?=lang('bulletin.list');?></a>&nbsp;&nbsp;
                    <?php if(!empty($session_userno)&&!empty($session_authno)):?>
                        <a href="#" onClick="gopassword('MOD',0);"><?=lang('bulletin.modify');?></a>&nbsp;&nbsp;
                        <a href="#" onClick="gopassword('DEL',0);"><?=lang('bulletin.delete');?></a>&nbsp;&nbsp;
                        <a href="#" onClick="javascript:document.replyform.submit();"><?=lang('bulletin.reply');?></a>&nbsp;&nbsp;
                        <?php echo anchor('contents/bulletin/'.$tb_id.'/write',lang('bulletin.write'));?>
                    <?php endif;?>
                </div>
            </div>

            <div style="height:2px; line-height:1px; font-size:1px; background-color:#dedede; clear:both;">&nbsp;</div>

        </td></tr></table><br>

<div style="height: 20px;"></div>

<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-43093066-3', 'auto');
    ga('send', 'pageview');

</script>