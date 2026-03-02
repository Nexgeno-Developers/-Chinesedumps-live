

var xmlHttp



function showsubcateexams(str)
{
  
if (str.length==0)
  { 
  document.getElementById("txtHintsearch2").innerHTML="";
  return;
  }
xmlHttp=GetXmlHttpObject();
if (xmlHttp==null)
  {
  alert ("Your browser does not support AJAX!");
  return;
  } 
var url="getsubcategorysearchexams.php";
url=url+"?q="+str;
url=url+"&sid="+Math.random();
xmlHttp.onreadystatechange=stateChangedexam;
xmlHttp.open("GET",url,true);
xmlHttp.send(null);
} 

function stateChangedexam() 
{ 
if (xmlHttp.readyState==4)
{ 
document.getElementById("txtHintsearch2").innerHTML=xmlHttp.responseText;
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
 