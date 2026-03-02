function valid_fo(){
	var	fn	=	document.asse;
	var membl	= fn.listTitle.length;
	var Flag;
	for(i=0;i<membl;i++){
		if(document.asse.listTitle[i].checked==true){
			Flag = true;
			break;
		}else{
			Flag = false;
		}
	}
	if(Flag==true){
	}else{
		alert("Please Enter the Title.");
		return false;
	}
	if(fn.txtFirstName.value==''){
		alert("Please enter the First Name");
		fn.txtFirstName.focus();
		return false;
	}
	if(fn.txtLastName.value==''){
		alert("Please enter the Last Name");
		fn.txtLastName.focus();
		return false;
	}
	if(fn.listDay.value=='' || fn.listMonth.value=='' || fn.listYear.value==''){
		alert("Please Select Date of Birth");
		fn.txtLastName.focus();
		return false;
	}
	if(fn.listNationality.value==''){
		alert("Please Select Nationality");
		fn.listNationality.focus();
		return false;
	}
	if(fn.listCountryOfResidence.value==''){
		alert("Please Select Country of Residence");
		fn.listCountryOfResidence.focus();
		return false;
	}
	if(fn.listCountry.value==''){
		alert("Please Select Contact Country");
		fn.listCountry.focus();
		return false;
	}
	if(fn.txtEmail.value==''){
		alert("Please Select Contact Email");
		fn.txtEmail.focus();
		return false;
	}else{
					var validRegExp = /^[^@]+@[^@]+.[a-z]{2,}$/i;
					fn.txtEmail.value;
					if (fn.txtEmail.value.search(validRegExp) == -1) 
					{
					alert('Please Enter the Valid E-Mail Address');
					return false;
					}
		
	}
	if(fn.txtPhone.value==''){
		alert("Please Select Contact Phone");
		fn.txtPhone.focus();
		return false;
	}
	if(fn.listTotalYearsEducation.value==''){
		alert("Please Select years of full-time education");
		fn.listTotalYearsEducation.focus();
		return false;
	}
	if(fn.listTotalYearsWorkExp.value==''){
		alert("Please Select Total years of work experience");
		fn.listTotalYearsWorkExp.focus();
		return false;
	}
	
	var memb2	= fn.listEnglishReading.length;
	var Flag2;
	for(i=0;i<memb2;i++){
		if(document.asse.listEnglishReading[i].checked==true){
			Flag2 = true;
			break;
		}else{
			Flag2 = false;
		}
	}
	if(Flag2==true){
	}else{
		alert("Please Select English Language Reading Ability.");
		return false;
	}
	var memb3	= fn.listEnglishWriting.length;
	var Flag3;
	for(i=0;i<memb3;i++){
		if(document.asse.listEnglishWriting[i].checked==true){
			Flag3 = true;
			break;
		}else{
			Flag3 = false;
		}
	}
	if(Flag3==true){
	}else{
		alert("Please Select English Language Writing Ability.");
		return false;
	}
	var memb4	= fn.listEnglishSpeaking.length;
	var Flag4;
	for(i=0;i<memb4;i++){
		if(document.asse.listEnglishSpeaking[i].checked==true){
			Flag4 = true;
			break;
		}else{
			Flag4 = false;
		}
	}
	if(Flag4==true){
	}else{
		alert("Please Select English Language Speaking Ability.");
		return false;
	}
	
	var memb5	= fn.listEnglishListening.length;
	var Flag5;
	for(i=0;i<memb5;i++){
		if(document.asse.listEnglishListening[i].checked==true){
			Flag5 = true;
			break;
		}else{
			Flag5 = false;
		}
	}
	if(Flag5==true){
	}else{
		alert("Please Select English Language Listening Ability.");
		return false;
	}
	
	if(fn.listInstituteKind.value==''){
		alert("Please Select Institute do you prefer.");
		fn.listInstituteKind.focus();
		return false;
	}
	if(fn.listProgramType.value==''){
		alert("Please Select program are you interested.");
		fn.listProgramType.focus();
		return false;
	}
	if(fn.listMajor.value==''){
		alert("Please Select type of Major do you prefer.");
		fn.listMajor.focus();
		return false;
	}
	if(fn.listStudySession.value==''){
		alert("Please Select Session do you prefer to apply.");
		fn.listStudySession.focus();
		return false;
	}
	if(fn.listStudyFeeCurrency.value==''){
		alert("Please Select study fee can you afford to pay.");
		fn.listStudyFeeCurrency.focus();
		return false;
	}
	if(fn.listStudyFee.value==''){
		alert("Please Select study fee can you afford to pay.");
		fn.listStudyFee.focus();
		return false;
	}
	
	
}