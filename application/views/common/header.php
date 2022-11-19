<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="cleartype" content="on">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="">

    <!--<link rel="icon" href="../../favicon.ico">-->

    <title>Institution Name</title>


    <!--link the bootstrap css file-->
    <link rel="stylesheet" href="<?php echo base_url("assets/css/bootstrap.min.css"); ?>" />
    <link rel="stylesheet" href="<?php echo base_url("assets/css/ekko-lightbox.min.css"); ?>" />
    <link rel="stylesheet" href="<?php echo base_url("dhtmlxSuite/sources/dhtmlxForm/codebase/ext/dhtmlxform_item_btn2state.js");?>" />

    <style>
        /* XS smartphones, iPhone, portrait 480x320 phones */
        @media (min-width:320px) {
            .m1-xs {
                position: relative;
                overflow: hidden;
                width: 100%;
                height: 270px;
                margin: 0px 5px 0px 5px;
                text-align:center;
            }
            .m1-xs:before {
                content: "";
                position: absolute;
                left: 0;
                right: 0;
                z-index: -1;

                display: block;
                background-image: url(<?php echo base_url("assets/images/main31a.jpg?ss=1"); ?>);
                background-size:cover;
                width: 100%;
                height: 270px;

                filter: blur(5px);
                -webkit-filter: blur(5px);
                -moz-filter: blur(5px);
                -o-filter: blur(5px);
                -ms-filter: blur(5px);
                margin: -5px -10px -10px -10px;
            }
            .m2-xs {
                position: relative;
                overflow: hidden;
                width: 100%;
                height: 270px;
                margin: 0px 5px 0px 5px;
                text-align:center;
            }
            .m2-xs:before {
                content: "";
                position: absolute;
                left: 0;
                right: 0;
                z-index: -1;

                display: block;
                background-image: url(<?php echo base_url("assets/images/main32a.jpg?ss=1"); ?>);
                background-size:cover;
                width: 100%;
                height: 270px;

                filter: blur(5px);
                -webkit-filter: blur(5px);
                -moz-filter: blur(5px);
                -o-filter: blur(5px);
                -ms-filter: blur(5px);
                margin: -5px -10px -10px -10px;
            }
            .m3-xs {
                position: relative;
                overflow: hidden;
                width: 100%;
                height: 320px;
                margin: 0px 5px 0px 5px;
                text-align:center;
            }
            .m3-xs:before {
                content: "";
                position: absolute;
                width: 100%;
                height: 320px;
                left: 0;
                right: 0;
                z-index: -1;

                display: block;
                background-image: url(<?php echo base_url("assets/images/main24b.jpg?ss=1"); ?>);
                background-size:cover;

                filter: blur(5px);
                -webkit-filter: blur(5px);
                -moz-filter: blur(5px);
                -o-filter: blur(5px);
                -ms-filter: blur(5px);
                filter: blur(5px);
                margin: -5px -10px -10px -10px;
            }
            a { font-size: 14px; }
            p { font-size: 14px; }
            input { font-size: 14px; }
            div { font-size: 14px; }
            span { font-size: 14px; }
            h1 { font-size: 26px; }
            h2 { font-size: 24px; }
            h3 { font-size: 22px; }
            h4 { font-size: 20px; }
            h5 { font-size: 18px; }


            .title_live {
                margin-top:40px;
            }
            .title_live > p{
                color:#fff;
                font-weight:bold;
                font-size:36px;
                margin:0px 0px -10px 0px;
                text-shadow:1px 1px 0 #7a7a7a;
                display:block;
            }

            .title_learn {
                margin-top:50px;
            }
            .title_learn > p{
                color:#fff;
                font-weight:bold;
                font-size:32px;
                text-shadow:1px 1px 0 #7a7a7a;
                display:block;
            }
            .title_learn > a{
                margin-top:20px;
            }

            .title_ali {
                margin-top:0px;
            }
            .title_ali > h2{
                color:#fff;
                font-weight:bold;
                font-size:32px;
                text-shadow:1px 1px 0 #7a7a7a;
                display:block;
            }
            .title_ali > p{
                color:#fff;
                font-size:18px;
                margin-top:40px;
                text-shadow:1px 1px 0 #7a7a7a;
                display:block;
            }
            .title_ali > a{
                margin-top:5px;
            }
            .title_ali > button{
                margin-top:5px;
            }

        }
        /* portrait e-readers (Nook/Kindle), smaller tablets @ 600 or @ 640 wide. */
        @media (min-width:481px) {
            .nav-tabs .topwidth01{
                width:5%;
            }
            .nav-tabs .topwidth02{
                width:2%;
            }
        }

        /* SM portrait tablets, portrait iPad, landscape e-readers, landscape 800x480 or 854x480 phones */
        @media (min-width:641px) {
            .m1-sm {
                position: relative;
                overflow: hidden;
                width: 100%;
                height: 270px;
                margin: 0px 5px 0px 5px;
                text-align:center;
            }
            .m1-sm:before {
                content: "";
                position: absolute;
                left: 0;
                right: 0;
                z-index: -1;

                display: block;
                background-image: url(<?php echo base_url("assets/images/main31a.jpg?ss=6"); ?>);
                background-size:cover;
                width: 100%;
                height: 270px;

                filter: blur(0px);
                -webkit-filter: blur(0px);
                -moz-filter: blur(0px);
                -o-filter: blur(0px);
                -ms-filter: blur(0px);
                margin: -5px -10px -10px -10px;
            }
            .m2-sm {
                position: relative;
                overflow: hidden;
                width: 100%;
                height: 270px;
                margin: 0px 5px 0px 5px;
                text-align:center;
            }
            .m2-sm:before {
                content: "";
                position: absolute;
                left: 0;
                right: 0;
                z-index: -1;

                display: block;
                background-image: url(<?php echo base_url("assets/images/main32a.jpg?ss=8"); ?>);
                background-size:cover;
                width: 100%;
                height: 270px;

                filter: blur(0px);
                -webkit-filter: blur(0px);
                -moz-filter: blur(0px);
                -o-filter: blur(0px);
                -ms-filter: blur(0px);
                margin: -5px -10px -10px -10px;
            }
            .m3-sm {
                position: relative;
                overflow: hidden;
                width: 100%;
                height: 270px;
                margin: 0px 5px 0px 5px;
                text-align:center;
            }
            .m3-sm:before {
                content: "";
                position: absolute;
                width: 100%;
                height: 270px;
                left: 0;
                right: 0;
                z-index: -1;

                display: block;
                background-image: url(<?php echo base_url("assets/images/main24b.jpg?ss=1"); ?>);
                background-size:cover;

                filter: blur(0px);
                -webkit-filter: blur(0px);
                -moz-filter: blur(0px);
                -o-filter: blur(0px);
                -ms-filter: blur(0px);
                margin: -5px -10px -10px -10px;
            }
            a { font-size: 16px; }
            p { font-size: 16px; }
            input { font-size: 16px; }
            div { font-size: 16px; }
            span { font-size: 16px; }
            h1 { font-size: 26px; }
            h2 { font-size: 24px; }
            h3 { font-size: 22px; }
            h4 { font-size: 20px; }

            .nav-tabs .topwidth01{
                width:2%;
            }
            .nav-tabs .topwidth02{
                width:2%;
            }


        }

        /* MD tablet, landscape iPad, lo-res laptops ands desktops */
        @media (min-width:961px) {
            .m1-md {
                position: relative;
                overflow: hidden;
                width: 100%;
                height: 430px;
                margin: 0px 0px 0px 0px;
                text-align:left;
            }
            .m1-md:before {
                content: "";
                position: absolute;
                left: 0;
                right: 0;
                z-index: -1;

                display: block;
                background-image: url(<?php echo base_url("assets/images/main31a.jpg?ss=9"); ?>);
                background-size:cover;
                width: 100%;
                height: 430px;

                filter: blur(0px);
                -webkit-filter: blur(0px);
                -moz-filter: blur(0px);
                -o-filter: blur(0px);
                -ms-filter: blur(0px);
                margin: -1px -2px -2px -1px;
            }
            .m2-md {
                position: relative;
                overflow: hidden;
                width: 100%;
                height: 430px;
                text-align:center;
                margin: 0px 0px 0px 0px;
            }
            .m2-md:before {
                content: "";
                position: absolute;
                left: 0;
                right: 0;
                z-index: -1;

                display: block;
                background-image: url(<?php echo base_url("assets/images/main32a.jpg?ss=33"); ?>);
                background-size:cover;
                width: 100%;
                height: 430px;
                filter: blur(0px);
                -webkit-filter: blur(0px);
                -moz-filter: blur(0px);
                -o-filter: blur(0px);
                -ms-filter: blur(0px);
                margin: -1px -2px -2px -1px;
            }
            .m3-md {
                position: relative;
                overflow: hidden;
                width: 100%;
                height: 340px;
                margin: 0px 0px 0px 0px;
                text-align:center;
            }
            .m3-md:before {
                content: "";
                position: absolute;
                width: 100%;
                height: 340px;
                left: 0;
                right: 0;
                z-index: -1;

                display: block;
                background-image: url(<?php echo base_url("assets/images/main24a.jpg?ss=22"); ?>);
                background-size:cover;
                filter: blur(0px);
                -webkit-filter: blur(0px);
                -moz-filter: blur(0px);
                -o-filter: blur(0px);
                -ms-filter: blur(0px);
                margin: -1px -2px -2px -1px;
            }
            a { font-size: 16px; }
            p { font-size: 16px; }
            input { font-size: 16px; }
            div { font-size: 16px; }
            span { font-size: 16px; }
            h1 { font-size: 30px; }
            h2 { font-size: 28px; }
            h3 { font-size: 26px; }
            h4 { font-size: 24px; }
            h5 { font-size: 22px; }


            .nav-tabs .topwidth01{
                width:20%;
            }
            .nav-tabs .topwidth02{
                width:0%;
            }

            .title_live {
                margin-top:200px;
            }
            .title_live > p{
                color:#fff;
                font-weight:bold;
                font-size:58px;
                margin:0px 0px -20px 20px;
                text-shadow:1px 1px 0 #7a7a7a;
                display:block;
            }

            .title_learn {
                margin-top:130px;
            }
            .title_learn > p{
                color:#fff;
                font-weight:bold;
                font-size:40px;
                margin:0px 0px -20px 20px;
                text-shadow:1px 1px 0 #7a7a7a;
                display:block;
            }
            .title_learn > a{
                margin-top:60px;
            }

            .title_ali {
                margin-top:80px;
            }
            .title_ali > h2{
                color:#fff;
                font-weight:bold;
                font-size:40px;
                margin:0px 0px -20px 20px;
                text-shadow:1px 1px 0 #7a7a7a;
                display:block;
            }
            .title_ali > p{
                color:#fff;
                font-size:18px;
                margin-top:40px;
                text-shadow:1px 1px 0 #7a7a7a;
                display:block;
            }
            .title_ali > a{
                margin-top:20px;
            }
            .title_ali > button{
                margin-top:20px;
            }


            .container_custom {
                max-width:1160px;
            }
        }

        /* LG big landscape tablets, laptops, and desktops */
        @media (min-width:1025px) {
            .container_custom {
                max-width:1160px;
                width:95%;
            }
        }
        /* hi-res laptops and desktops */
        @media (min-width:1281px) {
            .container_custom {
                max-width:1160px;
                width:95%;
            }
        }


    </style>
</head>
<body>