<?php
include("includes/config/classDbConnection.php");

// Create connection object
$configConn = new classDbConnection();

// Fetch all vendors (only need demo_json and name)
$q = $configConn->ExecQuery("SELECT ven_id, ven_name, demo_json FROM tbl_vendors ORDER BY ven_name ASC");

// ---------- Helpers (PHP 5.6 safe) ----------
function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

// Normalize slashes from JSON
function unslash_url($url) { return str_replace('\/', '/', $url); }

// Is a YouTube URL?
function isYoutubeUrl($url) {
    $u = unslash_url($url);
    return (bool)preg_match('~(youtube\.com|youtu\.be)~i', $u);
}

// Convert YouTube to embed
function getYoutubeEmbedUrl($url) {
    $url = unslash_url($url);

    if (preg_match('~youtu\.be/([^\?&/]+)~i', $url, $m)) {
        return "https://www.youtube.com/embed/" . $m[1];
    }
    if (preg_match('~[?&]v=([^\&]+)~i', $url, $m)) {
        return "https://www.youtube.com/embed/" . $m[1];
    }
    if (preg_match('~/embed/([^\?&/]+)~i', $url, $m)) {
        return "https://www.youtube.com/embed/" . $m[1];
    }
    return $url;
}

// Light MIME guess by extension
function guess_mime_from_path($path) {
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    $map = array(
        'jpg'=>'image/jpeg','jpeg'=>'image/jpeg','png'=>'image/png','gif'=>'image/gif','webp'=>'image/webp',
        'pdf'=>'application/pdf'
    );
    return isset($map[$ext]) ? $map[$ext] : 'application/octet-stream';
}
function is_image_mime($mime) { return strpos($mime, 'image/') === 0; }
function is_pdf_mime($mime)   { return $mime === 'application/pdf'; }

// ---------- First pass: do we have any attachments across all vendors? ----------
$hasAttachments = false;
while ($row_check = mysql_fetch_assoc($q)) {
    if (!empty($row_check['demo_json'])) {
        $att = json_decode($row_check['demo_json'], true);
        if (is_array($att) && !empty($att)) { $hasAttachments = true; break; }
    }
}
// Reset pointer for main loop
mysql_data_seek($q, 0);

include("includes/header.php");
?>

<div class="content-box ">
  <div class="exam-group-box tagspage paddtop60 paddbottom60">
    <div class="container">
      <div class="main_heading text-center mb-00">
        All <span>Demos</span>
        <div style="font-size:20px; margin-top:8px; color:#333;">
          If you’d like to watch live exam sessions of any certification, feel free to contact us using the details below.<br>
          <strong>WhatsApp:</strong> <a href="https://wa.me/447591437400" target="_blank" style="color:#007bff; text-decoration:none;">+44 7591 437400</a>
        </div>
      </div>
      <?php if ($hasAttachments): ?>
        <?php while ($row = mysql_fetch_assoc($q)) :
            $attachments = array();
            if (!empty($row['demo_json'])) {
                $tmp = json_decode($row['demo_json'], true);
                if (is_array($tmp)) $attachments = $tmp;
            }
            if (empty($attachments)) continue; // skip vendors without demos
        ?>
          <div class="vendor-section" style="margin-top:40px;">
            <h3 class="text-center" style="margin-bottom:20px;"><?= h($row['ven_name']); ?></h3>

            <div class="row">
              <?php foreach ($attachments as $att):
                  $mode = isset($att['mode']) ? $att['mode'] : '';

                  if ($mode === 'link') {
                      $url  = isset($att['url']) ? unslash_url($att['url']) : '';
                      if ($url === '') continue;

                      // YouTube -> iframe
                      if (isYoutubeUrl($url)) {
                          $embed = getYoutubeEmbedUrl($url); ?>
                          <div class="col-md-4 col-sm-6">
                            <div class="video_study" style="margin-bottom:20px;">
                              <iframe width="100%" height="200" src="<?= h($embed) ?>" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                            </div>
                          </div>
                      <?php
                      } else {
                          // Non-YouTube link: preview image/pdf if possible
                          $mime = guess_mime_from_path($url); ?>
                          <div class="col-md-4 col-sm-6">
                            <div class="video_study" style="margin-bottom:20px; padding:10px; border:1px solid #eee;">
                              <?php if (is_image_mime($mime)): ?>
                                <div style="margin-bottom:8px;">
                                  <img src="<?= h($url) ?>" alt="Attachment Image" style="max-width:100%; height:auto;">
                                </div>
                                <a class="btn btn-primary btn-sm" href="<?= h($url) ?>" target="_blank" rel="noopener">Open Image</a>
                              <?php elseif (is_pdf_mime($mime)): ?>
                                <div style="margin-bottom:8px;">PDF Link</div>
                                <a class="btn btn-primary btn-sm" href="<?= h($url) ?>" target="_blank" rel="noopener">View PDF</a>
                              <?php else: ?>
                                <div style="margin-bottom:8px; word-break:break-all;"><?= h($url) ?></div>
                                <a class="btn btn-default btn-sm" href="<?= h($url) ?>" target="_blank" rel="noopener">Open Link</a>
                              <?php endif; ?>
                            </div>
                          </div>
                      <?php
                      }
                  } elseif ($mode === 'file') {
                      // Uploaded/stored file
                      $path = isset($att['path']) ? $att['path'] : '';
                      if ($path === '') continue;
                      $label = isset($att['file_name']) ? $att['file_name'] : basename($path);
                      $mime  = isset($att['mime']) ? $att['mime'] : guess_mime_from_path($path); ?>
                      <div class="col-md-4 col-sm-6">
                        <div class="video_study" style="margin-bottom:20px; padding:10px; border:1px solid #eee;">
                          <?php if (is_image_mime($mime)): ?>
                            <div style="margin-bottom:8px;">
                              <img src="<?= h($path) ?>" alt="<?= h($label) ?>" style="max-width:100%; height:auto;">
                            </div>
                            <a class="btn btn-primary btn-sm" href="<?= h($path) ?>" target="_blank" rel="noopener">Open Image</a>
                          <?php elseif (is_pdf_mime($mime)): ?>
                            <div style="margin-bottom:8px;"><?= h($label) ?></div>
                            <a class="btn btn-primary btn-sm" href="<?= h($path) ?>" target="_blank" rel="noopener">View PDF</a>
                          <?php else: ?>
                            <div style="margin-bottom:8px;"><?= h($label) ?></div>
                            <a class="btn btn-default btn-sm" href="<?= h($path) ?>" target="_blank" rel="noopener">Download / Open</a>
                          <?php endif; ?>
                        </div>
                      </div>
                  <?php
                  } // end mode
              endforeach; ?>
            </div>
          </div>
        <?php endwhile; ?>

      <?php else: ?>
        <div class="vendor-section" style="margin-top:40px;">
          <div class="col-md-12 text-center" style="margin-bottom:20px;">
            <div class="video_study" style="padding:20px; border:1px solid #ccc; background:#f9f9f9;">
              Coming Soon
            </div>
          </div>
        </div>
      <?php endif; ?>

    </div>
  </div>
</div>

<?php include("includes/footer.php"); ?>
