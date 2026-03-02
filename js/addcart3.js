function submitProexam(str, tptp)

{

	var texthint = "txtHint"+str;

	setID(str);

	

	var tblname	=	'';

	var	subsr	=	'';

	var ptype	=	'p';

	

	if(document.getElementById("sellcombi") && document.getElementById("sellcombi").checked==true){

		var tblname	=	document.getElementById("sellcombi").value;

		var	subsr	=	'3';

	}

	if(document.getElementById("sellcombi2") && document.getElementById("sellcombi2").checked==true){

		var tblname	=	document.getElementById("sellcombi2").value;

		var	subsr	=	'6';

	}

	if(document.getElementById("sellcombi3") && document.getElementById("sellcombi3").checked==true){

		var tblname	=	document.getElementById("sellcombi3").value;

		var	subsr	=	'12';

	}

	if(document.getElementById("sellcombi0") && document.getElementById("sellcombi0").checked==true){

		var tblname	=	document.getElementById("sellcombi0").value;

		var	subsr	=	'0';

	}

	setTblname(tblname);

	setArea('carthidshowss');

	if(tptp == 'p'){ptype	=	document.getElementById("type_p").value;}

	if(tptp == 'sp'){ptype	=	document.getElementById("type_sp").value;}

	if(tptp == 'both'){ptype	=	document.getElementById("type_both").value;}

	if(tptp == '7'){ptype	=	document.getElementById("type_7").value;}

	if(tptp == '8'){ptype	=	document.getElementById("type_8").value;}

	if(tptp == '9'){ptype	=	document.getElementById("type_9").value;}

    // Reset notify for the currently logged‑in user
    resetNotify();		

if (str.length==0)

  { 

  

  document.getElementById("carthidshow").innerHTML="";

  return;

  }

xmlHttp=GetXmlHttpObject();

if (xmlHttp==null)

  {

  alert ("Your browser does not support AJAX!");

  return;

  } 

var url="addproprocerexam.php";

url=url+"?id="+str+"&tb="+tblname+"&subsr="+subsr+"&ptype="+ptype;



url=url+"&sid="+Math.random();

xmlHttp.onreadystatechange=stateChangedproncerexam;

xmlHttp.open("GET",url,true);

xmlHttp.send(null);

} 

function resetNotify() {
    var xhr = GetXmlHttpObject();
    if (!xhr) return;
    // no userID in the query string—PHP will pull it from $_SESSION['uid']
    xhr.open("GET", "/resetNotify.php", true);
    xhr.send(null);
}


function stateChangedproncerexam() 

{

	

var texthint = h2+g2;





if (xmlHttp.readyState==4)

{ 

document.location="cart.html";

document.getElementById("carthidshow").innerHTML=xmlHttp.responseText;

document.getElementById(texthint).innerHTML="<font style='color:#FF9900;font-size:14px;'><br/><b>Added in cart</b><br/>";

}

}



function GetXmlHttpObject()

{

var xmlHttp=null;

try

  {

  // Firefox, Opera 8.0+, Safari

  xmlHttp=new XMLHttpRequest();

  }

catch (e)

  {

  // Internet Explorer

  try

    {

    xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");

    }

  catch (e)

    {

    xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");

    }

  }

return xmlHttp;

} 



function setID(str)

{

	window.g2 = str; 

}

function setTblname(tblname)

{

	window.t2 = tblname;

}

function setField(fieldname)

{

	window.f2=fieldname;

}



function setArea(textarearpl)

{

	window.h2=textarearpl;

}