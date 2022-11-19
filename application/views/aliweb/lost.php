<div class="container">
    <div class="page-header col-xs-12 col-md-6 col-md-offset-4">
        <h1>Lost Password</h1>
        <h4>Please fill out the following form with your login credentials:</h4>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-6 col-md-offset-4">
            <?php echo form_open('aliweb/lost'); ?>
            <div class="form-group">
                <label for="userid">User ID or Email :</label>
                <input type="text" class="form-control" required name="usr" id="usr" placeholder="<?php echo $session_txt;?>">
            </div>
            <div class="form-group">
                <img src="<?php echo $_SERVER["PHP_SELF"];?>/captcha"/>
                <input type="text" class="form-control" required name="captcha" id="captcha" placeholder="<?php echo $session_cap;?>">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary" style="width:120px;">Submit</button>
                <?=anchor('/aliweb/login','Cancel',array('class'=>'form-control btn btn-default','style' => 'width:120px;color:#0080FF;'))?>
            </div>

            <input type="hidden" name="grp" value="<?php echo $grp;?>"/>
            <?php echo form_close(); ?>
            <?=$this->session->flashdata('session_top');?>

        </div>

    </div>
</div>

