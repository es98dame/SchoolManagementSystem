<script>
function gopassword(mo,cno){
var frm = document.passform;
 frm.tmode.value = mo;
 frm.commentno.value = cno;
 frm.submit();
}
function golist(cno){
    var frm = document.listform;
    frm.current_page.value = cno;
    frm.submit();
}
function goreply(cno,bno){
    var frm = document.replyform;
    frm.current_page.value = cno;
    frm.bno.value = bno;
    frm.submit();
}
</script>




<div class="container">
    <div class="page-header">
        <h1>DOWNLOAD</h1>
    </div>


    <div class="row">
        <div class="col-md-12">


        <div style="clear:both; height:30px;">
            <div style="float:left; margin-top:6px;">
            <img src="<?php echo base_url("assets/images/bbs/icon_date.gif");?>" align=absmiddle border='0'>
            <span style="color:#888888;">Date: <?php echo $result->regdate;?></span>
            </div>
        </div>


            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo $result->subject;?></h3>
                </div>
                <div class="panel-body">
                    <div style="float:left;">&nbsp;Writer :
                        <span class='guest'><?php echo $result->writer;?></span>
                    </div>
                    <div style="float:right;">
                        <img src="<?php echo base_url("assets/images/bbs/icon_view.gif");?>" border='0' align=absmiddle> Hit : <?php echo $result->hit;?>                       &nbsp;
                    </div>

                    <?php if(!empty($result->uploadfile1) || !empty($result->uploadfile2)):?>
                        <div>
                            <?php if(!empty($result->uploadfile1)):?>&nbsp;&nbsp;<img src='<?php echo base_url("assets/images/bbs/icon_file.gif");?>' align='absmiddle'>
                                <a href="javascript:document.downform.filename1.value='<?php echo $result->uploadfile1;?>';document.downform.submit();" title=''>&nbsp;
                                    <span style="color:#888;"><?php echo $result->uploadfile1;?> (<?php echo floor(@filesize("./userfiles/".$result->uploadfile1)/1024);?>K)</span>&nbsp;</a>
                            <?php endif;?>

                            <?php if(!empty($result->uploadfile2)):?>&nbsp;&nbsp;<img src='<?php echo base_url("assets/images/bbs/icon_file.gif");?>' align='absmiddle'>
                                <a href="javascript:document.downform.filename2.value='<?php echo $result->uploadfile2;?>';document.downform.submit();" title=''>&nbsp;
                                    <span style="color:#888;"><?php echo $result->uploadfile2;?> (<?php echo floor(@filesize("./userfiles/".$result->uploadfile2)/1024);?>K)</span>&nbsp;</a>
                            <?php endif;?>
                        </div>
                    <?php endif;?>

                    <div style="width:100%;margin-bottom: 20px; margin-top:40px; text-align:left;">
                        <?php echo nl2br($result->contents);?>
                    </div>

                </div>
            </div>


<?php 
$attributes1 = array('name' => 'replyform', 'id' => 'replyform');
echo form_open('apply/'.$tb_id.'/reply', $attributes1);
echo form_hidden('current_page', $current_page);
echo form_hidden('bno',$bno);
echo form_hidden('tmode', 'REPLY');
echo form_close(); 

$attributes2 = array('name' => 'listform', 'id' => 'listform');
echo form_open('apply/'.$tb_id.'/list', $attributes2);
echo form_hidden('current_page', $current_page);
echo form_close(); 

$attributes3 = array('name' => 'passform', 'id' => 'passform');
echo form_open('apply/'.$tb_id.'/password', $attributes3);
echo form_hidden('bno',$bno);
echo form_hidden('tmode','');
echo form_hidden('current_page', $current_page);
echo form_hidden('commentno','');
echo form_close(); 

$attributes4 = array('name' => 'downform', 'id' => 'downform');
echo form_open('apply/'.$tb_id.'/downfile', $attributes4);
echo form_hidden('filename1','');
echo form_hidden('filename2','');
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




    <div class="panel panel-default">
        <div class="panel-heading">Reply</div>
        <ul class="list-group">
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
                <li class="list-group-item">

                    <div style="float:left; margin:2px 0 0 2px;">
                        <strong><span style="color: #ADD8E6; font-size: 11px;"> Written by <?php echo $writer;?> (<?php echo $regdate;?>)</span></strong>
                    </div>
                    <div style="float:right; margin-top:5px;">
                        &nbsp;<span style="color:#B2B2B2; font-size:11px;"><?php echo $ipAddr;?></span>
                        <?php if($row->ipAddr == $_SERVER['REMOTE_ADDR'] ):?>
                            <a href="#"><img src='<?php echo base_url("assets/images/bbs/co_btn_delete.gif");?>' border=0 onClick="gopassword('COMMENTDEL',<?php echo $comment_no;?>);" align=absmiddle alt='delete'></a> &nbsp;
                        <?php endif;?>
                    </div>

                    <!-- 코멘트 출력 -->
                    <div style='line-height:20px; padding:7px; word-break:break-all; overflow:hidden; clear:both; '>
                    <?php echo nl2br(htmlspecialchars($contents));?>
                    </div>

                </li>
    <?php }?>
    </ul>
</div>

            <!-- 코멘트 리스트 -->


<?php echo form_open('apply/'.$tb_id.'/inscom',array('class'=>'form-inline'));
echo form_hidden('bno', $bno); 
echo form_hidden('tmode', 'INS'); 
?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="form-group">
                        <label for="wr_name">Writer</label>
                        <?php echo form_input(array('name'=>'wr_name','id'=>'wr_name','class'=>'form-control','maxlength'=>'20','size'=>'10','required'=>'required'), ''); ?>
                    </div>
                    <div class="form-group">
                        <label for="wr_password">Password</label>
                        <?php echo form_password(array('name'=>'wr_password','id'=>'wr_password','class'=>'form-control','maxlength'=>'20','size'=>'10','required'=>'required'), ''); ?>
                    </div>
                </div>
                <div class="panel-body">
                    <div><?php echo form_textarea(array('name'=>'wr_content','class'=>'form-control','rows'=>'8','id'=>'wr_content','style'=>'width:100%;','required'=>'required'),''); ?></div>
                    <div style="width:100%;float:right;"></div><?php echo form_submit('btn_submit','Save',array('class'=>'btn')); ?>
                </div>
            </div>
<?php echo form_close(); ?>


    <div class="btn-group" role="group" aria-label="Basic example" style="float:right;">
                <?php echo anchor('apply/'.$tb_id.'/list/'.$current_page,'List',array('class'=>'btn btn-primary'));?>
        <?php if(!empty($session_userno)&&!empty($session_authno)):?>
                <button type="button" class="btn btn-primary" onclick="gopassword('MOD',0);">Modify</button>
                <button type="button" class="btn btn-primary" onclick="goreply(<?php echo $current_page;?>,<?php echo $bno;?>);">Reply</button>
                <button type="button" class="btn btn-primary" onclick="gopassword('DEL',0);">Delete</button>
                <?php echo anchor('apply/'.$tb_id.'/write','Write',array('class'=>'btn btn-primary'));?>
        <?php endif;?>
	</div>



        </div>
    </div>
</div>
