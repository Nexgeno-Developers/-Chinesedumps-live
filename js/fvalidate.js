
/* Check() -- Check constructor function */
function Check(element,name,checks)
{
 this.element = element;
 this.name = name;
 this.checks = checks;
 return this;
}
/* warn_empty() -- Warn that field is empty */
function warn_empty(field,field_name)
{
 field.focus();
 var msg = "A field in form is blank";
 
 switch (field.type)
 {
  case "file":
  case "textarea": 
  case "text":
  case "password":
   msg = "" + field_name + "\n";
   break;
  /*case "textarea": 
   msg =  field_name;
   break;
  case "text":
   msg =  field_name;
   break;*/
  case "hidden":
   msg =  field_name;
   break;
  case "checkbox":
   msg = "" + field_name + "\n";
   break;
  case "radio":
   msg = "You forgot to select the following option:\n\n\t" + field_name + "\n";
   break;
  case "select-one":
   msg = field_name;
   break;
  case "select-multiple":
   msg = "You failed to select options for:\n\n\t" + field_name + "\n";
   break;
 }
 alert(msg);
 return false;
}
/* warn_invalid() -- Warn that field is invalid */
function warn_invalid(field,field_name,warn_message)
{
 field.focus();
 alert("" + warn_message + "\n\n" + "");
 //alert(warn_message);
 return false;
}
/* add_check() -- Add a form check */
function add_check(element,name,checks)
{
 this.form_checks.push(new Check(element,name,checks));
}
function matchpwd()
{
 
 
 if (document.frmregister.userpass.value != document.frmregister.cpass.value)
  return "Passwords do not match";
 
 return null;
}
function matchemail()
{
 
 
 if (document.frm.email.value != document.frm.cemail.value)
  return "Email do not match";
 
 return null;
}
function matchpswd()
{
 
 if (document.reg.Password.value != document.reg.confirm_password.value)
  return "Passwords do not match";
 
 return null;
}
function validatedate(){
  
  var fromdate =document.advancesearch.formdate.value;
  var todate = document.advancesearch.todate.value;
  if(fromdate==""&&todate==""){
   return true;
  }
  var str = new String(fromdate);
  day = str.substring(0,2);
  month=str.substring(3,5);
  year=str.substring(6,10);
  
  var str1 = new String(todate);
  day1= str1.substring(0,2);
  month1=str1.substring(3,5);
  year1=str1.substring(6,10);
  
  var curr_date=new Date();
  var dateFrom = new Date(year1, month1, day1);
     var dateTo=new Date(year,month,day);
  
  if(dateTo.getTime()< dateFrom.getTime()) {
   alert("from date  must be less than To date!");
   return false;
  }
 
return true;
}
function submitonce(theform){                             //if IE 4+ or NS 6+
 if (document.all||document.getElementById){                     //screen thru every element in the form, and hunt down "submit" and "reset"
  for (i=0;i<theform.length;i++){
   var tempobj=theform.elements[i];
   if(tempobj.type.toLowerCase()=="submit"||tempobj.type.toLowerCase()=="reset")
   tempobj.disabled=true;                            //disable em
  }
 }
}
/*
 if(trim(document.add_classified.element["classified_price"].value) !="")
 {
  var checkOK ="0123456789.";
  var checkStr = t.price.value;
  var allValid = true;
  for (i = 0;  i < checkStr.length;  i++)
  {
   ch = checkStr.charAt(i);
   for (j = 0;  j < checkOK.length;  j++)
    if (ch == checkOK.charAt(j))
     break;
   
   if (j == checkOK.length)
   {
    allValid = false;
    break;
   }
  }*/
  
//  if (!allValid)
//  {
//   return "Price must only contain numerical characters and decimal.";
//   //return false;
//  }
// }

/********************************************* 
   *DATE FORMAT
 *********************************************
 // Declaring valid date character, minimum year and maximum year
 /*var dtCh= "-";
 var minYear=1900;
 var maxYear=2100;
 var dt=document.add_art_samp.dos
   
 if (isDate(dt.value)==false) {
  dt.focus()
  return false
 }
 function isInteger(s){
  var i;
  for (i = 0; i < s.length; i++)
   {   
    // Check that current character is number.
    var c = s.charAt(i);
    if (((c < "0") || (c > "9"))) return false;
   }
   // All characters are numbers.
   return true;
 }
 function stripCharsInBag(s, bag){
  var i;
  var returnString = "";
  // Search through string's characters one by one.
  // If character is not in bag, append to returnString.
  for (i = 0; i < s.length; i++)
   {   
    var c = s.charAt(i);
    if (bag.indexOf(c) == -1) returnString += c;
   }
  return returnString;
 }
function daysInFebruary (year)
 {
  // February has 29 days in any year evenly divisible by four,
  // EXCEPT for centurial years which are not also divisible by 400.
     return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
 }
function DaysArray(n)
 {
  for (var i = 1; i <= n; i++)
  {
   this[i] = 31
   if (i==4 || i==6 || i==9 || i==11) {this[i] = 30}
   if (i==2) {this[i] = 29}
     } 
     return this
 }
function isDate(dtStr)
 {
  var daysInMonth = DaysArray(12)
  var pos1=dtStr.indexOf(dtCh)
  var pos2=dtStr.indexOf(dtCh,pos1+1)
  var strDay=dtStr.substring(0,pos1)
  var strMonth=dtStr.substring(pos1+1,pos2)
  var strYear=dtStr.substring(pos2+1)
  strYr=strYear
  if (strDay.charAt(0)=="0" && strDay.length>1) strDay=strDay.substring(1)
  if (strMonth.charAt(0)=="0" && strMonth.length>1) strMonth=strMonth.substring(1)
  for (var i = 1; i <= 3; i++) 
   {
    if (strYr.charAt(0)=="0" && strYr.length>1) strYr=strYr.substring(1)
   }
  month=parseInt(strMonth)
  day=parseInt(strDay)
  year=parseInt(strYr)
  if (pos1==-1 || pos2==-1)
   {
    alert("The date format should be : dd-mm-yyyy")
    return false
   }
  if (strMonth.length<1 || month<1 || month>12)
   {
    alert("Please enter a valid month")
    return false
   }
  if (strDay.length<1 || day<1 || day>31 || (month==2 && day>daysInFebruary(year)) || day > daysInMonth[month])
   {
    alert("Please enter a valid day")
    return false
   }
  if (strYear.length != 4 || year==0 || year<minYear || year>maxYear)
   {
    alert("Please enter a valid 4 digit year between "+minYear+" and "+maxYear)
    return false
   }
  if (dtStr.indexOf(dtCh,pos2+1)!=-1 || isInteger(stripCharsInBag(dtStr, dtCh))==false)
   {
    alert("Please enter a valid date")
    return false
   }
 return true
}
/*********************************************/
var image='image';//'swf'
/*function uploadedimgval()
{
 if( image =='image' )
 {
  if (upload_img.the_file.value.indexOf(".jpg") == -1 && upload_img.the_file.value.indexOf(".png") == -1 && upload_img.the_file.value.indexOf(".jpeg") == -1 && upload_img.the_file.value.indexOf(".gif") == -1 && upload_img.the_file.value.indexOf(".JPG") == -1 && upload_img.the_file.value.indexOf(".PNG") == -1  && upload_img.the_file.value.indexOf(".JPEG") == -1 && upload_img.the_file.value.indexOf(".GIF") == -1 )
  {
   return "Please upload .gif/.jpg/.jpeg/.png files only";
  }
 }// end of if
 return null;
}*/
function uploadedimgval1(obj,type){
 
 
 if( type =='audio' )
 {
  if (obj.indexOf(".mp3") == -1 && obj.indexOf(".wav") == -1 && obj.indexOf(".WAV") == -1 && obj.indexOf(".MP3") == -1 &&obj.indexOf(".WMA") == -1 && obj.indexOf(".wma") == -1 )
  {
   return "Please upload .mp3/.wav/.wma files only";
  }
 }// end of if
 else if( type =='video' )
 {
  if (obj.indexOf(".mpeg") == -1 && obj.indexOf(".MPEG") == -1 && obj.indexOf(".avi") == -1 && obj.indexOf(".AVI") == -1 && obj.indexOf(".wmv") == -1 && obj.indexOf(".WMV") == -1  )
  {
   return "Please upload .mpeg/.wmv/.avi files only";
  }
 }// end of if
 else 
 {
  if (obj.indexOf(".jpg") == -1 && obj.indexOf(".png") == -1 && obj.indexOf(".jpeg") == -1 && obj.indexOf(".gif") == -1 && obj.indexOf(".JPG") == -1 && obj.indexOf(".PNG") == -1  &&obj.indexOf(".JPEG") == -1 && obj.indexOf(".GIF") == -1 )
  {
   return "Please upload .gif/.jpg/.jpeg/.png files only";
  }
  
 }
 

 return null;
}

 function myform_value (myfield) {
  myfield.value="";
 }
 var maxWidth=100;                // width to resize large images to
 var maxHeight=100;               // height to resize large images to
 var fileTypes=["gif","png","jpg","jpeg"]; // valid file types
 var outImage="previewField";          // the id of the preview image tag
 var defaultPic="spacer.gif";           // what to display when the image is not valid
 function preview(what){
  var source=what.value;
  var ext=source.substring(source.lastIndexOf(".")+1,source.length).toLowerCase();
  for (var i=0; i<fileTypes.length; i++) if (fileTypes[i]==ext) break;
  globalPic=new Image();
  if (i<fileTypes.length) globalPic.src=source;
  else {
   globalPic.src=defaultPic;
   alert("THAT IS NOT A VALID IMAGE\nPlease load an image with an extention of one of the following:\n\n"+fileTypes.join(", "));
  }
  setTimeout("applyChanges()",200);
 }
 var globalPic;
 function applyChanges(){
  var field=document.getElementById(outImage);
  var x=parseInt(globalPic.width);
  var y=parseInt(globalPic.height);
  if (x>maxWidth) {
   y*=maxWidth/x;
   x=maxWidth;
  }
  if (y>maxHeight) {
   x*=maxHeight/y;
   y=maxHeight;
  }
  field.style.display=(x<1 || y<1)?"none":"";
  field.src=globalPic.src;
  field.width=x;
  field.height=y;
 }
/* do_check() -- Perform form validation check */
function do_check()
{
 var form = eval("document." + this.form_name);
 var checklen = this.form_checks.length;
 for (var i=0; i<checklen; i++)
 {
  // Check field
  var thefield = form.elements[this.form_checks[i].element];
  if (thefield == null) continue;
  // Check if multiple fields
  var fname = this.form_checks[i].name;
  var fields = (thefield.type == null) ? thefield : [thefield];
  // Check fields
  var flen = fields.length;
  var ftype = fields[0].type;
  var clen = this.form_checks[i].checks.length;
  switch (ftype)
  {
   case "text":
   case "password":
   case "textarea":
    for (var j=0; j<flen; j++)
    {
     var field = fields[j];
     var fvalue = fields[j].value;
     if (field.length == 0 || fvalue.search(/\S/) < 0) return warn_empty(field,this.form_checks[i].name);
     for (var k=0; k<clen; k++)
     {
         var fcheck = this.form_checks[i].checks[k];
        
         if (fcheck == "trim"){
        fvalue = fvalue.replace(/^\s+|\s+$/g,"");
        fields[j].value = fvalue;
       }
      else if (fcheck == "alpha" && fvalue.search(/^\w+$/) < 0) return warn_invalid(field,fname,"Non-alphanumeric characters");
      else if (fcheck == "num" && fvalue.search(/^\d+$/) < 0) return warn_invalid(field,fname,"Non-numeric characters");
      //else if (fcheck == "currency" && fvalue.search( /^-?[0-9\.\,]+$/ ) < 0 ) return warn_invalid(field,fname,"Non-numeric and . characters");
      else if (fcheck == "currency" && fvalue.search( /^-?[0-9\.\,]+$/ ) < 0 ) return warn_invalid(field,fname,'Only Numeric Values Allowed in "'+fname+'" Field');
      /*else if (fcheck == "currency" && fvalue.search( /\$?(\d{1,3}(\,\d{3})*|(\d+))(\.\d{0,2})?/ ) < 0 ) return warn_invalid(field,fname,"Non-numeric and . characters");*/
      else if (fcheck.search(/^alpha(.+)$/) == 0 && eval("fvalue.search(/^[\\w" + fcheck.match(/^alpha(.+)/)[1].replace(/(\W|s)/g,"\\$1") + "]+$/)") < 0) return warn_invalid(field,fname,"Cannot use " + eval("fvalue.match(/([^\\w" + fcheck.match(/^alpha(.+)/)[1].replace(/(\W|s)/g,"\\$1") + "])/)[1]") + " character")
      else if (fcheck.search(/^num(.+)$/) == 0 && eval("fvalue.search(/^[\\d" + fcheck.match(/^num(.+)/)[1].replace(/(\D|s)/g,"\\$1") + "]+$/)") < 0) return warn_invalid(field,fname,"Cannot use " + eval("fvalue.match(/([^\\d" + fcheck.match(/^num(.+)/)[1].replace(/(\D|s)/g,"\\$1") + "])/)[1]") + " character")
      else if (fcheck == "email"  && (fvalue.indexOf("@") != fvalue.lastIndexOf("@") || fvalue.search(/\w+\@[\w\-\.]+\.\w{2,4}$/) < 0)) return warn_invalid(field,fname,"invalid email address");
      else if (fcheck == "cemai" && (fvalue.indexOf("@") != fvalue.lastIndexOf("@") || fvalue.search(/\w+\@[\w\-\.]+\.\w{2,4}$/) < 0)) return warn_invalid(field,fname,"invalid email address");
      else if (fcheck == "url" && fvalue.search(/^https*:\/\/\w[\w\-\.]{4,}/) < 0) return warn_invalid(field,fname,"Invalid URL");
      else if (fcheck.search(/^min(\d+)$/) == 0 && fvalue.length < fcheck.match(/^min(\d+)$/)[1]) return warn_invalid(field,fname,"Minimum of " + fcheck.match(/^min(\d+)$/)[1] + " characters "+fname);
      else if (fcheck.search(/^max(\d+)$/) == 0 && fvalue.length > fcheck.match(/^max(\d+)$/)[1]) return warn_invalid(field,fname,"Maximum of " + fcheck.match(/^max(\d+)$/)[1] + " characters");
      else if (fcheck.search(/^regex\/(.+)\/$/) == 0 && eval("fvalue.search(/" + fcheck.match(/^regex\/(.+)\/$/)[1] + "/)") < 0) return warn_invalid(field,fname,"Invalid format");
      else if (fcheck.search(/^func-(\w+)$/) == 0)
      {
       var invalidmsg = eval(fcheck.match(/^func-(\w+)$/)[1] + "(field)");
       if (invalidmsg != null && invalidmsg.length > 0) return warn_invalid(field,fname,invalidmsg);
      }
     }
    }
    break;
   case "checkbox":
   case "radio":
    
    var fcount = 0;
    var field = fields[0];
    for (var j=0; j<flen; j++)
    {
     if (fields[j].checked) fcount++;
    }
    if (fcount == 0) return warn_empty(field,fname);
    for (var k=0; k<clen; k++)
    {
     var fcheck = this.form_checks[i].checks[k];
     if (fcheck.search(/^min(\d+)$/) == 0 && fcount < fcheck.match(/^min(\d+)$/)[1]) return warn_invalid(field,fname,"At least " + fcheck.match(/^min(\d+)$/)[1] + " options are required");
     else if (fcheck.search(/^max(\d+)$/) == 0 && fcount > fcheck.match(/^max(\d+)$/)[1]) return warn_invalid(field,fname,"Cannot choose more than " + fcheck.match(/^max(\d+)$/)[1] + " options");
    }
    break;
   case "select-one":
   case "select-multiple":
    for (var j=0; j<flen; j++)
    {
     var field = fields[j];
     if (field.type == "select-one" && field.selectedIndex == 0) return warn_empty(field,fname);
     else if (field.type == "select-multiple" && clen > 0)
     {
      var fcount = 0;
      for (var k=0; k<field.options.length; k++)
      {
       if (field.options[k].selected) fcount++;
      }
      for (var k=0; k<clen; k++)
      {
       var fcheck = this.form_checks[i].checks[k];
       if (fcheck.search(/^min(\d+)$/) == 0 && fcount < fcheck.match(/^min(\d+)$/)[1]) return warn_invalid(field,fname,"Please select atleast "+ fcheck.match(/^min(\d+)$/)[1] +" "+fname);
       else if (fcheck.search(/^max(\d+)$/) == 0 && fcount > fcheck.match(/^max(\d+)$/)[1]) return warn_invalid(field,fname,"A user can only select maximum of " + fcheck.match(/^max(\d+)$/)[1] + " choices.");
      }
     }
    }
    break;
  case "file":
    var fcount = 0;
    var alertimage
    var field = fields[0];
    for (var j=0; j<flen; j++)
    {
     var fcheck = this.form_checks[i].checks[j];
     //alert(fields[j].value);
     if (fields[j].value!=""){ //1
      alertimage=uploadedimgval1(fields[j].value,fcheck);
      if(alertimage!=""&&alertimage!=null){  //1.1
       alert(alertimage);
       return false;
      }
     }
    }
    
    break;
  
  }
 }
 return true;
}
/* FValidate -- FValidate constructor function */
function FValidate(form_name)
{
 
 this.form_checks = new Array();
 this.form_name = form_name;
 this.add_check = add_check;
 this.check = do_check;
}
 
 
 
/* ]]> */
 
/*
<script type="text/javascript" src="fvalidate.js"></script>
<form name="loginform" action="" method="post" onsubmit='return fvalid_login.check(this)'>
var fvalid_login = new FValidate("loginform");
fvalid_login.add_check("payableto","Payable To",["trim"]);
fvalid_login.add_check("address","Address",["trim","min5","max40"]);
fvalid_login.add_check("city","City",["trim","min3","max30"]);
fvalid_login.add_check("province","State / Province",["trim","min2","max25"]);
fvalid_login.add_check("zip","Zip",["trim"]);
fvalid_login.add_check("price","Price",["trim","currency","min1","max11"]); 
fvalid_login.add_check("country","Fill Country",["trim"]);
fvalid_login.add_check("siteurl","Site URL",["trim","url","max90"]);
 
fvalid_login.add_check("name","Contact Name",["trim","min5","max32"]);
fvalid_login.add_check("email","Email Address",["trim","email","max45"]);
fvalid_login.add_check("terms","Terms and Conditions",["trim"]);
fvalid_login.add_check("password","Password",["trim","alpha","min5","max20"]);
fvalid_login.add_check("password2","Confirm Password",["trim","alpha","min5","max20","func-matchpwd"]); // signupform
fvalid_login.add_check("the_file","Property Images",["trim","func-uploadedimgval"]);// signupform
*/

