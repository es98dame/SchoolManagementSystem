<style>


    .nav-tabs > li {
        float:none;
        display:inline-block;
        *display:inline; /* ie7 fix */
        zoom:1; /* hasLayout ie7 trigger */
        margin-left:1px;
        margin-right:1px;
    }
    /* center tabs */
    .nav-tabs {
        text-align:center;
        border:none !important;
    }

    .nav-tabs > li > a,span{
        color: #000000;
        font-weight:bold;
        font-size:13px;
    }


    .nav-tabs > li.active > a,
    .nav-tabs > li.active > a:hover,
    .nav-tabs > li.active > a:focus{
        color: #ffffff;
        background-color: #000000;
    }


    .navbar-custom > li {
        float:none;
        display:inline-block;
        *display:inline; /* ie7 fix */
        zoom:1; /* hasLayout ie7 trigger */
        margin-left:10px;
        margin-right:10px;
    }

    .navbar-custom {
        background-color: black;
        text-align:center;
        width:100%;
    }
    .navbar-custom > li > a {  color: #a9a9a9;    }
    .navbar-custom > li > a:hover,
    .navbar-custom > li > a:focus {
        background-color: #000000;
        color: #ffffff;
    }

    .navbar-custom > li.dropdown.active.open > a,
    .navbar-custom > li.dropdown.active.open > ul.dropdown-menu a:hover,
    .navbar-custom > li.dropdown.open > a,
    .navbar-custom > li.dropdown.open > ul.dropdown-menu a:hover
    {
        color: #fff;
        background-color: #000000;
        border-color: #fff;
    }

    #topmenu > li > a:hover,
    #topmenu > li > a:focus {
        background-color: #000000;
        color: #ffffff;
    }

    .topmenu-xs-bottom {
        -webkit-box-shadow: 0px 6px 11px 0px rgba(0,0,0,0.75);
        -moz-box-shadow: 0px 6px 11px 0px rgba(0,0,0,0.75);
        box-shadow: 0px 6px 11px 0px rgba(0,0,0,0.75);
    }

</style>
<!-- Fixed navbar -->
<div class="container" style="width:100%;">
    <div class="tabbable">
        <div class="row">
            <nav  role="navigation" class="navbar navbar-default visible-xs">
                <div class="navbar-header topmenu-xs-bottom">
                    <div class="col-xs-2 text-left">
                        <a href="<?php echo site_url("/"); ?>" class="navbar-brand">
                            <img alt="ALI" width="40" style="margin-top:-16px; margin-left:-26px;" src="<?php echo base_url("assets/images/logo.png"); ?>">
                        </a>
                    </div>
                    <div class="col-xs-8 text-right" style="margin-top: 5px;">
                        <span class="glyphicon glyphicon-phone"> 214-352-0582</span>
                        <span class="glyphicon glyphicon-envelope"> <a href="mailto:info@schoolname.com">Info@schoolname.com</a></span>
                    </div>
                    <div class="col-xs-2">
                        <button type="button" style="margin-right:-8px;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                </div>
                <div id="navbarCollapse" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">ABOUT <b class="caret"></b></a>
                            <ul role="menu" class="dropdown-menu">
                                <li><?=anchor('about/welcome',' Welcome to ALI', array('class'=>'glyphicon glyphicon-education') )?></li>
                                <li><?=anchor('about/overview', ' Program', array('class'=>'glyphicon glyphicon-th-list') )?></li>
                                <li><?=anchor('about/staff', ' Staff', array('class'=>'glyphicon glyphicon-user') )?></li>

                                <li class="divider"></li>
                                <li><?=anchor('about/calendar', ' Start Dates', array('class'=>'glyphicon glyphicon-calendar') )?></li>
                                <li><?=anchor('about/tuition', ' Tuition', array('class'=>'glyphicon glyphicon-piggy-bank') )?></li>
                                <li><?=anchor('about/toefl', ' TOEFL', array('class'=>'glyphicon glyphicon-text-width') )?></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">APPLY <b class="caret"></b></a>
                            <ul role="menu" class="dropdown-menu" >
                                <li><?=anchor('apply/applicationinfo', ' Application Information', array('class'=>'glyphicon glyphicon-pencil') )?></li>
                                <li><?=anchor('apply/visa', ' Visa Information', array('class'=>'glyphicon glyphicon-plane') )?></li>
                                <li><?=anchor('apply/download', ' Downloads', array('class'=>'glyphicon glyphicon-cloud-download') )?></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">STUDENT LIFE <b class="caret"></b></a>
                            <ul role="menu" class="dropdown-menu" >
                                <li><?=anchor('life/schoolactivities', ' School Activities', array('class'=>'glyphicon glyphicon-tree-conifer') )?></li>
                                <li><?=anchor('life/aboutdfw', ' Life in Dallas', array('class'=>'glyphicon glyphicon-star') )?></li>
                                <li><?=anchor('life/newsletter', ' The Eagles', array('class'=>'glyphicon glyphicon-list-alt') )?></li>
                                <li><?=anchor('life/handbooks', ' Student Handbook', array('class'=>'glyphicon glyphicon-book') )?></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="row">
                <div class="container container_custom text-center hidden-xs">
                     <a href="<?php echo site_url("/"); ?>">
                         <img alt="ALI" width="70" src="<?php echo base_url("assets/images/logo.png"); ?>">
                     </a>
                </div>
                <div class="container container_custom text-center hidden-xs">
                    <ul id="topmenu" class="nav nav-tabs">
                        <li style="float:left"><?=anchor('/', 'HOME', null )?></li>
                        <li class="topwidth01"></li>
                        <li<?php if($navi_seq=="about"){ echo " class='active'";}?>><?=anchor('#about', 'ABOUT', array('data-toggle'=>'tab') )?></li>
                        <li<?php if($navi_seq=="apply"){ echo " class='active'";}?>><?=anchor('#apply', 'APPLY', array('data-toggle'=>'tab') )?></li>
                        <li<?php if($navi_seq=="life"){ echo " class='active'";}?>><?=anchor('#life', 'STUDENT LIFE', array('data-toggle'=>'tab') )?></li>
                        <li style="float:right;margin-top:8px;"><span class="glyphicon glyphicon-phone" style="font-size:16px;">&nbsp;214-352-0582</span></li>
                        <li style="float:right;margin-top:8px;"><span class="glyphicon glyphicon-envelope">&nbsp;<a href="mailto:info@schoolname.com" style="font-weight:bold; font-size:13px; color:black;" target="_top">info@schoolname.com</a></span>&nbsp;&nbsp;</li>
                    </ul>
                </div>
                <div class="tab-content hidden-xs">
                    <div id="about" class="tab-pane fade <?php if($navi_seq=="about"){ echo "in active";}?>" >
                        <ul class="nav navbar-nav navbar-custom">
                            <li ><?=anchor('about/welcome', 'WELCOME', array('style'=>'font-size:12px;') )?></li>
                            <li><?=anchor('about/overview', 'PROGRAM', array('style'=>'font-size:12px;') )?></li>
                            <li><?=anchor('about/staff', 'STAFF', array('style'=>'font-size:12px;') )?></li>

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="font-size:12px;">INTENSIVE ENGLISH<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><?=anchor('about/calendar', 'START DATES', array('style'=>'font-size:12px;') )?></li>
                                    <li><?=anchor('about/tuition', 'TUITION', array('style'=>'font-size:12px;') )?></li>
                                    <li><?=anchor('about/toefl', 'TOEFL', array('style'=>'font-size:12px;') )?></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div id="apply" class="tab-pane fade <?php if($navi_seq=="apply"){ echo "in active";}?>">
                        <ul class="nav navbar-nav navbar-custom">
                            <li><?=anchor('apply/applicationinfo', 'APPLICATION INFORMATION', array('style'=>'font-size:12px;') )?></li>
                            <li><?=anchor('apply/visa', 'VISA INFORMATION', array('style'=>'font-size:12px;') )?></li>
                            <li><?=anchor('apply/download', 'DOWNLOADS', array('style'=>'font-size:12px;') )?></li>
                        </ul>
                    </div>
                    <div id="life" class="tab-pane fade <?php if($navi_seq=="life"){ echo "in active";}?>">
                        <ul class="nav navbar-nav navbar-custom">
                                    <li><?=anchor('life/schoolactivities', 'SCHOOL ACTIVITIES', array('style'=>'font-size:12px;') )?></li>
                                    <li><?=anchor('life/aboutdfw', 'LIFE IN DALLAS', array('style'=>'font-size:12px;') )?></li>
                                    <li><?=anchor('life/newsletter', 'THE EAGLES', array('style'=>'font-size:12px;') )?></li>
                                    <li><?=anchor('life/handbooks', 'STUDENT HANDBOOK', array('style'=>'font-size:12px;') )?></li>
                        </ul>
                    </div>
                </div>
        </div>
    </div>
</div>
