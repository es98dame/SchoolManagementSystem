<div id="msgResult" style="display:none;top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 10; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #FFD700; border: 1px solid Black;"></div>
<div id="ErrorMsg" style="display: none; top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 999; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #EE82EE; border: 1px solid Black;"></div>
<div id="layoutObj" style="width:100%;height:600px;margin:-16px 0px 0px 0px;;padding:0px;overflow:hidden;"></div>
<div id="SubMenuId" style="margin-left:-20px;"><ul style="list-style:none;"><?php echo $param["menus"];?></ul></div>

<div id="view_container" style="display:none;margin-top:20px;margin-left:20px;width:100%; height:100%; overflow-x: auto; overflow-y: auto;">
    <div id="viewbox" style="width:100%;height:100%; background-color:white;"></div>
</div>

<div id="winMG"></div>

<script>
    window.onload=loadClassTeachers;

    //========================= Layout
    var	dhxLayout=new dhtmlXLayoutObject("layoutObj","2U");
    dhxLayout.cells("a").showHeader();
    dhxLayout.cells("a").setText("Remediation Class");
    dhxLayout.cells("a").setWidth(160);
    dhxLayout.cells("a").attachObject("SubMenuId");
    dhxLayout.cells("b").showHeader();
    dhxLayout.cells("b").setText("<div style=\"font-family: Tahoma; font-size: 15px; font-weight: bold; color: #696969; float:left;\"><?php echo $param["title"];?></div><div style=\"vertical-align: middle;margin-left:220px;margin-top:-12px;\" id=\"GPClassForm\"></div>");


    var gpclassfmData = [{type: "settings", position: "label-left", labelWidth:0,inputWidth: "auto"},
        {type: "button", name:"btNew", offsetLeft:20, value:"<i class=\"icon-plus\"></i> Add Teacher"} ];
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

    //========================= discussion Lines
    var discgrid = dhxLayout.cells("b").attachGrid();
    discgrid.setImagePath("/dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
    discgrid.setHeader("Full Name,Permissions,Primary,Last Login,&nbsp;",null,["text-align:left","text-align:center","text-align:left","text-align:left","text-align:left"]);
    discgrid.setColumnIds("teachername,permission,isprimary,logindate,&nbsp;");
    discgrid.setInitWidths("200,100,100,120,100");
    discgrid.setColAlign("left,center,center,center,center");
    discgrid.setColTypes("ro,ro,ro,ro,ro");
    discgrid.setColSorting("str,int,int,date,str");
    discgrid.setSkin("dhx_skyblue");
    discgrid.attachEvent("onRowSelect", function(id,ind){});
    discgrid.attachEvent("onBeforeSorting",function(ind,type,direction){ window.ct_col = ind; window.ct_drt = ((direction == "des") ? "asc": "des"); return true; });
    discgrid.init();
    function loadClassTeachers(){
        var oparams = "";
        if(window.ct_drt){
            discgrid.setSortImgState(true, window.ct_col, window.ct_drt,1);
            oparams="&orderby=" + window.ct_col + "&direct=" + window.ct_drt;
        }
        dhxLayout.cells("b").progressOn();
        discgrid.clearAndLoad("<?php echo $_SERVER["PHP_SELF"];?>/getRClassTeachers?unqueId="+(new Date()).valueOf()+ oparams,function(){
            dhxLayout.cells("b").progressOff();
        });
    }

    var SendformData = [    {type: "settings", position: "label-left",offsetLeft:25,offsetTop:5, labelWidth: 80,inputWidth: "auto"},
        {type: "combo",  className: "css_form1", label: "Teachers: ",inputWidth:200, name: "teacher_no",id: "teacher_no", connector:"<?php echo $_SERVER["PHP_SELF"];?>/getComRClassTeachers?mod=insert&unqueId="+(new Date()).valueOf(), required: true },
        {type: "select", className: "css_form1", label: "Permission",name: "permission", options: [{ value:"1",text: "Full moderator"}] },
        {type: "checkbox",labelWidth: 200, position: "label-right", label: "Primary", name:"isprimary"},
        {type: "hidden", name: "class_no", value:<?php echo $param["classno"];?>},
        {type: "hidden", name: "teachername"},
        {type: "hidden", name: "oldprimary"},
        {type: "hidden", name:"id"}
    ];

    var dhxWins2 = new dhtmlXWindows();
    dhxWins2.setSkin("dhx_skyblue");
    var w2 = dhxWins2.createWindow("w2",320,200, 320, 240);
    w2.hide();
    //w2.center();
    w2.button("minmax1").disable();
    w2.setModal(false);
    w2.attachEvent("onClose",function(win){
        if (win.getId() == "w2") {
            win.setModal(false);
            win.hide();
        }
    });

    var editForm2 = new dhtmlXForm("viewbox",SendformData);
    editForm2.setSkin('dhx_skyblue');
    editForm2.enableLiveValidation(true);
    editForm2.attachEvent("onButtonClick", function(name,command){
        if(editForm2.validate()){
            if(name=='button_delete'){
                if(confirm('Do you really want to delete it?')){
                    editForm2.disableItem(name);
                    editForm2.resetDataProcessor("deleted");
                    editForm2.save();
                }
            }
            if(name=='button_save'){
                var cbClassteacher = editForm2.getCombo("teacher_no");
                var ctval =  cbClassteacher.getSelectedText();
                editForm2.setItemValue("teachername",ctval);
                editForm2.disableItem(name);
                editForm2.resetDataProcessor("updated");
                editForm2.save();
            }
            if(name=='button_new'){
                var cbClassteacher = editForm2.getCombo("teacher_no");
                var ctval =  cbClassteacher.getSelectedText();
                editForm2.setItemValue("teachername",ctval);
                editForm2.disableItem(name);
                editForm2.resetDataProcessor("inserted");
                editForm2.save();
            }
            return true;
        }
        return false;
    });

    w2.attachObject("view_container");

    var	dpTeacher = new dataProcessor("<?php echo $_SERVER["PHP_SELF"];?>/setRClassTeacher?unqueId="+(new Date()).valueOf()); //lock feed url
    dpTeacher.setTransactionMode("POST",true); //set mode as send-all-by-post
    dpTeacher.enableDebug(true);
    dpTeacher.init(editForm2);
    dpTeacher.attachEvent("onBeforeUpdate",function(id,status, data){ return true;	});
    dpTeacher.defineAction("updated",function(response){
        discgrid.updateFromXML("<?php echo $_SERVER["PHP_SELF"];?>/getRClassTeachers?unqueId="+(new Date()).valueOf(),true,true,function(){
            w2.setModal(false);
            w2.hide();
            showmsg("msgResult",response);
        });
        return true;
    });
    dpTeacher.defineAction("inserted",function(response){
        discgrid.updateFromXML("<?php echo $_SERVER["PHP_SELF"];?>/getRClassTeachers?unqueId="+(new Date()).valueOf(),true,true,function(){
            w2.setModal(false);
            w2.hide();
            showmsg("msgResult",response);
        });
        return true;
    });
    dpTeacher.defineAction("deleted",function(response){
        discgrid.updateFromXML("<?php echo $_SERVER["PHP_SELF"];?>/getRClassTeachers?unqueId="+(new Date()).valueOf(),true,true,function(){
            w2.setModal(false);
            w2.hide();
            showmsg("msgResult",response);
        });
        return true;
    });




    function showNewWindow(){
        editForm2.clear();
        editForm2.setItemValue("class_no",<?php echo $param["classno"];?>);
        var combo1 = editForm2.getCombo("teacher_no");
        combo1.clearAll();
        combo1.setComboText("");
        w2.progressOn();
        combo1.load("<?php echo $_SERVER["PHP_SELF"];?>/getComRClassTeachers?mod=insert&classno=<?php echo $param["classno"];?>&teacher_no=&unqueId="+(new Date()).valueOf(),function(){
            w2.progressOff();
            this.setComboValue("");
        });

        editForm2.removeItem("button_save");
        editForm2.removeItem("button_new");
        editForm2.removeItem("button_delete");
        var btninsert = {type: "button", value:"Insert", name: "button_new", offsetLeft:100};
        editForm2.addItem(null,btninsert,19);

        w2.setText("New Teacher");
        w2.setModal(true);
        w2.show();
    }

    function doOnLoadInfo(sno,tno,pno){
        editForm2.clear();
        var combo2 = editForm2.getCombo("teacher_no");
        combo2.clearAll();
        combo2.setComboText("");
        w2.progressOn();
        combo2.load("<?php echo $_SERVER["PHP_SELF"];?>/getComRClassTeachers?mod=update&classno=<?php echo $param["classno"];?>&teacher_no="+tno+"&unqueId="+(new Date()).valueOf(),function(){
            w2.progressOff();
            this.setComboValue(tno);
        });

        editForm2.setItemValue("id",sno);
        editForm2.setItemValue("oldprimary",pno);
        editForm2.setItemValue("class_no",<?php echo $param["classno"];?>);

        editForm2.removeItem("button_save");
        editForm2.removeItem("button_new");
        editForm2.removeItem("button_delete");

        var btnblock = {type: "block",name:"btnblock", offsetLeft:6 , list:[ {type: "button", value: "Update",offsetTop:5, name: "button_save"},{type: "newcolumn", offset:2},{type: "button", className: "css_button", value: "Delete",offsetTop:5, name: "button_delete"}]};
        editForm2.addItem(null,btnblock,19);
        editForm2.load("<?php echo $_SERVER["PHP_SELF"];?>/getRClassTeacher?id="+sno+"&unqueId="+(new Date()).valueOf());
        w2.setText("Edit Teacher");
        w2.setModal(true);
        w2.show();
    }

    function myCallBack(message) {
        //w2.setModal(false);
        //w2.hide();
        var div = document.createElement('div');
        div.textContent = message;
        showmsg("msgResult",div);
        discgrid.updateFromXML("<?php echo $_SERVER["PHP_SELF"];?>/getRClassTeacher?unqueId="+(new Date()).valueOf(),true,true,function(){
            w2.setModal(false);
            w2.hide();
        });
    }


</script>
