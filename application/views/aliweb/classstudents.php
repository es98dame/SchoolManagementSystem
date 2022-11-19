<div id="msgResult" style="display:none;top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 10; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #FFD700; border: 1px solid Black;"></div>
<div id="ErrorMsg" style="display: none; top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 999; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #EE82EE; border: 1px solid Black;"></div>
<div id="layoutObj" style="width:100%;height:600px;margin:-16px 0px 0px 0px;;padding:0px;overflow:hidden;"></div>
<div id="SubMenuId" style="margin-left:-20px;"><ul style="list-style:none;"><?php echo $param["menus"];?></ul></div>

    <div id="schoolroster" style="padding:0px;width:100%; height:100%; overflow: auto;">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr><td> <font style="font-size:11px; color:grey;">School Roster</font></td><td> <font style="font-size:11px; color:grey;">Class Roster</font></td></tr>
            <tr><td height="300" width="50%">
                    <div id="studentgridbox" style="padding: 0px;width:100%;height:100%; background-color:white;overflow:hidden"></div>
                </td><td height="300" width="50%">
                    <div id="rostergridbox" style="padding: 0px;width:100%;height:100%; background-color:white;overflow:hidden"></div>
                </td></tr>
            <tr><td align="right"><input type="text" name="search"  id="search" value=""> <input type="button" name="df" value="Search" onClick="loadStudent(1);"></td><td align="right"><div id='crForm'></div></td></tr>
        </table>
    </div>

    <div id="view_container" style="display:none;margin-top:20px;margin-left:20px;width:600px; height:400px; overflow: hidden;">
        <form action="<?php echo $_SERVER["PHP_SELF"];?>/setSendMessage" target="upload_area"  method="post" enctype="multipart/form-data" name="partmasterform" id="partmasterform">
            <div id="viewbox" style="width:100%;height:100%; background-color:white;"></div>
        </form>
    </div>

    <iframe name="upload_area" frameBorder="0" height="0"></iframe>

    <script>
        window.onload=loadClassStudents;

        //========================= Layout
        var	dhxLayout=new dhtmlXLayoutObject("layoutObj","2U");
        dhxLayout.cells("a").showHeader();
        dhxLayout.cells("a").setText("Class Apps");
        dhxLayout.cells("a").setWidth(160);
        dhxLayout.cells("a").attachObject("SubMenuId");
        dhxLayout.cells("b").showHeader();
        dhxLayout.cells("b").setText("<div style=\"font-family: Tahoma; font-size: 15px; font-weight: bold; color: #696969; float:left;\"><?php echo $param["title"];?></div><div style=\"vertical-align: middle;margin-left:220px;margin-top:-12px;\" id=\"GPClassForm\"></div>");
/*
        var gpclassfmData = [{type: "settings", position: "label-left", labelWidth:0,inputWidth: "auto"},
            {type: "button", name:"btNew", offsetLeft:20, value:"<i class=\"icon-plus\"></i> Edit Roster"} ];
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
*/

      var discgrid = dhxLayout.cells("b").attachGrid();
        discgrid.setImagePath("/dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
        discgrid.setHeader("Name,ID,Last Login,&nbsp;",null,["text-align:left","text-align:center","text-align:left","text-align:left"]);
        discgrid.setColumnIds("fullname,student_ID,logindate,&nbsp;");
        discgrid.setInitWidths("160,160,150,100");
        discgrid.setColAlign("left,center,center,center");
        discgrid.setColTypes("link,ro,ro,ro");
        discgrid.setColSorting("str,str,str,str");
        discgrid.setSkin("dhx_skyblue");
        discgrid.attachEvent("onRowSelect", function(id,ind){});
        discgrid.attachEvent("onBeforeSorting",function(ind,type,direction){ window.ct_col = ind; window.ct_drt = ((direction == "des") ? "asc": "des"); return true; });
        discgrid.init();

        function loadClassStudents(){
            var oparams = "";
            if(window.ct_drt){
                discgrid.setSortImgState(true, window.ct_col, window.ct_drt,1);
                oparams="&orderby=" + window.ct_col + "&direct=" + window.ct_drt;
            }
            dhxLayout.cells("b").progressOn();
            discgrid.clearAndLoad("<?php echo $_SERVER["PHP_SELF"];?>/getClassStudents?unqueId="+(new Date()).valueOf()+ oparams,function(){
                dhxLayout.cells("b").progressOff();
            });
        }

        //========================= School Roster
        var studentgrid = new dhtmlXGridObject('studentgridbox');
        studentgrid.selMultiRows = true;
        studentgrid.setImagePath("/dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
        studentgrid.setHeader("Last First,ID",null,["text-align:center;","text-align:center;","text-align:center;"]);
        studentgrid.setColumnIds("fullname,student_ID");
        studentgrid.setInitWidths("120,120");
        studentgrid.setColAlign("left,left");
        studentgrid.setColTypes("ro,ro");
        studentgrid.setColSorting("str,str");
        studentgrid.setSkin("dhx_skyblue");
        studentgrid.attachEvent("onRowSelect", function(id,ind){  });
        studentgrid.attachEvent("onBeforeSorting",function(ind,type,direction){ window.student_col = ind; window.student_drt = ((direction == "des") ? "asc": "des"); return true; });
        studentgrid.setMultiLine(false);
        studentgrid.enableDragAndDrop(true);
        studentgrid.init();
        function loadStudent(s){
            var oparams = "";
            if(window.student_drt){
                studentgrid.setSortImgState(true, window.student_col, window.student_drt,1);
                oparams="&orderby=" + window.student_col + "&direct=" + window.student_drt;
            }
            var vsearch = document.getElementById("search");

            //dhxLayout.cells("c").progressOn();
            if(s==1){
                studentgrid.clearAndLoad("<?php echo $_SERVER["PHP_SELF"];?>/getSchoolRoster?vseach="+vsearch.value+"&unqueId="+(new Date()).valueOf()+ oparams,function(){
                    //dhxLayout.cells("c").progressOff();
                });
            }else{
                studentgrid.updateFromXML("<?php echo $_SERVER["PHP_SELF"];?>/getSchoolRoster?vseach="+vsearch.value+"&unqueId="+(new Date()).valueOf() + oparams);
                //dhxLayout.cells("c").progressOff();
            }
        }

        //========================= Class Roster
        var rostergrid = new dhtmlXGridObject('rostergridbox');
        rostergrid.selMultiRows = true;
        rostergrid.setImagePath("/dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
        rostergrid.setHeader("Last First ,ID",null,["text-align:center;","text-align:center;"]);
        rostergrid.setColumnIds("fullname,student_ID");
        rostergrid.setInitWidths("120,120");
        rostergrid.setColAlign("left,left");
        rostergrid.setColTypes("ro,ro");
        rostergrid.setColSorting("str,str");
        rostergrid.setSkin("dhx_skyblue");
        rostergrid.attachEvent("onRowSelect", function(id,ind){  }); //resetClassFormUpdate();
        rostergrid.attachEvent("onBeforeSorting",function(ind,type,direction){ window.roster_col = ind; window.roster_drt = ((direction == "des") ? "asc": "des"); return true; });
        rostergrid.setMultiLine(false);
        rostergrid.enableDragAndDrop(true);
        rostergrid.init();
        function loadRoster(s){
            var oparams = "";
            if(window.class_drt){
                rostergrid.setSortImgState(true, window.roster_col, window.roster_drt,1);
                oparams="&orderby=" + window.roster_col + "&direct=" + window.roster_drt;
            }
            //dhxLayout.cells("d").progressOn();
            if(s==1){
                rostergrid.clearAndLoad("<?php echo $_SERVER["PHP_SELF"];?>/getClassRoster?unqueId="+(new Date()).valueOf()+ oparams,function(){
                    //dhxLayout.cells("d").progressOff();
                });
            }else{
                rostergrid.updateFromXML("<?php echo $_SERVER["PHP_SELF"];?>/getClassRoster?unqueId="+(new Date()).valueOf() + oparams);
                //dhxLayout.cells("d").progressOff();
            }
        }

        //========================= Class Roster Form
        var newId = (new Date()).valueOf();
        var	crFormData = [ {type: "settings", position: "label-left", labelWidth:"auto", inputWidth:"auto"},
            {type: "button", name:"btSave", value: "Save", disabled:"false",  width:80},{type: "newcolumn"},
            {type: "hidden", name:"lines", value: ""},
            {type: "hidden", name:"class_no", value:<?php echo $param['classno'];?>},
            {type: "hidden", name:"id", value:""}
        ];
        var	crFormObj = new dhtmlXForm("crForm", crFormData);
        crFormObj.setSkin('dhx_skyblue');
        crFormObj.enableLiveValidation(true);
        crFormObj.attachEvent("onButtonClick", function(name,command){
            if(name=='btSave'){
                var arrvals =[];
                rostergrid.forEachRow(function(id){
                    var sfn = rostergrid.cells(id,0).getValue();
                    var sid = rostergrid.cells(id,1).getValue();
                    if(id>0){
                        arrvals.push(id+'|:|'+sfn+'|:|'+sid);
                    }
                });
                var val =arrvals.join(";");
                crFormObj.setItemValue("lines",val);
                crFormObj.save();
                return true;
            }
        });

        var dhxWins2 = new dhtmlXWindows();
        dhxWins2.setSkin("dhx_skyblue");
        var w1 = dhxWins2.createWindow("w1",300,160,600,400);
        w1.hide();
       // w1.center();
        w1.button("minmax1").disable();
        w1.setModal(false);
        w1.attachEvent("onClose",function(win){
            if (win.getId() == "w1") {
                win.setModal(false);
                win.hide();
            }
        });
        w1.attachObject("schoolroster");

        //============================================================================================
        var	dpForm = new dataProcessor("<?php echo $_SERVER["PHP_SELF"];?>/setClassRoster?unqueId="+(new Date()).valueOf()); //lock feed url
        dpForm.setTransactionMode("POST",true);
        dpForm.enableDebug(true);
        dpForm.init(crFormObj);
        //dpForm.attachEvent("onBeforeUpdate",function(id,status, data){ return true;	});
        dpForm.defineAction("deleted",function(response){  showmsg("msgResult",response); return true; });
        dpForm.defineAction("inserted",function(response){ loadClassStudents();
            w1.setModal(false); w1.hide();
            showmsg("msgResult",response);  return true; });
        dpForm.defineAction("updated",function(response){ loadClassStudents();
            w1.setModal(false); w1.hide();
            showmsg("msgResult",response); return true; });

        var SendformData = [
            {type: "settings", position: "label-left", labelWidth: 70,inputWidth: "auto"},
            {type: "hidden", name: "rno", value:"0"},
            {type: "hidden", name: "receiveremail", value:""},
            {type: "hidden", name: "remail", value:""},
            {type: "hidden", name: "receiver", value:""},
            {type: "input", name: "receivername",label: "To :", value:""},
            {type: "input", className: "css_form1", label: "Subject :",name: "subject",inputWidth: "480", required: true, validate: "NotEmpty"  },
            {type: "file", name: "uploadfile",id:"uploadfile",label: "Attach :", inputWidth: "480"},
            {type: "input", name: "FCKeditor1", label: "Contents :", value: "",inputWidth: "480", rows: 11, note: { text: "" }},
//            {type: "block", list:[
//                {type: "checkbox",inputWidth:200, labelWidth: 300, name: "IsUseSMTPEmail", position: "label-right", label:"Send emails through SMTP", checked:true}
//            ]},
            {type: "button", className: "css_button", value: "Send", name: "button_send",offsetLeft: 260 },
            {type: "hidden", name:"mode", value:"insert"},
            {type: "hidden", name:"id", value:""}
        ];

        var dhxWins3 = new dhtmlXWindows();
        dhxWins3.setSkin("dhx_skyblue");
        var w2 = dhxWins3.createWindow("w2", 200, 160, 600, 400);
        w2.hide();
       // w2.center();
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
                if(name=='button_send'){
                    //var isval = editForm2.getItemValue("IsUseSMTPEmail");
                    //if(isval){
                        var email = editForm2.getItemValue("remail");
                        editForm2.setItemValue("receiveremail",email+",");
                    //}else{
                      //  editForm2.setItemValue("receiveremail","");
                   // }
                    editForm2.disableItem(name);
                    document.getElementById("partmasterform").submit();
                    return true;}

            }
            return false;
        });
        w2.attachObject("view_container");


        function showNewWindow(){
            loadStudent(1);
            loadRoster(1);
            w1.setText("Edit Roster");
            w1.setModal(true);
            w1.show();
        }

        function doOnLoadMessage(sno,fullname,email){
            editForm2.clear();
            editForm2.setItemValue("rno","0");
            editForm2.setItemValue("receiver",sno);
            editForm2.setItemValue("receivername",fullname);
            editForm2.setItemValue("remail",email);
            editForm2.setItemValue("mode","insert");
            editForm2.setItemValue("uploadfile",null);
            editForm2.enableItem("button_send");
            w2.setText("Send Message");
            w2.setModal(true);
            w2.show();
        }

        function myCallBack() {
            w2.setModal(false);
            w2.hide();
        }
        function setdefault(){
        }
    </script>
