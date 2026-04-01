<!-- template 2 | Return Dumps by written.php -->

<?/*php if ($freeDumpAvailable) { */  ?>
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
                        <button class="carts_button" type="button"
                            onclick="<?= $isLoggedIn ? "document.getElementById('freeDumpForm').submit();" : "redirectToLogin();" ?>">
                            Buy Now <img src="images/new-image/new_cart_icons.png">
                        </button>
                    </div>
                </div>
                <?php if ($freeDumpError !== '') { ?>
                    <div class="error-message" style="color:#ff4d4f; padding-top:6px;"><?php echo $freeDumpError; ?></div>
                <?php } ?>
            </div>
        </div>
    </li>

    <li class="main_cert courses_listing_prc">
        <div class="row align_centers">
            <div class="col-md-1 col-xs-2"><img src="images/new-image/secure_pdf.svg"></div>
            <div class="col-md-6 col-xs-10"><div class="pkg-name pro_cet">
                <?php echo $exam['exam_fullname']; ?> Exam Secure PDC Package
            </div></div>
            <div class="col-md-5 col-xs-12">
                <div class="flex_boxex">
                    <div class="green_clr price_sizes">$<?= $price ?></div>
                    <div class="buy-package pro_cet_1">
                        <button class="carts_button" value="p" name="ptype" id="type_p"
                            onclick="<?= $isLoggedIn ? "submitProexam($examID, 'p');" : "redirectToLogin();" ?>">
                            Buy Now <img src="images/new-image/new_cart_icons.png">
                        </button>
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
                    <div class="pkg-name pro_cet">
                        <?php echo $exam['exam_fullname']; ?> Exam Engine Package
                    </div>
                </div>
            </div>
            <div class="col-md-5 col-xs-12">
                <div class="flex_boxex">
                    <div class="green_clr price_sizes">
                        $
                        <?= $eprice ?>
                    </div>
                    <div class="buy-package pro_cet_1">
                        <button class="carts_button" value="sp" name="ptype" id="type_sp"
                            onclick="<?= $isLoggedIn ? "submitProexam($examID, 'sp');" : "redirectToLogin();" ?>">
                            Buy Now <img src="images/new-image/new_cart_icons.png">
                        </button>
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
                    <div class="pkg-name pro_cet">
                        <?php echo $exam['exam_fullname']; ?> Exam Secure PDC + Engine Package
                    </div>
                </div>
            </div>
            <div class="col-md-5 col-xs-12">
                <div class="flex_boxex">
                    <div class="green_clr price_sizes">
                        $
                        <?= $bprice ?>
                    </div>
                    <div class="buy-package pro_cet_1">
                        <button class="carts_button" value="both" name="ptype" id="type_both"
                            onclick="<?= $isLoggedIn ? "submitProexam($examID, 'both');" : "redirectToLogin();" ?>">
                            Buy Now <img src="images/new-image/new_cart_icons.png">
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </li>

