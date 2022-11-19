<div id="msgResult" style="display:none;top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 10; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #FFD700; border: 1px solid Black;"></div>
<div id="ErrorMsg" style="display: none; top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 999; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #EE82EE; border: 1px solid Black;"></div>
<div id="layoutObj" style="width:100%;height:600px;margin:-16px 0px 0px 0px;;padding:0px;overflow:hidden;"></div>


<div id="files_container">
<form action="uploadgrades/setImportFile" method="post" enctype="multipart/form-data" name="realForm" id="realForm" target="upload_area"><div id="dhxForm"></div></form>
</div>
<iframe name="upload_area" frameBorder="0" height="0"></iframe>

<div id="classall_container" style="display: none;"></div>
<div id="studentid_container" style="display: none;"></div>

<script>
    window.dhx_globalImgPath = "dhtmlxSuite/sources/dhtmlxCombo/codebase/imgs/";

    var dhxLayout;
    dhxLayout=new dhtmlXLayoutObject("layoutObj","3J");
    dhxLayout.cells("a").setWidth(460);
    dhxLayout.cells("a").setHeight(260);
    dhxLayout.cells("a").setText("Import Engrade Files (Grade)");
    dhxLayout.cells("b").setText("Check Engrade Data (Grade)  <input type=\"button\" name=\"selectall\" value=\"Select All\" style=\"font-size:10px;\" onClick=\"datagrid.selectAll();\"> <input type=\"button\" name=\"delete\" value=\"Delete\" style=\"font-size:10px;\" onClick=\"javascript:if(confirm('Are you sure you want to delete row')) { datagrid.deleteSelectedRows();} \"> <input type=\"button\" name=\"deleteall\" value=\"Delete All\" style=\"font-size:10px;\" onClick=\"setData('delall')\"> <input type=\"button\" name=\"repairdata\" value=\"Repair\" style=\"font-size:10px;\" onClick=\"setData('repair')\"> <input type=\"button\" name=\"importresult\" id=\"importresult\" value=\"ImportToDB\" style=\"font-size:10px;\" onClick=\"setData('import')\">");
    dhxLayout.cells("a").showHeader();
    dhxLayout.cells("b").showHeader();
    dhxLayout.cells("c").hideHeader();

    var formData = [{ type: "settings", position: "label-left", labelWidth: 100, inputWidth: 200},
        {type: "fieldset",label: "Import Files", inputWidth: "auto", list: [
            {type: "combo",  label: "Trimester : ", name: "trimester_no", id:"trimester_no", connector: "uploadgrades/getTrimestersCombo",required: true },
            {type: "input",  label: "Title : ",value:"", name:"title",required: true },
            {type: "file", name: "uploadfile", inputWidth: "330"},
            {type: "block", style:"font-size:10px; text-align:center;",width: 300, offsetTop:0, list:[
                {type: "button",name: "upload",value: "Upload"} ]}
        ]}
    ];
    var myFormAdd = new dhtmlXForm("dhxForm", formData);
    //var myFormAdd =dhxLayout.cells("a").attachForm(formData);
    myFormAdd.attachEvent("onChange", function (name, value, is_checked){
        if(name=='trimester_no'){
            var cb = this.getCombo("trimester_no");
            var sno = cb.getSelectedValue();
            loadFiles(sno);
            datagrid.clearAll();
            this.setItemValue("title", "");
        }
    });
    myFormAdd.attachEvent("onButtonClick", function(name,command){
        if (name == "upload") {
            document.getElementById("realForm").submit();
            myFormAdd.disableItem("upload");
        }
    });

    function myCallBack() {
        var cb = myFormAdd.getCombo("trimester_no");
        var sno = cb.getSelectedValue();
        loadFiles(sno);
        myFormAdd.enableItem("upload");
    }
    dhxLayout.cells("a").attachObject("files_container");

    var filesgrid = dhxLayout.cells("c").attachGrid();
    filesgrid.setHeader("No,&nbsp;,Title,File,Active,Writer,Write date");
    filesgrid.setColumnIds("file_no,trimester_no,title,filename,active,writer,created");
    filesgrid.setInitWidths("30,0,140,140,60,60,100");
    filesgrid.enableAutoWidth(true);
    filesgrid.setColAlign("center,left,left,left,left,left,left");
    filesgrid.setColTypes("ro,ro,ed,link,coro,ro,ro");
    filesgrid.setColSorting("int,int,str,str,int,str,date");
    filesgrid.getCombo(4).put(1, "Available");
    filesgrid.getCombo(4).put(2, "Imported");
    filesgrid.setSkin("dhx_skyblue");
    filesgrid.attachEvent("onRowSelect", function(id,ind){
        if(ind == 4)
            this.cells(id, 1).setDisabled(true);

    });
    filesgrid.attachEvent("onBeforeSorting",function(ind,type,direction){
        window.fg_col = ind;
        window.fg_drt = ((direction == "des") ? "asc": "des");
        return true;
    });
    filesgrid.attachEvent("onBeforeContextMenu", function(id,ind,obj){
        var stat = filesgrid.cells(id,4).getValue();
        if(stat==2){
            myfamilyMenu.setItemDisabled("import");
            myfamilyMenu.setItemEnabled("remove");
        }else{
            myfamilyMenu.setItemEnabled("import");
            myfamilyMenu.setItemEnabled("remove");
        }
        return true;
    });
    filesgrid.init();
    filesgrid.entBox.id = "filegridBody";
    filesgrid.enableSmartRendering(true, 50);
    var myfamilyMenu = new dhtmlXMenuObject();
    myfamilyMenu.renderAsContextMenu();
    filesgrid.enableContextMenu(myfamilyMenu);
    myfamilyMenu.addContextZone("filegridBody");
    myfamilyMenu.attachEvent("onClick",function(menuitemId,type){
        var rowID = filesgrid.getSelectedId();
        var gno = filesgrid.cells(rowID,1).getValue();
        var selectedId=filesgrid.getSelectedRowId();
        switch(menuitemId){
            case "remove":
                if(selectedId){
                    if (confirm("Are you sure you want to delete row")) {
                        filesgrid.deleteSelectedItem();
                        return true;
                    }else{
                        return false;
                    }
                }else{
                    alert('select a row');
                    return false;
                }
                break;
            case "import":
                if(selectedId){
                    if (confirm("Are you sure you want to import temp data")) {
                        var posturl = "uploadgrades/setImportTemps", params = "fno="+selectedId+"&gno="+gno;
                        dhxLayout.cells("b").progressOn();
                        dhx4.ajax.post(posturl,params, function(r){
                            var xml = r.xmlDoc.responseXML;
                            var nodes = xml.getElementsByTagName("item");
                            var result = nodes[0].getAttribute("value");
                            if(result>0){
                                loadData(selectedId);
                                dhxLayout.cells("b").progressOff();
                                return true;
                            }else{
                                showfullmsg("ErrorMsg","Error by File");
                                dhxLayout.cells("b").progressOff();
                                return false;
                            }
                        });
                        return false;
                    } else {
                        return false;
                    }
                }else{
                    alert('select a row');
                    return false;
                }
                break;
        }
    });
    myfamilyMenu.loadStruct("uploadgrades/menucontextUploadGrade?unqueId="+(new Date()).valueOf());

    function loadFiles(gno){
        var oparams = "";
        if(window.fg_drt){
            filesgrid.setSortImgState(true, window.fg_col, window.fg_drt,1);
            oparams="&orderby=" + window.fg_col + "&direct=" + window.fg_drt;
        }

        filesgrid.clearAndLoad("uploadgrades/getImportFiles?gno="+gno+"&unqueId="+(new Date()).valueOf()+ oparams,function(){
            //loadclassall(gno);
            //loadstudentid();
        });
    }

    var	myDataProcessor = new dataProcessor("uploadgrades/setImportFiles?unqueId="+(new Date()).valueOf());
    myDataProcessor.init(filesgrid);
    myDataProcessor.defineAction("updated",function(response){
        showfullmsg("msgResult","It has been "+response.textContent+" successfully.");
        filesgrid.updateFromXML("uploadgrades/getImportFiles?unqueId="+(new Date()).valueOf());
        return true;
    });
    myDataProcessor.defineAction("deleted",function(response){
        showfullmsg("msgResult","It has been "+response.textContent+" successfully.");
        filesgrid.updateFromXML("uploadgrades/getImportFiles?unqueId="+(new Date()).valueOf());
        return true;
    });
    myDataProcessor.defineAction("invalid",function(response){
        showfullmsg("ErrorMsg","Invalid Data");
        return true;
    });

    var datagrid = dhxLayout.cells("b").attachGrid();
    datagrid.setHeader("No,ClassId,Year,GP,ClassName,Teacher,First,Last,StudentId,Grade,Percent,Missing,Comment,Writer,Created");
    datagrid.attachHeader("&nbsp;,#text_filter,#select_filter,#select_filter,#select_filter,#select_filter,#text_filter,#text_filter,#text_filter,#select_filter,#text_filter,&nbsp;,&nbsp;,&nbsp;,&nbsp;");
    datagrid.setColumnIds("file_no,engradeclassid,classschoolyear,classgradingperiod,classname,teachername,studentfirst,studentlast,studentid,grade,percent,missing,teachercomment,writer,regdate");
    datagrid.setInitWidths("30,100,40,30,100,90,70,70,100,60,60,60,80,80,100");
    datagrid.enableAutoWidth(true);
    datagrid.setColAlign("left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
    datagrid.setColTypes("ro,ro,ro,ro,ro,ro,co,ro,ro,ro,ed,co,ro,ro,ro");
    datagrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str,str,date");
    datagrid.setSkin("dhx_skyblue");
    datagrid.attachEvent("onBeforeSorting",function(ind,type,direction){
        window.dg_col = ind;
        window.dg_drt = ((direction == "des") ? "asc": "des");
        return true;
    });
    datagrid.init();

    function loadData(fno){
        filesgrid.selectRowById(fno);
        if(fno){
            var oparams = "";
            if(window.dg_drt){
                datagrid.setSortImgState(true, window.dg_col, window.dg_drt,1);
                oparams="&orderby=" + window.dg_col + "&direct=" + window.dg_drt;
            }
            dhxLayout.cells("b").progressOn();
            datagrid.clearAndLoad("uploadgrades/getEGGrades?fno="+fno+"&unqueId="+(new Date()).valueOf()+ oparams,function(){
                dhxLayout.cells("b").progressOff();
            });
        }
    }
/*
    var	myDataProcessor2 = new dataProcessor("uploadgrades/setImportData?unqueId="+(new Date()).valueOf()); //lock feed url
    myDataProcessor2.setTransactionMode("POST",true); //set mode as send-all-by-post
    myDataProcessor2.setUpdateMode("cell"); //disable auto-update
    myDataProcessor2.enableDebug(true);
    myDataProcessor2.init(datagrid); //link dataprocessor to the grid
    myDataProcessor2.defineAction("inserted",function(response){
        if(response.textContent){ alert(response.textContent);  }else{ alert(response.text); }
        return true;
    });
    myDataProcessor2.defineAction("updated",function(response){
        if(response.textContent){ alert(response.textContent);  }else{ alert(response.text); }
        return true;
    });
    myDataProcessor2.defineAction("deleted",function(response){
        if(response.textContent){  }else{ }
        return true;
    });
    myDataProcessor2.defineAction("invalid",function(response){
        if(response.textContent){ alert(response.textContent);  }else{ alert(response.text); }
        return true;
    });


    function setData(menuitemId) {
        var totalrows=datagrid.getRowsNum();
        var question="";

        if(totalrows>0){
            var rowID=datagrid.getRowId(0);
            var fno = datagrid.cells(rowID,0).getValue();

            switch(menuitemId) {
                case "import":
                    question = "Are you sure you want to import data";
                    break;
                case "repair":
                    question = "Are you sure you want to repair data";
                    break;
                case "delall":
                    question = "Are you sure you want to delete data all";
                    break;
            }
            if (confirm(question)) {
                var posturl = "uploadgrades/setUpdateData.php", params = "mode="+menuitemId+"&fno="+fno;
                postAjax(posturl,params,function(the_pays){
                    if(BR_n=='msie'){
                        alert(the_pays[0].childNodes[0].childNodes[0].nodeValue);
                    }else{
                        alert(the_pays[0].getElementsByTagName("sumtard")[0].childNodes[0].nodeValue);
                    }
                    loadData(fno);
                });
                return true;
            } else {
                return false;
            }

        }else{
            alert('select a row');
            return false;
        }
    }
    function addcombos(comb,obj){
        var id = obj.first();
        while(id){
            var data = obj.get(id);
            comb.put(id,data.value_name);
            id = obj.next(id);
        }
    }
    function loadclassall(sno){
        var combo1 = datagrid.getCombo(6);
        classdata.clearAll();
        classdata.load("uploadgrades/getClasswDV.php?gno="+sno,function(){
            addcombos(combo1,this);
        });
    }
    function loadstudentid(){
        var combo1 = datagrid.getCombo(11);
        stdiddata.clearAll();
        stdiddata.load("uploadgrades/getStudentIDwDV.php",function(){
            addcombos(combo1,this);
        });
    }
    var	classdata = new dhtmlXDataView({
        container:"classall_container",
        type:{ template:"<span class='dhx_strong'>#value_name#</span>",css:"",width:0,height:0,border:0,padding:0,margin:0 }
    });
    var	stdiddata = new dhtmlXDataView({
        container:"studentid_container",
        type:{ template:"<span class='dhx_strong'>#value_name#</span>",css:"",width:0,height:0,border:0,padding:0,margin:0 }
    });
 */

</script>
