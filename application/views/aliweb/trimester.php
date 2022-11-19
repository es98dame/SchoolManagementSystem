<div id="msgResult" style="display:none;top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 10; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #FFD700; border: 1px solid Black;"></div>
<div id="ErrorMsg" style="display: none; top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 999; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #EE82EE; border: 1px solid Black;"></div>
<div id="layoutObj" style="width:100%;height:600px;margin:-16px 0px 0px 0px;;padding:0px;overflow:hidden;"></div>


<script>
    window.onload=loadTrimester;

    var	dhxLayout=new dhtmlXLayoutObject("layoutObj","1C");
    dhxLayout.cells("a").showHeader();
    dhxLayout.cells("a").setText("<div style=\"font-family: Tahoma; font-size: 15px; font-weight: bold; color: #696969;\">Trimesters</div>");

    //=== Grid of Family List ----------------------------------------------------------------
    var grid_trimester = dhxLayout.cells("a").attachGrid();
    grid_trimester.setImagePath("dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
    grid_trimester.setHeader("Year, GP, Start Day,Eed Day,Active,Created Date,Writer");
    grid_trimester.setColumnIds("schoolyear, gradingperiod,startday,endday,active,created,writer");
    grid_trimester.setInitWidths("100,100,100,100,100,100,100");
    grid_trimester.enableAutoWidth(true);
    grid_trimester.setColAlign("center,center,center,center,center,center,center");
    grid_trimester.setColTypes("co,co,dhxCalendar,dhxCalendar,co,ro,ro");
    grid_trimester.setColSorting("str,str,date,date,int,date,str");
    grid_trimester.getCombo(0).put("2017", "2017");
    grid_trimester.getCombo(0).put("2016-2017", "2016-2017");
    grid_trimester.getCombo(0).put("2016", "2016");
    grid_trimester.getCombo(0).put("2015-2016", "2015-2016");
    grid_trimester.getCombo(0).put("2015", "2015");
    grid_trimester.getCombo(0).put("2014-2015", "2014-2015");
    grid_trimester.getCombo(0).put("2014", "2014");
    grid_trimester.getCombo(0).put("2013-2014", "2013-2014");
    grid_trimester.getCombo(0).put("2013", "2013");
    grid_trimester.getCombo(0).put("2012-2013", "2012-2013");
    grid_trimester.getCombo(0).put("2012", "2012");
    grid_trimester.getCombo(0).put("2011-2012", "2011-2012");
    grid_trimester.getCombo(0).put("2011", "2011");
    grid_trimester.getCombo(0).put("2010-2011", "2010-2011");
    grid_trimester.getCombo(0).put("2010", "2010");
    grid_trimester.getCombo(1).put("1", "1");
    grid_trimester.getCombo(1).put("2", "2");
    grid_trimester.getCombo(1).put("3", "3");
    grid_trimester.getCombo(1).put("4", "4");
    grid_trimester.getCombo(1).put("5", "5");
    grid_trimester.getCombo(1).put("6", "6");
    grid_trimester.getCombo(1).put("7", "7");
    grid_trimester.getCombo(1).put("8", "8");
    grid_trimester.getCombo(1).put("9", "9");
    grid_trimester.getCombo(1).put("10", "10");
    grid_trimester.getCombo(1).put("11", "11");
    grid_trimester.getCombo(1).put("12", "12");
    grid_trimester.getCombo(1).put("13", "13");
    grid_trimester.getCombo(1).put("14", "14");
    grid_trimester.getCombo(1).put("15", "15");
    grid_trimester.getCombo(4).put(1, "Active");
    grid_trimester.getCombo(4).put(0, "InActive");
    grid_trimester.setDateFormat("%Y-%m-%d");
    grid_trimester.setSkin("dhx_skyblue");
    grid_trimester.attachEvent("onRowSelect", function(id,ind){});
    grid_trimester.init();
    grid_trimester.entBox.id = "trimestergridBody";
    grid_trimester.enableSmartRendering(true, 50);
    var myfamilyMenu = new dhtmlXMenuObject();
    myfamilyMenu.renderAsContextMenu();
    grid_trimester.enableContextMenu(myfamilyMenu);
    myfamilyMenu.addContextZone("trimestergridBody");
    myfamilyMenu.attachEvent("onClick",function(menuitemId,type){
        var selectedId=grid_trimester.getSelectedRowId();
        switch(menuitemId) {
            case "edit_add":
                var newId = (new Date()).valueOf();
                grid_trimester.addRow(newId,["","",gettoday(),gettoday(),0,getfullday(),""],0);
                break;
            case "edit_remove":
                if(selectedId) {
                    if (confirm("Are you sure you want to delete row")) {
                        grid_trimester.deleteSelectedItem();
                        //resetFamily();
                    }
                }
                break;
        }

        return true
    });
    myfamilyMenu.loadStruct("trimester/menucontext?unqueId="+(new Date()).valueOf());

    var dpTrimester = new dataProcessor("trimester/setTrimester?unqueId="+(new Date()).valueOf()); //lock feed url
    dpTrimester.init(grid_trimester); //link dataprocessor to the grid
    dpTrimester.defineAction("inserted",function(response){
        showfullmsg("msgResult","It has been "+response.textContent+" successfully.");
        grid_trimester.updateFromXML("trimester/getTrimesters?unqueId="+(new Date()).valueOf());
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

    function loadTrimester(){
        grid_trimester.clearSelection();
        var selectedId=grid_trimester.getSelectedRowId();
        var qunq="";
        if(selectedId){
            qunq = "&sno=" + selectedId;
        }
        dhxLayout.cells("a").progressOn();
        grid_trimester.clearAndLoad("trimester/getTrimesters?unqueId="+(new Date()).valueOf()+qunq,function(){
            dhxLayout.cells("a").progressOff();
        });
    }

</script>