function showHintnamesave(str)
{
	//var texthint = "txtHint"+str;
	setID(str);
	
	var torder	=	document.getElementById('tid'+str).value;
	
if (str.length==0)
  { 
  
  document.getElementById('successmsg').innerHTML="";
  return;
  }
xmlHttp=GetXmlHttpObject();
if (xmlHttp==null)
  {
  alert ("Your browser does not support AJAX!");
  return;
  } 
var url="../cms2/processsavere.php";
url=url+"?id="+str+"&ord="+torder;

url=url+"&sid="+Math.random();
xmlHttp.onreadystatechange=stateChangedhot3;
xmlHttp.open("GET",url,true);

xmlHttp.send(null);
} 

function stateChangedhot3() 
{ 
//var texthint = h + g;

//alert(g);

if (xmlHttp.readyState==4)
{ 
document.getElementById('successmsg').innerHTML=xmlHttp.responseText;
//document.getElementById('successmsg').innerHTML='Data updated successfully.';
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
	window.g = str; 
}
function setTblname(tblname)
{
	window.t = tblname;
}
function setField(fieldname)
{
	window.f=fieldname;
}

function setArea(textarearpl)
{
	window.h=textarearpl;
}