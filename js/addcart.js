function submitPro(str)
{
	//var texthint = "txtHint"+str;
	setID(str);
	
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
//	setArea('carthidshowss');
	
		
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
var url="addpropro.php";
url=url+"?id="+str+"&tb="+tblname+"&subsr="+subsr;

url=url+"&sid="+Math.random();
xmlHttp.onreadystatechange=stateChangedpron;
xmlHttp.open("GET",url,true);
xmlHttp.send(null);
} 

function stateChangedpron() 
{
	
var texthint = h2+g2;


if (xmlHttp.readyState==4)
{ 
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