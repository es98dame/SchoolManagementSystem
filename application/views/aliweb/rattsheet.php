<div id="msgResult" style="display:none;top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 10; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #FFD700; border: 1px solid Black;"></div>
<div id="ErrorMsg" style="display: none; top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 999; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #EE82EE; border: 1px solid Black;"></div>
<div id="layoutObj" style="width:100%;height:600px;margin:-16px 0px 0px 0px;;padding:0px;overflow:hidden;"></div>
<div id="winVP" style="font-family: Tahoma; font-size: 11px; width: 100%; height:100%; overflow: hidden;">
    <div id="printform" style="font-family: Tahoma; font-size: 11px; width: 100%; height:50px; overflow: hidden;"></div>
    <div id="sheetform" style="font-family: Tahoma; font-size: 11px; width: 100%; height:100%; overflow: hidden;"></div>
</div>
<script>

    var	dhxLayout=new dhtmlXLayoutObject("layoutObj","2E");
    dhxLayout.cells("a").showHeader();
    dhxLayout.cells("a").setText("<div style=\"float:left;font-family: Tahoma; font-size: 15px; font-weight: bold; color: #696969;\">Attendance Sheet ( Remediation Class )</div>");
    dhxLayout.cells("a").setHeight(100);
    dhxLayout.cells("b").hideHeader();

    var gpclassfmData = [{type: "settings", position: "label-top",offsetLeft:10, labelWidth:80,inputWidth: "auto"},
        {type: "combo", label: "Year: ",inputWidth:80, name: "schoolyear", required: true, connector:"<?php echo $_SERVER["PHP_SELF"];?>/getComSchoolYear?unqueId="+(new Date()).valueOf() },
        {type: "newcolumn", offset:1},
        {type: "combo", label: "Trimester: ",inputWidth:80, name: "trimester", required: true, options:[
            {text: "", value: "", selected: true},
            {text: "1", value: "1"},
            {text: "2", value: "2"},
            {text: "3", value: "3"}
        ] },
        {type: "newcolumn", offset:1},
        {type: "button", name:"btSearch", value:"Search"} ];

    //var	gpclassfm = new dhtmlXForm("addform", gpclassfmData);
    var	gpclassfm = dhxLayout.cells("a").attachForm(gpclassfmData);
    gpclassfm.setSkin('dhx_skyblue');
    gpclassfm.attachEvent("onButtonClick", function(name,command){
        if(gpclassfm.validate()){
            if(name=='btSearch'){
                var v1 = this.getCombo("schoolyear").getSelectedValue();
                var v2 = this.getCombo("trimester").getSelectedValue();
                var oparams = "&year="+v1+"&trim="+v2;
                mainfm.getCombo("class_no").load("<?php echo $_SERVER["PHP_SELF"];?>/getComRClasses?unqueId="+(new Date()).valueOf()+oparams);



                /*
                var v1 = this.getCombo("schoolyear").getSelectedValue();
                var v2 = this.getCombo("trimester").getSelectedValue();
                var v4 = this.getCombo("level").getSelectedValue();
                var v5 = this.getCombo("session").getSelectedValue();
                var oparams = "&year="+v1+"&trim="+v2+"&level="+v4+"&session="+v5;
                subGrid.clearAndLoad("<?php echo $_SERVER["PHP_SELF"];?>/getRStudentList?unqueId="+(new Date()).valueOf()+oparams,function(){
                });
                */

                return false;
            }

        }
        return true;
    });

    //=== Grid of Family List ----------------------------------------------------------------
    var mainfmData = [
        {type: "fieldset",  name: "emydata", label: "Search Results",offsetLeft:10, width:"auto", list:[
            {type: "settings", position: "label-left",offsetLeft:1, labelWidth:90,inputWidth: "auto"},
            {type: "combo", label: "Class: ",inputWidth:140, name: "class_no", required: true},
            {type:"container",name:"studentGrid",label:"Students",inputWidth:280,inputHeight:240},
            {type: "input", style:"font-size:11px;", label: "Notes", value:"", rows:"4", inputWidth:280, name:"memo"},
            {type: "newcolumn", offset:40},
            {type: "combo", label: "Instructor: ",inputWidth:140, name: "teacher_no", required: true, connector:"<?php echo $_SERVER["PHP_SELF"];?>/getComTeachers?unqueId="+(new Date()).valueOf() },
            {type: "combo", label: "Month: ",inputWidth:140,required: true, name: "attmonth", options:[
                {text: "", value: "", selected: true},
                {text: "January", value: "01"},
                {text: "February", value: "02"},
                {text: "March", value: "03"},
                {text: "April", value: "04"},
                {text: "May", value: "05"},
                {text: "June", value: "06"},
                {text: "July", value: "07"},
                {text: "August", value: "08"},
                {text: "September", value: "09"},
                {text: "October", value: "10"},
                {text: "November", value: "11"},
                {text: "December", value: "12"}
            ] },
            {type: "button", name:"btAttendance", value:"Create Attendance Sheet"}
        ]}
    ];

    var	mainfm = dhxLayout.cells("b").attachForm(mainfmData);
    var cbclass = mainfm.getCombo("class_no");
    var evId = cbclass.attachEvent("onSelectionChange", function(){
        var v1 = gpclassfm.getCombo("schoolyear").getSelectedValue();
        var v2 = gpclassfm.getCombo("trimester").getSelectedValue();
      //  var v4 = gpclassfm.getCombo("level").getSelectedValue();
       // var v5 = gpclassfm.getCombo("session").getSelectedValue();
        var oparams = "&year="+v1+"&trim="+v2;
        subGrid.clearAndLoad("<?php echo $_SERVER["PHP_SELF"];?>/getRStudentList?unqueId="+(new Date()).valueOf()+oparams,function(){
        });
    });

    mainfm.setSkin('dhx_skyblue');
    mainfm.attachEvent("onButtonClick", function(name,command){
        if(mainfm.validate()){
            if(name=='btAttendance'){
                showNewWindow();
                return true;
            }
        }
        return false;
    });

    var subGrid = new dhtmlXGridObject(mainfm.getContainer("studentGrid"));
    subGrid.setImagePath("dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
    subGrid.setHeader("No,Student Name",null,["text-align:center;","text-align:center;"]);
    subGrid.setColumnIds("students_no,studentname");
    subGrid.setInitWidths("60,220");
    //subGrid.enableAutoWidth(true);
    subGrid.setColAlign("center,left");
    subGrid.setColTypes("ro,ro");
    subGrid.setColSorting("int,str");
    subGrid.setSkin("dhx_skyblue");
    // subGrid.init();

    var dhxWins = new dhtmlXWindows();
    dhxWins.setSkin("dhx_skyblue");
    var w1 = dhxWins.createWindow("w1",100,100,1100,400);
    w1.hide();
    w1.allowResize();
    //w1.center();
    //w1.button("minmax1").disable();
    w1.setModal(false);
    w1.attachEvent("onClose",function(win){
        if (win.getId() == "w1") {
            win.setModal(false);
            win.hide();
        }
    });


    var printfmData = [{type: "settings", position: "label-left",offsetLeft:1,labelWidth:0,inputWidth: "auto"},
        {type: "newcolumn", offset:10},
        {type: "button", name:"btExcel", value:"Export(.xlsx)"},
        {type: "newcolumn", offset:1},
        {type: "button", name:"btPDF", value:"Export(.pdf)"} ];

    var   printfm = new dhtmlXForm("printform",printfmData);
    printfm.setSkin('dhx_skyblue');
    printfm.attachEvent("onButtonClick", function(name,command){
        if(name=='btExcel'){
            sheetgrid.toExcel('http://'+$_SERVER['HTTP_HOST']+'/dhtmlxSuite/sources/dhtmlxGrid/codebase/grid-excel-php/generate.php?title=Attendance Sheet&filename=Attendance_Sheet(Remediation_Class)');
            return true;
        }
        if(name=='btPDF'){
            sheetgrid.toPDF('http://'+$_SERVER['HTTP_HOST']+'/dhtmlxSuite/sources/dhtmlxGrid/codebase/grid-pdf-php/generate.php?filename=Attendance_Sheet');
            return true;
        }
        return false;
    });


    var sheetgrid = new dhtmlXGridObject('sheetform');
    sheetgrid.setSkin("dhx_skyblue");
    //sheetgrid.setColumnIds("name,duedate,catename,isview");

    w1.attachObject("winVP");

    function showNewWindow(){
        uncnt = 0;
        var v1 = gpclassfm.getCombo("schoolyear").getSelectedValue();
        var v2 = gpclassfm.getCombo("trimester").getSelectedValue();
       // var v4 = gpclassfm.getCombo("level").getSelectedValue();
       // var level = gpclassfm.getCombo("level").getSelectedText();
       // var v5 = gpclassfm.getCombo("session").getSelectedText();
       // var session = gpclassfm.getCombo("session").getSelectedValue();
        var cno = mainfm.getCombo("class_no").getSelectedValue();
        var teacher = mainfm.getCombo("teacher_no").getSelectedText();
        var month = mainfm.getCombo("attmonth").getSelectedText();
        var mon = mainfm.getCombo("attmonth").getSelectedValue();
        var memo = mainfm.getItemValue("memo");
        var ddee = getMondays(v1,month);
        uncnt++;
        var trima= v2 + " Trimerster " + v1 + " " + month;
        sheetgrid.clearAll(true);
        sheetgrid.setImagePath("/dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
        sheetgrid.setHeader("ALI Class Attendance Sheet - Remediation Class,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan",null,["text-align:center"]); //,null,["text-align:left","text-align:left","text-align:left","text-align:left"]
        sheetgrid.attachHeader(trima+",#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan",["text-align:center"]);
        sheetgrid.attachHeader("Instructor,#cspan,"+teacher+",#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,Course,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,Session,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan"); //#cspan
        sheetgrid.attachHeader("Date,#cspan"+ddee+""); //#cspan
        sheetgrid.attachHeader("No,Student Name,#cspan,&nbsp;,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan,#cspan"); //#cspan
        sheetgrid.setInitWidths("26,220,25,25,25,25,25,25,25,25,25,25,25,25,25,25,25,25,25,25,25,25,25,25,25,25,25,25,25,25,25,25,25,25,25,25,25,25,25,25,25,25,120");
        sheetgrid.enableAutoWidth(true);
        sheetgrid.setColAlign("center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center,center");
        sheetgrid.setColTypes("ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro");
        sheetgrid.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str");
        sheetgrid.setSkin("dhx_skyblue");
        sheetgrid.enableColSpan(true);
        sheetgrid.setSizes();
        sheetgrid.init();
        sheetgrid.clearAndLoad("<?php echo $_SERVER["PHP_SELF"];?>/getRAttSheet?vyear="+v1+"&vtrim="+v2+"&vcno="+cno+"&vmon="+mon+"&ucnt="+uncnt+"&unqueId="+(new Date()).valueOf(),function(){
            var rowID=sheetgrid.getRowId(0);
            sheetgrid.cells(rowID,43-uncnt).setValue(memo);
        });

        w1.setText("Attendance Sheet - Remediation Class");
        w1.setModal(false);
        w1.show();
    }

    var uncnt=0;

    function getMondays(y,m) {
        var d = new Date(y+'/'+m+'/1');
        var month = d.getMonth();
        var monthur="";
        var k=1;

        while (d.getMonth() === month) {
            if(d.getDay() == 1||d.getDay() == 2||d.getDay() == 3||d.getDay() == 4){
                monthur = monthur + "," + d.getDate().toString() + "," + d.getDate().toString();
                k = k+2;
            }
            d.setDate(d.getDate() + 1);
        }
        monthur = monthur + ",Note";
        while(k<=40){
            monthur = monthur + ",#cspan";
            k++;
            uncnt++;
        }

        return monthur;
    }


</script>