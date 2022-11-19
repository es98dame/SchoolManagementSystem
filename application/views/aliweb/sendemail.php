<!DOCTYPE html>
<html>
<head>
    <title>Send E-mail</title>

    <script src="<?php echo base_url("ckeditor/ckeditor.js"); ?>"></script>
    <script language="JavaScript" type="text/javascript">
        function gosubmit(){
            var frm= document.getElementById("form_RankMail");

            if(frm.subject.value==''){
                alert('input subject');
                frm.subject.focus();
                return false;
            }
            frm.submit();
        }
    </script>
    <style type="text/css">
        div.title_sendemail{ font: bold 16px Lucida Sans; color: #D3D3D3; margin-left: 20px;}
        div.input_sendemail{
            font: bold 12px Lucida Sans;
            color: Black;
            line-height: 30px;
        }
        span.subjectorg{ margin-left: 20px; text-align: right; width: 110px; }
        span.subjectorg2{ margin-left: 20px; text-align: right; height: 18px; width: 110px; font-size: 10px;}
        span.submessage{ font: bold 10px Lucida Sans; color: Gray; text-align: right;}
        input.inputtext{ width: 350px; }
        input.inputbutton{ width: 50px; }
    </style>
</head>
<body bgcolor="#f5f5f5" >
<div class="title_sendemail">Sending E-mail</div>
<div class="input_sendemail">
    <form action="sendemail/setSend" method="post" enctype="multipart/form-data" name="form_RankMail" id="form_RankMail">
        <table width="95%" border="0" cellspacing="0" cellpadding="0">
            <tr><td><span class="subjectorg">From :</span></td><td><input type="text" name="from" id="from" value="<?=$useremail;?>" style="width: 300px;"></td></tr>
            <tr><td><span class="subjectorg">To :</span></td><td><textarea cols="50" rows="2" id="receiveremail" name="receiveremail" class="inputtext"></textarea></td></tr>
            <tr><td><span class="subjectorg">Subject :</span></td><td><input type="text" id="subject" name="subject" value="" class="inputtext" tabindex="1"></td></tr>
            <tr><td colspan="2" align="center"><textarea cols="20" rows="6" name="FCKeditor1" id="FCKeditor1" class="ckeditor" style="height:100px; width:200px;line-height: 5px;"></textarea></td></tr>
            <tr><td><span class="subjectorg2">Attach Files :</span></td><td><input type="file" name="uploadfile"  class="inputtext"> <span class="submessage">max size( 8M )</span></td></tr>
            <tr><td colspan="2" align="center"><input type="button" name="Send" value="Send" class="inputbutton" onclick="gosubmit();" > <input type="button" name="Close" value="Close" class="inputbutton"  onclick="self.close();"></td></tr>
        </table>
    </form>
</div>
</body>
</html>