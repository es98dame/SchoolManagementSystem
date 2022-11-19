<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Alidb
{
	var $con;
	var $HostName;
	var $UserName;
	var $PasswdName;
	var $DatabaseName;

	var $vClassGroupNo;
	var $vClassGroupRNo;
	var $vClassGroupName;
	var $vClassGroupStartday;
	var $vClassGroupEndday;
	var $vClassGroupSumClass;

	var $vNowClassGroupNo;
	var $vNowClassGroupStartday;
	var $vNowClassGroupEndday;
	var $vNowClassGroupSumClass;

	function ALIDb(){
		 $HostName = "sql549.main-hosting.eu";
		 $UserName = "u866174927_es98dame";
		 $PasswdName = "chzhclq312A";
		 $DatabaseName = "u866174927_alidb";
		 print_r('ggddddd connec');
	    $this->con = mysqli_connect($HostName, $UserName, $PasswdName,$DatabaseName);
		if (!$this->con) {
			print_r('ggd connec');
			die('Connect Error: ' . mysqli_connect_error());
		}else{
			print_r('Good connec');
		}
		mysqli_query($this->con,"SET NAMES 'euckr'");
	}

	function close(){
	    mysqli_close($this->con);
	}

	/* Execute a myqsl query. Optionally set the number of records affected. Connection is handled automatically  by the constructor and destructor.  */
	function query($sql){
	    $sql = str_replace(array('\\"', "\\'"), array('"', "'"), $sql);
			print_r("hi3");
			if ($result = mysqli_query($this->con, $sql)) {

		}else{
			echo("Error description: " . mysqli_error($this->con));
			exit();
		}
		return $result;
	}

	function queryAsSingle($sql, $type = MYSQLI_ASSOC){
	    $result = $this->query($sql); $this->isqueryresource($result);
	    return mysqli_fetch_array($result, $type);
	}

	function queryAsNumRows($sql){
	    $result = $this->query($sql); $this->isqueryresource($result);
	    return mysqli_num_rows($result);
	}

	/* turns your result into an array (valid typs are MYSQL_ASSOC, MYSQL_NUM, and MYSQL_BOTH) */
	function queryAsArray($sql, $type = MYSQLI_ASSOC){
	    $result = $this->query($sql); $this->isqueryresource($result);
	    $data = array();
	    while($row = mysqli_fetch_array($result, $type))
	        $data[] = $row;

	    return $data;
	}

	function isqueryresource($result){
	    if(!is_resource($result)){
	   //     trigger_error("query did not return a valid resource", E_USER_WARNING);
	        return $result;
	    }
	}

	/* turns your result into an array (valid typs are MYSQL_ASSOC, MYSQL_NUM, and MYSQL_BOTH) */
	function queryGetHolidays($pClassGropuNo,$items,$type = MYSQLI_ASSOC){
		$querywhere = "";
	    if(trim($pClassGropuNo)==true): $querywhere = " and class_group_no=".$pClassGropuNo; endif;
		$sql = "select special_day, subject from `ali_specialday` where items in ('".$items."') ".$querywhere." order by special_day asc";
	    $result = $this->query($sql);  $this->isqueryresource($result);

	    $data = array();
	    while($row = mysqli_fetch_array($result, $type))
	        $data[$row['special_day'] ] = $row['subject'];
	    return $data;
	}

	function queryGetCalendar($auth,$permis,$type = MYSQLI_ASSOC){
		$indata="";
		$sql = "select a.calboard_no, a.user_no,b.roleid,b.firstname,b.lastname,b.initial,b.nickname,b.bgcolorone, a.subject, a.contents, a.thisday, a.state, a.regdate from ali_calboard a, ali_user b where a.permission in ('".$permis."') and a.user_no=b.no and b.roleid in (".$auth.") order by a.thisday";
	    $result = $this->query($sql); $this->isqueryresource($result);
		 $cntin=0; $preday="";
		while($row = mysqli_fetch_array($result, $type)):
		      $subj = (strlen($row['subject'])>25)?substr($row['subject'],0,25)."...":$row['subject'];
			    if($row['roleid']==3):  $subj = "<font color=\"#008000\">".$subj."</font>"; endif;
				if($row['state']=='C'):  $subj = "<s>".$subj."</s>"; endif;
				if($permis=='P'): //Personal
					$indata[ $row['thisday'] ][]= "<span title='".$row['firstname']." ".$row['lastname'].": ".$row['contents']."'> <span style='background-color: ".$row['bgcolorone'].";'><b>".$row['initial']."</b></span>: ".$subj." </span>";
				else: //Shared
					$indata[ $row['thisday'] ][]= "<span title='".$row['contents']."'> ".$subj." </span>";
				endif;
				 //$preday = $row['thisday'];
	   			//if($preday != $row['thisday']): $cntin=0; else: $cntin++;  endif;
				$cntin++;
		endwhile;
	  return $indata;
	}

	function queryGetStaff($staff,$type = MYSQLI_ASSOC){
		$sql = "select no,firstname,lastname from ali_user where active=1";
	    $result = $this->query($sql); $this->isqueryresource($result);
		$contents = "<option value=\"\">Staff</option>";
		while($row = mysqli_fetch_array($result, $type)):
		 	if($staff==$row['no']): $sel="selected"; else: $sel=""; endif;
			$contents .= "<option value=\"".$row['no']."\" ".$sel.">".$row['firstname']." ".$row['lastname']."</option>";
		endwhile;
	   return $contents;
	}
	/*
	function queryGetTrimesterInfo($vGroupNo){
	    if(!empty($vGroupNo)): $querycon = "class_group_no=".$vGroupNo; else: $querycon = "rno=0"; endif;
		$sql = "select  class_group_no, group_name, path,startday,endday, rno,sum_classes  from ali_class_group where items in ('u') and ".$querycon." order by class_group_no desc";
	    $result = $this->query($sql); $this->isqueryresource($result);
		if($row = mysqli_fetch_array($result, MYSQL_ASSOC)):
			$this->vClassGroupRNo = $row['rno'];
			$this->vClassGroupNo = $row['class_group_no'];
			$this->vClassGroupName = $row['group_name'];
			$this->vClassGroupStartday = $row['startday'];
			$this->vClassGroupEndday = $row['endday'];
			$this->vClassGroupSumClass = $row['sum_classes'];
		endif;
	  return $sql;
	}

	function queryGetTopInfo($vTopNo){
		$sql = "select a.class_group_no, a.startday, a.endday, a.sum_classes  from ali_class_group a, ali_class_group b where a.class_group_no=b.rno and b.class_group_no=".$vTopNo;
	    $result = $this->query($sql); $this->isqueryresource($result);
		if($row = mysqli_fetch_array($result, MYSQL_ASSOC)):
			$this->vNowClassGroupNo = $row['class_group_no'];
			$this->vNowClassGroupStartday = $row['startday'];
			$this->vNowClassGroupEndday = $row['endday'];
			$this->vNowClassGroupSumClass = $row['sum_classes'];
		endif;
	}

	function queryGetTrimGroupNolist($vTopNo){
		$sql = "select  class_group_no  from ali_class_group where rno=".$vTopNo." order by group_name";
	    $result = $this->query($sql); $this->isqueryresource($result);
		$vClassGroupNo=0;
		//$j=0;
		if($row = mysqli_fetch_array($result, MYSQL_ASSOC)):
		    $vClassGroupNo = $row['class_group_no'];
			//if($j > 0): $sddds = $sddds.","; endif;
			//$sddds = $sddds."".$row['class_group_no'];
			//$j++;
		endif;
		return $vClassGroupNo;
	}

	function queryGetStudentNolist($vTopNo){
		$sql = "SELECT distinct(students_no) FROM ali_enroll_class WHERE class_group_no=".$vTopNo." order by class_group_no";
	    $result = $this->query($sql); $this->isqueryresource($result);
		$j=0;
		while($row = mysqli_fetch_array($result, MYSQL_ASSOC)):
			if($j > 0): $sddds = $sddds.","; endif;
			$sddds = $sddds."".$row['students_no'];
			$j++;
		endwhile;
		return " and a.students_no in (".$sddds.")";
	}

	function GetWLetterCount($vSTDNo){ $scnt = 0;
		$sql = "select count(*) AS cnt from ali_warning_letter a, ali_students b where a.receiver_no=b.students_no and b.students_no=".$vSTDNo;
	    $result = $this->query($sql); $this->isqueryresource($result);
		if($row = mysqli_fetch_array($result, MYSQL_ASSOC)):
			$scnt = $row['cnt'];
		endif;
		return $scnt;
	}


	function GetStudentRank($vStudentsNo,$pStartday,$pEndday,$pClassGropuNo){
			$result8 = $this->queryAsArray("select a.GUBUN, a.class_no, a.items,a.period2, count(a.items) as itemscnt, d.class_name, e.name AS instructorname  from ali_attendance a, ali_enroll_class b, ali_students c, ali_classes d, ali_user e  where a.students_no=b.students_no and  a.students_no=c.students_no and  c.students_no=b.students_no and a.students_no=".$vStudentsNo." AND a.class_no = d.classes_no AND a.instructors_no = e.no and a.semester_no=b.semester_no and a.semester_no=".$pClassGropuNo." AND a.attendance_day BETWEEN b.startday AND b.endday group by c.name, a.GUBUN, a.semester_no, a.class_group_no,a.period2, a.class_no, a.items ");
			foreach( $result8 as $i => $val2):
				$eachitem_total[ $vStudentsNo ]["".$val2["GUBUN"]."" ][$val2["class_no"]]["".$val2["items"].""] = $val2["itemscnt"];
			endforeach;

			$totalcnt =0;
			//echo "select b.enroll_class_no, b.gubun, b.sum_classes, b.startday, b.endday, b.etc from  ali_enroll_class b where b.students_no=".$vStudentsNo." and b.semester_no=".$pClassGropuNo."  ";
			$result7 = $this->queryAsSingle("select count(*) as cnt from  ali_enroll_class b where b.students_no=".$vStudentsNo." and b.semester_no=".$pClassGropuNo."  " );
			foreach( $result7 as $i => $val7 ):
				$totalcnt = $val7["cnt"];
			endforeach;

			if($totalcnt > 1):
			$result3 = $this->queryAsArray("select b.enroll_class_no, b.gubun, b.sum_classes, b.startday, b.endday, b.etc from  ali_enroll_class b where b.students_no=".$vStudentsNo." and b.semester_no=".$pClassGropuNo."  " );// limit $current_page,
			foreach( $result3 as $i => $val3 ):
			  // if( $val3['gubun']=="R" || $val3['gubun']=="E"): // R normal   E change class    except A, B, C, D
				$vAttendanRESum[ $vStudentsNo ][ $val3['enroll_class_no']][ $val3['gubun']]['sum'] =  $val3['sum_classes'];
				$vAttendanRESum[ $vStudentsNo ][ $val3['enroll_class_no']][ $val3['gubun']]['startday'] =  $val3['startday'];
				$vAttendanRESum[ $vStudentsNo ][ $val3['enroll_class_no']][ $val3['gubun']]['endday'] =  $val3['endday'];
				$vAttendanRESum[ $vStudentsNo ][ $val3['enroll_class_no']][ $val3['gubun']]['etc'] =  $val3['etc'];
			 //  endif;
			endforeach;

			else:
				$sql = "select b.enroll_class_no, b.gubun, b.sum_classes, b.startday, b.endday, b.etc from  ali_enroll_class b where b.students_no=".$vStudentsNo." and b.semester_no=".$pClassGropuNo."  ";
				if($val3 = $this->queryAsSingle($sql)):
				$vAttendanRESum[ $vStudentsNo ][ $val3['enroll_class_no']][ $val3['gubun']]['sum'] =  $val3['sum_classes'];
				$vAttendanRESum[ $vStudentsNo ][ $val3['enroll_class_no']][ $val3['gubun']]['startday'] =  $val3['startday'];
				$vAttendanRESum[ $vStudentsNo ][ $val3['enroll_class_no']][ $val3['gubun']]['endday'] =  $val3['endday'];
				$vAttendanRESum[ $vStudentsNo ][ $val3['enroll_class_no']][ $val3['gubun']]['etc'] =  $val3['etc'];
				endif;
			endif;



		$vToday=date("Y-m-d",time()+(86400*0));

		//============= get Holidays
		$holidayarr = $this->queryGetHolidays($pClassGropuNo);

//###################################### summary part
	$total_days=0; $todays_sum=0;
	$uAttSum = $vAttendanRESum[ $vStudentsNo ];
	if( count($uAttSum) > 0):
	foreach( array_keys($uAttSum) as $key):

	  if($uAttSum[$key]['R']['sum'] || $uAttSum[$key]['E']['sum']):
	    if($uAttSum[$key]['R']['sum']): $uAI='R'; endif;
	    if($uAttSum[$key]['E']['sum']): $uAI='E'; endif;
		$total_days = $total_days + $uAttSum[$key][$uAI]['sum'];
		$starttime = strtotime($uAttSum[$key][$uAI]['startday']);
		$endtime = strtotime($uAttSum[$key][$uAI]['endday']);
		$todaytime = strtotime($vToday); // or today
			if( $endtime <= $todaytime ):
				$todays_sum = $todays_sum + $uAttSum[$key][$uAI]['sum'];
			else:
				if( $starttime <= $todaytime):  // or today is bigger than startday

					//=============$start_day,$end_day,$holidayarr
					$HH=0;
					$starttime = strtotime($uAttSum[$key][$uAI]['startday']);
					$endtime = strtotime($vToday); // or today
					if( $starttime < $endtime):  // or today is bigger than startday

					//$total_days = round((strtotime($end_day)-strtotime($start_day) )/86400)+1; //total day
					$total_days = round(($endtime - $starttime)/(24 * 60 * 60)+1);

					$countdays=0;
					for($d=0; $d < $total_days; $d++):
						$firstDay = date('w',strtotime($uAttSum[$key][$uAI]['startday'])+(86400*$d));
						$noeDay = date('Y-m-d',strtotime($uAttSum[$key][$uAI]['startday'])+(86400*$d));
					    if(!empty($holidayarr[''.$noeDay.''])): $HH++;  endif;
						if($firstDay==0 || $firstDay ==5 || $firstDay==6){ //5 fri 6 sat 0 sun
							$countdays=$countdays+1;
						}
					endfor;
					$kese = intval($total_days - $countdays - $HH);
					elseif( $starttime == $endtime ):
					$kese=1;
					else:
					$kese=0;
					endif;
					//=====================================================

					$todays_sum = $todays_sum + $kese;
				endif;
			endif;
	   endif;
	endforeach;
	endif;

	//============= makeup
	$mak_diffsum=0;
	$makeupcnt=0;
	$result = $this->queryAsArray("select makeup_no, students_no, date_format(attendance_day,'%Y') AS yy,date_format(attendance_day,'%m') AS mm,date_format(attendance_day,'%d') AS dd, exit_time,enter_time, etc from ali_makeup where attendance_day between '".$pStartday."' and '".$pEndday."' and `students_no`=".$vStudentsNo." order by attendance_day asc");
	foreach( $result as $i => $val ):
		$mak_exit_time = $val["exit_time"];
		$mak_enter_time = $val["enter_time"];

		$timev = "";
		if($mak_exit_time && $mak_enter_time):
			$mak_diffsum = $mak_diffsum+ (strtotime($mak_exit_time) - strtotime($mak_enter_time));
		endif;
		$i = $i+1;
	endforeach;

	if($mak_diffsum > 0):
		$hourv = intval($mak_diffsum/3600);
		$minv = str_pad( intval(  ($mak_diffsum%3600)/60 ), 2, "0", STR_PAD_LEFT);
		$makeupcnt = intval(($mak_diffsum/14400));//4hr=1present(now) or  4.5hr = 1present 16200
		$timev = $hourv.":".$minv;
	endif;

	$psum=0; $t1sum=0; $t2sum=0; $asum=0; $totalsum=0;
  	  $rItemSumListR = $eachitem_total[ $vStudentsNo ]["R" ];
	if(count($rItemSumListR)>0):
	foreach( $rItemSumListR as $k => $val4 ):
		$t1extend=0; if($rItemSumListR[$k]["T1"] > 5): $t1extend=intval($rItemSumListR[$k]["T1"]/5); endif;
		$t2extend=0; if($rItemSumListR[$k]["T2"] > 3): $t2extend=intval($rItemSumListR[$k]["T2"]/3); endif;
		$psum=$psum+$rItemSumListR[$k]['P'];
		$t1sum=$t1sum+$rItemSumListR[$k]['T1']-$t1extend;
		$t2sum=$t2sum+$rItemSumListR[$k]['T2']-$t2extend;
		$asum=$asum+$rItemSumListR[$k]['A'];
	endforeach;
	endif;

	  $rItemSumListE = $eachitem_total[ $vStudentsNo ]["E" ];
	if(!empty($rItemSumListE)):
 	foreach( $rItemSumListE as $k => $val4 ):
		$t1extend=0; if($rItemSumListE[$k]["T1"] > 5): $t1extend=intval($rItemSumListE[$k]["T1"]/5);endif;
		$t2extend=0; if($rItemSumListE[$k]["T2"] > 3): $t2extend=intval($rItemSumListE[$k]["T2"]/3);endif;
		$psum=$psum+$rItemSumListE[$k]['P'];
		$t1sum=$t1sum+$rItemSumListE[$k]['T1']-$t1extend;
		$t2sum=$t2sum+$rItemSumListE[$k]['T2']-$t2extend;
		$asum=$asum+$rItemSumListE[$k]['A'];
	endforeach;
	endif;

	$this_stu=($psum+$t1sum+$t2sum)/2;
	$pByTodaysSum = $this_stu+$makeupcnt;

	if($todays_sum>0):
		$pPercent = number_format(($pByTodaysSum/$todays_sum)*100,1);
		return $pPercent;// ;
	else:
		return "";
	endif;


	}
	*/

}//end class

