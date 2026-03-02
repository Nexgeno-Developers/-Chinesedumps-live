<?php
/*
		//Class which provides the functionality of the classPaging on the records coming from the Db.
		// It is for mysql

..........................................................................................................
	   										MotivNation Project
											-------------------
										
						File:    classPaging.php 
						Class:   classclassPaging
						Author:   Syed Murtaza Hussain Kazmi
						Company:  Solution Summit
						Email :	  solution_summit@yahoo.com , solutionsummit@aol.com									
						Module Started Date : 2006-04-13.
						Time of Craetion:	   7:01:15 PM	
						Modification History: Null
						All Rights Reserved By Solution Summit,Any unAthorized Copy of any part 
						of this module is Prohibited.
													
..........................................................................................................

*/
		

class classPaging
{
	var $_PageName;				# Var to store the Page Name on which classPaging is required.
	var $_CURRENT_Page_Index;  	# Var Which is used to check the Start of the page.
	var $_LIMIT;    			# Var to Limit the Records.
	var $_COUNT; 				# Var to store the Total # of records.
	var $_PageCount; 			# Var to store the Total # of Pages.	
	var $_links_per_Page;		# Var to store Links per page.					
	var $_SP_Query;				# Var to store the Query to navigation of records.					
	var $_Page_Index;			# Var to store the position of the current page.						
	var $_Qry_PARAMS;			# Var to store the Any URLQueryString of the current page.							
	var $_LinksClass;			# Var to store the Class of the Link
		
	// Constructor Function Which sets some Starting parameters for navigation
	function classPaging($PageName,$_Limit , $linksPerPage,$qryParam="",$txtLinkClass="")
	{
		$this->_CURRENT_Page_Index	= 1 ; 				# Set the current page Idex to zero.
		$this->_PageName 			= $PageName;  	 	# Set page Name from on which you want classPaging.
		$this->_LIMIT 				= $_Limit ;			# Set Records which are to be displayed on each Page. 
		$this->_links_per_Page 		= $linksPerPage; 	# Set total links which are to be displayed on each Page. 			
		$this->_Page_Index 			= 1 ;				# Set each Page Index (position). 
		$this->_Qry_PARAMS 			= $qryParam ;		# Set the Any URLQueryString of the current page.
		$this->_LinksClass			= $txtLinkClass;	# Set the Links Class.	
	}# End of Constructor.
	
		//This function provides the Functionaliy of Counting total # of Records in the given table.
		// Takes three parameters , 
		// 1)  Name of all the Table of which you want to get total Records.
		// 2)  Condition if any 
		// 3)  Object of the Connection		
		// Returns total # of Records.

		//..........................................................................................................		
		function count_TotalRecs($resultSet)
		{
				//====================================================================================
				//				Local Variables
				$result = "";
				//====================================================================================
				//Execute the Query for given table
				//$result = $conObj->Sql_Query_Exec(" COUNT(1) AS CountRec ", $_Table_Name ,$Condition );
				//Get the total # of Recs
				$this->_COUNT = mysql_num_rows($resultSet); 
				//Return the count
				return $this->_COUNT;

		} # End of count_TotalRecs($_Table_Name , $Condition);
		//..........................................................................................................	
	
		//This function provides the Functionaliy of Counting Total Pages.
		// Takes no parameter , 
		// Returns Page Count.
		// Sets the Total Pages.
		
		//..........................................................................................................		
		function pageCount()
		{
			if(!empty($this->_COUNT))
				$this->_PageCount	= ceil($this->_COUNT / $this->_LIMIT);
			else
				$this->_PageCount	= 0 ;
			return $this->_PageCount;	
		}# End of pageCount()
		//..........................................................................................................	
		
		//This function provides the Functionaliy of Navigable links.
		// Takes no parameter , 
		// Returns A navigable Link
		
		//..........................................................................................................		
		function NavigationInnerLinks()
		{
			//====================================================================================
			//				Local Variables
			$Navigable_Links = "";
			//====================================================================================
			
			//Make it sure that there are some pages for Navigation
			if($this->_PageCount > 1)
			{
				//If Page Count is Less than the Desired Navigation Links, 
				// Set Navigation links per page ver to page Count.
				if($this->_PageCount <= $this->_links_per_Page )
					$this->_links_per_Page = $this->_PageCount;

				//Set the Current Page which has been hit
				if(!empty($_GET['pgIndex']) && isset($_GET['pgIndex']) && !empty($_GET['currentPage']) && isset($_GET['currentPage']))
				{
					$this->_Page_Index = $_GET['pgIndex'];
					$this->_CURRENT_Page_Index = $_GET['currentPage'] ; 
				}	

				//Check that if the Coming page is the last link of the Navigatoin
				//Set CurrentPageIndex variable to that Coming Page #.

				  if (($this->_Page_Index==1 || $this->_Page_Index == $this->_links_per_Page ) && $this->_CURRENT_Page_Index !=1)
					  $this->_Page_Index = 1;
				  else if(($this->_Page_Index > 1 && $this->_Page_Index < $this->_links_per_Page ))
				  	$this->_CURRENT_Page_Index = (($this->_CURRENT_Page_Index - $this->_Page_Index) + 1) ; 
				
					//Loop up to the Allowable links on per page.	
					for($inc = 1 ; $inc <= $this->_links_per_Page; $inc++)
					{
						if($this->_CURRENT_Page_Index <= $this->_PageCount)	
						{
							if($_GET['currentPage'] == $this->_CURRENT_Page_Index)
								$Navigable_Links .= "<b>". $_GET['currentPage'] ."</b>&nbsp;";
	
							 if($_GET['currentPage'] <> $this->_CURRENT_Page_Index)	
								$Navigable_Links .= "<a href=\"". $this->_PageName ."?pgIndex=". $inc ."&currentPage=". $this->_CURRENT_Page_Index . $this->_Qry_PARAMS ."\" ". $this->_LinksClass .">". $this->_CURRENT_Page_Index ."</a>&nbsp;";

							$this->_CURRENT_Page_Index ++;
						}				
					}# End of for Loop
			}# End of pageCount Check If
				
			//Return the links
			return $Navigable_Links;
		}# End of NavigationInnerLinks()

		//..........................................................................................................	
		
		//..........................................................................................................		
		function NavigationInnerLinksUser()
		{
			//====================================================================================
			//				Local Variables
			$Navigable_Links = "";
			//====================================================================================
			
			//Make it sure that there are some pages for Navigation
			if($this->_PageCount > 1)
			{
				//If Page Count is Less than the Desired Navigation Links, 
				// Set Navigation links per page ver to page Count.
				if($this->_PageCount <= $this->_links_per_Page )
					$this->_links_per_Page = $this->_PageCount;

				//Set the Current Page which has been hit
				if(!empty($_GET['pgIndex']) && isset($_GET['pgIndex']) && !empty($_GET['currentPage']) && isset($_GET['currentPage']))
				{
					$this->_Page_Index = $_GET['pgIndex'];
					$this->_CURRENT_Page_Index = $_GET['currentPage'] ; 
				}	

				//Check that if the Coming page is the last link of the Navigatoin
				//Set CurrentPageIndex variable to that Coming Page #.

				  if (($this->_Page_Index==1 || $this->_Page_Index == $this->_links_per_Page ) && $this->_CURRENT_Page_Index !=1)
					  $this->_Page_Index = 1;
				  else if(($this->_Page_Index > 1 && $this->_Page_Index < $this->_links_per_Page ))
				  	$this->_CURRENT_Page_Index = (($this->_CURRENT_Page_Index - $this->_Page_Index) + 1) ; 
				
					//Loop up to the Allowable links on per page.	
					for($inc = 1 ; $inc <= $this->_links_per_Page; $inc++)
					{
						if($this->_CURRENT_Page_Index <= $this->_PageCount)	
						{
							if($_GET['currentPage'] == $this->_CURRENT_Page_Index)
								$Navigable_Links .= "<li><a class=\"active\" href=\"#\">". $_GET['currentPage'] ."</a></li>&nbsp;";
	
							 if($_GET['currentPage'] <> $this->_CURRENT_Page_Index)	
								$Navigable_Links .= "<li><a href=\"". $this->_PageName ."?pgIndex=". $inc ."&currentPage=". $this->_CURRENT_Page_Index . $this->_Qry_PARAMS ."\" ". $this->_LinksClass .">". $this->_CURRENT_Page_Index ."</a></li>&nbsp;";

							$this->_CURRENT_Page_Index ++;
						}				
					}# End of for Loop
			}# End of pageCount Check If
				
			//Return the links
			return $Navigable_Links;
		}# End of NavigationInnerLinks()

		//..........................................................................................................
		//..........................................................................................................		
		function NavigationInnerLinksMedia()
		{
			//====================================================================================
			//				Local Variables
			$Navigable_Links = "";
			//====================================================================================
			
			//Make it sure that there are some pages for Navigation
			if($this->_PageCount > 1)
			{
				//If Page Count is Less than the Desired Navigation Links, 
				// Set Navigation links per page ver to page Count.
				if($this->_PageCount <= $this->_links_per_Page )
					$this->_links_per_Page = $this->_PageCount;

				//Set the Current Page which has been hit
				if(!empty($_GET['pgIndex']) && isset($_GET['pgIndex']) && !empty($_GET['currentPage']) && isset($_GET['currentPage']))
				{
					$this->_Page_Index = $_GET['pgIndex'];
					$this->_CURRENT_Page_Index = $_GET['currentPage'] ; 
				}	

				//Check that if the Coming page is the last link of the Navigatoin
				//Set CurrentPageIndex variable to that Coming Page #.

				  if (($this->_Page_Index==1 || $this->_Page_Index == $this->_links_per_Page ) && $this->_CURRENT_Page_Index !=1)
					  $this->_Page_Index = 1;
				  else if(($this->_Page_Index > 1 && $this->_Page_Index < $this->_links_per_Page ))
				  	$this->_CURRENT_Page_Index = (($this->_CURRENT_Page_Index - $this->_Page_Index) + 1) ; 
				
					//Loop up to the Allowable links on per page.	
					for($inc = 1 ; $inc <= $this->_links_per_Page; $inc++)
					{
						if($this->_CURRENT_Page_Index <= $this->_PageCount)	
						{
							if($_GET['currentPage'] == $this->_CURRENT_Page_Index)
								$Navigable_Links .= "<li><a class=\"active\" href=\"#\">". $_GET['currentPage'] ."</a></li>&nbsp;";
	
							 if($_GET['currentPage'] <> $this->_CURRENT_Page_Index)	
								$Navigable_Links .= "<li><a href=\"". $this->_PageName ."?pgIndex=". $inc ."&currentPage=". $this->_CURRENT_Page_Index . $this->_Qry_PARAMS ."\" ". $this->_LinksClass .">". $this->_CURRENT_Page_Index ."</a></li>&nbsp;";

							$this->_CURRENT_Page_Index ++;
						}				
					}# End of for Loop
			}# End of pageCount Check If
				
			//Return the links
			return $Navigable_Links;
		}# End of NavigationInnerLinks()

		//..........................................................................................................	
		//This function provides the Functionaliy of  Back Navigable link.
		// Takes no parameter , 
		// Returns A navigable Link
		
		//..........................................................................................................		
		function BackNavigationLink()
		{
			//====================================================================================
			//				Local Variables
			$Navigable_Links = "";
			$currentPage = "";
			$pgIndex = $this->_Page_Index;
			//====================================================================================
			
			//Set the Current Page which has been hit
			if(!empty($_GET['currentPage']) && isset($_GET['currentPage']))
				$currentPage = $_GET['currentPage'];
			else
				$currentPage = 1;
			if($this->_Page_Index > 1)
				$pgIndex = ($this->_Page_Index - 1);
			else if($this->_Page_Index == 1)	
				$pgIndex = ($this->_links_per_Page);
				
			//Make it sure that Current Page is Greater Than 1
			//Show Back Link
			if($currentPage > 1)
				$Navigable_Links = "<a href=\"". $this->_PageName ."?pgIndex=". $pgIndex  ."&currentPage=". ($currentPage - 1) . $this->_Qry_PARAMS ."\" ". $this->_LinksClass .">&laquo;&nbsp;Previous</a>&nbsp;";

			//Return the links
			return $Navigable_Links;
		}# End of BackNavigationLink()

		//..........................................................................................................	

		//This function provides the Functionaliy of Forward Navigable link.
		// Takes no parameter , 
		// Returns A navigable Link
		
		//..........................................................................................................		
		function ForwardNavigationLink()
		{
			//====================================================================================
			//				Local Variables
			$Navigable_Links = "";
			$currentPage = "";
			$pgIndex = $this->_Page_Index;
			//====================================================================================
			
			//Set the Current Page which has been hit
			if(!empty($_GET['currentPage']) && isset($_GET['currentPage']))
				$currentPage = $_GET['currentPage'];
			else
				$currentPage = 1;

			if($this->_Page_Index < $this->_links_per_Page)
				$pgIndex = ($this->_Page_Index + 1);
			//Make it sure that Current Page is Greater Than 1
			//Show Forward Link
			if($currentPage < $this->_PageCount)
				$Navigable_Links = "<a href=\"". $this->_PageName ."?pgIndex=". $pgIndex  ."&currentPage=". ($currentPage + 1) . $this->_Qry_PARAMS ."\" ". $this->_LinksClass .">Next&nbsp;&raquo;</a>";

			//Return the links
			return $Navigable_Links;
		}# End of ForwardNavigationLink()

		//..........................................................................................................	

		//This function provides the Functionaliy of Setting the Environment.
		// Takes three parameters , 
		// 1)  Name of all the Table of which you want to get total Records.
		// 2)  Condition if any 
		// 3)  Object of the Connection
		// Returns Nothing.

		//..........................................................................................................		
		function SetNavigationalLinks($_Table_Name , $Condition , $conObj)
		{
			//====================================================================================
			//				Global Variables
			//Get Total # of Recs.
			global $TotalRecs ;
			//Count Total # of Pages
			global $TotalPages ;
			# Shows Navigational Links
			global $NaviLinks;
			global $NaviLinksUser;
			global $NaviLinksMedia;
			global $BackNaviLinks ;		# Shows Back Navigational Links	
			global $ForwardNaviLinks;	# Shows Forward Navigational Links		
			//====================================================================================
			
			//Get Total # of Recs.
			$TotalRecs = $this->count_TotalRecs($_Table_Name , $Condition,$conObj);

			//Count Total # of Pages
			$TotalPages  =  $this->pageCount();
			# Shows Navigational Links
			$NaviLinks	=	$this->NavigationInnerLinks();
			$NaviLinksUser	=	$this->NavigationInnerLinksUser();
			$NaviLinksMedia	=	$this->NavigationInnerLinksMedia();
			$BackNaviLinks	= $this->BackNavigationLink();		# Shows Back Navigational Links	
			$ForwardNaviLinks= $this->ForwardNavigationLink();	# Shows Forward Navigational Links		
			
		}# End of SetNavigationalLinks()

		function SetNavigationalLinksNew($resultSet)
		{
			//====================================================================================
			//				Global Variables
			//Get Total # of Recs.
			global $TotalRecs ;
			//Count Total # of Pages
			global $TotalPages ;
			# Shows Navigational Links
			global $NaviLinks;
			global $NaviLinksUser;
			global $NaviLinksMedia;
			global $BackNaviLinks ;		# Shows Back Navigational Links	
			global $ForwardNaviLinks;	# Shows Forward Navigational Links		
			//====================================================================================
			
			//Get Total # of Recs.
			$TotalRecs = $this->count_TotalRecs($resultSet);

			//Count Total # of Pages
			$TotalPages  =  $this->pageCount();
			# Shows Navigational Links
			$NaviLinks	=	$this->NavigationInnerLinks();
			$NaviLinksUser	=	$this->NavigationInnerLinksUser();
			$NaviLinksMedia	=	$this->NavigationInnerLinksMedia();
		
			$BackNaviLinks	= $this->BackNavigationLink();		# Shows Back Navigational Links	
			$ForwardNaviLinks= $this->ForwardNavigationLink();	# Shows Forward Navigational Links		
			
		}# End of SetNavigationalLinks()

		//..........................................................................................................	


}#End of classPaging Class...

?>