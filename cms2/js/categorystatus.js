
function showHint(str,tblname,fieldname,fieldstatus)
{
	//var texthint = "txtHint"+str;
	setID(str);
	setTblname(tblname);
	setField(fieldname);
	
if (str.length==0)
  { 
  
  document.getElementById("txtHint").innerHTML="";
  return;
  }
xmlHttp=GetXmlHttpObject();
if (xmlHttp==null)
  {
  alert ("Your browser does not support AJAX!");
  return;
  } 
var url="../cms2/changestatus.php";
url=url+"?id="+str+"&tb="+tblname+"&fld="+fieldname+"&flds="+fieldstatus;

url=url+"&sid="+Math.random();
xmlHttp.onreadystatechange=stateChangedstatus;
xmlHttp.open("GET",url,true);
xmlHttp.send(null);
} 

function stateChangedstatus() 
{ 
var texthint ="txtHint"+ g;
//alert(g);

if (xmlHttp.readyState==4)
{ 
document.getElementById(texthint).innerHTML=xmlHttp.responseText;
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