<?php
require_once __DIR__ . '/auth.php';
admin_require_login();

$pageTitle = 'Page Editor';

$home         = read_json('home.json')          ?: [];
$about_page   = read_json('about.json')         ?: [];
$services_page= read_json('services_page.json') ?: [];

$hero     = $home['hero']     ?? [];
$about    = $home['about']    ?? [];
$cta      = $home['cta']      ?? [];
$services = $home['services'] ?? [];

include __DIR__ . '/_layout-top.php';
?>

<!-- PAGE TABS -->
<div class="tab-buttons">
  <button class="tab-btn active" onclick="showTab('hero',this)">Home Hero</button>
  <button class="tab-btn" onclick="showTab('services',this)">Home Services</button>
  <button class="tab-btn" onclick="showTab('about',this)">Home About</button>
  <button class="tab-btn" onclick="showTab('cta',this)">Home CTA</button>
  <button class="tab-btn" onclick="showTab('about-page',this)">About Us Page</button>
  <button class="tab-btn" onclick="showTab('services-page',this)">Services Page</button>
</div>

<!-- ═══════════════════════════════════════════════
     HOME HERO
═══════════════════════════════════════════════ -->
<div id="tab-hero" class="tab-pane active">
  <div class="card">
    <div class="card-header">
      <h2>🦸 Hero Section</h2>
      <button class="btn btn-primary btn-sm" onclick="saveHomeSection('home_hero','hero-form')">Save Hero</button>
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

<!-- ═══════════════════════════════════════════════
     HOME SERVICES (repeatable cards)
═══════════════════════════════════════════════ -->
<div id="tab-services" class="tab-pane">
  <div class="card">
    <div class="card-header">
      <h2>🩺 Home Services Cards</h2>
      <button class="btn btn-primary btn-sm" onclick="saveServices()">Save All Services</button>
    </div>
    <div class="form-grid" style="margin-bottom:16px;">
      <div class="form-group">
        <label class="form-label">Section Title <span class="badge">EN</span></label>
        <input type="text" id="services_title_en" class="form-control" value="<?= h($home['services_title_en'] ?? 'Advanced Physiotherapy') ?>">
      </div>
      <div class="form-group">
        <label class="form-label">Section Title <span class="badge bn">BN</span></label>
        <input type="text" id="services_title_bn" class="form-control" value="<?= h($home['services_title_bn'] ?? 'উন্নত ফিজিওথেরাপি') ?>">
      </div>
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
            <label class="form-label">Icon Image</label>
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
        <?php if (isset($svc['subtitle_en']) || isset($svc['subtitle_bn'])): ?>
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">Subtitle <span class="badge">EN</span></label>
            <input type="text" name="subtitle_en" class="form-control" value="<?= h($svc['subtitle_en'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label class="form-label">Subtitle <span class="badge bn">BN</span></label>
            <input type="text" name="subtitle_bn" class="form-control" value="<?= h($svc['subtitle_bn'] ?? '') ?>">
          </div>
        </div>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
    <button type="button" class="add-item-btn" onclick="addService()">+ Add Service</button>
  </div>
</div>

<!-- ═══════════════════════════════════════════════
     HOME ABOUT (teaser snippet)
═══════════════════════════════════════════════ -->
<div id="tab-about" class="tab-pane">
  <div class="card">
    <div class="card-header">
      <h2>ℹ️ Home About Snippet</h2>
      <button class="btn btn-primary btn-sm" onclick="saveHomeSection('home_about','about-form')">Save About</button>
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

<!-- ═══════════════════════════════════════════════
     HOME CTA
═══════════════════════════════════════════════ -->
<div id="tab-cta" class="tab-pane">
  <div class="card">
    <div class="card-header">
      <h2>📢 CTA Banner Section</h2>
      <button class="btn btn-primary btn-sm" onclick="saveHomeSection('home_cta','cta-form')">Save CTA</button>
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

<!-- ═══════════════════════════════════════════════
     ABOUT US PAGE
═══════════════════════════════════════════════ -->
<div id="tab-about-page" class="tab-pane">
  <div class="card">
    <div class="card-header">
      <h2>📄 About Us Page</h2>
      <button class="btn btn-primary btn-sm" onclick="saveAboutPage()">Save About Page</button>
    </div>
    <form id="about-page-form" onsubmit="return false;">
      <p style="font-size:.85rem;color:#999;margin-bottom:16px;">Edits the <code>/about-us</code> page content.</p>
      <div class="form-group">
        <label class="form-label">Banner Image</label>
        <div class="image-upload-group">
          <img src="<?= h($about_page['banner'] ?? '/assets/images/banners/banner-6.jpeg') ?>" class="image-preview" id="ap-banner-preview">
          <div class="image-upload-controls">
            <input type="hidden" name="banner" value="<?= h($about_page['banner'] ?? '/assets/images/banners/banner-6.jpeg') ?>">
            <input type="file" id="ap-banner-input" accept="image/*" style="display:none">
            <button type="button" class="btn-upload" onclick="document.getElementById('ap-banner-input').click()">Upload Image</button>
          </div>
        </div>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Subtitle <span class="badge">EN</span></label>
          <input type="text" name="subtitle_en" class="form-control" value="<?= h($about_page['subtitle_en'] ?? 'About Us') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Subtitle <span class="badge bn">BN</span></label>
          <input type="text" name="subtitle_bn" class="form-control" value="<?= h($about_page['subtitle_bn'] ?? 'আমাদের সম্পর্কে') ?>">
        </div>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Title <span class="badge">EN</span></label>
          <input type="text" name="title_en" class="form-control" value="<?= h($about_page['title_en'] ?? 'Asia Digital Physiotherapy and Orthopedic Rehabilitation Center (ADPORC)') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Title <span class="badge bn">BN</span></label>
          <input type="text" name="title_bn" class="form-control" value="<?= h($about_page['title_bn'] ?? 'এশিয়া ডিজিটাল ফিজিওথেরাপি এন্ড অর্থোপেডিক রিহ্যাবিলিটেশন সেন্টার') ?>">
        </div>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Paragraph 1 <span class="badge">EN</span></label>
          <textarea name="text1_en" class="form-control" rows="4"><?= h($about_page['text1_en'] ?? 'Welcome to ADPORC, your trusted destination for certified, patient-centered physiotherapy in Dhaka, Bangladesh. Led by renowned physiotherapist Dr. Saddam Hossain, our center offers expert care for pain relief, mobility restoration, and injury recovery.') ?></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Paragraph 1 <span class="badge bn">BN</span></label>
          <textarea name="text1_bn" class="form-control" rows="4"><?= h($about_page['text1_bn'] ?? 'ADPORC-এ স্বাগতম, যেখানে আপনার সুস্থতার জন্য রয়েছে আধুনিক, প্রমাণভিত্তিক এবং যত্নশীল ফিজিওথেরাপি সেবা। ডা. সাদ্দাম হোসেন-এর নেতৃত্বে আমাদের বিশেষজ্ঞ টিম ঢাকায় সেরা ফিজিওথেরাপি প্রদান করে।') ?></textarea>
        </div>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Paragraph 2 <span class="badge">EN</span></label>
          <textarea name="text2_en" class="form-control" rows="4"><?= h($about_page['text2_en'] ?? "Whether you're searching for the best physiotherapy near Jatrabari, Shonir Akhra, Doyaginj, Mir Hazirbagh, or Puran Dhaka, ADPORC is here to serve you. We're also proud to support patients from Narayanganj and surrounding areas.") ?></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Paragraph 2 <span class="badge bn">BN</span></label>
          <textarea name="text2_bn" class="form-control" rows="4"><?= h($about_page['text2_bn'] ?? 'আপনি যদি ঢাকা, যাত্রাবাড়ী, শনির আখড়া, দয়াগঞ্জ, মীর হাজিরবাগ বা পুরান ঢাকার আশেপাশে সেরা ফিজিওথেরাপি খুঁজে থাকেন, তাহলে ADPORC আপনার জন্য প্রস্তুত।') ?></textarea>
        </div>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Paragraph 3 <span class="badge">EN</span></label>
          <textarea name="text3_en" class="form-control" rows="3"><?= h($about_page['text3_en'] ?? 'Every treatment is delivered by a certified physiotherapist in Dhaka, ensuring safe, effective care tailored to your unique condition. At ADPORC, we don\'t just treat- we care, educate, and empower.') ?></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Paragraph 3 <span class="badge bn">BN</span></label>
          <textarea name="text3_bn" class="form-control" rows="3"><?= h($about_page['text3_bn'] ?? 'আমরা নিশ্চিত করি, প্রতিটি রোগী পান সার্টিফায়েড ফিজিওথেরাপিস্ট দ্বারা পরিচালিত যত্নবান এবং নিরাপদ চিকিৎসা। ADPORC বিশ্বাস করে- সঠিক চিকিৎসা শুরু হয় যত্ন ও বোঝাপথ থেকে।') ?></textarea>
        </div>
      </div>
      <p style="font-size:.82rem;color:#aaa;margin-top:4px;">Note: Branch locations and maps are managed in the JSON file directly.</p>
    </form>
  </div>
</div>

<!-- ═══════════════════════════════════════════════
     SERVICES PAGE
═══════════════════════════════════════════════ -->
<div id="tab-services-page" class="tab-pane">
  <div class="card">
    <div class="card-header">
      <h2>🩺 Services Page</h2>
      <button class="btn btn-primary btn-sm" onclick="saveServicesPage()">Save Services Page</button>
    </div>
    <form id="services-page-form" onsubmit="return false;">
      <p style="font-size:.85rem;color:#999;margin-bottom:16px;">Edits the <code>/services</code> page content.</p>
      <div class="form-group">
        <label class="form-label">Banner Image</label>
        <div class="image-upload-group">
          <img src="<?= h($services_page['banner'] ?? '/assets/images/banners/banner-1.jpg') ?>" class="image-preview" id="sp-banner-preview">
          <div class="image-upload-controls">
            <input type="hidden" name="banner" value="<?= h($services_page['banner'] ?? '/assets/images/banners/banner-1.jpg') ?>">
            <input type="file" id="sp-banner-input" accept="image/*" style="display:none">
            <button type="button" class="btn-upload" onclick="document.getElementById('sp-banner-input').click()">Upload Image</button>
          </div>
        </div>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Page Title <span class="badge">EN</span></label>
          <input type="text" name="title_en" class="form-control" value="<?= h($services_page['title_en'] ?? 'Our Services') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Page Title <span class="badge bn">BN</span></label>
          <input type="text" name="title_bn" class="form-control" value="<?= h($services_page['title_bn'] ?? 'আমাদের সেবা সমূহ') ?>">
        </div>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Conditions List <span class="badge">EN</span> <small style="color:#aaa">(one per line)</small></label>
          <textarea name="conditions_en" class="form-control" rows="16"><?= h(implode("\n", $services_page['conditions_en'] ?? ['Neck pain and stiffness','Low back pain','Knee pain','Ankle and heel pain','Headache and migraine-related neck strain','Shoulder pain and frozen shoulder','Tingling, heaviness, or weakness in hands and feet','Spine problems including cervical and lumbar spondylosis','Disc prolapse / PLID','Slipped disc / Spondylolisthesis','Joint pain (hip, elbow, wrist, ankle)','Stroke and paralysis rehabilitation','Sudden facial paralysis (Bell\'s palsy)','Sports injuries and muscle strain','ACL Injury'])) ?></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Conditions List <span class="badge bn">BN</span> <small style="color:#aaa">(এক লাইনে একটি)</small></label>
          <textarea name="conditions_bn" class="form-control" rows="16"><?= h(implode("\n", $services_page['conditions_bn'] ?? ['ঘাড় ব্যথা ও শক্তভাব','কোমর ব্যথা','হাঁটু ব্যথা','পায়ের গোড়ালির ব্যথা','মাথা ব্যথা ও মাইগ্রেইন-সম্পর্কিত ঘাড়ের টান','কাঁধের ব্যথা ও ফ্রোজেন শোল্ডার','হাত-পা ঝিনঝিন, ভারী বা অবস লাগা','স্পন্ডাইলোসিস সমস্যা (ঘাড় ও কোমরে)','ডিস্ক প্রলাপ্স / পিএলআইডি','স্লিপড ডিস্ক / স্পন্ডিলোলিস্থেসিস','বিভিন্ন জয়েন্টের ব্যথা (হিপ, কনুই, কবজি, গোড়ালি)','হঠাৎ মুখ বেকে যাওয়া (বেলস্‌ পালসি)','স্ট্রোক ও প্যারালাইসিস পুনর্বাসন','খেলাধুলাজনিত আঘাত ও পেশির টান','ACL ইনজুরি'])) ?></textarea>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
// ─── Tab switching ─────────────────────────────────────────
function showTab(name, btn) {
  document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.tab-buttons .tab-btn').forEach(b => b.classList.remove('active'));
  document.getElementById('tab-' + name).classList.add('active');
  btn.classList.add('active');
}

// ─── Generic save helpers ──────────────────────────────────
async function saveHomeSection(section, formId) {
  const form = document.getElementById(formId);
  const data = {};
  form.querySelectorAll('[name]').forEach(el => { data[el.name] = el.value; });
  const btn = event.target;
  btn.disabled = true; btn.textContent = 'Saving…';
  await saveSection(section, data);
  btn.disabled = false; btn.textContent = btn.dataset.label || btn.textContent.replace('Saving…','Saved ✓');
  setTimeout(() => { btn.textContent = btn.dataset.origLabel || 'Save'; }, 2000);
}

async function saveAboutPage() {
  const form = document.getElementById('about-page-form');
  const data = {};
  form.querySelectorAll('[name]').forEach(el => { data[el.name] = el.value; });
  const btn = event.target;
  btn.disabled = true; btn.textContent = 'Saving…';
  await saveSection('about_page', data);
  btn.disabled = false; btn.textContent = 'Saved ✓';
  setTimeout(() => { btn.textContent = 'Save About Page'; }, 2000);
}

async function saveServicesPage() {
  const form = document.getElementById('services-page-form');
  const data = {};
  form.querySelectorAll('[name]').forEach(el => { data[el.name] = el.value; });
  // Split textarea lines into arrays
  if (data.conditions_en) data.conditions_en = data.conditions_en.split('\n').map(s => s.trim()).filter(Boolean);
  if (data.conditions_bn) data.conditions_bn = data.conditions_bn.split('\n').map(s => s.trim()).filter(Boolean);
  const btn = event.target;
  btn.disabled = true; btn.textContent = 'Saving…';
  await saveSection('services_page', data);
  btn.disabled = false; btn.textContent = 'Saved ✓';
  setTimeout(() => { btn.textContent = 'Save Services Page'; }, 2000);
}

// ─── Services cards ────────────────────────────────────────
function collectService(item) {
  const data = {};
  item.querySelectorAll('[name]').forEach(el => { data[el.name] = el.value; });
  return data;
}

async function saveServices() {
  const items = document.querySelectorAll('#services-list .repeatable-item');
  const services = Array.from(items).map(collectService);
  const data = {
    services: services,
    services_title_en: document.getElementById('services_title_en').value,
    services_title_bn: document.getElementById('services_title_bn').value,
  };
  // Merge with full home.json by saving each key
  const btn = event.target;
  btn.disabled = true; btn.textContent = 'Saving…';
  await saveSection('home_services', services);
  btn.disabled = false; btn.textContent = 'Saved ✓';
  setTimeout(() => { btn.textContent = 'Save All Services'; }, 2000);
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
      <div class="form-group"><label class="form-label">Title <span class="badge">EN</span></label><input type="text" name="title_en" class="form-control"></div>
      <div class="form-group"><label class="form-label">Title <span class="badge bn">BN</span></label><input type="text" name="title_bn" class="form-control"></div>
    </div>
    <div class="form-grid">
      <div class="form-group"><label class="form-label">Description <span class="badge">EN</span></label><textarea name="desc_en" class="form-control"></textarea></div>
      <div class="form-group"><label class="form-label">Description <span class="badge bn">BN</span></label><textarea name="desc_bn" class="form-control"></textarea></div>
    </div>
    <div class="form-grid">
      <div class="form-group"><label class="form-label">Link URL</label><input type="text" name="link" class="form-control"></div>
      <div class="form-group"><label class="form-label">Icon Path</label><input type="hidden" name="icon" value=""><span style="font-size:.8rem;color:#aaa">Upload via image button after saving</span></div>
    </div>`;
  list.appendChild(div);
  div.querySelector('[data-remove-item]').addEventListener('click', () => {
    if (list.children.length > 1 && confirm('Remove this service?')) div.remove();
    else showToast('Cannot remove the last service.','warning');
  });
}

// Wire existing remove buttons
document.querySelectorAll('#services-list [data-remove-item]').forEach(btn => {
  btn.addEventListener('click', () => {
    const list = document.getElementById('services-list');
    if (list.children.length > 1 && confirm('Remove this service?')) btn.closest('.repeatable-item').remove();
    else showToast('Cannot remove the last service.','warning');
  });
});

// ─── Image uploads ─────────────────────────────────────────
setupImageUpload('hero-banner-input', 'hero-banner-input', 'hero-banner-preview', null);
setupImageUpload('hero-bg-input',     'hero-bg-input',     'hero-bg-preview',     null);
setupImageUpload('about-banner-input','about-banner-input','about-banner-preview', null);
setupImageUpload('cta-banner-input',  'cta-banner-input',  'cta-banner-preview',  null);
setupImageUpload('ap-banner-input',   'ap-banner-input',   'ap-banner-preview',   null);
setupImageUpload('sp-banner-input',   'sp-banner-input',   'sp-banner-preview',   null);

// Service card icon uploads
document.querySelectorAll('.svc-icon-btn').forEach(btn => {
  const item   = btn.closest('.repeatable-item');
  const input  = item.querySelector('.svc-icon-input');
  const preview= item.querySelector('.svc-icon-preview');
  const hidden = item.querySelector('input[name="icon"]');
  btn.addEventListener('click', () => input.click());
  setupImageUpload(input.id || null, null, null, null, input, preview, hidden);
});
</script>

<?php include __DIR__ . '/_layout-bottom.php'; ?>
