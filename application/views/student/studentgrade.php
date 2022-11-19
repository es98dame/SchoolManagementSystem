<div id="msgResult" style="display:none;top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 10; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #FFD700; border: 1px solid Black;"></div>
<div id="ErrorMsg" style="display: none; top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 999; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #EE82EE; border: 1px solid Black;"></div>
<div id="layoutObj" style="width:100%;height:600px;margin:-16px 0px 0px 0px;;padding:0px;overflow:hidden;"></div>
<div id="SubMenuId" style="margin-left:-20px;"><ul style="list-style:none;"><?php echo $param["menus"];?></ul></div>


<script>
    window.onload=loadGrades;
    //========================= Layout
    var	dhxLayout=new dhtmlXLayoutObject("layoutObj","2U");
    dhxLayout.cells("a").showHeader();
    dhxLayout.cells("a").setText("Class Apps");
    dhxLayout.cells("a").setWidth(100);
    dhxLayout.cells("a").attachObject("SubMenuId");
    dhxLayout.cells("b").showHeader();
    dhxLayout.cells("b").setText("<div style=\"font-family: Tahoma; font-size: 15px; font-weight: bold; color: #696969; float:left;\"><?php echo $param["title"];?></div>");

    //========================= Assignment Lines
    var assigngrid = dhxLayout.cells("b").attachGrid();
    assigngrid.setImagePath("/dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
    assigngrid.setHeader("Assignment,Score,Possible",null,["text-align:left","text-align:center","text-align:center"]);
    assigngrid.setColumnIds("name,score,points");
    assigngrid.setInitWidths("300,80,80");
    assigngrid.enableAutoWidth(true);
    assigngrid.setColAlign("left,right,right");
    assigngrid.setColTypes("ro,ro,ro");
    assigngrid.setSkin("dhx_skyblue");
    assigngrid.preventIECaching(true);
    assigngrid.init();
    assigngrid.attachEvent("onBeforeSelect", function(row,old_row){
        return false;
    });

    function loadGrades(){
        dhxLayout.cells("b").progressOn();
        assigngrid.clearAndLoad("<?php echo $_SERVER["PHP_SELF"];?>/getStudentGrades?unqueId="+(new Date()).valueOf(),function(){
            dhxLayout.cells("b").progressOff();
        });
    }


</script>
