document.addEventListener('DOMContentLoaded', () => {

  const form = document.getElementById('contactForm');
  if (!form) return;

  const name = document.getElementById('contactName');
  const email = document.getElementById('contactEmail');
  const subject = document.getElementById('contactSubject');
  const message = document.getElementById('contactMessage');

  const nameError = document.getElementById('contactNameError');
  const emailError = document.getElementById('contactEmailError');
  const subjectError = document.getElementById('contactSubjectError');
  const messageError = document.getElementById('contactMessageError');

  function isValidEmail(value) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
  }

  form.addEventListener('submit', (e) => {
    let valid = true;

    if (!name.value.trim()) {
      nameError.textContent = 'يرجى إدخال الاسم الكامل';
      valid = false;
    } else {
      nameError.textContent = '';
    }

    if (!email.value.trim()) {
      emailError.textContent = 'يرجى إدخال البريد الإلكتروني';
      valid = false;
    } else if (!isValidEmail(email.value.trim())) {
      emailError.textContent = 'صيغة البريد الإلكتروني غير صحيحة';
      valid = false;
    } else {
      emailError.textContent = '';
    }

    if (!subject.value) {
      subjectError.textContent = 'يرجى اختيار موضوع الرسالة';
      valid = false;
    } else {
      subjectError.textContent = '';
    }

    if (!message.value.trim()) {
      messageError.textContent = 'يرجى كتابة رسالتك';
      valid = false;
    } else {
      messageError.textContent = '';
    }

    if (!valid) {
      e.preventDefault();
      return;
    }

    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = 'جارِ الإرسال ...';
  });

});
