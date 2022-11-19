<div id="msgResult" style="display:none;top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 10; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #FFD700; border: 1px solid Black;"></div>
<div id="ErrorMsg" style="display: none; top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 999; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #EE82EE; border: 1px solid Black;"></div>
<div id="layoutObj" style="width:100%;height:800px;margin:-16px 0px 0px 0px;;padding:0px;overflow:hidden;"></div>
<style>
    #music {
        background-color: white;
        color:black;
        display: block;
        height: 18px;
        line-height: 18px;
        text-decoration: none;
        width: 60px;
        text-align: center;
        float:none;
        border:1px solid black;
        margin:4px 0px 0px 3px;
    }
</style>
<script>
    window.onload = loadTranscripts;

    var dhxLayout;
    dhxLayout=new dhtmlXLayoutObject("layoutObj","2E");
    dhxLayout.cells("a").setText('Transcripts');
    dhxLayout.cells("a").setHeight(400);
    dhxLayout.cells("b").setText('Attendance');

    var admingrid =dhxLayout.cells("a").attachGrid();
    admingrid.setImagePath("dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
    admingrid.setHeader("No,FirstName,LastName,Class,Trimester,Grade,#cspan,#cspan,#cspan,#cspan,#cspan,Attendance,Writer,Date");
    admingrid.attachHeader("#rspan,#text_filter,#text_filter,#select_filter,#select_filter,Participation,Homework,Quiz,Midterm,Final,Grade,#rspan,#rspan,#rspan");
    admingrid.setColumnIds("grade_no,firstname,lastname,class_name,group_name,part_score,home_score,quiz_score,midtem_score,final_score,record,att_score,writer,regdate");
    admingrid.setInitWidths("30,100,100,200,120,50,50,50,50,50,50,80,100,120");
    admingrid.enableAutoWidth(true);
    admingrid.setColAlign("left,left,left,left,left,center,center,center,center,center,center,center,left,left");
    admingrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,link,ro,ro");
    admingrid.setColSorting("int,str,str,str,str,int,int,int,int,int,int,int,str,date");
//    admingrid.getCombo(0).put('1', "Transcripts");
//    admingrid.getCombo(9).put('1', "Active");
//    admingrid.getCombo(9).put('0', "Inactive");
    admingrid.setSkin("dhx_skyblue");
    admingrid.attachEvent("onRowSelect", function(id,ind){
        //myFormAdd.enableItem("button_save");
       // myFormAdd.enableItem("button_delete");
       // myFormAdd.disableItem("button_insert");
    });
    admingrid.attachEvent("onBeforeSorting",function(ind,type,direction){
        window.sf_col = ind;
        window.sf_drt = ((direction == "des") ? "asc": "des");
        return true;
    });
    admingrid.init();
    admingrid.enableSmartRendering(true, 50);


    function loadTranscripts(){
        //resetTranscriptsList();
        var oparams = "";
        if(window.sf_drt){
            admingrid.setSortImgState(true, window.sf_col, window.sf_drt,1);
            oparams="&orderby=" + window.sf_col + "&direct=" + window.sf_drt;
        }

        dhxLayout.cells("a").progressOn();
        admingrid.clearAndLoad("transcripts/getTranscripts?unqueId="+(new Date()).valueOf()+ oparams,function(){
            dhxLayout.cells("a").progressOff();
        });

    }


    var attgrid =dhxLayout.cells("b").attachGrid();
    attgrid.setImagePath("dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
    attgrid.setHeader("No,Class,Instructor,Student,Items,Date Attended,Writer,Date");
    attgrid.attachHeader("#rspan,#rspan,#rspan,#rspan,#select_filter,#rspan,#rspan,#rspan");
    attgrid.setColumnIds("attendance_no,class_name,teacherfullname,studentfullname,items,attendance_day,writer,regdate");
    attgrid.setInitWidths("30,200,180,180,120,80,100,120");
    attgrid.enableAutoWidth(true);
    attgrid.setColAlign("left,left,left,left,left,center,center,center");
    attgrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro");
    attgrid.setColSorting("int,str,str,str,str,date,str,date");
    //    admingrid.getCombo(0).put('1', "Transcripts");
    //    admingrid.getCombo(9).put('1', "Active");
    //    admingrid.getCombo(9).put('0', "Inactive");
    attgrid.setSkin("dhx_skyblue");
    attgrid.attachEvent("onRowSelect", function(id,ind){
        //myFormAdd.enableItem("button_save");
        // myFormAdd.enableItem("button_delete");
        // myFormAdd.disableItem("button_insert");
    });
    attgrid.attachEvent("onBeforeSorting",function(ind,type,direction){
        window.sf_col2 = ind;
        window.sf_drt2 = ((direction == "des") ? "asc": "des");
        return true;
    });
    attgrid.init();
    attgrid.enableSmartRendering(true, 50);

    function loadData(cno,sno){
        var oparams = "";
        if(window.sf_drt2){
            attgrid.setSortImgState(true, window.sf_col2, window.sf_drt2,1);
            oparams="&orderby=" + window.sf_col2 + "&direct=" + window.sf_drt2;
        }
        dhxLayout.cells("b").progressOn();
        attgrid.clearAndLoad("transcripts/getTranscriptAtt?sno="+sno+"&cno="+cno+"&unqueId="+(new Date()).valueOf()+ oparams,function(){
            dhxLayout.cells("b").progressOff();
        });
    }


/*
    var myDataProcessor = new dataProcessor("transcripts/setTranscripts?unqueId="+(new Date()).valueOf());
    //myDataProcessor.setTransactionMode("POST",true);
    myDataProcessor.init(admingrid);
    myDataProcessor.defineAction("inserted",function(response){
        showfullmsg("msgResult","It has been "+response.textContent+" successfully.");
        admingrid.updateFromXML("transcripts/getTranscripts?unqueId="+(new Date()).valueOf());
        return true;
    });
    myDataProcessor.defineAction("updated",function(response){
        showfullmsg("msgResult","It has been "+response.textContent+" successfully.");
        return true;
    });
    myDataProcessor.defineAction("deleted",function(response){
        showfullmsg("msgResult","It has been "+response.textContent+" successfully.");
        return true;
    });
    myDataProcessor.defineAction("invalid",function(response){
        showfullmsg("ErrorMsg","Invalid Data");
        return true;
    });

    var formData = [{ type: "settings", position: "label-left", labelWidth: 100},
        {type: "fieldset",label: "Insert Data",inputWidth: "auto", list: [
            {type: "colorpicker", imagePath: "dhtmlxSuite/sources/dhtmlxColorPicker/codebase/imgs/", value:"#52e56", label: "Color", name: "bgcolorone"},
            {type: "select", label: "Authority",name: "roleid", required: true, options: [ {value: "1",text: "Transcripts", selected:true } ] },
            {type: "input",  validate: "NotEmpty",label: "User ID",name: "user_ID", inputWidth: 170, required: true  },
            {type: "password", label: "Password",name: "passw", inputWidth: 170 },
            {type: "password", label: "Confirm Password",name: "inst_pwd2", inputWidth: 170 },
            {type: "input",label: "First Name",name: "firstname", inputWidth: 170, required: true  },
            {type: "input",label: "Last Name",name: "lastname", inputWidth: 170, required: true  },
            {type: "input", validate: "NotEmpty",label: "initial",name: "initial", inputWidth: 170 },
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
    var myFormAdd =dhxLayout.cells("b").attachForm(formData);
    myFormAdd.enableLiveValidation(true);
    myFormAdd.bind(admingrid);
    myFormAdd.attachEvent("onButtonClick", function(name,command){
        if(name=='button_reset'){
            setdefaultform();
            return true;
        }

        if(name=='button_delete'){
            var selectedId=admingrid.getSelectedRowId();
            if(selectedId){
                if (confirm("Are you sure you want to delete row")) {
                    admingrid.deleteSelectedItem();
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
                var selectedId=admingrid.getSelectedRowId();
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
                dhx4.ajax.post("transcripts/checkDuplicateUserID","newuid="+uid, function(r){
                    var xml = r.xmlDoc.responseXML;
                    var nodes = xml.getElementsByTagName("item");
                    var result = nodes[0].getAttribute("value");
                    if(result>0){
                        showfullmsg("ErrorMsg","UserId is duplicated");
                        return false;
                    }else{

                        admingrid.addRow(newId,[autho,uid,fnam,nam,initi,nicnam,cephon,emai,bgcolo,sta,'',getfullday(),etc,pwd],0);
                        setdefaultform();
                        return true;
                    }
                });

                return true;
            }
        }
        return true;
    });
*/

/*
    function resetTranscriptsList(){
        myFormAdd.disableItem("button_delete");
        myFormAdd.disableItem("button_save");
        myFormAdd.enableItem("button_insert");
    }

    function setdefaultform(){
        myFormAdd.setItemValue("bgcolorone","");
        myFormAdd.setItemValue("roldid",1);
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
        resetTranscriptsList();
    }
*/
</script>