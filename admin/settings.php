<?php
require_once __DIR__ . '/auth.php';
admin_require_login();

$pageTitle = 'Site Settings';
$site = read_json('site.json') ?: [];

include __DIR__ . '/_layout-top.php';
?>

<div class="card">
  <div class="card-header">
    <h2>🏥 Clinic Information</h2>
    <button class="btn btn-primary btn-sm" onclick="saveSiteSection('clinic')">Save</button>
  </div>
  <div id="section-clinic">
    <div class="form-grid">
      <div class="form-group">
        <label class="form-label">Clinic Short Name</label>
        <input type="text" class="form-control" name="name" value="<?= h($site['clinic']['name'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label class="form-label">Full Name</label>
        <input type="text" class="form-control" name="full_name" value="<?= h($site['clinic']['full_name'] ?? '') ?>">
      </div>
    </div>
    <div class="form-grid">
      <div class="form-group">
        <label class="form-label">Address <span class="badge">EN</span></label>
        <input type="text" class="form-control" name="address_en" value="<?= h($site['clinic']['address_en'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label class="form-label">Address <span class="badge bn">BN</span></label>
        <input type="text" class="form-control" name="address_bn" value="<?= h($site['clinic']['address_bn'] ?? '') ?>">
      </div>
    </div>
    <div class="form-grid">
      <div class="form-group">
        <label class="form-label">Phone (Display)</label>
        <input type="text" class="form-control" name="phone" value="<?= h($site['clinic']['phone'] ?? '') ?>" placeholder="01950-935236">
      </div>
      <div class="form-group">
        <label class="form-label">Phone (Full with country code)</label>
        <input type="text" class="form-control" name="phone_full" value="<?= h($site['clinic']['phone_full'] ?? '') ?>" placeholder="+8801950935236">
      </div>
    </div>
    <div class="form-grid">
      <div class="form-group">
        <label class="form-label">Google Maps URL (Header)</label>
        <input type="url" class="form-control" name="maps_url" value="<?= h($site['clinic']['maps_url'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label class="form-label">Google Maps URL (Footer)</label>
        <input type="url" class="form-control" name="maps_url_footer" value="<?= h($site['clinic']['maps_url_footer'] ?? '') ?>">
      </div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h2>🕐 Schedule</h2>
    <button class="btn btn-primary btn-sm" onclick="saveSiteSection('schedule')">Save</button>
  </div>
  <div id="section-schedule">
    <div class="form-group">
      <label class="form-label">Schedule Text <span class="badge">EN</span> <small style="color:#999">(HTML ok, use &lt;br&gt; for line breaks)</small></label>
      <textarea class="form-control" name="en" rows="3"><?= h($site['schedule']['en'] ?? '') ?></textarea>
    </div>
    <div class="form-group">
      <label class="form-label">Schedule Text <span class="badge bn">BN</span></label>
      <textarea class="form-control" name="bn" rows="3"><?= h($site['schedule']['bn'] ?? '') ?></textarea>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h2>🔗 Social Media Links</h2>
    <button class="btn btn-primary btn-sm" onclick="saveSiteSection('social')">Save</button>
  </div>
  <div id="section-social">
    <div class="form-grid">
      <div class="form-group">
        <label class="form-label">Facebook</label>
        <input type="url" class="form-control" name="facebook" value="<?= h($site['social']['facebook'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label class="form-label">YouTube</label>
        <input type="url" class="form-control" name="youtube" value="<?= h($site['social']['youtube'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label class="form-label">LinkedIn</label>
        <input type="url" class="form-control" name="linkedin" value="<?= h($site['social']['linkedin'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label class="form-label">WhatsApp Link</label>
        <input type="url" class="form-control" name="whatsapp" value="<?= h($site['social']['whatsapp'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label class="form-label">Messenger Link</label>
        <input type="url" class="form-control" name="messenger" value="<?= h($site['social']['messenger'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label class="form-label">Instagram (optional)</label>
        <input type="url" class="form-control" name="instagram" value="<?= h($site['social']['instagram'] ?? '') ?>">
      </div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h2>📊 Analytics</h2>
    <button class="btn btn-primary btn-sm" onclick="saveSiteSection('analytics')">Save</button>
  </div>
  <div id="section-analytics">
    <div class="form-grid">
      <div class="form-group">
        <label class="form-label">Google Analytics ID</label>
        <input type="text" class="form-control" name="gtag_id" value="<?= h($site['analytics']['gtag_id'] ?? '') ?>" placeholder="G-XXXXXXXXXX">
      </div>
      <div class="form-group">
        <label class="form-label">Microsoft Clarity ID</label>
        <input type="text" class="form-control" name="clarity_id" value="<?= h($site['analytics']['clarity_id'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label class="form-label">Google Tag Manager ID</label>
        <input type="text" class="form-control" name="gtm_id" value="<?= h($site['analytics']['gtm_id'] ?? '') ?>" placeholder="GTM-XXXXXXX">
      </div>
      <div class="form-group">
        <label class="form-label">Google Sheet URL (for appointment form)</label>
        <input type="url" class="form-control" name="google_sheet_url" value="<?= h($site['google_sheet_url'] ?? '') ?>" placeholder="https://script.google.com/...">
      </div>
    </div>
    <p style="font-size:.8rem;color:#999;margin-top:4px;">The Google Sheet URL is pasted into the form action. This is the Apps Script web app URL that receives form submissions.</p>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h2>📄 Footer Text</h2>
    <button class="btn btn-primary btn-sm" onclick="saveSiteSection('footer')">Save</button>
  </div>
  <div id="section-footer">
    <div class="form-group">
      <label class="form-label">Footer Description <span class="badge">EN</span></label>
      <textarea class="form-control" name="text_en" rows="3"><?= h($site['footer']['text_en'] ?? '') ?></textarea>
    </div>
    <div class="form-group">
      <label class="form-label">Footer Description <span class="badge bn">BN</span></label>
      <textarea class="form-control" name="text_bn" rows="3"><?= h($site['footer']['text_bn'] ?? '') ?></textarea>
    </div>
  </div>
</div>

<script>
async function saveSiteSection(sectionKey) {
  const container = document.getElementById('section-' + sectionKey);
  const inputs = container.querySelectorAll('[name]');
  const data = {};
  inputs.forEach(el => { data[el.name] = el.value.trim(); });

  // Load current site.json data (read from hidden storage) and merge
  const btn = event.target;
  btn.disabled = true;
  btn.textContent = 'Saving...';

  const ok = await saveSection('site_' + sectionKey + '_partial', data);
  // Actually we need to save the whole site.json merging
  // Use a special merge endpoint instead
  await saveSitePartial(sectionKey, data);

  btn.disabled = false;
  btn.textContent = 'Save';
}

async function saveSitePartial(key, data) {
  try {
    const res = await fetch('/admin/save.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': getCsrfToken() },
      body: JSON.stringify({ section: 'site', data: collectAllSiteData() })
    });
    const json = await res.json();
    if (json.ok) showToast('Saved successfully!', 'success');
    else showToast(json.error || 'Save failed.', 'error');
  } catch(e) {
    showToast('Network error.', 'error');
  }
}

function collectAllSiteData() {
  const sections = ['clinic','schedule','social','analytics','footer'];
  const result = {};
  sections.forEach(sec => {
    const container = document.getElementById('section-' + sec);
    if (!container) return;
    const inputs = container.querySelectorAll('[name]');
    const sectionData = {};
    inputs.forEach(el => { sectionData[el.name] = el.value.trim(); });
    if (sec === 'google_sheet_url') {
      result.google_sheet_url = sectionData.google_sheet_url || '';
    } else {
      result[sec] = sectionData;
    }
  });
  // Handle google_sheet_url from analytics section
  const urlInput = document.querySelector('#section-analytics [name="google_sheet_url"]');
  if (urlInput) result.google_sheet_url = urlInput.value.trim();
  // Remove it from analytics sub obj
  if (result.analytics) delete result.analytics.google_sheet_url;
  return result;
}

// Override saveSiteSection to work correctly
async function saveSiteSection(sectionKey) {
  const btn = event.target;
  btn.disabled = true;
  btn.textContent = 'Saving...';
  
  try {
    const res = await fetch('/admin/save.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': getCsrfToken() },
      body: JSON.stringify({ section: 'site', data: collectAllSiteData() })
    });
    const json = await res.json();
    if (json.ok) showToast('Saved successfully!', 'success');
    else showToast(json.error || 'Save failed.', 'error');
  } catch(e) {
    showToast('Network error.', 'error');
  }
  
  btn.disabled = false;
  btn.textContent = 'Save';
}
</script>

<?php include __DIR__ . '/_layout-bottom.php'; ?>
