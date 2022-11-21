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
            <a class="navbar-brand" href="<?php echo site_url("/aliweb/");?>" >
                <img alt="ALI" width="48px" height="48px" style="margin-top:-16px;margin-left:0px;" src="<?php echo base_url("assets/images/logo.png"); ?>">
            </a>
        </div>

        <div id="navbar" class="collapse navbar-collapse">
            <p class="navbar-text navbar-right"><?=anchor('aliweb/logout', 'Logout', null )?></p>
            <p class="navbar-text navbar-right"><?=anchor('aliweb/myaccount', 'Myaccount', null )?></p>
            <p class="navbar-text navbar-right">Signed in as <a href="#" class="navbar-link"><?php echo $this->session->userdata('ALISESS_USERNAME');?></a></p>
            <ul class="nav navbar-nav">
                <?php if($this->session->userdata('TOPLEVEL_AUTH') == 3 ){ ?>
                    <li <?php if($navi_seq=="classes"){ echo "class='active'";};?> class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Classes<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><?=anchor('aliweb/classinquiry', 'Class Inquiry', null )?></li>
                        </ul>
                    </li>
                    <?php if($this->session->userdata('ALISESS_EXREMED') > 0 ){ ?>
                        <li <?php if($navi_seq=="rclasses"){ echo "class='active'";};?> class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Remediation<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><?=anchor('aliweb/rclassinquiry', 'Remediation Class Inquiry', null )?></li>
                                <li><?=anchor('aliweb/rattsheet','Attendance Sheet', null )?></li>
                            </ul>
                        </li>
                        <!-- 20180501 -->
                        <li <?php if($navi_seq=="finance"){ echo "class='active'";};?> class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Finance<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><?=anchor('aliweb/allfinance', 'All finance', null )?></li>
                            </ul>
                        </li>
                    <?php } ?>
                    <li <?php if($navi_seq=="messages"){ echo "class='active'";};?> class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Messages<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><?=anchor('aliweb/sendmessage', 'Send Message', null )?></li>
                            <li><?=anchor('aliweb/teacheremail', 'Teacher Email', null )?></li>
                        </ul>
                    </li>
                <?php } ?>
                <?php if($this->session->userdata('TOPLEVEL_AUTH') == 1 || $this->session->userdata('TOPLEVEL_AUTH')==2 || $this->session->userdata('TOPLEVEL_AUTH')==4){ ?>

                    <li <?php if($navi_seq=="student"){ echo "class='active'";};?> class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Student <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><?=anchor('aliweb/students', 'Manage Inquiry', null )?></li>
                            <li class="dropdown-submenu">
                                <a tabindex="-1" href="#">Manage Requests</a>
                                <ul class="dropdown-submenu">
                                    <li><?=anchor('aliweb/exitsurvey', 'Exit Survey', null )?></li>
                                    <li><?=anchor('aliweb/suggestions', 'Suggestions', null )?></li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li <?php if($navi_seq=="classes"){ echo "class='active'";};?> class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Classes<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><?=anchor('aliweb/classinquiry', 'Class Inquiry', null )?></li>
                            <li><?=anchor('aliweb/attsheet','Attendance Sheet', null )?></li>
                            <li><?=anchor('aliweb/allattsheet','All Attendance', null )?></li>
                            <li><?=anchor('aliweb/roster', 'School Roster', null )?></li>
                            <li><?=anchor('aliweb/classroster', 'Class Roster', null )?></li>
                            <li><?=anchor('aliweb/warningletter', 'Warning Letter', null )?></li>
                        </ul>
                    </li>

                    <!-- 20180501 -->
                    <li <?php if($navi_seq=="finance"){ echo "class='active'";};?> class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Finance<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><?=anchor('aliweb/allfinance', 'All finance', null )?></li>
                        </ul>
                    </li>

                    <li <?php if($navi_seq=="rclasses"){ echo "class='active'";};?> class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Remediation<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><?=anchor('aliweb/rclassinquiry', 'Remediation Class Inquiry', null )?></li>
                            <li><?=anchor('aliweb/rattsheet','Attendance Sheet', null )?></li>
                        </ul>
                    </li>

                    <li <?php if($navi_seq=="messages"){ echo "class='active'";};?> class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Messages<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><?=anchor('aliweb/sendmessage', 'Send Message', null )?></li>
                            <li><?=anchor('aliweb/teacheremail', 'Teacher Email', null )?></li>
                        </ul>
                    </li>
                    <li <?php if($navi_seq=="bullentines"){ echo "class='active'";};?> class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Bulletines <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><?=anchor('aliweb/alinews', 'ALI News', null )?></li>
                            <li><?=anchor('aliweb/download', 'Download', null )?></li>
                            <li><?=anchor('aliweb/qna', 'Q&A', null )?></li>
                            <li><?=anchor('aliweb/gallery', 'Photo Gallery', null )?></li>
                        </ul>
                    </li>

                    <li  <?php if($navi_seq=="setting"){ echo "class='active'";};?> class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Setting<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><?=anchor('aliweb/roles', 'Roles', null )?></li>
                            <li class="dropdown-submenu">
                                <a tabindex="-1" href="#">User</a>
                                <ul class="dropdown-submenu">
                                    <li><?=anchor('aliweb/instructors', 'Instructors', null )?></li>
                                    <li><?=anchor('aliweb/staffs', 'Staffs', null )?></li>
                                    <li><?=anchor('aliweb/administrators', 'Administrators', null )?></li>
                                </ul>
                            </li>
                            <li class="dropdown-submenu">
                                <a tabindex="-1" href="#">Classes</a>
                                <ul class="dropdown-submenu">
                                    <li><?=anchor('aliweb/trimester', 'Trimester', null )?></li>
                                    <li><?=anchor('aliweb/level', 'Level', null )?></li>
                                    <li class="disabled"><?=anchor('aliweb/holidays', 'Holidays', null )?></li>
                                    <li class="disabled"><?=anchor('aliweb/rooms', 'Rooms', null )?></li>
                                    <li class="disabled"><?=anchor('aliweb/assignmentdefaultcategory', 'Assignment Categoris', null )?></li>
                                </ul>
                            </li>
                            <li class="dropdown-submenu">
                                <a tabindex="-1" href="#">Engrade</a>
                                <ul class="dropdown-submenu">
                                    <li><?=anchor('aliweb/uploadgrades', 'Import Engrade Grades', null )?></li>
                                </ul>
                                <ul class="dropdown-submenu">
                                    <li><?=anchor('aliweb/uploadattendance', 'Import Engrade Attendance', null )?></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                <?php } ?>
            </ul>

        </div>
    </div>

</nav>