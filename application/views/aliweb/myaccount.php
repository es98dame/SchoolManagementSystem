<div id="msgResult" style="display:none;top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 10; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #FFD700; border: 1px solid Black;"></div>
<div id="ErrorMsg" style="display: none; top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 999; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #EE82EE; border: 1px solid Black;"></div>
<div id="layoutObj" style="width:100%;height:600px;margin:-16px 0px 0px 0px;;padding:0px;overflow:hidden;"></div>

<script>
    window.onload=loadMyaccount;

    var	dhxLayout=new dhtmlXLayoutObject("layoutObj","1C");
    dhxLayout.cells("a").showHeader();
    dhxLayout.cells("a").setText("<div style=\"font-family: Tahoma; font-size: 15px; font-weight: bold; color: #696969;\">My Account</div>");

    var formData = [    {type: "settings", position: "label-left",offsetLeft:20, labelWidth: 80,inputWidth:200},
        {type: "colorpicker", imagePath: "dhtmlxSuite/sources/dhtmlxColorPicker/codebase/imgs/", value:"#52e56", label: "Color", name: "bgcolorone"},
        {type: "input", label: "First Name",name: "firstname",required: true },
        {type: "input", label: "Last Name",name: "lastname",required: true  },
        {type: "input", label: "Nickname",name: "nickname"},
        {type: "input", label: "Cellphone",name: "cellphone"},
        {type: "input", label: "Email",name: "email", required: true,validate: "NotEmpty,ValidEmail"  },
        {type: "input", label: "User ID",name: "user_ID",required: true, disabled:"true", validate: "NotEmpty" },
        {type: "password", label: "Password",name: "inst_pwd" },
        {type: "password", label: "Confirm Password",name: "inst_pwd2" },
        {type: "input", label: "Memo",name: "etc",rows:"3",inputWidth: 170 },
        {type: "input", label: "School Email",name: "schoolemail",note:{text:"Use for your webmail in Messages > Teacher Email."} },
        {type: "password", label: "School Password",name: "schoolpassword" },
        {type: "button", value: "Submit", name: "button_save"},
        {type: "hidden", name:"id"}
    ];
    var myFormAdd = dhxLayout.cells("a").attachForm(formData);
    myFormAdd.enableLiveValidation(true);
    myFormAdd.attachEvent("onButtonClick", function(name,command){
        if(myFormAdd.validate()){
            if(name=='button_save'){
                var pw1 = this.getItemValue('inst_pwd');
                var pw2 = this.getItemValue('inst_pwd2');
                if(pw1!=''&&pw2!=''){
                    if(pw1==pw2){
                        myFormAdd.resetDataProcessor("updated");
                        myFormAdd.save();
                        return true;
                    }else{
                        alert("Both password is not matched");
                        return false;
                    }
                }else{
                    myFormAdd.resetDataProcessor("updated");
                    myFormAdd.save();
                    return true;
                }
            }

        }
        return false;
    });

    function resetMyaccount(){
        myFormAdd.disableItem("button_save");
    }
    function loadMyaccount(){
        myFormAdd.load("myaccount/getMyaccount?unqueId="+(new Date()).valueOf());
    }
    function setdefaultform(){
        myFormAdd.setItemValue("inst_pwd","");
        myFormAdd.setItemValue("inst_pwd2","");
    }

    //-----------------------------------------------------------------
    var	dpAccount = new dataProcessor("myaccount/setMyaccount?unqueId="+(new Date()).valueOf()); //lock feed url
    dpAccount.init(myFormAdd);
    dpAccount.defineAction("updated",function(response){
        showfullmsg("msgResult","It has been "+response.textContent+" successfully.");
        setdefaultform();
        return true;
    });
    dpAccount.defineAction("invalid",function(response){
        showfullmsg("ErrorMsg","Invalid Data");
        return true;
    });

</script>