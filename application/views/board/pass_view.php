
<div class="container">
    <div class="page-header">
        <h1>DOWNLOAD</h1>
    </div>
    <div class="row">
        <div class="col-md-7">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">:: Verify password ::</h3>
                </div>
                <div class="panel-body">


<?php
if($tmode=="MOD"):
echo form_open('apply/'.$tb_id.'/edit',array('class'=>'form-inline'));
elseif($tmode=="DEL"):
echo form_open('apply/'.$tb_id.'/delete',array('class'=>'form-inline'));
elseif($tmode=="COMMENTDEL"):
echo form_open('apply/'.$tb_id.'/delcom',array('class'=>'form-inline'));
echo form_hidden('commentno', $commentno); 
endif;

echo form_hidden('bno', $bno); 
echo form_hidden('tmode', $tmode); 

?>
            <br/>
            <br/>
            <br/>
            <br/>

            <div class="form-group">
                <label for="wr_name">Password</label>
                <?php echo form_password(array('name'=>'wr_password','class'=>'form-control','maxlength'=>'20','size'=>'15','required'=>'required'),''); ?>
                <?php echo form_submit('btn_submit', 'Submit',array('class'=>'btn btn-primary')); ?>
            </div>
            <div class="form-group">
                <?php echo anchor('apply/'.$tb_id.'/list/'.$current_page,'List',array('class'=>'btn btn-primary'));?>
            </div>

            <br/>
            <br/>
            <br/>
            <br/>

<?php echo form_close(); ?>

                </div>
            </div>


        </div>
    </div>
</div>
