<div id="msgResult" style="display:none;top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 10; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #FFD700; border: 1px solid Black;"></div>
<div id="ErrorMsg" style="display: none; top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 999; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #EE82EE; border: 1px solid Black;"></div>
<div id="layoutObj" style="width:100%;height:600px;margin:-16px 0px 0px 0px;;padding:0px;overflow:hidden;"></div>
<div id="SubMenuId" style="margin-left:-20px;"><ul style="list-style:none;"><?php echo $param["menus"];?></ul></div>

    <div id="assignm_container" style="padding: 1px 1px 1px 1px; width:100%; height:100%; overflow-x: auto; overflow-y: auto;">
        <div id="assignmbox" style="padding: 0px 0px 0px 0px; width:100%;height:100%; background-color:white;overflow:hidden"></div>
    </div>
    <form action="<?php echo $_SERVER["PHP_SELF"];?>/setflagview" target="upload_area" method="post" name="partmasterform" id="partmasterform" >
        <input type="hidden" name="id">
        <input type="hidden" name="isview">
    </form>
    <iframe name="upload_area" frameBorder="0" height="0"></iframe>
    <script>
        window.onload = loadAssignm;

        //========================= Layout
        var	dhxLayout=new dhtmlXLayoutObject("layoutObj","2U");
        dhxLayout.cells("a").showHeader();
        dhxLayout.cells("a").setText("Class Apps");
        dhxLayout.cells("a").setWidth(160);
        dhxLayout.cells("a").attachObject("SubMenuId");
        dhxLayout.cells("b").showHeader();
        dhxLayout.cells("b").setText("<div style=\"font-family: Tahoma; font-size: 15px; font-weight: bold; color: #696969; float:left;\"><?php echo $param["title"];?></div><div style=\"vertical-align: middle;margin-left:220px;margin-top:-12px;\" id=\"GPClassForm\"></div>");

        var gpclassfmData = [{type: "settings", position: "label-left", labelWidth:0,inputWidth: "auto"},
            {type: "button", name:"btNew", offsetLeft:20, value:"<i class=\"icon-plus\"></i> Add Assignment"} ];
        var	gpclassfm = new dhtmlXForm("GPClassForm", gpclassfmData);
        gpclassfm.setSkin('dhx_skyblue');
        gpclassfm.enableLiveValidation(true);
        gpclassfm.attachEvent("onButtonClick", function(name,command){
            if(name=='btNew'){
                self.location.href="/index.php/aliweb/assignnew?unqueId="+(new Date()).valueOf();
                return true;
            }
            return false;
        });

        //========================= Assignment Lines
        //var assigngrid = new dhtmlXGridObject('assignmbox');
        var assigngrid = dhxLayout.cells("b").attachGrid();
        assigngrid.setImagePath("/dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
        assigngrid.setHeader("Assignment,Duedate,Category,Hidden from Students",null,["text-align:left","text-align:left","text-align:left","text-align:left"]);
        //assigngrid.attachHeader("#text_filter,&nbsp;,#cspan,#cspan,#cspan,#cspan");
        assigngrid.attachHeader("#text_filter,#select_filter,&nbsp;,#cspan");
        assigngrid.setColumnIds("name,duedate,catename,isview");
        assigngrid.setInitWidths("200,120,200,100");
        assigngrid.enableAutoWidth(true);
        assigngrid.setColAlign("left,left,left,left");
        assigngrid.setColTypes("link,ro,ro,ch");
        assigngrid.setColSorting("str,date,str,int");
        //assigngrid.enableMultiline(true);
        assigngrid.setSkin("dhx_skyblue");
        assigngrid.attachEvent("onRowSelect", function(id,ind){});
        assigngrid.attachEvent("onBeforeSorting",function(ind,type,direction){ window.am_col = ind; window.am_drt = ((direction == "des") ? "asc": "des"); return true; });
        assigngrid.preventIECaching(true);
        assigngrid.init();

        function loadAssignm(){
            var oparams = "";
            if(window.am_drt){
                assigngrid.setSortImgState(true, window.am_col, window.am_drt,1);
                oparams="&orderby=" + window.am_col + "&direct=" + window.am_drt;
            }
            dhxLayout.cells("b").progressOn();
            assigngrid.clearAndLoad("<?php echo $_SERVER["PHP_SELF"];?>/getAssignments?unqueId="+(new Date()).valueOf()+ oparams,function(){
                dhxLayout.cells("b").progressOff();
            });
        }


        var	dpatten = new dataProcessor("<?php echo $_SERVER["PHP_SELF"];?>/setAssignments?unqueId="+(new Date()).valueOf()); //lock feed url
        dpatten.setTransactionMode("POST",true); //set mode as send-all-by-post
        dpatten.setUpdateMode("cell"); //disable auto-update
        dpatten.enableDebug(true);
        dpatten.init(assigngrid); //link dataprocessor to the grid
        dpatten.attachEvent("onBeforeUpdate",function(id,status, data){ return true; });
        dpatten.defineAction("inserted",function(response){ showmsg("msgResult",response); return true; });
        dpatten.defineAction("updated",function(response){ showmsg("msgResult",response); return true; });
        dpatten.defineAction("deleted",function(response){ showmsg("msgResult",response); return true; });
        dpatten.defineAction("invalid",function(response){ showmsg("msgResult",response); return true; });


        function SHfromStudents(id,v){
            var fm = document.getElementById("partmasterform");
            fm.id.value = id;
            fm.isview.value = v;
            fm.submit();
        }
    </script>
