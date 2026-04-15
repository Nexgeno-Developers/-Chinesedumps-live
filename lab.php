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
                    echo '
														<style>
															.courses_main_boxex1.paddtop60 {
																background: #3C85BA0D;
																display: inline-block;
																width: 100%;
															}
															.workbook_listing_box {
																width: 100%;
															}
															.workbookLab div {
																background: #3C85BA1A !important;
																margin-bottom: 7px;
																padding: 3px 8px;
																border-radius: 5px;
															}
															.bootcampLab div {
																	background: #3C85BA1A !important;
																margin-bottom: 7px;
																padding: 3px 8px;
																border-radius: 5px;
															}
															.bootcampLab span {
																background: transparent !important;
																font-weight: 500 !important;
																font-size: 12px;
																color: #000;
															}
															.basicLab div
															{
																background: #3C85BA1A !important;
																margin-bottom: 7px;
																padding: 3px 8px;
																border-radius: 5px;
															}

															.basicLab span {
																background: transparent !important;
																font-weight: 500 !important;
																font-size: 12px;
																color: #000;
															}
															.popular-list-box.sidetext {
																width: 100% !important;
																// text-align: center;
																// padding-bottom: 20px;
															}
                                                            .courses_headings {
                                                                font-size: 2.25rem;
                                                                line-height: 2rem;
                                                                padding-bottom: 1rem;
                                                            }
															.popular-list-box.sidetext .popular-list-heading {
																width: 100%;
															}
															.basicLab p, .basicLab div, .basicLab p, .basicLab span {
																font-size: 16px !important;
															}
															.pkg-name.pro_cet {
																font-size: 18px;
																font-weight: 600;
																color: #363636;
															}
															li.main_cert .package-price {
																font-size: 18px;
																float: left;
																font-weight: 600;
															}
															.basicLab span {
																background: transparent !important;
																font-weight: 500 !important;
																font-size: 12px !important;
																color: #000;
															}

															.workbookLab span {
																background: transparent !important;
																font-weight: 500 !important;
																font-size: 12px;
																color: #000;
															}

															.bootcampLab span {
																background: transparent !important;
																font-weight: 500 !important;
																font-size: 12px;
																color: #000;
															}
															.package-box {
																width: 100%;
															}

														</style>
													';
                    ?>
                    <div class="col-md-8 workbook_listing_box">
                        <div class="exam-pkg-list group">
                            <?php
                            $strSql11 = "SELECT id FROM tbl_package_engine WHERE package_id='" . $examID . "'";

                            $examsult11 = mysql_query($strSql11) or die(mysql_error());

                            if (mysql_num_rows($examsult11) > 0) {
                                ?>
                                <ul class="group">


                                    <?php if ($exam['QA'] == '0') { ?>
                                        <li>
                                            <div style="width:95%">
                                                <span style="padding-bottom:12px;">Exam Request</span>
                                                <form action="" name="demoForm2" method="post" onsubmit="return demoVerify3();">
                                                    Exam Code:
                                                    <br />
                                                    <input type="text" class="textbox" name="request_ecode" id="request_ecode"
                                                        value="<?php echo $pCode; ?>" />
                                                    <br /> Email:
                                                    <br />
                                                    <input type="text" class="textbox" name="request_email"
                                                        id="request_email" />
                                                    <br />
                                                    <br />
                                                    <input type="submit" value="Send Request" class="black-button" />
                                                </form>
                                            </div>
                                        <?php } else { ?>
                                        </li>

                                        <?php
                                        $isCcdeLabRequest = ($_SERVER['REQUEST_URI'] == '/CCDE-Lab.htm');
                                        $fullCourseName = isset($exam['exam_fullname']) ? trim($exam['exam_fullname']) : '';
                                        $aliasName = isset($exam['alias_name']) ? trim((string) $exam['alias_name']) : '';
                                        $labDisplayName = ($aliasName !== '') ? $aliasName : $fullCourseName;
                                        $nameWords = preg_split('/\s+/', $labDisplayName);
                                        if (count($nameWords) > 2) {
                                            array_splice($nameWords, -2);
                                            $courseBaseName = trim(implode(' ', $nameWords));
                                        } else {
                                            $courseBaseName = $labDisplayName;
                                        }
                                        if ($courseBaseName === '') {
                                            $courseBaseName = $labDisplayName;
                                        }

                                        $rawExamDescription = isset($exam['exam_descr']) ? trim((string) $exam['exam_descr']) : '';
                                        $featureComparisonData = json_decode($rawExamDescription, true);
                                        $isFeatureComparisonJson = is_array($featureComparisonData);
                                        $hasFeatureComparison = false;
                                        if ($isFeatureComparisonJson) {
                                            $normalizedFeatureComparison = array();
                                            foreach ($featureComparisonData as $featureRow) {
                                                if (!is_array($featureRow)) {
                                                    continue;
                                                }
                                                $featureName = isset($featureRow['name']) ? trim((string) $featureRow['name']) : '';
                                                if ($featureName === '') {
                                                    continue;
                                                }
                                                $normalizedFeatureComparison[] = array(
                                                    'name' => $featureName,
                                                    'workbook' => !empty($featureRow['workbook']),
                                                    'racks' => !empty($featureRow['racks']),
                                                    'bootcamp' => !empty($featureRow['bootcamp'])
                                                );
                                            }
                                            $featureComparisonData = $normalizedFeatureComparison;
                                            $hasFeatureComparison = !empty($featureComparisonData);
                                        } else {
                                            $featureComparisonData = array();
                                        }

                                        $pieces = explode('[BREAK]', $rawExamDescription);

                                        $paidPackages = array(
                                            array(
                                                'ptype' => '7',
                                                'title' => trim($courseBaseName . ($isCcdeLabRequest ? ' Written' : ' Real Lab Workbook')),
                                                'price' => $price,
                                                'icon' => 'images/new-image/secure_pdf.svg',
                                                'support' => isset($pieces[0]) ? $pieces[0] : ''
                                            ),
                                            array(
                                                'ptype' => '8',
                                                'title' => trim($courseBaseName . ($isCcdeLabRequest ? ' Real Lab Workbook' : ' Real Lab Workbook + Racks')),
                                                'price' => $eprice,
                                                'icon' => 'images/new-image/pdc_icons.svg',
                                                'support' => isset($pieces[1]) ? $pieces[1] : ''
                                            ),
                                            array(
                                                'ptype' => '9',
                                                'title' => trim($courseBaseName . ($isCcdeLabRequest ? ' Real Lab Workbook + Bootcamp' : ' Real Lab Workbook + Racks + Bootcamp')),
                                                'price' => $bprice,
                                                'icon' => 'images/new-image/engine_package.svg',
                                                'support' => isset($pieces[2]) ? $pieces[2] : ''
                                            )
                                        );

                                        $cleanSupportHtml = function ($html) {
                                            if (!is_string($html)) {
                                                return '';
                                            }

                                            $clean = trim($html);
                                            if ($clean === '') {
                                                return '';
                                            }

                                            $clean = preg_replace('/<p[^>]*>\s*Includes:\s*<\/p>/i', '', $clean);
                                            $clean = preg_replace('/<div[^>]*>\s*Includes:\s*<\/div>/i', '', $clean);
                                            $clean = preg_replace('/Includes:\s*/i', '', $clean, 1);

                                            return trim($clean);
                                        };

                                        $hasLegacySupportContent = false;
                                        if (!$isFeatureComparisonJson) {
                                            foreach ($paidPackages as $package) {
                                                if ($cleanSupportHtml($package['support']) !== '') {
                                                    $hasLegacySupportContent = true;
                                                    break;
                                                }
                                            }
                                        }
                                        ?>
                                        <style>
                                            .lab-offer-price {
                                                padding-right: 10px;
                                                font-weight: bold;
                                            }

                                            .lab-btn-download {
                                                background-color: #0c642f;
                                                border-color: #0c642f;
                                            }

                                            .lab-offer-btn {
                                                border-radius: 16px;
                                                font-weight: bold;
                                            }

                                            .lab-offer-row {
                                                margin: 0 0 10px 0;
                                                border-radius: 8px;
                                            }

                                            .lab-offer-list {
                                                display: flex;
                                                gap: 3px;
                                                flex-direction: column;
                                            }
                                            .media-body, .media-left, .media-right{
                                                vertical-align: middle;
                                            }
                                            .lab-offer-icon img {
                                                width: 5rem;
                                                height: 5rem;
                                            }
                                            .lab-offer-free .lab-offer-price {
                                                color: #ff1412;
                                            }
                                            .lab-offer-free .lab-offer-title {
                                                color: #ff1412;
                                            }
                                            .lab-offer-free {
                                                border: 1px solid #ffd8d8;
                                            }
                                            .lab-verified-chip {
                                                display: inline-flex;
                                                align-items: center;
                                                justify-content: center;
                                                gap: 8px;
                                                background: #e5ebe7;
                                                color: #0f6b3c;
                                                border-radius: 999px;
                                                padding: 8px 16px;
                                                font-size: 1.4rem;
                                                font-weight: 600;
                                                line-height: 1;
                                                margin-top: 12px;
                                            }
                                            .lab-verified-chip i {
                                                color: #69a684;
                                                font-size: 20px;
                                            }
                                            .lab-support-section {
                                                margin-top: 34px;
                                            }
                                            .lab-support-heading {
                                                font-size: 40px;
                                                font-weight: 700;
                                                color: #1f1f1f;
                                                margin: 0 0 26px;
                                            }
                                            .lab-support-section .row {
                                                margin-left: -8px;
                                                margin-right: -8px;
                                                display: flex;
                                                flex-wrap: wrap;
                                            }
                                            .lab-support-section .row [class*="col-"] {
                                                display: flex;
                                            }
                                            .lab-support-section .col-md-4,
                                            .lab-support-section .col-sm-6 {
                                                padding-left: 8px;
                                                padding-right: 8px;
                                                margin-bottom: 14px;
                                            }
                                            .lab-support-card {
                                                border: 1px solid #d9d9d9;
                                                border-radius: 12px;
                                                box-shadow: none;
                                                background: #fff;
                                                min-height: 250px;
                                                height: 100%;
                                                display: flex;
                                                flex-direction: column;
                                            }
                                            .lab-support-card .panel-body {
                                                padding: 26px 24px 22px;
                                                flex: 1;
                                                display: flex;
                                                flex-direction: column;
                                            }
                                            .lab-support-title {
                                                font-size: 2rem;
                                                line-height: 1.3;
                                                font-weight: 700;
                                                color: #202020;
                                                margin-bottom: 12px;
                                            }
                                            .lab-support-content,
                                            .lab-support-content p,
                                            .lab-support-content li {
                                                font-size: 1.5rem;
                                                line-height: 1.7;
                                                color: #5b5f66;
                                            }
                                            .lab-support-content ul {
                                                list-style: none;
                                                margin: 0;
                                                padding: 0;
                                            }
                                            .lab-support-content li {
                                                position: relative;
                                                padding-left: 16px;
                                                margin-bottom: 2px;
                                            }
                                            .lab-support-content li:before {
                                                content: "";
                                                position: absolute;
                                                left: 0;
                                                top: 0.75em;
                                                width: 6px;
                                                height: 6px;
                                                border-radius: 50%;
                                                background: #2f7f61;
                                            }
                                            .lab-comparison-table-wrap {
                                                margin-top: 34px;
                                            }
                                            .lab-comparison-table {
                                                width: 100%;
                                                border-collapse: collapse;
                                                background: #fff;
                                            }
                                            .lab-comparison-table th,
                                            .lab-comparison-table td {
                                                border: 1px solid #d9d9d9;
                                                padding: 14px 16px;
                                            }
                                            .lab-comparison-table th {
                                                background: #f8fafb;
                                                font-size: 1.5rem;
                                                font-weight: 700;
                                                color: #202020;
                                            }
                                            .lab-comparison-table td {
                                                font-size: 1.5rem;
                                                color: #5b5f66;
                                            }
                                            .lab-comparison-table td:not(:first-child),
                                            .lab-comparison-table th:not(:first-child) {
                                                text-align: center;
                                            }
                                            .lab-comparison-table .fa {
                                                font-size: 18px;
                                            }
                                            .lab-comparison-table .text-success {
                                                color: #2f7f61;
                                            }
                                            .lab-comparison-table .text-danger {
                                                color: #d9534f;
                                            }
                                            .lab-comparison-empty {
                                                border: 1px solid #d9d9d9;
                                                padding: 18px;
                                                border-radius: 8px;
                                                background: #fff;
                                                font-size: 1.5rem;
                                                color: #5b5f66;
                                                text-align: center;
                                            }
                                        </style>
                                        <script>
                                            (function () {
                                                var links = document.getElementsByTagName('link');
                                                for (var i = 0; i < links.length; i++) {
                                                    if ((links[i].href || '').indexOf('font-awesome') !== -1) {
                                                        return;
                                                    }
                                                }
                                                var fontAwesomeLink = document.createElement('link');
                                                fontAwesomeLink.rel = 'stylesheet';
                                                fontAwesomeLink.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css';
                                                document.getElementsByTagName('head')[0].appendChild(fontAwesomeLink);
                                            })();
                                        </script>
                                        <li class="main_cert lab-figma-layout">
                                            <div class="lab-figma-top row">
                                                <div class="col-md-4 col-sm-12">
                                                    <div class="lab-media-card panel panel-default">
                                                        <div class="panel-body text-center">
                                                            <div class="lab-media-image">
                                                                <img class="img-responsive center-block"
                                                                    src="/images/slider/<?= $examID ?>/<?= $exam['course_image'] ?>"
                                                                    alt="<?= htmlspecialchars($labDisplayName); ?>">
                                                            </div>
                                                            <div class="lab-verified-chip">
                                                                <i class="fa fa-shield"></i>
                                                                <span>Verified Dumps</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-8 col-sm-12">
                                                    <ul>
                                                        <li>
                                                            <div class="courses_headings"><strong><?php echo htmlspecialchars($labDisplayName); ?></strong></div>
                                                            <div class="popular-list-box sidetext">
                                                                <div class="popular-list-heading">

                                                                    <?php
                                                                    $status = getCourseStatusLabelHtml($exam['exam_name']);
                                                                    ?>
                                                                    <?php echo $status; ?> <br>


                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                    <div class="lab-offer-list list-group">
                                                        <form method="post" id="freeDumpFormLab">
                                                            <input type="hidden" name="download_free_dump" value="1">
                                                        </form>
                                                        <form method="post" id="demoPracticeFormLab">
                                                            <input type="hidden" name="download_demo_practice" value="1">
                                                        </form>

                                                        <?php
                                                        $freeDumpLabel = isset($exam['free_dump_label']) ? trim((string)$exam['free_dump_label']) : '';
                                                        $demoPracticeLabel = isset($exam['demo_practice_label']) ? trim((string)$exam['demo_practice_label']) : '';
                                                        ?>

                                                        <?php if (!empty($exam['free_dump_pdf']) && $freeDumpLabel !== ''): ?>
                                                        <div class="lab-offer-row lab-offer-free list-group-item">
                                                            <div class="row align_centers">
                                                                <div class="col-sm-8 col-xs-12">
                                                                    <div class="media lab-offer-main">
                                                                        <div class="media-left">
                                                                            <span class="lab-offer-icon"><img
                                                                                    src="images/new-image/demo_pdf2.svg"
                                                                                    alt="Demo question"></span>
                                                                        </div>
                                                                        <div class="media-body">
                                                                            <span class="lab-offer-title">
                                                                                <?= htmlspecialchars($freeDumpLabel, ENT_QUOTES); ?>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4 col-xs-12 text-right">
                                                                    <div class="lab-offer-side">
                                                                        <span class="lab-offer-price">Free</span>
                                                                        <button
                                                                            class="btn btn-success lab-offer-btn lab-btn-download"
                                                                            type="button"
                                                                            onclick="<?= $isLoggedIn ? "document.getElementById('freeDumpFormLab').submit();" : "redirectToLogin();" ?>">
                                                                            Download
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php if (!empty($freeDumpError)) { ?>
                                                            <div class="lab-row-error"><?= htmlspecialchars($freeDumpError); ?>
                                                            </div>
                                                        <?php } ?>
                                                        <?php endif; ?>

                                                        <?php if (!empty($exam['demo_practice_file']) && $demoPracticeLabel !== ''): ?>
                                                        <div class="lab-offer-row lab-offer-free list-group-item">
                                                            <div class="row align_centers">
                                                                <div class="col-sm-8 col-xs-12">
                                                                    <div class="media lab-offer-main">
                                                                        <div class="media-left">
                                                                            <span class="lab-offer-icon"><img
                                                                                    src="images/new-image/demo_pdf.svg"
                                                                                    alt="Demo practice"></span>
                                                                        </div>
                                                                        <div class="media-body">
                                                                            <span class="lab-offer-title">
                                                                                <?= htmlspecialchars($demoPracticeLabel, ENT_QUOTES); ?>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4 col-xs-12 text-right">
                                                                    <div class="lab-offer-side">
                                                                        <span class="lab-offer-price">Free</span>
                                                                        <button
                                                                            class="btn btn-success lab-offer-btn lab-btn-download"
                                                                            type="button"
                                                                            onclick="<?= $isLoggedIn ? "document.getElementById('demoPracticeFormLab').submit();" : "redirectToLogin();" ?>">
                                                                            Download
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php if (!empty($demoPracticeError)) { ?>
                                                            <div class="lab-row-error"><?= htmlspecialchars($demoPracticeError); ?>
                                                            </div>
                                                        <?php } ?>
                                                        <?php endif; ?>

                                                        <?php foreach ($paidPackages as $package) { ?>
                                                            <div class="lab-offer-row list-group-item">
                                                                <div class="row align_centers">
                                                                    <div class="col-sm-8 col-xs-12">
                                                                        <div class="media lab-offer-main">
                                                                            <div class="media-left">
                                                                                <span class="lab-offer-icon"><img
                                                                                        src="<?= $package['icon']; ?>"
                                                                                        alt="Package"></span>
                                                                            </div>
                                                                            <div class="media-body">
                                                                                <span
                                                                                    class="lab-offer-title"><?= htmlspecialchars($package['title']); ?></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-4 col-xs-12 text-right">
                                                                        <div class="lab-offer-side">
                                                                            <span
                                                                                class="lab-offer-price">$<?= $package['price']; ?></span>
                                                                            <button
                                                                                class="btn btn-primary lab-offer-btn lab-btn-buy"
                                                                                value="<?= $package['ptype']; ?>" name="ptype"
                                                                                id="type_<?= $package['ptype']; ?>"
                                                                                onclick="<?= $isLoggedIn ? "submitProexam($examID, '" . $package['ptype'] . "');" : "redirectToLogin();" ?>">
                                                                                Buy Now
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php if ($hasFeatureComparison) { ?>
                                            <div class="lab-comparison-table-wrap">
                                                <h3 class="lab-support-heading text-center bold">Package Details &amp; Support</h3>
                                                <div class="table-responsive">
                                                    <table class="lab-comparison-table">
                                                        <thead>
                                                            <tr>
                                                                <th><?= htmlspecialchars($courseBaseName); ?></th>
                                                                <th>Real Lab Workbook + Racks + Bootcamp</th>
                                                                <th>Real Lab Workbook + Racks</th>
                                                                <th>Real Lab Workbook</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($featureComparisonData as $featureRow) { ?>
                                                            <tr>
                                                                <td><?= htmlspecialchars($featureRow['name']); ?></td>
                                                                <td><i class="fa <?= !empty($featureRow['bootcamp']) ? 'fa-check text-success' : 'fa-times text-danger'; ?>"></i></td>
                                                                <td><i class="fa <?= !empty($featureRow['racks']) ? 'fa-check text-success' : 'fa-times text-danger'; ?>"></i></td>
                                                                <td><i class="fa <?= !empty($featureRow['workbook']) ? 'fa-check text-success' : 'fa-times text-danger'; ?>"></i></td>
                                                            </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <?php } elseif ($hasLegacySupportContent) { ?>
                                            <div class="lab-support-section">
                                                <h3 class="lab-support-heading text-center bold">Package Details &amp; Support</h3>
                                                <div class="row">
                                                    <?php foreach ($paidPackages as $package) {
                                                        $supportContent = $cleanSupportHtml($package['support']);
                                                        if ($supportContent === '') {
                                                            continue;
                                                        }
                                                    ?>
                                                        <div class="col-md-4 col-sm-6">
                                                            <div class="lab-support-card panel panel-default">
                                                                <div class="panel-body">
                                                                    <div class="lab-support-title">
                                                                        <?= htmlspecialchars($package['title']); ?></div>
                                                                    <div class="lab-support-content">
                                                                        <?= $supportContent; ?></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </li>


                                    <?php } ?>
                                </ul>
                                <?php
                            } else {
                                ?>
                                <li>
                                    <?php if ($exam['QA'] == '0') { ?>
                                        <div style="width:95%">
                                            <span class="popular-heading">This Exam <?php echo $exam['exam_name']; ?> is Under
                                                Development, Please Enter Your Email Address Below. We Will Notify You Once This
                                                Exam Ready To Download.</span>
                                            <form action="" name="demoForm2" method="post" onsubmit="return demoVerify3();">
                                                Exam Code:
                                                <br />
                                                <input type="text" class="textbox" name="request_ecode" id="request_ecode"
                                                    value="<?php echo $pCode; ?>" />
                                                <br /> Email:
                                                <br />
                                                <input type="text" class="textbox" name="request_email" id="request_email" />
                                                <br />
                                                <input type="submit" class="orange-button" width="100" vspace="" hspace="90" />
                                            </form>
                                        </div>
                                    <?php } else { ?>
                                        <div class="pkg-name-box">
                                            <div class="unlimite-image">&nbsp;</div>
                                            <div class="pkg-name-box02">&nbsp;</div>
                                        </div>
                                        <div class="package-box">
                                            <div class="pkg-name"> Single
                                                <?php echo $exam['exam_name']; ?> PDF Exams Package
                                            </div>
                                            <div class="list-discription">
                                                <ul class="popular-feature-list">
                                                    <li>
                                                        <?php echo $venName; ?>
                                                        <?php echo $exam['exam_name']; ?> Exam based on Real Questions
                                                    </li>
                                                    <li>Include Multiple Choice, Drag Drop and Lab Scenario</li>
                                                    <li>Easy to use and downloadable PDF for
                                                        <?php echo $exam['exam_name']; ?> Exam
                                                    </li>
                                                    <li>Free Demo Available for Exam
                                                        <?php echo $exam['exam_name']; ?>
                                                    </li>
                                                    <li>
                                                        <?php echo $venName; ?> Experts Verified Answers for
                                                        <?php echo $exam['exam_name']; ?> Dumps
                                                    </li>
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
                                <?php } ?>
                            </li>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <?php
        // Load passing results banners (group-specific first, then fallback). Hide section if none found.
        $banners = mysql_query('select * from sliders_new where group_id=' . $examID);
        $bannerCount = mysql_num_rows($banners);
        if ($bannerCount == 0) {
            $banners = mysql_query("select * from sliders where type='lab' AND status = 1");
            $bannerCount = mysql_num_rows($banners);
        }

        if ($bannerCount > 0) {
            ?>
            <div class="passing_dumps display_inlines paddtop60 paddbottom60 ">
                <div class="container">
                    <div class="main_heading text-center paddbtm10"><?php echo htmlspecialchars($labDisplayName); ?>
                        (<?php echo $exam['QA'] ?>) Passing <span>Results</span></div>
                    <ul class="courses_pass_img" id="scroller">
                        <?php while ($banner = mysql_fetch_object($banners)) { ?>
                            <li>
                                <a href="/images/slider/<?php echo $banner->s_image; ?>" data-fancybox="course-gallery"
                                    data-caption="<?php echo htmlspecialchars($banner->s_alt); ?>">

                                    <img src="/images/slider/<?php echo $banner->s_image; ?>"
                                        alt="<?php echo htmlspecialchars($banner->s_alt); ?>" />
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                    <br>
                    <br>
                    <section id="service_table" class="commom-sec grey-sec display_none2">
                        <div class="">
                            <div class="table-responsive stable-unstable workbook_table">
                                <table width="100%" border="0">
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
                                    <tr>
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
                                            <dd><span
                                                    style="color:<?php echo (checkCourseStatus('350-401 ENCOR') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?php echo (checkCourseStatus('350-401 ENCOR') == 'stable') ? 'Stable' : 'Unstable'; ?></span>
                                            </dd>
                                            <dd><span
                                                    style="color:<?php echo (checkCourseStatus('CCIE EI Lab') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?php echo (checkCourseStatus('CCIE EI Lab') == 'stable') ? 'stable' : 'Unstable'; ?></span>
                                            </dd>
                                        </td>
                                        <td>
                                            <dd>350-401</dd>
                                            <dd>Lab</dd>
                                        </td>
                                        <td>
                                            <button class="carts_button" value="8" name="ptype" id="type_8"
                                                onclick="<?php echo $isLoggedIn ? "submitProexam(6794, '8');" : 'redirectToLogin();' ?>">Buy
                                                Now <img src="images/new-image/new_cart_icons.png"></button>
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
                                            <dd><span
                                                    style="color:<?php echo (checkCourseStatus('350-701 SCOR') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?php echo (checkCourseStatus('350-701 SCOR') == 'stable') ? 'Stable' : 'Unstable'; ?></span>
                                            </dd>
                                            <dd><span
                                                    style="color:<?php echo (checkCourseStatus('CCIE Security Lab') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?php echo (checkCourseStatus('CCIE Security Lab') == 'stable') ? 'Stable' : 'Unstable'; ?></span>
                                            </dd>
                                        </td>
                                        <td>
                                            <dd>350-701</dd>
                                            <dd>Lab</dd>
                                        </td>
                                        <td>
                                            <button class="carts_button" value="8" name="ptype" id="type_8"
                                                onclick="<?php echo $isLoggedIn ? "submitProexam(6791, '8');" : 'redirectToLogin();' ?>">Buy
                                                Now <img src="images/new-image/new_cart_icons.png"></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>CCIE Data Center</td>
                                        <td>
                                            <dd><a href="">Written</a></dd>
                                            <dd><a href="">LAB</a></dd>
                                        </td>
                                        <td>
                                            <dd>1Design + 1Deploy</dd>
                                            <dd>1Design + 1Deploy</dd>
                                        </td>
                                        <td>
                                            <dd><span
                                                    style="color:<?php echo (checkCourseStatus('350-601 DCCOR') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?php echo (checkCourseStatus('350-601 DCCOR') == 'stable') ? 'Stable' : 'Unstable'; ?></span>
                                            </dd>
                                            <dd><span
                                                    style="color:<?php echo (checkCourseStatus('CCIE DC Lab') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?php echo (checkCourseStatus('CCIE DC Lab') == 'stable') ? 'Stable' : 'Unstable'; ?></span>
                                            </dd>
                                        </td>
                                        <td>
                                            <dd>350-601</dd>
                                            <dd>Lab</dd>
                                        </td>
                                        <td>
                                            <button class="carts_button" value="8" name="ptype" id="type_8"
                                                onclick="<?php echo $isLoggedIn ? "submitProexam(6792, '8');" : 'redirectToLogin();' ?>">Buy
                                                Now <img src="images/new-image/new_cart_icons.png"></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>CCIE Wireless Infra</td>
                                        <td>
                                            <dd><a href="">Written</a></dd>
                                            <dd><a href="https://cciedumps.chinesedumps.com/">LAB</a></dd>
                                        </td>
                                        <td>
                                            <dd>3Design + 3Deploy</dd>
                                            <dd>3Design + 3Deploy</dd>
                                        </td>
                                        <td>
                                            <dd><span
                                                    style="color:<?php echo (checkCourseStatus('350-401 ENCOR') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?php echo (checkCourseStatus('350-401 ENCOR') == 'stable') ? 'Stable' : 'Unstable'; ?></span>
                                            </dd>
                                            <dd><span
                                                    style="color:<?php echo (checkCourseStatus('CCIE Wireless Lab') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?php echo (checkCourseStatus('CCIE Wireless Lab') == 'stable') ? 'Stable' : 'Unstable'; ?></span>
                                            </dd>
                                        </td>
                                        <td>
                                            <dd>350-401</dd>
                                            <dd>Lab</dd>
                                        </td>
                                        <td>
                                            <button class="carts_button" value="8" name="ptype" id="type_8"
                                                onclick="<?php echo $isLoggedIn ? "submitProexam(6793, '8');" : 'redirectToLogin();' ?>">Buy
                                                Now <img src="images/new-image/new_cart_icons.png"></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>CCIE Service Provider</td>
                                        <td>
                                            <dd><a href="https://cciedumps.chinesedumps.com/">Written</a></dd>
                                            <dd><a href="">LAB</a></dd>
                                        </td>
                                        <td>
                                            <dd>1Design + 1Deploy</dd>
                                            <dd>1Design + 1Deploy</dd>
                                        </td>
                                        <td>
                                            <dd><span
                                                    style="color:<?php echo (checkCourseStatus('350-501 SPCOR') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?php echo (checkCourseStatus('350-501 SPCOR') == 'stable') ? 'Stable' : 'Unstable'; ?></span>
                                            </dd>
                                            <dd><span
                                                    style="color:<?php echo (checkCourseStatus('CCIE SP Lab') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?php echo (checkCourseStatus('CCIE SP Lab') == 'stable') ? 'Stable' : 'Unstable'; ?></span>
                                            </dd>
                                        </td>
                                        <td>
                                            <dd>350-501</dd>
                                            <dd>Lab</dd>
                                        </td>
                                        <td>
                                            <button class="carts_button" value="8" name="ptype" id="type_8"
                                                onclick="<?php echo $isLoggedIn ? "submitProexam(6790, '8');" : 'redirectToLogin();' ?>">Buy
                                                Now <img src="images/new-image/new_cart_icons.png"></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>CCIE Collaboration</td>
                                        <td>
                                            <dd><a href="">Written</a></dd>
                                            <dd><a href="https://cciedumps.chinesedumps.com/">LAB</a></dd>
                                        </td>
                                        <td>
                                            <dd>1Design + 1Deploy</dd>
                                            <dd>1Design + 1Deploy</dd>
                                        </td>
                                        <td>
                                            <dd><span
                                                    style="color:<?php echo (checkCourseStatus('CLCOR 350-801') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?php echo (checkCourseStatus('CLCOR 350-801') == 'stable') ? 'Stable' : 'Unstable'; ?></span>
                                            </dd>
                                            <dd><span
                                                    style="color:<?php echo (checkCourseStatus('CCIE Collab Lab') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?php echo (checkCourseStatus('CCIE Collab Lab') == 'stable') ? 'Stable' : 'Unstable'; ?></span>
                                            </dd>
                                        </td>
                                        <td>
                                            <dd>350-801</dd>
                                            <dd>Lab</dd>
                                        </td>
                                        <td>
                                            <button class="carts_button" value="8" name="ptype" id="type_8"
                                                onclick="<?php echo $isLoggedIn ? "submitProexam(6788, '8');" : 'redirectToLogin();' ?>">Buy
                                                Now <img src="images/new-image/new_cart_icons.png"></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>CCDE</td>
                                        <td>
                                            <dd><a href="">Written</a></dd>
                                            <dd><a href="">LAB</a></dd>
                                        </td>
                                        <td>
                                            <dd>1Design + 1Deploy</dd>
                                            <dd>1Design + 1Deploy</dd>
                                        </td>
                                        <td>
                                            <dd><span
                                                    style="color:<?php echo (checkCourseStatus('350-401 ENCOR') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?php echo (checkCourseStatus('350-401 ENCOR') == 'stable') ? 'Stable' : 'Unstable'; ?></span>
                                            </dd>
                                            <dd><span
                                                    style="color:<?php echo (checkCourseStatus('CCDE Lab') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?php echo (checkCourseStatus('CCDE Lab') == 'stable') ? 'Stable' : 'Unstable'; ?></span>
                                            </dd>
                                        </td>
                                        <td>
                                            <dd>400-007</dd>
                                            <dd>Lab</dd>
                                        </td>
                                        <td>
                                            <button class="carts_button" value="sp" name="ptype" id="type_sp"
                                                onclick="<?php echo $isLoggedIn ? "submitProexam(6807, 'sp');" : 'redirectToLogin();' ?>">Buy
                                                Now <img src="images/new-image/new_cart_icons.png"></button>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>CCIE DevNet</td>
                                        <td>
                                            <dd><a href="">Written</a></dd>
                                            <dd><a href="">LAB</a></dd>
                                        </td>
                                        <td>
                                            <dd>1Design + 1Deploy</dd>
                                            <dd>1Design + 1Deploy</dd>
                                        </td>
                                        <td>
                                            <dd><span
                                                    style="color:<?php echo (checkCourseStatus('350-401 ENCOR') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?php echo (checkCourseStatus('350-401 ENCOR') == 'stable') ? 'Stable' : 'Unstable'; ?></span>
                                            </dd>
                                            <dd><span
                                                    style="color:<?php echo (checkCourseStatus('CCDE Lab') == 'stable') ? '#1F733D' : '#ff0000'; ?>"><?php echo (checkCourseStatus('CCDE Lab') == 'stable') ? 'Stable' : 'Unstable'; ?></span>
                                            </dd>
                                        </td>
                                        <td>
                                            <dd>350-901</dd>
                                            <dd>Lab</dd>
                                        </td>
                                        <td>
                                            <button class="carts_button" value="sp" name="ptype" id="type_sp"
                                                onclick="">Buy Now <img
                                                    src="images/new-image/new_cart_icons.png"></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <?php } ?>

        <?php if ($video_links) { ?>
            <div class="passing_dumps paddbottom60 display_inlines">
                <div class="container">
                    <div class="main_heading text-center paddbtm10"> <?php echo htmlspecialchars($labDisplayName); ?>
                        <?php if (!empty($exam['QA'])) { ?>(<?php echo $exam['QA'] ?>)<?php } ?> <span>Live Exam Videos</span></div>
                    <div class="exam-demo-gallery">

                        <div class="row">
                            <?php foreach ($video_links as $video):
                                $service = isset($video['service']) ? $video['service'] : 'youtube';
                                $url = isset($video['url']) ? $video['url'] : '';
                                $embed = getVideoEmbedUrl($service, $url);
                                if (!$embed) {
                                    continue;
                                }

                                ?>
                                <div class="col-12 col-sm-6 col-md-4" style="margin-bottom:14px;">
                                    <div class="video-wrap" style="position:relative;padding-top:56.25%;cursor:pointer;">
                                        <div class="yt-thumb" data-embed="<?php echo htmlspecialchars($embed); ?>"
                                            aria-role="button" tabindex="0" style="position:absolute;inset:0;display:block;">
                                            <iframe src="<?php echo htmlspecialchars($embed); ?>" frameborder="0"
                                                allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen
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
    <?php } ?>

    <?php if (!empty($exam['telegram_url']) || !empty($exam['skype_url']) || !empty($exam['whatsapp_url'])) { ?>
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
                            <?php if (!empty($exam['whatsapp_url'])) { ?>
                                <a style="padding-left: 5px;" href="<?php echo $exam['whatsapp_url']; ?>">
                                    <img class="ldicon_img" src="<?php echo BASE_URL; ?>images/whatsapp.png" />
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>



    <section class="why_chooseus_sec paddtop60 paddbottom60">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="main_heading">Why Choose Us?</div>
                    <p class="paddbtm20">
                        We provide regularly updated exam dumps to ensure you get the most recent and accurate questions
                        for your certification exams.
                    </p>

                    <div class="choose_boxex">
                        <div class="chooose_box_img">
                            <img src="images/new-image/real_icons.svg" />
                        </div>

                        <div class="chooose_box_content">
                            <h5 class="green_clr">Latest & Updated Exam Dumps</h5>
                            <p class="green_clr">Our dumps are regularly updated with the latest exam patterns and real
                                questions, so you always study relevant, up-to-date material.</p>
                        </div>
                    </div>

                    <div class="choose_boxex">
                        <div class="chooose_box_img">
                            <img src="images/new-image/garantee_icons.svg" />
                        </div>

                        <div class="chooose_box_content">
                            <h5 class="green_clr">Proven First-Attempt Success</h5>
                            <p class="green_clr">Expert-reviewed and validated by recent test-takers, our dumps have
                                helped thousands pass on their first attempt.</p>
                        </div>
                    </div>

                    <div class="choose_boxex">
                        <div class="chooose_box_img">
                            <img src="images/new-image/why_choose_1.svg" />
                        </div>

                        <div class="chooose_box_content">
                            <h5 class="green_clr">Fast, Secure & Verified Delivery</h5>
                            <p class="green_clr">Your exam dumps are delivered to your registered email within 24–48
                                hours after payment, ensuring proper verification and quality checks.</p>
                        </div>
                    </div>

                    <div class="choose_boxex">
                        <div class="chooose_box_img">
                            <img src="images/new-image/why_choose_3.svg" />
                        </div>

                        <div class="chooose_box_content">
                            <h5 class="green_clr">Real Exam-Level Questions</h5>
                            <p class="green_clr">We provide real exam-style questions that match actual structure,
                                difficulty, and topic weightage for focused preparation.</p>
                        </div>
                    </div>

                </div>

                <div class="col-md-4 position_sticky1">
                    <div class="enquiry_forms_section mt_00">
                        <h4 class="entroll_class">Enqire Now</h4>
                        <div role="form" class="wpcf7" id="wpcf7-f611-p6-o1" lang="en-US" dir="ltr">
                            <div class="screen-reader-response"></div>
                            <form name="myForm" action="/thanks-you.htm" method="post" onsubmit="return validateForm()"
                                class="wpcf7-form">
                                <div class="form-group">
                                    <span class="wpcf7-form-control-wrap text-128">
                                        <input type="text" required name="name" value="" size="40"
                                            class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required form-control"
                                            aria-required="true" aria-invalid="false" placeholder="Your Name">
                                    </span>
                                </div>
                                <div class="form-group">
                                    <span class="wpcf7-form-control-wrap email-415">
                                        <input type="email" required name="email" value="" size="40"
                                            class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email form-control"
                                            aria-required="true" aria-invalid="false" placeholder="Your Email">
                                    </span>
                                </div>
                                <div class="form-group numbere_fileds">
                                    <span class="wpcf7-form-control-wrap tel-128">
                                        <input type="mobile" id="mobile_code" required name="tel" value="" size="40"
                                            class="wpcf7-form-control wpcf7-text wpcf7-tel wpcf7-validates-as-required wpcf7-validates-as-tel form-control"
                                            aria-required="true" aria-invalid="false" placeholder="Mobile Number">
                                    </span>
                                </div>


                                <div class="form-group">
                                    <span class="wpcf7-form-control-wrap tel-128">
                                        <textarea rows="3" name="message" class="form-control"
                                            placeholder="I am looking for ......"
                                            onfocus="if(this.value==='I am looking for ......'){this.value='';}"
                                            onblur="if(this.value===''){this.value='I am looking for ......';}">
</textarea>
                                        <span>
                                </div>
                                <div class="g-recaptcha"
                                    data-sitekey="<?php echo htmlspecialchars((string) secret('RECAPTCHA_SITE_KEY_DEFAULT', ''), ENT_QUOTES); ?>">
                                </div>
                                <input type="hidden" name="recaptcha" data-rule-recaptcha="true">
                                <input type="hidden" name="home_form">
                                <input type="hidden" name="type" value="home">
                                <input type="submit" value="Submit"
                                    class="wpcf7-form-control wpcf7-submit ti_btn button_cls">
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
            <div class="course-description" style="clear:both"> <?php echo $exam['exam_descr2']; ?> </div>



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
    $additionalQuery = mysql_query("SELECT * FROM tbl_exam WHERE ven_id='" . $exam['ven_id'] . "' AND exam_id!='" . $examID . "' AND exam_status='1' AND LOWER(TRIM(exam_type))='lab'");
    while ($row = mysql_fetch_array($additionalQuery)) {
        $relatedProducts[] = $row;
    }
}

if (!empty($relatedProducts)) {
    ?>
    <section class="related_products paddtop60 owl_buttons">
        <div class="container">
            <div class="main_heading text-center pbbtm20">Related <span> Products </span></div>
            <div class="owl-carousel owl-theme related_products_slider">
                <?php
                foreach ($relatedProducts as $rowallitems2) {
                    $examName = $rowallitems2['exam_name'];
                    $eurl = $base_path . $rowallitems2['exam_url'] . '.htm';
                    $venID = $rowallitems2['ven_id'];
                    $vendor_result = $objPro->get_vendorName($venID);
                    $venName = $vendor_result['ven_name'];
                    $vendorImg = isset($vendor_result['default_image']) ? trim($vendor_result['default_image']) : '';
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
                                    $certID = $rowallitems2['cert_id'];
                                    $cert_result = $objPro->get_certName($certID);
                                    $certName = $cert_result[0];
                                    ?>
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
                                <p class="exam_reallab_desc"><?php echo $rowallitems2['exam_related_descr'] ?></p>
                            </div>
                            <a class="related_buttons" href="<?php echo $eurl ?>">View Details</a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
<?php } ?>
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
                                                                              AND e.exam_id != '" . $examID . "'
                                                                              AND e.ven_id != '" . $exam['ven_id'] . "'
                                                                              AND LOWER(TRIM(e.exam_type)) = 'lab'
                                                                            ORDER BY e.exam_name ASC
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
                        <img class="hvr-bounce-in" src="<?php echo $finalImage; ?>"
                            alt="<?php echo htmlspecialchars($row['exam_name'], ENT_QUOTES); ?>">
                    </a>
                </div>
            <?php } ?>
        </div>


    </div>
</section>




<section class="faqs_sections paddtop60 paddbottom60">
    <div class="container">
        <div class="main_heading text-center">Frequently <span> Asked Questions </span></div>
        <p class="text-center pbtm20">Find answers to common questions about our exam dumps, payment options,
            guarantees, and more!</p>

        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <?php
                $faqItems = array();
                $appendFaqItem = function ($question, $answer) use (&$faqItems) {
                    $question = trim((string) $question);
                    $answer = trim((string) $answer);
                    if ($question === '' || $answer === '') {
                        return;
                    }
                    $faqItems[] = array(
                        'question' => $question,
                        'answer' => $answer,
                    );
                };

                $rawFaqJson = isset($exam['faq_json']) ? trim((string) $exam['faq_json']) : '';
                if ($rawFaqJson !== '') {
                    $decodedFaq = json_decode($rawFaqJson, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decodedFaq)) {
                        if (
                            isset($decodedFaq['question']) || isset($decodedFaq['q']) || isset($decodedFaq['title']) ||
                            isset($decodedFaq['answer']) || isset($decodedFaq['a']) || isset($decodedFaq['content'])
                        ) {
                            $appendFaqItem(
                                isset($decodedFaq['question']) ? $decodedFaq['question'] : (isset($decodedFaq['q']) ? $decodedFaq['q'] : (isset($decodedFaq['title']) ? $decodedFaq['title'] : '')),
                                isset($decodedFaq['answer']) ? $decodedFaq['answer'] : (isset($decodedFaq['a']) ? $decodedFaq['a'] : (isset($decodedFaq['content']) ? $decodedFaq['content'] : ''))
                            );
                        } else {
                            foreach ($decodedFaq as $key => $item) {
                                if (is_array($item)) {
                                    $appendFaqItem(
                                        isset($item['question']) ? $item['question'] : (isset($item['q']) ? $item['q'] : (isset($item['title']) ? $item['title'] : '')),
                                        isset($item['answer']) ? $item['answer'] : (isset($item['a']) ? $item['a'] : (isset($item['content']) ? $item['content'] : (isset($item['description']) ? $item['description'] : '')))
                                    );
                                } elseif (is_string($key)) {
                                    $appendFaqItem($key, $item);
                                }
                            }
                        }
                    }
                }

                if (empty($faqItems)) {
                    $appendFaqItem(
                        'Are these dumps 100% real and updated?',
                        'Yes, our exam dumps are based on real exam questions and are continuously updated to match the latest exam patterns, syllabus changes, and question formats. This ensures you always prepare with current, accurate, and exam-relevant content.'
                    );
                    $appendFaqItem(
                        'Can I pass my exam just by using these dumps?',
                        'Yes. Our exam dumps are built from real exam questions with verified answers, giving you exactly what appears in the exam and helping you pass with confidence when used properly.'
                    );
                    $appendFaqItem(
                        'How soon can I access my dumps after purchase?',
                        'After your purchase is confirmed, the study materials are typically delivered to your registered email address. In most cases, customers receive access within a few hours of completing the payment. However, because we serve customers across different time zones and process orders manually for quality verification, we maintain a delivery window of 24 to 48 hours as a standard buffer. If you need your exam dumps urgently or on priority, you can contact support at +44 7591 437400 for faster delivery assistance.'
                    );
                    $appendFaqItem(
                        'Are these dumps suitable for first-time test takers?',
                        'Yes. Our exam dumps are designed to be easy to understand and exam-focused, making them ideal for first-time candidates as well as experienced professionals preparing for certification success.'
                    );
                    $appendFaqItem(
                        'Do you offer a passing guarantee?',
                        'While we do not offer a formal passing guarantee, our exam dumps are built from highly accurate, real exam questions with verified answers, giving candidates everything they need to prepare with confidence and significantly improve their chances of success on the first attempt.'
                    );
                    $appendFaqItem(
                        'What format are the dumps provided in?',
                        'Our exam dumps are provided in Secure PDF and VCE formats, making them easy to access on desktops, laptops, and mobile devices for flexible and convenient exam preparation.'
                    );
                    $appendFaqItem(
                        'Can I access these dumps on my mobile device?',
                        'Yes. Our exam dumps are fully compatible with mobile phones, tablets, and laptops, allowing you to study anytime, anywhere with complete convenience.'
                    );
                    $appendFaqItem(
                        'Is my purchase secure?',
                        'Yes. All transactions on our platform are 100% secure and processed through trusted payment gateways such as Razorpay, PayPal, wire transfer, Western Union, purchase orders, Cisco vouchers, and Bitcoin, ensuring your payment information remains fully protected.'
                    );
                    $appendFaqItem(
                        'Do you offer customer support?',
                        'Questions or issues do not follow office hours and neither do we. Our 24/7 support team is always available to help with access, updates, or any concerns before and after your purchase.'
                    );
                    $appendFaqItem(
                        'Mode of Payments',
                        'Payments on ChineseDumps can be made securely using Razorpay or PayPal, as well as wire transfer, Western Union, credit/debit cards, purchase orders, Cisco vouchers, and Bitcoin, ensuring flexible and safe payment options for all customers. View payment mode: https://ccierack.rentals/payment-modes/'
                    );
                }
                ?>
                <div class="panel-group" id="accordion">
                    <?php foreach ($faqItems as $index => $faqItem) {
                        $faqId = 'faq' . ($index + 1);
                        $answerText = nl2br(htmlspecialchars($faqItem['answer'], ENT_QUOTES));
                        ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <a data-toggle="collapse" data-parent="#accordion" href="#<?php echo $faqId; ?>">
                                    <h4 class="panel-title">
                                        <?php echo htmlspecialchars($faqItem['question'], ENT_QUOTES); ?>
                                        <i class="fa fa-plus pull-right"></i>
                                    </h4>
                                </a>
                            </div>
                            <div id="<?php echo $faqId; ?>" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <p><?php echo $answerText; ?></p>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

            </div>
        </div>
    </div>
    </div>
</section>


<!-----------labs close---------->
<?php if ($uri[0] == '/pmp.htm') { ?>
    <div class="row">
        <div class="col-md-6"> <img class="cirti_img" src="images/critificate_examimg.jpg" alt="critificate_examimg"> </div>
        <div class="col-md-6"> <img class="cirti_img" src="images/chinese_dumps.jpg" alt="critificate_examimg"> </div>
    </div>
<?php } ?>
</div>


<script>
    $('#writtenSlider').multislider({
        continuous: true,
        duration: 10000,
    });
</script>
</div>
