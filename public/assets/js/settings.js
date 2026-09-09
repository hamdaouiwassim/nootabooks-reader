document.addEventListener('DOMContentLoaded', () => {

  // ---- Sidebar navigation ----
  const navBtns = document.querySelectorAll('.settings-nav-btn');
  const panels = document.querySelectorAll('.settings-panel');

  navBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      navBtns.forEach(b => b.classList.remove('active'));
      panels.forEach(p => p.classList.remove('active'));
      btn.classList.add('active');
      document.getElementById(`panel-${btn.dataset.panel}`)?.classList.add('active');
    });
  });

  // ---- Personal info form ----
  const personalForm = document.getElementById('personalForm');
  personalForm?.addEventListener('submit', (e) => {
    e.preventDefault();
    const success = document.getElementById('personalSuccess');
    success.hidden = false;
    setTimeout(() => { success.hidden = true; }, 3000);
  });

  // ---- Security form ----
  const securityForm = document.getElementById('securityForm');
  securityForm?.addEventListener('submit', (e) => {
    e.preventDefault();
    const newPassword = document.getElementById('newPassword');
    const confirmPassword = document.getElementById('confirmPassword');
    const confirmError = document.getElementById('confirmPasswordError');

    if (newPassword.value && newPassword.value !== confirmPassword.value) {
      confirmError.textContent = 'كلمتا المرور غير متطابقتين';
      return;
    }
    confirmError.textContent = '';

    const success = document.getElementById('securitySuccess');
    success.hidden = false;
    securityForm.reset();
    setTimeout(() => { success.hidden = true; }, 3000);
  });

  // ---- Delete account (demo confirmation) ----
  document.getElementById('deleteAccountBtn')?.addEventListener('click', function () {
    const confirmed = window.confirm('هل أنت متأكد من رغبتك في حذف حسابك نهائيًا؟ لا يمكن التراجع عن هذا الإجراء.');
    if (confirmed) {
      window.location.href = 'login.html';
    }
  });

});
