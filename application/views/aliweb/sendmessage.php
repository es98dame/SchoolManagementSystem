<div id="msgResult" style="display:none;top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 10; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #FFD700; border: 1px solid Black;"></div>
<div id="ErrorMsg" style="display: none; top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 999; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #EE82EE; border: 1px solid Black;"></div>
<div id="layoutObj" style="width:100%;height:600px;margin:-16px 0px 0px 0px;;padding:0px;overflow:hidden;"></div>
<script src="<?php echo base_url("ckeditor/ckeditor.js"); ?>"></script>
<div id="view_container" style="margin-top:0px;margin-left:20px;width:100%; height:250px; overflow: hidden;">
<form action="<?php echo $_SERVER["PHP_SELF"];?>/setSendMessage" target="upload_area"  method="post" enctype="multipart/form-data" name="partmasterform" id="partmasterform">
    <div id="viewbox" style="width:100%;height:100%; background-color:white;"></div>
</form>
</div>
<iframe name="upload_area" frameBorder="0" height="0"></iframe>
<script>
    //window.onload=loadClassInquiry;
    var	dhxLayout=new dhtmlXLayoutObject("layoutObj","2E");
    dhxLayout.cells("a").showHeader();
    dhxLayout.cells("a").setHeight(250);
    dhxLayout.cells("a").setText("<div style=\"float:left;font-family: Tahoma; font-size: 15px; font-weight: bold; color: #696969;\">Send Info</div>");
    dhxLayout.cells("b").setText("<div style=\"float:left;font-family: Tahoma; font-size: 15px; font-weight: bold; color: #696969;\">Message</div>");


    var classfmData = [{type: "settings", position: "label-left",offsetLeft:10, labelWidth:80,inputWidth: 400},
        {type: "input", label:"Subject: ", name:"subject",required: true, value:""},
        {type: "input", label:"From: ", name:"fromemail",required: true, value:"<?php echo $fromemail;?>"},
        {type: "input", label:"To: ", value:"", rows:"3",required: true, name:"receiveremail",note:{text:"Use a comma (,) to separate each email address."}},
        {type: "file",  label: "Attach File: ", style:"width:400px;", name: "uploadfile"  },
        {type: "button", name:"btSend",offsetLeft:100, value:"Send"},
        {type: "newcolumn", offset:1},
        {type: "button", name:"btCopy",offsetTop:80, value:"<<"},
        {type: "newcolumn", offset:1},
        {type: "combo", label: "Classes: ",name: "class_no",inputWidth: 200, connector:"<?php echo $_SERVER["PHP_SELF"];?>/getComMessageClasses?unqueId="+(new Date()).valueOf() },
        {type: "container",name:"studentGrid",label:"Students",inputWidth: 320,inputHeight:160},
        {type: "hidden", name:"FCKeditor1"} ];


//    var	classfm = dhxLayout.cells("a").attachForm(classfmData);
    var classfm = new dhtmlXForm("viewbox",classfmData);
    classfm.setSkin('dhx_skyblue');
    classfm.attachEvent("onButtonClick", function(name,command){
        if(name=='btCopy'){
            var arrval = new Array();
            subGrid.forEachRow(function(id){
                var nam=subGrid.cells(id,0).getValue();
                var email=subGrid.cells(id,1).getValue();
                arrval.push(email);
            });
            classfm.setItemValue("receiveremail",arrval.toString());
            return true;
        }
        if(classfm.validate()){
            if(name=='btSend'){
                var content = myEditor.getContent();
                classfm.setItemValue("FCKeditor1",content);
                document.getElementById("partmasterform").submit();
                return true;
            }
        }
        return true;
    });
    var dhxCombo = classfm.getCombo("class_no");
    dhxCombo.attachEvent("onChange", function(value, text){
        subGrid.clearAndLoad("<?php echo $_SERVER["PHP_SELF"];?>/getConStudentList?class_no="+value+"&unqueId="+(new Date()).valueOf(),function(){
        });
    });


    var subGrid = new dhtmlXGridObject(classfm.getContainer("studentGrid"));
    subGrid.setImagePath("dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
    subGrid.setHeader("Student Name","Email",null,["text-align:center;","text-align:center;"]);
    subGrid.setColumnIds("fullname,email");
    subGrid.setInitWidths("160,160");
    //subGrid.enableAutoWidth(true);
    subGrid.setColAlign("left,left");
    subGrid.setColTypes("ro,ro");
    subGrid.setColSorting("str,str");
    subGrid.setSkin("dhx_skyblue");
    subGrid.enableDragAndDrop(true);


    dhxLayout.cells("a").attachObject("view_container");

    var myEditor = dhxLayout.cells("b").attachEditor();
    myEditor.setContent("");


    function myCallBack(){
        classfm.setItemValue("subject","");
        classfm.setItemValue("receiveremail","");
        classfm.setItemValue("uploadfile","");
        classfm.setItemValue("FCKeditor1","");
        myEditor.setContent("");
    }
</script>