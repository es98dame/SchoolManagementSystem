<div class="container">
    <div class="page-header col-xs-12 col-md-6 col-md-offset-4">
        <h1>Login</h1>
        <h4>Please fill out the following form with your login credentials:</h4>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-6 col-md-offset-4">

            <?php echo $this->session->flashdata('message');?>
            <?php echo form_open('aliweb/login');?>
            <div class="form-group">
                <label for="userid">User ID</label>
                <input type="text" class="form-control" required name="userid" id="userid" placeholder="User ID">
                <div style="color:red;"><?php echo form_error('userid'); ?></div>
            </div>
            <div class="form-group">
                <label for="userpwd">Password</label>
                <input type="password" class="form-control" required name="userpwd" id="userpwd" placeholder="Password">
                <div style="color:red;"><?php echo form_error('userpwd'); ?></div>
            </div>
            <div class="form-group">
            <button type="submit" name="submit" class="btn btn-primary" style="width:120px;">Submit</button>
            <?=anchor('/aliweb/lost?grp=9','Lost Password?',array('class'=>'form-control btn btn-default','style' => 'width:120px;color:#0080FF;'))?>
            </div>

            <?php echo form_hidden('grp', '9');?>
            <?php echo form_close(); ?>
            <?=$this->session->flashdata('successnewpw');?>
            <?=$this->session->flashdata('aerror');?>
        </div>

    </div>
</div>