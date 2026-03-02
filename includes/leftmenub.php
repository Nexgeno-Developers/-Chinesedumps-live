				<div id="leftbar_body">
                  <?
				if(isset($_SESSION['uid']))
				{
					include("html/account.html");
				}
				
				?>
		<div id="div_heading"><h2>Popular Vendor</h2></div>
			<div class="sidemenu">
				<ul>
                <?php
							$sql	=	"Select * from tbl_vendors where ven_home='1' and ven_status='1' limit 12";
							$result	=	mysql_query($sql)or die(mysql_error()."There is no vendor");
							$iex	=	1;
							while($used=mysql_fetch_array($result))
							{
								$pageright		= 	$used['ven_name']; 
								$urlright		=	$websiteURL."braindumps-".str_replace(' ', '-',$pageright).".html";
								$titleright		=	$used['ven_name']." Certification";
/*				
								$pageright		= 	$used['ven_name']; 
								$urlright		=	$websiteURL.str_replace(' ', '-',$pageright)."-certification-training.html";
								$titleright		=	$used['ven_name']." Certification";
*/                     			
							?>
				 			 <li><a href="<?=$urlright?>" title="<?=$titleright?>"><?php echo $used['ven_name']; ?></a></li>
                              <? 
							$iex++;
							} ?>
				</ul><br />
                <a href="vendors.html">View All Vendors...</a><br />
			</div>
		</div>

