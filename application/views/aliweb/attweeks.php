<div id="msgResult" style="display:none;top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 10; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #FFD700; border: 1px solid Black;"></div>
<div id="ErrorMsg" style="display: none; top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 999; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #EE82EE; border: 1px solid Black;"></div>
<div id="layoutObj" style="width:100%;height:600px;margin:-16px 0px 0px 0px;;padding:0px;overflow:hidden;"></div>
<div id="SubMenuId" style="margin-left:-20px;"><ul style="list-style:none;"><?php echo $param["menus"];?></ul></div>

 <div id="myForm" ></div>

    <script>
        window.onload=loadAttendance;
        //========================= Layout
        var	dhxLayout=new dhtmlXLayoutObject("layoutObj","2U");
        dhxLayout.cells("a").showHeader();
        dhxLayout.cells("a").setText("Class Apps");
        dhxLayout.cells("a").setWidth(160);
        dhxLayout.cells("a").attachObject("SubMenuId");
        dhxLayout.cells("b").showHeader();
        dhxLayout.cells("b").setText("<div style=\"font-family: Tahoma; font-size: 15px; font-weight: bold; color: #696969; float:left;\"><?php echo $param["title"];?></div><div style=\"vertical-align: middle;margin-left:220px;margin-top:-12px;\" id=\"GPClassForm\"></div>");


        var gpclassfmData = [{type: "settings", position: "label-left", labelWidth:10,inputWidth: 100},
            {type: "calendar", className: "css_form1", label: "",offsetTop:8,name: "startdate",value:gettoday(), validate: "NotEmpty" },
            {type: "newcolumn", offset:2},
            {type: "button", name:"btSearch", offsetLeft:20, value:"Search"} ];
        var	gpclassfm = new dhtmlXForm("GPClassForm", gpclassfmData);
        gpclassfm.setSkin('dhx_skyblue');
        gpclassfm.enableLiveValidation(true);
        gpclassfm.attachEvent("onChange", function (name, value){
            if(name=='startdate'){
                loadAttendance();
            }
        });
        gpclassfm.attachEvent("onButtonClick", function(name,command){
            if(name=='btSearch'){
                loadAttendance();
                return true;
            }
            return false;
        });

        //-------------------------------------------------------------------------
        var formData = [    {type: "settings", position: "label-left", labelWidth: 80,inputWidth:200},
            {type: "hidden", name:"attval"},
            {type: "hidden", name:"attno"},
            {type: "hidden", name:"stdno"},
            {type: "hidden", name:"classno"},
            {type: "hidden", name:"selday"},
            {type: "hidden", name:"id"}
        ];

        var   myFormAdd = new dhtmlXForm("myForm",formData);
        myFormAdd.setSkin('dhx_skyblue');
        myFormAdd.enableLiveValidation(true);

        //========================= Assignment Lines
        var assigngrid = dhxLayout.cells("b").attachGrid();
        assigngrid.setImagePath("/dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
        assigngrid.setSkin("dhx_skyblue");
        assigngrid.attachEvent("onRowSelect", function(id,ind){});
        assigngrid.attachEvent("onEditCell",function(stage,rowId,cellInd,newValue,oldValue){
            if(stage==0){

            }else if(stage==1){
                if(assigngrid.editor.obj.value.length > 2) {
                    return false;
                }
                assigngrid.editor.obj.onkeypress = function(e){
                    var key = String.fromCharCode( e.keyCode);
                    var regex = new RegExp("[aAtTpP]{1}");
                    if( !regex.test(key) ) {
                        return false;
                    }
                }
                //alert("Cell editor opened");
            }else if(stage==2){

                if(cellInd > 0){ //MON
                    var attval = assigngrid.cells(rowId,cellInd).getValue().toString().toUpperCase();
                    var seld = assigngrid.cells(rowId,cellInd).getAttribute("selday");
                    var attno = assigngrid.cells(rowId,cellInd).getAttribute("attno");

                    myFormAdd.setItemValue("stdno",rowId); //studentid
                    myFormAdd.setItemValue("attno",attno);

                    myFormAdd.setItemValue("classno",<?php echo $param["classno"];?>); //classno
                    myFormAdd.setItemValue("attval",attval); //att value
                    myFormAdd.setItemValue("selday",seld); //selday


                    if(attval!=oldValue) {
                        if (attval != '') {
                            if (typeof(attno) == "undefined") {
                                var newId = (new Date()).valueOf();
                                myFormAdd.resetDataProcessor("inserted");
                                myFormAdd.save();
                            } else {
                                myFormAdd.formId = attno;
                                myFormAdd.resetDataProcessor("updated");
                                myFormAdd.save();
                            }
                            assigngrid.cells(rowId,cellInd).setValue(attval);

                        }else{
                            myFormAdd.formId = attno;
                            myFormAdd.resetDataProcessor("deleted");
                            myFormAdd.save();
                        }
                    }

                }
            }
            return true;
        });
        assigngrid.init();

        function loadAttendance(){
            var oparams = "";
            if(window.am_drt){
                assigngrid.setSortImgState(true, window.am_col, window.am_drt,1);
                oparams="&orderby=" + window.am_col + "&direct=" + window.am_drt;
            }
            var sday = gpclassfm.getItemValue("startdate",true);

            dhxLayout.cells("b").progressOn();
            assigngrid.clearAndLoad("<?php echo $_SERVER["PHP_SELF"];?>/getAttendance?unqueId="+(new Date()).valueOf()+ oparams+"&sday=" +sday,function(){
                dhxLayout.cells("b").progressOff();
            });
        }

       // dhxLayout.cells("b").attachObject("grade_container");

        //========================= DataProcessor

        var	dpatten = new dataProcessor("<?php echo $_SERVER["PHP_SELF"];?>/setAttendance?unqueId="+(new Date()).valueOf()); //lock feed url
        dpatten.setTransactionMode("POST",true); //set mode as send-all-by-post
        dpatten.setUpdateMode("off"); //disable auto-update
        dpatten.enableDebug(true);
        dpatten.init(myFormAdd); //link dataprocessor to the grid
        //dpatten.attachEvent("onBeforeUpdate",function(id,status, data){ return true; });
        dpatten.defineAction("inserted",function(response){ showmsg("msgResult",response); return true; });
        dpatten.defineAction("updated",function(response){ showmsg("msgResult",response); return true; });
        dpatten.defineAction("deleted",function(response){ showmsg("msgResult",response); return true; });
        dpatten.defineAction("invalid",function(response){ showmsg("msgResult",response); return true; });

    </script>
