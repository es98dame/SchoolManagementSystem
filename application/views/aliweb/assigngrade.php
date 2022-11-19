<div id="msgResult" style="display:none;top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 10; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #FFD700; border: 1px solid Black;"></div>
<div id="ErrorMsg" style="display: none; top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 999; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #EE82EE; border: 1px solid Black;"></div>
<div id="layoutObj" style="width:100%;height:600px;margin:-16px 0px 0px 0px;;padding:0px;overflow:hidden;"></div>
<div id="SubMenuId" style="margin-left:-20px;"><ul style="list-style:none;"><?php echo $param["menus"];?></ul></div>
<div id="myForm" ></div>

    <script>
        window.onload=loadGrades;

        var	dhxLayout=new dhtmlXLayoutObject("layoutObj","2U");
        dhxLayout.cells("a").showHeader();
        dhxLayout.cells("a").setText("Class Apps");
        dhxLayout.cells("a").setWidth(160);
        dhxLayout.cells("a").attachObject("SubMenuId");
        dhxLayout.cells("b").showHeader();
        dhxLayout.cells("b").setText("<div style=\"font-family: Tahoma; font-size: 15px; font-weight: bold; color: #696969; float:left;\"><?php echo $param["title"];?></div><div style=\"vertical-align: middle;margin-left:220px;margin-top:-12px;\" id=\"GPClassForm\"></div>");

        var gpclassfmData = [{type: "settings", position: "label-left", labelWidth:0,inputWidth: "auto"},
            {type: "button", name:"btNew", offsetLeft:20, value:"<i class=\"icon-plus\"></i> Add Assignment"} ];

        var	gpclassfm = new dhtmlXForm("GPClassForm", gpclassfmData);
            gpclassfm.setSkin('dhx_skyblue');
            gpclassfm.enableLiveValidation(true);
            gpclassfm.attachEvent("onButtonClick", function(name,command){
            if(name=='btNew'){
                self.location.href="/index.php/aliweb/assignnew?unqueId="+(new Date()).valueOf();
                return true;
            }
            return false;
        });

        //========================= Assignment Lines
        var formData = [    {type: "settings", position: "label-left", labelWidth: 80,inputWidth:200},
            {type: "hidden", name:"scorval"},
            {type: "hidden", name:"selcell"},
            {type: "hidden", name:"assignno"},
            {type: "hidden", name:"assigncatno"},
            {type: "hidden", name:"gradeno"},
            {type: "hidden", name:"stdno"},
            {type: "hidden", name:"class_no",value:<?php echo $param['classno'];?>},
            {type: "hidden", name:"id"}
        ];

        var   myFormAdd = new dhtmlXForm("myForm",formData);
        myFormAdd.setSkin('dhx_skyblue');
        myFormAdd.enableLiveValidation(true);

        var assigngrid = dhxLayout.cells("b").attachGrid();
        assigngrid.setImagePath("/dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
        assigngrid.setSkin("dhx_skyblue");
        assigngrid.attachEvent("onEditCell",function(stage,rowId,cellInd,newValue,oldValue){
            if(stage==0){

            }else if(stage==1){
                assigngrid.editor.obj.onkeypress = function(e){
                    var key = String.fromCharCode( e.keyCode);
                    var regex = /^[0-9\b]+$/;
                    if( !regex.test(key) ) {
                        return false;
                    }
                }
            }else if(stage==2){

                if(cellInd > 0){
                    var scorval = assigngrid.cells(rowId,cellInd).getValue();
                    var gradeno = assigngrid.cells(rowId,cellInd).getAttribute("gradeno");
                    var assignno = assigngrid.cells(rowId,cellInd).getAttribute("assignno");
                    var assigncatno = assigngrid.cells(rowId,cellInd).getAttribute("assigncatno");

                    myFormAdd.setItemValue("stdno",rowId);
                    myFormAdd.setItemValue("assignno",assignno);
                    myFormAdd.setItemValue("assigncatno",assigncatno);
                    myFormAdd.setItemValue("gradeno",gradeno);
                    myFormAdd.setItemValue("scorval",scorval);
                    myFormAdd.setItemValue("selcell",cellInd);

                    if(scorval!=oldValue) {
                        if (scorval != '') {
                            if(assignno!=''){

                                var newId = (new Date()).valueOf();
                                if (typeof(gradeno) == "undefined" || gradeno == "") {
                                    myFormAdd.resetDataProcessor("inserted");
                                    myFormAdd.save();
                                } else {
                                    myFormAdd.formId = rowId;
                                    myFormAdd.resetDataProcessor("updated");
                                    myFormAdd.save();
                                }
                            }else{
                                return false;
                            }

                        }else{
                            myFormAdd.formId = gradeno;
                            myFormAdd.resetDataProcessor("deleted");
                            myFormAdd.save();

                        }

                    }


                }
            }
            return true;
        });
        assigngrid.init();

        function loadGrades(){
            var oparams = "";
            if(window.am_drt){
                assigngrid.setSortImgState(true, window.am_col, window.am_drt,1);
                oparams="&orderby=" + window.am_col + "&direct=" + window.am_drt;
            }
            dhxLayout.cells("b").progressOn();
            assigngrid.clearAndLoad("<?php echo $_SERVER["PHP_SELF"];?>/getGrades?classno=<?php echo $param['classno'];?>&classname=<?php echo $param['classname'];?>&unqueId="+(new Date()).valueOf()+ oparams,function(){
                dhxLayout.cells("b").progressOff();
            });
        }

        var	dpgrade = new dataProcessor("<?php echo $_SERVER["PHP_SELF"];?>/setGrades?unqueId="+(new Date()).valueOf()); //lock feed url
        dpgrade.setTransactionMode("POST",true); //set mode as send-all-by-post
        dpgrade.setUpdateMode("off"); //disable auto-update
        dpgrade.enableDebug(true);
        dpgrade.init(myFormAdd); //link dataprocessor to the grid
        //	dpgrade.attachEvent("onBeforeUpdate",function(id,status, data){ return true; });
        dpgrade.defineAction("inserted",function(response){ showmsgwithgrade("msgResult",response,assigngrid); return true; });
        dpgrade.defineAction("updated",function(response){  showmsgwithgrade("msgResult",response,assigngrid); return true; });
        dpgrade.defineAction("deleted",function(response){ showmsgwithgrade("msgResult",response,assigngrid); return true; });
        dpgrade.defineAction("invalid",function(response){ showmsg("msgResult",response); return true; });

    </script>
