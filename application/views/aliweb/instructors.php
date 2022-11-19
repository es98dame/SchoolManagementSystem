<div id="msgResult" style="display:none;top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 10; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #FFD700; border: 1px solid Black;"></div>
<div id="ErrorMsg" style="display: none; top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 999; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #EE82EE; border: 1px solid Black;"></div>
<div id="layoutObj" style="width:100%;height:600px;margin:-16px 0px 0px 0px;;padding:0px;overflow:hidden;"></div>

<script>
    window.onload = loadInstructors;

    var dhxLayout;
    dhxLayout=new dhtmlXLayoutObject("layoutObj","2U");
    dhxLayout.cells("a").setText('Instructor Info');
    dhxLayout.cells("b").setText('<input type="button" name="save" value="Export(.xlsx)" style="font-size: 11px; font-family: Tahoma;" onClick="teachergrid.toExcel(\'<?php echo base_url("dhtmlxSuite/sources/dhtmlxGrid/codebase/grid-excel-php/generate.php"); ?>\')"> <input type="button" name="save" value="Export(.pdf)" style="font-size: 11px; font-family: Tahoma;" onClick="teachergrid.toPDF(\'<?php echo base_url("dhtmlxSuite/sources/dhtmlxGrid/codebase/grid-pdf-php/generate.php"); ?>\')">');
    dhxLayout.cells("a").setWidth(380);

    var teachergrid =dhxLayout.cells("b").attachGrid();
    teachergrid.setImagePath("dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
    teachergrid.setHeader("Authority,User ID,FirstName,LastName,Initial,Nickname,Cellphone,Email,Color,Status,Writer,Write date,Memo,&nbsp;");
    teachergrid.setColumnIds("roleid,user_ID,firstname,lastname,initial,nickname,cellphone,email,bgcolorone,active,writer,created,etc,passw");
    teachergrid.setInitWidths("80,80,80,80,50,80,100,100,50,50,80,150,0,0");
    teachergrid.enableAutoWidth(true);
    teachergrid.setColAlign("left,left,left,left,left,center,center,center,center,center,center,center,left,left");
    teachergrid.setColTypes("co,ed,ed,ed,ed,ed,ed,ed,ed,co,txt,ro,ro,ro");
    teachergrid.setColSorting("str,str,str,str,str,str,str,str,str,str,str,str,date,str");
    teachergrid.getCombo(0).put('3', "Instructor");
    teachergrid.getCombo(9).put('1', "Active");
    teachergrid.getCombo(9).put('0', "Inactive");
    teachergrid.setSkin("dhx_skyblue");
    teachergrid.attachEvent("onRowSelect", function(id,ind){
        myFormAdd.enableItem("button_save");
        myFormAdd.enableItem("button_delete");
        myFormAdd.disableItem("button_insert");
    });
    teachergrid.attachEvent("onBeforeSorting",function(ind,type,direction){
        window.sf_col = ind;
        window.sf_drt = ((direction == "des") ? "asc": "des");
        return true;
    });
    teachergrid.init();
    teachergrid.enableSmartRendering(true, 50);

    var myDataProcessor = new dataProcessor("instructors/setInstructor?unqueId="+(new Date()).valueOf()); //lock feed url
    //myDataProcessor.setTransactionMode("POST",true); //set mode as send-all-by-post
    myDataProcessor.init(teachergrid); //link dataprocessor to the grid
    myDataProcessor.defineAction("inserted",function(response){
        showmsg("msgResult",response);
        //showfullmsg("msgResult","It has been "+response.textContent+" successfully.");
        teachergrid.updateFromXML("instructors/getInstructors?unqueId="+(new Date()).valueOf());
        return true;
    });
    myDataProcessor.defineAction("updated",function(response){
        showmsg("msgResult",response);
        //showfullmsg("msgResult","It has been "+response.textContent+" successfully.");
        return true;
    });
    myDataProcessor.defineAction("deleted",function(response){
        showmsg("msgResult",response);
        setdefaultform();
        //showfullmsg("msgResult","It has been "+response.textContent+" successfully.");
        return true;
    });
    myDataProcessor.defineAction("invalid",function(response){
        showfullmsg("ErrorMsg","Invalid Data");
        return true;
    });

    var formData = [{ type: "settings", position: "label-left", labelWidth: 100},
        {type: "fieldset",label: "Insert Data",inputWidth: "auto", list: [
            {type: "colorpicker", imagePath: "dhtmlxSuite/sources/dhtmlxColorPicker/codebase/imgs/", value:"#52e56", label: "Color", name: "bgcolorone"},
            {type: "select", label: "Authority",name: "roleid", required: true, options: [ {value: "3",text: "Instructor", selected:true } ] },
            {type: "input",  validate: "NotEmpty",label: "User ID",name: "user_ID", inputWidth: 170, required: true  },
            {type: "password", label: "Password",name: "passw", inputWidth: 170 },
            {type: "password", label: "Confirm Password",name: "inst_pwd2", inputWidth: 170 },
            {type: "input",label: "First Name",name: "firstname", inputWidth: 170, required: true  },
            {type: "input",label: "Last Name",name: "lastname", inputWidth: 170, required: true  },
            {type: "input", validate: "NotEmpty",label: "initial",name: "initial", required: true, inputWidth: 170 },
            {type: "input",label: "NickName",name: "nickname", inputWidth: 170 },
            {type: "input",label: "Cellphone",name: "cellphone", inputWidth: 170 },
            {type: "input", validate: "NotEmpty,ValidEmail",label: "Email",name: "email", inputWidth: 170, required: true  },
            {type: "select", label: "Status",name: "active", options: [ {value: "0",text: "Inactive",selected: true }, { value: "1",text: "Active"} ] },
            {type: "input",label: "Memo",name: "etc",rows:"3",inputWidth: 170 },
            {type: "block", width: 300, offsetTop:5, list:[
                {type: "button", value: "Update", name: "button_save"},{type:"newcolumn"},
                {type: "button", value: "Add", name: "button_insert"},{type:"newcolumn"},
                {type: "button", value: "Reset", name: "button_reset"},{type:"newcolumn"},
                {type: "button", value: "Delete", name: "button_delete"} ]} ]
        }];
    var myFormAdd =dhxLayout.cells("a").attachForm(formData);
    myFormAdd.enableLiveValidation(true);
    myFormAdd.bind(teachergrid);
    myFormAdd.attachEvent("onButtonClick", function(name,command){
        if(name=='button_reset'){
            setdefaultform();
            return true;
        }

        if(name=='button_delete'){
            var selectedId=teachergrid.getSelectedRowId();
            if(selectedId){
                if (confirm("Are you sure you want to delete row")) {
                    teachergrid.deleteSelectedItem();
                    myFormAdd.clear();
                    return true;
                }else{
                    return false;
                }
            }else{
                alert('select a row');
                return false;
            }
        }

        if(myFormAdd.validate()){
            var pw1 = this.getItemValue('passw');
            var pw2 = this.getItemValue('inst_pwd2');
            if(pw1!=''&&pw2!=''){
                if(pw1!=pw2){
                    alert("Both password is not matched");
                    return false;
                }
            }

            if(name=='button_save'){
                var selectedId=teachergrid.getSelectedRowId();
                if(selectedId){
                    myFormAdd.save();
                    return true;
                }else{
                    alert('select a row');
                    return false;
                }
            }

            if(name=='button_insert'){
                var newId = (new Date()).valueOf();
                var bgcolo = myFormAdd.getItemValue("bgcolorone");
                var autho = myFormAdd.getItemValue("roleid");
                var uid = myFormAdd.getItemValue("user_ID");
                var pwd = myFormAdd.getItemValue("passw");
                var fnam = myFormAdd.getItemValue("firstname");
                var nam = myFormAdd.getItemValue("lastname");
                var initi = myFormAdd.getItemValue("initial");
                var nicnam = myFormAdd.getItemValue("nickname");
                var cephon = myFormAdd.getItemValue("cellphone");
                var emai = myFormAdd.getItemValue("email");
                var sta = myFormAdd.getItemValue("active");
                var etc = myFormAdd.getItemValue("etc");
                dhx4.ajax.post("instructors/checkDuplicateUserID","newuid="+uid, function(r){
                    var xml = r.xmlDoc.responseXML;
                    var nodes = xml.getElementsByTagName("item");
                    var result = nodes[0].getAttribute("value");
                    if(result>0){
                        showfullmsg("ErrorMsg","UserId is duplicated");
                        return false;
                    }else{
                        teachergrid.addRow(newId,[autho,uid,fnam,nam,initi,nicnam,cephon,emai,bgcolo,sta,'',getfullday(),etc,pwd],0);
                        setdefaultform();
                        return true;
                    }
                });

                return true;
            }
        }
        return true;
    });

    function loadInstructors(){
        resetInstructorList();
        var oparams = "";
        if(window.sf_drt){
            teachergrid.setSortImgState(true, window.sf_col, window.sf_drt,1);
            oparams="&orderby=" + window.sf_col + "&direct=" + window.sf_drt;
        }

        dhxLayout.cells("b").progressOn();
        teachergrid.clearAndLoad("instructors/getInstructors?unqueId="+(new Date()).valueOf()+ oparams,function(){
            dhxLayout.cells("b").progressOff();
        });
    }
    function resetInstructorList(){
        myFormAdd.disableItem("button_delete");
        myFormAdd.disableItem("button_save");
        myFormAdd.enableItem("button_insert");
    }
    function setdefaultform(){
        myFormAdd.setItemValue("bgcolorone","");
        myFormAdd.setItemValue("roldid",3);
        myFormAdd.setItemValue("user_ID","");
        myFormAdd.setItemValue("passw","");
        myFormAdd.setItemValue("firstname","");
        myFormAdd.setItemValue("lastname","");
        myFormAdd.setItemValue("initial","");
        myFormAdd.setItemValue("nickname","");
        myFormAdd.setItemValue("cellphone","");
        myFormAdd.setItemValue("email","");
        myFormAdd.setItemValue("active","1");
        myFormAdd.setItemValue("etc","");
        resetInstructorList();
    }

</script>