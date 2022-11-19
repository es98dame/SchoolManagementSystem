<div id="msgResult" style="display:none;top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 10; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #FFD700; border: 1px solid Black;"></div>
<div id="ErrorMsg" style="display: none; top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 999; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #EE82EE; border: 1px solid Black;"></div>
<div id="layoutObj" style="width:100%;height:600px;margin:-16px 0px 0px 0px;;padding:0px;overflow:hidden;"></div>

<script>
    window.onload=loadMyaccount;

    var	dhxLayout=new dhtmlXLayoutObject("layoutObj","1C");
    dhxLayout.cells("a").showHeader();
    dhxLayout.cells("a").setText("<div style=\"font-family: Tahoma; font-size: 15px; font-weight: bold; color: #696969;\">My Account</div>");
/*
    var formData = [    {type: "settings", position: "label-left",offsetLeft:20, labelWidth: 80,inputWidth:200},
        {type: "input", label: "First Name",name: "firstname",required: true,disabled:"true" },
        {type: "input", label: "Last Name",name: "lastname",required: true,disabled:"true"  },
        {type: "input", label: "Nickname",name: "nickname",disabled:"true"},
        {type: "input", label: "Cellphone",name: "cellphone",disabled:"true"},
        {type: "input", label: "Email",name: "email", required: true,disabled:"true",validate: "NotEmpty,ValidEmail"  },
        {type: "input", label: "User ID",name: "user_ID",required: true, disabled:"true", validate: "NotEmpty" },
        {type: "password", label: "Password",name: "inst_pwd" },
        {type: "password", label: "Confirm Password",name: "inst_pwd2" },
        {type: "input", label: "Memo",name: "note",rows:"3",inputWidth: 170,disabled:"true" },
        {type: "button", value: "Submit", name: "button_save"},
        {type: "hidden", name:"id"}
    ];
*/
    var formData = [{ type: "settings", position: "label-left", labelWidth: 100},

            {type: "block",style:"font-size:10px;",offsetTop:5, list:[
                {type: "input",   inputWidth: 180, style:"font-size:11px;", label: "First Name",value:"", name:"firstname", className:"myLabel", validate: "NotEmpty", readonly:true, disabled: true},
                {type: "input",   inputWidth: 180, style:"font-size:11px;", label: "Last Name",value:"", name:"lastname", className:"myLabel", validate: "NotEmpty", readonly: true, disabled: true},
                {type: "input",  style:"font-size:11px;",inputWidth: 180, label: "Nickname", value:"", name:"nickname", className:"myLabel", readonly: true},
                {type: "input",  inputWidth: 180, style:"font-size:11px;", label: "Nationality",value:"", name:"country", className:"myLabel", validate: "NotEmpty", readonly: true,disabled: true},
                {type: "input",  inputWidth: 180, style:"font-size:11px;", label: "Phone #1",value:"", name:"cellphone", className:"myLabel", validate: "NotEmpty", readonly: true},
                {type: "input",  inputWidth: 180, style:"font-size:11px;", label: "Phone #2",value:"", name:"cellphone2", className:"myLabel", readonly: true},
                {type: "input",  inputWidth: 180, style:"font-size:11px;", label: "E-mail #1",value:"", name:"email", className:"myLabel"},
                {type: "input",  inputWidth: 180, style:"font-size:11px;", label: "E-mail #2",value:"", name:"email2", className:"myLabel", readonly: true},
                {type: "input",  inputWidth: 180, style:"font-size:11px;", label: "Current Address",value:"", name:"address1", className:"myLabel", readonly: true, disabled: true},
                {type: "input",  inputWidth: 180, style:"font-size:11px;", label: "               ",value:"", name:"address2", className:"myLabel", readonly: true, disabled: true},
                {type: "input",  inputWidth: 180, style:"font-size:11px;", label: "Previous School",value:"", name:"preschool", className:"myLabel", readonly: true},
                {type: "input",  inputWidth: 180, style:"font-size:11px;", label: "Transfer to School", value:"", name:"transfer", className:"myLabel", readonly: true},
                {type: "input", style:"font-size:11px;", label: "Memo", value:"", rows:"3", inputWidth:180, name:"memo", className:"myLabel", readonly: true},
                {type: "newcolumn", offset: 20 },
                {type: "input",  inputWidth: 180, style:"font-size:11px;", label: "SEVIS ID #", value:"", name:"student_ID", className:"myLabel", readonly: true, disabled: true},
                { type: "calendar", dateFormat:"%Y-%m-%d", name: "birthday", label: "D.O.B.",value:"", className:"myLabel", validate: "NotEmpty", disabled: true},
                {type: "select", style:"font-size:11px;", label: "Gender",name: "gender", options: [{ value: "f",text: "Female"}, {value: "m",text: "Male" }] , disabled: true, className:"myLabel"},
                {type: "input",  inputWidth: 180, style:"font-size:11px;", label: "Emergency #1",value:"", name:"emergencyphone", className:"myLabel", readonly: true},
                {type: "input",  inputWidth: 180, style:"font-size:11px;", label: "Emergency #2",value:"", name:"emergencyphone2", className:"myLabel", readonly: true},
                { type: "editor", name: "note", label: "Note", inputWidth: 180, inputHeight: 70, value: "" , className:"myLabel", disabled: true},
                { type: "input",  inputWidth: 180, style:"font-size:11px;",rows: 2, label: "VISA Status",value:"", name:"etc_memo", className:"myLabel", readonly: true},
                { type: "calendar", dateFormat:"%Y-%m-%d", name: "register_day", label: "Start Date",value:"", className:"myLabel", disabled: true},
                { type: "input",  inputWidth: 140, style:"font-size:11px;", label: "Web ID",value:"", name:"user_ID", className:"myLabel", readonly: true},
                {type: "password", label: "Password",name: "inst_pwd" },
                {type: "password", label: "Confirm Password",name: "inst_pwd2" },
                {type: "hidden", name:"id"}
            ]},
            {type: "block", style:"font-size:10px; text-align:center;",offsetLeft:250, offsetTop:5, list:[
                {type: "button", value: "Save", name: "button_save"}]}
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