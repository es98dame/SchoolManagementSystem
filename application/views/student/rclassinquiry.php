<div id="msgResult" style="display:none;top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 10; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #FFD700; border: 1px solid Black;"></div>
<div id="ErrorMsg" style="display: none; top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 999; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #EE82EE; border: 1px solid Black;"></div>
<div id="layoutObj" style="width:100%;height:600px;margin:-16px 0px 0px 0px;;padding:0px;overflow:hidden;"></div>
<div id="winVP" style="font-family: Tahoma; font-size: 11px; width: 100%; height:100%; overflow: hidden;"></div>

<script>
    window.onload=loadRClassInquiry;

    var	dhxLayout=new dhtmlXLayoutObject("layoutObj","1C");
    dhxLayout.cells("a").showHeader();
    dhxLayout.cells("a").setText("<div style=\"float:left;font-family: Tahoma; font-size: 15px; font-weight: bold; color: #696969;\">Remediation Class Inquiry</div>");

    //=== Grid of Family List ----------------------------------------------------------------
    var grid_classgrid = dhxLayout.cells("a").attachGrid();
    grid_classgrid.setImagePath("dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
    grid_classgrid.setHeader("Year,Trimester,Class,Instructor,Attendance,Total Att",null,["text-align:center;","text-align:center;","text-align:center;","text-align:center;","text-align:center;"]);
    grid_classgrid.setColumnIds("schoolyear,trimester,classname,teachername,attendance,totalatt");
    grid_classgrid.setInitWidths("60,60,120,120,80,80");
    grid_classgrid.enableAutoWidth(true);
    grid_classgrid.setColAlign("center,center,center,center,center,center");
    grid_classgrid.setColTypes("ro,ro,link,ro,ro,ro");
    grid_classgrid.setColSorting("str,str,str,str,str,str");
    grid_classgrid.setSkin("dhx_skyblue");
    grid_classgrid.attachEvent("onRowSelect", function(id,ind){});
    grid_classgrid.attachEvent("onBeforeSorting",function(ind,type,direction){ window.class_col = ind; window.class_drt = ((direction == "des") ? "asc": "des"); return true; });
    grid_classgrid.init();

    function loadRClassInquiry(){
        var oparams = "";
        if(window.class_drt){
            grid_classgrid.setSortImgState(true, window.class_col, window.class_drt,1);
            oparams="&orderby=" + window.class_col + "&direct=" + window.class_drt;
        }
        dhxLayout.cells("a").progressOn();
        grid_classgrid.clearAndLoad("<?php echo $_SERVER["PHP_SELF"];?>/getRClasses?unqueId="+(new Date()).valueOf()+ oparams,function(){
            dhxLayout.cells("a").progressOff();
        });
    }

</script>