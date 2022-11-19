 function textarea_decrease(id, row)
    {
        if (document.getElementById(id).rows - row > 0)
            document.getElementById(id).rows -= row;
			
parent.resizeIframe('myframe');
			
		//parent.frames['myframe'].onload = 
		//alert(parent.frames['myframe'].name);
    }

    function textarea_original(id, row)
    {
        document.getElementById(id).rows = row;
parent.resizeIframe('myframe');
		//alert(parent.frames['myframe'].name);
    }

    function textarea_increase(id, row)
    {
        document.getElementById(id).rows += row;
		parent.resizeIframe('myframe');
			//parent.window.location.reload();
			
		//alert(parent.frames['myframe'].name);
    }

function godetail(bval){
var frm = document.postform;
frm.bno.value = bval;
frm.submit();
}	