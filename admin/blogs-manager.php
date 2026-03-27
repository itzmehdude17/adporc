<?php
require_once __DIR__ . '/auth.php';
admin_require_login();

$pageTitle = 'Blog Manager';
$blogs = read_json('blogs.json') ?: [];

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
    <?php $total = count($blogs); foreach (array_reverse($blogs) as $revIdx => $blog): $serial = $total - $revIdx; ?>
    <div class="repeatable-item" data-serial="<?= $serial ?>">
      <div class="item-header">
        <span class="item-number">#<?= $serial ?> — <?= h($blog['title_en'] ?? 'Post') ?></span>
        <button type="button" class="btn btn-danger btn-sm" data-remove-item>Remove</button>
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
          <label class="form-label">Date <span class="badge">EN</span> <small style="color:#999">e.g. Feb 2, 2026</small></label>
          <input type="text" name="date_en" class="form-control" value="<?= h($blog['date_en'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Date <span class="badge bn">BN</span> <small style="color:#999">Bengali date</small></label>
          <input type="text" name="date_bn" class="form-control" value="<?= h($blog['date_bn'] ?? '') ?>">
        </div>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Slug (URL, no .html)</label>
          <input type="text" name="slug" class="form-control" value="<?= h($blog['slug'] ?? '') ?>" placeholder="blog-post-slug">
        </div>
        <div class="form-group">
          <label class="form-label">Datetime (ISO, for SEO)</label>
          <input type="text" name="datetime" class="form-control" value="<?= h($blog['datetime'] ?? '') ?>" placeholder="2026-02-02T00:00:00+06:00">
        </div>
      </div>
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
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Pagination bar -->
  <div id="pagination-bar" style="display:flex;align-items:center;justify-content:space-between;margin-top:20px;flex-wrap:wrap;gap:8px;">
    <span id="page-info" style="font-size:.85rem;color:#666;"></span>
    <div id="page-buttons" style="display:flex;gap:5px;flex-wrap:wrap;"></div>
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
function collectBlog(item) {
  const d = {};
  item.querySelectorAll('[name]').forEach(el => { d[el.name] = el.value; });
  return d;
}

async function saveBlogs() {
  // Always save in ascending serial (oldest=1 first → index 0 in JSON)
  const items = getAllItems().slice().sort((a, b) =>
    (parseInt(a.dataset.serial) || 0) - (parseInt(b.dataset.serial) || 0)
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
      <button type="button" class="btn btn-danger btn-sm" data-remove-item>Remove</button>
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
      <div class="form-group"><label class="form-label">Date <span class="badge">EN</span></label><input type="text" name="date_en" class="form-control" value=""></div>
      <div class="form-group"><label class="form-label">Date <span class="badge bn">BN</span></label><input type="text" name="date_bn" class="form-control" value=""></div>
    </div>
    <div class="form-grid">
      <div class="form-group"><label class="form-label">Slug</label><input type="text" name="slug" class="form-control" value="" placeholder="blog-post-slug"></div>
      <div class="form-group"><label class="form-label">Datetime (ISO)</label><input type="text" name="datetime" class="form-control" value="" placeholder="2026-01-01T00:00:00+06:00"></div>
    </div>
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

/* ── Wire remove + image upload ─────────────────────────────── */
function wireItem(item) {
  item.querySelector('[data-remove-item]')?.addEventListener('click', () => {
    if (confirm('Remove this blog entry?')) {
      item.remove();
      renderPagination();
    }
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
</script>

<?php include __DIR__ . '/_layout-bottom.php'; ?>
