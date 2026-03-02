/*This function changes action of the form and submits it to fetch_contacts.php*/


function fetchContacts()
{
	var contactString = "";
	var count		  =  0;
	
	for(var i = 0;i < document.getElementById("selContacts[]").options.length;i++)
	{
		if(document.getElementById("selContacts[]").options[i].selected)
		{
			if(count > 0)
			{
				contactString += ",";
			}
				contactString += document.getElementById("selContacts[]").options[i].text;
				count++;
		}
	}
	
	document.form1.txtTo.value = contactString;
}

/*This function will make all checkboxes on the form checked or unchecked*/

function checkAll(CheckBoxControl)
{
	if(CheckBoxControl.checked == true)   
	{
		var i = 0;
		var j = 0;
		
		for (i=0; i < document.forms[0].elements.length; i++) 
		{
			if(document.forms[0].elements[i].name == "chkbox" + j)
			{
				document.forms[0].elements[i].checked = true;
			}
			j++;
		}
	}
	
	else if(CheckBoxControl.checked == false)
	{
		var i = 0;
		var j = 0;
		
		for (i=0; i < document.forms[0].elements.length; i++) 
		{
			if(document.forms[0].elements[i].name == "chkbox" +j)
			{
				document.forms[0].elements[i].checked = false;
			}
			j++;
		}
	}
}

/*This function will make all checkboxes on the form checked or unchecked*/

function checkAllOneOne(CheckBoxControl)
{ 

	
	if(CheckBoxControl.checked == true)   
	{ 
		var i = 0;
		var j = 1;
		
		for (i=1; i < document.forms[0].elements.length; i++) 
		{	
			
			if(document.forms[0].elements[i].name == "chkbox" + j)
			{	
				document.forms[0].elements[i].checked = true;
				j++;
			}
		}
	}
	
	else if(CheckBoxControl.checked == false)
	{
		var i = 0;
		var j = 1;
		
		for (i=1; i < document.forms[0].elements.length; i++) 
		{
			if(document.forms[0].elements[i].name == "chkbox" +j)
			{
				document.forms[0].elements[i].checked = false;
				j++;
			}
		}
	}
}

/*This function will make all checkboxes on the form checked or unchecked*/

function checkAllOneOne1(CheckBoxControl)
{ 
	
	if(CheckBoxControl.checked == true)   
	{ 
		var i = 0;
		var j = 1;
		
		for (i=1; i < document.form1.elements.length; i++) 
		{	
			
			if(document.form1.elements[i].name == "chkbox" + j)
			{	
				document.form1.elements[i].checked = true;
				j++;
			}
		}
	}
	
	else if(CheckBoxControl.checked == false)
	{
		var i = 0;
		var j = 1;
		
		for (i=1; i < document.form1.elements.length; i++) 
		{
			if(document.form1.elements[i].name == "chkbox" +j)
			{
				document.form1.elements[i].checked = false;
				j++;
			}
		}
	}
}

/*This function will make all checkboxes on the form checked or unchecked*/






function checkAllOne(CheckBoxControl)
{
	if(CheckBoxControl.checked == true)   
	{
		var i = 0;
		var j = 1;
		for (i=1; i < document.forms[0].elements.length; i++) 
		{
//			alert (document.forms[0].elements[i].name + "==" + "chkbox" + j);
			if(document.forms[0].elements[i].name == "chkbox" + j)
			{
//				alert(document.forms[0].elements[i].name + "now checked");
				document.forms[0].elements[i].checked = true;
				j++;
			}
		}
	}
	
	else if(CheckBoxControl.checked == false)
	{
		var i = 0;
		var j = 1;
		
		for (i=1; i < document.forms[0].elements.length; i++) 
		{
//			alert (document.forms[0].elements[i].name + "==" + "chkbox" + j);
			if(document.forms[0].elements[i].name == "chkbox" +j)
			{
				document.forms[0].elements[i].checked = false;
				j++;
			}
		}
	}
}




function confirmCheckBoxes(flag)
{
	var frm;
	frm	=	this.form1;
	var intCount = document.getElementById("hid_Cont").value;
		
	for(i=0;i < intCount;i++)
	{
		if(document.getElementById("chkbox"+(i+1)).checked == true)
		{
			selected = true;
			break;
		}
		
		else
		{
			selected = false;
		}
	}
	
	if(!selected)
	{
		alert("No message selcted!");
		document.form1.elements(0).focus();
		return false;
	}
	
	/*if flag = 1 form shall be submited to delete_from_inbox.php page
	  if flag = 2 form shall be submited to delete_from_sent.php page 
	  if flag = 3 form shall be submited to delete_from_trash.php page
	*/
	
	if(flag == 1)	
	{
		document.form1.action = "delete_from_inbox.php";
		document.form1.submit(); 	
	}
	
	else if(flag == 2)	
	{
		document.form1.action = "delete_from_sent_items.php";
		document.form1.submit(); 	
	}
	
	else if(flag == 3)
	{
		document.form1.action = "delete_from_trash.php";
		document.form1.submit(); 	
	}	
	
}


/*This function confirms that atleast one message is selected*/

/*function confirmCheckBoxes(flag)
{
	var form1 = document.getElementById("form1");
	alert (form1.length);
	for(i=0;i<form1.length;i++)
	{
		if(form1.elements(i).type=='checkbox' && form1.elements(i).checked == true)
		{
			selected = true;
			break;
		}
		
		else
		{
			selected = false;
		}
	}
	alert selected;
	if(!selected)
	{
		alert("No message selcted!");
		form1.elements(0).focus();
		return false;
	}
	
	/*if flag = 1 form shall be submited to delete_from_inbox.php page
	  if flag = 2 form shall be submited to delete_from_sent.php page 
	  if flag = 3 form shall be submited to delete_from_trash.php page
	*/
	
/*	if(flag == 1)	
	{
		form1.action = "delete_from_inbox.php";
		form1.submit(); 	
	}
	
	else if(flag == 2)	
	{
		form1.action = "delete_from_sent_items.php";
		alert form1.action; 
		form1.submit(); 	
	}
	
	else if(flag == 3)
	{
		form1.action = "delete_from_trash.php";
		form1.submit(); 	
	}	
//	return true;

}*/

/*This function checks whether form fields are empty or not and also checks whether new password 
  and confirm password fields match or not*/
  
function validateForm()
{
	if(document.form1.txtCurrPass.value == "")
	{
		alert("Please enter your current password");
		document.form1.txtCurrPass.focus();
		return false;
	}
	
	if(document.form1.txtNewPass.value == "")
	{
		alert("Please enter your new password");
		document.form1.txtNewPass.focus();
		return false;
	}
	
	if(document.form1.txtConfNewPass.value == "")
	{
		alert("Please confirm your new password");
		document.form1.txtConfNewPass.focus();
		return false;
	}
	
	if(document.form1.txtNewPass.value != document.form1.txtConfNewPass.value)
	{
		alert("New password and confirm new password do not match");
		document.form1.txtConfNewPass.focus();
		return false;
	}
	
	return true;
}	

/*This function checks blog length, whether it is more than 120 characters or not*/

function checkBlogLength()
{
	var str = "";
	
	str = trimString(document.form1.txtDetail.value);
	
/*	if(str.length > 120)
	{		
		alert("Blog detail must not be more than 120 characters");
		return false;
	}*/
	
	return true;
}

/*This function checks forum length, whether it is more than 120 characters or not*/

function checkForumLength()
{
	var str = "";
	
	str = trimString(document.form1.txtDetailForum.value);
	
/*	if(str.length > 120)
	{		
		alert("Forum detail must not be more than 120 characters");
		return false;
	}*/
	
	return true;
}

/*This function checks forum post post length, whether it is more than 120 characters or not*/

function checkForumPostLength()
{
	var str = "";
	
	str = trimString(document.form1.txtReply.value);
	
/*	if(str.length > 120)
	{		
		alert("Forum reply must not be more than 120 characters");
		return false;
	}*/
	
	return true;
}

/*This function removes leading and trailing white spaces from a string*/

function trimString(str)
{
	str = this != window? this : str;
  	return str.replace(/^\s+/g, '').replace(/\s+$/g, '');
}

/*This function performs date comparison between two dates*/

function dateComparison(obj,val1,val2,lbl1,lbl2)
{
	if(document.forms[obj].selType.value == "Daily")
	{
		if(document.forms[obj].txtStartDate.value == "")
		{
			alert("Please select a start date");
			document.forms[obj].txtStartDate.focus();
			return false;
		}
	}

	else if(document.forms[obj].selType.value == "One")
	{
		if(document.forms[obj].txtStartDate.value == "")
		{
			alert("Please select a start date");
			document.forms[obj].txtStartDate.focus();
			return false;
		}
	}

	else if(document.forms[obj].selType.value == "Monthly")
	{
		if(document.forms[obj].txtStartDate.value == "")
		{
			alert("Please select a start date");
			document.forms[obj].txtStartDate.focus();
			return false;
		}
		
		else if(document.forms[obj].txtEndDate.value == "")
		{
			alert("Please select an end date");
			document.forms[obj].txtEndDate.focus();
			return false;
		}
	}
	
	else if(document.forms[obj].selType.value == "Weekly")
	{
		if(document.forms[obj].txtStartDate.value == "")
		{
			alert("Please select a start date");
			document.forms[obj].txtStartDate.focus();
			return false;
		}
		
		else if(document.forms[obj].txtEndDate.value == "")
		{
			alert("Please select an end date");
			document.forms[obj].txtEndDate.focus();
			return false;
		}
	}
		
	var frmName = document.forms[obj].name;	
	var startDate = eval('document.'+frmName+'.'+val1);
	var endDate   = eval('document.'+frmName+'.'+val2);
	
	if(startDate.value != "" && endDate.value != "")
	{
		if(startDate.value > endDate.value)
		{
			alert(lbl1+' must be less or equal to '+lbl2);
			return false;
		}
		
		return true;
	}
}

function delete_cat()
{
	$var	= confirm("Are you sure to delete this category? \n if you delete this catrgory all the product related \nto this category will be lost");
	if($var==true)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function delete_pro()
{
	$var	= confirm("Are you sure to delete this?");
	if($var==true)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function delete_subpage()
{
	$var	= confirm("Are you sure to delete this page?");
	if($var==true)
	{
		return true;
	}
	else
	{
		return false;
	}
}


function delete_cont()
{
	$var	= confirm("Are you sure to delete this contact?");
	if($var==true)
	{
		return true;
	}
	else
	{
		return false;
	}
}
function delete_promake()
{
	$var	= confirm("Are you sure to delete this record?");
	if($var==true)
	{
		return true;
	}
	else
	{
		return false;
	}
}