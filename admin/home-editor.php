<?php
require_once __DIR__ . '/auth.php';
admin_require_login();

$pageTitle = 'Home Page Editor';
$home = read_json('home.json') ?: [];
$hero = $home['hero'] ?? [];
$about = $home['about'] ?? [];
$cta = $home['cta'] ?? [];
$services = $home['services'] ?? [];

include __DIR__ . '/_layout-top.php';
?>

<!-- TABS -->
<div class="tab-buttons">
  <button class="tab-btn active" onclick="showTab('hero',this)">Hero</button>
  <button class="tab-btn" onclick="showTab('services',this)">Services</button>
  <button class="tab-btn" onclick="showTab('about',this)">About</button>
  <button class="tab-btn" onclick="showTab('cta',this)">CTA Banner</button>
</div>

<!-- HERO SECTION -->
<div id="tab-hero" class="tab-pane active">
  <div class="card">
    <div class="card-header">
      <h2>🦸 Hero Section</h2>
      <button class="btn btn-primary btn-sm" onclick="saveHomeSection('home_hero', 'hero-form')">Save Hero</button>
    </div>
    <form id="hero-form" onsubmit="return false;">
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Subtitle <span class="badge">EN</span></label>
          <input type="text" name="subtitle_en" class="form-control" value="<?= h($hero['subtitle_en'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Subtitle <span class="badge bn">BN</span></label>
          <input type="text" name="subtitle_bn" class="form-control" value="<?= h($hero['subtitle_bn'] ?? '') ?>">
        </div>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Main Title <span class="badge">EN</span></label>
          <input type="text" name="title_en" class="form-control" value="<?= h($hero['title_en'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Main Title <span class="badge bn">BN</span></label>
          <input type="text" name="title_bn" class="form-control" value="<?= h($hero['title_bn'] ?? '') ?>">
        </div>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Description <span class="badge">EN</span></label>
          <textarea name="text_en" class="form-control"><?= h($hero['text_en'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Description <span class="badge bn">BN</span></label>
          <textarea name="text_bn" class="form-control"><?= h($hero['text_bn'] ?? '') ?></textarea>
        </div>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Banner Image</label>
          <div class="image-upload-group">
            <img src="<?= h($hero['banner_image'] ?? '/assets/images/hero-banner.png') ?>" class="image-preview" id="hero-banner-preview">
            <div class="image-upload-controls">
              <input type="hidden" name="banner_image" value="<?= h($hero['banner_image'] ?? '') ?>">
              <input type="file" id="hero-banner-input" accept="image/*" style="display:none">
              <button type="button" class="btn-upload" onclick="document.getElementById('hero-banner-input').click()">Upload New Image</button>
              <p style="font-size:.78rem;color:#999;margin-top:6px;">Max 5MB · JPG, PNG, WEBP</p>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Background Image</label>
          <div class="image-upload-group">
            <img src="<?= h($hero['bg_image'] ?? '/assets/images/hero-bg.png') ?>" class="image-preview" id="hero-bg-preview">
            <div class="image-upload-controls">
              <input type="hidden" name="bg_image" value="<?= h($hero['bg_image'] ?? '') ?>">
              <input type="file" id="hero-bg-input" accept="image/*" style="display:none">
              <button type="button" class="btn-upload" onclick="document.getElementById('hero-bg-input').click()">Upload New Image</button>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Banner Alt Text</label>
        <input type="text" name="banner_alt" class="form-control" value="<?= h($hero['banner_alt'] ?? '') ?>">
      </div>
    </form>
  </div>
</div>

<!-- SERVICES SECTION -->
<div id="tab-services" class="tab-pane">
  <div class="card">
    <div class="card-header">
      <h2>🩺 Services</h2>
      <button class="btn btn-primary btn-sm" onclick="saveServices()">Save All Services</button>
    </div>
    <div id="services-list">
      <?php foreach ($services as $i => $svc): ?>
      <div class="repeatable-item" data-idx="<?= $i ?>">
        <div class="item-header">
          <span class="item-number">#<?= $i + 1 ?></span>
          <button type="button" class="btn btn-danger btn-sm" data-remove-item>Remove</button>
        </div>
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">Title <span class="badge">EN</span></label>
            <input type="text" name="title_en" class="form-control" value="<?= h($svc['title_en'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label class="form-label">Title <span class="badge bn">BN</span></label>
            <input type="text" name="title_bn" class="form-control" value="<?= h($svc['title_bn'] ?? '') ?>">
          </div>
        </div>
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">Description <span class="badge">EN</span></label>
            <textarea name="desc_en" class="form-control"><?= h($svc['desc_en'] ?? '') ?></textarea>
          </div>
          <div class="form-group">
            <label class="form-label">Description <span class="badge bn">BN</span></label>
            <textarea name="desc_bn" class="form-control"><?= h($svc['desc_bn'] ?? '') ?></textarea>
          </div>
        </div>
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">Link URL</label>
            <input type="text" name="link" class="form-control" value="<?= h($svc['link'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label class="form-label">Icon Image Path</label>
            <div class="image-upload-group">
              <img src="<?= h($svc['icon'] ?? '') ?>" class="image-preview svc-icon-preview" style="width:50px;height:50px">
              <div class="image-upload-controls">
                <input type="hidden" name="icon" value="<?= h($svc['icon'] ?? '') ?>">
                <input type="file" class="svc-icon-input" accept="image/*" style="display:none">
                <button type="button" class="btn-upload svc-icon-btn">Upload Icon</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <button type="button" class="add-item-btn" onclick="addService()">+ Add Service</button>
  </div>
</div>

<!-- ABOUT SECTION -->
<div id="tab-about" class="tab-pane">
  <div class="card">
    <div class="card-header">
      <h2>ℹ️ About Section</h2>
      <button class="btn btn-primary btn-sm" onclick="saveHomeSection('home_about', 'about-form')">Save About</button>
    </div>
    <form id="about-form" onsubmit="return false;">
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Subtitle <span class="badge">EN</span></label>
          <input type="text" name="subtitle_en" class="form-control" value="<?= h($about['subtitle_en'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Subtitle <span class="badge bn">BN</span></label>
          <input type="text" name="subtitle_bn" class="form-control" value="<?= h($about['subtitle_bn'] ?? '') ?>">
        </div>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Title <span class="badge">EN</span></label>
          <input type="text" name="title_en" class="form-control" value="<?= h($about['title_en'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Title <span class="badge bn">BN</span></label>
          <input type="text" name="title_bn" class="form-control" value="<?= h($about['title_bn'] ?? '') ?>">
        </div>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Text <span class="badge">EN</span></label>
          <textarea name="text_en" class="form-control"><?= h($about['text_en'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Text <span class="badge bn">BN</span></label>
          <textarea name="text_bn" class="form-control"><?= h($about['text_bn'] ?? '') ?></textarea>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Banner Image</label>
        <div class="image-upload-group">
          <img src="<?= h($about['banner'] ?? '') ?>" class="image-preview" id="about-banner-preview">
          <div class="image-upload-controls">
            <input type="hidden" name="banner" value="<?= h($about['banner'] ?? '') ?>">
            <input type="file" id="about-banner-input" accept="image/*" style="display:none">
            <button type="button" class="btn-upload" onclick="document.getElementById('about-banner-input').click()">Upload Image</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- CTA SECTION -->
<div id="tab-cta" class="tab-pane">
  <div class="card">
    <div class="card-header">
      <h2>📢 CTA Banner Section</h2>
      <button class="btn btn-primary btn-sm" onclick="saveHomeSection('home_cta', 'cta-form')">Save CTA</button>
    </div>
    <form id="cta-form" onsubmit="return false;">
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Subtitle <span class="badge">EN</span></label>
          <input type="text" name="subtitle_en" class="form-control" value="<?= h($cta['subtitle_en'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Subtitle <span class="badge bn">BN</span></label>
          <input type="text" name="subtitle_bn" class="form-control" value="<?= h($cta['subtitle_bn'] ?? '') ?>">
        </div>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Text <span class="badge">EN</span></label>
          <textarea name="text_en" class="form-control"><?= h($cta['text_en'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Text <span class="badge bn">BN</span></label>
          <textarea name="text_bn" class="form-control"><?= h($cta['text_bn'] ?? '') ?></textarea>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">CTA Banner Image</label>
        <div class="image-upload-group">
          <img src="<?= h($cta['banner'] ?? '') ?>" class="image-preview" id="cta-banner-preview">
          <div class="image-upload-controls">
            <input type="hidden" name="banner" value="<?= h($cta['banner'] ?? '') ?>">
            <input type="file" id="cta-banner-input" accept="image/*" style="display:none">
            <button type="button" class="btn-upload" onclick="document.getElementById('cta-banner-input').click()">Upload Image</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
function showTab(name, btn) {
  document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.tab-buttons .tab-btn').forEach(b => b.classList.remove('active'));
  document.getElementById('tab-' + name).classList.add('active');
  btn.classList.add('active');
}

async function saveHomeSection(section, formId) {
  const form = document.getElementById(formId);
  const data = {};
  form.querySelectorAll('[name]').forEach(el => { data[el.name] = el.value; });
  const btn = event.target;
  btn.disabled = true; btn.textContent = 'Saving...';
  await saveSection(section, data);
  btn.disabled = false; btn.textContent = btn.textContent.replace('Saving...', 'Save ' + (formId.split('-')[0].charAt(0).toUpperCase() + formId.split('-')[0].slice(1)));
}

function collectService(item) {
  const data = {};
  item.querySelectorAll('[name]').forEach(el => { data[el.name] = el.value; });
  return data;
}

async function saveServices() {
  const items = document.querySelectorAll('#services-list .repeatable-item');
  const data = Array.from(items).map(collectService);
  const btn = event.target;
  btn.disabled = true; btn.textContent = 'Saving...';
  await saveSection('home_services', data);
  btn.disabled = false; btn.textContent = 'Save All Services';
}

function addService() {
  const list = document.getElementById('services-list');
  const n = list.children.length + 1;
  const div = document.createElement('div');
  div.className = 'repeatable-item';
  div.innerHTML = `
    <div class="item-header">
      <span class="item-number">#${n}</span>
      <button type="button" class="btn btn-danger btn-sm" data-remove-item>Remove</button>
    </div>
    <div class="form-grid">
      <div class="form-group"><label class="form-label">Title <span class="badge">EN</span></label><input type="text" name="title_en" class="form-control" value=""></div>
      <div class="form-group"><label class="form-label">Title <span class="badge bn">BN</span></label><input type="text" name="title_bn" class="form-control" value=""></div>
    </div>
    <div class="form-grid">
      <div class="form-group"><label class="form-label">Description <span class="badge">EN</span></label><textarea name="desc_en" class="form-control"></textarea></div>
      <div class="form-group"><label class="form-label">Description <span class="badge bn">BN</span></label><textarea name="desc_bn" class="form-control"></textarea></div>
    </div>
    <div class="form-grid">
      <div class="form-group"><label class="form-label">Link URL</label><input type="text" name="link" class="form-control"></div>
      <div class="form-group"><label class="form-label">Icon Path</label><input type="hidden" name="icon" class="form-control" value=""></div>
    </div>`;
  list.appendChild(div);
  div.querySelector('[data-remove-item]').addEventListener('click', () => {
    if (list.children.length > 1 && confirm('Remove this service?')) div.remove();
  });
}

// Wire remove buttons
document.querySelectorAll('#services-list [data-remove-item]').forEach(btn => {
  btn.addEventListener('click', () => {
    const list = document.getElementById('services-list');
    if (list.children.length > 1 && confirm('Remove this service?')) btn.closest('.repeatable-item').remove();
    else showToast('Cannot remove the last service.','warning');
  });
});

// Image uploads
setupImageUpload('hero-banner-input', 'hero-banner-input', 'hero-banner-preview', null);
document.getElementById('hero-banner-input').addEventListener('change', async function() {
  const url = await uploadImageFile(this.files[0], 'hero-banner-preview');
  if (url) document.querySelector('#hero-form [name="banner_image"]').value = url;
});
document.getElementById('hero-bg-input').addEventListener('change', async function() {
  const url = await uploadImageFile(this.files[0], 'hero-bg-preview');
  if (url) document.querySelector('#hero-form [name="bg_image"]').value = url;
});
document.getElementById('about-banner-input').addEventListener('change', async function() {
  const url = await uploadImageFile(this.files[0], 'about-banner-preview');
  if (url) document.querySelector('#about-form [name="banner"]').value = url;
});
document.getElementById('cta-banner-input').addEventListener('change', async function() {
  const url = await uploadImageFile(this.files[0], 'cta-banner-preview');
  if (url) document.querySelector('#cta-form [name="banner"]').value = url;
});

async function uploadImageFile(file, previewId) {
  if (!file) return null;
  const reader = new FileReader();
  reader.onload = e => { const p = document.getElementById(previewId); if (p) p.src = e.target.result; };
  reader.readAsDataURL(file);
  return await uploadImage(file, document.getElementById(previewId));
}
</script>

<?php include __DIR__ . '/_layout-bottom.php'; ?>
