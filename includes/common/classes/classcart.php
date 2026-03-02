<?PHP
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start session only if not already started
}

/*

..........................................................................................................



	..........................................................................................................

	

*/

	//--------------------------------------------------------------------------------

	//					<<<<Creation of the Class Admin class>>>>	

	error_reporting(15);

	require("classPrices.php");

	//--------------------------------------------------------------------------------

	//					<<<<Headers including area>>>>>

	//===================================================================================



	class classCart

	{

		//--------------------------------------------------------------------------------

								// Class Properties (Class Level Variables)

		//--------------------------------------------------------------------------------

			var $objDbConn		;

		//..........................................................................................................



		/*Constructor initialize all the variables*/

		

		function classCart($objDbConn)

		{

			$this->objDbConn	= 	$objDbConn; # Populate the Connection property with the Connection Class Object.

		}//

        /**
        * Deletes all cart items for a given user ID.
        */
        function clearCartByUser($userId)
        {
            $uid   = intval($userId);
            $sql   = "DELETE FROM temp_order WHERE UserID = {$uid}";
            mysql_query($sql) or die(mysql_error());
        }
        
        /**
         * Render the header cart box HTML with up-to-date totals.
         *
         * @return string HTML block for header-cart-box
        */
        function renderHeaderCartBox()
        {
            // Ensure session is started
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }
    
            // Determine if user is logged in
            $userId = isset($_SESSION['uid']) ? (int) $_SESSION['uid'] : 0;
            if ($userId > 0) {
                $where = "UserID = '{$userId}'";
            } else {
                $cartId = session_id();
                $where  = "UserID = 0 AND CartID = '{$cartId}'";
            }
    
            // Query all cart items for this user/session
            $sql = "SELECT Pid, TrackID, VendorID, PType, subscrip, Quantity
                      FROM temp_order
                     WHERE {$where}";
            $res = mysql_query($sql);
    
            $qtyTotal   = 0;
            $valueTotal = 0.0;
    
            while ($row = mysql_fetch_assoc($res)) {
                $qtyTotal += (int) $row['Quantity'];
                // lookup unit price using your existing logic
                $unitPrice = $this->getProductPrice(
                    $row['Pid'],
                    $row['PType'],
                    $row['subscrip']
                );
                $valueTotal += $unitPrice * $row['Quantity'];
            }
    
            // Format values
            $count  = $qtyTotal;
            $amount = number_format($valueTotal);
            // $amount = number_format($valueTotal, 2, '.', '');
    
            // Optionally update session values
            $_SESSION['totalprod']   = $count;
            $_SESSION['totalricecc'] = $amount;
    
            // Build HTML
            $html  = '<div class="header-cart-box">';
            $html .=   '<a href="cart.php">';
            $html .=     '<div class="header-cart">';
            $html .=       '<span>(' . $count . ')</span> Item $' . $amount;
            $html .=     '</div>';
            $html .=   '</a>';
            $html .=   '<a class="cart-icon-box" href="cart.php">';
            $html .=     '<img src="images/new-image/cart_icons.svg" alt="cart"/>';
            $html .=   '</a>';
            $html .= '</div>';
    
            return $html;
        }
    
        /**
         * Example stub for getProductPrice. Replace with your actual logic.
         */
        protected function getProductPrice($pid, $ptype, $subscrip)
        {
            // TODO: pull correct pricing logic from your existing code
            // e.g. new classPrices()->getProductPrice($pid, $ptype, $subscrip);
            $objPrices = new classPrices();
            return $objPrices->getProductPrice($pid, $ptype, $subscrip);
        }
        
		///.........................................................................................................

function showd_cart_left($arr,$base_path,$sesid)

	{

			$totalrice	=	'';

			$Gross_one	=	'';

			$Gross_cart	=	'';

			$count		= 	mysql_num_rows($arr);	

	        $counter=0;

			$show_cart	=	'';

			if($sesid==''){

			$sesid	=	'0';

			}

		

		$show_cart	.='<table width="217" border="0">';

			while($row_cart	=	mysql_fetch_array($arr))

			{

			

			$cart_pids = $row_cart['Pid'];

			$cart_tids = $row_cart['TrackID'];

			$cart_vids = $row_cart['VendorID'];

						

		if($cart_pids!='' && $cart_pids!='0'){

	 	

		$q_pcode		=	"select * from tbl_exam where exam_id='".$cart_pids."'";

		$rs_pcode		=	mysql_query($q_pcode);

				

		$rowPro	=	mysql_fetch_array($rs_pcode);

		$cart_pcod		=	$rowPro['exam_name'];

		$url	=	str_replace(' ','-',$rowPro['exam_name']);		

		$show_cart		.='<tr><td width="162"><a href="'.strtolower($url).'.html" title="'.$rowPro['exam_name'].'" style="color:#C3C3C3;">'.$cart_pcod.'</a></td>';

		

		$cartPcode	=	$rowPro['exam_name'];

		$CartProId	= 	$rowPro['exam_id'];

		$proType 	=   $rowPro['exam_name'];

		

		if($row_cart['subscrip']=='3'){	$price	=	 $rowPro['exam_pri3'];	}

		if($row_cart['subscrip']=='6'){	$price	=	 $rowPro['exam_pri6'];	}

		if($row_cart['subscrip']=='12'){ $price	=	 $rowPro['exam_pri12'];	}

		if($row_cart['subscrip']=='0'){ $price	=	 $rowPro['exam_pri0'];	}

		

		$add_cart = $row_cart['Quantity'] * $price;

			

		//$Gross_cart		= $add_cart-$Discount_cart;

		$Gross_cart	=	$add_cart;

		

		$show_cart	.='	

	    <td width="5" align="center">'.$row_cart['Quantity'].'</td>

	    <td width="20" >$'.$Gross_cart.'</td>

	    <td width="20" align="right"><a href="javascript:void(0);" onclick="return showallproafterleft('.$row_cart['ID'].','.$sesid.');" ><img  border="0"  src="'.$base_path.'images/images.jpeg" alt="Remove" /></a></td>

	   </tr>';

		

		

		} //else brace end		

			

			if($cart_tids!='' && $cart_tids!='0'){

	 	

		$q_pcode		=	"select * from tbl_cert where cert_id='".$cart_tids."'";

		$rs_pcode		=	mysql_query($q_pcode);

				

		$rowPro	=	mysql_fetch_array($rs_pcode);

		$cart_pcod		=	$rowPro['cert_name'];

		$url	=	str_replace(' ','-',$rowPro['cert_name']);		

		$show_cart		.='<tr><td width="162"><a href="certificate/'.$url.'" title="'.$rowPro['cert_name'].'" style="color:#C3C3C3;">'.$cart_pcod.'</a></td>';

		

		$cartPcode	=	$rowPro['cert_name'];

		$CartProId	= 	$rowPro['cert_id'];

		$proType 	=   $rowPro['cert_name'];

		

		if($row_cart['subscrip']=='3'){	$price	=	 $rowPro['cert_pri3'];	}

		if($row_cart['subscrip']=='6'){	$price	=	 $rowPro['cert_pri6'];	}

		if($row_cart['subscrip']=='12'){ $price	=	 $rowPro['cert_pri12'];	}

		

		$add_cart = $row_cart['Quantity'] * $price;

			

		//$Gross_cart		= $add_cart-$Discount_cart;

		$Gross_cart	=	$add_cart;

		

		$show_cart	.='	

	    <td width="5" align="center">'.$row_cart['Quantity'].'</td>

	    <td width="20" >$'.$Gross_cart.'</td>

	    <td width="20" align="right"><a href="javascript:void(0);" onclick="return showallproafterleft('.$row_cart['ID'].','.$sesid.');" ><img  border="0"  src="'.$base_path.'images/images.jpeg" alt="Remove" /></a></td>

	   </tr>';

		

		

		} //else brace end		

		

			if($cart_vids!='' && $cart_vids!='0'){

	 	

		$q_pcode		=	"select * from tbl_vendors where ven_id='".$cart_vids."'";

		$rs_pcode		=	mysql_query($q_pcode);

				

		$rowPro	=	mysql_fetch_array($rs_pcode);

		$cart_pcod		=	$rowPro['ven_name'];

		$url	=	str_replace(' ','-',$rowPro['ven_name']);		

		$show_cart		.='<tr><td width="162"><a href="vendor/'.$url.'" title="'.$rowPro['ven_name'].'" style="color:#C3C3C3;">'.$cart_pcod.'</a></td>';

		

		$cartPcode	=	$rowPro['ven_name'];

		$CartProId	= 	$rowPro['ven_id'];

		$proType 	=   $rowPro['ven_name'];

		

		if($row_cart['subscrip']=='3'){	$price	=	 $rowPro['ven_pri3'];	}

		if($row_cart['subscrip']=='6'){	$price	=	 $rowPro['ven_pri6'];	}

		if($row_cart['subscrip']=='12'){ $price	=	 $rowPro['ven_pri12'];	}

		

		$add_cart = $row_cart['Quantity'] * $price;

			

		//$Gross_cart		= $add_cart-$Discount_cart;

		$Gross_cart	=	$add_cart;

		

		$show_cart	.='	

	    <td width="5" align="center">'.$row_cart['Quantity'].'</td>

	    <td width="20" >$'.$Gross_cart.'</td>

	    <td width="20" align="right"><a href="javascript:void(0);" onclick="return showallproafterleft('.$row_cart['ID'].','.$sesid.');" ><img  border="0"  src="'.$base_path.'images/images.jpeg" alt="Remove" /></a></td>

	   </tr>';

		

		

		} //else brace end	

		

			if($cart_vids==0 && $cart_tids=='0' && $cart_pids=='0'){

	 	

						$q_pcode		=	"select * from website";

						$rs_pcode		=	mysql_query($q_pcode);

								

						$rowPro	=	mysql_fetch_array($rs_pcode);

							

						$show_cart		.='<tr><td width="162"><a href="subscription.html" title="Monthly Subscription" style="color:#C3C3C3;">All Exams</a></td>';

						

						$show_cart	.='	

						<td width="5" align="center">'.$row_cart['Quantity'].'</td>

						<td width="20" >$'.$rowPro['timming'].'</td>

						<td width="20" align="right"><a href="javascript:void(0);" onclick="return showallproafterleft('.$row_cart['ID'].','.$sesid.');" ><img  border="0"  src="'.$base_path.'images/images.jpeg" alt="Remove" /></a></td>

					   </tr>';

						$Gross_cart	=	$row_cart['Quantity']*$rowPro['timming'];

		

		} //else brace end			

				

				$totalrice	=	$Gross_cart+$totalrice;

			}

			

			$show_cart		.='

		<tr><td style="border-bottom: 1px dashed #333333;border-top: 1px dashed #333333;">&nbsp;</td><td style="border-bottom: 1px dashed #333333;border-top: 1px dashed #333333;"><strong style="color:#333333">Total</strong></td>

	    <td colspan="2" height="30" style="border-bottom: 1px dashed #333333;border-top: 1px dashed #333333;"><strong style="color:#333333">$'.$totalrice.'</strong></td>

	   </tr>';

				return $show_cart.'</table><div><table align="right" border="0"><tr><td><a href="'.$base_path.'cart.html" title="View Cart"><img src="'.$base_path.'images/view.jpg" border="0"></a>&nbsp;</td><td>&nbsp;<a  href="'.$base_path.'cart.html" title="Check Out" ><img src="'.$base_path.'images/checkout_right.jpg" border="0"></a></td></tr></table></div>';

}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////



function find_file_now($dirname, $fname, &$file_path) {

  $dir = opendir($dirname);

  while ($file = readdir($dir)) {

    if (empty($file_path) && $file != '.' && $file != '..') {

      if (is_dir($dirname.'/'.$file)) {

		  	  

		//$dirname = $dirname."/".$file;

		  		  

        $this->find_file_now($dirname.'/'.$file, $fname, $file_path);

		

      }

      else {

        if (file_exists($dirname.'/'.$fname)) {

          $file_path = $dirname.'/'.$fname;

		  //echo $file_path; die;

          return;

        }

      }

    }

  }



} // find_file

	function showd_cart($arr,$base_path,$sessss)

	{

	  		global $contactEmail;

			$priceitem	=	'';

			$totalrice	=	'';

			$show_cart	=	'';

			$fff	=	'';

			$fff2	=	'';

			$sp	=	'';

			$sp2	=	'';

			$sortingg = "order by pay_sort DESC";

			

			if($sessss!='' && $sessss!='0'){

			$query_cart	=	"select * from temp_order where UserID='".$sessss."' order by ID ASC";

			}else{

			$query_cart	=	"select * from temp_order where CartID='".session_id()."' and UserID='0' order by ID ASC";

			}

			//$query_cart	=	"select * from temp_order where CartID='".$arr."' ORDER BY ID";

			if($sessss==''){

			$sessss	=	'0';

			}

			$rs_cart	=	mysql_query($query_cart);

			$count		= 	mysql_num_rows($rs_cart);
			
            $_SESSION["totalprod"] = $count; //added to display in header cartby nexgeno

	        $counter=0;

			$show_cart	.='<ul class="cart-list cart-list-titles group">

                            <li class="item-image">Item</li>

                            <li class="item-title">Description</li>

                            <li class="item-action">Action</li>

                            <li class="item-total">Sub Total</li>

                            <li class="item-quantity">Quantity</li>

                            <li class="item-price">Price</li>

                        </ul>';

			

		



//			$getUserData = mysql_fetch_array(mysql_query("select * from tbl_user where user_id='".$_SESSION['uid']."'"));		

//			$nameparts = explode(" ",$getUserData["user_fname"]);



			

					



/*			if(isset($nameparts[1]) and $nameparts[1] != ''){

				$name2ndPart = $nameparts[1];

			}else{$name2ndPart = "";}



			if($getUserData["user_lname"] != ""){$lastName = $getUserData["user_lname"];}else{$lastName = "";}

			if($getUserData["user_country"] != ""){$userContry = $getUserData["user_country"];}else{$userContry = "";}

			if($getUserData["user_city"] != ""){$userCity = $getUserData["user_city"];}else{$userCity = "";}

			if($getUserData["user_address"] != ""){$userAddress = $getUserData["user_address"];}else{$userAddress = "";}

			if($getUserData["user_postcode"] != ""){$userPostc = $getUserData["user_postcode"];}else{$userPostc = "";} */

			

			global $price_both, $price_sp;

			while($row_cart	=	mysql_fetch_array($rs_cart)){

                  	$cart_pids = $row_cart['Pid'];

                    $cart_tids = $row_cart['TrackID'];

                    $cart_vids = $row_cart['VendorID'];

                   

                $show_cart	.= '<input type="hidden" name="txt_id[]" id="txt_id[]" value = '.$row_cart['ID'].' />';

				

                    if($cart_pids!='' && $cart_pids!='0'){

                    

                    $q_pcode		=	"select * from tbl_exam where exam_id='".$cart_pids."'";

                    $rs_pcode		=	mysql_query($q_pcode);

                            

                    $rowPro	=	mysql_fetch_array($rs_pcode);



						$myexamId = $cart_pids;

						$myexamtype = $row_cart['PType'];

						$durationn = $row_cart['subscrip'];

					

					$objPricess = new classPrices();



                    //$cart_pcod		=	$rowPro['exam_name']; //commented by nexgeno dev
                    // $cart_pcod		= ($row_cart['PType']=='7' || $row_cart['PType']=='8') || $row_cart['PType']=='9') ? $rowPro['exam_name'] :	$rowPro['exam_fullname'].' '.$rowPro['exam_name']; //added by nexgeno dev
                         $cart_pcod = ($row_cart['PType'] == '7' || $row_cart['PType'] == '8' || $row_cart['PType'] == '9') ? $rowPro['exam_name'] : $rowPro['exam_fullname'].' '.$rowPro['exam_name']; //added by nexgeno dev

                    $url	=	str_replace(' ','-',$rowPro['exam_name']);

					$cartPcode	=	$rowPro['exam_name'];

                    $CartProId	= 	$rowPro['exam_id'];

                    $proType 	=   $rowPro['exam_name'];

					$append = " (Secure PDC)";

                    

                    $price = $objPricess->getProductPrice($myexamId,$myexamtype,$durationn);	

					if($row_cart['PType']=='both'){ $append = " (Secure PDC + Testing Engine)";	}

                    if($row_cart['PType']=='sp'){ $append = " (Testing Engine only)";	}
					if($row_cart['PType']=='7'){ $append = " (Real Lab Workbook)";	}
					if($row_cart['PType']=='7'){ $append = " (Real labs Workbook + Racks)";	}
					if($row_cart['PType']=='8'){ $append = " (Real labs Workbook + Racks)";	}
					if($row_cart['PType']=='9'){ $append = " (Real Labs Workbook + Racks + Bootcamp)";	}



                    $add_cart = $row_cart['Quantity'] * $price;

                   $Gross_cart	=	$add_cart;

					$show_cart	.= '<li>

                                <ul class="cart-list item-box group">

                                    <li class="item-image"><img src="images/product-img02.png" alt=""></li>

                                    <li class="item-title">'.$cart_pcod.' '.$append.'</li>

                                    <li class="item-action"><a href="javascript:void(0);" onclick="return showallpro('.$row_cart['ID'].','.$sessss.');" >&nbsp;</a></li>

                                    <li class="item-total">$'.$price.'</li>

                                    <li class="item-quantity"><input type="text" name="txt[]" id="txt[]" maxlength="2" value="'.$row_cart['Quantity'].'" class="input-quantity"><a href="#">update</a></li>

                                    <li class="item-price">$'.$add_cart.'</li>

                                </ul>

                   </li>';

		

				} //else brace end	

                    

                    if($cart_tids!='' && $cart_tids!='0'){

						$q_pcode		=	"select * from tbl_cert where cert_id='".$cart_tids."'";

						$rs_pcode		=	mysql_query($q_pcode);

						$rowPro	=	mysql_fetch_array($rs_pcode);

						$cart_pcod		=	$rowPro['cert_name'];

						$url	=	str_replace(' ','-',$rowPro['cert_name']);

			$cartPcode	=	$rowPro['cert_name'];

			$CartProId	= 	$rowPro['cert_id'];

			$proType 	=   $rowPro['cert_name'];

		

			if($row_cart['subscrip']=='3'){	$price	=	 $rowPro['cert_pri3'];	}

			if($row_cart['subscrip']=='6'){	$price	=	 $rowPro['cert_pri6'];	}

			if($row_cart['subscrip']=='12'){ $price	=	 $rowPro['cert_pri12'];	}

		

			$add_cart = $row_cart['Quantity'] * $price;

			

			$Gross_cart	=	$add_cart;

					$show_cart	.= '<tr>

                                        <td align="left" bgcolor="#FFFFFF" class="text" style="padding-left:5px;"><strong>'.$cart_pcod.'</td>

                                        <td align="center" bgcolor="#FFFFFF" class="text"><span class="vandorpopnav style4">$'.$price.'</span></td>

                                        <td align="center" bgcolor="#FFFFFF" class="text"><input type="text" size="2" style="width:35px;border:1px solid #CCCCCC;"  name="txt[]" id="txt[]" maxlength="2" value="'.$row_cart['Quantity'].'" /></td>

                                        <td height="40" align="center" bgcolor="#FFFFFF" class="redbold">$'.$add_cart.'</td>

                                        <td align="center" bgcolor="#FFFFFF"><a href="javascript:void(0);" onclick="return showallpro('.$row_cart['ID'].','.$sessss.');" ><img src="'.$base_path.'images/shopping_icon.jpg" width="30" height="35" border="0" /></td>

                                      </tr>';

				}

				

				

				if($cart_vids!='' && $cart_vids!='0'){

				

				$q_pcode		=	"select * from tbl_vendors where ven_id='".$cart_vids."'";

				$rs_pcode		=	mysql_query($q_pcode);

				$rowPro	=	mysql_fetch_array($rs_pcode);

				$cart_pcod		=	$rowPro['ven_name'];

				$url	=	str_replace(' ','-',$rowPro['ven_name']);

				$cartPcode	=	$rowPro['ven_name'];

				$CartProId	= 	$rowPro['ven_id'];

				$proType 	=   $rowPro['ven_name'];

				

				if($row_cart['subscrip']=='3'){	$price	=	 $rowPro['ven_pri3'];	}

				if($row_cart['subscrip']=='6'){	$price	=	 $rowPro['ven_pri6'];	}

				if($row_cart['subscrip']=='12'){ $price	=	 $rowPro['ven_pri12'];	}

				

				$add_cart = $row_cart['Quantity'] * $price;

				

				$Gross_cart	=	$add_cart;

				

				

					$show_cart	.= '<li>

                                <ul class="cart-list item-box group">

                                    <li class="item-image"><img src="images/product-img02.png" alt=""></li>

                                    <li class="item-title">'.$cart_pcod.'</li>

                                    <li class="item-action"><a href="javascript:void(0);" onclick="return showallpro('.$row_cart['ID'].','.$sessss.');" >&nbsp;</a></li>

                                    <li class="item-total">$'.$price.'</li>

                                    <li class="item-quantity"><input type="text" name="txt[]" id="txt[]" maxlength="2" value="'.$row_cart['Quantity'].'" class="input-quantity"><a href="#">update</a></li>

                                    <li class="item-price">$'.$add_cart.'</li>

                                </ul>

                   </li>';

				}

				

				if($cart_vids=='0' && $cart_tids=='0' && $cart_vids=='0'){

				

				$q_pcode		=	"select * from website";

				$rs_pcode		=	mysql_query($q_pcode);

				$rowPro	=	mysql_fetch_array($rs_pcode);

				if($row_cart['Jumbo'] == "1"){$dsr = "for 1 Month";}

				if($row_cart['Jumbo'] == "3"){$dsr = "for 3 Months";}

				if($row_cart['Jumbo'] == "6"){$dsr = "for 6 Months";}

				if($row_cart['Jumbo'] == "12"){$dsr = "for 12 Months";}

				else{}

						

				$Gross_cart	=	$row_cart['Quantity']*$row_cart['Price'];

					$show_cart	.= '

					<li>

                                <ul class="cart-list item-box group">

                                    <li class="item-image"><img src="images/product-img02.png" alt=""></li>

                                    <li class="item-title">Unlimited Access Package '.$dsr.'<br /><a href="vendors.html" style="color: rgb(0, 0, 0);">View All 3000+ Exams</a></li>

                                    <li class="item-action"><a href="javascript:void(0);" onclick="return showallpro('.$row_cart['ID'].','.$sessss.');" >&nbsp;</a></li>

                                    <li class="item-total">$'.$row_cart['Price'].'</li>

                                    <li class="item-quantity"><input type="text" name="txt[]" id="txt[]" maxlength="2" value="'.$row_cart['Quantity'].'" class="input-quantity"><a href="#">update</a></li>

                                    <li class="item-price">$'.$row_cart['Price'].'</li>

                                </ul>

                   </li>';

				}

				

				

				 $totalrice	=	$Gross_cart+$totalrice;
				 
				 $_SESSION["totalricecc"] = $totalrice; //added to display in header cartby nexgeno

$counter ++;



	}			

			if(!empty($_REQUEST['pagepart']) && $_REQUEST['pagepart'] == "ajaxpage") { 

			 $show_cart	.= '

				<div class="cart-total">

                            <div class="group">* - In order to avoid duplicate orders only 1 copy of each product can be purchased.<br />

                              If you are interested in multiple copies please contact '.$contactEmail.' </div>';

				//$show_cart	.= '<div class="group">Select Payment Method: <input id="payoption"  name="payoption" checked=checked type="radio" value="1"> Credit / Debit Card &nbsp;&nbsp;&nbsp;&nbsp;</div>';

				// $show_cart	.= '<div class="group">Select Payment Method: <input id="payoption" name="payoption" type="radio" value="2" checked="checked"> PAYPAL <a class="mrgleft20" target="_blank" href="https://ccierack.rentals/payment-modes/"><input type="radio" style="pointer-events:none;"><img class="razorpay_biutton" src="'.BASE_URL.'images/razorpay-logo.svg"></a></div>';
				$show_cart	.= '<div class="group">Select Payment Method: <input id="payoption" name="payoption" type="radio" value="2" checked="checked"> PAYPAL </div>';

$show_cart	.= '<div>The total Price inclusive of applicable taxes is being displayed before the order is transmitted.</div>

                        </div>

<div class="price-and-buy cart-buttons group">                

	<a href="javascript:void(0)" onClick="javascript:pageChecktout();">

	<span class="black-button" style="padding-top:10px;">Confirm Order & Pay</span>

	</a>

    <button class="black-button">Continue Shopping</button>

</div>';

			} 

			else 

			{

			

				$show_cart	.= '

				<div class="cart-total">

                            <div class="group">* - In order to avoid duplicate orders only 1 copy of each product can be purchased.<br />

                              If you are interested in multiple copies please contact '.$contactEmail.' </div>';

				//$show_cart	.= '<div class="group">Select Payment Method: <input id="payoption"  name="payoption" checked=checked type="radio" value="1"> Credit / Debit Card &nbsp;&nbsp;&nbsp;&nbsp;</div>';

				// $show_cart	.= '<div class="group">Select Payment Method: <input id="payoption" name="payoption" type="radio" value="2" checked="checked"> PAYPAL <a class="mrgleft20" target="_blank" href="https://ccierack.rentals/payment-modes/"><input type="radio" style="pointer-events:none;"><img class="razorpay_biutton" src="'.BASE_URL.'images/razorpay-logo.svg"></a></div> ';
				$show_cart	.= '<div class="group">Select Payment Method: <input id="payoption" name="payoption" type="radio" value="2" checked="checked"> PAYPAL </div> ';

$show_cart	.= '<div>The total Price inclusive of applicable taxes is being displayed before the order is transmitted.</div>

                        </div>

<div class="price-and-buy cart-buttons group">                

	<a href="javascript:void(0)" onClick="javascript:pageChecktout();">

	<span class="black-button" style="padding-top:10px;">Confirm Order & Pay</span>

	</a>

    <button class="black-button">Continue Shopping</button>

</div>';



			 }

	

				return $show_cart.'<input name="counter" type="hidden" value="'.$counter.'" /> </table>';

	}

	

	////////////////////////////////////////////////////////////////////////////////////////////////////

				function cart_manage($arr)

				{
				    
                unset($_SESSION["totalprod"]); //unset when no data in cart - added by nexgeno
                unset($_SESSION["totalricecc"]); //unset when no data in cart - added by nexgeno			    

				$cart_showed	=	'';

				$query			=	"select * from temp_order where CartID='".$arr."' order by ID ASC";

				$rs				=	mysql_query($query);

				$num			=	mysql_num_rows($rs);

				$cart_showed 	.= "<table><tr><td colspan=2 class='redbold'>Your Shopping Cart is Empty</td></tr></table>";

				return $cart_showed;

				}

	//---------------------------------------------------------------------------------------//

	function Checkout($cart_Id,$base_path, $discount=0)

		{

			$priceitem	=	'';

			$show_cart	=	'';			

			 $totalrice	=	'';

			if(isset($_SESSION['uid'])){

			$query_cart		=	"select * from temp_order where UserID='".$_SESSION['uid']."' order by ID ASC";

			}else{

			$query_cart		=	"select * from temp_order where CartID='".session_id()."' and UserID='0' order by ID ASC";

			}

			//$query_cart		=	"select * from temp_order where CartID='".$cart_Id."' order by ID ASC";

			

			$rs_cart		=	mysql_query($query_cart);

			$num			=	mysql_num_rows($rs_cart);

			

			$totalpriceall	=	'';

		global $price_both, $price_sp;

		$ord_str = "";

		$cart_qtyb = 0;

		$cart_qtys = 0;

		$cart_qty1 = 0;

		$cart_qty2 = 0;

		$cart_qty3 = 0;

		$cart_qty4 = 0;

		while($row_cart	=	mysql_fetch_array($rs_cart)){

                  	$cart_pids = $row_cart['Pid'];

                    $cart_tids = $row_cart['TrackID'];

                    $cart_vids = $row_cart['VendorID'];

                   

                    if($cart_pids!='' && $cart_pids!='0'){

                    

                    $q_pcode		=	"select * from tbl_exam where exam_id='".$cart_pids."'";

                    $rs_pcode		=	mysql_query($q_pcode);

                            

                    $rowPro	=	mysql_fetch_array($rs_pcode);

                    $cart_pcod		=	$rowPro['exam_name'];

                    $url	=	str_replace(' ','-',$rowPro['exam_name']);

					$append = " (Secure PDC)";

			 

			//$show_cart	.= '<td align="center" class="allvendor" style="border-left:solid #d1d1d1 1px; border-right:solid #d1d1d1 1px">&nbsp;&nbsp;<input type="text" name="txt[]" id="txt[]" size="2" maxlength="2" class="qty1" value="'.$row_cart['Quantity'].'" style=" width:20px;"/></td><td align="center" valign="top" ><a href=html" title="<'.$rowPro['exam_name']. class="allvendor" >'.$cart_pcod.'</a></td>';

			

					$cartPcode	=	$rowPro['exam_name'];

                    $CartProId	= 	$rowPro['exam_id'];

                    $proType 	=   $rowPro['exam_name'];

                    

                    $myexamId = $cart_pids;

					$myexamtype = $row_cart['PType'];

					$durationn = $row_cart['subscrip'];



					$objPricess = new classPrices();

                    

                    $price = $objPricess->getProductPrice($myexamId,$myexamtype,$durationn);	

                    if($row_cart['PType']=='both'){  $append = " (Secure PDC + Testing Engine)";	} //tttt

                    if($row_cart['PType']=='sp'){ $append = " (Testing Engine only)";	} //tttt

                   

					$ord_str .= ",".$cart_pcod.$append;

                    $add_cart = $row_cart['Quantity'] * $price;

                        

                   $Gross_cart	=	$add_cart;

		//	$show_cart	.= ' <td align="right" valign="top" class="allvendor" style="border-left:solid #d1d1d1 1px; border-right:solid #d1d1d1 1px;">$ '.$price.'&nbsp;</td>      <td align="right" valign="top" class="allvendor">$ '.$add_cart.'&nbsp;</td><td align="center" valign="middle" class="leftcate" style="border-left:solid #d1d1d1 1px; border-right:solid #d1d1d1 1px"><a href="javascript:void(0);" onclick="return showallpro('.$row_cart['ID'].','.$sessss.');" ><img  border="0"  src="'.$base_path.'images/images.jpeg" alt="Remove" /></a></td></tr>	';

		

				} //else brace end	

                    

                    if($cart_tids!='' && $cart_tids!='0'){

						$q_pcode		=	"select * from tbl_cert where cert_id='".$cart_tids."'";

						$rs_pcode		=	mysql_query($q_pcode);

						$rowPro	=	mysql_fetch_array($rs_pcode);

						$cart_pcod		=	$rowPro['cert_name'];

						$url	=	str_replace(' ','-',$rowPro['cert_name']);

			//$show_cart	.=	'<tr> <td align="center" class="allvendor" style="border-left:solid #d1d1d1 1px; border-right:solid #d1d1d1 1px">&nbsp;&nbsp;<input type="text" name="txt[]" id="txt[]" size="2" maxlength="2" class="qty1" value="'.$row_cart['Quantity'].'" style=" width:20px;"/></td>   <td align="center" valign="top" ><a href="'.$url.'-certification-training.html" title="'.$rowPro['cert_name'].'" class="allvendor" >'.$cart_pcod.'</a></td>';

			$cartPcode	=	$rowPro['cert_name'];

			$CartProId	= 	$rowPro['cert_id'];

			$proType 	=   $rowPro['cert_name'];

		

			if($row_cart['subscrip']=='3'){	$price	=	 $rowPro['cert_pri3'];	}

			if($row_cart['subscrip']=='6'){	$price	=	 $rowPro['cert_pri6'];	}

			if($row_cart['subscrip']=='12'){ $price	=	 $rowPro['cert_pri12'];	}

		$ord_str .= ",".$cart_pcod;

			$add_cart = $row_cart['Quantity'] * $price;

			

			$Gross_cart	=	$add_cart;

			//$show_cart	.=	'<td align="right" valign="top" class="allvendor" style="border-left:solid #d1d1d1 1px; border-right:solid #d1d1d1 1px;">$ '.$price.'&nbsp;</td>              <td align="right" valign="top" class="allvendor">$ '.$add_cart.'&nbsp;</td>            <td align="center" valign="middle" class="leftcate" style="border-left:solid #d1d1d1 1px; border-right:solid #d1d1d1 1px"><a href="javascript:void(0);" onclick="return showallpro('.$row_cart['ID'].','.$sessss.');" ><img  border="0"  src="'.$base_path.'images/images.jpeg" alt="Remove" /></a></td></tr>';

				}

				

				

				if($cart_vids!='' && $cart_vids!='0'){

				

				$q_pcode		=	"select * from tbl_vendors where ven_id='".$cart_vids."'";

				$rs_pcode		=	mysql_query($q_pcode);

				$rowPro	=	mysql_fetch_array($rs_pcode);

				$cart_pcod		=	$rowPro['ven_name'];

				$url	=	str_replace(' ','-',$rowPro['ven_name']);

				

			// $show_cart	.=	'<tr> <td align="center" class="allvendor" style="border-left:solid #d1d1d1 1px; border-right:solid #d1d1d1 1px">&nbsp;&nbsp;	<input type="text" name="txt[]" id="txt[]" size="2" maxlength="2" class="qty1" value="'.$row_cart['Quantity'].'" style=" width:20px;"/></td> <td align="center" valign="top" ><a href="$url-certification-training.html" title="'.$rowPro['ven_name'].'" class="allvendor" >'.$cart_pcod.'</a></td>';

			

				$cartPcode	=	$rowPro['ven_name'];

				$CartProId	= 	$rowPro['ven_id'];

				$proType 	=   $rowPro['ven_name'];

				

				if($row_cart['subscrip']=='3'){	$price	=	 $rowPro['ven_pri3'];	}

				if($row_cart['subscrip']=='6'){	$price	=	 $rowPro['ven_pri6'];	}

				if($row_cart['subscrip']=='12'){ $price	=	 $rowPro['ven_pri12'];	}

				$ord_str .= ",".$cart_pcod;

				$cart_qty3 = $cart_qty3+$row_cart['Quantity'];

				$add_cart = $row_cart['Quantity'] * $price;

				

				$Gross_cart	=	$add_cart;

				

				//$show_cart	.= '<td align="right" valign="top" class="allvendor" style="border-left:solid #d1d1d1 1px; border-right:solid #d1d1d1 1px;">$ '.$price.'&nbsp;</td>     <td align="right" valign="top" class="allvendor">$ '.$add_cart.'&nbsp;</td> <td align="center" valign="middle" class="leftcate" style="border-left:solid #d1d1d1 1px; border-right:solid #d1d1d1 1px"><a href="javascript:void(0);" onclick="return showallpro('.$row_cart['ID'].','.$sessss.');" ><img  border="0"  src="'.$base_path.'images/images.jpeg" alt="Remove" /></a></td></tr>';

				}

				if($cart_vids=='0' && $cart_tids=='0' && $cart_pids=='0'){

				

				$q_pcode		=	"select * from website";

				$rs_pcode		=	mysql_query($q_pcode);

				$rowPro	=	mysql_fetch_array($rs_pcode);

				

				$add_cart = $row_cart['Quantity'] * $row_cart['Price'];

				$ord_str .= ",Unlimited";

				$cart_qty4 = $cart_qty4+$row_cart['Quantity'];

				

				$Gross_cart	=	$add_cart;

				

				

				}

				

				 $totalrice	=	$Gross_cart+$totalrice;



	}



			



			$discount_desc = '';

			if($discount!=0)

			{

				$disc_type = $discount['coupon_type'];

				$disc_value = $discount['coupon_value'];

				if($disc_type=='2')

				{

					$disc_value = $totalrice * ($discount['coupon_value']/100);

				}

				$discounted_price = $totalrice - $disc_value;

				if($discounted_price != $totalrice && $discounted_price > 0)

				{

					$discount_desc = '<tr><td align="right" style="background-color: #ccc;padding: 6px;font-size: 17px;"><strong>Total Discount :&nbsp;&nbsp;</strong></td><td style="background-color: #fff;padding: 6px;font-size: 17px;"><strong>$ '.$disc_value.'</strong></td></tr><tr><td align="right" style="background-color: #ccc;padding: 6px;font-size: 17px;"><strong>Total Payable :&nbsp;&nbsp;</strong></td><td style="background-color: #fff;padding: 6px;font-size: 17px;"><strong>$ '.$discounted_price.'</strong></td></tr>';

				}

			}

		$show_cart	.="<table width='350' border='0' align='centre' cellpadding='5' cellspacing='5' >

		<tr>

		<td align='right' style='background-color: #ccc;padding: 6px;font-size: 17px;'><strong>Total Price:&nbsp;&nbsp;</strong></td>";

		

		$show_cart	.= '<td  width="50%" style="background-color: #fff;padding: 6px;font-size: 17px;"><strong>$ '.$totalrice.'</strong></td></tr>'.$discount_desc.'<tr>

		

		<td align="left" valign="top" class="allvendor" colspan="2" style="text-align:center" >

		<br /><input name="Final2" type="submit" class="orange-button" id="Final2" value="Confirm Order" />

	<input name="Final" type="hidden" id="Final" value="Final" /></td>   </tr>';

			

	$show_cart .= '';
	
//$_SESSION["totalprod"] = $count;
//$_SESSION["totalricecc"] = $totalrice;

			return $show_cart.'</table>';



		}

		

	function Checkout22($cart_Id,$base_path)

		{

			$priceitem	=	'';

			$show_cart	=	'';			

			 $totalrice	=	'';

			if(isset($_SESSION['uid'])){

			$query_cart		=	"select * from temp_order where UserID='".$_SESSION['uid']."' order by ID ASC";

			}else{

			$query_cart		=	"select * from temp_order where CartID='".session_id()."' and UserID='0' order by ID ASC";

			}

			//$query_cart		=	"select * from temp_order where CartID='".$cart_Id."' order by ID ASC";

			

			$rs_cart		=	mysql_query($query_cart);

			$num			=	mysql_num_rows($rs_cart);

			

			$totalpriceall	=	'';

			$show_cart	.="<table width='600' border='0' align='centre' cellpadding='5' cellspacing='5' ><tr>

			<td style='' align='right'><strong>Total Price:&nbsp;&nbsp;</strong></td>";

		

		$ord_str = "";

		$cart_qtyb = 0;

		$cart_qtys = 0;

		$cart_qty1 = 0;

		$cart_qty2 = 0;

		$cart_qty3 = 0;

		$cart_qty4 = 0;

		global $price_both, $price_sp;

		while($row_cart	=	mysql_fetch_array($rs_cart)){

                  	$cart_pids = $row_cart['Pid'];

                    $cart_tids = $row_cart['TrackID'];

                    $cart_vids = $row_cart['VendorID'];

                   

                    if($cart_pids!='' && $cart_pids!='0'){

                    

                    $q_pcode		=	"select * from tbl_exam where exam_id='".$cart_pids."'";

                    $rs_pcode		=	mysql_query($q_pcode);

                            

                    $rowPro	=	mysql_fetch_array($rs_pcode);

                    $cart_pcod		=	$rowPro['exam_name'];

                    $url	=	str_replace(' ','-',$rowPro['exam_name']);

					$append = " (Secure PDC)";

			//$show_cart	.= '<td align="center" class="allvendor" style="border-left:solid #d1d1d1 1px; border-right:solid #d1d1d1 1px">&nbsp;&nbsp;<input type="text" name="txt[]" id="txt[]" size="2" maxlength="2" class="qty1" value="'.$row_cart['Quantity'].'" style=" width:20px;"/></td><td align="center" valign="top" ><a href=html" title="<'.$rowPro['exam_name']. class="allvendor" >'.$cart_pcod.'</a></td>';

			

					$cartPcode	=	$rowPro['exam_name'];

                    $CartProId	= 	$rowPro['exam_id'];

                    $proType 	=   $rowPro['exam_name'];

                    

                    $myexamId = $cart_pids;

					$myexamtype = $row_cart['PType'];

					$durationn = $row_cart['subscrip'];





					$objPricess = new classPrices();

                    

                   	$price = $objPricess->getProductPrice($myexamId,$myexamtype,$durationn); 

					$scode = $objPricess->getProductSwreg($myexamId,$myexamtype,$durationn);	

					

                    if($row_cart['PType']=='both'){ $append = " (Secure PDC + Testing Engine)"; $cart_qtyb += $row_cart['Quantity'];	}

					else if($row_cart['PType']=='sp'){ $append = " (Testing Engine only)"; $cart_qtys += $row_cart['Quantity'];	}

					else{ $cart_qty1 = $cart_qty1+$row_cart['Quantity']; }

                    

					$ord_str .= ",".$cart_pcod.$append;

                    $add_cart = $row_cart['Quantity'] * $price;

                   $Gross_cart	=	$add_cart;

		//	$show_cart	.= ' <td align="right" valign="top" class="allvendor" style="border-left:solid #d1d1d1 1px; border-right:solid #d1d1d1 1px;">$ '.$price.'&nbsp;</td>      <td align="right" valign="top" class="allvendor">$ '.$add_cart.'&nbsp;</td><td align="center" valign="middle" class="leftcate" style="border-left:solid #d1d1d1 1px; border-right:solid #d1d1d1 1px"><a href="javascript:void(0);" onclick="return showallpro('.$row_cart['ID'].','.$sessss.');" ><img  border="0"  src="'.$base_path.'images/images.jpeg" alt="Remove" /></a></td></tr>	';

		

				} //else brace end	

                    

                    if($cart_tids!='' && $cart_tids!='0'){

						$q_pcode		=	"select * from tbl_cert where cert_id='".$cart_tids."'";

						$rs_pcode		=	mysql_query($q_pcode);

						$rowPro	=	mysql_fetch_array($rs_pcode);

						$cart_pcod		=	$rowPro['cert_name'];

						$url	=	str_replace(' ','-',$rowPro['cert_name']);

					$ord_str .= ",".$cart_pcod;

                    $cart_qty2 = $cart_qty2+$row_cart['Quantity'];

			//$show_cart	.=	'<tr> <td align="center" class="allvendor" style="border-left:solid #d1d1d1 1px; border-right:solid #d1d1d1 1px">&nbsp;&nbsp;<input type="text" name="txt[]" id="txt[]" size="2" maxlength="2" class="qty1" value="'.$row_cart['Quantity'].'" style=" width:20px;"/></td>   <td align="center" valign="top" ><a href="'.$url.'-certification-training.html" title="'.$rowPro['cert_name'].'" class="allvendor" >'.$cart_pcod.'</a></td>';

			$cartPcode	=	$rowPro['cert_name'];

			$CartProId	= 	$rowPro['cert_id'];

			$proType 	=   $rowPro['cert_name'];

		

			if($row_cart['subscrip']=='3'){	$price	=	 $rowPro['cert_pri3'];	}

			if($row_cart['subscrip']=='6'){	$price	=	 $rowPro['cert_pri6'];	}

			if($row_cart['subscrip']=='12'){ $price	=	 $rowPro['cert_pri12'];	}

		

			$add_cart = $row_cart['Quantity'] * $price;

			

			$Gross_cart	=	$add_cart;

			//$show_cart	.=	'<td align="right" valign="top" class="allvendor" style="border-left:solid #d1d1d1 1px; border-right:solid #d1d1d1 1px;">$ '.$price.'&nbsp;</td>              <td align="right" valign="top" class="allvendor">$ '.$add_cart.'&nbsp;</td>            <td align="center" valign="middle" class="leftcate" style="border-left:solid #d1d1d1 1px; border-right:solid #d1d1d1 1px"><a href="javascript:void(0);" onclick="return showallpro('.$row_cart['ID'].','.$sessss.');" ><img  border="0"  src="'.$base_path.'images/images.jpeg" alt="Remove" /></a></td></tr>';

				}

				

				

				if($cart_vids!='' && $cart_vids!='0'){

				

				$q_pcode		=	"select * from tbl_vendors where ven_id='".$cart_vids."'";

				$rs_pcode		=	mysql_query($q_pcode);

				$rowPro	=	mysql_fetch_array($rs_pcode);

				$cart_pcod		=	$rowPro['ven_name'];

				$url	=	str_replace(' ','-',$rowPro['ven_name']);

				$ord_str .= ",".$cart_pcod;

				$cart_qty3 = $cart_qty3+$row_cart['Quantity'];

				

			// $show_cart	.=	'<tr> <td align="center" class="allvendor" style="border-left:solid #d1d1d1 1px; border-right:solid #d1d1d1 1px">&nbsp;&nbsp;	<input type="text" name="txt[]" id="txt[]" size="2" maxlength="2" class="qty1" value="'.$row_cart['Quantity'].'" style=" width:20px;"/></td> <td align="center" valign="top" ><a href="$url-certification-training.html" title="'.$rowPro['ven_name'].'" class="allvendor" >'.$cart_pcod.'</a></td>';

			

				$cartPcode	=	$rowPro['ven_name'];

				$CartProId	= 	$rowPro['ven_id'];

				$proType 	=   $rowPro['ven_name'];

				

				if($row_cart['subscrip']=='3'){	$price	=	 $rowPro['ven_pri3'];	}

				if($row_cart['subscrip']=='6'){	$price	=	 $rowPro['ven_pri6'];	}

				if($row_cart['subscrip']=='12'){ $price	=	 $rowPro['ven_pri12'];	}

				

				$add_cart = $row_cart['Quantity'] * $price;

				

				$Gross_cart	=	$add_cart;

				

				//$show_cart	.= '<td align="right" valign="top" class="allvendor" style="border-left:solid #d1d1d1 1px; border-right:solid #d1d1d1 1px;">$ '.$price.'&nbsp;</td>     <td align="right" valign="top" class="allvendor">$ '.$add_cart.'&nbsp;</td> <td align="center" valign="middle" class="leftcate" style="border-left:solid #d1d1d1 1px; border-right:solid #d1d1d1 1px"><a href="javascript:void(0);" onclick="return showallpro('.$row_cart['ID'].','.$sessss.');" ><img  border="0"  src="'.$base_path.'images/images.jpeg" alt="Remove" /></a></td></tr>';

				}

				if($cart_vids=='0' && $cart_tids=='0' && $cart_pids=='0'){

				

				$q_pcode		=	"select * from website";

				$rs_pcode		=	mysql_query($q_pcode);

				$rowPro	=	mysql_fetch_array($rs_pcode);

				

				$add_cart = $row_cart['Quantity'] * $row_cart['Price'];

				$ord_str .= ",Unlimited";

				$cart_qty4 = $cart_qty4+$row_cart['Quantity'];

				

				$Gross_cart	=	$add_cart;

				

				}

				

				 $totalrice	=	$Gross_cart+$totalrice;



	}

	

$abc = mysql_query("select DISTINCT PType from temp_order where UserID='".$_SESSION['uid']."' order by ID ASC");

$typequan = mysql_num_rows($abc);







for($j=0;$j<mysql_num_rows($abc);$j++)

{

$typequanabc = mysql_fetch_object($abc);

$typb[$j] = $typequanabc->PType;

}





	

	global $swergID;

	$swergID = "122452";

	$ord_str = trim($ord_str,",");

	$products = $swergID."-";

	$quantity="";

	$num_quantity=0;

	$v="0";

	$d="0";

	$pri = "0";

	$quanty = '';

	$t="For ".$ord_str;

	



					$myexamId = $cart_pids;

					$myexamtype = $row_cart['PType'];

					$durationn = $row_cart['subscrip'];





					$objPricess = new classPrices();

	

function checkquan($tttp){

$bc = mysql_query("select * from temp_order where UserID='".$_SESSION['uid']."' and PType='$tttp' order by ID ASC");

$bb = mysql_fetch_object($bc);

$qqqbb = $bb->Quantity;

return mysql_num_rows($bc)+$qqqbb-1;

}



	for($z=0;$z<$typequan;$z++){

	///////////////////////////////////

		if($typb[$z]=="p"){

			$proCode = $objPricess->getProductSwreg($myexamId,'p','0'); 

			$pric = $objPricess->getProductPrice($myexamId,'p','0'); 

			$quantity = checkquan('p');

			$discrr = $cart_pcod." Secure PDC "; 

		}

		if($typb[$z]=="sp"){

			$proCode = $objPricess->getProductSwreg($myexamId,'sp','0'); 

			$pric = $objPricess->getProductPrice($myexamId,'sp','0'); 

			$quantity = checkquan('sp'); 

			$discrr = $cart_pcod." Testing Engine Only"; 

			}

		if($typb[$z]=="both"){

			$proCode = $objPricess->getProductSwreg($myexamId,'both','0'); 

			$pric = $objPricess->getProductPrice($myexamId,'both','0'); 

			$quantity = checkquan('both');

			$discrr = $cart_pcod." Secure PDC + Testing Engine"; 

			}

		if($typb[$z]=="p1"){

			$proCode = "140394-49";

			$qaq = checkquan('p1'); 

			$discrr = "Unlimited Secure PDC For 1 Month"; 

			$pric = $price_3allboth;

			}

		if($typb[$z]=="p3"){

			$proCode = "119611-666";

			$qaq = checkquan('p3'); 

			$discrr = "Unlimited Secure PDC For 3 Months"; 

			$pric = "149.97";

			}

		if($typb[$z]=="p6"){

			$proCode = "119611-667";

			$qaq = checkquan('p6'); 

			$discrr = "Unlimited Secure PDC For 6 Months"; 

			$pric = "239.94";

			}

		if($typb[$z]=="p12"){

			$proCode = "119611-668";

			$qaq = checkquan('p12'); 

			$discrr = "Unlimited Secure PDC For 12 Months"; 

			$pric = "359.88";

			}



		if($typb[$z]=="all6te"){$proCode = $swreg_6allte; $qaq = checkquan('all6te'); $discrr = "Unlimited Testing Engine For 6 Months";$pric = $price_6allte;}

		if($typb[$z]=="all12te"){$proCode = $swreg_12allte; $qaq = checkquan('all12te'); $discrr = "Unlimited Testing Engine For 12 Months"; $pric = $price_12allte;}

		if($typb[$z]=="all3pdf"){$proCode = $swreg_3allpdf; $qaq = checkquan('all3pdf'); $discrr = "Unlimited Secure PDC For 3 Months"; $pric = $price_3allpdf;}

		if($typb[$z]=="all6pdf"){$proCode = $swreg_6allpdf; $qaq = checkquan('all6pdf'); $discrr = "Unlimited Secure PDC For 6 Months"; $pric = $price_6allpdf;}

		if($typb[$z]=="all12pdf"){$proCode = $swreg_12allpdf; $qaq = checkquan('all12pdf'); $discrr = "Unlimited Secure PDC For 12 Months"; $pric = $price_12allpdf;}

		

		

		$quantity = $quantity;

		$num_quantity = $num_quantity + $cart_qty1;



		if($z == "0"){

			$pri = $pric;	

			$prods = $proCode;	

			$quanty += $quantity;

		}

		else{

			$proCode.=":".$prods;

			$quanty.=":".$quantity;

			$v.=":0";

			$d.=":0";

			$pric.=":".$pri;

			$num_quantity = $num_quantity + $cart_qty2;

		}

	/////////////////////////////////

	}



		$show_cart	.= '<td style=""><strong>$ '.$totalrice.'</strong></td></tr><tr><td align="right"><input type="hidden" name="orderurl" value="https://www.swreg.org/cgi-bin/s.cgi?s=119611&p='.$prods.'&q='.$quantity.'&v=0&d=0&vp='.$pric.'&t='.$discrr.'" /><input name="Final2" type="image" src="'.$base_path.'images/confirm_order.jpg" id="Final2" value="Final" /></td></tr>';



	$show_cart .= '';

			return $show_cart.'</table>';



		}

	//////////////////////////////////////////////////////////////////////////////////////////////////////

	function getAllCurrentProducts($userId)

	{

		$sql = " select * from order_detail  where UserID = '".$userId."' and 	status='1' and (PTypeID='p' or PTypeID='' or PTypeID='both' or PTypeID='p1' or PTypeID='p3' or PTypeID='p6' or PTypeID='p12') ORDER BY  ExpireDate DESC ";

		$rs  = mysql_query($sql);

		return $rs; 

	}

	//--------------------------------------------show current products-------------------------

		function show_current_products($result)

		{

		$showTR	=	'';

		function generateRandomString($length = 10) {

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    $charactersLength = strlen($characters);

    $randomString = '';

    for ($i = 0; $i < $length; $i++) {

        $randomString .= $characters[rand(0, $charactersLength - 1)];

    }

    return $randomString;

}

		while($rows=mysql_fetch_array($result))

		{

		 

		  $Status = "";	

			

		  $userID  = $rows['UserID'];

		  $ExpireDate  = $rows['ExpireDate'];

		  

		  if($rows['VendorID']!='' && $rows['VendorID']!='0'){

		   $sql_pro = "select * from tbl_exam  where ven_id='".$rows['VendorID']."' ";

		   $exequery	=	mysql_query($sql_pro);

		  }

		  

		  if($rows['TrackID']!='' && $rows['TrackID']!='0'){

		   $sql_pro = "select * from tbl_exam  where cert_id='".$rows['TrackID']."' ";

		   $exequery	=	mysql_query($sql_pro);

		  }

		  

		  if($rows['ProductID']!='' && $rows['ProductID']!='0'){

		   $sql_pro = "select * from tbl_exam  where exam_id='".$rows['ProductID']."' ";

		   $exequery	=	mysql_query($sql_pro);

		  }

		  if($rows['ProductID']=='0' && $rows['TrackID']=='0' && $rows['VendorID']=='0'){  

		  $current = date('Y-m-d');

		  	  		   

		   if($current > $ExpireDate && $ExpireDate!='' && $ExpireDate!=0 )

		   {

			   

			  $Status = "Expire";

			  $newstatus	=	"<font style='color:red; font-weight:bold;'>Expire</font>";

			  $action_Link="<a href='subscription.html'>Re-Order </a>";

			  

		   }

		   else

		   {

			  

			   $Status = "Active";

			   $newstatus	=	"<font style='color:#006600; font-weight:bold;'>Active</font>";

			   $action_Link	=	'';

			   }  

	

				if($Status=="Active")

				{

				$showTR .= '<tr>

    <td colspan="4" width="100%"><div id="vendor_style" class="blink">';

						if(isset($_GET["view"]) and $_GET["view"] == "vednor"){

						$res1 = mysql_query("select * from tbl_exam where exam_status='1' and ven_id='".$_GET["idt"]."' and QA !='0'  order by exam_name ASC");

						$aa = "select * from tbl_vendors where ven_status='1' order by ven_name ASC";

                    	$aar = mysql_query($aa);

							for($j = 0;$j<mysql_num_rows($aar);$j++)

							{

								$vres = mysql_fetch_object($aar);

								$showTR .= "<li><a href='mydownloads.php?view=vednor&idt=$vres->ven_id'>$vres->ven_name</a></li>";

							}

						}

						else{

							$aa = "select * from tbl_vendors where ven_status='1' order by ven_name ASC";

                    		$aar = mysql_query($aa);

							$showTR .= '';

							for($j = 0;$j<mysql_num_rows($aar);$j++)

							{

								$vres = mysql_fetch_object($aar);

								$showTR	.= "<li><a href='mydownloads.php?view=vednor&idt=$vres->ven_id'>$vres->ven_name</a></li>";

							}

							$showTR .= '';

							$res1 = mysql_query("select * from tbl_exam where exam_status='1' and ven_id='1' order by exam_name ASC");

						}

				$showTR	.= '</div><td></tr>';

				while($getuser1	=	mysql_fetch_array($res1))

				{	

			

			$no_assigned=0;

			$filenamename	=	basename($getuser1['exam_full']);

			$file_path = '';

			

			if($filenamename=='')

			{

				$filenamename = $getuser1['exam_name'].".zip";

			}

			

			$jk  = find_file('devil/full/', $filenamename, $file_path);

			

				$strno1 = generateRandomString();

				$strno2 = generateRandomString();



				$SentNo1=$strno1."-".$rows['ID']."-".$getuser1['exam_id']."-".$strno2;

				$SentNo1=base64_encode($SentNo1);

			

				$download="<a href='download.php?x=".$SentNo1."' style='text-decoration:none;color:#F75703;'>Download</a>";

				$download1="<a href='download.php?x=".$SentNo1."' style='text-decoration:none;color:#F75703;'>Download</a>";

			

			

			

			

				$showTR	.='

				

				

			<table width="100%" style="border-bottom:dashed 1px #333333;margin-bottom:8px;padding-bottom:8px;" border="0" >

				<tr>

			<td width="100" nowrap="nowrap" style=" color:#333333;" width:><strong>Exam Code</strong></td>

			<td width="166">'.$getuser1['exam_name'].'</td>

			<td width="100" style=" color:#333333;"></td>

			<td width="166"></td>

		  </tr>

			 <td width="100" style=" color:#333333; " ><strong></strong></td>

			<td width="166">'.$download.'</td>

			<td width="100" style=" color:#333333;"  ><strong>Status</strong></td>

			<td width="166">'.$newstatus.'</td>



		  </tr>

		  <tr>

		   <td width="100" nowrap="nowrap" style=" color:#333333;" width:><strong></strong></td>

			<td width="166"></td>

		   

		   <td width="100" style=" color:#333333;" ><strong></strong></td>

			<td width="166">'.$action_Link.'</td>

		  </tr></table>	

				

		 ';

			

		}

		

				}

				elseif($Status=="Expire")

				{

					$showTR	.'NO Products available for download in your account.';

					

				}

				/*$showTR	.=' <table width="100%" style="border-bottom:dashed 1px #333333;margin-bottom:8px;padding-bottom:8px;" border="0" >

				<tr>

			<td width="100" nowrap="nowrap" style=" color:#333333;" width:><strong>Exam Code</strong></td>

			<td width="166">All Exams</td>

			<td width="100" style=" color:#333333;"><strong>Expires on</strong></td>

			<td width="166">'.$ExpireDate.'</td>

		  </tr>

			 <td width="100" style=" color:#333333; " ><strong></strong></td>

			<td width="166">'.$download.'</td>

			<td width="100" style=" color:#333333;"  ><strong>Status</strong></td>

			<td width="166">'.$newstatus.'</td>



		  </tr>

		  <tr>

		   <td width="100" nowrap="nowrap" style=" color:#333333;" width:><strong></strong></td>

			<td width="166"></td>

		   

		   <td width="100" style=" color:#333333;" ><strong></strong></td>

			<td width="166">'.$action_Link.'</td>

		  </tr></table>';*/

		  



		

		  }else{

			  

		  while($allexam	=	mysql_fetch_array($exequery)){

		  

		  $current              = date('Y-m-d');

		  	 

		  $sql_pro_sin = "select * from tbl_exam  where exam_id='".$allexam['exam_id']."'";

		  $rs_pro_sin  = mysql_query($sql_pro_sin);

		  $row_pro_sin = mysql_fetch_array($rs_pro_sin);

		  		   

		   if($current > $ExpireDate && $ExpireDate!='' && $ExpireDate!=0)

		   {

			  $Status = "Expire";

			  $newstatus	=	"<font style='color:red; font-weight:bold;'>Expire</font>";

			  $action_Link="<a href='reorder.php?id=".$rows['ID']."'>Re-Order </a>";

			  

		   }

		   else

		   {

			   $Status = "Active";

			   $newstatus	=	"<font style='color:#006600; font-weight:bold;'>Active</font>";

			   $action_Link	=	'';

			   }  

						

				if($Status=="Active")

				{

					$filenamename	=	basename($row_pro_sin['exam_full']);

					$file_path = '';

					if($filenamename=='')

					{

						$filenamename = $row_pro_sin['exam_name'].".zip";

					}

					$this->find_file_now('devil/full/', $filenamename, $file_path);



						$strno1 = generateRandomString();

						$strno2 = generateRandomString();



						$SentNo1=$strno1."-".$rows['ID']."-".$row_pro_sin['exam_id']."-".$strno2;

						$SentNo1=base64_encode($SentNo1);

					

						$download="<a href='download.php?x=".$SentNo1."' style='text-decoration:none;color:#F75703;'>Download</a>";

									

				}

				elseif($Status=="Expire")

				{

					$download="Download";

					

				}

				

			

				$showTR	.='

				

<li>

                        <ul class="list-box list-elements">

                            <li style="text-align:left">

                               <strong>Exam Code:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row_pro_sin['exam_name'].'</strong><br />

                               <strong>Exam:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row_pro_sin['exam_fullname']. '</strong> 

                            </li>

							<li><b><font style="color:#006600; font-weight:bold;">'.$download.'</font></b></li>

                            <li><b><font style="color:#006600; font-weight:bold;">Active</font></b></li>

                            <li><b></b></li>

                        </ul>

              </li>

				

				

				

		 ';

				

				}

			}  

		}

		

		

		return $showTR;

		

		}



/////////////////////////////////////////////////////////////////////////////////////////////

function fillComboCategory($intValue = 0)

			{

			$txtSelected = "";	//Set the option to Selected

			$qry = mysql_query("select * from tbl_vendors where ven_status='1' ORDER BY ven_name");

				   

			$txtCombo = "<select name=\"cmb_category\" id=\"cmb_category\" class=\"csstxtfield2\" style='width:200px;'  onchange='showsubcate(this.value)' >";

			$txtCombo .= "<option value=''>Select Vendor</option>";	

			

 		while ($row1 = mysql_fetch_array($qry)) 

					{

						if($intValue == $row1["ven_id"])

								{

									$txtSelected = "selected";

									$txtCombo .= "<option value=\"".$row1['ven_id']."\" $txtSelected >".$row1['ven_name']."</option>";

								}

								else

								{

									$txtSelected = "";

									$txtCombo .= "<option value=\"".$row1['ven_id']."\"  >".$row1['ven_name']."</option>";

								}

					}

					return $txtCombo."</select> " ;

				}

///////////////////////////////////////////////////////////////////////////////////////////////

function fillComboSubCategory($intValue = 0)

			{

			$txtSelected = "";	//Set the option to Selected

			$qry = mysql_query("select * from tbl_cert where cert_id='gggggggg'  ORDER BY cert_name");

				   

			$txtCombo = "<select name=\"cmb_subcategory\" id=\"cmb_subcategory\" style='width:200px;' onchange='showsubcateexams(this.value)' >";

			$txtCombo .= "<option value='' >Select Certification</option>";	

				while ($row1 = mysql_fetch_array($qry)) 

					{

						if($intValue == $row1["cert_id"])

								{

									$txtSelected = "selected";

									$txtCombo .= "<option value=\"".$row1['cert_id']."\" $txtSelected >".$row1['cert_name']."</option>";

								}

								else

								{

									$txtSelected = "";

									$txtCombo .= "<option value=\"".$row1['cert_id']."\"  >".$row1['cert_name']."</option>";

								}

															

					}

						

					return $txtCombo."</select> " ;

				}



function fillComboSubCategoryexams($intValue = 0,$certid)

			{

			$txtSelected = "";	//Set the option to Selected

			$rrr	=	"SELECT  * FROM tbl_exam WHERE exam_id= '".$certid."'";

			$qry = mysql_query($rrr);

				   

			$txtCombo = "<select name=\"cmb_subcategoryexam\" id=\"cmb_subcategoryexam\" class=\"csstxtfield2\"  style='width:200px;'> ";

			$txtCombo .= "<option value=''>Select Exam</option>";	

				while ($row1 = mysql_fetch_array($qry)) 

					{

						if($intValue == $row1["exam_id"])

								{

									$txtSelected = "selected";

									$txtCombo .= "<option value=\"".$row1['exam_id']."\" $txtSelected >".$row1['exam_name']."</option>";

								}

								else

								{

									$txtSelected = "";

									$txtCombo .= "<option value=\"".$row1['exam_id']."\"  >".$row1['exam_name']."</option>";

								}

					}

					return $txtCombo."</select> " ;

				}

///////////////////////////////////////////////////////////////////////////////////////////////

		



	}

?>