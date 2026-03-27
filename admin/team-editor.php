<?php
require_once __DIR__ . '/auth.php';
admin_require_login();

$pageTitle = 'Team Manager';
$team = read_json('team.json') ?: [];

include __DIR__ . '/_layout-top.php';
?>

<div class="card">
  <div class="card-header">
    <h2>👥 Team Members</h2>
    <button class="btn btn-primary btn-sm" onclick="saveTeam()">Save All</button>
  </div>
  <p style="font-size:.85rem;color:#666;margin-bottom:16px;">Edit team member details below. Click "Save All" when done.</p>
  <div id="team-list">
    <?php foreach ($team as $i => $member): ?>
    <div class="repeatable-item">
      <div class="item-header">
        <span class="item-number">#<?= $i + 1 ?> — <?= h($member['name_en'] ?? 'Member') ?></span>
        <button type="button" class="btn btn-danger btn-sm" data-remove-item>Remove</button>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Name <span class="badge">EN</span></label>
          <input type="text" name="name_en" class="form-control" value="<?= h($member['name_en'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Name <span class="badge bn">BN</span></label>
          <input type="text" name="name_bn" class="form-control" value="<?= h($member['name_bn'] ?? '') ?>">
        </div>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Role / Title <span class="badge">EN</span></label>
          <input type="text" name="role_en" class="form-control" value="<?= h($member['role_en'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Role / Title <span class="badge bn">BN</span></label>
          <input type="text" name="role_bn" class="form-control" value="<?= h($member['role_bn'] ?? '') ?>">
        </div>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Profile URL</label>
          <input type="text" name="profile_url" class="form-control" value="<?= h($member['profile_url'] ?? '') ?>" placeholder="/team/dr-name-pt">
        </div>
        <div class="form-group">
          <label class="form-label">Photo Alt Text</label>
          <input type="text" name="photo_alt" class="form-control" value="<?= h($member['photo_alt'] ?? '') ?>">
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Photo</label>
        <div class="image-upload-group">
          <img src="<?= h($member['photo'] ?? '') ?>" class="image-preview member-photo-preview">
          <div class="image-upload-controls">
            <input type="hidden" name="photo" value="<?= h($member['photo'] ?? '') ?>">
            <input type="file" class="member-photo-input" accept="image/*" style="display:none">
            <button type="button" class="btn-upload member-photo-btn">Upload Photo</button>
            <p style="font-size:.75rem;color:#999;margin-top:4px;">Recommended: 460×500px</p>
          </div>
        </div>
      </div>
      <div style="margin-top:8px;">
        <label class="form-label">Social Links <small style="color:#999">(leave blank to hide)</small></label>
        <div class="form-grid">
          <div class="form-group">
            <input type="url" name="social_facebook" class="form-control" placeholder="Facebook URL" value="<?= h($member['social']['facebook'] ?? '') ?>">
          </div>
          <div class="form-group">
            <input type="url" name="social_twitter" class="form-control" placeholder="Twitter/X URL" value="<?= h($member['social']['twitter'] ?? '') ?>">
          </div>
          <div class="form-group">
            <input type="url" name="social_linkedin" class="form-control" placeholder="LinkedIn URL" value="<?= h($member['social']['linkedin'] ?? '') ?>">
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <button type="button" class="add-item-btn" id="add-member-btn">+ Add Team Member</button>
</div>

<script>
function collectMember(item) {
  const d = {};
  item.querySelectorAll('[name]').forEach(el => {
    if (el.name.startsWith('social_')) {
      if (!d.social) d.social = {};
      d.social[el.name.replace('social_','')] = el.value;
    } else {
      d[el.name] = el.value;
    }
  });
  return d;
}

async function saveTeam() {
  const items = document.querySelectorAll('#team-list .repeatable-item');
  const data = Array.from(items).map(collectMember);
  const btn = event.target;
  btn.disabled = true; btn.textContent = 'Saving...';
  await saveSection('team', data);
  btn.disabled = false; btn.textContent = 'Save All';
}

function newMemberTemplate(n) {
  return `<div class="repeatable-item">
    <div class="item-header">
      <span class="item-number">#${n} — New Member</span>
      <button type="button" class="btn btn-danger btn-sm" data-remove-item>Remove</button>
    </div>
    <div class="form-grid">
      <div class="form-group"><label class="form-label">Name <span class="badge">EN</span></label><input type="text" name="name_en" class="form-control" value=""></div>
      <div class="form-group"><label class="form-label">Name <span class="badge bn">BN</span></label><input type="text" name="name_bn" class="form-control" value=""></div>
    </div>
    <div class="form-grid">
      <div class="form-group"><label class="form-label">Role <span class="badge">EN</span></label><input type="text" name="role_en" class="form-control" value=""></div>
      <div class="form-group"><label class="form-label">Role <span class="badge bn">BN</span></label><input type="text" name="role_bn" class="form-control" value=""></div>
    </div>
    <div class="form-grid">
      <div class="form-group"><label class="form-label">Profile URL</label><input type="text" name="profile_url" class="form-control" value="" placeholder="/team/name-pt"></div>
      <div class="form-group"><label class="form-label">Photo Alt</label><input type="text" name="photo_alt" class="form-control" value=""></div>
    </div>
    <div class="form-group">
      <label class="form-label">Photo</label>
      <div class="image-upload-group">
        <img src="" class="image-preview member-photo-preview" style="background:#eee">
        <div class="image-upload-controls">
          <input type="hidden" name="photo" value="">
          <input type="file" class="member-photo-input" accept="image/*" style="display:none">
          <button type="button" class="btn-upload member-photo-btn">Upload Photo</button>
        </div>
      </div>
    </div>
    <div class="form-grid">
      <div class="form-group"><input type="url" name="social_facebook" class="form-control" placeholder="Facebook URL" value=""></div>
      <div class="form-group"><input type="url" name="social_twitter" class="form-control" placeholder="Twitter/X URL" value=""></div>
      <div class="form-group"><input type="url" name="social_linkedin" class="form-control" placeholder="LinkedIn URL" value=""></div>
    </div>
  </div>`;
}

document.getElementById('add-member-btn').addEventListener('click', () => {
  const list = document.getElementById('team-list');
  const n = list.children.length + 1;
  const tmp = document.createElement('div');
  tmp.innerHTML = newMemberTemplate(n);
  const item = tmp.firstElementChild;
  list.appendChild(item);
  wireItem(item);
});

function wireItem(item) {
  const removeBtn = item.querySelector('[data-remove-item]');
  if (removeBtn) {
    removeBtn.addEventListener('click', () => {
      if (confirm('Remove this team member?')) item.remove();
    });
  }
  const photoBtn = item.querySelector('.member-photo-btn');
  const photoInput = item.querySelector('.member-photo-input');
  if (photoBtn && photoInput) {
    photoBtn.addEventListener('click', () => photoInput.click());
    photoInput.addEventListener('change', async function() {
      if (!this.files[0]) return;
      const preview = item.querySelector('.member-photo-preview');
      const pathInput = item.querySelector('[name="photo"]');
      const url = await uploadImage(this.files[0], preview);
      if (url && pathInput) pathInput.value = url;
    });
  }
}

// Wire all existing items
document.querySelectorAll('#team-list .repeatable-item').forEach(wireItem);
</script>

<?php include __DIR__ . '/_layout-bottom.php'; ?>
