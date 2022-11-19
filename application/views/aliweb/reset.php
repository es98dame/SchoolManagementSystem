<div style="color:red;"><?php echo $session_top;?></div>
<?php if(!empty($gpart)&&!empty($ema)){?>
    <script>
        function chkpw(){
            var f = document.inform;
            if(f.pw.value!=f.pw2.value){
                alert("Please make sure your passwords match");
                return false;
            }
            return true;
        }
    </script>

    <div class="container">
        <div class="page-header col-xs-12 col-md-6 col-md-offset-4">
            <h1>Reset Password</h1>
            <h4>Please fill out the following form with your login credentials:</h4>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-6 col-md-offset-4">
                <form action="<?php echo $_SERVER["PHP_SELF"];?>\setNewPassw" onsubmit="chkpw()" method="post" name="inform" id="inform">
                <div class="form-group">
                    <label for="userid">New Password :</label>
                    <input type="password" class="form-control" required name="pw" id="pw" maxlength="10" placeholder="Enter a new password (max 7 digits)">
                </div>
                <div class="form-group">
                    <label for="userid">Confirm Password :</label>
                    <input type="password" class="form-control" required name="pw2" id="pw2" maxlength="10" placeholder="Re-enter the password above">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" id="formsubmit" style="width:120px;">Submit</button>
                </div>

                    <input type="hidden" name="grp" value="<?php echo $gpart;?>"/>
                    <input type="hidden" name="ema" value="<?php echo $ema;?>"/>
                    <input type="hidden" name="uid" value="<?php echo $uid;?>"/>
                <?php echo form_close(); ?>
            </div>

        </div>
    </div>

<?php } ?>