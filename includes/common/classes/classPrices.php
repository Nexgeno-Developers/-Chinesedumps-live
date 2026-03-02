<?PHP

/*

..........................................................................................................



..........................................................................................................	

*/

	//--------------------------------------------------------------------------------

	//					<<<<Creation of the Class Admin class>>>>	

	error_reporting(15);

	$homepth = getcwd()."/includes/config/classDbConnection.php";

//	include($homepth);



//	$objDBcon   =   new classDbConnection;

	//--------------------------------------------------------------------------------

	//					<<<<Headers including area>>>>>

	//===================================================================================



	class classPrices

	{





///////////////////////////////////////////////////////

public function getProductPrice($pCodeid,$ptype,$duration)

	{



	$strSql 	=	"SELECT * FROM tbl_exam WHERE exam_id='".$pCodeid."'";

    $result		=	mysql_query($strSql);

	$re 		=	mysql_fetch_array($result);



	if($re["ven_id"] == "16" || $re["ven_id"] == "107"){

		$price_plus = "10.00";

	}else{

		$price_plus = "0.00";

	}





		if($ptype == "both"){
			if($re["both_pri"] != "" && $re["both_pri"] != "0.00"){
				$setPrice = $re["both_pri"];
			}else{
				$setPrice = $this->getdbPrice($ptype,$duration);
				$setPrice = $setPrice["pri_value"]+$price_plus;
			}
		}elseif($ptype == "sp"){
			if($re["engn_pri3"] != "" && $re["engn_pri3"] != "0.00"){
				$setPrice = $re["engn_pri3"];
			}else{
				$setPrice = $this->getdbPrice($ptype,$duration);
				$setPrice = $setPrice["pri_value"]+$price_plus;
			}
		}elseif($ptype == "9"){
			if($re["both_pri"] != "" && $re["both_pri"] != "0.00"){
				$setPrice = $re["both_pri"];
			}else{
				$setPrice = $this->getdbPrice($ptype,$duration);
				$setPrice = $setPrice["pri_value"]+$price_plus;
			}
		}elseif($ptype == "8"){
			if($re["engn_pri3"] != "" && $re["engn_pri3"] != "0.00"){
				$setPrice = $re["engn_pri3"];
			}else{
				$setPrice = $this->getdbPrice($ptype,$duration);
				$setPrice = $setPrice["pri_value"]+$price_plus;
			}
		}elseif($ptype == "7"){
			if($re["exam_pri3"] != "" && $re["exam_pri3"] != "0.00"){
				$setPrice = $re["exam_pri3"];
			}else{
				$setPrice = $this->getdbPrice($ptype,$duration);
				$setPrice = $setPrice["pri_value"]+$price_plus;
			}
		}else{
			if($re["exam_pri3"] != "" && $re["exam_pri3"] != "0.00"){
				$setPrice = $re["exam_pri3"];
			}else{
				$setPrice = $this->getdbPrice($ptype,$duration);
				$setPrice = $setPrice["pri_value"]+$price_plus;
			}
		}





	return $setPrice;

	}

//////////////////////////////////////////////////////

public function getProductSwreg($pCode,$ptype,$duration)

	{

	$strSql 	=	"SELECT * FROM tbl_exam WHERE exam_id='".$pCode."'";

    $result		=	mysql_query($strSql);

	$re 		=	mysql_fetch_array($result);

		if($ptype == "both"){

			if($re["swreg_both"] != ""){

				$setSwreg = $re["swreg_both"];

			}else{

				$setSwreg = $this->getdbPrice($ptype,$duration);

				$setSwreg = $setSwreg["pri_swreg"];

			}

		}elseif($ptype == "sp"){

			if($re["swreg_sp"] != ""){

				$setSwreg = $re["swreg_sp"];

			}else{

				$setSwreg = $this->getdbPrice($ptype,$duration);

				$setSwreg = $setSwreg["pri_swreg"];

			}

		}else{

			if($re["swregid"] != ""){

				$setSwreg = $re["swregid"];

			}else{

				$setSwreg = $this->getdbPrice($ptype,$duration);

				$setSwreg = $setSwreg["pri_swreg"];

			}

		}





	return $setSwreg;

	}

///////////////////////////////////////////////////////////

private function getdbPrice($pCode,$duration)

	{

	$strSql 	=	"SELECT * FROM product_prices WHERE pri_type='".$pCode."' and pri_duration='".$duration."'";

    $result		=	mysql_query($strSql);

	$re 		=	mysql_fetch_array($result);

	return $re;

	}













	/////////////////////////////////////////////////////////////////////////////////////////////		

	}

?>