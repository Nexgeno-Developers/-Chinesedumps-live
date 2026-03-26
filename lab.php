<!-- template 1 -->
<div class="row">
    <div class="col-md-4 col-sm-4">
        <li class="main_cert bootcamp_boxex_li pttop20">
            <div class="package-box">
                <div class="pkg-name pro_cet">
                    <?php
                    // Original content
                    $fullname = $exam['exam_fullname'];

                    // Split the string into an array of words
                    $words = explode(' ', $fullname);

                    // Remove the last two words
                    array_splice($words, -2);

                    // Join the remaining words back into a string
                    $updatedFullname = implode(' ', $words);

                    // Output the result
                    echo $updatedFullname;

                    // Check if the current URL matches the specific one
                    if ($_SERVER['REQUEST_URI'] == '/CCDE-Lab.htm') {
                        // Output custom text for the specific URL
                        echo ' Written';
                    } else {
                        // Otherwise, output 'Real Lab Workbook'
                        echo ' Real Lab Workbook';
                    }
                    ?>
                </div>
            </div>

            <div class=" products_img_labs">
                <img src="/images/slider/<?= $examID ?>/<?= $exam['course_image'] ?>" style="width: 100%;">
            </div>
            <div class="package-box">
                <div style="clear:both;"></div>
                <div class="basicLab">
                    <?= $pieces[0]; ?>
                </div>
            </div>

            <div class="labs_blocks">
                <div class="labs_flex">
                    <div class="package-price">
                        Price: <span class="green_clr">$ <?= $price ?></span>
                    </div>
                    <div class="buy-package pro_cet_1">
                        <button class="carts_button" value="7" name="ptype" id="type_7"
                            onclick="<?= $isLoggedIn ? "submitProexam($examID, '7');" : "redirectToLogin();" ?>">
                            Buy Now <img src="images/new-image/new_cart_icons.png">
                        </button>
                    </div>
                </div>
            </div>
        </li>
    </div>

    <div class="col-md-4 col-sm-4 padd000">
        <li class="main_cert bootcamp_boxex_li">
            <div class="package-box">
                <div class="pkg-name pro_cet">
                    <?php
                    // Original content
                    $fullname = $exam['exam_fullname'];

                    // Split the string into an array of words
                    $words = explode(' ', $fullname);

                    // Remove the last two words
                    array_splice($words, -2);

                    // Join the remaining words back into a string
                    $updatedFullname = implode(' ', $words);

                    // Output the result
                    echo $updatedFullname;

                    // Check if the current URL matches the specific one
                    if ($_SERVER['REQUEST_URI'] == '/CCDE-Lab.htm') {
                        // Output custom text for the specific URL
                        echo ' Real Lab Workbook + Bootcamp';
                    } else {
                        // Otherwise, output 'Real Lab Workbook'
                        echo ' Real Lab Workbook + Racks + Bootcamp';
                    }
                    ?>
                </div>
            </div>

            <div class="products_img_labs">
                <img src="/images/slider/<?= $examID ?>/<?= $exam['course_image'] ?>" style="width: 100%;">
            </div>
            <div class="package-box">
                <div class="bootcampLab">
                    <?= $pieces[2]; ?>
                </div>
            </div>

            <div class="labs_blocks">
                <div class="labs_flex">
                    <div class="package-price">
                        Price: <span class="green_clr">$ <?= $bprice ?> </span>
                    </div>
                    <div class="buy-package pro_cet_1">
                        <button class="carts_button" value="9" name="ptype" id="type_9"
                            onclick="<?= $isLoggedIn ? "submitProexam($examID, '9');" : "redirectToLogin();" ?>">
                            Buy Now <img src="images/new-image/new_cart_icons.png">
                        </button>
                    </div>
                </div>
            </div>
        </li>
    </div>

    <div class="col-md-4 col-sm-4">
        <li class="main_cert bootcamp_boxex_li pttop20">
            <div class="package-box">
                <div class="pkg-name pro_cet">
                    <?php
                    // Original content
                    $fullname = $exam['exam_fullname'];

                    // Split the string into an array of words
                    $words = explode(' ', $fullname);

                    // Remove the last two words
                    array_splice($words, -2);

                    // Join the remaining words back into a string
                    $updatedFullname = implode(' ', $words);

                    // Output the result
                    echo $updatedFullname;

                    // Check if the current URL matches the specific one
                    if ($_SERVER['REQUEST_URI'] == '/CCDE-Lab.htm') {
                        // Output custom text for the specific URL
                        echo ' Real Lab Workbook';
                    } else {
                        // Otherwise, output 'Real Lab Workbook'
                        echo ' Real Lab Workbook + Racks';
                    }
                    ?>
                </div>
            </div>

            <div class="products_img_labs">
                <img src="/images/slider/<?= $examID ?>/<?= $exam['course_image'] ?>" style="width:100%;">
            </div>

            <div class="package-box">
                <div class="workbookLab">
                    <?= $pieces[1]; ?>
                </div>
            </div>

            <div class="labs_blocks">
                <div class="labs_flex">
                    <div class="package-price">
                        Price: <span class="green_clr">$ <?= $eprice ?> </span>
                    </div>
                    <div class="buy-package pro_cet_1">
                        <button class="carts_button" value="8" name="ptype" id="type_8"
                            onclick="<?= $isLoggedIn ? "submitProexam($examID, '8');" : "redirectToLogin();" ?>">
                            Buy Now <img src="images/new-image/new_cart_icons.png">
                        </button>
                    </div>
                </div>
            </div>
        </li>
    </div>
</div>

