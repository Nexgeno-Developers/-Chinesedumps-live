function submitPromonthly(str)
{
	//var texthint = "txtHint"+str;
	setID(str);
	
if (str.length==0)
  { 
  
  document.getElementById("carthidshowss").innerHTML="";
  return;
  }
xmlHttp=GetXmlHttpObject();
if (xmlHttp==null)
  {
  alert ("Your browser does not support AJAX!");
  return;
  } 
var url="addpropromonthly.php";
url=url+"?id="+str;

url=url+"&sid="+Math.random();
xmlHttp.onreadystatechange=stateChangedpron;
xmlHttp.open("GET",url,true);
xmlHttp.send(null);
} 

function stateChangedpron() 
{
	
if (xmlHttp.readyState==4)
{ 
document.location="cart.html";
document.getElementById("carthidshow").innerHTML=xmlHttp.responseText;
document.getElementById("carthidshowss").innerHTML="<font style='color:#FF9900;font-size:14px;'><br/><b>Added in cart</b><br/>";
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