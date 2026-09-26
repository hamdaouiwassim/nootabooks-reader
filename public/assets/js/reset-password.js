document.addEventListener('DOMContentLoaded', () => {

  const form = document.getElementById('resetPasswordForm');
  const code = document.getElementById('code');
  const password = document.getElementById('resetPassword');
  const confirm = document.getElementById('resetPasswordConfirm');

  const codeError = document.getElementById('resetCodeError');
  const passwordError = document.getElementById('resetPasswordError');
  const confirmError = document.getElementById('resetPasswordConfirmError');

  // ---- Password strength meter ----
  const strengthFill = document.querySelector('.strength-fill');
  const strengthLabel = document.querySelector('.strength-label');

  password.addEventListener('input', () => {
    const value = password.value;
    let score = 0;
    if (value.length >= 8) score++;
    if (/[A-Z]/.test(value)) score++;
    if (/[0-9]/.test(value)) score++;
    if (/[^A-Za-z0-9]/.test(value)) score++;

    const levels = [
      { width: '0%', color: '#e6e2d8', label: '' },
      { width: '25%', color: '#c0392b', label: 'ضعيفة' },
      { width: '50%', color: '#e8b93f', label: 'متوسطة' },
      { width: '75%', color: '#5a9c4a', label: 'جيدة' },
      { width: '100%', color: '#2f7a4a', label: 'قوية' },
    ];
    const chosen = value ? levels[Math.max(score, 1)] : levels[0];
    strengthFill.style.width = chosen.width;
    strengthFill.style.background = chosen.color;
    strengthLabel.textContent = chosen.label;
    strengthLabel.style.color = chosen.color;
  });

  form.addEventListener('submit', (e) => {
    let valid = true;

    if (!code.value.trim()) {
      codeError.textContent = 'يرجى إدخال رمز التحقق';
      valid = false;
    } else {
      codeError.textContent = '';
    }

    if (!password.value) {
      passwordError.textContent = 'يرجى إدخال كلمة المرور';
      valid = false;
    } else if (password.value.length < 8) {
      passwordError.textContent = 'يجب أن تتكون كلمة المرور من 8 أحرف على الأقل';
      valid = false;
    } else {
      passwordError.textContent = '';
    }

    if (!confirm.value) {
      confirmError.textContent = 'يرجى تأكيد كلمة المرور';
      valid = false;
    } else if (confirm.value !== password.value) {
      confirmError.textContent = 'كلمتا المرور غير متطابقتين';
      valid = false;
    } else {
      confirmError.textContent = '';
    }

    if (!valid) {
      e.preventDefault();
      return;
    }

    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = 'جارِ التحديث ...';
  });

});
