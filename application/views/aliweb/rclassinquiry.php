<div id="msgResult" style="display:none;top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 10; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #FFD700; border: 1px solid Black;"></div>
<div id="ErrorMsg" style="display: none; top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 999; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #EE82EE; border: 1px solid Black;"></div>
<div id="layoutObj" style="width:100%;height:600px;margin:-16px 0px 0px 0px;;padding:0px;overflow:hidden;"></div>
<div id="winVP" style="font-family: Tahoma; font-size: 11px; width: 100%; height:100%; overflow: hidden;"></div>

<script>
    window.onload=loadClassInquiry;

    var	dhxLayout=new dhtmlXLayoutObject("layoutObj","1C");
    dhxLayout.cells("a").showHeader();
    dhxLayout.cells("a").setText("<div style=\"float:left;font-family: Tahoma; font-size: 15px; font-weight: bold; color: #696969;\">Remediation Class Inquiry</div> <div style=\"vertical-align: middle;margin-left:220px;margin-top:-12px;\" id=\"GPClassForm\"></div>");

    var gpclassfmData = [{type: "settings", position: "label-left", labelWidth:0,inputWidth: "auto"},
        {type: "button", name:"btNew", offsetLeft:20, value:"<i class=\"icon-plus\"></i> Add Class"}
    ];

    var	gpclassfm = new dhtmlXForm("GPClassForm", gpclassfmData);
    gpclassfm.setSkin('dhx_skyblue');
    gpclassfm.enableLiveValidation(true);
    gpclassfm.attachEvent("onButtonClick", function(name,command){
        if(name=='btNew'){
            showNewWindow();
            return true;
        }
        return false;
    });


    //=== Grid of Family List ----------------------------------------------------------------
    var grid_classgrid = dhxLayout.cells("a").attachGrid();
    grid_classgrid.setImagePath("dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
    grid_classgrid.setHeader("Year,Trimester,Class,Instructor,Edit",null,["text-align:center;","text-align:center;","text-align:center;","text-align:center;","text-align:center;"]);
    grid_classgrid.attachHeader("#select_filter,#select_filter,#text_filter,#select_filter,&nbsp;");
    grid_classgrid.setColumnIds("schoolyear,trimester,classname,teachername,&nbsp;");
    grid_classgrid.setInitWidths("100,100,180,180,160");
    grid_classgrid.enableAutoWidth(true);
    grid_classgrid.setColAlign("center,center,center,center,center");
    grid_classgrid.setColTypes("ro,ro,link,ro,ro");
    grid_classgrid.setColSorting("str,str,str,str,str");
    grid_classgrid.setSkin("dhx_skyblue");
    grid_classgrid.attachEvent("onRowSelect", function(id,ind){});
    grid_classgrid.attachEvent("onBeforeSorting",function(ind,type,direction){ window.class_col = ind; window.class_drt = ((direction == "des") ? "asc": "des"); return true; });
    grid_classgrid.init();

    function loadClassInquiry(){
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


    var formData = [    {type: "settings", position: "label-left", labelWidth: 120,inputWidth: "auto"},
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
        {type: "hidden", name:"classtype", value:""},
        {type: "input",  className: "css_form1", label: "Class Name",inputWidth:200,name: "name", required: true, validate: "NotEmpty" },
        {type: "combo",  className: "css_form1", label: "Teachers: ",inputWidth:200, name: "teacher_no",id: "teacher_no", connector:"<?php echo $_SERVER["PHP_SELF"];?>/getComTeachers?unqueId="+(new Date()).valueOf(), required: true },
        {type: "select", className: "css_form1", label: "Room",name: "room_no", options: [{ value: "1",text: "Texas"}, {value: "2",text: "Florida" }] },
        {type: "select", className: "css_form1", label: "Status",name: "status", options: [{ value:"0",text: "Open"}, {value:"9",text: "Close" }] },
        {type: "hidden", name:"id", value:""}
    ];

    var dhxWins = new dhtmlXWindows();
    dhxWins.setSkin("dhx_skyblue");
    //dhxWins.attachViewportTo("winVP");
    var w1 = dhxWins.createWindow("w1", 320, 200, 400, 400);
    w1.hide();
    //w1.center();
    w1.button("minmax1").disable();
    w1.setModal(false);
    w1.attachEvent("onClose",function(win){
        if (win.getId() == "w1") {
            win.setModal(false);
            win.hide();
        }
    });

    var editForm = w1.attachForm(formData);
    editForm.setSkin('dhx_skyblue');
    editForm.enableLiveValidation(true);
    editForm.attachEvent("onButtonClick", function(name,command){
        if(editForm.validate()){
            if(name=='button_save'){
                editForm.disableItem(name);
                editForm.resetDataProcessor("updated");
                editForm.save();
            }
            if(name=='button_new'){
                editForm.disableItem(name);
                editForm.resetDataProcessor("inserted");
                editForm.save();
            }
            if(name=='button_delete'){
                if(confirm('Do you really want to delete it?')){
                    editForm.disableItem(name);
                    editForm.resetDataProcessor("deleted");
                    editForm.save();
                }
            }
            return true;
        }
        return false;
    });

    var	dpClass = new dataProcessor("<?php echo $_SERVER["PHP_SELF"];?>/setRClass?unqueId="+(new Date()).valueOf()); //lock feed url
    dpClass.setTransactionMode("POST",true); //set mode as send-all-by-post
    dpClass.enableDebug(true);
    dpClass.init(editForm);
    dpClass.attachEvent("onBeforeUpdate",function(id,status, data){ return true;	});
    dpClass.defineAction("updated",function(response){
        grid_classgrid.updateFromXML("<?php echo $_SERVER["PHP_SELF"];?>/getRClasses?unqueId="+(new Date()).valueOf(),true,true,function(){
            w1.setModal(false);
            w1.hide();
            showmsg("msgResult",response);
        });
        return true;
    });
    dpClass.defineAction("inserted",function(response){
        grid_classgrid.updateFromXML("<?php echo $_SERVER["PHP_SELF"];?>/getRClasses?unqueId="+(new Date()).valueOf(),true,true,function(){
            w1.setModal(false);
            w1.hide();
            showmsg("msgResult",response);
        });
        return true;
    });
    dpClass.defineAction("deleted",function(response){
        grid_classgrid.updateFromXML("<?php echo $_SERVER["PHP_SELF"];?>/getRClasses?unqueId="+(new Date()).valueOf(),true,true,function(){
            w1.setModal(false);
            w1.hide();
            showmsg("msgResult",response);
        });
        return true;
    });
    function showNewWindow(){
        editForm.clear();
        editForm.removeItem("button_save");
        editForm.removeItem("button_new");
        editForm.removeItem("button_delete");
        editForm.enableItem("teacher_no");
        var btninsert = {type: "button", value:"Insert", name: "button_new", offsetLeft:160};
        editForm.addItem(null,btninsert,19);
        w1.setText("New Class");
        w1.setModal(true);
        w1.show();
    }
    function doOnLoadInfo(sno){
        editForm.clear();
        editForm.disableItem("teacher_no");
        editForm.removeItem("button_save");
        editForm.removeItem("button_new");
        editForm.removeItem("button_delete");
        var btnupdate = {type: "button", value:"Update", name: "button_save", offsetLeft:160};
        editForm.addItem(null,btnupdate,19);
        var btndelete = {type: "button", value:"Delete", name: "button_delete", offsetLeft:160};
        editForm.addItem(null,btndelete,20);
        editForm.load("<?php echo $_SERVER["PHP_SELF"];?>/getRClass?id="+sno+"&unqueId="+(new Date()).valueOf());
        w1.setText("Edit Class");
        w1.setModal(true);
        w1.show();
    }


</script>