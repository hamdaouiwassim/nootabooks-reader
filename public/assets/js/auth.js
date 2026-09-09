document.addEventListener('DOMContentLoaded', () => {

  // ---- Toggle password visibility ----
  document.querySelectorAll('.toggle-password').forEach(btn => {
    btn.addEventListener('click', () => {
      const input = btn.previousElementSibling;
      const icon = btn.querySelector('i');
      const showing = input.type === 'text';
      input.type = showing ? 'password' : 'text';
      icon.classList.toggle('fa-eye');
      icon.classList.toggle('fa-eye-slash');
    });
  });

  // ---- Social auth buttons (demo only) ----
  document.querySelectorAll('.social-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const original = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> جارِ التحويل ...';
      setTimeout(() => { btn.innerHTML = original; btn.disabled = false; }, 1500);
    });
  });

});
