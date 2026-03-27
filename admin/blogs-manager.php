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
    <button class="btn btn-primary btn-sm" onclick="saveBlogs()">Save All</button>
  </div>
  <p style="font-size:.85rem;color:#666;margin-bottom:16px;">
    Manage blog entries shown on the home page and blogs page. The <strong>Slug</strong> is the filename without <code>.html</code> (e.g. <code>knee-pain-physiotherapy-treatment-dhaka</code>).
  </p>
  <div id="blog-list">
    <?php foreach ($blogs as $i => $blog): ?>
    <div class="repeatable-item">
      <div class="item-header">
        <span class="item-number">#<?= $i + 1 ?> — <?= h($blog['title_en'] ?? 'Post') ?></span>
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
  <button type="button" class="add-item-btn" id="add-blog-btn">+ Add Blog Entry</button>
</div>

<script>
function collectBlog(item) {
  const d = {};
  item.querySelectorAll('[name]').forEach(el => { d[el.name] = el.value; });
  return d;
}

async function saveBlogs() {
  const items = document.querySelectorAll('#blog-list .repeatable-item');
  const data = Array.from(items).map(collectBlog);
  const btn = event.target;
  btn.disabled = true; btn.textContent = 'Saving...';
  await saveSection('blogs', data);
  btn.disabled = false; btn.textContent = 'Save All';
}

function newBlogHtml(n) {
  return `<div class="repeatable-item">
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

document.getElementById('add-blog-btn').addEventListener('click', () => {
  const list = document.getElementById('blog-list');
  const n = list.children.length + 1;
  const tmp = document.createElement('div');
  tmp.innerHTML = newBlogHtml(n);
  const item = tmp.firstElementChild;
  list.appendChild(item);
  wireItem(item);
});

function wireItem(item) {
  item.querySelector('[data-remove-item]')?.addEventListener('click', () => {
    if (confirm('Remove this blog entry?')) item.remove();
  });
  const btn = item.querySelector('.blog-banner-btn');
  const inp = item.querySelector('.blog-banner-input');
  if (btn && inp) {
    btn.addEventListener('click', () => inp.click());
    inp.addEventListener('change', async function() {
      if (!this.files[0]) return;
      const preview = item.querySelector('.blog-banner-preview');
      const pathInput = item.querySelector('[name="banner"]');
      const url = await uploadImage(this.files[0], preview);
      if (url && pathInput) pathInput.value = url;
    });
  }
}

document.querySelectorAll('#blog-list .repeatable-item').forEach(wireItem);
</script>

<?php include __DIR__ . '/_layout-bottom.php'; ?>
