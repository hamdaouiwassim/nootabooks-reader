document.addEventListener('DOMContentLoaded', () => {

  const form = document.getElementById('loginForm');
  const email = document.getElementById('loginEmail');
  const password = document.getElementById('loginPassword');
  const emailError = document.getElementById('loginEmailError');
  const passwordError = document.getElementById('loginPasswordError');

  function isValidEmail(value) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
  }

  form.addEventListener('submit', (e) => {
    let valid = true;

    if (!email.value.trim()) {
      emailError.textContent = 'يرجى إدخال البريد الإلكتروني';
      valid = false;
    } else if (!isValidEmail(email.value.trim())) {
      emailError.textContent = 'صيغة البريد الإلكتروني غير صحيحة';
      valid = false;
    } else {
      emailError.textContent = '';
    }

    if (!password.value) {
      passwordError.textContent = 'يرجى إدخال كلمة المرور';
      valid = false;
    } else {
      passwordError.textContent = '';
    }

    if (!valid) {
      e.preventDefault();
      return;
    }

    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = 'جارِ تسجيل الدخول ...';
  });

});
