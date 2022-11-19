<script language="JavaScript" src="/jscss/main.js" type="text/javascript"></script>

<div class="board_top">
    <div style="float:right; margin-right: 20px; ">
        <img src="/images/bbs/icon_total.gif" align="absmiddle" border='0'>
        <span style="color:#888888; font-weight:bold;">Total <?php echo $total_count;?></span>
    </div>
</div>

<table width="97%" align="center" cellpadding="0" cellspacing="0"><tr><td>

            <div class="row">
                <?php if($total_count == 0):?>
                <div class="col-sm-6 col-md-3"><?=lang('bulletin.nodata');?></div>
                <?php endif;
                $ss = $total_count - $current_page;
                foreach ($result as $row)
                {
                    ?>
                    <div class="col-sm-6 col-md-3">
                        <div class="thumbnail">
                            <a href="#" title="<?php echo $row->board_no;?>" onClick="godetail('<?php echo $row->board_no;?>');"><img src="<?php echo base_url("./userfiles/".$row->uploadfile1);?>" alt="Generic placeholder thumbnail"></a>
                        </div>
                        <div class="caption">
                            <h4><?php echo $row->subject;?></h4>
                            <p>by <?php echo $row->writer;?></p>
                            <?php if($row->commenttotal>0):?>
                                <span style="font-size: 10px; color: Silver;">(Reply<strong style="color: Red;"><?php echo $row->commenttotal;?></strong>)</span>
                            <?php endif;?>
                        </div>
                    </div>
                    <?php $ss--;
                };
                ?>
            </div>


            <div style="clear:both; margin-top:7px; height:31px;">
                <?php if( (isset($sfl) && isset($stx) ) || ($total_count == 0)): ?>
                    <div style="float:left;"><?php echo anchor('contents/bulletin/'.$tb_id.'/glist',lang('bulletin.list'));?></div>
                <?php endif;?>
                <?php if(!empty($session_userno)&&!empty($session_authno)):?>
                    <div style="float:right;"><?php echo anchor('contents/bulletin/'.$tb_id.'/write',lang('bulletin.write'));?></div>
                <?php endif;?>
            </div>

            <div class="board_page"><?php echo $links; ?></div>

            <div class="board_search" style="display:none;">
                <?php
                $attSearchForm = array('name' => 'SearchForm', 'id' => 'SearchForm');
                echo form_open('contents/bulletin/'.$tb_id.'/glist', $attSearchForm);
                echo form_hidden('current_page','');
                $options = array('subject'=>lang('bulletin.subject'),'contents'=>lang('bulletin.contents'),'writer'=>lang('bulletin.writer'));
                echo form_dropdown('sfl', $options, 'subject');
                echo form_input(array('name'=>'stx','class'=>'stx','maxlength'=>'15','required'=>'required'), $stx);
                echo form_submit('btn_submit', lang('bulletin.search'));
                echo form_close();
                ?>
            </div>

        </td></tr></table>
<?php
$attpostform = array('name' => 'postform', 'id' => 'postform');
echo form_open('contents/bulletin/'.$tb_id.'/gread', $attpostform);
echo form_hidden('bno', '');
echo form_hidden('current_page', $current_page);
echo form_close(); ?>
<div style="height: 20px;"></div>

