<?php
include("includes/config/classDbConnection.php");
$configConn = new classDbConnection();

$limit  = 3;
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$isAjax = isset($_GET['ajax']) && $_GET['ajax'] == 1;

/* ----------------------------------------
   Fetch vendors (paged)
----------------------------------------- */
$q = $configConn->ExecQuery("
    SELECT ven_id, ven_name, youtube_links
    FROM tbl_vendors
    WHERE youtube_links IS NOT NULL
      AND youtube_links != ''
      AND youtube_links != '[]'
    ORDER BY ven_name ASC
    LIMIT $limit OFFSET $offset
");

/* ----------------------------------------
   Helpers
----------------------------------------- */
function getYoutubeId($url)
{
    $url = str_replace('\/', '/', $url);
    if (preg_match('~youtu\.be/([^?&]+)~', $url, $m)) return $m[1];
    if (preg_match('~v=([^&]+)~', $url, $m)) return $m[1];
    if (preg_match('~/embed/([^?&]+)~', $url, $m)) return $m[1];
    return null;
}

/* ----------------------------------------
   Vendor HTML renderer (reusable)
   Note: outputs only the inner snippets for vendors
----------------------------------------- */
function renderVendors($q)
{
    while ($row = mysql_fetch_assoc($q)) :
        $links = json_decode($row['youtube_links'], true);
        if (empty($links)) continue;
    ?>
      <div class="vendor-section" style="padding:16px 0;border-bottom:1px solid #eee;">
        <h3 class="text-center" style="margin-bottom:12px; font-size:2.4rem;"><?= htmlspecialchars($row['ven_name']); ?></h3>
        <div class="row">
          <?php foreach ($links as $link):
              $id = getYoutubeId($link);
              if (!$id) continue;
              $embed = "https://www.youtube.com/embed/$id";
              $thumb = "https://img.youtube.com/vi/$id/hqdefault.jpg";
          ?>
            <div class="col-12 col-sm-6 col-md-4" style="margin-bottom:14px;">
              <div class="video-wrap" style="position:relative;padding-top:56.25%;cursor:pointer;">
                <div class="yt-thumb" data-embed="<?= htmlspecialchars($embed); ?>" aria-role="button" tabindex="0"
                     style="position:absolute;inset:0;display:block;">
                  <img src="<?= $thumb ?>" loading="lazy" alt="video thumbnail"
                       style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;border-radius:6px;">
                  <span style="
                    position:absolute;top:50%;left:50%;
                    transform:translate(-50%,-50%);
                    font-size:40px;color:#fff;text-shadow:0 2px 8px rgba(0,0,0,.6);
                  ">▶</span>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php
    endwhile;
}

/* ----------------------------------------
   AJAX REQUEST → return HTML only
----------------------------------------- */
if ($isAjax) {
    renderVendors($q);
    exit;
}

/* ----------------------------------------
   NORMAL PAGE LOAD
----------------------------------------- */
include("includes/header.php");
?>

<!-- inline minimal mobile-friendly styles (or move to your css) -->
<style>
  /* ensure mobile viewport meta exists in header.php: <meta name="viewport" content="width=device-width,initial-scale=1"> */
  .video-wrap img, .video-wrap iframe {
    border-radius: 6px;
  }
  #loader { color:#555; padding:14px 0; }
  /* make touch targets more forgiving */
  .yt-thumb { -webkit-tap-highlight-color: rgba(0,0,0,0.1); }
</style>

<div class="content-box">
  <div class="exam-group-box tagspage">
    <div class="container" >

      <div class="main_heading text-center mb-00" style="padding-top:30px">
        All <span>Dumps Videos</span>
      </div>

      <div id="vendors-container">
        <?php renderVendors($q); ?>
      </div>

      <div id="loader" style="display:none;text-align:center;margin:14px 0;">
        Loading more videos…
      </div>

      <!-- sentinel observed by IntersectionObserver -->
      <div id="scroll-sentinel" style="height:1px;"></div>

    </div>
  </div>
</div>

<script>
(function () {
  const limit = <?php echo (int)$limit; ?>;
  let offset = limit; // already loaded first page
  let loading = false;
  let finished = false;

  const loaderEl = document.getElementById('loader');
  const container = document.getElementById('vendors-container');
  const sentinel = document.getElementById('scroll-sentinel');

  // robust fetch path: same file, works with rewrites
  function ajaxUrl(off) {
    return window.location.pathname + '?ajax=1&offset=' + off;
  }

  function showLoader(show) {
    loaderEl.style.display = show ? 'block' : 'none';
  }

  // load more vendor HTML
  async function loadMore() {
    if (loading || finished) return;
    loading = true;
    showLoader(true);

    try {
      const res = await fetch(ajaxUrl(offset), { method: 'GET', credentials: 'same-origin' });
      if (!res.ok) throw new Error('HTTP ' + res.status);
      const html = await res.text();
      if (html.trim() === '') {
        finished = true;
        observer.disconnect && observer.disconnect();
      } else {
        container.insertAdjacentHTML('beforeend', html);
        offset += limit;
        // small delay to allow layout to settle on low-end devices
        await new Promise(r => setTimeout(r, 120));
      }
    } catch (err) {
      console.error('Failed to load more vendors:', err);
      // on transient network failures, don't mark finished — allow retry
    } finally {
      loading = false;
      showLoader(false);
    }
  }

  // IntersectionObserver for sentinel
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting && !loading && !finished) {
        loadMore();
      }
    });
  }, {
    root: null,
    rootMargin: '300px', // start loading before hitting bottom
    threshold: 0
  });

  observer.observe(sentinel);

  // Click / keyboard (enter/space) to load iframe inside a thumbnail (touch-friendly)
  document.addEventListener('click', function (e) {
    const t = e.target.closest('.yt-thumb');
    if (!t) return;
    const embed = t.dataset.embed;
    if (!embed) return;

    t.innerHTML = `<iframe src="${embed}?autoplay=1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="position:absolute;inset:0;width:100%;height:100%;border:0;border-radius:6px;"></iframe>`;
  }, { passive: true });

  // keyboard accessibility: Enter/Space loads video
  document.addEventListener('keydown', function (e) {
    if (e.key !== 'Enter' && e.key !== ' ') return;
    const focused = document.activeElement;
    if (!focused || !focused.classList.contains('yt-thumb')) return;
    focused.click();
    e.preventDefault();
  });

})();
</script>

<?php include("includes/footer.php"); ?>
