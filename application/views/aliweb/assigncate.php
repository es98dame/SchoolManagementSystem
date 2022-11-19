<div id="msgResult" style="display:none;top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 10; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #FFD700; border: 1px solid Black;"></div>
<div id="ErrorMsg" style="display: none; top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 999; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #EE82EE; border: 1px solid Black;"></div>
<div id="layoutObj" style="width:100%;height:600px;margin:-16px 0px 0px 0px;;padding:0px;overflow:hidden;"></div>
<div id="SubMenuId" style="margin-left:-20px;"><ul style="list-style:none;"><?php echo $param["menus"];?></ul></div>


    <div id="winVP"></div>

    <script>
        window.onload=loadCate;

        //========================= Layout
        var	dhxLayout=new dhtmlXLayoutObject("layoutObj","2U");
        dhxLayout.cells("a").showHeader();
        dhxLayout.cells("a").setText("Class Apps");
        dhxLayout.cells("a").setWidth(160);
        dhxLayout.cells("a").attachObject("SubMenuId");
        dhxLayout.cells("b").showHeader();
        dhxLayout.cells("b").setText("<div style=\"font-family: Tahoma; font-size: 15px; font-weight: bold; color: #696969; float:left;\"><?php echo $param["title"];?></div><div style=\"vertical-align: middle;margin-left:60px;margin-top:-12px;\" id=\"GPClassForm\"></div>");

        var gpclassfmData = [{type: "settings", position: "label-left", labelWidth:0,inputWidth: "auto"},
            {type: "button", name:"btNew", offsetLeft:0, value:"<i class=\"icon-plus\"></i> Add Category"},
            {type: "newcolumn", offset:1},
            {type: "button", name:"btLast", offsetLeft:10, value:"<i class=\"icon-plus\"></i> Copy Default Categories"}];

        var	gpclassfm = new dhtmlXForm("GPClassForm", gpclassfmData);
        gpclassfm.setSkin('dhx_skyblue');
        gpclassfm.enableLiveValidation(true);
        gpclassfm.attachEvent("onButtonClick", function(name,command){
            if(name=='btNew'){
                showNewWindow();
                return true;
            }
            if(name=='btLast'){
                if(confirm('Do you  want to get default value?')) {
                    dhx4.ajax.post("<?php echo $_SERVER["PHP_SELF"];?>/setDefaultCategories?unqueId="+(new Date()).valueOf(), null, function(r){
                        var xml = r.xmlDoc.responseXML;
                        var nodes = xml.getElementsByTagName("data");
                        var msg = nodes[0].childNodes[0].nodeValue;
                        showfullmsg("msgResult",msg);
                        loadCate();
                    });
                    return true;
                }else{
                    return false;
                }
            }
            return false;
        });

        //========================= Cate Lines
        var categrid = dhxLayout.cells("b").attachGrid();
        categrid.setImagePath("/dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
        categrid.setHeader("Cate,Percentage,Edit",null,["text-align:left","text-align:center","text-align:left"]);
        categrid.setColumnIds("name,wpercentage,&nbsp;");
        categrid.setInitWidths("240,100,100");
        categrid.setColAlign("left,center,center");
        categrid.setColTypes("ro,ro,ro");
        categrid.setColSorting("str,str,str");
        categrid.setSkin("dhx_skyblue");
        categrid.attachEvent("onRowSelect", function(id,ind){});
        categrid.attachEvent("onBeforeSorting",function(ind,type,direction){ window.ct_col = ind; window.ct_drt = ((direction == "des") ? "asc": "des"); return true; });
        categrid.init();
        function loadCate(){
            var oparams = "";
            if(window.ct_drt){
                categrid.setSortImgState(true, window.ct_col, window.ct_drt,1);
                oparams="&orderby=" + window.ct_col + "&direct=" + window.ct_drt;
            }
            dhxLayout.cells("b").progressOn();
            categrid.clearAndLoad("<?php echo $_SERVER["PHP_SELF"];?>/getAssignCategories?unqueId="+(new Date()).valueOf()+ oparams,function(){
                dhxLayout.cells("b").progressOff();
            });
        }

        var formData = [    {type: "settings", position: "label-top",offsetLeft:25,offsetTop:5, labelWidth: 100,inputWidth: "auto"},
            {type: "input", className: "css_form1", label: "Cate Name: ",inputWidth:200, name: "name", required: true, validate: "NotEmpty"  },
            {type: "input", className: "css_form1", label: "Percentage: ",name: "wpercentage", required: true, validate: "NotEmpty" },
            {type: "hidden", name:"class_no"},
            {type: "hidden", name:"id", value:""}
        ];

        var dhxWins = new dhtmlXWindows();
        dhxWins.setSkin("dhx_skyblue");
        var w1 = dhxWins.createWindow("w1", 320, 200, 330, 260);
        w1.hide();
        //w1.center();
        w1.button("minmax1").disable();
        w1.setModal(false);
        w1.attachEvent("onClose",function(win){
            if (win.getId() == "w1") {
                win.setModal(false);
                win.hide();
            }
        });

        var editForm = w1.attachForm(formData);
        editForm.setSkin('dhx_skyblue');
        editForm.enableLiveValidation(true);

        editForm.attachEvent("onButtonClick", function(name,command){
            if(editForm.validate()){
                if(name=='button_delete'){
                    if(confirm('Do you really want to delete it?')){
                        editForm.disableItem(name);
                        editForm.resetDataProcessor("deleted");
                        editForm.save();
                    }
                }
                if(name=='button_save'){
                    editForm.disableItem(name);
                    editForm.resetDataProcessor("updated");
                    editForm.save();
                }
                if(name=='button_new'){
                    editForm.setItemValue("class_no",<?php echo $param["classno"];?>);
                    editForm.disableItem(name);
                    editForm.resetDataProcessor("inserted");
                    editForm.save();
                }
                return true;
            }
            return false;
        });

        var	dpClass = new dataProcessor("<?php echo $_SERVER["PHP_SELF"];?>/setAssignCategory?unqueId="+(new Date()).valueOf()); //lock feed url
        dpClass.setTransactionMode("POST",true); //set mode as send-all-by-post
        dpClass.enableDebug(true);
        dpClass.init(editForm);
        dpClass.attachEvent("onBeforeUpdate",function(id,status, data){ return true;	});
        dpClass.defineAction("updated",function(response){
            showmsg("msgResult",response);
            categrid.updateFromXML("<?php echo $_SERVER["PHP_SELF"];?>/getAssignCategories?unqueId="+(new Date()).valueOf(),true,true,function(){
                w1.setModal(false);
                w1.hide();
            });
            return true;
        });
        dpClass.defineAction("inserted",function(response){
            showmsg("msgResult",response);
            categrid.updateFromXML("<?php echo $_SERVER["PHP_SELF"];?>/getAssignCategories?unqueId="+(new Date()).valueOf(),true,true,function(){
                w1.setModal(false);
                w1.hide();
            });
            return true;
        });
        dpClass.defineAction("deleted",function(response){
            showmsg("msgResult",response);
            categrid.updateFromXML("<?php echo $_SERVER["PHP_SELF"];?>/getAssignCategories?unqueId="+(new Date()).valueOf(),true,true,function(){
                w1.setModal(false);
                w1.hide();
            });
            return true;
        });
        dpClass.defineAction("invalid",function(response){
            showfullmsg("ErrorMsg","It has some data included others");
            return true;
        });


        function showNewWindow(){
            editForm.clear();
            editForm.removeItem("button_save");
            editForm.removeItem("button_new");
            editForm.removeItem("button_delete");
            var btninsert = {type: "button", value:"Insert", name: "button_new", offsetLeft:100};
            editForm.addItem(null,btninsert,19);

            w1.setText("New Category");
            w1.setModal(true);
            w1.show();
        }

        function doOnLoadInfo(sno){
            editForm.clear();
            editForm.removeItem("button_delete");
            editForm.removeItem("button_save");
            editForm.removeItem("button_new");
            var btnblock = {type: "block",name:"btnblock", offsetLeft:6 , list:[ {type: "button", value: "Update",offsetTop:5, name: "button_save"},{type: "newcolumn", offset:2},{type: "button", className: "css_button", value: "Delete",offsetTop:5, name: "button_delete"}]};
            editForm.addItem(null,btnblock,19);
            w1.progressOn();
            editForm.load("<?php echo $_SERVER["PHP_SELF"];?>/getAssignCategory?id="+sno+"&unqueId="+(new Date()).valueOf(),function(){
                w1.progressOff();
            });
            w1.setText("Edit Category");
            w1.setModal(true);
            w1.show();
        }

    </script>
