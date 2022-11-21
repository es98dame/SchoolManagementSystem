
<div id="msgResult" style="display:none;top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 10; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #FFD700; border: 1px solid Black;"></div>
<div id="ErrorMsg" style="display: none; top: 50px; left: 40%; margin-top: 0px; margin-left: 0px; position: absolute; z-index: 999; color: Navy; font-weight: bold; font-size: 11px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px; padding-top: 3px; background: #EE82EE; border: 1px solid Black;"></div>
<div id="layoutObj" style="display: block;width:100%;height:700px;margin:-16px 0px 0px 0px;;padding:0px;overflow:auto;"></div>
<div id="recordContainer" style="position: relative;width: 100%;height: 100%;overflow: auto;">
    <div id="addform" style="position: relative;width: 100%;height: 50px;overflow: auto;"></div>
    <div id="recordgrid" style="position: relative;width: 100%;height: 100%;overflow: auto;"></div>
</div>
<div id="financeContainer" style="position: relative;width: 100%;height: 100%;overflow: auto;">
    <div id="addfinanceform" style="position: relative;width: 100%;height: 50px;overflow: auto;"></div>
    <div id="financegrid" style="position: relative;width: 100%;height: 100%;overflow: auto;"></div>
</div>
<div id="stdrecordContainer" style="position: relative;width: 100%;height: 100%;overflow: auto;">
    <div class="container" style="padding-top:1px;margin-top:1px;padding-left:10px;margin-left:10px;">
        <div class="row">
            <div id="tri" class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div id="btngroup" class="btn-group" data-toggle="buttons"></div>
            </div>
        </div>
    </div>

    <div class="container" style="padding-top:1px;margin-top:1px;padding-left:10px;margin-left:10px;">
        <div class="row">
            <div id="attLS" class="col-sm-6 col-md-6" style="padding: 1px 1px 1px 1px;width:330px; height:100%; overflow-x: auto; overflow-y: auto;">
                <div id="title">Attendance <div style="float:right;"><input type="button" data-target="attLSbox" class="btn btn-xs PrintButton" value="Print" />

                    </div></div>
                <div id="attLSbox" style="padding: 0px 5px 0px 5px; width:100%;height:630px; background-color:white;"></div>
            </div>
            <div id="gradels" class="col-sm-6 col-md-6" style="padding: 1px 1px 1px 1px;width:460px; height:100%;">
                <div id="title">Student Grade <div style="float:right;"><input type="button" data-classno="" id="btnLSgrade" class="btn btn-xs " value="Print" />
                        <input type="button" class="btn btn-xs" name="aaa1" data-classno="" id="exportLSgrade" value="Export(.xlsx)">
                    </div>
                    <div id="lsbox"  style="margin: 0px; padding: 0px;width:100%; height:630px;background-color:white;"></div>
                </div>
            </div>
        </div>

        <div class="container" style="padding-top:1px;margin-top:1px;padding-left:0px;margin-left:0px;">
            <div class="row">
                <div id="attRW" class="col-sm-6 col-md-6" style="padding: 1px 1px 1px 1px;width:330px; height:100%; overflow-x: auto; overflow-y: auto;">
                    <div id="title">Attendance <div style="float:right;"><input type="button" data-target="attRWbox" class="btn btn-xs PrintButton" value="Print" /></div></div>
                    <div id="attRWbox" style="padding: 0px 5px 0px 5px; width:100%;height:630px; background-color:white;"></div>
                </div>
                <div id="graderw" class="col-sm-6 col-md-6" style="padding: 1px 1px 1px 1px;width:460px; height:100%;">
                    <div id="title">Student Grade   <div style="float:right;"><input type="button" data-classno="" id="btnRWgrade"  class="btn btn-xs" value="Print" />
                            <input type="button" class="btn btn-xs" name="aaa2" data-classno="" id="exportRWgrade" value="Export(.xlsx)">
                        </div>
                        <div id="rwbox"  style="margin: 0px; padding: 0px;width:100%; height:630px;background-color:white;"></div>
                    </div>
                </div>
            </div>

            <div class="container" style="padding-top:1px;margin-top:1px;padding-left:0px;margin-left:0px;">
                <div class="row">
                    <div id="rAtt" class="col-sm-6 col-md-6" style="padding: 1px 1px 1px 1px;width:330px; height:100%; overflow-x: auto; overflow-y: auto;">
                        <div id="title">Remediation Attendance <div style="float:right;"><input type="button" data-target="rAttbox" class="btn btn-xs PrintButton" value="Print" /></div>
                        </div>
                        <div id="rAttbox" style="padding: 0px 5px 0px 5px; width:100%;height:630px; background-color:white;"></div>
                    </div>
                </div>
            </div>

        </div>

        <div id="pagination">
            <div id="pageNavPosition"></div>
            <div><input type="button" name="aaa3" value="Export(.xlsx)" onclick="exportStudentlist();"></div>
        </div>

        <div id="winVP" style="font-family: Tahoma; font-size: 11px; width: 100%; height:100%; overflow: hidden;"></div><div id="winVP3" style="font-family: Tahoma; font-size: 11px; width: 100%; height:100%; overflow: hidden;"></div>

        <style type="text/css">
            #attLS,#attRW,#rAtt,#gradels,#graderw {
                background:lightGrey;
                border-radius: 10px 10px 10px 10px;
                padding-left: 5px;
                margin-left: 5px;
                margin-top: 15px;
            }
            #title {
                padding:5px;
            }

            .formtable {
                border-top:1px solid rgb(240,240,240);
                border-left:1px solid rgb(240,240,240);
                padding:0;
                width:300px;
                empty-cells:show;
                margin-bottom:20px;
            }

            .formtable td {
                background:rgb(255,255,255);
                border-bottom:1px solid rgb(240,240,240);
                border-right:1px solid rgb(240,240,240);
                padding:4px;
                margin:0;
                vertical-align:top;
                text-align:left;
                font-size:8pt;
            }

            .formtable td.tableinfo {
                font-weight:bold;
                background:rgb(230,230,230);
                padding:6px 4px;
            }

            .formtable td.tablecol {
                background:rgb(245,245,245);
                font-size:10px;
                text-transform:uppercase;
            }

            .month {
                width:290px;
                float:left;
                margin-right:20px;
            }

            .month td {
                padding:2px;
            }

            .month td a i {
                font-style:normal;
            }

            .month td a {
                color:rgb(128,128,128);
            }

            .month td a:hover {
                color:rgb(0,85,255);
            }


            #music {
                background-color: white;
                color:black;
                display: block;
                height: 18px;
                line-height: 18px;
                text-decoration: none;
                width: 60px;
                text-align: center;
                float:right;
                border:1px solid black;
                margin:4px 0px 0px 3px;
            }
            .pg-normal {
                color: black;
                font-weight: normal;
                text-decoration: none;
                cursor: pointer;
            }
            .pg-selected {
                color: black;
                font-weight: bold;
                text-decoration: underline;
                cursor: pointer;
            }

            /* to change color, style for label 01172018 */
            .dhxform_obj_dhx_skyblue .myLabel2 div.dhxform_txt_label2{
                color: black;
                text-decoration: underline;
            }

            /* enabled, not checked */
            .dhxform_obj_material div.dhxform_img.btn2state_0 {
                background-image: url("../../../dhtmlxSuite/samples/dhtmlxForm/common/button2state/toggle_off.png");
                width: 42px;
                height: 24px;
            }
            /* enabled, checked */
            .dhxform_obj_material div.dhxform_img.btn2state_1 {
                background-image: url("../../../dhtmlxSuite/samples/dhtmlxForm/common/button2state/toggle_on.png");
                width: 42px;
                height: 24px;
            }
            /* disabled, not checked */
            .dhxform_obj_material div.disabled div.dhxform_img.btn2state_0 {
                background-image: url("../../../dhtmlxSuite/samples/dhtmlxForm/common/button2state/toggle_off_dis.png");
                width: 42px;
                height: 24px;
            }
            /* disabled, checked */
            .dhxform_obj_material div.disabled div.dhxform_img.btn2state_1 {
                background-image: url("../../../dhtmlxSuite/samples/dhtmlxForm/common/button2state/toggle_on_dis.png");
                width: 42px;
                height: 24px;
            }

            /* common */
            /* fix label align a bit */
            .dhxform_obj_material div.dhxform_item_label_right div.dhxform_label div.dhxform_label_nav_link {
                padding-top: 2px;
            }
        </style>

        <script>


        var totalrows = 0;
            var pages = 0;
            var itemsPerPage = 0;
            var currentPage = 1;

            function showPage(pageNumber) {
                var oldPageAnchor = document.getElementById('pg'+currentPage);
                if (oldPageAnchor) {
                    oldPageAnchor.className = 'pg-normal';
                }
                currentPage = pageNumber;
                var newPageAnchor = document.getElementById('pg'+currentPage);
                if (newPageAnchor) {
                    newPageAnchor.className = 'pg-selected';
                }
            }
            function prev() {
                if (currentPage > 1){
                    //showPage(currentPage - 1);
                }

            }

            function next() {
                if (currentPage < pages) {
                    //showPage(currentPage + 1);
                }
            }

            function init(totalrows) {
                pages = Math.ceil(totalrows / itemsPerPage);
            }

            function showPageNav(positionId) {
                var element = document.getElementById(positionId);

                var pagerHtml = '<span onclick="prev();" class="pg-normal"> &#171 Prev </span> | ';
                for (var page = 1; page <= pages; page++)
                    pagerHtml += '<span id="pg' + page + '" class="pg-normal" onclick="loadStudents(' + page + ');">' + page + '</span> | ';
                pagerHtml += '<span onclick="next();" class="pg-normal"> Next &#187;</span>';

                element.innerHTML = pagerHtml;
            }


            document.getElementById("layoutObj").style.width = document.body.style.width;
            window.onload = loadRank;
            window.dhx_globalImgPath = "dhtmlxSuite/dhtmlxCombo/codebase/imgs/";

            //============= Layout
            var dhxLayout;
            dhxLayout=new dhtmlXLayoutObject("layoutObj","3J"); //3L
            dhxLayout.cells("a").setWidth(500);
            dhxLayout.cells("a").setHeight(190);
            dhxLayout.cells("a").setText('Student List');
            dhxLayout.cells("b").hideHeader();
            dhxLayout.cells("c").hideHeader();

            //============= Cell B Tab List
            var dhxTabbar = dhxLayout.cells("b").attachTabbar();
            dhxTabbar.setSkin('dhx_skyblue');
            dhxTabbar.addTab("b1","Student Summary","150px");
            dhxTabbar.addTab("b6","Academic Records","150px");
            dhxTabbar.addTab("b7","Student Records","150px");
            dhxTabbar.addTab("b3","Consultations","100px");
            dhxTabbar.addTab("b8","Financials","100px");
            dhxTabbar.addTab("b2","Family","100px");
            dhxTabbar.addTab("b4","Requests","100px");
            dhxTabbar.addTab("b5","Files","100px");
            dhxTabbar.tabs("b1").setActive();
            dhxTabbar.attachEvent("onSelect", function(id,last_id){
                var selectedId=grid_stulist.getSelectedRowId();
                if(selectedId<=0){
                    return false;
                }
                return true;
            });


        //============= Cell A- Search Form (Student list)
        var searchfmData = [{type: "settings", position: "label-right", labelWidth:200,inputWidth: "auto"},
            {type: "block", width: 496, offsetLeft:0, list:[
                {type: "checkbox", name: "all", labelWidth:30,label: "All", checked:true},
                {type: "newcolumn", offset:0},
                {type: "checkbox", name: "s1", labelWidth:30,label: "COS", value:"o", checked:true},
                {type: "newcolumn", offset:0},
                {type: "checkbox", name: "s2", labelWidth:40,label: "Active", value:"r", checked:true},
                {type: "newcolumn", offset:0},
                {type: "checkbox", name: "s3", labelWidth:50,label: "Vacation", value:"v", checked:true},
                {type: "newcolumn", offset:0},
                {type: "checkbox", name: "s4", labelWidth:60,label: "Complete", value:"l", checked:true},
                {type: "newcolumn", offset:0},
                {type: "checkbox", name: "s5", labelWidth:60,label: "Acceptance", value:"a", checked:true},
                {type: "newcolumn", offset:0},
                {type: "checkbox", name: "s6", labelWidth:60,label: "Med-Leave", value:"m", checked:true},
                {type: "newcolumn", offset:0},
                {type: "checkbox", name: "s7", labelWidth:60,label: "Cancelled", value:"f", checked:true},
                {type: "newcolumn", offset:0},
                {type: "checkbox", name: "s8", labelWidth:70,label: "Consultations", value:"c", checked:true},
                {type: "newcolumn", offset:0},
                {type: "checkbox", name: "s9", labelWidth:80,label: "COS-Approved", value:"s", checked:true},
                {type: "newcolumn", offset:0},
                {type: "checkbox", name: "s10", labelWidth:90,label: "Withdrawn-AEW", value:"h", checked:true},
                {type: "newcolumn", offset:0},
                {type: "checkbox", name: "s11", labelWidth:100, label: "Initial-Transfer in", value:"n", checked:true},
                {type: "newcolumn", offset:0},
                {type: "checkbox", name: "s12", labelWidth:120, label: "Continuing Education", value:"d", checked:true},
                {type: "newcolumn", offset:0},
                {type: "checkbox", name: "s13", labelWidth:120, label: "Initial-COS Approved", value:"p", checked:true},
                {type: "newcolumn", offset:0},
                {type: "checkbox", name: "s14", labelWidth:110, label: "Initial-Visa Interview", value:"e", checked:true},
                {type: "newcolumn", offset:0},
                {type: "checkbox", name: "s15", labelWidth:130, label: "Withdrawn-Transfer out", value:"w", checked:true},
                {type: "newcolumn", offset:0},
                {type: "checkbox", name: "s16", labelWidth:180, label: "Withdrawn-Terminated. No Show", value:"t", checked:true}
            ]},
            {type: "newcolumn", offset:1},
            {type: "select", style:"font-size:11px;",offsetLeft:10, label: "",name: "keyword", options: [{ value: "1",text: "Email"}, {value: "2",text: "Nationality" }, {value: "3",text: "D.O.B" }, {value: "4",text: "Start Date" }] , className:"myLabel", required: true},
            {type: "newcolumn", offset:1},
            {type: "input",   inputWidth: 180, style:"font-size:11px;", label: "",value:"", name:"searchword", className:"myLabel", validate: "NotEmpty", required: true},
            {type: "newcolumn", offset:1},
            {type: "button", name:"btSearch",offsetTop:0, offsetLeft:10, value:"Search"},
            {type: "newcolumn", offset:1},
            {type: "button", name:"btExport",offsetTop:0, offsetLeft:10, value:"Export"}];

        var	searchfm  = dhxLayout.cells("a").attachForm(searchfmData);
        searchfm.setSkin('dhx_skyblue');
        searchfm.enableLiveValidation(true);
        searchfm.attachEvent("onChange", function(name,value,is_checked){
            if(name=="all"){
                if(is_checked){
                    searchfm.setFormData({s1: true,s2: true,s3: true,s4: true,s5:true,s6:true,s7:true,s8:true,s9:true,s10:true,s11:true,s12:true,s13:true,s14:true,s15:true,s16:true});
                }else{
                    searchfm.setFormData({s1: false,s2: false,s3: false,s4: false,s5:false,s6:false,s7:false,s8:false,s9:false,s10:false,s11:false,s12:false,s13:false,s14:false,s15:false,s16:false});
                }
            }
        });
        searchfm.attachEvent("onButtonClick", function(name,command){
            var t = searchfm.getFormData();
            var keyword = "",searchword="";
            var valstatus = Array();
            for (var a in t){
                if(a=='all') {
                }else if(a=='keyword') {
                    keyword = t[a];
                }else if(a=='searchword') {
                    searchword = t[a];
                }else{
                    if(t[a]!='0'){
                        valstatus.push(t[a]);
                    }
                }
            }
            valstatus = valstatus.join(",");
            if(name=='btSearch'){
                loadSearchResults(keyword,searchword,valstatus);
                return true;
            }
            if(name=='btExport'){
                exportStudentlist(keyword,searchword,valstatus);
                return true;
            }
            return false;
        });

        //============= Cell A - Student Grid (Student list)
        //20180130
        var grid_stulist =dhxLayout.cells("c").attachGrid();
        grid_stulist.setImagePath("dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
        grid_stulist.setHeader("No,FirstName,LastName,Nickname,D.O.B,Status,Note,&nbsp;,&nbsp;,&nbsp;,Email,&nbsp;,&nbsp;,&nbsp;,&nbsp;,&nbsp;,&nbsp;,&nbsp;,&nbsp;,&nbsp;,&nbsp;,&nbsp;,Start,&nbsp;,&nbsp;,&nbsp,&nbsp;,&nbsp;,&nbsp;,&nbsp;");
        grid_stulist.attachHeader("&nbsp;,#text_filter,#text_filter,&nbsp;,#text_filter,#select_filter,&nbsp;,&nbsp;,&nbsp;,&nbsp;,#text_filter,&nbsp;,&nbsp;,&nbsp;,&nbsp;,&nbsp;,&nbsp;,&nbsp;,&nbsp;,&nbsp;,&nbsp;,&nbsp;,#select_filter,&nbsp;,&nbsp;,&nbsp;,&nbsp;,&nbsp;,#select_filter,&nbsp;");
        grid_stulist.setColumnIds("students_no,firstname,lastname,nickname,birthday,progress,note,country,cellphone,cellphone2,email,email2,address1,address2,items,preschool,transfer,student_ID,gender,emergencyphone,emergencyphone2,etc_memo,register_day,memo,user_ID,probation,probation2,probation3,withd_day,complete");
        grid_stulist.setInitWidths("40,100,100,0,80,100,80,0,0,0,200,0,0,0,0,0,0,0,0,0,0,0,80,0,0,0,0,0,0,0");
        grid_stulist.enableAutoWidth(true);
        grid_stulist.setColAlign("center,left,left,left,left,center,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left,left");
        grid_stulist.setColTypes("ro,ro,ro,ro,ro,co,txt,ro,ro,ro,ro,ro,ro,ro,co,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro,ro"); //20180321
        grid_stulist.setColSorting("int,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,str,date,str,str,str,str,str,date,str"); //20180321
        grid_stulist.getCombo(5).put('n', "Initial-Transfer in");
        grid_stulist.getCombo(5).put('r', "Active");
        grid_stulist.getCombo(5).put('w', "Withdrawn-Transfer out");
        grid_stulist.getCombo(5).put('c', "Consultations");
        grid_stulist.getCombo(5).put('a', "Acceptance");
        grid_stulist.getCombo(5).put('v', "Vacation");
        grid_stulist.getCombo(5).put('m', "Med-Leave");
        grid_stulist.getCombo(5).put('s', "COS-Approved");
        grid_stulist.getCombo(5).put('d', "Continuing Education");
        grid_stulist.getCombo(5).put('p', "Initial-COS Approved");
        grid_stulist.getCombo(5).put('f', "Cancelled");
        grid_stulist.getCombo(5).put('l', "Complete");
        grid_stulist.getCombo(5).put('o', "COS");
        grid_stulist.getCombo(5).put('e', "Initial-Visa Interview");
        grid_stulist.getCombo(5).put('t', "Withdrawn-Terminated. No Show");
        grid_stulist.getCombo(5).put('h', "Withdrawn-AEW");
        grid_stulist.getCombo(14).put('n', "new");
        grid_stulist.getCombo(14).put('d', "transfer");
        grid_stulist.setSkin("dhx_skyblue");
        grid_stulist.attachEvent("onRowSelect", function(id,ind){
            myForm.enableItem("button_save");
            myForm.enableItem("button_delete");
            myForm.disableItem("button_insert");
            //20180130
            myForm.enableItem("probationForm");
            myForm.enableItem("withd_day");
            myForm.enableItem("completeForm");
            loadFamily();
            loadConsult();
            loadRecords();
            loadStudentRecodes();
            loadFinance();
            var vr0 = myForm.getItemValue("firstname");
            var vr1 = myForm.getItemValue("lastname");
            var vr2 = myForm.getItemValue("student_ID");
            //to check value 20180130
            //to check value 20180201
            //var vr3 = myForm.getItemValue("withd_day");
            financefm.setItemLabel("stdname",vr0+" "+vr1);
            financefm.setItemLabel("sevis",vr2);
        });
        grid_stulist.attachEvent("onBeforeSorting",function(ind,type,direction){
            window.stulist_col = ind;
            window.stulist_direction = ((direction == "des") ? "asc": "des");
            return true;
        });
        grid_stulist.init();
        grid_stulist.enableSmartRendering(true, 50);


        //============= Cell B / b1 Tab - Student Summary
        var formData = [{ type: "settings", position: "label-left", labelWidth: 100},
            {type: "fieldset",label: "", inputWidth: "auto", list: [
                {type: "block",style:"font-size:10px;",offsetTop:5, list:[
                    {type: "input",   inputWidth: 180, style:"font-size:11px;", label: "First Name",value:"", name:"firstname", className:"myLabel", validate: "NotEmpty", required: true},
                    {type: "input",   inputWidth: 180, style:"font-size:11px;", label: "Last Name",value:"", name:"lastname", className:"myLabel", validate: "NotEmpty", required: true},
                    {type: "input",  style:"font-size:11px;",inputWidth: 180, label: "Nickname", value:"", name:"nickname", className:"myLabel"},
                    {type: "input",  inputWidth: 180, style:"font-size:11px;", label: "Nationality",value:"", name:"country", className:"myLabel", validate: "NotEmpty", required: true},
                    {type: "input",  inputWidth: 180, style:"font-size:11px;", label: "Phone #1",value:"", name:"cellphone", className:"myLabel", validate: "NotEmpty", required: true},
                    {type: "input",  inputWidth: 180, style:"font-size:11px;", label: "Phone #2",value:"", name:"cellphone2", className:"myLabel"},
                    {type: "input",  inputWidth: 180, style:"font-size:11px;", label: "E-mail #1",value:"", name:"email", className:"myLabel"},
                    {type: "input",  inputWidth: 180, style:"font-size:11px;", label: "E-mail #2",value:"", name:"email2", className:"myLabel"},
                    {type: "input",  inputWidth: 180, style:"font-size:11px;", label: "Current Address",value:"", name:"address1", className:"myLabel"},
                    {type: "input",  inputWidth: 180, style:"font-size:11px;", label: "               ",value:"", name:"address2", className:"myLabel"},
                    {type: "select", style:"font-size:11px;", label: "Student Type",name: "items", options: [{ value: "n",text: "New(n)"}, {value: "d",text: "Transfer(t)" }] , className:"myLabel", required: true},
                    {type: "input",  inputWidth: 180, style:"font-size:11px;", label: "Previous School",value:"", name:"preschool", className:"myLabel"},
                    {type: "input",  inputWidth: 180, style:"font-size:11px;", label: "Transfer to School", value:"", name:"transfer", className:"myLabel"},
                    //divide
                    {type: "newcolumn", offset: 20 },
                    {type: "input",  inputWidth: 180, style:"font-size:11px;", label: "SEVIS ID #", value:"", name:"student_ID", className:"myLabel"},
                    { type: "calendar", dateFormat:"%Y-%m-%d", name: "birthday", label: "D.O.B.",value:gettoday(), className:"myLabel", validate: "NotEmpty", required: true},
                    {type: "select", style:"font-size:11px;", label: "Gender",name: "gender", options: [{ value: "f",text: "Female"}, {value: "m",text: "Male" }] , className:"myLabel", required: true},
                    {type: "input",  inputWidth: 180, style:"font-size:11px;", label: "Emergency #1",value:"", name:"emergencyphone", className:"myLabel"},
                    {type: "input",  inputWidth: 180, style:"font-size:11px;", label: "Emergency #2",value:"", name:"emergencyphone2", className:"myLabel"},
                    {type: "select", style:"font-size:11px;", label: "Status",name: "progress", validate: "NotEmpty", required: true, options: [
                        { value: "n",text: "Initial-Transfer in"},
                        { value: "r",text: "Active" },
                        { value: "w",text: "Withdrawn-Transfer out" },
                        { value: "c",text: "Consultations" },
                        { value: "a",text: "Acceptance" },
                        { value: "v",text: "Vacation" },
                        { value: "m",text: "Med-Leave" },
                        { value: "s",text: "COS-Approved" },
                        { value: "d",text: "Continuing Education" },
                        { value: "p",text: "Initial-COS Approved" },
                        { value: "f",text: "Cancelled" },
                        { value: "l",text: "Complete" },
                        { value: "o",text: "COS" },
                        { value: "e",text: "Initial-Visa Interview" },
                        { value: "t",text: "Withdrawn-Terminated. No Show" },
                        { value: "h",text: "Withdrawn-AEW" }
                    ], className:"myLabel"},
                    { type: "editor", name: "note", label: "Note", inputWidth: 180, inputHeight: 70, value: "" , className:"myLabel"},
                    { type: "input",  inputWidth: 180, style:"font-size:11px;",rows: 2, label: "VISA Status",value:"", name:"etc_memo", className:"myLabel"},
                    { type: "calendar",inputWidth: 180, dateFormat:"%Y-%m-%d", name: "register_day", label: "Start Date",value:gettoday(), className:"myLabel"},
                    { type: "input",  inputWidth: 180, style:"font-size:11px;", label: "Web ID",value:"", name:"user_ID", className:"myLabel"},
                    { type: "calendar", inputWidth: 180,dateFormat:"%Y-%m-%d", name: "withd_day", label: "Withdrawal Date",value:gettoday(), className:"myLabel", disabled:true},
                    {type: "label", labelWidth: 180, style:"font-size:11px;", label: "Web PW (default) : ali123 ",className:"myLabel"},
                    {type: "button", value: "Student View", disabled:false, name: "button_stdview"},
                    {type: "newcolumn", offset: 10 },
                    //class name changed for font color, style 01172018
                    // {type: "label", style:"font-size:11px;", label: "Current Student Status ",labelWidth:180,className:"myLabel2"},

                    //20180130
                    {type: "fieldset", name:"probationForm", disabled:true, label: "Current Student Status", list:[
                        {type: "block", list:[
                            {type:"label", label:"Academic probation"},
                            {type: "radio", name: "probation", label: "Yes", labelWidth:25, value: "a1"},
                            {type: "radio", name: "probation", label: "No", labelWidth:25, value: "a2"},
                            {type:"newcolumn"},
                            {type:"label", label:"Attendance Probation"},
                            {type: "radio", name: "probation2", label: "Yes" ,labelWidth:25, value: "b1"},
                            {type: "radio", name: "probation2", label: "No", labelWidth:25, value: "b2"},
                            {type:"newcolumn"},
                            {type:"label", label:"Financial Outstanding Balance", labelWidth:"130"}, //need padding = 0
                            {type: "radio", name: "probation3", label: "Yes", labelWidth:25, offsettop:0, value: "c1"},
                            {type: "radio", name: "probation3", label: "No",labelWidth:25, value: "c2"}

                        ]
                        }
                    ]
                    },

                    {type: "fieldset", name:"completeForm", disabled:true, label:"Completed Program", list:[
                        {type: "block", list:[
                            {type:"label", label:"Completed Program", labelWidth:150},{type:"newcolumn"},
                            {type: "radio", name: "complete", label: "Yes", labelWidth:25, value: "d1"},
                            {type:"newcolumn"},
                            {type: "radio", name: "complete", label: "No", labelWidth:25, value: "d2"}
                        ]
                        }
                    ]
                    },


                ]},
                {type: "input", offsetLeft:20, style:"font-size:11px;", label: "Memo", value:"", rows:"3", inputWidth:180, name:"memo", className:"myLabel"},
                {type: "block", style:"font-size:10px; text-align:center;",offsetLeft:150, offsetTop:5, list:[
                    {type: "button", value: "Update", disabled:true, name: "button_save"},{type:"newcolumn"},
                    {type: "button", value: "Add", name: "button_insert"},{type:"newcolumn"},
                    {type: "button", value: "Reset", name: "button_reset"},{type:"newcolumn"},
                    {type: "button", value: "Delete", disabled:true, name: "button_delete"} ]
                }
            ]
            }
        ];
        //{type: "button", value: "<img src='http://dhtmlx.com/docs/products/dhtmlxTree/codebase/imgs/csh_vista/folderClosed.gif' style='position:absolute;width:18px;height:18px;left:16px;'> <span style='margin-left:10px;'>Proceed</span>"}
        var	myForm = dhxTabbar.cells("b1").attachForm(formData);
        myForm.enableLiveValidation(true);
        myForm.bind(grid_stulist);
        myForm.attachEvent("onButtonClick", function(name,command){
            if(name=='button_stdview'){
                var selectedId=grid_stulist.getSelectedRowId();
                if(!selectedId){
                    return false;
                }
                popupStudentView(selectedId);
                return true;
            }
            if(name=='button_reset'){
                resetStdList();
                return true;
            }
            if(name=='button_delete'){
                var selectedId=grid_stulist.getSelectedRowId();
                if(selectedId){
                    if (confirm("Are you sure you want to delete row")) {
                        grid_stulist.deleteSelectedItem();
                        myForm.clear();
                        return true;
                    }else{
                        return false;
                    }
                }else{
                    alert('select a row');
                    return false;
                }
            }
            if(myForm.validate()){
                if(name=='button_save'){
                    var selectedId=grid_stulist.getSelectedRowId();
                    if(selectedId){
                        myForm.save();
//20180321                        grid_stulist.refresh();
                        return true;
                    }else{
                        alert('select a row');
                        return false;
                    }
                }

                if(name=='button_insert'){
                    var newId = (new Date()).valueOf();
                    var vr0 = myForm.getItemValue("firstname");
                    var vr1 = myForm.getItemValue("lastname");
                    var vr2 = myForm.getItemValue("nickname");
                    var vr3 = myForm.getItemValue("birthday",true);
                    var vr4 = myForm.getItemValue("progress");
                    var vr5 = myForm.getItemValue("note");
                    var vr6 = myForm.getItemValue("country");
                    var vr7 = myForm.getItemValue("cellphone");
                    var vr8 = myForm.getItemValue("cellphone2");
                    var vr9 = myForm.getItemValue("email");
                    var vr10 = myForm.getItemValue("email2");
                    var vr11 = myForm.getItemValue("address1");
                    var vr12 = myForm.getItemValue("address2");
                    var vr13 = myForm.getItemValue("items");
                    var vr14 = myForm.getItemValue("preschool");
                    var vr15 = myForm.getItemValue("transfer");
                    var vr16 = myForm.getItemValue("student_ID");
                    var vr17 = myForm.getItemValue("gender");
                    var vr18 = myForm.getItemValue("emergencyphone");
                    var vr19 = myForm.getItemValue("emergencyphone2");
                    var vr20 = myForm.getItemValue("etc_memo");
                    var vr21 = myForm.getItemValue("register_day",true);
                    var vr22 = myForm.getItemValue("memo");
                    var vr23 = myForm.getItemValue("user_ID");
                    //20180130
                    var vr24 = myForm.getItemValue("probation");
                    var vr25 = myForm.getItemValue("probation2");
                    var vr26 = myForm.getItemValue("probation3");
                    //20180201
                    var vr27 = myForm.getItemValue("withd_day");
                    var vr28 = myForm.getItemValue("complete");

                    grid_stulist.addRow(grid_stulist.uid(),[0,vr0,vr1,vr2,vr3,vr4,vr5,vr6,vr7,vr8,vr9,vr10,vr11,vr12,vr13,vr14,vr15,vr16,vr17,vr18,vr19,vr20,vr21,vr22,vr23,vr24,vr25,vr26,vr27,vr28],0);
                    return true;
                }
            }
        });

        var myDataProcessor = new dataProcessor("students/setStudent?unqueId="+(new Date()).valueOf()); //lock feed url
        myDataProcessor.init(grid_stulist); //link dataprocessor to the grid
        myDataProcessor.attachEvent("onBeforeUpdate",function(id,status, data){
            if(status=='updated'){
                var index = grid_stulist.getColIndexById( "register_day" );
                var r_day = myForm.getItemValue("register_day",true);
                grid_stulist.cellById(id,index).setValue(r_day);

                var index2 = grid_stulist.getColIndexById( "birthday" );
                var r_day2 = myForm.getItemValue("birthday",true);
                grid_stulist.cellById(id,index2).setValue(r_day2);

                //20180201
                var index3 = grid_stulist.getColIndexById( "withd_day" );
                var r_day3 = myForm.getItemValue("withd_day",true);
                grid_stulist.cellById(id,index3).setValue(r_day3);


            }
            return true;
        });
        myDataProcessor.defineAction("inserted",function(response){
            showfullmsg("msgResult","It has been "+response.textContent+" successfully.");
            var selectedId=grid_stulist.getSelectedRowId();
            var qunq="";
            if(selectedId){
                qunq = "&sno=" + selectedId;
            }
            grid_stulist.updateFromXML("students/getStudents?unqueId="+(new Date()).valueOf()+qunq);
            return true;
        });
        myDataProcessor.defineAction("updated",function(response){
            showfullmsg("msgResult","It has been "+response.textContent+" successfully.");
            return true;
        });
        myDataProcessor.defineAction("deleted",function(response){
            showfullmsg("msgResult","It has been "+response.textContent+" successfully.");
            return true;
        });
        myDataProcessor.defineAction("invalid",function(response){
            showfullmsg("ErrorMsg","Invalid Data");
            return true;
        });

        function loadRank(){
            var orqu = "";
            if(window.stulist_direction){
                grid_stulist.setSortImgState(true, window.stulist_col, window.stulist_direction,1);
                orqu="&orderby=" + window.stulist_col + "&direct=" + window.stulist_direction;
            }
            dhxLayout.cells("c").progressOn();
            grid_stulist.clearAndLoad("students/getStudents?page=1&ppage=100&unqueId="+(new Date()).valueOf()+ orqu,function(){
                var totalrows = grid_stulist.getUserData("","totalrows");
                var currentpage = grid_stulist.getUserData("","currentpage");
                itemsPerPage = 100;
                dhxLayout.cells("c").progressOff();
            });
        }

        function loadStudents(page){
            var orqu = "";
            if(window.stulist_direction){
                grid_stulist.setSortImgState(true, window.stulist_col, window.stulist_direction,1);
                orqu="&orderby=" + window.stulist_col + "&direct=" + window.stulist_direction;
            }

            dhxLayout.cells("c").progressOn();
            grid_stulist.clearAndLoad("students/getStudents?page="+page+"&ppage=100&unqueId="+(new Date()).valueOf()+ orqu,function(){
                var totalrows = grid_stulist.getUserData("","totalrows");
                var currentpage = grid_stulist.getUserData("","currentpage");
                itemsPerPage = 100;
                dhxLayout.cells("c").progressOff();
            });
        }

        function resetStdList(){
            grid_stulist.clearSelection();
            myForm.clear();
            //20180201
            myForm.disableItem("button_delete");
            myForm.disableItem("button_save");
            myForm.enableItem("button_insert");
            myForm.disableItem("probationForm");
            myForm.disableItem("withd_day");
            myForm.disableItem("completeForm");

        }
        function loadSearchResults(keyword,searchword,vstatus){
            var orqu = "";
            if(keyword > 0){
                orqu="&keyword=" + keyword + "&searchword=" + searchword + "&vstatus=" + vstatus;
                dhxLayout.cells("c").progressOn();
                grid_stulist.clearAndLoad("students/getStudents?unqueId="+(new Date()).valueOf()+ orqu,function(){
                    dhxLayout.cells("c").progressOff();
                });
            }
        }

            //============= Cell B / b2 Tab - Family
            var grid_family = dhxTabbar.cells("b2").attachGrid();
            grid_family.setImagePath("dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
            grid_family.setHeader("Name,DOB,Status,Regdate,students_no");
            grid_family.setColumnIds("name,birthday,memo,regdate,students_no");
            grid_family.setInitWidths("150,100,200,100,0");
            grid_family.enableAutoWidth(true);
            grid_family.setColAlign("center,center,center,center,center");
            grid_family.setColTypes("ed,dhxCalendar,txt,ro,ro");
            grid_family.setColSorting("str,date,str,date,int");
            grid_family.setDateFormat("%Y/%m/%d");
            grid_family.setSkin("dhx_skyblue");
            grid_family.attachEvent("onRowSelect", function(id,ind){});
            grid_family.init();
            grid_family.entBox.id = "familygridBody";
            grid_family.enableSmartRendering(true, 50);
            var myfamilyMenu = new dhtmlXMenuObject();
            myfamilyMenu.renderAsContextMenu();
            grid_family.enableContextMenu(myfamilyMenu);
            myfamilyMenu.addContextZone("familygridBody");
            myfamilyMenu.attachEvent("onClick",function(menuitemId,type){
                var selectedId=grid_stulist.getSelectedRowId();
                switch(menuitemId) {
                    case "edit_add":
                        var newId = (new Date()).valueOf();
                        grid_family.addRow(newId,["",gettodayGrid(),"",getfullday(),selectedId],grid_family.getRowsNum());
                        break;
                    case "edit_remove":
                        if(selectedId) {
                            if (confirm("Are you sure you want to delete row")) {
                                grid_family.deleteSelectedItem();
                                //resetFamily();
                            }
                        }
                        break;
                }

                return true
            });
            myfamilyMenu.loadStruct("students/menucontext?unqueId="+(new Date()).valueOf());
            var dpFamily = new dataProcessor("students/setFamily?unqueId="+(new Date()).valueOf()); //lock feed url
            dpFamily.init(grid_family); //link dataprocessor to the grid
            dpFamily.defineAction("inserted",function(response){
                showfullmsg("msgResult","It has been "+response.textContent+" successfully.");
                return true;
            });
            dpFamily.defineAction("updated",function(response){
                showfullmsg("msgResult","It has been "+response.textContent+" successfully.");
                //updateGridXml(grid_family,"students/getFamilies");
                return true;
            });
            dpFamily.defineAction("deleted",function(response){
                showfullmsg("msgResult","It has been "+response.textContent+" successfully.");
                //updateGridXml(grid_family,"students/getFamilies");
                return true;
            });
            dpFamily.defineAction("invalid",function(response){
                showfullmsg("ErrorMsg","Invalid Data");
                return true;
            });

            function loadFamily(){
                grid_family.clearSelection();
                var selectedId=grid_stulist.getSelectedRowId();
                var qunq="";
                if(selectedId){
                    qunq = "&sno=" + selectedId;
                }
                dhxLayout.cells("b").progressOn();
                grid_family.clearAndLoad("students/getFamilies?unqueId="+(new Date()).valueOf()+qunq,function(){
                    dhxLayout.cells("b").progressOff();
                });
            }

            //============= Cell B / b3 Tab - Consultations
            var grid_consult = dhxTabbar.cells("b3").attachGrid();
            grid_consult.setImagePath("dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
            grid_consult.setHeader("recordday,memo,Writer,students_no");
            grid_consult.setColumnIds("recordday,memo,writer,students_no");
            grid_consult.setInitWidths("100,400,100,0");
            grid_consult.enableAutoWidth(true);
            grid_consult.setColAlign("center,left,left,left");
            grid_consult.setColTypes("dhxCalendar,txt,ro,ro");
            grid_consult.setColSorting("date,str,str,int");
            grid_consult.setDateFormat("%Y-%m-%d");
            grid_consult.setSkin("dhx_skyblue");
            grid_consult.init();
            grid_consult.entBox.id = "consultgridBody";
            grid_consult.enableSmartRendering(true, 50);
            var myconsultMenu = new dhtmlXMenuObject();
            myconsultMenu.renderAsContextMenu();
            grid_consult.enableContextMenu(myconsultMenu);
            myconsultMenu.addContextZone("consultgridBody");
            myconsultMenu.attachEvent("onClick",function(menuitemId,type){
                var selectedId=grid_stulist.getSelectedRowId();
                switch(menuitemId) {
                    case "edit_add":
                        var newId = (new Date()).valueOf();
                        grid_consult.addRow(newId,[gettoday(),"","",selectedId],grid_consult.getRowsNum());
                        break;
                    case "edit_remove":
                        if(selectedId) {
                            if (confirm("Are you sure you want to delete row")) {
                                grid_consult.deleteSelectedItem();
                            }
                        }
                        break;
                }
                return true
            });
            myconsultMenu.loadStruct("students/menucontext?unqueId="+(new Date()).valueOf());
            var dpConsult = new dataProcessor("students/setConsult?unqueId="+(new Date()).valueOf()); //lock feed url
            dpConsult.init(grid_consult); //link dataprocessor to the grid
            dpConsult.defineAction("inserted",function(response){
                showfullmsg("msgResult","It has been "+response.textContent+" successfully.");
                var selectedId=grid_stulist.getSelectedRowId();
                var qunq="";
                if(selectedId){
                    qunq = "&sno=" + selectedId;
                }
                grid_consult.updateFromXML("students/getConsults?unqueId="+(new Date()).valueOf()+qunq);
                return true;
            });
            dpConsult.defineAction("updated",function(response){
                showfullmsg("msgResult","It has been "+response.textContent+" successfully.");
                return true;
            });
            dpConsult.defineAction("deleted",function(response){
                showfullmsg("msgResult","It has been "+response.textContent+" successfully.");
                return true;
            });
            dpConsult.defineAction("invalid",function(response){
                showfullmsg("ErrorMsg","Invalid Data");
                return true;
            });

            function loadConsult(){
                grid_consult.clearSelection();
                var selectedId=grid_stulist.getSelectedRowId();
                var qunq="";
                if(selectedId){
                    qunq = "&sno=" + selectedId;
                }
                grid_consult.clearAndLoad("students/getConsults?unqueId="+(new Date()).valueOf()+qunq,function(){});
            }


            //============= Cell B / b6 Tab - Academic Records
            var recordfmData = [{type: "settings", position: "label-left", labelWidth:0,inputWidth: "auto"},
                {type: "button", name:"btRoster", offsetLeft:10, value:"School Roster"},
                {type: "newcolumn", offset:1},
                {type: "button", name:"btClassRoster", offsetLeft:10, value:"Class Roster"},
                {type: "newcolumn", offset:1},
                {type: "button", name:"btTranscript", offsetLeft:10, value:"Create Transcript"},
                {type: "newcolumn", offset:1},
                {type: "button", name:"btRefresh", offsetLeft:10, value:"Refresh"},
                {type: "newcolumn", offset:1},
                {type: "button", name:"btAddFinal", offsetLeft:10, value:"Add Finalization"}];

            var	recordfm = new dhtmlXForm("addform", recordfmData);
            recordfm.setSkin('dhx_skyblue');
            recordfm.enableLiveValidation(true);
            recordfm.attachEvent("onButtonClick", function(name,command){
                if(name=='btRoster'){
                    self.location.href="/index.php/aliweb/roster?unqueId="+(new Date()).valueOf();
                    return false;
                }
                if(name=='btClassRoster'){
                    self.location.href="/index.php/aliweb/classroster?unqueId="+(new Date()).valueOf();
                    return false;
                }
                if(name=='btTranscript'){
                    var selectedId=grid_stulist.getSelectedRowId();
                    window.open("<?php echo $_SERVER["PHP_SELF"];?>/createTranscript?sno="+selectedId+"&unqueId="+(new Date()).valueOf(),'_blank');
                    // showNewWindow();
                    return true;
                }
                if(name=='btRefresh'){
                    loadRecords();
                    return true;
                }
                if(name=='btAddFinal'){
                    var selectedId=grid_stulist.getSelectedRowId();
                        if(selectedId) {
                        if (confirm("Are you sure you want to add finalizations for all trimester?")) {
                            jQuery.ajax({
                                type: "POST",
                                url: "<?php echo base_url(); ?>" + "index.php/aliweb/addFinalization",
                                dataType: 'json',
                                data: {gubun:8,stdno: selectedId },
                                success: function(res4) {
                                    if (res4){
                                        showfullmsg("msgResult","It has been added successfully.");
                                        var qunq2="";
                                        if(selectedId){
                                            qunq2 = "&sno=" + selectedId;
                                        }
                                        grid_records.updateFromXML("students/getAcademicRecords?unqueId="+(new Date()).valueOf()+qunq2);
                                        return true;
                                    }
                                }
                            });
                        }
                    }
                    return true;
                }

                return false;
            });

            var grid_records = new dhtmlXGridObject('recordgrid');
            grid_records.setImagePath("/dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
            grid_records.enableColSpan(true);
            grid_records.setDateFormat("%Y-%m-%d");
            grid_records.setSkin("dhx_skyblue");
            grid_records.enableMultiselect(true);
            grid_records.init();
            grid_records.entBox.id = "recordgridBody";
            grid_records.enableSmartRendering(true, 50);
            var myrecordMenu = new dhtmlXMenuObject();
            myrecordMenu.renderAsContextMenu();
            grid_records.enableContextMenu(myrecordMenu);
            myrecordMenu.addContextZone("recordgridBody");
            myrecordMenu.attachEvent("onClick",function(menuitemId,type){
                var selectedId=grid_stulist.getSelectedRowId();
                var newId = (new Date()).valueOf();
                var currentTime = new Date();
                var year = currentTime.getFullYear();

                switch(menuitemId) {
                    case "addTrimester":
                        grid_records.addRow(newId,[year,"1",1,1,"0","0","0","0",gettoday(),null,"<?php echo $username;?>",selectedId,2],grid_records.getRowsNum());
                        break;
                    case "addPlacement":
                        grid_records.addRow(newId,[year,"1",7,4,"0",null,null,null,gettoday(),null,"<?php echo $username;?>",selectedId,1],grid_records.getRowsNum());
                        grid_records.setRowColor(newId,"#DCDCDC");
                        break;
                    case "addFinalization":
                        if(selectedId) {
                            var selectedRowId=grid_records.getSelectedRowId();
                            if(selectedRowId){
                                var selyear = grid_records.cells(selectedRowId,0).getValue();
                                var seltrim = grid_records.cells(selectedRowId,1).getValue();
                                var sellevel = grid_records.cells(selectedRowId,2).getValue();
                                var selsession = grid_records.cells(selectedRowId,3).getValue();
                                if(selyear!=""&&seltrim!=""&&sellevel!=""&&selsession!=""&& selsession!=4){
                                    if (confirm("Are you sure you want to add finalization for the trimester?")) {
                                        jQuery.ajax({
                                            type: "POST",
                                            url: "<?php echo base_url(); ?>" + "index.php/aliweb/addFinalization",
                                            dataType: 'json',
                                            data: {gubun:1,stdno: selectedId,year:selyear,trim:seltrim,level:sellevel,session:selsession},
                                            success: function(res4) {
                                                if (res4){
                                                    showfullmsg("msgResult","It has been added successfully.");
                                                    var qunq2="";
                                                    if(selectedId){
                                                        qunq2 = "&sno=" + selectedId;
                                                    }
                                                    grid_records.updateFromXML("students/getAcademicRecords?unqueId="+(new Date()).valueOf()+qunq2);
                                                    return true;
                                                }
                                            }
                                        });
                                    }
                                }
                            }
                        }
                        break;
                    case "removeRow":
                        if(selectedId) {
                            if (confirm("Are you sure you want to delete row?")) {
                                grid_records.deleteSelectedItem();
                            }
                        }
                        break;
                }
            });

            myrecordMenu.loadStruct("students/menuRcordContext?unqueId="+(new Date()).valueOf());

            var dpRecord = new dataProcessor("students/setRecord?unqueId="+(new Date()).valueOf()); //lock feed url
            dpRecord.init(grid_records); //link dataprocessor to the grid
            dpRecord.defineAction("inserted",function(response){
                showfullmsg("msgResult","It has been "+response.textContent+" successfully.");
                var selectedId=grid_stulist.getSelectedRowId();
                var qunq="";
                if(selectedId){
                    qunq = "&sno=" + selectedId;
                }
                grid_records.updateFromXML("students/getAcademicRecords?unqueId="+(new Date()).valueOf()+qunq);
                return true;
            });
            dpRecord.defineAction("updated",function(response){
                showfullmsg("msgResult","It has been "+response.textContent+" successfully.");
                return true;
            });
            dpRecord.defineAction("deleted",function(response){
                showfullmsg("msgResult","It has been "+response.textContent+" successfully.");
                return true;
            });
            dpRecord.defineAction("invalid",function(response){
                showfullmsg("ErrorMsg","Invalid Data");
                return true;
            });
            dhxTabbar.cells("b6").attachObject("recordContainer");

            function loadRecords(){
                grid_records.clearSelection();
                var selectedId=grid_stulist.getSelectedRowId();
                var qunq="";
                if(selectedId){
                    qunq = "&sno=" + selectedId;
                }
                dhxLayout.cells("b").progressOn();
                grid_records.clearAndLoad("students/getAcademicRecords?unqueId="+(new Date()).valueOf()+qunq,function(){
                    dhxLayout.cells("b").progressOff();
                });
            }


            //============= Cell B / b7 Tab - Student Records
            var gradelsgrid = new dhtmlXGridObject('lsbox');
            gradelsgrid.setImagePath("/dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
            gradelsgrid.setHeader("Assignment,Score,Possible",null,["text-align:left","text-align:center","text-align:center"]);
            gradelsgrid.setColumnIds("name,score,points");
            gradelsgrid.setInitWidths("*,80,80");
            gradelsgrid.enableAutoWidth(true);
            gradelsgrid.setColAlign("left,right,right");
            gradelsgrid.setColTypes("ro,ro,ro");
            gradelsgrid.setSkin("dhx_skyblue");
            gradelsgrid.preventIECaching(true);
            gradelsgrid.init();
            gradelsgrid.attachEvent("onBeforeSelect", function(row,old_row){
                return false;
            });
            function loadLSGrades(classno,std){
                gradelsgrid.clearAndLoad("students/getStudentGrades?classno="+classno+"&stno="+std+"&unqueId="+(new Date()).valueOf(),function(){
                });
            }
            var graderwgrid = new dhtmlXGridObject('rwbox');
            graderwgrid.setImagePath("/dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
            graderwgrid.setHeader("Assignment,Score,Possible",null,["text-align:left","text-align:center","text-align:center"]);
            graderwgrid.setColumnIds("name,score,points");
            graderwgrid.setInitWidths("*,80,80");
            graderwgrid.enableAutoWidth(true);
            graderwgrid.setColAlign("left,right,right");
            graderwgrid.setColTypes("ro,ro,ro");
            graderwgrid.setSkin("dhx_skyblue");
            graderwgrid.preventIECaching(true);
            graderwgrid.init();
            graderwgrid.attachEvent("onBeforeSelect", function(row,old_row){
                return false;
            });
            function loadRWGrades(classno,std){
                graderwgrid.clearAndLoad("students/getStudentGrades?classno="+classno+"&stno="+std+"&unqueId="+(new Date()).valueOf(),function(){
                });
            }
            dhxTabbar.cells("b7").attachObject("stdrecordContainer");
            function loadStudentRecodes(){
                var selectedId=grid_stulist.getSelectedRowId();
                if(!selectedId){
                    return false;
                }
                $("#btngroup").empty();
                $("#attLSbox").empty();
                $("#attRWbox").empty();


                gradelsgrid.clearAll();
                graderwgrid.clearAll();

                jQuery.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>" + "index.php/aliweb/addtributton",
                    dataType: 'json',
                    data: {stdno: selectedId},
                    success: function(res) {
                        if (res)
                        {
                            $.each(res, function( key, data ) {
                                if(key>0){
                                    $("#btngroup").append('<label class="btn btn-default trisubmit" data-year="'+data.year+'" data-tri="'+data.tri+'" ><input type="radio"  name="options"  id="option'+data.year+'" autocomplete="off">'+data.year+' Trimester '+data.tri+'</label>');
                                }
                            });

                            $(".trisubmit").click(function(event) {
                                event.preventDefault();
                                jQuery.ajax({
                                    type: "POST",
                                    url: "<?php echo base_url(); ?>" + "index.php/aliweb/addclassbutton",
                                    dataType: 'json',
                                    data: {stdno: selectedId,year: $(this).attr("data-year"),tri: $(this).attr("data-tri")},
                                    success: function(res2) {
                                        if (res2)
                                        {
                                            $.each(res2, function( key2, data2 ) {
                                                if(key2>0){
                                                    if(data2.classtype=="LS"){
                                                        $("#btnLSgrade").attr("data-classno",data2.class_no);
                                                        $("#exportLSgrade").attr("data-classno",data2.class_no);
                                                        loadLSGrades(data2.class_no,selectedId);
                                                        jQuery.ajax({
                                                            type: "POST",
                                                            url: "<?php echo base_url(); ?>" + "index.php/aliweb/addATThtml",
                                                            dataType: 'json',
                                                            data: {stdno: selectedId,classno:data2.class_no,classtype:data2.classtype },
                                                            success: function(res3) {
                                                                if (res3){
                                                                    $("#attLSbox").html(res3.html);
                                                                    // mygrid.setHeader("A,B,C");
                                                                }
                                                            }
                                                        });
                                                    }
                                                    if(data2.classtype=="RW"){
                                                        $("#btnRWgrade").attr("data-classno",data2.class_no);
                                                        $("#exportRWgrade").attr("data-classno",data2.class_no);
                                                        loadRWGrades(data2.class_no,selectedId);
                                                        jQuery.ajax({
                                                            type: "POST",
                                                            url: "<?php echo base_url(); ?>" + "index.php/aliweb/addATThtml",
                                                            dataType: 'json',
                                                            data: {stdno: selectedId,classno:data2.class_no,classtype:data2.classtype },
                                                            success: function(res4) {
                                                                if (res4){ $("#attRWbox").html(res4.html); }
                                                            }
                                                        });
                                                    }
                                                }
                                            });
                                        }
                                    }
                                });

                                $("#rAttbox").empty();
                                jQuery.ajax({
                                    type: "POST",
                                    url: "<?php echo base_url(); ?>" + "index.php/aliweb/addRATThtml",
                                    dataType: 'json',
                                    data: {stdno: selectedId,year:$(this).attr("data-year"),trim:$(this).attr("data-tri") },
                                    success: function(res3) {
                                        if (res3){
                                            $("#rAttbox").html(res3.html);
                                            // mygrid.setHeader("A,B,C");
                                        }
                                    },
                                    error: function (request, status, error) {
                                        //alert(request.responseText);
                                    }
                                });

                            });

                        }
                    }
                });


            }

            //============= Cell B / b8 Tab - Financials
            var financefmData = [{type: "settings", position: "label-left", labelWidth:0,inputWidth: "auto"},
                {type: "label", label:"",name:"stdname",labelWidth:"auto", offsetLeft:10},
                {type: "newcolumn", offset:1},
                {type: "label", label:" - SEVIS# ",labelWidth:"auto", offsetLeft:0},
                {type: "newcolumn", offset:1},
                {type: "label", label:"",name:"sevis",labelWidth:"auto", offsetLeft:1},
                {type: "newcolumn", offset:1},
                {type: "button", name:"btPrint", offsetLeft:100, value:"Export(.xlsx)"},
                {type: "newcolumn", offset:1},
                {type: "button", name:"btPrintPDF", offsetLeft:100, value:"Export(.pdf)"}
            ];

            var	financefm = new dhtmlXForm("addfinanceform", financefmData);
            financefm.setSkin('dhx_skyblue');
            financefm.enableLiveValidation(true);
            financefm.attachEvent("onButtonClick", function(name,command){
                if(name=='btPrint'){
                    var v1 = financefm.getItemLabel("stdname") + " " + financefm.getItemLabel("sevis");
                    grid_finance.toExcel('http://'+$_SERVER['HTTP_HOST']+'/dhtmlxSuite/sources/dhtmlxGrid/codebase/grid-excel-php/generate.php?title='+v1+'&filename=Financials('+v1+')');
                    return true;
                }
                if(name=='btPrintPDF'){
                    var v1 = financefm.getItemLabel("sevis");
                    var v2 = financefm.getItemLabel("stdname");
                    var selectedId=grid_stulist.getSelectedRowId();
                    window.open("<?php echo $_SERVER["PHP_SELF"];?>/createpdf?sno="+selectedId+"&stdnam="+v2+"&sevis="+v1+"&unqueId="+(new Date()).valueOf(),'_blank');
                    return true;
                }
                return false;
            });

            var grid_finance = new dhtmlXGridObject('financegrid');
            grid_finance.setImagePath("/dhtmlxSuite/sources/dhtmlxGrid/codebase/imgs/");
            grid_finance.setDateFormat("%Y-%m-%d");
            grid_finance.setNumberFormat("0,000.00",4,".",",");
            grid_finance.setNumberFormat("0,000.00",5,".",",");
            grid_finance.setNumberFormat("0,000.00",6,".",",");
            grid_finance.setSkin("dhx_skyblue");
            grid_finance.init();
            grid_finance.entBox.id = "financegridBody";
            grid_finance.enableSmartRendering(true, 50);
            var myfinanceMenu = new dhtmlXMenuObject();
            myfinanceMenu.renderAsContextMenu();
            grid_finance.enableContextMenu(myfinanceMenu);
            myfinanceMenu.addContextZone("financegridBody");
            myfinanceMenu.attachEvent("onClick",function(menuitemId,type){
                var selectedId=grid_stulist.getSelectedRowId();
                var newId = (new Date()).valueOf();
                var currentTime = new Date();
                var year = currentTime.getFullYear();
                switch(menuitemId) {
                    case "addPayment":
                        grid_finance.addRow(newId,[year,"1",gettoday(),null,"0.00","0.00","0.00",null,null,null,"<?php echo $username;?>",selectedId],grid_finance.getRowsNum());
                        break;
                    case "removeRow":
                        if(selectedId) {
                            if (confirm("Are you sure you want to delete row?")) {
                                grid_finance.deleteSelectedItem();
                            }
                        }
                        break;
                }
            });
            myfinanceMenu.loadStruct("students/menuFinanceContext?unqueId="+(new Date()).valueOf());

            var dpFinance = new dataProcessor("students/setFinance?unqueId="+(new Date()).valueOf()); //lock feed url
            dpFinance.init(grid_finance); //link dataprocessor to the grid
            dpFinance.defineAction("inserted",function(response){
                var msg = response.textContent || response.innerText || 'inserted';
                showfullmsg("msgResult","It has been "+msg+" successfully.");
                var selectedId=grid_stulist.getSelectedRowId();
                var qunq="";
                if(selectedId){
                    qunq = "&sno=" + selectedId;
                }
                grid_finance.updateFromXML("students/getFinance?unqueId="+(new Date()).valueOf()+qunq);
                return true;
            });
            dpFinance.defineAction("updated",function(response){
                var msg = response.textContent || response.innerText || 'updated';
                showfullmsg("msgResult","It has been "+msg+" successfully.");
                return true;
            });
            dpFinance.defineAction("deleted",function(response){
                var msg = response.textContent || response.innerText || 'deleted';
                showfullmsg("msgResult","It has been "+msg+" successfully.");
                return true;
            });
            dpFinance.defineAction("invalid",function(response){
                showfullmsg("ErrorMsg","Invalid Data");
                return true;
            });
            dhxTabbar.cells("b8").attachObject("financeContainer");
            function loadFinance(){
                grid_finance.clearSelection();
                var selectedId=grid_stulist.getSelectedRowId();
                var qunq="";
                if(selectedId){
                    qunq = "&sno=" + selectedId;
                }
                dhxLayout.cells("b").progressOn();
                grid_finance.clearAndLoad("students/getFinance?unqueId="+(new Date()).valueOf()+qunq,function(){
                    dhxLayout.cells("b").progressOff();
                });
            }



            //============= WINDOW
            //============= Cell B / b6 Tab ( Academic Records - Create Transcript )
            var formData = [    {type: "settings", position: "label-left",offsetLeft:10, labelWidth: 180,inputWidth:200},
                {type: "input",  label: "Student",name: "studentname", required: true,readonly:true, validate: "NotEmpty" },
                {type: "input",  label: "Last Day of Attendance: ",name: "lastdate", required: true, validate: "NotEmpty" },
                {type: "input",  label: "Advisor's Name",name: "advisorname", required: true, validate: "NotEmpty" },
                {type: "input",  label: "Advisor's Title",name: "advisortitle", required: true, validate: "NotEmpty" },
                {type: "calendar", dateFormat:"%m-%d-%Y",inputWidth:100, label: "Date of Issuance: ",name: "issuancedate",readonly:true, required: true },
                {type: "hidden", name:"sno"}
            ];

            var dhxWins = new dhtmlXWindows();
            dhxWins.setSkin("dhx_skyblue");
            var w1 = dhxWins.createWindow("w1",0,0,440,260);
            w1.hide();
            w1.center();
            w1.button("minmax1").disable();
            w1.setModal(false);
            w1.attachEvent("onClose",function(win){
                if (win.getId() == "w1") {
                    win.setModal(false);
                    win.hide();
                }
            });
            w1.attachObject("winVP");

            var editForm = w1.attachForm(formData);
            editForm.setSkin('dhx_skyblue');
            editForm.enableLiveValidation(true);
            editForm.attachEvent("onButtonClick", function(name,command){
                if(editForm.validate()){
                    var selectedId=grid_stulist.getSelectedRowId();
                    if(name=='btExcel8'){
                        var advtitle = editForm.getItemValue("advisortitle");
                        var advname = editForm.getItemValue("advisorname");
                        var lastdate = editForm.getItemValue("lastdate",true);
                        var issdate = editForm.getItemValue("issuancedate",true);
                        self.location.href="<?php echo $_SERVER["PHP_SELF"];?>/createexcel?sno="+selectedId+"&advtitle="+advtitle+"&advname="+advname+"&lastdate="+lastdate+"&issdate="+issdate+"&unqueId="+(new Date()).valueOf();
                        w1.setModal(false);
                        w1.hide();
                    }
                    if(name=='btPDF'){

                    }
                    return true;
                }
                return false;
            });

            //============= WINDOW
            //============= Cell B / b6 Tab ( Academic Records - Add Finalization )
            var formData2 = [    {type: "settings", position: "label-left",offsetLeft:10, labelWidth: 180,inputWidth:200},
                //   {type: "input",  label: "Student",name: "studentname", required: true,readonly:true, validate: "NotEmpty" },
                //  {type: "input",  label: "Last Day of Attendance: ",name: "lastdate", required: true, validate: "NotEmpty" },
                //  {type: "input",  label: "Advisor's Name",name: "advisorname", required: true, validate: "NotEmpty" },
                //  {type: "input",  label: "Advisor's Title",name: "advisortitle", required: true, validate: "NotEmpty" },
                //   {type: "calendar", dateFormat:"%m-%d-%Y",inputWidth:100, label: "Date of Issuance: ",name: "issuancedate",readonly:true, required: true },
                {type: "hidden", name:"sno"}
            ];

            var dhxWins2 = new dhtmlXWindows();
            dhxWins2.setSkin("dhx_skyblue");
            var w3 = dhxWins2.createWindow("w3",0,0,440,260);
            w3.hide();
            w3.center();
            w3.button("minmax1").disable();
            w3.setModal(false);
            w3.attachEvent("onClose",function(win){
                if (win.getId() == "w3") {
                    win.setModal(false);
                    win.hide();
                }
            });
            w3.attachObject("winVP3");

            var editForm2 = w1.attachForm(formData2);
            editForm2.setSkin('dhx_skyblue');
            editForm2.enableLiveValidation(true);
            editForm2.attachEvent("onButtonClick", function(name,command){
                if(editForm2.validate()){
                    var selectedId=grid_stulist.getSelectedRowId();
                    if(name=='btExcel'){
                        // var advtitle = editForm.getItemValue("advisortitle");
                        // var advname = editForm.getItemValue("advisorname");
                        // var lastdate = editForm.getItemValue("lastdate",true);
                        //  var issdate = editForm.getItemValue("issuancedate",true);
                        //  self.location.href="<?php echo $_SERVER["PHP_SELF"];?>/createexcel?sno="+selectedId+"&advtitle="+advtitle+"&advname="+advname+"&lastdate="+lastdate+"&issdate="+issdate+"&unqueId="+(new Date()).valueOf();
                        w3.setModal(false);
                        w3.hide();
                    }
                    if(name=='btPDF'){

                    }
                    return true;
                }
                return false;
            });
            function showNewWindow3(){
                //  editForm2.clear();
                //editForm2.removeItem("btExcel");
                var selectedId=grid_stulist.getSelectedRowId();
                var firtname = grid_stulist.cells(selectedId,1).getValue();
                var lastname = grid_stulist.cells(selectedId,2).getValue();
                //    editForm2.setItemValue("studentname",firtname+' '+lastname);

                var currentTime = new Date();
                var month = currentTime.getMonth()+1;
                if(month <= 9) month = '0'+month;
                var day = currentTime.getDate();
                if(day <= 9) day = '0'+day;
                var year = currentTime.getFullYear();
                var idate = month + "-" + day +"-"+year;
                //   editForm2.setItemValue("issuancedate",idate);

                //   var btnblock = {type: "button",offsetLeft:140, value: "Export(.xlsx)",name: "btExcel"};
                //   editForm2.addItem(null,btnblock,19);
                w3.setText("Add Finalization");
                w3.setModal(true);
                w3.show();
            }

            //============= PRINT
            //============= Cell B / b7 Tab - Student Records
            $( document ).ready(function() {
                $(".PrintButton").click(function() {
                    var target = $(this).attr("data-target");
                    popup($("#"+target).html());
                });

                $("#btnLSgrade").click(function() {
                    var classno = $(this).attr("data-classno");
                    var selectedId=grid_stulist.getSelectedRowId();
                    if(!selectedId){
                        return false;
                    }
                    window.open("students/printgrade?stno="+selectedId+"&classno="+classno+"&unqueId="+(new Date()).valueOf(),"winname","directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=720,height=800");
                });

                $("#btnRWgrade").click(function(event) {
                    event.preventDefault();
                    var classno = $(this).attr("data-classno");
                    var selectedId=grid_stulist.getSelectedRowId();
                    if(!selectedId){
                        return false;
                    }
                    window.open("students/printgrade?stno="+selectedId+"&classno="+classno+"&unqueId="+(new Date()).valueOf(),"winname","directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=720,height=800");
                });

                $("#exportLSgrade").click(function() {
                    var classno = $(this).attr("data-classno");
                    var selectedId=grid_stulist.getSelectedRowId();
                    if(!selectedId){
                        return false;
                    }
                    document.getElementById('id_stno').value = selectedId;
                    document.getElementById('id_classno').value = classno;
                    document.exportform.action = "students/exportGrade";
                    document.exportform.submit();
                });

                $("#exportRWgrade").click(function(event) {
                    event.preventDefault();
                    var classno = $(this).attr("data-classno");
                    var selectedId=grid_stulist.getSelectedRowId();
                    if(!selectedId){
                        return false;
                    }
                    document.getElementById('id_stno').value = selectedId;
                    document.getElementById('id_classno').value = classno;
                    document.exportform.action = "students/exportGrade";
                    document.exportform.submit();
                });

            });

            function popup(data){
                var myWindow = window.open('', 'Attendance', 'directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=720,height=800');
                //myWindow.document.open();

                myWindow.document.onreadystatechange=function(){
                    if(this.readyState==='complete'){
                        this.onreadystatechange=function(){};
                        myWindow.focus();
                        myWindow.print();
                        myWindow.close();
                    }
                }
                $(myWindow.document.head).html( '<title>Attendance</title><link rel="stylesheet" href="http://'+$_SERVER['HTTP_HOST']+'/assets/css/attmonth.css?v=2" type="text/css" />');
                $(myWindow.document.body).html( '</head><body>' + data + '</body></html>');

                myWindow.document.close();
                /*20180215 delete it to print
                myWindow.focus(); // necessary for IE >= 10
                myWindow.print();
                myWindow.close();
                */
                return true;
            }

            function popupStudentView(selectedId){
                window.open("students/getstudentview?stno="+selectedId+"&unqueId="+(new Date()).valueOf(),"studentview","directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=800,height=800");
            }

            function exportStudentlist(keyword,searchword,valstatus){
                document.getElementById("id_keyword").value = keyword;
                document.getElementById("id_searchword").value = searchword;
                document.getElementById("id_valstatus").value = valstatus;
                document.exportform.action = "students/exportStudentList";
                document.exportform.submit();
            }

            function exportGrade(){
                // var form = document.forms['exportform'];
                //  form['stno'].value = "New value";
                // document.exportform.action = "students/exportStudentList";
                // document.exportform.submit();
            }
        </script>
        <form action="" target="export_area"  method="post" name="exportform" id="exportform">
            <input type="hidden" id="id_stno" name="stno"/>
            <input type="hidden" id="id_classno" name="classno"/>
            <input type="hidden" id="id_keyword" name="keyword"/>
            <input type="hidden" id="id_searchword" name="searchword"/>
            <input type="hidden" id="id_valstatus" name="vstatus"/>
        </form>
        <iframe name="export_area" frameBorder="0" height="0"></iframe>


