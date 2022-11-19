<div class="container">
    <div class="page-header">
        <h1>DOWNLOAD</h1>
    </div>


    <div class="row">
        <div class="col-md-7">
<?php 
echo form_open_multipart('apply/'.$tb_id.'/answer');
echo form_hidden('tmode', 'REPLY'); 
echo form_hidden('page', ''); 
echo form_hidden('MAX_FILE_SIZE', '8388608'); 
echo form_hidden('upload_check', 'true'); 
echo form_hidden('bno', $bno); 
echo form_hidden('groupNum', $result->groupNum); 
echo form_hidden('depth', intval($result->depth+1)); 
echo form_hidden('orderNo', intval($result->orderNo+1)); 
echo form_hidden('parent', $bno); 
?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">:: Reply ::</h3>
                </div>
                <div class="panel-body">

                    <div class="form-group">
                        <label for="wr_name">Writer</label>
                        <?php echo form_input(array('name'=>'wr_name','class'=>'form-control','maxlength'=>'20','size'=>'15','required'=>'required'), $this->session->userdata('SEASESS_USERNAME')); ?>
                    </div>

                    <?php
                    if($this->session->userdata('ALISESS_AUTHNO')!=1){ ?>
                        <div class="form-group">
                            <label for="wr_password">Password</label>
                            <?php echo form_password(array('name'=>'wr_password','class'=>'form-control','maxlength'=>'20','size'=>'15','required'=>'required'), ''); ?>
                        </div>
                    <?php }?>

                    <div class="form-group">
                        <label for="wr_email">Email</label>
                        <?php echo form_input(array('name'=>'wr_email','class'=>'form-control','maxlength'=>'100','size'=>'50'), ''); ?>
                    </div>

                    <div class="form-group">
                        <label for="wr_subject">Subject</label>
                        <?php echo form_input(array('name'=>'wr_subject','class'=>'form-control','style'=>'width:100%;','required'=>'required'), ''); ?>
                    </div>

                    <div class="form-group">
                        <label for="wr_content">Contents</label>
                        <?php echo form_textarea(array('name'=>'wr_content','class'=>'form-control','rows'=>'10','id'=>'wr_content','style'=>'width:100%;','required'=>'required'), ''); ?>
                    </div>

                    <div class="form-group">
                        <label for="wr_name">Attachedfile</label>
                        <table id="variableFiles" cellpadding=0 cellspacing=0></table>
                    </div>

                </div>
            </div>


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
            objCell.innerHTML = " <input type='file' style='width:400px;' class='form-control' name='"+filnam+"' title='It is available to upload file size less than 8,388,608 bytes'> (png,jpeg,jpg,gif,pdf,doc,xls,ppt,docx,xlsx,pptx)";
            if (delete_code)
                objCell.innerHTML += delete_code;
            else
            {
                                ;
            }

            flen++;
        }

        add_file('','userfile1');
        add_file('','userfile2');
        </script>


            <div class="btn-group" role="group" aria-label="Basic example" style="float:right;">
                <?php echo form_submit('btn_submit', 'Save',array('class'=>'btn btn-primary')); ?>&nbsp;
                <button type="button" class="btn btn-primary" onclick="self.location.href='./';">List</button>
            </div>


<?php echo form_close(); ?>


        </div>
    </div>
</div>