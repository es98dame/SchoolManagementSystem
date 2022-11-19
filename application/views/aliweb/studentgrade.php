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
        dhxLayout.cells("a").setWidth(160);
        dhxLayout.cells("a").attachObject("SubMenuId");
        dhxLayout.cells("b").showHeader();
        dhxLayout.cells("b").setText("<div style=\"font-family: Tahoma; font-size: 15px; font-weight: bold; color: #696969; float:left;\"><?php echo $param["title"];?></div><div style=\"vertical-align: middle;margin-left:220px;margin-top:-12px;\" id=\"PrintForm\"></div>");

        var printfmData = [{type: "settings", position: "label-left", labelWidth:0,inputWidth: "auto"},
            {type: "button", name:"button_print", offsetLeft:20, value:"Print"} ];
        var	printfm = new dhtmlXForm("PrintForm", printfmData);
        printfm.setSkin('dhx_skyblue');
        printfm.enableLiveValidation(true);
        printfm.attachEvent("onButtonClick", function(name,command){
            if(name=='button_print'){
                window.open("<?php echo $_SERVER["PHP_SELF"];?>/printgrade?stno=<?php echo $param['stno'];?>&unqueId="+(new Date()).valueOf(),"winname","directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=720,height=800");
                return true;
            }
            return false;
        });


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
            assigngrid.clearAndLoad("<?php echo $_SERVER["PHP_SELF"];?>/getStudentGrades?stno=<?php echo $stno;?>&unqueId="+(new Date()).valueOf(),function(){
                dhxLayout.cells("b").progressOff();
            });
        }


    </script>
