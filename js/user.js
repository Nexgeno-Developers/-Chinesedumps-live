 function validation()
   {
   
   		filter=/^.+@.+\..{2,3}$/;
		if (document.form2.Email.value=="")
		{
			alert("Please Enter Your Email Address!")
			document.form2.Email.focus();
    		return false;
		}
		else if (!(filter.test(document.form2.Email.value)))
		{
			alert("Please enter a valid email address!")
			document.form2.Email.focus();
    		return false;
		}
			
	if(document.form2.Password.value=="")
   		{
   		alert('please enter Password')
		document.form2.Password.focus();
		return false;
   		}
	
	return true;

   }
  
  function forgot(){
  
  var	usr	=	document.getElementById('email').value;
	if(usr == '')
	{
		alert("Please Provide Email Address.");
		document.getElementById('email').focus();
		return false;
		
	}else{
				var validRegExp = /^[^@]+@[^@]+.[a-z]{2,}$/i;
					if (usr.search(validRegExp) == -1) 
					{
					alert("Please Provide Valid Email");
					document.getElementById('email').focus();
					return false;
					}
		}
	
  $("#showforgot").load("ajax/forgot.php?usr="+usr);
	return false;
 }
 
 function chekemailsignup(){
 
  var	usr	=	document.getElementById('emails').value;
  var validRegExp = /^[^@]+@[^@]+.[a-z]{2,}$/i;
	 
	if(document.getElementById('fName').value==''){
	 alert("Please Provide First Name.");
	 document.getElementById('fName').focus();
	 return false;
	 }
	 
	 if(document.getElementById('lastname') && document.getElementById('lastname').value==''){
		  alert("Please Provide Last Name.");
		  document.getElementById('lastname').focus();
	 return false;
	 }
	 
	  if(document.getElementById('emails').value==''){
		  alert("Please Provide Email Address.");
		  document.getElementById('emails').focus();
	 return false;
	 }else{
	 	if (usr.search(validRegExp) == -1) 
			{
				alert("Please enter a valid email address!")
				document.getElementById('emails').focus();
				return false;
			}
	 }
	 
	 if(document.getElementById('passwords').value==''){
	 	alert("Please enter the password!");
		document.getElementById('passwords').focus();
	 return false;
	 }
	
	 if(document.getElementById('repassword').value==''){
		 alert("Please enter the re password!");
		 document.getElementById('repassword').focus();
	 return false;
	 }else{
	 	
		if(document.getElementById('passwords').value!= document.getElementById('repassword').value){
		  alert("Password and Repassword not match.!");
		  document.getElementById('passwords').focus();
		 return false; 
	  }
	 
	 }
	if(document.getElementById('captcha_code') && document.getElementById('captcha_code').value==''){
	 alert("Please Provide Security code.");
	 document.getElementById('captcha_code').focus();
	 return false;
	 }
	 
	return true;
	
 }
 
  function updatosb(){
 
 if(document.getElementById('fName').value==''){
	 alert("Please Provide First Name.");
	 document.getElementById('fName').focus();
	 return false;
	 }
	 
	 if(document.getElementById('lastname').value==''){
		  alert("Please Provide Last Name.");
		  document.getElementById('lastname').focus();
	 return false;
	 }
	 
	  if(document.getElementById('passwords').value!='' || document.getElementById('repassword').value!=''){	  	
		if(document.getElementById('passwords').value!= document.getElementById('repassword').value){
		  alert("Password and Repassword not match.!");
		  document.getElementById('passwords').focus();
		 return false; 
	  }
	 
	 }
	 
	return true;
	
 }