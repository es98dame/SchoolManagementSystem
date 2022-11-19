<?


function getToday($diff_period){
	return date("Y-m-d");
}

function getAttColor($val){
		$bgc="";
		if($val=='P'): $bgc="#C5FF75"; endif;
		 if($val=='T1'): $bgc="#5EE7FF"; endif;
		 if($val=='T2'): $bgc="#8080FF"; endif;
		 if($val=='A'): $bgc="#FF8080"; endif;
 return $bgc;
}

function createCalendar($aParam,$holidayarr,$indata,$topdata,$vToday){
	$calendar="<table class='table'>";

	$gmtimestamp=gmmktime(0,0,0,$aParam["month"],1,$aParam["year"]);
	list($year,$month,$firstDay)=explode(",",gmstrftime('%Y,%m,%w',$gmtimestamp)); //monday tuesday wednesday thursday friday saturday sunday

	$ee=1;
	if($firstDay==0||$firstDay==6):
		$firstDay=1;
		$ee=2;
	endif;


    $calendar.="<tr align=\"center\" bgcolor=\"#E3E9FF\"><td width=\"14%\" height=\"20\">Monday</td><td width=\"14%\">Tuesday</td><td width=\"14%\">Wednesday</td><td width=\"14%\">Thursday</td><td width=\"14%\">Friday</td></tr>";
	$calendar.="<tr valign=\"top\">";

	  for($i=$firstDay;$i > 1;$i--):

		  $harr = null;
		  $tarr = null;
		  $iarr = null;

  	      $prevmonday = date("M, j",mktime(0,0,0,$month,2-$i,$year));
		  $isholiday = date("Y-m-d",mktime(0,0,0,$month,2-$i,$year));

 		  if(isset($holidayarr[$isholiday])):
			  $hocolo = "#FEBAC6";
			  $harr=$holidayarr[$isholiday];
		  else:
			  $hocolo = "#EBEBEB";
		  endif;

		  if(isset($topdata[$isholiday])):
			  $tarr=$topdata[$isholiday];
		  endif;

		  if(isset($indata[$isholiday])):
			  $iarr=$indata[$isholiday];
		  endif;

		  $sharelist="";
		  for($j=0; $j < count($tarr); $j++):
			  $sharelist .= "<div onMouseOver=\"this.style.backgroundColor='#FFE980';\" onMouseOut=\"this.style.backgroundColor='#EBED9A';\" onClick=\"javascript:document.formMonth.swmode.value='caldetail';document.formMonth.currentDay.value='".$isholiday."'; document.formMonth.submit();\" style='cursor:pointer; '>".$tarr[$j]."</div>";
		  endfor;

		  $mesaglist="";
		  for($j=0; $j < count($iarr); $j++):
			  $mesaglist .= "<div onMouseOver=\"javascript:this.pbg1=this.style.backgroundColor; this.style.backgroundColor='#EFEFEF';\" onMouseOut=\"this.style.backgroundColor=this.pbg1;\" onClick=\"javascript:document.formMonth.swmode.value='caldetail';document.formMonth.currentDay.value='".$isholiday."'; document.formMonth.submit();\" style='cursor:pointer; '>".$iarr[$j]."</div>";
		  endfor;

		 $calendar.="<td  height=\"70\" bgcolor=\"".$hocolo."\" >";
		 $calendar.="<div align=\"right\" onMouseOver=\"this.style.backgroundColor='#E6E6FF';\" onMouseOut=\"this.style.backgroundColor='#F0FFFF';\" onClick=\"javascript:document.formMonth.swmode.value='caldetail';document.formMonth.currentDay.value='".$isholiday."'; document.formMonth.submit();\" style=\"cursor: pointer; background-color: #F0FFFF; \">".$harr." ".$prevmonday."</div>";
		 $calendar.="<div align=\"center\" style=\"background-color: #EBED9A; \">".$sharelist."</div>";
 	     $calendar.="".$mesaglist."</td>";

	  endfor;

	//echo $firstDay;
	if($firstDay==0):
	   $calendar.="<tr bgcolor=\"#FFFFFF\"  valign=\"top\">";
	endif;

	for($d=$ee;$d<=gmdate('t',$gmtimestamp);$d++)://t total of month
		$harr = null;
		$tarr = null;
		$iarr = null;

		$ww = date("w",mktime(0,0,0,$month,$d,$year));
		if($ww!=0 && $ww!=6):

			$isholiday = date("Y-m-d",mktime(0,0,0,$month,$d,$year));

			if(isset($holidayarr[$isholiday])):
				$harr=$holidayarr[$isholiday];
				$hocolo = "#FEBAC6";
			else:
				$hocolo = "#ffffff";
			endif;

			if(isset($topdata[$isholiday])):
				$tarr=$topdata[$isholiday];
			endif;

			if(isset($indata[$isholiday])):
				$iarr=$indata[$isholiday];
			endif;

			//if($firstDay==6): $hocolo = "#FFD9D9"; endif;//sat
			$styletoday="";
			$calltoday="";
			$aligntoday="right";

			if($vToday==$isholiday):
				$aligntoday="center";
				$calltoday="<span style=\"font-size: 13px; font-weight: bold;\">Today</span >";
				$hocolo="#FFFA9B";
				$styletoday="style=\"border: 1px solid Maroon;\"";
			endif;

			 $sharelist="";
			 for($j=0; $j < count($tarr); $j++):
				$sharelist .= "<div onMouseOver=\"this.style.backgroundColor='#FFE980';\" onMouseOut=\"this.style.backgroundColor='#EBED9A';\" onClick=\"javascript:document.formMonth.swmode.value='caldetail';document.formMonth.currentDay.value='".$isholiday."'; document.formMonth.submit();\" style='cursor:pointer; '>".$tarr[$j]."</div>";
			 endfor;

			$mesaglist="";
			for($j=0; $j < count($iarr); $j++):
				$mesaglist .= "<div onMouseOver=\"javascript:this.pbg1=this.style.backgroundColor; this.style.backgroundColor='#EFEFEF';\" onMouseOut=\"this.style.backgroundColor=this.pbg1;\" onClick=\"javascript:document.formMonth.swmode.value='caldetail'; document.formMonth.currentDay.value='".$isholiday."';   document.formMonth.submit();\"  style='cursor:pointer;'>".$iarr[$j]."</div>";
			endfor;

			$calendar.="<td height=\"70\" ".$styletoday." bgcolor=\"".$hocolo."\">";
			$calendar.="<div align=\"".$aligntoday."\" onMouseOver=\"this.style.backgroundColor='#E6E6FF';\" onMouseOut=\"this.style.backgroundColor='#F0FFFF';\" onClick=\"javascript:document.formMonth.swmode.value='caldetail'; document.formMonth.currentDay.value='".$isholiday."';   document.formMonth.submit();\" style=\"cursor: pointer; background-color: #F0FFFF; \">".$harr.$calltoday." ".$d."</div>";
			$calendar.="<div align=\"center\" style=\"background-color: #EBED9A; \">".$sharelist."</div>";
			$calendar.="".$mesaglist."</td>";

			 //$firstDay++;
			if($ww==5): // representation of the day 0=sunday  6=saturday
				$calendar.="</tr><tr bgcolor=\"#FFFFFF\"  valign=\"top\">";
			endif;

		endif;

	endfor;


    for($i=0,$empty=(5-$ww); $i<$empty;$i++):
	  	 if( $firstDay>=0):
			 $harr = null;
			 $tarr = null;
			 $iarr = null;

		    $nextmonday = date("M, j",mktime(0,0,0,$month,$d+$i,$year));
			$isholiday = date("Y-m-d",mktime(0,0,0,$month,$d+$i,$year));

			 if(isset($holidayarr[$isholiday])):
				 $harr=$holidayarr[$isholiday];
				 $hocolo = "#FEBAC6";//FFD9D9
			 else:
				 $hocolo = "#EBEBEB";
			 endif;

			 if(isset($topdata[$isholiday])):
				 $tarr=$topdata[$isholiday];
			 endif;

			 if(isset($indata[$isholiday])):
				 $iarr=$indata[$isholiday];
			 endif;

			 $sharelist="";
			 for($j=0; $j < count($tarr); $j++):
				 $sharelist .= "<div onMouseOver=\"this.style.backgroundColor='#FFE980';\" onMouseOut=\"this.style.backgroundColor='#EBED9A';\" onClick=\"javascript:document.formMonth.swmode.value='caldetail';document.formMonth.currentDay.value='".$isholiday."'; document.formMonth.submit();\" style='cursor:pointer; '>".$tarr[$j]."</div>";
			 endfor;

			 $mesaglist="";
			 for($j=0; $j < count($iarr); $j++):
				 $mesaglist .= "<div onMouseOver=\"javascript:this.pbg1=this.style.backgroundColor; this.style.backgroundColor='#EFEFEF';\" onMouseOut=\"this.style.backgroundColor=this.pbg1;\"  onClick=\"javascript:document.formMonth.swmode.value='caldetail'; document.formMonth.currentDay.value='".$isholiday."'; document.formMonth.submit();\" style='cursor:pointer;'>".$iarr[$j]."</div>";
			 endfor;

	     	 $calendar.="<td height=\"70\" bgcolor=\"".$hocolo."\"><div align=\"right\" onMouseOver=\"this.style.backgroundColor='#E6E6FF';\" onMouseOut=\"this.style.backgroundColor='#F0FFFF';\" onClick=\"javascript:document.formMonth.swmode.value='caldetail'; document.formMonth.currentDay.value='".$isholiday."'; document.formMonth.submit();\" style=\"cursor: pointer; background-color: #F0FFFF;\">".$nextmonday."</div>";
			 $calendar.="<div align=\"center\" style=\"background-color: #EBED9A;\">".$sharelist."</div>";
			 $calendar.="".$mesaglist."</td>";

		 endif;
	endfor;


	$calendar.='</tr></table>';

	return $calendar;
}// end function

//==================== changejss  (attendanceRank.php) (AttendanceCharts.php)
function unescape($text) {
      return urldecode(preg_replace_callback('/%u([[:alnum:]]{4})/', create_function( '$word', 'return iconv("UTF-16LE", "UHC", chr(hexdec(substr($word[1], 2, 2))).chr(hexdec(substr($word[1], 0, 2))));' ), $text));
}

//==================== gettotalclassdays  (attendanceRank.php) (AttendanceCharts.php)
function gettotalclassdays($start_day,$end_day,$holidayarr){
$HH=0;
/*
foreach(array_keys($holidayarr) as $holkey):
	$daaa = strtotime($start_day);
	$dbbb = strtotime($holkey);
	if($daaa >= $dbbb):
		$HH++;
	endif;
endforeach;
*/
$starttime = strtotime($start_day);
$endtime = strtotime($end_day); // or today
if( $starttime < $endtime):  // or today is bigger than startday

		//$total_days = round((strtotime($end_day)-strtotime($start_day) )/86400)+1; //total day
		$total_days = round(($endtime - $starttime)/(24 * 60 * 60)+1);
		//echo $total_days;
		$countdays=0;
		for($d=0; $d < $total_days; $d++):
			$firstDay = date('w',strtotime($start_day)+(86400*$d));

			if($firstDay==0 || $firstDay ==5 || $firstDay==6){ //5 fri 6 sat 0 sun
				$countdays=$countdays+1;
			}else{
				$noeDay = date('Y-m-d',strtotime($start_day)+(86400*$d));
		    	if(!empty($holidayarr[''.$noeDay.''])):   $HH++;  endif;

			}

		endfor;
		$total_days2 = intval($total_days - $countdays - $HH);

elseif( $starttime == $endtime ):
	$total_days2=1;

else:
	$total_days2=0;
endif;

return $total_days2;
}

//========== get time diff (attendancemakeup.php)
function gettimediff($exittime,$entertime){
$mak_diffsum = 0;
$timev="";
if( $exittime && $entertime):
	$mak_diffsum = strtotime($exittime) - strtotime($entertime);
	$hourv = str_pad(intval($mak_diffsum/3600), 2, "0", STR_PAD_LEFT);
	$minv = str_pad( intval(  ($mak_diffsum%3600)/60 ), 2, "0", STR_PAD_LEFT);
	$timev = $hourv.":".$minv;
endif;
return $timev;
}


//========== get createCalendarforCharts (AttendanceCharts.php)
function createCalendarforCharts($aParam, $parr,$marr,$holiarr,$ttday2,$uAttSum){

$gmtimestamp=gmmktime(0,0,0,$aParam["month"],1,$aParam["year"]);
list($year,$month,$firstDay)=explode(",",gmstrftime('%Y,%m,%w',$gmtimestamp));
$calendar='<table border="0" cellspacing="1" cellpadding="0" style="font-size: 10px; color: Black; font-family: \'Lucida Sans\';">
		  <tr align="center">
		    <td width="30" bgcolor="#CCCCCC"></td>
		    <td width="30" bgcolor="#CCCCCC">M</td>
		    <td width="30"  bgcolor="#CCCCCC">T</td>
		    <td width="30"  bgcolor="#CCCCCC">W</td>
		    <td width="30"  bgcolor="#CCCCCC">T</td>
		    <td width="30"  bgcolor="#CCCCCC">F</td>
		  </tr>
		  <tr align="center">
		    <td rowspan="6" bgcolor="#CCCCCC"><strong>'.date('M',strtotime($aParam["month"]."-".$aParam["month"]."-01")).'</strong></td>
		    <td colspan="5" bgcolor="#FFFFFF"></td>
		  </tr>';

if($firstDay > 0 && $firstDay < 6 ):
 $calendar.='</tr><tr bgcolor="#FFFFFF">';
  for($i=1;$i<$firstDay;$i++):
	$calendar.='<td bgcolor="#EBEBEB">&nbsp;</td>';
  endfor;
endif;

$ss=0;
for($d=1, $t=gmdate('t',$gmtimestamp);$d<=$t;$d++,$firstDay++)://t total of month
	$d_c = str_pad($d, 2, "0", STR_PAD_LEFT); $rr=1;
		$hocolo='bgcolor="#FFFFFF"'; $gubuntitle="";
		foreach(array_keys($uAttSum) as $key):
				$uAI='';
			    if( $uAttSum[$key]['A']['startday']): $uAI='A'; $uTitle='Sick-Leave';  endif;
			    if($uAttSum[$key]['B']['startday']): $uAI='B';  $uTitle='Leave of Ab.'; endif;
			    if($uAttSum[$key]['C']['startday']): $uAI='C';  $uTitle='Early Withdrawal'; endif;
			    if($uAttSum[$key]['D']['startday']): $uAI='D';  $uTitle='Blank';endif;
				if($uAttSum[$key]['E']['startday']): $uAI='E';  $uTitle='Change of Class';endif;
				if($uAI):
				    $pday = strtotime($year."-".$month."-".$d_c);
					$start_day = strtotime($uAttSum[$key][$uAI]['startday']);
					$end_day = strtotime($uAttSum[$key][$uAI]['endday']);
					if( $start_day <= $pday && $end_day >= $pday ):
						$hocolo='bgcolor="#B0D2CB"'; $gubuntitle="title='".$uTitle." ".$uAttSum[$key][$uAI]['etc']."'";
					endif;
					$rr=0;
				else:
					$rr=1;
				endif;
		endforeach;


	if($firstDay==7): // representation of the day 0=sunday  6=saturday
		$firstDay=0;
		$calendar.='</tr><tr bgcolor="#FFFFFF" >';
	endif;
  	 if( $firstDay>0 && $firstDay<6):
		 $bgp_r=""; $bgp_e="";

		 if($parr["".$d_c.""]):
			 $attnoarr_p = explode(":||:",$parr["".$d_c.""]);
			 $apcnt = count( $attnoarr_p );
			 for($kk=0,$jj=0; $kk < $apcnt; $kk++):
				 if($attnoarr_p[$kk]):
					 		$bgc = ""; $swiperiod="";
				 			 $attarr_r = explode("^^^",$attnoarr_p[$kk]);
							 //======== change class 'E'
							 //if($attarr_r[6] =='E'): $hocolo='bgcolor="#B0D2CB"'; $gubuntitle="title='Change of Class ".$attarr_r[9]."'";  $rr=0;  endif;
						     if($attarr_r[1]=='P'): $bgc="#C5FF75"; endif;
							 if($attarr_r[1]=='T1'): $bgc="#5EE7FF"; endif;
							 if($attarr_r[1]=='T2'): $bgc="#8080FF"; endif;
							 if($attarr_r[1]=='A'): $bgc="#FF8080"; endif;
							 $brc="";
							 if($kk == 1 && $apcnt > 2): $brc="<br>";  endif;
							 if($attarr_r[3]=='R'):
									$bgp_r .="<span id=\"att".$attarr_r[0]."\" onMouseOver=\"switchColorover(this,'#EBF516');\" onMouseOut=\"switchColorout(this);\" style=\"font-size: 20px;cursor: pointer; color:".$bgc.";\"  onclick=\"javascript:getattendenceInfo('','".$attarr_r[0]."','','','','');\" >��</span>".$brc;
		 					    	$swiperiod = ($attarr_r[2]=="1st")?"2nd":"1st";
							     $jj++;
							 endif;
							 if($attarr_r[3]=='E'):
								$bgp_e .="<span  id=\"att".$attarr_r[0]."\" onMouseOver=\"switchColorover(this,'#EBF516');\" onMouseOut=\"switchColorout(this);\" style=\"font-size: 12px;cursor: pointer; color:".$bgc.";\" onclick=\"javascript:getattendenceInfo('','".$attarr_r[0]."','','','','');\" >��</span>".$brc;
							 endif;

				 endif;
			 endfor;

			 if($jj==1 && $r==1): //empty attendance => black color
			  		if($swiperiod=='1st'):
						$bgp_r .="<span onMouseOver=\"switchColorover(this,'#EBF516');\" onMouseOut=\"switchColorout(this);\" style=\"font-size: 20px;cursor: pointer; color:#000000; \" onclick=\"javascript:getattendenceInfo('".$attarr_r[4]."','','".$year."-".$month."-".$d_c."','".$attarr_r[5]."','".$attarr_r[3]."','".$swiperiod."');\" >��</span>";
					elseif($swiperiod=='2nd'):
						$bgp_r .="<span onMouseOver=\"switchColorover(this,'#EBF516');\" onMouseOut=\"switchColorout(this);\" style=\"font-size: 20px;cursor: pointer; color:#000000; \" onclick=\"javascript:getattendenceInfo('".$attarr_r[4]."','','".$year."-".$month."-".$d_c."','".$attarr_r[5]."','".$attarr_r[3]."','".$swiperiod."');\" >��</span>";
					endif;
			 endif;

			 if($jj==0 && $r==1): //empty attendance => black color
					$bgp_r .="<span onMouseOver=\"switchColorover(this,'#EBF516');\" onMouseOut=\"switchColorout(this);\" style=\"font-size: 20px;cursor: pointer; color:#000000;\" onclick=\"javascript:getattendenceInfo('".$attarr_r[4]."','','".$year."-".$month."-".$d_c."','".$attarr_r[5]."','".$attarr_r[3]."','1st');\" >��</span>";
					$bgp_r .="<span onMouseOver=\"switchColorover(this,'#EBF516');\" onMouseOut=\"switchColorout(this);\" style=\"font-size: 20px;cursor: pointer; color:#000000;\" onclick=\"javascript:getattendenceInfo('".$attarr_r[4]."','','".$year."-".$month."-".$d_c."','".$attarr_r[5]."','".$attarr_r[3]."','2nd');\" >��</span>";
			 endif;

		 else:


		   //empty attendance => black color
		   //1. mon - thur  2. not holiday  3. only previous today  4. not exception
		   if( $firstDay < 5 && empty($holiarr["".$d_c.""]) && strtotime($year."-".$month."-".$d_c) <= strtotime($ttday2) && $r==1):
					$bgp_r .="<span onMouseOver=\"switchColorover(this,'#EBF516');\" onMouseOut=\"switchColorout(this);\" style=\"font-size: 20px;cursor: pointer; color:#000000;\" onclick=\"javascript:getattendenceInfo('".$attarr_r[4]."','','".$year."-".$month."-".$d_c."','".$attarr_r[5]."','".$attarr_r[3]."','1st');\" >��</span>";
					$bgp_r .="<span onMouseOver=\"switchColorover(this,'#EBF516');\" onMouseOut=\"switchColorout(this);\" style=\"font-size: 20px;cursor: pointer; color:#000000;\" onclick=\"javascript:getattendenceInfo('".$attarr_r[4]."','','".$year."-".$month."-".$d_c."','".$attarr_r[5]."','".$attarr_r[3]."','2nd');\" >��</span>";
		   endif;

		 endif;
		// unset($jj);
		  unset($attnoarr_p);

		 //make-up pop window
		 $bgm=""; if($marr["".$d_c.""]): $bgm="<span onMouseOver=\"switchColorover(this,'#EBF516');\" onMouseOut=\"switchColorout(this);\" style=\"font-size: 15px;cursor: pointer; color:#FEDE01;\"  onclick=\"javascript:getmakeupInfo('".$marr["".$d_c.""]."');\"  title=\"".$marr["".$d_c.""]."\"  >��</span>"; endif;

		$sse=0; if($holiarr["".$d_c.""]): $hocolo = 'bgcolor="#FFD9D9"'; $sse=1; endif;
		 $bempty="";
		 if($bgp_r=="" && $bgp_e=="" && $bgm=="" && $sse==0):
		 		$time_today = strtotime($ttday2);
		    if( strtotime($year."-".$month."-".$d_c) == $time_today ):
				$es = date('w',$time_today); //$ttday2;
				if($es != 5):
				  $bempty="style=\"border: 1px solid #0000FF;\"";
				endif;
			  endif;
		 endif;

		$calendar.='<td '.$gubuntitle.' '.$hocolo.' '.$bempty.' '.$bempty2.' valign="top" height="30"><div align="right">'.$d.'</div>'.$bgp_r.$bgm.$bgp_e.'</td>';
		unset($attarr_r);
	 endif;
endfor;

if($firstDay!=7):
  for($i=0,$empty=(7-$firstDay); $i<$empty;$i++):
  	 if( $firstDay>0 && $firstDay<6):
     $calendar.='<td bgcolor="#EBEBEB">&nbsp;</td>';
	 endif;
  endfor;
endif;

	$calendar.='</tr></table>';

return $calendar;
}


//========== get getpageslist (AttendanceCharts.php)
function getpageslist($per_page_lists,$per_paging_lists,$total_count,$current_page){
$returncontents='';

$cp2 = ceil(ceil($total_count/$per_page_lists)/$per_paging_lists);
$perbas = $per_page_lists*$per_paging_lists;// 15*10
$last_page = ($cp2-1)*$perbas;
$cp = floor(ceil($current_page/$per_page_lists)/$per_paging_lists);
if($cp > 0): $prev_page = ($cp*$perbas)-$per_page_lists; endif;
$next_page = ($cp+1)*$perbas+1;
if($next_page > $total_count):  $next_page = ""; endif;

if( $per_page_lists <= $current_page ): $returncontents .=" &nbsp;<img style=\"cursor: hand;\" src='/skin/board/basic/img/page_begin.gif' border='0' onClick=\"gochang(0)\" align='absmiddle' title='begin'>";endif;
if( $perbas <= $current_page): $returncontents .=" &nbsp;<img style=\"cursor: hand;\" src='/skin/board/basic/img/page_prev.gif' border='0' onClick=\"gochang(".$prev_page.")\" align='absmiddle' title='prev'>";endif;

for($j=0; $j < $per_paging_lists ; $j++):
	$dddd = (($j*$per_page_lists)+($cp*$perbas));
	if( $dddd < $total_count):
		if( $current_page == $dddd):
			$returncontents .=" <b><span style=\"color:#4D6185; font-size:12px; text-decoration:underline;\">".($j+1+($cp*$per_paging_lists))."</span></b>";
		else:
    		$returncontents .=" &nbsp;<span style=\"cursor: hand;\" onClick=\"gochang(".$dddd.")\">".($j+1+($cp*$per_paging_lists))."</span>";
		endif;
	endif;
endfor;

if($cp < ($cp2-1)): $returncontents .=" &nbsp;<img  style=\"cursor: hand;\" src='/skin/board/basic/img/page_next.gif' border='0' onClick=\"gochang(".($next_page-1).")\" align='absmiddle' title='next'>"; endif;
if( $current_page <= $last_page ): $returncontents .=" &nbsp;<img style=\"cursor: hand;\" src='/skin/board/basic/img/page_end.gif' border='0' onClick=\"gochang(".($last_page+$per_page_lists).")\" align='absmiddle' title='end'>"; endif;

return $returncontents;
}

function generatePassword ($length = 8)
  {
    // start with a blank password
    $password = "";
    // define possible characters - any character in this string can be
    // picked for use in the password, so if you want to put vowels back in
    // or add special characters such as exclamation marks, this is where
    // you should do it
    $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
    // we refer to the length of $possible a few times, so let's grab it now
    $maxlength = strlen($possible);
    // check for length overflow and truncate if necessary
    if ($length > $maxlength) {
      $length = $maxlength;
    }
    // set up a counter for how many characters are in the password so far
    $i = 0;
    // add random characters to $password until $length is reached
    while ($i < $length) {
      // pick a random character from the possible ones
      $char = substr($possible, mt_rand(0, $maxlength-1), 1);
      // have we already used this character in $password?
      if (!strstr($password, $char)) {
        // no, so it's OK to add it onto the end of whatever we've already got...
        $password .= $char;
        // ... and increase the counter by one
        $i++;
      }
    }
    // done!
    return $password;
  }

?>
