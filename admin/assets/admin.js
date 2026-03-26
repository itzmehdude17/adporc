/* ─── Admin Panel JS ──────────────────────────────────────── */

/** Get CSRF token from meta tag */
function getCsrfToken() {
  const meta = document.querySelector('meta[name="csrf-token"]');
  return meta ? meta.content : '';
}

/** Show toast notification */
function showToast(message, type = 'success') {
  let toast = document.getElementById('adminToast');
  if (!toast) {
    toast = document.createElement('div');
    toast.id = 'adminToast';
    toast.className = 'toast';
    document.body.appendChild(toast);
  }
  toast.textContent = message;
  toast.className = 'toast ' + type;
  clearTimeout(toast._timeout);
  requestAnimationFrame(() => {
    requestAnimationFrame(() => toast.classList.add('show'));
  });
  toast._timeout = setTimeout(() => toast.classList.remove('show'), 3800);
}

/** Save section data via AJAX POST to /admin/save.php */
async function saveSection(section, data) {
  try {
    const res = await fetch('/admin/save.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': getCsrfToken()
      },
      body: JSON.stringify({ section, data })
    });
    if (!res.ok) throw new Error('HTTP ' + res.status);
    const json = await res.json();
    if (json.ok) {
      showToast(json.message || 'Saved successfully!', 'success');
      return true;
    } else {
      showToast(json.error || 'Save failed.', 'error');
      return false;
    }
  } catch (e) {
    showToast('Network error. Please try again.', 'error');
    return false;
  }
}

/** Upload an image file, optionally updating a preview element */
async function uploadImage(file, previewEl) {
  if (!file) return null;
  const formData = new FormData();
  formData.append('image', file);
  formData.append('csrf_token', getCsrfToken());

  // Show local preview immediately
  if (previewEl) {
    const reader = new FileReader();
    reader.onload = e => { previewEl.src = e.target.result; };
    reader.readAsDataURL(file);
  }

  try {
    const res = await fetch('/admin/upload.php', {
      method: 'POST',
      headers: { 'X-CSRF-Token': getCsrfToken() },
      body: formData
    });
    if (!res.ok) throw new Error('HTTP ' + res.status);
    const json = await res.json();
    if (json.ok) {
      if (previewEl) previewEl.src = json.url;
      showToast('Image uploaded!', 'success');
      return json.url;
    } else {
      showToast(json.error || 'Upload failed.', 'error');
      return null;
    }
  } catch (e) {
    showToast('Upload error. Please try again.', 'error');
    return null;
  }
}

/**
 * Wire a simple image upload button.
 * @param {string} btnId      - id of the trigger button
 * @param {string} inputId    - id of the file <input>
 * @param {string} previewId  - id of the preview <img>
 * @param {string} pathInputId - id of the hidden path input
 */
function setupImageUpload(btnId, inputId, previewId, pathInputId) {
  const btn = document.getElementById(btnId);
  const inp = document.getElementById(inputId);
  const preview = previewId ? document.getElementById(previewId) : null;
  const pathInput = pathInputId ? document.getElementById(pathInputId) : null;
  if (!btn || !inp) return;

  btn.addEventListener('click', () => inp.click());
  inp.addEventListener('change', async function () {
    const file = this.files[0];
    if (!file) return;
    const url = await uploadImage(file, preview);
    if (url && pathInput) pathInput.value = url;
  });
}

/* ── Tab switching ──────────────────────────────── */
document.addEventListener('click', e => {
  const btn = e.target.closest('.tab-btn');
  if (!btn) return;
  const target = btn.dataset.tab;
  if (!target) return;

  // Deactivate siblings
  btn.closest('.tab-buttons').querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');

  // Hide/show panes in the parent card
  const card = btn.closest('.card');
  card.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
  const pane = card.querySelector('#tab-' + target);
  if (pane) pane.classList.add('active');
});

/* ── Confirm before page leave (unsaved changes guard) ── */
let _dirty = false;
document.addEventListener('input', () => { _dirty = true; });
document.addEventListener('change', () => { _dirty = true; });
window.addEventListener('beforeunload', e => {
  if (_dirty) {
    e.preventDefault();
    e.returnValue = '';
  }
});
// Allow save buttons to clear dirty flag
document.addEventListener('click', e => {
  if (e.target.closest('.btn-primary')) {
    setTimeout(() => { _dirty = false; }, 500);
  }
});
