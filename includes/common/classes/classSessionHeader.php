<?PHP
/*
..........................................................................................................
   																						
..........................................................................................................

*/
		//--------------------------------------------------------------------------------
		//					<<<<<Flush any output Area>>>>>	
	ob_start();         # Flush the output.

	error_reporting(0); # Stop all unwanted Warnings and Errors to be displayed.
	session_start();    # Start the Session.
			
   // This class provides the Functionlity to handle Sessions.
	class classSessionHeader
	{
		//..........................................................................................................

		//This function provides the Functionaliy of Checking Sessions.
		// Takes three parameters
		// 1: Session Name
		// 2: Redirect url
		// 3: Faliure Message.
		// Returns nothing.
		//..........................................................................................................		
		function checkSession($txtSessionName,$urlRedirect,$txtErrTxt)
		{
			global $_PATH;
			//Check the session.User is properly get signed in or not
			if (!empty($_SESSION[$txtSessionName]) && isset($_SESSION[$txtSessionName]));
			else
			{
				//Display message to the user to get signed in properly.
				die("<center>". $txtErrTxt ."<a href='".$urlRedirect."'>Click here</a></center>");
			}						
		} # checkSession($txtSessionName,$urlRedirect,$txtErrTxt)

		//..........................................................................................................

		//This function provides the Functionaliy of Creating Sessions.
		// Takes two parameters
		// 1: Session Name
		// 2: Session Text
		// Returns nothing.
		//..........................................................................................................		
		function createSession($txtSessionName,$txtSessionTxt)
		{
			//If Session is already exists, do nothing
			if (!empty($_SESSION[$txtSessionName]) && isset($_SESSION[$txtSessionName]));
			//Create a new Session with provided parameters
			else
			{
				$_SESSION[$txtSessionName] =  $txtSessionTxt;
			}						
		} # createSession($txtSessionName,$txtSessionTxt)
		//..........................................................................................................	

		//This function provides the Functionaliy of Destroying Sessions.
		// Takes no parameters
		// Returns nothing.
		//..........................................................................................................		
		function destroySession()
		{
			//Unset and Destroy the Session.
			session_unset();
			session_destroy();
		} # destroySession()
		//..........................................................................................................	

	}# classSessionHeader
	
?>