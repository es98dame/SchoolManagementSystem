<div id="msgResult" style="display:none;top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 10; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #FFD700; border: 1px solid Black;"></div>
<div id="ErrorMsg" style="display: none; top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 999; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #EE82EE; border: 1px solid Black;"></div>
<div id="layoutObj" style="width:100%;height:600px;margin:-16px 0px 0px 0px;;padding:0px;overflow:hidden;"></div>
<div id="winVP" style="font-family: Tahoma; font-size: 11px; width: 100%; height:100%; overflow: hidden;"></div>

<div id="view_container" style="display:none;margin-top:20px;margin-left:20px;width:600px; height:400px; overflow: hidden;">
    <form action="<?php echo $_SERVER["PHP_SELF"];?>/setSendWLetter" target="upload_area" method="post" enctype="multipart/form-data" name="partmasterform" id="partmasterform">
        <div id="viewbox" style="width:100%;height:100%; background-color:white;"></div>
    </form>
</div>
<iframe name="upload_area" frameBorder="0" height="0"></iframe>
<script>
    //  window.onload=loadRoster;

    var	dhxLayout=new dhtmlXLayoutObject("layoutObj","2E");
    dhxLayout.cells("a").showHeader();
    dhxLayout.cells("a").setText("<div style=\"float:left;font-family: Tahoma; font-size: 15px; font-weight: bold; color: #696969;\">Warning Letter</div>");
    dhxLayout.cells("a").setHeight(100);
    dhxLayout.cells("b").hideHeader();
    dhxLayout.cells("b").setText("Warning Letter");

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
        {type: "button", name:"btSearch", value:"Search"},
        {type: "newcolumn", offset:1},
        {type: "button", name:"btExcel", value:"Export(.xlsx)"}
 ];

    var	gpclassfm = dhxLayout.cells("a").attachForm(gpclassfmData);
    gpclassfm.setSkin('dhx_skyblue');
    gpclassfm.attachEvent("onButtonClick", function(name,command){
        if(gpclassfm.validate()){
            if(name=='btSearch'){
                loadWLetter();
                return true;
            }
            if(name=='btExcel'){
                var v1 = gpclassfm.getCombo("schoolyear").getSelectedValue();
                var v2 = gpclassfm.getCombo("trimester").getSelectedValue();
                document.getElementById("yearid").value = v1;
                document.getElementById("trimid").value = v2;
                document.getElementById("exportform").submit();
               grid_classgrid.toExcel('http://'.$_SERVER['HTTP_HOST'].'/dhtmlxSuite/sources/dhtmlxGrid/codebase/grid-excel-php/generate.php');
                return true;
            }
        }
        return false;
    });

    //=== Grid of Family List ----------------------------------------------------------------
    var grid_classgrid = dhxLayout.cells("b").attachGrid();
    grid_classgrid.setImagePath("/dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
    grid_classgrid.setHeader("No,Student,ID,Level,Session,L/S,#cspan,#cspan,#cspan,#cspan,R/W,#cspan,#cspan,#cspan,#cspan,Sum\n\rof\n\rTardy,Sum\n\rof\n\rAbsent,Att(%),Remediation,#cspan,#cspan,#cspan,W1,W2,TN,Edit,Total\nAtt");
    grid_classgrid.attachHeader("#rspan,#text_filter,#rspan,#rspan,#rspan,Grade,P,T,A,Total,Grade,P,T,A,Total,#rspan,#rspan,#numeric_filter,P,A,Total,Att(%),#master_checkbox,#master_checkbox,#master_checkbox,#rspan,#rspan");
    grid_classgrid.setColumnIds("seq,fullname,progress,&nbsp;,&nbsp;,&nbsp;,&nbsp;,&nbsp;,&nbsp;,&nbsp;,&nbsp;,&nbsp;,&nbsp;,&nbsp;,&nbsp;,&nbsp;,&nbsp;,&nbsp;,&nbsp;,&nbsp;,&nbsp;,&nbsp;,&nbsp;,&nbsp;&nbsp;");
    grid_classgrid.setInitWidths("30,140,90,60,60,60,40,40,40,40,60,40,40,40,40,50,50,60,40,40,40,40,50,50,50,50,*");
    // grid_classgrid.enableAutoWidth(true);
    grid_classgrid.setColAlign("left,left,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,left,left");
    grid_classgrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ch,ch,ch,ro,ro");
    grid_classgrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,int,int,int,str,int");
    grid_classgrid.setSkin("dhx_skyblue");
    // grid_classgrid.attachEvent("onRowSelect", function(id,ind){});
    grid_classgrid.attachEvent("onBeforeSorting",function(ind,type,direction){ window.am_col = ind; window.am_drt = ((direction == "des") ? "asc": "des"); return true; });
    // grid_classgrid.preventIECaching(true);
    grid_classgrid.attachEvent("onRowCreated", function(rId,rObj,rXml){
        //if (grid_classgrid.cells(rId,14).getAttribute("disabled"))
        //   grid_classgrid.cells(rId,14).setDisabled(true);
    });
    grid_classgrid.init();

    function loadWLetter(){
        var v1 = gpclassfm.getCombo("schoolyear").getSelectedValue();
        var v2 = gpclassfm.getCombo("trimester").getSelectedValue();
        var oparams = "&year="+v1+"&trim="+v2;
        dhxLayout.cells("b").progressOn();
        grid_classgrid.clearAndLoad("<?php echo $_SERVER["PHP_SELF"];?>/getWarningLetter?unqueId="+(new Date()).valueOf()+ oparams,function(){
            dhxLayout.cells("b").progressOff();
        });
    }

    var formData = [
        {type: "settings", position: "label-left", labelWidth: 70,inputWidth: "auto"},
        {type: "hidden", name: "rno", value:"0"},
        {type: "hidden", name: "receiveremail", value:""},
        {type: "hidden", name: "receiver", value:""},
        {type: "input", name: "receivername",label: "To :", value:""},
        {type: "input", label: "E-mail :",name: "remail", inputWidth: "480", required: true, validate: "NotEmpty"},
        {type: "input", label: "Subject :",name: "subject",inputWidth: "480", required: true, validate: "NotEmpty"  },
        {type: "block", inputWidth: "auto", blockOffset:0, list:[
            {type: "label", label: "Attach Warning Letter :", labelWidth: 70},
            {type: "newcolumn", offset:1},
            {type: "radio",name: "wtn", value:"wt1", position: "label-right", label:"First", checked:false},
            {type: "newcolumn", offset:1},
            {type: "radio", name: "wtn", value:"wt2", position: "label-right", label:"Second", checked:false},
            {type: "newcolumn", offset:1},
            {type: "radio", name: "wtn", value:"ttn", position: "label-right", label:"Terminate", checked:false}
        ]},
        {type: "file", name: "uploadfile",id:"uploadfile",label: "Attach Files :", inputWidth: "480"},
        {type: "input", name: "FCKeditor1", label: "Contents :", value: "",inputWidth: "480", rows: 5, note: { text: "" }},
        {type: "button", value: "Send", name: "button_send",offsetLeft: 260 },
        {type: "hidden", name:"schoolyear", value:""},
        {type: "hidden", name:"trimester", value:""},
        {type: "hidden", name:"mode", value:"insert"},
        {type: "hidden", name:"id", value:""}
    ];
    var editForm = new dhtmlXForm("viewbox",formData);
//    var editForm = w1.attachForm(formData);
    editForm.setSkin('dhx_skyblue');
    editForm.enableLiveValidation(true);
    editForm.attachEvent("onButtonClick", function(name,command){
        if(editForm.validate()){
            if(name=='button_send'){
                //var isval = editForm2.getItemValue("IsUseSMTPEmail");
                //if(isval){
                var email = editForm.getItemValue("remail");
                editForm.setItemValue("receiveremail",email+",");
                var v1 = gpclassfm.getCombo("schoolyear").getSelectedValue();
                var v2 = gpclassfm.getCombo("trimester").getSelectedValue();
                editForm.setItemValue("schoolyear",v1);
                editForm.setItemValue("trimester",v2);
                //}else{
                //  editForm2.setItemValue("receiveremail","");
                // }
                editForm.disableItem(name);
                document.getElementById("partmasterform").submit();
                return true;
            }
        }
        return false;
    });

    var dhxWins = new dhtmlXWindows();
    dhxWins.setSkin("dhx_skyblue");
    var w1 = dhxWins.createWindow("w1", 200, 160, 600, 400);
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
    w1.attachObject("view_container");

    function doOnLoadInfo(sno,fullname,email){
        editForm.clear();
        editForm.setItemValue("rno","0");
        editForm.setItemValue("receiver",sno);
        editForm.setItemValue("receivername",fullname);
        editForm.setItemValue("remail",email);
        editForm.setItemValue("mode","insert");
        editForm.setItemValue("uploadfile",null);
        editForm.enableItem("button_send");

       // editForm.load("<?php echo $_SERVER["PHP_SELF"];?>/getAssignRosterForm?rno="+rno+"&unqueId="+(new Date()).valueOf());
        w1.setText("Send Warning Letter");
        w1.setModal(true);
        w1.show();
    }
    function myCallBack() {
        w1.setModal(false);
        w1.hide();
        loadWLetter();
    }

    function setdefault(){
    }
</script>
<form action="<?php echo $_SERVER["PHP_SELF"];?>/exportWLetter" target="export_area" method="post" name="exportform" id="exportform">
    <input type="hidden" name="year" id="yearid"/>
    <input type="hidden" name="trim" id="trimid"/>
</form>
<iframe name="export_area" frameBorder="0" height="0"></iframe>