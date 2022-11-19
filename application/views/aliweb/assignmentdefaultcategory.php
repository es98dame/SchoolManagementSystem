<div id="msgResult" style="display:none;top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 10; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #FFD700; border: 1px solid Black;"></div>
<div id="ErrorMsg" style="display: none; top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 999; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #EE82EE; border: 1px solid Black;"></div>
<div id="layoutObj" style="width:100%;height:600px;margin:-16px 0px 0px 0px;;padding:0px;overflow:hidden;"></div>


<script>
    window.onload=loadAssignCat;

    var	dhxLayout=new dhtmlXLayoutObject("layoutObj","1C");
    dhxLayout.cells("a").showHeader();
    dhxLayout.cells("a").setText("<div style=\"font-family: Tahoma; font-size: 15px; font-weight: bold; color: #696969;\">Assignment Categories</div>");

    //=== Grid of Family List ----------------------------------------------------------------
    var grid_assignmentcate = dhxLayout.cells("a").attachGrid();
    grid_assignmentcate.setImagePath("dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
    grid_assignmentcate.setHeader("Name,wpercentage,writer,Created Date");
    grid_assignmentcate.setColumnIds("name,wpercentage,writer,regdate");
    grid_assignmentcate.setInitWidths("200,100,100,150");
    grid_assignmentcate.enableAutoWidth(true);
    grid_assignmentcate.setColAlign("center,center,center,center");
    grid_assignmentcate.setColTypes("ed,ed,ro,ro");
    grid_assignmentcate.setColSorting("str,str,str,date");

    grid_assignmentcate.setSkin("dhx_skyblue");
    grid_assignmentcate.attachEvent("onRowSelect", function(id,ind){});
    grid_assignmentcate.init();
    grid_assignmentcate.entBox.id = "assigncatgridBody";
    grid_assignmentcate.enableSmartRendering(true, 50);
    var myfamilyMenu = new dhtmlXMenuObject();
    myfamilyMenu.renderAsContextMenu();
    grid_assignmentcate.enableContextMenu(myfamilyMenu);
    myfamilyMenu.addContextZone("assigncatgridBody");
    myfamilyMenu.attachEvent("onClick",function(menuitemId,type){
        var selectedId=grid_assignmentcate.getSelectedRowId();
        switch(menuitemId) {
            case "edit_add":
                var newId = (new Date()).valueOf();
                grid_assignmentcate.addRow(newId,["","",gettoday(),gettoday(),0,getfullday(),""],0);
                break;
            case "edit_remove":
                if(selectedId) {
                    if (confirm("Are you sure you want to delete row")) {
                        grid_assignmentcate.deleteSelectedItem();
                        //resetFamily();
                    }
                }
                break;
        }

        return true
    });
    myfamilyMenu.loadStruct("assignmentdefaultcategory/menucontext?unqueId="+(new Date()).valueOf());

    var dpTrimester = new dataProcessor("assignmentdefaultcategory/setAssignDefaultCategory?unqueId="+(new Date()).valueOf()); //lock feed url
    dpTrimester.init(grid_assignmentcate); //link dataprocessor to the grid
    dpTrimester.defineAction("inserted",function(response){
        showfullmsg("msgResult","It has been "+response.textContent+" successfully.");
        grid_assignmentcate.updateFromXML("assignmentdefaultcategory/getAssignDefaultCategory?unqueId="+(new Date()).valueOf());
        return true;
    });
    dpTrimester.defineAction("updated",function(response){
        showfullmsg("msgResult","It has been "+response.textContent+" successfully.");
        //updateGridXml(grid_family,"students/getFamilies");
        return true;
    });
    dpTrimester.defineAction("deleted",function(response){
        showfullmsg("msgResult","It has been "+response.textContent+" successfully.");
        //updateGridXml(grid_family,"students/getFamilies");
        return true;
    });
    dpTrimester.defineAction("invalid",function(response){
        showfullmsg("ErrorMsg","Invalid Data");
        return true;
    });

    function loadAssignCat(){
        grid_assignmentcate.clearSelection();
        var selectedId=grid_assignmentcate.getSelectedRowId();
        var qunq="";
        if(selectedId){
            qunq = "&sno=" + selectedId;
        }
        dhxLayout.cells("a").progressOn();
        grid_assignmentcate.clearAndLoad("assignmentdefaultcategory/getAssignDefaultCategory?unqueId="+(new Date()).valueOf()+qunq,function(){
            dhxLayout.cells("a").progressOff();
        });
    }

</script>