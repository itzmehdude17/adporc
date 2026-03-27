<?php
require_once __DIR__ . '/auth.php';
admin_require_login();

$pageTitle = 'Blog Manager';
$blogs = read_json('blogs.json') ?: [];

// Load views from api/views.json
$viewsFile = dirname(__DIR__) . '/api/views.json';
$views = [];
if (is_file($viewsFile)) {
    $raw = file_get_contents($viewsFile);
    $decoded = json_decode($raw, true);
    if (is_array($decoded)) $views = $decoded;
}

include __DIR__ . '/_layout-top.php';
?>

<div class="card">
  <div class="card-header">
    <h2>📝 Blog Posts</h2>
    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
      <select id="sort-select" class="form-control" style="width:auto;padding:5px 10px;font-size:.85rem;" onchange="applySortAndPage()">
        <option value="newest">Newest First</option>
        <option value="oldest">Oldest First</option>
      </select>
      <button class="btn btn-primary btn-sm" onclick="saveBlogs()">Save All</button>
    </div>
  </div>
  <p style="font-size:.85rem;color:#666;margin-bottom:16px;">
    Manage blog entries shown on the home page and blogs page. The <strong>Slug</strong> is the filename without <code>.html</code> (e.g. <code>knee-pain-physiotherapy-treatment-dhaka</code>).
  </p>
  <button type="button" class="add-item-btn" id="add-blog-btn" style="margin-bottom:16px;">+ Add Blog Entry</button>
  <div id="blog-list">
    <?php $total = count($blogs); foreach ($blogs as $idx => $blog): $serial = $total - $idx; ?>
    <div class="repeatable-item" data-serial="<?= $serial ?>">
      <div class="item-header">
        <span class="item-number">#<?= $serial ?> — <?= h($blog['title_en'] ?? 'Post') ?></span>
        <div style="display:flex;gap:6px;">
          <button type="button" class="btn btn-success btn-sm" data-save-item>Save</button>
          <button type="button" class="btn btn-danger-light btn-sm" data-remove-item>Remove</button>
        </div>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Title <span class="badge">EN</span></label>
          <input type="text" name="title_en" class="form-control" value="<?= h($blog['title_en'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Title <span class="badge bn">BN</span></label>
          <input type="text" name="title_bn" class="form-control" value="<?= h($blog['title_bn'] ?? '') ?>">
        </div>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Excerpt / Summary <span class="badge">EN</span></label>
          <textarea name="excerpt_en" class="form-control" rows="2"><?= h($blog['excerpt_en'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Excerpt / Summary <span class="badge bn">BN</span></label>
          <textarea name="excerpt_bn" class="form-control" rows="2"><?= h($blog['excerpt_bn'] ?? '') ?></textarea>
        </div>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Published Date</label>
          <input type="date" name="date" class="form-control" value="<?= h($blog['datetime'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Slug (URL, no .html)</label>
          <input type="text" name="slug" class="form-control" value="<?= h($blog['slug'] ?? '') ?>" placeholder="blog-post-slug">
        </div>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Banner Image</label>
          <div class="image-upload-group">
            <img src="<?= h($blog['banner'] ?? '') ?>" class="image-preview blog-banner-preview">
            <div class="image-upload-controls">
              <input type="hidden" name="banner" value="<?= h($blog['banner'] ?? '') ?>">
              <input type="file" class="blog-banner-input" accept="image/*" style="display:none">
              <button type="button" class="btn-upload blog-banner-btn">Upload Banner</button>
              <p style="font-size:.75rem;color:#999;margin-top:4px;">Recommended: 800×450px</p>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Initial Views</label>
          <input type="number" min="0" name="views" class="form-control" value="<?= (int)($views['/blogs/' . ($blog['slug'] ?? '')] ?? 0) ?>">
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Pagination bar -->
  <div id="pagination-bar" style="display:flex;align-items:center;justify-content:space-between;margin-top:20px;flex-wrap:wrap;gap:8px;">
    <span id="page-info" style="font-size:.85rem;color:#666;"></span>
    <div id="page-buttons" style="display:flex;gap:5px;flex-wrap:wrap;"></div>
  </div>

</div>

<!-- Blog Views Editor -->
<div class="card" style="margin-top:24px;">
  <div class="card-header">
    <h2>👁️ Blog View Counts</h2>
    <button class="btn btn-primary btn-sm" onclick="saveViews()">Save Views</button>
  </div>
  <p style="font-size:.85rem;color:#666;margin-bottom:16px;">
    Set the initial view count for each blog. Views will increment automatically when visitors open the blog page.
  </p>
  <div id="views-list">
    <?php foreach ($blogs as $blog):
      $slug = '/blogs/' . ($blog['slug'] ?? '');
      $count = $views[$slug] ?? 0;
    ?>
    <div class="views-row" style="display:flex;align-items:center;gap:12px;padding:8px 0;border-bottom:1px solid #eee;">
      <input type="number" min="0" class="form-control" style="width:100px;flex-shrink:0;" 
        data-view-slug="<?= h($slug) ?>" value="<?= (int)$count ?>">
      <span style="font-size:.85rem;color:#333;"><?= h($blog['title_en'] ?? $blog['slug'] ?? 'Untitled') ?></span>
      <span style="font-size:.75rem;color:#999;margin-left:auto;white-space:nowrap;"><?= h($slug) ?></span>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<script>
const ITEMS_PER_PAGE = 5;
let currentPage = 1;

function getAllItems() {
  return Array.from(document.querySelectorAll('#blog-list .repeatable-item'));
}

/* ── Sort ─────────────────────────────── */
function applySortAndPage() {
  const sort = document.getElementById('sort-select').value;
  const list = document.getElementById('blog-list');
  const items = getAllItems();
  items.sort((a, b) => {
    const sa = parseInt(a.dataset.serial) || 0;
    const sb = parseInt(b.dataset.serial) || 0;
    return sort === 'oldest' ? sa - sb : sb - sa;
  });
  items.forEach(item => list.appendChild(item));
  currentPage = 1;
  renderPagination();
}

/* ── Pagination ─────────────────────────────── */
function pageRange(cur, total) {
  if (total <= 7) return Array.from({length: total}, (_, i) => i + 1);
  if (cur <= 4)          return [1,2,3,4,5,'…',total];
  if (cur >= total - 3)  return [1,'…',total-4,total-3,total-2,total-1,total];
  return [1,'…',cur-1,cur,cur+1,'…',total];
}

function renderPagination() {
  const items  = getAllItems();
  const total  = items.length;
  const pages  = Math.max(1, Math.ceil(total / ITEMS_PER_PAGE));
  if (currentPage > pages) currentPage = pages;
  const start  = (currentPage - 1) * ITEMS_PER_PAGE;
  const end    = start + ITEMS_PER_PAGE;

  items.forEach((item, i) => {
    item.style.display = (i >= start && i < end) ? '' : 'none';
  });

  const bar  = document.getElementById('pagination-bar');
  const info = document.getElementById('page-info');
  const btns = document.getElementById('page-buttons');

  info.textContent = `Showing ${total ? start+1 : 0}–${Math.min(end,total)} of ${total} posts`;
  btns.innerHTML   = '';
  bar.style.display = pages <= 1 ? 'none' : 'flex';

  const mk = (label, page, active, disabled) => {
    const b = document.createElement('button');
    b.type = 'button';
    b.textContent = label;
    b.className = 'btn btn-sm' + (active ? ' btn-primary' : '');
    b.disabled = disabled;
    b.style.minWidth = '34px';
    if (!disabled) b.onclick = () => { currentPage = page; renderPagination(); };
    return b;
  };

  btns.appendChild(mk('←', currentPage - 1, false, currentPage === 1));
  pageRange(currentPage, pages).forEach(p => {
    if (p === '…') {
      const s = document.createElement('span');
      s.textContent = '…'; s.style.cssText = 'padding:0 4px;line-height:30px;color:#999;';
      btns.appendChild(s);
    } else {
      btns.appendChild(mk(p, p, p === currentPage, false));
    }
  });
  btns.appendChild(mk('→', currentPage + 1, false, currentPage === pages));
}

/* ── Collect & Save ─────────────────────────────── */
/* ── Date formatting helpers ──────────────────────────── */
const MONTHS_EN = ['January','February','March','April','May','June','July','August','September','October','November','December'];
const MONTHS_BN = ['জানুয়ারি','ফেব্রুয়ারি','মার্চ','এপ্রিল','মে','জুন','জুলাই','আগস্ট','সেপ্টেম্বর','অক্টোবর','নভেম্বর','ডিসেম্বর'];
const BN_DIGITS = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
const BN_ORDINALS = {1:'লা',2:'রা',3:'রা',4:'ঠা',5:'ই',6:'ই',7:'ই',8:'ই',9:'ই',10:'ই',11:'ই',12:'ই',13:'ই',14:'ই',15:'ই',16:'ই',17:'ই',18:'ই',19:'শে',20:'শে',21:'শে',22:'শে',23:'শে',24:'শে',25:'শে',26:'শে',27:'শে',28:'শে',29:'শে',30:'শে',31:'শে'};

function toBnDigits(n) { return String(n).split('').map(c => BN_DIGITS[c] || c).join(''); }

function formatDateEn(iso) {
  if (!iso) return '';
  const [y, m, d] = iso.split('-').map(Number);
  return `${MONTHS_EN[m - 1]} ${d}, ${y}`;
}
function formatDateBn(iso) {
  if (!iso) return '';
  const [y, m, d] = iso.split('-').map(Number);
  return `${toBnDigits(d)}${BN_ORDINALS[d] || 'শে'} ${MONTHS_BN[m - 1]}, ${toBnDigits(y)} খ্রি.`;
}

function collectBlog(item) {
  const d = {};
  item.querySelectorAll('[name]').forEach(el => { d[el.name] = el.value; });
  // Auto-generate date fields from the single date picker
  const iso = d.date || '';
  d.datetime = iso;
  d.date_en  = formatDateEn(iso);
  d.date_bn  = formatDateBn(iso);
  delete d.date;
  return d;
}

async function saveBlogs() {
  // Save in descending serial (newest first → matches blogs.json format)
  const items = getAllItems().slice().sort((a, b) =>
    (parseInt(b.dataset.serial) || 0) - (parseInt(a.dataset.serial) || 0)
  );
  const data = items.map(collectBlog);
  const btn = event.target;
  btn.disabled = true; btn.textContent = 'Saving…';
  await saveSection('blogs', data);
  btn.disabled = false; btn.textContent = 'Save All';
}

/* ── New blog HTML template ─────────────────────────────── */
function newBlogHtml(n) {
  return `<div class="repeatable-item" data-serial="${n}">
    <div class="item-header">
      <span class="item-number">#${n} — New Post</span>
      <div style="display:flex;gap:6px;">
        <button type="button" class="btn btn-success btn-sm" data-save-item>Save</button>
        <button type="button" class="btn btn-danger-light btn-sm" data-remove-item>Remove</button>
      </div>
    </div>
    <div class="form-grid">
      <div class="form-group"><label class="form-label">Title <span class="badge">EN</span></label><input type="text" name="title_en" class="form-control" value=""></div>
      <div class="form-group"><label class="form-label">Title <span class="badge bn">BN</span></label><input type="text" name="title_bn" class="form-control" value=""></div>
    </div>
    <div class="form-grid">
      <div class="form-group"><label class="form-label">Excerpt <span class="badge">EN</span></label><textarea name="excerpt_en" class="form-control" rows="2"></textarea></div>
      <div class="form-group"><label class="form-label">Excerpt <span class="badge bn">BN</span></label><textarea name="excerpt_bn" class="form-control" rows="2"></textarea></div>
    </div>
    <div class="form-grid">
      <div class="form-group"><label class="form-label">Published Date</label><input type="date" name="date" class="form-control" value=""></div>
      <div class="form-group"><label class="form-label">Slug</label><input type="text" name="slug" class="form-control" value="" placeholder="blog-post-slug"></div>
    </div>
    <div class="form-grid">
      <div class="form-group">
        <label class="form-label">Banner Image</label>
        <div class="image-upload-group">
          <img src="" class="image-preview blog-banner-preview" style="background:#eee">
          <div class="image-upload-controls">
            <input type="hidden" name="banner" value="">
            <input type="file" class="blog-banner-input" accept="image/*" style="display:none">
            <button type="button" class="btn-upload blog-banner-btn">Upload Banner</button>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Initial Views</label>
        <input type="number" min="0" name="views" class="form-control" value="0">
      </div>
    </div>
  </div>`;
}

/* ── Add blog ─────────────────────────────── */
document.getElementById('add-blog-btn').addEventListener('click', () => {
  const list      = document.getElementById('blog-list');
  const allItems  = getAllItems();
  const n         = allItems.length + 1;
  const tmp       = document.createElement('div');
  tmp.innerHTML   = newBlogHtml(n);
  const item      = tmp.firstElementChild;
  const sort      = document.getElementById('sort-select').value;

  if (sort === 'oldest') {
    list.appendChild(item);
    currentPage = Math.ceil(n / ITEMS_PER_PAGE); // jump to last page
  } else {
    list.prepend(item);
    currentPage = 1; // jump to first page
  }
  wireItem(item);
  renderPagination();
});

/* ── Wire remove + save + image upload ─────────────────────────────── */
function wireItem(item) {
  item.querySelector('[data-remove-item]')?.addEventListener('click', () => {
    if (confirm('Remove this blog entry?')) {
      item.remove();
      renderPagination();
    }
  });
  item.querySelector('[data-save-item]')?.addEventListener('click', async function() {
    const serial = parseInt(item.dataset.serial) || 0;
    const data = collectBlog(item);
    data.serial = serial;
    this.disabled = true; this.textContent = 'Saving…';
    try {
      const res = await fetch('/admin/save.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': getCsrfToken() },
        body: JSON.stringify({ section: 'blog_single', data })
      });
      const json = await res.json();
      if (json.ok) showToast(json.message, 'success');
      else showToast(json.error || 'Save failed.', 'error');
    } catch (e) { showToast('Network error.', 'error'); }
    this.disabled = false; this.textContent = 'Save';
  });
  const btn = item.querySelector('.blog-banner-btn');
  const inp = item.querySelector('.blog-banner-input');
  if (btn && inp) {
    btn.addEventListener('click', () => inp.click());
    inp.addEventListener('change', async function() {
      if (!this.files[0]) return;
      const preview   = item.querySelector('.blog-banner-preview');
      const pathInput = item.querySelector('[name="banner"]');
      const url = await uploadImage(this.files[0], preview);
      if (url && pathInput) pathInput.value = url;
    });
  }
}

document.querySelectorAll('#blog-list .repeatable-item').forEach(wireItem);
renderPagination(); // init on page load

/* ── Save Blog Views ─────────────────────────────── */
async function saveViews() {
  const inputs = document.querySelectorAll('[data-view-slug]');
  const data = {};
  inputs.forEach(el => {
    const slug = el.getAttribute('data-view-slug');
    data[slug] = parseInt(el.value) || 0;
  });

  const btn = event.target;
  btn.disabled = true;
  btn.textContent = 'Saving…';

  try {
    const res = await fetch('/admin/save.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': getCsrfToken()
      },
      body: JSON.stringify({ section: 'blog_views', data: data })
    });
    const json = await res.json();
    if (json.ok) showToast('Views saved!', 'success');
    else showToast(json.error || 'Save failed.', 'error');
  } catch (e) {
    showToast('Network error.', 'error');
  }

  btn.disabled = false;
  btn.textContent = 'Save Views';
}
</script>

<?php include __DIR__ . '/_layout-bottom.php'; ?>
