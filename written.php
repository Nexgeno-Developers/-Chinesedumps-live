<?php
?>
						<div class="">
							<div class="group">
								<div class="courses_main_boxex1 paddtop60 paddbottom60 ">
								    <div class="container">
								        <div class="row">

									<!-- #labSlider -->
									<?php
                                        $uri = explode('-', $_SERVER['REQUEST_URI']);
                                    ?>
                                        <div class="col-md-4">
                                            <div class="course_image_bg">
                                            <?php
                                                $vendorImgMain = isset($vendor_result['default_image']) ? trim($vendor_result['default_image']) : '';
                                                        $mainImage     = resolveExamVendorImagePath($examID, $exam['course_image'], $vendorImgMain);
                                                        if ($mainImage !== '') {
                                                            echo '<img src="' . asset_url($mainImage) . '" style="width:100%;">';
                                                        } else {
                                                            echo '<div class="exam-pkg-image01">&nbsp;</div>';
                                                        }
                                                    ?>
                                            </div>
                                        </div>
											<div class="col-md-8 workbook_listing_box">
											<div class="exam-pkg-list group">
												<?php
                                                    $strSql11 = "SELECT id FROM tbl_package_engine WHERE package_id='" . $examID . "'";

                                                        $examsult11 = mysql_query($strSql11) or die(mysql_error());

                                                        if (mysql_num_rows($examsult11) > 0) {
                                                        ?>
													<ul class="group">


														<?php if ($exam['QA'] == '0') {?>
															<li>
																<div style="width:95%">
																	<span style="padding-bottom:12px;">Exam Request</span>
																	<form action="" name="demoForm2" method="post" onsubmit="return demoVerify3();"> Exam Code:
																		<br />
																		<input type="text" class="textbox" name="request_ecode" id="request_ecode" value="<?php echo $pCode; ?>" />
																		<br /> Email:
																		<br />
																		<input type="text" class="textbox" name="request_email" id="request_email" />
																		<br />
																		<br />
																		<input type="submit" value="Send Request" class="black-button" /> </form>
																</div>
																<?php } else {?>
															</li>
															<li>
																<div class="courses_headings"><strong><?php echo $exam['exam_fullname']; ?> Exam Dumps & Study Guide</strong> </div>

																<div class="popular-list-box sidetext">
																	<div class="popular-list-heading">

																		<strong>Exam Code: </strong>
																			<span class="green_clr"><?php echo $exam['QA'] ?></span>

																				<br>
																					<?php
                                                                                    $status = getCourseStatusLabelHtml($exam['exam_name']);
                                                                                                ?> <strong>Status: </strong>
																						<?php echo $status; ?> <br>


																		<strong>Support days :</strong>
																			<span class="green_clr"> 15 days</span>

																				<br>

																						</div>
																</div>

															</li>
															<li class="main_cert courses_listing_prc free_dump_row">
    <div class="row align_centers">
        <div class="col-md-1 col-xs-2"><img src="images/new-image/demo_pdf2.svg" alt="50 Real Demo Question"></div>
        <div class="col-md-6 col-xs-10">
            <div class="pkg-name pro_cet">
                <span class="flash-text"><?php echo $exam['exam_fullname']; ?> Demo Question Download Free</span>
            </div>
        </div>
        <div class="col-md-5 col-xs-12">
            <div class="flex_boxex">
                <div class="green_clr price_sizes">$0</div>
                <div class="buy-package pro_cet_1">
                    <form method="post" id="freeDumpForm">
                        <input type="hidden" name="download_free_dump" value="1">
                    </form>
                    <button class="carts_button" type="button" onclick="<?php echo $isLoggedIn ? "document.getElementById('freeDumpForm').submit();" : "redirectToLogin();" ?>">Buy Now <img src="images/new-image/new_cart_icons.png"></button>
                </div>
            </div>
            <?php if ($freeDumpError !== '') {?>
                <div class="error-message" style="color:#ff4d4f; padding-top:6px;"><?php echo $freeDumpError; ?></div>
            <?php }?>
        </div>
    </div>
</li>

<li class="main_cert courses_listing_prc">
    <div class="row align_centers">
        <div class="col-md-1 col-xs-2"><img src="images/new-image/secure_pdf.svg"></div>
        <div class="col-md-6 col-xs-10">
            <div class="pkg-name pro_cet"><?php echo $exam['exam_fullname']; ?> Exam Secure PDC Package</div>
        </div>
        <div class="col-md-5 col-xs-12">
            <div class="flex_boxex">
                <div class="green_clr price_sizes">$<?php echo $price ?></div>
                <div class="buy-package pro_cet_1">
                    <button class="carts_button" value="p" name="ptype" id="type_p" onclick="<?php echo $isLoggedIn ? "submitProexam($examID, 'p');" : "redirectToLogin();" ?>"> Buy Now <img src="images/new-image/new_cart_icons.png"></button>
                </div>
            </div>
        </div>
    </div>
</li>

<li class="main_cert courses_listing_prc">
    <div class="row align_centers">
        <div class="col-md-1 col-xs-2"><img src="images/new-image/engine_package.svg"></div>
        <div class="col-md-6 col-xs-10">
            <div class="package-box">
                <div class="pkg-name pro_cet"><?php echo $exam['exam_fullname']; ?> Exam Engine Package</div>
            </div>
        </div>
        <div class="col-md-5 col-xs-12">
            <div class="flex_boxex">
                <div class="green_clr price_sizes">$<?php echo $eprice ?></div>
                <div class="buy-package pro_cet_1">
                    <button class="carts_button" value="sp" name="ptype" id="type_sp" onclick="<?php echo $isLoggedIn ? "submitProexam($examID, 'sp');" : "redirectToLogin();" ?>"> Buy Now <img src="images/new-image/new_cart_icons.png"></button>
                </div>
            </div>
        </div>
    </div>
</li>

<li class="main_cert courses_listing_prc">
    <div class="row align_centers">
        <div class="col-md-1 col-xs-2"><img src="images/new-image/pdc_icons.svg"></div>
        <div class="col-md-6 col-xs-10">
            <div class="package-box">
                <div class="pkg-name pro_cet"><?php echo $exam['exam_fullname']; ?> Exam Secure PDC + Engine Package</div>
            </div>
        </div>
        <div class="col-md-5 col-xs-12">
            <div class="flex_boxex">
                <div class="green_clr price_sizes">$<?php echo $bprice ?></div>
                <div class="buy-package pro_cet_1">
                    <button class="carts_button" value="both" name="ptype" id="type_both" onclick="<?php echo $isLoggedIn ? "submitProexam($examID, 'both');" : "redirectToLogin();" ?>"> Buy Now <img src="images/new-image/new_cart_icons.png"></button>
                </div>
            </div>
        </div>
    </div>
</li>

															<?php }?>
													</ul>
													<?php
                                                        } else {
                                                            ?>
														<li>
															<?php if ($exam['QA'] == '0') {?>
																<div style="width:95%">
																	<span class="popular-heading">This Exam <?php echo $exam['exam_name']; ?> is Under Development, Please Enter Your Email Address Below. We Will Notify You Once This Exam Ready To Download.</span>
																	<form action="" name="demoForm2" method="post" onsubmit="return demoVerify3();"> Exam Code:
																		<br />
																		<input type="text" class="textbox" name="request_ecode" id="request_ecode" value="<?php echo $pCode; ?>" />
																		<br /> Email:
																		<br />
																		<input type="text" class="textbox" name="request_email" id="request_email" />
																		<br />
																		<input type="submit" class="orange-button" width="100" vspace="" hspace="90" /> </form>
																</div>
																<?php } else {?>
																	<div class="pkg-name-box">
																		<div class="unlimite-image">&nbsp;</div>
																		<div class="pkg-name-box02">&nbsp;</div>
																	</div>
																	<div class="package-box">
																		<div class="pkg-name"> Single
																			<?php echo $exam['exam_name']; ?> PDF Exams Package</div>
																		<div class="list-discription">
																			<ul class="popular-feature-list">
																				<li>
																					<?php echo $venName; ?>
																						<?php echo $exam['exam_name']; ?> Exam based on Real Questions</li>
																				<li>Include Multiple Choice, Drag Drop and Lab Scenario</li>
																				<li>Easy to use and downloadable PDF for
																					<?php echo $exam['exam_name']; ?> Exam</li>
																				<li>Free Demo Available for Exam
																					<?php echo $exam['exam_name']; ?>
																				</li>
																				<li>
																					<?php echo $venName; ?> Experts Verified Answers for
																						<?php echo $exam['exam_name']; ?> Dumps</li>
																				<li>Cover all Latest and Updated Objectives from
																					<?php echo $venName; ?>
																				</li>
																				<li>100% Money Back Guarantee</li>
																				<li>Free Lifetime Updates</li>
																			</ul>
																		</div>
																	</div>
																	<div class="buy-package">
																		<div class="package-price">$
																			<?php echo $exam['exam_pri0'] ?>
																		</div>
																		<button class="black-button">Buy Now </button>
																	</div>
																	<?php
                                                                        }
                                                                            ?>
												    <?php }?>
														</li>
														</div>

									 </div>
									 </div>
									 </div>
									 </div>

									 <div class="passing_dumps display_inlines paddtop60 paddbottom60 ">
									     <div class="container">
    									 <div class="main_heading text-center paddbtm10"><?php echo $exam['exam_fullname']; ?> (<?php echo $exam['QA'] ?>) Passing <span>Results</span></div>
        								<ul class="courses_pass_img" id="scroller">
                                                <?php
                                                    $banners = mysql_query('select * from sliders_new where group_id=' . $examID);
                                                        if (mysql_num_rows($banners) == 0) {
                                                            $banners = mysql_query("select * from sliders where type='lab' AND status = 1");
                                                        }

                                                        while ($banner = mysql_fetch_object($banners)) {
                                                        ?>
                                                    <li>
                                                        <a href="/images/slider/<?php echo $banner->s_image; ?>"
                                                           data-fancybox="course-gallery"
                                                           data-caption="<?php echo htmlspecialchars($banner->s_alt); ?>">

                                                            <img src="/images/slider/<?php echo $banner->s_image; ?>"
                                                                 alt="<?php echo htmlspecialchars($banner->s_alt); ?>" />
                                                        </a>
                                                    </li>
                                                <?php }?>
                                                </ul>
																		<!-- <table width="100%" border="0">
																			<thead>
																				<tr>
																					<td>Products</td>
																					<td>Categories</td>
																					<td>labs</td>
																					<td>Stable/Unstable</td>
																					<td>Exam Code</td>
																					<td>Dumps</td>
																				</tr>
																			</thead>
																			<tbody>
																				<tr >
																					<td>CCIE Enterprise</td>
																					<td>
																						<dd><a href="">Written</a></dd>
																						<dd><a href="">LAB</a></dd>
																					</td>
																					<td>
																						<dd>3Design + 2Deploy</dd>
																						<dd>3Design + 2Deploy</dd>
																					</td>
																					<td>
																						<dd><span style="color:<?php echo(checkCourseStatus('350-401 ENCOR') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?php echo(checkCourseStatus('350-401 ENCOR') == 'stable') ? 'Stable' : 'Unstable'; ?></span></dd>
																						<dd><span style="color:<?php echo(checkCourseStatus('CCIE EI Lab') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?php echo(checkCourseStatus('CCIE EI Lab') == 'stable') ? 'stable' : 'Unstable'; ?></span></dd>
																					</td>
																					<td>
																						<dd>350-401</dd>
																						<dd>Lab</dd>
																					</td>
																					<td>
																						<button class="carts_button" value="8" name="ptype" id="type_8" onclick="<?php echo $isLoggedIn ? "submitProexam(6794, '8');" : 'redirectToLogin();' ?>">Buy Now <img src="images/new-image/new_cart_icons.png"></button>
																					</td>
																				</tr>
																				<tr>
																					<td>CCIE Security</td>
																					<td>
																						<dd><a href="">Written</a></dd>
																						<dd> <a href="">LAB</a></dd>
																					</td>
																					<td>
																						<dd>1Design + 1Deploy</dd>
																						<dd>1Design + 1Deploy</dd>
																					</td>
																					<td>
																						<dd><span style="color:<?php echo(checkCourseStatus('350-701 SCOR') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?php echo(checkCourseStatus('350-701 SCOR') == 'stable') ? 'Stable' : 'Unstable'; ?></span></dd>
																						<dd><span style="color:<?php echo(checkCourseStatus('CCIE Security Lab') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?php echo(checkCourseStatus('CCIE Security Lab') == 'stable') ? 'Stable' : 'Unstable'; ?></span></dd>
																					</td>
																					<td>
																						<dd>350-701</dd>
																						<dd>Lab</dd>
																					</td>
																					<td>
																						<button class="carts_button" value="8" name="ptype" id="type_8" onclick="<?php echo $isLoggedIn ? "submitProexam(6791, '8');" : 'redirectToLogin();' ?>">Buy Now <img src="images/new-image/new_cart_icons.png"></button>
																					</td>
																				</tr>
																				<tr>
																					<td >CCIE Data Center</td>
																					<td  >
																						<dd ><a href="">Written</a></dd>
																						 <dd><a href="">LAB</a></dd>
																					</td>
																					<td  >
																						<dd >1Design + 1Deploy</dd>
																						 <dd>1Design + 1Deploy</dd>
																					</td>
																					<td  >
																						<dd ><span style="color:<?php echo(checkCourseStatus('350-601 DCCOR') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?php echo(checkCourseStatus('350-601 DCCOR') == 'stable') ? 'Stable' : 'Unstable'; ?></span></dd>
																						 <dd><span style="color:<?php echo(checkCourseStatus('CCIE DC Lab') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?php echo(checkCourseStatus('CCIE DC Lab') == 'stable') ? 'Stable' : 'Unstable'; ?></span></dd>
																					</td>
																					<td  >
																						<dd >350-601</dd>
																						 <dd>Lab</dd>
																					</td>
																					<td >
																						<button class="carts_button" value="8" name="ptype" id="type_8" onclick="<?php echo $isLoggedIn ? "submitProexam(6792, '8');" : 'redirectToLogin();' ?>">Buy Now <img src="images/new-image/new_cart_icons.png"></button>
																					</td>
																				</tr>
																				<tr  >
																					<td >CCIE Wireless Infra</td>
																					<td  >
																						<dd ><a href="">Written</a></dd>
																						 <dd><a href="https://cciedumps.chinesedumps.com/">LAB</a></dd>
																					</td>
																					<td  >
																						<dd >3Design + 3Deploy</dd>
																						 <dd>3Design + 3Deploy</dd>
																					</td>
																					<td  >
																						<dd ><span style="color:<?php echo(checkCourseStatus('350-401 ENCOR') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?php echo(checkCourseStatus('350-401 ENCOR') == 'stable') ? 'Stable' : 'Unstable'; ?></span></dd>
																						 <dd><span style="color:<?php echo(checkCourseStatus('CCIE Wireless Lab') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?php echo(checkCourseStatus('CCIE Wireless Lab') == 'stable') ? 'Stable' : 'Unstable'; ?></span></dd>
																					</td>
																					<td  >
																						<dd >350-401</dd>
																						 <dd>Lab</dd>
																					</td>
																					<td >
																						<button class="carts_button" value="8" name="ptype" id="type_8" onclick="<?php echo $isLoggedIn ? "submitProexam(6793, '8');" : 'redirectToLogin();' ?>">Buy Now <img src="images/new-image/new_cart_icons.png"></button>
																					</td>
																				</tr>
																				<tr>
																					<td >CCIE Service Provider</td>
																					<td  >
																						<dd ><a href="https://cciedumps.chinesedumps.com/">Written</a></dd>
																						 <dd><a href="">LAB</a></dd>
																					</td>
																					<td  >
																						<dd >1Design + 1Deploy</dd>
																						 <dd>1Design + 1Deploy</dd>
																					</td>
																					<td  >
																						<dd ><span style="color:<?php echo(checkCourseStatus('350-501 SPCOR') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?php echo(checkCourseStatus('350-501 SPCOR') == 'stable') ? 'Stable' : 'Unstable'; ?></span></dd>
																						 <dd><span style="color:<?php echo(checkCourseStatus('CCIE SP Lab') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?php echo(checkCourseStatus('CCIE SP Lab') == 'stable') ? 'Stable' : 'Unstable'; ?></span></dd>
																					</td>
																					<td  >
																						<dd >350-501</dd>
																						 <dd>Lab</dd>
																					</td>
																					<td >
																						<button class="carts_button" value="8" name="ptype" id="type_8" onclick="<?php echo $isLoggedIn ? "submitProexam(6790, '8');" : 'redirectToLogin();' ?>">Buy Now <img src="images/new-image/new_cart_icons.png"></button>
																					</td>
																				</tr>
																				<tr  >
																					<td >CCIE Collaboration</td>
																					<td  >
																						<dd ><a href="">Written</a></dd>
																						 <dd><a href="https://cciedumps.chinesedumps.com/">LAB</a></dd>
																					</td>
																					<td  >
																						<dd >1Design + 1Deploy</dd>
																						 <dd>1Design + 1Deploy</dd>
																					</td>
																					<td  >
																						<dd ><span style="color:<?php echo(checkCourseStatus('CLCOR 350-801') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?php echo(checkCourseStatus('CLCOR 350-801') == 'stable') ? 'Stable' : 'Unstable'; ?></span></dd>
																						 <dd><span style="color:<?php echo(checkCourseStatus('CCIE Collab Lab') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?php echo(checkCourseStatus('CCIE Collab Lab') == 'stable') ? 'Stable' : 'Unstable'; ?></span></dd>
																					</td>
																					<td  >
																						<dd >350-801</dd>
																						 <dd>Lab</dd>
																					</td>
																					<td >
																						<button class="carts_button" value="8" name="ptype" id="type_8" onclick="<?php echo $isLoggedIn ? "submitProexam(6788, '8');" : 'redirectToLogin();' ?>">Buy Now <img src="images/new-image/new_cart_icons.png"></button>
																					</td>
																				</tr>
																				<tr >
																					<td >CCDE</td>
																					<td  >
																						<dd ><a href="">Written</a></dd>
																						 <dd><a href="">LAB</a></dd>
																					</td>
																					<td  >
																						<dd >1Design + 1Deploy</dd>
																						 <dd>1Design + 1Deploy</dd>
																					</td>
																					<td  >
																						<dd ><span style="color:<?php echo(checkCourseStatus('350-401 ENCOR') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?php echo(checkCourseStatus('350-401 ENCOR') == 'stable') ? 'Stable' : 'Unstable'; ?></span></dd>
																						<dd><span style="color:<?php echo(checkCourseStatus('CCDE Lab') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?php echo(checkCourseStatus('CCDE Lab') == 'stable') ? 'Stable' : 'Unstable'; ?></span></dd>
																					</td>
																					<td  >
																						<dd >400-007</dd>
																						 <dd>Lab</dd>
																					</td>
																					<td >
																						<button class="carts_button" value="sp" name="ptype" id="type_sp" onclick="<?php echo $isLoggedIn ? "submitProexam(6807, 'sp');" : 'redirectToLogin();' ?>">Buy Now <img src="images/new-image/new_cart_icons.png"></button>
																					</td>
																				</tr>

																					<tr >
																					<td >CCIE DevNet</td>
																					<td  >
																						<dd ><a href="">Written</a></dd>
																						 <dd><a href="">LAB</a></dd>
																					</td>
																					<td  >
																						<dd >1Design + 1Deploy</dd>
																						 <dd>1Design + 1Deploy</dd>
																					</td>
																					<td  >
																						<dd ><span style="color:<?php echo(checkCourseStatus('350-401 ENCOR') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?php echo(checkCourseStatus('350-401 ENCOR') == 'stable') ? 'Stable' : 'Unstable'; ?></span></dd>
																						<dd><span style="color:<?php echo(checkCourseStatus('CCDE Lab') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?php echo(checkCourseStatus('CCDE Lab') == 'stable') ? 'Stable' : 'Unstable'; ?></span></dd>
																					</td>
																					<td  >
																						<dd >350-901</dd>
																						 <dd>Lab</dd>
																					</td>
																					<td >
																						<button class="carts_button" value="sp" name="ptype" id="type_sp" onclick="">Buy Now <img src="images/new-image/new_cart_icons.png"></button>
																					</td>
																				</tr>
																			</tbody>
																		</table> -->
																	</div>
																</div>
    									</div>
									</div>

									<?php if ($video_links) {?>
 									 <div class="passing_dumps paddbottom60 display_inlines">
									     <div class="container">
        									 <div class="main_heading text-center paddbtm10"> <?php echo $exam['exam_fullname']; ?> (<?php echo $exam['QA'] ?>) <span>Live Exam Videos</span></div>
        									     <div class="exam-demo-gallery">

                                                    <div class="row">
                                                      <?php foreach ($video_links as $video):
                                                                      $service = isset($video['service']) ? $video['service'] : 'youtube';
                                                                      $url     = isset($video['url']) ? $video['url'] : '';
                                                                      $embed   = getVideoEmbedUrl($service, $url);
                                                                      if (! $embed) {
                                                                          continue;
                                                                      }

                                                              ?>
                                                        <div class="col-12 col-sm-6 col-md-4" style="margin-bottom:14px;">
                                                          <div class="video-wrap" style="position:relative;padding-top:56.25%;cursor:pointer;">
                                                            <div class="yt-thumb" data-embed="<?php echo htmlspecialchars($embed); ?>" aria-role="button" tabindex="0" style="position:absolute;inset:0;display:block;">
                                                                <iframe
                                                                  src="<?php echo htmlspecialchars($embed); ?>"
                                                                  frameborder="0"
                                                                  allow="autoplay; encrypted-media; picture-in-picture"
                                                                  allowfullscreen
                                                                  loading="lazy"
                                                                  style="position:absolute; inset:0; width:100%; height:100%; border:0; border-radius:6px;">
                                                                </iframe>
                                                            </div>
                                                          </div>
                                                        </div>
                                                      <?php endforeach; ?>
                                                    </div>

                                                </div>
        									 </div>
    									 </div>
									 </div>
									 <?php }?>

									 <?php if (! empty($exam['telegram_url']) || ! empty($exam['skype_url']) || ! empty($exam['whatsapp_url'])) {?>
                                    <div class="social_group_section display_inlines" style="background-image:url(images/new-image/bg_social_img.jpg)">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-8 col-sm-10">
                                                    <div class="main_heading text-left">
                                                        Join Our 10,000+ Learner whatsapp Community
                                                        <img class="arrow_images" src="images/new-image/left_arrows.svg">
                                                    </div>
                                                    <p style="font-size:16px;">
                                                      Connect with fellow candidates, get free study materials, and unlock bonus resources.
                                                    </p>
                                                </div>
                                                <div class="col-md-4 col-sm-2">
                                                    <div class="mnimg_cl">
                                                        <?php if (! empty($exam['whatsapp_url'])) {?>
                                                            <a style="padding-left: 5px;" href="<?php echo $exam['whatsapp_url']; ?>">
                                                                <img class="ldicon_img" src="<?php echo BASE_URL; ?>images/whatsapp.png" />
                                                            </a>
                                                        <?php }?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php }?>



									  <section class="why_chooseus_sec paddtop60 paddbottom60">
   <div class="container">
       <div class="row">
           <div class="col-md-8">
                 <div class="main_heading">Why Choose Us?</div>
       <p class="paddbtm20">
           We provide regularly updated exam dumps to ensure you get the most recent and accurate questions for your certification exams.
       </p>

       <div class="choose_boxex">
          <div class="chooose_box_img">
              <img src="images/new-image/real_icons.svg" />
          </div>

           <div class="chooose_box_content">
                <h5 class="green_clr">Latest & Updated Exam Dumps</h5>
                <p class="green_clr">Our dumps are regularly updated with the latest exam patterns and real questions, so you always study relevant, up-to-date material.</p>
          </div>
       </div>

       <div class="choose_boxex">
          <div class="chooose_box_img">
              <img src="images/new-image/garantee_icons.svg" />
          </div>

           <div class="chooose_box_content">
                <h5 class="green_clr">Proven First-Attempt Success</h5>
                <p class="green_clr">Expert-reviewed and validated by recent test-takers, our dumps have helped thousands pass on their first attempt.</p>
          </div>
       </div>

       <div class="choose_boxex">
          <div class="chooose_box_img">
              <img src="images/new-image/why_choose_1.svg" />
          </div>

           <div class="chooose_box_content">
                <h5 class="green_clr">Fast, Secure & Verified Delivery</h5>
                <p class="green_clr">Your exam dumps are delivered to your registered email within 24–48 hours after payment, ensuring proper verification and quality checks.</p>
          </div>
       </div>

        <div class="choose_boxex">
          <div class="chooose_box_img">
              <img src="images/new-image/why_choose_3.svg" />
          </div>

           <div class="chooose_box_content">
                <h5 class="green_clr">Real Exam-Level Questions</h5>
                <p class="green_clr">We provide real exam-style questions that match actual structure, difficulty, and topic weightage for focused preparation.</p>
          </div>
       </div>

           </div>

           <div class="col-md-4 position_sticky1">
              <div class="enquiry_forms_section mt_00">
                  <h4 class="entroll_class">Enqire Now</h4>
                <div role="form" class="wpcf7" id="wpcf7-f611-p6-o1" lang="en-US" dir="ltr">
                  <div class="screen-reader-response"></div>
                  <form name="myForm" action="/thanks-you.htm" method="post" onsubmit="return validateForm()" class="wpcf7-form">
                    <div class="form-group">
                      <span class="wpcf7-form-control-wrap text-128">
                        <input type="text" required name="name" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required form-control" aria-required="true" aria-invalid="false" placeholder="Your Name">
                      </span>
                    </div>
                    <div class="form-group">
                      <span class="wpcf7-form-control-wrap email-415">
                        <input type="email" required name="email" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email form-control" aria-required="true" aria-invalid="false" placeholder="Your Email">
                      </span>
                    </div>
                     <div class="form-group numbere_fileds">
                      <span class="wpcf7-form-control-wrap tel-128">
                        <input type="mobile" id="mobile_code" required name="tel" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-tel wpcf7-validates-as-required wpcf7-validates-as-tel form-control" aria-required="true" aria-invalid="false" placeholder="Mobile Number">
                      </span>
                    </div>


                    <div class="form-group">
                      <span class="wpcf7-form-control-wrap tel-128">
                        <textarea rows="3"
          name="message"
          class="form-control"
          placeholder="I am looking for ......"
          onfocus="if(this.value==='I am looking for ......'){this.value='';}"
          onblur="if(this.value===''){this.value='I am looking for ......';}">
</textarea>
                        <span>
                    </div>
                    <div class="g-recaptcha" data-sitekey="<?php echo htmlspecialchars((string) secret('RECAPTCHA_SITE_KEY_DEFAULT', ''), ENT_QUOTES); ?>"></div>
                    <input type="hidden" name="recaptcha" data-rule-recaptcha="true">
                    <input type="hidden" name="home_form">
                    <input type="hidden" name="type" value="home">
                    <input type="submit" value="Submit" class="wpcf7-form-control wpcf7-submit ti_btn button_cls">
                    <span class="ajax-loader"></span>
                  </form>
                  </div>
                </div>
              </div>
            </div>
       </div>
   </div>
</section>


<div class="seo_sections">
    <div class="container">
        <div style="clear:both">
            <div class="course-description" style="clear:both"> <?php echo $exam['exam_descr']; ?> </div>



</div>
    </div>
</div>

<?php
    $relatedProducts = [];
    while ($row = mysql_fetch_array($exerel)) {
        $relatedProducts[] = $row;
    }

    if (count($relatedProducts) <= 3) {
        $relatedProducts = [];
        $additionalQuery = mysql_query("SELECT * FROM tbl_exam WHERE ven_id='31' AND exam_status='1'");
        while ($row = mysql_fetch_array($additionalQuery)) {
            $relatedProducts[] = $row;
        }
    }

    if (! empty($relatedProducts)) {
        ?>
                                                                <section class="related_products paddtop60 owl_buttons">
                                                                    <div class="container">
                                                                        <div class="main_heading text-center pbbtm20">Related <span> Products </span></div>
                                                                        <div class="owl-carousel owl-theme related_products_slider">
                                                                            <?php
                                                                                foreach ($relatedProducts as $rowallitems2) {
                                                                                            $examName      = $rowallitems2['exam_name'];
                                                                                            $eurl          = $base_path . $rowallitems2['exam_url'] . '.htm';
                                                                                            $venID         = $rowallitems2['ven_id'];
                                                                                            $vendor_result = $objPro->get_vendorName($venID);
                                                                                            $venName       = $vendor_result['ven_name'];
                                                                                            $vendorImg     = isset($vendor_result['default_image']) ? trim($vendor_result['default_image']) : '';
                                                                                        ?>
                                                                                <div class="item">
                                                                                    <div class="related_products_box">
                                                                                        <div class="related_img">
                                                                                            <?php
                                                                                                $imagePath = resolveExamVendorImagePath($rowallitems2['exam_id'], $rowallitems2['course_image'], $vendorImg);
                                                                                                            if ($imagePath !== '') {
                                                                                                                echo '<img src="' . $imagePath . '" style="width: 100%;">';
                                                                                                            } else {
                                                                                                                echo '<div class="exam-pkg-image01">&nbsp;</div>';
                                                                                                            }
                                                                                                        ?>
                                                                                        </div>
                                                                                        <div class="realted_box_content">
                                                                                            <h5 class=""><a title="<?php echo $examName ?>" href="<?php echo $eurl ?>">
                                                                                                <?php echo $rowallitems2['exam_fullname'] ?></a></h5>

                                                                                            <div class="exam_code_sec">
                                                                                                <?php
                                                                                                    $certID      = $rowallitems2['cert_id'];
                                                                                                                $cert_result = $objPro->get_certName($certID);
                                                                                                                $certName    = $cert_result[0];
                                                                                                ?>
                                                                                                        <div class="related_flex">
                                                                                                            <img src="images/new-image/exam_code_icons.svg" />
                                                                                                            <p>Exam Code: <span class="green_clr"><?php echo $examName; ?></span></p>
                                                                                                        </div>


                                                                                                <div class="related_flex">
                                                                                                    <img src="images/new-image/status_icons.svg" />
                                                                                                    <p>
                                                                                                    <?php
                                                                                                        $status2 = getCourseStatusLabelHtml($examName);
                                                                                                                ?>
                                                                                                    <strong>Status: </strong><?php echo $status2; ?>
                                                                                                    </p>
                                                                                                </div>
                                                                                            </div>
                                                                                                <div class="package-box">
                                                                                                    <div class="list-discription courses_discriptions">
                                                                                                        Interactive Testing Engine Tool that enables customize
                                                                                                        <?php echo $venName; ?>
                                                                                                        <?php echo $examName; ?>
                                                                                                        <?php echo $certName; ?> questions into Topics and Objectives. Real
                                                                                                        <?php echo $examName; ?> Exam Questions with 100% Money back Guarantee.
                                                                                                    </div>
                                                                                                </div>
                                                                                        </div>
                                                                                        <a class="related_buttons" href="<?php echo $eurl ?>">View Details</a>
                                                                                    </div>
                                                                                </div>
                                                                            <?php }?>
                                                                        </div>
                                                                    </div>
                                                                </section>
                                                                <?php }?>
																<section class="courses_slider_sec paddtop60 paddbottom60 owl_buttons green_bg_clr">
                                                               <div class="container">

                                                                        <?php
                                                                            $sql = "
                                                                            SELECT
                                                                                e.exam_id,
                                                                                e.exam_name,
                                                                                e.exam_url,
                                                                                e.course_image,
                                                                                v.default_image
                                                                            FROM tbl_exam e
                                                                            INNER JOIN tbl_vendors v ON v.ven_id = e.ven_id
                                                                            WHERE e.exam_status = 1
                                                                            ORDER BY RAND()
                                                                            LIMIT 12
                                                                        ";

                                                                                $result = mysql_query($sql) or die(mysql_error());
                                                                            ?>

                                                                        <div class="main_heading text-center paddbtm10">
                                                                            Other <span>Dumps</span>
                                                                        </div>

                                                                        <div class="owl-carousel owl-theme best_selling">
                                                                        <?php
                                                                            while ($row = mysql_fetch_assoc($result)) {
                                                                                    $finalImage = resolveExamVendorImagePath(
                                                                                        $row['exam_id'],
                                                                                        $row['course_image'],
                                                                                        $row['default_image'],
                                                                                        '/images/exam-book-ico.png'
                                                                                    );
                                                                                ?>
                                                                            <div class="item">
                                                                                <a href="<?php echo $row['exam_url']; ?>.htm">
                                                                                    <img
                                                                                        class="hvr-bounce-in"
                                                                                        src="<?php echo $finalImage; ?>"
                                                                                        alt="<?php echo htmlspecialchars($row['exam_name'], ENT_QUOTES); ?>"
                                                                                    >
                                                                                </a>
                                                                            </div>
                                                                        <?php }?>
                                                                        </div>


                                                               </div>
                                                            </section>




																<section class="faqs_sections paddtop60 paddbottom60">
   <div class="container">
       <div class="main_heading text-center">Frequently <span> Asked Questions </span></div>
       <p class="text-center pbtm20">Find answers to common questions about our exam dumps, payment options, guarantees, and more!</p>

       <div class="row">
           <div class="col-md-10 col-md-offset-1">
               <div class="panel-group" id="accordion">

                   <div class="panel panel-default">
                       <div class="panel-heading">
                           <a data-toggle="collapse" data-parent="#accordion" href="#faq1">
                           <h4 class="panel-title">

                                  Are these dumps 100% real and updated?
                                   <i class="fa fa-plus pull-right"></i>
                           </h4>
                           </a>
                       </div>
                       <div id="faq1" class="panel-collapse collapse">
                           <div class="panel-body">
                               <p>Yes, our exam dumps are based on real exam questions and are continuously updated to match the latest exam patterns, syllabus changes, and question formats. This ensures you always prepare with current, accurate, and exam-relevant content.</p>
                           </div>
                       </div>
                   </div>

                   <div class="panel panel-default">
                       <div class="panel-heading">
                           <a data-toggle="collapse" data-parent="#accordion" href="#faq2">
                           <h4 class="panel-title">
                                   Can I pass my exam just by using these dumps?
                                   <i class="fa fa-plus pull-right"></i>

                           </h4>
                            </a>
                       </div>
                       <div id="faq2" class="panel-collapse collapse">
                           <div class="panel-body">
                               <p>Yes. Our exam dumps are built from real exam questions with verified answers, giving you exactly what appears in the exam and helping you pass with confidence when used properly.</p>

                           </div>
                       </div>
                   </div>

                   <div class="panel panel-default">
                       <div class="panel-heading">
                           <a data-toggle="collapse" data-parent="#accordion" href="#faq3">
                           <h4 class="panel-title">
                               How soon can I access my dumps after purchase?
                                   <i class="fa fa-plus pull-right"></i>

                           </h4>
                           </a>
                       </div>
                       <div id="faq3" class="panel-collapse collapse">
                           <div class="panel-body">
                               <p>
After your purchase is confirmed, the study materials are typically delivered to your registered email address. In most cases, customers receive access within a few hours of completing the payment. However, because we serve customers across different time zones and process orders manually for quality verification, we maintain a delivery window of 24 to 48 hours as a standard buffer. If you need your exam dumps urgently or on priority, we’re happy to help. You can contact our support team directly at
<a href="https://api.whatsapp.com/send/?phone=447591437400&text&type=phone_number&app_absent=0" target="_blank">
+44 7591 437400
</a> for faster delivery assistance.
</p>

                           </div>
                       </div>
                   </div>

                   <div class="panel panel-default">
                       <div class="panel-heading">
                           <a data-toggle="collapse" data-parent="#accordion" href="#faq4">
                           <h4 class="panel-title">
                               Are these dumps suitable for first-time test takers?
                                   <i class="fa fa-plus pull-right"></i>

                           </h4>
                           </a>
                       </div>
                       <div id="faq4" class="panel-collapse collapse">
                           <div class="panel-body">
                               <p>Yes, our dumps are designed for both beginners and experienced professionalsYes. Our exam dumps are designed to be easy to understand and exam-focused, making them ideal for first-time candidates as well as experienced professionals preparing for certification success.</p>
                           </div>
                       </div>
                   </div>

                   <div class="panel panel-default">
                       <div class="panel-heading">
                           <a data-toggle="collapse" data-parent="#accordion" href="#faq5"><h4 class="panel-title">
                               Do you offer a passing guarantee?
                                   <i class="fa fa-plus pull-right"></i>

                           </h4>
                           </a>
                       </div>
                       <div id="faq5" class="panel-collapse collapse">
                           <div class="panel-body">
                               <p>While we don’t offer a formal passing guarantee, our exam dumps are built from highly accurate, real exam questions with verified answers, giving candidates everything they need to prepare with confidence and significantly improve their chances of success on the first attempt.</p>
                           </div>
                       </div>
                   </div>

                   <div class="panel panel-default">
                       <div class="panel-heading">
                           <a data-toggle="collapse" data-parent="#accordion" href="#faq6">
                           <h4 class="panel-title">
                               What format are the dumps provided in?
                                   <i class="fa fa-plus pull-right"></i>

                           </h4>
                           </a>
                       </div>
                       <div id="faq6" class="panel-collapse collapse">
                           <div class="panel-body">
                               <p>Our exam dumps are provided in Secure PDF and VCE formats, making them easy to access on desktops, laptops, and mobile devices for flexible and convenient exam preparation.
 </p>
                           </div>
                       </div>
                   </div>
                       <div id="faq7" class="panel-collapse collapse">
                           <div class="panel-body">
                               <p>Yes, we offer lifetime updates to keep your study material up to date.</p>
                           </div>
                       </div>
                   </div>

                   <div class="panel panel-default">
                       <div class="panel-heading">
                           <a data-toggle="collapse" data-parent="#accordion" href="#faq8">
                           <h4 class="panel-title">
                               Can I access these dumps on my mobile device?
                                   <i class="fa fa-plus pull-right"></i>

                           </h4>
                            </a>
                       </div>
                       <div id="faq8" class="panel-collapse collapse">
                           <div class="panel-body">
                               <p>Yes. Our exam dumps are fully compatible with mobile phones, tablets, and laptops, allowing you to study anytime, anywhere with complete convenience.</p>
                           </div>
                       </div>
                   </div>

                   <div class="panel panel-default">
                       <div class="panel-heading">
                           <a data-toggle="collapse" data-parent="#accordion" href="#faq9">
                           <h4 class="panel-title">
                               Is my purchase secure?
                                   <i class="fa fa-plus pull-right"></i>

                           </h4>
                           </a>
                       </div>
                       <div id="faq9" class="panel-collapse collapse">
                           <div class="panel-body">
                               <p>Yes. All transactions on our platform are 100% secure and processed through trusted payment gateways such as Razorpay, PayPal, wire transfer, Western Union, purchase orders, Cisco vouchers, and Bitcoin, ensuring your payment information remains fully protected.</p>
                           </div>
                       </div>
                   </div>

                   <div class="panel panel-default">
                       <div class="panel-heading">
                           <a data-toggle="collapse" data-parent="#accordion" href="#faq10">
                           <h4 class="panel-title">

                                 Do you offer customer support?
                                   <i class="fa fa-plus pull-right"></i>

                           </h4>
                           </a>
                       </div>
                       <div id="faq10" class="panel-collapse collapse">
                           <div class="panel-body">
                               <p>Questions or issues don’t follow office hours and neither do we. Our 24/7 support team is always available to help with access, updates, or any concerns before and after your purchase.
</div>
                       </div>
                   </div>
                   <div class="panel panel-default">
                       <div class="panel-heading">
                           <a data-toggle="collapse" data-parent="#accordion" href="#faq11">
                           <h4 class="panel-title">
                                 Mode of Payments
                                   <i class="fa fa-plus pull-right"></i>
                           </h4>
                           </a>
                       </div>
                       <div id="faq11" class="panel-collapse collapse">
                           <div class="panel-body">
<p>
  Payments on ChineseDumps can be made securely using Razorpay or PayPal, as well as wire transfer, Western Union, credit/debit cards, purchase orders, Cisco vouchers, and Bitcoin, ensuring flexible and safe payment options for all customers.
  <a href="https://ccierack.rentals/payment-modes/">View Payment Mode</a>
</p>                           </div>
                       </div>
                   </div>

               </div>
           </div>
       </div>
   </div>
</section>


																<!-----------labs close---------->
																<?php if ($uri[0] == '/pmp.htm') {?>
																	<div class="row">
																		<div class="col-md-6"> <img class="cirti_img" src="images/critificate_examimg.jpg" alt="critificate_examimg"> </div>
																		<div class="col-md-6"> <img class="cirti_img" src="images/chinese_dumps.jpg" alt="critificate_examimg"> </div>
																	</div>
																	<?php }?>
											</div>


											<script>
											$('#writtenSlider').multislider({
												continuous: true,
												duration: 10000,
											});
											</script>
						</div>
