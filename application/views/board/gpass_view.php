<link rel="stylesheet" href="/jscss/style.css" type="text/css">

<?php
if($tmode=="MOD"):
    echo form_open('contents/bulletin/'.$tb_id.'/gedit');
elseif($tmode=="DEL"):
    echo form_open('contents/bulletin/'.$tb_id.'/delete');
elseif($tmode=="COMMENTDEL"):
    echo form_open('contents/bulletin/'.$tb_id.'/delcom');
    echo form_hidden('commentno', $commentno);
endif;

echo form_hidden('bno', $bno);
echo form_hidden('tmode', $tmode);

?>
<table width="668" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="20" height="26"></td>
        <td width="628"></td>
        <td width="20"></td>
    </tr>
    <tr>
        <td width="20" height="2"></td>
        <td width="628" bgcolor="#8F8F8F"></td>
        <td width="20"></td>
    </tr>
    <tr>
        <td width="20" height="48"></td>
        <td width="628" align="right" background="/images/bbs/secrecy_table_bg_top.gif"><img src="/images/bbs/secrecy_img.gif" width="344" height="48"></td>
        <td width="20"></td>
    </tr>
    <tr>
        <td width="20" height="223"></td>
        <td width="628" align="center" background="/images/bbs/secrecy_table_bg.gif">
            <table width="460" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="460" height="223" align="center" bgcolor="#FFFFFF">
                        <table width="350" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="30" align="center"><img src="/images/bbs/icon.gif" width="3" height="3"></td>
                                <td width="70" align="left"><b><?=lang('bulletin.password');?></b></td>
                                <td width="150"><?php echo form_password(array('name'=>'wr_password','class'=>'ed','maxlength'=>'20','size'=>'15','required'=>'required'),''); ?></td>
                                <td width="100" height="100" valign="middle"><?php echo form_submit('btn_submit', lang('bulletin.submit')); ?></td>
                            </tr>
                            <tr align="center">
                                <td height="1" colspan="4" background="/images/bbs/dot_line.gif"></td>
                            </tr>
                            <tr align="center">
                                <td height="60" colspan="4"><?php echo form_button('btn_list', lang('bulletin.goback'),'onClick=window.history.go(-1);'); ?></td>
                            </tr>
                        </table></td>
                </tr>
            </table></td>
        <td width="20"></td>
    </tr>
    <tr>
        <td width="20" height="1"></td>
        <td width="628" bgcolor="#F0F0F0"></td>
        <td width="20"></td>
    </tr>
    <tr>
        <td height="20" colspan="3"></td>
    </tr>
</table>
<?php echo form_close(); ?>
