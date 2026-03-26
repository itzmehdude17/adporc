<?php
require_once __DIR__ . '/auth.php';
admin_require_login();

$pageTitle = 'FAQ Manager';
$faqs = read_json('faqs.json') ?: [];

include __DIR__ . '/_layout-top.php';
?>

<div class="card">
  <div class="card-header">
    <h2>❓ FAQ Items</h2>
    <button class="btn btn-primary btn-sm" onclick="saveFaqs()">Save All</button>
  </div>
  <p style="font-size:.85rem;color:#666;margin-bottom:16px;">Edit questions and answers below. The order they appear here is the order shown on the website.</p>
  <div id="faq-list">
    <?php foreach ($faqs as $i => $faq): ?>
    <div class="repeatable-item">
      <div class="item-header">
        <span class="item-number">#<?= $i + 1 ?></span>
        <button type="button" class="btn btn-danger btn-sm" data-remove-item>Remove</button>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Question <span class="badge">EN</span></label>
          <input type="text" name="question_en" class="form-control" value="<?= h($faq['question_en'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Question <span class="badge bn">BN</span></label>
          <input type="text" name="question_bn" class="form-control" value="<?= h($faq['question_bn'] ?? '') ?>">
        </div>
      </div>
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Answer <span class="badge">EN</span></label>
          <textarea name="answer_en" class="form-control" rows="3"><?= h($faq['answer_en'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Answer <span class="badge bn">BN</span></label>
          <textarea name="answer_bn" class="form-control" rows="3"><?= h($faq['answer_bn'] ?? '') ?></textarea>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <button type="button" class="add-item-btn" id="add-faq-btn">+ Add FAQ</button>
</div>

<script>
function collectFaq(item) {
  const d = {};
  item.querySelectorAll('[name]').forEach(el => { d[el.name] = el.value; });
  return d;
}

async function saveFaqs() {
  const items = document.querySelectorAll('#faq-list .repeatable-item');
  const data = Array.from(items).map(collectFaq);
  const btn = event.target;
  btn.disabled = true; btn.textContent = 'Saving...';
  await saveSection('faqs', data);
  btn.disabled = false; btn.textContent = 'Save All';
}

document.getElementById('add-faq-btn').addEventListener('click', () => {
  const list = document.getElementById('faq-list');
  const n = list.children.length + 1;
  const div = document.createElement('div');
  div.className = 'repeatable-item';
  div.innerHTML = `
    <div class="item-header">
      <span class="item-number">#${n}</span>
      <button type="button" class="btn btn-danger btn-sm" data-remove-item>Remove</button>
    </div>
    <div class="form-grid">
      <div class="form-group"><label class="form-label">Question <span class="badge">EN</span></label><input type="text" name="question_en" class="form-control" value=""></div>
      <div class="form-group"><label class="form-label">Question <span class="badge bn">BN</span></label><input type="text" name="question_bn" class="form-control" value=""></div>
    </div>
    <div class="form-grid">
      <div class="form-group"><label class="form-label">Answer <span class="badge">EN</span></label><textarea name="answer_en" class="form-control" rows="3"></textarea></div>
      <div class="form-group"><label class="form-label">Answer <span class="badge bn">BN</span></label><textarea name="answer_bn" class="form-control" rows="3"></textarea></div>
    </div>`;
  list.appendChild(div);
  div.querySelector('[data-remove-item]').addEventListener('click', () => {
    if (confirm('Remove this FAQ?')) div.remove();
  });
});

document.querySelectorAll('#faq-list [data-remove-item]').forEach(btn => {
  btn.addEventListener('click', () => {
    if (confirm('Remove this FAQ?')) btn.closest('.repeatable-item').remove();
  });
});
</script>

<?php include __DIR__ . '/_layout-bottom.php'; ?>
