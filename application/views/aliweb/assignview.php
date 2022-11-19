<div id="msgResult" style="display:none;top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 10; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #FFD700; border: 1px solid Black;"></div>
<div id="ErrorMsg" style="display: none; top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 999; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #EE82EE; border: 1px solid Black;"></div>
<div id="layoutObj" style="width:100%;height:600px;margin:-16px 0px 0px 0px;;padding:0px;overflow:hidden;"></div>
<div id="SubMenuId" style="margin-left:-20px;"><ul style="list-style:none;"><?php echo $param["menus"];?></ul></div>

    <div id="form_container" style="padding: 1px 1px 1px 1px;width:100%; height:100%; overflow-x: auto; overflow-y: auto;">
        <form action="<?php echo $_SERVER["PHP_SELF"];?>/setAssignview" target="upload_area" method="post" enctype="multipart/form-data" name="partmasterform" id="partmasterform" >
            <div id="myForm" style="padding: 0px 0px 0px 0px;width: 98%; height: 500px; aborder: #B5CDE4 1px solid;"></div>
        </form>
    </div>
    <iframe name="upload_area" frameBorder="0" height="0"></iframe>

    <script>
        window.onload=loadAssignment;
        var	fmct ='';
        //========================= Layout
        var	dhxLayout=new dhtmlXLayoutObject("layoutObj","2U");
        dhxLayout.cells("a").showHeader();
        dhxLayout.cells("a").setText("Class Apps");
        dhxLayout.cells("a").setWidth(160);
        dhxLayout.cells("a").attachObject("SubMenuId");
        dhxLayout.cells("b").showHeader();
        dhxLayout.cells("b").setText("<div style=\"font-family: Tahoma; font-size: 15px; font-weight: bold; color: #696969;\"><?php echo $param["title"];?></div>");

        //========================= Attached Files
        function getFiles(name, value) {
            var con = "<?php echo $contx;?>";
            return con;
        }

        function delFiles(no){
            if(confirm("Are you sure to delete the file?")){
                var posturl = "<?php echo $_SERVER["PHP_SELF"];?>/delFile";
                var params = "fno="+no; var mes='';
                postAjax(posturl,params,function(the_pays){
                    if(BR_n=='msie'){
                        mes = the_pays[0].childNodes[0].childNodes[0].nodeValue;
                    }else{
                        mes = the_pays[0].getElementsByTagName("cell")[0].childNodes[0].nodeValue;
                    }
                    showfullmsg("msgResult",mes);
                    document.getElementById("upf"+no).style.display = 'none';
                });
                return true;
            }
        }

        function getScores(name, value) {
            var score = "<?php echo $scores;?>";
            return score;
        }

        //========================= Edit Form
        var ctformData = [    {type: "settings", position: "label-left", labelWidth: 140,inputWidth: "auto"},
            {type: "fieldset", label: "Edit Assignemnt", inputWidth: "auto", list:[
                {type: "hidden", name: "gpno"},
                {type: "hidden", name: "class_no", value:<?php echo $param['classno'];?> },
                {type: "hidden", name: "selbookno" },
                {type: "hidden", name: "points", value:"100" },
                {type: "input", className: "css_form1", label:"Class: ",readonly:"true",offsetTop:5, name:"classname"},
                {type: "input", className: "css_form1", label: "Assignment Name: ",offsetTop:5, inputWidth:200, name: "name", required: true  },
                {type: "calendar", dateFormat:"%Y-%m-%d", className: "css_form1", label: "Due Date: ",offsetTop:5,name: "duedate",readonly:true, required: true },
                {type: "combo", className: "css_form1", label: "Category: ",inputWidth:200, offsetTop:5, name: "assigncat_no", required: true, connector:"<?php echo $_SERVER["PHP_SELF"];?>/getComCate?unqueId="+(new Date()).valueOf() },
                {type: "block", offsetLeft:0,list:[
                    {type: "file", offsetLeft:0, className: "css_form1",inputWidth: 200, label: "Attach File1: ",offsetTop:5, name: "myFiles"  },
                    {type: "file", offsetLeft:0, className: "css_form1",inputWidth: 200, label: "Attach File2: ",offsetTop:5, name: "myFiles2"  },{type: "newcolumn", offset:2},
                    {type:"template",name:"attachedfiles",labelWidth: 10,label:" ",value:"<?php echo $id;?>",position:"label-left",format:"getFiles"}
                ]},
                {type: "input", className: "css_form1", label: "Description: ",offsetTop:5,inputWidth:300,name: "description",rows:"10" },

                {type: "block", offset:100, list:[
                    {type: "checkbox",labelWidth: 300, position: "label-right", label: "Allow students to see this assignment", name:"isview"}
                ]},
            ]},
            {type: "block", list:[
                {type: "button", className: "css_button", value: "Update",offsetTop:5, name: "btSave"},{type: "newcolumn", offset:2},
                {type: "button", className: "css_button", value: "Delete",offsetTop:5, name: "btDelete"},{type: "newcolumn", offset:2},
                {type: "button", className: "css_button", value: "Back",offsetTop:5, name: "btCancel"}
            ]},
            {type: "newcolumn", offset:22},
            {type: "fieldset", label: "Student Scores", inputWidth: "auto", list:[
                {type:"template",name:"students",labelWidth:0,label:"",value:"<?php echo $id;?>",position:"label-left",format:"getScores"}
            ]},
            {type: "hidden", name:"mode"},
            {type: "hidden", name:"visview"},
            {type: "hidden", name:"id"}
        ];

        fmct = new dhtmlXForm("myForm",ctformData);

        fmct.setSkin('dhx_skyblue');
        fmct.enableLiveValidation(true);
        fmct.attachEvent("onButtonClick", function(name,command){
            if(fmct.validate()){
                if(name=='btCancel'){
                    self.location.href="/index.php/aliweb/assignments?unqueId="+(new Date()).valueOf();
                    return false;
                }
                if(name=='btDelete'){
                    if(confirm("Are you sure to delete the data?")){
                        fmct.setItemValue("mode","delete");
                        document.getElementById("partmasterform").submit();
                        return true;
                    }
                }
                if(name=='btSave'){
                    var ee = fmct.isItemChecked("isview");
                    fmct.setItemValue("visview",ee);
                    fmct.setItemValue("mode","update");
                    document.getElementById("partmasterform").submit();
                    return true;
                }

            }
            return false;
        });
       // var mcal = fmct.getCalendar('duedate');
       // mcal.setSensitiveRange(gettoday(),null);
        dhxLayout.cells("b").attachObject("form_container");

        function loadAssignment(){
            getFiles('attachedfiles',<?php echo $id;?>);
            dhxLayout.cells("b").progressOn();
            fmct.load("<?php echo $_SERVER["PHP_SELF"];?>/getAssignview?id=<?php echo $id;?>&unqueId="+(new Date()).valueOf(),function(){
                dhxLayout.cells("b").progressOff();
            });

        }

        function myCallBack(message) {
            var div = document.createElement('div');
            div.textContent = message;
            showmsg("msgResult",div,0);
            self.location.href="/index.php/aliweb/assignments?unqueId="+(new Date()).valueOf();
        }



    </script>
