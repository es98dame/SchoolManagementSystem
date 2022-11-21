<div id="msgResult" style="display:none;top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 10; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #FFD700; border: 1px solid Black;"></div>
<div id="ErrorMsg" style="display: none; top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 999; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #EE82EE; border: 1px solid Black;"></div>
<div id="layoutObj" style="width:100%;height:600px;margin:-16px 0px 0px 0px;;padding:0px;overflow:hidden;"></div>
<div id="winVP" style="font-family: Tahoma; font-size: 11px; width: 100%; height:100%; overflow: hidden;">
    <div id="printform" style="font-family: Tahoma; font-size: 11px; width: 100%; height:50px; overflow: hidden;"></div>
    <div id="sheetform" style="font-family: Tahoma; font-size: 11px; width: 100%; height:100%; overflow: hidden;"></div>
</div>
<script>

    var	dhxLayout=new dhtmlXLayoutObject("layoutObj","3L");
    dhxLayout.cells("a").showHeader();
    dhxLayout.cells("a").setText("All Attendance");
    dhxLayout.cells("a").setWidth(220);
    dhxLayout.cells("b").setText("Export");
    dhxLayout.cells("b").setHeight(80);
    dhxLayout.cells("c").hideHeader();

    var gpclassfmData = [{type: "settings", position: "label-left",offsetLeft:10, labelWidth:100,inputWidth: "auto"},
        {type: "combo", label: "Year: ",inputWidth:80, name: "schoolyear", required: true, connector:"<?php echo $_SERVER["PHP_SELF"];?>/getComSchoolYear?unqueId="+(new Date()).valueOf() },
        {type: "combo", label: "Trimester: ",inputWidth:80, name: "trimester", required: true, options:[
            {text: "", value: "", selected: true},
            {text: "1", value: "1"},
            {text: "2", value: "2"},
            {text: "3", value: "3"}
        ] },
        {type: "combo", label: "Level: ",inputWidth:80, name: "level", required: true, connector:"<?php echo $_SERVER["PHP_SELF"];?>/getComLevel?unqueId="+(new Date()).valueOf() },
        {type: "combo", label: "Session: ",inputWidth:80, name: "session", required: true, options:[
            {text: "", value: "", selected: true},
            {text: "AM", value: "1"},
            {text: "AFT", value: "2"},
            {text: "PM", value: "3"}
        ] },
        {type: "combo", label: "Instructor: ",inputWidth:80, name: "teacher_no", required: true, connector:"<?php echo $_SERVER["PHP_SELF"];?>/getComTeachers?unqueId="+(new Date()).valueOf() },
        {type: "combo", label: "Class: ",required: true, name: "classtype", options:[
            {text: "", value: "", selected: true},
            {text: "LS", value: "LS"},
            {text: "RW", value: "RW"}
        ] },
        {type: "combo", label: "Form Month: ",required: true, name: "attfrommon", options:[
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
        {type: "combo", label: "To Month: ",required: true, name: "atttomon", options:[
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
        {type: "button", name:"btSearch", value:"Search"}

    ];

    var	gpclassfm = dhxLayout.cells("a").attachForm(gpclassfmData);
    gpclassfm.setSkin('dhx_skyblue');
    gpclassfm.attachEvent("onButtonClick", function(name,command){
        if(gpclassfm.validate()){
            if(name=='btSearch'){
                var v1 = this.getCombo("schoolyear").getSelectedValue();
                var v2 = this.getCombo("trimester").getSelectedValue();
                var v4 = this.getCombo("level").getSelectedValue();
                var v5 = this.getCombo("session").getSelectedValue();
                var teacher = this.getCombo("teacher_no").getSelectedText();
                var v6 = this.getCombo("attfrommon").getSelectedValue();
                var v7 = this.getCombo("atttomon").getSelectedValue();
                var v8 = this.getCombo("classtype").getSelectedValue();

                var oparams = "vyear="+v1+"&vtrim="+v2+"&vlevel="+v4+"&vsession="+v5+"&vfrommon="+v6+"&vtomon="+v7+"&vclasstype="+v8+"&teacher="+teacher;
                allattgrid.clearAndLoad("<?php echo $_SERVER["PHP_SELF"];?>/getAllAttSheet?"+oparams+"&unqueId="+(new Date()).valueOf(),function(){
                });

                return false;
            }

        }
        return true;
    });


    var allattgrid = dhxLayout.cells("c").attachGrid();
    allattgrid.setImagePath("/dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
    allattgrid.setSkin("dhx_skyblue");
    allattgrid.attachEvent("onRowSelect", function(id,ind){
    });
    allattgrid.attachEvent("onBeforeSorting",function(ind,type,direction){ window.cc_col = ind; window.cc_drt = ((direction == "des") ? "asc": "des"); return true; });
    allattgrid.init();



    var exportfmData = [{type: "settings", position: "label-left",offsetLeft:10, labelWidth:100,inputWidth: "auto"},
        {type: "button", name:"btExport", value:"Export(xslx"}
    ];
    var	exportfm = dhxLayout.cells("b").attachForm(exportfmData);
    exportfm.setSkin('dhx_skyblue');
    exportfm.attachEvent("onButtonClick", function(name,command){
        if(exportfm.validate()){
            if(name=='btExport'){
                allattgrid.toExcel('http://'+$_SERVER['HTTP_HOST']+'/dhtmlxSuite/sources/dhtmlxGrid/codebase/grid-excel-php/generate.php?title=AttendanceSheet&filename=Attendance_Sheet');
                return false;
            }

        }
        return true;
    });



</script>