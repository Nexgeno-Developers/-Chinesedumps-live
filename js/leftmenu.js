function showallproafterleft(id,sesid){
			
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			var fullurl2 = 'ajax/getcheckoutproleft.php?pid='+id+'&sesss='+sesid;
			xmlhttp.open("GET",fullurl2,false);
			xmlhttp.send(null);
			document.getElementById('carthidshowafterdel').innerHTML=xmlhttp.responseText;
				
						
			}
			
			function showallpro(id,sesid){
			
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			var fullurl3 = 'ajax/getcheckoutpro.php?pid='+id+'&sesss='+sesid+'&pagepart=ajaxpage';
			xmlhttp.open("GET",fullurl3,false);
			xmlhttp.send(null);
			document.getElementById('showallpro').innerHTML=xmlhttp.responseText;
			
			location.reload();
				
						
			}
	