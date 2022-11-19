
<script language="JavaScript" type="text/javascript">
    <?=$messag;?>
    function switchdisplay(aa){
        var SelList = document.getElementById(aa);
        if(SelList.style.display=='none'){SelList.style.display='block';}
        if(SelList.style.display=='block'){SelList.style.display='none';}
    }
    function modifydata(no,mode,rno){
        var frm = document.forms["updateform"+no];
        frm.pmode.value = mode;
        frm.replyno.value = rno;
        if(mode=='DEL'){
            if( !confirm('Are you sure you want to delete?') ){
                return false;
            }
        }
        if(mode=='REPLYDEL'){
            if( !confirm('Are you sure you want to delete?') ){
                return false;
            }
        }
        frm.submit();
    }
    function adddata(){
        var frm  = document.newform;
        if(frm.newsubject.value == ''){
            alert('input subject');
            frm.newsubject.focus();
            return false;
        }
        if(frm.newcontents.value == ''){
            alert('input contents');
            frm.newcontents.focus();
            return false;
        }
        frm.submit();
    }
    function sendmail_popupwin(){
        cw=600;
        ch=450;

        sw=screen.availWidth;
        sh=screen.availHeight;

        px=(sw-cw)/2;
        py=(sh-ch)/2;
        window.open('', 'mailWin', 'resizable=no,menubar=no,toolbar=no,location=no,status=no,scrollbars=no,left='+px+',top='+py+',width='+cw+',height='+ch+'');
        document.checkform.target='mailWin';
        document.checkform.action='sendemail';
        document.checkform.submit();
    }
</script>
<table width="100%" border="0" cellspacing="1" cellpadding="0" style="font-size: 11px; color: Black; font-family: 'Lucida Sans';">
    <tr><td width="120" align="center" valign="top">
            <!-My calendar widget - HTML code - mycalendar.org --><div align="center" style="margin:15px 0px 0px 0px"><noscript><div align="center" style="width:140px;border:1px solid #ccc;background:#fff ;color: #fff ;font-weight:bold;"><a style="font-size:12px;text-decoration:none;color:#000 ;" href="http://mycalendar.org/"> Calendar</a></div></noscript><script type="text/javascript" src="http://mycalendar.org/calendar.php?group=&widget_number=1&cp3_Hex=FFB200&cp2_Hex=040244&cp1_Hex=F9F9FF&fwdt=100&lab=1"></script></div><!-end of code-->
            <!-Local Time Clock widget - HTML code - localtimes.info --><div align="center" style="margin:15px 0px 0px 0px">Dallas<script type="text/javascript" src="http://localtimes.info/clock.php?continent=North+America&country=United+States&city=Dallas&cp3_Hex=4C2100&cp2_Hex=FFF6F0&cp1_Hex=000080&fwdt=100"></script><br>
                <script type="text/javascript" src="http://localtimes.info/clock.php?continent=North+America&country=United+States&city=Dallas&color=white-black&widget_number=1000&fwdt=100&lab=1&mon=1"></script></div><!-end of code-->
            <!-Local Time Clock widget - HTML code - localtimes.info --><div align="center" style="margin:15px 0px 0px 0px">Seoul<script type="text/javascript" src="http://localtimes.info/clock.php?continent=Asia&country=Korea+%28South%29&city=Seoul&widget_number=117&cp3_Hex=21252D&cp2_Hex=CC0000&cp1_Hex=FFFFFF&fwdt=100&hbg=0&hfg=0&ham=0"></script><br>
                <script type="text/javascript" src="http://localtimes.info/clock.php?continent=Asia&country=Korea+%28South%29&city=Seoul&color=white-black&widget_number=1000&fwdt=100&lab=1&mon=1"></script></div><!-end of code-->
        </td>
        <td valign="top">
            <!--- main center start --->

            <table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="#BBCCFF">
                <tr><td >

                        <table width="100%" border="0" cellspacing="1" cellpadding="0" style="font-size: 12px; color: Black; font-family: 'Lucida Sans';">
                            <tr><td colspan="5" >

                                    <table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>
                                            <form action="scheduler" method="post" name="formMonth" id="formMonth">
                                                <td width="20%"><input type="button" value="Today" style="font-weight:bold; font-size: 12px; width: 70px;" onClick="javascript:document.formMonth.currentMon.value='<?=date('m',strtotime(getToday(0)));?>';document.formMonth.nowyear.value='<?=date('Y',strtotime(getToday(0)));?>'; document.formMonth.submit();">
                                                    <input type="button" value="&#9668;" style="font-size: 12px; width: 30px;" onClick="javascript:document.formMonth.nextvalue.value=-1; document.formMonth.submit();">
                                                    <input type="button" value="&#9658;" style="font-size: 12px; width: 30px;" onClick="javascript:document.formMonth.nextvalue.value=+1; document.formMonth.submit();">
                                                    <input type="hidden" name="currentMon" value="<?=$currentMon;?>">
                                                    <input type="hidden" name="currentDay">
                                                    <input type="hidden" name="nextvalue" >
                                                    <input type="hidden" name="swmode" value="calmonth">
                                                    <input type="hidden" name="nowyear" value="<?=$nowyear;?>"></td>
                                                <td width="50%" align="center" valign="middle"><font size="+2"><?=$montitle;?></font></td>
                                                <td width="30%" align="right">
                                                    <input type="button" name="df" value="Email" style="margin-left:240px; width: 72px; height: 18px; font-size: 11px;" onClick="sendmail_popupwin();">
                                                </td>
                                        </tr>
                                        </form>
                                    </table>

                                </td>
                            </tr>
                            <form action="scheduler" method="post" name="formSearch" id="formSearch">
                                <tr>
                                    <td height="25" colspan="5" align="right">
                                        <div><select name="staffs" id="staffs" style="font-size: 12px; background-color: #03013C; color: #ffffff; font-family: 'Lucida Sans'; font-weight: bold; width: 120px;" onChange="javascrpt:document.formSearch.submit();">
                                                <?=$queryGetStaff;?>
                                            </select>
                                            <select name="spart" id="spart" style="font-size: 12px; background-color: #03013C; color: #ffffff; font-family: 'Lucida Sans'; font-weight: bold; width: 80px;" >
                                                <option value="subject">Subject</option><option value="contents">Content</option></select>
                                            <input type="text" name="searchword" value="" style="font-size: 12px; width: 200px;"> <input type="submit" name="df" value="Search"></div>
                                        <input type="hidden" name="swmode" value="calSearch">
                                    </td>
                                </tr>
                            </form>

                            <?if(trim($swmode)=="calmonth"):?>
                                <tr><td colspan="5">
                                <?=$createCalendar;?>
                                </td></tr>
                            <?else:?>
                                <tr bgcolor="#E3E9FF">
                                    <td colspan="5" height="25" >
                                        <span style="cursor: pointer;" onClick="javascript:document.formMonth.swmode.value='calmonth'; document.formMonth.submit();"><< back calendar</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td height="425" colspan="5"  align="center" valign="top" bgcolor="#FFFFFF">

                                        <!--- start message --->
                                        <table width="98%" border="0" cellspacing="2" cellpadding="6" style="font-size: 12px; color: Black; font-family: 'Lucida Sans';">
                                            <?=$mainContents;?>
                                        </table>
                                        <!--- end message --->

                                    </td>
                                </tr>
                            <?endif;?>
                        </table>

                    </td>
                </tr>
            </table>
            <!--- main center end --->
        </td>
        <td width="20">



            <!--- right corner --->



        </td>
</table>
<form method="post" id="checkform" name="checkform"></form>


