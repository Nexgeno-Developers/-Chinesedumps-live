// JavaScript Document
<!--
function setTab(m,n){
 var tli=document.getElementById("tab_menu"+m).getElementsByTagName("li");
 var mli=document.getElementById("tab_main"+m).getElementsByTagName("div");
 for(i=0;i<tli.length;i++){
  tli[i].className=i==n?"hover":"";
  mli[i].style.display=i==n?"block":"none";
 }
}
//-->