document.addEventListener('DOMContentLoaded', () => {

  // ---- Delegated: reply form toggles ----
  document.getElementById('commentList')?.addEventListener('click', (e) => {
    const replyBtn = e.target.closest('.reply-btn');
    if (!replyBtn) return;

    const target = document.getElementById(replyBtn.dataset.replyTarget);
    if (!target) return;

    target.hidden = !target.hidden;
    if (!target.hidden) target.querySelector('input[name="body"]')?.focus();
  });

});
