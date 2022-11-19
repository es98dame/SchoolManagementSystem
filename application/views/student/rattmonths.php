<div id="msgResult" style="display:none;top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 10; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #FFD700; border: 1px solid Black;"></div>
<div id="ErrorMsg" style="display: none; top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 999; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #EE82EE; border: 1px solid Black;"></div>
<div id="layoutObj" style="width:100%;height:600px;margin:-16px 0px 0px 0px;;padding:0px;overflow:hidden;"></div>
<div id="SubMenuId" style="margin-left:-20px;"><ul style="list-style:none;"><?php echo $param["menus"];?></ul></div>

<style type="text/css" media="screen">
    .PrintButton{
        display:block;
    }
</style>
<style type="text/css" media="print">
    body * {
        visibility: hidden;
    }
    .PrintButton{
        display:none;
    }
    #grade_container, #grade_container * {
        visibility: visible;
    }

    #SubMenuId{
        display: none;
    }

    #grade_container {
        position: relative;
        left: 0;
        top: 0;
    }
</style>


<style type="text/css">
    <!--
    .formtable {
        border-top:1px solid rgb(240,240,240);
        border-left:1px solid rgb(240,240,240);
        padding:0;
        width:620px;
        empty-cells:show;
        margin-bottom:20px;
    }

    .formtable td {
        background:rgb(255,255,255);
        border-bottom:1px solid rgb(240,240,240);
        border-right:1px solid rgb(240,240,240);
        padding:4px;
        margin:0;
        vertical-align:top;
        text-align:left;
        font-size:8pt;
    }

    .formtable td.tableinfo {
        font-weight:bold;
        background:rgb(230,230,230);
        padding:6px 4px;
    }

    .formtable td.tablecol {
        background:rgb(245,245,245);
        font-size:10px;
        text-transform:uppercase;
    }

    .month {
        width:300px;
        float:left;
        margin-right:20px;
    }

    .month td {
        padding:2px;
    }

    .month td a i {
        font-style:normal;
    }

    .month td a {
        color:rgb(128,128,128);
    }

    .month td a:hover {
        color:rgb(0,85,255);
    }

    // -->
</style>

<div id="grade_container" style="padding: 1px 1px 1px 1px; width:900px; height:100%; overflow-x: auto; overflow-y: auto;">
    <div id="gradebox" style="padding: 0px 0px 0px 0px; width:100%;height:100%; background-color:white;overflow:hidden">

        <table class="formtable" cellspacing="0">
            <tr><td colspan="2"><?php echo $gpname;?> / <?php echo $classname;?></td></tr>
            <tr><td colspan="2"><b><?php echo $stname;?></b></td></tr>
            <tr>
                <td style="padding:6px;line-height:130%" colspan="2">
                    Totals: <b style='color:#'>P</b> = <?php echo $sump;?>&nbsp;<span style='color:rgb(128,128,128);'>|</span>&nbsp;<b style='color:#'>A</b> = <?php echo $suma;?>	</td>
            </tr>
        </table>
        <?php
        $yyy = array();
        foreach ($arr as $k => $v) {
            $mmm = array();
            $yyy = $arr[$k];
            foreach ($yyy as $k2 => $v2) {
                $mmm = $yyy[$k2];
                $mon = date("M Y",strtotime($k."-".$k2."-01"));
                echo "<table class='formtable month' cellspacing='0'>
			<tr><td class='tableinfo' colspan='7'>".$mon."</td></tr><tr> 
							<td  class='tablecol'>Mon</td>
							<td  class='tablecol'>Tue</td>
							<td  class='tablecol'>Wed</td>
							<td  class='tablecol'>Thu</td>
							<td  class='tablecol'>Fri</td>
							<td  class='tablecol'>Sat</td>
							<td  class='tablecol'>Sun</td>
		</tr><tr>";
                $days = cal_days_in_month(CAL_GREGORIAN,$k2,$k);
                $s=0;
                for($m=1; $m <= $days; $m++){
                    $d = date("d",strtotime($k."-".$k2."-".$m) );
                    $w = date("w",strtotime($k."-".$k2."-".$d) );
                    if($m <= 7 && $s==0){
                        if($w==0){
                            echo "<td colspan='6'></td>";
                        }elseif($w==1){
                            echo "";
                        }elseif($w==2){
                            echo "<td></td>";
                        }else{
                            echo "<td colspan='".($w-1)."'></td>";
                        }
                        $s=1;
                    }//end if

                    $nam="";
                    if(isset($mmm["".$d.""])){
                        $nam = $mmm[$d];
                    }

                    echo "<td><i>".$d."</i>&nbsp;<b>".$nam."</b></td>";
                    if($w==0){ //sunday
                        echo "</tr><tr>";
                    }//end if

                }// end for

                echo "</tr></table>";
            }
        }


        ?>
    </div>
</div>
<script>
    var	dhxLayout=new dhtmlXLayoutObject("layoutObj","2U");
    dhxLayout.cells("a").showHeader();
    dhxLayout.cells("a").setText("Remediation Class");
    dhxLayout.cells("a").setWidth(100);
    dhxLayout.cells("a").attachObject("SubMenuId");
    dhxLayout.cells("b").showHeader();
    dhxLayout.cells("b").setText("<div style=\"font-family: Tahoma; font-size: 15px; font-weight: bold; color: #696969; float:left;\"><?php echo $param["title"];?></div>");
    dhxLayout.cells("b").attachObject("grade_container");
</script>
