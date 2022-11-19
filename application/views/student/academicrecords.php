<div id="msgResult" style="display:none;top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 10; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #FFD700; border: 1px solid Black;"></div>
<div id="ErrorMsg" style="display: none; top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 999; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #EE82EE; border: 1px solid Black;"></div>
<div id="layoutObj" style="width:100%;height:600px;margin:-16px 0px 0px 0px;;padding:0px;overflow:hidden;"></div>
<div id="winVP" style="font-family: Tahoma; font-size: 11px; width: 100%; height:100%; overflow: hidden;"></div>

<script>
    window.onload=loadRecords;

    var	dhxLayout=new dhtmlXLayoutObject("layoutObj","1C");
    dhxLayout.cells("a").showHeader();
    dhxLayout.cells("a").setText("<div style=\"float:left;font-family: Tahoma; font-size: 15px; font-weight: bold; color: #696969;\">Academic Records</div>");

    //=== Grid of Family List ----------------------------------------------------------------


    //var grid_records = new dhtmlXGridObject('recordgrid');
    var grid_records = dhxLayout.cells("a").attachGrid();
    grid_records.setImagePath("/dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
    grid_records.enableColSpan(true);
    grid_records.setDateFormat("%Y-%m-%d");
    grid_records.setSkin("dhx_skyblue");
    grid_records.init();
    grid_records.enableSmartRendering(true, 50);

    function loadRecords(){
        grid_records.clearSelection();
        dhxLayout.cells("a").progressOn();
        grid_records.clearAndLoad("<?php echo $_SERVER["PHP_SELF"];?>/getAcademicRecords?unqueId="+(new Date()).valueOf(),function(){
            dhxLayout.cells("a").progressOff();
        });
    }

</script>