<div id="msgResult" style="display:none;top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 10; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #FFD700; border: 1px solid Black;"></div>
<div id="ErrorMsg" style="display: none; top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 999; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #EE82EE; border: 1px solid Black;"></div>
<div id="layoutObj" style="width:100%;height:600px;margin:-16px 0px 0px 0px;;padding:0px;overflow:hidden;"></div>
<div id="winVP" style="font-family: Tahoma; font-size: 11px; width: 100%; height:100%; overflow: hidden;"></div>

<script>
    //  window.onload=loadRoster;

    var	dhxLayout=new dhtmlXLayoutObject("layoutObj","3T");
    dhxLayout.cells("a").showHeader();
    dhxLayout.cells("a").setText("<div style=\"float:left;font-family: Tahoma; font-size: 15px; font-weight: bold; color: #696969;\">Class Roster</div>");
    dhxLayout.cells("a").setHeight(100);
    dhxLayout.cells("b").showHeader();
    dhxLayout.cells("b").setText("Classes");
    dhxLayout.cells("b").setWidth(300);
    dhxLayout.cells("c").showHeader();
    dhxLayout.cells("c").setText("Class Roster");

    var gpclassfmData = [{type: "settings", position: "label-top",offsetLeft:20, labelWidth:80,inputWidth: "auto"},
        {type: "combo", label: "Year: ",inputWidth:80, name: "schoolyear", required: true, connector:"<?php echo $_SERVER["PHP_SELF"];?>/getComSchoolYear?unqueId="+(new Date()).valueOf() },
        {type: "newcolumn", offset:1},
        {type: "combo", label: "Trimester: ",inputWidth:80, name: "trimester", required: true, options:[
            {text: "", value: "", selected: true},
            {text: "1", value: "1"},
            {text: "2", value: "2"},
            {text: "3", value: "3"}
        ] },
        {type: "newcolumn", offset:1},
        {type: "combo", label: "Level: ",inputWidth:80, name: "level", connector:"<?php echo $_SERVER["PHP_SELF"];?>/getComLevel?unqueId="+(new Date()).valueOf() },
        {type: "newcolumn", offset:1},
        {type: "combo", label: "Session: ",inputWidth:80, name: "session", options:[
            {text: "", value: "", selected: true},
            {text: "AM", value: "1"},
            {text: "AFT", value: "2"},
            {text: "PM", value: "3"}
        ] },
        {type: "newcolumn", offset:1},
        {type: "button", name:"btSearch", value:"Search"},
        {type: "newcolumn", offset:1},
        {type: "button", name:"btCreate", value:"Create Classes"},
        {type: "newcolumn", offset:1},
        {type: "button", name:"btRoster", value:"Assign Roster"} ];

    var	gpclassfm = dhxLayout.cells("a").attachForm(gpclassfmData);
    gpclassfm.setSkin('dhx_skyblue');
    gpclassfm.attachEvent("onButtonClick", function(name,command){
        if(gpclassfm.validate()){
            if(name=='btSearch'){
                loadClasses();
                return true;
            }
            if(name=='btCreate'){
                if(confirm("Are you sure to create the classes?")){
                    var v1 = this.getCombo("schoolyear").getSelectedValue();
                    var v2 = this.getCombo("trimester").getSelectedValue();
                    var posturl = "<?php echo $_SERVER["PHP_SELF"];?>/createClasses";
                    var params = "year="+v1+"&trim="+v2;
                    var mes='';
                    postAjax(posturl,params,function(the_pays){
                        if(BR_n=='msie'){
                            mes = the_pays[0].childNodes[0].childNodes[0].nodeValue;
                        }else{
                            mes = the_pays[0].getElementsByTagName("cell")[0].childNodes[0].nodeValue;
                        }
                        showfullmsg("msgResult",mes);
                        loadClasses();
                    });
                    return true;
                }
                return true;
            }
            if(name=='btRoster'){
                if(confirm("Are you sure to assign the roster?")){
                    var v1 = this.getCombo("schoolyear").getSelectedValue();
                    var v2 = this.getCombo("trimester").getSelectedValue();
                    var posturl = "<?php echo $_SERVER["PHP_SELF"];?>/assignRoster";
                    var params = "year="+v1+"&trim="+v2;
                    var mes='';
                    postAjax(posturl,params,function(the_pays){
                        if(BR_n=='msie'){
                            mes = the_pays[0].childNodes[0].childNodes[0].nodeValue;
                        }else{
                            mes = the_pays[0].getElementsByTagName("cell")[0].childNodes[0].nodeValue;
                        }
                        showfullmsg("msgResult",mes);
                        var rid=classgrid.getSelectedRowId();
                        grid_classgrid.updateFromXML("<?php echo $_SERVER["PHP_SELF"];?>/getAssignRoster?cno="+rid+"&unqueId="+(new Date()).valueOf(),true,true,function(){});
                    });
                    return true;
                }
                return true;
            }
        }
        return false;
    });


    //=== Grid of Family List ----------------------------------------------------------------
    var classnam="";
    var classgrid = dhxLayout.cells("b").attachGrid();
    classgrid.setImagePath("/dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
    classgrid.setHeader("No,Class,Teacher,&nbsp;");
    classgrid.setSkin("dhx_skyblue");
    classgrid.attachEvent("onRowSelect", function(id,ind){
       loadRoster(id);
        classnam = this.cells(id,1).getValue();
    });
    classgrid.attachEvent("onBeforeSorting",function(ind,type,direction){ window.cc_col = ind; window.cc_drt = ((direction == "des") ? "asc": "des"); return true; });
    classgrid.preventIECaching(true);
    classgrid.enableMultiselect(true);
    classgrid.init();
    classgrid.entBox.id = "classgridBody";
    classgrid.enableSmartRendering(true, 50);
    var myrecordMenu = new dhtmlXMenuObject();
    myrecordMenu.renderAsContextMenu();
    classgrid.enableContextMenu(myrecordMenu);
    myrecordMenu.addContextZone("classgridBody");
    myrecordMenu.attachEvent("onClick",function(menuitemId,type){
        var selectedId=classgrid.getSelectedRowId();

        switch(menuitemId) {
            case "removeRow":
                if(selectedId) {
                    if (confirm("Are you sure you want to delete row")) {
                        classgrid.deleteSelectedItem();
                    }
                }
                break;
        }
        return true
    });
    myrecordMenu.loadStruct("<?php echo $_SERVER["PHP_SELF"];?>/menuRosterContext?unqueId="+(new Date()).valueOf());


    var	dpatten = new dataProcessor("<?php echo $_SERVER["PHP_SELF"];?>/setClassList?unqueId="+(new Date()).valueOf());
//    dpatten.setTransactionMode("POST",true);
//    dpatten.setUpdateMode("cell");
//    dpatten.enableDebug(true);
    dpatten.init(classgrid);
    dpatten.attachEvent("onBeforeUpdate",function(id,status, data){ return true; });
    dpatten.defineAction("inserted",function(response){showmsg("msgResult",response); return true; });
    dpatten.defineAction("updated",function(response){ showmsg("msgResult",response); return true; });
    dpatten.defineAction("deleted",function(response){
        var rid=classgrid.getSelectedRowId();
        grid_classgrid.updateFromXML("<?php echo $_SERVER["PHP_SELF"];?>/getAssignRoster?cno="+rid+"&unqueId="+(new Date()).valueOf(),true,true,function(){});
        showmsg("msgResult",response); return true;
    });
    dpatten.defineAction("invalid",function(response){ showmsg("msgResult",response); return true; });

    function loadClasses(){
        var v1 = gpclassfm.getCombo("schoolyear").getSelectedValue();
        var v2 = gpclassfm.getCombo("trimester").getSelectedValue();
        var v4 = gpclassfm.getCombo("level").getSelectedValue();
        var v5 = gpclassfm.getCombo("session").getSelectedValue();
        var oparams = "&year="+v1+"&trim="+v2+"&level="+v4+"&session="+v5;
        dhxLayout.cells("a").progressOn();
        classgrid.clearAndLoad("<?php echo $_SERVER["PHP_SELF"];?>/getClassList?unqueId="+(new Date()).valueOf()+ oparams,function(){
            dhxLayout.cells("a").progressOff();
            grid_classgrid.clearAll();
        });
    }

    //=== Grid of Family List ----------------------------------------------------------------
    var grid_classgrid = dhxLayout.cells("c").attachGrid();
    grid_classgrid.setImagePath("/dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
    grid_classgrid.setHeader("No,Student,progress,Edit");
    grid_classgrid.setColumnIds("seq,fullname,progress,&nbsp;");
    grid_classgrid.setInitWidths("30,200,100,200");
    grid_classgrid.enableAutoWidth(true);
    grid_classgrid.setColAlign("left,left,left,left");
    grid_classgrid.setColTypes("ro,ro,coro,ro");
    grid_classgrid.setColSorting("int,str,str,str");
    grid_classgrid.getCombo(2).put('n', "Initial-Transfer in");
    grid_classgrid.getCombo(2).put('r', "Active");
    grid_classgrid.getCombo(2).put('w', "Withdrawn-Transfer out");
    grid_classgrid.getCombo(2).put('c', "Consultations");
    grid_classgrid.getCombo(2).put('a', "Acceptance");
    grid_classgrid.getCombo(2).put('v', "Vacation");
    grid_classgrid.getCombo(2).put('m', "Med-Leave");
    grid_classgrid.getCombo(2).put('s', "COS-Approved");
    grid_classgrid.getCombo(2).put('d', "Continuing Education");
    grid_classgrid.getCombo(2).put('p', "Initial-COS Approved");
    grid_classgrid.getCombo(2).put('f', "Cancelled");
    grid_classgrid.getCombo(2).put('o', "COS");
    grid_classgrid.getCombo(2).put('e', "Initial-Visa Interview");
    grid_classgrid.getCombo(2).put('t', "Withdrawn-Terminated. No Show");
    grid_classgrid.getCombo(2).put('h', "Withdrawn-AEW");
    grid_classgrid.setSkin("dhx_skyblue");
    grid_classgrid.attachEvent("onRowSelect", function(id,ind){});
    grid_classgrid.attachEvent("onBeforeSorting",function(ind,type,direction){ window.am_col = ind; window.am_drt = ((direction == "des") ? "asc": "des"); return true; });
    grid_classgrid.preventIECaching(true);
    grid_classgrid.init();

    function loadRoster(cno){
        var oparams = "&cno="+cno;
        dhxLayout.cells("c").progressOn();
        grid_classgrid.clearAndLoad("<?php echo $_SERVER["PHP_SELF"];?>/getAssignRoster?unqueId="+(new Date()).valueOf()+ oparams,function(){
            dhxLayout.cells("c").progressOff();
        });
    }

    var formData = [    {type: "settings", position: "label-left",offsetLeft:10, labelWidth: 120,inputWidth: "auto"},
        {type: "input",  className: "css_form1", label: "Class Name",inputWidth:140,readonly:true, name: "classname", required: true, validate: "NotEmpty" },
        {type: "combo", label: "New Class: ",inputWidth:140, name: "newclassno" },
//        {type: "block", offset:10, list:[
//            {type: "checkbox",position: "label-right", label: " Allow update/delete Academic Records", name:"isupdate"}
//        ]},
        {type: "hidden", name:"oldclassno"},
        {type: "hidden", name:"studentno"},
        {type: "hidden", name:"classtype"},
        {type: "hidden", name:"rno"},
        {type: "hidden", name:"id"}
    ];

    var dhxWins = new dhtmlXWindows();
    dhxWins.setSkin("dhx_skyblue");
    var w1 = dhxWins.createWindow("w1",60,120,310,200);
    w1.hide();
    w1.center();
    //w1.button("minmax1").disable();
    w1.setModal(false);
    w1.attachEvent("onClose",function(win){
        if (win.getId() == "w1") {
            win.setModal(false);
            win.hide();
        }
    });
    w1.attachObject("winVP");

    var editForm = w1.attachForm(formData);
    editForm.setSkin('dhx_skyblue');
    editForm.enableLiveValidation(true);
    editForm.attachEvent("onButtonClick", function(name,command){
        if(editForm.validate()){
            if(name=='button_save'){
                //editForm.disableItem(name);
                var ccb= editForm.getCombo("newclassno");
                if(ccb.getComboText()!=""){
                    //alert(editForm.getItemValue('id'));
                   // editForm.getItemValue('id');
                    //editForm.formId = editForm.getItemValue('id');
                    editForm.resetDataProcessor("updated");
                    editForm.save();
                    return true;
                }else{
                    return false;
                }
            }
            if(name=='button_delete'){
                if(confirm('Do you really want to delete it?')){
                    //editForm.disableItem(name);
                    editForm.resetDataProcessor("deleted");
                    editForm.save();
                }
            }
            return true;
        }
        return false;
    });


    var	dpClass = new dataProcessor("<?php echo $_SERVER["PHP_SELF"];?>/setAssignRoster?unqueId="+(new Date()).valueOf()); //lock feed url
    dpClass.init(editForm);
    //dpClass.attachEvent("onBeforeUpdate",function(id,status, data){ return true;	});
    dpClass.defineAction("updated",function(response){
        var rid=classgrid.getSelectedRowId();
        grid_classgrid.updateFromXML("<?php echo $_SERVER["PHP_SELF"];?>/getAssignRoster?cno="+rid+"&unqueId="+(new Date()).valueOf(),true,true,function(){
            w1.setModal(false);
            w1.hide();
            showmsg("msgResult",response);
        });
        return true;
    });
    dpClass.defineAction("deleted",function(response){
        var rid=classgrid.getSelectedRowId();
        grid_classgrid.updateFromXML("<?php echo $_SERVER["PHP_SELF"];?>/getAssignRoster?cno="+rid+"&unqueId="+(new Date()).valueOf(),true,true,function(){
            w1.setModal(false);
            w1.hide();
            showmsg("msgResult",response);
        });
        return true;
    });


    function doOnLoadInfo(rno,sno,cno,classtype){
        editForm.clear();
        editForm.disableItem("teacher_no");
        editForm.removeItem("button_save");
        editForm.removeItem("button_delete");
        editForm.removeItem("btnblock");

        //editForm.formId = rno;
        //editForm.setItemValue("id",rno);
        //editForm.setItemValue("classname",classnam);
       // editForm.setItemValue("classtype",classtype);
       // editForm.setItemValue("studentno",sno);
      //  editForm.setItemValue("oldclassno",cno);
        var btnblock = {type: "block",name:"btnblock", offsetLeft:20 , list:[ {type: "button", value: "Update",offsetTop:5, name: "button_save"},{type: "newcolumn", offset:2},{type: "button", className: "css_button", value: "Delete",offsetTop:5, name: "button_delete"}]};
        editForm.addItem(null,btnblock,19);
        editForm.load("<?php echo $_SERVER["PHP_SELF"];?>/getAssignRosterForm?rno="+rno+"&unqueId="+(new Date()).valueOf());

        var v1 = gpclassfm.getCombo("schoolyear").getSelectedValue();
        var v2 = gpclassfm.getCombo("trimester").getSelectedValue();
        var ccb= editForm.getCombo("newclassno");
        ccb.setComboText("");
        ccb.load("<?php echo $_SERVER["PHP_SELF"];?>/getComClasses?cno="+cno+"&year="+v1+"&trim="+v2+"&classtype="+classtype+"&unqueId="+(new Date()).valueOf());

        w1.setText("Edit Student Roster");
        w1.setModal(true);
        w1.show();
    }

</script>