    </div><!-- .admin-content -->
  </div><!-- .admin-main -->
</div><!-- .admin-layout -->

<div id="adminToast" class="toast"></div>
<script src="/admin/assets/admin.js"></script>
<script>
(function() {
  var toggle  = document.getElementById('sidebarToggle');
  var sidebar = document.getElementById('adminSidebar');
  var overlay = document.getElementById('sidebarOverlay');
  if (!toggle) return;
  toggle.addEventListener('click', function() {
    sidebar.classList.toggle('open');
    overlay.classList.toggle('active');
  });
  overlay.addEventListener('click', function() {
    sidebar.classList.remove('open');
    overlay.classList.remove('active');
  });
  // Close on nav link click (mobile UX)
  sidebar.querySelectorAll('a').forEach(function(a) {
    a.addEventListener('click', function() {
      sidebar.classList.remove('open');
      overlay.classList.remove('active');
    });
  });
})();
</script>
</body>
</html>
