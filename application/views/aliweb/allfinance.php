<div id="msgResult" style="display:none;top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 10; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #FFD700; border: 1px solid Black;"></div>
<div id="ErrorMsg" style="display: none; top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 999; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #EE82EE; border: 1px solid Black;"></div>
<div id="layoutObj" style="width:100%;height:600px;margin:-16px 0px 0px 0px;;padding:0px;overflow:hidden;"></div>

<script>
    //  window.onload=loadRoster;

    var	dhxLayout=new dhtmlXLayoutObject("layoutObj","2E");
    dhxLayout.cells("a").showHeader();
    dhxLayout.cells("a").setText("<div style=\"float:left;font-family: Tahoma; font-size: 15px; font-weight: bold; color: #696969;\">All Finance Sheet</div>");
    dhxLayout.cells("a").setHeight(100);
    dhxLayout.cells("b").hideHeader();

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
        {type: "button", name:"btExcel", value:"Export(.xlsx)"}];


    //var	gpclassfm = new dhtmlXForm("addform", gpclassfmData);
    var	gpclassfm = dhxLayout.cells("a").attachForm(gpclassfmData);
    gpclassfm.setSkin('dhx_skyblue');
    gpclassfm.attachEvent("onButtonClick", function(name,command){
        if(gpclassfm.validate()){
            if(name=='btSearch'){
                var v1 = this.getCombo("schoolyear").getSelectedValue();
                var v2 = this.getCombo("trimester").getSelectedValue();
                var oparams = "&year="+v1+"&trim="+v2;
                dhxLayout.cells("a").progressOn();
                grid_classgrid.clearAndLoad("<?php echo $_SERVER["PHP_SELF"];?>/getAllFinance?unqueId="+(new Date()).valueOf()+ oparams,function(){
                    dhxLayout.cells("a").progressOff();
                });
                return true;
            }
            if(name=='btExcel'){
                var v6 = this.getCombo("schoolyear").getSelectedValue() + "(" + this.getCombo("trimester").getSelectedValue() + "trimester)";
                grid_classgrid.toExcel('http://'+$_SERVER['HTTP_HOST']+'/dhtmlxSuite/sources/dhtmlxGrid/codebase/grid-excel-php/generate.php?title='+v6+'&filename=All_Finance_Sheet'+v6);
                return true;
            }
            if(name=='btPDF'){
                grid_classgrid.toPDF('http://'+$_SERVER['HTTP_HOST']+'/dhtmlxSuite/sources/dhtmlxGrid/codebase/grid-pdf-php/generate.php');
                return true;
            }
        }
        return false;
    });

    //=== Grid of Family List ----------------------------------------------------------------
    //    var grid_classgrid = new dhtmlXGridObject('rostergrid');
    var grid_classgrid = dhxLayout.cells("b").attachGrid();
    grid_classgrid.setImagePath("/dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
    //  grid_classgrid.setHeader("Year,Trimester,Session,Level1,Level2,Level3,Level4");
    //  grid_classgrid.setColumnIds("schoolyear,trimester,session,level1,level2,level3,level4");
    //  grid_classgrid.setInitWidths("60,70,60,130,130,130");
    //  grid_classgrid.setColAlign("center,center,center,center,center,center");
    //  grid_classgrid.setColTypes("coro,coro,coro,ro,ro,ro");
    //  grid_classgrid.setColSorting("str,str,int,str,str,str");
    grid_classgrid.setSkin("dhx_skyblue");
    grid_classgridgrid.attachEvent("onRowSelect", function(id,ind){
    });
    grid_classgrid.attachEvent("onBeforeSorting",function(ind,type,direction){ window.cc_col = ind; window.cc_drt = ((direction == "des") ? "asc": "des"); return true; });
    grid_classgrid.init();




    // dhxLayout.cells("a").attachObject("rosterContainer");


</script>