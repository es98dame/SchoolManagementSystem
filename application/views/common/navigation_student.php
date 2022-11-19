<!-- Fixed navbar -->
<nav class="navbar navbar-default">

    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo site_url("/student/");?>" style="margin-top:-20px;margin-left:0px;">
                <img alt="ALI" width="45" src="<?php echo base_url("assets/images/logo.png"); ?>">
            </a>
        </div>

        <div id="navbar" class="collapse navbar-collapse">
            <p class="navbar-text navbar-right"><?=anchor('student/logout', 'Logout', null )?></p>
            <p class="navbar-text navbar-right"><?=anchor('student/myaccount', 'Myaccount', null )?></p>
            <p class="navbar-text navbar-right">Signed in as <a href="#" class="navbar-link"><?php echo $this->session->userdata('STDSESS_USERNAME');?></a></p>
            <ul class="nav navbar-nav">
                    <li <?php if($navi_seq=="classes"){ echo "class='active'";};?>><?=anchor('student/classinquiry', 'Classes', null )?></li>
                    <li <?php if($navi_seq=="academicrecords"){ echo "class='active'";};?>><?=anchor('student/academicrecords', 'Academic Records', null )?></li>
                <?php if($this->session->userdata('STDSESS_EXREMED') > 0 ){ ?>
                    <li <?php if($navi_seq=="rclasses"){ echo "class='active'";};?> class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Remediation<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><?=anchor('student/rclassinquiry', 'Remediation Class Inquiry', null )?></li>
                        </ul>
                    </li>
                <?php } ?>
                    <li <?php if($navi_seq=="messages"){ echo "class='active'";};?>><?=anchor('student/sendmessage', 'Messages', null )?></li>
            </ul>

        </div>
    </div>

</nav>