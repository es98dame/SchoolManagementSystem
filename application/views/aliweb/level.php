<div id="msgResult" style="display:none;top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 10; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #FFD700; border: 1px solid Black;"></div>
<div id="ErrorMsg" style="display: none; top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 999; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #EE82EE; border: 1px solid Black;"></div>
<div id="layoutObj" style="width:100%;height:600px;margin:-16px 0px 0px 0px;;padding:0px;overflow:hidden;"></div>


<script>
    window.onload=loadLevel;

    var	dhxLayout=new dhtmlXLayoutObject("layoutObj","1C");
    dhxLayout.cells("a").showHeader();
    dhxLayout.cells("a").setText("<div style=\"font-family: Tahoma; font-size: 15px; font-weight: bold; color: #696969;\">Levels</div>");

    //=== Grid of Family List ----------------------------------------------------------------
    var grid_level = dhxLayout.cells("a").attachGrid();
    grid_level.setImagePath("dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
    grid_level.setHeader("Level Name, Level Value");
    grid_level.setColumnIds("levelname,levelvalue");
    grid_level.setInitWidths("100,100");
    grid_level.enableAutoWidth(true);
    grid_level.setColAlign("center,center");
    grid_level.setColTypes("ed,ed");
    grid_level.setColSorting("str,int");
    grid_level.setSkin("dhx_skyblue");
    grid_level.attachEvent("onRowSelect", function(id,ind){});
    grid_level.init();
    grid_level.entBox.id = "levelgridBody";
    grid_level.enableSmartRendering(true, 50);
    var myfamilyMenu = new dhtmlXMenuObject();
    myfamilyMenu.renderAsContextMenu();
    grid_level.enableContextMenu(myfamilyMenu);
    myfamilyMenu.addContextZone("levelgridBody");
    myfamilyMenu.attachEvent("onClick",function(menuitemId,type){
        var selectedId=grid_level.getSelectedRowId();
        switch(menuitemId) {
            case "edit_add":
                var newId = (new Date()).valueOf();
                grid_level.addRow(newId,["",""],0);
                break;
            case "edit_remove":
                if(selectedId) {
                    if (confirm("Are you sure you want to delete row")) {
                        grid_level.deleteSelectedItem();
                        //resetFamily();
                    }
                }
                break;
        }

        return true
    });
    myfamilyMenu.loadStruct("level/menucontext?unqueId="+(new Date()).valueOf());

    var dpLevel = new dataProcessor("level/setLevel?unqueId="+(new Date()).valueOf()); //lock feed url
    dpLevel.init(grid_level); //link dataprocessor to the grid
    dpLevel.defineAction("inserted",function(response){
        showfullmsg("msgResult","It has been "+response.textContent+" successfully.");
        grid_level.updateFromXML("level/getLevels?unqueId="+(new Date()).valueOf());
        return true;
    });
    dpLevel.defineAction("updated",function(response){
        showfullmsg("msgResult","It has been "+response.textContent+" successfully.");
        //updateGridXml(grid_family,"students/getFamilies");
        return true;
    });
    dpLevel.defineAction("deleted",function(response){
        showfullmsg("msgResult","It has been "+response.textContent+" successfully.");
        //updateGridXml(grid_family,"students/getFamilies");
        return true;
    });
    dpLevel.defineAction("invalid",function(response){
        showfullmsg("ErrorMsg","Invalid Data");
        return true;
    });

    function loadLevel(){
        grid_level.clearSelection();
        var selectedId=grid_level.getSelectedRowId();
        var qunq="";
        if(selectedId){
            qunq = "&sno=" + selectedId;
        }
        dhxLayout.cells("a").progressOn();
        grid_level.clearAndLoad("level/getLevels?unqueId="+(new Date()).valueOf()+qunq,function(){
            dhxLayout.cells("a").progressOff();
        });
    }

</script>