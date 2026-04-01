<!-- template 2 | Return Dumps by lab.php -->
<?php
$isCcdeLabRequest = ($_SERVER['REQUEST_URI'] == '/CCDE-Lab.htm');
$fullCourseName = isset($exam['exam_fullname']) ? trim($exam['exam_fullname']) : '';
$nameWords = preg_split('/\s+/', $fullCourseName);
if (count($nameWords) > 2) {
    array_splice($nameWords, -2);
    $courseBaseName = trim(implode(' ', $nameWords));
} else {
    $courseBaseName = $fullCourseName;
}
if ($courseBaseName === '') {
    $courseBaseName = $fullCourseName;
}

$paidPackages = array(
    array(
        'ptype' => '7',
        'title' => trim($courseBaseName . ($isCcdeLabRequest ? ' Written' : ' Real Lab Workbook')),
        'price' => $price,
        'icon' => 'images/new-image/secure_pdf.svg',
        'support' => isset($pieces[0]) ? $pieces[0] : ''
    ),
    array(
        'ptype' => '9',
        'title' => trim($courseBaseName . ($isCcdeLabRequest ? ' Real Lab Workbook + Bootcamp' : ' Real Lab Workbook + Racks + Bootcamp')),
        'price' => $bprice,
        'icon' => 'images/new-image/engine_package.svg',
        'support' => isset($pieces[2]) ? $pieces[2] : ''
    ),
    array(
        'ptype' => '8',
        'title' => trim($courseBaseName . ($isCcdeLabRequest ? ' Real Lab Workbook' : ' Real Lab Workbook + Racks')),
        'price' => $eprice,
        'icon' => 'images/new-image/pdc_icons.svg',
        'support' => isset($pieces[1]) ? $pieces[1] : ''
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
?>
<style>
::root {
    --free-color: #ff133e;
}
.lab-offer-row.lab-offer-free .lab-offer-price {
    color: var(--free-color);
}
.lab-offer-row.lab-offer-free{
    color: var(--free-color);    
}
.lab-offer-row .lab-offer-main .media-body{
    vertical-align: middle !important;  
}
.lab-offer-price {
    padding-right: 10px;
    font-weight: bold;
}
.lab-btn-download{
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
</style>
<li class="main_cert lab-figma-layout">
    <div class="lab-figma-top row">
        <div class="col-md-4 col-sm-12">
            <div class="lab-media-card panel panel-default">
                <div class="panel-body text-center">
                    <div class="lab-media-image">
                        <img class="img-responsive center-block" src="/images/slider/<?= $examID ?>/<?= $exam['course_image'] ?>" alt="<?= htmlspecialchars($fullCourseName); ?>">
                    </div>
                    <div class="lab-verified-chip">
                        <span class="lab-verified-icon"></span>
                        Verified Dumps
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-sm-12">
            <div class="lab-offer-list list-group">
                <form class="hidden" method="post" id="freeDumpFormLab">
                    <input type="hidden" name="download_free_dump" value="1">
                </form>
                <form class="hidden" method="post" id="demoPracticeFormLab">
                    <input type="hidden" name="download_demo_practice" value="1">
                </form>

                <div class="lab-offer-row lab-offer-free list-group-item">
                    <div class="row">
                        <div class="col-sm-8 col-xs-12">
                            <div class="media lab-offer-main">
                                <div class="media-left">
                                    <span class="lab-offer-icon"><img src="images/new-image/demo_pdf2.svg" alt="Demo question"></span>
                                </div>
                                <div class="media-body">
                                    <span class="lab-offer-title"><?= htmlspecialchars($fullCourseName); ?> Demo Question Download</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12 text-right">
                            <div class="lab-offer-side">
                                <span class="lab-offer-price">Free</span>
                                <button class="btn btn-success lab-offer-btn lab-btn-download" type="button"
                                    onclick="<?= $isLoggedIn ? "document.getElementById('freeDumpFormLab').submit();" : "redirectToLogin();" ?>">
                                    Download
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (!empty($freeDumpError)) { ?>
                    <div class="lab-row-error"><?= htmlspecialchars($freeDumpError); ?></div>
                <?php } ?>

                <div class="lab-offer-row lab-offer-free list-group-item">
                    <div class="row">
                        <div class="col-sm-8 col-xs-12">
                            <div class="media lab-offer-main">
                                <div class="media-left">
                                    <span class="lab-offer-icon"><img src="images/new-image/demo_pdf.svg" alt="Demo practice"></span>
                                </div>
                                <div class="media-body">
                                    <span class="lab-offer-title"><?= htmlspecialchars($fullCourseName); ?> Demo Practise Test</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12 text-right">
                            <div class="lab-offer-side">
                                <span class="lab-offer-price">Free</span>
                                <button class="btn btn-success lab-offer-btn lab-btn-download" type="button"
                                    onclick="<?= $isLoggedIn ? "document.getElementById('demoPracticeFormLab').submit();" : "redirectToLogin();" ?>">
                                    Download
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (!empty($demoPracticeError)) { ?>
                    <div class="lab-row-error"><?= htmlspecialchars($demoPracticeError); ?></div>
                <?php } ?>

                <?php foreach ($paidPackages as $package) { ?>
                    <div class="lab-offer-row list-group-item">
                        <div class="row">
                            <div class="col-sm-8 col-xs-12">
                                <div class="media lab-offer-main">
                                    <div class="media-left">
                                        <span class="lab-offer-icon"><img src="<?= $package['icon']; ?>" alt="Package"></span>
                                    </div>
                                    <div class="media-body">
                                        <span class="lab-offer-title"><?= htmlspecialchars($package['title']); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-xs-12 text-right">
                                <div class="lab-offer-side">
                                    <span class="lab-offer-price">$<?= $package['price']; ?></span>
                                    <button class="btn btn-primary lab-offer-btn lab-btn-buy"
                                        value="<?= $package['ptype']; ?>"
                                        name="ptype"
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

    <div class="lab-support-section">
        <h3 class="lab-support-heading text-center bold">Exam Details &amp; Support</h3>
        <div class="row">
            <?php foreach ($paidPackages as $package) { ?>
                <div class="col-md-4 col-sm-6">
                    <div class="lab-support-card panel panel-default">
                        <div class="panel-body">
                            <div class="lab-support-title"><?= htmlspecialchars($package['title']); ?></div>
                            <div class="lab-support-content"><?= $cleanSupportHtml($package['support']); ?></div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</li>

